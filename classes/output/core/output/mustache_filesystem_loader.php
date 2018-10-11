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

use coding_exception;

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
     * Load a Template by name.
     *
     *     $loader = new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views');
     *     $loader->load('admin/dashboard'); // loads "./views/admin/dashboard.mustache";
     *
     * @param string $name
     *
     * @return string Mustache Template source
     */
    public function load($name)
    {
        //error_log(print_r('load('.$this->partials.'): '.$name, true));
        if (!isset($this->templates[$name])) {
            $this->templates[$name] = $this->loadFile($name);
        }

        return $this->templates[$name];
    }

    /**
     * Helper function for loading a Mustache file by name.
     *
     * @throws Mustache_Exception_UnknownTemplateException If a template file is not found
     *
     * @param string $name
     *
     * @return string Mustache Template source
     */
    protected function loadFile($name)
    {
        //error_log(print_r('loadFile('.$this->partials.'): '.$name, true));
        $fileName = $this->getFileName($name);

        if ($this->shouldCheckPath() && !file_exists($fileName)) {
            throw new Mustache_Exception_UnknownTemplateException($name);
        }

        return file_get_contents($fileName);
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
        // Call the Moodle template finder.
        if (substr_count($name, '/') == 2) {
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
