<?php
if(!defined('EMLOG_ROOT')) {exit('error!');}
//激活时调用
function callback_init(){
    $db = MySql::getInstance();
    $db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."nglinksort` (
		`linksort_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`linksort_name` varchar(60) NOT NULL DEFAULT '',
		`taxis` int(10) unsigned NOT NULL DEFAULT '0',
        PRIMARY KEY (`linksort_id`)
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
	
    $db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."nglink` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`linksortid` int(10) NOT NULL,
		`sitename` varchar(30) NOT NULL DEFAULT '',
		`siteurl` varchar(75) NOT NULL DEFAULT '',
		`description` varchar(255) NOT NULL DEFAULT '',
		`hide` enum('n','y') NOT NULL DEFAULT 'n',
		`taxis` int(10) unsigned NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
}
//关闭时调用
function callback_rm(){
	
}