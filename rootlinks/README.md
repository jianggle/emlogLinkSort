# 方案二
emlog友链分类管理，基于emlog5.3.1（需更改源代码）

使用说明：http://www.eqifei.net/emlog-link-sort.html

## 1.数据库改动

  a.添加表“表前缀_sortlink”并增加以下字段

  linksort_id（主键） 类型int(10)

  linksort_name 类型varchar(50)

  taxis 类型int(10)

  b.在原有的“表前缀_link”表中增加以下字段

  linksortid 类型int(10)

## 2.添加文件

  a. admin/sortlink.php

  b. admin/views/sortlink.php

  c. admin/views/sortlinkedit.php

  d. include/model/sortlink_model.php

## 3.修改文件

  a. admin/link.php

  b. admin/views/header.php

  c. admin/views/links.php

  d. admin/views/linkedit.php

  e. include/model/link_model.php

  f. include/lib/cache.php