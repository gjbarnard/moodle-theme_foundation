/* jshint ignore:start */
define(['jquery', 'core/log'], function($, log) {

    "use strict"; // jshint ;_;

    log.debug('Foundation Drawer AMD');

    $(document).ready(function($) {
        $('#drawer').click(function() {
            var closeDrawer = $('#drawerclose');
            var openDrawer = $('#draweropen');
            var drawer = $('[data-region="blocks-drawer"]');

            if (closeDrawer.hasClass('d-none')) {
                // Drawer closed -> open.
                openDrawer.addClass('d-none');
                closeDrawer.removeClass('d-none');
                drawer.removeClass('drawer-hidden');
                $('body').addClass('drawer-open');
                drawer.attr('aria-hidden', 'false');
                openDrawer.attr('aria-hidden', 'true');
                closeDrawer.attr('aria-hidden', 'false');
                M.util.set_user_preference('drawerclosed', false);
            } else {
                // Drawer open -> closed.
                closeDrawer.addClass('d-none');
                openDrawer.removeClass('d-none');
                drawer.addClass('drawer-hidden');
                $('body').removeClass('drawer-open');
                drawer.attr('aria-hidden', 'true');
                closeDrawer.attr('aria-hidden', 'true');
                openDrawer.attr('aria-hidden', 'false');
                M.util.set_user_preference('drawerclosed', true);
            }
        });
        log.debug('Foundation Drawer AMD init');
    });
});
/* jshint ignore:end */
