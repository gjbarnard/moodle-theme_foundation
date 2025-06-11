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
 * @copyright  2018 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\module;

/**
 * Swatch module.
 *
 * Implements the ability to change swatches.
 *
 * Swatches from https://bootswatch.com/ and licensed under the MIT Licence:
 * https://github.com/thomaspark/bootswatch/blob/master/LICENSE.
 */
class swatch_module extends \theme_foundation\module_basement {
    /**
     * @var string $swatchcustomcolourdefaults Swatch custom colour defaults.
     */
    private static $swatchcustomcolourdefaults = [
        'primary' => ['colour' => '#ffaabb', 'selector' => '.foundation-default-primary-colour', 'element' => 'color'],
        'secondary' => ['colour' => '#82b6fc', 'selector' => '.foundation-default-secondary-colour', 'element' => 'color'],
        'success' => ['colour' => '#28a745', 'selector' => '.foundation-default-success-colour', 'element' => 'color'],
        'info' => ['colour' => '#17a2b8', 'selector' => '.foundation-default-info-colour', 'element' => 'color'],
        'warning' => ['colour' => '#ffc107', 'selector' => '.foundation-default-warning-colour', 'element' => 'color'],
        'danger' => ['colour' => '#dc3545', 'selector' => '.foundation-default-danger-colour', 'element' => 'color'],
        'light' => ['colour' => '#fab2fd', 'selector' => '.foundation-default-light-colour', 'element' => 'color'],
        'dark' => ['colour' => '#3c6afb', 'selector' => '.foundation-default-dark-colour', 'element' => 'color'],
        'body-bg' => ['colour' => '#fffa0f', 'selector' => '.foundation-default-body-bg-colour', 'element' => 'color'],
        'body-color' => ['colour' => '#03b40a', 'selector' => '.foundation-default-body-color-colour', 'element' => 'color'],
        'component-active-color' =>
            ['colour' => '#bbfc70', 'selector' => '.foundation-default-component-active-color-colour', 'element' => 'color'],
        'component-active-bg' =>
            ['colour' => '#f475fc', 'selector' => '.foundation-default-component-active-bg-colour', 'element' => 'color'],
        'headings-color' =>
            ['colour' => '#ffca8e', 'selector' => '.foundation-default-headings-color-colour', 'element' => 'color'],
        'text-muted' => ['colour' => '#015a22', 'selector' => '.foundation-default-text-muted-colour', 'element' => 'color'],
        'card-color' => ['colour' => '#ffaabb', 'selector' => '.foundation-default-card-color-colour', 'element' => 'color'],
        'card-bg' => ['colour' => '#060064', 'selector' => '.foundation-default-card-bg-colour', 'element' => 'color'],
    ];

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

        if ($toolbox->get_setting('swatchcustomcolours', $themename)) {
            foreach (array_keys(self::$swatchcustomcolourdefaults) as $settingkey) {
                $settingvalue = $this->get_custom_swatch_setting($settingkey, $themename, $toolbox);
                if (!empty($settingvalue)) {
                    $prescss .= '$' . $settingkey . ': ' . $settingvalue . ';' . PHP_EOL;
                }
            }
        }

        if ($toolbox->get_setting('swatchcustomtypography', $themename)) {
            $fontsizebase = $toolbox->get_setting('swatchcustomfontsizebase', $themename);
            if (empty($fontsizebase)) {
                $fontsizebase = '1';
            }
            $prescss .= '$font-size-base: ' . $fontsizebase . 'rem;' . PHP_EOL;

            $lineheightbase = $toolbox->get_setting('swatchcustomlineheightbase', $themename);
            if (empty($lineheightbase)) {
                $lineheightbase = '1.5';
            }
            $prescss .= '$line-height-base: ' . $lineheightbase . ';' . PHP_EOL;
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
        $settingfullname = 'swatchcustom' . str_replace('-', '', $settingname) . 'colour';
        $settingvalue = $toolbox->get_setting($settingfullname, $themename);
        if ((!empty($settingvalue)) && ($settingvalue[0] == '-')) {
            $settingvalue = '';
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
        // Todos: Cope with the theme being in $CFG->themedir.
        global $CFG;
        $scss = file_get_contents($CFG->dirroot . '/theme/foundation/classes/module/swatch/' . $swatch . '_variables.scss');
        $scss .= $toolbox->get_core_framework_scss();
        $scss .= file_get_contents($CFG->dirroot . '/theme/foundation/classes/module/swatch/' . $swatch . '_bootswatch.scss');

        return $scss;
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
        foreach (array_keys(self::$swatchcustomcolourdefaults) as $settingkey) {
            $scss .= '.foundation-default-' . $settingkey . '-colour {' . PHP_EOL;
            $scss .= 'color: $' . $settingkey . ';' . PHP_EOL;
            $scss .= '}' . PHP_EOL;
        }
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
        $settingspages['swatch'] = [
            \theme_foundation\toolbox::SETTINGPAGE => $swatchsettings,
            \theme_foundation\toolbox::HASSETTINGS => true,
        ];

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
        $choices = [
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
            'morph' => 'Morph',
            'pulse' => 'Pulse',
            'quartz' => 'Quartz',
            'sandstone' => 'Sandstone',
            'seventies' => 'Seventies',
            'simplex' => 'Simplex',
            'sketchy' => 'Sketchy',
            'slate' => 'Slate',
            'solar' => 'Solar',
            'spacelab' => 'Spacelab',
            'superhero' => 'Superhero',
            'united' => 'United',
            'vapor' => 'Vapor',
            'yeti' => 'Yeti',
            'zephyr' => 'Zephyr',
        ];
        $default = 'default';
        $setting = new \theme_foundation\admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $swatchsettings->add($setting);

        // Custom swatch colour settings.
        $name = 'theme_foundation/swatchcustomcolours';
        $title = get_string('swatchcustomcolours', 'theme_foundation');
        $description = get_string('swatchcustomcoloursdesc', 'theme_foundation');
        $default = false;
        $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');  // Will alter the 'prescss'.
        $swatchsettings->add($setting);

        // Custom swatch typography settings.
        $name = 'theme_foundation/swatchcustomtypography';
        $title = get_string('swatchcustomtypography', 'theme_foundation');
        $description = get_string('swatchcustomtypographydesc', 'theme_foundation');
        $default = false;
        $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');  // Will alter the 'prescss'.
        $swatchsettings->add($setting);

        $this->add_custom_settings($settingspages, $toolbox);
    }

    /**
     * Add the custom swatch settings.
     *
     * @param array $settingspages The setting pages.
     * @param toolbox $toolbox The theme toolbox.
     */
    private function add_custom_settings(&$settingspages, $toolbox) {
        if ($toolbox->get_setting('swatchcustomcolours', 'foundation')) {
            $custompage = new \admin_settingpage(
                'theme_foundation_swatchcustomcolours',
                get_string('swatchcustomcoloursheading', 'theme_foundation')
            );
            $settingspages['swatchcustomcolours'] = [
                \theme_foundation\toolbox::SETTINGPAGE => $custompage,
                \theme_foundation\toolbox::HASSETTINGS => true,
            ];

            $custompage->add(
                new \admin_setting_heading(
                    'theme_foundation_swatchcustomcoloursheading',
                    get_string('swatchcustomcoloursheadingsub', 'theme_foundation'),
                    format_text(get_string('swatchcustomcoloursheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );

            foreach (array_keys(self::$swatchcustomcolourdefaults) as $settingkey) {
                $custompage->add($this->create_custom_swatch_colour_setting($settingkey));
            }
        }

        if ($toolbox->get_setting('swatchcustomtypography', 'foundation')) {
            $custompage = new \admin_settingpage(
                'theme_foundation_swatchcustomtypography',
                get_string('swatchcustomtypographyheading', 'theme_foundation')
            );
            $settingspages['swatchcustomtypography'] = [
                \theme_foundation\toolbox::SETTINGPAGE => $custompage,
                \theme_foundation\toolbox::HASSETTINGS => true,
            ];

            $custompage->add(
                new \admin_setting_heading(
                    'theme_foundation_swatchcustomtypographyheading',
                    get_string('swatchcustomtypographyheadingsub', 'theme_foundation'),
                    format_text(get_string('swatchcustomtypographyheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );

            // Font size base.
            $name = 'theme_foundation/swatchcustomfontsizebase';
            $title = get_string('swatchcustomfontsizebase', 'theme_foundation');
            $description = get_string('swatchcustomfontsizebasedesc', 'theme_foundation');
            $default = '1';
            $setting = new \admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $custompage->add($setting);

            // Line height base.
            $name = 'theme_foundation/swatchcustomlineheightbase';
            $title = get_string('swatchcustomlineheightbase', 'theme_foundation');
            $description = get_string('swatchcustomlineheightbasedesc', 'theme_foundation');
            $default = '1.5';
            $setting = new \admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $custompage->add($setting);
        }
    }

    /**
     * Helper method.
     *
     * @param string $settingname Setting name.
     *
     * @return object Admin setting instance.
     */
    private function create_custom_swatch_colour_setting($settingname) {
        $name = 'theme_foundation/swatchcustom' . str_replace('-', '', $settingname) . 'colour';
        $title = get_string('swatchcustomcolour', 'theme_foundation', ucfirst($settingname));
        $description = get_string('swatchcustomcolourdesc', 'theme_foundation', $settingname);
        $default = self::$swatchcustomcolourdefaults[$settingname];
        $setting = new \theme_foundation\admin_setting_configcolourpicker(
            $name,
            $title,
            $description,
            $default['colour'],
            $default,
            'foundation-default-' . $settingname . '-colour'
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
        $strings = [];

        // Note: 'en' must be specified.
        if ($lang == 'en') {
            $strings['swatch'] = 'Swatch';
            $strings['swatchdesc'] = 'Choose the swatch for the theme.  A \'Swatch\' is a way of changing the look of the theme ' .
                'using a preset list of definitions that you attach a name to.  All swatches are from \'Bootswatch.com\' and ' .
                'licensed under the \'MIT License\'.  Note:  The Google font CDN\'s have been removed due to limitations with ' .
                'the PHP SCSS compiler and I don\'t want to have the complications of updating the privacy too.';

            $strings['swatchheading'] = 'Swatch';
            $strings['swatchheadingsub'] = 'Swatch settings';
            $strings['swatchheadingdesc'] = 'Configure the swatch settings for Foundation here.';
        }

        return $strings;
    }
}
