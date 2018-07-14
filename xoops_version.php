<?php
$modversion['name'] = _MI_myReviews_NAME;
$modversion['version'] = 2.19;
$modversion['description'] = _MI_myReviews_DESC;
$modversion['credits'] = "
based on CJReviews modified by CJ<br>
(http://)<br>
Modified by the GiantSpider<br>
(http://www.giantspider.biz)<br>
based on myDownloads module modified by the wanderer<br>
(http://www.mpn-tw.com/)<br>
Based on MyLinks by Kazumi Ono<br>
(http://www.mywebaddons.com/)<br>
The XOOPS Project";
$modversion['author'] = "Riaan AJ van den Berg<br>
Camper<br>
( http://www.craftsonline.co.za )";
$modversion['help'] = "myReviews.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/mydl_slogo.gif";
$modversion['dirname'] = "myReviews";

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "myReviews_cat";
$modversion['tables'][1] = "myReviews_downloads";
$modversion['tables'][2] = "myReviews_text";
$modversion['tables'][3] = "myReviews_excerpt";
$modversion['tables'][4] = "myReviews_votedata";
$modversion['tables'][5] = "myReviews_votecat";
$modversion['tables'][6] = "myReviews_reviews";
$modversion['tables'][7] = "myReviews_editorials";
$modversion['tables'][8] = "myReviews_mod";
$modversion['tables'][9] = "myReviews_ratingcat";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Blocks
$modversion['blocks'][1]['file'] = "myreviews_top.php";
$modversion['blocks'][1]['name'] = _MI_myReviews_BNAME1;
$modversion['blocks'][1]['description'] = "Shows recently added web reviews";
$modversion['blocks'][1]['show_func'] = "b_myreviews_top_show";
$modversion['blocks'][1]['edit_func'] = "b_myreviews_top_edit";
$modversion['blocks'][1]['options'] = "date|10|25";

$modversion['blocks'][2]['file'] = "myreviews_top.php";
$modversion['blocks'][2]['name'] = _MI_myReviews_BNAME2;
$modversion['blocks'][2]['description'] = "Shows most visited web reviews";
$modversion['blocks'][2]['show_func'] = "b_myreviews_top_show";
$modversion['blocks'][2]['edit_func'] = "b_myreviews_top_edit";
$modversion['blocks'][2]['options'] = "hits|10|25";

$modversion['blocks'][3]['file'] = "waiting_reviews.php";
$modversion['blocks'][3]['name'] = _MI_myReviews_BNAME3;
$modversion['blocks'][3]['description'] = "Shows books waiting to be reviewed";
$modversion['blocks'][3]['show_func'] = "b_reviews_waiting_show";
$modversion['blocks'][3]['edit_func'] = "b_myReviews_waiting_edit";
$modversion['blocks'][3]['options'] = "10";

$modversion['blocks'][4]['file'] = "myreviews_fame.php";
$modversion['blocks'][4]['name'] = _MI_myReviews_BNAME4;
$modversion['blocks'][4]['description'] = "Shows most prominent reviewers";
$modversion['blocks'][4]['show_func'] = "b_myreviews_fame_show";
$modversion['blocks'][4]['edit_func'] = "b_myreviews_fame_edit";
$modversion['blocks'][4]['options'] = "10";

$modversion['blocks'][5]['file'] = "myreviews_top.php";
$modversion['blocks'][5]['name'] = _MI_myReviews_BNAME5;
$modversion['blocks'][5]['description'] = "Shows most visited web reviews";
$modversion['blocks'][5]['show_func'] = "b_myreviews_top_show";
$modversion['blocks'][5]['edit_func'] = "b_myreviews_top_edit";
$modversion['blocks'][5]['options'] = "votes|10|25";

$modversion['blocks'][6]['file'] = "myreviews_top.php";
$modversion['blocks'][6]['name'] = _MI_myReviews_BNAME6;
$modversion['blocks'][6]['description'] = "Shows most visited web reviews";
$modversion['blocks'][6]['show_func'] = "b_myreviews_top_show";
$modversion['blocks'][6]['edit_func'] = "b_myreviews_top_edit";
$modversion['blocks'][6]['options'] = "rating|10|25";

// Menu
$modversion['hasMain'] = 1;

$modversion['sub'][1]['name'] = _MI_myReviews_SMNAME3;
$modversion['sub'][1]['url'] = "submit.php";

$modversion['sub'][2]['name'] = _MI_myReviews_SMNAME4;
$modversion['sub'][2]['url'] = "randomfile.php";

$modversion['sub'][3]['name'] = _MI_myReviews_SMNAME5;
$modversion['sub'][3]['url'] = "halloffame.php";

$modversion['sub'][4]['name'] = _MI_myReviews_SMNAME1;
$modversion['sub'][4]['url'] = "topten.php?hit=1";

$modversion['sub'][5]['name'] = _MI_myReviews_SMNAME2;
$modversion['sub'][5]['url'] = "topten.php?rate=1";

$modversion['sub'][6]['name'] = _MI_myReviews_SMNAME6;
$modversion['sub'][6]['url'] = "onion.php?rate=1";

$modversion['sub'][7]['name'] = _MI_myReviews_SMNAME7;
$modversion['sub'][7]['url'] = "loveit.php?rate=1";

$modversion['sub'][8]['name'] = _MI_myReviews_SMNAME8;
$modversion['sub'][8]['url'] = "recommendit.php?rate=1";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "myReviews_search";

// Comments
$modversion['hasComments'] = 0;
$modversion['comments']['pageName'] = 'detailfile.php';
$modversion['comments']['itemName'] = 'reviewid';
// Comment callback functions
$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'myreview_com_approve';
$modversion['comments']['callback']['update'] = 'myreview_com_update';


// Templates
$modversion['templates'][1]['file'] = 'myreviews_detailfile.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'myreviews_modfile.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'myreviews_halloffame.html';
$modversion['templates'][3]['description'] = '';


// Config Settings (only for modules that need config settings generated automatically)

// name of config option for accessing its specified value. i.e. $xoopsModuleConfig['storyhome']
$modversion['config'][1]['name'] = 'xmyReviews_perpage';

// title of this config option displayed in config settings form
$modversion['config'][1]['title'] = '_MD_DLSPERPAGE';

// description of this config option displayed under title
$modversion['config'][1]['description'] = '_MD_DLSPERPAGE_DESC';

// form element type used in config form for this option. can be one of either textbox, textarea, select, select_multi, yesno, group, group_multi
$modversion['config'][1]['formtype'] = 'select';

// value type of this config option. can be one of either int, text, float, array, or other
// form type of 'group_multi', 'select_multi' must always be 'array'
// form type of 'yesno', 'group' must be always be 'int'
$modversion['config'][1]['valuetype'] = 'int';

// the default value for this option
// ignore it if no default
// 'yesno' formtype must be either 0(no) or 1(yes)
$modversion['config'][1]['default'] = 5;

// options to be displayed in selection box
// required and valid for 'select' or 'select_multi' formtype option only
// language constants can be used for both array keys and values
$modversion['config'][1]['options'] = array('5' => 5, '10' => 10, '20' => 20, '30' => 30, '40' => 40, '50' => 50, '100' => 100);


$modversion['config'][2]['name'] = 'xmyReviews_reviewsperpage';
$modversion['config'][2]['title'] = '_MD_DLSREVIEWSPERPAGE';
$modversion['config'][2]['description'] = '_MD_DLSREVIEWSPERPAGE_DESC';
$modversion['config'][2]['formtype'] = 'select';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 5;
$modversion['config'][2]['options'] = array('5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '50' => 50);

$modversion['config'][3]['name'] = 'xmyReviews_popular';
$modversion['config'][3]['title'] = '_MD_HITSPOP';
$modversion['config'][3]['description'] = '_MD_HITSPOP_DESC';
$modversion['config'][3]['formtype'] = 'select';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 5;
$modversion['config'][3]['options'] = array('5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '50' => 50);

$modversion['config'][4]['name'] = 'xmyReviews_newdownloads';
$modversion['config'][4]['title'] = '_MD_DLSNEW';
$modversion['config'][4]['description'] = '_MD_DLSNEW_DESC';
$modversion['config'][4]['formtype'] = 'select';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 5;
$modversion['config'][4]['options'] = array('0' => 0, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '50' => 50);

$modversion['config'][5]['name'] = 'xmyReviews_maxrate';
$modversion['config'][5]['title'] = '_MD_MAXRATE';
$modversion['config'][5]['description'] = '_MD_MAXRATE_DESC';
$modversion['config'][5]['formtype'] = 'select';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 10;
$modversion['config'][5]['options'] = array('2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10, '11' => 11, '12' => 12, '13' => 13, '14' => 14, '15' => 15, '16' => 16, '17' => 17, '18' => 18, '19' => 19, '20' => 20);

$modversion['config'][6]['name'] = 'xmyReviews_shotplacement';
$modversion['config'][6]['title'] = '_MD_SHOTSPLACEMENT';
$modversion['config'][6]['description'] = '_MD_SHOTSPLACEMENT_DESC';
$modversion['config'][6]['formtype'] = 'select';
$modversion['config'][6]['valuetype'] = 'text';
$modversion['config'][6]['default'] = 'top';
$modversion['config'][6]['options'] = array('NONE' => 'none', 'LEFT' => 'left', 'TOP' => 'top');

$modversion['config'][7]['name'] = 'xmyReviews_catsperline';
$modversion['config'][7]['title'] = '_MD_CATEGORIESPERLINE';
$modversion['config'][7]['description'] = '_MD_CATEGORIESPERLINE_DESC';
$modversion['config'][7]['formtype'] = 'select';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 2;
$modversion['config'][7]['options'] = array('1' => 1, '2' => 2, '3' => 3, '4' => 4);

$modversion['config'][8]['name'] = 'xmyReviews_useshots';
$modversion['config'][8]['title'] = '_MD_USESHOTS';
$modversion['config'][8]['description'] = '_MD_USESHOTS_DESC';
$modversion['config'][8]['formtype'] = 'yesno';
$modversion['config'][8]['valuetype'] = 'int';
$modversion['config'][8]['default'] = 1;

$modversion['config'][9]['name'] = 'xmyReviews_shotwidth';
$modversion['config'][9]['title'] = '_MD_SHOTWIDTH';
$modversion['config'][9]['description'] = '_MD_SHOTWIDTH_DESC';
$modversion['config'][9]['formtype'] = 'textbox';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['default'] = 100;

$modversion['config'][10]['name'] = 'xmyReviews_shotloc';
$modversion['config'][10]['title'] = '_MI_SHOTLOCATION';
$modversion['config'][10]['description'] = '_MI_SHOTLOCATION_DESC';
$modversion['config'][10]['formtype'] = 'select';
$modversion['config'][10]['valuetype'] = 'text';
$modversion['config'][10]['default'] = 'outside';
$modversion['config'][10]['options'] = array('INSIDE' => 'inside', 'OUTSIDE' => 'outside');

$modversion['config'][11]['name'] = 'anonpost';
$modversion['config'][11]['title'] = '_MI_MYREVIEWS_ANONPOST';
$modversion['config'][11]['description'] = '_MI_MYREVIEWS_ANONPOST_DESC';
$modversion['config'][11]['formtype'] = 'yesno';
$modversion['config'][11]['valuetype'] = 'int';
$modversion['config'][11]['default'] = 0;

$modversion['config'][12]['name'] = 'autoapprove';
$modversion['config'][12]['title'] = '_MI_MYREVIEWS_AUTOAPPROVE';
$modversion['config'][12]['description'] = '_MI_MYREVIEWS_AUTOAPPROVE_DESC';
$modversion['config'][12]['formtype'] = 'yesno';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] = 0;

$modversion['config'][13]['name'] = 'xmyReviews_detplace';
$modversion['config'][13]['title'] = '_MI_MYREVIEWS_DETPLACE';
$modversion['config'][13]['description'] = '_MI_MYREVIEWS_DETPLACE_DESC';
$modversion['config'][13]['formtype'] = 'select';
$modversion['config'][13]['valuetype'] = 'text';
$modversion['config'][13]['default'] = 'bottom';
$modversion['config'][13]['options'] = array('RIGHT' => 'right', 'BOTTOM' => 'bottom');

$modversion['config'][14]['name'] = 'xmyReviews_blocked';
$modversion['config'][14]['title'] = '_MI_MYREVIEWS_BLOCKED';
$modversion['config'][14]['description'] = '_MI_MYREVIEWS_BLOCKED_DESC';
$modversion['config'][14]['formtype'] = 'yesno';
$modversion['config'][14]['valuetype'] = 'int';
$modversion['config'][14]['default'] = 0;

$modversion['config'][15]['name'] = 'xmyReviews_bartype';
$modversion['config'][15]['title'] = '_MI_MYREVIEWS_BARTYPE';
$modversion['config'][15]['description'] = '_MI_MYREVIEWS_BARTYPE_DESC';
$modversion['config'][15]['formtype'] = 'select';
$modversion['config'][15]['valuetype'] = 'int';
$modversion['config'][15]['default'] = 1;
$modversion['config'][15]['options'] = array('ROUND BAR' => 1, 'STARS' => 2, 'SLIDE BAR' => 3);

$modversion['config'][16]['name'] = 'xmyReviews_catbarwidth';
$modversion['config'][16]['title'] = '_MD_CATBARWIDTH';
$modversion['config'][16]['description'] = '_MD_CATBARWIDTH_DESC';
$modversion['config'][16]['formtype'] = 'textbox';
$modversion['config'][16]['valuetype'] = 'int';
$modversion['config'][16]['default'] = 100;

$modversion['config'][17]['name'] = 'xmyReviews_catlabelwidth';
$modversion['config'][17]['title'] = '_MD_CATLABELWIDTH';
$modversion['config'][17]['description'] = '_MD_CATLABELWIDTH_DESC';
$modversion['config'][17]['formtype'] = 'textbox';
$modversion['config'][17]['valuetype'] = 'int';
$modversion['config'][17]['default'] = 200;

?>