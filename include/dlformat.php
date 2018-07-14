<?php

//include("../cache/config.php");
//include("config.php");
//include("../../mainfile.php");
//include("catconfig.php");

//Get the categories for this rating
$resultCID=$xoopsDB->query("SELECT cid FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid");
list($cid) = $xoopsDB->fetchRow($resultCID);

$resultRatingCategories=$xoopsDB->query("SELECT rid, ratingcat FROM ".$xoopsDB->prefix("myReviews_ratingcat")." WHERE cid=$cid");
while(list($rid,$ratingcat) = $xoopsDB->fetchRow($resultRatingCategories))
  {
    $myReviews_catname[$rid] = $ratingcat;
    if ($myReviews_catnum < $rid)
      {
        $myReviews_catnum = $rid;
      }//End if
  }//End while

/*
if (basename($GLOBALS['PHP_SELF']) == "dlformatread.php")
  {
    exit();
  }
*/
echo "<tr>";

//ScreenShot Logic
if ( $myReviews_useshots && $myReviews_shotlocation=='outside')
  {
    $tablewidth = $myReviews_shotwidth+10;
    echo "<td width='".$tablewidth."' align='center' ";
    if ($logourl)
      {
        if (file_exists(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$logourl))
          {
            echo "><a href='".XOOPS_URL."/modules/myReviews/images/shots/".$logourl."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/shots/thumbs/".$logourl."' width='".$myReviews_shotwidth."' border='0' align='center'></a></td><td>";
          }
          else
          {
            $thumbimage = reviews_createthumb($logourl,XOOPS_ROOT_PATH, '/modules/myReviews/images/shots', '/thumbs/',$myReviews_shotwidth,$myReviews_shotwidth,70);
            if (file_exists(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$logourl))
              {
                echo "><a href='".XOOPS_URL."/modules/myReviews/images/shots/".$logourl."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/shots/thumbs/".$logourl."' width='".$myReviews_shotwidth."' border='0' align='center'></a></td><td>";
              }
              else
              {
                echo "><a href='".XOOPS_URL."/modules/myReviews/images/shots/".$logourl."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/shots/".$logourl."' width='".$myReviews_shotwidth."' border='0' align='center'></a></td><td>";
              }//End if
          }//End if
      }
      else
      {
        echo "><a><img src='".XOOPS_URL."/modules/myReviews/images/shots/nopic.gif' width='".$myReviews_shotwidth."' border='0' align='center'></a></td><td>";
      }//End If
  }
  else
  {
    echo "<td>";
  }//End If

//Do Category Header
$path = $mytree->getPathFromId($cid, "title");
$path = substr($path, 1);
$path = str_replace("/"," <img src='".XOOPS_URL."/modules/myReviews/images/arrow.gif' board='0' alt=''> ",$path);
echo "<table class='outer' width='100%' border='0' cellspacing='1' cellpadding='0' ><tr><td>";
echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' ><tr><td>";
echo "<table width='100%' border='0' cellspacing='0' cellpadding='1' ><tr><td class='head' colspan='2' align='left'>";

echo "<b>"._MD_CATEGORYC."</b>".$path."<br>";
//echo "<img src='images/redpixel.gif'width=100% height=1 Vspace=0 Hspace=0>\n";
echo "</td>
      </tr>";
echo "<tr class='odd'>";

//Do Review Title Header
echo "<td width=75% align='left'>";
//Show hamepage pic
if ($homepage && $homepage!='http://')
  {
    echo "<a href='".$homepage."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/home.gif' border='0'></a>&nbsp;";
  }
  else
  {
    echo "";
  }//End if
//Show shopping cart pic
if ($url && $url!='http://')
  {
    echo "<a href='".$url."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/cart.gif' border='0'></a>&nbsp;";
  }
  else
  {
    echo "";
  }//End if
//Show love at first sight pic
if ($loveit && $loveit==1)
  {
    echo "<a href='".XOOPS_URL."/modules/myReviews/loveit.php?rate=1'><img src='".XOOPS_URL."/modules/myReviews/images/inlove.gif' border='0'></a>&nbsp;";
  }
  else
  {
    echo "";
  }//End if
//Recommendation pic
if ($recommendit && $recommendit==1)
  {
    echo "<a href='".XOOPS_URL."/modules/myReviews/recommendit.php?rate=1'><img src='".XOOPS_URL."/modules/myReviews/images/recommend.gif' border='0'></a>&nbsp;";
  }
  else
  {
    echo "";
  }//End if

echo "<font size=2>"._MD_DLTITLE."";

//Clicking on title leads to review
echo "&nbsp;<b><a href='".XOOPS_URL."/modules/myReviews/detailfile.php?lid=".$lid."'>".$dtitle."</a></b></font>";

//Do Graphics for new updated popular etc
newdownloadgraphic($time, $status);
popgraphic($hits);
echo "</td><td align='right'>";

//Do ratings and hits display
if ( $rating!="0" || $rating!="0.0" )
  {
    echo ""._MD_RATINGC."<b>$rating</b> ";
  }//End If

echo "&nbsp;";
echo ""._MD_DLTIMES."<b>$hits</b></td></tr>";

//Description
echo "<tr><td class='even' colspan='7' align='left'>";

//ScreenShot Logic
if ( $myReviews_useshots && $myReviews_shotlocation=='inside')
  {
    if ($logourl)
      {
        if (file_exists(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$logourl))
          {
            echo "<a href='".XOOPS_URL."/modules/myReviews/images/shots/".$logourl."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/shots/thumbs/".$logourl."' width='".$myReviews_shotwidth."' border='0' align='right'></a>";
          }
          else
          {
            $thumbimage = reviews_createthumb($logourl,XOOPS_ROOT_PATH, '/modules/myReviews/images/shots', '/thumbs/',$myReviews_shotwidth,$myReviews_shotwidth,70);
            if (file_exists(XOOPS_ROOT_PATH."/modules/myReviews/images/shots/thumbs/".$logourl))
              {
                echo "<a href='".XOOPS_URL."/modules/myReviews/images/shots/".$logourl."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/shots/thumbs/".$logourl."' width='".$myReviews_shotwidth."' border='0' align='right'></a>";
              }
              else
              {
                echo "<a href='".XOOPS_URL."/modules/myReviews/images/shots/".$logourl."' target='_blank'><img src='".XOOPS_URL."/modules/myReviews/images/shots/".$logourl."' width='".$myReviews_shotwidth."' border='0' align='right'></a>";
              }//End if
          }//End if
      }
      else
      {
        echo "<a><img src='".XOOPS_URL."/modules/myReviews/images/shots/nopic.gif' width='".$myReviews_shotwidth."' border='0' align='right'></a>";
      }//End If
  }//End If

echo "<img src='".XOOPS_URL."/modules/myReviews/images/description.gif' board='0' width='76' height='12' align='buttom' alt='"._MD_DESCRIPTION."' />&nbsp;&nbsp; $description";
echo "</td></tr>";

//Editorial
if ($editorial)
  {
    echo "<tr><td class='even' colspan='7' align='left'>";
    echo "<img src='".XOOPS_URL."/modules/myReviews/images/editorial.gif' board='0' width='76' height='12' align='buttom' alt='"._MD_DESCRIPTION."' />&nbsp;&nbsp; $editorial";
    echo "</td></tr>";
  }//End if

echo "</td></tr>";
echo "</table>";
//echo "<table width=100%>\n";

//Start with the Review Display

//Do the overall rating Display first

//$formatted_date = formatTimestamp($reviewtimestamp);
$review = $myts->makeTareaData4Show($review,1);
//$reviewuname = XoopsUser::getUnameFromId($reviewuser);
$resulttot=$xoopsDB->query("SELECT rating FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE lid = $lid");

$totalraw=0;
$totalint=0;
$totaltotal=0;
$totbar=0;
$totalstuff=0;
$q=0;

while(list($total)=$xoopsDB->fetchRow($resulttot))
  {
    $totalstuff+=$total;
    $q++;
  }//End while

if ($q==0)
  {
    $totalraw=0;
    $totalint=1;
  }
  else
  {
    $totalraw=$totalstuff/$q;
    $totalint=intval($totalstuff/$q)+.5;
  }//End if

if ($totalraw>$totalint)
  {
    $totaltotal=intval($totalraw)+1;
  }
  else
  {
    $totaltotal=intval($totalraw);
  }//End if

$totbar=(($totaltotal/$myReviews_totrate)*$myReviews_categorybarwidth-15);
$emptybar=$myReviews_categorybarwidth-15-$totbar;
$fullmarks=0;
if ($totaltotal==$myReviews_totrate)
 {
   $fullmarks=1;
 }//End if

//Check if there has been any reviews to display
if ($q>0)
  {
    echo "<table border=0 width=100% class='odd' cellspacing='0' cellpadding='0'>
            <tr>
              <td width=$myReviews_categorywidth valign=top>
                <table width=$myReviews_categorywidth>
                  <td align=right width=$myReviews_categorylabelwidth><b>"._MD_AVERAGE.":</b>
                  </td>
                  <td allign=left><b>(1)</b>
                  </td>
                  <td width=$myReviews_categorybarwidth>
                  </td>
                  <td align=right><b>($myReviews_totrate)</b>
                  </td>
            </tr>";
    echo "  <tr>
              <td align=right width=$myReviews_categorylabelwidth><b>"._MD_TOTNAME_TITLE.":</b>
              </td>";
    //Logic to display the different review bar types
    if ($myReviews_reviewbartype==1)//Bars
      {
        echo "    <td colspan=2 align=left width=$myReviews_categorybarwidth><img src='images/leftbar1.gif' alt='"._MD_FEATURES_TITLE.": $totaltotal'><img src='images/mainbar1.gif' height=15 width=$totbar alt='"._MD_FEATURES_TITLE.": $totaltotal'><img src='images/rightbar1.gif' alt='"._MD_FEATURES_TITLE.": $rating'>";
      }//End if
    if ($myReviews_reviewbartype==2)//Stars
      {
        $starbartot=round((($totaltotal/($myReviews_totrate/10))),0);
        switch ($starbartot)
          {
            default:
              $starbar='stars_0.gif';
              break;
            case 1:
              $starbar='stars_0_5.gif';
              break;
            case 2:
              $starbar='stars_1.gif';
              break;
            case 3:
              $starbar='stars_1_5.gif';
              break;
            case 4:
              $starbar='stars_2.gif';
              break;
            case 5:
              $starbar='stars_2_5.gif';
              break;
            case 6:
              $starbar='stars_3.gif';
              break;
            case 7:
              $starbar='stars_3_5.gif';
              break;
            case 8:
              $starbar='stars_4.gif';
              break;
            case 9:
              $starbar='stars_4_5.gif';
              break;
            case 10:
              $starbar='stars_5.gif';
              break;
          }//End switch

        echo "    <td colspan=2 align=left width=$myReviews_categorybarwidth><img src='images/$starbar' alt='"._MD_FEATURES_TITLE.": $totaltotal'>";
      }//End if
    if ($myReviews_reviewbartype==3)//Full Bars
      {
        if ($fullmarks)
          {
            $totbar+=6;
            echo "    <td colspan=2 align=left width=$myReviews_categorybarwidth><img src='images/full_l.gif' alt='"._MD_FEATURES_TITLE.": $totaltotal'><img src='images/full_m.gif' height=10 width=$totbar alt='"._MD_FEATURES_TITLE.": $totaltotal'><img src='images/full_r.gif' alt='"._MD_FEATURES_TITLE.": $rating'>";
          }
          else
          {
            echo "    <td colspan=2 align=left width=$myReviews_categorybarwidth><img src='images/full_l.gif' alt='"._MD_FEATURES_TITLE.": $totaltotal'><img src='images/full_m.gif' height=10 width=$totbar alt='"._MD_FEATURES_TITLE.": $totaltotal'><img src='images/full_r.gif' alt='"._MD_FEATURES_TITLE.": $rating'><img src='images/empty_l.gif' alt='"._MD_FEATURES_TITLE.": $totaltotal'><img src='images/empty_m.gif' height=10 width=$emptybar alt='"._MD_FEATURES_TITLE.": $totaltotal'><img src='images/empty_r.gif' alt='"._MD_FEATURES_TITLE.": $rating'>";
          }
      }//End if

    echo "    <td align=right><b>$rating</b>
              </td>
              </td>";

    for ($e=1;$e<$myReviews_catnum+1;$e++)
      {
        $resultcat[$e]=$xoopsDB->query("SELECT rating FROM ".$xoopsDB->prefix("myReviews_votecat")." WHERE lid=$lid AND ratingcat=$e");
        $x=0;
        $tottotcatrate[$e]=0;
        $catstarbartot[$e]=0;

        while(list($totcatrate[$e])=$xoopsDB->fetchRow($resultcat[$e]))
          {
            $x++;
            $tottotcatrate[$e]+=$totcatrate[$e];
          }//End while

        if ($x == 0)
          {
            $totalcatrateraw[$e]=0;
            $totalcatrateint[$e]=1;
          }
          else
          {
            $totalcatrateraw[$e]=$tottotcatrate[$e]/$x;
            $totalcatrateint[$e]=intval($tottotcatrate[$e]/$x)+.5;
          }//End if

        if ($totalcatrateraw[$e]>$totalcatrateint[$e])
          {
            $catrate[$e]=intval($totalcatrateraw[$e])+1;
          }
          else
          {
            $catrate[$e]=intval($totalcatrateraw[$e]);
          }//End if

        $catbar[$e]=($catrate[$e]/$myReviews_maxrate)*$myReviews_categorybarwidth-15;
        $catemptybar[$e]=$myReviews_categorybarwidth-15-$catbar[$e];
        $catstarbartot[$e]=round((($catrate[$e]/($myReviews_maxrate/10))),0);
        $catfullmarks[$e]=0;
        if ($catrate[$e]==$myReviews_maxrate)
         {
           $catfullmarks[$e]=1;
         }//End if

        $tottotcatrate[$e]=0;
      }//End for

    //Now Display the Category Reviews
    for ($e=1;$e<$myReviews_catnum+1;$e++)
      {
        if ($catrate[$e] != 0)
          {
            echo "<tr>
                    <td align=right width=$myReviews_categorylabelwidth>$myReviews_catname[$e]:
                    </td>";

            //Logic to display the different review bar types
            if ($myReviews_reviewbartype==1)//Bars
              {
                echo "    <td colspan=2 align='left'><img src='images/leftbar.gif' alt='$myReviews_catname[$e]: $catrate[$e]'><img src='images/mainbar.gif' height=16 width=$catbar[$e] alt='$myReviews_catname[$e]: $catrate[$e]'><img src='images/rightbar.gif' alt='$myReviews_catname[$e]: $catrate[$e]'>";
              }//End if
            if ($myReviews_reviewbartype==2)//Stars
              {
                switch ($catstarbartot[$e])
                  {
                    default:
                      $catstarbar='stars_0.gif';
                      break;
                    case 1:
                      $catstarbar='stars_0_5.gif';
                      break;
                    case 2:
                      $catstarbar='stars_1.gif';
                      break;
                    case 3:
                      $catstarbar='stars_1_5.gif';
                      break;
                    case 4:
                      $catstarbar='stars_2.gif';
                      break;
                    case 5:
                      $catstarbar='stars_2_5.gif';
                      break;
                    case 6:
                      $catstarbar='stars_3.gif';
                      break;
                    case 7:
                      $catstarbar='stars_3_5.gif';
                      break;
                    case 8:
                      $catstarbar='stars_4.gif';
                      break;
                    case 9:
                      $catstarbar='stars_4_5.gif';
                      break;
                    case 10:
                      $catstarbar='stars_5.gif';
                      break;
                  }//End switch

                echo "    <td colspan=2 align='left'><img src='images/$catstarbar' alt='$myReviews_catname[$e]: $catrate[$e]'>";
              }//End if
            if ($myReviews_reviewbartype==3)//Full Bars
              {
                if ($catfullmarks[$e])
                  {
                    $catbar[$e]+=6;
                    echo "    <td colspan=2 align='left'><img src='images/full_l.gif' alt='$myReviews_catname[$e]: $catrate[$e]'><img src='images/full_m.gif' height=10 width=$catbar[$e] alt='$myReviews_catname[$e]: $catrate[$e]'><img src='images/full_r.gif' alt='$myReviews_catname[$e]: $catrate[$e]'>";
                  }
                  else
                  {
                    echo "    <td colspan=2 align='left'><img src='images/full_l.gif' alt='$myReviews_catname[$e]: $catrate[$e]'><img src='images/full_m.gif' height=10 width=$catbar[$e] alt='$myReviews_catname[$e]: $catrate[$e]'><img src='images/full_r.gif' alt='$myReviews_catname[$e]: $catrate[$e]'><img src='images/empty_l.gif' alt='$myReviews_catname[$e]: $catrate[$e]'><img src='images/empty_m.gif' height=10 width=$catemptybar[$e] alt='$myReviews_catname[$e]: $catrate[$e]'><img src='images/empty_r.gif' alt='$myReviews_catname[$e]: $catrate[$e]'>";
                  }//End if
              }//End if

            echo "  </td>
                    <td align=right>$catrate[$e]
                    </td>
                  </tr>";
            $catrate[$e]=0;
            $catbar[$e]=0;
          }//End if
      }//End for

    echo "</table>";
    echo "</td>
          <td valign=top>
          <table border=0 width=100%>
            <tr>
              <td align=right width=50%>" ._MD_DATE." <b>$datetime </b>
              </td>
            </tr>";
    //echo "<tr><td colspan=2><b>"._MD_DESCRIPTION."</b>&nbsp;:&nbsp; $description</td></tr>";
    echo "</table>";
    echo "</td></tr>";
    echo "</table>";
  }
  else
//There was no ratings done so far
  {
    echo "<table border=0 width=100% cellspacing='0' cellpadding='0'>
            <tr>
              <td width=$myReviews_categorywidth valign=top>
                <table width=$myReviews_categorywidth>
                  <td align=right><font size=2><b></b></font>
                  </td>
                  <td width=$myReviews_categorylabelwidth><font size=2><b></b></font>
                  </td>
                  <td align=right><font size=2><b></b></font>
                  </td>
            </tr>
            <tr>
              <td align=right width=$myReviews_categorywidth><font size=2><b></b></font>
              </td>
              <td colspan=2>
              </td>";

    echo "</table>";

    echo "</td>
          <td valign=top>
            <table border=0 width=100%>
              <tr>
                <td align=right width=50%>" ._MD_DATE." <b>$datetime </b>
                </td>
              </tr>";
    //echo "<tr><td colspan=2><b>"._MD_DESCRIPTION."</b>&nbsp;:&nbsp; $description</td></tr>";
    echo "</table>";
    echo "</td></tr>";
    echo "</table>";

  }//End if

echo "</table>";
$q=0;

echo "<tr><td class='foot' colspan='2' align='center'>";

//echo "<a href='javascript:history.go(-1)'>"._MD_BACKSTEP."</a>";
//echo " | <a href='".XOOPS_URL."/modules/myReviews/excerptfile.php?lid=".$lid."'>"._MD_READEXCERPT."</a>";
//echo " | <a href='".XOOPS_URL."/modules/myReviews/ratefile.php?lid=".$lid."'>"._MD_RATETHISFILE."</a>";

$result300=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid=$lid");
list($reviewuserDB) = $xoopsDB->fetchRow($result300);

$result350=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("myReviews_editorials")." WHERE lid=$lid");
list($editorialuserDB) = $xoopsDB->fetchRow($result350);

if ($reviewuserDB || $editorialuserDB)
  {
    echo "<b><a href='".XOOPS_URL."/modules/myReviews/detailfile.php?lid=".$lid."'>"._MD_DETAILS."</a> | </b>";
  }//End if

// echo "<a href='".XOOPS_URL."/modules/myReviews/detailfile.php?lid=".$lid."'>"._MD_DETAILS."</a> | ";
// echo "<a href='".XOOPS_URL."/modules/myReviews/ratefile.php?lid=".$lid."'>"._MD_RATETHISFILE."</a>";

echo "<b><a target='_top' href='mailto:?subject=".rawurlencode(sprintf(_MD_INTFILEAT,$xoopsConfig['sitename']))."&body=".rawurlencode(sprintf(_MD_INTFILEFOUND,$xoopsConfig['sitename']).":  ".XOOPS_URL."/modules/myReviews/singlefile.php?lid=".$lid)."'>"._MD_TELLAFRIEND."</a> | </b>";

global $xoopsUser;

if ($xoopsUser)
  {
    $submitter = $xoopsUser->uid();
    $result240=$xoopsDB->query("SELECT submitter FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid AND submitter=$submitter");
    list($submitterDB) = $xoopsDB->fetchRow($result240);
    if ($submitterDB)
      {
        echo "<a href='".XOOPS_URL."/modules/myReviews/modfile.php?lid=".$lid."'><b>"._MD_REVIEWEDIT."</a> | </b>";
      }
      else
      {
      }//End if
  }//End if


if ( $xoopsUser )
  {
    //$submitter = $xoopsUser->uid();
	if ( $xoopsUser->isAdmin($xoopsModule->mid()) )
      {
        $result3000=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("myReviews_editorials")." WHERE lid=$lid AND editorialuser=$submitter");
        list($editorialuserDB) = $xoopsDB->fetchRow($result3000);
        if ($editorialuserDB)
          {
             echo "<b><a href='".XOOPS_URL."/modules/myReviews/editorialbook.php?lid=".$lid."'>"._MD_EDITORIALEDIT."</a> | </b>";
          }
          else
          {
             echo "<b><a href='".XOOPS_URL."/modules/myReviews/editorialbook.php?lid=".$lid."'>"._MD_EDITORIAL."</a> | </b>";
          }//End if
        echo "<b><a href='".XOOPS_URL."/modules/myReviews/admin/index.php?lid=".$lid."&fct=myReviews&op=modDownload'>"._MD_EDIT."</a> | </b>";
		//echo " | <a href='".XOOPS_URL."/modules/myReviews/reviewbook.php?lid=".$lid."'>"._MD_VSCOMMENTS."</a>";
	  }//End if
  }//End if

if ( $xoopsUser )
  {
    //$submitter = $xoopsUser->uid();
    if ( $xoopsUser->isAdmin($xoopsModule->mid()) )
      {
        $result3000=$xoopsDB->query("SELECT loveit, recommendit FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid");
        list($loveitDB, $recommenditDB) = $xoopsDB->fetchRow($result3000);
        if ($loveitDB==0)
          {
             echo "<b><a href='".XOOPS_URL."/modules/myReviews/singlefile.php?loveit=1&lid=".$lid."'>"._MD_LOVEIT."</a> | </b>";
          }
          else
          {
             echo "<b><a href='".XOOPS_URL."/modules/myReviews/singlefile.php?loveit=0&lid=".$lid."'>"._MD_UNLOVEIT."</a> | </b>";
          }//End if
        if ($recommenditDB==0)
          {
             echo "<b><a href='".XOOPS_URL."/modules/myReviews/singlefile.php?recommendit=1&lid=".$lid."'>"._MD_RECOMMENDIT."</a> | </b>";
          }
          else
          {
             echo "<b><a href='".XOOPS_URL."/modules/myReviews/singlefile.php?recommendit=0&lid=".$lid."'>"._MD_UNRECOMMENDIT."</a> | </b>";
          }//End if
      }//End if
  }//End if

if ( $xoopsUser )
  {
    $submitter = $xoopsUser->uid();
    $result3000=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid=$lid AND reviewuser=$submitter");
    list($editreviewDB) = $xoopsDB->fetchRow($result3000);
    //echo " | <a href='".XOOPS_URL."/modules/myReviews/admin/index.php?lid=".$lid."&fct=myReviews&op=modDownload'>"._MD_EDIT."</a>";
    if ($editreviewDB)
      {
        echo "<b><a href='".XOOPS_URL."/modules/myReviews/reviewbook.php?lid=".$lid."'>"._MD_REVIEWEDITIT."</a> | <b/>";
      }
      else
      {
        echo "<b><a href='".XOOPS_URL."/modules/myReviews/reviewbook.php?lid=".$lid."'>"._MD_VSCOMMENTS."</a> | <b/>";
      }//End if
  }//End if

echo "<b><a target='_top' href='mailto:".($xoopsConfig['adminmail'])."?subject=".rawurlencode(sprintf(_MD_MAILBROKEN1,$dtitle))."&body=".rawurlencode(sprintf(_MD_BROKENLINK).":  ".XOOPS_URL."/modules/myReviews/singlefile.php?lid=".$lid)."'>"._MD_REPORTBROKEN."</a></b>";

echo "</td></tr></table>";

//voting & comments stats
if ($comments != 0)
  {
    if ($comments == 1)
      {
        $poststring = _MD_ONEPOST;
      }
      else
      {
        $poststring = sprintf(_MD_NUMPOSTS,$comments);
      }
    echo "<b>"._MD_COMMENTSC."</b>$poststring";
  }

echo "<br>"

?>