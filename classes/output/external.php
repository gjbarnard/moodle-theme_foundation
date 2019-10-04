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
 * @copyright  &copy; 2019-onwards G J Barnard.  Based upon work by Damyon Wiese and G Thomas.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\output;

class external extends \core\output\external {

    /**
     * Return a mustache template, and all the strings it requires.
     *
     * @param string $component The component that holds the template.
     * @param string $templatename The name of the template.
     * @param string $themename The name of the current theme.
     * @return string the template.
     */
    public static function load_template($component, $template, $themename, $includecomments = false) {
        global $DB, $CFG, $PAGE;

        $params = self::validate_parameters(
            self::load_template_parameters(),
            array(
                'component' => $component,
                'template' => $template,
                'themename' => $themename,
                'includecomments' => $includecomments
            )
        );

        $component = $params['component'];
        $template = $params['template'];
        $themename = $params['themename'];
        $includecomments = $params['includecomments'];

        $templatename = $component . '/' . $template;

        // Will throw exceptions if the template does not exist.  Use our template finder instead of the core one.
        $filename = \theme_foundation\output\core\output\mustache_template_finder::get_template_filepath($templatename, $themename);
        $templatestr = file_get_contents($filename);

        // Remove comments from template.
        if (!$includecomments) {
            $templatestr = self::strip_template_comments($templatestr);
        }

        return $templatestr;
    }

    /**
     * Returns description of load_icon_map() parameters.
     *
     * @return external_function_parameters
     */
    public static function load_fontawesome_icon_map_parameters() {
        return new \external_function_parameters([]);
    }

    /**
     * Return a mapping of icon names to icons.
     *
     * @return array the mapping
     */
    public static function load_fontawesome_icon_map() {
        $instance = \core\output\icon_system::instance('\\theme_foundation\\output\\icon_system_fontawesome');

        $map = $instance->get_icon_name_map();
        $result = [];

        foreach ($map as $from => $to) {
            list($component, $pix) = explode(':', $from);
            $one = [];
            $one['component'] = $component;
            $one['pix'] = $pix;
            $one['to'] = $to;
            $result[] = $one;
        }
        return $result;
    }

    /**
     * Returns description of load_icon_map() result value.
     *
     * @return external_description
     */
    public static function load_fontawesome_icon_map_returns() {
        return new \external_multiple_structure(new \external_single_structure(
            array(
                'component' => new \external_value(PARAM_COMPONENT, 'The component for the icon.'),
                'pix' => new \external_value(PARAM_RAW, 'Value to map the icon from.'),
                'to' => new \external_value(PARAM_RAW, 'Value to map the icon to.')
            )
        ));
    }
}
