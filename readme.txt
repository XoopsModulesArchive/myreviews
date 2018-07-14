myReviews v. 2.1 RC1
brought to you by:
AJ van den Berg (Camper)
http://www.craftsonline.co.za

Note: After users have entered data, you should not change the configuration of the reviews categories, number of reviews categories, number for the overall review and name of reviews categories because this will unlink the names from the data. Although I have made every effort to keep track of changes within the rating categories and making sure that if you delete categories or add categories that it does not effect ratings aready made, you will undestand that if you change what the description of a rating category all previous ratings will be calculated under the new heading. 

All efforts have been made to have the module work with PHP Globals off.

I have tested the module with several themes and tried to use common style sheet class elements.

Common Problems:
Beta1 has a problem with accessing the reviewcat table under unix - fixed the script in Beta2.
Beta5 has a problem with hall of fame using too much memory on servers where there is a lot of registered users.
Beta6 has a problem with image submission under unix.

Things I am still working on:
- Dynamic fields creation
- Comparative searching
- Bulk rating categories updates
- Image submission on its own where no image exist
- Add blank descriptions for all pages returning blank information.
- Submission groups profile
- User category submissions
- X people found this review helpful
- Original reviewer vs community reviews
- Manufacturer / Vendor can be added per review with Graphics
- Smarty templates
- Xoops Commements integration
- Xoops Notification integration

(What do you want to see here :-))

Update : v2.1 RC1:
- Extensive code cleanup by m0nty.

Updates: v2.1 Beta8:
- Added thumbnail creation if the thumbnail does not exist for some reason on the index page.
- Added code to create thumbnail in the same size as image display pixels in admin.
- Icons on Index page were bundled up to the left when there was no reviews loaded.
- Added code when deleting a review, the pic is also deleted from shots and thumbs directory.
- Images thumbnails available in blocks
- Renamed files in templates folder;myReviews_modfile.html rename to: myreviews_modfile.html (m0nty)
- Renamed files in templates folder;myReviews_halloffame.html rename to: myreviews_halloffame.html (m0nty)
- Renamed files in templates folder;myReviews_detailfile.html rename to: myreviews_detailfile.html (m0nty)
- In file: modfile.php line 98: $xoopsOption['template_main'] = 'myReviews_modfile.html'; 
  changed to: $xoopsOption['template_main'] = 'myreviews_modfile.html'; (m0nty)
- In file: modfile.php line 100: $result = $xoopsDB->query("SELECT cid, title, url, homepage, version, size, platform, logourl FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=".$lid." AND status>0");
  changed to: $result = $xoopsDB->query("SELECT cid, title, url, homepage, logourl FROM ".$xoopsDB->prefix("myReviews_downloads")." WHERE lid=".$lid." AND status>0"); (m0nty)
- In file: modfile.php line 102: list($cid, $title, $url, $homepage, $version, $size, $platform, $logourl) = $xoopsDB->fetchRow($result);
  changed to: list($cid, $title, $url, $homepage, $logourl) = $xoopsDB->fetchRow($result); (m0nty)
- In file: modfile.phpline 124: $xoopsTpl->assign('file', array('id' => $lid, 'title' => $title, 'url' => $url, 'logourl' => $logourl, 'description' => $description, 'plataform' => $platform,'size' => $size,'homepage' => $homepage,'version' => $version));
  changed to: $xoopsTpl->assign('file', array('lid' => $lid, 'title' => $title, 'url' => $url, 'logourl' => $logourl, 'description' => $description, 'homepage' => $homepage)); (m0nty)
- In file: include/dlformatexcerpt.php line 214: $result100=$xoopsDB->query("SELECT excerpt FROM ".$xoopsDB->prefix("myreviews_excerpt")." WHERE lid = $lid");
  should be: $result100=$xoopsDB->query("SELECT excerpt FROM ".$xoopsDB->prefix("myReviews_excerpt")." WHERE lid = $lid"); (m0nty)
- in file: admin/index.php line 113: $result = $xoopsDB->query("SELECT lid, cid, title, url, homepage, version, size, platform, price, logourl, submitter FROM ".$xoopsDB->prefix("myReviews_downloads")." where status=0 ORDER BY date DESC");
  changed to: $result = $xoopsDB->query("SELECT lid, cid, title, url, homepage, logourl, submitter FROM ".$xoopsDB->prefix("myReviews_downloads")." where status=0 ORDER BY date DESC"); (m0nty)
- in file: admin/index.php line 123: while(list($lid, $cid, $title, $url, $homepage, $version, $size, $platform, $price, $logourl, $uid) = $xoopsDB->fetchRow($result)) {
  changed to: while(list($lid, $cid, $title, $url, $homepage, $logourl, $uid) = $xoopsDB->fetchRow($result)) {  (m0nty)
- in file: detailfile.php line 31: $q = "SELECT d.cid, d.title, d.url, d.homepage, d.version, d.size, d.platform, d.price, d.logourl, d.status, d.date, d.hits, d.rating, d.votes, d.comments, t.description, d.loveit, d.helpfull, d.unhelpfull, d.recommendit FROM ".$xoopsDB->prefix("myReviews_downloads")." d, ".$xoopsDB->prefix("myReviews_text")." t WHERE d.lid=$lid AND d.lid=t.lid AND status>0";
  changed to: $q = "SELECT d.lid, d.cid, d.title, d.url, d.homepage, d.version, d.size, d.platform, d.price, d.logourl, d.status, d.date, d.hits, d.rating, d.votes, d.comments, t.description, d.loveit, d.helpfull, d.unhelpfull, d.recommendit FROM ".$xoopsDB->prefix("myReviews_downloads")." d, ".$xoopsDB->prefix("myReviews_text")." t WHERE d.lid=$lid AND d.lid=t.lid AND status>0"; (m0nty)
- in file: detailfile.php line 33: list($cid, $title, $url, $homepage, $version, $size, $platform, $price, $logourl, $status, $time, $hits, $rating, $votes, $comments, $description, $loveit, $helpfull, $unhelpfull, $recommendit)=$xoopsDB->fetchRow($result);
  changed to: list($lid, $cid, $title, $url, $homepage, $version, $size, $platform, $price, $logourl, $status, $time, $hits, $rating, $votes, $comments, $description, $loveit, $helpfull, $unhelpfull, $recommendit)=$xoopsDB->fetchRow($result); (m0nty)


Updates: v2.1 Beta7:
- Fixed stripslashes bug in image uploader and other upload problems.
- Changed hall of fame internal workings.
- Added thumbnail image creating on submission. (GD library extension needed)
- Fixed sql error when viewing single file without review.

Updates: v2.1 Beta6:
- Fixed a bug that made the right column drop to the botom of the page in 3 column view mode.
- Added module menu like the one in system admin.
- Detail.php was giving a blank page under certain conditions of POST.
- Added links "Tell a friend | Modify review | Write editorial | Edit | Write review | Broken link" to the detail.php file
- Added display number of lines config to waiting reviews block.
- Adapted star ratings to incremment by 1/2 stars.
- Added image upload on submission.
- Added homepage link pic. (if a homepage now url is used)
- Added Cart link pic. (if a buy now url is used)
- Changes made to image linking. Clicking on the review image will load the image into it's own window for larger display.
- Added Love at first sight menu item and page. This is used by administrator to select certain items they like more than others. These items are marked with an icon.
- Added Site Recommendation menu item and page. This is used by administrator to select certain items they would like to recommend to browsers. These items are marked with an icon.
- Added icon legends to the bottom of the page.
- Changed admin of rating categories to administer 5 levels deep.

Updates: v2.1 Beta5:
- Added review.gif pic to the detailed review
- Different graphical review bar types (Round, Stars, Slide)
- Xoops search integration
- Head of the Class block added
- Most Voted block added
- Cleaned up php notice in all displays
- Browse reviews by alphabetical listing

Updates: v2.1 Beta4:
- Changed modfile.php and myReviewsmodfile.html major errors.
- Added Frame blocking support to admin
- Hall of fame block and menu item
- Onion awards menu item

Updates: v2.1 Beta3:
- In the detailfile.php I was already doing some beta comment stuff that was not stable under some installs, I took it out for now.
- Added Written review placement in relation to review bars to admin config.
- Added function to be able to specify 0 ratings on front page in admin config.
- Added Category Pic placement in relation to hyperlinks in admin config.
- Fixed a math bug that was calculating ave wrong for multiple ratings.

Updates: v2.1 Beta2:
-The description.gif gliph was in uppercase.
-The ratingcat table also had a case problem under unix.


Please see the FAQ on the CraftsOnline Web site for support.

Module Development support is provided through the Xoops develpoment forge:
http://dev.xoops.org/modules/xfmod/project/?myreviews

Here you may follow development and releases for this module and log bugs if you find any.

THIS MODULE IS PROVIDED AS IS WITH NO WARRANTIES OR IMPLIED STATEMENT OF SUITABILITY.

Regards,

Riaan