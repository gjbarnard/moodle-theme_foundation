<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Foundation theme.
 *
 * @package    theme_foundation
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation;

defined('MOODLE_INTERNAL') || die();

/**
 * The theme's toolbox.
 *
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class toolbox {

    /**
     * @var core_renderer
     */
    protected $corerenderer = null;
    /**
     * @var string Theme name.
     */
    protected $themename = '';
    /**
     * @var the_config The hierarchy of the_config instances with the current theme last.
     */
    protected $theconfigs = array(); // Indexed on theme name in hierarchy order.
    /**
     * @var module_basement Sub class instances of the abstract module_basement class representing all of the modules in the theme.
     */
    protected $modules = array();
    /**
     * @var toolbox Singleton instance of us.
     */
    protected static $instance = null;

    /**
     * Setting page index.
     */
    const SETTINGPAGE = 'p';

    /**
     * Has settings.
     */
    const HASSETTINGS = 's';

    /**
     * This is a lonely object.
     */
    private function __construct() {
        $this->init_modules();
    }

    /**
     * Gets the toolbox singleton.
     *
     * @return toolbox The toolbox instance.
     */
    public static function get_instance() {
        if (!is_object(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Finds and instantiates the available theme modules.
     */
    private function init_modules() {
        global $CFG;

        // TODO: Cope with $CFG->themedir.
        if ($handle = opendir($CFG->dirroot.'/theme/foundation/classes/module/')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                $fullpath = $CFG->dirroot.'/theme/foundation/classes/module/'.$entry;
                if (is_dir($fullpath)) {
                    continue;
                }

                // Remove '.php'.
                $classname = rtrim($entry, '.php');
                $classname = '\theme_foundation\module\\'.$classname;
                if (class_exists($classname)) {
                    $this->modules[] = new $classname();
                } else {
                    \debugging('theme_foundation::toolbox:init_modules() Class \''.$classname.'\' not found.');
                }
            }
            closedir($handle);
        }
    }

    /**
     * Returns the core renderer for the theme.
     *
     * @return core_renderer The core renderer object.
     */
    public function get_core_renderer() {
        global $PAGE;
        $themename = $PAGE->theme->name;
        if (empty($this->corerenderer)) {
            $this->corerenderer = $PAGE->get_renderer('theme_'.$themename, 'core');
            $this->themename = $themename;
        } else {
            if ($themename != $this->themename) {
                // More of a humm! if this happens.
                \debugging('theme_foundation toolbox::get_core_renderer() - Different theme \''.$themename.'\' from original \''.
                    $this->themename.'\'.');
            }
        }
        return $this->corerenderer;
    }

    /**
     * Gets the pre SCSS.
     *
     * @param string $themename The name of the theme.
     * @return string SCSS.
     */
    public function pre_scss($themename) {
        $scss = '';

        foreach ($this->modules as $module) {
            $scss .= $module->pre_scss($themename, $this);
        }

        // TODO: Does there need to be a parent daisy chain of this setting?
        $prescss = $this->get_setting('prescss', $themename);
        if (!empty($prescss)) {
            $scss .= $prescss;
        }

        return $scss;
    }

    /**
     * Gets the main SCSS for the theme.
     *
     * @param theme_config $theme The theme configuration object.
     * @return string SCSS.
     */
    public function get_main_scss_content(\theme_config $theme) {
        global $CFG;

        if (!$this->theme_exists($theme->name)) {
            $this->add_theme($theme->name);
        }

        $scss = '';
        foreach ($this->modules as $module) {
            $scss .= $module->get_main_scss_content($theme, $this);
        }

        $scss .= file_get_contents($CFG->dirroot . '/theme/foundation/scss/theme/theme.scss');

        return $scss;
    }

    /**
     * Returns the core framework SCSS.
     *
     * @return string SCSS.
     */
    public function get_core_framework_scss() {
        // TODO: If theme is in $CFG->themedir then work out the relative path from the theme to the 'boost' folder.
        $path = '../../boost/scss/';

        $scss = '// Import FontAwesome.'.PHP_EOL;
        $scss .= '@import "'.$path.'fontawesome";'.PHP_EOL;
        $scss .= '// Import All of Bootstrap'.PHP_EOL;
        $scss .= '@import "'.$path.'bootstrap";'.PHP_EOL;
        $scss .= '// Import Core moodle CSS'.PHP_EOL;
        $scss .= '@import "'.$path.'moodle";'.PHP_EOL;

        return $scss;
    }

    /**
     * Gets the module bodyclasses.
     *
     * @return array bodyclass strings.
     */
    public function body_classes() {
        $bodyclasses = array();
        foreach ($this->modules as $module) {
            $bodyclasses = array_merge($bodyclasses, $module->body_classes());
        }
        return $bodyclasses;
    }

    /**
     * Returns the module instance for the given modulename.
     *
     * @param string $themename The name of the theme.
     * @return module_basement extended object or null if not found.
     */
    public function get_module($modulename) {
        $themodule = null;
        foreach ($this->modules as $module) {
            // Get the actual classname from the end of the prefixing namespace.
            $classname = explode('\\', get_class($module));
            $classname = end($classname);
            if ($classname == $modulename.'_module') {
                $themodule = $module;
                break;
            }
        }
        return $themodule;
    }

    /**
     * Return an instance of the mustache class.
     *
     * @since 2.9
     * @return Mustache_Engine
     */
    public function get_mustache() {
        global $PAGE;
        $renderer = $PAGE->get_renderer('theme_foundation', 'mustache');

        return $renderer->getmustache();
    }

    /**
     * Gets the extra SCSS.
     *
     * @param string $themename The name of the theme.
     * @return string SCSS.
     */
    public function extra_scss($themename) {
        $scss = '';

        foreach ($this->modules as $module) {
            $scss .= $module->extra_scss($themename, $this);
        }

        // TODO: Does there need to be a parent daisy chain of this setting?
        $customscss = $this->get_setting('customscss', $themename);
        if (!empty($customscss)) {
            $scss .= $customscss;
        }

        return $scss;
    }

    /**
     * Add the settings to the theme.
     *
     * @param admin_root $admin The admin root.
     */
    public function add_settings(\admin_root $admin) {
        $admin->add('themes', new \admin_category('theme_foundation', 'Foundation'));

        // The settings pages we create.
        $settingspages = array(
            'general' => array(
                self::SETTINGPAGE => new \admin_settingpage('theme_foundation_generic',
                    get_string('generalheading', 'theme_foundation')),
                self::HASSETTINGS => true),
            'hvp' => array(
                self::SETTINGPAGE => new \admin_settingpage('theme_foundation_hvp',
                    get_string('hvpheading', 'theme_foundation')),
                self::HASSETTINGS => true),
            'module' => array(
                self::SETTINGPAGE => new \admin_settingpage('theme_foundation_module',
                    get_string('moduleheading', 'theme_foundation')),
                self::HASSETTINGS => false)
        );

        // General settings.
        if ($admin->fulltree) {
            $settingspages['general'][self::SETTINGPAGE]->add(
                new \admin_setting_heading(
                    'theme_foundation_generalheading',
                    get_string('generalheadingsub', 'theme_foundation'),
                    format_text(get_string('generalheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN).PHP_EOL.
                    format_text(get_string('privacynote', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );

            // Custom favicon.
            $name = 'theme_foundation/favicon';
            $title = get_string('favicon', 'theme_foundation');
            $description = get_string('favicondesc', 'theme_foundation');
            $setting = new \admin_setting_configstoredfile($name, $title, $description, 'favicon');
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settingspages['general'][self::SETTINGPAGE]->add($setting);

            // Pre SCSS.
            $name = 'theme_foundation/prescss';
            $title = get_string('prescss', 'theme_foundation');
            $description = get_string('prescssdesc', 'theme_foundation');
            $default = '';
            $setting = new \admin_setting_configtextarea($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settingspages['general'][self::SETTINGPAGE]->add($setting);

            // Custom SCSS.
            $name = 'theme_foundation/customscss';
            $title = get_string('customscss', 'theme_foundation');
            $description = get_string('customscssdesc', 'theme_foundation');
            $default = '';
            $setting = new \admin_setting_configtextarea($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settingspages['general'][self::SETTINGPAGE]->add($setting);
        }

        // H5P settings.
        if ($admin->fulltree) {
            $settingspages['hvp'][self::SETTINGPAGE]->add(
                new \admin_setting_heading(
                    'theme_foundation_hvpheading',
                    get_string('hvpheadingsub', 'theme_foundation'),
                    format_text(get_string('hvpheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN).PHP_EOL.
                    format_text(get_string('privacynote', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );

            // H5P Custom CSS.
            $name = 'theme_foundation/hvpcustomcss';
            $title = get_string('hvpcustomcss', 'theme_foundation');
            $description = get_string('hvpcustomcssdesc', 'theme_foundation');
            $default = '';
            $setting = new \admin_setting_configtextarea($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settingspages['hvp'][self::SETTINGPAGE]->add($setting);

            // H5P Font CSS.
            $name = 'theme_foundation/hvpfontcss';
            $title = get_string('hvpfontcss', 'theme_foundation');
            $description = get_string('hvpfontcssdesc', 'theme_foundation');
            $default = $this->gethvpdefaultfonts();
            $setting = new \admin_setting_configtextarea($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settingspages['hvp'][self::SETTINGPAGE]->add($setting);
        }

        // Module settings.
        if ($admin->fulltree) {
            $settingspages['module'][self::SETTINGPAGE]->add(
                new \admin_setting_heading(
                    'theme_foundation_moduleheading',
                    get_string('moduleheadingsub', 'theme_foundation'),
                    format_text(get_string('moduleheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );
        }

        /* Call each module where they can either add their settings to an existing settings page or create their own
           and have it added. */
        foreach ($this->modules as $module) {
            $module->add_settings($settingspages, $admin->fulltree, $this);
        }

        // Add the settings pages if they have more than just the settings page heading.
        foreach (array_values($settingspages) as $settingspage) {
            if ($settingspage[self::HASSETTINGS] == true) {
                $thepage = $settingspage[self::SETTINGPAGE];
                $admin->add('theme_foundation', $thepage);
            }
        }
    }

    /**
     * Gets the default H5P fonts as supplied by the theme.
     *
     * @return string The font CSS.
     */
    private function gethvpdefaultfonts() {
        return "
            @font-face {
                font-family: 'Cabin Sketch';
                font-style: normal;
                font-weight: normal;
                src: url('[[font:theme|CabinSketch-Regular.otf]]');
            }

            @font-face {
                font-family: 'Cabin Sketch';
                font-style: normal;
                font-weight: bold;
                src: url('[[font:theme|CabinSketch-Bold.otf]]');
            }

            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 300;
                src: url('[[font:theme|Lato-Light.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|Lato-Regular.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Lato';
                font-weight: 400;
                font-style: italic;
                font-stretch: normal;
                src: url('[[font:theme|Lato-Italic.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 700;
                src: url('[[font:theme|Lato-Bold.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 100;
                src: url('[[font:theme|Montserrat-Thin.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: italic;
                font-weight: 100;
                src: url('[[font:theme|Montserrat-ThinItalic.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 200;
                src: url('[[font:theme|Montserrat-ExtraLight.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: italic;
                font-weight: 200;
                src: url('[[font:theme|Montserrat-ExtraLightItalic.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 300;
                src: url('[[font:theme|Montserrat-Light.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: italic;
                font-weight: 300;
                src: url('[[font:theme|Montserrat-LightItalic.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|Montserrat-Regular.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: italic;
                font-weight: 400;
                src: url('[[font:theme|Montserrat-Italic.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 500;
                src: url('[[font:theme|Montserrat-Medium.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: italic;
                font-weight: 500;
                src: url('[[font:theme|Montserrat-MediumItalic.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 600;
                src: url('[[font:theme|Montserrat-SemiBold.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: italic;
                font-weight: 600;
                src: url('[[font:theme|Montserrat-SemiBoldItalic.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 700;
                src: url('[[font:theme|Montserrat-Bold.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: italic;
                font-weight: 700;
                src: url('[[font:theme|Montserrat-BoldItalic.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 800;
                src: url('[[font:theme|Montserrat-ExtraBold.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: italic;
                font-weight: 800;
                src: url('[[font:theme|Montserrat-ExtraBoldItalic.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 900;
                src: url('[[font:theme|Montserrat-Black.otf]]');
            }

            @font-face {
                font-family: 'Montserrat';
                font-style: italic;
                font-weight: 900;
                src: url('[[font:theme|Montserrat-BlackItalic.otf]]');
            }

            @font-face {
                font-family: 'News Cycle';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|NewsCycle-Regular.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'News Cycle';
                font-style: normal;
                font-weight: 700;
                src: url('[[font:theme|NewsCycle-Bold.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito Sans';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|NunitoSans-Regular.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito Sans';
                font-style: normal;
                font-weight: 600;
                src: url('[[font:theme|NunitoSans-SemiBold.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Open Sans';
                font-style: normal;
                font-weight: 300;
                src: url('[[font:theme|OpenSans-Light-webfont.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Open Sans';
                font-weight: 300;
                font-style: italic;
                font-stretch: normal;
                src: url('[[font:theme|OpenSans-LightItalic-webfont.ttf]]') format('woff');
            }

            @font-face {
                font-family: 'Open Sans';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|OpenSans-Regular-webfont.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Open Sans';
                font-weight: 400;
                font-style: italic;
                font-stretch: normal;
                src: url('[[font:theme|OpenSans-Italic-webfont.ttf]]') format('woff');
            }

            @font-face {
                font-family: 'Open Sans';
                font-style: normal;
                font-weight: 700;
                src: url('[[font:theme|OpenSans-Bold-webfont.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Open Sans';
                font-weight: 700;
                font-style: italic;
                font-stretch: normal;
                src: url('[[font:theme|OpenSans-BoldItalic-webfont.ttf]]') format('woff');
            }

            @font-face {
                font-family: 'Raleway';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|Raleway-Regular.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Raleway';
                font-style: normal;
                font-weight: 700;
                src: url('[[font:theme|Raleway-Bold.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 300;
                src: url('[[font:theme|Roboto-Light-webfont.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|Roboto-Regular-webfont.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 500;
                src: url('[[font:theme|Roboto-Medium-webfont.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 700;
                src: url('[[font:theme|Roboto-Black-webfont.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 200;
                font-style: normal;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-ExtraLight.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 200;
                font-style: italic;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-ExtraLightIt.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 300;
                font-style: normal;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-Light.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 300;
                font-style: italic;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-LightIt.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 400;
                font-style: normal;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-Regular.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 400;
                font-style: italic;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-It.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 600;
                font-style: normal;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-Semibold.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 600;
                font-style: italic;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-SemiboldIt.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 700;
                font-style: normal;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-Bold.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 700;
                font-style: italic;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-BoldIt.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 900;
                font-style: normal;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-Black.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-weight: 900;
                font-style: italic;
                font-stretch: normal;
                src: url('[[font:theme|SourceSansPro-BlackIt.woff]]') format('woff');
            }

            @font-face {
                font-family: 'Ubuntu';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|Ubuntu-R.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Ubuntu';
                font-style: normal;
                font-weight: 700;
                src: url('[[font:theme|Ubuntu-B.ttf]]') format('truetype');
            }
        ";
    }

    /**
     * Returns the strings from the modules.
     *
     * Note: Not currently called due to https://docs.moodle.org/dev/Plugin_contribution_checklist#Strings
     *
     * @param string $lang The language code to get.
     * @return array Array of strings.
     */
    public function get_lang_strings($lang) {
        $strings = array();

        foreach ($this->modules as $module) {
            $strings = array_merge($strings, $module->get_lang_strings($lang, $this));
        }

        return $strings;
    }

    /**
     * Creates an instance of the_config for the given theme and adds it to the known list of themes if not already.
     *
     * @param string $themename Theme name.
     */
    protected function add_theme($themename) {
        // Does the theme exist already?
        if (!$this->theme_exists($themename)) {
            // No.
            $theme = \theme_foundation\the_config::load($themename);

            /* Everything went ok.  So add it and its parents if any.
               So.... start with the top parent and move forward, recursively. */
            if (!empty($theme->parents)) {
                $parentname = end($theme->parents);
                while ($parentname !== false) {
                    $this->add_theme($parentname);
                    $parentname = prev($theme->parents);
                }
            }

            // Add to the end.
            end($this->theconfigs);
            $this->theconfigs[$themename] = $theme;
        }
    }

    /**
     * Do we already know about the theme?
     *
     * @param string $themename Theme name.
     * @return boolean true or false.
     */
    protected function theme_exists($themename) {
        return array_key_exists($themename, $this->theconfigs);
    }

    // Settings.
    /**
     * Gets the specified setting.
     *
     * @param string $settingname The name of the setting.
     * @param string $themename The name of the theme to start looking in.
     * @return boolean|mixed false if not found or setting value.
     */
    public function get_setting($settingname, $themename = null) {
        $settingvalue = false;

        $theconfig = $this->get_setting_theme_config($settingname, $themename);
        if ($theconfig != null) {
            $settingvalue = $theconfig->settings->$settingname;
        }

        return $settingvalue;
    }

    /**
     * Gets the setting moodle_url for the given setting if it exists and set.
     *
     * See: https://moodle.org/mod/forum/discuss.php?d=371252#p1516474 and change if theme_config::setting_file_url
     * changes.
     * My need to do: $url = preg_replace('|^https?://|i', '//', $url->out(false)); separately.
     */
    public function get_setting_moodle_url($setting, $themename = null) {
        $settingurl = null;

        $theconfig = $this->get_setting_theme_config($setting, $themename);
        if ($theconfig != null) {
            $thesetting = $theconfig->settings->$setting;
            if (!empty($thesetting)) {
                global $CFG;
                $itemid = \theme_get_revision();
                $syscontext = \context_system::instance();

                $settingurl = \moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/$syscontext->id/theme_$theconfig->name/$setting/$itemid".$thesetting);
            }
        }
        return $settingurl;
    }

    /**
     * Gets the setting file url for the given setting if it exists and set.
     */
    public function setting_file_url($setting, $filearea, $themename = null) {
        $url = null;
        $settingconfig = $this->get_setting_theme_config($setting, $themename);

        if ($settingconfig) {
            $thesetting = $settingconfig->settings->$setting;
            if (!empty($thesetting)) {
                // From theme_config::setting_file_url.
                global $CFG;
                $component = 'theme_'.$themename;
                $itemid = \theme_get_revision();
                $filepath = $thesetting;
                $syscontext = \context_system::instance();

                $url = \moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/$syscontext->id/$component/$filearea/$itemid".$filepath);

                /* Now this is tricky because the we can not hardcode http or https here, lets use the relative link.
                   Note: unfortunately moodle_url does not support //urls yet. */

                $url = preg_replace('|^https?://|i', '//', $url->out(false));
            }
        }
        return $url;
    }

    /**
     * Gets the youngest theme config that the setting is stored in or null if not.
     */
    private function get_setting_theme_config($settingname, $themename = null) {
        $theconfig = null;

        if ($themename == null) {
            global $PAGE;
            $themename = $PAGE->theme->name;
        }

        if (!$this->theme_exists($themename)) {
            $this->add_theme($themename);
        }

        /* Get the array internal pointer to the end then walk backwards to find the theme.  As we need to get the correct value
           for the setting with the theme specified as the starting point. */
        $current = end($this->theconfigs);
        while (($current !== false) && ($current->name != $themename)) {
            $current = prev($this->theconfigs);
        }

        // We need to work on 'properties' so that empty values can be used.
        if (property_exists($current->settings, $settingname)) {
            $theconfig = $current;
        } else {
            /* Look in the parents.
               Parents will be in the correct order of the hierarchy as defined in $THEME->parents in config.php. */
            $current = prev($this->theconfigs);
            while ($current !== false) {
                if (property_exists($current->settings, $settingname)) {
                    $theconfig = $current;
                    break;
                }
                $current = prev($this->theconfigs);
            }
        }

        return $theconfig;
    }
}
