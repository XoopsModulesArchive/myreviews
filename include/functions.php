<?php
function mainheader($mainlink=1)
  {
    echo "<p><div align=\"center\">";
	echo "<a href=\"".XOOPS_URL."/modules/myReviews/index.php\"><img src=\"".XOOPS_URL."/modules/myReviews/images/logo-en.gif\" border=\"0\" alt\"\" /></a>";
    echo "</p>";
  }//End Function

function newdownloadgraphic($time, $status)
  {
    $count = 7;
	$startdate = (time()-(86400 * $count));
    if ($startdate < $time)
      {
	    if($status==1)
          {
		    echo "&nbsp;<img src=\"".XOOPS_URL."/modules/myReviews/images/newred.gif\" alt=\""._MD_NEWTHISWEEK."\" />";
		  }
          elseif($status==2)
          {
		    echo "&nbsp;<img src=\"".XOOPS_URL."/modules/myReviews/images/update.gif\" alt=\""._MD_UPTHISWEEK."\" />";
          }
      }
  }//End Function

function popgraphic($hits)
  {
    global $myReviews_popular;
    if ($hits>=$myReviews_popular)
      {
        echo "&nbsp;<img src =\"".XOOPS_URL."/modules/myReviews/images/pop.gif\" alt=\""._MD_POPULAR."\" />";
      }
  }//End Function

//Reusable Link Sorting Functions
function convertorderbyin($orderby)
  {
    if ($orderby == "titleA") $orderby = "title ASC";
    if ($orderby == "dateA") $orderby = "date ASC";
    if ($orderby == "hitsA") $orderby = "hits ASC";
    if ($orderby == "ratingA") $orderby = "rating ASC";
    if ($orderby == "titleD") $orderby = "title DESC";
    if ($orderby == "dateD") $orderby = "date DESC";
    if ($orderby == "hitsD") $orderby = "hits DESC";
    if ($orderby == "ratingD") $orderby = "rating DESC";
    return $orderby;
  }//End Function

function convertorderbytrans($orderby)
  {
    if ($orderby == "hits ASC") $orderbyTrans = _MD_POPULARITYLTOM;
    if ($orderby == "hits DESC") $orderbyTrans = _MD_POPULARITYMTOL;
    if ($orderby == "title ASC") $orderbyTrans = _MD_TITLEATOZ;
   	if ($orderby == "title DESC") $orderbyTrans = _MD_TITLEZTOA;
    if ($orderby == "date ASC") $orderbyTrans = _MD_DATEOLD;
    if ($orderby == "date DESC") $orderbyTrans = _MD_DATENEW;
    if ($orderby == "rating ASC") $orderbyTrans = _MD_RATINGLTOH;
    if ($orderby == "rating DESC") $orderbyTrans = _MD_RATINGHTOL;
    return $orderbyTrans;
  }//End Function

function convertorderbyout($orderby)
  {
    if ($orderby == "title ASC") $orderby = "titleA";
    if ($orderby == "date ASC") $orderby = "dateA";
    if ($orderby == "hits ASC") $orderby = "hitsA";
    if ($orderby == "rating ASC") $orderby = "ratingA";
    if ($orderby == "title DESC") $orderby = "titleD";
    if ($orderby == "date DESC") $orderby = "dateD";
    if ($orderby == "hits DESC") $orderby = "hitsD";
    if ($orderby == "rating DESC") $orderby = "ratingD";
    return $orderby;
  }//End Function

function PrettySize($size)
  {
    $mb = 1024*1024;
    if ( $size > $mb )
      {
        $mysize = sprintf ("%01.2f",$size/$mb) . " MB";
      }
      elseif ( $size >= 1024 )
        {
          $mysize = sprintf ("%01.2f",$size/1024) . " KB";
        }//End ElseIf
    else
    {
      $mysize = sprintf(_MD_NUMBYTES,$size);
    }//End If
    return $mysize;
  }//End Function

//updates rating data in itemtable for a given item
function updaterating($sel_id)
  {
    global $xoopsDB;
	$query = "select rating FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE lid = ".$sel_id."";
	$voteresult = $xoopsDB->query($query);
    $votesDB = $xoopsDB->getRowsNum($voteresult);
	$totalrating = 0;
    while(list($rating)=$xoopsDB->fetchRow($voteresult))
      {
	    $totalrating += $rating;
	  }//End Foreach
    if ($votesDB==0)
      {
        $finalrating = 0;
      }
      else
      {
        $finalrating = $totalrating/$votesDB;
      }//End if
	$finalrating = number_format($finalrating, 4);
	$query =  "UPDATE ".$xoopsDB->prefix("myReviews_downloads")." SET rating=$finalrating, votes=$votesDB WHERE lid = $sel_id";
    $xoopsDB->query($query);
  }//End Function

//returns the total number of items in items table that are accociated with a given table $table id
function getTotalItems($sel_id, $status="")
  {
	global $xoopsDB, $mytree;
	$count = 0;
	$arr = array();
	$query = "select count(*) from ".$xoopsDB->prefix("myReviews_downloads")." where cid=".$sel_id."";
	if($status!="")
      {
	    $query .= " and status>=$status";
	  }//End If
	$result = $xoopsDB->query($query);
	list($thing) = $xoopsDB->fetchRow($result);
	$count = $thing;
	$arr = $mytree->getAllChildId($sel_id);
	$size = sizeof($arr);
	for($i=0;$i<$size;$i++)
      {
	    $query2 = "select count(*) from ".$xoopsDB->prefix("myReviews_downloads")." where cid=".$arr[$i]."";
		if($status!="")
          {
		    $query2 .= " and status>=$status";
		  }//End If
		$result2 = $xoopsDB->query($query2);
		list($thing) = $xoopsDB->fetchRow($result2);
		$count += $thing;
	  }//End For
	return $count;
  }//End Function

function letters()
{
    $letterchoice = "[  ";
    $alphabet = array ("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
    $num = count($alphabet) - 1;
    $counter = 0;
    while (list(, $ltr) = each($alphabet)) {
        $letterchoice .= "<a href='viewlist.php?list=$ltr'>$ltr</a>";
        if ($counter == round($num / 2))
            $letterchoice .= " ]<br>[ ";
        elseif ($counter != $num)
            $letterchoice .= "&nbsp;|&nbsp;";
        $counter++;
    }
    $letterchoice .= " ]";
    return $letterchoice;
}

//Check if the file extension is allowed
function bad_ext($filename)
{
global $ext;
$exp=explode(".",$filename);
$nb=count($exp);
for($i=0;$i<$nb;$i++)
 {
   if($exp[($nb-1)]==$ext[$i])
   {
   return false;
   exit;
   }
 }
return true;
}

//Get filename extension
function get_ext($filename)
{
global $ext;
$exp=explode(".",$filename);
$nb=count($exp);
$extension='';
for($i=0;$i<$nb;$i++)
 {
   if($exp[($nb-1)]==$ext[$i])
   {
     $extension=$ext[$i];
   return $extension;
   exit;
   }
 }
return $extension;
}

/*
Function createthumb($name,$filename,$new_w,$new_h)
creates a resized image
variables:
$name        Original Image Name
$root           Root path up to the web directory (XOOPS_ROOT_PATH)
$path           Path after root (/modules/wfsection/images/article/)
$savepath       Path that appears after $path (thumbs/)
$new_w        width of resized image
$new_h        height of resized image
$quality        Compression level (0-100)
*/
function reviews_createthumb($name,$root, $path, $savepath, $new_w=100, $new_h=100, $quality=80){

    //$savefile = $path.$savepath.$new_w."x".$new_h."_".$name;
    $savefile = $path.$savepath.$name;
    $savepath = $root.$savefile;

    if(!file_exists($savepath)){

            // Get image location
            $image_path = $root.$path ."/".$name;
            //echo "$image_path";
            // Load image
            $img = null;
            //$ext = end(explode('.', $image_path));
            $extention = get_ext($image_path);
            if ($extention == 'jpg' || $extention == 'jpeg') {
                if (!function_exists('imagecreatefromjpeg'))
                  {
                    redirect_header('index.php',2,_MD_NO_GD_FOUND);
                  }//End if
                $img = @imagecreatefromjpeg($image_path);
            } else if ($extention == 'png') {
                $img = @imagecreatefrompng($image_path);
            // Only if your version of GD includes GIF support
            } else if ($extention == 'gif') {
                $img = @imagecreatefrompng($image_path);
            }
            // If an image was successfully loaded, test the image for size
            if ($img) {

                // Get image size and scale ratio
                $width = imagesx($img);
                $height = imagesy($img);
                $scale = min($new_w/$width, $new_h/$height);

                // If the image is larger than the max shrink it
                if ($scale < 1) {
                    $new_width = floor($scale*$width);
                    $new_height = floor($scale*$height);

                    // Create a new temporary image
                    //$tmp_img = imagecreatetruecolor($new_width, $new_height);
                    if (!function_exists('imagecreatetruecolor'))
                      {
                        $tmp_img = @imagecreatetruecolor($new_width, $new_height);
                      }
                      else
                      {
                        $tmp_img = @imagecreate($new_width, $new_height);
                      }//End if

                    // Copy and resize old image into new image
                    imagecopyresized($tmp_img, $img, 0, 0, 0, 0,
                                     $new_width, $new_height, $width, $height);
                    imagedestroy($img);
                    $img = $tmp_img;
                }
            }
            /*
            // Create error image if necessary
            if (!$img) {
                $img = imagecreate($new_w, $new_h);
                imagecolorallocate($img,0,0,0);
                $c = imagecolorallocate($img,70,70,70);
                imageline($img,0,0,$new_w,$new_h,$c2);
                imageline($img,$new_w,0,0,$new_h,$c2);
            }
            */

            // output the image as a file to the output stream
            //echo "$savepath";
            if ($extention == 'jpg' || $extention == 'jpeg')
              {
                Imagejpeg($img,$savepath,$quality);
              }//End if

    }
    return $savefile;
}

?>