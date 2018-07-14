<?php
include "../../mainfile.php";

$limit = 10;

	$xoopsOption['template_main'] = 'myreviews_halloffame.html';
	include XOOPS_ROOT_PATH."/header.php";

	$iamadmin = false;
	if ( $xoopsUser && $xoopsUser->isAdmin() ) {
		$iamadmin = true;
	}
	$myts =& MyTextSanitizer::getInstance();

    $result = $xoopsDB->query("SELECT ratinguser, count(*) as reviews FROM ".$xoopsDB->prefix("myReviews_votedata")." GROUP BY ratinguser ORDER BY reviews DESC",$limit,0);
    $total = $xoopsDB->getRowsnum($result);

	$start = (!empty($HTTP_POST_VARS['start'])) ? intval($HTTP_POST_VARS['start']) : 0;

	$xoopsTpl->assign('lang_search', _MM_SEARCH);
	$xoopsTpl->assign('lang_results', _MM_RESULTS);
	$xoopsTpl->assign('total_found', $total);

	if ( $total == 0 ) {
		$xoopsTpl->assign('lang_nonefound', _MM_NOFOUND);
	} elseif ( $start < $total ) {
		$xoopsTpl->assign('lang_username', _MM_UNAME);
		$xoopsTpl->assign('lang_realname', _MM_REALNAME);
		$xoopsTpl->assign('lang_avatar', _MM_AVATAR);
		$xoopsTpl->assign('lang_email', _MM_EMAIL);
		$xoopsTpl->assign('lang_privmsg', _MM_PM);
		$xoopsTpl->assign('lang_regdate', _MM_REGDATE);
		$xoopsTpl->assign('lang_lastlogin', _MM_LASTLOGIN);
		$xoopsTpl->assign('lang_posts', _MM_POSTS);
		$xoopsTpl->assign('lang_url', _MM_URL);
		$xoopsTpl->assign('lang_admin', _MM_ADMIN);

		if ( $iamadmin ) {
			$xoopsTpl->assign('is_admin', true);
		}

		//$foundusers =& $member_handler->getUsers($result, true);
        $ratinguserstring='';
        while(list($ratinguser)=$xoopsDB->fetchRow($result))
          {
            if ($ratinguserstring)
              {
                $ratinguserstring=$ratinguserstring.','.$ratinguser;
              }
              else
              {
                $ratinguserstring=$ratinguser;
              }//End if
          }//End while
        $user_array = "(".$ratinguserstring.")";
        $criteria = new Criteria('uid', $user_array, 'IN');
        $foundusers =& $member_handler->getUsers($criteria, true);

		foreach (array_keys($foundusers) as $j) {
			$userdata['avatar'] = $foundusers[$j]->getVar("user_avatar") ? "<img src='".XOOPS_UPLOAD_URL."/".$foundusers[$j]->getVar("user_avatar")."' alt='' />" : "&nbsp;";
			$userdata['realname'] = $foundusers[$j]->getVar("name") ? $foundusers[$j]->getVar("name") : "&nbsp;";
			$userdata['name'] = $foundusers[$j]->getVar("uname");
			$userdata['id'] = $foundusers[$j]->getVar("uid");
			if ( $foundusers[$j]->getVar("user_viewemail") == 1 || $iamadmin ) {
				$userdata['email'] = "<a href='mailto:".$foundusers[$j]->getVar("email")."'><img src='".XOOPS_URL."/images/icons/email.gif' border='0' alt='".sprintf(_SENDEMAILTO,$foundusers[$j]->getVar("uname", "E"))."' /></a>";
			} else {
				$userdata['email'] = "&nbsp;";
			}
			if ( $xoopsUser ) {
				$userdata['pmlink'] = "<a href='javascript:openWithSelfMain(\"".XOOPS_URL."/pmlite.php?send2=1&amp;to_userid=".$foundusers[$j]->getVar("uid")."\",\"pmlite\",450,370);'><img src='".XOOPS_URL."/images/icons/pm.gif' border='0' alt='".sprintf(_SENDPMTO,$foundusers[$j]->getVar("uname", "E"))."' /></a>";
			} else {
				$userdata['pmlink'] = "&nbsp;";
			}
			if ( $foundusers[$j]->getVar("url","E") != "" ) {
				$userdata['website'] =  "<a href='".$foundusers[$j]->getVar("url","E")."' target='_blank'><img src='".XOOPS_URL."/images/icons/www.gif' border='0' alt='"._VISITWEBSITE."' /></a>";
			} else {
				$userdata['website'] =  "&nbsp;";
			}
			$userdata['registerdate'] = formatTimeStamp($foundusers[$j]->getVar("user_regdate"),"s");
			if ( $foundusers[$j]->getVar("last_login") != 0 ) {
				$userdata['lastlogin'] =  formatTimeStamp($foundusers[$j]->getVar("last_login"),"m");
			} else {
				$userdata['lastlogin'] =  "&nbsp;";
			}
            $reviews = $xoopsDB->query("SELECT count(*) as reviews FROM ".$xoopsDB->prefix("myReviews_votedata")." WHERE ratinguser = ".$userdata['id']." ",1,0);
            list($reviewnum)=$xoopsDB->fetchRow($reviews);
            $userdata['posts'] = $reviewnum;

			$xoopsTpl->append('users', $userdata);
		}

		$totalpages = ceil($total / $limit);
		if ( $totalpages > 1 ) {
			$hiddenform = "<form name='findnext' action='index.php' method='post'>";
			foreach ( $HTTP_POST_VARS as $k => $v ) {
				$hiddenform .= "<input type='hidden' name='".$myts->oopsHtmlSpecialChars($k)."' value='".$myts->makeTboxData4PreviewInForm($v)."' />\n";
			}
			if (!isset($HTTP_POST_VARS['limit'])) {
				$hiddenform .= "<input type='hidden' name='limit' value='".$limit."' />\n";
			}
			if (!isset($HTTP_POST_VARS['start'])) {
				$hiddenform .= "<input type='hidden' name='start' value='".$start."' />\n";
			}
			$prev = $start - $limit;
			if ( $start - $limit >= 0 ) {
				$hiddenform .= "<a href='#0' onclick='javascript:document.findnext.start.value=".$prev.";document.findnext.submit();'>"._MM_PREVIOUS."</a>&nbsp;\n";
        	}
			$counter = 1;
			$currentpage = ($start+$limit) / $limit;
			while ( $counter <= $totalpages ) {
				if ( $counter == $currentpage ) {
					$hiddenform .= "<b>".$counter."</b> ";
				} elseif ( ($counter > $currentpage-4 && $counter < $currentpage+4) || $counter == 1 || $counter == $totalpages ) {
					if ( $counter == $totalpages && $currentpage < $totalpages-4 ) {
						$hiddenform .= "... ";
					}
					$hiddenform .= "<a href='#".$counter."' onclick='javascript:document.findnext.start.value=".($counter-1)*$limit.";document.findnext.submit();'>".$counter."</a> ";
					if ( $counter == 1 && $currentpage > 5 ) {
						$hiddenform .= "... ";
					}
				}
				$counter++;
			}
			$next = $start+$limit;
			if ( $total > $next ) {
				$hiddenform .= "&nbsp;<a href='#".$total."' onclick='javascript:document.findnext.start.value=".$next.";document.findnext.submit();'>"._MM_NEXT."</a>\n";
			}
			$hiddenform .= "</form>";
			$xoopsTpl->assign('pagenav', $hiddenform);
			$xoopsTpl->assign('lang_numfound', sprintf(_MM_USERSFOUND, $total));
		}
	}

include_once XOOPS_ROOT_PATH."/footer.php";
?>