<?php
// This file is part of The Bootstrap Moodle theme
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
 * @copyright  2024 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\output\core_course\output;

use core\output\action_link;
use core\output\url_select;
use core\url;

/**
 * The class activity navigation renderable.
 */
class activity_navigation extends \core_course\output\activity_navigation {

    /**
     * Constructor.
     *
     * @param \cm_info|null $prevmod The previous module to display, null if none.
     * @param \cm_info|null $nextmod The next module to display, null if none.
     * @param array $activitylist The list of activity URLs (as key) and names (as value) for the activity dropdown menu.
     */
    public function __construct($prevmod, $nextmod, $activitylist = []) {
        global $OUTPUT;

        // Enabled?
        $toolbox = \theme_foundation\toolbox::get_instance();
        $activitynavigationmodulenames = $toolbox->get_setting('activitynavigationmodulenames');

        // Check if there is a previous module to display.
        if ($prevmod) {
            $linkurl = new url($prevmod->url, ['forceview' => 1]);

            if ($activitynavigationmodulenames) {
                $linkname = $prevmod->get_formatted_name();
            } else {
                $linkname = get_string('previous');
            }
            if (!$prevmod->visible) {
                $linkname .= ' ' . get_string('hiddenwithbrackets');
            }

            $attributes = [
                'class' => 'btn btn-link',
                'id' => 'prev-activity-link',
            ];
            $this->prevlink = new action_link($linkurl, $OUTPUT->larrow() . ' ' . $linkname, null, $attributes);
        }

        // Check if there is a next module to display.
        if ($nextmod) {
            $linkurl = new url($nextmod->url, ['forceview' => 1]);
            if ($activitynavigationmodulenames) {
                $linkname = $nextmod->get_formatted_name();
            } else {
                $linkname = get_string('next');
            }
            if (!$nextmod->visible) {
                $linkname .= ' ' . get_string('hiddenwithbrackets');
            }

            $attributes = [
                'class' => 'btn btn-link',
                'id' => 'next-activity-link',
            ];
            $this->nextlink = new action_link($linkurl, $linkname . ' ' . $OUTPUT->rarrow(), null, $attributes);
        }

        // Render the activity list dropdown menu if available.
        if (!empty($activitylist)) {
            $select = new url_select($activitylist, '', ['' => get_string('jumpto')]);
            $select->set_label(get_string('jumpto'), ['class' => 'visually-hidden']);
            $select->attributes = ['id' => 'jump-to-activity'];
            $this->activitylist = $select;
        }
    }
}
