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
 * The core renderer.
 *
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class core_renderer extends \core_renderer {
    use core_renderer_toolbox;
    use mustache_engine;

    protected $syntaxhighlighterenabled = false;
    protected $syntaxhighlighterhelpenabled = false;

    /**
     * The standard tags (meta tags, links to stylesheets and JavaScript, etc.)
     * that should be included in the <head> tag. Designed to be called in theme
     * layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_head_html() {
        switch ($this->page->pagelayout) {
            case 'course':
            case 'incourse':
                $this->syntax_highlighter();
            break;
            default:
                if (join('-', array_slice(explode('-', trim($this->page->pagetype)), 0, 1)) == 'mod') {
                    $this->syntax_highlighter(false);
                }
            break;
        }
        return parent::standard_head_html();
    }

    protected function syntax_highlighter($codeandhelp = true) {
        $toolbox = \theme_foundation\toolbox::get_instance();
        if ($toolbox->get_setting('syntaxhighlight') == 2) {
            if (in_array($this->get_current_category(), explode(',', $toolbox->get_setting('syntaxhighlightcat'))) !== false) {
                if ($codeandhelp) {
                    $this->page->requires->css('/theme/foundation/javascript/syntaxhighlighter_3_0_83/styles/shCore.css');
                    $this->page->requires->css('/theme/foundation/javascript/syntaxhighlighter_3_0_83/styles/shThemeDefault.css');
                    $this->syntaxhighlighterenabled = true;
                }
                $this->syntaxhighlighterhelpenabled = true;
            }
        }
    }

    /**
     * Gets the current category.
     *
     * @return int Category id.
     */
    protected function get_current_category() {
        $catid = 0;

        if (is_array($this->page->categories)) {
            $catids = array_keys($this->page->categories);
            $catid = reset($catids);
        } else if (!empty($$this->page->course->category)) {
            $catid = $this->page->course->category;
        }

        return $catid;
    }

    /**
     * The standard tags (typically script tags that are not needed earlier) that
     * should be output after everything else. Designed to be called in theme layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_end_of_body_html() {
        global $CFG;

        $output = '';

        $context = \context_course::instance($this->page->course->id);
        // Typically if you can update the course settings then you can use syntax highlighting.
        if (($this->syntaxhighlighterhelpenabled) && (\has_capability('moodle/course:update', $context))) {
            $output .= html_writer::start_tag('div', array('class' => 'syntaxhighlightmodal'));
            $output .= '<a href="#mySHModal" role="button" class="btn" data-toggle="modal">'.get_string('syntaxhighlightpage', 'theme_foundation').'</a>';

            $output .= '<div id="mySHModal" class="modal fade text-dark" tabindex="-1" role="dialog" aria-labelledby="mySHModalLabel" '.
                'aria-hidden="true">';
            $output .= '<div class="modal-dialog" role="document">';
            $output .= '<div class="modal-content">';
            $output .= '<div class="modal-header">';
            $output .= '<h3 id="mySHModalLabel">'.get_string('syntaxhighlightpage', 'theme_foundation').'</h3>';
            $output .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">'.
                $this->pix_icon('t/show', get_string('closebuttontitle')).'</button>';
            $output .= '</div>';
            $output .= '<div class="modal-body">';
            $output .= '<div class="modal-content">';
            $output .= html_writer::start_tag('div', array('class' => 'row'));
            $output .= html_writer::start_tag('div', array('class' => 'col-12 lead'));
            $output .= html_writer::tag('p', get_string('syntaxhelpone', 'theme_foundation'));
            $output .= html_writer::start_tag('table', array('class' => 'syntax'));
            $output .= html_writer::start_tag('thead');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('th', get_string('syntaxhelptwo', 'theme_foundation'));
            $output .= html_writer::tag('th', get_string('syntaxhelpthree', 'theme_foundation'));
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::end_tag('thead');
            $output .= html_writer::start_tag('tbody');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'ActionScript3');
            $output .= html_writer::tag('td', 'as3, actionscript3');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Bash/shell');
            $output .= html_writer::tag('td', 'bash, shell');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'ColdFusion');
            $output .= html_writer::tag('td', 'cf, coldfusion');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'C#');
            $output .= html_writer::tag('td', 'c-sharp, csharp');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'C++');
            $output .= html_writer::tag('td', 'cpp, c');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'CSS');
            $output .= html_writer::tag('td', 'css');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Delphi');
            $output .= html_writer::tag('td', 'delphi, pas, pascal');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Diff');
            $output .= html_writer::tag('td', 'diff, patch');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Erlang');
            $output .= html_writer::tag('td', 'erl, erlang');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Groovy');
            $output .= html_writer::tag('td', 'groovy');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'JavaScript');
            $output .= html_writer::tag('td', 'js, jscript, javascript');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Java');
            $output .= html_writer::tag('td', 'java');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'JavaFX');
            $output .= html_writer::tag('td', 'jfx, javafx');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Perl');
            $output .= html_writer::tag('td', 'perl, pl');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'PHP');
            $output .= html_writer::tag('td', 'php');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Plain Text');
            $output .= html_writer::tag('td', 'plain, text');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'PowerShell');
            $output .= html_writer::tag('td', 'ps, powershell');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Python');
            $output .= html_writer::tag('td', 'py, python');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Ruby');
            $output .= html_writer::tag('td', 'rails, ror, ruby');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Scala');
            $output .= html_writer::tag('td', 'scala');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'SQL');
            $output .= html_writer::tag('td', 'sql');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'Visual Basic');
            $output .= html_writer::tag('td', 'vb, vbnet');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', 'XML');
            $output .= html_writer::tag('td', 'xml, xhtml, xslt, html, xhtml');
            $output .= html_writer::end_tag('tr');
            $output .= html_writer::end_tag('tbody');
            $output .= html_writer::end_tag('table');
            $output .= html_writer::empty_tag('br');
            $output .= html_writer::tag('p', get_string('syntaxhelpfour', 'theme_foundation'));
            $output .= html_writer::start_tag('pre');
            $output .= htmlentities('<pre class="brush: java">').PHP_EOL;
            $output .= 'public class Test'.PHP_EOL;
            $output .= '{'.PHP_EOL;
            $output .= '   private String name = "Java program";'.PHP_EOL;
            $output .= PHP_EOL;
            $output .= '   public static void main (String args[])'.PHP_EOL;
            $output .= '   {'.PHP_EOL;
            $output .= '      Test us = new Test();'.PHP_EOL;
            $output .= '      System.out.println(us.getName());'.PHP_EOL;
            $output .= '   }'.PHP_EOL;
            $output .= PHP_EOL;
            $output .= '   public String getName()'.PHP_EOL;
            $output .= '   {'.PHP_EOL;
            $output .= '      return name;'.PHP_EOL;
            $output .= '   }'.PHP_EOL;
            $output .= '}'.PHP_EOL;
            $output .= htmlentities('</pre>');
            $output .= html_writer::end_tag('pre');
            $output .= html_writer::tag('p', get_string('syntaxhelpfive', 'theme_foundation'));
            $output .= '<pre class="brush: java">'.PHP_EOL;
            $output .= 'public class Test'.PHP_EOL;
            $output .= '{'.PHP_EOL;
            $output .= '   private String name = "Java program";'.PHP_EOL;
            $output .= PHP_EOL;
            $output .= '   public static void main (String args[])'.PHP_EOL;
            $output .= '   {'.PHP_EOL;
            $output .= '      Test us = new Test();'.PHP_EOL;
            $output .= '      System.out.println(us.getName());'.PHP_EOL;
            $output .= '   }'.PHP_EOL;
            $output .= PHP_EOL;
            $output .= '   public String getName()'.PHP_EOL;
            $output .= '   {'.PHP_EOL;
            $output .= '      return name;'.PHP_EOL;
            $output .= '   }'.PHP_EOL;
            $output .= '}'.PHP_EOL;
            $output .= '</pre>'.PHP_EOL;
            $output .= html_writer::tag('p', get_string('syntaxhelpsix', 'theme_foundation'));
            $output .= html_writer::tag('p', get_string('syntaxhelpseven', 'theme_foundation').' \''.html_writer::tag('a', 'SyntaxHighlighter',
                array('href' => '//alexgorbatchev.com/SyntaxHighlighter/', 'target' => '_blank')).'\'.');
            $output .= html_writer::end_tag('div');
            $output .= html_writer::end_tag('div');
            $output .= html_writer::start_tag('div', array('class' => 'row'));
            $output .= html_writer::start_tag('div',  array('class' => 'col-12'));
            $output .= html_writer::tag('p', html_writer::tag('a', 'SyntaxHighlighter',
                array('href' => '//alexgorbatchev.com/SyntaxHighlighter/', 'target' => '_blank')).
                ' - '.html_writer::tag('span', 'Alex Gorbatchev 2004-2011', array ('class' => 'copyright')).
                ' - LGPL v3 '.html_writer::tag('a', 'www.gnu.org/copyleft/lesser.html',
                array('href' => '//www.gnu.org/copyleft/lesser.html', 'target' => '_blank')),
                array ('class' => 'text-center col-12'));
            $output .= html_writer::end_tag('div');
            $output .= html_writer::end_tag('div');
            $output .= '</div>';
            $output .= '<div class="modal-footer">';
            $output .= '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= html_writer::end_tag('div');
        }

        $output .= parent::standard_end_of_body_html();

        if ($this->syntaxhighlighterenabled) {
            $syscontext = \context_system::instance();
            $itemid = \theme_get_revision();
            $url = \moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php",
                "/$syscontext->id/theme_foundation/syntaxhighlighter/$itemid/");
            $url = preg_replace('|^https?://|i', '//', $url->out(false));

            $output .= html_writer::script('', $url.'shCore.js');
            $output .= html_writer::script('', $url.'shAutoloader.js');
            $script = "require(['jquery', 'core/log'], function($, log) {";  // Use AMD to get jQuery.
            $script .= "log.debug('Foundation SyntaxHighlighter AMD autoloader');";
            $script .= "$('document').ready(function(){";
            $script .= "SyntaxHighlighter.autoloader(";
            $script .= "[ 'applescript', '" . $url . "shBrushAppleScript.js' ],";
            $script .= "[ 'actionscript3', 'as3', '" . $url . "shBrushAS3.js' ],";
            $script .= "[ 'bash', 'shell', '" . $url . "shBrushBash.js' ],";
            $script .= "[ 'coldfusion', 'cf', '" . $url . "shBrushColdFusion.js' ],";
            $script .= "[ 'cpp', 'c', '" . $url . "shBrushCpp.js' ],";
            $script .= "[ 'c#', 'c-sharp', 'csharp', '" . $url . "shBrushCSharp.js' ],";
            $script .= "[ 'css', '" . $url . "shBrushCss.js' ],";
            $script .= "[ 'delphi', 'pascal', '" . $url . "shBrushDelphi.js' ],";
            $script .= "[ 'diff', 'patch', 'pas', '" . $url . "shBrushDiff.js' ],";
            $script .= "[ 'erl', 'erlang', '" . $url . "shBrushErlang.js' ],";
            $script .= "[ 'groovy', '" . $url . "shBrushGroovy.js' ],";
            $script .= "[ 'haxe hx', '" . $url . "shBrushHaxe.js', ],";
            $script .= "[ 'java', '" . $url . "shBrushJava.js' ],";
            $script .= "[ 'jfx', 'javafx', '" . $url . "shBrushJavaFX.js' ],";
            $script .= "[ 'js', 'jscript', 'javascript', '" . $url . "shBrushJScript.js' ],";
            $script .= "[ 'perl', 'pl', '" . $url . "shBrushPerl.js' ],";
            $script .= "[ 'php', '" . $url . "shBrushPhp.js' ],";
            $script .= "[ 'text', 'plain', '" . $url . "shBrushPlain.js' ],";
            $script .= "[ 'py', 'python', '" . $url . "shBrushPython.js' ],";
            $script .= "[ 'ruby', 'rails', 'ror', 'rb', '" . $url . "shBrushRuby.js' ],";
            $script .= "[ 'scala', '" . $url . "shBrushScala.js' ],";
            $script .= "[ 'sql', '" . $url . "shBrushSql.js' ],";
            $script .= "[ 'vb', 'vbnet', '" . $url . "shBrushVb.js' ],";
            $script .= "[ 'xml', 'xhtml', 'xslt', 'html', '" . $url . "shBrushXml.js' ]";
            $script .= ');';
            $script .= 'SyntaxHighlighter.all(); console.log("Syntax Highlighter Init");';
            $script .= '});';
            $script .= '});';
            $output .= html_writer::script($script);
        }

        return $output;
    }
}