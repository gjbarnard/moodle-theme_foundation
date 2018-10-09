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
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_foundation\output;

use html_writer;

defined('MOODLE_INTERNAL') || die();

class core_renderer_maintenance extends \core_renderer_maintenance {

    public function __construct(\moodle_page $page, $target) {
        parent::__construct($page, $target);
    }

    // The page.
    public function render_page() {
        $data = new \stdClass();
        $data->htmlattributes = $this->htmlattributes();
        $data->page_title = $this->page_title();
        $data->favicon = $this->favicon();
        $data->standard_head_html = $this->standard_head_html();

        $data->body_attributes = $this->body_attributes();
        $data->standard_top_of_body_html = $this->standard_top_of_body_html();
        $data->pagelayout = $this->render_maintenance_template();
        $data->standard_end_of_body_html = $this->standard_end_of_body_html();

        return $this->render_from_template('theme_shoelace/wrapper_layout', $data);
    }

    protected function render_maintenance_template() {
        $data = new \stdClass();

        $data->heading = $this->page_heading();
        $data->main_content = $this->main_content();
        $data->footer = $this->standard_footer_html();

        return $this->render_from_template('theme_shoelace/maintenance', $data);
    }
}
