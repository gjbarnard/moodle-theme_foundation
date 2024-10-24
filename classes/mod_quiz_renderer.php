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

defined('MOODLE_INTERNAL') || die;

use core\output\html_writer;

global $CFG;
$quizrenderer = $CFG->dirroot . '/mod/quiz/renderer.php';
if (file_exists($quizrenderer)) {
    // Be sure to include the quiz renderer so it can be extended.
    require_once($quizrenderer);

    /**
     * Class theme_foundation_mod_quiz_renderer
     */
    class theme_foundation_mod_quiz_renderer extends mod_quiz_renderer {
        /**
         * Display a quiz navigation button.
         *
         * @param quiz_nav_question_button $button
         * @return string HTML fragment.
         */
        protected function render_quiz_nav_question_button(quiz_nav_question_button $button) {
            $classes = ['qnbutton', $button->stateclass, $button->navmethod];
            $extrainfo = [];

            if ($button->currentpage) {
                $classes[] = 'thispage';
                $extrainfo[] = get_string('onthispage', 'quiz');
            }

            // Flagged?
            if ($button->flagged) {
                $classes[] = 'flagged';
                $flaglabel = get_string('flagged', 'question');
            } else {
                $flaglabel = '';
            }
            $extrainfo[] = html_writer::tag('span', $flaglabel, ['class' => 'flagstate']);

            if (is_numeric($button->number)) {
                $qnostring = 'questionnonav';
            } else {
                $qnostring = 'questionnonavinfo';
            }

            $a = new stdClass();
            $a->number = $button->number;
            $a->attributes = implode(' ', $extrainfo);
            $tagcontents = html_writer::tag('span', '', ['class' => 'thispageholder']) .
                html_writer::tag('span', '', ['class' => 'trafficlight']) .
                get_string($qnostring, 'quiz', $a);
            $tagattributes = ['class' => implode(' ', $classes), 'id' => $button->id,
                'title' => $button->statestring, 'data-quiz-page' => $button->page, ];

            if ($button->url) {
                return html_writer::link($button->url, $tagcontents, $tagattributes);
            } else {
                return html_writer::tag('span', $tagcontents, $tagattributes);
            }
        }
    }
}
