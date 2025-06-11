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

namespace theme_foundation\output;

use core_block\output\block_contents;
use core\output\action_link;
use core\output\action_menu;
use core\output\action_menu\filler;
use core\output\action_menu\link_secondary;
use core\output\html_writer;
use core\output\pix_icon;
use core\output\pix_icon_fontawesome;
use core\url;
use navigation_node;
use stdClass;

/**
 * The core renderer toolbox.
 */
trait core_renderer_toolbox {
    /**
     * Orchestrates the rendering of the page.
     */
    public function render_page() {
        global $CFG, $COURSE, $SITE, $USER;

        $toolbox = \theme_foundation\toolbox::get_instance();

        $data = new stdClass();
        $data->output = $this;
        $data->sitename = format_string($SITE->shortname, true,
            ['context' => \context_course::instance(SITEID), "escape" => false]);
        $bodyclasses = [];
        $regionmainsettingsmenu = $this->region_main_settings_menu();

        if (
            $this->page->include_region_main_settings_in_header_actions() &&
                !$this->page->blocks->is_block_present('settings')
        ) {
            /* Only include the region main settings if the page has requested it and it doesn't already have
               the settings block on it. The region main settings are included in the settings block and
               duplicating the content causes behat failures. */
            $this->page->add_header_action(html_writer::div(
                $regionmainsettingsmenu,
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        if (!empty($this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
            if (in_array('drawer', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                $drawerblockshtml = $this->blocks('drawer');
                $hasdrawerblocks = ((strpos($drawerblockshtml, 'data-block=') !== false) || ($this->page->user_is_editing()));

                $data->drawerblocks = $drawerblockshtml;
                $data->hasdrawerblocks = $hasdrawerblocks;

                if ($hasdrawerblocks) {
                    $USER->foundation_user_pref['drawerclosed'] = PARAM_BOOL;
                    $data->drawerclosed = get_user_preferences('drawerclosed', true);
                    if (!$data->drawerclosed) {
                        $bodyclasses[] = 'drawer-open';
                    }
                }
            }

            if (in_array('poster', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                $posterblockshtml = $this->blocks('poster');
                $hasposterblocks = ((strpos($posterblockshtml, 'data-block=') !== false) || ($this->page->user_is_editing()));

                $data->posterblocks = $posterblockshtml;
                $data->hasposterblocks = $hasposterblocks;
            }

            if (in_array('marketing', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                $hblockshtml = $this->hblocks('marketing');
                $hashblocks = ((strpos($hblockshtml, 'data-block=') !== false) || ($this->page->user_is_editing()));

                $data->hblocks = $hblockshtml;
                $data->hashblocks = $hashblocks;
            }

            if (in_array('side-pre', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                if (($this->page->pagelayout == 'report') || ($this->page->pagetype == 'mod-assign-grading')) {
                    $preblockshtml = $this->hblocks('side-pre');
                } else {
                    $preblockshtml = $this->blocks('side-pre');
                }
                $haspreblocks = ((strpos($preblockshtml, 'data-block=') !== false) || ($this->page->user_is_editing()));

                $data->sidepreblocks = $preblockshtml;
                $data->haspreblocks = $haspreblocks;
            }

            if (in_array('side-post', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                if (($this->page->pagelayout == 'report') || ($this->page->pagetype == 'mod-assign-grading')) {
                    $postblockshtml = $this->hblocks('side-post');
                } else {
                    $postblockshtml = $this->blocks('side-post');
                }
                $haspostblocks = ((strpos($postblockshtml, 'data-block=') !== false) || ($this->page->user_is_editing()));

                $data->sidepostblocks = $postblockshtml;
                $data->haspostblocks = $haspostblocks;
            }

            if (in_array('courseend', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                $ceblockshtml = $this->hblocks('courseend');
                $hasceblocks = ((strpos($ceblockshtml, 'data-block=') !== false) || ($this->page->user_is_editing()));

                $data->ceblocks = $ceblockshtml;
                $data->hasceblocks = $hasceblocks;
            }
        }

        $modules = $toolbox->get_modules();
        foreach ($modules as $module) {
            if (method_exists($module, 'export_for_template')) {
                $moduledata = $module->export_for_template($this);
                if (!empty($moduledata)) {
                    foreach ($moduledata as $mkey => $mvalue) {
                        $data->$mkey = $mvalue;
                    }
                }
            }
        }

        $data->nonavbar = (!empty($this->page->theme->layouts[$this->page->pagelayout]['options']['nonavbar']));
        if ($data->nonavbar) {
            $bodyclasses[] = 'no-navbar';
        } else {
            if ($toolbox->get_setting('navbareditswitch')) {
                $data->navbareditswitch = $this->edit_switch();
            }
            $data->navbarposition = $toolbox->get_setting('navbarposition');
            if (empty($data->navbarposition)) {
                $data->navbarposition = 'top';
            }
            $bodyclasses[] = 'navbar-' . $data->navbarposition;
            $data->navbarbottom = ($data->navbarposition == 'bottom');

            $data->navbarstyle = $toolbox->get_setting('navbarstyle');
            if (empty($data->navbarstyle)) {
                $data->navbarstyle = 'dark';
            }

            $primary = new \core\navigation\output\primary($this->page);
            $primarymenu = $primary->export_for_template($this);
            $data->primarymoremenu = $primarymenu['moremenu'];
            $data->mobileprimarynav = $primarymenu['mobileprimarynav'];
        }

        if ((!empty($USER->auth)) && ($USER->auth == 'lti')) {
            $data->ltiauth = true;
        }

        if (empty($COURSE->visible)) {
            $bodyclasses[] = 'course-hidden';
        }

        $fav = $toolbox->get_setting('fav');
        if (empty($fav)) {
            $data->favi = true;
            $bodyclasses[] = 'favi';
        } else if ($fav == 2) {
            $data->favi = true;
            $bodyclasses[] = 'favi';
        }
        $bodyclasses = array_merge($bodyclasses, $toolbox->body_classes());
        if (!empty($bodyclasses)) {
            $bodyclasses = implode(' ', $bodyclasses);
        } else {
            $bodyclasses = '';
        }
        $data->bodyattributes = $this->body_attributes($bodyclasses);

        $data->regionmainsettingsmenu = $regionmainsettingsmenu;
        $data->hasregionmainsettingsmenu = !empty($regionmainsettingsmenu);
        require_once($CFG->dirroot . '/course/format/lib.php');  // For course_get_format() call?  Not sure if needed.
        $data->contextheadersettingsmenu = $this->context_header_settings_menu();
        $data->hascontextheadersettingsmenu = !empty($data->contextheadersettingsmenu);

        $header = $this->page->activityheader;
        $data->headercontent = $header->export_for_template($this);

        $data->nofooter = (!empty($this->page->theme->layouts[$this->page->pagelayout]['options']['nofooter']));
        if (!$data->nofooter) {
            $data->pagedoclink = $this->page_doc_link();
            $data->debugfooterhtml = $this->debug_footer_html();
        }

        $mustache = $this->page->theme->layouts[$this->page->pagelayout]['mustache'];
        if ($mustache == 'columns2') {
            if (!empty($toolbox->get_setting('trio'))) {
                $mustache = 'columns3';
            }

            $buildsecondarynavigation = $this->page->has_secondary_navigation();
            if ($buildsecondarynavigation) {
                $tablistnav = $this->page->has_tablist_secondary_navigation();
                $moremenu = new \core\navigation\output\more_menu($this->page->secondarynav, 'nav-tabs', true, $tablistnav);
                $data->secondarymoremenu = $moremenu->export_for_template($this);
                $overflowdata = $this->page->secondarynav->get_overflow_menu_data();
                if (!is_null($overflowdata)) {
                    $data->overflow = $overflowdata->export_for_template($this);
                }
            }
        }

        echo $this->render_from_template('theme_foundation/' . $mustache, $data);
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        $toolbox = \theme_foundation\toolbox::get_instance();
        return $toolbox->get_module('header')->header($this, $toolbox);
    }

    /**
     * Orchestrates the rendering of a plain page.
     */
    public function render_plain_page() {
        $mustache = $this->page->theme->layouts[$this->page->pagelayout]['mustache'];
        $data = new stdClass();
        $data->output = $this;
        $data->fakeblocks = $this->blocks('side-pre', [], 'aside', true);
        $data->hasfakeblocks = strpos($data->fakeblocks, 'data-block="_fake"') !== false;
        $data->headercontent = $this->page->activityheader->export_for_template($this);

        echo $this->render_from_template('theme_foundation/' . $mustache, $data);
    }

    /**
     * Outputs the opening section of a box.
     *
     * @param string $classes A space-separated list of CSS classes
     * @param string $id An optional ID
     * @param array $attributes An array of other attributes to give the box.
     * @return string the HTML to output.
     */
    public function box_start($classes = 'generalbox', $id = null, $attributes = []) {
        $this->opencontainers->push('box', html_writer::end_tag('div'));
        $attributes['id'] = $id;
        $attributes['class'] = 'box ' . \renderer_base::prepare_classes($classes);
        return html_writer::start_tag('div', $attributes);
    }

    /**
     * Outputs a container.
     *
     * @param string $contents The contents of the box
     * @param string $classes A space-separated list of CSS classes
     * @param string $id An optional ID
     * @param array $attributes Optional other attributes as array
     * @return string the HTML to output.
     */
    public function container($contents, $classes = null, $id = null, $attributes = []) {
        // Manipulate the grader report.
        if ((!is_null($classes)) && ($classes == 'gradeparent')) {
            $contents = preg_replace('/<th class="(header|userfield)(.*?)>(.*?)<\/th>/is',
                '<th class="$1$2><div class="d-flex">$3</div></th>', $contents);
        }
        return $this->container_start($classes, $id, $attributes) . $contents . $this->container_end();
    }

    /**
     * Get the HTML for blocks in the given region.
     *
     * @param string $region The region to get HTML for.
     * @param array $classes Classes.
     * @param string $tag Tag.
     * @param boolean $fakeblocksonly Include fake blocks only.
     *
     * @return string HTML.
     */
    public function blocks($region, $classes = [], $tag = 'aside', $fakeblocksonly = false) {
        $displayregion = $this->page->apply_theme_region_manipulations($region);
        $classes = (array)$classes;
        $classes[] = 'block-region';
        $editing = $this->page->user_is_editing();
        $content = '';

        if ($editing) {
            $content .= $this->block_region_title($displayregion);
        }

        $attributes = [
            'id' => 'block-region-' . preg_replace('#[^a-zA-Z0-9_\-]+#', '-', $displayregion),
            'class' => join(' ', $classes),
            'data-blockregion' => $displayregion,
            'data-droptarget' => '1',
        ];

        $content .= html_writer::tag('h2', get_string('blocks'), ['class' => 'visually-hidden']);
        if ($this->page->blocks->region_has_content($displayregion, $this)) {
            $content .= $this->blocks_for_region($displayregion, $fakeblocksonly);
        }

        if ($region != 'content') {
            $content .= $this->add_block_button($region, $editing, $fakeblocksonly);
        }

        return html_writer::tag($tag, $content, $attributes);
    }

    /**
     * Get the HTML for horizontal blocks in the given region.
     *
     * @param string $region The region to get HTML for.
     * @param array $classes Classes.
     * @param string $tag Tag.
     *
     * @return string HTML.
     */
    public function hblocks($region, $classes = [], $tag = 'aside') {
        $classes = (array)$classes;
        $classes[] = 'block-region row hblocks';
        $editing = $this->page->user_is_editing();
        $content = '';

        $toolbox = \theme_foundation\toolbox::get_instance();
        $blocksperrow = $toolbox->get_setting($region . 'blocksperrow');
        if (($blocksperrow > 6) || ($blocksperrow < 1)) {
            $blocksperrow = 4;
        }

        if ($editing) {
            $classes[] = 'editing bpr-' . $blocksperrow;
            $content .= $this->block_region_title($region);
        }

        $attributes = [
            'id' => 'block-region-' . preg_replace('#[^a-zA-Z0-9_\-]+#', '-', $region),
            'class' => join(' ', $classes),
            'data-blockregion' => $region,
            'data-droptarget' => '1',
        ];

        if ($this->page->blocks->region_has_content($region, $this)) {
            $content .= $this->hblocks_for_region($region, $editing, $blocksperrow);
        } else {
            $content .= '';
        }

        $content .= $this->add_block_button($region, $editing);

        return html_writer::tag($tag, $content, $attributes);
    }

    /**
     * Output all the blocks in a particular region.
     *
     * @param string $region the name of a region on this page.
     * @param boolean $fakeblocksonly Output fake block only.
     * @return string the HTML to be output.
     */
    public function add_block_button($region, $editing, $fakeblocksonly = false) {
        $o = '';
        if (($editing) && (!$fakeblocksonly)) {
            $o = $this->addblockbutton($region);
        }
        return $o;
    }

    /**
     * Get the HTML for block title in the given region.
     *
     * @param string $region The region to get HTML for.
     *
     * @return string HTML.
     */
    protected function block_region_title($region) {
        return html_writer::tag(
            'p',
            get_string('region-' . $region, 'theme_foundation'),
            ['class' => 'block-region-title col-12 text-center fst-italic fw-bold']
        );
    }

    /**
     * Output all the blocks in a particular region.
     *
     * @param string $region the name of a region on this page.
     * @param boolean $fakeblocksonly Output fake block only.
     * @return string the HTML to be output.
     */
    public function blocks_for_region($region, $fakeblocksonly = false) {
        $blockcontents = $this->page->blocks->get_content_for_region($region, $this);
        $lastblock = null;
        $zones = [];
        foreach ($blockcontents as $key => $bc) {
            if ($bc instanceof block_contents) {
                if ($bc->attributes['data-block'] == 'adminblock') {
                    // Remove 'Add block'.
                    unset($blockcontents[$key]);
                    continue;
                }
                $zones[] = $bc->title;
            }
        }
        $output = '';

        foreach ($blockcontents as $bc) {
            if ($bc instanceof block_contents) {
                if ($fakeblocksonly && !$bc->is_fake()) {
                    // Skip rendering real blocks if we only want to show fake blocks.
                    continue;
                }
                $output .= $this->block($bc, $region);
                $lastblock = $bc->title;
            } else if ($bc instanceof block_move_target) {
                if (!$fakeblocksonly) {
                    $output .= $this->block_move_target($bc, $zones, $lastblock, $region);
                }
            } else {
                throw new coding_exception('Unexpected type of thing (' . get_class($bc) . ') found in list of block contents.');
            }
        }
        return $output;
    }

    /**
     * Output all the horizontal blocks in a particular region.
     *
     * @param string $region The name of a region on this page.
     * @param boolean $editing If the user is editing the page.
     * @param int $blocksperrow Number of blocks per row.
     *
     * @return string the HTML to be output.
     */
    public function hblocks_for_region($region, $editing, $blocksperrow) {
        $blockcontents = $this->page->blocks->get_content_for_region($region, $this);
        $lastblock = null;
        $zones = [];
        foreach ($blockcontents as $key => $bc) {
            if ($bc instanceof block_contents) {
                if ($bc->attributes['data-block'] == 'adminblock') {
                    // Remove 'Add block'.
                    unset($blockcontents[$key]);
                    continue;
                }
                $zones[] = $bc->title;
            }
        }
        $output = '';

        $blockcount = count($blockcontents);

        if ($blockcount >= 1) {
            $rows = $blockcount / $blocksperrow; // Maximum blocks per row.

            if (!$editing) {
                if ($rows <= 1) {
                    $col = 12 / $blockcount;
                    if ($col < 1) {
                        // Should not happen but a fail safe - block will be small so good for screen shots when this happens.
                        $col = 1;
                    }
                } else {
                    $col = 12 / $blocksperrow;
                }
            }

            $currentblockcount = 0;
            $currentrow = 0;
            $currentrequiredrow = 1;

            foreach ($blockcontents as $bc) {
                if (!$editing) { // Using CSS when editing.
                    $currentblockcount++;
                    if ($currentblockcount > ($currentrequiredrow * $blocksperrow)) {
                        // Tripping point.
                        $currentrequiredrow++;
                        // Recalculate col if needed...
                        $remainingblocks = $blockcount - ($currentblockcount - 1);
                        if ($remainingblocks < $blocksperrow) {
                            $col = 12 / $remainingblocks;
                            if ($col < 1) {
                                /* Should not happen but a fail safe.
                                   Block will be small so good for screen shots when this happens. */
                                $col = 1;
                            }
                        }
                    }

                    if ($currentrow < $currentrequiredrow) {
                        $currentrow = $currentrequiredrow;
                    }

                    $bc->attributes['width'] = 'col-sm-' . $col;
                }

                if ($bc instanceof block_contents) {
                    $output .= $this->block($bc, $region);
                    $lastblock = $bc->title;
                } else if ($bc instanceof block_move_target) {
                    $output .= $this->block_move_target($bc, $zones, $lastblock, $region);
                } else {
                    throw new coding_exception(
                        'Unexpected type of thing (' . get_class($bc) . ') found in list of block contents.');
                }
            }
        }

        return $output;
    }

    /**
     * Prints a nice side block with an optional header.
     *
     * @param block_contents $bc HTML for the content
     * @param string $region the region the block is appearing in.
     * @return string the HTML to be output.
     */
    public function block(block_contents $bc, $region) {
        $bc = clone($bc); // Avoid messing up the object passed in.

        $editing = $this->page->user_is_editing();
        if (!$editing && (($region == 'marketing') || ($region == 'poster'))) {
            $bc->title = '';
        }

        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        } else {
            global $USER;
            $USER->foundation_user_pref['block' . $bc->blockinstanceid . 'hidden'] = PARAM_INT;
        }
        $id = !empty($bc->attributes['id']) ? $bc->attributes['id'] : uniqid('block-');
        $context = new stdClass();
        $context->skipid = $bc->skipid;
        $context->blockinstanceid = $bc->blockinstanceid;
        $context->dockable = $bc->dockable;
        $context->collapsible = $bc->collapsible;
        $context->id = $id;
        $context->hidden = $bc->collapsible == block_contents::HIDDEN;
        $context->skiptitle = strip_tags($bc->title);
        $context->showskiplink = !empty($context->skiptitle);
        $context->arialabel = $bc->arialabel;
        $context->ariarole = !empty($bc->attributes['role']) ? $bc->attributes['role'] : 'complementary';
        $context->class = $bc->attributes['class'];
        $context->type = $bc->attributes['data-block'];
        $context->title = $bc->title;
        $context->content = $bc->content;
        $context->annotation = $bc->annotation;
        $context->footer = $bc->footer;
        $context->hascontrols = !empty($bc->controls);
        if (!empty($bc->attributes['width'])) {
            $context->haswidth = true;
            $context->width = $bc->attributes['width'];
        } else {
            $context->haswidth = false;
            $context->width = '';
        }
        if ($context->hascontrols) {
            $context->controls = $this->block_controls($bc->controls, $id);
        }

        return $this->render_from_template('core/block', $context);
    }

    /**
     * Returns standard navigation between activities in a course.
     *
     * @return string the navigation HTML.
     */
    public function activity_navigation() {
        // First we should check if we want to add navigation.
        $context = $this->page->context;
        if (
            ($this->page->pagelayout !== 'incourse' && $this->page->pagelayout !== 'frametop')
            || $context->contextlevel != CONTEXT_MODULE
        ) {
            return '';
        }

        // Enabled?
        $toolbox = \theme_foundation\toolbox::get_instance();
        if (!$toolbox->get_setting('activitynavigationenabled')) {
            return '';
        }

        // If the activity is in stealth mode, show no links.
        if ($this->page->cm->is_stealth()) {
            return '';
        }

        // Get a list of all the activities in the course.
        $course = $this->page->cm->get_course();
        $modules = get_fast_modinfo($course->id)->get_cms();

        // Put the modules into an array in order by the position they are shown in the course.
        $mods = [];
        $activitylist = [];

        // Add a link to the main course page.
        $activitylist[course_get_url($course)->out(false)] = get_string('maincoursepage');

        foreach ($modules as $module) {
            // Only add activities the user can access, aren't in stealth mode and have a url (eg. mod_label does not).
            if (!$module->uservisible || $module->is_stealth() || empty($module->url)) {
                continue;
            }
            $mods[$module->id] = $module;

            // No need to add the current module to the list for the activity dropdown menu.
            if ($module->id == $this->page->cm->id) {
                continue;
            }
            // Module name.
            $modname = $module->get_formatted_name();
            // Display the hidden text if necessary.
            if (!$module->visible) {
                $modname .= ' ' . get_string('hiddenwithbrackets');
            }
            // Module URL.
            $linkurl = new url($module->url, ['forceview' => 1]);
            // Add module URL (as key) and name (as value) to the activity list array.
            $activitylist[$linkurl->out(false)] = $modname;
        }

        $nummods = count($mods);

        // If there is only one mod then do nothing.
        if ($nummods == 1) {
            return '';
        }

        // Get an array of just the course module ids used to get the cmid value based on their position in the course.
        $modids = array_keys($mods);

        // Get the position in the array of the course module we are viewing.
        $position = array_search($this->page->cm->id, $modids);

        $prevmod = null;
        $nextmod = null;

        // Check if we have a previous mod to show.
        if ($position > 0) {
            $prevmod = $mods[$modids[$position - 1]];
        }

        // Check if we have a next mod to show.
        if ($position < ($nummods - 1)) {
            $nextmod = $mods[$modids[$position + 1]];
        }

        $activitynav = new \theme_foundation\output\core_course\output\activity_navigation($prevmod, $nextmod, $activitylist);
        $renderer = $this->page->get_renderer('core', 'course');
        return $renderer->render($activitynav);
    }

    /**
     * Return the standard string that says whether you are logged in (and switched
     * roles/logged in as another user).
     * @param bool $withlinks if false, then don't include any links in the HTML produced.
     * If not set, the default is the nologinlinks option from the theme config.php file,
     * and if that is not set, then links are included.
     * @return string HTML fragment.
     */
    public function login_info($withlinks = null) {
        $loggedinas = parent::login_info($withlinks);

        $toolbox = \theme_foundation\toolbox::get_instance();
        $customlogouturl = $toolbox->get_setting('customlogouturl');
        if (!empty($customlogouturl)) {
             // Replace if there.
             $loggedinas = str_replace('/login/logout.php', '/theme/foundation/logout.php', $loggedinas);
        }

        return $loggedinas;
    }

    /**
     * Take a node in the nav tree and make an action menu out of it.
     * The links are injected in the action menu.
     *
     * @param action_menu $menu
     * @param navigation_node $node
     * @param boolean $indent
     * @param boolean $onlytopleafnodes
     * @return boolean nodesskipped - True if nodes were skipped in building the menu
     */
    protected function build_action_menu_from_navigation(
        action_menu $menu,
        navigation_node $node,
        $indent = false,
        $onlytopleafnodes = false
    ) {
        $skipped = false;
        // Build an action menu based on the visible nodes from this navigation tree.
        foreach ($node->children as $menuitem) {
            if ($menuitem->display) {
                if ($onlytopleafnodes && $menuitem->children->count()) {
                    $skipped = true;
                    continue;
                }
                if ($menuitem->action) {
                    if ($menuitem->action instanceof \action_link) {
                        $link = $menuitem->action;
                        // Give preference to setting icon over action icon.
                        if (!empty($menuitem->icon)) {
                            $link->icon = $menuitem->icon;
                        }
                    } else {
                        $link = new action_link($menuitem->action, $menuitem->text, null, null, $menuitem->icon);
                    }
                } else {
                    if ($onlytopleafnodes) {
                        $skipped = true;
                        continue;
                    }
                    $link = new action_link(new url('#'), $menuitem->text, null, ['disabled' => true], $menuitem->icon);
                }
                if ($indent) {
                    $link->add_class('ps-3'); // The changed line!
                }
                if (!empty($menuitem->classes)) {
                    $link->add_class(implode(" ", $menuitem->classes));
                }

                $menu->add_secondary_action($link);
                $skipped = $skipped || $this->build_action_menu_from_navigation($menu, $menuitem, true);
            }
        }
        return $skipped;
    }

    /**
     * Renders an mform element from a template.
     *
     * @param HTML_QuickForm_element $element element
     * @param bool $required if input is required field
     * @param bool $advanced if input is an advanced field
     * @param string $error error message to display
     * @param bool $ingroup True if this element is rendered as part of a group
     * @return mixed string|bool
     */
    public function mform_element($element, $required, $advanced, $error, $ingroup) {
        $templatename = 'core_form/element-' . $element->getType();
        if ($ingroup) {
            $templatename .= "-inline";
        }
        try {
            /* We call this to generate a file not found exception if there is no template.
               We don't want to call export_for_template if there is no template. */
            \theme_foundation\output\core\output\mustache_template_finder::get_template_filepath($templatename);

            if ($element instanceof \templatable) {
                $elementcontext = $element->export_for_template($this);

                $helpbutton = '';
                if (method_exists($element, 'getHelpButton')) {
                    $helpbutton = $element->getHelpButton();
                }
                $label = $element->getLabel();
                $text = '';
                if (method_exists($element, 'getText')) {
                    // There currently exists code that adds a form element with an empty label.
                    // If this is the case then set the label to the description.
                    if (empty($label)) {
                        $label = $element->getText();
                    } else {
                        $text = $element->getText();
                    }
                }

                // Generate the form element wrapper ids and names to pass to the template.
                // This differs between group and non-group elements.
                if ($element->getType() === 'group') {
                    // Group element.
                    // The id will be something like 'fgroup_id_NAME'. E.g. fgroup_id_mygroup.
                    $elementcontext['wrapperid'] = $elementcontext['id'];

                    // Ensure group elements pass through the group name as the element name.
                    $elementcontext['name'] = $elementcontext['groupname'];
                } else {
                    // Non grouped element.
                    // Creates an id like 'fitem_id_NAME'. E.g. fitem_id_mytextelement.
                    $elementcontext['wrapperid'] = 'fitem_' . $elementcontext['id'];
                }

                $context = [
                    'element' => $elementcontext,
                    'label' => $label,
                    'text' => $text,
                    'required' => $required,
                    'advanced' => $advanced,
                    'helpbutton' => $helpbutton,
                    'error' => $error,
                ];
                return $this->render_from_template($templatename, $context);
            }
        } catch (\Exception $e) {
            // No template for this element.
            /*
             * Note: Currently catches the 'element-link-inline' template when viewing the course settings page.
             * Happens in Boost too.
             */
            return false;
        }
    }

    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param boolean $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $CFG, $USER;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        /* Note: this behaviour is intended to match that of core_renderer::login_info,
           but should not be considered to be good practice; layout options are
           intended to be theme-specific. Please don't copy this snippet anywhere else. */
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = '';

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = get_string('loggedinnot', 'moodle');
            if (!$loginpage) {
                $returnstr .= " (<a href=\"$loginurl\">" . get_string('login') . '</a>)';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login nav-link'
                ),
                $usermenuclasses
            );
        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">" . get_string('login') . '</a>)';
            }

            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login nav-link'
                ),
                $usermenuclasses
            );
        }

        // Get some navigation opts.
        $opts = \user_get_user_navigation_info($user, $this->page, ['avatarsize' => 30]);

        $toolbox = \theme_foundation\toolbox::get_instance();
        $navbardisplay = $toolbox->navbardisplaysettings();

        $avatarcontents = '';
        $usertextcontents = '';
        if ($navbardisplay['navbardisplayicons']) {
            $avatarcontents .= html_writer::span($opts->metadata['useravatar'], 'avatar current');
        }
        if ($navbardisplay['navbardisplaytitles']) {
            $usertextcontents = $opts->metadata['userfullname'];
        }

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            if ($navbardisplay['navbardisplayicons']) {
                $avatarcontents .= html_writer::span(
                    $opts->metadata['realuseravatar'],
                    'avatar realuser'
                );
            }

            if ($navbardisplay['navbardisplaytitles']) {
                $usertextcontents = $opts->metadata['realuserfullname'];
                $usertextcontents .= html_writer::tag(
                    'span',
                    get_string(
                        'loggedinas',
                        'moodle',
                        html_writer::span(
                            $opts->metadata['userfullname'],
                            'value'
                        )
                    ),
                    ['class' => 'meta viewingas']
                );
            }
        }

        if ($navbardisplay['navbardisplaytitles']) {
            // Role.
            if (!empty($opts->metadata['asotherrole'])) {
                $role = \core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
                $usertextcontents .= html_writer::span(
                    $opts->metadata['rolename'],
                    'meta role role-' . $role
                );
            }

            // User login failures.
            if (!empty($opts->metadata['userloginfail'])) {
                $usertextcontents .= html_writer::span(
                    $opts->metadata['userloginfail'],
                    'meta loginfailures'
                );
            }

            // MNet.
            if (!empty($opts->metadata['asmnetuser'])) {
                $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
                $usertextcontents .= html_writer::span(
                    $opts->metadata['mnetidprovidername'],
                    'meta mnet mnet-' . $mnet
                );
            }
        }

        // Logout URL.  Only works when Foundation not in $CFG->themedir.
        $customlogouturl = $toolbox->get_setting('customlogouturl');
        if (!empty($customlogouturl)) {
            foreach ($opts->navitems as $object) {
                if (!empty($object->titleidentifier)) {
                    $titleidentifier = explode(',', $object->titleidentifier);
                    if ($titleidentifier[0] == 'logout') {
                        $foundationlogout = new url('/theme/foundation/logout.php', $object->url->params());
                        $object->url = $foundationlogout;
                        break;
                    }
                }
            }
        }

        $returntxt = '';
        if ($navbardisplay['navbardisplayicons']) {
            $avatarclasses = "avatars";
            $returntxt .= html_writer::span($avatarcontents, $avatarclasses);
        }

        if ($navbardisplay['navbardisplaytitles']) {
            $returntxt .= html_writer::span($usertextcontents, 'usertext me-1');
        }

        $returnstr .= html_writer::span($returntxt, 'userbutton');

        // Create a divider (well, a filler).
        $divider = new filler();
        $divider->primary = false;

        $am = new action_menu();
        $am->set_menu_trigger($returnstr, 'nav-link');
        $am->set_action_label(get_string('usermenu'));
        $am->set_menu_left();
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $value) {
                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, $value->title, null, ['class' => 'iconsmall']);
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                ['class' => 'iconsmall']
                            ) . $value->title;
                        }

                        $al = new link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            ['class' => 'icon']
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount - 1) {
                    $am->add($divider);
                }
            }
        }

        return html_writer::div(
            $this->render($am),
            $usermenuclasses
        );
    }

    /**
     * Returns the custom menu if one has been set
     *
     * A custom menu can be configured by browsing to a theme's settings page
     * and then configuring the custommenu config setting as described.
     *
     * Theme developers: DO NOT OVERRIDE! Please override function
     * {@see core_renderer::render_custom_menu()} instead.  Nah!
     *
     * @param string $custommenuitems - custom menuitems set by theme instead of global theme settings.
     * @return string Markup.
     */
    public function custom_menu($custommenuitems = '') {
        global $CFG;

        if (empty($custommenuitems) && !empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        }

        if (!empty($custommenuitems)) {
            $menu = new custom_menu();

            $menu->add_custom_menu_items($custommenuitems, current_language());

            return $this->render_our_custom_menu($menu);
        }

        return '';
    }

    /**
     * Renders the a menu.
     *
     * @param object $menu Menu branch.
     * @return string Markup if any.
     */
    public function render_the_menu($menu) {
        if (!$menu->has_children()) {
            return '';
        }

        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('core/custom_menu_item', $context);
        }

        return $content;
    }

    /**
     * Renders a custom menu object (located in outputcomponents.php)
     *
     * The custom menu this method produces makes use of the YUI3 menunav widget
     * and requires very specific html elements and classes.
     *
     * @param custom_menu $menu
     *
     * @return string
     */
    protected function render_our_custom_menu(custom_menu $menu) {
        return $this->render_the_menu($menu);
    }

    /**
     * Renders the language menu.
     *
     * @return string
     */
    public function render_lang_menu() {
        global $CFG;

        if (empty($CFG->langmenu)) {
            return '';
        }

        if ($this->page->course != SITEID && !empty($this->page->course->lang)) {
            // Do not show lang menu if language forced.
            return '';
        }

        $toolbox = \theme_foundation\toolbox::get_instance();
        $headerlangmenu = $toolbox->get_setting('headerlangmenu');
        $headerlangmenu = explode(',', $headerlangmenu);
        if (!in_array($this->page->pagelayout, $headerlangmenu)) {
            // Only show the lang menu if the layout specifies it.
            return '';
        }

        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2) {
            return '';
        }

        $menu = new custom_menu_item('');
        $strlang = get_string('language');
        $currentlangcode = current_language();
        if (isset($langs[$currentlangcode])) {
            $currentlang = html_writer::tag('span', $langs[$currentlangcode], ['class' => 'd-none d-md-inline']) .
                html_writer::tag('span', $currentlangcode, ['class' => 'd-md-none']);
        } else {
            $currentlang = $strlang;
        }
        $this->language = $menu->add($currentlang, new url('#'), $strlang, 10000);
        foreach ($langs as $langtype => $langname) {
            $this->language->add($langname, new url($this->page->url, ['lang' => $langtype]), $langname);
        }

        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('core/custom_menu_item', $context);
        }

        return $content;
    }

    /**
     * Render the primary menu.
     *
     * returns string Markup.
     */
    public function primary_menu() {
        $toolbox = \theme_foundation\toolbox::get_instance();
        $nodisplaymycourses = ($toolbox->get_setting('displaymycourses') < 2);
        $navbardisplay = $toolbox->navbardisplaysettings();

        // See lib/classes/navigation/output/primary.php.
        $menudata = [];
        foreach ($this->page->primarynav->children as $node) {
            if ($navbardisplay['navbardisplayicons']) {
                switch ($node->key) {
                    case 'siteadminnode':
                        $iconmarkup = $toolbox->getfontawesomemarkup('wrench', ['icon'], [], '', $node->text);
                        break;
                    case 'myhome':
                        $iconmarkup = $toolbox->getfontawesomemarkup('dashboard', ['icon'], [], '', $node->text);
                        break;
                    case 'courses':
                        if ($nodisplaymycourses) {
                            continue 2;
                        }
                        $iconmarkup = $toolbox->getfontawesomemarkup('briefcase', ['icon'], [], '', $node->text);
                        break;
                    default:
                        $subpix = new pix_icon_fontawesome($node->icon);
                        $icondata = $subpix->export_for_template($this);
                        if (!$subpix->is_mapped()) {
                            $icondata['unmappedIcon'] = $node->icon->export_for_template($this);
                            $iconmarkup = $this->render_from_template('theme_foundation/pix_icon_fontawesome', $icondata);
                        } else {
                            $classes = ['icon', $icondata['key']];
                            $iconmarkup = $toolbox->getfontawesomemarkup('', $classes, [], '', $node->text);
                        }
                }
            }

            $menuitem = [
                'url' => $node->action(),
                'title' => $node->text,
                'isactive' => $node->isactive,
                'key' => $node->key,
            ];

            if ($navbardisplay['navbardisplayicons']) {
                $menuitem['icon'] = $iconmarkup;
            }
            if ($navbardisplay['navbardisplaytitles']) {
                $menuitem['text'] = $node->text;
            }

            $menudata[] = $menuitem;
        }

        $primarymenu = new \core\navigation\output\more_menu((object) $menudata, 'navbar-nav', false);
        $templatecontext = $primarymenu->export_for_template($this);

        return $this->render_from_template('theme_foundation/partials/primarymenu', $templatecontext);
    }

    /**
     * This renders the navbar.
     * Improved on core not to output any markup if no items.
     *
     * @return string Markup if any.
     */
    public function navbar() {
        $output = '';
        $navbaritems = $this->page->navbar->get_items();
        if (!empty($navbaritems)) {
            $toolbox = \theme_foundation\toolbox::get_instance();
            if (!$toolbox->get_setting('breadcrumbdisplaythiscourse')) {
                global $CFG;
                $removemycourses = true;
                if ($CFG->defaulthomepage == HOMEPAGE_MYCOURSES) {
                    $removemycourses = false;
                } else if ($CFG->defaulthomepage == HOMEPAGE_USER) {
                    global $USER;
                    if (
                        (!empty($USER->preference['user_home_page_preference'])) &&
                        ($USER->preference['user_home_page_preference'] == HOMEPAGE_MYCOURSES)) {
                        $removemycourses = false;
                    }
                }
                if ($removemycourses) {
                    $replacementnavbaritems = [];
                    foreach ($navbaritems as $navbaritem) {
                        if (!empty($navbaritem->key)) {
                            if ($navbaritem->key != 'mycourses') {
                                $replacementnavbaritems[] = $navbaritem;
                            } // Else it is mycourses so don't add it back in.
                        } else {
                            $replacementnavbaritems[] = $navbaritem;
                        }
                    }
                    $navbaritems = $replacementnavbaritems;
                }
            }
            $navbar = new stdClass();
            $navbar->get_items = $navbaritems;
            $output .= $this->render_from_template('core/navbar', $navbar);
        }
        return $output;
    }

    /**
     * Renders a navigation node object.
     *
     * @param navigation_node $item The navigation node to render.
     * @return string HTML fragment
     */
    protected function render_navigation_node(navigation_node $item) {
        // Action link template uses the 'pix' mustache helper for the icon.
        if ($item->action instanceof action_link) {
            $item->hideicon = true;
        }
        return parent::render_navigation_node($item);
    }
}
