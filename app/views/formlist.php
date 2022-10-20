{%include file="header.php"%}
<style>
 ul li{list-style:none}
</style>
<script type="text/javascript">
	function tab(str1,str2){
		$(function(){
			//alert($("div[name='box"+str+"']").css("display"));
			if($("div[name='box"+str1+str2+"']").css("display")=="none"){
				$("div[name='box"+str1+str2+"']").css("display","block");
				if(str1 == '001'){
					$("div[name='box002"+str2+"']").css("display","none");
					$("div[name='box003"+str2+"']").css("display","none");
				}else if(str1 == '002'){
					$("div[name='box001"+str2+"']").css("display","none");
					$("div[name='box003"+str2+"']").css("display","none");
				}else{
					$("div[name='box001"+str2+"']").css("display","none");
					$("div[name='box002"+str2+"']").css("display","none");
				}
			}else{
				$("div[name='box"+str1+str2+"']").css("display","none");
				if(str1 == '001'){
					$("div[name='box002"+str2+"']").css("display","none");
					$("div[name='box003"+str2+"']").css("display","none");
				}else if(str1 == '002'){
					$("div[name='box001"+str2+"']").css("display","none");
					$("div[name='box003"+str2+"']").css("display","none");
				}else{
					$("div[name='box001"+str2+"']").css("display","none");
					$("div[name='box002"+str2+"']").css("display","none");
				}
			}
		})
	}
	function packup(str){
		$(function(){
			$("div[name='"+str+"']").css("display","none");
		})
	}
	function del(id){
		var id = id;
		art.dialog.confirm("删除后无法恢复，你确定要删除吗?",function(){
			window.location.href="/contact/delform?id="+id;
		},function(){})
//		var result = art.dialog.confirm("删除后无法恢复，你确定要删除吗?");
//		if(result){
//			window.location.href="/contact/delform?id="+id;
//		}
	}
</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#" class="">订阅管理</a><a href="#" class="current">订阅列表</a></div>
	</div>
	<div class="container-fluid">
		<div class="row-fluid" style="margin-top: 0px;">
			<div class="span12">
				<div class="widget-box" style="margin-top: 35px;">
					<div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
						<h5>订阅管理</h5>
						<div  style="width:100%;height:35px;"><a href="/contact/createform" class="btn" style="float: right; width: 60px; height: 18px; line-height: 20px; display: block; font-size: 12px; margin-top: 4px; margin-right: 10px;">新增订阅</a></div>
					</div>
					<div class="widget-content nopadding" style="none repeat scroll 0 0 #FFFFFF">
						<div class="container-fluid" style="padding-right: 0px; padding-left: 0px;">
							<!-- <div  style="width:100%;height:35px;"><a href="/contact/createform" class="btn" style="float: right; width: 59px; height: 24px; line-height: 25px; display: block;">创建表单</a></div> -->
							<div>
								<ul class="dy_bdlb" style="margin-left: 0px;">
								{%section name=loop loop=$result%}
										<li style="background:none repeat scroll 0 0 #F8F8F8;border-radius:3px;padding:5px 10px 0;margin-bottom:10px;border:1px solid #DDDDDD;">
											<div class="bdlb_top" style="border-bottom:1px solid #DDDDDD;height:30px;line-height:30px;">
											<span class="bdlb_top_le"><b>{%$result[loop].formname%}</b></span>
											<span class="bdlb_top_ri" style="display:block;width:175px!important;width:180px;float:right;">
											<a href="/contact/updatestatus?id={%$result[loop].id%}">{%if $result[loop].tag==1%}启用{%else if $result[loop].tag==0%}禁用{%/if%}</a>
											| <a href="/contact/preview?type=form&id={%$result[loop].id%}" target="_blank">订阅预览</a>
											{%if $result[loop].mine == 1 %}
												| <a href="/contact/editform?id={%$result[loop].id%}">编辑</a>
											{%else%}
												| <span style="color:#BBBBBB">编辑</span>
											{%/if%}
											| <a href="javascript:del({%$result[loop].id%});" name="form_delete" targeturl="subscribe.php?act=delete&id=386">删除</a>
											</span>
											</div>
											<div class="bdlb_bottom" n="1" style="height:30px;line-height:30px;">
											<a href="javascript:" onclick="tab('001',{%$result[loop].id%})" class="panel" index="001" group="a"><i class="icon-pencil" style="margin-right: 5px;"></i>订阅样式设置</a>&nbsp;&nbsp;
											<!--<a href="javascript:" onclick="tab('002',{%$result[loop].id%})" class="panel" index="002" group="a"><i class="icon-envelope" style="margin-right: 5px;"></i>自动回复邮件设置</a>&nbsp;&nbsp;-->
											<!-- <a href="javascript:" onclick="tab('003',{%$result[loop].id%})" class="panel" index="003" group="a"><i class="icon-sign-blank" style="margin-right: 5px;"></i>表单样式显示</a> -->
											</div>
										<div name="box001{%$result[loop].id%}" style="display:none;"  group="a">
											<div class="dy_bdys" style="background-color:#F1F1F1;border-radius: 3px; border: 1px solid #DDDDDD; padding: 10px;">
											  <table width="100%" border="0" cellspacing="0" cellpadding="0">
												  <tr>
													<td width="616" height="50" class="boder_bottom" style="padding-left: 5px;"><span class="font14_bold"><b>订阅</b></span><br /><span class="font12_hui">用户填写订阅信息的页面</span></td>
													<td width="50" align="center" class="boder_bottom" style="color:#5f97d5;"><a href="/contact/edittemplate?type=form&id={%$result[loop].id%}" >编辑</a> | <a href="/contact/preview?type=form&id={%$result[loop].id%}" target="_blank">预览</a></td>
												  </tr>
												  <tr>
													<td height="50" class="boder_bottom" style="padding-left: 5px;"><span class="font14_bold"><b>感谢订阅页面</b></span><br /><span class="font12_hui">用户订阅成功后自动弹出的感谢页面</span></td>
													<td align="center" class="boder_bottom" style="color:#5f97d5;"><a href="/contact/edittemplate?type=thanks&id={%$result[loop].id%}" >编辑</a> | <a href="/contact/preview?type=thanks&id={%$result[loop].id%}" target="_blank">预览</a></td>
												  </tr>
												  <!--<tr>
													<td height="50" class="boder_bottom" style="padding-left: 5px;"><span class="font14_bold"><b>验证成功页面</b></span><br /><span class="font12_hui">用户验证成功后自动弹出的页面</span></td>
													<td align="center" class="boder_bottom" style="color:#5f97d5;"><a href="/contact/edittemplate?type=success&id={%$result[loop].id%}" >编辑</a> | <a href="/contact/preview?type=success&id={%$result[loop].id%}" target="_blank">预览</a></td>
												  </tr>-->
											 </table>		
											</div>
											<div style="text-align:center;margin-top: 10px;">
												<input type="button" value="收起" onclick="packup('box001{%$result[loop].id%}')" class="but panel" index="001"  style=" margin:0 auto; margin-bottom:10px;" />
											</div>
										</div>
										<div name="box002{%$result[loop].id%}" style="display:none;" group="a">
											<div class="dy_bdys" style="background-color:#F1F1F1;border-radius: 3px; border: 1px solid #DDDDDD; padding: 10px;">
											   <table width="100%" border="0" cellspacing="0" cellpadding="0">
												  <tr>
													<td width="616" height="50" class="boder_bottom" style="padding-left: 5px;"><span class="font14_bold"><b>验证邮件</b></span><br /><span class="font12_hui">订阅后会收到这封邮件，并且必须点击这封邮件里的验证链接才能完成验证</span></td>
													<td width="50" align="center" class="boder_bottom" style="color:#5f97d5;"><a href="/contact/edittemplate?type=validation&id={%$result[loop].id%}" >编辑</a> | <a href="/contact/preview?type=validation&id={%$result[loop].id%}" target="_blank">预览</a></td>
												  </tr>
												  <tr>
													<td height="50" class="boder_bottom" style="padding-left: 5px;"><span class="font14_bold"><b>感谢订阅</b></span><br /><span class="font12_hui">客户成功验证后，系统自动回复的欢迎内容</span></td>
													<td align="center" class="boder_bottom" style="color:#5f97d5;"><a href="/contact/edittemplate?type=welcome&id={%$result[loop].id%}" >编辑</a> | <a href="/contact/preview?type=welcome&id={%$result[loop].id%}" target="_blank">预览</a></td>
												  </tr>
												  <tr>
													<!--<td height="50" class="boder_bottom"><span class="font14_bold">验证提醒</span><br /><span class="font12_hui">通知那些尚未验证的客户</span></td>
													<td align="right" class="boder_bottom" style="color:#5f97d5;"><a href="subscribe.php?act=edit_template&type=activation_remind&id=386" >编辑</a> | <a href="subscribe.php?act=preview&type=activation_remind&id=386">预览</a></td>
													-->
												  </tr>
											   </table>		
											</div>
											<div style="text-align:center;margin-top: 10px;">
												<input type="button" value="收起" onclick="packup('box002{%$result[loop].id%}')" class="but panel" index="002"   style=" margin:0 auto; margin-bottom:10px;" />
											</div>
										</div>
										<!-- <div name="box003{%$result[loop].id%}" style="display:none;" group="a">
											<div class="dy_bdys" style="background-color:#F1F1F1;border-radius: 3px; border: 1px solid #DDDDDD; padding: 10px;">
											  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
												  <tr>
													<td height="30" class="font14_bold"><b>您的表单可以在网页中有以下三种展示样式：</b></td>
												  </tr>
												  <tr>
													<td class="font14_ju"><span style="color:#ff9900">方法一：图片按钮（在页面中直接展示表单图片按钮）</span></td>
												  </tr>
												  <tr>
													<td height="50">
													<input id="img386_1" name="386" type="radio" value="" style="vertical-align:middle;" class="margin_right5 float_left margin_top10" />
													<label for="img386_1">
													<img sid="http://send.360email.cn/subscribe.php?act=subscribe&c=nVDrNUgRTaevsLaQDKjfCwYX_J2bhLDj93Mox_ec0tI" src="/themes/360email/images/zh_cn/dy_mbut1.jpg" width="130" height="40" class="margin_right10 float_left" />
													</label>
													<input id="img386_2" name="386" type="radio" value="" style="vertical-align:middle;" class="margin_right5 float_left margin_top10" />
													<label for="img386_2">
													<img sid="http://send.360email.cn/subscribe.php?act=subscribe&c=nVDrNUgRTaevsLaQDKjfCwYX_J2bhLDj93Mox_ec0tI" src="/themes/360email/images/zh_cn/dy_mbut2.jpg" width="130" height="40" class="float_left" />
													</label>
													</td>
												  </tr>
												  <tr>
													<td style="padding-top:10px; padding-bottom:10px;" class="boder_bottom"><span class="dy_dm">代码</span>
														<textarea name="" cols="" rows="" class="inwbk_re" style="width:600px; height:60px;"></textarea><br />
														<span class="font12_hui">请将以上代码嵌入网页，即可采集用户信息</span></td>
												  </tr>
												  <tr>
													<td class="font14_ju" style="padding-top:20px;"><span style="color:#ff9900">方法二：文字链接（在页面中直接展示表单文字链接）</span></td>
												  </tr>
												  <tr>
													<td height="35"><input name="" type="text" class="inwbk_re margin_right10 float_left" style="width:240px;" /><input type="button" sid="http://send.360email.cn/subscribe.php?act=subscribe&c=nVDrNUgRTaevsLaQDKjfCwYX_J2bhLDj93Mox_ec0tI" value="生成" name="make" class="but float_left" /></td>
												  </tr>
												  <tr>
													<td style="padding-top:10px; padding-bottom:10px;" class="boder_bottom"><span class="dy_dm">代码</span>
														<textarea name="" cols="" rows="" class="inwbk_re" style="width:600px; height:60px;"></textarea><br />
														<span class="font12_hui">请将以上代码嵌入网页，即可采集用户信息</span></td>
												  </tr>
												  <tr>
													<td class="font14_ju" style="padding-top:20px; padding-bottom:10px;"><span style="color:#ff9900">方法三：表单框（在页面中直接展示最终的表样式）</span></td>
												  </tr>
												  <tr>
													<td>
													<iframe src="http://send.360email.cn/subscribe.php?act=subscribe&c=nVDrNUgRTaevsLaQDKjfCwYX_J2bhLDj93Mox_ec0tI" style="width: 600px; height: 200px; border: solid 1px #ccc; margin-bottom: 10px;"></iframe>
													</td>
												  </tr>
												  <tr>
													<td style="padding-top:10px; padding-bottom:10px;" class="boder_bottom"><span class="dy_dm">代码</span>
														<textarea name="" cols="" rows="" class="inwbk_re" style="width:600px; height:60px;"><iframe scrolling='no' frameborder='0' src='http://send.360email.cn/subscribe.php?act=subscribe&c=nVDrNUgRTaevsLaQDKjfCwYX_J2bhLDj93Mox_ec0tI' style=' width:500px;'  ></iframe></textarea><br />
														<span class="font12_hui">请将以上代码嵌入网页，即可采集用户信息</span></td>
												  </tr>
											</table>
										
											</div>
											<div style="text-align:center;margin-top: 10px;">
												<input type="button" value="收起" onclick="packup('box003{%$result[loop].id%}')" class="but panel" index="003" style=" margin:0 auto; margin-bottom:10px;" />
											</div>
										</div> -->
										<div class="clear"></div>
									</li>
									<p></p>
								{%/section%}
								</ul>
							</div>
							<div></div>
							<div></div>
						</div>
							<div class="row-fluid" style="text-align:right;margin-bottom: 20px;">
								<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
									<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
										{%$page%}
									</div>
								</div>
							</div>
					</div>
					</div>
				</div>
			</div>
		</div>
		
</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="" method="post">
		<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h3 id="myModalLabel" class="text-left">编辑字段</h3>
		</div>
		<div class="modal-body">
			<div><b>基本信息</b></div>
			{%section name=loop loop=$basic%}
				<label class="checkbox inline" style="width:100px;margin:0;">
					<input type="checkbox" id="{%$basic[loop].name%}" name="info[]" value="{%$basic[loop].id%}" {%if $basic[loop].name == 'mailbox' %} disabled=true {%/if%} /><span>{%$basic[loop].showname%}</span>
				</label>
			{%/section%}
			<!-- <label class="checkbox inline" style="width:100px;margin:0;">
				<input type="checkbox" id=mailbox name="info[]" checked disabled=true value="邮箱" />邮箱
			</label>
			<label class="checkbox inline" style="width:100px;margin:0;">
				<input type="checkbox" id="username" name="info[]" value="姓名" />姓名
			</label>
			<label class="checkbox inline" style="width:100px;margin:0;">
				<input type="checkbox" id="sex" name="info[]" value="性别" />性别
			</label>
			<label class="checkbox inline" style="width:100px;margin:0;">
				<input type="checkbox" id="birth" name="info[]" value="出生日期" />出生日期
			</label>
			<label class="checkbox inline" style="width:100px;margin:0;">
				<input type="checkbox" id="tel" name="info[]" value="手机" />手机
			</label> -->
			<p> </p>
			<div><b>扩展信息</b></div>
			{%section name=loop loop=$extension%}
				<label class="checkbox inline" style="width:100px;margin:0;">
					<input type="checkbox" id="{%$extension[loop].name%}" name="info[]" value="{%$extension[loop].id%}" /><span>{%$extension[loop].showname%}</span>
				</label>
			{%/section%}	
		</div>
		<div class="modal-footer">
			<input type="button" class="close" class="btn" aria-hidden="true" data-dismiss="modal" style="font-size:14px;filter:alpha(opacity=50);-moz-opacity:0.5;-khtml-opacity:0.5;opacity:0.5;" id="btn" value="保存" />
		</div>
	</form>
</div>
<input type="hidden" value="{%$per_num%}" id="per_num" />
<input type="hidden" value="{%$search_cont%}" id="search_cont" />
<input type="hidden" value="{%$total_page%}" id="total_page" />
<input type="hidden" value="{%$cur_page%}" id="cur_page" />
<input type="hidden" value="{%$page_type%}" id="page_type" />
<input type="hidden" value="" id="checklist" name="checklist" />
<!--Footer-part-->
{%include file="footer.php"%}