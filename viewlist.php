<?php
include("header.php");
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");

$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix("myReviews_cat"),"cid","pid");

$list = $HTTP_GET_VARS['list'];
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
echo "</td></tr></table>";

$fullcountresult=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE title LIKE '$list%' AND status>0");
list($numrows) = $xoopsDB->fetchRow($fullcountresult);

if($numrows>0)
  {
	$q = "SELECT d.lid, d.title, d.url, d.homepage, d.logourl, d.status, d.date, d.hits, d.rating, d.votes, d.comments, d.submitter, t.description, d.loveit, d.helpfull, d.unhelpfull, d.recommendit FROM ".$xoopsDB->prefix("myReviews_downloads")." d, ".$xoopsDB->prefix("myReviews_text")." t WHERE d.title LIKE '$list%' AND d.status>0 AND d.lid=t.lid ORDER BY ".$orderby."";
	$result = $xoopsDB->query($q,$show,$min);

	//if 2 or more items in result, show the sort menu
	if($numrows>1)
      {
        $orderbyTrans = convertorderbytrans($orderby);
        echo "<br /><small><center>"._MD_SORTBY."&nbsp;&nbsp;
        "._MD_TITLE." (<a href='viewlist.php?list=$list&orderby=titleA'><img src='images/up.gif' border='0' align='middle' alt='' /></a><a href='viewlist.php?list=$list&orderby=titleD'><img src='images/down.gif' border='0' align='middle' alt='' /></a>)
        "._MD_DATE." (<a href='viewlist.php?list=$list&orderby=dateA'><img src='images/up.gif' border='0' align='middle' alt='' /></a><a href='viewlist.php?list=$list&orderby=dateD'><img src='images/down.gif' border='0' align='middle' alt='' /></a>)
        "._MD_RATING." (<a href='viewlist.php?list=$list&orderby=ratingA'><img src='images/up.gif' border='0' align='middle' alt='' /></a><a href=viewlist.php?list=$list&orderby=ratingD><img src='images/down.gif' border='0' align='middle' alt='' /></a>)
        "._MD_POPULARITY." (<a href='viewlist.php?list=$list&orderby=hitsA'><img src='images/up.gif' border='0' align='middle' alt='' /></a><a href='viewlist.php?list=$list&orderby=hitsD'><img src='images/down.gif' border='0' align='middle' alt='' /></a>)
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
            echo "&nbsp;<a href='viewlist.php?list=$list&min=$prev&orderby=$orderby&show=$show'>";
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
			    echo "<a href='viewlist.php?list=$list&min=$mintemp&orderby=$orderby&show=$show'>$counter</a>&nbsp;";
			  }//End If
            $counter++;
          }//End While
        if ( $numrows>$max )
          {
            echo "&nbsp;<a href='viewlist.php?list=$list&min=$max&orderby=$orderby&show=$show'>";
            echo "<b> "._MD_NEXT." &gt;</b></a>";
          }//End If
      }//End If
  }//End If

echo "</td></tr></table>\n";

if ($myReviews_blocked)
  {
    CloseTable();
  }//End if

include("footer.php");

?>