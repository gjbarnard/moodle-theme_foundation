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
 *               based upon work by Damyon Wiese.
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\output\core\output;

use coding_exception;
use core_component;
use moodle_exception;
use theme_config;

/**
 * Get information about valid locations for mustache templates.
 *
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class mustache_template_finder {
    /**
     * Helper function for getting a list of valid template directories for a specific component.
     *
     * @param string $component The component to search
     * @param string $themename The current theme name
     * @return string[] List of valid directories for templates for this component.
     * Directories are not checked for existence.
     */
    public static function get_template_directories_for_component($component, $themename = '') {
        global $CFG, $PAGE;

        // Default the param.
        if ($themename == '') {
            $themename = $PAGE->theme->name;
        }

        // Clean params for safety.
        $component = clean_param($component, PARAM_COMPONENT);
        $themename = clean_param($themename, PARAM_COMPONENT);

        // Validate the component.
        $dirs = [];
        $partial = ($component == 'partials');
        if (!$partial) { // This allows the theme to put partial templates in a sub-folder.
            $compdirectory = core_component::get_component_directory($component);
            if (!$compdirectory) {
                throw new coding_exception("Component was not valid: " . s($component));
            }
        }

        // Find the parent themes.
        $parents = [];
        if ($themename === $PAGE->theme->name) {
            $parents = $PAGE->theme->parents;
        } else {
            $themeconfig = theme_config::load($themename);
            $parents = $themeconfig->parents;
        }

        // First check the theme.
        $dirs[] = $CFG->dirroot . '/theme/' . $themename . '/templates/' . $component . '/';
        if (isset($CFG->themedir)) {
            $dirs[] = $CFG->themedir . '/' . $themename . '/templates/' . $component . '/';
        }
        /* Now check the parent themes.
           Search each of the parent themes second. */
        foreach ($parents as $parent) {
            $dirs[] = $CFG->dirroot . '/theme/' . $parent . '/templates/' . $component . '/';
            if (isset($CFG->themedir)) {
                $dirs[] = $CFG->themedir . '/' . $parent . '/templates/' . $component . '/';
            }
        }

        if (!$partial) {
            // Now check the Boost theme.  This helps us to process the templates in core_renderer_boost.php.
            $dirs[] = $CFG->dirroot . '/theme/boost/templates/' . $component . '/';

            $dirs[] = $compdirectory . '/templates/';
        }

        return $dirs;
    }

    /**
     * Helper function for getting a filename for a template from the template name.
     *
     * @param string $name - This is the componentname/templatename combined.
     * @param string $themename - This is the current theme name.
     * @return string
     */
    public static function get_template_filepath($name, $themename = '') {
        if (strpos($name, '/') === false) {
            throw new coding_exception('Templates names must be specified as "componentname/templatename"' .
                ' (' . s($name) . ' requested) ');
        }

        [$component, $templatename] = explode('/', $name, 2);
        $component = clean_param($component, PARAM_COMPONENT);

        $dirs = self::get_template_directories_for_component($component, $themename);

        foreach ($dirs as $dir) {
            $candidate = $dir . $templatename . '.mustache';
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        throw new moodle_exception('filenotfound', 'error', '', null, $name);
    }
}
