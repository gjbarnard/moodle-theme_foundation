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
        echo $this->doctype();

        $mustache = $this->page->theme->layouts[$this->page->pagelayout]['mustache'];
        $data = new \stdClass();
        $data->output = $this;
        $bodyclasses = array();
        $regionmainsettingsmenu = $this->region_main_settings_menu();

        if (!empty($this->page->theme->layouts[$this->page->pagelayout]['regions'])) {
            $drawerblockshtml = $this->blocks('drawer');
            $hasdrawerblocks = ((strpos($drawerblockshtml, 'data-block=') !== false) or ($this->page->user_is_editing()));
            $preblockshtml = $this->blocks('side-pre');
            $haspreblocks = strpos($preblockshtml, 'data-block=') !== false;

            $data->drawerblocks = $drawerblockshtml;
            $data->hasdrawerblocks = $hasdrawerblocks;
            $data->sidepreblocks = $preblockshtml;
            $data->haspreblocks = $haspreblocks;

            if ($hasdrawerblocks) {
                \user_preference_allow_ajax_update('drawerclosed', PARAM_BOOL);
                $data->drawerclosed = get_user_preferences('drawerclosed', true);
                if (!$data->drawerclosed) {
                    $bodyclasses[] = 'drawer-open';
                }
            }
        }

        $toolbox = \theme_foundation\toolbox::get_instance();
        $data->thealerts = array();
        $featuresmodule = $toolbox->get_module('features');
        if (!empty($featuresmodule)) {
            $featuresdata = $featuresmodule->export_for_template($this);
            if (!empty($featuresdata)) {
                foreach($featuresdata as $fkey => $fvalue) {
                    $data->$fkey = $fvalue;
                }
            }
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

        echo $this->render_from_template('theme_foundation/'.$mustache, $data);
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
                    'login'
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
                    'login'
                ),
                $usermenuclasses
            );
        }

        // Get some navigation opts.
        $opts = \user_get_user_navigation_info($user, $this->page);

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
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
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
            foreach ($opts->navitems as $key => $value) {

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
}