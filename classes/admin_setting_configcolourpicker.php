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
 * @package    theme_foundation
 * @copyright  &copy; 2021-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_foundation;

defined('MOODLE_INTERNAL') || die();

use html_writer;

/**
 * Colour picker that allows you to state 'use default' and not apply the setting.
 *
 * @copyright  &copy; 2021-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class admin_setting_configcolourpicker extends \admin_setting_configcolourpicker {

    /** @var string default colour */
    public $defaultcolour;

    /**
     * Config colour picker constructor.
     *
     * @param string $name
     * @param string $visiblename
     * @param string $description
     * @param string $defaultsetting
     * @param string $defaultcolour default colour
     * @param array $previewconfig Array('selector'=>'.some .css .selector','style'=>'backgroundColor');
     */
    public function __construct($name, $visiblename, $description, $defaultsetting, $defaultcolour,
            array $previewconfig = null, $usedefaultwhenempty = true) {
        $this->previewconfig = $previewconfig;
        $this->usedefaultwhenempty = $usedefaultwhenempty;
        $this->defaultcolour = $defaultcolour;
        parent::__construct($name, $visiblename, $description, $defaultsetting);
        $this->set_force_ltr(true);
    }

    /**
     * Validates the colour that was entered by the user
     *
     * @param string $data
     * @return string|false
     */
    protected function validate($data) {
        if (!empty($data) && ($data[0] == '-')) {
            return '';
        }
        return parent::validate($data);
    }

    /**
     * Returns XHTML select field and wrapping div(s)
     *
     * @see output_select_html()
     *
     * @param string $data the option to show as selected
     * @param string $query
     * @return string XHTML field and wrapping div
     */
    public function output_html($data, $query='') {
        global $PAGE;
        $id = $this->get_id();
        $PAGE->requires->js('/theme/foundation/js/fd_colourpopup.js');
        $PAGE->requires->js_init_call('M.util.init_fdcolour_popup', array($id));
        if (!empty($data)) {
            if ($data[0] == '-') {
                $colour = $this->defaultcolour;
            } else {
                $colour = $data;
            }
        } else {
            $data = '-';
            $colour = $this->defaultcolour;
        }

        $element = '<div class="form-colourpicker defaultsnext">';
        //$element .= '<div class="form-control-static clearfix">';
        $element .= "<input size='5' name='" . $this->get_full_name() . "' value='" . $data . "' initvalue='".$colour."' id='{$id}' type='text' class='form-control text-ltr'>";
        $element .= html_writer::tag('span', '&nbsp;', array(
            'id' => 'colpicked_'.$id,
            'class' => 'fdcolourpopupbox',
            'tabindex' => '-1',
            'style' => 'background-color: '.$colour.'; border: 1px solid #000; cursor: pointer; margin: 0 0 0 2px; padding: 0 8px;')
        );
        $element .= html_writer::start_tag('div', array(
            'id' => 'colpick_' . $id,
            'style' => "display: none; position: absolute; z-index: 500;",
            'class' => 'fdcolourpopupsel form-colourpicker defaultsnext'));
        $element .= html_writer::tag('div', '', array('class' => 'admin_colourpicker clearfix'));
        $element .= html_writer::end_tag('div');

        $element .= '</div>';
        /* global $PAGE, $OUTPUT;

        if (!empty($data)) {
            if ($data[0] == '-') {
                $colour = $this->getAttribute('defaultcolour');
            } else {
                $colour = $data;
            }
        } else {
            $data = '-';
            $colour = $this->getAttribute('defaultcolour');
        }
        
        $icon = new pix_icon('i/loading', get_string('loading', 'admin'), 'moodle', ['class' => 'loadingicon']);
        $context = (object) [
            'id' => $this->get_id(),
            'name' => $this->get_full_name(),
            'value' => $data,
            'icon' => $icon->export_for_template($OUTPUT),
            'haspreviewconfig' => !empty($this->previewconfig),
            'forceltr' => $this->get_force_ltr(),
            'readonly' => $this->is_readonly(),
        ];

        $element = $OUTPUT->render_from_template('core_admin/setting_configcolourpicker', $context);
        $PAGE->requires->js_init_call('M.util.init_colour_picker', array($this->get_id(), $this->previewconfig));
        */

        return format_admin_setting($this, $this->visiblename, $element, $this->description, true, '',
            $this->get_defaultsetting(), $query);
    }
}
