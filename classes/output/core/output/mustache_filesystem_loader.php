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
 * Perform some custom name mapping for template file names (strip leading component/).
 *
 * @package    core
 * @category   output
 * @copyright  2015 Damyon Wiese
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_foundation\output\core\output;

/**
 * Perform some custom name mapping for template file names.
 *
 * @copyright  2015 Damyon Wiese
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      2.9
 */
class mustache_filesystem_loader extends \core\output\mustache_filesystem_loader {

    private $partials;
 
    /**
     * Provide a default no-args constructor (we don't really need anything).
     */
    public function __construct() {
        $this->partials = 'false';
    }
    
    public function tisPartials() {
        $this->partials = 'true';
    }

    /**
     * Helper function for getting a Mustache template file name.
     * Uses the leading component to restrict us specific directories.
     *
     * @param string $name
     * @return string Template file name
     */
    protected function getFileName($name) {
        error_log(print_r('getFileName('.$this->partials.'): '.$name, true));
        /* Call the Moodle template finder.
         * 
         * If there is no underscore before the first forward slash then from our theme persepective it
         * is a non-frankenstyle overridden template that should have been defined in the Boost theme.
         * This can be called either from PHP or as a 'partials', i.e. the 'partials_loader' concept.
         * 
         * But if we wanted to overload a Boost 'partial' then all we need to do is prefix it with the
         * theme name, i.e. 'theme_foundation/core/action_menu' and this code will cope with that syntax
         * even if core does not support partials.
         * 
         * All of this allows us as a theme not to have to have copies of and maintain all of the Bootstrap
         * version 4 core templates that Boost provides.
         */
        $component = substr($name, 0, strpos($name, '/'));
        if (strpos($component, '_') === FALSE) {
            return \core\output\mustache_template_finder::get_template_filepath($name, 'boost');            
        } else if (substr_count($name, '/') == 2) {
            $parts = explode('/', $name);
            // First part is theme name.
            $themename = explode('_', $parts[0]);
            $name = $parts[1].'/'.$parts[2];
            return \core\output\mustache_template_finder::get_template_filepath($name, $themename[1]);
        } else {
            return \core\output\mustache_template_finder::get_template_filepath($name);
        }
    }
}
