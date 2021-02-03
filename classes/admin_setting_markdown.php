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
 * @package    theme_foundation
 * @copyright  &copy; 2021-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_foundation;

defined('MOODLE_INTERNAL') || die();

/**
 * Setting that uses the 'Parsedown' class to show markup.
 * Based on admin_setting_description in adminlib.php.
 *
 * @copyright  &copy; 2021-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class admin_setting_markdown extends \admin_setting {

    private $filename;

    /**
     * Not a setting, just markup.
     *
     * @param string $name.
     * @param string $visiblename.
     * @param string $description.
     * @param string $filename.
     */
    public function __construct($name, $visiblename, $description, $filename) {
        $this->nosave = true;
        $this->filename = $filename;
        parent::__construct($name, $visiblename, $description, '');
    }

    /**
     * Always returns true.
     *
     * @return bool Always returns true.
     */
    public function get_setting() {
        return true;
    }

    /**
     * Always returns true.
     *
     * @return bool Always returns true.
     */
    public function get_defaultsetting() {
        return true;
    }

    /**
     * Never write settings
     *
     * @param mixed $data Gets converted to str for comparison against yes value.
     * @return string Always returns an empty string.
     */
    public function write_setting($data) {
        // Do not write any setting.
        return '';
    }

    /**
     * Returns an HTML string
     *
     * @param string $data
     * @param string $query
     * @return string Returns an HTML string
     */
    public function output_html($data, $query='') {
        global $CFG, $OUTPUT;

        $parsedown = new \theme_foundation\parsedown\Parsedown();

        $context = new \stdClass();
        $context->title = $this->visiblename;
        $context->description = $this->description;
        //$context->markdown = $parsedown->text('Hello _Parsedown_!').format_text('Hello _Parsedown_!', FORMAT_MARKDOWN);
        $filecontents = file_get_contents($CFG->dirroot.'/theme/foundation/Changes.md');
        $context->markdown = $parsedown->text($filecontents).format_text($filecontents, FORMAT_MARKDOWN);

        return $OUTPUT->render_from_template('theme_foundation/admin_setting_markdown', $context);
    }
}
