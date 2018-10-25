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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\output;

defined('MOODLE_INTERNAL') || die();

trait core_renderer_toolbox {

    public function render_page() {
        echo $this->doctype();

        $mustache = $this->page->theme->layouts[$this->page->pagelayout]['mustache'];
        $blockshtml = $this->blocks('side-pre');
        $hasblocks = strpos($blockshtml, 'data-block=') !== false;
        $bodyattributes = $this->body_attributes();
        $regionmainsettingsmenu = $this->region_main_settings_menu();

        $data = new \stdClass();
        $data->output = $this;
        $data->sidepreblocks = $blockshtml;
        $data->hasblocks = $hasblocks;
        $data->bodyattributes = $bodyattributes;
        $data->regionmainsettingsmenu = $regionmainsettingsmenu;
        $data->hasregionmainsettingsmenu = !empty($regionmainsettingsmenu);

        echo $this->render_from_template('theme_foundation/' . $mustache, $data);
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

            if ($element instanceof templatable) {
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
        } catch (Exception $e) {
            // No template for this element.
            return false;
        }
    }
}