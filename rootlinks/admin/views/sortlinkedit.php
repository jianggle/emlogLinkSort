<?php if(!defined('EMLOG_ROOT')) {exit('error!');}?>
<?php if(isset($_GET['error_a'])):?><span class="error">分类名称不能为空</span><?php endif;?>
<div class=containertitle><b>编辑分类</b></div>
<div class=line></div>
<form action="sortlink.php?action=update" method="post">
<div class="item_edit">
	<li><input style="width:200px;" value="<?php echo $linksort_name; ?>" name="linksort_name" id="linksort_name" class="input" /> 名称 <span class="required">*</sapn></li>
	<input type="hidden" value="<?php echo $linksort_id; ?>" name="linksort_id" />
	<input type="submit" value="保 存" class="button" id="save"  />
	<input type="button" value="取 消" class="button" onclick="javascript: window.history.back();" />
    </li>
</div>
</form>
<script>
$("#menu_sortlink").addClass('sidebarsubmenu1');
</script>