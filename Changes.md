Version Information
===================
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
