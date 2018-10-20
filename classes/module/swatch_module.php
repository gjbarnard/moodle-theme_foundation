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
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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
 */
class swatch_module extends \theme_foundation\module_basement {

    public function get_main_scss_content(\theme_config $theme, $toolbox) {
        $swatch = $toolbox->get_setting('swatch', $theme->name);
        if (empty($swatch)) {
            $swatch = 'default';
        }
        // TODOs: Cope with the theme being in $CFG->themedir.  Serve locally unique fonts in the swatch?
        global $CFG;
        $scss = file_get_contents($CFG->dirroot.'/theme/foundation/classes/module/swatch/'.$swatch.'_variables.scss');
        $scss .= $toolbox->get_core_framework_scss();
        $scss .= file_get_contents($CFG->dirroot.'/theme/foundation/classes/module/swatch/'.$swatch.'_bootswatch.scss');

        return $scss;
    }

    public function add_settings(&$settingspages, $adminfulltree, $toolbox) {

        // Create our own settings page.
        $settingspages['swatch'] = new \admin_settingpage('theme_foundation_swatch', get_string('swatchheading', 'theme_foundation'));
        if ($adminfulltree) {
            $settingspages['swatch']->add(
                new \admin_setting_heading(
                    'theme_foundation_swatchheading',
                    get_string('swatchheadingsub', 'theme_foundation'),
                    format_text(get_string('swatchheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );
        }

        // Swatch.
        $name = 'theme_foundation/swatch';
        $title = get_string('swatch', 'theme_foundation');
        $description = get_string('swatchdesc', 'theme_foundation');
        $choices = array(
            'default' => new \lang_string('default'),
            'cerulean' => 'Cerulean',
            'cyborg' => 'Cyborg',
            'literia' => 'Literia',
            'lumen' => 'Lumen',
            'lux' => 'Lux',
            'materia' => 'Materia',
            'pulse' => 'Pulse',
            'simplex' => 'Simplex',
            'superhero' => 'Superhero',
            'united' => 'United',
            'yeti' => 'Yeti'
        );
        $default = 'default';
        $setting = new \theme_foundation\admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settingspages['swatch']->add($setting);
    }

    public function get_lang_strings($lang, $toolbox) {
        $strings = array();

        // Note: 'en' must be specified.
        if ($lang == 'en') {
            $strings['swatch'] = 'Swatch';
            $strings['swatchdesc'] = 'Choose the swatch for the theme.  Note:  The Google font CDN\'s have been removed due to limitations with the PHP SCSS compiler and I don\'t want to have the complications of updating the privacy too.';

            $strings['swatchheading'] = 'Swatch';
            $strings['swatchheadingsub'] = 'Swatch settings';
            $strings['swatchheadingdesc'] = 'Configure the swatch settings for Foundation here.';
        }

        return $strings;
    }
}
