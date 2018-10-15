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
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195} - based upon work by Tim Hunt in theme_config.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_foundation;

defined('MOODLE_INTERNAL') || die();

use stdClass;

/**
 * Needed as theme_config is inflexible in terms of allowing access to parent settings.....
 * But theme_config does not actually store the parent settings at at all in the 'parents'
 * attribute but the settings of the theme itself as they are not loaded from the database
 * by find_theme_config().
 *
 * And we cannot extend it as it has a private constructor, so can only be instantiated
 * from itself and not even an inheriting class.
 */
class the_config {

    public $parents;

    //==Following properties are not configurable from theme config.php==

    /**
     * @var string The name of this theme. Set automatically when this theme is
     * loaded. This can not be set in theme config.php
     */
    public $name;

    /**
     * @var string The folder where this themes files are stored. This is set
     * automatically. This can not be set in theme config.php
     */
    public $dir;

    /**
     * @var stdClass Theme settings stored in config_plugins table.
     * This can not be set in theme config.php
     */
    public $settings = null;

    /**
     * Load the config.php file for a particular theme, and return an instance
     * of this class. (That is, this is a factory method.)
     *
     * @param string $themename the name of the theme.
     * @return theme_config an instance of this class.
     */
    public static function load($themename) {
        global $CFG;

        // Load theme settings from db.
        try {
            $settings = \get_config('theme_'.$themename);
        } catch (\dml_exception $e) {
            // most probably moodle tables not created yet
            $settings = new stdClass();
        }

        if ($config = self::find_theme_config($themename, $settings)) {
            return new self($config);

        } else if ($themename == \theme_config::DEFAULT_THEME) {
            throw new \coding_exception('Default theme '.\theme_config::DEFAULT_THEME.' not available or broken!');

        } else if ($config = self::find_theme_config($CFG->theme, $settings)) {
            debugging('This page should be using theme ' . $themename .
                    ' which cannot be initialised. Falling back to the site theme '.$CFG->theme, DEBUG_NORMAL);
            return new self($config);

        } else {
            // bad luck, the requested theme has some problems - admin see details in theme config
            debugging('This page should be using theme ' . $themename .
                    ' which cannot be initialised. Nor can the site theme ' . $CFG->theme .
                    '. Falling back to '.\theme_config::DEFAULT_THEME, DEBUG_NORMAL);
            return new self(self::find_theme_config(\theme_config::DEFAULT_THEME, $settings));
        }
    }

    /**
     * Private constructor, can be called only from the factory method.
     * @param stdClass $config
     */
    private function __construct($config) {
        global $CFG; //needed for included lib.php files

        $this->settings = $config->settings;
        $this->name     = $config->name;
        $this->dir      = $config->dir;

        $configurable = array('parents');

        foreach ($config as $key => $value) {
            if (in_array($key, $configurable)) {
                $this->$key = $value;
            }
        }

        // Verify all parents and load configs.
        foreach ($this->parents as $parent) {
            if (!$parent_config = self::find_theme_config($parent)) {
                // This is not good - better exclude faulty parents.
                continue;
            }
            $this->parent_configs[$parent] = $parent_config;
        }
    }

    /**
     * Loads the theme config from config.php file.
     *
     * @param string $themename
     * @param stdClass $settings from config_plugins table
     * @param boolean $parentscheck true to also check the parents.    .
     * @return stdClass The theme configuration
     */
    private static function find_theme_config($themename, $parentscheck = true) {
        /* We have to use the variable name $THEME (upper case) because that
           is what is used in theme config.php files. */

        if (!$dir = self::find_theme_location($themename)) {
            return null;
        }

        $THEME = new stdClass();
        $THEME->name = $themename;
        $THEME->dir = $dir;

        // Note: theme_config does not do this but store the themes settings in the parent and not the parents settings.
        try {
            $settings = \get_config('theme_'.$themename);
        } catch (\dml_exception $e) {
            // Most probably moodle tables not created yet.
            $settings = new \stdClass();
        }

        $THEME->settings = $settings;

        global $CFG; // Just in case somebody tries to use $CFG in theme config.
        include("$THEME->dir/config.php");

        // Verify the theme configuration is OK.
        if (!is_array($THEME->parents)) {
            // Parents option is mandatory now.
            return null;
        } else {
            /* We use $parentscheck to only check the direct parents (avoid infinite loop).  Really?  Not sure why with an
               inheritance structure.  So really could be 'parent' then that 'parent' could refer to its 'parent' but as 'parents'
               then it is conceptually possible for a theme to define the wrong grand-parent and break / get a different
               ancestry than intended by the author of the 'parent'. */
            if ($parentscheck) {
                // Find all parent theme configs.
                foreach ($THEME->parents as $parent) {
                    // Load theme settings from db.
                    $parentconfig = theme_config::find_theme_config($parent, false);
                    if (empty($parentconfig)) {
                        return null;
                    }
                }
            }
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

        } else if (!empty($CFG->themedir) and file_exists("$CFG->themedir/$themename/config.php")) {
            $dir = "$CFG->themedir/$themename";

        } else {
            return null;
        }

        if (file_exists("$dir/styles.php")) {
            // Legacy theme - needs to be upgraded - upgrade info is displayed on the admin settings page.
            return null;
        }

        return $dir;
    }
}
