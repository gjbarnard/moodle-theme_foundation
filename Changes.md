Version Information
===================
Version 3.6.1.0 - TBD
  1. Fix no popup layout #4.
  2. Only show a navbar if there are items in it, e.g. empty on the base layout.
  3. Fix user menu on mobile #3.
  4. Fix assign grader #5.
  5. Fix dashboard overflow.
  6. Fix my-index page background.
  7. Swatch fixes.
  8. Put back collapsible block functionality.
  9. Improve login page opacity.
 10. Add 'hvpfontcss' setting to allow use of theme fonts within H5P custom CSS.
 11. Add Font Awesome 5.11.2 free icons.  Turn on / off with 'fav' setting.
     Please see the license file 'Font Awesome 5 Free LICENSE.txt' in the fonts folder.
 12. General style fixes.
 13. Fix position of goto bottom icon when logged out.
 14. Fix quiz drag and drop colour and not working in preview.

Version 3.6.0.5
  1. Fix core_text scope issue causing exception.

Version 3.6.0.4
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
  1. Add H5P custom CSS setting from Essential.
  2. Add scrollable drawer block region.
  3. Add new Bootswatches: Cosmo, Darkly, Flatly, Journal, Sandstone, Sketchy, Slate, Solar and Spacelab.
  4. Add local fonts as much as possible to each Bootswatch.

Version 3.6.0.2
  1. Moodle 3.6.1 version.
  2. Improvements to the way the Grid format looks.
  3. Fix template loading when called from JavaScript via the external web service API.  This effectively fixes
     the user tours not using the Boost theme tourstep.mustache template instead of the core one and saves us
     having to store our own copy of that template because the Foundation template finder creates a hierarchy
     whereby Foundation, then Boost, then core is checked for a given template without the theme needing to be
     a child theme of Boost itself and thereby use the implemented core logic.

Version 3.6.0.1
  1. Moodle 3.6rc1 beta version based on 3.5.0.6.
  2. Fix footer position.
  3. Tidy up in line with core Boost.

Version 3.5.0.6
  1. Beta version.
  2. Changes for CONTRIB-7493 including a 'pre SCSS' setting.
  3. Conform to https://docs.moodle.org/dev/Plugin_contribution_checklist#Strings for modules for now.

Version 3.5.0.5
  1. Beta version.
  2. Collapsing blocks - MDL-57305 - thanks to Daniel Miericke.

Version 3.5.0.4
  1. Beta version.

Version 3.5.0.3
  1. Beta version.

Version 3.5.0.2
  1. Beta version.

Version 3.5.0.1
  1. Initial version.
