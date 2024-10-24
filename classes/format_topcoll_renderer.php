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

defined('MOODLE_INTERNAL') || die();

/**
 * Foundation theme Collapsed Topics trait.
 */
trait theme_foundation_format_topcoll_renderer_trait {
    /**
     * The grid row class.
     *
     * @return string CSS class.
     */
    protected function get_row_class() {
        return 'row';
    }

    /**
     * The grid column class depending on the number of columns.
     *
     * @param byte $columns Number of columns.
     * @return string CSS class.
     */
    protected function get_column_class($columns) {
        $colclasses = [
            1 => 'col-sm-12',
            2 => 'col-sm-6',
            3 => 'col-md-4',
            4 => 'col-lg-3',
            'D' => 'col-sm-12 col-md-12 col-lg-6 col-xl-4',
        ];

        return $colclasses[$columns];
    }
}

global $CFG;
if (file_exists("$CFG->dirroot/course/format/topcoll/classes/output/renderer.php")) {
    /**
     * The theme's Collapsed Topics renderer.
     *
     * @copyright  &copy; 2021-onwards G J Barnard.
     * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
     */
    class theme_foundation_format_topcoll_renderer extends \format_topcoll\output\renderer {
        use theme_foundation_format_topcoll_renderer_trait;
    }
}
