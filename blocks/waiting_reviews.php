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

function b_reviews_waiting_show($options){
	global $xoopsDB, $xoopsUser;
	$block = array();

// myReviews waiting contents
    $myts =& MyTextSanitizer::getInstance();
    //$order = date for most recent reviews
    //$order = hits for most popular reviews
    $result = $xoopsDB->query("SELECT lid, cid, title, date, hits, logourl FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE votes=0 AND status <> 0",$options[0],0);
    //$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE status=0");
    $block['content'] = "<ul>";
    while($myrow=$xoopsDB->fetchArray($result))
      {
        $result2 = $xoopsDB->query("SELECT title, cid FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE cid=".$myrow['cid']."");
        $myrow2 = $xoopsDB->fetchArray($result2);
        $title = $myts->makeTboxData4Show($myrow["title"]);
        $title2 = $myts->makeTboxData4Show($myrow2["title"]);

        if ( !XOOPS_USE_MULTIBYTES )
          {
            if (strlen($title) >= 30)
              {
                $title = substr($title,0,29)."...";
              }//End if
          }//End if
        if ( !XOOPS_USE_MULTIBYTES )
          {
            if (strlen($title2) >= 30)
              {
                $title2 = substr($title2,0,29)."...";
              }//End if
          }//End if

       if ($options[1]==1)
         {
           if ($myrow['logourl'])
             {
               if (file_exists(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$myrow['logourl']))
                 {
                   $block['content'] .= "<center><a href='".XOOPS_URL."/modules/myReviews/images/shots/".$myrow['logourl']."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/shots/thumbs/".$myrow['logourl']."' width='100' border='0' align='center'></a></center>";
                 }//End if
             }//End If
         }//End if

        $block['content'] .= "<li><b>".$title2.":</b> </strong>&nbsp;<a href='".XOOPS_URL."/modules/myReviews/singlefile.php?lid=".$myrow['lid']."'>".$title."</a>&nbsp;(".$myrow['hits'].")</li>";
        $block['title'] = _MB_SYSTEM_myReviewsWDLS;
      }//End while

    $block['content'] .= "</ul>";

	return $block;
}

function b_myReviews_waiting_edit($options) {
    $form = ""._MB_myReviews_DISP."&nbsp;";
    $form .= "<input type=\"text\" name=\"options[]\" value=\"".$options[0]."\" />&nbsp;"._MB_myReviews_FILES."";

    $form .= "<br />"._MB_myReviews_DISPPIC."&nbsp;<input type='radio' id='options[]' name='options[]' value='1'";
    if ( intval($options[1]) == 1 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._YES."&nbsp;<input type='radio' id='options[]' name='options[]' value='0'";
    if ( intval($options[1]) == 0 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._NO."";

    return $form;
}

?>