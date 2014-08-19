create table app_contents (
	cid int primary key auto_increment,
	title varchar(200),
	created int unsigned default 0,
	modified int unsigned default 0,
	text text,
	author_id int
);

CREATE TABLE `app_metas` (
   `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `name` varchar(200) DEFAULT NULL,
   `type` varchar(32) NOT NULL,
   `description` varchar(200) DEFAULT NULL,
   `count` int(10) unsigned DEFAULT '0',
   `meta_order` int(10) unsigned DEFAULT '0',
   PRIMARY KEY (`mid`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `app_relationships` (
   `cid` int(10) unsigned NOT NULL,
   `mid` int(10) unsigned NOT NULL,
   PRIMARY KEY (`cid`,`mid`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `app_comments` (
   `coid` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `cid` int(10) unsigned DEFAULT '0',
   `created` int(10) unsigned DEFAULT '0',
   `text` text,
   `type` varchar(16) DEFAULT 'comment',
   `status` varchar(16) DEFAULT 'approved',
   `parent` int(10) unsigned DEFAULT '0',
   PRIMARY KEY (`coid`),
   KEY `cid` (`cid`),
   KEY `created` (`created`)
 ) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

CREATE TABLE `app_users` (
   `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `name` varchar(32) DEFAULT NULL,
   `password` varchar(64) DEFAULT NULL,
   `mail` varchar(200) DEFAULT NULL,
   `url` varchar(200) DEFAULT NULL,
   `screenName` varchar(32) DEFAULT NULL,
   `created` int(10) unsigned DEFAULT '0',
   `activated` int(10) unsigned DEFAULT '0',
   PRIMARY KEY (`uid`),
   UNIQUE KEY `name` (`name`),
   UNIQUE KEY `mail` (`mail`)
 ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;