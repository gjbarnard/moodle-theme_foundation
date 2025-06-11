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

namespace theme_foundation\output\core;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/course/renderer.php');

use core\output\html_writer;
use core\url;

/**
 * The course renderer.
 */
class course_renderer extends \core_course_renderer {
    /**
     * Return an instance of the mustache class.
     *
     * @return Mustache_Engine
     */
    protected function get_mustache() {
        $toolbox = \theme_foundation\toolbox::get_instance();
        return $toolbox->get_mustache();
    }

    /**
     * Returns HTML to display course name.
     *
     * @param coursecat_helper $chelper
     * @param core_course_list_element $course
     * @return string
     */
    protected function course_name(\coursecat_helper $chelper, \core_course_list_element $course): string {
        $content = '';
        if ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $nametag = 'h3';
        } else {
            $nametag = 'div';
        }
        $toolbox = \theme_foundation\toolbox::get_instance();
        $coursename = $toolbox->getfontawesomemarkup('graduation-cap', ['me-1']) . $chelper->get_course_formatted_name($course);
        $coursenamelink = html_writer::link(
            new url('/course/view.php', ['id' => $course->id]),
            $coursename,
            ['class' => $course->visible ? 'aalink' : 'aalink dimmed']
        );
        $content .= html_writer::tag($nametag, $coursenamelink, ['class' => 'coursename']);
        // If we display course in collapsed form but the course has summary or course contacts, display the link to the info page.
        $content .= html_writer::start_tag('div', ['class' => 'moreinfo']);
        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            if (
                $course->has_summary() || $course->has_course_contacts() || $course->has_course_overviewfiles()
                || $course->has_custom_fields()
            ) {
                $url = new url('/course/info.php', ['id' => $course->id]);
                $image = $this->output->pix_icon('i/info', $this->strings->summary);
                $content .= html_writer::link($url, $image, ['title' => $this->strings->summary]);
                // Make sure JS file to expand course content is included.
                $this->coursecat_include_js();
            }
        }
        $content .= html_writer::end_tag('div');
        return $content;
    }
}
