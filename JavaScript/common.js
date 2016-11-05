/**
 * jquery 常用插件扩展库
 * @authro JiRY <390066398@qq.com>
 * @desc 依赖Jquery库
 */
define(function(require, exports, module){
	$.extend({
		/**
		 * 常用插件集合
		 */
		common: {
			
			/**
			 * 弹出框
			 */
			openWindow: function(params){
				var params = typeof(params) == 'undefined' ? {} : params;
				
				alert(params.content);
				
//				if( typeof(params.id) != 'undefined' ){
//					$("#"+params.id).dialog({
//						title: '你好',
//						html_title: true,
//						
//					});
//				}
				
			},
			
			/**
			 * 表单ajax提交
			 */
			ajaxFormSubmit: function(){
				
				$(document).find('form').submit(function(e){
					var form 		= $(this); 
					if( form.attr('ajax') == 'true' ){
						
						var ajaxParam = {
								method: $.common.typeCheck(form.attr('method') , 'undefined' , 'GET'),
								url: $.common.typeCheck(form.attr('action') , 'undefined' , ''),
								cache: $.common.typeCheck(form.attr('cache') , 'boolean' , true),
								timeout: $.common.typeCheck(form.attr('timeout') , 'undefined' , 30000),
								async: $.common.typeCheck(form.attr('async') , 'undefined' , true),
								dataType: $.common.typeCheck(form.attr('dataType') , 'undefined' , 'JSON'),
								contentType: $.common.typeCheck(form.attr('contentType') , 'undefined' , 'application/x-www-form-urlencoded'),
								beforeSend: function(XMLHttpRequest){
									var beforeSend_fun = $.common.typeCheck(form.attr('beforeSend') , 'undefined' , null)
									if( beforeSend_fun === null ){
										$.common.beforeSend(XMLHttpRequest);
									}else{
										eval(beforeSend_fun)(XMLHttpRequest);
									}
								},
								complete: function(XMLHttpRequest, textStatus){
									var complete_fun = $.common.typeCheck(form.attr('complete') , 'undefined' , null)
									if( complete_fun === null ){
										$.common.complete(XMLHttpRequest, textStatus);
									}else{
										eval(complete_fun)(XMLHttpRequest, textStatus);
									}
								},
								success: function(data, textStatus){
									var success_fun = $.common.typeCheck(form.attr('success') , 'undefined' , null);
									if( success_fun === null ){
										$.common.success(data, textStatus, form);
									}else{
										eval(success_fun)(data, textStatus, form);
									}
								},
								error: function(XMLHttpRequest, textStatus, errorThrown){
									var error_fun = $.common.typeCheck(form.attr('error') , 'undefined' , null);
									if( error_fun === null ){
										$.common.error(XMLHttpRequest, textStatus, errorThrown);
									}else{
										eval(error_fun)(XMLHttpRequest, textStatus, errorThrown);
									}
								},
								data: form.serializeArray()
							};
						
						//表单数据处理
						var data = {};
						$.each(ajaxParam.data,function(i,item){
							if(typeof(data[item.name]) == 'undefined'){
								data[item.name] = item.value;
								
							}else if(typeof(data[item.name]) == 'string'){
								
								var arr = new Array();
								arr.push(data[item.name]);
								arr.push(item.value);
								
								data[item.name] = arr;
							}else if(typeof(data[item.name]) == 'object'){
								data[item.name].push(item.value);
							}
						});
						
						//替换提交数据
						ajaxParam.data = data;
						
						$.ajax(ajaxParam);
						
						return false;
					}
					
				});
			},
			
			/**
			 * 数据类型检测
			 * @author JiRY
			 * @param all data   						数据源
			 * @param string|object|boolean type 		类型
			 * @param all default_value					默认值
			 * @return string|object|boolean
			 */
			typeCheck: function(data, type, default_value){
				return typeof(data) == type ? default_value : data;
			},
			
			/**
			 * 发送请求前
			 * @param object XMLHttpRequest  响应对象
			 */
			beforeSend: function(XMLHttpRequest){
				$(document).find('body').append('<h3 loading-id="loadding">请求中...</h3>');
			},
			
			/**
			 * 请求完成时
			 * @param object XMLHttpRequest	响应对象
			 * @param string textStatus		响应状态
			 */
			complete: function(XMLHttpRequest, textStatus){
				$(document).find('[loading-id="loadding"]').remove();
			},
			
			/**
			 * 请求成功
			 * @param string|object|boolean data	服务器返回数据
			 * @param string testStatus				响应状态
			 */
			success: function(data, textStatus , form){
				if( typeof(data) == 'object' ){
					if( typeof(data.status) != 'undefined' && parseInt(data.status) == 1){
						//操作成功
						if( typeof(data.msg) != 'undefined' ){
							//提示
							$.common.openWindow({content:data.msg});
							
							if( typeof(form.redirect) != 'undefined' ){
								//3秒后关闭提示并重定向
								setTimeout(function(){
									//关闭提示
									//...
									//跳转
									window.localtion.href = form.redirect;
								},3000);
							}else{
								//3秒钟后关闭提示，且刷新
								setTimeout(function(){
									//关闭提示
									//...
									
									//刷新
									window.localtion.reload();
								},3000);
							}
							
						}
					}else{
						//只提示
						$.common.openWindow({content:JSON.stringify(data)});
					}
				}else if( typeof(data) == 'string' ){
					
				}else if( typeof(data) == 'boolean' ){
					
				}
			},
			
			/**
			 * 请求失败
			 * @param object XMLHttpRequest		响应对象
			 * @param string textStatus			响应状态
			 * @param string errorThrown		错误信息
			 */
			error: function(XMLHttpRequest, textStatus, errorThrown){
				console.error(errorThrown);
				var errorMsg = '';
				switch( textStatus ){
					case 'error':
						errorMsg = '服务器发送错误，请重试！';
						break;
					case 'parsererror':
						errorMsg = '服务器数据解析错误，请重试！';
						break;
				}
				
				$.common.openWindow({content:errorMsg});
			},
			
		}
	});
});