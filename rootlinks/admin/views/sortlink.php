<?php if(!defined('EMLOG_ROOT')) {exit('error!');} ?>
<script>setTimeout(hideActived,2600);</script>
<div class=containertitle><b>链接分类管理</b>
<?php if(isset($_GET['active_taxis'])):?><span class="actived">排序更新成功</span><?php endif;?>
<?php if(isset($_GET['active_del'])):?><span class="actived">删除分类成功</span><?php endif;?>
<?php if(isset($_GET['active_edit'])):?><span class="actived">修改分类成功</span><?php endif;?>
<?php if(isset($_GET['active_add'])):?><span class="actived">添加分类成功</span><?php endif;?>
<?php if(isset($_GET['error_a'])):?><span class="error">分类名称不能为空</span><?php endif;?>
<?php if(isset($_GET['error_b'])):?><span class="error">没有可排序的分类</span><?php endif;?>
</div>
<div class=line></div>
<form  method="post" action="sortlink.php?action=taxis">
<table width="100%" id="adm_sort_list" class="item_list">
<thead>
    <tr>
    <th width="55"><b>序号</b></th>
    <th width="160"><b>名称</b></th>
    <th width="40" class="tdcenter"><b>链接数量</b></th>
    <th width="60">操作</th>
</tr>
</thead>
<tbody>
<?php if($sortlink):foreach($sortlink as $key=>$value):?>
<tr>
    <td>
        <input type="hidden" value="<?php echo $value['linksort_id'];?>" class="sort_id" />
        <input maxlength="4" class="num_input" name="sortlink[<?php echo $value['linksort_id']; ?>]" value="<?php echo $value['taxis']; ?>" />
    </td>
    <td class="sortname">
        <a href="sortlink.php?action=mod_sortlink&linksort_id=<?php echo $value['linksort_id']; ?>"><?php echo $value['linksort_name']; ?></a>
    </td>
    <td class="tdcenter"><a href="./link.php?linksortid=<?php echo $value['linksort_id']; ?>"><?php echo $value['linknum']; ?></a></td>
    <td>
        <a href="sortlink.php?action=mod_sortlink&linksort_id=<?php echo $value['linksort_id']; ?>">编辑</a>
        <a href="javascript: em_confirm(<?php echo $value['linksort_id']; ?>, 'sortlink', '<?php echo LoginAuth::genToken(); ?>');" class="care">删除</a>
    </td>
</tr>
<?php endforeach;else:?><tr><td class="tdcenter" colspan="8">还没有添加分类</td></tr><?php endif;?>  
</tbody>
</table>
<div class="list_footer"><input type="submit" value="改变排序" class="button" /></div>
</form>

<form action="sortlink.php?action=add" method="post">
<div style="margin:30px 0px 10px 0px;"><a href="javascript:displayToggle('sortlink_new', 2);">添加分类+</a></div>
<div id="sortlink_new" class="item_edit">
    <li><input maxlength="4" style="width:30px;" name="taxis" class="input"/> 序号</li>
	<li><input maxlength="200" style="width:243px;" class="input" name="linksort_name" id="linksort_name"/>名称<span class="required">*</sapn></li>
    <input name="token" id="token" value="<?php echo LoginAuth::genToken(); ?>" type="hidden" />
	<li><input type="submit" id="addsort" value="添加新分类" class="button"/></li>
</div>
</form>
<script>
$("#sortlink_new").css('display', $.cookie('em_sortlink_new') ? $.cookie('em_sortlink_new') : 'none');
$(document).ready(function(){
	$("#adm_sort_list tbody tr:odd").addClass("tralt_b");
	$("#adm_sort_list tbody tr")
	.mouseover(function(){$(this).addClass("trover")})
	.mouseout(function(){$(this).removeClass("trover")});
	$("#menu_sortlink").addClass('sidebarsubmenu1');
});
</script>