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
include '../../../mainfile.php';
if ( file_exists(XOOPS_ROOT_PATH.'/modules/myReviews/language/'.$xoopsConfig['language']."/main.php") )
  {
    include (XOOPS_ROOT_PATH.'/modules/myReviews/language/'.$xoopsConfig['language']."/main.php");
  }
  else
  {
    include (XOOPS_ROOT_PATH.'/modules/myReviews/language/english/main.php');
  }//End if

include (XOOPS_ROOT_PATH.'/modules/myReviews/admin/admin_header.php');
include (XOOPS_ROOT_PATH.'/modules/myReviews/include/functions.php');

include (XOOPS_ROOT_PATH.'/modules/myReviews/include/config.php');
include (XOOPS_ROOT_PATH.'/modules/myReviews/ulconf/exten.php');

include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';

//include(XOOPS_ROOT_PATH."/modules/myReviews/cache/config.php");

$myts =& MyTextSanitizer::getInstance();
$eh = new ErrorHandler;
$mytree = new XoopsTree($xoopsDB->prefix("myReviews_cat"),"cid","pid");

function myReviews()
  {
	global $xoopsDB, $xoopsModule, $myts, $mytree;
	xoops_cp_header();
    adminmenu();
    echo "<br />";
    OpenTable();

    // Temporarily 'homeless' downloads (to be revised in index.php breakup)
/*	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_broken")."");
        list($totalbrokendownloads) = $xoopsDB->fetchRow($result);
        if($totalbrokendownloads>0){
                $totalbrokendownloads = "<font color=\"#ff0000\"><b>$totalbrokendownloads</b></font>";
        }
*/
    $result2 = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_mod")."");
    list($totalmodrequests) = $xoopsDB->fetchRow($result2);
    if($totalmodrequests>0)
      {
        $totalmodrequests = "<span style='color: #ff0000; font-weight: bold'>$totalmodrequests</span>";
      }//End if
    $result3 = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE status=0");
    list($totalnewdownloads) = $xoopsDB->fetchRow($result3);
    if($totalnewdownloads>0)
      {
        $totalnewdownloads = "<font color=\"#ff0000\"><b>$totalnewdownloads</b></font>";
      }//End if
    echo " - <a href='".XOOPS_URL."/modules/system/admin.php?fct=preferences&op=showmod&mod=".$xoopsModule->getVar('mid')."'>"._MD_GENERALSET."</a>";
    echo "<br><br>";
    echo " - <a href=index.php?op=booksConfigMenu>"._MD_MANAGEBOOKS."</a>";
    echo "<br><br>";
    echo " - <a href=index.php?op=catConfigMenu>"._MD_MANAGECATEGORIES."</a>";
    echo "<br><br>";
    echo " - <a href=index.php?op=myReviewsCat>"._MD_MANAGERATECAT."</a>";
    echo "<br><br>";
//       echo " - <a href=index.php?op=myReviewsExtensions>"._MD_MANAGEEXTENSIONS."</a>";
//       echo "<br><br>";
//       echo " - <a href=index.php?op=myReviewsUploadExtensions>"._MD_MANAGEUPLOADEXTENSIONS."</a>";
//       echo "<br><br>";
    echo " - <a href=index.php?op=listNewDownloads>"._MD_DLSWAITING." ($totalnewdownloads)</a>";
    echo "<br><br>";
    echo " - <a href=index.php?op=listModReq>"._MD_MODREQUESTS." ($totalmodrequests)</a>";

	$result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE status>0");
    list($numrows) = $xoopsDB->fetchRow($result);
	echo "<br><br><div align=\"center\">";
	printf(_MD_THEREARE,$numrows);	echo "</div>";
    CloseTable();
	xoops_cp_footer();
  }//End if

//################# LISTNEWDOWNLOADS
function listNewDownloads(){
        global $xoopsDB, $myts, $eh, $mytree;
// List downloads waiting for validation
        //$result = $xoopsDB->query("SELECT lid, cid, title, url, homepage, version, size, platform, price, logourl, submitter FROM ".$xoopsDB->prefix("myReviews_downloads")." where status=0 ORDER BY date DESC");
        $result = $xoopsDB->query("SELECT lid, cid, title, url, homepage, logourl, submitter FROM ".$xoopsDB->prefix("myReviews_downloads")." where status=0 ORDER BY date DESC");
        $numrows = $xoopsDB->getRowsNum($result);
	xoops_cp_header();

    adminmenu();
    echo "<br />";

        OpenTable();
        echo "<h4>"._MD_DLSWAITING."&nbsp;($numrows)</h4><br>";
        if ($numrows>0) {
                //while(list($lid, $cid, $title, $url, $homepage, $version, $size, $platform, $price, $logourl, $uid) = $xoopsDB->fetchRow($result)) {
                while(list($lid, $cid, $title, $url, $homepage, $logourl, $uid) = $xoopsDB->fetchRow($result)) {
                	$result2 = $xoopsDB->query("SELECT description FROM ".$xoopsDB->prefix("myReviews_text")." WHERE lid=$lid");
                	list($description) = $xoopsDB->fetchRow($result2);
                	$title = $myts->makeTboxData4Edit($title);
                	$url = $myts->makeTboxData4Edit($url);
                	$homepage = $myts->makeTboxData4Edit($homepage);
                	$description = $myts->makeTareaData4Edit($description);
                	$submitter = XoopsUser::getUnameFromId($uid);
                	echo "<form action=\"index.php\" method=post>\n";
                	echo "<table width=\"80%\">";
                	echo "<tr><td align=\"right\" nowrap>"._MD_SUBMITTER."</td><td>\n";
                	echo "<a href=\"".XOOPS_URL."/userinfo.php?uid=".$uid."\">$submitter</a>";
                	echo "</td></tr>\n";
                	echo "<tr><td align=\"right\" nowrap>"._MD_FILETITLE."</td><td>";
                	echo "<input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\" value=\"$title\">";
                	//echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_DLURL."</td><td>";
                	//echo "<input type=\"text\" name=\"url\" size=\"50\" maxlength=\"250\" value=\"$url\">";
                	echo "&nbsp;[&nbsp;<a href=\"$url\">"._MD_DOWNLOAD."</a>&nbsp;]";
                	echo "</td></tr>";
                	echo "<tr><td align=\"right\" nowrap>"._MD_CATEGORYC."</td><td>";
                	$mytree->makeMySelBox("title", "title", $cid);
                	echo "</td></tr>\n";
                	echo "<tr><td align=\"right\" nowrap>"._MD_HOMEPAGEC."</td><td>\n";
                	echo "<input type=\"text\" name=\"homepage\" size=\"50\" maxlength=\"100\" value=\"$homepage\"></td></tr>\n";

                	echo "<tr><td align=\"right\" valign=\"top\" nowrap>"._MD_DESCRIPTIONC."</td><td>\n";
                	echo "<textarea name=description cols=\"60\" rows=\"5\">$description</textarea>\n";
                	echo "</td></tr>\n";

                	echo "<tr><td align=\"right\" nowrap>"._MD_SHOTIMAGE."</td><td>\n";
                	echo "<input type=\"text\" name=\"logourl\" value=\"$logourl\" size=\"50\" maxlength=\"60\"></td></tr>\n";
                	echo "<tr><td></td><td>";
			$directory = XOOPS_URL."/modules/myReviews/images/shots/";
			printf(_MD_MUSTBEVALID,$directory);

                	echo "</table>\n";
                	echo "<br><input type=\"hidden\" name=\"op\" value=\"approve\"></input>";
                	echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\"></input>";
                	echo "<input type=\"submit\" value=\""._MD_APPROVE."\"></form>\n";
			echo myTextForm("index.php?op=delNewDownload&lid=$lid",_MD_DELETE);
			echo "<br><br>";

                }
	}else{
		echo _MD_NOSUBMITTED;
        }
        CloseTable();
	xoops_cp_footer();
}
//######################END LISTNEWDOWNLOADS

//#################################APPROVE######################################//
function approve(){
        global $xoopsConfig, $xoopsDB, $HTTP_POST_VARS, $myts, $eh;
        $lid = $HTTP_POST_VARS['lid'];
        $title = $HTTP_POST_VARS['title'];
        $cid = $HTTP_POST_VARS['cid'];
	if ( empty($cid) ) {
		$cid = 0;
	}
        $homepage = $HTTP_POST_VARS['homepage'];
        $description = $HTTP_POST_VARS['description'];
        if (($HTTP_POST_VARS["url"]) || ($HTTP_POST_VARS["url"]!="")) {
                $url = $myts->makeTboxData4Save($HTTP_POST_VARS["url"]);
        }
        $logourl = $myts->makeTboxData4Save($HTTP_POST_VARS["logourl"]);
        $title = $myts->makeTboxData4Save($title);
        $homepage = $myts->makeTboxData4Save($homepage);
        $description = $myts->makeTareaData4Save($description);

        //$query = "UPDATE ".$xoopsDB->prefix("myReviews_downloads")." SET cid=$cid, title='$title', url='$url', homepage='$homepage', price='$price', logourl='$logourl', status=1, date=".time()." where lid=".$lid."";
        $query = "UPDATE ".$xoopsDB->prefix("myReviews_downloads")." SET cid=$cid, title='$title', url='$url', homepage='$homepage', logourl='$logourl', status=1, date=".time()." where lid=".$lid."";
//version='$version', size=$size, platform='$platform',
        $xoopsDB->query($query) or $eh->show("0013");
        $query = "UPDATE ".$xoopsDB->prefix("myReviews_text")." SET description='$description' where lid=".$lid."";
        $xoopsDB->query($query) or $eh->show("0013");

        $result = $xoopsDB->query("SELECT submitter FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid");
        list($submitter) = $xoopsDB->fetchRow($result);
	$submitter = new XoopsUser($submitter);
        $subject = sprintf(_MD_YOURFILEAT,$xoopsConfig['sitename']);
        $message = sprintf(_MD_HELLO,$submitter->uname());
	$message .= "\n\n"._MD_WEAPPROVED."\n\n";
	$siteurl = XOOPS_URL."/modules/myReviews/";
	$message .= sprintf(_MD_VISITAT,$siteurl);
	$message .= "\n\n"._MD_THANKSSUBMIT."\n\n".$xoopsConfig['sitename']."\n".XOOPS_URL."\n".$xoopsConfig['adminmail']."";
	$xoopsMailer =& getMailer();
	$xoopsMailer->useMail();
	$xoopsMailer->setToEmails($submitter->getVar("email"));
	$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
	$xoopsMailer->setFromName($xoopsConfig['sitename']);
	$xoopsMailer->setSubject($subject);
	$xoopsMailer->setBody($message);
	$xoopsMailer->send();
        redirect_header("index.php",1,_MD_NEWDLADDED);
}
//################################END APPROVE#############################//


function booksConfigMenu()
{
       
global $xoopsDB, $myts, $eh, $mytree;
// Add a New Main Category
	xoops_cp_header();
    adminmenu();
    echo "<br />";


	// Add a New Sub-Category
        $result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_cat")."");
        list($numrows)=$xoopsDB->fetchRow($result);
        if($numrows>0) {
                echo "<form method=post action=index.php>";
		// If there is a category, add a New Download

                OpenTable();
                echo "<form method=post action=index.php>\n";
                echo "<h4>"._MD_ADDNEWFILE."</h4><br>\n";
                echo "<table width=\"80%\"><tr>\n";
                echo "<td align=\"right\">"._MD_FILETITLE."</td><td>";
                echo "<input type=text name=title size=50 maxlength=100>";
                echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_DLURL."</td><td>";
                echo "<input type=text name=url size=50 maxlength=100 value=\"http://\">";
                echo "</td></tr>";
                echo "<tr><td align=\"right\" nowrap>"._MD_HOMEPAGEC."</td><td>\n";
                echo "<input type=text name=homepage size=50 maxlength=100 value=\"http://\"></td></tr>\n";
                echo "<tr><td align=\"right\" nowrap>"._MD_CATEGORYC."</td><td>";
                $mytree->makeMySelBox("title", "title");
                echo "</td></tr><tr><td></td><td></td></tr>\n";

 //           echo "<tr><td align=\"right\">"._MD_PRICEC."</td><td>\n";
 //       	echo "<input type=text name=price size=45 maxlength=60 value="._MD_FREE."></td></tr>\n";
                echo "<tr><td align=\"right\" valign=\"top\" nowrap>"._MD_DESCRIPTIONC."</td><td>\n";
                echo "<textarea name=description cols=60 rows=5 >"._MD_NONEDESCRIPTION."</textarea>\n";
                echo "</td></tr>\n";

              echo "<tr><td align=\"right\"nowrap>"._MD_SHOTIMAGE."</td><td>\n";
              echo "<input type=\"text\" name=\"logourl\" size=\"50\" maxlength=\"60\"></td></tr>\n";
              echo "<tr><td align=\"right\"></td><td>";
		$directory = XOOPS_URL."/modules/myReviews/images/shots/";
		printf(_MD_MUSTBEVALID,$directory);

		echo "</td></tr>\n";
                echo "</table>\n<br>";
                echo  "<input type=\"hidden\" name=\"op\" value=\"addDownload\"></input>";
                echo "<input type=\"submit\" class=\"button\" value=\""._MD_ADD."\"></input>\n";
                echo "</form>";
                CloseTable();
	}
      else
      {
      OpenTable();
      echo "<br><br><br>";
      echo "<center><font size=4>"._MD_MUSTADD." <a href='index.php?op=catConfigMenu'><font size=4>"._MD_CATADD."</font></a> "._MD_DOWNLOADCAT."</font></center>";
      echo "<br><br><br><br>";
      CloseTable();
      }
      
	// Modify Download
        $result2 = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_downloads")."");
        list($numrows2) = $xoopsDB->fetchRow($result2);
        if ($numrows2>0) {
        	OpenTable();
                echo "<form method=get action=\"index.php\">\n";
                echo "<h4>"._MD_MODDL."</h4><br>\n";
                echo _MD_FILEID."<input type=text name=lid size=12 maxlength=11>\n";
                echo "<input type=hidden name=fct value=myReviews>\n";
                echo "<input type=hidden name=op value=modDownload><br><br>\n";
                echo "<input type=submit value="._MD_MODIFY."></form>\n";
                CloseTable();
	}
   
	xoops_cp_footer();


}

function catConfigMenu(){
        global $xoopsDB, $myts, $eh, $mytree;
// Add a New Main Category
	xoops_cp_header();

    adminmenu();
    echo "<br />";

	OpenTable();
	echo "<form method=post action=index.php>\n";
        echo "<h4>"._MD_ADDMAIN."</h4><br>"._MD_TITLEC."<input type=text name=title size=30 maxlength=50><br>";
        echo _MD_IMGURL."<br><input type=\"text\" name=\"imgurl\" size=\"100\" maxlength=\"150\" value=\"http://\"><br><br>";
        echo "<input type=hidden name=cid value=0>\n";
        echo "<input type=hidden name=op value=addCat>";
        echo "<input type=submit value="._MD_ADD."><br></form>";
	CloseTable();
	echo "<br>";
	// Add a New Sub-Category
        $result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_cat")."");
        list($numrows)=$xoopsDB->fetchRow($result);
        if($numrows>0) {
                OpenTable();
                echo "<form method=post action=index.php>";
                echo "<h4>"._MD_ADDSUB."</h4><br />"._MD_TITLEC."<input type=text name=title size=30 maxlength=50>&nbsp;"._MD_IN."&nbsp;";
                $mytree->makeMySelBox("title", "title");
#               echo "<br>"._MD_IMGURL."<br><input type=\"text\" name=\"imgurl\" size=\"100\" maxlength=\"150\">\n";
                echo "<input type=hidden name=op value=addCat><br><br>";
                echo "<input type=submit value="._MD_ADD."><br></form>";
                CloseTable();
                echo "<br>";

	// Modify Category
                OpenTable();
                echo "<form method=post action=index.php><h4>"._MD_MODCAT."</h4><br>";
                echo _MD_CATEGORYC;
                $mytree->makeMySelBox("title", "title");
                echo "<br><br>\n";
                echo "<input type=hidden name=op value=modCat>\n";
                echo "<input type=submit value="._MD_MODIFY.">\n";
                echo "</form>";
                CloseTable();
                echo "<br>";
	}
	xoops_cp_footer();
}

function modDownload() {
	global $xoopsDB, $HTTP_GET_VARS, $myts, $eh, $mytree;
        $lid = $HTTP_GET_VARS['lid'];
	    xoops_cp_header();

        adminmenu();
        echo "<br />";

        OpenTable();
        $result = $xoopsDB->query("SELECT cid, title, url, homepage, logourl FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid") or $eh->show("0013");
        echo "<h4>"._MD_MODDL."</h4><br>";
        list($cid, $title, $url, $homepage, $logourl) = $xoopsDB->fetchRow($result);
        $title = $myts->makeTboxData4Edit($title);
        $url = $myts->makeTboxData4Edit($url);
        $homepage = $myts->makeTboxData4Edit($homepage);
        $logourl = $myts->makeTboxData4Edit($logourl);
        $result2 = $xoopsDB->query("SELECT description FROM ".$xoopsDB->prefix("myReviews_text")." WHERE lid=$lid");
        list($description)=$xoopsDB->fetchRow($result2);
        $description = $myts->makeTareaData4Edit($description);

        echo "<table>";
        echo "<form method=post action=index.php>";
        echo "<tr><td>"._MD_FILEID."</td><td><b>$lid</b></td></tr>";
        echo "<tr><td>"._MD_FILETITLE."</td><td><input type=text name=title value=\"$title\" size=50 maxlength=100></input></td></tr>\n";

        echo "<tr><td>"._MD_DLURL."</td><td><input type=text name=url value=\"$url\" size=50 maxlength=100></input></td></tr>\n";
        echo "<tr><td>"._MD_HOMEPAGEC."</td><td><input type=text name=homepage value=\"$homepage\" size=50 maxlength=100></input></td></tr>\n";

        echo "<tr><td>"._MD_CATEGORYC."</td><td>";
        $mytree->makeMySelBox("title", "title", $cid);

		echo "<tr><td valign=\"top\">"._MD_DESCRIPTIONC."</td><td><textarea name=description cols=60 rows=5>$description</textarea></td></tr>";

        echo "<tr><td align=\"right\"nowrap>"._MD_SHOTIMAGE."</td><td>\n";
        echo "<input type=\"text\" name=\"logourl\" value=\"$logourl\" size=\"50\" maxlength=\"60\"></td></tr>\n";
        echo "<tr><td align=\"right\"></td><td>";
        $directory = XOOPS_URL."/modules/myReviews/images/shots/";
        printf(_MD_MUSTBEVALID,$directory);


        echo "</td></tr>\n";
		echo "</td></tr>\n";
        echo "</table>";
        echo "<br><BR><input type=hidden name=lid value=$lid></input>\n";
        echo "<input type=hidden name=op value=modDownloadS><input type=submit value="._MD_MODIFY.">";
        echo "</form>\n";
	echo "<table><tr><td>\n";
	echo myTextForm("index.php?op=delDownload&lid=".$lid , _MD_DELETE);
	echo "</td><td>\n";
	echo myTextForm("index.php?op=booksConfigMenu", _MD_CANCEL);
	echo "</td></tr></table>\n";
        echo "<hr>";

//Vote Data
        $result5=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_votedata")."");
        list($totalvotes) = $xoopsDB->getRowsNum($result5);
        $totalrating = 0;
        while(list($rating5) = $xoopsDB->fetchRow($result5))
                {
                $totalrating = $totalrating + $rating5;
                }

        echo "<table valign=top width=100%>\n";
        echo "<tr><td colspan=7><b>";


    echo ""._MD_DLRATINGS." ("._MD_DLRATINGS1." $totalrating)";
	echo "</b><br><br></td></tr>\n";

        // Show Registered Users Votes
        $result5=$xoopsDB->query("SELECT ratingid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE lid = $lid AND ratinguser != 0 ORDER BY ratingtimestamp DESC");
        $votes = $xoopsDB->getRowsNum($result5);
        echo "<tr><td colspan=7><br><br><b>";
	printf(_MD_REGUSERVOTES,$votes);
	echo "</b><br><br></td></tr>\n";
        echo "<tr><td><b>" ._MD_USER."  </b></td><td><b>" ._MD_IP."  </b></td><td><b>" ._MD_RATING."  </b></td><td><b>" ._MD_USERAVG."  </b></td><td><b>" ._MD_TOTALRATE."  </b></td><td><b>" ._MD_DATE."  </b></td><td align=\"center\"><b>" ._MD_DELETE."</b></td></tr>\n";
        if ($votes == 0){
        	echo "<tr><td align=\"center\" colspan=\"7\">" ._MD_NOREGVOTES."<br></td></tr>\n";
	}
        $x=0;
        $colorswitch="";
        while(list($ratingid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp)=$xoopsDB->fetchRow($result5)) {
        	$formatted_date = formatTimestamp($ratingtimestamp);
            	//Individual user information
                $result2=$xoopsDB->query("SELECT rating FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE ratinguser = $ratinguser");
                $uservotes = $xoopsDB->getRowsNum($result2);
                $useravgrating = 0;
                while(list($rating2) = $xoopsDB->fetchRow($result2)){
                        $useravgrating = $useravgrating + $rating2;
                }
                $useravgrating = $useravgrating / $uservotes;
                $useravgrating = number_format($useravgrating, 1);
		$ratinguname = XoopsUser::getUnameFromId($ratinguser);
                // echo "<tr><td bgcolor=\"$colorswitch\">$ratinguname</td><td bgcolor=\"$colorswitch\">$ratinghostname</td><td bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">$useravgrating</td><td bgcolor=\"$colorswitch\">$uservotes</td><td bgcolor=\"$colorswitch\">$formatted_date</td><td bgcolor=\"$colorswitch\" align=\"center\"><b><a href=index.php?op=delVote&lid=$lid&rid=$ratingid>X</a></b></td></tr>\n";

				// echo "<tr><td bgcolor=\"$colorswitch\">$ratinguname</td><td bgcolor=\"$colorswitch\">$ratinghostname</td><td bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">$useravgrating</td><td bgcolor=\"$colorswitch\">$uservotes</td><td bgcolor=\"$colorswitch\">$formatted_date</td><td bgcolor=\"$colorswitch\" align=\"center\">";
				echo "<tr><td bgcolor=\"$colorswitch\">$ratinguname</td><td bgcolor=\"$colorswitch\">$ratinghostname</td><td bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">$useravgrating</td><td bgcolor=\"$colorswitch\">$uservotes</td><td bgcolor=\"$colorswitch\">$formatted_date</td><td bgcolor=\"$colorswitch\" align=\"center\">";
				//echo "<table><tr><td>\n";
				echo myTextForm("index.php?op=delVote&lid=$lid&rid=$ratingid" , "X");
				// echo "</td></tr></table>\n";
                echo "</td></tr>\n";

                $x++;
                if ($colorswitch==""){
                	$colorswitch="";
                } else {
                        $colorswitch="";
                }
	}


//Show Reviews
        $result100=$xoopsDB->query("SELECT reviewid, reviewuser, review, reviewhostname, reviewtimestamp FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid = $lid AND reviewuser != 0 ORDER BY reviewtimestamp DESC");
        $votes = $xoopsDB->getRowsNum($result100);
        echo "<tr><td colspan=7><br><br><b>";
	printf(_MD_REGUSERREVIEWS,$votes);
	echo "</b><br><br></td></tr>\n";
  

        if ($votes == 0){
        	echo "<tr><td align=\"center\" colspan=\"7\">" ._MD_NOREGREVIEWS."<br></td></tr>\n";
	}
        $x=0;
        $colorswitch="";
        while(list($reviewid, $reviewuser, $review, $reviewhostname, $reviewtimestamp)=$xoopsDB->fetchRow($result100)) {
        	$formatted_date = formatTimestamp($reviewtimestamp);
            $review = $myts->makeTareaData4Show($review, 1);

            	//Individual user information
                $result200=$xoopsDB->query("SELECT review FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE reviewuser = $reviewuser");
		      $reviewuname = XoopsUser::getUnameFromId($reviewuser);
                  //echo "$reviewuser";

      echo "<tr bgcolor=\"$colorswitch\"><td colspan=5 align=center valign=top>" ._MD_USER.": <b>$reviewuname</b> &nbsp;&nbsp;&nbsp;&nbsp;" ._MD_IP.": <b>$reviewhostname</b>  &nbsp;&nbsp;&nbsp;&nbsp; " ._MD_DATE.": <b>$formatted_date </b></td><td valign=top align=right> <b>" ._MD_DELETE."</td><td align=center>";  
echo	myTextForm("index.php?op=delReview&lid=$lid&rid=$reviewid&user=$reviewuser" , "X");
echo "</b></td></tr>\n";

if ($colorswitch==""){
                	$colorswitch="";
                } else {
                        $colorswitch="";
                }
				echo "<td bgcolor=\"$colorswitch\" colspan=7>$review</td>";
                echo "</tr>\n";

if ($colorswitch==""){
                	$colorswitch="";
                } else {
                        $colorswitch="";
                }

                $x++;

	}



 /*    // Show Unregistered Users Votes
        $result5=$xoopsDB->query("SELECT ratingid, rating, ratinghostname, ratingtimestamp FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE lid = $lid AND ratinguser = 0 ORDER BY ratingtimestamp DESC");
        $votes = $xoopsDB->getRowsNum($result5);
        echo "<tr><td colspan=7><b><br><br>";
	printf(_MD_ANONUSERVOTES,$votes);
	echo "</b><br><br></td></tr>\n";
        echo "<tr><td colspan=2><b>" ._MD_IP."  </b></td><td colspan=3><b>" ._MD_RATING."  </b></td><td><b>" ._MD_DATE."  </b></b></td><td align=\"center\"><b>" ._MD_DELETE."</b></td><br></tr>";
        if ($votes == 0) {
        	echo "<tr><td colspan=\"7\" align=\"center\">" ._MD_NOUNREGVOTES."<br></td></tr>";
        }
        $x=0;
        $colorswitch="dddddd";
        while(list($ratingid, $rating, $ratinghostname, $ratingtimestamp)=$xoopsDB->fetchRow($result5)) {
        	$formatted_date = formatTimestamp($ratingtimestamp);
			// echo "<td colspan=\"2\" bgcolor=\"$colorswitch\">$ratinghostname</td><td colspan=\"3\" bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">$formatted_date</td><td bgcolor=\"$colorswitch\" aling=\"center\"><b><a href=index.php?op=delVote&lid=$lid&rid=$ratingid>X</a></b></td></tr>";
			echo "<td colspan=\"2\" bgcolor=\"$colorswitch\">$ratinghostname</td><td colspan=\"3\" bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">$formatted_date</td><td bgcolor=\"$colorswitch\" align=\"center\">";
			//echo "<table><tr><td>\n";
			//align=\"center\"
			echo myTextForm("index.php?op=delVote&lid=$lid&rid=$ratingid" , "X");
			//echo "</td></tr></table>\n";

                echo "</td></tr>";

                $x++;
                if ($colorswitch=="dddddd") {
                	$colorswitch="ffffff";
                } else {
                        $colorswitch="dddddd";
                }
	}*/



        echo "<tr><td colspan=\"6\">&nbsp;<br></td></tr>\n";
        echo "</table>\n";
        CloseTable();
        xoops_cp_footer();
}


function delReview()
  {
        global $xoopsDB, $HTTP_GET_VARS, $eh;
        $rid = $HTTP_GET_VARS['rid'];
        $lid = $HTTP_GET_VARS['lid'];
        $user = $HTTP_GET_VARS['user'];

        $query = "SELECT logourl FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE reviewid=$rid";
        list($logourl) = $xoopsDB->fetchRow($query);
        echo"$logourl";
          {
            if ($logourl)
              {
                if (file_exists(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$logourl))
                 {
                   unlink(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$logourl);
                   echo XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$logourl;
                 }//End if
                if (file_exists(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/".$logourl))
                 {
                   unlink(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/".$logourl);
                   echo XOOPS_ROOT_PATH."/modules/myReviews/images/shots/".$logourl;
                 }//End if
              }//End if
          }//End while

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE reviewid=$rid";
        $xoopsDB->query($query) or $eh->show("0013");

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE ratingid=$rid";
        $xoopsDB->query($query) or $eh->show("0013");

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_votecat")." WHERE lid=$lid AND ratinguser=$user";
        $xoopsDB->query($query) or $eh->show("0013");

        updaterating($lid);

        redirect_header("index.php",1,_MD_REVIEWDELETED);
  }//End function


function delVote() {
        global $xoopsDB, $HTTP_GET_VARS, $eh;
        $rid = $HTTP_GET_VARS['rid'];
        $lid = $HTTP_GET_VARS['lid'];

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE ratingid=$rid";
        $xoopsDB->query($query) or $eh->show("0013");
        updaterating($lid);

        redirect_header("index.php",1,_MD_VOTEDELETED);
}

function delRatingCat()
  {
    global $xoopsDB, $HTTP_GET_VARS, $eh;
    $rid = $HTTP_GET_VARS['rid'];

    $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE rid=$rid";
    $xoopsDB->query($query) or $eh->show("0013");

    $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_votecat")." WHERE ratingcat=$rid";
    $xoopsDB->query($query) or $eh->show("0013");

    //updaterating($lid);

   redirect_header("index.php?op=myReviewsCat",1,_MD_VOTEDELETED);
  }//End function

function listBrokenDownloads() {
        global $xoopsDB, $eh;
        $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("myReviews_broken")." ORDER BY reportid");
        $totalbrokendownloads = $xoopsDB->getRowsNum($result);
	xoops_cp_header();
        OpenTable();
        echo "<h4>"._MD_BROKENREPORTS." ($totalbrokendownloads)</h4><br>";

        if ($totalbrokendownloads==0) {
        	echo _MD_NOBROKEN;
        } else {
                echo "<center>"._MD_IGNOREDESC."<br>"._MD_DELETEDESC."</center><br><br><br>";
                $colorswitch="#dddddd";
                echo "<table align=\"center\" width=\"90%\">";
                echo "
                <tr>
                  <td><b>"._MD_FILETITLE."</b></td>
                  <td><b>" ._MD_REPORTER."</b></td>
                  <td><b>" ._MD_FILESUBMITTER."</b></td>
                  <td><b>" ._MD_IGNORE."</b></td>
                  <td><b>" ._MD_DELETE."</b></td>
                </tr>";
                while(list($reportid, $lid, $sender, $ip)=$xoopsDB->fetchRow($result)){
                	$result2 = $xoopsDB->query("SELECT title, url, submitter FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid");
                        if ($sender != 0) {
                                $result3 = $xoopsDB->query("SELECT uname, email FROM ".$xoopsDB->prefix("users")." WHERE uid=".$sender."");
                                list($sendername, $email)=$xoopsDB->fetchRow($result3);
                        }
                        list($title, $url, $owner)=$xoopsDB->fetchRow($result2);
                        $result4 = $xoopsDB->query("SELECT uname, email FROM ".$xoopsDB->prefix("users")." WHERE uid=".$owner."");
                        list($ownername, $owneremail)=$xoopsDB->fetchRow($result4);
                        echo "<tr><td bgcolor=$colorswitch><a href=$url>$title</a></td>";
                        if ($email=="") {
				echo "<td bgcolor=$colorswitch>$sendername ($ip)";
                        } else {
                                echo "<td bgcolor=$colorswitch><a href=mailto:$email>$sendername</a> ($ip)";
                        }
                        echo "</td>";
                        if ($owneremail=='') {
                                echo "<td bgcolor=$colorswitch>$ownername";
                        } else {
				echo "<td bgcolor=$colorswitch><a href=mailto:$owneremail>$ownername</a>";
                        }
                        echo "</td><td bgcolor='$colorswitch' align='center'>\n";
			echo myTextForm("index.php?op=ignoreBrokenDownloads&lid=$lid" , "X");
                        echo "</td>";
                        echo "<td bgcolor='$colorswitch' align='center'>\n";
			echo myTextForm("index.php?op=delBrokenDownloads&lid=$lid" , "X");
                        echo "</td></tr>\n";
                        if ($colorswitch=="#dddddd") {
                                $colorswitch="#ffffff";
                        } else {
                                $colorswitch="#dddddd";
                        }
		}
                echo "</table>";
	}
        CloseTable();
	xoops_cp_footer();
}

function delBrokenDownloads() {
	global $xoopsDB, $HTTP_GET_VARS, $eh;
        $lid = $HTTP_GET_VARS['lid'];
        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_broken")." WHERE lid=$lid";
        $xoopsDB->query($query) or $eh->show("0013");
        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid";
        $xoopsDB->query($query) or $eh->show("0013");
        redirect_header("index.php",1,_MD_FILEDELETED);
}

function ignoreBrokenDownloads() {
        global $xoopsDB, $HTTP_GET_VARS, $eh;
        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_broken")." WHERE lid=".$HTTP_GET_VARS['lid']."";
        $xoopsDB->query($query) or $eh->show("0013");
        redirect_header("index.php",1,_MD_BROKENDELETED);
}

function listModReq()
  {
    global $xoopsDB, $myts, $eh, $mytree, $myReviews_useshots, $myReviews_shotwidth;
    $result = $xoopsDB->query("SELECT requestid, lid, cid, title, url, homepage, logourl, description, modifysubmitter FROM ".$xoopsDB->prefix("myReviews_mod")." ORDER BY requestid");
    $totalmodrequests = $xoopsDB->getRowsNum($result);
	xoops_cp_header();

    adminmenu();
    echo "<br />";

    OpenTable();
    echo "<h4>"._MD_USERMODREQ." ($totalmodrequests)</h4><br>";
    if($totalmodrequests>0)
      {
        echo "<table width=95%><tr><td>";
        while(list($requestid, $lid, $cid, $title, $url, $homepage, $logourl, $description, $modifysubmitter)=$xoopsDB->fetchRow($result))
          {
            $result2 = $xoopsDB->query("SELECT cid, title, url, homepage, logourl, submitter FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid");
            list($origcid, $origtitle, $origurl, $orighomepage, $origlogourl, $ownerid)=$xoopsDB->fetchRow($result2);
            $result2 = $xoopsDB->query("SELECT description FROM ".$xoopsDB->prefix("myReviews_text")." WHERE lid=$lid");
            list($origdescription) = $xoopsDB->fetchRow($result2);

            $result7 = $xoopsDB->query("SELECT uname, email FROM ".$xoopsDB->prefix("users")." WHERE uid=$modifysubmitter");
            $result8 = $xoopsDB->query("SELECT uname, email FROM ".$xoopsDB->prefix("users")." WHERE uid=$ownerid");
            $cidtitle=$mytree->getPathFromId($cid, "title");
            $origcidtitle=$mytree->getPathFromId($origcid, "title");
            list($submittername, $submitteremail)=$xoopsDB->fetchRow($result7);
            list($ownername, $owneremail)=$xoopsDB->fetchRow($result8);
            $title = $myts->makeTboxData4Show($title);
            //$url = $myts->makeTboxData4Show($url);
            $homepage = $myts->makeTboxData4Show($homepage);

            $origlogourl = $myts->makeTboxData4Edit($origlogourl);
            $logourl = $myts->makeTareaData4Show($logourl);
            $description = $myts->makeTareaData4Show($description);
            //$origurl = $myts->makeTboxData4Show($origurl);
            $orighomepage = $myts->makeTboxData4Show($orighomepage);
            $origdescription = $myts->makeTareaData4Show($origdescription);

			if ($ownerid=="")
              {
                $ownername = "administration";
              }//End if
            echo "<table border=1 bordercolor=black cellpadding=5 cellspacing=0 align=center width=90%><tr><td>
                  <table width=100% bgcolor=dddddd>
                    <tr>
                      <td valign=top width=45%><b>"._MD_ORIGINAL."</b></td>
                      <td rowspan=14 valign=top align=left><br>"._MD_DESCRIPTIONC."<br>$origdescription</td>
                    </tr>
                    <tr><td valign=top width=45%><small>"._MD_FILETITLE." ".$origtitle."</small></td></tr>
                    <tr><td valign=top width=45%><small>"._MD_CATEGORYC." ".$origcidtitle."</small></td></tr>
                    <tr><td valign=top width=45%><small>"._MD_HOMEPAGE." ".$orighomepage."</small></td></tr>
                    <tr><td valign=top width=45%><small>"._MD_SHOTIMAGE."</small> ";

			if ( !empty($origlogourl) )
              {
			    echo "<img src=\"".XOOPS_URL."/modules/myReviews/images/shots/".$origlogourl."\" width=\"50\">";
			  }
              else
              {
				echo "&nbsp;";
			  }//End if

			echo "</td></tr>
                  </table></td></tr><tr><td>
                  <table width=100%>
                    <tr>
                      <td valign=top width=45%><b>"._MD_PROPOSED."</b></td>
                      <td rowspan=14 valign=top align=left><br>"._MD_DESCRIPTIONC."<br>$description</td>
                   </tr>
                   <tr><td valign=top width=45%><small>"._MD_FILETITLE." ".$title."</small></td></tr>
                   <tr><td valign=top width=45%><small>"._MD_CATEGORYC." ".$cidtitle."</small></td></tr>
                   <tr><td valign=top width=45%><small>"._MD_HOMEPAGE." ".$homepage."</small></td></tr>
                   <tr><td valign=top width=45%><small>"._MD_SHOTIMAGE."</small> ";

			if ( !empty($logourl) )
              {
				echo "<img src=\"".XOOPS_URL."/modules/myReviews/images/shots/".$logourl."\" width=\"50\">";
			  }
              else
              {
				echo "&nbsp;";
			  }//End if

			echo "</td></tr>
                  </table></td></tr></table>
                  <table align=center width=90%>
                  <tr>";

			if ( $submitteremail=="" )
              {
                echo "<td align=left><small>"._MD_SUBMITTER." $submittername</small></td>";
              }
              else
              {
                echo "<td align=left><small>"._MD_SUBMITTER." <a href=mailto:$submitteremail>$submittername</a></small></td>";
              }//End if

            if ($owneremail=="")
              {
                echo "<td align=center><small>"._MD_OWNER." $ownername</small></td>";
              }
              else
              {
                echo "<td align=center><small>"._MD_OWNER." <a href=mailto:$owneremail>$ownername</a></small></td>";
              }//End if

            echo "<td align=right><small>\n";
			echo "<table><tr><td>\n";
			echo myTextForm("index.php?op=changeModReq&requestid=$requestid" , _MD_APPROVE);
			echo "</td><td>\n";
			echo myTextForm("index.php?op=ignoreModReq&requestid=$requestid", _MD_IGNORE);
			echo "</td></tr></table>\n";
            echo "</small></td></tr>\n";
            echo "</table><br><br>";

		}//End while
        echo "</td></tr></table>";
      }
      else
      {
        echo _MD_NOMODREQ;
      }//End if
    CloseTable();
	xoops_cp_footer();
  }//End function

function changeModReq()
  {
    global $xoopsDB, $HTTP_GET_VARS, $eh, $myts;
    $requestid = $HTTP_GET_VARS['requestid'];
    $query = "SELECT lid, cid, title, url, homepage, logourl, description FROM ".$xoopsDB->prefix("myReviews_mod")." WHERE requestid=$requestid";
    $result = $xoopsDB->query($query);
    while(list($lid, $cid, $title, $url, $logourl, $description)=$xoopsDB->fetchRow($result))
      {
        if (get_magic_quotes_runtime())
          {
            $title = stripslashes($title);
            $url = stripslashes($url);
            $homepage = stripslashes($homepage);
            $logourl = stripslashes($logourl);
            $description = stripslashes($description);
          }//End if
        $title = addslashes($title);
        $url = addslashes($url);
        $homepage = addslashes($homepage);
        $logourl = addslashes($logourl);
        $description = addslashes($description);
        $xoopsDB->query("UPDATE ".$xoopsDB->prefix("myReviews_downloads")." SET cid=$cid,title='$title',url='$url',homepage='$homepage',logourl='$logourl', status=2, date=".time()." WHERE lid=$lid") or $eh->show("0013");
        $xoopsDB->query("UPDATE ".$xoopsDB->prefix("myReviews_text")." SET description='$description' WHERE lid=$lid") or $eh->show("0013");
        $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_mod")." WHERE requestid=$requestid") or $eh->show("0013");
    }//End while
    redirect_header("index.php",1,_MD_DBUPDATED);
  }//End function

function ignoreModReq()
  {
	global $xoopsDB, $HTTP_GET_VARS, $eh;
	$query= "DELETE FROM ".$xoopsDB->prefix("myReviews_mod")." WHERE requestid=".$HTTP_GET_VARS['requestid']."";
	$xoopsDB->query($query) or $eh->show("0013");
    redirect_header("index.php",1,_MD_MODREQDELETED);
  }//End function

function modDownloadS() {
	global $xoopsDB, $HTTP_POST_VARS, $myts, $eh;
	$cid = $HTTP_POST_VARS["cid"];
	if (($HTTP_POST_VARS["url"]) || ($HTTP_POST_VARS["url"]!="")) {
                $url = $myts->makeTboxData4Save($HTTP_POST_VARS["url"]);
        }
        $logourl = $myts->makeTboxData4Save($HTTP_POST_VARS["logourl"]);
        $title = $myts->makeTboxData4Save($HTTP_POST_VARS["title"]);
        $homepage = $myts->makeTboxData4Save($HTTP_POST_VARS["homepage"]);
        $description = $myts->makeTareaData4Save($HTTP_POST_VARS["description"]);
//        $excerpt = $myts->makeTareaData4Save($HTTP_POST_VARS["excerpt"]);

        $xoopsDB->query("UPDATE ".$xoopsDB->prefix("myReviews_downloads")." SET cid=$cid, title='$title', url='$url', homepage='$homepage', logourl='$logourl', status=2, date=".time()." WHERE lid=".$HTTP_POST_VARS['lid']."")  or $eh->show("0013");

        $xoopsDB->query("UPDATE ".$xoopsDB->prefix("myReviews_text")." SET description='$description' WHERE lid=".$HTTP_POST_VARS['lid']."")  or $eh->show("0013");
//        $xoopsDB->query("UPDATE ".$xoopsDB->prefix("myReviews_excerpt")." SET excerpt='$excerpt' WHERE lid=".$HTTP_POST_VARS['lid']."")  or $eh->show("0013");


        redirect_header("index.php",1,_MD_DBUPDATED);
}

function delDownload() {
        global $xoopsDB, $HTTP_GET_VARS, $eh;

        $query =$xoopsDB->query("SELECT logourl FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=".$HTTP_GET_VARS['lid']."");
        list($logourl) = $xoopsDB->fetchRow($query);
          {
            if ($logourl)
              {
                if (file_exists(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$logourl))
                 {
                   $answer = unlink(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$logourl);
                 }//End if
                if (file_exists(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/".$logourl))
                 {
                   $answer = unlink(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/".$logourl);
                 }//End if
              }//End if
          }//End while

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=".$HTTP_GET_VARS['lid']."";
        $xoopsDB->query($query) or $eh->show("0013");

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_text")." WHERE lid=".$HTTP_GET_VARS['lid']."";
        $xoopsDB->query($query) or $eh->show("0013");

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE lid=".$HTTP_GET_VARS['lid']."";
        $xoopsDB->query($query) or $eh->show("0013");

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_votecat")." WHERE lid=".$HTTP_GET_VARS['lid']."";
        $xoopsDB->query($query) or $eh->show("0013");

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid=".$HTTP_GET_VARS['lid']."";
        $xoopsDB->query($query) or $eh->show("0013");

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_editorials")." WHERE lid=".$HTTP_GET_VARS['lid']."";
        $xoopsDB->query($query) or $eh->show("0013");

//        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_excerpt")." WHERE lid=".$HTTP_GET_VARS['lid']."";
//        $xoopsDB->query($query) or $eh->show("0013");

        $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_mod")." WHERE lid=".$HTTP_GET_VARS['lid']."";
        $xoopsDB->query($query) or $eh->show("0013");

        redirect_header("index.php",1,_MD_FILEDELETED);
}

function modCat() {
        global $xoopsDB, $HTTP_POST_VARS, $myts, $eh, $mytree;
        $cid = $HTTP_POST_VARS["cid"];
	xoops_cp_header();

    adminmenu();
    echo "<br />";

        OpenTable();
        echo "<h4>"._MD_MODCAT."</h4><br>";
        $result=$xoopsDB->query("SELECT pid, title, imgurl FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE cid=$cid");
        list($pid,$title,$imgurl) = $xoopsDB->fetchRow($result);
        $title = $myts->makeTboxData4Edit($title);
        $imgurl = $myts->makeTboxData4Edit($imgurl);
        echo "<form action=index.php method=post>"._MD_TITLEC."<input type=text name=title value=\"$title\" size=51 maxlength=50><br><br>"._MD_IMGURLMAIN."<br><input type=text name=imgurl value=\"$imgurl\" size=100 maxlength=150><br />
	<br />"._MD_PARENT."&nbsp;";
	$mytree->makeMySelBox("title", "title", $pid, 1, "pid");
	echo "<input type='hidden' name='cid' value='$cid'>
        <input type=hidden name=op value=modCatS><br>
        <input type=submit value=\""._MD_SAVE."\">
        <input type=button value="._MD_DELETE." onClick=\"location='index.php?pid=$pid&cid=$cid&op=delCat'\">";
        echo "&nbsp;<input type=button value="._MD_CANCEL." onclick=\"javascript:history.go(-1)\">";
        echo "</form>";
        CloseTable();
	xoops_cp_footer();
}

function modCatS() {
        global $xoopsDB, $HTTP_POST_VARS, $myts, $eh;
        $cid =  $HTTP_POST_VARS['cid'];
        $sid =  $HTTP_POST_VARS['pid'];
        $title =  $myts->makeTboxData4Save($HTTP_POST_VARS['title']);
        if (isset($HTTP_POST_VARS["imgurl"]))
          {
            $imgurl = $myts->makeTboxData4Save($HTTP_POST_VARS["imgurl"]);
          }
          else
          {
            $imgurl = '';
          }//End if
        $xoopsDB->query("UPDATE ".$xoopsDB->prefix("myReviews_cat")." SET title='$title', imgurl='$imgurl', pid='$sid' where cid=$cid") or $eh->show("0013");
        redirect_header("index.php?op=catConfigMenu",1,_MD_DBUPDATED);
}

function modRatingCat()
  {
    global $xoopsDB, $HTTP_GET_VARS, $myts, $eh, $mytree;
    $rid = $HTTP_GET_VARS['rid'];
    xoops_cp_header();

    adminmenu();
    echo "<br />";

    OpenTable();
    echo "<h4>"._MD_MODRATCAT."</h4><br>";
    $result=$xoopsDB->query("SELECT ratingcat FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE rid=$rid");
    list($ratingcat) = $xoopsDB->fetchRow($result);
    $ratingcat = $myts->makeTboxData4Edit($ratingcat);
    echo "<form action=index.php method=post>"._MD_TITLEC."<input type=text name=ratingcat value=\"$ratingcat\" size=50 maxlength=50><br />&nbsp;";
    echo "<input type='hidden' name='rid' value='$rid'>
        <input type=hidden name=op value=modRatingCatSave><br>
        <input type=submit value=\""._MD_SAVE."\">";
    echo "&nbsp;<input type=button value="._MD_CANCEL." onclick=\"javascript:history.go(-1)\">";
    echo "</form>";
    CloseTable();
    xoops_cp_footer();
  }//End function

function modRatingCatSave()
  {
        global $xoopsDB, $HTTP_POST_VARS, $myts, $eh;
        $rid =  $HTTP_POST_VARS['rid'];
        $ratingcat =  $myts->makeTboxData4Save($HTTP_POST_VARS['ratingcat']);
        $xoopsDB->query("UPDATE ".$xoopsDB->prefix("myReviews_ratingcat")." SET ratingcat='$ratingcat' where rid=$rid") or $eh->show("0013");
        redirect_header("index.php?op=myReviewsCat",1,_MD_DBUPDATED);
  }//End function

function delCat() {
        global $xoopsDB, $HTTP_GET_VARS, $eh, $mytree;
        $cid =  $HTTP_GET_VARS['cid'];
        if($HTTP_GET_VARS['ok']){
        	$ok =  $HTTP_GET_VARS['ok'];
        }
        if($ok==1) {
                //get all subcategories under the specified category
                $arr=$mytree->getAllChildId($cid);
                for($i=0;$i<sizeof($arr);$i++){
                        //get all downloads in each subcategory
                        $result=$xoopsDB->query("SELECT lid FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE cid=".$arr[$i]."") or $eh->show("0013");
                        //now for each download, delete the text data and vote data associated with the download
                        while(list($lid)=$xoopsDB->fetchRow($result)){
                                $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_text")." WHERE lid=".$lid."") or $eh->show("0013");
                                $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_editorials")." WHERE lid=".$lid."") or $eh->show("0013");
//                                $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_excerpt")." WHERE lid=".$lid."") or $eh->show("0013");
                                $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_mod")." WHERE lid=".$lid."") or $eh->show("0013");
                                $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid=".$lid."") or $eh->show("0013");
                                $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE lid=".$lid."") or $eh->show("0013");
                                $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=".$lid."") or $eh->show("0013");
                                $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_votecat")." WHERE lid=".$lid."") or $eh->show("0013");
                        }

                    //all downloads for each subcategory is deleted, now delete the subcategory data
					$xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE cid=".$arr[$i]."") or $eh->show("0013");
                }
                //all subcategory and associated data are deleted, now delete category data and its associated data
                $result=$xoopsDB->query("SELECT lid FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE cid=".$cid."") or $eh->show("0013");
                while(list($lid)=$xoopsDB->fetchRow($result)){
                        $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid") or $eh->show("0013");
                        $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_text")." WHERE lid=$lid") or $eh->show("0013");

                        $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_editorials")." WHERE lid=$lid") or $eh->show("0013");

//                        $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_excerpt")." WHERE lid=$lid") or $eh->show("0013");
                        $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_mod")." WHERE lid=$lid") or $eh->show("0013");
                        $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid=$lid") or $eh->show("0013");
                        $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE lid=".$lid."") or $eh->show("0013");
                        $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_votecat")." WHERE lid=".$lid."") or $eh->show("0013");
                }
                $xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE cid=$cid") or $eh->show("0013");
                redirect_header("index.php",1,_MD_CATDELETED);
		exit();
	} else {
		xoops_cp_header();
                OpenTable();
                echo "<center>";
                echo "<h4><font color=\"#ff0000\">";
                echo _MD_WARNING."</font></h4><br>";
		echo "<table><tr><td>\n";
		echo myTextForm("index.php?op=delCat&cid=$cid&ok=1",_MD_YES);
		echo "</td><td>\n";
		echo myTextForm("index.php", _MD_NO);
		echo "</td></tr></table>\n";
            	CloseTable();
		xoops_cp_footer();
	}
}

function delNewDownload()
  {
	global $xoopsDB, $HTTP_GET_VARS, $eh;
	$query = "DELETE FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=".$HTTP_GET_VARS['lid']."";
	$xoopsDB->query($query) or $eh->show("0013");
	$query = "DELETE FROM ".$xoopsDB->prefix("myReviews_text")." WHERE lid=".$HTTP_GET_VARS['lid']."";
    $xoopsDB->query($query) or $eh->show("0013");
    $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_editorials")." WHERE lid=".$HTTP_GET_VARS['lid']."";
    $xoopsDB->query($query) or $eh->show("0013");
//	$query = "DELETE FROM ".$xoopsDB->prefix("myReviews_excerpt")." WHERE lid=".$HTTP_GET_VARS['lid']."";
//    $xoopsDB->query($query) or $eh->show("0013");
    $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_mod")." WHERE lid=".$HTTP_GET_VARS['lid']."";
    $xoopsDB->query($query) or $eh->show("0013");
	$query = "DELETE FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid=".$HTTP_GET_VARS['lid']."";
    $xoopsDB->query($query) or $eh->show("0013");

    redirect_header("index.php",1,_MD_FILEDELETED);
  }//End function

function addCat()
  {
    global $xoopsDB, $HTTP_POST_VARS, $myts, $eh;
    $pid = $myts->makeTboxData4Save($HTTP_POST_VARS['cid']);
    $title = $myts->makeTboxData4Save($HTTP_POST_VARS['title']);
    if (isset($HTTP_POST_VARS["imgurl"]))
      {
        $imgurl = $myts->makeTboxData4Save($HTTP_POST_VARS["imgurl"]);
      }
      else
      {
        $imgurl = '';
      }//End if
    $title = $myts->makeTboxData4Save($title);
	$newid = $xoopsDB->genId($xoopsDB->prefix("myReviews_cat")."_cid_seq");
    $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_cat")." (cid, pid, title, imgurl) VALUES ($newid, $pid, '$title', '$imgurl')") or $eh->show("0013");
    redirect_header("index.php?op=catConfigMenu",1,_MD_NEWCATADDED);
  }//End function

function addDownload() {
        global $xoopsDB, $xoopsUser, $HTTP_POST_VARS, $myts, $eh;
        if (($HTTP_POST_VARS["url"]) || ($HTTP_POST_VARS["url"]!="")) {
                $url = $myts->makeTboxData4Save($HTTP_POST_VARS["url"]);
        }
        $logourl = $myts->makeTboxData4Save($HTTP_POST_VARS["logourl"]);
        $title = $myts->makeTboxData4Save($HTTP_POST_VARS["title"]);
        $homepage = $myts->makeTboxData4Save($HTTP_POST_VARS["homepage"]);
        $description = $myts->makeTareaData4Save($HTTP_POST_VARS["description"]);
        //$excerpt = $myts->makeTareaData4Save($HTTP_POST_VARS["excerpt"]);
        $submitter = $xoopsUser->uid();
        $result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE url='$url'");
        list($numrows) = $xoopsDB->fetchRow($result);
	$error = 0;
	$errormsg = "";
        /*if ($numrows>0) {
                $errormsg .= "<h4><font color=\"#ff0000\">";
                $errormsg .= _MD_ERROREXIST."</font></h4><br>";
                $error = 1;
        }*/
// Check if Title exist
        if ($title=="") {
                $errormsg .= "<h4><font color=\"#ff0000\">";
                $errormsg .= _MD_ERRORTITLE."</font></h4><br>";
                $error =1;
        }
	if( empty($size) || !is_numeric($size) ){
		$size = 0;
	}
// Check if Description exist
        if ($description=="") {
                $errormsg .= "<h4><font color=\"#ff0000\">";
                $errormsg .= _MD_ERRORDESC."</font></h4><br>";
                $error =1;
        }

/* // Check if Excerpt exist
        if ($excerpt=="") {
                $errormsg .= "<h4><font color=\"#ff0000\">";
                $errormsg .= _MD_ERROREXCERPT."</font></h4><br>";
                $error =1;
        } */


        if($error == 1) {
		xoops_cp_header();
		echo $errormsg;
                xoops_cp_footer();
                exit();
        }
	if ( !empty($HTTP_POST_VARS['cid']) ) {
        	$cid = $HTTP_POST_VARS['cid'];
	} else {
		$cid = 0;
	}
	$newid = $xoopsDB->genId($xoopsDB->prefix("myReviews_downloads")."_lid_seq");
        $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_downloads")." (lid, cid, title, url, homepage, logourl, submitter, status, date, hits, rating, votes, comments) VALUES ($newid, $cid, '$title', '$url', '$homepage', '$logourl', $submitter, 1, ".time().", 0, 0, 0, 0)") or $eh->show("0013");
        if($newid == 0){
		$newid = $xoopsDB->getInsertId();
	}
        $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_text")." (lid, description) VALUES ($newid, '$description')") or $eh->show("0013");

//        $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_excerpt")." (lid, excerpt) VALUES ($newid, '$excerpt')") or $eh->show("0013");

        redirect_header("index.php",1,_MD_NEWDLADDED);
}

########################ExtensionAdmin############################################
function myReviewsExtensions(){

        global $myReviews_extensions, $myReviews_extitle, $myReviews_exname, $myReviews_eximage, $myReviews_exdlimage, $myReviews_exdlbuyimage;

	xoops_cp_header();
        OpenTable();

        echo "<h4>" . _MD_MANAGEEXTENSIONS . "</h4><br>";
        echo ""._MD_EXTENSIONHERE."<br>";
        echo ""._MD_NOEXTENSIONS1."<a href=\"index.php?op=myReviewsConfigAdmin\">"._MD_NOEXTENSIONS2."</a> "._MD_NOEXTENSIONS3."<br>";
        echo ""._MD_EXTENSIONORDER."<br>";
        echo "<form action=\"index.php\" method=\"post\">";
    	  echo "<table width=50% border=0>";

        for ($x=1;$x<$myReviews_extensions+1;$x++)
        {

        echo "<tr><td nowrap align=right><b>" . _MD_EXTENSIONTITLE . ":".$x."</b> </td><td>";
        echo "<INPUT TYPE=\"text\" size=\"25\" NAME='xmyReviews_extitle".$x."' VALUE=\"$myReviews_extitle[$x]\"></INPUT>";
        echo "</td></tr>";
        echo "<tr><td nowrap align=right>" . _MD_EXTENSIONNAME . " </td><td>";
        echo "<INPUT TYPE=\"text\" size=\"10\" NAME='xmyReviews_exname".$x."' VALUE=\"$myReviews_exname[$x]\"></INPUT>";
        echo "</td></tr>";
        echo "<tr><td nowrap align=right>" . _MD_EXTENSIONIMAGE . " </td><td>";
        echo "<INPUT TYPE=\"text\" size=\"85\" NAME='xmyReviews_eximage".$x."' VALUE=\"$myReviews_eximage[$x]\"></INPUT>";
        echo "</td></tr>";
        echo "<tr><td nowrap align=right>" . _MD_EXTENSIONDLIMAGE . " </td><td>";
        echo "<INPUT TYPE=\"text\" size=\"85\" NAME='xmyReviews_exdlimage".$x."' VALUE=\"$myReviews_exdlimage[$x]\"></INPUT>";
        echo "</td></tr>";
//        echo "<tr><td nowrap align=right>" . _MD_EXTENSIONDLBUYIMAGE . " </td><td>";
//        echo "<INPUT TYPE=\"text\" size=\"85\" NAME='xmyReviews_exdlbuyimage".$x."' VALUE=\"$myReviews_exdlbuyimage[$x]\"></INPUT>";
//        echo "</td></tr>";
        }


        echo "<tr><td>&nbsp;</td></tr>";
        echo "</table>";
        echo "<input type=\"hidden\" name=\"op\" value=\"myReviews_ExtensionChange\">";
        echo "<input type=\"submit\" value=\""._MD_SAVE."\">";
        echo "&nbsp;<input type=\"button\" value=\""._MD_CANCEL."\" onclick=\"javascript:history.go(-1)\">";
        echo "</form>";
       	CloseTable();
	xoops_cp_footer();
}


function myReviews_ExtensionChange ()
{
        global $HTTP_POST_VARS;
        global $myReviews_extensions;

           for ($x=1;$x<$myReviews_extensions+1;$x++)
           {
           $xmyReviews_extitle[$x] = $HTTP_POST_VARS['xmyReviews_extitle'.$x.''];
           $xmyReviews_exname[$x] = $HTTP_POST_VARS['xmyReviews_exname'.$x.''];
           $xmyReviews_eximage[$x] = $HTTP_POST_VARS['xmyReviews_eximage'.$x.''];
           $xmyReviews_exdlimage[$x] = $HTTP_POST_VARS['xmyReviews_exdlimage'.$x.''];
           $xmyReviews_exdlbuyimage[$x] = $HTTP_POST_VARS['xmyReviews_exdlbuyimage'.$x.''];
           }

        $filename = XOOPS_ROOT_PATH."/modules/myReviews/include/config.php";

        $file = fopen($filename, "w");
        $content = "";
        $content .= "<?php\n";
        $content .= "\n";
        $content .= "###############################################################################\n";
        $content .= "# myReviews v0.3.0                                                                #\n";
        $content .= "#                                                                              #\n";
        $content .= "# Stores Extensions#\n";

        $content .= "###############################################################################\n";
        $content .= "\n";

        for ($x=1;$x<$myReviews_extensions+1;$x++)
        {
        $content .= "\$myReviews_extitle[$x] = \"$xmyReviews_extitle[$x]\";\n";
        $content .= "\$myReviews_exname[$x] = \"$xmyReviews_exname[$x]\";\n";        
        $content .= "\$myReviews_eximage[$x] = \"$xmyReviews_eximage[$x]\";\n";
        $content .= "\$myReviews_exdlimage[$x] = \"$xmyReviews_exdlimage[$x]\";\n";
 //       $content .= "\$myReviews_exdlbuyimage[$x] = \"$xmyReviews_exdlbuyimage[$x]\";\n";
        $content .= "\n";
        }
   
        $content .= "?>\n";

        fwrite($file, $content);
        fclose($file);

        redirect_header("index.php",1,_MD_CONFUPDATED);
}


########################ExtensionAdmin############################################
function myReviewsUploadExtensions(){

        global $myReviews_uploadextensions, $ext;

	xoops_cp_header();
        OpenTable();

        echo "<h4>" . _MD_MANAGEUPLOADEXTENSIONS . "</h4><br>";
        echo ""._MD_EXTENSIONHERE."<br>";
        echo ""._MD_NOEXTENSIONS1."<a href=\"index.php?op=myReviewsConfigAdmin\">"._MD_NOEXTENSIONS2."</a> "._MD_NOEXTENSIONS3."<br>";
        echo "<form action=\"index.php\" method=\"post\">";
    	  echo "<table width=25% border=0>";

        for ($x=1;$x<$myReviews_uploadextensions+1;$x++)
        {
        echo "<tr><td nowrap align=right>" . _MD_UPLOADEXTENSIONNAME . " <b>$x</b></td><td>";
        echo "<INPUT TYPE=\"text\" size=\"10\" NAME='xext".$x."' VALUE=\"$ext[$x]\"></INPUT>";
        }


        echo "<tr><td>&nbsp;</td></tr>";
        echo "</table>";
        echo "<input type=\"hidden\" name=\"op\" value=\"myReviews_UploadExtensionChange\">";
        echo "<input type=\"submit\" value=\""._MD_SAVE."\">";
        echo "&nbsp;<input type=\"button\" value=\""._MD_CANCEL."\" onclick=\"javascript:history.go(-1)\">";
        echo "</form>";
       	CloseTable();
	xoops_cp_footer();
}


function myReviews_UploadExtensionChange ()
{
        global $HTTP_POST_VARS;
        global $myReviews_uploadextensions;

           for ($x=1;$x<$myReviews_uploadextensions+1;$x++)
           {
           $xext[$x] = $HTTP_POST_VARS['xext'.$x.''];
           }

        $filename = XOOPS_ROOT_PATH."/modules/myReviews/ulconf/exten.php";

        $file = fopen($filename, "w");
        $content = "";
        $content .= "<?php\n";
        $content .= "\n";
        $content .= "###############################################################################\n";
        $content .= "# myReviews v0.3.0                                                                #\n";
        $content .= "#                                                                              #\n";
        $content .= "# Stores upload extensions#\n";
        $content .= "###############################################################################\n";
        $content .= "\n";

        for ($x=1;$x<$myReviews_uploadextensions+1;$x++)
        {
        $content .= "\$ext[$x] = \"$xext[$x]\";\n";
        $content .= "\n";
        }

        $content .= "?>\n";

        fwrite($file, $content);
        fclose($file);

        redirect_header("index.php",1,_MD_CONFUPDATED);
}

########################ExtensionAdmin############################################
function myReviewsCat()
  {
    global $xoopsDB, $myts, $eh, $mytree;
    xoops_cp_header();

    adminmenu();
    echo "<br />";

    // Add a New RatingSub-Category
    $result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_cat")."");
    list($numrows)=$xoopsDB->fetchRow($result);
    if($numrows>0)
      {
        OpenTable();
        echo "<form method=post action=index.php?op=myReviewsCat>";
        echo "<h4>"._MD_ADDRATINGMAIN."</h4><br />"._MD_TITLEC."<input type=text name=ratingcat size=30 maxlength=50>&nbsp;"._MD_IN."&nbsp;";
        $mytree->makeMySelBox("title", "title");
#               echo "<br>"._MD_IMGURL."<br><input type=\"text\" name=\"imgurl\" size=\"100\" maxlength=\"150\">\n";
                echo "<input type=hidden name=op value=myReviews_addRatingCat><br><br>";
                echo "<input type=submit value="._MD_ADD."><br></form>";
                CloseTable();
                echo "<br>";
       }

    // Modify Category
      $result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_ratingcat")."");
      list($numrows)=$xoopsDB->fetchRow($result);
      if($numrows>0)
        {
          //OpenTable();
          echo "<table width='100%' border='0' cellspacing='2' cellpadding='0' style='border: 2px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";
          echo "<tr>";
          echo "<td colspan=4>";
          echo "<h4>" . _MD_MANAGECAT . "</h4><br>";
          echo "<b><font color=red>" . _MD_CATORDER . "</font></b><br>";
          echo "<b><font color=red>" . _MD_CATORDER1 . "</font></b><br>";
          echo "<b><font color=red>" . _MD_CATORDER2 . "</font></b><br>";
          echo "<b><font color=red>" . _MD_CATORDER3 . "</font></b><br>";
          echo "<br>";
          echo "</td>";
          echo "</tr>";

          echo "<tr>";
          echo "<th><b>"._MD_FEATURES."</b></th>";
          echo "<th><b>"._MD_RATING_CATNAME."</b></th>";
          echo "<th><b>"._MD_MODIFY."</b></th>";
          echo "<th><b>"._MD_DELETE."</b></th>";
          echo "</tr>";
          echo "<br> ";


          //Do the root level
          $result_cats_parent=$xoopsDB->query("SELECT cid, pid, title FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE pid=0 ORDER BY title");
          while(list($cid_parent,$pid_parent,$title_parent)=$xoopsDB->fetchRow($result_cats_parent))
            {
              //Check if Root has rating categories
              $result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid_parent");
              list($numrows)=$xoopsDB->fetchRow($result);
              if($numrows>0)
                {
                  echo "<tr><td class='odd' colspan=4 valign='top'><b>$title_parent</b></td></tr>";
                  //Root has rating categories
                  $result_root_ratingcats=$xoopsDB->query("SELECT rid, cid, ratingcat FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid_parent");
                  while(list($rid_root_rating,$cid_root_rating,$ratingcat_root_rating)=$xoopsDB->fetchRow($result_root_ratingcats))
                    {
                      echo "<tr>";
                      echo "<td valign='top'></td>";
                      echo "<td valign='top'>$ratingcat_root_rating</td>";
                      echo "<td valign='top'>";
                      echo myTextForm("index.php?op=modRatingCat&rid=$rid_root_rating" , ""._MD_MODIFY."");
                      echo "</td>";
                      echo "<td valign='top'>";
                      echo myTextForm("index.php?op=delRatingCat&rid=$rid_root_rating" , "X");
                      echo "</td>";
                      echo "</tr>";
                    }//End while
                }//End if

              //Do the Child 1 level
              $result_cats_child1=$xoopsDB->query("SELECT cid, pid, title FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE pid=$cid_parent ORDER BY title");
              while(list($cid_child1,$pid_child1,$title_child1)=$xoopsDB->fetchRow($result_cats_child1))
                {
                  //Check if Child 1 has rating categories
                  $result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid_child1");
                  list($numrows)=$xoopsDB->fetchRow($result);
                  if($numrows>0)
                    {
                      echo "<tr><td class='odd' colspan=4 valign='top'><b>$title_parent -- $title_child1</b></td></tr>";
                      //Child 1 has rating categories
                      $result_child1_ratingcats=$xoopsDB->query("SELECT rid, cid, ratingcat FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid_child1");
                      while(list($rid_child1_rating,$cid_child1_rating,$ratingcat_child1_rating)=$xoopsDB->fetchRow($result_child1_ratingcats))
                        {
                          echo "<tr>";
                          echo "<td valign='top'></td>";
                          echo "<td valign='top'>$ratingcat_child1_rating</td>";
                          echo "<td valign='top'>";
                          echo myTextForm("index.php?op=modRatingCat&rid=$rid_child1_rating" , ""._MD_MODIFY."");
                          echo "</td>";
                          echo "<td valign='top'>";
                          echo myTextForm("index.php?op=delRatingCat&rid=$rid_child1_rating" , "X");
                          echo "</td>";
                          echo "</tr>";
                        }//End while
                    }//End if

                  //Do the Child 2 level
                  $result_cats_child2=$xoopsDB->query("SELECT cid, pid, title FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE pid=$cid_child1 ORDER BY title");
                  while(list($cid_child2,$pid_child2,$title_child2)=$xoopsDB->fetchRow($result_cats_child2))
                    {
                      //Check if Child 2 has rating categories
                      $result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid_child2");
                      list($numrows)=$xoopsDB->fetchRow($result);
                      if($numrows>0)
                        {
                          echo "<tr><td class='odd' colspan=4 valign='top'><b>$title_parent -- $title_child1 -- $title_child2</b></td></tr>";
                          //Child 2 has rating categories
                          $result_child2_ratingcats=$xoopsDB->query("SELECT rid, cid, ratingcat FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid_child2");
                          while(list($rid_child2_rating,$cid_child2_rating,$ratingcat_child2_rating)=$xoopsDB->fetchRow($result_child2_ratingcats))
                            {
                              echo "<tr>";
                              echo "<td valign='top'></td>";
                              echo "<td valign='top'>$ratingcat_child2_rating</td>";
                              echo "<td valign='top'>";
                              echo myTextForm("index.php?op=modRatingCat&rid=$rid_child2_rating" , ""._MD_MODIFY."");
                              echo "</td>";
                              echo "<td valign='top'>";
                              echo myTextForm("index.php?op=delRatingCat&rid=$rid_child2_rating" , "X");
                              echo "</td>";
                              echo "</tr>";
                            }//End while
                        }//End if

                    //Do the Child 3 level
                    $result_cats_child3=$xoopsDB->query("SELECT cid, pid, title FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE pid=$cid_child2 ORDER BY title");
                    while(list($cid_child3,$pid_child3,$title_child3)=$xoopsDB->fetchRow($result_cats_child3))
                      {
                        //Check if Child 3 has rating categories
                        $result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid_child3");
                        list($numrows)=$xoopsDB->fetchRow($result);
                        if($numrows>0)
                          {
                            echo "<tr><td class='odd' colspan=4 valign='top'><b>$title_parent -- $title_child1 -- $title_child2 -- $title_child3</b></td></tr>";
                            //Child 3 has rating categories
                            $result_child3_ratingcats=$xoopsDB->query("SELECT rid, cid, ratingcat FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid_child3");
                            while(list($rid_child3_rating,$cid_child3_rating,$ratingcat_child3_rating)=$xoopsDB->fetchRow($result_child3_ratingcats))
                              {
                                echo "<tr>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>$ratingcat_child3_rating</td>";
                                echo "<td valign='top'>";
                                echo myTextForm("index.php?op=modRatingCat&rid=$rid_child3_rating" , ""._MD_MODIFY."");
                                echo "</td>";
                                echo "<td valign='top'>";
                                echo myTextForm("index.php?op=delRatingCat&rid=$rid_child3_rating" , "X");
                                echo "</td>";
                                echo "</tr>";
                              }//End while
                          }//End if

                      //Do the Child 4 level
                      $result_cats_child4=$xoopsDB->query("SELECT cid, pid, title FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE pid=$cid_child3 ORDER BY title");
                      while(list($cid_child4,$pid_child4,$title_child4)=$xoopsDB->fetchRow($result_cats_child4))
                        {
                          //Check if Child 4 has rating categories
                          $result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid_child4");
                          list($numrows)=$xoopsDB->fetchRow($result);
                          if($numrows>0)
                            {
                              echo "<tr><td class='odd' colspan=4 valign='top'><b>$title_parent -- $title_child1 -- $title_child2 -- $title_child3 -- $title_child4</b></td></tr>";
                              //Child 4 has rating categories
                              $result_child4_ratingcats=$xoopsDB->query("SELECT rid, cid, ratingcat FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid_child4");
                              while(list($rid_child4_rating,$cid_child4_rating,$ratingcat_child4_rating)=$xoopsDB->fetchRow($result_child4_ratingcats))
                                {
                                  echo "<tr>";
                                  echo "<td valign='top'></td>";
                                  echo "<td valign='top'>$ratingcat_child4_rating</td>";
                                  echo "<td valign='top'>";
                                  echo myTextForm("index.php?op=modRatingCat&rid=$rid_child4_rating" , ""._MD_MODIFY."");
                                  echo "</td>";
                                  echo "<td valign='top'>";
                                  echo myTextForm("index.php?op=delRatingCat&rid=$rid_child4_rating" , "X");
                                  echo "</td>";
                                  echo "</tr>";
                                }//End while
                            }//End if

                        }//End while
                     }//End while
                  }//End while
              }//End while
           }//End while
        }//End if

    //CloseTable();
    echo "</td></tr></table>";

    xoops_cp_footer();
/*
    global $myReviews_catname, $myReviews_catnum, $myReviews_catweight;

	xoops_cp_header();
    OpenTable();

    echo "<h4>" . _MD_MANAGECAT . "</h4><br>";
    echo "<b><font color=red>" . _MD_CATORDER . "</font></b><br><br>";
    echo "<b><font color=red>" . _MD_CATORDER1 . "</font></b><br><br>";
    echo "<b><font color=red>" . _MD_CATORDER2 . "</font></b><br><br>";
    echo "<b><font color=red>" . _MD_CATORDER3 . "</font></b><br><br>";
//        echo ""._MD_EXTENSIONHERE."<br>";
//        echo ""._MD_NOEXTENSIONS1."<a href=\"index.php?op=myReviewsConfigAdmin\">"._MD_NOEXTENSIONS2."</a> "._MD_NOEXTENSIONS3."<br>";
    echo "<form action=\"index.php\" method=\"post\">";
    echo "<table width=25% border=0>";
    echo "<tr><td></td><td></td><td align=center></td><td></td></tr>";

//    for ($x=1;$x<$myReviews_catnum+1;$x++)
    for ($x=1;$x<10+1;$x++)
      {
        echo "<tr><td nowrap align=right><b>" . _MD_RATING_CATNAME . " $x</b></td><td>";
        echo "<INPUT TYPE=\"text\" size=\"25\" NAME='xmyReviews_catname".$x."' VALUE=\"$myReviews_catname[$x]\"></INPUT></td><td>";
// echo "<INPUT TYPE=\"text\" size=\"10\" NAME='xmyReviews_catweight".$x."' VALUE=\"$myReviews_catweight[$x]\"></INPUT></td><td align=center>";
//	echo myTextForm("index.php?op=delVote&lid=$lid&rid=$ratingid" , "X");
        echo "</td>";
      }//End for

    echo "<tr><td>&nbsp;</td></tr>";
    echo "</table>";
    echo "<input type=\"hidden\" name=\"op\" value=\"myReviews_CatChange\">";
    echo "<input type=\"submit\" value=\""._MD_SAVE."\">";
    echo "&nbsp;<input type=\"button\" value=\""._MD_CANCEL."\" onclick=\"javascript:history.go(-1)\">";
    echo "</form>";


//   	  echo "<table width=25% border=0>";
//        echo "<tr><td></td><td align=center>Delete</td></tr>";
//
//        for ($x=1;$x<$myReviews_catnum+1;$x++)
//        {
//        echo "<tr><td align=right>$myReviews_catname[$x]</td><td align=center>";
//	  echo myTextForm("index.php?op=delVote&lid=$lid&rid=$ratingid" , "X");
//        echo "</td></tr>";
//        }

        echo "</table>";

    CloseTable();
	xoops_cp_footer();

*/
  }//End function

function myReviews_CatChange ()
{
        global $HTTP_POST_VARS;
        global $myReviews_catnum;
//        global $xoopsConfig;

//           for ($x=1;$x<$myReviews_catnum+1;$x++)
           for ($x=1;$x<10+1;$x++)
           {
           $xmyReviews_catname[$x] = $HTTP_POST_VARS['xmyReviews_catname'.$x.''];
           $xmyReviews_catweight[$x] = $HTTP_POST_VARS['xmyReviews_catweight'.$x.''];
           }

if ( file_exists(XOOPS_ROOT_PATH.'/modules/myReviews/language/'.$xoopsConfig['language']."/catconfig.php") )
  {
    $filename = XOOPS_ROOT_PATH.'/modules/myReviews/language/'.$xoopsConfig['language']."/catconfig.php";
  }
  else
  {
    $filename = XOOPS_ROOT_PATH.'/modules/myReviews/language/english/catconfig.php';
  }

//        $filename = XOOPS_ROOT_PATH."/modules/myReviews/catconfig.php";

        $file = fopen($filename, "w");
        $content = "";
        $content .= "<?php\n";
        $content .= "\n";
        $content .= "###############################################################################\n";
        $content .= "# myReviews v0.3.0                                                                #\n";
        $content .= "#                                                                              #\n";
        $content .= "# Stores upload extensions#\n";
        $content .= "###############################################################################\n";
        $content .= "\n";

//        for ($x=1;$x<$myReviews_catnum+1;$x++)
        for ($x=1;$x<10+1;$x++)
        {
        $content .= "\$myReviews_catname[$x] = \"$xmyReviews_catname[$x]\";\n";
        $content .= "\$myReviews_catweight[$x] = \"$xmyReviews_catweight[$x]\";\n";
        $content .= "\n";
        }

        $content .= "?>\n";

        fwrite($file, $content);
        fclose($file);

        redirect_header("index.php",1,_MD_CONFUPDATED);
}

function myReviews_addRatingCat()
  {
    global $xoopsDB, $HTTP_POST_VARS, $myts, $eh;
    $cid = $myts->makeTboxData4Save($HTTP_POST_VARS['cid']);
    $ratingcat = $myts->makeTboxData4Save($HTTP_POST_VARS['ratingcat']);
    $ratingcat = $myts->makeTboxData4Save($ratingcat);
    $newid = $xoopsDB->genId($xoopsDB->prefix("myReviews_Ratingcat")."_rid_seq");
    $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_ratingcat")." (rid, cid, ratingcat) VALUES ($newid, $cid, '$ratingcat')") or $eh->show("0013");
    redirect_header("index.php?op=myReviewsCat",1,_MD_NEWCATADDED);
  }//End function

/*
* Whats this class/function for?
*
* To render a nice ordered menu for your modules.
* You can change the amount of cells per menu table, i.e. 2.3.4.5.6 etc and will render the cell class per cell
*
* The menu item can either be defined before you call adminmenu or you could change the menu array to you own taste.
*
*/

/**
* adminmenu()
*
* @param string $header optional : You can gice the menu a nice header
* @param string $extra optional : You can gice the menu a nice footer
* @param array $menu required : This is an array of links. U can
* @param int $scount required : This will difine the amount of cells long the menu will have.
* NB: using a value of 3 at the moment will break the menu where the cell colours will be off display.
* @return
*/

/*
* Notice: There is a slight problem with dealing with 3 cell menu.
* For some reason 3 doesn't convert well when divided by 2 ;-) You will always get 1.5 lol
* Will fix this issue but should be good enough to use now
*/

function adminmenu( $header = 'myReviews Admin', $extra = '', $menu = '', $scount = 3 )
{
    global $xoopsConfig, $xoopsModule;
    if ( file_exists(XOOPS_ROOT_PATH.'/modules/myReviews/language/'.$xoopsConfig['language']."/modinfo.php") )
      {
        include_once (XOOPS_ROOT_PATH.'/modules/myReviews/language/'.$xoopsConfig['language']."/modinfo.php");
      }
      else
      {
        include_once (XOOPS_ROOT_PATH.'/modules/myReviews/language/english/modinfo.php');
      }//End if


    if ( empty( $menu ) )
    {
        /*
        * You can change this part to suit your own module. Defining this here will save you form having to do this each time.
        */
        $menu = array(
            _MI_myReviews_ADMENU1 => "" . XOOPS_URL . "/modules/system/admin.php?fct=preferences&op=showmod&mod=" . $xoopsModule -> getVar( 'mid' ) . "",
            _MI_myReviews_ADMENU2 => "index.php?op=booksConfigMenu",
            _MI_myReviews_ADMENU6 => "index.php?op=catConfigMenu",
            _MI_myReviews_ADMENU8 => "index.php?op=myReviewsCat",
            _MI_myReviews_ADMENU9 => "index.php?op=listNewDownloads",
            _MI_myReviews_ADMENU10 => "index.php?op=listModReq"
            );
    }
    /*
    * the amount of cells per menu row
    */
    $count = 0;
    /*
    * Set up the first class
    */
    $class = "even";
    /*
    * Sets up the width of each menu cell
    */
    $width = 100 / $scount;

    /*
    * Menu table begin
    */
    echo "<h3>" . $header . "</h3>";
    echo "<table width = '100%' cellpadding= '2' cellspacing= '1' class='outer'><tr>";

    /*
    * Check to see if $menu is and array
    */
    if ( is_array( $menu ) )
    {
        foreach ( $menu as $menutitle => $menulink )
        {
            $count++;
            echo "<td class='$class' align='center' valign='middle' width= $width%>";
            echo "<a href='" . $menulink . "'>" . $menutitle . "</a></td>";

            /*
            * Break menu cells to start a new row if $count > $scount
            */
            if ( $count >= $scount )
            {
                /*
                * If $class is the same for the end and start cells, invert $class
                */
                $class = ( ( ( $count % 2 ) == 0 ) && $class == 'even' ) ? "even" : "odd";
                echo "</tr>";
                $count = 0;
            }
            else
            {
                $class = ( $class == 'even' ) ? "odd" : "even";
            }
        }
        /*
        * checks to see if there are enough cell to fill menu row, if not add empty cells
        */
        if ( $count >= 1 )
        {
            $counter = 0;
            while ( $counter < $scount - $count )
            {
                echo '<td class="' . $class . '">&nbsp;</td>';
                $class = ( $class == 'even' ) ? 'odd' : 'even';
                $counter++;
            }
        }
        echo "</table>";
    }
    if ( $extra )
    {
        echo "<br /><div>$extra</div>";
    }
}

if(!isset($HTTP_POST_VARS['op'])) {
    $op = isset($HTTP_GET_VARS['op']) ? $HTTP_GET_VARS['op'] : 'main';
} else {
    $op = $HTTP_POST_VARS['op'];
}
switch ($op) {
		default:
			myReviews();
			break;
		case "delNewDownload":
			delNewDownload();
			break;
            case "catConfigMenu":
			catConfigMenu();
			break;
            case "myReviewsCat":
			myReviewsCat();
			break;
            case "myReviews_CatChange":
			myReviews_CatChange();
			break;
          case "myReviews_addRatingCat":
            myReviews_addRatingCat();
            break;
	      case "myReviews_ExtensionChange":
			myReviews_ExtensionChange();
			break;
	      case "myReviews_UploadExtensionChange":
			myReviews_UploadExtensionChange();
			break;
		case "approve":
			approve();
			break;
		case "addCat":
			addCat();
			break;
		case "addSubCat":
			addSubCat();
			break;
		case "addDownload":
			addDownload();
			break;
		case "listBrokenDownloads":
			listBrokenDownloads();
			break;
		case "delBrokenDownloads":
			delBrokenDownloads();
			break;
		case "ignoreBrokenDownloads":
			ignoreBrokenDownloads();
			break;
		case "listModReq":
			listModReq();
			break;
		case "changeModReq":
			changeModReq();
			break;
	        case "ignoreModReq":
			ignoreModReq();
			break;
		case "delCat":
			delCat();
			break;
		case "modCat":
			modCat();
			break;
        case "modRatingCat":
            modRatingCat();
            break;
        case "modRatingCatSave":
            modRatingCatSave();
            break;
		case "modCatS":
			modCatS();
			break;
		case "modDownload":
			modDownload();
			break;
		case "modDownloadS":
			modDownloadS();
			break;
		case "delDownload":
			delDownload();
			break;
		case "delVote":
			delVote();
			break;
		case "delReview":
			delReview();
			break;
        case "delRatingCat":
            delRatingCat();
            break;
		case "delComment":
			delComment($bid, $rid);
			break;
		case "myReviewsConfigAdmin":
			myReviewsConfigAdmin();
			break;
		case "myReviewsConfigChange":
			if (xoopsfwrite()) {
				myReviewsConfigChange();
			}
			break;
		case "booksConfigMenu":
			booksConfigMenu();
			break;
		case "listNewDownloads":
			listNewDownloads();
			break;
            case "myReviewsExtensions":
			myReviewsExtensions();
			break;
            case "myReviewsUploadExtensions":
			myReviewsUploadExtensions();
			break;
}
?>