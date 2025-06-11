Introduction
============
Foundation theme.

Foundation is a [Bootstrap](//getbootstrap.com) theme that aims to be different and yet at the same time have some traditions of the past.
It does not extend the Boost theme but rather 'pull' the files it needs.  No navigation drawer or docking has been
implemented, however, you can collapse and expand blocks and place them in a 'drawer' region.

There is added support for 'partial' templates which can then be imported into a main template with the syntax
'> partials/mypartial'.  But this only works for templates rendered by a renderer in PHP, not if called by JS via
AJAX and the 'core_output_load_template' method defined in /lib/db/services.php which then calls 'load_template'
in /lib/classes/output/external.php which has a fixed namespace reference to the 'mustache_template_finder' class.

Currently the theme does not work when placed within a $CFG->themedir folder.

Features
========
* Bootstrap CSS with core Moodle CSS.
* Child theme capable - in 'beta'.
* Pre and Custom SCSS.
* Custom Swatches from [Bootswatch.com](//bootswatch.com) and licensed under the MIT License (MIT).
* Partial template support.
* Theme modules - in 'beta'.

About
=====
Copyright  2019-onwards G J Barnard.
Author     G J Barnard - [gjbarnard.co.uk](https://gjbarnard.co.uk) and [Moodle.org](https://moodle.org/user/profile.php?id=442195)
License    [GNU GPL v3 or later](https://www.gnu.org/copyleft/gpl.html).

Developed and maintained by
===========================
G J Barnard MSc. BSc(Hons)(Sndw). MBCS. CEng. CITP. PGCE.
Moodle profile | [Moodle.org](https://moodle.org/user/profile.php?id=442195)
Web profile | [gjbarnard.co.uk](https://gjbarnard.co.uk)

Free Software
=============
The Foundation theme is 'free' software under the terms of the GNU GPLv3 License, please see 'COPYING.txt'.

It can be obtained for free from:
[github.com/gjbarnard/moodle-theme_foundation/releases](https://github.com/gjbarnard/moodle-theme_foundation/releases)

You have all the rights granted to you by the GPLv3 license.  If you are unsure about anything, then the
FAQ - [www.gnu.org/licenses/gpl-faq.html](//www.gnu.org/licenses/gpl-faq.html) - is a good place to look.

If you reuse any of the code then I kindly ask that you make reference to the theme.

The 'Swatches' are from [Bootswatch.com](https://bootswatch.com/) and licensed under the
[MIT Licence](https://github.com/thomaspark/bootswatch/blob/master/LICENSE).  I have modified them to disable the '@import url($web-font-path))'
in order for them to work with Moodle.

If you make improvements or bug fixes then I would appreciate if you would send them back to me by forking from
[GitHub](https://github.com/gjbarnard/moodle-theme_foundation) and doing a 'Pull Request' so that the rest of the Moodle community
benefits.

Support
=======
As Foundation is licensed under the GNU GPLv3 License it comes with NO support.  If you would like support from
me then I'm happy to provide it for a fee (please see my contact details above).  Otherwise, the '[Themes](https://moodle.org/mod/forum/view.php?id=46)'
forum is an excellent place to ask questions.

Sponsorships
============
This theme is provided to you for free, and if you want to express your gratitude for using this theme, please consider sponsoring
by:

PayPal - Please contact me via my 'Moodle profile' (above) for details as I am an individual and therefore am unable to have
'buy me now' buttons under their terms.

Sponsorships may allow me to provide you with more or better features in less time.

Customisation
=============
If you like this theme and would like me to customise it, transpose functionality to another theme or build a new theme
from scratch, then I offer competitive rates.  Please contact me via my 'Moodle profile' in 'Developed and maintained by'
above to discuss your requirements.

Required version of Moodle
==========================
This version works with Moodle 5.0 version 20250414.00 (Build: 20250414) and above within the MOODLE_500_STABLE branch until the
next release.

Please ensure that your hardware and software complies with 'Requirements' in '[Installing Moodle](https://docs.moodle.org/500/en/Installing_Moodle)'.

Installation
============
 1. Ensure you have the version of Moodle as stated above in 'Required version of Moodle'.  This is essential as the
    theme relies on underlying core code that is out of my control.
 2. Login as an administrator and put Moodle in 'Maintenance Mode' so that there are no users using it bar you as the administrator.
 3. Copy the extracted 'foundation' folder to the '/theme/' folder.
 4. Go to 'Site administration' -> 'Notifications' and follow standard the 'plugin' update notification.
 5. Select as the theme for the site.
 6. Put Moodle out of Maintenance Mode.

Upgrading
=========
 1. Ensure you have the version of Moodle as stated above in 'Required version of Moodle'.  This is essential as the
    theme relies on underlying core code that is out of my control.
 2. Login as an administrator and put Moodle in 'Maintenance Mode' so that there are no users using it bar you as the administrator.
 3. Make a backup of your old 'foundation' folder in '/theme/' and then delete the folder.
 4. Copy the replacement extracted 'foundation' folder to the '/theme/' folder.
 5. Go to 'Site administration' -> 'Notifications' and follow standard the 'plugin' update notification.
 6. If automatic 'Purge all caches' appears not to work by lack of display etc. then perform a manual 'Purge all caches'
   under 'Home -> Site administration -> Development -> Purge all caches'.
 7. Put Moodle out of Maintenance Mode.

Uninstallation
==============
 1. Put Moodle in 'Maintenance Mode' so that there are no users using it bar you as the administrator.
 2. Change the theme to another theme of your choice.
 3. In '/theme/' remove the folder 'foundation'.
 4. Go to 'Site administration' -> 'Notifications' and follow standard the 'plugin' update notification.
 5. Put Moodle out of Maintenance Mode.

Reporting Issues
================
Before reporting an issue, please ensure that you are running the latest version for your release of Moodle.  It is essential
that you are operating the required version of Moodle as stated at the top - this is because the theme relies on core
functionality that is out of its control.

I operate a policy that I will fix all genuine issues for free.  Improvements are at my discretion.  I am happy to make bespoke
customisations / improvements for a negotiated fee.

It is essential that you provide as much information as possible, the critical information being the contents of the theme's
version.php file.  Other version information such as specific Moodle version, theme name and version also helps.  A screen shot
can be really useful in visualising the issue along with any files you consider to be relevant.

Version Information
===================
See Changes.md

Me
==
G J Barnard MSc. BSc(Hons)(Sndw). MBCS. CEng. CITP. PGCE.

- Moodle profile | [Moodle.org](https://moodle.org/user/profile.php?id=442195)
- Web profile | [gjbarnard.co.uk](https://gjbarnard.co.uk)
