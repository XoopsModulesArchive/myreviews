<?php

###############################################################################
# myReviews v0.3.0                                                                #
#                                                                              #
# $myReviews_popular:        The number of hits required for a download to be a popular site. Default = 20      #
# $myReviews_newdownloads:        The number of downloads that appear on the front page as latest listings. Default = 10  #
# $myReviews_perpage:            The number of downloads that appear for each page. Default = 10 #
# $myReviews_useshots:            Use screenshots? Default = 1 (Yes) #
# $myReviews_shotwidth:            Screenshot Image Width (Default = 140) #
# $myReviews_extensions:            Number of extensions (Default = 1) #
###############################################################################

$myReviews_popular = $xoopsModuleConfig['xmyReviews_popular'];
$myReviews_newdownloads = $xoopsModuleConfig['xmyReviews_newdownloads'];
$myReviews_perpage = $xoopsModuleConfig['xmyReviews_perpage'];
$myReviews_reviewsperpage = $xoopsModuleConfig['xmyReviews_reviewsperpage'];
$myReviews_catnum = 0;
$myReviews_maxrate = $xoopsModuleConfig['xmyReviews_maxrate'];
$myReviews_totrate = $xoopsModuleConfig['xmyReviews_maxrate'];
$myReviews_useshots = $xoopsModuleConfig['xmyReviews_useshots'];
$myReviews_shotwidth = $xoopsModuleConfig['xmyReviews_shotwidth'];
$myReviews_categorybarwidth = $xoopsModuleConfig['xmyReviews_catbarwidth'];
$myReviews_categorylabelwidth = $xoopsModuleConfig['xmyReviews_catlabelwidth'];
$myReviews_categorywidth = $myReviews_categorybarwidth+$myReviews_categorylabelwidth+40;
$myReviews_categoriesperline = $xoopsModuleConfig['xmyReviews_catsperline'];
$myReviews_shotplacement = $xoopsModuleConfig['xmyReviews_shotplacement'];
$myReviews_detail_placement = $xoopsModuleConfig['xmyReviews_detplace'];
$myReviews_blocked = $xoopsModuleConfig['xmyReviews_blocked'];
$myReviews_shotlocation = $xoopsModuleConfig['xmyReviews_shotloc'];
$myReviews_reviewbartype = $xoopsModuleConfig['xmyReviews_bartype'];


?>
