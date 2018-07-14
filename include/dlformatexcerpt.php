<?php
include("../cache/config.php");
include("config.php");
include("../../mainfile.php");


if (basename($GLOBALS['PHP_SELF']) == "dlformatread.php") {
	exit();
}
echo "<tr>";
if ( $myReviews_useshots ) {
	$tablewidth = $myReviews_shotwidth+10;
	echo "<td width='".$tablewidth."' align='center'";
	if ( $logourl ) {
		echo "><a href='".XOOPS_URL."/modules/myReviews/visit.php?lid=".$lid."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/shots/".$logourl."' width='".$myreviews_shotwidth."' border='0'></a></td><td>";
        } else {
        	echo " style='display: none'></td><td colspan='2'>";
        }
//	echo "</td>";
} else {
	echo "<td>";
}
$path = $mytree->getPathFromId($cid, "title");
$path = substr($path, 1);
$path = str_replace("/"," <img src='".XOOPS_URL."/modules/myReviews/images/arrow.gif' board='0' alt=''> ",$path);
echo "<table width='100%' border='0' cellspacing='1' cellpadding='0' class='bg4'><tr><td>";
echo "<table width='100%' border='0' cellspacing='1' cellpadding='4' class='bg1'><tr><td colspan='2'>";

echo "<b>"._MD_CATEGORYC."</b>".$path."";
echo "</td></tr>";

echo "<tr>";
echo "<td class='bg1' width=50%><b><img src='images/download.gif' border='0' alt='"._MD_DLNOW."'>";

echo "&nbsp;".$dtitle."</b>";

newdownloadgraphic($time, $status);
popgraphic($hits);
echo "</td><td class='bg1' align='right'>";

if ( $rating!="0" || $rating!="0.0" ) {
	if ($votes == 1) {
		$votestring = _MD_ONEVOTE;
	} else {
		$votestring = sprintf(_MD_NUMVOTES,$votes);
	}
	echo "<b>"._MD_RATINGC."</b>$rating ($votestring)";
}

echo "&nbsp;";

echo "<img src='".XOOPS_URL."/modules/myReviews/images/counter.gif' width='14' height='14' border='0' align='absmiddle' alt='".sprintf(_MD_DLTIMES,$hits)."' />&nbsp;".$hits."&nbsp;&nbsp;<b>"._MD_PRICEC.""._MD_MONEY."</b>&nbsp;$price&nbsp;</td></tr>";


echo "<tr><td colspan='2' class='bg4'>";
echo "<img src='".XOOPS_URL."/modules/myReviews/images/decs.gif' board='0' width='14' height='14' align='buttom' alt='"._MD_DESCRIPTION."' />&nbsp;:&nbsp; $description<br>";
echo "</td></tr>";

//The Link Engine
echo "<tr><td class='bg1' colspan='2' align='center'><b>";

//This statement ties the book links to PayPal or to a hard download link
if ($price==_MD_FREE)
{
echo "<table align=center width='100%'>\n";
echo "<tr>\n";

for ($x=1;$x<$myreviews_extensions+1;$x++)
{
echo "<td align=middle><img src='".$myreviews_eximage[$x]."' width='16' height='16' border='0'align='absmiddle'/>&nbsp;<b>".$myreviews_extitle[$x]."</b></td>\n";
}
echo "</tr>\n";
echo "<tr>\n";


for ($x=1;$x<$myreviews_extensions+1;$x++)
{
echo "<td align=middle><a href='".XOOPS_URL."/modules/myReviews/dl".$x.".php?lid=$lid' target='_blank'><img src='".$myreviews_exdlimage[$x]."' border='0' align='absmiddle' alt='"._MD_DOWNLOADNOW."'/></a></b></td>\n";
}
echo "</tr>\n";
echo "</table>\n";
echo "<br>\n";
}
else
{
//This section ties the link to PayPal if the customer has to pay for the file.
echo "<table align=center width='100%'>\n";
echo "<tr>\n";
for ($x=1;$x<$myreviews_extensions+1;$x++)
{
echo "<td align=middle><img src='".$myreviews_eximage[$x]."' width='16' height='16' border='0' align='absmiddle'/>&nbsp;<b>".$myreviews_extitle[$x]."</b></td>\n";
}

echo "</tr>\n";
echo "<tr>\n";
for ($x=1;$x<$myreviews_extensions+1;$x++)
{
echo "<td align=middle>\n";
echo "<form action=https://www.paypal.com/cgi-bin/webscr method=post>\n";
echo "<input type=hidden name=cmd value=_xclick>\n";
echo "<input type=hidden name=business value='$homepage'>\n";
echo "<input type=hidden name=item_name value='$dtitle, ".$myreviews_extitle[$x]." format'>\n";
echo "<input type=hidden name=item_number value=$lid>\n";
echo "<input type=hidden name=amount value='$price'>\n";
echo "<input type=hidden name=no_shipping value=1>\n";
echo "<input type=hidden name=return value='".XOOPS_URL."/modules/myReviews/dl".$x.".php?lid=$lid'>\n";
echo "<input type=hidden name=cancel_return value='".XOOPS_URL."'>\n";
echo "<input type=hidden name=no_note value=1>\n";
echo "<input type=image src=".$myreviews_exdlbuyimage[$x]." border=0 name=submit alt='"._MD_BUYPAYPAL."'>\n";
echo "</form>\n";
echo "</td>\n";
}

echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
}

echo "</td></tr>";
echo "<tr><td colspan='2' class='bg4' align='center'>";

//voting & comments stats

if ($comments != 0) {
	if ($comments == 1) {
		$poststring = _MD_ONEPOST;
	} else {
		$poststring = sprintf(_MD_NUMPOSTS,$comments);
	}
	echo "<b>"._MD_COMMENTSC."</b>$poststring";
}

echo "<a href='javascript:history.go(-1)'>"._MD_BACKSTEP."</a>";

echo " | <a href='".XOOPS_URL."/modules/myReviews/detailfile.php?lid=".$lid."'>"._MD_DETAILS."</a>";

echo " | <a href='".XOOPS_URL."/modules/myReviews/ratefile.php?lid=".$lid."'>"._MD_RATETHISFILE."</a>";

echo " | <a target='_top' href='mailto:?subject=".rawurlencode(sprintf(_MD_INTFILEAT,$xoopsConfig['sitename']))."&body=".rawurlencode(sprintf(_MD_INTFILEFOUND,$xoopsConfig['sitename']).":  ".XOOPS_URL."/modules/myReviews/singlefile.php?lid=".$lid)."'>"._MD_TELLAFRIEND."</a>";

if ($xoopsUser)
{
             $reviewexist=0;
             $reviewuser = $xoopsUser->uid();
             $result10=$xoopsDB->query("SELECT reviewuser FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid=$lid");
             while(list($reviewuserDB)=$xoopsDB->fetchRow($result10)) 
                {
                  if ($reviewuserDB==$reviewuser) 
                  {
                     $reviewexist=1;
                  }
                  else
                  {
                     $reviewexist=0;
                  }
                }
            if ($reviewexist==0)
               {
               echo " | <a href='".XOOPS_URL."/modules/myReviews/reviewbook.php?lid=".$lid."'>"._MD_VSCOMMENTS."</a>";
                $reviewexist=0;
               }
               else
               {
               echo " | <a href='".XOOPS_URL."/modules/myReviews/reviewbook.php?lid=".$lid."'>"._MD_REVIEWEDIT."</a>";
               $reviewexist=0;
               }

}

echo " | <a target='_top' href='mailto:".($xoopsConfig['adminmail'])."?subject=".rawurlencode(sprintf(_MD_MAILBROKEN1,$dtitle))."&body=".rawurlencode(sprintf(_MD_BROKENLINK).":  ".XOOPS_URL."/modules/myReviews/singlefile.php?lid=".$lid)."'>"._MD_REPORTBROKEN."</a>";

global $xoopsUser;

if ( $xoopsUser ) 
      {
	if ( $xoopsUser->isAdmin($xoopsModule->mid()) ) 
            {
             $editexist1=0;
             $editorialuser1 = $xoopsUser->uid();
             $result1=$xoopsDB->query("SELECT editorialuser FROM ".$xoopsDB->prefix("myReviews_editorials")." WHERE lid=$lid");
             while(list($editorialuserDB)=$xoopsDB->fetchRow($result1)) 
                {
                  if ($editorialuserDB==$editorialuser1) 
                  {
                     $editexist1=1;
                  }
                  else
                  {
                     $editexist1=0;
                  }
                }
            if ($editexist1==0)
               {
		    echo " | <a href='".XOOPS_URL."/modules/myReviews/editorialbook.php?lid=".$lid."'>"._MD_EDITORIAL."</a>";
                $editexist1=0;
               }
               else
               {
               echo " | <a href='".XOOPS_URL."/modules/myReviews/editorialbook.php?lid=".$lid."'>"._MD_EDITORIALEDIT."</a>";
               $editexist1=0;
               }
            echo " | <a href='".XOOPS_URL."/modules/myReviews/admin/index.php?lid=".$lid."&fct=myReviews&op=modDownload'>"._MD_EDIT."</a>";
	      }
}

echo "</td></tr>";
echo "<tr><td colspan=2>";


// Place Editorial and Review Code Here!!!
        OpenTable();

//Show Excerpt
       //$result100=$xoopsDB->query("SELECT excerpt FROM ".$xoopsDB->prefix("myreviews_excerpt")." WHERE lid = $lid");
       $result100=$xoopsDB->query("SELECT excerpt FROM ".$xoopsDB->prefix("myReviews_excerpt")." WHERE lid = $lid");
        $votes = $xoopsDB->getRowsNum($result100);
        echo "<tr><td><b>";
	printf(_MD_EXCERPT);
	echo "</b></td></tr>\n";
        if ($votes == 0){
        	echo "<tr><td align=center>" ._MD_NOEXCERPT."<br></td></tr>\n";
	                  }
        $x=0;
        $colorswitch="dddddd";
        while(list($excerpt)=$xoopsDB->fetchRow($result100)) 
                  {
                  $excerpt = $myts->makeTareaData4Show($excerpt, 1);
                  echo "<tr bgcolor=\"$colorswitch\"><td width=100%>$excerpt</td></tr>\n";
                  echo "<tr><td align=center><form action=index.php method=post>";
                  echo "&nbsp;<input type=button value='"._MD_BACKSTEP."' onclick=\"javascript:history.go(-1)\">";
                  echo "</form>";
                  echo "</td></tr>\n";
                if ($colorswitch=="dddddd"){
                	$colorswitch="ffffff";
                } else {
                        $colorswitch="dddddd";
                }
	}


/* //Show Editorials
        $result100=$xoopsDB->query("SELECT editorialid, editorialuser, editorial, editorialhostname, editorialtimestamp FROM ".$xoopsDB->prefix("myreviews_editorials")." WHERE lid = $lid AND editorialuser != 0 ORDER BY editorialtimestamp DESC");
        $votes = $xoopsDB->getRowsNum($result100);
        echo "<tr><td colspan=7><br><br><b>";
	printf(_MD_REGUSEREDITORIALS,$votes);
	echo "</b><br><br></td></tr>\n";
        echo "<tr><td width='10%'><b>" ._MD_USER."  </b></td><td width='20%'><b>" ._MD_DATE."  </b></td><td colspan=3><b>" ._MD_EDITORIALS."  </b></td></tr>\n";
        if ($votes == 0){
        	echo "<tr><td align=\"center\" colspan=\"7\">" ._MD_NOREGEDITORIALS."<br></td></tr>\n";
	}
        $x=0;
        $colorswitch="dddddd";
        while(list($editorialid, $editorialuser, $editorial, $editorialhostname, $editorialtimestamp)=$xoopsDB->fetchRow($result100)) {
        	$formatted_date = formatTimestamp($editorialtimestamp);

            	//Individual user information
                $result200=$xoopsDB->query("SELECT editorial FROM ".$xoopsDB->prefix("myreviews_editorials")." WHERE editorialuser = $editorialuser");
		      $editorialuname = XoopsUser::getUnameFromId($editorialuser);

				echo "<tr><td bgcolor=\"$colorswitch\" width='10%'>$editorialuname</td><td bgcolor=\"$colorswitch\" width='20%'>$formatted_date</td><td bgcolor=\"$colorswitch\" colspan=3>$editorial</td></tr>\n";

                $x++;
                if ($colorswitch=="dddddd"){
                	$colorswitch="ffffff";
                } else {
                        $colorswitch="dddddd";
                }
	}


//Show Reviews
        $result100=$xoopsDB->query("SELECT reviewid, reviewuser, review, reviewhostname, reviewtimestamp FROM ".$xoopsDB->prefix("myreviews_reviews")." WHERE lid = $lid AND reviewuser != 0 ORDER BY reviewtimestamp DESC");
        $votes = $xoopsDB->getRowsNum($result100);
        echo "<tr><td colspan=7><br><br><b>";
	printf(_MD_REGUSERREVIEWS,$votes);
	echo "</b><br><br></td></tr>\n";
        echo "<tr><td width='10%'><b>" ._MD_USER."  </b></td><td width='20%'><b>" ._MD_DATE."  </b></td><td colspan=3><b>" ._MD_REVIEW."  </b></td></tr>\n";
        if ($votes == 0){
        	echo "<tr><td align=\"center\" colspan=\"7\">" ._MD_NOREGREVIEWS."<br></td></tr>\n";
	}
        $x=0;
        $colorswitch="dddddd";
        while(list($reviewid, $reviewuser, $review, $reviewhostname, $reviewtimestamp)=$xoopsDB->fetchRow($result100)) {
        	$formatted_date = formatTimestamp($reviewtimestamp);

            	//Individual user information
                $result200=$xoopsDB->query("SELECT review FROM ".$xoopsDB->prefix("myreviews_reviews")." WHERE reviewuser = $reviewuser");
		      $reviewuname = XoopsUser::getUnameFromId($reviewuser);

				echo "<tr><td bgcolor=\"$colorswitch\" width='10%'>$reviewuname</td><td bgcolor=\"$colorswitch\" width='20%'>$formatted_date</td><td bgcolor=\"$colorswitch\" colspan=3>$review</td></tr>\n";

                $x++;
                if ($colorswitch=="dddddd"){
                	$colorswitch="ffffff";
                } else {
                        $colorswitch="dddddd";
                }
	} */

          CloseTable();


echo "</td></tr>";
echo "</table>";
echo "</td></tr></table>";
echo "</td></tr>";
?>