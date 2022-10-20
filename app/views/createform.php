{%include file="header.php"%}
<style>
        .col{color:red;}
        #dv1{float:left;}
        #dv2{float:left;}
        #dv3{clear:both;}
        #dv4{float:left;}
        #dv5{float:left;}
        #dv6{clear:both;}
</style>
<script type="text/javascript">
	function selAll(){
		//alert(document.getElementsByName("gname[]").length);
		for(var i=0;i<document.getElementsByName("gname[]").length;i++){
			document.getElementsByName("gname[]")[i].checked = true;
		}
	}
	function cancerAll(){
		for(var i=0;i<document.getElementsByName("gname[]").length;i++){
			document.getElementsByName("gname[]")[i].checked = false;
		}
	}
	function refreshFormlist(){
		location.href="/contact/formlist";
	}
	$(function(){
		$("#btn").click(function(){
			var ck = $("input[name='info[]']:checked");
			var tr = "";
			var field = "";
			$("#tb").empty();
			$.each(ck,function(){
				//alert($(this).attr('value'));
				if($(this).attr('id') != 'mailbox'){
					tr += "<tr><td width='25%'>"+$(this).next().html()+"</td><td width='25%'><select name='sel' style='width:100px'><option value='-1'>验证规则</option><option>email</option><option value='2'>手机</option><option value='3'>字符串</option><option value='4'>数字</option><option value='0'>无</option></select></td><td width='25%' style='text-align:right'><input type='checkbox' name='neces[]'></td></tr>";//<td width='25%'><input style='width:50px;' type='text' name='num' /></td>
					field += $(this).attr('value')+",";
				}
			})
			$("#field").attr('value',field);
			$("#tb").append(tr);
		})
		$("input[name=subname]").focus(function(){
			$(this).attr('value',"");
		})
		$("input[name=subname]").blur(function(){
			if($(this).attr('value')==""){
				$(this).attr('value',"请输入电子期刊");
			}
		})
		$("#btn1").click(function(){
			var fname = $("input[name='fname']").val();
			if($("input[name='fname']").val()==""){
				art.dialog.alert("请输入表单名称");
				$("input[name='fname']").focus();
				return false;
			}
			//alert($("input[name='gname[]']:checked").length);
			if($("input[name='gname[]']:checked").length==0){
				art.dialog.alert("至少选择一个联系人组");
				return false;
			}
			var rule = "";
			$.each($("select[name='sel']"),function(){
				rule += $(this).val()+","; 
			})
			$("#rule").attr("value",rule);
			var num = "";
			$.each($("input[name='num']"),function(){
				num += $(this).val()+",";
			})
			$("#number").attr("value",num);
			//alert($("input[name='necessary[]']").length);
			var necessary = "";
			$.each($("input[name='neces[]']"),function(){
				if($(this).attr("checked") == 'checked'){
					necessary += 1+","; 
				}else{
					necessary += 0+",";
				}
			})
			$("#necessary").attr("value",necessary);
			$("#preview").attr("value","preview");
			$.ajax({
				type:'post',
				dataType:'json',
				url:'ajaxform',
				//async:true,
				data:{'fname':fname},
				success:function(data){
					if(data == 1){
						art.dialog.alert("该表单名称已经存在");
						$("input[name='fname']").focus();
						return false;
					}
					$("#myform").attr("target","_blank");
					$("#myform").submit();
				}
			});
			setTimeout ("refreshFormlist()",500); 

		});
		$("#btn2").click(function(){
			var fname = $("input[name='fname']").val();
			if($("input[name='fname']").val()==""){
				art.dialog.alert("请输入表单名称");
				$("input[name='fname']").focus();
				return false;
			}
			//alert($("input[name='gname[]']:checked").length);
			if($("input[name='gname[]']:checked").length==0){
				art.dialog.alert("至少选择一个联系人组");
				return false;
			}
			//alert($("select[name='sel']").length);
			var rule = "";
			$.each($("select[name='sel']"),function(){
				rule += $(this).val()+","; 
			})
			$("#rule").attr("value",rule);
			var num = "";
			$.each($("input[name='num']"),function(){
				num += $(this).val()+",";
			})
			$("#number").attr("value",num);
			//alert($("input[name='necessary[]']").length);
			var necessary = "";
			$.each($("input[name='neces[]']"),function(){
				if($(this).attr("checked") == 'checked'){
					necessary += 1+","; 
				}else{
					necessary += 0+",";
				}
			})
			$("#necessary").attr("value",necessary);
			$("#preview").attr("value","");
			$.ajax({
				type:'post',
				dataType:'json',
				url:'ajaxform',
				data:{'fname':fname},
				success:function(data){
					if(data == 1){
						art.dialog.alert("该表单名称已经存在");
						$("input[name='fname']").focus();
						return false;
					}
					$("#myform").attr("target","");
					$("#myform").submit();
				}
			})
		})
		$("#model").click(function(){
			$("#selmodel").css("display","block");
		})
		$("#define").click(function(){
			$("#selmodel").css("display","none");
		})
	})
</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#" class="">订阅管理</a><a href="#" class="current">新增订阅</a></div>
	</div>
	<div class="container-fluid" >
		<div class="widget-box" style="margin-top:35px;">
			<div class="widget-title"> <span class="icon"><i class="icon-inbox"></i></span>
				<h5>新增订阅</h5>
			</div>
			<div class="widget-content">
				<div style="margin-left:20px; margin-right: 20px;">
					<form action="/contact/docreateform" id="myform" method="post">
					<div style="background:#F1F1F1;padding:10px;">
						<table width="100%">
							<input type="hidden" name="status" value="" id="preview" />
							<input type="hidden" name="field" value="" id="field" />
							<input type="hidden" name="rule" value="" id="rule" />
							<input type="hidden" name="number" value="" id="number" />
							<input type="hidden" name="necessary" value="" id="necessary" />
							<tr>
								<td width="10%" valign="top" style="margin-left:20px;padding-top: 6px;"><b>订阅名称</b></td>
								<td width="90%" style="padding-top: 5px; padding-bottom: 5px;"><input type="text" name="fname" value="{%$result['formname']%}" style="width:458px;"/>&nbsp;&nbsp;<span style="color:red">*</span></td>
							</tr>
							<tr>
								<td valign="top" style="padding-top: 5px;"><b>订阅描述</b></td>
								<td style="padding-top: 5px; padding-bottom: 5px;"><textarea rows="4" cols="10" name="description" style="width:458px;height:125px;">{%$result.description%}</textarea></td>
							</tr>
						</table>
					</div>
					<p></p>
					<!-- <div style="height:30px;margin-top: 10px;line-height: 25px; padding-top: 3px;">
						<label><input type="radio" name="createway" id="define" style="margin-top:3px;" value="1" checked />自定义创建</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" value="1" name="createway" id="model" style="margin-top:3px;" />使用模板</label>
					</div>
					<div style="display:none" id="selmodel">
						<div><b>选择模板</b></div>
					</div> -->
					<div>
						<div style="background:#CCCCCC;height:25px;margin-top:1px;line-height: 25px;">
							<div id="dv1" style="margin-top:1px;line-height: 25px; margin-left: 10px;float:left;"><b>请选择订阅字段</b></div>
							<div id="dv2" style="color:#CC0000;margin-top:1px;margin-right:10px;cursor:pointer;float:right;"><a href="#myModal" role="button" style="color:#CC0000;" data-toggle="modal">+编辑字段</a></div>
						</div>
						<div id="dv3" style="margin-top: 5px; margin-left: 10px;">
							<table width="100%" class="table table-bordered table-striped">
								<thead>
									<tr>
										<td width="25%">字段名称</td>
										<td width="25%">验证规则</td>
										<!--<td width="25%">排序序号</td>-->
										<td width="25%" style="text-align:right">是否必填</td>
									</tr>
									<tr>
										<td>邮箱</td>
										<td>
											<select style="width:100px;height: 20px; padding-top: 0px; padding-bottom: 0px;padding-right: 0px;" disabled=true>
												<option value='1'>email</option>
											</select>
										</td>
										<!--<td><input type="text" readonly="readonly" style="width: 50px; padding-top: 0px; padding-bottom: 0px; height: 18px;" value="1" /></td>-->
										<td align="center" style="text-align:right"><input type="checkbox" checked disabled=true name="" /></td>
									</tr>
								</thead>
								<tbody id="tb">
									
									{%if $brr%}
										{%section name=loop loop=$brr%}
											<tr>
												<td>{%$brr[loop].showname%}</td>
												<td>
													<select style="width:100px">
														<option>email</option>
													</select>
												</td>
												<td><input type="text" name="num" style="width:50px" /></td>
												<td><input type="checkbox" checked disabled=true name="" /></td>
											</tr>
										{%/section%}
									{%/if%}
								</tbody>
							</table>
						</div>
						<div style="margin-top:10px;margin-left: 807px;">选中必选项后，页面栏目会自动加上<span style="color:red">*</span>号</div>
					</div>
					<div style="margin-top:10px;">
						<div style="background:#CCCCCC;height:25px;margin-top:1px;line-height: 25px;">
							<div id="dv4" style="margin-top:1px;line-height: 25px; margin-left: 10px;float:left;"><b>请选择订阅要应用到哪些联系人组</b></div>
							<div id="dv5" style="margin-top:1px;margin-right:10px;float:right;"><span style="color:#CC0000;cursor:pointer;" onclick="selAll()">全选</span>&nbsp;|&nbsp;<span style="color:#CC0000;cursor:pointer;" onclick="cancerAll()">取消</span></div>
							
						</div>
						<p></p>
						<div>
							{%section name=loop loop=$groups%}
								<label class="checkbox inline" style="width:200px;margin:0;" id="ck">
									<a title="{%$groups[loop].gname%}"><input type="checkbox" id="{%$groups[loop].id%}" name="gname[]" value="{%$groups[loop].id%}" />{%if $groups[loop].gm%}{%$groups[loop].gm%}{%else%}{%$groups[loop].gname%}{%/if%}</a>
								</label>
							{%/section%}
						</div>
						<div id="dv6" style="margin-top:1px;margin-right:10px;cursor:pointer;float:right;">至少选择一个联系人组</div>
						<p></p>
					</div>
					<p></p>
					<div>
						<div style="background:#CCCCCC;height:25px;padding-top: 2px;line-height: 25px;margin-top:32px;"><b style="margin-left: 10px;">请输入表单提交按钮上的文字</b></div>
						<div style="margin-top:10px"><input type="text" name="subname" value="订阅电子期刊"></div>
					</div>
					<div  style="margin-top:10px">
						<input type="button" class="btn" id="btn1" value="保存并预览" style="border-radius: 3px; padding-top: 5px;"/><input type="button" id="btn2" class="btn" value="保存" style="border-radius: 3px; padding-top: 5px;margin-left: 10px;width: 95px;"/>
					</div>
					</form>
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
				<label class="checkbox inline" style="width:70px;margin:0;">
					<input type="checkbox" id="{%$basic[loop].name%}" name="info[]" value="{%$basic[loop].id%}" {%if $basic[loop].name == 'mailbox' %} disabled=true checked="checked" {%/if%} /><span>{%$basic[loop].showname%}</span>
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