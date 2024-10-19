hMailAdmin: an hMailServer PHPWebAdmin redesign project
========================

I was delighted when first started using hMailServer on my Windows 2016 server (replaced SmarterMail) but web-interface was punch in the eye so I decided to redesign it. The new layout is fully responsive and free to use/download.

![hmailadmin-v1 7](https://github.com/user-attachments/assets/19ef5ab2-b70c-4e16-a52b-98996f09d898)

Features
-----
- modern look and feel
- responsive layout for desktop and mobile browsers
- dashboard graphs refresh with live data
- ability to view source of queued messages
- confirmation dialogs in modal
- form validation
- table sorting
- log parser
- blacklist check
- based on original PHPWebAdmin
- works with latest hMailServer (stable) version
- continuous development

Donate
-----
Don't worry, hMailAdmin will stay free - but I do need your support in order to continue to develop it! Thank you :)

[![PayPal donate button](https://www.paypalobjects.com/webstatic/en_US/btn/btn_donate_pp_142x27.png)](https://www.paypal.me/MatijaMatecic/10EUR)

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

Common issues: https://www.hmailserver.com/documentation/latest/?page=ts_setup_phpwebadmin

Changelog
-----
Version 1.7 (2024-10-18)
- [tweak] UI refresh (better navigation on desktop/tablet/mobile, new login, new icons)
- [new] notices with warnings
- [new] added Protocols and Greylisting White listing pages
- [new] possible to delete message from Delivery queue
- [tweak] breadcrumbs repositioned to header nav
- [fix] various JavaScript fixes and optimizations

Version 1.6 (2024-10-15)
- [new] added breadcrumbs to several pages (Alias, External accounts, Rules, IMAP folders)
- [tweak] merged some pull requests
- [fix] new IP to Country API (shows flags in IP Ranges)
- [fix] part of navigation on mobile UI wasn't clickable

Version 1.5 (2018-08-22)
- [tweak] changes to config.php
- [tweak] Server graph on dashboard displays delivery queue count
- [new] DMARC reports in side navigation (thanks to @tunis)
- [new] IMAP folders under Account
- [tweak] replaced "tablesort" with "stupidtable" plugin
- [tweak] added APIPA addresses to regex in geoIp() function
- [fix] CSS fix

Version 1.4 (2018-08-11)
- [new] Blacklist check (under Utilities)
- [new] Dashboard: Live logging (thanks to @tunis)
- [tweak] Dashboard: new delivery queue view
- [tweak] IP Range: delete banned IP button
- [tweak] Domains: visual quota bar for each domain
- [tweak] Domain: show accounts button
- [tweak] Accounts: visual quota bar for each account
- [fix] IP Ranges: new IP to country lookup API link
- [tweak] back button now returns to parent page (instead of previous page in browser history)
- [fix] even more form validations, maxlength fixes
- [new] textarea autosize
- [new] version checker
- [tweak] added empty text in tables
- [tweak] small layout changes, CSS tweaks, all icons changed to SVG

Version 1.3 (2017-12-29)
- [new] translation class for missing phrases in hMailServer, anyone can add new languages (eg. italian.php) to /languages folder
- [new] config.php: allow or deny built-in Administrator within IP range or address
- [new] config.php: week start day
- [new] view IP address on IP ranges list
- [fix] minor translation and maxlength fixes
- [tweak] update 3rd party plugins
- [tweak] CSS optimization

Version 1.2 (2017-06-12)
- [fix] multiple typos and small fixes
- [fix] dropdowns in rule criteria actions
- [fix] distribution lists checkboxes
- [new] distribution list add/edit/delete members
- [fix] maxlength added to account input fields

Version 1.1 (2017-03-12)
- [new] country name and flag in auto-ban info
- [tweak] navigation UI improvements
- [tweak] CSS revamp
- [tweak] selected domain shows all submenus
- [fix] typos in hm_account_externalaccount.php

Version 1.0 (2017-02-08)
- stable release
- minor tweaks and improvements

Version 0.9.8 beta (2017-02-03)
- [new] powerful log parser (using server-side logs)
- [new] datepicker for date fields
- [tweak] IP Ranges expiry date more friendly
- [fix] greylisting checkbox in hm_domain.php

Version 0.9.7 beta (2017-02-01)
- [tweak] convert all dates to ISO (YYYY-MM-DD HH:MM:SS) due consistency
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
- [Defunkt Facebox](http://defunkt.io/facebox/)
- [stupidtable](https://github.com/joequery/Stupid-Table-Plugin)
- [datepicker](https://github.com/fengyuanchen/datepicker)
- [autosize](https://github.com/jackmoore/autosize)
- [timeago](http://timeago.yarp.com/timeago)
- [Chartist](https://gionkunz.github.io/chartist-js/Chartist)
- [jBox](https://github.com/StephanWagner/jBox)
- [Feather](https://feathericons.com/)

Questions and contributions
-----
Feel free to ask any question or report bug via GitHub Issues. I'll try to answer any question.

There's an official thread on [hMailServer forums](https://www.hmailserver.com/forum/viewtopic.php?f=10&t=30713).

If you want to help me with this project, simply fork it and let me know ;)

Thanks
-----
Andreas Tunberg and other contributors who make developing easier.

Copyright?
-----
Author: [Matija Matečić](https://matija.matecic.com)

Released under [CC BY-SA 4.0 license](https://creativecommons.org/licenses/by-sa/4.0/) (use it freely in personal/commercial project but don't resell, provided "as-is")
