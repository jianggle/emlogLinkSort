<?php
/**
 * 链接分类
 * @copyright (c) JiangGle All Rights Reserved
 */

class SortLink_Model {

	private $db;

	function __construct() {
		$this->db = Database::getInstance();
	}

	function updateSortLink($sortLinkData, $linksort_id) {
		$Item = array();
		foreach ($sortLinkData as $key => $data) {
			$Item[] = "$key='$data'";
		}
		$upStr = implode(',', $Item);
		$this->db->query("update ".DB_PREFIX."sortlink set $upStr where linksort_id=$linksort_id");
	}

	function addSortLink($linksort_name, $taxis) {
		$sql="insert into ".DB_PREFIX."sortlink (linksort_name,taxis) values('$linksort_name',$taxis)";
		$this->db->query($sql);
	}

	function deleteSortLink($linksort_id) {
		$this->db->query("update ".DB_PREFIX."link set linksortid=0 where linksortid=$linksort_id");
		$this->db->query("DELETE FROM ".DB_PREFIX."sortlink where linksort_id=$linksort_id");
	}

	function getOneSortLinkById($linksort_id) {
		$sql = "select * from ".DB_PREFIX."sortlink where linksort_id=$linksort_id";
		$res = $this->db->query($sql);
		$row = $this->db->fetch_array($res);
		$sortLinkData = array();
		if ($row) {
			$sortLinkData = array(
					'linksort_name' => htmlspecialchars(trim($row['linksort_name'])),
			);
		}
		return $sortLinkData;
	}

}