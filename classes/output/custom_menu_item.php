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
 * Foundation's custom menu item.
 *
 * @package    theme_foundation
 * @copyright  2025 G J Barnard
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\output;

use context_system;
use core\output\custom_menu_item as core_custom_menu_item;
use core\output\renderer_base;
use core\url;
use moodle_url;
use stdClass;

/**
 * Foundations's custom menu item.
 */
class custom_menu_item extends core_custom_menu_item {
    /**
     * Adds a custom menu item as a child of this node given its properties.
     *
     * @param string $text
     * @param null|moodle_url $url
     * @param string $title
     * @param int $sort
     * @param array $attributes Array of other HTML attributes for the custom menu item.
     * @return custom_menu_item
     */
    public function add(
        $text,
        ?moodle_url $url = null,
        $title = null,
        $sort = null,
        $attributes = [],
    ) {
        $key = count($this->children);
        if (empty($sort)) {
            $sort = $this->lastsort + 1;
        }
        $this->children[$key] = new custom_menu_item($text, $url, $title, $sort, $this, $attributes);
        $this->lastsort = (int)$sort;
        return $this->children[$key];
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output Used to do a final render of any components that need to be rendered for export.
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $syscontext = context_system::instance();

        $context = new stdClass();
        $context->moremenuid = uniqid();
        $context->text = \core_external\util::format_text($this->text, null, $syscontext->id)[0];
        $context->url = $this->url ? $this->url->out() : null;
        // No need for the title if it's the same with text.
        if ($this->text !== $this->title) {
            // Show the title attribute only if it's different from the text.
            $context->title = \core_external\util::format_string($this->title, $syscontext->id);
        }
        $context->sort = $this->sort;
        if (!empty($this->attributes)) {
            $context->attributes = $this->attributes;
        }
        $context->children = [];
        if (preg_match("/^#+$/", $this->text)) {
            $context->divider = true;
        }
        $context->haschildren = !empty($this->children) && (count($this->children) > 0);
        foreach ($this->children as $child) {
            $child = $child->export_for_template($output);
            array_push($context->children, $child);
        }

        return $context;
    }
}
