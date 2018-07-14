<?
// -------------------------------------------------------------------------
//                             Ze Uploader 0.6
//                                Module for
//                  XOOPS - PHP Content Management System 1RC3
//                         <http://www.xoops.org/>
// -------------------------------------------------------------------------
//  Based on:
//  myPHPNUKE Web Portal System - http://myphpnuke.com/
//  PHP-NUKE Web Portal System - http://phpnuke.org/
//  Thatware - http://thatware.org/
// -------------------------------------------------------------------------
//  This program is free software; you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation; either version 2 of the License, or
//  (at your option) any later version.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
// -------------------------------------------------------------------------
//
//  Author: XoopsOnLine.com
//  Purpose: Module for upload files with Xoops 1RC3.
//  Email: xoopsonline@franceonline.fr
//  Site: http://www.XoopsOnLine.com
//
// -------------------------------------------------------------------------

include("../../mainfile.php");
include(XOOPS_ROOT_PATH."/header.php");
$xoopsOption['show_rblock']=1;
OpenTable();

include("ulconf/config.php");
include("cache/config.php");
include("ulconf/exten.php");

/*function bad_ext($filename)
{
global $ext;
$exp=explode(".",$filename);
for($i=0;$i<$ext+1;$i++)
 {
   if($exp[($nb-1)]==$ext[$i])
   {
   return false;
   exit;
   }
 }
return true;
}*/

if(!(isset($submit)))
{
echo"
    <center><br><br><b>"._MD_UPLOAD."</b><br><br>
    <form enctype=\"multipart/form-data\" action=\"$PHP_SELF\" method=\"post\">
    <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$sizemax\">
    <input name=\"userfile\" type=\"file\">
    <input type=\"submit\" name=\"submit\" value="._MD_UPLOADFILE.">
    </form></center><br>
";

if($affichage=="oui")
{
echo"
<center>
<table border=0 width=60%>
<tr><td><b>Nom du Fichier</b></td><td align=right><b>Taille du Fichier</b></td></tr>
";
$lsdir=opendir($gsdownloads_uphome);
while($reslsdir=readdir($lsdir))
{
      if($reslsdir!="." && $reslsdir!="..")
      {
       $sizereslsdir=filesize($gsdownloads_uphome.$reslsdir);
       $ahrefreslsdir="$gsdownloads_uphome"."$reslsdir";
       echo"
       <tr><td>
       ";
       if($liens=="oui")
       {
        echo"<a href=$ahrefreslsdir>";
       }
       echo"$reslsdir";
       if($liens=="oui")
        {
         echo"</a>";
        }
       echo"
       </td><td align=right>$sizereslsdir octets</td></tr>
       ";
      }
}
echo"</table></center>";
closedir($lsdir);
}

}
else
{
    if(file_exists($gsdownloads_uphome.$userfile_name))
    {
        echo"<center><br><br><b>"._MD_UPLOAD."</b><br><br>$userfile_name<br><br><b><font color=\"red\">"._MD_NOFILE."</font></b><br><br><a href=javascript:history.back();>"._MD_TRYAGAIN."</a></center><br><br><br><br><br>";
    }
    elseif($userfile_size>$sizemax)
    {
        echo"<center><br><br><b>"._MD_UPLOAD."</b><br><br>$userfile_name<br><br><b><font color=\"red\">"._MD_FILETOOBIG."</font></b><br><br><a href=javascript:history.back();>"._MD_TRYAGAIN."</a></center><br><br><br><br><br>";
    }
/*    elseif(bad_ext($userfile_name))
    {
        echo"<center><br><br><b>"._MD_UPLOAD."</b><br><br>$userfile_name<br><br><b><font color=\"red\">"._MD_FILENOTACCEPTED."</font></b><br><br><a href=javascript:history.back();>"._MD_TRYAGAIN." </a></center><br><br><br><br><br>";
    }*/
    else
    {
        copy($userfile,$myreviews_uphome.$userfile_name);
        if(is_uploaded_file($userfile))
        {
        redirect_header("index.php",2,_MD_RECEIVED."<br><br>"._MD_WHENAPPROVED."");
        }
        else
        {
            echo "<center><br><br><b>"._MD_UPLOAD."</b><br><br>$userfile_name<br><br><b>"._MD_PROBLEMCOPY."</b><br><br><a href=javascript:history.back();>"._MD_TRYAGAIN." </a></center><br><br><br><br><br>";
        }
    }
}

CloseTable();
include(XOOPS_ROOT_PATH."/footer.php");
?>
