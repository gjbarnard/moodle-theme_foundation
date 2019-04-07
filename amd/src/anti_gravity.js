/**
 * Foundation theme.
 *
 * @package     theme_foundation
 * @copyright   2019 Gareth J Barnard
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* jshint ignore:start */
define(['jquery', 'core/log'], function($, log) {

    "use strict"; // jshint ;_;

    log.debug('Foundation anti gravity AMD initialised');

    $(document).ready(function() {
        var offset = 220;
        var duration = 1000;
        $(window).scroll(function() {
            if ($(window).scrollTop() > offset) {
                $('#backtotop').fadeIn(duration);
            } else {
                $('#backtotop').fadeOut(duration);
            }
        });

        $('#backtotop').click(function(event) {
            event.preventDefault();
            $('html, body').animate({scrollTop: 0}, duration);
            return false;
        });

        $('#gotobottom').click(function(e) {
            e.preventDefault();
            var target = $('#page-footer');
            $('html, body').animate({scrollTop: target.position().top}, duration);
            return false;
        });
    });
});
/* jshint ignore:end */
