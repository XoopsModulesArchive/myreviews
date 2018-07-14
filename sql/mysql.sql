# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table myReviews_cat
#

CREATE TABLE myReviews_cat (
  cid int(5) unsigned NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  imgurl varchar(150) NOT NULL default '',
  PRIMARY KEY  (cid),
  KEY pid (pid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table myReviews_downloads
#

CREATE TABLE myReviews_downloads (
  lid int(11) unsigned NOT NULL auto_increment,
  cid int(5) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(250) NOT NULL default '',
  homepage varchar(100) NOT NULL default '',
  logourl varchar(60) NOT NULL default '',
  submitter int(11) NOT NULL default '0',
  status tinyint(2) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  hits int(11) unsigned NOT NULL default '0',
  rating double(6,4) NOT NULL default '0.0000',
  votes int(11) unsigned NOT NULL default '0',
  comments int(11) unsigned NOT NULL default '0',
  loveit tinyint(2) unsigned NOT NULL default '0',
  helpfull int(11) unsigned NOT NULL default '0',
  unhelpfull int(11) unsigned NOT NULL default '0',
  recommendit tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (lid),
  KEY cid (cid),
  KEY status (status),
  KEY title (title(40))
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table myReviews_mod
#

CREATE TABLE myReviews_mod (
  requestid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  cid int(5) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(250) NOT NULL default '',
  homepage varchar(100) NOT NULL default '',
  logourl varchar(60) NOT NULL default '',
  description text NOT NULL,
  modifysubmitter int(11) NOT NULL default '0',
  PRIMARY KEY  (requestid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table myReviews_text
#

CREATE TABLE myReviews_text (
  lid int(11) unsigned NOT NULL default '0',
  description text NOT NULL,
  KEY lid (lid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table myReviews_excerpt
#

CREATE TABLE myReviews_excerpt (
  lid int(11) unsigned NOT NULL default '0',
  excerpt text NOT NULL,
  KEY lid (lid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table myReviews_votedata
#

CREATE TABLE myReviews_votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname),
  KEY lid (lid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table myReviews_votecat
#

CREATE TABLE myReviews_votecat (
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) NOT NULL default '0',
  rating tinyint (3) NOT NULL default '0',
  ratingcat tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname),
  KEY lid (lid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table myReviews_reviews
#

CREATE TABLE myReviews_reviews (
  reviewid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  reviewuser int(11) NOT NULL default '0',
  review text NOT NULL,
  reviewhostname varchar(60) NOT NULL default '',
  reviewtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (reviewid),
  KEY reviewuser (reviewuser),
  KEY reviewhostname (reviewhostname),
  KEY lid (lid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table myReviews_editorials
#

CREATE TABLE myReviews_editorials (
  editorialid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  editorialuser int(11) NOT NULL default '0',
  editorial text NOT NULL,
  editorialhostname varchar(60) NOT NULL default '',
  editorialtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (editorialid),
  KEY reviewuser (editorialuser),
  KEY reviewhostname (editorialhostname),
  KEY lid (lid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table myReviews_ratingcat
#

CREATE TABLE myReviews_ratingcat (
  rid int(5) unsigned NOT NULL auto_increment,
  cid int(5) unsigned NOT NULL default '0',
  ratingcat varchar(50) NOT NULL default '',
  PRIMARY KEY  (rid),
  KEY pid (rid),
  KEY cid (cid)
) TYPE=MyISAM;
