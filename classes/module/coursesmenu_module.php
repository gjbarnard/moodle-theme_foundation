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
 * @copyright  &copy; 2019-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\module;

defined('MOODLE_INTERNAL') || die();

use html_writer;
use moodle_url;

/**
 * Course menu module.
 *
 * Implements the features of the theme.
 *
 * @copyright  &copy; 2019-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class coursesmenu_module extends \theme_foundation\module_basement implements \templatable {

    /**
     * Add the course menu settings.
     *
     * @param array $settingspages The setting pages.
     * @param boolean $adminfulltree If the full tree is required.
     * @param toolbox $toolbox The theme toolbox.
     */
    public function add_settings(&$settingspages, $adminfulltree, $toolbox) {
        // Create our own settings page.
        $settingspages['coursesmenu'] = array(\theme_foundation\toolbox::SETTINGPAGE => new \admin_settingpage('theme_foundation_coursesmenu',
            get_string('coursesmenuheading', 'theme_foundation')), \theme_foundation\toolbox::HASSETTINGS => true);
        if ($adminfulltree) {
            global $CFG;
            if (file_exists("{$CFG->dirroot}/theme/foundation/foundation_admin_setting_configselect.php")) {
                require_once($CFG->dirroot . '/theme/foundation/foundation_admin_setting_configselect.php');
                require_once($CFG->dirroot . '/theme/foundation/foundation_admin_setting_configinteger.php');
            } else if (!empty($CFG->themedir) && file_exists("{$CFG->themedir}/foundation/foundation_admin_setting_configselect.php")) {
                require_once($CFG->themedir . '/foundation/foundation_admin_setting_configselect.php');
                require_once($CFG->themedir . '/foundation/foundation_admin_setting_configinteger.php');
            }

            $settingspages['coursesmenu'][\theme_foundation\toolbox::SETTINGPAGE]->add(
                new \admin_setting_heading(
                    'theme_foundation_coursesmenuheading',
                    get_string('coursesmenuheadingsub', 'theme_foundation'),
                    format_text(get_string('coursesmenuheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
                )
            );

            // Toggle courses display in custommenu.
            $name = 'theme_foundation/displaymycourses';
            $title = get_string('displaymycourses', 'theme_foundation');
            $description = get_string('displaymycoursesdesc', 'theme_foundation');
            $default = true;
            $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
            $settingspages['coursesmenu'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            // Toggle hidden courses display in custommenu.
            $name = 'theme_foundation/displayhiddenmycourses';
            $title = get_string('displayhiddenmycourses', 'theme_foundation');
            $description = get_string('displayhiddenmycoursesdesc', 'theme_foundation');
            $default = true;
            $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
            $settingspages['coursesmenu'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            // My courses order.
            $name = 'theme_foundation/mycoursesorder';
            $title = get_string('mycoursesorder', 'theme_foundation');
            $description = get_string('mycoursesorderdesc', 'theme_foundation');
            $default = 1;
            $choices = array(
                1 => get_string('mycoursesordersort', 'theme_foundation'),
                2 => get_string('mycoursesorderid', 'theme_foundation'),
                3 => get_string('mycoursesorderlast', 'theme_foundation')
            );
            $setting = new \foundation_admin_setting_configselect($name, $title, $description, $default, $choices);
            $settingspages['coursesmenu'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            // Course ID order.
            $name = 'theme_foundation/mycoursesorderidorder';
            $title = get_string('mycoursesorderidorder', 'theme_foundation');
            $description = get_string('mycoursesorderidorderdesc', 'theme_foundation');
            $default = 1;
            $choices = array(
                1 => get_string('mycoursesorderidasc', 'theme_foundation'),
                2 => get_string('mycoursesorderiddes', 'theme_foundation')
            );
            $setting = new \foundation_admin_setting_configselect($name, $title, $description, $default, $choices);
            $settingspages['coursesmenu'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            // Max courses.
            $name = 'theme_foundation/mycoursesmax';
            $title = get_string('mycoursesmax', 'theme_foundation');
            $default = 0;
            $lower = 0;
            $upper = 20;
            $description = get_string('mycoursesmaxdesc', 'theme_foundation',
                array('lower' => $lower, 'upper' => $upper));
            $setting = new \foundation_admin_setting_configinteger($name, $title, $description, $default, $lower, $upper);
            $settingspages['coursesmenu'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            // Set terminology for dropdown course list.
            $name = 'theme_foundation/mycoursetitle';
            $title = get_string('mycoursetitle', 'theme_foundation');
            $description = get_string('mycoursetitledesc', 'theme_foundation');
            $default = 'course';
            $choices = array(
                'course' => get_string('mycourses', 'theme_foundation'),
                'unit' => get_string('myunits', 'theme_foundation'),
                'class' => get_string('myclasses', 'theme_foundation'),
                'module' => get_string('mymodules', 'theme_foundation')
            );
            $setting = new \foundation_admin_setting_configselect($name, $title, $description, $default, $choices);
            $settingspages['coursesmenu'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        }
    }

    /**
     * Export for template.
     *
     * @param renderer_base $output The renderer.
     * @return stdClass containing the data or null.
     */
    public function export_for_template(\renderer_base $output) {
        $data = null;

        $coursemenu = $this->courses_menu($output);
        if (!empty($coursemenu)) {
            $data = new \stdClass;
            $data->coursemenu = $coursemenu;
        }

        return $data;
    }

    /**
     * Outputs the courses menu.
     *
     * @param renderer_base $output Output renderer.
     * @return string Rendered custom menu if any.
     */
    protected function courses_menu(\renderer_base $output) {
        $toolbox = \theme_foundation\toolbox::get_instance();
        $hasdisplaymycourses = $toolbox->get_setting('displaymycourses');
        if (isloggedin() && !isguestuser() && $hasdisplaymycourses) {
            global $PAGE;
            $coursemenu = new \theme_foundation\output\course_menu_item('');
            $mycoursesorder = $toolbox->get_setting('mycoursesorder');
            if (!$mycoursesorder) {
                $mycoursesorder = 1;
            }

            $lateststring = '';
            if ($mycoursesorder == 3) {
                $lateststring = 'latest';
            }

            $mycoursetitle = $toolbox->get_setting('mycoursetitle');
            if ($mycoursetitle == 'module') {
                $branchtitle = get_string('my'.$lateststring.'modules', 'theme_foundation');
            } else if ($mycoursetitle == 'unit') {
                $branchtitle = get_string('my'.$lateststring.'units', 'theme_foundation');
            } else if ($mycoursetitle == 'class') {
                $branchtitle = get_string('my'.$lateststring.'classes', 'theme_foundation');
            } else {
                $branchtitle = get_string('my'.$lateststring.'courses', 'theme_foundation');
            }
            $branchlabel = $output->getfontawesomemarkup('briefcase', array('icon')).$branchtitle;
            $branchurl = $PAGE->url;
            $branchsort = 200;

            $coursemenubranch = $coursemenu->add($branchlabel, $branchurl, $branchtitle, $branchsort);

            $hometext = get_string('myhome');
            $homelabel = '<span>'.$output->getfontawesomemarkup('home', array('icon')).$hometext.'</span>';
            $coursemenubranch->add($homelabel, new moodle_url('/my/index.php'), $homelabel);

            // Retrieve courses and add them to the menu when they are visible.
            $numcourses = 0;
            $hasdisplayhiddenmycourses = $toolbox->get_setting('displayhiddenmycourses');

            $courses = array();
            if (($mycoursesorder == 1) || ($mycoursesorder == 2)) {
                $direction = 'ASC';
                if ($mycoursesorder == 1) {
                    // Get 'My courses' sort preference from admin config.
                    global $CFG;
                    if (!$sortorder = $CFG->navsortmycoursessort) {
                        $sortorder = 'sortorder';
                    }
                } else if ($mycoursesorder == 2) {
                    $sortorder = 'id';
                    $mycoursesorderidorder = $toolbox->get_setting('mycoursesorderidorder');
                    if ($mycoursesorderidorder == 2) {
                        $direction = 'DESC';
                    }
                }
                $courses = \enrol_get_my_courses(null, $sortorder.' '.$direction);
            } else if ($mycoursesorder == 3) {
                /* To test:
                 * 1. As an administrator...
                 * 2. Create a test user to be a student.
                 * 3. Create a course with a start time before the current and enrol the student.
                 * 4. Log in as the student and access the course.
                 * 5. Log back in as an administrator and create a second course and enrol the student.
                 * 6. Log back in as the student and navigate to the dashboard.
                 * 7. Confirm that the second course is listed before the first on the menu.
                 */
                // Get the list of enrolled courses as before but as for us, ignore 'navsortmycoursessort'.
                $courses = \enrol_get_my_courses(null, 'sortorder ASC');
                if ($courses) {
                    // We have something to work with.  Get the last accessed information for the user and populate.
                    global $DB, $USER;
                    $lastaccess = $DB->get_records('user_lastaccess', array('userid' => $USER->id), '', 'courseid, timeaccess');
                    if ($lastaccess) {
                        foreach ($courses as $course) {
                            if (!empty($lastaccess[$course->id])) {
                                $course->timeaccess = $lastaccess[$course->id]->timeaccess;
                            }
                        }
                    }
                    // Determine if we need to query the enrolment and user enrolment tables.
                    $enrolquery = false;
                    foreach ($courses as $course) {
                        if (empty($course->timeaccess)) {
                            $enrolquery = true;
                            break;
                        }
                    }
                    if ($enrolquery) {
                        // We do.
                        $params = array('userid' => $USER->id);
                        $sql = "SELECT ue.id, e.courseid, ue.timestart
                            FROM {enrol} e
                            JOIN {user_enrolments} ue ON (ue.enrolid = e.id AND ue.userid = :userid)";
                        $enrolments = $DB->get_records_sql($sql, $params, 0, 0);
                        if ($enrolments) {
                            // Sort out any multiple enrolments on the same course.
                            $userenrolments = array();
                            foreach ($enrolments as $enrolment) {
                                if (!empty($userenrolments[$enrolment->courseid])) {
                                    if ($userenrolments[$enrolment->courseid] < $enrolment->timestart) {
                                        // Replace.
                                        $userenrolments[$enrolment->courseid] = $enrolment->timestart;
                                    }
                                } else {
                                    $userenrolments[$enrolment->courseid] = $enrolment->timestart;
                                }
                            }
                            // We don't need to worry about timeend etc. as our course list will be valid for the user from above.
                            foreach ($courses as $course) {
                                if (empty($course->timeaccess)) {
                                    $course->timestart = $userenrolments[$course->id];
                                }
                            }
                        }
                    }
                    uasort($courses, array($this, 'timeaccesscompare'));
                }
            }

            if ($courses) {
                $mycoursesmax = $toolbox->get_setting('mycoursesmax');
                if (!$mycoursesmax) {
                    $mycoursesmax = PHP_INT_MAX;
                }
                foreach ($courses as $course) {
                    if ($this->courses_menu_add_course($coursemenubranch, $course, $hasdisplayhiddenmycourses, $output)) {
                        $numcourses += 1;
                    }
                    if ($numcourses == $mycoursesmax) {
                        break;
                    }
                }
            }
            if ($numcourses == 0) {
                $noenrolments = get_string('noenrolments', 'theme_foundation');
                $coursemenubranch->add('<em>'.$noenrolments.'</em>', $PAGE->url, $noenrolments);
            }
            return $output->render_the_course_menu($coursemenu);
        }

        return '';
    }

    /**
     * Renders menu items for the course_menu.
     *
     * @param custom_menu_item $branch Menu branch to add the course to.
     * @param stdClass $course Course to use.
     * @param boodlean $hasdisplayhiddenmycourses Display hidden courses.
     * @param renderer_base $output Output renderer.
     * @return boolean $courseadded if the course was added to the branch.
     */
    protected function courses_menu_add_course($branch, $course, $hasdisplayhiddenmycourses, \renderer_base $output) {
        $courseadded = false;
        if ($course->visible) {
            $branchtitle = format_string($course->shortname);
            $branchurl = new moodle_url('/course/view.php', array('id' => $course->id));
            $branchlabel = '<span>'.$output->getfontawesomemarkup('graduation-cap', array('icon')).format_string($course->fullname).'</span>';
            $branch->add($branchlabel, $branchurl, $branchtitle);
            $courseadded = true;
        } else if (has_capability('moodle/course:viewhiddencourses', \context_course::instance($course->id)) && $hasdisplayhiddenmycourses) {
            $branchtitle = format_string($course->shortname);
            $branchlabel = '<span class="dimmed_text">'.$output->getfontawesomemarkup('eye-slash', array('icon')).
                format_string($course->fullname).'</span>';
            $branchurl = new moodle_url('/course/view.php', array('id' => $course->id));
            $branch->add($branchlabel, $branchurl, $branchtitle);
            $courseadded = true;
        }
        return $courseadded;
    }

    protected static function timeaccesscompare($a, $b) {
        // The timeaccess is lastaccess entry and timestart an enrol entry.
        if ((!empty($a->timeaccess)) && (!empty($b->timeaccess))) {
            // Both last access.
            if ($a->timeaccess == $b->timeaccess) {
                return 0;
            }
            return ($a->timeaccess > $b->timeaccess) ? -1 : 1;
        } else if ((!empty($a->timestart)) && (!empty($b->timestart))) {
            // Both enrol.
            if ($a->timestart == $b->timestart) {
                return 0;
            }
            return ($a->timestart > $b->timestart) ? -1 : 1;
        }

        /* Must be comparing an enrol with a last access.
           -1 is to say that 'a' comes before 'b'. */
        if (!empty($a->timestart)) {
            // If 'a' is the enrol entry.
            return -1;
        }
        // Then 'b' must be the enrol entry.
        return 1;
    }
}
