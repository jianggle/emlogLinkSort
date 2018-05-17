<?php
if(!defined('EMLOG_ROOT')) {exit('error!');}
	//分类：列表
	function getLinkSort() {
		global $tables;
		$DB = MySql::getInstance();
		$linksort = array();
		$query = $DB->query("select linksort_id,linksort_name,taxis from ".DB_PREFIX."nglinksort order by taxis ASC");
		while ($row = $DB->fetch_array($query)) {
			$row['linksort_name'] = htmlspecialchars($row['linksort_name']);
			$row['linksort_id'] = intval($row['linksort_id']);
			$row['taxis'] = intval($row['taxis']);
			$row['num'] = getJgLinkNum($condition = "WHERE linksortid in (".$row['linksort_id'].")");
			$linksort[] = $row;
		}
		return $linksort;
	}
	//分类：详情
	function getOneLinkSort($linksort_id) {
		global $tables;
		$DB = MySql::getInstance();
		$sort = array();
		$row = $DB->once_fetch_array("SELECT linksort_id,linksort_name FROM ".DB_PREFIX."nglinksort WHERE linksort_id=$linksort_id");
		$sort['linksort_name'] = htmlspecialchars(trim($row['linksort_name']));
		$sort['linksort_id'] = intval($row['linksort_id']);
		return $sort;
	}
	//分类：根据id查name
	function getOneLinkSortName($linksort_id) {
		global $tables;
		$DB = MySql::getInstance();
		$row = $DB->once_fetch_array("SELECT linksort_name FROM ".DB_PREFIX."nglinksort WHERE linksort_id=$linksort_id");
		$sortname = htmlspecialchars(trim($row['linksort_name']));
		if($linksort_id == -1){
			$sortname = "未分类";
		}else if($linksort_id == 0){
			$sortname = "身份不明";
		}
		return $sortname;
	}
	//分类：编辑
	function updateSortName($linksort_id, $linksort_name) {
		global $tables;
		$DB = MySql::getInstance();
		$sql="UPDATE ".DB_PREFIX."nglinksort SET linksort_name='$linksort_name' WHERE linksort_id=$linksort_id";
		$DB->query($sql);
	}
	//分类：删除
	function deleteLinkSort($linksort_id) {
		global $tables;
		$DB = MySql::getInstance();
		$DB->query("DELETE FROM ".DB_PREFIX."nglinksort where linksort_id=$linksort_id");
	}
	//分类：添加
	function addLinkSort($linksort_name, $taxis) {
		global $tables;
		$DB = MySql::getInstance();
		$DB->query("insert into ".DB_PREFIX."nglinksort (linksort_name,taxis) values('$linksort_name','$taxis')");
	}
	//分类：排序
	function updateLinkSortTaxis($linkSortData, $linksort_id) {
		global $tables;
		$DB = MySql::getInstance();
		$Item = array();
		foreach ($linkSortData as $key => $data) {
			$Item[] = "$key='$data'";
		}
		$upStr = implode(',', $Item);
		$DB->query("update ".DB_PREFIX."nglinksort set $upStr where linksort_id=$linksort_id");
	}
	//统计某个分类下的数量
	function getJgLinkNum($condition = '') {
		global $tables;
		$DB = MySql::getInstance();
        $data = $DB->once_fetch_array("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "nglink $condition");
		return $data['total'];
	}
/*
 *links
 */
	//链接：显示/隐藏/修改/移动/排序
	function updateJgLink($linkData, $linkId) {
		global $tables;
		$DB = MySql::getInstance();
		$Item = array();
		foreach ($linkData as $key => $data) {
			$Item[] = "$key='$data'";
		}
		$upStr = implode(',', $Item);
		$DB->query("update ".DB_PREFIX."nglink set $upStr where id=$linkId");
	}
	//链接：添加
	function addJgLink($linksortid, $name, $url, $des, $taxis) {
		global $tables;
		$DB = MySql::getInstance();
		if ($taxis > 30000 || $taxis < 0) {
			$taxis = 0;
		}
		$sql="insert into ".DB_PREFIX."nglink (linksortid,sitename,siteurl,description,taxis) values('$linksortid','$name','$url','$des', $taxis)";
		$DB->query($sql);
	}
	//链接：详情
	function getLinkDetail($linkId) {
		global $tables;
		$DB = MySql::getInstance();
		$sql = "select * from ".DB_PREFIX."nglink where id=$linkId ";
		$res = $DB->query($sql);
		$row = $DB->fetch_array($res);
		$linkData = array();
		if ($row) {
			$linkData = array(
			'linksortid' => htmlspecialchars(trim($row['linksortid'])),
			'sitename' => htmlspecialchars(trim($row['sitename'])),
			'siteurl' => htmlspecialchars(trim($row['siteurl'])),
			'description' => htmlspecialchars(trim($row['description']))
			);
		}
		return $linkData;
	}
	//链接：删除
	function deleteJgLink($linkId) {
		global $tables;
		$DB = MySql::getInstance();
		$DB->query("DELETE FROM ".DB_PREFIX."nglink where id=$linkId");
	}
	//链接：显隐开关
	function switchJgLink($linkId, $state) {
		global $tables;
		$DB = MySql::getInstance();
		$DB->query("UPDATE " . DB_PREFIX . "nglink SET hide='$state' WHERE id=$linkId");
	}
	//链接：列表
	function getJgLinksForAdmin($condition = '', $page = 1, $perpage_num = 2) {
		global $tables;
		$DB = MySql::getInstance();
		$start_limit = !empty($page) ? ($page - 1) * $perpage_num : 0;
		$limit = "LIMIT $start_limit, " . $perpage_num;
		$sql = "SELECT * FROM " . DB_PREFIX . "nglink $condition order by taxis ASC,id DESC $limit";
		
		$res = $DB->query($sql);
		$links = array();
		while ($row = $DB->fetch_array($res)) {
			$row['sitename'] = htmlspecialchars($row['sitename']);
			$row['description'] = subString(htmlClean($row['description'], false),0,80);
			$row['siteurl'] = $row['siteurl'];
			$row['linksortname'] = getOneLinkSortName($row['linksortid']);
			$links[] = $row;
		}
		return $links;
	}
	//链接：前台列表
	function getJgLinksForJson() {
		global $tables;
		$DB = MySql::getInstance();
		$links = array();
		$linksort = getLinkSort();
		foreach($linksort as $value){
			$links_one = array();
			$links_one['sort_id'] = $value['linksort_id'];
			$links_one['sort_name'] = $value['linksort_name'];
			$links_one['list'] = array();
			$linksql = $DB->query ("SELECT * FROM ".DB_PREFIX."nglink WHERE linksortid='$value[linksort_id]' AND hide='n' order by id DESC");
			while ($row = $DB->fetch_array($linksql)){
				$row['sitename'] = htmlspecialchars($row['sitename']);
				$row['description'] = subString(htmlClean($row['description'], false),0,80);
				$row['siteurl'] = $row['siteurl'];
				$row['linksortname'] = getOneLinkSortName($row['linksortid']);
				$links_one['list'][] = $row;
			}
			$links[] = $links_one;
		}
		return $links;
	}