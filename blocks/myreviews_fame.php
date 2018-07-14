<?php
/******************************************************************************
 * Function: b_myReviews_fame_show
 * Input   : $options[0] = How many reviews are displayed
 *           $block['content'] = The optional above content
 * Output  : Returns the most previlant reviewers
 ******************************************************************************/
function b_myReviews_fame_show($options) {
	global $xoopsDB;
	$block = array();
	$myts =& MyTextSanitizer::getInstance();
	//$order = date for most recent reviews
	//$order = hits for most popular reviews
	$result = $xoopsDB->query("SELECT ratinguser, count(*) as reviews FROM ".$xoopsDB->prefix("myReviews_votedata")." GROUP BY ratinguser ORDER BY reviews DESC",$options[0],0);
	$block['content'] = "<ul>";
    $q=1;
	while($myrow=$xoopsDB->fetchArray($result))
      {
		$ratinguser = $myts->makeTboxData4Show($myrow["ratinguser"]);
		$reviews = $myts->makeTboxData4Show($myrow["reviews"]);
        $reviewuname = XoopsUser::getUnameFromId($ratinguser);
/*
		if ( !XOOPS_USE_MULTIBYTES ) {
			if (strlen($title) >= 33) {
				$title = substr($title,0,32)."...";
			}
		}
		if ( !XOOPS_USE_MULTIBYTES ){
                        if (strlen($title2) >= 33) {
                                $title2 = substr($title2,0,32)."...";
                        }
                }
*/
    	$block['content'] .= "<li><b>".$q.":</b> </strong>&nbsp;<a href=".XOOPS_URL."/userinfo.php?uid=$ratinguser>$reviewuname</a>&nbsp;(".$myrow['reviews'].")</li>";
	    $block['title'] = _MB_myReviews_TITLE3;
        $q++;
	}
    	$block['content'] .= "</ul>";
	return $block;
}

function b_myReviews_fame_edit($options) {
    $form = ""._MB_myReviews_DISP."&nbsp;";
    $form .= "<input type=\"text\" name=\"options[]\" value=\"".$options[0]."\" />&nbsp;"._MB_myReviews_FILES."";
    return $form;
}
?>