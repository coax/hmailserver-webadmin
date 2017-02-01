hMailAdmin: an hMailServer PHPWebAdmin redesign project
========================

I was delighted when first started using hMailServer on my Windows 2016 server (replaced SmarterMail) but web-interface was punch in the eye so I decided to redesign it. The new layout is fully responsive and free to use/download.

![](http://www.coax.hr/img/hmailserver-redesign.png)

Features
-----
- modern look and feel
- responsive layout for desktop and mobile browsers
- dashboard graphs refresh with live data
- ability to view source of queued messages
- confirmation dialogs in modal
- form validation
- table sorting
- simple log parser
- based on original PHPWebAdmin
- works with latest hMailServer (stable) version
- continous development

Roadmap for 2017
-----
- better log parser
- buy community license for [Imperavi Grafs](https://imperavi.com/grafs/) (hence the donation link below)

Donate
-----
Don't worry, hMailAdmin will stay free - but I do need your support in order to continue to develop it! Thank you :)

[![PayPal donate button](https://www.paypalobjects.com/webstatic/en_US/btn/btn_donate_pp_142x27.png)](https://www.paypal.me/MatijaMatecic/)

How to use
-----
If you HAVE PHPWebAdmin installed:

1\. Extract "hMailAdmin" folder in the same root as "PHPWebAdmin" folder (which you can delete) and make changes to config-dist.php accordingly, then rename to config.php

2\. Access web admin interface from browser (eg. http://www.yourdomain.com/hmailadmin/) and sign in with hMailServer credentials

If you DON'T HAVE PHPWebAdmin installed:

1\. Extract "hMailAdmin" folder in the root of any of your websites (or create new website for it) and make changes to config-dist.php accordingly, then rename to config.php

2\. On IIS enable php_com_dotnet.dll in PHP

3\. In php.ini set register_globals=off and display_errors=off

4\. On IIS give the service account access to the hMailServer COM library: https://www.hmailserver.com/documentation/latest/?page=howto_dcom_permissions

5\. Access web admin interface from browser (eg. http://www.yourdomain.com/hmailadmin/) and sign in with hMailServer credentials

Common issue: https://www.hmailserver.com/documentation/latest/?page=ts_setup_phpwebadmin

Changelog
-----
Version 0.9.7 beta (2017-02-01)
- tweak] convert all dates to ISO (YYYY-MM-DD HH:MM:SS) due consistency
- [tweak] dashboard optimizations in JSON
- [fix] typo in background_account_save.php
- [fix] minor fixes in validation fields

Version 0.9.6 beta (2017-01-30)
- [new] view queued messages source (click on message ID)
- [new] merged security improvements from Version 5.6.7 - Build 2407 BETA
- [tweak] renamed from PHPWebAdmin to hMailAdmin
- [tweak] javascripts and fonts no longer loaded remotely (due to security restrictions)
- [fix] live refresh of queued messages on dashboard
- [fix] typo in hm_tcpipport.php

Version 0.9.5 beta (2017-01-20)
- [fix] server start/stop button
- [fix] minor typos in pages
- [fix] invisible checkboxes
- [fix] some mobile submenus unresponsive to click
- [tweak] XHTML to HTML5 declaration
- [tweak] more things translated
- [new] removed all old JS and CSS
- [new] count TCP/IP ports in menu
- [new] define webmail link in config.php
- [new] external accounts inside account

Version 0.9.4 beta (2017-01-18)
- [fix] error in hm_status.php
- [fix] server start/stop button
- [tweak] CSS/HTML optimizations
- [tweak] all words are now translatable (EchoTranslation)
- [new] new chart on dashboard (shows sessions activity line graph in time)
- [new] move rules up/down with arrows
- [new] redesign of single account (non-admin) interface
- [new] documentation link (on each page) points directly to online documentation reference

Version 0.9.3 beta (2017-01-04)
- [fix] SSL certificates typo
- [fix] number validation
- [fix] removed PHP 7 incompatibility error
- [tweak] validation for IP address input field

Version 0.9.2 beta (2017-01-04)
- [fix] all tables are now sorting
- [fix] increase autoban field size from 4 to 5
- [new] all tables now show total count
- [new] domains/accounts show usage size and color coded
- [new] autoban color coded and show time left in minutes
- [new] offline log parser
- [new] menu link to documentation
- [new] added favicon
- [tweak] if using newer version of hMailServer just modify include_versioncheck.php

Version 0.9.1 beta (2016-12-28)
- [new] Renamed folder from "PHPWebAdmin" to "WebAdmin" (to avoid confusion)
- [new] Replaced Administrator in top menu with $username
- [new] Added counters for Rules and DNS blacklists in menu
- [new] Domain accounts, rules, distribution lists
- [new] Renamed folder from "PHPWebAdmin" to "WebAdmin"
- [new] PrintSaveButton function can accept custom captions to display text different than default "Save" (eg. PrintSaveButton("Run");)
- [tweak] Better navigation tree layout
- [tweak] Created jQuery version of testVirusScanner() function in hm_smtp_antivirus.php
- [fix] Legit messages are now calculated from Total - Virus - Spam
- [other] In IIS 6 you'll need to manually add ".svg" mime-type as "image/svg+xml"

Version 0.9 beta (2016-12-28)
- Initial release

3rd party components used
-----
- [jQuery](https://jquery.com/)
- [Modernizr](https://modernizr.com/)
- [Defunkt Facebox](http://defunkt.io/facebox/Defunkt Facebox)
- [tablesort](http://github.com/kylefox/jquery-tablesorttablesort)
- [timeago](http://timeago.yarp.com/timeago)
- [Chartist](https://gionkunz.github.io/chartist-js/Chartist)
- [CodyHouse Responsive Sidebar Navigation](https://codyhouse.co/gem/responsive-sidebar-navigation/)
- [Open Iconic](https://useiconic.com/open/)
- [hMailServer log viewer](https://github.com/hazarkarabay/hmailserver-logviewer)

Questions and contributions
-----
Feel free to ask any question or report bug via GitHub Issues. I'll try to answer any question.

There's an official thread on [hMailServer forums](https://www.hmailserver.com/forum/viewtopic.php?f=10&t=30713).

If you want to help me with this project, simply fork it and let me know ;)

Copyright?
-----
Author: [Matija Matečić](http://www.matecic.com)

Released under [CC BY-SA 4.0 license](https://creativecommons.org/licenses/by-sa/4.0/) (use it freely in personal/commercial project but don't resell, provided "as-is")
