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
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\module;

defined('MOODLE_INTERNAL') || die();

/**
 * Swatch module.
 *
 * Implements the ability to change swatches.
 *
 * Swatches from https://bootswatch.com/ and licensed under the MIT Licence:
 * https://github.com/thomaspark/bootswatch/blob/master/LICENSE.
 *
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class swatch_module extends \theme_foundation\module_basement {

    /**
     * @var string $swatchcustomcolourdefaults SWatch custom colour defaults.
     */
    private static $swatchcustomcolourdefaults = array(
        'primary' => '#ffaabb',
        'secondary' => '#6c757d'
    );

    /**
     * Helper method.
     *
     * @param string $settingname Setting name.
     *
     * @return string Setting default.
     */
    private function get_custom_swatch_default_colour_setting($settingname) {
        return self::$swatchcustomcolourdefaults[$settingname];
    }

    /**
     * Gets the module pre SCSS.
     *
     * @param string $themename The theme name the SCSS is for.
     * @param toolbox $toolbox The toolbox instance.
     *
     * @return string SCSS.
     */
    public function pre_scss($themename, $toolbox) {
        $prescss = '';
        if ($toolbox->get_setting('swatchcustom', $themename)) {
            $prescss = '$primary: '.
                $this->get_custom_swatch_setting('primary', $themename, $toolbox).';';
        }

        return $prescss;
    }

    /**
     * Helper method.
     *
     * @param string $settingname Setting name.
     * @param string $themename The theme name the SCSS is for.
     * @param toolbox $toolbox The toolbox instance.
     *
     * @return string Setting value or default if empty.
     */
    private function get_custom_swatch_setting($settingname, $themename, $toolbox) {
        $settingfullname = 'swatchcustom'.$settingname.'colour';
        $settingvalue = $toolbox->get_setting($settingfullname, $themename);
        if (empty($settingvalue)) {
            $settingvalue = $this->get_custom_swatch_default_colour_setting($settingname);
        }
        return $settingvalue;
    }

    /**
     * Gets the swatch SCSS.
     *
     * @param theme_config $theme Theme configuration.
     * @param toolbox $toolbox The theme toolbox.
     * @return string SCSS.
     */
    public function get_main_scss_content(\theme_config $theme, $toolbox) {
        $swatch = $toolbox->get_setting('swatch', $theme->name);
        if (empty($swatch)) {
            $swatch = 'default';
        }
        // TODOs: Cope with the theme being in $CFG->themedir.
        global $CFG;
        $scss = file_get_contents($CFG->dirroot.'/theme/foundation/scss/foundation_variables.scss');
        $scss .= file_get_contents($CFG->dirroot.'/theme/foundation/classes/module/swatch/'.$swatch.'_variables.scss');
        $scss .= $toolbox->get_core_framework_scss();
        $scss .= file_get_contents($CFG->dirroot.'/theme/foundation/classes/module/swatch/'.$swatch.'_bootswatch.scss');

        return $scss;
    }

    /**
     * Add the swatch settings.
     *
     * @param array $settingspages The setting pages.
     * @param toolbox $toolbox The theme toolbox.
     */
    public function add_settings(&$settingspages, $toolbox) {
        // Create our own settings page.
        $swatchsettings = new \admin_settingpage('theme_foundation_swatch', get_string('swatchheading', 'theme_foundation'));
        $settingspages['swatch'] = array(
            \theme_foundation\toolbox::SETTINGPAGE => $swatchsettings,
            \theme_foundation\toolbox::HASSETTINGS => true
        );

        $swatchsettings->add(
            new \admin_setting_heading(
                'theme_foundation_swatchheading',
                get_string('swatchheadingsub', 'theme_foundation'),
                format_text(get_string('swatchheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );

        // Swatch.
        $name = 'theme_foundation/swatch';
        $title = get_string('swatch', 'theme_foundation');
        $description = get_string('swatchdesc', 'theme_foundation');
        $choices = array(
            'default' => new \lang_string('default'),
            'cerulean' => 'Cerulean',
            'cosmo' => 'Cosmo',
            'cyborg' => 'Cyborg',
            'darkly' => 'Darkly',
            'flatly' => 'Flatly',
            'journal' => 'Journal',
            'literia' => 'Literia',
            'lumen' => 'Lumen',
            'lux' => 'Lux',
            'materia' => 'Materia',
            'minty' => 'Minty',
            'pulse' => 'Pulse',
            'sandstone' => 'Sandstone',
            'seventies' => 'Seventies',
            'slate' => 'Slate',
            'simplex' => 'Simplex',
            'sketchy' => 'Sketchy',
            'solar' => 'Solar',
            'spacelab' => 'Spacelab',
            'superhero' => 'Superhero',
            'united' => 'United',
            'yeti' => 'Yeti'
        );
        $default = 'default';
        $setting = new \theme_foundation\admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $swatchsettings->add($setting);

        // Custom swatch settings.
        $name = 'theme_foundation/swatchcustom';
        $title = get_string('swatchcustom', 'theme_foundation');
        $description = get_string('swatchcustomdesc', 'theme_foundation');
        $default = false;
        $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');  // Will alter the 'prescss'.
        $swatchsettings->add($setting);

        if ($toolbox->get_setting('swatchcustom', 'foundation')) {
            $this->add_custom_settings($settingspages);
        }
    }

    /**
     * Add the custom swatch settings.
     *
     * @param array $settingspages The setting pages.
     */
    private function add_custom_settings(&$settingspages) {
        $custompage = new \admin_settingpage('theme_foundation_swatchcustom', get_string('swatchcustomheading', 'theme_foundation'));
        $settingspages['swatchcustom'] = array(
            \theme_foundation\toolbox::SETTINGPAGE => $custompage,
            \theme_foundation\toolbox::HASSETTINGS => true
        );

        $custompage->add(
            new \admin_setting_heading(
                'theme_foundation_customswatchheading',
                get_string('swatchcustomheadingsub', 'theme_foundation'),
                format_text(get_string('swatchcustomheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );

        // Primary colour.
        /*$name = 'theme_foundation/swatchcustomprimarycolour';
        $title = get_string('swatchcustomprimarycolour', 'theme_foundation');
        $description = get_string('swatchcustomprimarycolourdesc', 'theme_foundation');
        $previewconfig = null;
        $setting = new \admin_setting_configcolourpicker($name, $title, $description, '#ffaabb', $previewconfig);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $custompage->add($setting);*/

        $custompage->add($this->create_custom_swatch_colour_setting('primary'));
        $custompage->add($this->create_custom_swatch_colour_setting('secondary'));

        /*
        $success:       $green !default;
$info:          $cyan !default;
$warning:       $yellow !default;
$danger:        $red !default;
$light:         $gray-100 !default;
$dark:          $gray-800 !default;
*/
    }

    /**
     * Helper method.
     *
     * @param string $settingname Setting name.
     *
     * @return object Admin setting instance.
     */
    private function create_custom_swatch_colour_setting($settingname) {
        $settingfullname = 'swatchcustom'.$settingname.'colour';
        $name = 'theme_foundation/'.$settingfullname;
        $title = get_string($settingfullname, 'theme_foundation');
        $description = get_string($settingfullname.'desc', 'theme_foundation');
        $previewconfig = null;
        $setting = new \admin_setting_configcolourpicker(
            $name,
            $title,
            $description, 
            $this->get_custom_swatch_default_colour_setting($settingname),
            $previewconfig
        );
        $setting->set_updatedcallback('theme_reset_all_caches');

        return $setting;
    }

    /**
     * Returns the language strings for the swatch.
     *
     * Note: Not currently called due to https://docs.moodle.org/dev/Plugin_contribution_checklist#Strings
     *
     * @param string $lang Language code to get.
     * @param toolbox $toolbox The theme toolbox.
     * @return array Language strings.
     */
    public function get_lang_strings($lang, $toolbox) {
        $strings = array();

        // Note: 'en' must be specified.
        if ($lang == 'en') {
            $strings['swatch'] = 'Swatch';
            $strings['swatchdesc'] = 'Choose the swatch for the theme.  A \'Swatch\' is a way of changing the look of the theme '.
                'using a preset list of definitions that you attach a name to.  All swatches are from \'Bootswatch.com\' and '.
                'licensed under the \'MIT License\'.  Note:  The Google font CDN\'s have been removed due to limitations with '.
                'the PHP SCSS compiler and I don\'t want to have the complications of updating the privacy too.';

            $strings['swatchheading'] = 'Swatch';
            $strings['swatchheadingsub'] = 'Swatch settings';
            $strings['swatchheadingdesc'] = 'Configure the swatch settings for Foundation here.';
        }

        return $strings;
    }
}
