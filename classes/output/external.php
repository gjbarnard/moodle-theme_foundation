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
 * @copyright  2019 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\output;

use core\output\external as external_core;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;
use core_external\external_warnings;
use core\external\output\icon_system\load_fontawesome_map;
use core_user;

/**
 * External.
 */
class external extends external_core {
    /**
     * Return a mustache template, and all the strings it requires.
     *
     * @param string $component The component that holds the template.
     * @param string $template The name of the template.
     * @param string $themename The name of the current theme.
     * @param boolean $includecomments Include the comments.
     *
     * @return string the template.
     */
    public static function load_template($component, $template, $themename, $includecomments = false) {
        global $PAGE;

        $PAGE->set_context(\context_system::instance());
        $params = self::validate_parameters(
            self::load_template_parameters(),
            [
                'component' => $component,
                'template' => $template,
                'themename' => $themename,
                'includecomments' => $includecomments,
            ]
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
    public static function load_fontawesome_icon_map_parameters(): external_function_parameters {
        return new external_function_parameters([]);
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
            [$component, $pix] = explode(':', $from);
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
        return load_fontawesome_map::execute_returns();
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function set_user_preferences_parameters() {
        return new external_function_parameters(
            [
                'preferences' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'name' => new external_value(PARAM_RAW, 'The name of the preference'),
                            'value' => new external_value(PARAM_RAW, 'The value of the preference'),
                        ]
                    )
                ),
            ],
        );
    }

    /**
     * Set user preferences.
     *
     * @param array $preferences list of preferences including name, value and userid
     * @return array of warnings and preferences saved
     * @throws moodle_exception
     */
    public static function set_user_preferences($preferences) {
        global $PAGE, $USER;

        $params = self::validate_parameters(self::set_user_preferences_parameters(), ['preferences' => $preferences]);
        $warnings = [];
        $saved = [];

        $context = \context_system::instance();
        $PAGE->set_context($context);

        $userscache = [];
        // Check to which user set the preference.
        if (!empty($userscache[$USER->id])) {
            $user = $userscache[$USER->id];
        } else {
            try {
                $user = core_user::get_user($USER->id, '*', MUST_EXIST);
                core_user::require_active_user($user);
                $userscache[$user->id] = $user;
            } catch (Exception $e) {
                $warnings[] = [
                    'item' => 'user',
                    'itemid' => $USER->id,
                    'warningcode' => 'invaliduser',
                    'message' => $e->getMessage(),
                ];
            }
        }

        foreach ($params['preferences'] as $pref) {
            try {
                // Support legacy preferences from the old M.util.set_user_preference API (always using the current user).
                if (isset($USER->foundation_user_pref[$pref['name']])) {
                    set_user_preference($pref['name'], $pref['value']);
                    $saved[] = [
                        'name' => $pref['name'],
                        'userid' => $USER->id,
                    ];
                } else {
                    $warnings[] = [
                        'item' => 'user',
                        'itemid' => $user->id,
                        'warningcode' => 'nopermission',
                        'message' => 'You are not allowed to change the preference '.s($pref['name']).' for user '.$user->id,
                    ];
                }
            } catch (Exception $e) {
                $warnings[] = [
                    'item' => 'user',
                    'itemid' => $user->id,
                    'warningcode' => 'errorsavingpreference',
                    'message' => $e->getMessage(),
                ];
            }
        }

        $result = [];
        $result['saved'] = $saved;
        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function set_user_preferences_returns() {
        return new external_single_structure(
            [
                'saved' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'name' => new external_value(PARAM_RAW, 'The name of the preference'),
                            'userid' => new external_value(PARAM_INT, 'The user the preference was set for'),
                        ],
                    ), 'Preferences saved'
                ),
                'warnings' => new external_warnings(),
            ]
        );
    }
}
