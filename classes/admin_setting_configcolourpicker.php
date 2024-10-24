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
 * @copyright  2021 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation;

/**
 * Colour picker that allows you to state 'use default' and not apply the setting.
 */
class admin_setting_configcolourpicker extends \admin_setting_configcolourpicker {
    /** @var string default colour */
    protected $defaultcolour;

    /** @var string classname */
    protected $classname;

    /**
     * Config colour picker constructor.
     *
     * @param string $name
     * @param string $visiblename
     * @param string $description
     * @param string $defaultsetting
     * @param string/array $defaultcolour default colour in hex or
     *                     Array('colour' => '#ffaabb', 'selector' => 'body', 'element' => 'backgroundColor').
     * @param string $classname if not null then will be a string with a class name to create a hidden element to use.
     * @param array $previewconfig Array('selector'=>'.some .css .selector','style'=>'backgroundColor');
     * @param boolean $usedefaultwhenempty true or false.
     */
    public function __construct(
        $name,
        $visiblename,
        $description,
        $defaultsetting,
        $defaultcolour,
        $classname = null,
        ?array $previewconfig = null,
        $usedefaultwhenempty = true
    ) {
        $this->previewconfig = $previewconfig;
        $this->usedefaultwhenempty = $usedefaultwhenempty;
        $this->defaultcolour = $defaultcolour;
        $this->classname = $classname;
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
    public function output_html($data, $query = '') {
        global $PAGE, $OUTPUT;

        $id = $this->get_id();
        $PAGE->requires->js('/theme/foundation/js/fd_colourpopup.js');
        $PAGE->requires->js_init_call('M.util.init_fdcolour_popup', [$id]);
        $initvalue = [];
        if (empty($data)) {
            $data = '-';
        }
        if ($data[0] == '-') {
            if (is_array($this->defaultcolour)) {
                $initvalue = $this->defaultcolour;
            } else {
                $initvalue['colour'] = $this->defaultcolour;
            }
        } else {
            $initvalue['colour'] = $data;
        }

        $initvalue = json_encode($initvalue);

        $context = (object) [
            'id' => $this->get_id(),
            'name' => $this->get_full_name(),
            'value' => $data,
            'initvalue' => $initvalue,
            'haspreviewconfig' => !empty($this->previewconfig),
            'forceltr' => $this->get_force_ltr(),
            'readonly' => $this->is_readonly(),
        ];

        if (!empty($this->classname)) {
            $context->classname = $this->classname;
        }

        $element = $OUTPUT->render_from_template('theme_foundation/admin_setting_configcolourpicker', $context);

        return format_admin_setting(
            $this,
            $this->visiblename,
            $element,
            $this->description,
            true,
            '',
            $this->get_defaultsetting(),
            $query
        );
    }
}
