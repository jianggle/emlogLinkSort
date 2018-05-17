angular.module('ngRouteApp', ['ngRoute'])
.config(function ($routeProvider) {
    $routeProvider.
    when('/linksort', {
        templateUrl: '../content/plugins/nglinks/tpl/linksort.html',
        controller: 'LinkSortCtrl'
    }).
    when('/linksortedit/:linksort_id', {
        templateUrl: '../content/plugins/nglinks/tpl/linksortedit.html',
        controller: 'LinkSortEditCtrl'
    }).
	when('/links', {
		templateUrl: '../content/plugins/nglinks/tpl/links.html',
		controller: 'LinksCtrl'
	}).
	when('/links/:sortid', {
		templateUrl: '../content/plugins/nglinks/tpl/links.html',
		controller: 'LinksCtrl'
	}).
	when('/linkedit/:id', {
		templateUrl: '../content/plugins/nglinks/tpl/linkedit.html',
		controller: 'LinkEditCtrl'
	}).
    otherwise({
        redirectTo: '/linksort'
    });
})
.service('comTool',[function(){
	/*
	 *将使用jquery的serialize序列化结果转为json对象
	 */
	this.serializeToJSON = function(value){
		value = decodeURIComponent(value);
		var myArr = value.split("&");
		var myObj = {};
		for(var i=0;i<myArr.length;i++){
			var myArr2 = myArr[i].split("=");
			myObj[myArr2[0]] = myArr2[1];
		}
		return myObj;
	}
	/**
	 * length 总页数
	 * current 当前分页
	 * displayLength 显示长度
	 * @return  array[1,2,3,4,5,6,7,8]
	 */
	this.pageNavi = function(current,length,displayLength){
		var indexes = [];  
		var start = Math.round(current - displayLength / 2);  
		var end = Math.round(current + displayLength / 2);  
		if (start <= 1) {  
			start = 1;  
			end = start + displayLength - 1;  
			if (end >= length - 1) {  
				end = length - 1;  
			}  
		}  
		if (end >= length - 1) {  
			end = length ;  
			start = end - displayLength + 1;  
			if (start <= 1) {  
				start = 1;  
			}  
		}  
		for (var i = start; i <= end; i++) {  
			indexes.push(i);  
		}  
		return indexes;
	}
}])
.service('apiService', function($http, $window, $q){
	this.serverUrl = "./plugin.php?plugin=nglinks&";
	this.handleParams = function(params){
		params = params ? params : {};
		return params;
	};
	this.c_get = function(api, params){
		var deferred = $q.defer();
		var allParams = this.handleParams(params);
		var url = this.serverUrl + api;
		$http.get(url, {params:allParams}).then(function successCallback(res){
			deferred.resolve(res.data);
		});
		return deferred.promise;
	};
	//
	this.sortList = function(params){
		return this.c_get("action=linksort",params);
	};
	this.sortTaxis = function(params){
		return this.c_get("action=updateLinkSortTaxis",params);
	};
	this.sortAdd = function(params){
		return this.c_get("action=addLinkSort",params);
	};
	this.sortDelete = function(params){
		return this.c_get("action=deleteLinkSort",params);
	};
	this.sortInfo = function(params){
		return this.c_get("action=mod_linksort",params);
	};
	this.sortEdit = function(params){
		return this.c_get("action=updateSortName",params);
	};
	//
	this.linkList = function(params){
		return this.c_get("action=nglinks",params);
	};
	this.linkTaxis = function(params){
		return this.c_get("action=nglinks_taxis",params);
	};
	this.linkHide = function(params){
		return this.c_get("action=nglinks_hide",params);
	};
	this.linkShow = function(params){
		return this.c_get("action=nglinks_show",params);
	};
	this.linkAdd = function(params){
		return this.c_get("action=nglinks_add",params);
	};
	this.linkDelete = function(params){
		return this.c_get("action=nglinks_delete",params);
	};
	this.linkOperate = function(params){
		return this.c_get("action=nglinks_operate_link",params);
	};
	this.linkInfo = function(params){
		return this.c_get("action=link_detail",params);
	};
	this.linkEdit = function(params){
		return this.c_get("action=link_update",params);
	};
})
.controller("LinkSortCtrl",function($scope,$window,$route,apiService,comTool){
	//列表
	apiService.sortList().then(function(res){
		$scope.linksort = res;
	})
	//添加
	$scope.taxis = '';
	$scope.linksort_name = '';
	$scope.addLinkSort = function(taxis,linksort_name){
		apiService.sortAdd({
			taxis: taxis,
			linksort_name: linksort_name
		}).then(function(res){
			if(res.code == 555){
				$window.alert("名称不能为空哦！")
			}else if(res.code == 666){
				$route.reload();
			}
		});
	};
	//删除
	$scope.deleteLinkSort = function(linksort_id){
		var qr = confirm("确定删除吗？");
		if(qr){
			apiService.sortDelete({
				linksort_id: linksort_id
			}).then(function(res){
				if(res.code == 555){
					$window.alert("分类id未传入！")
				}else if(res.code == 666){
					$route.reload();
				}
			});
		}
	};
	//排序
	$scope.updateLinkSortTaxis = function(){
		apiService.sortTaxis(
			comTool.serializeToJSON($("#linkSortForm").serialize())
		).then(function(res){
			if(res.code == 555){
				$window.alert("失败啦！")
			}else if(res.code == 666){
				$route.reload();
			}
		});
	};
})
//分类编辑修改
.controller("LinkSortEditCtrl",function($scope,$routeParams,$location,$window,apiService){
	$scope.linksort_id = $routeParams.linksort_id;
	apiService.sortInfo({
		linksort_id: $scope.linksort_id
	}).then(function(res){
		$scope.sortinfo = res;
	});
	$scope.updateLinkSortName = function(linksort_id,linksort_name){
		apiService.sortEdit({
			linksort_id: linksort_id,
			linksort_name: linksort_name
		}).then(function(res){
			if(res.code == 555){
				$window.alert("名称不能为空哦！")
			}else if(res.code == 666){
				$location.path("/linksort");
			}
		});
	};
})
//链接
.controller("LinksCtrl",function($scope,$routeParams,$location,$window,$route,comTool,apiService){
	$scope.pageCur = 1;
	$scope.pageAll = 0;
	$scope.pages = [];
	$scope.loadPage = function(page,sortid,keyword){
		var myParams;
		if(keyword){
			myParams = {
				keyword: keyword,
				page: page,
				perpage_num: 16
			}
		}else{
			myParams = {
				linksortid: sortid,
				page: page,
				perpage_num: 16
			}
		}
		apiService.linkList(myParams).then(function(res){
			res.linksort.push({linksort_id:-1,linksort_name:'未分类'})
			$scope.linksInfo = res;
			$scope.pageCur = page;
			$scope.pageAll = res.allPage;
			$scope.pages = comTool.pageNavi($scope.pageCur,$scope.pageAll,5);
		});
	};
	$scope.mysortID = $routeParams.sortid?$routeParams.sortid:'';
	if($scope.mysortID){
		$scope.sortSeeForm = $scope.mysortID;
		$scope.loadPage($scope.pageCur,$scope.mysortID,'');
	}else{
		$scope.loadPage($scope.pageCur,'','');
	};
	//按分类列表
	$scope.chooseSortSee = function(){
		if($scope.sortSeeForm){
			$location.path("/links/"+$scope.sortSeeForm);
		}
	};
	//按关键词（搜索结果）
	$scope.doSearch = function(keyword){
		if(keyword){
			$scope.loadPage($scope.pageCur,'',keyword);
		}
	};
	$scope.updateLinksTaxis = function(){
		apiService.linkTaxis(
			comTool.serializeToJSON($("#linksTaxisForm").serialize())
		).then(function(res){
			if(res.code == 555){
				$window.alert("参数异常！")
			}else if(res.code == 666){
				$route.reload();
			}
		});
	};
	$scope.linkHide = function(linkid){
		apiService.linkHide({linkid: linkid}).then(function(res){
			if(res == 555){
				$window.alert("链接id未传入！")
			}else if(res.code == 666){
				$route.reload();
			}
		});
	};
	$scope.linkShow = function(linkid){
		apiService.linkShow({linkid: linkid}).then(function(res){
			if(res.code == 555){
				$window.alert("链接id未传入！")
			}else if(res.code == 666){
				$route.reload();
			}
		});
	};
	$scope.linkDel = function(linkid){
		var qr = confirm("确定删除吗？");
		if(qr){
			apiService.linkDelete({linkid: linkid}).then(function(res){
				if(res.code == 666){
					$window.alert("删除成功！");
					$route.reload();
				}
			});
		}
	};
	$scope.addLinkInfo = {
		taxis:'',
		linksortid:'',
		sitename:'',
		siteurl:'',
		description:''
	};
	$scope.linkAdd = function(){
		apiService.linkAdd($scope.addLinkInfo).then(function(res){
			if(res.code == 555){
				$window.alert("链接名称或地址不能为空！");
			}else if(res.code == 666){
				$window.alert("添加成功！");
				$route.reload();
			}
		});
	};
	//选择
	$scope.choosedId = [];
	$scope.isChecked = function(id){  
		return $scope.choosedId.indexOf(id) != -1;  
	};
	$scope.updateMyCheck = function($event,id){
		if($event.target.checked){
			$scope.choosedId.push(id);
		}else{
			$scope.choosedId.splice($scope.choosedId.indexOf(id), 1);
		}
	};
	$scope.chooseAll = function(){
		$scope.choosedId = [];
		for(i=0;i<$scope.linksInfo.links.length;i++){
			$scope.choosedId.push($scope.linksInfo.links[i].id);
		}
	};
	$scope.chooseBack = function(){
		if($scope.choosedId.length==0){
			$scope.chooseAll();
		}else if($scope.choosedId.length==$scope.linksInfo.links.length){
			$scope.choosedId = [];
		}else{
			for(i=0;i<$scope.linksInfo.links.length;i++){
				if($scope.choosedId.indexOf($scope.linksInfo.links[i].id) != -1){
					$scope.choosedId.splice($scope.choosedId.indexOf($scope.linksInfo.links[i].id), 1);
				}else{
					$scope.choosedId.push($scope.linksInfo.links[i].id);
				}
			}
		}
	};
	//批量操作：显示/隐藏/删除/移动
	$scope.operateAll = function(type,text){
		if($scope.choosedId.length>0){
			var confirmOperate = confirm(text);
			if(confirmOperate){
				var params = {
					operate: type,
					linkids: $scope.choosedId.toString()
				};
				if(type=='move'){
					params.linksort = $scope.moveForm;
				}
				apiService.linkOperate(params).then(function(res){
					if(res.code == 666){
						$route.reload();
					}
				});
			}
		}else{
			$scope.moveForm = '';
			$window.alert("请先勾选待操作项！");
		}
	};
})
//链接：修改编辑
.controller("LinkEditCtrl",function($scope,$routeParams,$location,apiService){
	$scope.link_id = $routeParams.id;
	$scope.myForm = {
		linkid: $scope.link_id,
		linksortid: '',
		sitename: '',
		siteurl: '',
		description: ''
	};
	apiService.sortList().then(function(res){
		$scope.sortList = res;
		apiService.linkInfo({linkid:$scope.link_id}).then(function(res1){
			$scope.myForm.linksortid = res1.linksortid;
			$scope.myForm.sitename = res1.sitename;
			$scope.myForm.siteurl = res1.siteurl;
			$scope.myForm.description = res1.description;
			for(var i=0;i<res.length;i++){
				if(res[i].linksort_id*1===res1.linksortid*1){
					$scope.selectSort = res[i];
				}
			};
		});
	});
	$scope.countryChange = function(){
		$scope.myForm.linksortid = $scope.selectSort.linksort_id;
	};
	$scope.doSubmit = function(){
		apiService.linkEdit($scope.myForm).then(function(res){
			if(res.code == 666){
				$location.path("/links");
			}
		});
	};
});