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

include("header.php");
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix("myReviews_cat"),"cid","pid");

// Used to view just a single DL file information. Called from the rating pages

$lid = intval($HTTP_GET_VARS['lid']);

//Love at first sight
if(isset($_GET['loveit']))
  {
    if ($_GET['loveit']==1)
      {
        $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("myReviews_downloads")." SET loveit=1 WHERE lid=$lid");
        redirect_header("singlefile.php?lid=$lid",1,_MD_REVIEWUPDATED);
      }
      else
      {
        $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("myReviews_downloads")." SET loveit=0 WHERE lid=$lid");
        redirect_header("singlefile.php?lid=$lid",1,_MD_REVIEWUPDATED);
      }
    exit();
  }//End If

//Admin Recommendation at first sight
if(isset($_GET['recommendit']))
  {
    if ($_GET['recommendit']==1)
      {
        $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("myReviews_downloads")." SET recommendit=1 WHERE lid=$lid");
        redirect_header("singlefile.php?lid=$lid",1,_MD_REVIEWUPDATED);
      }
      else
      {
        $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("myReviews_downloads")." SET recommendit=0 WHERE lid=$lid");
        redirect_header("singlefile.php?lid=$lid",1,_MD_REVIEWUPDATED);
      }
    exit();
  }//End If


//$cid = $HTTP_GET_VARS['cid'];
include(XOOPS_ROOT_PATH."/header.php");
OpenTable();
mainheader();

$q = "SELECT d.lid, d.cid, d.title, d.url, d.homepage, d.logourl, d.status, d.date, d.hits, d.rating, d.votes, d.comments, d.submitter, t.description, d.loveit, d.helpfull, d.unhelpfull, d.recommendit FROM ".$xoopsDB->prefix("myReviews_downloads")." d, ".$xoopsDB->prefix("myReviews_text")." t WHERE d.lid=$lid AND d.lid=t.lid AND status>0";
$result=$xoopsDB->query($q);
list($lid, $cid, $title, $url, $homepage, $logourl, $status, $time, $hits, $rating, $votes, $comments, $submitter, $description, $loveit, $helpfull, $unhelpfull, $recommendit)=$xoopsDB->fetchRow($result);

$p = "SELECT e.editorial FROM ".$xoopsDB->prefix("myReviews_editorials")." e WHERE e.lid=$lid ";
$editorialresult=$xoopsDB->query($p);
list($editorial)=$xoopsDB->fetchRow($editorialresult);

echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\"><tr><td align=\"center\">\n";
echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\"><tr><td>\n";
$pathstring = "<a href=index.php>"._MD_MAIN."</a>&nbsp;:&nbsp;";
$nicepath = $mytree->getNicePathFromId($cid, "title", "viewcat.php?op=");
$pathstring .= $nicepath;
echo "<b>".$pathstring."</b>";
echo "</td></tr></table><br>";
echo "<table width=\"100%\" cellspacing=0 cellpadding=10 border=0>";

$rating = number_format($rating, 2);
$dtitle = $myts->makeTboxData4Show($title);
$url = $myts->makeTboxData4Show($url);
$url = urldecode($url);
$homepage = $myts->makeTboxData4Show($homepage);
$logourl = $myts->makeTboxData4Show($logourl);
#$logourl = urldecode($logourl);
$datetime = formatTimestamp($time,"s");
$description = $myts->makeTareaData4Show($description,1);
$editorial = $myts->makeTareaData4Show($editorial,1);

$loveit = $myts->makeTboxData4Show($loveit);
$helpfull = $myts->makeTboxData4Show($helpfull);
$unhelpfull = $myts->makeTboxData4Show($unhelpfull);
$recommendit = $myts->makeTboxData4Show($recommendit);

include("include/dlformat.php");

echo "</td></tr></table>\n";
echo "</td></tr></table>\n";
CloseTable();

include("footer.php");

?>