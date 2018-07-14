<?php
include("header.php");
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");

$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix("myReviews_cat"),"cid","pid");

$cid = intval($HTTP_GET_VARS['cid']);
include(XOOPS_ROOT_PATH."/header.php");

if ($myReviews_blocked)
  {
    OpenTable();
  }//End if

mainheader();
if (isset($HTTP_GET_VARS['show']))
  {
    $show = intval($HTTP_GET_VARS['show']);
  }
  else
  {
	$show = $myReviews_perpage;
  }//End If
if (!isset($HTTP_GET_VARS['min']))
  {
	$min = 0;
  }
  else
  {
	$min = intval($HTTP_GET_VARS['min']);
  }//End If
if (!isset($max))
  {
	$max = $min + $show;
  }//End If
if(isset($HTTP_GET_VARS['orderby']))
  {
	$orderby = convertorderbyin($HTTP_GET_VARS['orderby']);
  }
  else
  {
	$orderby = "title ASC";
  }//End If

echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td align='center'>\n";

$letters = letters();
echo "Browse reviews by alphabetical listing<br />";
echo "<div align = 'center' class = 'itemPermaLink'>$letters</div><br />";

echo "<table width='100%' cellspacing='1' cellpadding='2' border='0' class='bg3'><tr><td>\n";
$pathstring = "<a href='index.php'>"._MD_MAIN."</a>&nbsp;:&nbsp;";
$nicepath = $mytree->getNicePathFromId($cid, "title", "viewcat.php?op=");
$pathstring .= $nicepath;
echo "<b>".$pathstring."</b>";
echo "</td></tr></table>";

// get child category objects
$arr=array();
$arr=$mytree->getFirstChild($cid, "title");
if ( count($arr) > 0 )
  {
    echo "</td></tr>";
	echo "<tr><td align='left'><h4>"._MD_CATEGORIES."</h4></td></tr>\n";
	echo "<tr><td align='center'>";
	$scount = 0;
    echo "<table width='90%'><tr>";
    foreach($arr as $ele)
      {
		$title = $myts->makeTboxData4Show($ele['title']);
		$totaldownload = getTotalItems($ele['cid'], 1);
        echo "<td align='left'><b><a href=viewcat.php?cid=".$ele['cid'].">".$title."</a></b>&nbsp;(".$totaldownload.")&nbsp;&nbsp;</td>";
        $scount++;
        if ( $scount == 4 )
          {
            echo "</tr><tr>";
            $scount = 0;
          }//End If
      }//End Foreach
    echo "</tr></table><br />\n";
	echo "<hr />";
  }//End If

$fullcountresult=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE cid=$cid AND status>0");
list($numrows) = $xoopsDB->fetchRow($fullcountresult);

if($numrows>0)
  {
	$q = "SELECT d.lid, d.title, d.url, d.homepage, d.logourl, d.status, d.date, d.hits, d.rating, d.votes, d.comments, d.submitter, t.description, d.loveit, d.helpfull, d.unhelpfull, d.recommendit FROM ".$xoopsDB->prefix("myReviews_downloads")." d, ".$xoopsDB->prefix("myReviews_text")." t WHERE cid=".$cid." AND d.status>0 AND d.lid=t.lid ORDER BY ".$orderby."";
	$result = $xoopsDB->query($q,$show,$min);

	//if 2 or more items in result, show the sort menu
	if($numrows>1)
      {
        $orderbyTrans = convertorderbytrans($orderby);
        echo "<br /><small><center>"._MD_SORTBY."&nbsp;&nbsp;
        "._MD_TITLE." (<a href='viewcat.php?cid=$cid&orderby=titleA'><img src='images/up.gif' border='0' align='middle' alt='' /></a><a href='viewcat.php?cid=$cid&orderby=titleD'><img src='images/down.gif' border='0' align='middle' alt='' /></a>)
        "._MD_DATE." (<a href='viewcat.php?cid=$cid&orderby=dateA'><img src='images/up.gif' border='0' align='middle' alt='' /></a><a href='viewcat.php?cid=$cid&orderby=dateD'><img src='images/down.gif' border='0' align='middle' alt='' /></a>)
        "._MD_RATING." (<a href='viewcat.php?cid=$cid&orderby=ratingA'><img src='images/up.gif' border='0' align='middle' alt='' /></a><a href=viewcat.php?cid=$cid&orderby=ratingD><img src='images/down.gif' border='0' align='middle' alt='' /></a>)
        "._MD_POPULARITY." (<a href='viewcat.php?cid=$cid&orderby=hitsA'><img src='images/up.gif' border='0' align='middle' alt='' /></a><a href='viewcat.php?cid=$cid&orderby=hitsD'><img src='images/down.gif' border='0' align='middle' alt='' /></a>)
        ";
        echo "<br /><b><small>";
		printf(_MD_CURSORTBY,$orderbyTrans);
		echo "</small></b><br /><br /></center>";
	  }//End If

    echo "<table width='100%' cellspacing=0 cellpadding=10 border=0>";
  	$x=0;
   	while(list($lid, $dtitle, $url, $homepage, $logourl, $status, $time, $hits, $rating, $votes, $comments, $submitter, $description, $loveit, $helpfull, $unhelpfull, $recommendit)=$xoopsDB->fetchRow($result))
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
	echo "</table>";
   	$orderby = convertorderbyout($orderby);
   	//Calculates how many pages exist.  Which page one should be on, etc...
   	$downloadpages = ceil($numrows / $show);
    //Page Numbering
   	if ($downloadpages!=1 && $downloadpages!=0)
      {
        echo "<br /><br />";
        $prev = $min - $show;
        if ($prev>=0)
          {
            echo "&nbsp;<a href='viewcat.php?cid=$cid&min=$prev&orderby=$orderby&show=$show'>";
            echo "<b>&lt; "._MD_PREVIOUS." </b></a>&nbsp;";
          }//End If
        $counter = 1;
        $currentpage = ($max / $show);
        while ( $counter<=$downloadpages )
          {
            $mintemp = ($show * $counter) - $show;
            if ($counter == $currentpage)
              {
			    echo "<b>$counter</b>&nbsp;";
			  }
              else
              {
			    echo "<a href='viewcat.php?cid=$cid&min=$mintemp&orderby=$orderby&show=$show'>$counter</a>&nbsp;";
			  }//End If
            $counter++;
          }//End While
        if ( $numrows>$max )
          {
            echo "&nbsp;<a href='viewcat.php?cid=$cid&min=$max&orderby=$orderby&show=$show'>";
            echo "<b> "._MD_NEXT." &gt;</b></a>";
          }//End If
      }//End If
  }//End If

echo "<br /><br />";

echo "<div border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"><b>"._MD_LEGEND."</b><br><br>";
echo "<a href='".XOOPS_URL."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/home.gif' border='0'> "._MD_HOMEPAGE." </a>&nbsp;";
echo "<a href='".XOOPS_URL."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/cart.gif' border='0'> "._MD_CART." </a>&nbsp;";
echo "<a href='".XOOPS_URL."/modules/myReviews/loveit.php?rate=1'><img src='".XOOPS_URL."/modules/myReviews/images/inlove.gif' border='0'> "._MD_LOVEIT." </a>&nbsp;";
echo "<a href='".XOOPS_URL."/modules/myReviews/recommendit.php?rate=1'><img src='".XOOPS_URL."/modules/myReviews/images/recommend.gif' border='0'> "._MD_RECOMMENDIT." </a>&nbsp;";
echo "</div>";
  
echo "</td></tr></table>\n";

if ($myReviews_blocked)
  {
    CloseTable();
  }//End if

include("footer.php");

?>