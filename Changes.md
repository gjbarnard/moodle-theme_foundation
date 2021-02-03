Version Information
===================
Version 3.10.0.2 - TBR
----------------------
 1. Fix - Ensure cache language strings are correct - /cache/admin.php.
 2. Fix - menu item hover.
 3. Add import / export settings code from Essential.
 4. Have an 'Information' settings page.

Version 3.10.0.1 - 09/11/20
---------------------------
 1. Navbar style improvements.

Version 3.9.1.2 - 08/11/20
--------------------------
 1. Fix 'Book module has two icons'.
 2. Fix empty block regions don't have JS drop area when editing.
 3. Side-pre is on the right.
 4. Fix default navbar dropdown icons and text hover.
 5. Adjustments to the navbar to sort out alignment issues.
 6. Settings tabs.
 7. Namespaced classes for settings.
 8. Improve goto bottom icon and make sure it floats on top of search 'Go' button.
 9. Deprecate syntax highlighting functionality in favour of 'SynHi' filter - https://moodle.org/plugins/filter_synhi.

Version 3.9.1.1 - 08/10/20
--------------------------
 1. Fix 'Call to undefined function course_get_format() on login' - #8.

Version 3.9.1.0 - 04/10/20
--------------------------
 1. Put back missing 'Turn editing on' in course settings block - MDL-69249.
 2. Fix 'Error when adding questions to a quiz' - #6.
 3. Update Travis to Moodle HQ version 3 - https://moodlehq.github.io/moodle-plugin-ci/UPGRADE-3.0.html.
 4. Tidy navbar.
 5. Move the context cog to the navbar.
 6. Removed borders on things for a cleaner look.

Version 3.9.0.1 - 17/06/2020
----------------------------
 1. Apply MDL-68041.
 Note: Custom H5P settings will only work with the mod_hvp (https://moodle.org/plugins/mod_hvp) module and not the
       core mod_h5pactivity module in core due to a reduction in the supporting API.  Ref: https://h5p.org/node/285646
       for how it should work with 'hvp_alter_styles'.

Version 3.8.1.1 - TBD
---------------------
 1. Back to top functionality in embedded template.
 2. Add 'seventies' swatch.

Version 3.8.1.0 - 26/01/2020
----------------------------
 1. Add Alex Gorbatchev's Syntax Highlighter (alexgorbatchev.com/SyntaxHighlighter), version 3.0.83 and 4.0.1.
    Licensed under LGPLv3, www.gnu.org/copyleft/lesser.html for 3.0.83 and MIT for 4.0.1.
    There is a setting whereby you can switch between the two.
 2. Add horizontal block area functionality.  Initially used for front page marketing spots.
 3. Update to FontAwesome 5 Free v5.12.0 and add in v4 shim CSS setting.
 4. Ensure that favicon is an 'ico' file only.
 5. Add brands to the footer.

Version 3.8.0.1 - 13/11/2019
----------------------------
 1. Fix position of goto bottom icon when logged out.
 2. Fix quiz drag and drop colour and not working in preview.
 3. Fix content overflow causing horizontal scrollbar when editing a quiz.
 4. Add floating drawer on screens less than 768px wide.
 5. Moodle 3.8 version.
 6. Apply MDL-50346.

Version 3.7.1.1 - 06/10/2019
----------------------------
 1. Add 'hvpfontcss' setting to allow use of theme fonts within H5P custom CSS.
 2. Add Font Awesome 5.11.2 free icons.  Turn on / off with 'fav' setting.
    Please see the license file 'Font Awesome 5 Free LICENSE.txt' in the fonts folder.
 3. General style fixes.

Version 3.7.1.0 - 29/09/2019
----------------------------
 1. Put back collapsible block functionality.
 2. Improve login page opacity.
 3. Stable version.

Version 3.7.0.3
---------------
 1. Fix no popup layout #4.
 2. Only show a navbar if there are items in it, e.g. empty on the base layout.
 3. Fix user menu on mobile #3.
 4. Fix assign grader #5.
 5. Fix dashboard overflow.
 6. Fix my-index page background.
 7. Swatch fixes.

Version 3.7.0.2
---------------
 1. Fix core_text scope issue causing exception.

Version 3.7.0.1
---------------
 1. Beta version based on theme version 3.6.0.4.

Version 3.6.0.4
---------------
 1. Use module templates as cannot override the Mustache engine with our own due to module locallib rendering
    preventing overriding.
 2. Add favicon setting to general settings.  Provides the ability to use your own custom favicon.
 3. Add login page background settings from Essential.
 4. Change login page forget password link styled like a button.
 5. Add anti-gravity functionality from Essential.
 6. Add alert system.  Multiple dismissible alert types on all, course, dashboard or frontpage pages.
 7. Add frontpage carousel similar to one I created for Essential.
 8. Add reduced version (no sub-menus on BS4) of the 'My Courses menu' from Essential.

Version 3.6.0.3
---------------
 1. Add H5P custom CSS setting from Essential.
 2. Add scrollable drawer block region.
 3. Add new Bootswatches: Cosmo, Darkly, Flatly, Journal, Sandstone, Sketchy, Slate, Solar and Spacelab.
 4. Add local fonts as much as possible to each Bootswatch.

Version 3.6.0.2
---------------
 1. Moodle 3.6.1 version.
 2. Improvements to the way the Grid format looks.
 3. Fix template loading when called from JavaScript via the external web service API.  This effectively fixes
    the user tours not using the Boost theme tourstep.mustache template instead of the core one and saves us
    having to store our own copy of that template because the Foundation template finder creates a hierarchy
    whereby Foundation, then Boost, then core is checked for a given template without the theme needing to be
    a child theme of Boost itself and thereby use the implemented core logic.

Version 3.6.0.1
---------------
 1. Moodle 3.6rc1 beta version based on 3.5.0.6.
 2. Fix footer position.
 3. Tidy up in line with core Boost.

Version 3.5.0.6
---------------
 1. Beta version.
 2. Changes for CONTRIB-7493 including a 'pre SCSS' setting.
 3. Conform to https://docs.moodle.org/dev/Plugin_contribution_checklist#Strings for modules for now.

Version 3.5.0.5
---------------
 1. Beta version.
 2. Collapsing blocks - MDL-57305 - thanks to Daniel Miericke.

Version 3.5.0.4
---------------
 1. Beta version.

Version 3.5.0.3
---------------
 1. Beta version.

Version 3.5.0.2
---------------
 1. Beta version.

Version 3.5.0.1
---------------
 1. Initial version.
