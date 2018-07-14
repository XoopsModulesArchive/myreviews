<?php
include '../../mainfile.php';
include(XOOPS_ROOT_PATH.'/modules/myReviews/header.php');
include_once(XOOPS_ROOT_PATH.'/class/module.errorhandler.php');
include_once(XOOPS_ROOT_PATH.'/class/xoopstree.php');
include_once (XOOPS_ROOT_PATH.'/modules/myReviews/include/functions.php');
include (XOOPS_ROOT_PATH.'/modules/myReviews/cache/config.php');

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

if(!$xoopsUser)
  {
    redirect_header("index.php",4,_MD_CANTREVIEW);
	exit();
  }//End If


//Edit the Review
if(isset($HTTP_POST_VARS['submit1']))
  {
    $eh = new ErrorHandler; //ErrorHandler object
	if(!$xoopsUser)
      {
	    $reviewuser = 0;
        $ratinguser = 0;
	  }
      else
      {
		$reviewuser = $xoopsUser->uid();
        $ratinguser = $xoopsUser->uid();
	  }

    //Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip = getenv("REMOTE_ADDR");
    $lid = intval($HTTP_POST_VARS['lid']);
    $review = $myts->makeTareaData4Save($HTTP_POST_VARS["review"]);
    $totradio = intval($HTTP_POST_VARS["totradio"]);

    // Check if review is Null
    if ($review=="")
      {
        redirect_header("reviewbook.php?lid=".$lid."",4,_MD_NOREVIEW);
		exit();
      }//End If

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

    //All is well.  Add to Line Item Rate to review DB.
	$datetime = time();
    $xoopsDB->query("UPDATE ".$xoopsDB->prefix("myReviews_reviews")." SET review='$review', reviewhostname='$ip', reviewtimestamp='$datetime' WHERE lid=$lid AND reviewuser='$reviewuser' ") or $eh->show("0013");

    //All is well. Add totalvote data to the votedata table.
    $xoopsDB->query("UPDATE ".$xoopsDB->prefix("myReviews_votedata")." SET rating=$totradio, ratinghostname='$ip', ratingtimestamp='$datetime' WHERE lid=$lid AND ratinguser='$ratinguser' ") or $eh->show("0013");

    //All is well. Add category votes to votecat DB.
    for ($x=1;$x<$myReviews_catnum+1;$x++)
      {
        if (isset($myReviews_catname[$x]))
          {
            $catradio[$x] = $HTTP_POST_VARS['catradio'.$x.''];
            $counter=1;
            $counterResult=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("myReviews_votecat")." WHERE lid=$lid AND ratingcat=$x AND ratinguser='$ratinguser'");
            list($counter) = $xoopsDB->fetchRow($counterResult);
            if ($counter==0)
              {
                $newid = $xoopsDB->genId($xoopsDB->prefix("myReviews_votecat")."_ratingid_seq");
                $datetime = time();
                $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_votecat")." (ratingid, lid, ratinguser, rating, ratingcat, ratinghostname, ratingtimestamp) VALUES ($newid, $lid, $reviewuser, $catradio[$x], $x, '$ip', $datetime)") or $eh("0013");
              }
              else
              {
                $xoopsDB->query("UPDATE ".$xoopsDB->prefix("myReviews_votecat")." SET rating=$catradio[$x], ratinghostname='$ip', ratingtimestamp='$datetime' WHERE lid=$lid AND ratingcat=$x AND ratinguser='$ratinguser' ") or $eh("0013");
              }//End if
          }//End if
      }//End For

    //All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
    updaterating($lid);

	$ratemessage = _MD_REVIEWAPPRE."<br>".sprintf(_MD_THANKYOU,$xoopsConfig['sitename']);
	redirect_header("index.php",4,$ratemessage);
	exit();

  }//End If

//Delete the Review
if(isset($HTTP_POST_VARS['delete']))
  {
    $eh = new ErrorHandler; //ErrorHandler object
	if(!$xoopsUser)
      {
	    $reviewuser = 0;
        $ratinguser = 0;
	  }
      else
      {
		$reviewuser = $xoopsUser->uid();
        $ratinguser = $xoopsUser->uid();
	  }//End If

   	$ip = getenv("REMOTE_ADDR");
	$lid = intval($HTTP_POST_VARS['lid']);
    $review = $myts->makeTareaData4Save($HTTP_POST_VARS["review"]);

    //All is well.  Delete Line Item Rate to review DB.
    global $xoopsDB, $HTTP_GET_VARS, $eh;
    $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid=$lid and reviewuser=$reviewuser";
    $xoopsDB->query($query) or $eh->show("0013");

    $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE lid=$lid and ratinguser=$ratinguser";
    $xoopsDB->query($query) or $eh->show("0013");

    $query = "DELETE FROM ".$xoopsDB->prefix("myReviews_votecat")." WHERE lid=$lid and ratinguser=$ratinguser";
    $xoopsDB->query($query) or $eh->show("0013");

    updaterating($lid);
    redirect_header("index.php",1,_MD_REVIEWDELETED);
    exit();
  }//End If

//Save a new Review
if(isset($HTTP_POST_VARS['submit']))
  {
    $eh = new ErrorHandler; //ErrorHandler object
	if(!$xoopsUser)
      {
		$reviewuser = 0;
        $ratinguser = 0;
	  }
      else
      {
		$reviewuser = $xoopsUser->uid();
        $ratinguser = $xoopsUser->uid();
	  }//End If

    //Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip = getenv("REMOTE_ADDR");
    $lid = intval($HTTP_POST_VARS['lid']);
    $review = $myts->makeTareaData4Save($HTTP_POST_VARS["review"]);
    $totradio = intval($HTTP_POST_VARS["totradio"]);

    // Check if review is Null
    if ($review=="")
      {
        redirect_header("reviewbook.php?lid=".$lid."",4,_MD_NOREVIEW);
		exit();
    	                 }
        // Check if REG user is trying to originally submit twice.
        $result=$xoopsDB->query("SELECT ratinguser FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE lid=$lid");
        while(list($ratinguserDB)=$xoopsDB->fetchRow($result))
          {
            if ($ratinguserDB==$ratinguser)
              {
                redirect_header("index.php",4,_MD_VOTEONCE);
				exit();
              }//End If
          }//End While

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

        //All is well.  Add to Line Item Rate to review DB.
	    $newid = $xoopsDB->genId($xoopsDB->prefix("myReviews_reviews")."_reviewid_seq");
	    $datetime = time();
        $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_reviews")." (reviewid, lid, reviewuser, review, reviewhostname, reviewtimestamp) VALUES ($newid, $lid, $reviewuser, '$review', '$ip', $datetime)") or $eh("0013");

        //All is well. Add totalvote data to the votedata table.
        $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_votedata")." (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES ($newid, $lid, $ratinguser, $totradio, '$ip', $datetime)") or $eh("0013");

        //All is well. Add category votes to votecat DB.
        for ($x=1;$x<$myReviews_catnum+1;$x++)
          {
            if (isset($myReviews_catname[$x]))
              {
                $newid = $xoopsDB->genId($xoopsDB->prefix("myReviews_votecat")."_ratingid_seq");
                $catradio[$x] = $HTTP_POST_VARS['catradio'.$x.''];
                $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("myReviews_votecat")." (ratingid, lid, ratinguser, rating, ratingcat, ratinghostname, ratingtimestamp) VALUES ($newid, $lid, $ratinguser, $catradio[$x], $x, '$ip', $datetime)") or $eh("0013");
              }//End if
          }//End for

        //All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
        updaterating($lid);

	    $ratemessage = _MD_REVIEWAPPRE."<br>".sprintf(_MD_THANKYOU,$xoopsConfig['sitename']);
	    redirect_header("index.php",4,$ratemessage);
	    exit();

  }//End If


include(XOOPS_ROOT_PATH."/header.php");
if ($myReviews_blocked)
  {
    OpenTable();
  }//End if
mainheader();
$lid = intval($HTTP_GET_VARS['lid']);

$submitter = $xoopsUser->uid();

$result350=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid=$lid AND reviewuser=$submitter");
list($reviewuserDB) = $xoopsDB->fetchRow($result350);

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

if ($reviewuserDB)
  {
    $result=$xoopsDB->query("SELECT title FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid");
    list($title) = $xoopsDB->fetchRow($result);
	$title = $myts->makeTboxData4Show($title);

    $submitter = $xoopsUser->uid();

    $result2000=$xoopsDB->query("SELECT review FROM ".$xoopsDB->prefix("myReviews_reviews")." WHERE lid=$lid AND reviewuser=$submitter");
	list($review) = $xoopsDB->fetchRow($result2000);
    $review = $myts->makeTareaData4Edit($review);

    $result3000=$xoopsDB->query("SELECT rating FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE lid=$lid AND ratinguser=$submitter");
    list($totrate) = $xoopsDB->fetchRow($result3000);

    $result4000=$xoopsDB->query("SELECT rating, ratingcat FROM ".$xoopsDB->prefix("myReviews_votecat")." WHERE lid=$lid AND ratinguser=$submitter ORDER BY ratingcat");

    echo "<hr />
	      <table border=0 cellpadding=1 cellspacing=0 width=\"80%\">
            <tr>
              <td>
                <h4>"._MD_EDITREVIEW." $title</h4>";
    echo "    </td>
            </tr>
          </table>";
    echo "<center><b>"._MD_TOTNAME_TITLE."</b></center>";
    echo "<table border=1 cellpadding=1 cellspacing=0 width=\"50%\">
            <tr>
              <td align=left>
              </td>";
    echo "<form method=\"POST\" action=\"reviewbook.php\">";

    for ($x=1;$x<$myReviews_totrate+1;$x++)
      {
        echo "<td align=center>$x</td>";
      }//End for
    echo "</tr>";
    echo "<tr><td align=right width=$myReviews_categorylabelwidth>"._MD_RATING."</td>";

    for ($x=1;$x<$myReviews_totrate+1;$x++)
      {
        if ($x==$totrate)
          {
            echo "<td align=center><INPUT TYPE=RADIO NAME='totradio' VALUE='$x' checked></td>";
          }
          else
          {
            echo "<td align=center><INPUT TYPE=RADIO NAME='totradio' VALUE='$x'></td>";
          }//End If
      }//End for

    echo "</tr></table>";

    //There is no rating categories
    if ($myReviews_catnum!=0)
      {
        echo "<center><b>"._MD_FEATURES_TITLE."</b></center>";

        echo "      <table border=1 cellpadding=1 cellspacing=0 width=\"50%\">   <tr><td align=left></td>";

        for ($x=1;$x<$myReviews_maxrate+1;$x++)
          {
            echo "<td align=center>$x</td>";
          }//End for
        echo "</tr>";

        $x=1;
        $ratingsdone=0;
        while(list($catrate, $ratingcat)=$xoopsDB->fetchRow($result4000))
          {
            echo "<tr>";
            echo "<td align=right width=$myReviews_categorylabelwidth>$myReviews_catname[$ratingcat]</td>";
            for ($y=1;$y<$myReviews_maxrate+1;$y++)
              {
                if ($y==$catrate)
                  {
                    echo "<td align=center><INPUT TYPE=RADIO NAME='catradio".$ratingcat."' VALUE=$y checked></td>";
                  }
                  else
                  {
                    echo "<td align=center><INPUT TYPE=RADIO NAME='catradio".$ratingcat."' VALUE=$y></td>";
                  }//Enf If
              }//End for
            //This is just to make sure that another rating category was not added
            if ($ratingsdone<$ratingcat)
              {
                $ratingsdone=$ratingcat;
              }//End if
          }//End While

        //Create review category for newly added categories not previously done
        for ($x=$ratingsdone+1;$x<$myReviews_catnum+1;$x++)
          {
            if (isset($myReviews_catname[$x]))
              {
                echo "<tr>";
                echo "<td align=right width=$myReviews_categorylabelwidth>$myReviews_catname[$x]</td>";
                for ($y=1;$y<$myReviews_maxrate+1;$y++)
                  {
                    if ($y==$checkrate)
                      {
                        echo "<td align=center><INPUT TYPE=RADIO NAME='catradio".$x."' VALUE=$y checked></td>";
                      }
                      else
                      {
                        echo "<td align=center><INPUT TYPE=RADIO NAME='catradio".$x."' VALUE=$y></td>";
                      }//End If
                  }//End for
                 echo "</tr>";
              }//End if
          }//End for

        $x=0;
        echo "</table>";
        echo "<br>";
      }//End if

    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
    echo "<textarea name=\"review\" cols=\"50\" rows=\"10\">$review</textarea>\n";
    echo "<br><br><input type=\"submit\" name=\"submit1\" value=\""._MD_REVIEWEDITIT."\"\n>";
    echo "&nbsp;<input type=\"submit\" name=\"delete\" value=\""._MD_DELETE."\"\n>";
    echo "&nbsp;<input type=\"button\" value=\""._MD_CANCEL."\" onclick=\"javascript:history.go(-1)\">\n";
    echo "</form>";
//    echo "</td></tr></table>";

  }
  else
  {
   	$result=$xoopsDB->query("SELECT title FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=$lid");
	list($title) = $xoopsDB->fetchRow($result);
	$title = $myts->makeTboxData4Show($title);

    echo "
   	  <hr />
	  <table border=0 cellpadding=1 cellspacing=0 width=\"80%\"> <tr><td>
      <h4>"._MD_REVIEW." $title</h4>";
    echo "</td></tr></table>";
    echo "<center><b>"._MD_TOTNAME_TITLE."</b></center>";
    echo "<table border=1 cellpadding=1 cellspacing=0 width=\"50%\">";
    echo "<td></td>";

    echo "<form method=\"POST\" action=\"reviewbook.php\">";

    for ($x=1;$x<$myReviews_totrate+1;$x++)
      {
        echo "<td align=center>$x</td>";
      }//End for
    echo "</tr>";

    echo "<tr><td align=right width=$myReviews_categorylabelwidth>"._MD_RATING."</td>";

    $totcheckrate=intval($myReviews_totrate/2);

    for ($x=1;$x<$myReviews_totrate+1;$x++)
      {
        if ($x==$totcheckrate)
          {
            echo "<td align=center><INPUT TYPE=RADIO NAME='totradio' VALUE='$x' checked></td>";
          }
          else
          {
            echo "<td align=center><INPUT TYPE=RADIO NAME='totradio' VALUE='$x'></td>";
          }//End If
      }//End for

    echo "</tr></table>";

    //There is no rating categories
    if ($myReviews_catnum!=0)
      {
        echo "<center><b>"._MD_FEATURES_TITLE."</b></center>";

        echo "      <table border=1 cellpadding=1 cellspacing=0 width=\"50%\">   <tr><td align=left></td>";

        for ($x=1;$x<$myReviews_maxrate+1;$x++)
          {
            echo "<td align=center>$x</td>";
          }//End for
        echo "</tr>";

        $checkrate=intval($myReviews_maxrate/2);

        for ($x=1;$x<$myReviews_catnum+1;$x++)
          {
            if (isset($myReviews_catname[$x]))
              {
                echo "<tr>";
                echo "<td align=right width=$myReviews_categorylabelwidth>$myReviews_catname[$x]</td>";
                for ($y=1;$y<$myReviews_maxrate+1;$y++)
                  {
                    if ($y==$checkrate)
                      {
                        echo "<td align=center><INPUT TYPE=RADIO NAME='catradio".$x."' VALUE=$y checked></td>";
                      }
                      else
                      {
                        echo "<td align=center><INPUT TYPE=RADIO NAME='catradio".$x."' VALUE=$y></td>";
                      }//End If
                  }//End for
                 echo "</tr>";
              }//End if
          }//End for
        echo "</table>";
        echo "<br>";
    }//End if

//    echo "</td></tr><tr><td align=center>";
    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
    echo "<textarea name=\"review\" cols=\"50\" rows=\"10\"></textarea>\n";
    echo "<br><br><input type=\"submit\" name=\"submit\" value=\""._MD_REVIEWIT."\"\n>";
    echo "&nbsp;<input type=\"button\" value=\""._MD_CANCEL."\" onclick=\"javascript:history.go(-1)\">\n";
    echo "</form>";
//    echo "</td></tr></table>";

  }//End If

if ($myReviews_blocked)
  {
    CloseTable();
  }//End if

include("footer.php");
?>