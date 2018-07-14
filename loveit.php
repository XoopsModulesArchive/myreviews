<?php
include("header.php");
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix("myReviews_cat"),"cid","pid");

include(XOOPS_ROOT_PATH."/header.php");
//generates top 10 love at first sight charts by rating and hits for each main category

if ($myReviews_blocked)
  {
    OpenTable();
  }//End if

mainheader();
echo "<div border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"><big><b>"._MD_LOVELIST."</b></big><br><br></div>";

if(isset($HTTP_POST_VARS['rate']) or isset($HTTP_GET_VARS['rate'])){
	$sort = _MD_RATING;
	$sortDB = "rating";
}else{
	$sort = _MD_HITS;
	$sortDB = "hits";
}
$arr=array();
$result=$xoopsDB->query("SELECT cid, title FROM ".$xoopsDB->prefix("myReviews_cat")." WHERE pid=0");
while(list($cid,$ctitle)=$xoopsDB->fetchRow($result)){
	$boxtitle = "<big>";
	$boxtitle .= sprintf(_MD_BOT10,$ctitle);
	$boxtitle .= " (".$sort.")</big>";
	$thing = "<table width='100%' border='0'><tr><td width='7%' class='bg3'><b>"._MD_RANK."</b></td><td width='28%' class='bg3'><b>"._MD_TITLE."</b></td><td width='40%' class='bg3'><b>"._MD_CATEGORY."</b></td><td width='8%' class='bg3' align='center'><b>"._MD_HITS."</b></td><td width='9%' class='bg3' align='center'><b>"._MD_RATING."</b></td><td width='8%' class='bg3' align='right'><b>"._MD_VOTE."</b></td></tr>";
	$query = "SELECT lid, cid, title, hits, rating, votes FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE status>0 AND rating > 0 AND loveit=1 AND (cid=$cid";
	// get all child cat ids for a given cat id
	$arr=$mytree->getAllChildId($cid);
	$size = sizeof($arr);
	for($i=0;$i<$size;$i++){
		$query .= " OR cid=".$arr[$i]."";
	}
	$query .= ") ORDER BY ".$sortDB." ASC";
	$result2 = $xoopsDB->query($query,50,0);
	$rank = 1;
	while(list($did,$dcid,$dtitle,$hits,$rating,$votes)=$xoopsDB->fetchRow($result2)){
		$rating = number_format($rating, 2);
		if($hits){
			$hits = "<span class='fg2'>$hits</span>";
		} elseif($rating) {
			$rating = "<span class='fg2'>$rating</span>";
		}else{
		}
		$catpath = $mytree->getPathFromId($dcid, "title");
		$catpath= substr($catpath, 1);
		$catpath = str_replace("/"," <span class='fg2'>&raquo;&raquo;</span> ",$catpath);
		$thing .= "<tr><td>$rank</td>";
		$thing .= "<td><a href='singlefile.php?lid=$did'>$dtitle</a></td>";
		$thing .= "<td>$catpath</td>";
		$thing .= "<td align='center'>$hits</td>";
		$thing .= "<td align='center'>$rating</td><td align='right'>$votes</td></tr>";
		$rank++;
	}
	$thing .= "</table>";
	themecenterposts($boxtitle, $thing);
	echo "<br />";
}

if ($myReviews_blocked)
  {
    CloseTable();
  }//End if

include("footer.php");

?>