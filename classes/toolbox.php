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
     * Module setting page index.
     */
    const SETTINGPAGE = 'p';

    /**
     * Module setting page count.
     */
    const SETTINGCOUNT = 'c';

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
                self::SETTINGCOUNT => 1),
            'module' => array(
                self::SETTINGPAGE => new \admin_settingpage('theme_foundation_module',
                    get_string('moduleheading', 'theme_foundation')),
                self::SETTINGCOUNT => 0)
        );

        // General settings.
        if ($admin->fulltree) {
            $settingspages['general'][self::SETTINGPAGE]->add(
                new \admin_setting_heading(
                    'theme_foundation_generalheading',
                    get_string('generalheadingsub', 'theme_foundation'),
                    format_text(get_string('generalheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );

            // Custom SCSS.
            $name = 'theme_foundation/customscss';
            $title = get_string('customscss', 'theme_foundation');
            $description = get_string('customscssdesc', 'theme_foundation');
            $default = '';
            $setting = new \admin_setting_configtextarea($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settingspages['general'][self::SETTINGPAGE]->add($setting);
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
            if ($settingspage[self::SETTINGCOUNT] > 0) {
                $thepage = $settingspage[self::SETTINGPAGE];
                $admin->add('theme_foundation', $thepage);
            }
        }
    }

    /**
     * Returns the strings from the modules.
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
            $settingvalue = $current->settings->$settingname;
        } else {
            /* Look in the parents.
               Parents will be in the correct order of the hierarchy as defined in $THEME->parents in config.php. */
            $current = prev($this->theconfigs);
            while ($current !== false) {
                if (property_exists($current->settings, $settingname)) {
                    $settingvalue = $current->settings->$settingname;
                    break;
                }
                $current = prev($this->theconfigs);
            }
        }
        return $settingvalue;
    }
}
