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
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\output;

defined('MOODLE_INTERNAL') || die();

use block_contents;
use html_writer;

/**
 * The core renderer toolbox.
 *
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
trait core_renderer_toolbox {

    /**
     * Orchestrates the rendering of the page.
     */
    public function render_page() {
        global $CFG, $COURSE, $USER;

        $toolbox = \theme_foundation\toolbox::get_instance();
        $mustache = $this->page->theme->layouts[$this->page->pagelayout]['mustache'];
        $trio = (!empty($toolbox->get_setting('trio')));
        if ($trio && ($mustache == 'columns2')) {
            $mustache = 'columns3';
        }

        $data = new \stdClass();
        $data->output = $this;
        $bodyclasses = array();
        $regionmainsettingsmenu = $this->region_main_settings_menu();

        if ($this->page->include_region_main_settings_in_header_actions() &&
                !$this->page->blocks->is_block_present('settings')) {
            // Only include the region main settings if the page has requested it and it doesn't already have
            // the settings block on it. The region main settings are included in the settings block and
            // duplicating the content causes behat failures.
            $this->page->add_header_action(html_writer::div(
                $regionmainsettingsmenu,
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        if (!empty($this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
            if (in_array('drawer', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                $drawerblockshtml = $this->blocks('drawer');
                $hasdrawerblocks = ((strpos($drawerblockshtml, 'data-block=') !== false) or ($this->page->user_is_editing()));

                $data->drawerblocks = $drawerblockshtml;
                $data->hasdrawerblocks = $hasdrawerblocks;

                if ($hasdrawerblocks) {
                    \user_preference_allow_ajax_update('drawerclosed', PARAM_BOOL);
                    $data->drawerclosed = get_user_preferences('drawerclosed', true);
                    if (!$data->drawerclosed) {
                        $bodyclasses[] = 'drawer-open';
                    }
                }
            }

            if (in_array('poster', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                $posterblockshtml = $this->blocks('poster');
                $hasposterblocks = ((strpos($posterblockshtml, 'data-block=') !== false) or ($this->page->user_is_editing()));

                $data->posterblocks = $posterblockshtml;
                $data->hasposterblocks = $hasposterblocks;
            }

            if (in_array('marketing', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                $hblockshtml = $this->hblocks('marketing');
                $hashblocks = ((strpos($hblockshtml, 'data-block=') !== false) or ($this->page->user_is_editing()));

                $data->hblocks = $hblockshtml;
                $data->hashblocks = $hashblocks;
            }

            if (in_array('side-pre', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                $preblockshtml = $this->blocks('side-pre');
                $haspreblocks = ((strpos($preblockshtml, 'data-block=') !== false) or ($this->page->user_is_editing()));

                $data->sidepreblocks = $preblockshtml;
                $data->haspreblocks = $haspreblocks;
            }

            if (in_array('side-post', $this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
                $postblockshtml = $this->blocks('side-post');
                $haspostblocks = ((strpos($postblockshtml, 'data-block=') !== false) or ($this->page->user_is_editing()));

                $data->sidepostblocks = $postblockshtml;
                $data->haspostblocks = $haspostblocks;
            }
        }

        $modules = $toolbox->get_modules();
        foreach ($modules as $module) {
            if (method_exists ($module, 'export_for_template')) {
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
        }
        $data->navbarposition = $toolbox->get_setting('navbarposition');
        if (empty($data->navbarposition)) {
            $data->navbarposition = 'top';
        }
        $bodyclasses[] = 'navbar-'.$data->navbarposition;
        $data->navbarbottom = ($data->navbarposition == 'bottom');

        if ((!empty($USER->auth)) && ($USER->auth == 'lti')) {
            $data->ltiauth = true;
        }

        if (empty($COURSE->visible)) {
            $bodyclasses[] = 'course-hidden';
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
        require_once($CFG->dirroot.'/course/format/lib.php');  // For course_get_format() call?  Not sure if needed.
        $data->contextheadersettingsmenu = $this->context_header_settings_menu();
        $data->hascontextheadersettingsmenu = !empty($data->contextheadersettingsmenu);
        $data->fav = !empty($toolbox->get_setting('fav'));

        echo $this->render_from_template('theme_foundation/'.$mustache, $data);
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        return \theme_foundation\toolbox::get_instance()->get_module('header')->header($this);
    }

    /**
     * Orchestrates the rendering of a plain page.
     */
    public function render_plain_page() {
        $mustache = $this->page->theme->layouts[$this->page->pagelayout]['mustache'];
        $data = new \stdClass();
        $data->output = $this;
        $data->fakeblocks = $this->blocks('side-pre', array(), 'aside', true);
        $data->hasfakeblocks = strpos($data->fakeblocks, 'data-block="_fake"') !== false;

        echo $this->render_from_template('theme_foundation/'.$mustache, $data);
    }

    /**
     * Outputs the opening section of a box.
     *
     * @param string $classes A space-separated list of CSS classes
     * @param string $id An optional ID
     * @param array $attributes An array of other attributes to give the box.
     * @return string the HTML to output.
     */
    public function box_start($classes = 'generalbox', $id = null, $attributes = array()) {
        $this->opencontainers->push('box', html_writer::end_tag('div'));
        $attributes['id'] = $id;
        $attributes['class'] = 'box ' . \renderer_base::prepare_classes($classes);
        return html_writer::start_tag('div', $attributes);
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
    public function blocks($region, $classes = array(), $tag = 'aside', $fakeblocksonly = false) {
        $displayregion = $this->page->apply_theme_region_manipulations($region);
        $classes = (array)$classes;
        $classes[] = 'block-region';
        $content = '';

        if ($this->page->user_is_editing()) {
            $content .= $this->block_region_title($displayregion);
        }

        $attributes = array(
            'id' => 'block-region-'.preg_replace('#[^a-zA-Z0-9_\-]+#', '-', $displayregion),
            'class' => join(' ', $classes),
            'data-blockregion' => $displayregion,
            'data-droptarget' => '1'
        );

        if ($this->page->blocks->region_has_content($displayregion, $this)) {
            $content .= $this->blocks_for_region($displayregion, $fakeblocksonly);
        } else {
            $content .= '';
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
    public function hblocks($region, $classes = array(), $tag = 'aside') {
        $classes = (array)$classes;
        $classes[] = 'block-region row hblocks';
        $editing = $this->page->user_is_editing();
        $content = '';

        $toolbox = \theme_foundation\toolbox::get_instance();
        $blocksperrow = $toolbox->get_setting('blocksperrow');
        if (($blocksperrow > 6) || ($blocksperrow < 1)) {
            $blocksperrow = 4;
        }

        if ($editing) {
            $classes[] = 'editing bpr-'.$blocksperrow;
            $content .= $this->block_region_title($region);
        }

        $attributes = array(
            'id' => 'block-region-'.preg_replace('#[^a-zA-Z0-9_\-]+#', '-', $region),
            'class' => join(' ', $classes),
            'data-blockregion' => $region,
            'data-droptarget' => '1'
        );

        if ($this->page->blocks->region_has_content($region, $this)) {
            $content .= $this->hblocks_for_region($region, $editing, $blocksperrow);
        } else {
            $content .= '';
        }

        return html_writer::tag($tag, $content, $attributes);
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
            get_string('region-'.$region, 'theme_foundation'),
            array('class' => 'block-region-title col-12 text-center font-italic font-weight-bold')
        );
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
        $blocks = $this->page->blocks->get_blocks_for_region($region);
        $lastblock = null;
        $zones = array();
        foreach ($blocks as $block) {
            $zones[] = $block->title;
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

                    $bc->attributes['width'] = 'col-sm-'.$col;
                }

                if ($bc instanceof block_contents) {
                    $output .= $this->block($bc, $region);
                    $lastblock = $bc->title;
                } else if ($bc instanceof block_move_target) {
                    $output .= $this->block_move_target($bc, $zones, $lastblock, $region);
                } else {
                    throw new coding_exception('Unexpected type of thing ('.get_class($bc).') found in list of block contents.');
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
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        } else {
            user_preference_allow_ajax_update('block'.$bc->blockinstanceid.'hidden', PARAM_INT);
        }
        $id = !empty($bc->attributes['id']) ? $bc->attributes['id'] : uniqid('block-');
        $context = new \stdClass();
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
        $templatename = 'core_form/element-'.$element->getType();
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

                $context = array(
                    'element' => $elementcontext,
                    'label' => $label,
                    'text' => $text,
                    'required' => $required,
                    'advanced' => $advanced,
                    'helpbutton' => $helpbutton,
                    'error' => $error
                );
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
        global $USER, $CFG;
        require_once($CFG->dirroot.'/user/lib.php');

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

        $returnstr = "";

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
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
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
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
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
        $opts = \user_get_user_navigation_info($user, $this->page, array('avatarsize' => 30));

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = $opts->metadata['userfullname'];

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
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
                array('class' => 'meta viewingas')
            );
        }

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

        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext mr-1').
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider (well, a filler).
        $divider = new \action_menu_filler();
        $divider->primary = false;

        $am = new \action_menu();
        $am->set_menu_trigger(
            $returnstr, 'nav-link'
        );
        $am->set_action_label(get_string('usermenu'));
        $am->set_alignment(\action_menu::TR, \action_menu::BR);
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
                            $pix = new \pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ).$value->title;
                        }

                        $al = new \action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
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
     * Returns the url of the custom favicon.
     */
    public function favicon() {
        /* See: https://moodle.org/mod/forum/discuss.php?d=371252#p1516474 and change if theme_config::setting_file_url
           changes.
           Need to do: $url = preg_replace('|^https?://|i', '//', $url->out(false)); separately as the tool_provider of
           the LTI tool does this in a different way. */
        $toolbox = \theme_foundation\toolbox::get_instance();
        $favicon = $toolbox->get_setting_moodle_url('favicon');

        if (empty($favicon)) {
            return $this->page->theme->image_url('favicon', 'theme');
        } else {
            return $favicon;
        }
    }

    /**
     * Renders the course menu.
     *
     * @param course_menu_item $menu Menu branch to add the course to.
     * @return string Markup if any.
     */
    public function render_the_course_menu(course_menu_item $menu) {
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
    protected function render_custom_menu(\custom_menu $menu) {
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
     * Renders the language menu.
     *
     * @return string
     */
    public function render_lang_menu() {
        global $CFG;

        if (empty($CFG->langmenu)) {
            return '';
        }

        if ($this->page->course != SITEID and !empty($this->page->course->lang)) {
            // Do not show lang menu if language forced.
            return '';
        }

        if (empty($this->page->theme->layouts[$this->page->pagelayout]['options']['langmenu'])) {
            // Only show the lang menu if the layout specifies it.
            return '';
        }

        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2) {
            return '';
        }

        $menu = new course_menu_item('');
        $strlang = get_string('language');
        $currentlangcode = current_language();
        if (isset($langs[$currentlangcode])) {
            $currentlang = html_writer::tag('span', $langs[$currentlangcode], array('class' => 'd-none d-md-inline')).
                html_writer::tag('span', $currentlangcode, array('class' => 'd-md-none'));
        } else {
            $currentlang = $strlang;
        }
        $this->language = $menu->add($currentlang, new \moodle_url('#'), $strlang, 10000);
        foreach ($langs as $langtype => $langname) {
            $this->language->add($langname, new \moodle_url($this->page->url, array('lang' => $langtype)), $langname);
        }

        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('core/custom_menu_item', $context);
        }

        return $content;
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
            $navbar = new \stdClass();
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
    protected function render_navigation_node(\navigation_node $item) {
        // Action link template uses the 'pix' mustache helper for the icon.
        if ($item->action instanceof \action_link) {
            $item->hideicon = true;
        }
        return parent::render_navigation_node($item);
    }
}