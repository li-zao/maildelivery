{%include file="header.php"%}
<script type="text/javascript" src="/dist/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/dist/js/searchmytemplpage.js"></script>
<script type="text/javascript" src="/dist/js/vendor/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/dist/js/vendor/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="/dist/js/vendor/jquery.fileupload.js"></script>
<script type="text/javascript" src="/dist/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/dist/js/jPages.js"></script>
<style type="text/css">
  .col{color:red;}
  .holder {
    margin: 15px 0;
  }
  .holder a {
	background: none repeat scroll 0 0 #F5F5F5;
    border-color: #ddd;
    border-style: solid;
    border-width: 1px;
    color: #333333;
    display: inline-block;
    font-size: 12px;
    line-height: 16px;
    padding: 4px 10px !important;
    text-shadow: 0 1px 0 #FFFFFF;
	margin: 0 10px 0 0px;
  }
  .holder a:hover {
    background-color: #E8E8E8;
    color: #222222;
  }
  .holder a.jp-first {border-radius: 4px 0 0 4px;}
  .holder a.jp-previous { margin-right: 10px; }
  .holder a.jp-current, a.jp-current:hover {
	background-color: #26B779;color: #FFFFFF;
	width: 20px;
    font-weight: bold;
	text-align:center;
	margin-right: 10px;
  }
  .holder a.jp-last {border-radius: 0 4px 4px 0;margin-left: 0px;}
  .holder a.jp-current, a.jp-current:hover,
  .holder a.jp-disabled, a.jp-disabled:hover {
    cursor: default;
    
  }
  .holder span { margin: 0 5px; }
  </style>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 投递任务管理</a><a href="#" class="current">创建向导任务</a></div>
  </div>
  <div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"><i class="icon-inbox"></i></span>
						<h5>内容设计</h5>		
					</div>
					<div class="widget-content">
						<table class="table table-striped">
							 <tbody>
								<tr>
									<td style="text-align: left;padding: 8px 0;border:0;font-weight: bold;">
										<span style="float: left;padding-top: 4px;">内容来源：</span>
										<!--获取网络模板-->
										<a style="width: 10%;height: 25px;display: block;background: #e6e6e6;border: 1px dashed #acacae;border-radius: 5px;font-size: 12px;line-height: 25px;text-align: center;padding: 0px 5px; float: left;margin-right: 9px;" href="#load_site" data-toggle="modal">网站获取</a>
										<div id="load_site" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:42%;">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel">获取网络模板</h3>
												</div>
												<div class="modal-body">					
													<input type="text" name="webURL" value="http://" id="focusedInput" style="width:430px;height:30px;border:1px solid #A9A9A9;border-radius: 3px;margin-left: 40px;">
												</div>										
												<div class="modal-footer">
													<button id="reads" type="button" class="btn" style="margin-right: 10px;">确认</button>
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>
										</div>
										<!--网页上传-->
										<a style="width: 10%;height: 25px;display: block;background: #e6e6e6;border: 1px dashed #acacae;border-radius: 5px;font-size: 12px;line-height: 25px;text-align: center;padding: 0 5px;float: left;margin-right: 9px;" href="#load_file" data-toggle="modal">上传网页</a>
										<div id="load_file" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:42%;">
											<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel">上传网页</h3>
												</div>
												<div class="modal-body">					
													<input type="file" id="inputfile"  name="files" style="height:30px;">
													<p style="font-size:12px;">上传文件类型为：html，htm，大小不超过200K</p>
												</div>										
												<div class="modal-footer">
													<button id="webfile" class="btn" style="margin-right: 10px;">确认</button>
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
											</div>
										</div>
										<!--选择模板-->
										<a id="load_public_template" style="width: 10%;height: 25px;display: block;background: #e6e6e6;border: 1px dashed #acacae;border-radius: 5px;font-size: 12px;line-height: 25px;text-align: center;padding: 0 5px;float: left;margin-right: 9px;" href="#seltpl" data-toggle="modal">选择模板</a>

										<div id="seltpl" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 800px;margin-left: -380px;top: 19px;font-size:13px">
										<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h3 id="myModalLabel">选择模板</h3>
										</div>
					<div style="min-height: 450px;min-width: 800px;">
						<div style="float: left;padding: 5px;border-right: 2px solid #ccc;width: 200px;min-height: 450px;">
							<div class="accordion" id="accordion2">
								<div class="accordion-group">
									<div class="accordion-heading">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" id="default">
										基本布局
										</a>
									</div>
									
								</div>
								<div class="accordion-group">
									<div class="accordion-heading">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree"  style="">
										我的模板
										</a>
									</div>
									<div id="collapseThree" class="accordion-body collapse in">
										<div class="accordion-inner">
									 	{%section name=val loop=$tplrows%}
								        <dd id="{%$tplrows[val].id%}"><a href="javascript:void(0);" class="default3" style="{%$tplrows[val].id%}">{%$tplrows[val].vocation_name%}</a></dd>
								        {%/section%}
										</div>
									</div>
								</div>

							</div>
						</div>
						<div style="float: right;background: #f5f5f5;width:570px ;height:400px;margin-right: 10px;margin-top: 5px;" id="replace_tpl">
									
						</div>
					</div>
										
										</div>
										
										<!--插入变量-->
										<!--<a style="width: 10%;height: 25px;display: block;background: #e6e6e6;border: 1px dashed #acacae;border-radius: 5px;font-size: 12px;line-height: 25px;text-align: center;padding: 0 5px;float: left;margin-right: 9px;" href="#in_par" data-toggle="modal">插入变量</a>-->
										<div id="in_par" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:42%;">
											<form action="/task/designtask" method="post">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel">插入变量</h3>
												</div>
												<div class="modal-body" style="font-size:12px;padding-left: 30px;">
													<table width="100%">
														<tbody>
															<tr><td width="30%" height="30" style="border:0;padding:2px;">默认字段</td><td width="80%" style="border:0;padding:2px;"></td></tr>
															{%section name=val loop=$basic_variable%}
																{%if $basic_variable[val].name == 'sex'%}
																	<tr><td colspan="2" style="border:0;padding:2px;"><input type="checkbox" value="[${%$basic_variable[val].name%}]" name="par"> <label id="n_text">称呼</label> 男 <input type="text" style="width:30px;" value="先生" name="">  女 <input type="text" style="width:30px;" value="女士" name="">   未知 <input type="text" style="width:30px;" value="客户" name=""></td></tr>
																{%else%}
																	<tr><td colspan="2" style="border:0;padding:2px;"><input type="checkbox" value="[${%$basic_variable[val].name%}]" name="par"> <label id="n_text">{%$basic_variable[val].showname%}</label></td></tr>
																{%/if%}
															{%/section%}
															<tr><td height="30" style="border:0;padding:2px;">自定义字段</td><td style="border:0;padding:2px;"></td></tr>
															{%section name=val loop=$define_variable%}
																<tr><td colspan="2" style="border:0;padding:2px;"><input type="checkbox" value="[${%$define_variable[val].name%}]" name="par"> <label id="n_text">{%$define_variable[val].showname%}</label></td></tr>
															{%/section%}
														</tbody>
													</table>
												</div>										
												<div class="modal-footer">
													<input type="button" id="variable" data-dismiss="modal" value="确认" class="btn" style="margin-right: 10px;">
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>
											</form>
										</div>
										<a id="btn_view" style="width: 10%;height: 25px;display: block;background: #e6e6e6;border: 1px dashed #acacae;border-radius: 5px;font-size: 12px;line-height: 25px;text-align: center;padding: 0 5px;float: left;margin-right: 9px;" href="javascript:void(0);">预览模板</a>
										<a style="background: none repeat scroll 0 0 #E6E6E6;border: 1px dashed #ACACAE;border-radius: 5px;display: block;float:left;font-size: 12px;padding: 0 10px;text-align: center;cursor:pointer;height:22px;line-height:22px;line-height:23px\9;width: 80px;" href="#selectsubscription" role="button" data-toggle="modal">选择订阅</a>
									</td>
								</tr>
								<form id="formcon" action="/task/inserttask" method="post">
								<input id="act" type="hidden" value="save_draft" name="act">
								<input type="hidden" value="three" name="step">
								<input type="hidden" value="{%$confirm%}" name="old_step">
								<input id="return" type="hidden" value="up" name="return">
								<input id="tid" type="hidden" name="tid" value="{%$tid%}">
								<input id="mail_t" type="hidden" value="" name="mail_t">
								<input type="hidden" name="path" value="{%$path%}" id="filepath" />
								<input type="hidden" name="filename" value="{%$filename%}" id="oldname" />
								<tr>
									<td style="text-align: left;padding: 8px 0;border:0;">
										{%if $content != ""%}
											<textarea id="texts" name="content" cols="20" rows="2" >{%$content%}</textarea>
										{%else%}	
											<textarea id="texts" name="content" cols="20" rows="2" ></textarea>
										{%/if%}	
									</td>
								</tr>
								<tr>
									<td style="text-align: left;padding: 10px 0;border:0;">
										<p style="border-bottom: 1px solid #A4A4A4;display: block;height: 26px;margin-bottom: 10px;width: 100%;"><span style="background: none repeat scroll 0 0 #A4A4A4;border-radius: 5px 5px 0 0;color: #FFFFFF;display: block;font-size: 13px;font-weight: bold;height: 26px;line-height: 26px;text-align: center;width: 10%;">高级功能</span></p>
									</td>
								</tr>
								<tr>
									<td style="text-align: left;padding: 0px 10px 0px 35px;border:0;font-weight: bold;font-size: 13px;" width="81" align="right" height="30">
									<!-- 附件：<input type="file" style="width:25%;border: 1px solid #BFBFBF;height: 25px;line-height: 25px;padding-left: 5px;" name="filename" id="fileupload_app"> -->
									附件：<input type="file" style="display:none;" name="files" id="fileupload" data-url="/task/uploadattchment" multiple /><a href="javascript:void(0);" class="btn" onclick="return getupload()" style="font-size:12px;color:#666;margin-left: 5px;margin-top: 2px;"/>从本地选择上传附件</a>

								    <!-- Button to trigger modal -->
									<a href="#attachs" id="attachments" role="button" class="btn" data-toggle="modal" style="font-size:12px;color:#666;margin-left: 10px;margin-top: 2px;">选择已上传过的附件</a>
				     
				    <!-- Modal -->
				    <div id="attachs" class="modal hide fade" style="width:600px;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top:5%">
						<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">附件列表</h3>
						</div>
						<div class="modal-body" id="appendattach">
							 <div id="quicknav" style="height: 20px;">
									<div style="float: right;" id="qv">
									检索条件
									<i class="icon-chevron-down"></i>
									</div>
							  </div>
							   <div id="dis" style="display:none;height: 30px;">
									附件名称：<input type="text" id="attachname" name="attachname" value="{%$attachname%}" placeholder="请输入附件名称" style="width:240px; height: 22px; margin-right: 5px;font-size: 12px;">
									<input id="searchattach" type="hidden" name="searchattach" value="search"/>
									<a id="searchtype" class="btn" style="width: 55px;margin-left:5px; height: 20px; background: url(/dist/img/but.jpg) no-repeat scroll 0% 0% transparent; border-radius: 3px; margin-right: 0px; font-size: 12px; padding-left: 5px; padding-right: 5px; color:#787878;border-width: 0px 1px 0px 0px;"><i class="icon-zoom-in"></i>&nbsp;搜索</a>
									<a id="resetsearch" class="btn" style="width: 55px;margin-left:5px; height: 20px; background: url(/dist/img/but.jpg) no-repeat scroll 0% 0% transparent; border-radius: 3px; margin-right: 0px; font-size: 12px; padding-left: 5px; padding-right: 5px; color:#787878;border-width: 0px 1px 0px 0px;">重置</a>
							  </div>
							   <p></p>
							<table width="100%" align="center">
								<tr class="modal-header">
									<td width="20%" style="text-align:center">选择</td>
									<td width="50%" style="text-align:center">附件名称</td>
									<td width="30%" style="text-align:center">附件创建时间</td>
								</tr>
							<tbody id="attachmentlist">
								{%section name=loop loop=$attachs%}
									<tr id="{%$attachs[loop].id%}">
										<td width="20%" style="text-align:center" class="{%$attachs[loop].path%}"><input type="checkbox" name="selattach[]" value="{%$attachs[loop].truename%}"></td>
										<td style="text-align:center"><a title="{%$attachs[loop].truename%}">{%$attachs[loop].truename|truncate:35:"..."%}</a></td>
										<td style="text-align:center">{%$attachs[loop].createtime%}</td>
									</tr>
								{%/section%}
							</tbody>
								<tr><td colspan="3" style="text-align:center"><div class="fenpages" style="float:left; margin-top: 20px; margin-left: 30px;"></div><div class="holder" style="float:left;"></div></td></tr>
							</table>
						</div>
						<div class="modal-footer">
							<input aria-hidden="true" data-dismiss="modal" type="button" class="btn" id="slelectconfirm" value="确定" />
						</div>
					</div>


									{%if !empty($filenames)%}
										<div id="appends" style="margin: 3px;">
											{%section name=loop loop=$filenames%}
												<span class="see" id="{%$filenames[loop].path%}" style="margin-left: 10px;"><label id="{%$filenames[loop].filename%}" for="">{%$filenames[loop].filename%}</label><a href="javascript:void(0)" id="{%$filenames[loop].tmp_id%}" onclick="delattch(this)"><i class="icon-remove" style="margin-left: 10px;"></i></a></span>
											{%/section%}
										</div>
										{%else%}	
											<div id="appends" style="margin: 3px;"></div>
										{%/if%}
										    <div class="progress progress-striped" id="progress" style="height: 5px;width: 500px;display:none;">
										    <div class="bar" style="height: 5px;width: 500px;display: none"></div>
										    </div>

<script>
	function getupload(){
		$('#fileupload').click();
		return false;
	}
	$('#fileupload').fileupload({
		dataType:"json",
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#progress').css('display','block');
			$('#progress .bar').css({
				'display':'block','width':
				progress + '%'
			});
		},
		done:function(e,data){ 
	        var arr = data.result;
			var see = $("#appends").find("label");
			var tag = true;
			$.each(see,function(i,result){
			//alert(result.id);
				if(arr.filename === result.id){
					tag = false;
				}
			}) 
			if(tag == false){
				art.dialog.alert(arr.filename+'附件重复上传');
				return false;
			}
				
			$('#appends').append('<span class="see" id="'+arr.path+'" style="margin-left: 10px;"><label id="'+arr.filename+'" for="">'+arr.filename+'</label><a href="javascript:void(0)" id="'+arr.tmp_id+'" onclick="delattch(this)"><i class="icon-remove" style="margin-left: 10px;"></i></a></span>');
			$('#progress').css('display','none');
			$('#progress .bar').css('display','none');
			var arr = $("#appends").find("span");
			var path = "";
			$.each(arr,function(i,result){
				path += result.id+",";
			})
			$("#filepath").val(path);
			
			var arr2 = $("#appends").find("label");
			var filename = '';
			$.each(arr2,function(i,result){
				filename += result.id+",";
			})
			$("#oldname").val(filename);
		}
	});

function delattch(obj){
		var val=obj.getAttribute('id');
		var fid=obj.parentNode;
		var path=fid.id;
		var fod = document.getElementById(path);
		var file = fod.firstChild.id;
		var t='tag';
		$.post('/task/delmodattch',{'delid':val,'path':path,'del':t},function(data){
			if(data == 'ok'){
				fid.parentNode.removeChild(fid);
				var filepath=$("#filepath").val();
				var newpath = filepath.replace(path+',', '');
				$("#filepath").val(newpath);
				var filename=$("#oldname").val();
				var newfile = filename.replace(file+',', '');
				$("#oldname").val(newfile);
			}
		}); 
	}
	//选择附件
	$("#slelectconfirm").click(function(){
		var con='';
		var attach = $("input[name='selattach[]']:checked").each(function(){
			//alert($(this).val());
			var filename=$(this).val();
			var path=$(this).parent().attr("class");
			var aid=$(this).parent().parent().attr("id");
			if(filename != ''){
				con += '<span class="see" id="'+path+'" style="margin-left: 10px;"><label id="'+filename+'" for="">'+filename+'</label><a href="javascript:void(0)" id="'+aid+'" onclick="delattch(this)"><i class="icon-remove" style="margin-left: 10px;"></i></a></span>';
			}	
		});
			$('#appends').html(con);
					var arr = $("#appends").find("span");
			var path = "";
			$.each(arr,function(i,result){
				path += result.id+",";
			})
			$("#filepath").val(path);
			
			var arr2 = $("#appends").find("label");
			var filename = '';
			$.each(arr2,function(i,result){
				filename += result.id+",";
			})
			$("#oldname").val(filename);
	});
	$("#qv").click(function(){
				if($("#dis").is(":hidden")){
					$("#dis").css("display","block");
					$("#quicknav").children().children("i").attr("class",'icon-chevron-up');
				}else{
					$("#dis").css("display","none");
					$("#quicknav").children().children("i").attr("class",'icon-chevron-down');
				}
	})

	$('#searchtype').on('click',function(){
		var attachname=$('#attachname').val();
		var searchattach=$('#searchattach').val();
		var con='';
			$.post('/task/searchattach',{'searchattach':searchattach,'attachname':attachname},function(data){
				var v=eval('('+data+')');
				var str = '';
				 $.each(v, function( i , arr){ 
					con += '<tr id="'+arr.id+'"><td width="20%" style="text-align:center" class="'+arr.path+'"><input type="checkbox" name="selattach[]" value="'+arr.truename+'"></td><td style="text-align:center"><a title="{%$attachs[loop].truename%}">'+arr.truename+'</a></td><td style="text-align:center">'+arr.createtime+'</td></tr>';
				});
				$('#attachmentlist').html(con);
				$('#attachs').on('show',function(){
						$("div.holder").jPages("destroy");
						$("div.holder").jPages({
							containerID:"attachmentlist",
							perPage    : 5,
							first      : "首 页", 
							previous : "上一页",
							next : "下一页",
							last   :"尾 页",
							delay      : 0,
							callback   : function(pages,items){
								$(".fenpages").html("共有<b>" + items.count +"</b>条记录  <b>" + pages.current + "</b>/<b>" +pages.count +"</b>&nbsp;&nbsp;");
							}
						});
				});
				$('#attachs').modal('show');
			});
	});
	$('#resetsearch').on('click',function(){
		$('#attachname').val('');
		var attachname=$('#attachname').val();
		var searchattach=$('#searchattach').val();
		var con='';
			$.post('/task/searchattach',{'searchattach':searchattach,'attachname':attachname},function(data){
				var v=eval('('+data+')');
				var str = '';
				 $.each(v, function( i , arr){ 
					con += '<tr id="'+arr.id+'"><td width="20%" style="text-align:center" class="'+arr.path+'"><input type="checkbox" name="selattach[]" value="'+arr.truename+'"></td><td style="text-align:center"><a title="{%$attachs[loop].truename%}">'+arr.truename+'</a></td><td style="text-align:center">'+arr.createtime+'</td></tr>';
				});
				$('#attachmentlist').html(con);
				$('#attachs').modal('show');
			});
	});
</script>

									</td>
								</tr>
								<tr>
									<td style="text-align: left;padding: 0px 10px 0px 35px;border:0;color: #999999;font-size: 12px;" width="81" height="30">
										<p style="margin-top: 5px; padding-left: 45px;">您可以上传新附件，也可以选择创建其他任务时您已上传过的附件！</p>
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 0px 10px 0px 20px;border:0;line-height:20px;font-size: 13px;">
									<label class="checkbox inline" style="padding-top: 0px;color: #999999;font-size: 12px;vertical-align: middle;">
									{%if $isreport == 1%}
										<input style="margin-top: 1px;" type="checkbox" id="report" name="isreport" value="1" checked>
									{%else%}
										<input style="margin-top: 1px;" type="checkbox" id="report" name="isreport" value="1" >
									{%/if%}
									</label><label style="cursor:pointer">是否接收任务报告</label>
								</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 0px 10px 0px 20px;border:0;font-weight: bold;line-height:20px;font-size: 13px;">	
									接收地址：
										<input class="inwbk_re" type="text" style="width:92%;" value="{%$reportemail%}" name="reportemail" id="reportemail">
									</td>
								</tr>								
								<tr>
									<td style="text-align: left;padding: 10px 0;border:0;">
										<p style="border-bottom: 1px solid #A4A4A4;display: block;height: 26px;margin-bottom: 10px;width: 100%;"><span style="background: none repeat scroll 0 0 #A4A4A4;border-radius: 5px 5px 0 0;color: #FFFFFF;display: block;font-size: 13px;font-weight: bold;height: 26px;line-height: 26px;text-align: center;width: 10%;">内容设置</span></p>
									</td>
								</tr>
								<tr>
									<td style="text-align: left;padding: 0px 10px 0px 20px;border:0;font-weight: bold;line-height:20px;font-size: 13px;">随机发送：<label class="checkbox inline" style="padding-top: 0px;color: #999999;font-size: 12px;vertical-align: middle;">	
										<input type="checkbox" id="inlineCheckbox1" name="random" value="1" checked>当选中此项时将会以随机打散的方式为您发送该任务，这样可以很大提升邮件的到达率</label></td>
								</tr>
								<tr>
									<td style="text-align: left;padding: 8px 10px 0px 20px;border:0;font-weight: bold;line-height:20px;font-size: 13px;"><span style="float: left;display: inline-block;">发送计划：</span>
										{%if $sendtype == 1%}
										<label class="radio" style="margin-left: 20px;"><input type="radio" class="cls_server_send" name="is_server_send" value="{%$sendtype%}">立即</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="1"  checked>定时</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="2">周期</label>
										{%else if $sendtype == 2%}
										<label class="radio" style="margin-left: 20px;"><input type="radio" class="cls_server_send" name="is_server_send" value="0" >立即</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="1">定时</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="2" checked>周期</label>
										{%else%}
										<label class="radio" style="margin-left: 20px;"><input type="radio" class="cls_server_send" name="is_server_send" value="0"  checked>立即</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="1" >定时</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="2">周期</label>
										{%/if%}
										</td>
									<script type="text/javascript">
										jQuery(function(){
											$("#cycle_time").val('');
											
											$("input[name=is_server_send]").click(function ()
											{
												switch($(this).val())
												{
													case '0': //立即
														$("#cycle_send").slideUp("slow");
														$("#timer_send").slideUp("slow");
														$("#now_send").slideDown("slow");
														break;
														case '1': //定时
														$("#now_send").slideUp("slow");
														$("#cycle_send").slideUp("slow");
														$("#timer_send").slideDown("slow");
														break;
													case '2': //周期
														$("#cycle_send").slideDown("slow");
														$("#timer_send").slideUp("slow");
														$("#now_send").slideUp("slow");
														break;
														default : break;
													}
												});
												
												$("input[name=cycle_type]").click(function ()
													{
														var t = $("#cycle_time").val();
														switch($(this).val())
														{
														case '1': //每天
														$("#cycle_week").hide();
														$("#cycle_month").hide();
														$("#cycle_type_day_desc").html($("#cycle_type_day_desc").attr("dt").replace("“发送时间”","“" + t + "”")).slideDown();
														$("#cycle_type_week_desc").slideUp();
														$("#cycle_type_month_desc").slideUp();
														break;
													case '2': //每周
														$("#cycle_week").show();
														$("#cycle_month").hide();
														$("#cycle_type_week_desc").html($("#cycle_type_week_desc").attr("dt").replace("“发送时间”","“" + t + "”")).slideDown();
														$("#cycle_type_day_desc").slideUp();
														$("#cycle_type_month_desc").slideUp();
														break;
													case '3': //每月
														$("#cycle_week").hide();
														$("#cycle_month").show();
														$("#cycle_type_month_desc").html($("#cycle_type_month_desc").attr("dt").replace("“发送时间”","“" + t + "”")).slideDown();
														$("#cycle_type_day_desc").slideUp();
														$("#cycle_type_week_desc").slideUp();
														break;
														default : break;
													}
												});
												
												$("#cycle_time").change(function ()
												{
													$("input[name=cycle_type]:checked").trigger('click');
												});
												
												$('.cls_server_send').each(function ()
												{
													$("input[name=is_server_send]:checked").trigger('click');
												});
												
												$('.cls_cycle_type').each(function ()
												{
													$("input[name=cycle_type]:checked").trigger('click');
												});
										}); 
										</script> 
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 10px 10px 0px 90px;border:0;line-height:20px;font-size: 12px;">
										<!--立即发送-->
										<div  id="now_send" style="line-height:20px;border: 2px dotted #CCCCCC; width: 76%; padding: 10px 0; padding-left: 10px; display: none;"> 您的任务将在审核通过后开始发送。如果需要跳过审核步骤直接发送任务，请联系管理员。 </div>
										<!--定时发送-->
										<div id="timer_send" style="line-height: 20px; border: 2px dotted rgb(204, 204, 204); width: 76%;padding-top: 10px; padding-bottom: 10px; padding-left: 10px; display: block;">请设置发送时间：<div class="input-append date form_datetime" style=" margin-right: 1px;margin-top: -4px;">
										{%if $sendtype == 1%}
										<input size="16" name="start_time" id="start_time" type="text" value="{%$sendtime%}" onfocus="" style="width:196px;padding: 2px 0px\9;" readonly><span class="add-on" style="padding-top: 5px; padding-bottom: 0px; height: 18px;"><i class="icon-th"></i></span></div><br/>定时计划会在您设定的时间开始尝试发送邮件。在此之前，您还可以撤销发送计划对邮件进行编辑。
										{%else%}
										<input size="16" name="start_time" id="start_time" type="text" value="" onfocus="" style="width:196px;padding: 2px 0px\9;" readonly><span class="add-on" style="padding-top: 5px; padding-bottom: 0px; height: 18px;"><i class="icon-th"></i></span></div><br/>定时计划会在您设定的时间开始尝试发送邮件。在此之前，您还可以撤销发送计划对邮件进行编辑。
										{%/if%}
										</div>
										<!--周期发送-->
										<div id="cycle_send" style="line-height: 20px; border: 2px dotted rgb(204, 204, 204); width: 76%; padding-top: 10px; padding-left: 10px;padding-bottom: 10px; display: none;">
										发送时间：<select id="cycle_time" name="cycle_time"  style="padding: 0px; width: 65px; height: 20px; margin-bottom: 5px;">				
														{%if $cycle_time != ''%}
															<option value="{%$cycle_time%}" selected="selected">{%$cycle_time%}</option>
														{%/if%}	
															<option value="00:00">00:00</option>
															<option value="00:30">00:30</option>
															<option value="01:00">01:00</option>
															<option value="01:30">01:30</option>
															<option value="02:00">02:00</option>
															<option value="02:30">02:30</option>
															<option value="03:00">03:00</option>
															<option value="03:30">03:30</option>
															<option value="04:00">04:00</option>
															<option value="04:30">04:30</option>
															<option value="05:00">05:00</option>
															<option value="05:30">05:30</option>
															<option value="06:00">06:00</option>
															<option value="06:30">06:30</option>
															<option value="07:00">07:00</option>
															<option value="07:30">07:30</option>
															<option value="08:00">08:00</option>
															<option value="08:30">08:30</option>
															<option value="09:00">09:00</option>
															<option value="09:30">09:30</option>
															<option value="10:00">10:00</option>
															<option value="10:30">10:30</option>
															<option value="11:00">11:00</option>
															<option value="11:30">11:30</option>
															<option value="12:00">12:00</option>
															<option value="12:30">12:30</option>
															<option value="13:00">13:00</option>
															<option value="13:30">13:30</option>
															<option value="14:00">14:00</option>
															<option value="14:30">14:30</option>
															<option value="15:00">15:00</option>
															<option value="15:30">15:30</option>
															<option value="16:00">16:00</option>
															<option value="16:30">16:30</option>
															<option value="17:00">17:00</option>
															<option value="17:30">17:30</option>
															<option value="18:00">18:00</option>
															<option value="18:30">18:30</option>
															<option value="19:00">19:00</option>
															<option value="19:30">19:30</option>
															<option value="20:00">20:00</option>
															<option value="20:30">20:30</option>
															<option value="21:00">21:00</option>
															<option value="21:30">21:30</option>
															<option value="22:00">22:00</option>
															<option value="22:30">22:30</option>
															<option value="23:00">23:00</option>
															<option value="23:30">23:30</option>
														</select>
												&nbsp;结束时间：<div class="input-append date form_date" id="endtime" style="margin-right: 1px;margin-top: -4px;">
												{%if $cycle_end_time == '0000-00-00' || $cycle_end_time == null%}
													<input size="16" name="next_end_time" id="next_end_time" type="text" value="" onfocus="" style="width:196px;padding: 2px 0px\9;" readonly><span class="add-on" style="padding-top: 5px; padding-bottom: 0px; height: 18px;"><i class="icon-th"></i></span>
												{%else%}
													<input size="16" name="next_end_time" id="next_end_time" type="text" value="{%$cycle_end_time%}" onfocus="" style="width:196px;padding: 2px 0px\9;" readonly><span class="add-on"><i class="icon-th"></i></span>
												{%/if%}
												</div>
												<table width="100%">
													<tbody><tr>
													{%if $cycle_type == '2'%}
														<td width="220px" style="text-align: left;padding:0;border:0;">
															<label><input name="cycle_type" type="radio" value="1" class="cls_cycle_type" style="margin-top: 3px; margin-right: 10px;">每天</label>
														</td>
														<td width="220px" style="text-align: left;padding:0;border:0;font-size:12px;">
															<label style="width: 55px;float:left"><input name="cycle_type" type="radio" style="margin-top: 3px; margin-right: 10px;" value="2" class="cls_cycle_type" checked>每周</label>
															<select id="cycle_week" name="cycle_week" style="display: none;width: 82px; font-size:12px;padding: 0px; height: 20px;">
															{%foreach from=$cycle_week_data key=k item=val%}
															{%if $k == $cycle_week%}
																<option value="{%$k%}" selected="selected">{%$val%}</option>
															{%else%}	
																<option value="{%$k%}">{%$val%}</option>
															{%/if%}
															{%/foreach%}	
															</select>
														</td>
														<td style="text-align: left;padding:0;border:0;">
															<label style="width: 55px;float:left"><input name="cycle_type" type="radio" style="margin-top: 3px; margin-right: 10px;" value="3" class="cls_cycle_type">每月</label>
															<select id="cycle_month" name="cycle_month" style="display: none;display: none; padding: 0px; font-size: 12px; width: 42px; height: 18px;">
															{%foreach from=$cycle_month_data key=k item=val%}	
																<option value="{%$k%}">{%$val%}</option>
															{%/foreach%}
															</select>
														</td>
														
													{%else if $cycle_type == '3'%}
														<td width="220px" style="text-align: left;padding:0;border:0;">
															<label><input name="cycle_type" type="radio" value="1" class="cls_cycle_type" style="margin-top: 3px; margin-right: 10px;">每天</label>
														</td>
														<td width="220px" style="text-align: left;padding:0;border:0;font-size:12px;">
															<label style="width: 55px;float:left"><input name="cycle_type" type="radio" style="margin-top: 3px; margin-right: 10px;" value="2" class="cls_cycle_type">每周</label>
															<select id="cycle_week" name="cycle_week" style="display: none;width: 82px; font-size:12px;padding: 0px; height: 20px;">
															{%foreach from=$cycle_week_data key=k item=val%}	
																<option value="{%$k%}">{%$val%}</option>
															{%/foreach%}
															</select>
														</td>
														<td style="text-align: left;padding:0;border:0;">
															<label style="width: 55px;float:left"><input name="cycle_type" type="radio" style="margin-top: 3px; margin-right: 10px;" value="3" class="cls_cycle_type" checked>每月</label>
															<select id="cycle_month" name="cycle_month" style="display: none;display: none; padding: 0px; font-size: 12px; width: 42px; height: 18px;">
															{%foreach from=$cycle_month_data key=k item=val%}
															{%if $k == $cycle_month%}
																<option value="{%$k%}" selected="selected">{%$cycle_month%}</option>
															{%else%}	
																<option value="{%$k%}">{%$val%}</option>
															{%/if%}
															{%/foreach%}
															</select>
														</td>
													{%else%}
														<td width="220px" style="text-align: left;padding:0;border:0;">
															<label><input name="cycle_type" type="radio" value="1" class="cls_cycle_type" style="margin-top: 3px; margin-right: 10px;" checked>每天</label>
														</td>
														<td width="220px" style="text-align: left;padding:0;border:0;font-size:12px;">
															<label style="width: 55px;float:left"><input name="cycle_type" type="radio" style="margin-top: 3px; margin-right: 10px;" value="2" class="cls_cycle_type">每周</label>
															<select id="cycle_week" name="cycle_week" style="display: none;width: 85px; font-size:12px;padding: 0px; height: 20px;">
															{%foreach from=$cycle_week_data key=k item=val%}	
																<option value="{%$k%}">{%$val%}</option>
															{%/foreach%}
															</select>
														</td>
														<td style="text-align: left;padding:0;border:0;">
															<label style="width: 55px;float:left"><input name="cycle_type" type="radio" style="margin-top: 3px; margin-right: 10px;" value="3" class="cls_cycle_type">每月</label>
															<select id="cycle_month" name="cycle_month" style="display: none;display: none; padding: 0px; font-size: 12px; width: 42px; height: 18px;">
															{%foreach from=$cycle_month_data key=k item=val%}	
																<option value="{%$k%}">{%$val%}</option>
															{%/foreach%}
															</select>
														</td>
														{%/if%}
													</tr>
													<tr>
														<td colspan="3" id="cycle_memo" style="text-align: left;padding:0;border:0;font-size: 12px;">
															<div id="cycle_type_day_desc" style="display: block;" dt="每天的“发送时间”都会发送一次本任务">每天的“00:00”都会发送一次本任务</div>
															<div id="cycle_type_week_desc" style="display:none" dt="每周符合周设置天的“发送时间”都会发送一次本任务"></div>
															<div id="cycle_type_month_desc" style="display:none;" dt="每月符合月设置天的“发送时间”都会发送一次本任务；如果本月没有设置的天数，就认为是本月的最后一天。如：4月在按月发送时，设置30号或31号的效果是相同的，请不必担心没有31号月的发送问题。"></div>
														</td>
													</tr>
													</tbody>
												</table>
										</div>
									</td>
								</tr>
								<tr>
									<td style="text-align: left;padding: 8px 0;border:0;"></td>
								</tr>
								{%if $confirm == ''%}
								<tr>
									<td style="text-align: left;padding: 8px 0;border:0;">
										<button id="design_prev" class="btn" type="button" style="width:113px;height:30px;background:url(/dist/img/pro_but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;margin-right: 5px;"></button>
										<button id="design_next" class="btn" type="button" style="width:113px;height:30px;background:url(/dist/img/next_but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;"></button>
									</td>
								</tr>
								{%else%}
								<tr>
									<td style="text-align: left;padding: 8px 0;border:0;">
									<button type="button" id="confirm" style="width:85px;height:27px;background:url(/dist/img/but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;border-width: 0px;">保存</button>
									</td>
								</tr>
								{%/if%}
								</form>
							</tbody>
						</table>
						
					</div>
				</div>
			</div>
		</div>

  </div>
</div>
<div id="selectsubscription" style="width:600px" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h3 id="myModalLabel" class="text-left">选择订阅</h3>
		</div>
		<p> </p>
		<div  class="modal-body">
			<div>
				
			</div>
			<div>
				选择订阅
				<span style="margin-left:100px;">
				<select id="selusersub" style="width:212px;">
					{%section name=loop loop=$usersubscription%}
						<option value="{%$usersubscription[loop].key%}">{%$usersubscription[loop].formname%}</option>
					{%/section%}
				</select>
				</span>
				<input type="hidden" name="domainname" />
				<input type="hidden" name="serviceport" />
			</div>
			<p> </p>	
			<div>自定义按钮上的内容<span style="margin-left:43px;"><input type="type" name="subname" /></span></div>
		</div>
		<p> </p>
		<div class="modal-footer">
			<input aria-hidden="true" data-dismiss="modal" type="button" class="btn" id="usersubscription" value="选择" />
		</div>
</div>
<!--Footer-part-->
<script type="text/javascript">
CKEDITOR.replace('texts');
// 点击选择模板默认基础布局
$(function(){
	$('#collapseOne').collapse('show');
	$('#collapseThree').collapse('hide');
	$('#default').css('color',"#28b779");
	$('#default').blur(function(){$(this).css('color','#666666')});
	$('#default').focus(function(){$(this).css('color','#28b779')});

})

//选择订阅
$(function(){
	$.ajax({
		type:"post",
		dataType:"json",
		url:"/task/ajaxgetdomainname",
		success:function(data){
			$("input[name='domainname']").attr("value",data.domainname);
			$("input[name='serviceport']").attr("value",data.serviceport);
		}
	})
	$("#usersubscription").click(function(){
		var id = $("#selusersub option:selected").val();
		var subname = $("input[name='subname']").val();
		//alert( CKEDITOR.instances.texts.getData());
		var domainname = $("input[name='domainname']").val();
		var serviceport = $("input[name='serviceport']").val();
		//alert(domainname);alert(serviceport);
		var cktext = CKEDITOR.instances.texts.getData();
		var str = "<a href='http://"+domainname+":"+serviceport+"/form.php?id="+id+"'><input type='button' value="+subname+"></a>";
		CKEDITOR.instances.texts.setData(cktext+str);
	})
})

$(function(){
	var tid=$('#tid').val();
	//点击button判断form表单是的值后提交表单
	$('#design_next').on('click', function(){
		
		$('#return').val('down');
		checkform();
	});
	
	$('#design_prev').on('click', function(){
		$('#return').val('up');
		checkform();
	})
	
	$("#confirm").click(function(){
		checkform();
	}); 
	
	function checkform(){
		var isreport=$("input[name='isreport']:checked").val();
		var reportemail=$('#reportemail').val();
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
		if(isreport == 1){
			if(reportemail != ''){
				if(!reg.test(reportemail)) {
					art.dialog.alert('您填写的邮箱格式不正确,请重新填写!');
					return false;
				}
			}else{
				art.dialog.alert('您选择接收任务报告时,接收报告邮箱地址不能为空!');
				return false;
			}
		}
		
	    var is_server_send=$("input[name='is_server_send']:checked").val();
		if(is_server_send == 1){
			var start_time=$('#start_time').val();
			if(start_time == ''){
				art.dialog.alert('发送时间不能为空，请设置发送时间!');
				return false;
			}
		}	
		
		if(is_server_send == 2){
			var next_end_time=$('#next_end_time').val();
			if(next_end_time == ''){
				art.dialog.alert('选择周期发送任务时，请设置结束时间!');
				return false;
			}
		} 
		
		$('#formcon').submit();
	}
	
	function checkStartTime(){
		//判断定时发送时间
		var start_time=$('#start_time').val();
		if(start_time != ''){
			var start=tostrings(start_time);
			var myDate=GetDateT();
			if(start < myDate){
				art.dialog.alert('选择定时发送任务时，请设置发送时间大于当前时间!');
				return false;
			}
			return true;  
		}
	}

}) 


//表单中日历时间显示
$(function(){
	$(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        autoclose: true,
        todayBtn: true,
		startDate: new Date(),
		endDate: '2099-12-31 23:59:59',
        pickerPosition: "bottom-left"
    });
	
	$('.form_date').datetimepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayBtn: true,
	    weekStart: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		startDate: new Date(),
		endDate: '2099-12-31 23:59:59',
        pickerPosition: "bottom-left"
    });
})

	$('#webfile').click(function(){
		var file=$('#inputfile').val();
		if(file != ''){
			return ajaxFileUpload();
		}else{
			$('#load_file').modal('hide');
			art.dialog.alert('上传文件不能为空');
		}
	});

function ajaxFileUpload()
  {
    $.ajaxFileUpload
    (
      {
        url:'/templet/uploadtem',
        secureuri:false,
        fileElementId:'inputfile',
        dataType: 'text',
        // data:{name:'logan', id:'id'},
        success: function (data)
        {
             //alert(data);
             if(data){
			 $('#load_file').modal('hide');
              CKEDITOR.instances.texts.setData(data) ;
              CKEDITOR.instances.texts.getData();
            } 
         },
        error: function (data, status, e)
        {
          alert(e);
        }
      }
    )
    
    return false;

  }
  
   $('#reads').click(function(){
          var values=$('#focusedInput').val();
          // alert(values);
          $.post('/templet/ajaxweb',{'files_contents':values},function(data){
            if(data){
				//alert(data);exit;
			  $('#load_site').modal('hide');
              CKEDITOR.instances.texts.setData(data) ;
              CKEDITOR.instances.texts.getData();
            }
          });
  });

   function stpl(obj){
          var values=obj.getAttribute('number');
          // alert(values);
          // alert(1111);
          $.post('/task/tplcont',{'contents':values},function(data){
            if(data){
				//alert(data);exit;
			  $('#seltpl').modal('hide');
              CKEDITOR.instances.texts.setData(data) ;
              CKEDITOR.instances.texts.getData();
            }
          });
      }
 
  
    //预览模板
   $("#btn_view").click(function(){
    var content=CKEDITOR.instances.texts.getData();
      $.post('/templet/preview',{'templets':content},function(data){
             // var obj = window.open("about:blank");  
                  if(data=='111'){
                    window.open('/templet/previewone');
                  }
      }); 

  });
	
	$("#variable").click(function(){
			var cktext = CKEDITOR.instances.texts.getData();
			var str = "";
			$("input[name='par']:checked").each(function(){
				str += $(this).val();
			})
			CKEDITOR.instances.texts.setData(cktext+str);
	})
   
</script> 
{%include file="footer.php"%}
