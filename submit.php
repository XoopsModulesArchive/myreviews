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
include_once(XOOPS_ROOT_PATH."/class/module.errorhandler.php");
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";

include(XOOPS_ROOT_PATH."/modules/myReviews/include/config.php");

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$eh = new ErrorHandler; //ErrorHandler object
$mytree = new XoopsTree($xoopsDB->prefix("myReviews_cat"),"cid","pid");

if (empty($xoopsUser) && !$xoopsModuleConfig['anonpost']) {
    redirect_header(XOOPS_URL."/user.php",2,_MD_MUSTREGFIRST);
    exit();
}

$op = isset($_POST['submit']) ? $_POST['submit'] : 'default';

    if(isset($_POST['submit']))
      {
        if(!isset($_POST['submitter']))
          {
            $submitter = $xoopsUser->uid();
          }
          else
          {
    		$submitter = intval($_POST['submitter']);
    	  }//End if


        // Check if Title exist
        if ($_POST["title"]=="")
          {
            $eh->show("1001");
          }//End if

        // Check if Description exist
        if ($_POST['description']=="")
          {
            $eh->show("1008");
          }//End if

        if ( !empty($_POST['cid']) )
          {
            $cid = intval($_POST['cid']);
          }
          else
          {
            $cid = 0;
          }//Enf if

        if ( $xoopsModuleConfig['autoapprove'] == 1 )
          {
            $status = $xoopsModuleConfig['autoapprove'];
          }
          else
          {
            $status = 0;
          }//End if

        $url = $myts->makeTboxData4Save($_POST["url"]);
        $title = $myts->makeTboxData4Save($_POST["title"]);
        $homepage = $myts->makeTboxData4Save($_POST["homepage"]);
        $description = $myts->makeTareaData4Save($_POST["description"]);
        $date = time();

        if ($_FILES["userfile"]!='')
          {
            //$userfile=(get_magic_quotes_gpc()) ? stripslashes($_FILES["userfile"]) : $_FILES["userfile"];
            $userfile=$_FILES['userfile']['name'];

            $extension=get_ext($userfile);
            mt_srand(time());
            $userfile_name=strval(mt_rand(10000000,99999999)).'.'.$extension;
            //$userfile_size=filesize($userfile);
            $userfile_size=$_FILES['userfile']['size'];
            while (file_exists($dir.$userfile_name)) :
              {
                $userfile_name=strval(mt_rand(10000000,99999999)).'.'.$extension;
              }
            endwhile;


            if(file_exists($dir.$userfile_name))
              {
                //echo"<center><br><br><b>Transfer the file</b><br><br>$userfile_name<br><br><b><font color=\"red\">does not exist</font></b><br><br></center><br><br><br><br><br>";
                $logourl = '';
              }
//              elseif($userfile_size>$sizemax)
//              {
//                //echo"<center><br><br><b>Transfer the file</b><br><br>$userfile_name<br><br><b><font color=\"red\">size to big</font></b><br><br></center><br><br><br><br><br>";
//                $logourl = '';
//              }
              elseif(bad_ext($userfile_name))
              {
                //echo"<center><br><br><b>Transfer the file</b><br><br>$userfile_name<br><br><b><font color=\"red\">bad extension</font></b><br><br></center><br><br><br><br><br>";
                $logourl = '';
              }
              else
              {
                $_FILES['userfile']['name'] = $myts->makeTboxData4Save($_FILES['userfile']['name']);
                move_uploaded_file($_FILES['userfile']['tmp_name'], $dir.$userfile_name );
                //copy($userfile,$dir.$userfile_name);
                if(file_exists($dir.$userfile_name))
                  {
                    //echo "<center><br><br><b>Transfer the file</b><br><br>$userfile_name<br><br><b>sucess</b><br><br></center><br><br><br><br><br>";
                    //Create Thumbnail - This is still in development
                    $thumbimg = reviews_createthumb($userfile_name,XOOPS_ROOT_PATH, '/modules/myReviews/images/shots', '/thumbs/',$myReviews_shotwidth,$myReviews_shotwidth,70);
                    $logourl = $userfile_name;
                  }
                  else
                  {
                    //echo "<center><br><br><b>Transfer the file</b><br><br>$userfile_name<br><br><b>problem with copy</b><br><br></center><br><br><br><br><br>";
                    $logourl = '';
                  }//End if
              }//End if

          }
          else
          {
            $logourl = $myts->makeTboxData4Save($_POST["logourl"]);
          }//End if

        $newid = $xoopsDB->genId($xoopsDB->prefix("myReviews_downloads")."_lid_seq");
        $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_downloads")." (lid, cid, title, url, homepage, logourl, submitter, status, date, hits, rating, votes, comments) VALUES ($newid, $cid, '$title', '$url', '$homepage', '$logourl', $submitter, $status, ".time().", 0, 0, 0, 0)") or $eh->show("0013");

        if($newid == 0)
          {
            $newid = $xoopsDB->getInsertId();
          }//End if

        $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_text")." (lid, description) VALUES ($newid, '$description')") or $eh->show("0013");

        redirect_header("index.php",2,_MD_RECEIVED."<br>"._MD_WHENAPPROVED."");
        exit();
      }
      else
      {

	include(XOOPS_ROOT_PATH."/header.php");
    if ($myReviews_blocked)
      {
        OpenTable();
      }//End if

   	mainheader();
   	echo "<table width=\"100%\" cellspacing=0 cellpadding=1 border=0><tr><td colspan=2>\n";
   	echo "<table width=\"100%\" cellspacing=0 cellpadding=8 border=0><tr>
          <td align=\"left\">\n";
   	//echo "<br><br>\n";
   	echo "<li>"._MD_SUBMITONCE."</li>\n";
    echo "<li>"._MD_ALLPENDING."</li>\n";
    echo "<li>"._MD_DONTABUSE."</li>\n";
	echo "<li>"._MD_TAKEDAYS."</li>\n";

    echo "<form action=\"submit.php\" method=post enctype=\"multipart/form-data\">\n";
    echo "<table width=\"80%\"><tr>";
    echo "<td align=\"right\" nowrap><b>"._MD_FILETITLE."</b></td>
          <td align=\"left\" nowrap>";
 	echo "<input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\">";
  	echo "</td></tr><tr><td align=\"right\" nowrap><b>"._MD_DLURL."</b></td><td>";
    echo "<input type=\"text\" name=\"url\" size=\"50\" maxlength=\"250\" value=\"http://\">";
    echo "</td></tr>";

    echo "<tr><td align=\"right\" nowrap><b>"._MD_HOMEPAGEC."</b></td>
          <td align=\"left\" nowrap>\n";
    echo "<input type=\"text\" name=\"homepage\" size=\"50\" maxlength=\"100\" value=\"http://\"></td></tr>\n";

    echo "<tr><td align=\"right\" nowrap><b>"._MD_CATEGORY."</b></td>
          <td align=\"left\" nowrap>";
    $mytree->makeMySelBox("title", "title");
    echo "</td></tr>\n";

    echo "</td></tr>\n";

    echo "<tr><td align=\"right\" valign=\"top\" nowrap><b>"._MD_DESCRIPTIONC."</b></td>
          <td align=\"left\" nowrap>\n";
    echo "<textarea name=description cols=50 rows=6></textarea>\n";
    echo "</td></tr>\n";

    echo "<tr><td align=\"right\"nowrap><b>"._MD_SHOTIMAGE."</b></td>
          <td align=\"left\" nowrap>\n";
    echo "<input type=\"text\" name=\"logourl\" size=\"50\" maxlength=\"60\"></td></tr>\n";
    echo "<tr><td align=\"right\"></td>
          <td align=\"left\" >";

    $directory = XOOPS_URL."/modules/myReviews/images/shots/";
    printf(_MD_MUSTBEVALID,$directory);
    echo "<br /><br />";

    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$sizemax\">";
    echo "<input name=\"userfile\" type=\"file\">";

    echo "</table>\n";
    echo "<br>";
    if (empty($xoopsUser))
      {
        echo "<input type=\"hidden\" name=\"submitter\" value=\"0\"></input>";
      }
      else
      {
        echo "<input type=\"hidden\" name=\"submitter\" value=\"".$xoopsUser->uid()."\"></input>";
      }//End if
    echo "<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\""._MD_SUBMIT."\"></input>\n";
	echo "&nbsp;<input type=button value="._MD_CANCEL." onclick=\"javascript:history.go(-1)\"></input></center>\n";
    echo "</form>\n";
    echo "</td></tr></table></td></tr></table>";

    if ($myReviews_blocked)
      {
        CloseTable();
      }//End if

  }

include("footer.php");
?>