{%include file="header.php"%}
<script type="text/javascript" src="/dist/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/dist/js/searchmytemplpage.js"></script>
<script type="text/javascript" src="/dist/js/vendor/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/dist/js/vendor/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="/dist/js/vendor/jquery.fileupload.js"></script>
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
	margin: 0 0px 0 0px;
  }
  .holder a:hover {
    background-color: #E8E8E8;
    color: #222222;
  }
  .holder a.jp-first {border-radius: 4px 0 0 4px;}
  .holder a.jp-previous { margin-right: 0px; }
  .holder a.jp-current, a.jp-current:hover {
	background-color: #26B779;color: #FFFFFF;
	width: 20px;
    font-weight: bold;
	text-align:center;
	margin-right: 0px;
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
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 投递任务管理</a><a href="#" class="current">编辑任务</a></div>
  </div>
  <div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"><i class="icon-inbox"></i></span>
						<h5>编辑任务</h5>
						<div style="width: 60px; float: right; height: 36px; line-height: 36px; margin-right: 15px;"><a href="{%$url_page%}">返回上一页</a></div>
					</div>
					<div class="widget-content">
						<form id="formAdd" action="" method="post">
						<input type="hidden" id='tid' name="taskId" value="{%$taskId%}">
						<input type="hidden" name="modifiedtime" value="{%$modifiedtime%}">
						<input type="hidden" name="uid" value="{%$uid%}" id='uid'>
						<input type="hidden" name="gnames" value="{%$groupIds%}" id="groupid" />
						<input type="hidden" name="tnum" value="{%$tnum%}" id="tnum" />
						<input type="hidden" name="fid" value="{%$filtersId%}" id="filtersId" />
						<input type="hidden" name="path" value="{%$filepath%}" id="filepath" />
						<input type="hidden" name="filename" value="{%$filename%}" id="oldname" />
						<input type="hidden" id="draft" name="draft" value=""/>
						<table class="table">
							 <tbody>
								<tr>
									<td colspan="4" style="text-align: left;border:0;padding: 8px 0px;">
										<p style="border-bottom: 1px solid #A4A4A4;display: block;height: 26px;width: 100%;"><span style="background: none repeat scroll 0 0 #A4A4A4;border-radius: 5px 5px 0 0;color: #FFFFFF;display: block;font-size: 12px;font-weight: bold;height: 26px;line-height: 26px;text-align: center;width: 10%;">基本信息</span></p>
									</td>
								</tr>
								<tr><td colspan="2" style="width: 60%;text-align:left;border:0;font-weight: bold;padding-left:0px;padding-bottom:0;">任务名称</td><td colspan="2" style="text-align:left;border:0;font-weight: bold;padding-left:15px;padding-bottom:0;">任务分类</td></tr>
								<tr>
									<td colspan="2" style="text-align: left;padding: 0;border:0;padding-top:0;" >
										<input type="text" id="taskname" name="task_name" value="{%$task_name%}" style="border: 1px solid rgb(191, 191, 191); line-height: 25px; width: 99.3%; padding: 0px 0px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px;">
										<div id="checktaskname" style="width:100%;text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;line-height: 27px;padding-left:2px;vertical-align: top;">&nbsp;仅内部使用 示例："活动策划2013-09-09#4" </div>
									</td>
									<td colspan="2" style="text-align: left;padding: 0;border:0;padding-top:0;" >
										<select name="cid" id="tasktype" style="border: 1px solid rgb(191, 191, 191); height:25px;line-height: 25px;padding-left:4px 0 0; width: 97%; margin-bottom: 0px;font-size:12px;margin-left: 15px;"><option value="0">请选择分类</option>
										{%section name=loop loop=$catdata%}
											{%if $catdata[loop].id == $cid%}
												<option value="{%$catdata[loop].id%}" selected="selected">{%$catdata[loop].vocation_name%}</option>
											{%else%}
												<option value="{%$catdata[loop].id%}">{%$catdata[loop].vocation_name%}</option>
											{%/if%}
										{%/section%}
										</select>
										<div  style="width: 97%;text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;line-height: 27px;padding-left:0px;vertical-align: top;margin-left: 15px;">&nbsp;&nbsp;给您的任务选择一个合适的分类   <a style="line-height:27px;line-height:27px;color:#0066FF" role="button" data-toggle="modal" href="#quick_add">快速新建</a></div>
										<div id="quick_add" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:42%;">
											<form id="formtype" action="/templet/addvocation" method="get" class="">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">添加任务分类</h3>
												</div>
												<div class="modal-body">					
													<p>
														<table width="510" style="border-left:0;border-top:0 ;margin:0 10px;">
														  <tr>
															<td style="border-left:0;border-top:0 ;">分类名称:</td>
															<td style="border-left:0;border-top:0 ;padding:8px 0;"><input type="text" name="names" id="inputuser" class="input-large" style="height: 24px;" maxlength="10">
															  <span id="namestyle" style="color: red">*</span>
															  <span>(10字以内)</span>
															  <div class="style_type" style="color:#999999;font-size:12px;">分类名称不能与已有分类重复</div>
															</td>
														  </tr> 
														  <tr>
															<td style="border-left: 0; border-top: 0">分类描述:</td>
															<td style="border-left:0;border-top:0 ;padding:8px 0;">
															  <textarea name="content" id="inputinput" cols="250" rows="2" style="resize:none;" maxlength="15"></textarea>
															  <span>(15字以内)</span>
															
															</td>
														  </tr>
														</table> 
													</p>
												</div>										
												<div class="modal-footer">
													<button type="button" id="sudu" class="btn btn-primary" style="margin-right: 10px;">创建
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>
											</form>
										</div>
									</td>
								</tr>		
								<tr>
									<td style="width: 21%;text-align:left;border:0;font-weight: bold;padding-left:0px;padding-bottom:0;">发件人</td>
									<td style="width: 39%;text-align:left;border:0;font-weight: bold;padding-left:8px;padding-bottom:0;">发件地址</td>
									<td colspan="2" style="text-align:left;border:0;font-weight: bold;padding-left:15px;padding-bottom:0;">回复地址</td>
								</tr>		
								<tr>
									<td style="text-align:left;border:0;padding-left:0px;padding-top: 0px;">
										<input type="text" id="sender" name="sender" value="{%$sender%}" style="border: 1px solid rgb(191, 191, 191); line-height: 25px; width: 98.4%; padding: 0px 2px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px;">
										<div  style="width:100%;text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;line-height: 27px;padding: 0px 2px;vertical-align: top;padding-left:2px;">&nbsp;让收件人知道您是谁</div>										
									</td>
									<td style="text-align:left;border:0;padding-left:0px;padding-top: 0px;">
										<input type="text" id="sendemail" name="sendemail" value="{%$sendemail%}" style="background-color:#fff;border: 1px solid rgb(191, 191, 191); line-height: 25px; width: 98.4%; padding: 0px 2px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px; margin-left: 8px;">
										<div  style="width:100%;text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;line-height: 27px;padding:0;vertical-align: top;padding-left:2px; margin-left: 8px;" readonly="readonly"/>&nbsp;收件人会看到的您的邮件地址</div>										
									</td>
									<td colspan="2" style="text-align:left;border:0;padding-left:0px;padding-top: 0px;padding-right: 0px;">
										<input type="text" id="replyemail" name="replyemail" value="{%$replyemail%}" style="border: 1px solid rgb(191, 191, 191); line-height: 25px; width: 95%; padding: 0px 2px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px; margin-left: 15px;" />
										<div  style="width:96.5%;text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;line-height: 27px;padding:0;vertical-align: top;padding-left:2px; margin-left: 15px;">&nbsp;回复您的任务到这个邮件地址</div>											
									</td>
								</tr>
								<tr><td colspan="4" style="text-align:left;border:0;font-weight: bold;padding-left:0px;padding-bottom: 5px;">邮件主题：<a style="background: none repeat scroll 0 0 #E6E6E6;border: 1px dashed #ACACAE;border-radius: 5px;display: block;font-size: 12px;padding: 0 10px;text-align: center;cursor:pointer;width:90px;float:right;height:22px;line-height:22px;line-height:23px\9;" href="#in_par2" data-toggle="modal">插入主题变量</a>
									<div id="in_par2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:42%;">
											<form action="/task/designtask" method="post">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel">插入主题变量</h3>
												</div>
												<div class="modal-body" style="font-size:12px;padding-left: 30px;">
													<table width="100%">
														<tbody>
															<tr><td width="30%" height="30" style="border:0;padding:2px;">默认字段</td><td width="80%" style="border:0;padding:2px;"></td></tr>
															{%section name=val loop=$basic_variable%}
																{%if $basic_variable[val].name == 'sex'%}
																	<tr><td colspan="2" style="border:0;padding:2px;"><input type="checkbox" value="[${%$basic_variable[val].name%}]" name="par2"> <label id="n_text">称呼</label> 男 <input type="text" style="width:30px;" value="先生" name="">  女 <input type="text" style="width:30px;" value="女士" name="">   未知 <input type="text" style="width:30px;" value="客户" name=""></td></tr>
																{%else%}
																	<tr><td colspan="2" style="border:0;padding:2px;"><input type="checkbox" value="[${%$basic_variable[val].name%}]" name="par2"> <label id="n_text">{%$basic_variable[val].showname%}</label></td></tr>
																{%/if%}
															{%/section%}
															<tr><td height="30" style="border:0;padding:2px;">自定义字段</td><td style="border:0;padding:2px;"></td></tr>
															{%section name=val loop=$define_variable%}
																<tr><td colspan="2" style="border:0;padding:2px;"><input type="checkbox" value="[${%$define_variable[val].name%}]" name="par2"> <label id="n_text">{%$define_variable[val].showname%}</label></td></tr>
															{%/section%}
														</tbody>
													</table>
												</div>										
												<div class="modal-footer">
													<input type="button" id="variable2" data-dismiss="modal" value="确认" class="btn btn-primary" style="margin-right: 10px;">
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>
											</form>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="4" style="width:100%;text-align:left;border:0;padding-left:0px;padding-top:0;">
										<input type="text" id="subject" name="subject" value="{%$subject%}" style="border: 1px solid rgb(191, 191, 191); line-height: 25px; width: 100%; padding: 0px 2px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px;">
										<div  style="width:100%;text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;line-height: 27px;padding: 0px 6px 0px 2px;vertical-align: top;padding-left:2px;">&nbsp;示例：缤纷夏日 省心省力。用途：显示在用户收件箱中的标题，请用心哦，这样才会提高打开率！</div></td>
								</tr>
								<tr><td colspan="4" style="text-align:left;border:0;padding-left:0px;padding-top:0;padding-top: 4px; padding-bottom: 0px;">
										<input type="checkbox" {%if $is_insert_ad == 1%}checked {%/if%} value="{%$is_insert_ad%}" style="margin-right:5px;margin-top: 0px;height: 20px;" id="is_insert_ad" name="is_insert_ad">
										<label style="cursor:pointer" for="is_insert_ad">以下域名的邮件主题前加(AD)</label>
									</td>
								</tr>
								<tr>
									<td colspan="4" height="30" style="font-size:12px;text-align:left;border:0;padding-left:0px;padding-top:0;"><input type="text" id="domainAD" name="domainAD" value="{%$domainAD%}" style="width:100%;" class="inwbk_re">
									</td>
								</tr>								
								<tr><td colspan="4" height="32" style="text-align: left; padding:0px;border:0;">
									<table width="100%">
										<tbody>
											<tr>
												<td width="7%" height="30" style="font-size: 12px;font-weight: bold;text-align: left;padding: 0;border:0;">邮件地址：</td>
												<td width="92%" style="text-align: left;padding:8px 0;border:0;">
													<table width="100%">
														<tbody>
															<tr>
																<td style="width:12%;text-align: left;padding:0;border:0;font-weight: bold;">
																	<a id="up_file" style="width:60%;background: none repeat scroll 0 0 #E6E6E6;border: 1px dashed #ACACAE;border-radius: 5px;display: block;font-size: 12px;padding: 0 10px;text-align:center;cursor:pointer;float:left;height:22px;line-height:22px;line-height:23px\9;" href="#myModal" role="button" data-toggle="modal">导入地址</a>
																</td>
																<td style="width:13%;text-align: left;padding:0;border:0;font-weight: bold;">
																	<a id="select_group" style="width:70%;background: none repeat scroll 0 0 #E6E6E6;border: 1px dashed #ACACAE;border-radius: 5px;display: block;font-size: 12px;padding: 0 10px;text-align:center;cursor:pointer;float:left;height:22px;line-height:22px;line-height:23px\9;" href="#myModal2" role="button" data-toggle="modal">选择联系人</a>
																</td>
																<td style="width:75%;text-align: left;padding:0;border:0;font-size:14px;">选择的地址个数：<span id="email_count">{%if $count%}{%$count%}{%else if%}0{%/if%}</span></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
								<tr>
									<td id="selperson" colspan="4" style="text-align: left;padding:0;border:0;font-size:12px;">
										{%section name=loop loop=$gnames%}
										{%if $gnames[loop] == 'all'%}
											<span href='#myModal3' role='button' onclick='postval(this)' data-toggle='modal' id="{%$gnames[loop]%}"><a href="javascript:person({%$gnames[loop]%})" id="{%$gnames[loop]%}">全部联系人</a>&nbsp;&nbsp;&nbsp;</span><a name="{%$gnames[loop]%}" class="icon-remove" onclick="del(this)" id="del_{%$gnames[loop]%}" href="javascript:void(0)"></a>&nbsp;&nbsp;&nbsp;&nbsp;
										{%else%}
											<span href='#myModal3' role='button' onclick='postval(this)' data-toggle='modal' id="{%$gnames[loop]%}"><a href="javascript:person({%$gnames[loop]%})" id="{%$gnames[loop]%}">{%$gnames[loop]%}</a>&nbsp;&nbsp;&nbsp;</span><a name="{%$gnames[loop]%}" class="icon-remove" onclick="del(this)" id="del_{%$gnames[loop]%}" href="javascript:void(0)"></a>&nbsp;&nbsp;&nbsp;&nbsp;
										{%/if%}
										{%/section%}
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 0px 8px 0px 0px;border:0;font-size:12px;padding-rignt:8px;"><textarea style="padding: 5px 3px; width: 100%;resize: none;" rows="5" cols="80" id="email_list" name="email_list">{%$receivers%}</textarea></td>
								</tr>		
								<tr>
									<td colspan="4" height="35" style="text-align: left;padding:0;border:0;font-size:12px;color:#999999;">您还可以在此添加额外地址,请使用逗号或者换行分隔符进行分隔,字数请不要超过1000字,如:lucky@maildata.cn,beauty@maildata.cn
									</td>
								</tr>
								<tr>
									<td colspan="4" style="width:20%;text-align: left; padding: 8px 0px 8px 0px;border:0;font-weight: bold;">
										<span style="display: block;float:left;height: 24px;line-height: 24px;">选择筛选器：&nbsp;&nbsp;</span><a style="background: none repeat scroll 0 0 #E6E6E6;border: 1px dashed #ACACAE;border-radius: 5px;display: block;float:left;font-size: 12px;padding: 0 10px;text-align: center;cursor:pointer;height:22px;line-height:22px;line-height:23px\9;width: 110px;" id="s_filter" href="#selectfilter" role="button" data-toggle="modal">选择一个筛选器</a><div id="appendsfilter" style="margin: 3px 0px;display: block;float:left;height: 24px;line-height: 24px;margin: 3px 0px 3px 20px;">
										{%if $filterone != null%}
											{%section name=loop loop=$filterone%}
												<span class="see" id="filters" style="font-size:12px;color:#666666"><label for="">{%$filterone[loop].name%}</label><a href="javascript:void(0)" id="{%$filterone[loop].id%}" onclick="delfilter(this)"><i class="icon-remove" style="margin-left: 5px;"></i></a></span>
											{%/section%}
										{%/if%}	
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 8px 0;border:0;font-weight: bold;">
										<span style="float: left;">内容来源：</span>
										<!--获取网络模板-->
										<a style="width:7%;display: block;background: #e6e6e6;border: 1px dashed #acacae;border-radius: 5px;font-size: 12px;height:22px;line-height:22px;line-height:23px\9;text-align: center;padding: 0 10px;float: left;margin-right: 9px;" href="#load_site" data-toggle="modal">网站获取</a>
										<div id="load_site" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:42%;">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel">获取网络模板</h3>
												</div>
												<div class="modal-body">					
													<input type="text" name="webURL" value="http://" id="focusedInput" style="width:430px;height:30px;border:1px solid #A9A9A9;border-radius: 3px;margin-left: 40px;">
												</div>										
												<div class="modal-footer">
													<button id="reads" type="button" class="btn btn-primary" style="margin-right: 10px;">确认</button>
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>											
										</div>
										<!--网页上传-->
										<a style="width:7%;display: block;background: #e6e6e6;border: 1px dashed #acacae;border-radius: 5px;font-size: 12px;height:22px;line-height:22px;line-height:23px\9;text-align: center;padding: 0 10px;float: left;margin-right: 9px;" href="#load_file" data-toggle="modal">上传网页</a>
										<div id="load_file" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:42%;">
											<!--<form action="/task/designtask" method="post">-->
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel">上传网页</h3>
												</div>
												<div class="modal-body">					
													<input type="file" id="inputfile"  name="files" style="height:30px;">
													<p style="font-size:12px;">上传文件类型为：html，htm，大小不超过200K</p>
												</div>										
												<div class="modal-footer">
													<a id="webfile" class="btn btn-primary" style="margin-right: 10px;">确认</a>
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>
											<!--</form>-->
										</div>
				<a id="load_public_template" style="width:7%;display: block;background: #e6e6e6;border: 1px dashed #acacae;border-radius: 5px;font-size: 12px;height:22px;line-height:22px;line-height:23px\9;text-align: center;padding: 0 10px;float: left;margin-right: 9px;" href="#seltpl" data-toggle="modal">选择模板</a>

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
									 	{%section name=val loop=$rows%}
								        <dd id="{%$rows[val].id%}"><a href="javascript:void(0);" class="default3" style="{%$rows[val].id%}">{%$rows[val].vocation_name%}</a></dd>
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
										<a style="width:7%;display: block;background: #e6e6e6;border: 1px dashed #acacae;border-radius: 5px;font-size: 12px;height:22px;line-height:22px;line-height:23px\9;text-align: center;padding: 0 10px;float: left;margin-right: 9px;" href="#in_par" data-toggle="modal">插入变量</a>
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
													<input type="button" id="variable" data-dismiss="modal" value="确认" class="btn btn-primary" style="margin-right: 10px;">
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>
											</form>
										</div>
										<a id="btn_view" style="width:7%;display: block;background: #e6e6e6;border: 1px dashed #acacae;border-radius: 5px;font-size: 12px;height:22px;line-height:22px;line-height:23px\9;text-align: center;padding: 0 10px;float: left;margin-right: 9px;" href="javascript:void(0);">预览模板</a>
										<a style="background: none repeat scroll 0 0 #E6E6E6;border: 1px dashed #ACACAE;border-radius: 5px;display: block;float:left;font-size: 12px;padding: 0 10px;text-align: center;cursor:pointer;height:22px;line-height:22px;line-height:23px\9;width: 80px;" id="s_filter" href="#selectsubscription" role="button" data-toggle="modal">选择订阅</a>
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 8px 0;border:0;">	
										<textarea id="texts" name="content" cols="20" rows="2" >{%$content%}</textarea>
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 10px 0px 0px;border:0;">
										<p style="border-bottom: 1px solid #A4A4A4;display: block;height: 26px;margin-bottom: 10px;width: 100%;"><span style="background: none repeat scroll 0 0 #A4A4A4;border-radius: 5px 5px 0 0;color: #FFFFFF;display: block;font-size: 12px;font-weight: bold;height: 26px;line-height: 26px;text-align: center;width: 10%;">高级功能</span></p>
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 0px 10px 0px 20px;border:0;font-weight: bold;font-size: 13px;" width="81" align="right" height="32">
										<!-- 附件：<input type="file" style="width:25%;border: 1px solid #BFBFBF;height: 30px;line-height: 20px;font-size:12px;padding-left: 5px;" name="filename" id="fileupload_app"> -->
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
									{%if strstr($filename,$attachs[loop].truename)%}
									<tr id="{%$attachs[loop].id%}">
										<td width="20%" style="text-align:center" class="{%$attachs[loop].path%}"><input type="checkbox" name="selattach[]" value="{%$attachs[loop].truename%}" checked="checked"/></td>
										<td style="text-align:center"><a title="{%$attachs[loop].truename%}">{%$attachs[loop].truename|truncate:35:"..."%}</a></td>
										<td style="text-align:center">{%$attachs[loop].createtime%}</td>
									</tr>
									{%else%}
									<tr id="{%$attachs[loop].id%}">
										<td width="20%" style="text-align:center" class="{%$attachs[loop].path%}"><input type="checkbox" name="selattach[]" value="{%$attachs[loop].truename%}"></td>
										<td style="text-align:center"><a title="{%$attachs[loop].truename%}">{%$attachs[loop].truename|truncate:35:"..."%}</a></td>
										<td style="text-align:center">{%$attachs[loop].createtime%}</td>
									</tr>
									{%/if%}
								{%/section%}
							</tbody>
								<tr><td colspan="3" style="text-align:center"><div class="fenpages" style="float:left; margin-top: 20px; margin-left: 15px;"></div><div class="holder" style="float:left;"></div></td></tr>
							</table>
						</div>
						<div class="modal-footer">
							<input aria-hidden="true" data-dismiss="modal" type="button" class="btn" id="slelectconfirm" value="确定" />
						</div>
					</div>

										{%if !empty($attachmentdata)%}
										<div id="appends" style="margin: 3px;">
											{%section name=loop loop=$attachmentdata%}
												<span class="see" id="{%$attachmentdata[loop].path%}" style="margin-left: 10px;"><label id="{%$attachmentdata[loop].truename%}" for="">{%$attachmentdata[loop].truename%}</label><a href="javascript:void(0)" id="{%$attachmentdata[loop].id%}" onclick="delmodattch(this)"><i class="icon-remove" style="margin-left: 10px;"></i></a></span>
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
		// dataType: 'text',
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
				
			$('#appends').append('<span class="see" id="'+arr.path+'" style="margin-left: 10px;"><label id="'+arr.filename+'" for="">'+arr.filename+'</label><a href="javascript:void(0)" id="'+arr.tmp_id+'" onclick="delmodattch(this)"><i class="icon-remove" style="margin-left: 10px;"></i></a></span>');
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

function delmodattch(obj){
		var val=obj.getAttribute('id');
		var fid=obj.parentNode;
		var path=fid.id;
		var fod = document.getElementById(path);
		var file = fod.firstChild.id;
		//alert(val);exit;
		$.post('/task/delmodattch',{'delid':val,'path':path},function(data){
			//alert(data);exit;
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
				con += '<span class="see" id="'+path+'" style="margin-left: 10px;"><label id="'+filename+'" for="">'+filename+'</label><a href="javascript:void(0)" id="'+aid+'" onclick="delmodattch(this)"><i class="icon-remove" style="margin-left: 10px;"></i></a></span>';
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
									<td colspan="4" style="text-align: left;padding: 0px 10px 0px 35px;border:0;color: #999999;font-size: 12px;" width="81" height="30">
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
									{%if $reportemail != ''%}
										<input class="inwbk_re" type="text" style="width:92%;" value="{%$reportemail%}" name="reportemail" id="reportemail">
									{%else%}	
										<input class="inwbk_re" type="text" style="width:92%;" value="{%$sendemail%}" name="reportemail" id="reportemail">
									{%/if%}
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 10px 0;border:0;">
										<p style="border-bottom: 1px solid #A4A4A4;display: block;height: 26px;margin-bottom: 10px;width: 100%;"><span style="background: none repeat scroll 0 0 #A4A4A4;border-radius: 5px 5px 0 0;color: #FFFFFF;display: block;font-size: 12px;font-weight: bold;height: 26px;line-height: 26px;text-align: center;width: 10%;">内容设置</span></p>
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 0px 10px 0px 20px;border:0;font-weight: bold;line-height:20px;font-size: 13px;">随机发送：<label class="checkbox inline" style="padding-top: 0px;color: #999999;font-size: 12px;vertical-align: middle;">
										<input type="checkbox" id="inlineCheckbox1" name="random" value="1" checked>当选中此项时将会以随机打散的方式为您发送该任务，这样可以很大提升邮件的到达率</label></td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 8px 10px 0px 20px;border:0;font-weight: bold;line-height:20px;font-size: 13px;"><span style="float: left;display: inline-block;">发送计划：</span>
									{%if $sendtype == 0%}
										<label class="radio" style="margin-left: 20px;"><input type="radio" class="cls_server_send" name="is_server_send" value="{%$sendtype%}" checked>立即</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="1" >定时</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="2">周期</label>
									{%else if $sendtype == 1%}
										<label class="radio" style="margin-left: 20px;"><input type="radio" class="cls_server_send" name="is_server_send" value="0">立即</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="1" checked>定时</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="2">周期</label>
									{%else%}
										<label class="radio" style="margin-left: 20px;"><input type="radio" class="cls_server_send" name="is_server_send" value="0">立即</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="1" >定时</label>
										<label class="radio" style="margin-left: 50px;"><input type="radio" class="cls_server_send" name="is_server_send" value="2" checked>周期</label>
									{%/if%}
									</td>
									 <script type="text/javascript" >
										 jQuery(function(){
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
										<div id="timer_send" style="line-height: 20px; border: 2px dotted rgb(204, 204, 204); width: 76%; padding-top: 10px; padding-bottom: 10px; padding-left: 10px; display: block;">请设置发送时间：<div class="input-append date form_datetime" style="margin-right: 1px;margin-top: -4px;">
										{%if $sendtype == 1%}
										<input size="16" name="start_time" id="start_time" type="text" value="{%$sendtime%}" onfocus="" style="width:200px;" readonly><span class="add-on"><i class="icon-th"></i></span></div><br/>定时计划会在您设定的时间开始尝试发送邮件。在此之前，您还可以撤销发送计划对邮件进行编辑。
										{%else%}
										<input size="16" name="start_time" id="start_time" type="text" value="" onfocus="" style="width:196px;" readonly><span class="add-on" style="padding-bottom: 0px\9;height:19px\9;"><i class="icon-th"></i></span></div><br/>定时计划会在您设定的时间开始尝试发送邮件。在此之前，您还可以撤销发送计划对邮件进行编辑。
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
													<input size="16" name="next_end_time" id="next_end_time" type="text" value="" onfocus="" style="width:196px;" readonly><span class="add-on" style="padding-bottom: 0px\9;height:19px\9;"><i class="icon-th"></i></span>
												{%else%}
													<input size="16" name="next_end_time" id="next_end_time" type="text" value="{%$cycle_end_time%}" onfocus="" style="width:200px;" readonly><span class="add-on"><i class="icon-th"></i></span>
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
									<td colspan="4" style="text-align: left;padding: 8px 0;border:0;"></td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 8px 0;border:0;">
										<div style="margin-left: 15px;" id="testbox" class="bj_nr">
											<strong>测试邮箱：</strong><input id="testemail" class="cemail" type="text" value="" placeholder="请输入测试的邮箱" name="test_email" style="width:315px;height: 25px;  padding:0 3px;margin-left: 13px;"><button id="testsend" class="btn" type="button"  style="margin-left:8px;width:140px;height:27px;background:url(/dist/img/but_test.jpg) no-repeat;border-radius: 3px 3px 3px 3px;">测试发送</button>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align: left;padding: 20px 0px 8px 15px;border:0;">
										<button id="send_btn" class="btn" type="button" style="width:85px;height:27px;background:url(/dist/img/but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;margin-right: 5px;">提交</button>
										<button id="draft_btn" class="btn" type="button" style="width:85px;height:27px;background:url(/dist/img/but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;margin-right: 5px;">存稿</button>
									</td>
								</tr>
							</tbody>
						</table>
						</form>
					</div>
				</div>
			</div>
		</div>

  </div>
</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
		<h3 id="myModalLabel" class="text-left">导入地址</h3>
	</div>
	<p> </p>
	<div  class="modal-body">
		<table>
		<!--<input type="hidden" name="import" value="import" />-->
		<input type="hidden" name="tid" value="{%$taskId%}">
			<tr height="40px">
				<td width="100px"><b>文件导入：</b></td>
				<td><input type="file" name="fileToUpload" id="fileToUpload" style="height: 28px;"/></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><span style="font-size:12px;">(*导入文件类型为：xls，xlsx)</span></td>
			</tr>
			<tr height="40px">
				<td><b>分隔符：</b></td>
				<td><input type="radio" style="margin-top:0px;" name="subchar" checked value="1" />逗号&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" style="margin-top:0px;" name="subchar" value="0" />空格</td>
			</tr>
			<tr height="40px">
				<td><b>联系人组：</b></td>
				<td>新建组<input type="text" name="gname" id="gname" /></td>
				<td id="td1"></td>
			</tr>
		</table>
	</div>
	<p> </p>
	<div class="modal-footer">
		<input type="button" class="btn" id="bt" value="保存" />
	</div>
</div>
<div id="myModal2" style="width:600px" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="addtask" method="post" name="myform2" enctype="multipart/form-data" id="myform2">
		<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h3 id="myModalLabel" class="text-left">选择联系人组</h3>
		</div>
		<p> </p>
		<div  class="modal-body">
			<div>
				
			</div>
			<table width="100%" align="center">
				<tr class="modal-header">
					<td width="25%" style="text-align:center"></td>
					<td width="35%" style="text-align:center">组名称</td>
					<td width="40%" style="text-align:center">联系人数</td>
				</tr>
				{%if $gnames[0] == 'all'%}
				<tr>
					<td width="25%" style="text-align:center"><input type="checkbox" name="gname[]" id="checkAll" value="all" checked=checked>&nbsp;全选</td>
					<td width="35%" style="text-align:center">全部联系人</td>
					<td width="40%" style="text-align:center">{%$countAll%}</td>
				</tr>
				{%else%}
				<tr>
					<td width="25%" style="text-align:center"><input type="checkbox" name="gname[]" id="checkAll" value="all">&nbsp;全选</td>
					<td width="35%" style="text-align:center">全部联系人</td>
					<td width="40%" style="text-align:center">{%$countAll%}</td>
				</tr>
				{%/if%}
				<tbody id="itemContainer">
				{%section name=loop loop=$groups%}
					{%if !empty($gnames) && in_array($groups[loop].gname,$gnames)%}
						<tr>
							<td width="25%" style="text-align:center"><input type="checkbox" name="gname[]" value="{%$groups[loop].gname%}" checked=checked></td>
							<td width="35%" style="text-align:center"><a title="{%$groups[loop].gname%}">{%$groups[loop].gname|truncate:30:"..."%}</a></td>
							<td width="40%" style="text-align:center">{%$groups[loop].count%}</td>
						</tr>
					{%else%}
						<tr>
							<td width="25%" style="text-align:center"><input type="checkbox" name="gname[]" value="{%$groups[loop].gname%}"></td>
							<td width="35%" style="text-align:center"><a title="{%$groups[loop].gname%}">{%$groups[loop].gname|truncate:30:"..."%}</a></td>
							<td width="40%" style="text-align:center">{%$groups[loop].count%}</td>
						</tr>
					{%/if%}
				{%/section%}
				</tbody>
				<tr><td colspan="3" style="text-align:center"><div class="fenpages" style="float:left; margin-top: 20px; margin-left: 60px;"></div><div class="holder" style="float:left;"></div></td></tr>
			</table>
		</div>
		<p> </p>
		<div class="modal-footer">
			<input aria-hidden="true" data-dismiss="modal" type="button" class="btn" id="bt2" value="选择" />
		</div>
	</form>
</div>
<div id="myModal3" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="addtask" method="post" enctype="multipart/form-data" id="myform3">
		<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h3 id="myModalLabel" class="text-left">查看联系人</h3>
		</div>
		<p> </p>
		<div  class="modal-body">
			<table width="100%">
				<thead>
					<tr style="text-align:center">
						<td width="40%">邮件地址</td>
						<td width="60%">姓名</td>
					</tr>
				</thead>
				<tbody id="person">
				
				</tbody>
			</table>
		</div>
		<p> </p>
		<div class="modal-footer">
			<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
		</div>
	</form>
</div>
<div id="selectfilter" style="width:600px" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h3 id="myModalLabel" class="text-left">选择筛选器</h3>
		</div>
		<p> </p>
		<div  class="modal-body">
			<div>
				
			</div>
			<table width="100%" align="center" class="table table-striped">
				<tr class="modal-header" style="font-size:14px;">
					<td width="30%" style="text-align:center">选择</td>
					<td width="70%" style="text-align:left">筛选器名称</td>
				</tr>
				<tbody id="filterlist">
				{%section name=loop loop=$filters%}
					{%if $filters[loop].id == $filtersId %}
					<tr>
						<td style="text-align:center"><input type="checkbox" name="filtersId[]" value="{%$filters[loop].id%}" id="{%$filters[loop].name%}" checked=checked/></td>
						<td style="text-align:left">{%$filters[loop].name%}</td>
					</tr>
					{%else%}
					<tr>
						<td style="text-align:center"><input type="checkbox" name="filtersId[]" value="{%$filters[loop].id%}" id="{%$filters[loop].name%}"></td>
						<td style="text-align:left">{%$filters[loop].name%}</td>
					</tr>
					{%/if%}
				{%/section%}
				</tbody>
				<tr><td colspan="2" style="text-align:center"><div class="fenpages" style="float:left; margin-top: 20px; margin-left: 60px;"></div><div class="holder" style="float:left;"></div></td></tr>
			</table>
		</div>
		<p> </p>
		<div class="modal-footer">
			<input aria-hidden="true" data-dismiss="modal" type="button" class="btn" id="confilter" value="选择" />
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
<script type="text/javascript">
$(function(){
	$("#bt").click(function(){
		var filename=$("#fileToUpload").val(); 
        var extStart=filename.lastIndexOf("."); 
        var ext=filename.substring(extStart,filename.length).toUpperCase(); 
		var tag = true;
		if(ext != ".XLS" && ext != ".XLSX"){ 
			   $('#myModal').modal('hide');
               art.dialog.alert("导入文件格式不正确，文件限于xls，xlsx格式！",function(){$('#myModal').modal('show');});
			   return false;
		}
		var gname = $("#gname").val();
		var subchar = $("input:radio[name='subchar']:checked").val();
		if(gname == ''){
			$('#myModal').modal('hide');
			art.dialog.alert("请填写组名",function(){$('#myModal').modal('show');});
			return false;
		}
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/task/ajaxgroup",
			async:false,
			data:{'gname':gname,'subchar':subchar},
			success:function(data){
				if(data){
					tag = false;
					$("#td1").text("该组已经存在");
					$("#td1").attr("class","col");
					return false;
					
				}
			}
		});
		if(tag == false){
			return tag;
		}
		if(filename != ''){
			return ajaxFileUploadAddr();
		}
	});
	
	function ajaxFileUploadAddr(){
		var gname = $("#gname").val();
		$.ajaxFileUpload ( {
			url:'/task/uploadaddr',
			secureuri:false,
			fileElementId:'fileToUpload',
			dataType: 'json',
			//csrfdata:'<?=$this->csrf->getToken()?>',
			data:{'gname':gname},
			success: function (data)
			{
				if(data){					
					if (data == 1){
						$('#myModal').modal('hide');
						art.dialog.alert("请选择要导入的Excel文件！",function(){$('#myModal').modal('show');});
						return false;
					}else if(data == 2){
						$('#myModal').modal('hide');
						art.dialog.alert("导入联系人地址失败，请检查文件内容，再重新上传",function(){$('#myModal').modal('show');});
						return false;
					}else{
						$('#myModal').modal('hide');
					}
				} 
			},
			error: function (data)
			{
				$('#myModal').modal('hide');
				art.dialog.alert("导入联系人地址失败，请检查文件内容，再重新上传",function(){$('#myModal').modal('show');});
				return false;
			}
		});
	}
	
	$('#select_group').on('click',function(){
		var garr = $("#selperson").find("span");
		var groupname = ",";
		$.each(garr,function(i,result){
			groupname += result.id+",";
		})
		var con='';
		$.post('/task/searchgroups',function(data){
			var v=eval('('+data+')');
			var str = '';
			$.each(v, function( i , arr){ 
				if (groupname.indexOf("," + arr.gname + ",") != -1) {
					con += '<tr><td width="25%" style="text-align:center"><input type="checkbox" checked name="gname[]" value="'+arr.gname+'"></td><td width="35%" style="text-align:center"><a title="{%$groups[loop].gname%}">'+arr.gname+'</a></td><td width="40%" style="text-align:center">'+arr.count+'</td></tr>';
				} else {
					con += '<tr><td width="25%" style="text-align:center"><input type="checkbox" name="gname[]" value="'+arr.gname+'"></td><td width="35%" style="text-align:center"><a title="{%$groups[loop].gname%}">'+arr.gname+'</a></td><td width="40%" style="text-align:center">'+arr.count+'</td></tr>';
				}  
				$('#itemContainer').html(con);
				$('#myModal2').modal('show');
			});
		});
	});
	
	$("#checkAll").click(function(){
		var g=$("input[name='gname[]']:checked").eq(0).val();
		if(g == 'all'){
			$("input[name='gname[]']").not($(this)).removeAttr("checked");
			$("input[name='gname[]']").not($(this)).attr("disabled",true);
		}else{
			$("input[name='gname[]']").attr("disabled",false);
		}  
	}); 
	
	$("#bt2").click(function(){
		var s="";
		var group="";
		var gname = $("input[name='gname[]']:checked").each(function(){
			if($(this).val()!=1){
				if($(this).val()=='all'){
					var groupname='全部联系人';
				}else{
					var groupname=$(this).val();
				}
				s+="<span href='#myModal3' role='button' onclick='postval(this)' data-toggle='modal' id='"+$(this).val()+"'><a href='javascript:person("+$(this).val()+")' id='"+$(this).val()+"'>"+groupname+"</a>&nbsp;&nbsp;&nbsp;</span><a name='"+$(this).val()+"' class='icon-remove' onclick='del(this)' id='del_"+$(this).val()+"' href='javascript:void(0)'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
				//if($(this).val()=='all') return false;
				group += $(this).val()+",";
			}
		});
		var arr = s.split("&nbsp;&nbsp;&nbsp;&nbsp;");//alert(arr.length);
		var tag = true;
		var reg = /.*(id='all').*/;
		if(arr.length-1 > 1){
			$.each(arr,function(index,tx){
				if(reg.test(tx)){
					tag = false;
					$('#myModal2').modal('hide');
					art.dialog.alert("不能同时选择全部联系人和单个联系人组",function(){$('#myModal2').modal('show');});
					return false;
				}
			})
		}

		//return false;
		if(s == ''){
			$('#myModal2').modal('hide');
			art.dialog.alert("请选择联系人组",function(){$('#myModal2').modal('show');});
			return false;
		}
		if(tag == false){
			return tag;
		}
		$("#selperson").html(s);
		//alert(group);
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/task/getcount",
			data:{'group':group},
			success:function(data){
				//alert(data);
				$("#email_count").text(data);
				$("#tnum").val(data);
			}
		})
		var arr = $("#selperson").find("span");
		var groupid = "";
		$.each(arr,function(i,result){
			groupid += result.id+",";
		})
		//alert(groupid);
		$("#groupid").val(groupid);
		var filtersId=$("#filtersId").val();
		if(filtersId != ''){
			$.post('/task/selectfilter',{'fid':filtersId,'groups':groupid},function(data){
				if(data){
					$("#email_count").text(data);
					$("#tnum").val(data);
				}
			})
		}
	})
})

	function person(obj){
			//alert(111);
	}
	function postval(obj){
			var str = "";
			$("#person").empty();
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/task/ajaxgetinfo",
				data:{'gname':obj.id},
				success:function(data){
					if(data){
						$.each(data,function(index,bl){
							str = "<tr><td style='text-align:center'>"+bl.mailbox+"</td><td style='text-align:center'>"+bl.username+"</td></tr>";
							$("#person").append(str);
						})
					}
				}
			})
	}

	function del(obj){
		var groupid=$('#groupid').val();
		var fid=$("#filtersId").val();
		var d=obj.id;
		var st=d.split('_').pop();
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/task/ajaxgroupcount",
			data:{'gname':st,'groupid':groupid,'fid':fid},
			success:function(data){
				if(data){
					//alert(data.gnames);
					var num = $("#email_count").text()-data.count;
					$("#email_count").text(num);
					$("#tnum").val(num);
					$("#groupid").val(data.gnames);
				}
			}
		})
		$('#'+d).remove();
		$('#'+st).remove();
	}

	//选择过滤器
	$(function(){
		$("#confilter").click(function(){
			var str='';
			var filternum = $("input[name='filtersId[]']:checked").length;
			if(filternum > 1){
				$('#selectfilter').modal('hide');
				art.dialog.alert('对不起,您不可以选择多个过滤项',function(){$('#selectfilter').modal('show');});
				return false;
			}
			var filtersId=$("input[name='filtersId[]']:checked").val();
			var filtersname=$("input[name='filtersId[]']:checked").attr("id");
			if(filtersId){
					$("#filtersId").attr('value',filtersId);
					$('#appendsfilter').html('<span class="see" id="filters" style="font-size:12px;color:#666666"><label for="">'+filtersname+'</label><a href="javascript:void(0)" id="'+filtersId+'" onclick="delfilter(this)"><i class="icon-remove" style="margin-left: 5px;"></i></a></span>');
					var groupid=$('#groupid').val();
					$.post('/task/selectfilter',{'fid':filtersId,'groups':groupid},function(data){
						if(data){
							var email_group=$("#email_count").text(data);
							$("#tnum").val(data);
						}
					})
			}else{
				art.dialog.alert('请选择筛选器！',function(){$('#selectfilter').modal('show');});
			}
		});
		$('#selectfilter').on('shown',function(){
			$(function(){
				$("div.holder").jPages("destroy");
				//$('#itemContainer').show();
				$("div.holder").jPages({
					containerID:"filterlist",
					perPage    : 5,
					first      : "首 页", 
					previous : "上一页",
					next : "下一页",
					last   :"尾 页",
					//midRange   : 5,
					//endRange   : 1,
					delay      : 0,
					callback   : function(pages,items){
						$(".fenpages").html("共有<b>" + items.count +"</b>条记录  <b>" + pages.current + "</b>/<b>" +pages.count +"</b>&nbsp;&nbsp;");
					}
				});
			});
		});
	});

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
	
	function delfilter(obj){
		$('#'+obj.id).parent().remove();
		$("#filtersId").attr('value','');
		var groupid=$('#groupid').val();
		$.post('/task/selectfilter',{'fid':'','groups':groupid},function(data){
			if(data){
				var email_group=$("#email_count").text(data);
				$("#tnum").val(data);
			}
		})
	}
	
//加载编辑器
CKEDITOR.replace('texts');

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

//添加分类
$(function(){
	$('#sudu').on('click',function(){
		//alert(123);exit;
	    var inputname=$('#inputuser').val();
		var inputcontent=$('#inputinput').val();
		if(inputname == ""){
			$('.style_type').html("<font color='red'>任务分类名称不能为空</font>");
			return false;
		}else{
			$.get('/templet/addvocation',{'names':inputname,'content':inputcontent},function(data){
            if(data == "error" ){
                $('.style_type').html("<font color='red'>该任务分类名称已存在</font>");
                //$('.style_prl').slideDown(500);
				//alert(12345);
				return false;

            }else{
				$('.style_type').text("恭喜，该任务分类名称可以使用！");
				$('#tasktype').get(0).add(new Option(inputname,data), null)
				$('#quick_add').modal('hide');
				 $('#quick_add').on('hidden',function(){
                  $('#inputname').val(""); 
                  $('#inputcontent').val("");
                });
            }  
          });
        	return false;

		}  
	})
})

$(function(){
	//点击button判断form表单是的值后提交表单
	$('#send_btn').on('click', function(){
	
		//验证任务名称
		var taskname=$('#taskname').val();
		if(taskname == ""){
			art.dialog.alert('创建任务名称不能为空');
			return false;
		}
		//验证主题
		var subject=$('#subject').val();
		//alert(subject.length);
		if(subject == ""){
			art.dialog.alert('主题不能为空');
			return false;			
		}
		if(subject.length < 2 || subject.length > 150){
			art.dialog.alert('对不起,您设定发送邮件的标题长度不符合标准（字数范围2-150）');
			return false;
		}
		
		//验证域名
		var domainAD=$('#domainAD').val();
		adArray = domainAD.split(',');
		for(var value in adArray){
			var reg = /^[A-Za-z0-9]+\.[A-Za-z]+$/;
			if(!reg.test(adArray[value])){
				art.dialog.alert('您填写的域名格式不正确,请重新填写!');
				return false;
			}
		}
		
		//验证收件人
		var sender=$('#sender').val();
		if(sender == ""){
			art.dialog.alert('收件人不能为空');
			return false;			
		}
		if(sender.length < 2){
			art.dialog.alert('请输入至少2位的用户名');
			return false;
		}
		
		//验证邮箱
		var sendemail=$('#sendemail').val();
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
		if(sendemail == ""){
			art.dialog.alert('发件人邮箱不能为空');
			return false;			
		}
		if(!reg.test(sendemail)) {
			art.dialog.alert('您填写的邮箱格式不正确,请重新填写!');
			return false;
		}
		
		//验证回复人地址replaymail
		var replyemail=$('#replyemail').val();
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
		if(replyemail == ""){
			art.dialog.alert('回复邮箱地址不能为空');
			return false;			
		}
		if(!reg.test(replyemail)) {
			art.dialog.alert('您填写的邮箱格式不正确,请重新填写!');
			return false;
		}
		
		//验证收件人
		var groupid=$('#groupid').val();
		var email_group=$("#email_count").text();
		//alert(email_group);exit;
		var email_list=$('#email_list').val();
		if(email_list == "" && email_group == '0'){
			art.dialog.alert('邮件地址不能为空');
			return false;	
		}
		if(email_list != ""){
			var email_list = email_list.replace(/\n/g,",");
			var emailaddress=ReplaceSeperator(email_list);
			if(emailaddress == false){
				art.dialog.alert('您填写的邮箱格式不正确或有非法字符,请重新填写!');
				return false;
			}
		}
		
		//验证任务分类
		var tasktype=$('#tasktype').val();
		if(tasktype == "0"){
			art.dialog.alert('请选择任务分类');
			return false;			
		}
		
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
		
		//提交任务	
		var uid=$('#uid').val();
		var tid=$('#tid').val();
		$.post('/task/checktask',{'taskname':taskname,'id':tid,'uid':uid},function(data){
			//alert(data);exit;
			//如果调用php成功
			if(data ==  'error'){
					art.dialog.alert('该任务名称已经存在');
					$("#taskname").val("").focus();
					$("#checktaskname").html("<font color='red' >该任务名称已经存在，请重新输入</font>");
					$("#taskname").focus(function(){
						$("#checktaskname").html('任务名称，请确认不要重复');
					})
					exit;
					return false;
			}else{
				$("#formAdd").attr("action", "/task/quickctask");
				$('#formAdd').submit();
			}
		})		

	})
	
	//存草稿
	$('#draft_btn').on('click', function(){
		//验证任务名称
		var taskname=$('#taskname').val();
		if(taskname == ""){
			art.dialog.alert('创建任务名称不能为空');
			return false;
		}

		//验证主题
		var subject=$('#subject').val();
		//alert(subject.length);
		if(subject == ""){
			art.dialog.alert('主题不能为空');
			return false;			
		}
		if(subject.length < 2 || subject.length > 150){
			art.dialog.alert('对不起,您设定发送邮件的标题长度不符合标准（字数范围2-150）');
			return false;
		}
		
		//验证收件人
		var sender=$('#sender').val();
		if(sender == ""){
			art.dialog.alert('收件人不能为空');
			return false;			
		}
		if(sender.length < 2){
			art.dialog.alert('请输入至少2位的用户名');
			return false;
		}
		
		//验证邮箱
		var sendemail=$('#sendemail').val();
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
		if(sendemail == ""){
			art.dialog.alert('发件人邮箱不能为空');
			return false;			
		}
		if(!reg.test(sendemail)) {
			art.dialog.alert('您填写的邮箱格式不正确,请重新填写!');
			return false;
		}
		
		//验证回复人地址replaymail
		var replyemail=$('#replyemail').val();
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
		if(replyemail == ""){
			art.dialog.alert('回复邮箱地址不能为空');
			return false;			
		}
		if(!reg.test(replyemail)) {
			art.dialog.alert('您填写的邮箱格式不正确,请重新填写!');
			return false;
		}
		
		//验证收件人
		var groupid=$('#groupid').val();
		var email_group=$("#email_count").text();
		//alert(email_group);exit;
		var email_list=$('#email_list').val();
		if(email_list == "" && email_group == '0'){
			art.dialog.alert('邮件地址不能为空');
			return false;	
		}
		if(email_list != ""){
			var email_list = email_list.replace(/\n/g,",");
			var emailaddress=ReplaceSeperator(email_list);
			if(emailaddress == false){
				art.dialog.alert('您填写的邮箱格式不正确或有非法字符,请重新填写!');
				return false;
			}
		}
		
		//验证任务分类
		var tasktype=$('#tasktype').val();
		if(tasktype == "0"){
			art.dialog.alert('请选择任务分类');
			return false;			
		}
		//验证接收报告邮箱
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
		
		var uid=$('#uid').val();
		var tid=$('#tid').val();
		$.post('/task/checktask',{'taskname':taskname,'id':tid,'uid':uid},function(data){
			//alert(data);exit;
			//如果调用php成功
			if(data ==  'error'){
					art.dialog.alert('该任务名称已经存在');
					$("#taskname").val("").focus();
					$("#checktaskname").html("<font color='red' >该任务名称已经存在，请重新输入</font>");
					$("#taskname").focus(function(){
						$("#checktaskname").html('任务名称，请确认不要重复');
					})
					return false;
			}else{
				$("#formAdd").attr("action", "/task/quickctask");
				$('#draft').val(1);
				$('#formAdd').submit();
			}		
		})		
		
	})
	
	//验证测试邮箱后提交
	$('#testsend').on('click', function(){
		var tid=$('#tid').val();
		var uid=$('#uid').val();
		var subject=$('#subject').val();
		var content=CKEDITOR.instances.texts.getData();;
		var path=$('#filepath').val();
		var filename=$('#oldname').val();
		var testemail=$('#testemail').val();
		var edit='edit_draft';
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
		if(testemail == ""){
			art.dialog.alert('邮箱不能为空');
			return false;			
		}
		if(!reg.test(testemail)) {
			art.dialog.alert('您填写的邮箱格式不正确,请重新填写!');
			return false;
		}
		//art.dialog.alert('存稿后才可发测试邮件');
		$.post('/task/inserttask',{'tid':tid,'uid':uid,'edit':edit,'testemail':testemail,'subject':subject,'content':content,'path':path,'filename':filename,'act':'test_send'},function(data){
			//alert(data);exit;
			if(data ==  'Success'){
				art.dialog.alert('测试邮件发送成功，请注意查收！');
			}else if(data ==  'Error'){
				art.dialog.alert('测试邮件发送失败,请检查报告发送配置！');
			}else{
				art.dialog.alert('sorry,发送测试邮件需要先在系统设置里设置报告发送配置！');
			}
		})
	});
}) 

	function ReplaceSeperator(str) {
		var i;
		var result = "";
		var c;
		var arr=str.split(","); 
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
		for (i = 0; i < arr.length; i++) {
			c = arr[i];
			if (!reg.test(c)){
				result = false;
				//return false;
			}else{
				//result += c + ',';
				result = true;
			}
		}
		return result;
	}

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
	//alert(123);exit;
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
  
  	//$('#select_group').click(function(){
	$('#myModal2').on('shown',function(){
			$(function(){
				$("div.holder").jPages("destroy");
				$("div.holder").jPages({
					containerID:"itemContainer",
					perPage    : 5,
					first      : "首 页", 
					previous : "上一页",
					next : "下一页",
					last   :"尾 页",
					//midRange   : 5,
					//endRange   : 1,
					delay      : 0,
					callback   : function(pages,items){
						$(".fenpages").html("共有<b>" + items.count +"</b>条记录  <b>" + pages.current + "</b>/<b>" +pages.count +"</b>&nbsp;&nbsp;");
					}
				});
			});
	});
	
   $('#reads').click(function(){
          var values=$('#focusedInput').val();
         // alert(values);exit;
          $.post('/templet/ajaxweb',{'files_contents':values},function(data){
            if(data){
				//alert(data);exit;
			  $('#load_site').modal('hide');
              CKEDITOR.instances.texts.setData(data) ;
              CKEDITOR.instances.texts.getData();
            }
          });
  });
  
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
	

$(function(){
	$('#collapseOne').collapse('show');
	$('#collapseThree').collapse('hide');
	$('#default').css('color',"#28b779");
	$('#default').blur(function(){$(this).css('color','#666666')});
	$('#default').focus(function(){$(this).css('color','#28b779')});

})

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
	  
	$(function(){
		$("#variable").click(function(){
			var cktext = CKEDITOR.instances.texts.getData();
			var str = "";
			$("input[name='par']:checked").each(function(){
				str += $(this).val();
			})
			CKEDITOR.instances.texts.setData(cktext+str);
		})
		$("#variable2").click(function(){
				var subject = $("#subject").val();
				var str2 = "";
				$("input[name='par2']:checked").each(function(){
					str2 += $(this).val();
				})
				$("#subject").attr("value",subject+str2);
		})
	})
</script>
<!--Footer-part-->
{%include file="footer.php"%}