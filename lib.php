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

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_foundation_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    static $theme = null;
    if (empty($theme)) {
        $theme = theme_config::load('foundation');
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        // By default, theme files must be cache-able by both browsers and proxies.  From 'More' theme.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        if ($filearea === 'logo') {
            return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
        } else if (preg_match("/^frontpageslideimage[1-9][0-9]*$/", $filearea)) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else if ($filearea === 'headerbackground') {
            return $theme->setting_file_serve('headerbackground', $args, $forcedownload, $options);
        } else if ($filearea === 'hvp') {
            theme_foundation_serve_hvp_css($args[1], $theme);
        } else if ($filearea === 'loginbackground') {
            return $theme->setting_file_serve('loginbackground', $args, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}

/**
 * Serves the H5P Custom CSS.
 *
 * @param string $filename The filename.
 * @param theme_config $theme The theme config object.
 */
function theme_foundation_serve_hvp_css($filename, $theme) {
    global $CFG, $PAGE;
    require_once($CFG->dirroot . '/lib/configonlylib.php'); // For 'min_enable_zlib_compression' function.

    $toolbox = \theme_foundation\toolbox::get_instance();
    $PAGE->set_context(context_system::instance());
    $themename = $theme->name;

    $content = '';
    $hvpfontcss = $toolbox->get_setting('hvpfontcss', $themename);
    if (!empty($hvpfontcss)) {
        // Code adapted from post_process() in the theme_config object.
        if (preg_match_all('/\[\[font:([a-z0-9_]+\|)?([^\]]+)\]\]/', $hvpfontcss, $matches, PREG_SET_ORDER)) {
            $replaced = [];
            foreach ($matches as $match) {
                if (isset($replaced[$match[0]])) {
                    continue;
                }
                $replaced[$match[0]] = true;
                $fontname = $match[2];
                $component = rtrim($match[1], '|');
                $fonturl = $theme->font_url($fontname, $component)->out(false);
                // We do not need full url because the font.php is always in the same dir.
                $fonturl = preg_replace('|^http.?://[^/]+|', '', $fonturl);
                $hvpfontcss = str_replace($match[0], $fonturl, $hvpfontcss);
            }

            $content .= $hvpfontcss . PHP_EOL . PHP_EOL;
        }
    }

    $content .= $toolbox->get_setting('hvpcustomcss', $themename);
    $md5content = md5($content);
    $md5stored = get_config('theme_' . $themename, 'hvpccssmd5');
    if ((empty($md5stored)) || ($md5stored != $md5content)) {
        // Content changed, so the last modified time needs to change.
        set_config('hvpccssmd5', $md5content, 'theme_' . $themename);
        $lastmodified = time();
        set_config('hvpccsslm', $lastmodified, 'theme_' . $themename);
    } else {
        $lastmodified = get_config('theme_' . $themename, 'hvpccsslm');
        if (empty($lastmodified)) {
            $lastmodified = time();
        }
    }

    // Sixty days only - the revision may get incremented quite often.
    $lifetime = 60 * 60 * 24 * 60;

    header('HTTP/1.1 200 OK');

    header('Etag: "' . $md5content . '"');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Pragma: ');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Accept-Ranges: none');
    header('Content-Type: text/css; charset=utf-8');
    if (!min_enable_zlib_compression()) {
        header('Content-Length: ' . strlen($content));
    }

    echo $content;

    die;
}

/**
 * Gets the pre SCSS for the theme.
 *
 * @param theme_config $theme The theme configuration object.
 * @return string SCSS.
 */
function theme_foundation_pre_scss($theme) {
    $toolbox = \theme_foundation\toolbox::get_instance();
    return $toolbox->pre_scss('foundation');
}

/**
 * Gets the extra SCSS for the theme.
 *
 * @param theme_config $theme The theme configuration object.
 * @return string SCSS.
 */
function theme_foundation_extra_scss($theme) {
    $toolbox = \theme_foundation\toolbox::get_instance();
    return $toolbox->extra_scss('foundation');
}

/**
 * Get the compiled css.
 *
 * @return string The compiled css.
 */
function theme_foundation_get_precompiled_css() {
    global $CFG;

    if (file_exists("{$CFG->dirroot}/theme/foundation/style/fallback.css")) {
        $filecontents = file_get_contents($CFG->dirroot . '/theme/foundation/style/fallback.css');
    } else if (!empty($CFG->themedir) && file_exists("{$CFG->themedir}/foundation/style/fallback.css")) {
        $filecontents = file_get_contents($CFG->themedir . '/foundation/style/fallback.css');
    } else {
        $filecontents = '';
    }

    return $filecontents;
}

/**
 * Override the core_output_load_template function to use our Mustache template finder.
 *
 * Info on: https://docs.moodle.org/dev/Miscellaneous_callbacks#override_webservice_execution
 *
 * @param stdClass $function Function details.
 * @param array $params Parameters
 *
 * @return boolean Success.
 */
function theme_foundation_override_webservice_execution($function, $params) {
    // Check if it's the function we want to override.
    if ($function->name === 'core_output_load_template') {
        // Call our load template function in our class instead of $function->classname.
        return call_user_func_array(['theme_foundation\output\external', $function->methodname], $params);
    }

    return false;
}

/**
 * Extend the course navigation.
 *
 * Ref: MDL-69249.
 *
 * @param navigation_node $coursenode The navigation node.
 * @param stdClass $course The course.
 * @param context_course $coursecontext The course context.
 */
function theme_foundation_extend_navigation_course($coursenode, $course, $coursecontext) {
    global $PAGE;

    if (($PAGE->theme->name == 'foundation') && ($PAGE->user_allowed_editing())) {
        // Add the turn on/off settings.
        if ($PAGE->url->compare(new \core\url('/course/view.php'), URL_MATCH_BASE)) {
            // We are on the course page, retain the current page params e.g. section.
            $baseurl = clone($PAGE->url);
            $baseurl->param('sesskey', sesskey());
        } else {
            // Edit on the main course page.
            $baseurl = new \core\url('/course/view.php', ['id' => $course->id,
                'return' => $PAGE->url->out_as_local_url(false), 'sesskey' => sesskey()]);
        }

        $editurl = clone($baseurl);
        if ($PAGE->user_is_editing()) {
            $editurl->param('edit', 'off');
            $editstring = get_string('turneditingoff');
        } else {
            $editurl->param('edit', 'on');
            $editstring = get_string('turneditingon');
        }

        $childnode = navigation_node::create($editstring, $editurl, navigation_node::TYPE_SETTING, null,
            'turneditingonoff', new pix_icon('i/edit', ''));
        $keylist = $coursenode->get_children_key_list();
        if (!empty($keylist)) {
            if (count($keylist) > 1) {
                $beforekey = $keylist[1];
            } else {
                $beforekey = $keylist[0];
            }
        } else {
            $beforekey = null;
        }
        $coursenode->add_node($childnode, $beforekey);
    }
}
