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
                // Drawer closed.
                openDrawer.addClass('d-none');
                closeDrawer.removeClass('d-none');
                drawer.removeClass('drawer-hidden');
                $('body').addClass('drawer-open');
                drawer.attr('aria-hidden', 'false');
            } else {
                // Drawer open.
                closeDrawer.addClass('d-none');
                openDrawer.removeClass('d-none');
                drawer.addClass('drawer-hidden');
                $('body').removeClass('drawer-open');
                drawer.attr('aria-hidden', 'true');
            }
        });
        log.debug('Foundation Drawer AMD init');
    });

    return {}
});
/* jshint ignore:end */
