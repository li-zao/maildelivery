{%include file="header.php"%}
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 投递任务管理</a><a href="#" class="current">创建向导任务</a></div>
  </div>
  <div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"><i class="icon-inbox"></i></span>
						<h5>任务设置</h5>
					</div>
					<div class="widget-content">
						<form id="setform" action="/task/inserttask" method="post">
						<input id="act" type="hidden" value="save_draft" name="act">
						<input type="hidden" value="two" name="step">
						<input type="hidden" value="{%$confirm%}" name="old_step">
						<input id="return" type="hidden" value="up" name="return">
						<input id="tid" type="hidden" name="tid" value="{%$tid%}">
						<input id="uid" type="hidden" name="uid" value="{%$uid%}">
						<table class="" width="80%">
							 <tbody>
								<tr><td style="text-align:left;border:0;font-weight: bold;padding-left:0px;">任务名称：</td></tr>
								<tr>
									<td style="text-align: left;padding: 0;border:0;" >
									{%if !empty($task_name)%}
										<input type="text" id="taskname" name="taskname" value="{%$task_name%}" onblur="check_plan(this.value,'{%$tid%}');" style="border: 1px solid rgb(191, 191, 191); line-height: 25px;width: 99.5%; padding: 0px 1px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px;">
										<div id="checktaskname" style="text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;width:100%;line-height: 27px;vertical-align: top;padding-left: 2px;">&nbsp;示例："活动策划2014-04-08#4"。 用途：在创建任务时，没有保存则可自动进入草稿箱。任务名称不可重复。</div>
									{%else%}
										<input type="text" id="taskname" name="taskname" value="新建任务_{%$currtime%}" style="border: 1px solid rgb(191, 191, 191); line-height: 25px;width: 99.5%; padding: 0px 1px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px;">
										<div id="checktaskname" style="text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;width:100%;line-height: 27px;vertical-align: top;padding-left: 2px;">&nbsp;示例："活动策划2014-04-08#4"。 用途：在创建任务时，没有保存则可自动进入草稿箱。任务名称不可重复。</div>
									{%/if%}
										
									</td>
								</tr>		
								<tr><td style="text-align:left;border:0;font-weight: bold;padding-left:0px;height: 29px;">邮件主题：
								<!--<a style="background: none repeat scroll 0 0 #E6E6E6;border: 1px dashed #ACACAE;border-radius: 5px;display: block;font-size: 12px;padding: 0 10px;text-align: center;cursor:pointer;width:90px;float:right;height:22px;line-height:22px;line-height:23px\9;" href="#in_par2" data-toggle="modal">插入主题变量</a>-->
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
													<input type="button" id="variable2" data-dismiss="modal" value="确认" class="btn" style="margin-right: 10px;">
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>
											</form>
										</div>
									</td>
								</tr>
								<tr>
									<td style="text-align: left;padding: 0;border:0;" >
									{%if !empty($subject)%}
										<input type="text" id="subject" name="subject" value="{%$subject%}" style="border: 1px solid rgb(191, 191, 191); line-height: 25px;width: 99.5%; padding: 0px 1px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px;">
									{%else%}
										<input type="text" id="subject" name="subject" value="" style="border: 1px solid rgb(191, 191, 191); line-height: 25px;width: 99.5%; padding: 0px 1px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px;">
									{%/if%}	
										<div style="text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;width:100%;line-height: 27px;vertical-align: top;padding-left: 2px;">&nbsp;示例：缤纷夏日 省心省力。用途：显示在用户收件箱中的主题，请用心哦，这样才会提高打开率！</div>
									</td>
								</tr>		
								<tr><td style="text-align:left;border:0;font-weight: bold;padding-left:0px;">发件人名称：</td></tr>
								<tr>
									<td style="text-align: left;padding: 0;border:0;" >
										<input type="text" id="sender" name="sender" value="{%$sender%}" style="border: 1px solid rgb(191, 191, 191); line-height: 25px;width: 99.5%; padding: 0px 1px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px;">
										<div style="text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;width:100%;line-height: 27px;vertical-align: top;padding-left: 2px;">&nbsp;提示： 告知用户这封邮件来自哪里。例如使用公司名称或者品牌名称等。示例：maildata系统通知。<div>
									</td>
								</tr>										
								<tr><td style="text-align:left;border:0;font-weight: bold;padding-left:0px;">发件人地址：</td></tr>
								<tr>
									<td style="text-align: left;padding: 0;border:0;" >
										<input type="text" id="sendemail" name="sendemail" value="{%$sendemail%}" style="border: 1px solid rgb(191, 191, 191); line-height: 25px;width: 99.5%; padding: 0px 1px 0px 4px; margin-bottom: 0px; height: 25px;font-size:12px;">
										<div style="text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;width:100%;line-height: 27px;vertical-align: top;padding-left: 2px;">&nbsp;提示：告知用户这封邮件来自哪里。示例：from@maildata.cn<div>
									</td>
								</tr>										
								<tr><td style="text-align:left;border:0;font-weight: bold;padding-left:0px;">邮件回复地址：</td></tr>
								<tr>
									<td style="text-align: left;padding: 0;border:0;" >
										<input type="text" id="replyemail" name="replyemail" value="{%$replyemail%}" style="border: 1px solid rgb(191, 191, 191); line-height: 25px;width: 99.5%; padding: 0px 1px 0px 4px;margin-bottom: 0px; height: 25px;font-size:12px;">
										<div style="text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;width:100%;line-height: 27px;vertical-align: top;padding-left: 2px;">&nbsp;提示：告知用户可以回复到的邮件地。示例：reply@maildata.cn<div>
									</td>
								</tr>							
								<tr><td style="text-align:left;border:0;font-weight: bold;padding-left:0px;">任务分类：</td></tr>
								<tr>
									<td style="text-align: left;padding: 0;border:0;" >
										<select name="cid" id="tasktype" style="border: 1px solid rgb(191, 191, 191); line-height: 25px;width: 100%; padding: 3px 0px 0px 0px; margin-bottom: 0px; height: 25px;font-size:12px;"><option value="0">选择</option>
										{%section name=loop loop=$catdata%}
											{%if $catdata[loop].id == $cid%}
											<option value="{%$catdata[loop].id%}" selected="selected">{%$catdata[loop].vocation_name%}</option>
											{%else%}
											<option value="{%$catdata[loop].id%}">{%$catdata[loop].vocation_name%}</option>
											{%/if%}
										{%/section%}
										</select>
										<div style="text-align:left;background: none repeat scroll 0 0 #E8E8E8;color: #999999;font-size: 12px;width:100%;line-height: 27px;padding:0;vertical-align: top;">&nbsp;&nbsp;给您的任务选择一个合适的分类   <a style="line-height:27px;line-height:27px;color:#0066FF;width: 50px;float:right" role="button" data-toggle="modal" href="#quick_add">快速新建</a></div>
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
													<button type="button" id="sudu" class="btn" style="margin-right: 10px;">创建
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>
											</form>
										</div>
									</td>
								</tr>
								<tr><td colspan="4" style="text-align:left;border:0;padding-left:0px;padding-top: 8px; padding-bottom: 0px;">
										<input type="checkbox" checked="checked" value="1" style="margin-right:5px;margin-top: 0px;" id="is_insert_ad" name="is_insert_ad">
										<label style="cursor:pointer" for="is_insert_ad">以下域名的邮件主题前加(AD)</label>
									</td>
								</tr>
								<tr>
									<td colspan="4" height="30" style="font-size:12px;text-align:left;border:0;padding-left:0px;padding-top:0;padding-right: 8px;"><input type="text" id="domainAD" name="domainAD" value="{%$domainAD%}" style="width:100%;" class="inwbk_re">
									</td>
								</tr>
								{%if $confirm == ''%}
								<tr>
									<td style="text-align: left;padding: 8px 0;border:0;">
										<button id="setprev" class="btn" type="button" style="width:113px;height:30px;background:url(/dist/img/pro_but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;margin-right: 5px;"></button>
										<button id="setnext" class="btn" type="button" style="width:113px;height:30px;background:url(/dist/img/next_but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;"></button>
									</td>
								</tr>
								{%else%}
								<tr>
									<td style="text-align: left;padding: 8px 0;border:0;">
									<button type="button" id="confirm" style="width:85px;height:27px;background:url(/dist/img/but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;border-width: 0px;">保存</button>
									</td>
								</tr>
								{%/if%}
							</tbody>
						</table>
						</form>
					</div>
				</div>
			</div>
		</div>

  </div>
</div>
<!--Footer-part-->
<script type="text/javascript">
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
            	$('#tasktype').get(0).add(new Option(inputname,data), null);
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

	function onblur(event) {
		var tid=$('#tid').val();
		check_plan(this.value,tid);
	} 

	//异步判断任务名称是否重复
	function check_plan(plan_name,id)
	{
		//ajax验证分类是否重复,不重复新建
		var plan_name=plan_name;
		if (plan_name!='')
		{
			var uid=$('#uid').val();
			//var tid=$('#tid').val();
			$.post('/task/checktask',{'taskname':plan_name,'id':id,'uid':uid},function(data){
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
					$("#taskname").focus(function(){
					$("#checktaskname").html('任务名称，请确认不要重复');
					})
					return true;
				}
			})	
		}
	} 

$(function(){
	//点击button判断form表单是的值后提交表单
	$('#setnext').on('click', function(){
		$('#return').val('down');
		checkform();
	});
	
	$('#setprev').on('click', function(){
		$('#return').val('up');
		$('#setform').submit();
	})
	
	$("#confirm").click(function(){
		checkform();
	}); 
	
	function checkform(){
		//验证任务名称
		var taskname=$('#taskname').val();
		if(taskname == ""){
			art.dialog.alert('创建任务名称不能为空');
			return false;
		}
		//check_plan(taskname);
		var fontid=$('font').attr('id');
		if(fontid == 1){
			return false;
		}
		
		//验证主题
		var subject=$('#subject').val();
		//alert(subject.length);
		if(subject == ""){
			art.dialog.alert('邮件主题不能为空');
			return false;			
		}
		if(subject.length < 2 || subject.length > 150){
			art.dialog.alert('对不起,您设定发送邮件主题长度不符合标准（字数范围2-150）');
			return false;
		}
		
		//验证收件人
		var sender=$('#sender').val();
		if(sender == ""){
			art.dialog.alert('收件人不能为空');
			return false;			
		}
		if(sender.length < 2 || sender.length > 20){
			art.dialog.alert('请输入至少6位的用户名（字数范围2-20）');
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
		
		//验证邮箱
		var sendemail=$('#sendemail').val();
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
		if(sendemail == ""){
			art.dialog.alert('邮箱不能为空');
			return false;			
		}
		if(!reg.test(sendemail)) {
			art.dialog.alert('您填写的邮箱格式不正确,请重新填写!');
			return false;
		}
		
		//验证任务分类
		var tasktype=$('#tasktype').val();
		if(tasktype == "0"){
			art.dialog.alert('请选择任务分类');
			return false;			
		}
		
		if(taskname != ''){
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
						$('#setform').submit();
					}
			})			
		}
	}
}) 
$(function(){
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
{%include file="footer.php"%}
