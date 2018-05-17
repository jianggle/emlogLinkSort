<?php
//获取所有链接分类
function getLinkSort(){
	global $CACHE;
	$sortlink_cache = $CACHE->readCache('sortlink'); ?>
	<?php foreach($sortlink_cache as $value):?>
<li sid="<?php echo $value['linksort_id']; ?>"><?php echo $value['linksort_name']; ?></li>
	<?php endforeach; ?>
<?php }?>

<?php
//按分类显示所有链接
function sortLinks(){
	$db = MySql::getInstance();
	global $CACHE;
	$sortlink_cache = $CACHE->readCache('sortlink');
	foreach($sortlink_cache as $value){
		$out .= '<dl id="item'.$value['linksort_id'].'"><dt>'.$sortlink_cache[$value['linksort_id']]['linksort_name'].'</dt><ul>';
		$links = $db->query ("SELECT * FROM ".DB_PREFIX."link WHERE linksortid='$value[linksort_id]' AND hide='n' order by id DESC");
		while ($row = $db->fetch_array($links)){
			$out .='<li><a href="'.$row['siteurl'].'" title="'.$row['description'].'" target="_blank">'.$row['sitename'].'</a></li>';
		}
		$out .='</ul></dl>';
	}
	echo $out;
}?>

<?php
//获取指定分类链接
function getOneSortLink($num) {
    $db = MySql::getInstance();
    $sql = "SELECT * FROM ".DB_PREFIX."link WHERE linksortid='1' and hide='n' LIMIT 0,$num";
    $list = $db->query($sql);
    while($row = $db->fetch_array($list)){ ?>
    <a href="<?php echo $row['siteurl']; ?>" target="_blank"><?php echo $row['sitename']; ?></a>
<?php } ?>
<?php } ?>

<?php
//其他使用方式可按需发掘
?>