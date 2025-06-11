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

namespace theme_foundation\module;

use stdClass;

/**
 * Frontpage carousel module.
 *
 * Implements the features of the theme.
 *
 * @copyright  &copy; 2019-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class frontpagecarousel_module extends \theme_foundation\module_basement implements \templatable {
    /**
     * Add the frontpage carousel settings.
     *
     * @param array $settingspages The setting pages.
     * @param toolbox $toolbox The theme toolbox.
     */
    public function add_settings(&$settingspages, $toolbox) {
        // Create our own settings page.
        $settingspages['frontpagecarousel'] = [\theme_foundation\toolbox::SETTINGPAGE => new \admin_settingpage(
            'theme_foundation_frontpagecarousel',
            get_string('frontpagecarouselheading', 'theme_foundation')
        ), \theme_foundation\toolbox::HASSETTINGS => true, ];

        $settingspages['frontpagecarousel'][\theme_foundation\toolbox::SETTINGPAGE]->add(
            new \admin_setting_heading(
                'theme_foundation_frontpagecarouselheading',
                get_string('frontpagecarouselheadingsub', 'theme_foundation'),
                format_text(get_string('frontpagecarouselheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );

        // Number of slides.
        $name = 'theme_foundation/frontpagecarouselslides';
        $title = get_string('frontpagecarouselslides', 'theme_foundation');
        $default = 2;
        $lower = 0;
        $upper = 8;
        $description = get_string(
            'frontpagecarouselslidesdesc',
            'theme_foundation',
            ['lower' => $lower, 'upper' => $upper]
        );
        $setting = new \theme_foundation\admin_setting_configinteger($name, $title, $description, $default, $lower, $upper);
        $settingspages['frontpagecarousel'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        $numberofslides = $toolbox->get_setting('frontpagecarouselslides', 'foundation'); // Stick to ours or could be confusing!
        if ($numberofslides > 0) {
            for ($slidenum = 1; $slidenum <= $numberofslides; $slidenum++) {
                // Slide X setting heading.
                $settingspages['frontpagecarousel'][\theme_foundation\toolbox::SETTINGPAGE]->add(
                    new \admin_setting_heading(
                        'theme_foundation_frontpagecarousel_' . $slidenum . '_heading',
                        get_string('frontpageslideno', 'theme_foundation', ['number' => $slidenum]),
                        get_string('frontpageslidenodesc', 'theme_foundation', ['number' => $slidenum])
                    )
                );

                // Slide enabled.
                $name = 'theme_foundation/frontpageenableslide' . $slidenum;
                $title = get_string('frontpageenableslide', 'theme_foundation', ['number' => $slidenum]);
                $description = get_string('frontpageenableslidedesc', 'theme_foundation', ['number' => $slidenum]);
                $default = false;
                $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
                $settingspages['frontpagecarousel'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                // Slide title.
                $name = 'theme_foundation/frontpageslidetitle' . $slidenum;
                $title = get_string('frontpageslidetitle', 'theme_foundation', ['number' => $slidenum]);
                $description = get_string('frontpageslidetitledesc', 'theme_foundation', ['number' => $slidenum]);
                $default = '';
                $setting = new \admin_setting_configtext($name, $title, $description, $default);
                $settingspages['frontpagecarousel'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                // Slide caption.
                $name = 'theme_foundation/frontpageslidecaption' . $slidenum;
                $title = get_string('frontpageslidecaption', 'theme_foundation', ['number' => $slidenum]);
                $description = get_string('frontpageslidecaptiondesc', 'theme_foundation', ['number' => $slidenum]);
                $default = '';
                $setting = new \admin_setting_confightmleditor($name, $title, $description, $default);
                $settingspages['frontpagecarousel'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                // Slide image.
                $name = 'theme_foundation/frontpageslideimage' . $slidenum;
                $title = get_string('frontpageslideimage', 'theme_foundation', ['number' => $slidenum]);
                $description = get_string('frontpageslideimagedesc', 'theme_foundation', ['number' => $slidenum]);
                $setting = new \theme_foundation\admin_setting_configstoredfiles(
                    $name,
                    $title,
                    $description,
                    'frontpageslideimage' . $slidenum,
                    ['accepted_types' => '*.jpg,*.jpeg,*.png', 'maxfiles' => 1]
                );
                $settingspages['frontpagecarousel'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);
            }
        }
    }

    /**
     * Gets the module bodyclasses.
     *
     * @return array bodyclass strings.
     */
    public function body_classes() {
        global $PAGE;
        $bodyclasses = [];

        if ($PAGE->pagelayout == 'frontpage') {
            $bodyclasses[] = 'frontpagecarousel';
        }

        return $bodyclasses;
    }

    /**
     * Export for template.
     *
     * @param renderer_base $output The renderer.
     * @return stdClass containing the data or null.
     */
    public function export_for_template(\renderer_base $output) {
        $data = null;
        global $PAGE;
        if ($PAGE->pagelayout == 'frontpage') {
            $toolbox = \theme_foundation\toolbox::get_instance();

            // Stick to ours or could be confusing!
            $numberofslides = $toolbox->get_setting('frontpagecarouselslides', 'foundation');
            if ($numberofslides > 0) {
                $slidesenabled = [];
                for ($slidenum = 1; $slidenum <= $numberofslides; $slidenum++) {
                    // Stick to ours or could be confusing!
                    $slideenabled = $toolbox->get_setting('frontpageenableslide' . $slidenum, 'foundation');
                    if ($slideenabled) {
                        $slidesenabled[] = $slidenum; // Slide to be shown on the page.
                    }
                }

                if (!empty($slidesenabled)) {
                    $data = new stdClass();
                    $data->carouselid = 'frontpagecarousel';
                    $data->carouselindicators = [];
                    $indicator = 0;
                    $data->carouselslides = [];
                    foreach ($slidesenabled as $slidenum) {
                        $theslide = new stdClass();
                        $theslide->slidetitle = $toolbox->get_setting('frontpageslidetitle' . $slidenum, 'foundation');
                        $theslide->slidecaption = $toolbox->get_setting('frontpageslidecaption' . $slidenum, 'foundation');
                        if (!empty($toolbox->get_setting('frontpageslideimage' . $slidenum, 'foundation'))) {
                            $theslide->slideimage = $toolbox->setting_file_url('frontpageslideimage' . $slidenum,
                                'frontpageslideimage' . $slidenum, 'foundation');
                        } else {
                            $theslide->slideimage = $output->image_url('Foundation_default_slide', 'theme_foundation');
                        }
                        $theslide->slideimagetext = 'TODO';

                        $theindicator = new stdClass();
                        $theindicator->indicatoractive = ($indicator == 0) ? 1 : 0;
                        $theindicator->indicatornumber = $indicator++;
                        $theindicator->slidetitle = $theslide->slidetitle;
                        $data->carouselindicators[] = $theindicator;

                        $theslide->slideactive = $theindicator->indicatoractive;
                        $data->carouselslides[] = $theslide;
                    }
                }
            }
        }

        return $data;
    }
}
