<?php
!defined('EMLOG_ROOT') && exit('access deined!');
header('Content-type:text/json');
//header('Access-Control-Allow-Origin:*');//指定允许其他域名访问
//header('Access-Control-Allow-Methods:POST');//响应类型
//header('Access-Control-Allow-Headers:x-requested-with,content-type');//响应头设置
require_once 'nglinks_model.php';
$a = isset($_GET['a']) ? addslashes($_GET['a']) : '';
	//分类：列表
	if ($a == 'linksort') {
		$linksort = getLinkSort();
		exit(json_encode($linksort));
	}else if ($a == 'links') {
		$links = getJgLinksForJson();
		exit(json_encode($links));
	}else{
		exit("there is nothing...");
	}

//echo "there is nothing...";