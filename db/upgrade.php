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

/**
 * Upgrade DB.
 *
 * @param int $oldversion Old version no if any.
 *
 * @return boolean Success.
 */
function xmldb_theme_foundation_upgrade($oldversion = 0) {
    if ($oldversion < 2021051805) {
        // Change in default names.
        $value = get_config('theme_foundation', 'blocksperrow');
        set_config('marketingblocksperrow', $value, 'theme_foundation');

        upgrade_plugin_savepoint(true, 2021051805, 'theme', 'foundation');
    }

    if ($oldversion < 2023042200) {
        // Dropping Font Awesome 5.
        $value = get_config('theme_foundation', 'fav');
        if ($value == 1) {
            set_config('fav', 2, 'theme_foundation'); // Set to own Font Awesome version 6.
        }

        upgrade_plugin_savepoint(true, 2023042200, 'theme', 'foundation');
    }

    if ($oldversion < 2024032801) {
        // Change of name from 'usermenulogouturl' to 'customlogouturl'.
        $value = get_config('theme_foundation', 'usermenulogouturl');
        if (!empty($value)) {
            set_config('customlogouturl', $value, 'theme_foundation');
            unset_config('usermenulogouturl', 'theme_foundation');
        }

        upgrade_plugin_savepoint(true, 2024032801, 'theme', 'foundation');
    }

    // Automatic 'Purge all caches'....
    purge_all_caches();

    return true;
}
