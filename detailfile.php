<?php
include("header.php");
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix("myReviews_cat"),"cid","pid");

// Used to view just a single Review file information. Called from the rating pages
if (isset($_POST['lid']))
  {
    $lid = intval($_POST['lid']);
  }
  elseif (isset($_GET['lid']))
  {
    $lid = intval($_GET['lid']);
  }
  else
  {
    redirect_header("index.php",1,_MD_ERRORREDIREDT);

    $eh = new ErrorHandler; //ErrorHandler object
  }//End if

include(XOOPS_ROOT_PATH."/header.php");

if ($myReviews_blocked)
  {
    OpenTable();
  }//End if

mainheader();

$q = "SELECT d.lid, d.cid, d.title, d.url, d.homepage, d.logourl, d.status, d.date, d.hits, d.rating, d.votes, d.comments, t.description, d.loveit, d.helpfull, d.unhelpfull, d.recommendit FROM ".$xoopsDB->prefix("myReviews_downloads")." d, ".$xoopsDB->prefix("myReviews_text")." t WHERE d.lid=$lid AND d.lid=t.lid AND status>0";
$result=$xoopsDB->query($q);

list($lid, $cid, $title, $url, $homepage, $logourl, $status, $time, $hits, $rating, $votes, $comments, $description, $loveit, $helpfull, $unhelpfull, $recommendit)=$xoopsDB->fetchRow($result);
$o = "SELECT k.review FROM ".$xoopsDB->prefix("myReviews_reviews")." k WHERE k.lid=$lid ";
$reviewresult=$xoopsDB->query($o);
list($review)=$xoopsDB->fetchRow($reviewresult);

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

include("include/dlformatread.php");
echo "</td></tr></table>\n";

if ($myReviews_blocked)
  {
    CloseTable();
  }//End if


//include XOOPS_ROOT_PATH.'/include/comment_view.php';

include("footer.php");

?>