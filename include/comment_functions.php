<?php
// comment callback functions

function myreview_com_update($lid, $total_num){
	$db =& Database::getInstance();
	$sql = 'UPDATE '.$db->prefix('myreview_downloads').' SET comments = '.$total_num.' WHERE lid = '.$lid;
	$db->query($sql);
}

function xdir_com_approve(&$comment){
	// notification mail here
}
?>