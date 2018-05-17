<?php if(!defined('EMLOG_ROOT')) {exit('error!');} ?>
<div class=containertitle><b>友情链接管理</b>
<?php if(isset($_GET['active_taxis'])):?><span class="actived">排序更新成功</span><?php endif;?>
<?php if(isset($_GET['active_del'])):?><span class="actived">删除成功</span><?php endif;?>
<?php if(isset($_GET['active_edit'])):?><span class="actived">修改成功</span><?php endif;?>
<?php if(isset($_GET['active_add'])):?><span class="actived">添加成功</span><?php endif;?>
<?php if(isset($_GET['error_a'])):?><span class="error">站点名称和地址不能为空</span><?php endif;?>
<?php if(isset($_GET['error_b'])):?><span class="error">没有可排序的链接</span><?php endif;?>
<?php if(isset($_GET['error_select_a'])):?><span class="error">请选择要处理的文章</span><?php endif;?>
<?php if(isset($_GET['error_select_b'])):?><span class="error">请选择要执行的操作</span><?php endif;?>
<?php if(isset($_GET['active_del_select'])):?><span class="actived">批量删除成功</span><?php endif;?>
<?php if(isset($_GET['active_hide_select'])):?><span class="actived">批量隐藏成功</span><?php endif;?>
<?php if(isset($_GET['active_show_select'])):?><span class="actived">批量显示成功</span><?php endif;?>
<?php if(isset($_GET['active_move_select'])):?><span class="actived">批量移动分类成功</span><?php endif;?>
</div>
<div class=line></div>
<div class="filters">
<div id="f_title">
	<div style="float:left; margin-top:8px;">
		<span <?php echo !$linksortid && !$keyword ? "class=\"filter\"" : ''; ?>><a href="./link.php">全部</a></span>
        <span id="f_t_sort">
        <select name="bysort" id="bysort" onChange="selectSort(this);" style="width:110px;">
            <option value="" selected="selected">按分类查看...</option>
            <?php foreach($sortlink as $key=>$value):$flg = $value['linksort_id'] == $linksortid ? 'selected' : '';?>
            <option value="<?php echo $key; ?>" <?php echo $flg; ?>><?php echo $value['linksort_name']; ?></option>
            <?php endforeach; ?>
            <option value="-1" <?php if($linksortid == -1) echo 'selected'; ?>>未分类</option>
        </select>
        </span>
	</div>
	<div style="float:right;">
		<form action="link.php" method="get">
		<input type="text" id="input_s" name="keyword">
		</form>
	</div>
	<div style="clear:both"></div>
</div>
</div>
<form action="link.php?action=operate_link" method="post" name="form_link" id="form_link">
  <table width="100%" id="adm_link_list" class="item_list">
    <thead>
      <tr>
        <th width="21"><b>选</b></th>
	  	<th width="50"><b>序号</b></th>
        <th width="230"><b>链接</b></th>
        <th width="80" class="tdcenter"><b>状态</b></th>
        <th width="80" class="tdcenter"><b>分类</b></th>
		<th width="80" class="tdcenter"><b>查看</b></th>
		<th width="299"><b>描述</b></th>
        <th width="100"></th>
      </tr>
    </thead>
    <tbody>
	<?php 
	if($links):
	foreach($links as $key=>$value):
	$linkSortName = ($value['linksortid'] == -1 || $value['linksortid'] == 0) && !array_key_exists($value['linksortid'], $sortlink) ? '未分类' : $sortlink[$value['linksortid']]['linksort_name'];
	doAction('adm_link_display');
	?>  
      <tr>
      	<td width="21"><input type="checkbox" name="linkids[]" value="<?php echo $value['id']; ?>" class="ids" /></td>
		<td><input class="num_input" name="link[<?php echo $value['id']; ?>]" value="<?php echo $value['taxis']; ?>" maxlength="4" /></td>
		<td><a href="link.php?action=mod_link&amp;linkid=<?php echo $value['id']; ?>" title="修改链接"><?php echo $value['sitename']; ?></a></td>
		<td class="tdcenter">
		<?php if ($value['hide'] == 'n'): ?>
		<a href="link.php?action=hide&amp;linkid=<?php echo $value['id']; ?>" title="点击隐藏链接">显示</a>
		<?php else: ?>
		<a href="link.php?action=show&amp;linkid=<?php echo $value['id']; ?>" title="点击显示链接" style="color:red;">隐藏</a>
		<?php endif;?>
		</td>
        <td><?php echo $linkSortName; ?></td>
		<td class="tdcenter">
	  	<a href="<?php echo $value['siteurl']; ?>" target="_blank" title="查看链接">
	  	<img src="./views/images/vlog.gif" align="absbottom" border="0" /></a>
	  	</td>
        <td><?php echo $value['description']; ?></td>
        <td>
        <a href="link.php?action=mod_link&amp;linkid=<?php echo $value['id']; ?>">编辑</a>
        <a href="javascript: em_confirm(<?php echo $value['id']; ?>, 'link', '<?php echo LoginAuth::genToken(); ?>');" class="care">删除</a>
        </td>
      </tr>
	<?php endforeach;else:?>
	  <tr><td class="tdcenter" colspan="8">还没有添加链接</td></tr>
	<?php endif;?>
    </tbody>
  </table>
<input type="hidden" name="linksortid" id="linksortid" value="<?php echo $linksortid; ?>" />
<input name="token" id="token" value="<?php echo LoginAuth::genToken(); ?>" type="hidden" />
<input name="operate" id="operate" value="" type="hidden" />
<div class="list_footer">
<a href="javascript:void(0);" id="select_order">改变排序</a> | 
	<a href="javascript:void(0);" id="select_all">全选</a> 选中项：
    <a href="javascript:linkact('del');" class="care">删除</a> | 
	<a href="javascript:linkact('hide');">隐藏</a> | 
    <a href="javascript:linkact('show');">显示</a> | 
	<select name="linksort" id="linksort" onChange="changeLinkSort(this);" style="width:110px;">
	<option value="" selected="selected">移动到分类...</option>
    <?php foreach($sortlink as $key=>$value):?>
    <option value="<?php echo $value['linksort_id']; ?>"><?php echo $value['linksort_name']; ?></option>
	<?php endforeach;?>
	<option value="-1">未分类</option>
	</select>
</div>
</form>
<div class="page"><?php echo $pageurl; ?> (报告大人，共有<?php echo $linkNum; ?>个链接)</div>
<form action="link.php?action=addlink" method="post" name="link" id="link">
<div style="margin:30px 0px 10px 0px;"><a href="javascript:displayToggle('link_new', 2);">添加链接+</a></div>
<div id="link_new" class="item_edit">
	<li><input maxlength="4" style="width:30px;" class="input" name="taxis" /> 序号</li>
    <li>
    <select name="linksortid" id="linksortid" class="input">
        <option value="-1">未分类</option>
        <?php foreach($sortlink as $key=>$value):?>
        <option value="<?php echo $key; ?>"><?php echo $value['linksort_name']; ?></option>
        <?php endforeach; ?>
    </select>选择分类
    </li>
	<li><input maxlength="200" style="width:232px;" class="input" name="sitename" /> 名称<span class="required">*</sapn></li>
	<li><input maxlength="200" style="width:232px;" class="input" name="siteurl" /> 地址<span class="required">*</sapn></li>
	<li>描述</li>
	<li><textarea name="description" type="text" class="textarea" style="width:230px;height:60px;overflow:auto;"></textarea></li>
	<li><input type="submit" name="" value="添加链接"  /></li>
</div>
</form>
<script>
$("#link_new").css('display', $.cookie('em_link_new') ? $.cookie('em_link_new') : 'none');
$(document).ready(function(){
	$("#adm_link_list tbody tr:odd").addClass("tralt_b");
	$("#adm_link_list tbody tr")
		.mouseover(function(){$(this).addClass("trover")})
		.mouseout(function(){$(this).removeClass("trover")})
});
setTimeout(hideActived,2600);
$("#menu_link").addClass('sidebarsubmenu1');
$("#select_all").toggle(function () {$(".ids").attr("checked", "checked");},function () {$(".ids").removeAttr("checked");});
function linkact(act){
	if (getChecked('ids') == false) {
		alert('请选择要操作的链接');
		return;}
	if(act == 'del' && !confirm('你确定要删除所选链接吗？')){return;}
	$("#operate").val(act);
	$("#form_link").submit();
}
function changeLinkSort(obj) {
	if (getChecked('ids') == false) {
		alert('请选择要操作的链接');
		return;}
	if($('#linksort').val() == '')return;
	$("#operate").val('move');
	$("#form_link").submit();
}
$("#select_order").click(function(){
	$("#form_link").attr("action","link.php?action=link_taxis");
	$("#form_link").submit();
})
function selectSort(obj) {
    window.open("./link.php?linksortid=" + obj.value, "_self");
}
</script>