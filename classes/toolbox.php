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
 * @package    theme
 * @subpackage foundation
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_foundation;

defined('MOODLE_INTERNAL') || die();

class toolbox {

    protected $corerenderer = null;
    protected $themename = '';
    protected $theconfig = null;
    protected static $instance = null;

    // This is a lonely object.
    private function __construct() {
    }

    public static function get_instance() {
        if (!is_object(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_theme_renderer() {
        global $PAGE;
        $themename = $PAGE->theme->name;
        if (empty($this->corerenderer)) {
            $this->corerenderer = $PAGE->get_renderer('theme_'.$themename, 'core');
            $this->themename = $themename;

            // Now is a good time to setup our theme configuration for settings etc.
            $this->theconfig = \theme_foundation\the_config::load($themename);
            //error_log(print_r($this->theconfig, true));
        } else {
            if ($themename != $this->themename) {
                // More of a humm! if this happens.
                \debugging('theme_foundation toolbox::get_theme_renderer() - Different theme \''.$themename.'\' from original \''.$this->themename.'\'.');
            }
        }
        return $this->corerenderer;
    }

    public function get_main_scss_content(\theme_config $theme) {
        global $CFG;

        // TODO: Cope with the theme being in $CFG->themedir.
        $scss = file_get_contents($CFG->dirroot.'/theme/foundation/scss/preset/default_variables.scss');
        $scss .= $this->get_core_framework_scss();
        $scss .= file_get_contents($CFG->dirroot.'/theme/foundation/scss/preset/default_bootswatch.scss');
        $scss .= file_get_contents($CFG->dirroot.'/theme/foundation/scss/theme/theme.scss');

        return $scss;
    }

    protected function get_core_framework_scss() {
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

    public function extra_scss($themename) {
        $scss = '';
        
        $customscss = $this->get_setting('customscss');  // TODO: Does there need to be a parent daisy chain of this setting?
        if (!empty($customscss)) {
            $scss .= $customscss;
        }

        return $scss;
    }

    public function add_settings($admin) {
        $admin->add('themes', new \admin_category('theme_foundation', 'Foundation'));

        // General settings.
        $generalsettings = new \admin_settingpage('theme_foundation_generic', get_string('generalheading', 'theme_foundation'));
        if ($admin->fulltree) {
            $generalsettings->add(new \admin_setting_heading('theme_foundation_generalheading',
                get_string('generalheadingsub', 'theme_foundation'),
                format_text(get_string('generalheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)));

            // Custom SCSS.
            $name = 'theme_foundation/customscss';
            $title = get_string('customscss', 'theme_foundation');
            $description = get_string('customscssdesc', 'theme_foundation');
            $default = '';
            $setting = new \admin_setting_configtextarea($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $generalsettings->add($setting);
        }
        $admin->add('theme_foundation', $generalsettings);
    }

    public function get_en_strings() {
        $strings = array();

        $strings['customscss'] = 'Custom SCSS';
        $strings['customscssdesc'] = 'Add custom SCSS to the theme.';

        return $strings;
    }
    
    public function get_setting($settingname) {
        $settingvalue = false;
        
        // We need to work on 'properties' so that empty values can be used.
        if (property_exists($this->theconfig->settings, $settingname)) {
            $settingvalue = $this->theconfig->settings->$settingname;            
        } else {
            /* Look in the parents.
               Parents will be in the correct order of the hierarchy as defined in $THEME->parents in config.php. */
            foreach ($this->theconfig->parents as $parent) {
                if (property_exists($parent->settings, $settingname)) {
                    $settingvalue = $parent->settings->$settingname;
                    break;
                }
            }
        }
        error_log($settingname.' - '.$settingvalue);
        return $settingvalue;
    }
}
