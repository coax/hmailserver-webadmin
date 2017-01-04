hMailServer PHPWebAdmin redesign project
========================

I was delighted when first started using hMailServer on my Windows 2016 server (replaced SmarterMail) but web-interface was punch in the eye so I decided to redesign it. The new layout is fully responsive and free to use/download.

![](http://www.coax.hr/img/hmailserver-redesign.png)

Features
-----
- modern look and feel
- responsive layout for desktop and mobile browsers
- dashboard graphs refresh with live data
- confirmation dialogs in modal
- form validation
- table sorting
- works with latest hMailServer (stable) version

Roadmap for 2017
-----
- better log parser
- buy community license for [Imperavi Grafs](https://imperavi.com/grafs/) (hence the donation link below)

How to use
-----
1\. Place "WebAdmin" folder next to your "PHPWebAdmin" installation and make changes to config-dist.php accordingly, then rename to config.php

2\. Link to "WebAdmin" folder from your IIS settings

3\. Access web admin interface from browser

Changelog
-----
Version 0.9.3 beta (04.01.2017)
- [fix] SSL certificates typo
- [fix] number validation
- [fix] removed PHP 7 incompatibility error
- [tweak] validation for IP address input field

Version 0.9.2 beta (04.01.2017)
- [fix] all tables are now sorting
- [fix] increase autoban field size from 4 to 5
- [new] all tables now show total count
- [new] domains/accounts show usage size and color coded
- [new] autoban color coded and show time left in minutes
- [new] offline log parser
- [new] menu link to documentation
- [new] added favicon
- [tweak] if using newer version of hMailServer just modify include_versioncheck.php

Version 0.9.1 beta (28.12.2016)
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

Version 0.9 beta (28.12.2016)
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

Donate
-----
Don't worry, hMailServer PHPWebAdmin redesign project will stay free - but I do need your support in order to continue to develop it! Thank you :)

[![PayPal donate button](https://www.paypalobjects.com/webstatic/en_US/btn/btn_donate_pp_142x27.png)](https://www.paypal.me/MatijaMatecic/)

Copyright?
-----
Author: [Matija Matečić](http://www.matecic.com)

Released under [CC BY-SA 4.0 license](https://creativecommons.org/licenses/by-sa/4.0/) (use it freely in personal/commercial project but don't resell, provided "as-is")
