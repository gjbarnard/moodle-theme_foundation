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

defined('MOODLE_INTERNAL') || die();

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_foundation_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    static $theme = null;
    if (empty($theme)) {
        $theme = theme_config::load('foundation');
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        // By default, theme files must be cache-able by both browsers and proxies.  From 'More' theme.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        if ($filearea === 'logo') {
            return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}

/**
 * Gets the pre SCSS for the theme.
 *
 * @param theme_config $theme The theme configuration object.
 * @return string SCSS.
 */
function theme_foundation_pre_scss($theme) {
    $toolbox = \theme_foundation\toolbox::get_instance();
    return $toolbox->pre_scss('foundation');
}

/**
 * Gets the extra SCSS for the theme.
 *
 * @param theme_config $theme The theme configuration object.
 * @return string SCSS.
 */
function theme_foundation_extra_scss($theme) {
    $toolbox = \theme_foundation\toolbox::get_instance();
    return $toolbox->extra_scss('foundation');
}

/**
 * Get the compiled css.
 *
 * @return string The compiled css.
 */
function theme_foundation_get_precompiled_css() {
    global $CFG;
    return file_get_contents($CFG->dirroot.'/theme/foundation/style/fallback.css');
}

/**
 * Override the core_output_load_template function to use our Mustache template finder.
 *
 * Info on: https://docs.moodle.org/dev/Miscellaneous_callbacks#override_webservice_execution
 */
function theme_foundation_override_webservice_execution($function, $params) {
    // Check if it's the function we want to override.
    if ($function->name === 'core_output_load_template') {
        // Call our load template function in our class instead of $function->classname.
        return call_user_func_array(['theme_foundation\output\external', $function->methodname], $params);
    }

    return false;
}
