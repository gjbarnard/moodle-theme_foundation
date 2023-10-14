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
 * @author     G J Barnard - based upon work by Tim Hunt in theme_config.
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation;

/**
 * Abstract 'basement' class that all theme modules should extend.
 */
abstract class module_basement {
    /**
     * Gets the module pre SCSS.
     *
     * @param string $themename The theme name the SCSS is for.
     * @param toolbox $toolbox The toolbox instance.
     * @return string SCSS.
     */
    public function pre_scss($themename, $toolbox) {
        return '';
    }

    /**
     * Gets the module main SCSS.
     *
     * @param theme_config $theme The theme configuration object for the theme the SCSS is for.
     * @param toolbox $toolbox The toolbox instance.
     * @return string SCSS.
     */
    public function get_main_scss_content(\theme_config $theme, $toolbox) {
        return '';
    }

    /**
     * Gets the module extra SCSS.
     *
     * @param string $themename The theme name the SCSS is for.
     * @param toolbox $toolbox The toolbox instance.
     * @return string SCSS.
     */
    public function extra_scss($themename, $toolbox) {
        return '';
    }

    /**
     * Gets the module bodyclasses.
     *
     * @return array bodyclass strings.
     */
    public function body_classes() {
        return [];
    }

    /**
     * Add the module settings to the theme.
     *
     * @param array $settingspages Reference to the settings pages array so that a module can add a new page to it.
     * @param toolbox $toolbox The toolbox instance.
     */
    public function add_settings(&$settingspages, $toolbox) {
    }

    /**
     * Gets the language strings for the given language code.
     * See 'What do codes like "en" and "en_us" or "es" and "es_mx" and "es_ve" mean??' on:
     * https://docs.moodle.org/35/en/Language_FAQ
     *
     * Note: Do not use at the moment due to https://docs.moodle.org/dev/Plugin_contribution_checklist#Strings but instead when
     *       adding a new module, put them in the language file for the theme.
     *
     * @param string $lang The language code to get.
     * @param toolbox $toolbox The toolbox instance.
     * @return array Array of strings for the module.
     */
    public function get_lang_strings($lang, $toolbox) {
        return [];
    }
}
