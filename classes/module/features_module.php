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
 * @copyright  &copy; 2019-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\module;

defined('MOODLE_INTERNAL') || die();

/**
 * Features module.
 *
 * Implements the features of the theme.
 *
 * @copyright  &copy; 2019-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class features_module extends \theme_foundation\module_basement {

    /**
     * Add the features settings.
     *
     * @param array $settingspages The setting pages.
     * @param boolean $adminfulltree If the full tree is required.
     * @param toolbox $toolbox The theme toolbox.
     */
    public function add_settings(&$settingspages, $adminfulltree, $toolbox) {
        // Create our own settings page.
        $settingspages['features'] = array(\theme_foundation\toolbox::SETTINGPAGE => new \admin_settingpage('theme_foundation_features',
            get_string('featuresheading', 'theme_foundation')), \theme_foundation\toolbox::SETTINGCOUNT => 3);
        if ($adminfulltree) {
            $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add(
                new \admin_setting_heading(
                    'theme_foundation_featuresheading',
                    get_string('featuresheadingsub', 'theme_foundation'),
                    format_text(get_string('featuresheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );

            // Login background image.
            $name = 'theme_foundation/loginbackground';
            $title = get_string('loginbackground', 'theme_foundation');
            $description = get_string('loginbackgrounddesc', 'theme_foundation');
            $setting = new \admin_setting_configstoredfile($name, $title, $description, 'loginbackground');
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            // Login background style.
            $name = 'theme_foundation/loginbackgroundstyle';
            $title = get_string('loginbackgroundstyle', 'theme_foundation');
            $description = get_string('loginbackgroundstyledesc', 'theme_foundation');
            $default = 'cover';
            $setting = new \foundation_admin_setting_configselect($name, $title, $description, $default,
                array(
                    'cover' => get_string('stylecover', 'theme_foundation'),
                    'stretch' => get_string('stylestretch', 'theme_foundation')
                )
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            $opactitychoices = array(
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

            // Overridden course title text background opacity setting.
            $name = 'theme_foundation/loginbackgroundopacity';
            $title = get_string('loginbackgroundopacity', 'theme_foundation');
            $description = get_string('loginbackgroundopacitydesc', 'theme_foundation');
            $default = '0.8';
            $setting = new \foundation_admin_setting_configselect($name, $title, $description, $default, $opactitychoices);
            $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);
        }
    }

    /**
     * Gets the module extra SCSS.
     *
     * @param string $themename The theme name the SCSS is for.
     * @param toolbox $toolbox The toolbox instance.
     * @return string SCSS.
     */
    public function extra_scss($themename, $toolbox) {
        $scss = '';

        $loginbackgroundurl = $toolbox->setting_file_url('loginbackground', 'loginbackground', $themename);

        if (!empty($loginbackgroundurl)) {
            $scss .= 'body.loginbackground {'.PHP_EOL;
            $scss .= 'background-image: url("'.$loginbackgroundurl.'");'.PHP_EOL;

            $loginbackgroundstyle = $toolbox->get_setting('loginbackgroundstyle', $themename);
            $replacementstyle = 'cover';
            if ($loginbackgroundstyle === 'stretch') {
                $replacementstyle = '100% 100%';
            }
            $scss .= 'background-size: '.$replacementstyle.';'.PHP_EOL;
            $scss .= '#page-content,'.PHP_EOL;
            $scss .= '#page-footer {'.PHP_EOL;
            $loginbackgroundopacity = $toolbox->get_setting('loginbackgroundopacity', $themename);
            $scss .= 'opacity: '.$loginbackgroundopacity.';'.PHP_EOL;
            $scss .= '}'.PHP_EOL;
            $scss .= '}'.PHP_EOL;
        }

        return $scss;
    }

    /**
     * Gets the module bodyclasses.
     *
     * @return array bodyclass strings.
     */
    public function body_classes() {
        global $PAGE;
        $bodyclasses = array();

        if ($PAGE->pagelayout == 'login') {
            $bodyclasses[] = 'loginbackground';
        }

        return $bodyclasses;
    }
}
