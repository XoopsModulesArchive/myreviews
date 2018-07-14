<?php
// Module Info

// The name of this module
define("_MI_myReviews_NAME","Reviews");

// A brief description of this module
define("_MI_myReviews_DESC","Creates a Review section where Webmasters can manage any review type.");

// Names of blocks for this module (Not all modules have blocks)
define("_MI_myReviews_BNAME1","Recent Reviews");
define("_MI_myReviews_BNAME2","Top Reviews");
define("_MI_myReviews_BNAME3","To be Reviewed");
define("_MI_myReviews_BNAME4","Reviews Hall of Fame");
define("_MI_myReviews_BNAME5","Most Voted Reviews");
define("_MI_myReviews_BNAME6","Head of the Class");

// Sub menu titles
define("_MI_myReviews_SMNAME1","Popular");
define("_MI_myReviews_SMNAME2","Top Rated");
define("_MI_myReviews_SMNAME3","Submit");
define("_MI_myReviews_SMNAME4","Random");
define("_MI_myReviews_SMNAME5","Hall of Fame");
define("_MI_myReviews_SMNAME6","Onion Awards");
define("_MI_myReviews_SMNAME7","Love at first Sight");
define("_MI_myReviews_SMNAME8","We Recommend");

// Names of admin menu items
define("_MI_myReviews_ADMENU1","General Settings");
define("_MI_myReviews_ADMENU2","Manage Reviews");
define("_MI_myReviews_ADMENU4","Review Submissions");
define("_MI_myReviews_ADMENU5","Manage Extensions");
define("_MI_myReviews_ADMENU6","Manage Categories");
//define("_MI_myReviews_ADMENU7","Manage Upload Extensions");
define("_MI_myReviews_ADMENU8","Manage Rating Categories");
define("_MI_myReviews_ADMENU9","Review Submissions");
define("_MI_myReviews_ADMENU10","Review Info Modification Requests");

// Title of config items
define("_MD_DLSPERPAGE","Displayed reviews per Page: ");
define("_MD_DLSREVIEWSPERPAGE","Reviews on Individual Reviews Page:");
define("_MD_HITSPOP","Hits to be Popular: ");
define("_MD_DLSNEW","Reviews as New on Top Page: ");
define("_MD_MAXRATE","Categories Maximum Rating:");
define("_MD_TOTRATE","Overall Rating Maximum Rating:");
define("_MD_USESHOTS","Use Screenshots: ");
define("_MD_SHOTSPLACEMENT","Category Screenshot placement to links: ");
define('_MD_SHOTWIDTH', 'Maximum allowed width of each screenshot image: ');
define("_MD_CATEGORIESPERLINE","Categories per Line on main page: ");
define("_MI_MYREVIEWS_ANONPOST","Allow anonymous users to post review items?");
define('_MI_MYREVIEWS_AUTOAPPROVE','Auto approve new reviews without admin intervention?');
define('_MI_MYREVIEWS_DETPLACE','Where do you want to place the detail review?');
define('_MI_MYREVIEWS_BLOCKED','Do you want to use a Blocked frame around reviews?');
define('_MI_SHOTLOCATION','Placement of the product image:');
define('_MI_MYREVIEWS_BARTYPE','Select the Graphic Bar type for the review display:');
define('_MD_CATBARWIDTH', 'Maximum allowed width of the review image bar: ');
define('_MD_CATLABELWIDTH', 'Maximum allowed width for the category label: ');

// Description of each config items
define("_MD_DLSPERPAGE_DESC","");
define("_MD_DLSREVIEWSPERPAGE_DESC","");
define("_MD_HITSPOP_DESC","");
define("_MD_DLSNEW_DESC","");
define("_MD_MAXRATE_DESC","What do you want the Maximum rating to be in Categories?");
define("_MD_TOTRATE_DESC","What do you want the Maximum rating to be in Overall Rating?");
define("_MD_USESHOTS_DESC",'');
define("_MD_SHOTSPLACEMENT_DESC","");
define('_MD_SHOTWIDTH_DESC', 'Maximum allowed width of each screenshot image measured in pixels, the default is 100');
define("_MD_CATEGORIESPERLINE_DESC"," ");
define('_MI_MYREVIEWS_ANONPOST_DESC','');
define('_MI_MYREVIEWS_AUTOAPPROVE_DESC', '');
define('_MI_MYREVIEWS_DETPLACE_DESC','Where do you want to place the detail review in respect to the rating bars?');
define('_MI_MYREVIEWS_BLOCKED_DESC','');
define('_MI_SHOTLOCATION_DESC','');
define('_MI_MYREVIEWS_BARTYPE_DESC','');
define('_MD_CATBARWIDTH_DESC', 'Maximum allowed width of each review image bar measured in pixels, the default is 100');
define('_MD_CATLABELWIDTH_DESC', 'Maximum allowed width for the category label measured in pixels, the default is 200');

/*
// Text for notifications

define('_MI_XDIR_GLOBAL_NOTIFY', 'Global');
define('_MI_XDIR_GLOBAL_NOTIFYDSC', 'Global business listing notification options.');

define('_MI_XDIR_CATEGORY_NOTIFY', 'Category');
define('_MI_XDIR_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current business category.');

define('_MI_XDIR_LINK_NOTIFY', 'Listing');
define('_MI_XDIR_LINK_NOTIFYDSC', 'Notification options that aply to the current listing.');

define('_MI_XDIR_GLOBAL_NEWCATEGORY_NOTIFY', 'New Category');
define('_MI_XDIR_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notify me when a new listing category is created.');
define('_MI_XDIR_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Receive notification when a new listing category is created.');
define('_MI_XDIR_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New business listing category');

define('_MI_XDIR_GLOBAL_LINKMODIFY_NOTIFY', 'Modify Listing Requested');
define('_MI_XDIR_GLOBAL_LINKMODIFY_NOTIFYCAP', 'Notify me of any listing modification requests.');
define('_MI_XDIR_GLOBAL_LINKMODIFY_NOTIFYDSC', 'Receive notification when any listing modification request is submitted.');
define('_MI_XDIR_GLOBAL_LINKMODIFY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Listing Modification Requested');

define('_MI_XDIR_GLOBAL_LINKBROKEN_NOTIFY', 'Broken Link Submitted');
define('_MI_XDIR_GLOBAL_LINKBROKEN_NOTIFYCAP', 'Notify me of any broken link report.');
define('_MI_XDIR_GLOBAL_LINKBROKEN_NOTIFYDSC', 'Receive notification when any broken link report is submitted.');
define('_MI_XDIR_GLOBAL_LINKBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Broken Link Reported');

define('_MI_XDIR_GLOBAL_LINKSUBMIT_NOTIFY', 'New Listing Submitted');
define('_MI_XDIR_GLOBAL_LINKSUBMIT_NOTIFYCAP', 'Notify me when any new listing is submitted (awaiting approval).');
define('_MI_XDIR_GLOBAL_LINKSUBMIT_NOTIFYDSC', 'Receive notification when any new listing is submitted (awaiting approval).');
define('_MI_XDIR_GLOBAL_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New business listing submitted');

define('_MI_XDIR_GLOBAL_NEWLINK_NOTIFY', 'New Listing');
define('_MI_XDIR_GLOBAL_NEWLINK_NOTIFYCAP', 'Notify me when any new listing is posted.');
define('_MI_XDIR_GLOBAL_NEWLINK_NOTIFYDSC', 'Receive notification when any new listing is posted.');
define('_MI_XDIR_GLOBAL_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New listing');

define('_MI_XDIR_CATEGORY_LINKSUBMIT_NOTIFY', 'New Listing Submitted');
define('_MI_XDIR_CATEGORY_LINKSUBMIT_NOTIFYCAP', 'Notify me when a new listing is submitted (awaiting approval) to the current category.');
define('_MI_XDIR_CATEGORY_LINKSUBMIT_NOTIFYDSC', 'Receive notification when a new link is submitted (awaiting approval) to the current category.');
define('_MI_XDIR_CATEGORY_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New link submitted in category');

define('_MI_XDIR_CATEGORY_NEWLINK_NOTIFY', 'New Listing');
define('_MI_XDIR_CATEGORY_NEWLINK_NOTIFYCAP', 'Notify me when a new listing is posted to the current category.');
define('_MI_XDIR_CATEGORY_NEWLINK_NOTIFYDSC', 'Receive notification when a new listing is posted to the current category.');
define('_MI_XDIR_CATEGORY_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New business listing in category');

define('_MI_XDIR_LINK_APPROVE_NOTIFY', 'Listing Approved');
define('_MI_XDIR_LINK_APPROVE_NOTIFYCAP', 'Notify me when this listing is approved.');
define('_MI_XDIR_LINK_APPROVE_NOTIFYDSC', 'Receive notification when this listing is approved.');
define('_MI_XDIR_LINK_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Listing approved');
*/
?>