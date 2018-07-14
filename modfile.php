<?php
// $Id: modfile.php,v 1.1 2004/01/29 14:45:12 buennagel Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

include "header.php";
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix("myReviews_cat"),"cid","pid");

if(isset($HTTP_POST_VARS['submit']))
  {
    $eh = new ErrorHandler; //ErrorHandler object
    if(empty($xoopsUser)){
        redirect_header(XOOPS_URL."/user.php",2,_MD_MUSTREGFIRST);
        exit();
    } else {
        $ratinguser = $xoopsUser->getVar('uid');
    }
    $submit_vars = array('lid', 'title', 'url', 'homepage', 'description', 'logourl', 'cid');
    foreach($submit_vars as $submit_key) {
        $$submit_key = $HTTP_POST_VARS[$submit_key];
    }
    $lid = intval($lid);

    // Check if Title exist
    if (trim($title)=="") {
        $eh->show("1001");
    }
/*
    // Check if URL exist
    if (trim($url)=="") {
        $eh->show("1016");
    }
*/
    // Check if HOMEPAGE exist
    if (trim($homepage)=="") {
        $eh->show("1016");
    }
    // Check if Description exist
    if (trim($description)=="") {
        $eh->show("1008");
    }

    $url = $myts->makeTboxData4Save($url);
    $logourl = $myts->makeTboxData4Save($logourl);
    $cid = intval($cid);
    $title = $myts->makeTboxData4Save($title);
    $homepage = $myts->makeTboxData4Save($homepage);
    $description = $myts->makeTareaData4Save($description);
    $newid = $xoopsDB->genId($xoopsDB->prefix("myReviews_mod")."_requestid_seq");

    $sql = sprintf("INSERT INTO %s (requestid, lid, cid, title, url, homepage, logourl, description, modifysubmitter) VALUES (%u, %u, %u, '%s', '%s', '%s', '%s', '%s', %u)", $xoopsDB->prefix("myReviews_mod"), $newid, $lid, $cid, $title, $url, $homepage, $logourl, $description, $ratinguser);
    $xoopsDB->query($sql) or $eh->show("0013");
    $tags = array();
    $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listModReq';
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->triggerEvent('global', 0, 'file_modify', $tags);
    redirect_header("index.php",2,_MD_THANKSFORINFO);
    exit();

  }//End if


    $lid = intval($HTTP_GET_VARS['lid']);
    if(empty($xoopsUser)){
        redirect_header(XOOPS_URL."/user.php",2,_MD_MUSTREGFIRST);
        exit();
    }
    $xoopsOption['template_main'] = 'myreviews_modfile.html';
    include XOOPS_ROOT_PATH."/header.php";
    $result = $xoopsDB->query("SELECT cid, title, url, homepage, logourl FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=".$lid." AND status>0");
    $xoopsTpl->assign('lang_requestmod', _MD_REQUESTMOD);
    list($cid, $title, $url, $homepage, $logourl) = $xoopsDB->fetchRow($result);
    $title = $myts->makeTboxData4Edit($title);
    $url = $myts->makeTboxData4Edit($url);
    $homepage = $myts->makeTboxData4Edit($homepage);
    $logourl = $myts->makeTboxData4Edit($logourl);
    $result2 = $xoopsDB->query("SELECT description FROM ".$xoopsDB->prefix("myReviews_text")." WHERE lid=$lid");
    list($description)=$xoopsDB->fetchRow($result2);
    $description = $myts->makeTareaData4Edit($description);

    $xoopsTpl->assign('file', array('lid' => $lid, 'title' => $title, 'url' => $url, 'logourl' => $logourl, 'description' => $description, 'homepage' => $homepage));
    $xoopsTpl->assign('lang_fileid', _MD_FILEID);
    $xoopsTpl->assign('lang_sitetitle', _MD_FILETITLE);
    $xoopsTpl->assign('lang_siteurl', _MD_DLURL);
    $xoopsTpl->assign('lang_category', _MD_CATEGORYC);
    $xoopsTpl->assign('lang_homepage', _MD_HOMEPAGEC);
    $xoopsTpl->assign('lang_logourl', _MD_SHOTIMAGE);
	
    ob_start();
    $mytree->makeMySelBox("title", "title", $cid);
    $selbox = ob_get_contents();
    ob_end_clean();
    $xoopsTpl->assign('category_selbox', $selbox);
    $xoopsTpl->assign('lang_description', _MD_DESCRIPTIONC);
    $xoopsTpl->assign('modifysubmitter', $xoopsUser->getVar('uid'));
    $xoopsTpl->assign('lang_sendrequest', _MD_SENDREQUEST);
    $xoopsTpl->assign('lang_cancel', _CANCEL);

    $xoopsTpl->assign('lang_submitonce', _MD_SUBMITONCE);
    $xoopsTpl->assign('lang_allpending', _MD_ALLPENDING);
    $xoopsTpl->assign('lang_dontabuse', _MD_DONTABUSE);
    $xoopsTpl->assign('lang_takedays', _MD_TAKEDAYS);

    include XOOPS_ROOT_PATH.'/footer.php';
?>