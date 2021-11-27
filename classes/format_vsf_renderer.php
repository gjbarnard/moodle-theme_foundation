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
 * @copyright  &copy; 2021-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
if (file_exists("$CFG->dirroot/course/format/vsf/renderer.php")) {
    include_once($CFG->dirroot."/course/format/vsf/renderer.php");

    /**
     * The theme's Progress Section format renderer.
     *
     * @copyright  &copy; 2021-onwards G J Barnard.
     * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
     */
    class theme_foundation_format_vsf_renderer extends format_vsf_renderer {
        protected function get_nav_link_icons() {
            if (!empty(\theme_foundation\toolbox::get_config_setting('fav'))) {
                return array(
                    'next' => 'far fa-arrow-alt-circle-right',
                    'previous' => 'far fa-arrow-alt-circle-left'
                );
            } else {
                return array(
                    'next' => 'fa fa-arrow-circle-o-right',
                    'previous' => 'fa fa-arrow-circle-o-left'
                );
            }
        }
    }
}
