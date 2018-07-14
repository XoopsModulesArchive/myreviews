<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
//  myReviews                                                                //
//  by Eric R. Evans                                                         //
//  GiantSpider Publisher                                                    //
//  http://www.giantspider.biz                                               //
//  based on mydownloads                                                     //
// ------------------------------------------------------------------------- //



/******************************************************************************
 * Function: b_myReviews_top_show
 * Input   : $options[0] = date for the most recent review
 *                    hits for the most popular review
 *                    votes for the most voted review
 *                    rating for the best ranked review
 *           $block['content'] = The optional above content
 *           $options[1]   = How many reviews are displayed
 * Output  : Returns the most recent or most popular reviews
 ******************************************************************************/
function b_myReviews_top_show($options) {
	global $xoopsDB;
	$block = array();
	$myts =& MyTextSanitizer::getInstance();
	//$order = date for most recent reviews
	//$order = hits for most popular reviews
    //$order = votes for most voted reviews
    //$order = rating for best ranked reviews
	$result = $xoopsDB->query("SELECT lid, cid, title, date, hits, votes, rating, logourl FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE status>0 ORDER BY ".$options[0]." DESC",$options[1],0);
	$block['content'] = "<ul>";
	while($myrow=$xoopsDB->fetchArray($result)){
		$result2 = $xoopsDB->query("SELECT title, cid FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE cid=".$myrow['cid']."");
		$myrow2 = $xoopsDB->fetchArray($result2);
		$title = $myts->makeTboxData4Show($myrow["title"]);
		$title2 = $myts->makeTboxData4Show($myrow2["title"]);
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
       if ($options[2]==1)
         {
           if ($myrow['logourl'])
             {
               if (file_exists(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$myrow['logourl']))
                 {
                   $block['content'] .= "<center><a href='".XOOPS_URL."/modules/myReviews/images/shots/".$myrow['logourl']."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/shots/thumbs/".$myrow['logourl']."' width='100' border='0' align='center'></a></center>";
                 }//End if
             }//End If
         }//End if

		if($options[0] == "date"){
			$block['content'] .= "<li><b>".$title2.":</b> </strong>&nbsp;<a href='".XOOPS_URL."/modules/myReviews/detailfile.php?lid=".$myrow['lid']."'>".$title." </a>&nbsp;(".formatTimestamp($myrow['date'],"s").")</li>";
			$block['title'] = _MB_myReviews_TITLE1;
		}elseif($options[0] == "hits"){
			$block['content'] .= "<li><b>".$title2.":</b> </strong>&nbsp;<a href='".XOOPS_URL."/modules/myReviews/detailfile.php?lid=".$myrow['lid']."'>".$title." </a>&nbsp;(".$myrow['hits'].")</li>";
			$block['title'] = _MB_myReviews_TITLE2;
        }elseif($options[0] == "votes"){
            $block['content'] .= "<li><b>".$title2.":</b> </strong>&nbsp;<a href='".XOOPS_URL."/modules/myReviews/detailfile.php?lid=".$myrow['lid']."'>".$title." </a>&nbsp;(".$myrow['votes'].")</li>";
            $block['title'] = _MB_myReviews_TITLE3;
        }elseif($options[0] == "rating"){
            $block['content'] .= "<li><b>".$title2.":</b> </strong>&nbsp;<a href='".XOOPS_URL."/modules/myReviews/detailfile.php?lid=".$myrow['lid']."'>".$title." </a>&nbsp;(".round($myrow['rating'],1).")</li>";
            $block['title'] = _MB_myReviews_TITLE4;
        }
	}
    	$block['content'] .= "</ul>";
	return $block;
}

function b_myReviews_top_edit($options) {
	$form = ""._MB_myReviews_DISP."&nbsp;";
	$form .= "<input type=\"hidden\" name=\"options[]\" value=\"";
	if($options[0] == "date"){
		$form .= "date\"";
	}elseif ($options[0] == "hits"){
		$form .= "hits\"";
    }elseif ($options[0] == "votes"){
        $form .= "votes\"";
    }elseif ($options[0] == "rating"){
        $form .= "rating\"";
    }
	$form .= " />";
	$form .= "<input type=\"text\" name=\"options[]\" value=\"".$options[1]."\" />&nbsp;"._MB_myReviews_FILES."";

    $form .= "<br />"._MB_myReviews_DISPPIC."&nbsp;<input type='radio' id='options[]' name='options[]' value='1'";
    if ( intval($options[2]) == 1 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._YES."&nbsp;<input type='radio' id='options[]' name='options[]' value='0'";
    if ( intval($options[2]) == 0 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._NO."";

	return $form;
}
?>