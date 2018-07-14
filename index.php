<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
// Based on:                                                                 //
// myPHPNUKE Web Portal System - http://myphpnuke.com/                       //
// PHP-NUKE Web Portal System - http://phpnuke.org/                          //
// Thatware - http://thatware.org/                                           //
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
//  myReviews a XOOPS module                                                 //
//  by Riaan AJ van den Berg, Camper                                         //
//  http://www.craftsonline.co.za                                            //
//  Based on mydownloads modified by the wanderer (http://www.mpn-tw.com)    //
//  Based on MyLinks by Kazumi Ono (http://www.mywebaddons.com)              //
//  Based on CJreviews                                                       //
//  Based on myReviews by Eric R. Evans, The GiantSpider (http://www.giantspider.biz)//
// ------------------------------------------------------------------------- //

include("../../mainfile.php");
include ('header.php');
//include (XOOPS_ROOT_PATH.'/modules/myReviews/header.php');
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
//include(XOOPS_ROOT_PATH."/header.php");
include(XOOPS_ROOT_PATH."/modules/myReviews/include/config.php");

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix("myReviews_cat"),"cid","pid");

$q = "SELECT cid, title, imgurl FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE pid = 0 ORDER BY title";
$result=$xoopsDB->query($q) or die("");

if($xoopsConfig['startpage'] == "myReviews")
  {
    $xoopsOption['show_rblock'] =1;
	include(XOOPS_ROOT_PATH."/header.php");
	//make_cblock();
    //echo "<br />";
  }
  else
  {
    $xoopsOption['show_rblock'] =1;
	include(XOOPS_ROOT_PATH."/header.php");
  }

if ($myReviews_blocked)
  {
    OpenTable();
  }//End if

 $mainlink = 0;
mainheader($mainlink);
echo "<center>\n";
$letters = letters();
echo "Browse reviews by alphabetical listing<br />";
echo "<div align = 'center' class = 'itemPermaLink'>$letters</div><br />";

echo "<table border=\"0\" cellspacing=\"5\" cellpadding=\"0\" width=\"90%\"><tr>\n";
$count = 0;

switch ($myReviews_shotplacement)
  {
    case 'left':
      //Shotplacement left
      while($myrow = $xoopsDB->fetchArray($result))
        {
          $title = $myts->makeTboxData4Show($myrow['title']);
          echo "<td valign=\"top\" align=\"right\">";
          if ($myrow['imgurl'] && $myrow['imgurl'] != "http://")
            {
              $imgurl = $myts->makeTboxData4Edit($myrow['imgurl']);
              echo "<a href=\"".XOOPS_URL."/modules/myReviews/viewcat.php?cid=".$myrow['cid']."\"><img src=\"".$imgurl."\" height=\"50\" border=\"0\"></a>";
            }
            else
            {
              echo "";
            }//End IF
          $totaldownload = getTotalItems($myrow['cid'], 1);
            echo "</td><td valign=\"top\" width=\"40%\"><a href=\"".XOOPS_URL."/modules/myReviews/viewcat.php?cid=".$myrow['cid']."\"><b>$title</b></a>&nbsp;($totaldownload)<br>";
          // get child category objects
          $arr=array();
          $arr=$mytree->getFirstChild($myrow['cid'], "title");
          $space = 0;
          $chcount = 0;
          foreach($arr as $ele)
            {
              $chtitle=$myts->makeTboxData4Show($ele['title']);
              if ($chcount>5)
                {
                  echo "...";
                  break;
                }//End If
              if ($space>0)
                {
                  echo ", ";
                }//End IF
              echo "<a href=\"".XOOPS_URL."/modules/myReviews/viewcat.php?cid=".$ele['cid']."\">".$chtitle."</a>";
              $space++;
              $chcount++;
            }//End Foreach
          if ($count<1)
            {
              echo "</td>";
            }//End If
          $count++;
          if ($count==$myReviews_categoriesperline)
            {
              echo "</td></tr><tr>";
              $count = 0;
            }//End If
        }//End While

        break;
    case 'top':
      //Shotplacement top
      while($myrow = $xoopsDB->fetchArray($result))
        {
          $title = $myts->makeTboxData4Show($myrow['title']);
          echo "<td valign=\"top\" align=\"center\">";
          if ($myrow['imgurl'] && $myrow['imgurl'] != "http://")
            {
              $imgurl = $myts->makeTboxData4Edit($myrow['imgurl']);
              echo "<a href=\"".XOOPS_URL."/modules/myReviews/viewcat.php?cid=".$myrow['cid']."\"><img src=\"".$imgurl."\" height=\"50\" border=\"0\"></a><br>";
            }
            else
            {
              echo "";
            }//End IF
          $totaldownload = getTotalItems($myrow['cid'], 1);
            echo "<a href=\"".XOOPS_URL."/modules/myReviews/viewcat.php?cid=".$myrow['cid']."\"><b>$title</b></a>&nbsp;($totaldownload)<br>";
          // get child category objects
          $arr=array();
          $arr=$mytree->getFirstChild($myrow['cid'], "title");
          $space = 0;
          $chcount = 0;
          foreach($arr as $ele)
            {
              $chtitle=$myts->makeTboxData4Show($ele['title']);
              if ($chcount>5)
                {
                  echo "...";
                  break;
                }//End If
              if ($space>0)
                {
                  echo ", ";
                }//End IF
              echo "<a href=\"".XOOPS_URL."/modules/myReviews/viewcat.php?cid=".$ele['cid']."\">".$chtitle."</a>";
              $space++;
              $chcount++;
            }//End Foreach
          if ($count<1)
            {
              echo "</td>";
            }//End If
          $count++;
          if ($count==$myReviews_categoriesperline)
            {
              echo "</td></tr><tr></tr><tr>";
              $count = 0;
            }//End If
        }//End While

        break;
    case 'none':
      //Shotplacement top
      while($myrow = $xoopsDB->fetchArray($result))
        {
          $title = $myts->makeTboxData4Show($myrow['title']);
          echo "<td valign=\"top\" align=\"center\">";
          if ($myrow['imgurl'] && $myrow['imgurl'] != "http://")
            {
              $imgurl = $myts->makeTboxData4Edit($myrow['imgurl']);
              echo "<a href=\"".XOOPS_URL."/modules/myReviews/viewcat.php?cid=".$myrow['cid']."\"><img src=\"".$imgurl."\" height=\"50\" border=\"0\"></a><br>";
            }
            else
            {
              echo "";
            }//End IF
          if ($count<1)
            {
              echo "</td>";
            }//End If
          $count++;
          if ($count==$myReviews_categoriesperline)
            {
              echo "</td></tr><tr></tr><tr>";
              $count = 0;
            }//End If
        }//End While

        break;
  }//End switch


echo "</td></tr></table>";
list($numrows)=$xoopsDB->fetchRow($xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE status>0"));
echo "<br><br>";
printf(_MD_THEREARE,$numrows);
echo "</center>";

if ($myReviews_blocked)
  {
    CloseTable();
  }//End if

echo "<br>";


if ($myReviews_newdownloads!=0)
  {
    if ($myReviews_blocked)
      {
        OpenTable();
      }//End if

    echo "<div border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"><big><b>"._MD_LATESTLIST."</b></big><br><br>";
    showNew($mytree);
    echo "</div>";

    if ($myReviews_blocked)
      {
        CloseTable();
      }//End if

  }//End if

include(XOOPS_ROOT_PATH."/modules/myReviews/footer.php");

// Shows the Latest Listings on the front page
function showNew($mytree)
  {
    global $myts, $xoopsDB, $xoopsConfig, $xoopsModule;
	global $myReviews_shotwidth, $myReviews_newdownloads, $myReviews_useshots, $myReviews_totrate, $myReviews_totname, $myReviews_catnum, $myReviews_popular, $myReviews_perpage, $myReviews_maxrate, $myReviews_categorybarwidth, $myReviews_categorylabelwidth, $myReviews_categorywidth, $myReviews_shotlocation, $myReviews_reviewbartype;
	$result = $xoopsDB->query("SELECT d.lid, d.cid, d.title, d.url, d.homepage, d.logourl, d.status, d.date, d.hits, d.rating, d.votes, d.comments, d.submitter, t.description, d.loveit, d.helpfull, d.unhelpfull, d.recommendit FROM ".$xoopsDB->prefix("myReviews_downloads")." d, ".$xoopsDB->prefix("myReviews_text")." t WHERE d.status>0 AND d.lid=t.lid ORDER BY date DESC",$myReviews_newdownloads,0);

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\"><tr><td width=\"".$myReviews_shotwidth."\" align=\"center\" border=\"0\">";

    $x=0;

    while(list($lid, $cid, $dtitle, $url, $homepage, $logourl, $status, $time, $hits, $rating, $votes,  $comments, $submitter, $description, $loveit, $helpfull, $unhelpfull, $recommendit)=$xoopsDB->fetchRow($result))
      {
        $p = "SELECT e.editorial FROM ".$xoopsDB->prefix("myReviews_editorials")." e WHERE e.lid=$lid ";
        $editorialresult=$xoopsDB->query($p);
        list($editorial)=$xoopsDB->fetchRow($editorialresult);

        $rating = number_format($rating, 2);
        $dtitle = $myts->makeTboxData4Show($dtitle);
        $url = $myts->makeTboxData4Show($url);
        $url = urldecode($url);
        $homepage = $myts->makeTboxData4Show($homepage);
        $logourl = $myts->makeTboxData4Show($logourl);
#       $logourl = urldecode($logourl);
        $datetime = formatTimestamp($time,"s");

        $description = $myts->makeTareaData4Show($description,1);
        $editorial = $myts->makeTareaData4Show($editorial,1);

        $loveit = $myts->makeTboxData4Show($loveit);
        $helpfull = $myts->makeTboxData4Show($helpfull);
        $unhelpfull = $myts->makeTboxData4Show($unhelpfull);
        $recommendit = $myts->makeTboxData4Show($recommendit);

        include("include/dlformat.php");
        $x++;

   	  }//End while
   if ($x<=0)
     {
       echo "<td width=\"100%\">";
     }//End if
   echo "<div border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"><b>"._MD_LEGEND."</b><br><br>";
   echo "<a href='".XOOPS_URL."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/home.gif' border='0'> "._MD_HOMEPAGE." </a>&nbsp;";
   echo "<a href='".XOOPS_URL."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/cart.gif' border='0'> "._MD_CART." </a>&nbsp;";
   echo "<a href='".XOOPS_URL."/modules/myReviews/loveit.php?rate=1'><img src='".XOOPS_URL."/modules/myReviews/images/inlove.gif' border='0'> "._MD_LOVEIT." </a>&nbsp;";
   echo "<a href='".XOOPS_URL."/modules/myReviews/recommendit.php?rate=1'><img src='".XOOPS_URL."/modules/myReviews/images/recommend.gif' border='0'> "._MD_RECOMMENDIT." </a>&nbsp;";
   echo "</div>";
   if ($x<=0)
     {
       echo "</td>";
     }//End if

   echo "</table>";

  }//End Function
?>