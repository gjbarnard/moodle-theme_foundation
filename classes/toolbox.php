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
 * @copyright  &copy; 2018 G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation;

/**
 * The theme's toolbox.
 *
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class toolbox {

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
     * @var array Opacity choices for settings.
     */
    public static $settingopactitychoices = array(
        '0.0' => '0.0',
        '0.1' => '0.1',
        '0.2' => '0.2',
        '0.3' => '0.3',
        '0.4' => '0.4',
        '0.5' => '0.5',
        '0.6' => '0.6',
        '0.7' => '0.7',
        '0.8' => '0.8',
        '0.9' => '0.9',
        '1.0' => '1.0'
    );

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

        return $PAGE->get_renderer('theme_'.$PAGE->theme->name, 'core');
    }

    /**
     * Gets the pre SCSS.
     *
     * @param string $themename The name of the theme.
     *
     * @return string SCSS.
     */
    public function pre_scss($themename) {
        $scss = '';

        foreach ($this->modules as $module) {
            $scss .= $module->pre_scss($themename, $this);
        }

        $footerantigravityhorizontaloffset = $this->get_setting('footerantigravityhorizontaloffset', $themename);
        if (!empty($footerantigravityhorizontaloffset)) {
            $scss .= '$footer-antigravity-horizontal-offset: '.$footerantigravityhorizontaloffset.';';
        }

        $footerantigravityverticaloffset = $this->get_setting('footerantigravityverticaloffset', $themename);
        if (!empty($footerantigravityverticaloffset)) {
            $scss .= '$footer-antigravity-vertical-offset: '.$footerantigravityverticaloffset.';';
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
     *
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

        if (!empty($this->get_setting('trio'))) {
            $scss .= file_get_contents($CFG->dirroot.'/theme/foundation/scss/theme/trio.scss');
        }

        $scss .= file_get_contents($CFG->dirroot.'/theme/foundation/scss/theme/foundation_variables.scss');

        $navbarstyle = $this->get_setting('navbarstyle');
        if (empty($navbarstyle)) {
            $navbarstyle = 'dark';
        }
        $scss .= '$navbar-style: '.$navbarstyle.';'.PHP_EOL;

        $scss .= file_get_contents($CFG->dirroot.'/theme/foundation/scss/theme/theme.scss');

        return $scss;
    }

    /**
     * Returns the core framework SCSS.
     *
     * @return string SCSS.
     */
    public function get_core_framework_scss() {
        global $CFG;
        // TODO: If theme is in $CFG->themedir then work out the relative path from the theme to the 'boost' folder.
        $path = '../../boost/scss/';

        $scss = file_get_contents($CFG->dirroot.'/theme/foundation/scss/theme/override_variables.scss');
        if (empty($this->get_setting('fav'))) {
            $scss .= '// Import Core FontAwesome.'.PHP_EOL;
            $scss .= '@import "'.$path.'fontawesome";'.PHP_EOL;
        } else {
            $scss .= '// Import Theme FontAwesome.'.PHP_EOL;
            $scss .= '@import "fontawesome/brands";'.PHP_EOL;
            $scss .= '@import "fontawesome/regular";'.PHP_EOL;
            $scss .= '@import "fontawesome/solid";'.PHP_EOL;
            if (!empty($this->get_setting('faiv'))) {
                $scss .= '@import "fontawesome/v4-shims";'.PHP_EOL;
            }
            $scss .= '@import "fontawesome/fontawesome";'.PHP_EOL;
        }
        $scss .= '// Import All of Bootstrap'.PHP_EOL;
        $scss .= '@import "'.$path.'bootstrap";'.PHP_EOL;
        $scss .= '// Import Core moodle CSS'.PHP_EOL;
        $scss .= '@import "'.$path.'moodle";'.PHP_EOL;

        return $scss;
    }

    /**
     * Gets the extra SCSS.
     *
     * @param string $themename The name of the theme.
     *
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
     * @param string $modulename The name of the module.
     *
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
     * Returns the module instances.
     *
     * @return module_basement extended objects.
     */
    public function get_modules() {
        return $this->modules;
    }

    /**
     * Return an instance of the mustache class.
     *
     * @since 2.9
     *
     * @return Mustache_Engine
     */
    public function get_mustache() {
        global $PAGE;
        $renderer = $PAGE->get_renderer('theme_foundation', 'mustache');

        return $renderer->getmustache();
    }

    /**
     * Add the settings to the theme.
     *
     * @param admin_settingpage $settings The core settings page reference.
     */
    public function add_settings(\admin_settingpage &$settings) {
        global $ADMIN;

        $settings = null;

        $ADMIN->add('themes', new \admin_category('theme_foundation', get_string('configtitle', 'theme_foundation')));
        $fsettings = new admin_settingspage_tabs('themesettingsfoundation', get_string('configtabtitle', 'theme_foundation'));

        if ($ADMIN->fulltree) {
            // The settings pages we create.
            $settingspages = array(
                'information' => array(
                    self::SETTINGPAGE => new \admin_settingpage('theme_foundation_information',
                        get_string('informationheading', 'theme_foundation')),
                    self::HASSETTINGS => true),
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

            // Information.
            $settingspages['information'][self::SETTINGPAGE]->add(
                new \theme_foundation\admin_setting_information('theme_foundation/themeinformation', '', '', 401)
            );

            // Support.md.
            $name = 'theme_foundation/themesupport';
            $title = get_string('themesupport', 'theme_foundation');
            $setting = new admin_setting_markdown($name, $title, '', 'Support.md');
            $settingspages['information'][self::SETTINGPAGE]->add($setting);

            // Changes.md.
            $name = 'theme_foundation/themechanges';
            $title = get_string('themechanges', 'theme_foundation');
            $setting = new admin_setting_markdown($name, $title, '', 'Changes.md');
            $settingspages['information'][self::SETTINGPAGE]->add($setting);

            // Readme.md.
            $name = 'theme_foundation/themereadme';
            $title = get_string('themereadme', 'theme_foundation');
            $setting = new admin_setting_markdown($name, $title, '', 'Readme.md');
            $settingspages['information'][self::SETTINGPAGE]->add($setting);

            // General settings.
            $settingspages['general'][self::SETTINGPAGE]->add(
                new \admin_setting_heading(
                    'theme_foundation_generalheading',
                    get_string('generalheadingsub', 'theme_foundation'),
                    format_text(get_string('generalheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN).PHP_EOL.
                    format_text(get_string('privacynote', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );

            // Core favicon information.
            $name = 'theme_foundation/favicon';
            $title = get_string('favicon', 'theme_foundation');
            $description = get_string('favicondesc', 'theme_foundation');
            $setting = new \admin_setting_description($name, $title, $description);
            $settingspages['general'][self::SETTINGPAGE]->add($setting);

            // Footer anti-gravity horizontal offset.
            $name = 'theme_foundation/footerantigravityhorizontaloffset';
            $title = get_string('footerantigravityhorizontaloffset', 'theme_foundation');
            $default = 0;
            $description = get_string('footerantigravityhorizontaloffsetdesc', 'theme_foundation');
            $setting = new \admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
            $setting->set_updatedcallback('purge_all_caches');
            $settingspages['general'][self::SETTINGPAGE]->add($setting);

            // Footer anti-gravity vertical offset.
            $name = 'theme_foundation/footerantigravityverticaloffset';
            $title = get_string('footerantigravityverticaloffset', 'theme_foundation');
            $default = 0;
            $description = get_string('footerantigravityverticaloffsetdesc', 'theme_foundation');
            $setting = new \admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
            $setting->set_updatedcallback('purge_all_caches');
            $settingspages['general'][self::SETTINGPAGE]->add($setting);

            // Font Awesome 6 Free.
            $name = 'theme_foundation/fav';
            $title = get_string('fav', 'theme_foundation');
            $description = get_string('favdesc', 'theme_foundation');
            $default = 0;
            $choices = array(
                0 => new \lang_string('favoff', 'theme_foundation'),
                2 => new \lang_string('fa6name', 'theme_foundation')
            );
            $setting = new \admin_setting_configselect($name, $title, $description, $default, $choices);
            $setting->set_updatedcallback('purge_all_caches');
            $settingspages['general'][self::SETTINGPAGE]->add($setting);

            // Font Awesome 6 Free v4 shims.
            $name = 'theme_foundation/faiv';
            $title = get_string('faiv', 'theme_foundation');
            $description = get_string('faivdesc', 'theme_foundation');
            $default = false;
            $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
            $setting->set_updatedcallback('purge_all_caches');
            $settingspages['general'][self::SETTINGPAGE]->add($setting);

            // Course end number of blocks per row.
            $name = 'theme_foundation/courseendblocksperrow';
            $title = get_string('courseendblocksperrow', 'theme_foundation');
            $default = '3';
            $description = get_string('courseendblocksperrowdesc', 'theme_foundation');
            $choices = array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '6' => '6'
            );
            $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
            $settingspages['general'][self::SETTINGPAGE]->add($setting);

            // Marketing number of blocks per row.
            $name = 'theme_foundation/marketingblocksperrow';
            $title = get_string('marketingblocksperrow', 'theme_foundation');
            $default = '2';
            $description = get_string('marketingblocksperrowdesc', 'theme_foundation');
            $choices = array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '6' => '6'
            );
            $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
            $settingspages['general'][self::SETTINGPAGE]->add($setting);

            // Three columns.
            $name = 'theme_foundation/trio';
            $title = get_string('trio', 'theme_foundation');
            $description = get_string('triodesc', 'theme_foundation');
            $default = false;
            $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
            $setting->set_updatedcallback('purge_all_caches');
            $settingspages['general'][self::SETTINGPAGE]->add($setting);

            // Unaddable blocks.
            $name = 'theme_foundation/unaddableblocks';
            $title = get_string('unaddableblocks', 'theme_foundation');
            $description = get_string('unaddableblocksdesc', 'theme_foundation');
            $default = '';
            $setting = new \admin_setting_configtext($name, $title, $description, $default, true, false);
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

            // H5P settings.
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

            // Module settings.
            $settingspages['module'][self::SETTINGPAGE]->add(
                new \admin_setting_heading(
                    'theme_foundation_moduleheading',
                    get_string('moduleheadingsub', 'theme_foundation'),
                    format_text(get_string('moduleheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );

            /* Call each module where they can either add their settings to an existing settings page or create their own
               and have it added. */
            foreach ($this->modules as $module) {
                $module->add_settings($settingspages, $this);
            }

            // Add the settings pages if they have more than just the settings page heading.
            foreach (array_values($settingspages) as $settingspage) {
                if ($settingspage[self::HASSETTINGS] == true) {
                    $thepage = $settingspage[self::SETTINGPAGE];
                    $fsettings->add($thepage);
                }
            }
        }
        $ADMIN->add('theme_foundation', $fsettings);

        $this->add_importexport_settings();
    }

    /**
     * Adds the separate import / export settings page.
     * This does not work on tabbed settings.
     */
    private function add_importexport_settings() {
        global $ADMIN;
        $page = new \admin_settingpage('theme_foundation_importexport', get_string('properties', 'theme_foundation'));
        if ($ADMIN->fulltree) {

            $page->add(new \admin_setting_heading('theme_foundation_importexport',
                get_string('propertiessub', 'theme_foundation'),
                format_text(get_string('propertiesdesc', 'theme_foundation'), FORMAT_MARKDOWN)));

            $foundationexportprops = optional_param('theme_foundation_getprops_saveprops', 0, PARAM_INT);
            $foundationprops = self::compile_properties('foundation');
            $page->add(new admin_setting_getprops('theme_foundation_getprops',
                get_string('propertiesproperty', 'theme_foundation'),
                get_string('propertiesvalue', 'theme_foundation'),
                $foundationprops,
                'theme_foundation_importexport',
                get_string('propertiesreturn', 'theme_foundation'),
                get_string('propertiesexport', 'theme_foundation'),
                $foundationexportprops
            ));

            // Import theme settings section (put properties).
            $name = 'theme_foundation/theme_foundation_putprops_import_heading';
            $heading = get_string('putpropertiesheading', 'theme_foundation');
            $setting = new \admin_setting_heading($name, $heading, '');
            $page->add($setting);

            $setting = new admin_setting_putprops('theme_foundation_putprops',
                get_string('putpropertiesname', 'theme_foundation'),
                get_string('putpropertiesdesc', 'theme_foundation'),
                'foundation',
                '\theme_foundation\toolbox::put_properties'
            );
            $setting->set_updatedcallback('purge_all_caches');
            $page->add($setting);
        }
        $ADMIN->add('theme_foundation', $page);
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
                font-family: 'Inter';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|Inter-Regular.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Inter';
                font-style: italic;
                font-weight: 400;
                src: url('[[font:theme|Inter-Italic.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 500;
                src: url('[[font:theme|Inter-Medium.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Inter';
                font-style: italic;
                font-weight: 500;
                src: url('[[font:theme|Inter-MediumItalic.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 700;
                src: url('[[font:theme|Inter-Bold.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Inter';
                font-style: italic;
                font-weight: 700;
                src: url('[[font:theme|Inter-BoldItalic.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 300;
                src: url('[[font:theme|Lato-Light.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 300;
                src: url('[[font:theme|Lato-LightItalic.ttf]]') format('truetype');
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
                font-family: 'Lato';
                font-style: italic;
                font-weight: 700;
                src: url('[[font:theme|Lato-BoldItalic.ttf]]') format('truetype');
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
                font-family: 'Nunito';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|Nunito-Regular.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito';
                font-style: italic;
                font-weight: 400;
                src: url('[[font:theme|Nunito-Italic.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito';
                font-style: normal;
                font-weight: 600;
                src: url('[[font:theme|Nunito-SemiBold.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito';
                font-style: italic;
                font-weight: 600;
                src: url('[[font:theme|Nunito-SemiBoldItalic.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito';
                font-style: normal;
                font-weight: 700;
                src: url('[[font:theme|Nunito-Bold.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito';
                font-style: italic;
                font-weight: 700;
                src: url('[[font:theme|Nunito-BoldItalic.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito Sans';
                font-style: normal;
                font-weight: 400;
                src: url('[[font:theme|NunitoSans-Regular.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito Sans';
                font-style: italic;
                font-weight: 400;
                src: url('[[font:theme|NunitoSans-Italic.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito Sans';
                font-style: normal;
                font-weight: 600;
                src: url('[[font:theme|NunitoSans-SemiBold.ttf]]') format('truetype');
            }

            @font-face {
                font-family: 'Nunito Sans';
                font-style: italic;
                font-weight: 600;
                src: url('[[font:theme|NunitoSans-SemiBoldItalic.ttf]]') format('truetype');
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
                src: url('[[font:theme|OpenSans-LightItalic-webfont.woff]]') format('woff');
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
                src: url('[[font:theme|OpenSans-Italic-webfont.woff]]') format('woff');
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
                src: url('[[font:theme|OpenSans-BoldItalic-webfont.woff]]') format('woff');
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
     *
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
     *
     * @return boolean true or false.
     */
    protected function theme_exists($themename) {
        return array_key_exists($themename, $this->theconfigs);
    }

    /**
     * Get the layout options for the theme.
     *
     * @param string $themename Theme name.
     *
     * @return array Of layouts with options if any.
     */
    public function get_theme_layout_options($themename) {
        if (!$this->theme_exists($themename)) {
            $this->add_theme($themename);
        }
        return $this->theconfigs[$themename]->layoutoptions;
    }

    // Settings.
    /**
     * Gets the specified setting.
     *
     * @param string $settingname The name of the setting.
     * @param string $themename The name of the theme to start looking in.
     *
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
     * Finds the given setting in the theme using the get_config core function for when the
     * theme_config object has not been created.
     *
     * @param string $setting Setting name.
     * @param themename $themename null(default of 'foundation' used)|theme name.
     *
     * @return any false|value of setting.
     */
    public static function get_config_setting($setting, $themename = null) {
        if (empty($themename)) {
            $themename = 'foundation';
        }
        return \get_config('theme_'.$themename, $setting);
    }

    /**
     * Gets the setting moodle_url for the given setting if it exists and set.
     *
     * See: https://moodle.org/mod/forum/discuss.php?d=371252#p1516474 and change if theme_config::setting_file_url
     * changes.
     * My need to do: $url = preg_replace('|^https?://|i', '//', $url->out(false)); separately.
     *
     * @param string $setting Setting name.
     * @param string $themename Theme name.
     *
     * @return moodle_url The URL.
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
     *
     * @param string $setting Setting name.
     * @param string $filearea File area.
     * @param string $themename Theme name.
     *
     * @return string The URL.
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
     *
     * @param string $settingname Setting name.
     * @param string $themename Theme name.
     *
     * @return theme_config Theme config object.
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

    /**
     * Gets the list of categories.
     *
     * @return array Categories.
     */
    public static function get_categories_list() {
        static $catlist = null;
        if (empty($catlist)) {
            global $DB;
            $catlist = $DB->get_records('course_categories', null, 'sortorder', 'id, name, depth, path');

            foreach ($catlist as $category) {
                $category->parents = array();
                if ($category->depth > 1 ) {
                    $path = preg_split('|/|', $category->path, -1, PREG_SPLIT_NO_EMPTY);
                    $category->namechunks = array();
                    foreach ($path as $parentid) {
                        $category->namechunks[] = $catlist[$parentid]->name;
                        $category->parents[] = $parentid;
                    }
                    $category->parents = array_reverse($category->parents);
                } else {
                    $category->namechunks = array($category->name);
                }
            }
        }

        return $catlist;
    }

    /**
     * Compile properties.
     *
     * @param string $themename Theme name
     * @param bool $array Is this an array (confusing variable name)
     *
     * @return array properties
     */
    public static function compile_properties($themename, $array = true) {
        global $CFG, $DB;

        $props = array();
        $themeprops = $DB->get_records('config_plugins', array('plugin' => 'theme_'.$themename));

        if ($array) {
            $props['moodle_version'] = $CFG->version;
            // Put the theme version next so that it will be at the top of the table.
            foreach ($themeprops as $themeprop) {
                if ($themeprop->name == 'version') {
                    $props['theme_version'] = $themeprop->value;
                    unset($themeprops[$themeprop->id]);
                    break;
                }
            }

            foreach ($themeprops as $themeprop) {
                $props[$themeprop->name] = $themeprop->value;
            }
        } else {
            $data = new \stdClass();
            $data->id = 0;
            $data->value = $CFG->version;
            $props['moodle_version'] = $data;
            // Convert 'version' to 'theme_version'.
            foreach ($themeprops as $themeprop) {
                if ($themeprop->name == 'version') {
                    $data = new \stdClass();
                    $data->id = $themeprop->id;
                    $data->name = 'theme_version';
                    $data->value = $themeprop->value;
                    $props['theme_version'] = $data;
                    unset($themeprops[$themeprop->id]);
                    break;
                }
            }
            foreach ($themeprops as $themeprop) {
                $data = new \stdClass();
                $data->id = $themeprop->id;
                $data->value = $themeprop->value;
                $props[$themeprop->name] = $data;
            }
        }

        return $props;
    }

    /**
     * Store properties.
     *
     * @param string $themename Theme name
     * @param string $props Properties
     * @return string
     */
    public static function put_properties($themename, $props) {
        global $DB;

        // Get the current properties as a reference and for theme version information.
        $currentprops = self::compile_properties($themename, false);

        // Build the report.
        $report = get_string('putpropertyreport', 'theme_foundation').PHP_EOL;
        $report .= get_string('putpropertyproperties', 'theme_foundation').' \'Moodle\' '.
            get_string('putpropertyversion', 'theme_foundation').' '.$props['moodle_version'].'.'.PHP_EOL;
        unset($props['moodle_version']);
        $report .= get_string('putpropertyour', 'theme_foundation').' \'Moodle\' '.
            get_string('putpropertyversion', 'theme_foundation').' '.$currentprops['moodle_version']->value.'.'.PHP_EOL;
        unset($currentprops['moodle_version']);
        $report .= get_string('putpropertyproperties', 'theme_foundation').' \''.ucfirst($themename).'\' '.
            get_string('putpropertyversion', 'theme_foundation').' '.$props['theme_version'].'.'.PHP_EOL;
        unset($props['theme_version']);
        $report .= get_string('putpropertyour', 'theme_foundation').' \''.ucfirst($themename).'\' '.
            get_string('putpropertyversion', 'theme_foundation').' '.$currentprops['theme_version']->value.'.'.PHP_EOL.PHP_EOL;
        unset($currentprops['theme_version']);

        // Pre-process files - using 'theme_foundation_pluginfile' in lib.php as a reference.
        $filestoreport = '';
        $preprocessfilesettings = array('logo', 'favicon', 'hvp', 'loginbackground');

        // Slide show.
        for ($propslide = 1; $propslide <= $props['frontpagecarouselslides']; $propslide++) {
            $preprocessfilesettings[] = 'frontpageslideimage'.$propslide;
        }

        // Process the file properties.
        foreach ($preprocessfilesettings as $preprocessfilesetting) {
            self::put_prop_file_preprocess($preprocessfilesetting, $props, $filestoreport);
            unset($currentprops[$preprocessfilesetting]);
        }

        if ($filestoreport) {
            $report .= get_string('putpropertiesreportfiles', 'theme_foundation').PHP_EOL.$filestoreport.PHP_EOL;
        }

        // Need to ignore and report on any unknown settings.
        $report .= get_string('putpropertiessettingsreport', 'theme_foundation').PHP_EOL;
        $changed = '';
        $unchanged = '';
        $added = '';
        $ignored = '';
        $settinglog = '';
        foreach ($props as $propkey => $propvalue) {
            $settinglog = '\''.$propkey.'\' '.get_string('putpropertiesvalue', 'theme_foundation').' \''.$propvalue.'\'';
            if (array_key_exists($propkey, $currentprops)) {
                if ($propvalue != $currentprops[$propkey]->value) {
                    $settinglog .= ' '.get_string('putpropertiesfrom', 'theme_foundation').' \''.$currentprops[$propkey]->value.'\'';
                    $changed .= $settinglog.'.'.PHP_EOL;
                    $DB->update_record('config_plugins', array('id' => $currentprops[$propkey]->id, 'value' => $propvalue), true);
                } else {
                    $unchanged .= $settinglog.'.'.PHP_EOL;
                }
            } else if (self::to_add_property($propkey)) {
                // Properties that have an index and don't already exist.
                $DB->insert_record('config_plugins', array(
                    'plugin' => 'theme_'.$themename, 'name' => $propkey, 'value' => $propvalue), true);
                $added .= $settinglog.'.'.PHP_EOL;
            } else {
                $ignored .= $settinglog.'.'.PHP_EOL;
            }
        }

        if (!empty($changed)) {
            $report .= get_string('putpropertieschanged', 'theme_foundation').PHP_EOL.$changed.PHP_EOL;
        }
        if (!empty($added)) {
            $report .= get_string('putpropertiesadded', 'theme_foundation').PHP_EOL.$added.PHP_EOL;
        }
        if (!empty($unchanged)) {
            $report .= get_string('putpropertiesunchanged', 'theme_foundation').PHP_EOL.$unchanged.PHP_EOL;
        }
        if (!empty($ignored)) {
            $report .= get_string('putpropertiesignored', 'theme_foundation').PHP_EOL.$ignored.PHP_EOL;
        }

        return $report;
    }

    /**
     * Property to add
     *
     * @param int $propkey

     * @return array matches
     */
    protected static function to_add_property($propkey) {
        static $matches = '('.
             // Slider ....
             '^frontpageenableslide[1-9][0-9]$|'.
             '^frontpageslidetitle[1-9][0-9]$|'.
             '^frontpageslidecaption[1-9][0-9]$|'.
            ')';

        return (preg_match($matches, $propkey) === 1);
    }

    /**
     * Pre process properties file.
     *
     * @param int $key
     * @param array $props
     * @param string $filestoreport
     *
     */
    private static function put_prop_file_preprocess($key, &$props, &$filestoreport) {
        if (!empty($props[$key])) {
            $filestoreport .= '\''.$key.'\' '.get_string('putpropertiesvalue', 'theme_foundation').' \''.
                \core_text::substr($props[$key], 1).'\'.'.PHP_EOL;
        }
        unset($props[$key]);
    }

    /**
     * Get the FontAwesome markup.
     *
     * @param string $theicon Icon name.
     * @param array $classes Classes.
     * @param array $attributes Attributes.
     * @param string $content Content.
     * @param string $title A title for when the icon needs to have an accessible semantic meaning.
     *
     * @return string Markup.
     */
    public function getfontawesomemarkup($theicon, $classes = array(), $attributes = array(), $content = '', $title = '') {
        if (!empty($theicon)) {
            $fav = $this->get_setting('fav');
            if (!empty($fav)) {
                if ($fav == 1) {
                    $classes[] = $this->get_fa5_from_fa4($theicon);
                } else {
                    $classes[] = $this->get_fa6_from_fa4($theicon);
                }
            } else {
                $classes[] = 'fa fa-'.$theicon;
            }
        }
        $attributes['aria-hidden'] = 'true';
        $attributes['class'] = implode(' ', $classes);
        if (!empty($title)) {
            $attributes['title'] = $title;
            $content .= \html_writer::tag('span', $title, array('class' => 'sr-only'));
        }
        return \html_writer::tag('span', $content, $attributes);
    }

    /**
     * Gets the Font Awesome 5 version of the version 4 icon.
     *
     * @param string $icon The icon.
     * @param boolean $hasprefix Has the 'fa' prefix.
     *
     * @return string Icon CSS classes.
     */
    public function get_fa5_from_fa4($icon, $hasprefix = false) {
        $icontofind = ($hasprefix) ? $icon : 'fa-'.$icon;

        // Ref: fa-v4-shims.js.
        static $icons = array(
            'fa-glass' => 'fas fa-glass-martini',
            'fa-meetup' => 'fab fa-meetup',
            'fa-star-o' => 'far fa-star',
            'fa-remove' => 'fas fa-times',
            'fa-close' => 'fas fa-times',
            'fa-gear' => 'fas fa-cog',
            'fa-trash-o' => 'far fa-trash-alt',
            'fa-file-o' => 'far fa-file',
            'fa-clock-o' => 'far fa-clock',
            'fa-arrow-circle-o-down' => 'far fa-arrow-alt-circle-down',
            'fa-arrow-circle-o-up' => 'far fa-arrow-alt-circle-up',
            'fa-play-circle-o' => 'far fa-play-circle',
            'fa-repeat' => 'fas fa-redo',
            'fa-rotate-right' => 'fas fa-redo',
            'fa-refresh' => 'fas fa-sync',
            'fa-list-alt' => 'far fa-list-alt',
            'fa-dedent' => 'fas fa-outdent',
            'fa-video-camera' => 'fas fa-video',
            'fa-picture-o' => 'far fa-image',
            'fa-photo' => 'far fa-image',
            'fa-image' => 'far fa-image',
            'fa-pencil' => 'fas fa-pencil-alt',
            'fa-map-marker' => 'fas fa-map-marker-alt',
            'fa-pencil-square-o' => 'far fa-edit',
            'fa-share-square-o' => 'far fa-share-square',
            'fa-check-square-o' => 'far fa-check-square',
            'fa-arrows' => 'fas fa-arrows-alt',
            'fa-times-circle-o' => 'far fa-times-circle',
            'fa-check-circle-o' => 'far fa-check-circle',
            'fa-mail-forward' => 'fas fa-share',
            'fa-expand' => 'fas fa-expand-alt',
            'fa-compress' => 'fas fa-compress-alt',
            'fa-eye' => 'far fa-eye',
            'fa-eye-slash' => 'far fa-eye-slash',
            'fa-warning' => 'fas fa-exclamation-triangle',
            'fa-calendar' => 'fas fa-calendar-alt',
            'fa-arrows-v' => 'fas fa-arrows-alt-v',
            'fa-arrows-h' => 'fas fa-arrows-alt-h',
            'fa-bar-chart' => 'far fa-chart-bar',
            'fa-bar-chart-o' => 'far fa-chart-bar',
            'fa-twitter-square' => 'fab fa-twitter-square',
            'fa-facebook-square' => 'fab fa-facebook-square',
            'fa-gears' => 'fas fa-cogs',
            'fa-thumbs-o-up' => 'far fa-thumbs-up',
            'fa-thumbs-o-down' => 'far fa-thumbs-down',
            'fa-heart-o' => 'far fa-heart',
            'fa-sign-out' => 'fas fa-sign-out-alt',
            'fa-linkedin-square' => 'fab fa-linkedin',
            'fa-thumb-tack' => 'fas fa-thumbtack',
            'fa-external-link' => 'fas fa-external-link-alt',
            'fa-sign-in' => 'fas fa-sign-in-alt',
            'fa-github-square' => 'fab fa-github-square',
            'fa-lemon-o' => 'far fa-lemon',
            'fa-square-o' => 'far fa-square',
            'fa-bookmark-o' => 'far fa-bookmark',
            'fa-twitter' => 'fab fa-twitter',
            'fa-facebook' => 'fab fa-facebook-f',
            'fa-facebook-f' => 'fab fa-facebook-f',
            'fa-github' => 'fab fa-github',
            'fa-credit-card' => 'far fa-credit-card',
            'fa-feed' => 'fas fa-rss',
            'fa-hdd-o' => 'far fa-hdd',
            'fa-hand-o-right' => 'far fa-hand-point-right',
            'fa-hand-o-left' => 'far fa-hand-point-left',
            'fa-hand-o-up' => 'far fa-hand-point-up',
            'fa-hand-o-down' => 'far fa-hand-point-down',
            'fa-arrows-alt' => 'fas fa-expand-arrows-alt',
            'fa-group' => 'fas fa-users',
            'fa-chain' => 'fas fa-link',
            'fa-scissors' => 'fas fa-cut',
            'fa-files-o' => 'far fa-copy',
            'fa-floppy-o' => 'far fa-save',
            'fa-navicon' => 'fas fa-bars',
            'fa-reorder' => 'fas fa-bars',
            'fa-pinterest' => 'fab fa-pinterest',
            'fa-pinterest-square' => 'fab fa-pinterest-square',
            'fa-google-plus-square' => 'fab fa-google-plus-square',
            'fa-google-plus' => 'fab fa-google-plus-g',
            'fa-money' => 'far fa-money-bill-alt',
            'fa-unsorted' => 'fas fa-sort',
            'fa-sort-desc' => 'fas fa-sort-down',
            'fa-sort-asc' => 'fas fa-sort-up',
            'fa-linkedin' => 'fab fa-linkedin-in',
            'fa-rotate-left' => 'fas fa-undo',
            'fa-legal' => 'fas fa-gavel',
            'fa-tachometer' => 'fas fa-tachometer-alt',
            'fa-dashboard' => 'fas fa-tachometer-alt',
            'fa-comment-o' => 'far fa-comment',
            'fa-comments-o' => 'far fa-comments',
            'fa-flash' => 'fas fa-bolt',
            'fa-clipboard' => 'far fa-clipboard',
            'fa-paste' => 'far fa-clipboard',
            'fa-lightbulb-o' => 'far fa-lightbulb',
            'fa-exchange' => 'fas fa-exchange-alt',
            'fa-cloud-download' => 'fas fa-cloud-download-alt',
            'fa-cloud-upload' => 'fas fa-cloud-upload-alt',
            'fa-bell-o' => 'far fa-bell',
            'fa-cutlery' => 'fas fa-utensils',
            'fa-file-text-o' => 'far fa-file-alt',
            'fa-building-o' => 'far fa-building',
            'fa-hospital-o' => 'far fa-hospital',
            'fa-tablet' => 'fas fa-tablet-alt',
            'fa-mobile' => 'fas fa-mobile-alt',
            'fa-mobile-phone' => 'fas fa-mobile-alt',
            'fa-circle-o' => 'far fa-circle',
            'fa-mail-reply' => 'fas fa-reply',
            'fa-github-alt' => 'fab fa-github-alt',
            'fa-folder-o' => 'far fa-folder',
            'fa-folder-open-o' => 'far fa-folder-open',
            'fa-smile-o' => 'far fa-smile',
            'fa-frown-o' => 'far fa-frown',
            'fa-meh-o' => 'far fa-meh',
            'fa-keyboard-o' => 'far fa-keyboard',
            'fa-flag-o' => 'far fa-flag',
            'fa-mail-reply-all' => 'fas fa-reply-all',
            'fa-star-half-o' => 'far fa-star-half',
            'fa-star-half-empty' => 'far fa-star-half',
            'fa-star-half-full' => 'far fa-star-half',
            'fa-code-fork' => 'fas fa-code-branch',
            'fa-chain-broken' => 'fas fa-unlink',
            'fa-shield' => 'fas fa-shield-alt',
            'fa-calendar-o' => 'far fa-calendar',
            'fa-maxcdn' => 'fab fa-maxcdn',
            'fa-html5' => 'fab fa-html5',
            'fa-css3' => 'fab fa-css3',
            'fa-ticket' => 'fas fa-ticket-alt',
            'fa-minus-square-o' => 'far fa-minus-square',
            'fa-level-up' => 'fas fa-level-up-alt',
            'fa-level-down' => 'fas fa-level-down-alt',
            'fa-pencil-square' => 'fas fa-pen-square',
            'fa-external-link-square' => 'fas fa-external-link-square-alt',
            'fa-compass' => 'far fa-compass',
            'fa-caret-square-o-down' => 'far fa-caret-square-down',
            'fa-toggle-down' => 'far fa-caret-square-down',
            'fa-caret-square-o-up' => 'far fa-caret-square-up',
            'fa-toggle-up' => 'far fa-caret-square-up',
            'fa-caret-square-o-right' => 'far fa-caret-square-right',
            'fa-toggle-right' => 'far fa-caret-square-right',
            'fa-eur' => 'fas fa-euro-sign',
            'fa-euro' => 'fas fa-euro-sign',
            'fa-gbp' => 'fas fa-pound-sign',
            'fa-usd' => 'fas fa-dollar-sign',
            'fa-dollar' => 'fas fa-dollar-sign',
            'fa-inr' => 'fas fa-rupee-sign',
            'fa-rupee' => 'fas fa-rupee-sign',
            'fa-jpy' => 'fas fa-yen-sign',
            'fa-cny' => 'fas fa-yen-sign',
            'fa-rmb' => 'fas fa-yen-sign',
            'fa-yen' => 'fas fa-yen-sign',
            'fa-rub' => 'fas fa-ruble-sign',
            'fa-ruble' => 'fas fa-ruble-sign',
            'fa-rouble' => 'fas fa-ruble-sign',
            'fa-krw' => 'fas fa-won-sign',
            'fa-won' => 'fas fa-won-sign',
            'fa-btc' => 'fab fa-btc',
            'fa-bitcoin' => 'fab fa-btc',
            'fa-file-text' => 'fas fa-file-alt',
            'fa-sort-alpha-asc' => 'fas fa-sort-alpha-down',
            'fa-sort-alpha-desc' => 'fas fa-sort-alpha-down-alt',
            'fa-sort-amount-asc' => 'fas fa-sort-amount-down',
            'fa-sort-amount-desc' => 'fas fa-sort-amount-down-alt',
            'fa-sort-numeric-asc' => 'fas fa-sort-numeric-down',
            'fa-sort-numeric-desc' => 'fas fa-sort-numeric-down-alt',
            'fa-youtube-square' => 'fab fa-youtube-square',
            'fa-youtube' => 'fab fa-youtube',
            'fa-xing' => 'fab fa-xing',
            'fa-xing-square' => 'fab fa-xing-square',
            'fa-youtube-play' => 'fab fa-youtube',
            'fa-dropbox' => 'fab fa-dropbox',
            'fa-stack-overflow' => 'fab fa-stack-overflow',
            'fa-instagram' => 'fab fa-instagram',
            'fa-flickr' => 'fab fa-flickr',
            'fa-adn' => 'fab fa-adn',
            'fa-bitbucket' => 'fab fa-bitbucket',
            'fa-bitbucket-square' => 'fab fa-bitbucket',
            'fa-tumblr' => 'fab fa-tumblr',
            'fa-tumblr-square' => 'fab fa-tumblr-square',
            'fa-long-arrow-down' => 'fas fa-long-arrow-alt-down',
            'fa-long-arrow-up' => 'fas fa-long-arrow-alt-up',
            'fa-long-arrow-left' => 'fas fa-long-arrow-alt-left',
            'fa-long-arrow-right' => 'fas fa-long-arrow-alt-right',
            'fa-apple' => 'fab fa-apple',
            'fa-windows' => 'fab fa-windows',
            'fa-android' => 'fab fa-android',
            'fa-linux' => 'fab fa-linux',
            'fa-dribbble' => 'fab fa-dribbble',
            'fa-skype' => 'fab fa-skype',
            'fa-foursquare' => 'fab fa-foursquare',
            'fa-trello' => 'fab fa-trello',
            'fa-gratipay' => 'fab fa-gratipay',
            'fa-gittip' => 'fab fa-gratipay',
            'fa-sun-o' => 'far fa-sun',
            'fa-moon-o' => 'far fa-moon',
            'fa-vk' => 'fab fa-vk',
            'fa-weibo' => 'fab fa-weibo',
            'fa-renren' => 'fab fa-renren',
            'fa-pagelines' => 'fab fa-pagelines',
            'fa-stack-exchange' => 'fab fa-stack-exchange',
            'fa-arrow-circle-o-right' => 'far fa-arrow-alt-circle-right',
            'fa-arrow-circle-o-left' => 'far fa-arrow-alt-circle-left',
            'fa-caret-square-o-left' => 'far fa-caret-square-left',
            'fa-toggle-left' => 'far fa-caret-square-left',
            'fa-dot-circle-o' => 'far fa-dot-circle',
            'fa-vimeo-square' => 'fab fa-vimeo-square',
            'fa-try' => 'fas fa-lira-sign',
            'fa-turkish-lira' => 'fas fa-lira-sign',
            'fa-plus-square-o' => 'far fa-plus-square',
            'fa-slack' => 'fab fa-slack',
            'fa-wordpress' => 'fab fa-wordpress',
            'fa-openid' => 'fab fa-openid',
            'fa-institution' => 'fas fa-university',
            'fa-bank' => 'fas fa-university',
            'fa-mortar-board' => 'fas fa-graduation-cap',
            'fa-yahoo' => 'fab fa-yahoo',
            'fa-google' => 'fab fa-google',
            'fa-reddit' => 'fab fa-reddit',
            'fa-reddit-square' => 'fab fa-reddit-square',
            'fa-stumbleupon-circle' => 'fab fa-stumbleupon-circle',
            'fa-stumbleupon' => 'fab fa-stumbleupon',
            'fa-delicious' => 'fab fa-delicious',
            'fa-digg' => 'fab fa-digg',
            'fa-pied-piper-pp' => 'fab fa-pied-piper-pp',
            'fa-pied-piper-alt' => 'fab fa-pied-piper-alt',
            'fa-drupal' => 'fab fa-drupal',
            'fa-joomla' => 'fab fa-joomla',
            'fa-spoon' => 'fas fa-utensil-spoon',
            'fa-behance' => 'fab fa-behance',
            'fa-behance-square' => 'fab fa-behance-square',
            'fa-steam' => 'fab fa-steam',
            'fa-steam-square' => 'fab fa-steam-square',
            'fa-automobile' => 'fas fa-car',
            'fa-envelope-o' => 'far fa-envelope',
            'fa-spotify' => 'fab fa-spotify',
            'fa-deviantart' => 'fab fa-deviantart',
            'fa-soundcloud' => 'fab fa-soundcloud',
            'fa-file-pdf-o' => 'far fa-file-pdf',
            'fa-file-word-o' => 'far fa-file-word',
            'fa-file-excel-o' => 'far fa-file-excel',
            'fa-file-powerpoint-o' => 'far fa-file-powerpoint',
            'fa-file-image-o' => 'far fa-file-image',
            'fa-file-photo-o' => 'far fa-file-image',
            'fa-file-picture-o' => 'far fa-file-image',
            'fa-file-archive-o' => 'far fa-file-archive',
            'fa-file-zip-o' => 'far fa-file-archive',
            'fa-file-audio-o' => 'far fa-file-audio',
            'fa-file-sound-o' => 'far fa-file-audio',
            'fa-file-video-o' => 'far fa-file-video',
            'fa-file-movie-o' => 'far fa-file-video',
            'fa-file-code-o' => 'far fa-file-code',
            'fa-vine' => 'fab fa-vine',
            'fa-codepen' => 'fab fa-codepen',
            'fa-jsfiddle' => 'fab fa-jsfiddle',
            'fa-life-ring' => 'far fa-life-ring',
            'fa-life-bouy' => 'far fa-life-ring',
            'fa-life-buoy' => 'far fa-life-ring',
            'fa-life-saver' => 'far fa-life-ring',
            'fa-support' => 'far fa-life-ring',
            'fa-circle-o-notch' => 'fas fa-circle-notch',
            'fa-rebel' => 'fab fa-rebel',
            'fa-ra' => 'fab fa-rebel',
            'fa-resistance' => 'fab fa-rebel',
            'fa-empire' => 'fab fa-empire',
            'fa-ge' => 'fab fa-empire',
            'fa-git-square' => 'fab fa-git-square',
            'fa-git' => 'fab fa-git',
            'fa-hacker-news' => 'fab fa-hacker-news',
            'fa-y-combinator-square' => 'fab fa-hacker-news',
            'fa-yc-square' => 'fab fa-hacker-news',
            'fa-tencent-weibo' => 'fab fa-tencent-weibo',
            'fa-qq' => 'fab fa-qq',
            'fa-weixin' => 'fab fa-weixin',
            'fa-wechat' => 'fab fa-weixin',
            'fa-send' => 'fas fa-paper-plane',
            'fa-paper-plane-o' => 'far fa-paper-plane',
            'fa-send-o' => 'far fa-paper-plane',
            'fa-circle-thin' => 'far fa-circle',
            'fa-header' => 'fas fa-heading',
            'fa-sliders' => 'fas fa-sliders-h',
            'fa-futbol-o' => 'far fa-futbol',
            'fa-soccer-ball-o' => 'far fa-futbol',
            'fa-slideshare' => 'fab fa-slideshare',
            'fa-twitch' => 'fab fa-twitch',
            'fa-yelp' => 'fab fa-yelp',
            'fa-newspaper-o' => 'far fa-newspaper',
            'fa-paypal' => 'fab fa-paypal',
            'fa-google-wallet' => 'fab fa-google-wallet',
            'fa-cc-visa' => 'fab fa-cc-visa',
            'fa-cc-mastercard' => 'fab fa-cc-mastercard',
            'fa-cc-discover' => 'fab fa-cc-discover',
            'fa-cc-amex' => 'fab fa-cc-amex',
            'fa-cc-paypal' => 'fab fa-cc-paypal',
            'fa-cc-stripe' => 'fab fa-cc-stripe',
            'fa-bell-slash-o' => 'far fa-bell-slash',
            'fa-trash' => 'fas fa-trash-alt',
            'fa-copyright' => 'far fa-copyright',
            'fa-eyedropper' => 'fas fa-eye-dropper',
            'fa-area-chart' => 'fas fa-chart-area',
            'fa-pie-chart' => 'fas fa-chart-pie',
            'fa-line-chart' => 'fas fa-chart-line',
            'fa-lastfm' => 'fab fa-lastfm',
            'fa-lastfm-square' => 'fab fa-lastfm-square',
            'fa-ioxhost' => 'fab fa-ioxhost',
            'fa-angellist' => 'fab fa-angellist',
            'fa-cc' => 'far fa-closed-captioning',
            'fa-ils' => 'fas fa-shekel-sign',
            'fa-shekel' => 'fas fa-shekel-sign',
            'fa-sheqel' => 'fas fa-shekel-sign',
            'fa-meanpath' => 'fab fa-font-awesome',
            'fa-buysellads' => 'fab fa-buysellads',
            'fa-connectdevelop' => 'fab fa-connectdevelop',
            'fa-dashcube' => 'fab fa-dashcube',
            'fa-forumbee' => 'fab fa-forumbee',
            'fa-leanpub' => 'fab fa-leanpub',
            'fa-sellsy' => 'fab fa-sellsy',
            'fa-shirtsinbulk' => 'fab fa-shirtsinbulk',
            'fa-simplybuilt' => 'fab fa-simplybuilt',
            'fa-skyatlas' => 'fab fa-skyatlas',
            'fa-diamond' => 'far fa-gem',
            'fa-intersex' => 'fas fa-transgender',
            'fa-facebook-official' => 'fab fa-facebook',
            'fa-pinterest-p' => 'fab fa-pinterest-p',
            'fa-whatsapp' => 'fab fa-whatsapp',
            'fa-hotel' => 'fas fa-bed',
            'fa-viacoin' => 'fab fa-viacoin',
            'fa-medium' => 'fab fa-medium',
            'fa-y-combinator' => 'fab fa-y-combinator',
            'fa-yc' => 'fab fa-y-combinator',
            'fa-optin-monster' => 'fab fa-optin-monster',
            'fa-opencart' => 'fab fa-opencart',
            'fa-expeditedssl' => 'fab fa-expeditedssl',
            'fa-battery-4' => 'fas fa-battery-full',
            'fa-battery' => 'fas fa-battery-full',
            'fa-battery-3' => 'fas fa-battery-three-quarters',
            'fa-battery-2' => 'fas fa-battery-half',
            'fa-battery-1' => 'fas fa-battery-quarter',
            'fa-battery-0' => 'fas fa-battery-empty',
            'fa-object-group' => 'far fa-object-group',
            'fa-object-ungroup' => 'far fa-object-ungroup',
            'fa-sticky-note-o' => 'far fa-sticky-note',
            'fa-cc-jcb' => 'fab fa-cc-jcb',
            'fa-cc-diners-club' => 'fab fa-cc-diners-club',
            'fa-clone' => 'far fa-clone',
            'fa-hourglass-o' => 'far fa-hourglass',
            'fa-hourglass-1' => 'fas fa-hourglass-start',
            'fa-hourglass-2' => 'fas fa-hourglass-half',
            'fa-hourglass-3' => 'fas fa-hourglass-end',
            'fa-hand-rock-o' => 'far fa-hand-rock',
            'fa-hand-grab-o' => 'far fa-hand-rock',
            'fa-hand-paper-o' => 'far fa-hand-paper',
            'fa-hand-stop-o' => 'far fa-hand-paper',
            'fa-hand-scissors-o' => 'far fa-hand-scissors',
            'fa-hand-lizard-o' => 'far fa-hand-lizard',
            'fa-hand-spock-o' => 'far fa-hand-spock',
            'fa-hand-pointer-o' => 'far fa-hand-pointer',
            'fa-hand-peace-o' => 'far fa-hand-peace',
            'fa-registered' => 'far fa-registered',
            'fa-creative-commons' => 'fab fa-creative-commons',
            'fa-gg' => 'fab fa-gg',
            'fa-gg-circle' => 'fab fa-gg-circle',
            'fa-tripadvisor' => 'fab fa-tripadvisor',
            'fa-odnoklassniki' => 'fab fa-odnoklassniki',
            'fa-odnoklassniki-square' => 'fab fa-odnoklassniki-square',
            'fa-get-pocket' => 'fab fa-get-pocket',
            'fa-wikipedia-w' => 'fab fa-wikipedia-w',
            'fa-safari' => 'fab fa-safari',
            'fa-chrome' => 'fab fa-chrome',
            'fa-firefox' => 'fab fa-firefox',
            'fa-opera' => 'fab fa-opera',
            'fa-internet-explorer' => 'fab fa-internet-explorer',
            'fa-television' => 'fas fa-tv',
            'fa-contao' => 'fab fa-contao',
            'fa-500px' => 'fab fa-500px',
            'fa-amazon' => 'fab fa-amazon',
            'fa-calendar-plus-o' => 'far fa-calendar-plus',
            'fa-calendar-minus-o' => 'far fa-calendar-minus',
            'fa-calendar-times-o' => 'far fa-calendar-times',
            'fa-calendar-check-o' => 'far fa-calendar-check',
            'fa-map-o' => 'far fa-map',
            'fa-commenting' => 'fas fa-comment-dots',
            'fa-commenting-o' => 'far fa-comment-dots',
            'fa-houzz' => 'fab fa-houzz',
            'fa-vimeo' => 'fab fa-vimeo-v',
            'fa-black-tie' => 'fab fa-black-tie',
            'fa-fonticons' => 'fab fa-fonticons',
            'fa-reddit-alien' => 'fab fa-reddit-alien',
            'fa-edge' => 'fab fa-edge',
            'fa-credit-card-alt' => 'fas fa-credit-card',
            'fa-codiepie' => 'fab fa-codiepie',
            'fa-modx' => 'fab fa-modx',
            'fa-fort-awesome' => 'fab fa-fort-awesome',
            'fa-usb' => 'fab fa-usb',
            'fa-product-hunt' => 'fab fa-product-hunt',
            'fa-mixcloud' => 'fab fa-mixcloud',
            'fa-scribd' => 'fab fa-scribd',
            'fa-pause-circle-o' => 'far fa-pause-circle',
            'fa-stop-circle-o' => 'far fa-stop-circle',
            'fa-bluetooth' => 'fab fa-bluetooth',
            'fa-bluetooth-b' => 'fab fa-bluetooth-b',
            'fa-gitlab' => 'fab fa-gitlab',
            'fa-wpbeginner' => 'fab fa-wpbeginner',
            'fa-wpforms' => 'fab fa-wpforms',
            'fa-envira' => 'fab fa-envira',
            'fa-wheelchair-alt' => 'fab fa-accessible-icon',
            'fa-question-circle-o' => 'far fa-question-circle',
            'fa-volume-control-phone' => 'fas fa-phone-volume',
            'fa-asl-interpreting' => 'fas fa-american-sign-language-interpreting',
            'fa-deafness' => 'fas fa-deaf',
            'fa-hard-of-hearing' => 'fas fa-deaf',
            'fa-glide' => 'fab fa-glide',
            'fa-glide-g' => 'fab fa-glide-g',
            'fa-signing' => 'fas fa-sign-language',
            'fa-viadeo' => 'fab fa-viadeo',
            'fa-viadeo-square' => 'fab fa-viadeo-square',
            'fa-snapchat' => 'fab fa-snapchat',
            'fa-snapchat-ghost' => 'fab fa-snapchat-ghost',
            'fa-snapchat-square' => 'fab fa-snapchat-square',
            'fa-pied-piper' => 'fab fa-pied-piper',
            'fa-first-order' => 'fab fa-first-order',
            'fa-yoast' => 'fab fa-yoast',
            'fa-themeisle' => 'fab fa-themeisle',
            'fa-google-plus-official' => 'fab fa-google-plus',
            'fa-google-plus-circle' => 'fab fa-google-plus',
            'fa-font-awesome' => 'fab fa-font-awesome',
            'fa-fa' => 'fab fa-font-awesome',
            'fa-handshake-o' => 'far fa-handshake',
            'fa-envelope-open-o' => 'far fa-envelope-open',
            'fa-linode' => 'fab fa-linode',
            'fa-address-book-o' => 'far fa-address-book',
            'fa-vcard' => 'fas fa-address-card',
            'fa-address-card-o' => 'far fa-address-card',
            'fa-vcard-o' => 'far fa-address-card',
            'fa-user-circle-o' => 'far fa-user-circle',
            'fa-user-o' => 'far fa-user',
            'fa-id-badge' => 'far fa-id-badge',
            'fa-drivers-license' => 'fas fa-id-card',
            'fa-id-card-o' => 'far fa-id-card',
            'fa-drivers-license-o' => 'far fa-id-card',
            'fa-quora' => 'fab fa-quora',
            'fa-free-code-camp' => 'fab fa-free-code-camp',
            'fa-telegram' => 'fab fa-telegram',
            'fa-thermometer-4' => 'fas fa-thermometer-full',
            'fa-thermometer' => 'fas fa-thermometer-full',
            'fa-thermometer-3' => 'fas fa-thermometer-three-quarters',
            'fa-thermometer-2' => 'fas fa-thermometer-half',
            'fa-thermometer-1' => 'fas fa-thermometer-quarter',
            'fa-thermometer-0' => 'fas fa-thermometer-empty',
            'fa-bathtub' => 'fas fa-bath',
            'fa-s15' => 'fas fa-bath',
            'fa-window-maximize' => 'far fa-window-maximize',
            'fa-window-restore' => 'far fa-window-restore',
            'fa-times-rectangle' => 'fas fa-window-close',
            'fa-window-close-o' => 'far fa-window-close',
            'fa-times-rectangle-o' => 'far fa-window-close',
            'fa-bandcamp' => 'fab fa-bandcamp',
            'fa-grav' => 'fab fa-grav',
            'fa-etsy' => 'fab fa-etsy',
            'fa-imdb' => 'fab fa-imdb',
            'fa-ravelry' => 'fab fa-ravelry',
            'fa-eercast' => 'fab fa-sellcast',
            'fa-snowflake-o' => 'far fa-snowflake',
            'fa-superpowers' => 'fab fa-superpowers',
            'fa-wpexplorer' => 'fab fa-wpexplorer',
            'fa-cab' => 'fas fa-taxi'
        );

        if (isset($icons[$icontofind])) {
            return $icons[$icontofind];
        } else {
            // Guess.
            return 'fas '.$icontofind;
        }
    }

    /**
     * Gets the Font Awesome 6 version of the version 4 icon.
     *
     * @param string $icon The icon.
     * @param boolean $hasprefix Has the 'fa' prefix.
     *
     * @return string Icon CSS classes.
     */
    public function get_fa6_from_fa4($icon, $hasprefix = false) {
        $icontofind = ($hasprefix) ? $icon : 'fa-'.$icon;

        // Ref: fa-v4-shims.js.
        /* Node JS Code:
            shims.forEach(function(value, index, array) {
                output = '            \'fa-' + value[0] + '\' => \'';
                if (value[1] == null) {
                    output += 'fas';
                } else {
                    output += value[1];
                }
                output = output + ' fa-';
                if (value[2] == null) {
                    output += value[0];
                } else {
                    output += value[2];
                }
                output += '\',';
                console.log(output);
            });
        */
        static $icons = array(
            'fa-glass' => 'fas fa-martini-glass-empty',
            'fa-envelope-o' => 'far fa-envelope',
            'fa-star-o' => 'far fa-star',
            'fa-remove' => 'fas fa-xmark',
            'fa-close' => 'fas fa-xmark',
            'fa-gear' => 'fas fa-gear',
            'fa-trash-o' => 'far fa-trash-can',
            'fa-home' => 'fas fa-house',
            'fa-file-o' => 'far fa-file',
            'fa-clock-o' => 'far fa-clock',
            'fa-arrow-circle-o-down' => 'far fa-circle-down',
            'fa-arrow-circle-o-up' => 'far fa-circle-up',
            'fa-play-circle-o' => 'far fa-circle-play',
            'fa-repeat' => 'fas fa-arrow-rotate-right',
            'fa-rotate-right' => 'fas fa-arrow-rotate-right',
            'fa-refresh' => 'fas fa-arrows-rotate',
            'fa-list-alt' => 'far fa-rectangle-list',
            'fa-dedent' => 'fas fa-outdent',
            'fa-video-camera' => 'fas fa-video',
            'fa-picture-o' => 'far fa-image',
            'fa-photo' => 'far fa-image',
            'fa-image' => 'far fa-image',
            'fa-map-marker' => 'fas fa-location-dot',
            'fa-pencil-square-o' => 'far fa-pen-to-square',
            'fa-edit' => 'far fa-pen-to-square',
            'fa-share-square-o' => 'fas fa-share-from-square',
            'fa-check-square-o' => 'far fa-square-check',
            'fa-arrows' => 'fas fa-up-down-left-right',
            'fa-times-circle-o' => 'far fa-circle-xmark',
            'fa-check-circle-o' => 'far fa-circle-check',
            'fa-mail-forward' => 'fas fa-share',
            'fa-expand' => 'fas fa-up-right-and-down-left-from-center',
            'fa-compress' => 'fas fa-down-left-and-up-right-to-center',
            'fa-eye' => 'far fa-eye',
            'fa-eye-slash' => 'far fa-eye-slash',
            'fa-warning' => 'fas fa-triangle-exclamation',
            'fa-calendar' => 'fas fa-calendar-days',
            'fa-arrows-v' => 'fas fa-up-down',
            'fa-arrows-h' => 'fas fa-left-right',
            'fa-bar-chart' => 'fas fa-chart-column',
            'fa-bar-chart-o' => 'fas fa-chart-column',
            'fa-twitter-square' => 'fab fa-twitter-square',
            'fa-facebook-square' => 'fab fa-facebook-square',
            'fa-gears' => 'fas fa-gears',
            'fa-thumbs-o-up' => 'far fa-thumbs-up',
            'fa-thumbs-o-down' => 'far fa-thumbs-down',
            'fa-heart-o' => 'far fa-heart',
            'fa-sign-out' => 'fas fa-right-from-bracket',
            'fa-linkedin-square' => 'fab fa-linkedin',
            'fa-thumb-tack' => 'fas fa-thumbtack',
            'fa-external-link' => 'fas fa-up-right-from-square',
            'fa-sign-in' => 'fas fa-right-to-bracket',
            'fa-github-square' => 'fab fa-github-square',
            'fa-lemon-o' => 'far fa-lemon',
            'fa-square-o' => 'far fa-square',
            'fa-bookmark-o' => 'far fa-bookmark',
            'fa-twitter' => 'fab fa-twitter',
            'fa-facebook' => 'fab fa-facebook-f',
            'fa-facebook-f' => 'fab fa-facebook-f',
            'fa-github' => 'fab fa-github',
            'fa-credit-card' => 'far fa-credit-card',
            'fa-feed' => 'fas fa-rss',
            'fa-hdd-o' => 'far fa-hard-drive',
            'fa-hand-o-right' => 'far fa-hand-point-right',
            'fa-hand-o-left' => 'far fa-hand-point-left',
            'fa-hand-o-up' => 'far fa-hand-point-up',
            'fa-hand-o-down' => 'far fa-hand-point-down',
            'fa-globe' => 'fas fa-earth-americas',
            'fa-tasks' => 'fas fa-bars-progress',
            'fa-arrows-alt' => 'fas fa-maximize',
            'fa-group' => 'fas fa-users',
            'fa-chain' => 'fas fa-link',
            'fa-cut' => 'fas fa-scissors',
            'fa-files-o' => 'far fa-copy',
            'fa-floppy-o' => 'far fa-floppy-disk',
            'fa-save' => 'far fa-floppy-disk',
            'fa-navicon' => 'fas fa-bars',
            'fa-reorder' => 'fas fa-bars',
            'fa-magic' => 'fas fa-wand-magic-sparkles',
            'fa-pinterest' => 'fab fa-pinterest',
            'fa-pinterest-square' => 'fab fa-pinterest-square',
            'fa-google-plus-square' => 'fab fa-google-plus-square',
            'fa-google-plus' => 'fab fa-google-plus-g',
            'fa-money' => 'fas fa-money-bill-1',
            'fa-unsorted' => 'fas fa-sort',
            'fa-sort-desc' => 'fas fa-sort-down',
            'fa-sort-asc' => 'fas fa-sort-up',
            'fa-linkedin' => 'fab fa-linkedin-in',
            'fa-rotate-left' => 'fas fa-arrow-rotate-left',
            'fa-legal' => 'fas fa-gavel',
            'fa-tachometer' => 'fas fa-gauge',
            'fa-dashboard' => 'fas fa-gauge',
            'fa-comment-o' => 'far fa-comment',
            'fa-comments-o' => 'far fa-comments',
            'fa-flash' => 'fas fa-bolt',
            'fa-clipboard' => 'fas fa-paste',
            'fa-lightbulb-o' => 'far fa-lightbulb',
            'fa-exchange' => 'fas fa-right-left',
            'fa-cloud-download' => 'fas fa-cloud-arrow-down',
            'fa-cloud-upload' => 'fas fa-cloud-arrow-up',
            'fa-bell-o' => 'far fa-bell',
            'fa-cutlery' => 'fas fa-utensils',
            'fa-file-text-o' => 'far fa-file-lines',
            'fa-building-o' => 'far fa-building',
            'fa-hospital-o' => 'far fa-hospital',
            'fa-tablet' => 'fas fa-tablet-screen-button',
            'fa-mobile' => 'fas fa-mobile-screen-button',
            'fa-mobile-phone' => 'fas fa-mobile-screen-button',
            'fa-circle-o' => 'far fa-circle',
            'fa-mail-reply' => 'fas fa-reply',
            'fa-github-alt' => 'fab fa-github-alt',
            'fa-folder-o' => 'far fa-folder',
            'fa-folder-open-o' => 'far fa-folder-open',
            'fa-smile-o' => 'far fa-face-smile',
            'fa-frown-o' => 'far fa-face-frown',
            'fa-meh-o' => 'far fa-face-meh',
            'fa-keyboard-o' => 'far fa-keyboard',
            'fa-flag-o' => 'far fa-flag',
            'fa-mail-reply-all' => 'fas fa-reply-all',
            'fa-star-half-o' => 'far fa-star-half-stroke',
            'fa-star-half-empty' => 'far fa-star-half-stroke',
            'fa-star-half-full' => 'far fa-star-half-stroke',
            'fa-code-fork' => 'fas fa-code-branch',
            'fa-chain-broken' => 'fas fa-link-slash',
            'fa-unlink' => 'fas fa-link-slash',
            'fa-calendar-o' => 'far fa-calendar',
            'fa-maxcdn' => 'fab fa-maxcdn',
            'fa-html5' => 'fab fa-html5',
            'fa-css3' => 'fab fa-css3',
            'fa-unlock-alt' => 'fas fa-unlock',
            'fa-minus-square-o' => 'far fa-square-minus',
            'fa-level-up' => 'fas fa-turn-up',
            'fa-level-down' => 'fas fa-turn-down',
            'fa-pencil-square' => 'fas fa-square-pen',
            'fa-external-link-square' => 'fas fa-square-up-right',
            'fa-compass' => 'far fa-compass',
            'fa-caret-square-o-down' => 'far fa-square-caret-down',
            'fa-toggle-down' => 'far fa-square-caret-down',
            'fa-caret-square-o-up' => 'far fa-square-caret-up',
            'fa-toggle-up' => 'far fa-square-caret-up',
            'fa-caret-square-o-right' => 'far fa-square-caret-right',
            'fa-toggle-right' => 'far fa-square-caret-right',
            'fa-eur' => 'fas fa-euro-sign',
            'fa-euro' => 'fas fa-euro-sign',
            'fa-gbp' => 'fas fa-sterling-sign',
            'fa-usd' => 'fas fa-dollar-sign',
            'fa-dollar' => 'fas fa-dollar-sign',
            'fa-inr' => 'fas fa-indian-rupee-sign',
            'fa-rupee' => 'fas fa-indian-rupee-sign',
            'fa-jpy' => 'fas fa-yen-sign',
            'fa-cny' => 'fas fa-yen-sign',
            'fa-rmb' => 'fas fa-yen-sign',
            'fa-yen' => 'fas fa-yen-sign',
            'fa-rub' => 'fas fa-ruble-sign',
            'fa-ruble' => 'fas fa-ruble-sign',
            'fa-rouble' => 'fas fa-ruble-sign',
            'fa-krw' => 'fas fa-won-sign',
            'fa-won' => 'fas fa-won-sign',
            'fa-btc' => 'fab fa-btc',
            'fa-bitcoin' => 'fab fa-btc',
            'fa-file-text' => 'fas fa-file-lines',
            'fa-sort-alpha-asc' => 'fas fa-arrow-down-a-z',
            'fa-sort-alpha-desc' => 'fas fa-arrow-down-z-a',
            'fa-sort-amount-asc' => 'fas fa-arrow-down-short-wide',
            'fa-sort-amount-desc' => 'fas fa-arrow-down-wide-short',
            'fa-sort-numeric-asc' => 'fas fa-arrow-down-1-9',
            'fa-sort-numeric-desc' => 'fas fa-arrow-down-9-1',
            'fa-youtube-square' => 'fab fa-youtube-square',
            'fa-youtube' => 'fab fa-youtube',
            'fa-xing' => 'fab fa-xing',
            'fa-xing-square' => 'fab fa-xing-square',
            'fa-youtube-play' => 'fab fa-youtube',
            'fa-dropbox' => 'fab fa-dropbox',
            'fa-stack-overflow' => 'fab fa-stack-overflow',
            'fa-instagram' => 'fab fa-instagram',
            'fa-flickr' => 'fab fa-flickr',
            'fa-adn' => 'fab fa-adn',
            'fa-bitbucket' => 'fab fa-bitbucket',
            'fa-bitbucket-square' => 'fab fa-bitbucket',
            'fa-tumblr' => 'fab fa-tumblr',
            'fa-tumblr-square' => 'fab fa-tumblr-square',
            'fa-long-arrow-down' => 'fas fa-down-long',
            'fa-long-arrow-up' => 'fas fa-up-long',
            'fa-long-arrow-left' => 'fas fa-left-long',
            'fa-long-arrow-right' => 'fas fa-right-long',
            'fa-apple' => 'fab fa-apple',
            'fa-windows' => 'fab fa-windows',
            'fa-android' => 'fab fa-android',
            'fa-linux' => 'fab fa-linux',
            'fa-dribbble' => 'fab fa-dribbble',
            'fa-skype' => 'fab fa-skype',
            'fa-foursquare' => 'fab fa-foursquare',
            'fa-trello' => 'fab fa-trello',
            'fa-gratipay' => 'fab fa-gratipay',
            'fa-gittip' => 'fab fa-gratipay',
            'fa-sun-o' => 'far fa-sun',
            'fa-moon-o' => 'far fa-moon',
            'fa-vk' => 'fab fa-vk',
            'fa-weibo' => 'fab fa-weibo',
            'fa-renren' => 'fab fa-renren',
            'fa-pagelines' => 'fab fa-pagelines',
            'fa-stack-exchange' => 'fab fa-stack-exchange',
            'fa-arrow-circle-o-right' => 'far fa-circle-right',
            'fa-arrow-circle-o-left' => 'far fa-circle-left',
            'fa-caret-square-o-left' => 'far fa-square-caret-left',
            'fa-toggle-left' => 'far fa-square-caret-left',
            'fa-dot-circle-o' => 'far fa-circle-dot',
            'fa-vimeo-square' => 'fab fa-vimeo-square',
            'fa-try' => 'fas fa-turkish-lira-sign',
            'fa-turkish-lira' => 'fas fa-turkish-lira-sign',
            'fa-plus-square-o' => 'far fa-square-plus',
            'fa-slack' => 'fab fa-slack',
            'fa-wordpress' => 'fab fa-wordpress',
            'fa-openid' => 'fab fa-openid',
            'fa-institution' => 'fas fa-bank',
            'fa-bank' => 'fas fa-bank',
            'fa-mortar-board' => 'fas fa-graduation-cap',
            'fa-yahoo' => 'fab fa-yahoo',
            'fa-google' => 'fab fa-google',
            'fa-reddit' => 'fab fa-reddit',
            'fa-reddit-square' => 'fab fa-reddit-square',
            'fa-stumbleupon-circle' => 'fab fa-stumbleupon-circle',
            'fa-stumbleupon' => 'fab fa-stumbleupon',
            'fa-delicious' => 'fab fa-delicious',
            'fa-digg' => 'fab fa-digg',
            'fa-pied-piper-pp' => 'fab fa-pied-piper-pp',
            'fa-pied-piper-alt' => 'fab fa-pied-piper-alt',
            'fa-drupal' => 'fab fa-drupal',
            'fa-joomla' => 'fab fa-joomla',
            'fa-behance' => 'fab fa-behance',
            'fa-behance-square' => 'fab fa-behance-square',
            'fa-steam' => 'fab fa-steam',
            'fa-steam-square' => 'fab fa-steam-square',
            'fa-automobile' => 'fas fa-car',
            'fa-cab' => 'fas fa-taxi',
            'fa-spotify' => 'fab fa-spotify',
            'fa-deviantart' => 'fab fa-deviantart',
            'fa-soundcloud' => 'fab fa-soundcloud',
            'fa-file-pdf-o' => 'far fa-file-pdf',
            'fa-file-word-o' => 'far fa-file-word',
            'fa-file-excel-o' => 'far fa-file-excel',
            'fa-file-powerpoint-o' => 'far fa-file-powerpoint',
            'fa-file-image-o' => 'far fa-file-image',
            'fa-file-photo-o' => 'far fa-file-image',
            'fa-file-picture-o' => 'far fa-file-image',
            'fa-file-archive-o' => 'far fa-file-zipper',
            'fa-file-zip-o' => 'far fa-file-zipper',
            'fa-file-audio-o' => 'far fa-file-audio',
            'fa-file-sound-o' => 'far fa-file-audio',
            'fa-file-video-o' => 'far fa-file-video',
            'fa-file-movie-o' => 'far fa-file-video',
            'fa-file-code-o' => 'far fa-file-code',
            'fa-vine' => 'fab fa-vine',
            'fa-codepen' => 'fab fa-codepen',
            'fa-jsfiddle' => 'fab fa-jsfiddle',
            'fa-life-bouy' => 'fas fa-life-ring',
            'fa-life-buoy' => 'fas fa-life-ring',
            'fa-life-saver' => 'fas fa-life-ring',
            'fa-support' => 'fas fa-life-ring',
            'fa-circle-o-notch' => 'fas fa-circle-notch',
            'fa-rebel' => 'fab fa-rebel',
            'fa-ra' => 'fab fa-rebel',
            'fa-resistance' => 'fab fa-rebel',
            'fa-empire' => 'fab fa-empire',
            'fa-ge' => 'fab fa-empire',
            'fa-git-square' => 'fab fa-git-square',
            'fa-git' => 'fab fa-git',
            'fa-hacker-news' => 'fab fa-hacker-news',
            'fa-y-combinator-square' => 'fab fa-hacker-news',
            'fa-yc-square' => 'fab fa-hacker-news',
            'fa-tencent-weibo' => 'fab fa-tencent-weibo',
            'fa-qq' => 'fab fa-qq',
            'fa-weixin' => 'fab fa-weixin',
            'fa-wechat' => 'fab fa-weixin',
            'fa-send' => 'fas fa-paper-plane',
            'fa-paper-plane-o' => 'far fa-paper-plane',
            'fa-send-o' => 'far fa-paper-plane',
            'fa-circle-thin' => 'far fa-circle',
            'fa-header' => 'fas fa-heading',
            'fa-futbol-o' => 'far fa-futbol',
            'fa-soccer-ball-o' => 'far fa-futbol',
            'fa-slideshare' => 'fab fa-slideshare',
            'fa-twitch' => 'fab fa-twitch',
            'fa-yelp' => 'fab fa-yelp',
            'fa-newspaper-o' => 'far fa-newspaper',
            'fa-paypal' => 'fab fa-paypal',
            'fa-google-wallet' => 'fab fa-google-wallet',
            'fa-cc-visa' => 'fab fa-cc-visa',
            'fa-cc-mastercard' => 'fab fa-cc-mastercard',
            'fa-cc-discover' => 'fab fa-cc-discover',
            'fa-cc-amex' => 'fab fa-cc-amex',
            'fa-cc-paypal' => 'fab fa-cc-paypal',
            'fa-cc-stripe' => 'fab fa-cc-stripe',
            'fa-bell-slash-o' => 'far fa-bell-slash',
            'fa-trash' => 'fas fa-trash-can',
            'fa-copyright' => 'far fa-copyright',
            'fa-eyedropper' => 'fas fa-eye-dropper',
            'fa-area-chart' => 'fas fa-chart-area',
            'fa-pie-chart' => 'fas fa-chart-pie',
            'fa-line-chart' => 'fas fa-chart-line',
            'fa-lastfm' => 'fab fa-lastfm',
            'fa-lastfm-square' => 'fab fa-lastfm-square',
            'fa-ioxhost' => 'fab fa-ioxhost',
            'fa-angellist' => 'fab fa-angellist',
            'fa-cc' => 'far fa-closed-captioning',
            'fa-ils' => 'fas fa-shekel-sign',
            'fa-shekel' => 'fas fa-shekel-sign',
            'fa-sheqel' => 'fas fa-shekel-sign',
            'fa-buysellads' => 'fab fa-buysellads',
            'fa-connectdevelop' => 'fab fa-connectdevelop',
            'fa-dashcube' => 'fab fa-dashcube',
            'fa-forumbee' => 'fab fa-forumbee',
            'fa-leanpub' => 'fab fa-leanpub',
            'fa-sellsy' => 'fab fa-sellsy',
            'fa-shirtsinbulk' => 'fab fa-shirtsinbulk',
            'fa-simplybuilt' => 'fab fa-simplybuilt',
            'fa-skyatlas' => 'fab fa-skyatlas',
            'fa-diamond' => 'far fa-gem',
            'fa-transgender' => 'fas fa-mars-and-venus',
            'fa-intersex' => 'fas fa-mars-and-venus',
            'fa-transgender-alt' => 'fas fa-transgender',
            'fa-facebook-official' => 'fab fa-facebook',
            'fa-pinterest-p' => 'fab fa-pinterest-p',
            'fa-whatsapp' => 'fab fa-whatsapp',
            'fa-hotel' => 'fas fa-bed',
            'fa-viacoin' => 'fab fa-viacoin',
            'fa-medium' => 'fab fa-medium',
            'fa-y-combinator' => 'fab fa-y-combinator',
            'fa-yc' => 'fab fa-y-combinator',
            'fa-optin-monster' => 'fab fa-optin-monster',
            'fa-opencart' => 'fab fa-opencart',
            'fa-expeditedssl' => 'fab fa-expeditedssl',
            'fa-battery-4' => 'fas fa-battery-full',
            'fa-battery' => 'fas fa-battery-full',
            'fa-battery-3' => 'fas fa-battery-three-quarters',
            'fa-battery-2' => 'fas fa-battery-half',
            'fa-battery-1' => 'fas fa-battery-quarter',
            'fa-battery-0' => 'fas fa-battery-empty',
            'fa-object-group' => 'far fa-object-group',
            'fa-object-ungroup' => 'far fa-object-ungroup',
            'fa-sticky-note-o' => 'far fa-note-sticky',
            'fa-cc-jcb' => 'fab fa-cc-jcb',
            'fa-cc-diners-club' => 'fab fa-cc-diners-club',
            'fa-clone' => 'far fa-clone',
            'fa-hourglass-o' => 'fas fa-hourglass-empty',
            'fa-hourglass-1' => 'fas fa-hourglass-start',
            'fa-hourglass-half' => 'fas fa-hourglass',
            'fa-hourglass-2' => 'fas fa-hourglass',
            'fa-hourglass-3' => 'fas fa-hourglass-end',
            'fa-hand-rock-o' => 'far fa-hand-back-fist',
            'fa-hand-grab-o' => 'far fa-hand-back-fist',
            'fa-hand-paper-o' => 'far fa-hand',
            'fa-hand-stop-o' => 'far fa-hand',
            'fa-hand-scissors-o' => 'far fa-hand-scissors',
            'fa-hand-lizard-o' => 'far fa-hand-lizard',
            'fa-hand-spock-o' => 'far fa-hand-spock',
            'fa-hand-pointer-o' => 'far fa-hand-pointer',
            'fa-hand-peace-o' => 'far fa-hand-peace',
            'fa-registered' => 'far fa-registered',
            'fa-creative-commons' => 'fab fa-creative-commons',
            'fa-gg' => 'fab fa-gg',
            'fa-gg-circle' => 'fab fa-gg-circle',
            'fa-odnoklassniki' => 'fab fa-odnoklassniki',
            'fa-odnoklassniki-square' => 'fab fa-odnoklassniki-square',
            'fa-get-pocket' => 'fab fa-get-pocket',
            'fa-wikipedia-w' => 'fab fa-wikipedia-w',
            'fa-safari' => 'fab fa-safari',
            'fa-chrome' => 'fab fa-chrome',
            'fa-firefox' => 'fab fa-firefox',
            'fa-opera' => 'fab fa-opera',
            'fa-internet-explorer' => 'fab fa-internet-explorer',
            'fa-television' => 'fas fa-tv',
            'fa-contao' => 'fab fa-contao',
            'fa-500px' => 'fab fa-500px',
            'fa-amazon' => 'fab fa-amazon',
            'fa-calendar-plus-o' => 'far fa-calendar-plus',
            'fa-calendar-minus-o' => 'far fa-calendar-minus',
            'fa-calendar-times-o' => 'far fa-calendar-xmark',
            'fa-calendar-check-o' => 'far fa-calendar-check',
            'fa-map-o' => 'far fa-map',
            'fa-commenting' => 'fas fa-comment-dots',
            'fa-commenting-o' => 'far fa-comment-dots',
            'fa-houzz' => 'fab fa-houzz',
            'fa-vimeo' => 'fab fa-vimeo-v',
            'fa-black-tie' => 'fab fa-black-tie',
            'fa-fonticons' => 'fab fa-fonticons',
            'fa-reddit-alien' => 'fab fa-reddit-alien',
            'fa-edge' => 'fab fa-edge',
            'fa-credit-card-alt' => 'fas fa-credit-card',
            'fa-codiepie' => 'fab fa-codiepie',
            'fa-modx' => 'fab fa-modx',
            'fa-fort-awesome' => 'fab fa-fort-awesome',
            'fa-usb' => 'fab fa-usb',
            'fa-product-hunt' => 'fab fa-product-hunt',
            'fa-mixcloud' => 'fab fa-mixcloud',
            'fa-scribd' => 'fab fa-scribd',
            'fa-pause-circle-o' => 'far fa-circle-pause',
            'fa-stop-circle-o' => 'far fa-circle-stop',
            'fa-bluetooth' => 'fab fa-bluetooth',
            'fa-bluetooth-b' => 'fab fa-bluetooth-b',
            'fa-gitlab' => 'fab fa-gitlab',
            'fa-wpbeginner' => 'fab fa-wpbeginner',
            'fa-wpforms' => 'fab fa-wpforms',
            'fa-envira' => 'fab fa-envira',
            'fa-wheelchair-alt' => 'fab fa-accessible-icon',
            'fa-question-circle-o' => 'far fa-circle-question',
            'fa-volume-control-phone' => 'fas fa-phone-volume',
            'fa-asl-interpreting' => 'fas fa-hands-asl-interpreting',
            'fa-deafness' => 'fas fa-ear-deaf',
            'fa-hard-of-hearing' => 'fas fa-ear-deaf',
            'fa-glide' => 'fab fa-glide',
            'fa-glide-g' => 'fab fa-glide-g',
            'fa-signing' => 'fas fa-hands',
            'fa-viadeo' => 'fab fa-viadeo',
            'fa-viadeo-square' => 'fab fa-viadeo-square',
            'fa-snapchat' => 'fab fa-snapchat',
            'fa-snapchat-ghost' => 'fab fa-snapchat',
            'fa-snapchat-square' => 'fab fa-snapchat-square',
            'fa-pied-piper' => 'fab fa-pied-piper',
            'fa-first-order' => 'fab fa-first-order',
            'fa-yoast' => 'fab fa-yoast',
            'fa-themeisle' => 'fab fa-themeisle',
            'fa-google-plus-official' => 'fab fa-google-plus',
            'fa-google-plus-circle' => 'fab fa-google-plus',
            'fa-font-awesome' => 'fab fa-font-awesome',
            'fa-fa' => 'fab fa-font-awesome',
            'fa-handshake-o' => 'far fa-handshake',
            'fa-envelope-open-o' => 'far fa-envelope-open',
            'fa-linode' => 'fab fa-linode',
            'fa-address-book-o' => 'far fa-address-book',
            'fa-vcard' => 'fas fa-address-card',
            'fa-address-card-o' => 'far fa-address-card',
            'fa-vcard-o' => 'far fa-address-card',
            'fa-user-circle-o' => 'far fa-circle-user',
            'fa-user-o' => 'far fa-user',
            'fa-id-badge' => 'far fa-id-badge',
            'fa-drivers-license' => 'fas fa-id-card',
            'fa-id-card-o' => 'far fa-id-card',
            'fa-drivers-license-o' => 'far fa-id-card',
            'fa-quora' => 'fab fa-quora',
            'fa-free-code-camp' => 'fab fa-free-code-camp',
            'fa-telegram' => 'fab fa-telegram',
            'fa-thermometer-4' => 'fas fa-temperature-full',
            'fa-thermometer' => 'fas fa-temperature-full',
            'fa-thermometer-3' => 'fas fa-temperature-three-quarters',
            'fa-thermometer-2' => 'fas fa-temperature-half',
            'fa-thermometer-1' => 'fas fa-temperature-quarter',
            'fa-thermometer-0' => 'fas fa-temperature-empty',
            'fa-bathtub' => 'fas fa-bath',
            'fa-s15' => 'fas fa-bath',
            'fa-window-maximize' => 'far fa-window-maximize',
            'fa-window-restore' => 'far fa-window-restore',
            'fa-times-rectangle' => 'fas fa-rectangle-xmark',
            'fa-window-close-o' => 'far fa-rectangle-xmark',
            'fa-times-rectangle-o' => 'far fa-rectangle-xmark',
            'fa-bandcamp' => 'fab fa-bandcamp',
            'fa-grav' => 'fab fa-grav',
            'fa-etsy' => 'fab fa-etsy',
            'fa-imdb' => 'fab fa-imdb',
            'fa-ravelry' => 'fab fa-ravelry',
            'fa-eercast' => 'fab fa-sellcast',
            'fa-snowflake-o' => 'far fa-snowflake',
            'fa-superpowers' => 'fab fa-superpowers',
            'fa-wpexplorer' => 'fab fa-wpexplorer',
            'fa-meetup' => 'fab fa-meetup'
        );

        if (isset($icons[$icontofind])) {
            return $icons[$icontofind];
        } else {
            // Guess.
            return 'fas '.$icontofind;
        }
    }
}
