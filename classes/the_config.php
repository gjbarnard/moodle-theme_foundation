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
 * Needed as theme_config is inflexible in terms of allowing access to parent settings.....
 * But theme_config does not actually store the parent settings at at all in the 'parents'
 * attribute but the settings of the theme itself as they are not loaded from the database
 * by find_theme_config().
 *
 * And we cannot extend it as it has a private constructor, so can only be instantiated
 * from itself and not even an inheriting class.
 *
 * @package    theme_foundation
 * @copyright  2018 G J Barnard.
 * @author     G J Barnard - based upon work by Tim Hunt in theme_config.
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation;

use core\exception\coding_exception;
use core\exception\dml_exception;
use stdClass;

/**
 * Theme configuration.
 */
class the_config {
    /**
     * @var array Theme parents.
     */
    public $parents = null;

    // The following properties are not configurable from theme config.php.

    /**
     * @var string The name of this theme.  Set automatically when this theme is
     * loaded. This can not be set in theme config.php
     */
    public $name = null;

    /**
     * @var string The folder where this themes files are stored. This is set
     * automatically. This can not be set in theme config.php
     */
    public $dir = null;

    /**
     * @var stdClass Theme settings stored in config_plugins table.
     * This can not be set in theme config.php
     */
    public $settings = null;

    /**
     * @var array Layout options (if any) indexed by layout type, ref:
     * https://docs.moodle.org/dev/Themes_overview#The_different_layouts_as_of_21st_April_2013.
     */
    public $layoutoptions = null;

    /**
     * Load the config.php file for a particular theme, and return an instance
     * of this class. (That is, this is a factory method.)
     *
     * @param string $themename the name of the theme.
     * @return theme_config an instance of this class.
     */
    public static function load($themename) {
        if ($config = self::find_theme_config($themename)) {
            return new self($config);
        } else {
            throw new coding_exception('Unable to load the \'' . $themename . '\' theme!');
        }
    }

    /**
     * Private constructor, can be called only from the factory method.
     * @param stdClass $config
     */
    private function __construct($config) {
        $this->settings = $config->settings;
        $this->name = $config->name;
        $this->dir = $config->dir;
        $this->parents = $config->parents;

        foreach ($config->layouts as $key => $value) {
            if (!empty($value['options'])) {
                $this->layoutoptions[$key] = $value['options'];
            } else {
                $this->layoutoptions[$key] = null;
            }
        }
    }

    /**
     * Loads the theme config from config.php file.
     *
     * @param string $themename The name of the theme.
     * @return stdClass The theme configuration.
     */
    private static function find_theme_config($themename) {
        /* We have to use the variable name $THEME (upper case) because that
           is what is used in theme config.php files. */

        $dir = self::find_theme_location($themename);

        $THEME = new stdClass();
        $THEME->name = $themename;
        $THEME->dir = $dir;

        // Note: theme_config does not do this but store the themes settings in the parent and not the parents settings.
        try {
            $settings = \get_config('theme_' . $themename);
        } catch (dml_exception $e) {
            // Most probably moodle tables not created yet.
            $settings = new stdClass();
        }

        $THEME->settings = $settings;

        global $CFG; // Just in case somebody tries to use $CFG in theme config.
        include("$THEME->dir/config.php");

        // Verify the theme configuration is OK.
        if (!is_array($THEME->parents)) {
            // Parents option is mandatory now.
            throw new coding_exception('Theme \'' . $themename . '\' has no \'parents\' array defined in its config.php file.');
        }

        return $THEME;
    }

    /**
     * Finds the theme location and verifies the theme has all needed files
     * and is not obsoleted.
     *
     * @param string $themename
     * @return string full dir path or null if not found
     */
    private static function find_theme_location($themename) {
        global $CFG;

        if (file_exists("$CFG->dirroot/theme/$themename/config.php")) {
            $dir = "$CFG->dirroot/theme/$themename";
        } else if (!empty($CFG->themedir) && file_exists("$CFG->themedir/$themename/config.php")) {
            $dir = "$CFG->themedir/$themename";
        } else {
            throw new coding_exception('Unable to find the \'' . $themename . '\' theme!');
        }

        if (file_exists("$dir/styles.php")) {
            // Legacy theme - needs to be upgraded - upgrade info is displayed on the admin settings page.
            throw new coding_exception('Legacy \'' . $themename . '\' theme needs to be upgraded!');
        }

        return $dir;
    }
}
