Version Information
===================

Version 500.0.1 - 11/06/2025
----------------------------
1. Release candidate version for Moodle 5.0.
2. Fix 'Slider images not in JSON settings backup'.
3. Change to 'Badge' colours, ref: https://moodledev.io/docs/5.0/guides/bs5migration#badges.
4. Make settings tabs work.
5. BS data JS updates, ref: https://moodledev.io/docs/5.0/guides/bs5migration#refactor-bs5-data-attributes.
6. Fix carousel.
7. Media query changes, ref: https://moodledev.io/docs/5.0/guides/bs5migration#refactor-media-query-mixins.
8. Add aria label to carousel indicators.
9. Change 'Directional utilities', ref: https://moodledev.io/docs/5.0/guides/bs5migration#directional-utilities.
10. Change 'Screen reader utilities', ref: https://moodledev.io/docs/5.0/guides/bs5migration#screen-reader-utilities.
11. Change 'Font utility classes', ref: https://moodledev.io/docs/5.0/guides/bs5migration#font-utility-classes.
12. Change 'Refactor dropdowns positioning classes', ref: https://moodledev.io/docs/5.0/guides/bs5migration#refactor-dropdowns-positioning-classes.
14. Fix 'Bootstrap spacer utilities not being generated'.

Version 405.0.2 - 25/03/2025
----------------------------
1. Fix 'Using trio setting breaks SCSS' - #35.
2. Data instance id format in line with core.
3. Fix missing '$font-size-base' in Journal swatch.
4. Swatch setting description improvements.
5. Add 'foreground' colours, 'fg-' prefix, to apply to the text on the given 'background' selector 'bg-' prefix:

```
$fg-primary:       $gray-800 !default;
$fg-secondary:     $gray-100 !default;
$fg-success:       $gray-800 !default;
$fg-info:          $gray-100 !default;
$fg-warning:       $gray-800 !default;
$fg-danger:        $gray-100 !default;
$fg-light:         $gray-900 !default;
$fg-dark:          $white !default;
```

If want to change these values, then define a new value in the 'prescss' theme setting, for instance:

`$fg-secondary:     #ffaabb !default;`

CSS selectors for each colour are then generated, for instance:

```
.fg-secondary {
  color: #ffaabb !important;
}
```

6. Fix layout of 'Assignment grading page' being 'mod-assign-grading'.
7. Fix 'currentlanguage' assignment to non-existent class attribute in custom_menu_item.
8. Fix navbar alignment with custom menu.
9. Update theme version of FontAwesome free to 6.7.2 from 6.6.0.

Version 405.0.1 - 24/10/2024
----------------------------
1. Release candidate version for Moodle 4.5.
2. Add file settings to import / export of settings.
3. Better file upload icon and for when FA4 shims disabled.
4. Update theme version of FontAwesome free to 6.6.0 from 6.5.2.
5. Impact of MDL-81920 and MDL-81960.
6. Remove duplicate 'Add block' in 'side-pre'.
7. Tidy 'Add block to region' button.
8. Fix block drag and drop time icon.

Version 404.1.0 - 24/07/24
--------------------------
1. Swap over user name and icon in navbar.  In line with a design style of other online applications.
2. Add 'activitynavigationenabled' setting to enable / disable activity navigation.
3. Add 'activitynavigationmodulenames' setting to show the activity module names in the navigation or previous / next if unset.
4. Change of name from 'usermenulogouturl' to 'customlogouturl' and move setting from 'Menus' to 'General' in the theme settings
   to fix the logout URL in the footer being inconsistent with the user menu - #33.

Version 404.0.1 - 22/04/24
--------------------------
1. Release candidate version for Moodle 4.4.
2. Grade report improvements.

Version 403.1.3 - 11/03/24
--------------------------
1. Added tooltip to show / hide block.
2. Fix 'error: class constructors must be invoked with 'new'' - ref: https://moodle.org/mod/forum/discuss.php?d=453804.
3. Fix 'Site Logo needs Alt Text for Accessibility' - #30.
4. Fix 'Alert warning secondary buttons'.

Version 403.1.2 - 15/10/23
--------------------------
1. Add the ability not to show 'My courses' in the breadcrumb when it is not used as the start page for the user.  See the
  'breadcrumbdisplaymycourses' setting in on the 'Header' settings tab under 'Breadcrumb'.

Version 403.1.1 - 12/10/23
--------------------------
1. Fix 'Image Icon and Video Icon: Browse Repository button does not get any action to proceed to next screen' - #29.
2. Effects of MDL-76974.

Version 403.1.0 - 08/10/23
--------------------------
1. First version for Moodle 4.3.

Version 402.1.3 - 15/10/23
--------------------------
1. Update theme version of Font Awesome 6 to 6.4.2 from 6.4.0.
2. Fix 'username unicode characters on login page'.  Really strange this one as caused by 'json_encode' in 'quote' method
   of 'mustache_quote_helper' being called when '!}}' and '{{' not used to make the 'input' tag on one line in the output.

Version 402.1.2 - 08/09/23
--------------------------
1. Fix 'Logout Url not working' - https://moodle.org/mod/forum/discuss.php?d=448979.

Version 402.1.1 - 15/06/23
--------------------------
1. Fix 'The course bulk editing does not work properly' - #28.
2. Tidy report layout to have horizontal block regions.
3. Distinguish 'Add block' functionality.
4. Cope with sticky footer core functionality.
5. Add 'Header background course image' functionality.

Version 402.1.0 - 10/05/23
--------------------------
1. Remove Font Awesome five.
2. Update theme version of Font Awesome 6 to 6.4.0.

Version 401.1.0 - 10/05/23
--------------------------
1. Apply MDL-70721.
2. Improve poster and marketing blocks.
3. Cards have borders.

Version 401.0.1 - 27/11/22
--------------------------
1. Release candidate for Moodle 4.1.

Version 400.1.3 - 21/11/22
--------------------------
1. Changed to semantic versioning 2.0.0 (https://semver.org/) for the release value, whereby the 'major' number is the Moodle core
   branch number.  The 'version' property still needs to follow the Moodle way in order for the plugin to operate within the core
   API.
2. Fix 'Editing Button clashes with Chat icon in Safari' - #26.
3. Update to FontAwesome 6.2.1 from 6.1.2.
4. Change the following mod icons to Font Awesome 6.2.1 free ones:
     Assign - fa-solid fa-file-pen
     Assignment - fa-solid fa-file-signature
     Book - fa-solid fa-book-open
     Chat - fa-regular fa-comments
     Choice - fa-solid fa-arrows-split-up-and-left
     Data - fa-solid fa-database
     Feedback - fa-regular fa-comment-dots
     File - fa-regular fa-file
     Folder - fa-regular fa-folder
     Forum - fa-solid fa-people-group
     Glossary - fa-solid fa-box-archive
     IMScp - fa-solid fa-boxes-stacked
     Label - fa-solid fa-tag
     Lesson - fa-solid fa-chalkboard-user
     LTI - fa-solid fa-puzzle-piece
     Page - fa-solid fa-sheet-plastic
     Quiz - fa-solid fa-person-circle-question
     Resource - fa-regular fa-file
     SCORM - fa-solid fa-box
     Survey - fa-solid fa-square-poll-horizontal
     URL - fa-solid fa-link
     Wiki - fa-solid fa-circle-nodes
     Workshop - fa-solid fa-people-arrows
   Converted to PNG with Inkscape.

Version 4.0.1.2 - 19/08/22
--------------------------
1. Better module rendering on course page.
2. Update to FontAwesome 6.1.2 from 6.1.1.

Version 4.0.1.1 - 17/08/22
--------------------------
1. Fix 'aria controls on drawer'.
2. Fix 'On "Course Reuse" page dropdown missing' - #17.
3. Fix 'Custom select indicator colour' - #17.
4. Add 'Add a block option for each block area'.
5. Add 'Put back activity navigation'.
6. Fix 'Completion status not showing on activity/return to course button' - #18.
7. Add 'Main course page link to activity navigation' - #18.
8. Improvement to edit switch accessibility -> https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Attributes/aria-label.
9. Fix 'Failing core PHPUnit test' - #19.
10. Apply MDL-74602.
11. Fix 'Collapsable in question preview won't work' - #22.
12. Fix 'doctype error' - #23.
13. Fix 'string definition for cachedef_foundationfontawesomeiconmapping/theme_foundation' - #25.
14. Apply MDL-72885.
15. Fix non-navbar menu padding.

Version 4.0.1.0 - 17/04/22
--------------------------
1. Fix small issue with overridden 'build_action_menu_from_navigation' action menu code.
2. Fix 'Block region drag and drop broken' - #15.
3. Fix 'Block drag and drop region when editing'.
4. Fix 'Message and notifications menu style'.
5. Fix 'Maintenance layout not outputting doctype'.
6. General tidy.

Version 4.0.0.1 - 10/04/22
--------------------------
1. Add core edit switch with 'navbareditswitch' setting to enable / disable.
2. Add core secondary navigation.
3. Add Progress Section format single page support.
4. Fix 'Course overview' dropdown menu when the navbar is at the bottom.
5. Fix 'Course overview' dropdown menu item 'All (except removed from view)' icon.
6. Update FontAwesome from 5.12.2 to 5.12.4.
7. Add FontAwesome 6.0.0-beta.
8. Add Morph, Quartz, Vapor and Zephyr bootswatches.
9. Fix dark text on file type descriptions.
10. Fix secondary colour.
11. Fix navigation and setting block positioning - pertaining to when hovered over.
12. Fix default colours, especially pertaining to the navbar.
13. New 'MyCourses' layout, see MDL-70801 and MDL-73173.
14. Added 'unaddableblocks' setting - MDL-73347 and MDL-73719.
15. Added 'navbarstyle' setting so that light coloured navbar's can have dark text.
16. Added ATTO style override.
17. Add horizontal 'Course end' block region that applies to the course, with a new
    'courseendblocksperrow' setting to set the blocks per row, causing 'blocksperrow'
    to be changed to 'marketingblocksperrow'.
18. Fix 'This course', 'Badges' icon when using Font Awesome 4.
19. Fix '$thiscoursemenu undefined error' - #13.
20. Fix 'Anti-gravity footer icons'.
21. Fix login page footer opacity when there is a background.
22. Fix 'Open Sans font file extensions'.
23. Fix 'Action menu indentation causes item escape from menu'.
24. Fix 'Activity action menu cannot escape confines of #region-main and be displayed correctly'.

**Note:**
If you like the new 'Course index' in Boost and want to have it in Foundation, then I've created a wrapper 'block' that you
can install and use in any region, such as 'Drawer', you can get it from: https://github.com/gjb2048/moodle-block_course_index.

Version 3.11.1.4 - 08/10/21
---------------------------
1. Add header language menu setting.

Version 3.11.1.3 - 22/09/21
---------------------------
1. Fix 'Quick search in the forum brings an error' - #12.
2. Add 'This course' menu with 'Badges', 'Competencies', 'Grades' and 'Participants'.

Version 3.11.1.2 - 23/08/21
---------------------------
1. Support change in Collapsed Topics renderer location in version 3.11.0.2.
2. Fix 'Turn editing on button not visible in courses with Sandstone swatch' - #11.
3. Fix 'usermenulogouturl setting not logging out'.

Version 3.11.1.1 - 28/07/21
---------------------------
1. Fix navigation and settings block FontAwesome 5 fixes.
2. Added 'User menu log out URL' general setting.  When populated with a URL, that will be used
   instead on the 'Log out' menu item of the user menu.

Version 3.11.1.0 - 20/05/21
---------------------------
1. First version for Moodle 3.11.

Version 3.10.1.2 - 28/04/21
---------------------------
1. Fix colour of search icon on navbar.
2. Fix blocks per row when not editing.
3. Fix mod_hvp editing page editor width.
4. Have course title as italic when course is hidden.
5. Don't have a link on the navbar logo / brand or show the breadcrumb when logged in via LTI.
6. Add 'Poster' region on the frontpage.

Version 3.10.1.1 - 15/03/21
---------------------------
1. Fix header opacity settings not resetting the theme cache.
2. Added swatch custom colour detection.
3. Added core H5P alter styles support - MDL-69087.
4. Add three column layout capability from Classic.
5. Fix bottom border on the last topic.
6. Fix dashboard padding when less than 768px.

Version 3.10.1.0 - 04/03/21
---------------------------
1. Fix - Ensure cache language strings are correct - /cache/admin.php.
2. Fix - menu item hover.
3. Add import / export settings code from Essential.
4. Have an 'Information' settings page.
5. Removed Syntax Highlighting, please use [SynHi](https://moodle.org/plugins/filter_synhi) instead.
6. Add the ability to have the navbar at the bottom.
7. Added an extra level of '$spacer' in Bootstrap classes, e.g. can now have 'pb-6'.
8. Fixed 'nonavbar' not working in config.php.
9. Fix 'Carousel does not start automatically' - #9.
10. Update FontAwesome 5 free from 5.12.0 to 5.15.2.
11. Tabs update in line with MDL-69301.
12. Anti-gravity adjustment settings.
13. Fix 'nocontextheader' not being checked in theme config layout options.
14. Fix 'Yeti' swatch 'context_header_settings_menu()' styling.
15. Fix course participants, modal and my overview block progress bar colours.
16. Better course icon in course listings.
17. Improve look of marketing blocks.
18. Fix paging bar position.
19. Regions display their names when editing.

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
