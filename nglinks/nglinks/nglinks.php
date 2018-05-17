<?php
/*
Plugin Name: 恋链不舍
Version: 1.0
Plugin URL：http://www.eqifei.net
Description: 此插件链接与官方系统链接不互通，不互通，不互通！！！
Author: JiangGle
Author URL: http://www.eqifei.net
*/
if(!defined('EMLOG_ROOT')) {exit('error!');}
require_once 'nglinks_model.php';
$action = isset($_GET['action']) ? addslashes($_GET['action']) : '';
//挂载到侧栏菜单
function nglinks_menu(){
	echo '<div class="sidebarsubmenu" id="nglinks_menu1"><a href="./plugin.php?plugin=nglinks#/linksort">恋链不舍的分类</a></div>';
	echo '<div class="sidebarsubmenu" id="nglinks_menu2"><a href="./plugin.php?plugin=nglinks#/links">恋链不舍的链接</a></div>';
}
addAction('adm_sidebar_ext', 'nglinks_menu');
	//判断登录
	if (ISLOGIN === false) {
		$action = '';
	}
	//分类：列表
	if ($action == 'linksort') {
		$linksort = getLinkSort();
		exit(json_encode($linksort));
	}
	//分类：详情
	if ($action== "mod_linksort") {
		$linksort_id = isset($_GET['linksort_id']) ? intval($_GET['linksort_id']) : '';
		$sort = getOneLinkSort($linksort_id);
		exit(json_encode($sort));
	}
	//分类：修改
	if ($action=='updateSortName') {
		$linksort_name = isset($_GET['linksort_name']) ? addslashes($_GET['linksort_name']) : '';
		$linksort_id = isset($_GET['linksort_id']) ? intval($_GET['linksort_id']) : '';
		if (empty($linksort_name) || empty($linksort_id)) {
			exit(json_encode(array("code"=>"555")));
		}else{
			updateSortName($linksort_id, $linksort_name);
			exit(json_encode(array("code"=>"666")));
		}
	}
	//分类：删除
	if ($action== 'deleteLinkSort') {
		$linksort_id = isset($_GET['linksort_id']) ? intval($_GET['linksort_id']) : '';
		if (empty($linksort_id)) {
			exit(json_encode(array("code"=>"555")));
		}else{
			deleteLinkSort($linksort_id);
			exit(json_encode(array("code"=>"666")));
		}
	}
	//分类：添加
	if ($action== 'addLinkSort') {
		$linksort_name = isset($_GET['linksort_name']) ? addslashes($_GET['linksort_name']) : '';
		$taxis = isset($_GET['taxis']) ? intval($_GET['taxis']) : '';
		if (empty($linksort_name)) {
			exit(json_encode(array("code"=>"555")));
		}else{
			addLinkSort($linksort_name, $taxis);
			exit(json_encode(array("code"=>"666")));
		}
	}
	//分类：排序
	if ($action== 'updateLinkSortTaxis') {
		$linksort = isset($_GET['linksort']) ? $_GET['linksort'] : '';
		if (!empty($linksort)) {
			foreach ($linksort as $key=>$value) {
				$value = intval($value);
				$key = intval($key);
				updateLinkSortTaxis(array('taxis'=>$value), $key);
			}
			exit(json_encode(array("code"=>"666")));
		} else{
			exit(json_encode(array("code"=>"555")));
		}
	}
/*
 *links
 */
	if ($action == 'nglinks') {
		$linksortid = isset($_GET['linksortid']) ? intval($_GET['linksortid']) : '';
		$keyword = isset($_GET['keyword']) ? addslashes($_GET['keyword']) : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$perpage_num = isset($_GET['perpage_num']) ? intval($_GET['perpage_num']) : 3;
		$sqlSegment = '';
		if ($linksortid) {
			$sqlSegment = "WHERE linksortid=$linksortid";
		} elseif ($keyword) {
			$sqlSegment = "WHERE sitename like '%$keyword%'";
		}
		$linkNum = getJgLinkNum($sqlSegment);
		$links = getJgLinksForAdmin($sqlSegment, $page, $perpage_num);
		$linksort = getLinkSort();
		exit(json_encode(array("linksort"=>$linksort,"links"=>$links,"linknum"=>$linkNum,"allPage"=>ceil($linkNum/$perpage_num))));
	}
	//链接：添加
	if ($action== 'nglinks_add') {
		$taxis = isset($_GET['taxis']) ? intval(trim($_GET['taxis'])) : 0;
		$linksortid = isset($_GET['linksortid']) ? addslashes(trim($_GET['linksortid'])) : '';
		$sitename = isset($_GET['sitename']) ? addslashes(trim($_GET['sitename'])) : '';
		$siteurl = isset($_GET['siteurl']) ? addslashes(trim($_GET['siteurl'])) : '';
		$description = isset($_GET['description']) ? addslashes(trim($_GET['description'])) : '';
		if ($sitename =='' || $siteurl =='') {
			exit(json_encode(array("code"=>"555")));
		}else{
			if (!preg_match("/^http|ftp.+$/i", $siteurl)) {
				$siteurl = 'http://'.$siteurl;
			}
			addJgLink($linksortid, $sitename, $siteurl, $description, $taxis);
			exit(json_encode(array("code"=>"666")));
		}
	}
	//链接：显示
	if ($action == 'nglinks_hide') {
		$linkId = isset($_GET['linkid']) ? intval($_GET['linkid']) : '';
		if (empty($linkId)) {
			exit(json_encode(array("code"=>"555")));
		}else{
			updateJgLink(array('hide'=>'y'), $linkId);
			exit(json_encode(array("code"=>"666")));
		}
	}
	//链接：隐藏
	if ($action == 'nglinks_show') {
		$linkId = isset($_GET['linkid']) ? intval($_GET['linkid']) : '';
		if (empty($linkId)) {
			exit(json_encode(array("code"=>"555")));
		}else{
			updateJgLink(array('hide'=>'n'), $linkId);
			exit(json_encode(array("code"=>"666")));
		}
	}
	//
	/*if ($action == 'nglinks_getSortName') {
		$linksort_id = isset($_GET['linksort_id']) ? intval($_GET['linksort_id']) : '';
		if (empty($linksort_id)) {
			exit(json_encode(array("code"=>"555")));
		}else{
			$linksort_name = getOneLinkSortName($linksort_id);
			exit(json_encode(array("code"=>"666","linksort_name"=>$linksort_name)));
		}
	}*/
	//链接：删除
	if ($action == 'nglinks_delete') {
		$linkid = isset($_GET['linkid']) ? intval($_GET['linkid']) : '';
		if (empty($linkid)) {
			exit(json_encode(array("code"=>"555")));
		}else{
			deleteJgLink($linkid);
			exit(json_encode(array("code"=>"666")));
		}
	}
	//链接：详情
	if ($action== 'link_detail') {
		$linkId = isset($_GET['linkid']) ? intval($_GET['linkid']) : '';
		$linkData = getLinkDetail($linkId);
		exit(json_encode($linkData));
	}
	//链接：修改更新
	if ($action=='link_update') {
		$linksortid = isset($_GET['linksortid']) ? addslashes(trim($_GET['linksortid'])) : '';
		$sitename = isset($_GET['sitename']) ? addslashes(trim($_GET['sitename'])) : '';
		$description = isset($_GET['description']) ? addslashes(trim($_GET['description'])) : '';
		$linkId = isset($_GET['linkid']) ? intval($_GET['linkid']) : '';
		
		$siteurl = isset($_GET['siteurl']) ? addslashes(trim($_GET['siteurl'])) : '';
		if (!preg_match("/^http|ftp.+$/i", $siteurl)) {
			$siteurl = 'http://'.$siteurl;
		}
		
		updateJgLink(array('linksortid'=>$linksortid, 'sitename'=>$sitename, 'siteurl'=>$siteurl, 'description'=>$description), $linkId);
		exit(json_encode(array("code"=>"666")));
	}
	//链接：排序
	if ($action== 'nglinks_taxis') {
		$link = isset($_GET['link']) ? $_GET['link'] : '';
		//$linksortid = isset($_POST['linksortid']) ? $_POST['linksortid'] : '';
		if (!empty($link)) {
			foreach ($link as $key=>$value) {
				$value = intval($value);
				$key = intval($key);
				updateJgLink(array('taxis'=>$value), $key);
			}
			exit(json_encode(array("code"=>"666")));
		} else {
			exit(json_encode(array("code"=>"555")));
		}
	}
	//批量操作
	if ($action == 'nglinks_operate_link') {
		$operate = isset($_GET['operate']) ? $_GET['operate'] : '';
		$linkids = isset($_GET['linkids']) ? addslashes(trim($_GET['linkids'])) : '';
		$linksort = isset($_GET['linksort']) ? intval($_GET['linksort']) : '';
		//$linksortid = isset($_GET['linksortid']) ? $_GET['linksortid'] : '';
		switch ($operate) {
			case 'del':
				$linkidsArr = explode(',', $linkids);
				foreach ($linkidsArr as $val){
					deleteJgLink($val);
				}
				exit(json_encode(array("code"=>"666")));
				break;
			case 'hide':
				$linkidsArr = explode(',', $linkids);
				foreach ($linkidsArr as $val){
					switchJgLink($val, 'y');
				}
				exit(json_encode(array("code"=>"666")));
				break;
			case 'show':
				$linkidsArr = explode(',', $linkids);
				foreach ($linkidsArr as $val){
					switchJgLink($val, 'n');
				}
				exit(json_encode(array("code"=>"666")));
				break;
			case 'move':
				$linkidsArr = explode(',', $linkids);
				foreach ($linkidsArr as $val){
					updateJgLink(array('linksortid'=>$linksort), $val);
				}
				exit(json_encode(array("code"=>"666")));
				break;
		}
	}