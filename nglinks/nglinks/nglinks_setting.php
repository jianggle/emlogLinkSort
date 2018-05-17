<?php
!defined('EMLOG_ROOT') && exit('access deined!');
function plugin_setting_view(){?>
<script src="<?php echo BLOG_URL;?>content/plugins/nglinks/js/angular.min.js" type="text/javascript"></script>
<script src="<?php echo BLOG_URL;?>content/plugins/nglinks/js/angular-route.min.js"></script>
<script src="<?php echo BLOG_URL;?>content/plugins/nglinks/js/nglinks.js"></script>
<style>
a{cursor:pointer}
.item_list tbody tr:nth-child(even){background-color:#f7f7f7}
</style>
<div ng-app="ngRouteApp">
<div ng-view=""></div>
</div>
<?php }?>