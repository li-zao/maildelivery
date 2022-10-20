{%include file="header.php"%}
<script type="text/javascript" src="/dist/js/filter.js"></script>	
<style>
	.col{color:red;}
	#alldel{float:left;}
	#alladd{float:left;}
	#DataTables_Table_0_paginate{float:left;}
	#tbone td{padding:4px;}
	.input { margin:0;padding:0; height:25px;} 
</style>
<script type="text/javascript">
	$(function(){
		$("#bt").click(function(){
			var mailbox = $("#mailbox").val();
			var search_str = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
			var gid = $("input[name='gname[]']:checked");
			var gname = $("#newgname").val();
			var tag;
			if(!search_str.test(mailbox)){
				$("#td1").text("请输入正确的邮箱格式");
				$("#td1").attr("class","col");
				$("#mailbox").focus();
			    return false;
			}else{
				$("#td1").text("邮箱格式合法");
				$("#td1").removeClass("col");
			}
			var birth = $("input[name='birth']").val();
			var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
			if(birth != ""){
				if(!reg.test(birth)){
					$("#birth").text("日期格式应为XXXX-XX-XX");
					$("#birth").attr("class","col");
					$("input[name='birth']").focus();
					return false;
				}else{
					$("#birth").text("日期格式正确");
					$("#birth").removeClass("col");
				}
			}
			var tel = $("input[name='tel']").val();
			var telrule = /^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8}$/;
			if(tel != ""){
				if(!telrule.test(tel)){
					$("#td3").text("手机号码格式不正确");
					$("#td3").attr("class","col");
					$("input[name='tel']").focus();
				    return false;
				}else{
					$("#td3").text("手机号码格式正确");
					$("#td3").removeClass("col");
				}
			}
			
			var extension = $("#extension input[type='text']");
			exten = true;
			if(extension){
				$.each(extension,function(i,result){
					if(this.id == 2){
						if(this.value){
							var num = /^\d+$/;
							if(!num.test(this.value)){
								//art.dialog.alert("请输入整型数字");
								$(this).parent().next().html("<span style='color:red'>请输入整型数字</span>");
								exten = false;
								//return false;
							}else{
								$(this).parent().next().text("");
							}
						}
					}else if(this.id == 3){
						if(this.value){
							var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
							if(!reg.test(this.value)){
								$(this).parent().parent().next().html("<span style='color:red'>日期格式为yyyy-mm-dd</span>");
								exten = false;
								//return false;
							}else{
								$(this).parent().parent().next().text("");
							}
						}
					}else if(this.id == 4){
						if(this.value){
							var num = /^[1-9]+[0-9]*\.[0-9]*/;
							if(!num.test(this.value)){
								//art.dialog.alert("请输入整型数字");
								$(this).parent().next().html("<span style='color:red'>请输入小数</span>");
								exten = false;
								//return false;
							}else{
								$(this).parent().next().text("");
							}
						}
					}
				})
			}
			if(exten == false){
				return false;
			}
			//return exten;
//			if(gname!='' && gid==0){
				$.ajax({
					type:"post",
					url:"ajaxgroup",
					data:{'gname':gname,'mailbox':mailbox},
					dataType:"json",
					success:function(data){
						// alert(data);
						if(data.mailbox && data.gname){
							$("#prompt").text("该组已经存在");
							$("#prompt").attr("class","col");
							$("#td1").text("邮箱重复");
							$("#td1").attr("class","col");
							return false;
						}else if(data.mailbox && typeof data.gname =="undefined"){
							$("#td1").text("邮箱重复");
							$("#td1").attr("class","col");
							$("#prompt").text("组名称合法");
							$("#prompt").removeClass("col");
							return false;
						}else if(typeof data.mailbox=="undefined" && data.gname){
							$("#prompt").text("该组已经存在");
							$("#prompt").attr("class","col");
							$("#td1").text("邮箱合法");
							$("#td1").removeClass("col");
							return false;
						}else{
							$("#td1").text("邮箱合法");
							$("#prompt").text("组名称合法");
							$("#td1").removeClass("col");
							$("#prompt").removeClass("col");
						}
						if(gname=='' && gid.length==0){
							$("#prompt").text("请选择组");
							$("#prompt").attr("class","col");
							return false;
						}
						$("#myform").submit();
					}
				})		
		})
		
	})
	//获取编辑联系人信息
	function getinfo(id,mail){
		$(function(){
			$("#hi").val(id);
			$.ajax({
				data:{'id':id,'status':'person','gid':$("input[name='gid']").val(),'mailbox':mail},
				type:'post',
				dataType:'json',
				url:'getinfo',
				success:function(data){
					$("#mb").val(data.mailbox);
					$("#un").val(data.username);
					$("#te").val(data.tel);
					if(data.birth != "" && data.birth !=0){
						$("#bi").val(data.birth);
					}
					if(data.sex == 1){
						$("#sex1").attr('checked','checked');
					}else{
						$("#sex2").attr('checked','checked');
					}
					var arr = $("#expand input");
					$.each(arr,function(i,result){
						var id = result.id;
						$("#"+id).val(data[id]);
					})
					if($("input[name='gid']").val()){
						var brr = $("#checkgroup input");
						$.each(brr,function(i,result){
							var id = result.id;
							if(id == $("input[name='gid']").val()){
								$(this).attr("checked",true);
							}
						})
					}else{
						var brr = $("#checkgroup input");
						var groups = data.groups;
						var gid = new Array();
						gid = groups.split(',');
						$.each(brr,function(i,result){
							var id = result.id;
							if($.inArray(id,gid)!=-1){
								$(this).attr("checked",true);
							}else{
								$(this).attr("checked",false);
							}
						})
					}
					
				}
			})

			$("#bt2").click(function(){
				var mailbox = $("#mb").val();
				var search_str = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
				var gid = $("input[name='gname[]']:checked");
				var gname = $("#newgname2").val();
				var tag;
				if(!search_str.test(mailbox)){
					$("#td2").text("请输入正确的邮箱格式");
					$("#td2").attr("class","col");
					$("#mb").focus();
				    return false;
				}else{
					$("#td2").text("邮箱格式合法");
					$("#td2").removeClass("col");
				}
				var birth = $("#bi").val();
				var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
				if(birth != "" && birth != 0){
					if(!reg.test(birth)){
						$("#birth_edit").text("日期格式应为XXXX-XX-XX");
						$("#birth_edit").attr("class","col");
						$("#bi").focus();
						return false;
					}else{
						$("#birth_edit").text("日期格式正确");
						$("#birth_edit").removeClass("col");
					}
				}
				var tel = $("#te").val();
				var telrule = /^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8}$/;
				if(tel != ""){
					if(!telrule.test(tel)){
						$("#td4").text("手机号码格式不正确");
						$("#td4").attr("class","col");
						$("#te").focus();
					    return false;
					}else{
						$("#td4").text("手机号码格式正确");
						$("#td4").removeClass("col");
					}
				}
				
			var expand = $("#expand input[type='text']");
			exten = true;
			if(expand){
				$.each(expand,function(i,result){
					if($(this).attr("status") == 2){
						if(this.value){
							var num = /^\d+$/;
							if(!num.test(this.value)){
								$(this).parent().next().html("<span style='color:red'>请输入整型数字</span>");
								exten = false;
							}else{
								$(this).parent().next().text("");
							}
						}
					}else if($(this).attr("status") == 3){
						if(this.value){
							var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
							if(!reg.test(this.value)){
								$(this).parent().parent().next().html("<span style='color:red'>日期格式为yyyy-mm-dd</span>");
								exten = false;
							}else{
								$(this).parent().parent().next().text("");
							}
						}
					}else if($(this).attr("status") == 4){
						if(this.value){
							var num = /^([1-9]+[0-9]*\.[0-9]|(\d{4})-(\d{2})-(\d{2}))*/;
							if(!num.test(this.value)){
								$(this).parent().next().html("<span style='color:red'>请输入小数</span>");
								exten = false;
							}else{
								$(this).parent().next().text("");
							}
						}
					}
				})
			}
			if(exten == false){
				return exten;
			}	

					$.ajax({
						type:"post",
						url:"ajaxgroup",
						data:{'gname':gname,'mailbox':mailbox,'status':'editperson','id':id},
						dataType:"json",
						success:function(data){
							if(data.mailbox && data.gname){
								$("#prompt2").text("该组已经存在");
								$("#prompt2").attr("class","col");
								$("#td2").text("邮箱重复");
								$("#td2").attr("class","col");
								return false;
							}else if(data.mailbox && typeof data.gname =="undefined"){
								$("#td2").text("邮箱重复");
								$("#td2").attr("class","col");
								$("#prompt2").text("组名称合法");
								$("#prompt2").removeClass("col");
								return false;
							}else if(typeof data.mailbox=="undefined" && data.gname){
								$("#prompt2").text("该组已经存在");
								$("#prompt2").attr("class","col");
								$("#td2").text("邮箱合法");
								$("#td2").removeClass("col");
								return false;
							}else{
								$("#td2").text("邮箱合法");
								$("#prompt2").text("组名称合法");
								$("#td2").removeClass("col");
								$("#prompt2").removeClass("col");
							}
							if(gname=='' && gid.length==0){
								$("#prompt2").text("请选择组");
								$("#prompt2").attr("class","col");
								return false;
							}
							$("#myform2").submit();
						}
					})		
			})
		})
	}

		$(function(){
			$("#check").click(function(){
				var gid = $("#sel2").find("option:selected").val();
				$.ajax({
					type:'post',
					dataType:'json',
					url:'checkmail',
					data:{'id':gid},
					success:function(data){
						if(!data.total){data.total = 0}
						if(!data.legal){
							data.legal = 0;
						}
						if(!data.illegal){data.illegal = 0}
						if(!data.str){data.str = ''}
						$("#total").text(data.total);
						$("#legal").text(data.legal);
						$("#illegal").text(data.illegal);
						$("#illegal").attr('href',"personlist?gid="+gid+"&str="+data.str);
					}
				})
			})
		})
		
		$(function () { 
		   	$("#butt").toggle(function () {
		   		$("#dis").slideDown("slow"); 
		      }, function () { 
				$("#dis").slideUp("slow"); 
			});
    	});
    	function getgid(){
        	var gid = $("#sel2").find("option:selected").val();
			$("#importgname").val(gid);
        }
		$(function(){
			$("#exportgname").val($("#sel2").find("option:selected").val());
			$("#qv").click(function(){
				if($("#dis").is(":hidden")){
					$("#dis").css("display","block");
					$("#quicknav").children().children("i").attr("class",'icon-chevron-up');
				}else{
					$("#dis").css("display","none");
					$("#quicknav").children().children("i").attr("class",'icon-chevron-down');
				}
			})
		})
		
		$(function(){
			$("#checkAll").click(function(){
				if($("#checkAll").attr("checked")){
					$.each($("input[name='ck[]']"),function(i,result){
						$(this).attr("checked",true);
					})
				}else{
					$.each($("input[name='ck[]']"),function(i,result){
						$(this).attr("checked",false);
					})
				}
				var ids = "";
				var ck = $("input[name='ck[]']:checked");
				$.each(ck,function(){
					ids += $(this).val()+",";
				})
				$("input[name='ids']").attr("value",ids);
			})
			$("#all").click(function(){
				if($("#all").attr("checked")){
					$.each($("input[name='ck[]']"),function(i,result){
						$(this).attr("checked",true);
					})
					$.post('/contact/selectalled',{'selectalls':'on'});
					$("input[name='tag']").val("on");
				}else{
					$.each($("input[name='ck[]']"),function(i,result){
						$(this).attr("checked",false);
					})
					$.post('/contact/selectalled',{'selectalls':'off'}
					);
					$("input[name='tag']").val("off");
				}
			})
		})
		$(function(){
				var sel_stat = $(':input[name=sel_status]').val();	
				if($("#all").attr("checked")){
					$.each($("input[name='ck[]']"),function(i,result){
						$(this).attr("checked",true);
					})
					$("input[name='tag']").val("on");
				}else{
					if(sel_stat == 'on'){
						$("#all").attr("checked",true);
						$.each($("input[name='ck[]']"),function(i,result){
							$(this).attr("checked",true);
						})
						$("input[name='tag']").val("on");
					}
				}
		})
		
		function delone(id,gid){
			if(!gid){
				confirm_ = art.dialog.confirm('确定删除该联系人?',function(){
					$.post('/contact/deleteperson',{'id':id,'gid':gid},function(data){
						location.reload();
					});
				});
			}else{
				confirm_ = art.dialog.confirm('确定删除该联系人?',function(){
				$.post('/contact/deleteperson',{'id':id,'gid':gid},function(data){
						location.reload();
				});
			});
			}
			
		}
</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#" class="">联系人管理</a><a href="#" class="current">联系人库</a> </div>
	</div>
	<input type="hidden" name="tag" value="" />
	<div class="container-fluid">
		{%if $search != "" || $fid != "" %}
		 <input id="s_data" type="hidden" value="yes" name="s_data">
		{%else%}
		 <input id="s_data" type="hidden" value="" name="s_data">
		{%/if%}	
		<div id="quicknav" class="widget-title bg_lo" style="border-bottom:1px dotted #cdcdcd;" data-toggle="collapse" href="#collapseG3">
			<h5 style="float: right;" id="qv">
			检索条件
			<i class="icon-chevron-down"></i>
			</h5>
		</div>
		<p></p>
		<div style="width:100%;">
			<form method="get" action="/contact/personlist" id="mf">
			<div id="dis" style="clear:both;display:none;font-size:12px">
				<input type="hidden" name="search" value="ser">
				<input type="hidden" name="gid" value="{%$id%}">
				<div>
					<p></p>
					<div>
						<b>请选择组：</b>
						<select style="font-size:12px;width:200px" name="groupid" id="sel2">
							<option value="0">全部</option>
							{%section name=loop loop=$group%}
								<option {%if $group[loop].id==$gid || $group[loop].id==$id%}selected="selected"{%/if%} value="{%$group[loop].id%}">{%$group[loop].gname%}</option>
							{%/section%}
						</select>
					</div>
					<p></p>
					<div>
						<table id="tbone" width="100%" class="table table-bordered">
							{%if $value %}
								{%section name=loop loop=$value%}
									<tr>
										<td width='10%' style="text-align:center">
										{%if $value[loop].jostr != 0%}
											<select style="width:80px;font-size:12px" name="jo[]">
												<option {%if $value[loop].jostr == 2 %}selected="selected"{%/if%} value="2">与(and)</option>
												<option {%if $value[loop].jostr == 1 %}selected="selected"{%/if%} value="1">或(or)</option>
											</select>
										{%else%}
											<input type="text" disabled=true value="筛选条件" style="width:73px;border:0px;text-align:center;font-size:12px" />
										{%/if%}
										</td>
										<td width='10%' style="text-align:center"><b><input type='text' value="{%$value[loop].numstr%}" disabled=true style='width:73px;border:0px;text-align:center;font-size:12px' name='number[]'></b></td>
										<td width='18%' style="text-align:center">
										<select id="selopr{%$numstr[loop]%}" name="showname[]" style="width:150px;font-size:12px" onchange="changevalue('selopr{%$numstr[loop]%}','oprator{%$numstr[loop]%}','text{%$numstr[loop]%}')">
										{%section name=loop3 loop=$ext%}
											<option {%if $value[loop].sname == $ext[loop3].id %}selected="selected"{%/if%} value="{%$ext[loop3].id%}" type="{%$ext[loop3].type%}">{%$ext[loop3].showname%}</option>
										{%/section%}
										</select>
										</td>
										<td width='15%' style="text-align:center">
											<select id="oprator{%$numstr[loop]%}" name="opra[]" style="width:120px;font-size:12px" onchange="changetime('selopr{%$numstr[loop]%}','oprator{%$numstr[loop]%}','text{%$numstr[loop]%}')">
												{%if $value[loop].type == 1 %}
													<option value="0" {%if $value[loop].opra == 0%}selected="selected"{%/if%}>是</option>
													<option value="1" {%if $value[loop].opra == 1%}selected="selected"{%/if%}>否</option>
													<option value="2" {%if $value[loop].opra == 2%}selected="selected"{%/if%}>包含</option>
													<option value="3" {%if $value[loop].opra == 3%}selected="selected"{%/if%}>不包含</option>
													<option value="4" {%if $value[loop].opra == 4%}selected="selected"{%/if%}>开始字符</option>
													<option value="5" {%if $value[loop].opra == 5%}selected="selected"{%/if%}>结束字符</option>
													<option value="300" {%if $value[loop].opra == 300%}selected="selected"{%/if%}>为空</option>
													<option value="301" {%if $value[loop].opra == 301%}selected="selected"{%/if%}>不为空</option>
												{%else if $value[loop].type == 2 %}
															{%if $value[loop].sname == 3%}
																<option value="0" {%if $value[loop].opra == 0%}selected="selected"{%/if%}>是</option>
																<option value="1" {%if $value[loop].opra == 1%}selected="selected"{%/if%}>否</option>
																<option value="300" {%if $value[loop].opra == 300%}selected="selected"{%/if%}>为空</option>
																<option value="301" {%if $value[loop].opra == 301%}selected="selected"{%/if%}>不为空</option>
															{%else%}
																<option value="100" {%if $value[loop].opra == 100%}selected="selected"{%/if%}>等于</option>
																<option value="101" {%if $value[loop].opra == 101%}selected="selected"{%/if%}>不等于</option>
																<option value="102" {%if $value[loop].opra == 102%}selected="selected"{%/if%}>大于</option>
																<option value="103" {%if $value[loop].opra == 103%}selected="selected"{%/if%}>不大于</option>
																<option value="104" {%if $value[loop].opra == 104%}selected="selected"{%/if%}>小于</option>
																<option value="105" {%if $value[loop].opra == 105%}selected="selected"{%/if%}>不小于</option>
																<option value="300" {%if $value[loop].opra == 300%}selected="selected"{%/if%}>为空</option>
																<option value="301" {%if $value[loop].opra == 301%}selected="selected"{%/if%}>不为空</option>
															{%/if%}
													
												{%else if $value[loop].type == 3 %}
													<option value="200" {%if $value[loop].opra == 200%}selected="selected"{%/if%}>之前</option>
													<option value="201" {%if $value[loop].opra == 201%}selected="selected"{%/if%}>之后</option>
													<option value="202" {%if $value[loop].opra == 202%}selected="selected"{%/if%}>介于</option>
													<option value="203" {%if $value[loop].opra == 203%}selected="selected"{%/if%}>不介于</option>
													<option value="300" {%if $value[loop].opra == 300%}selected="selected"{%/if%}>为空</option>
													<option value="301" {%if $value[loop].opra == 301%}selected="selected"{%/if%}>不为空</option>
												{%else%}
													<option value="102" {%if $value[loop].opra == 102%}selected="selected"{%/if%}>大于</option>
													<option value="104" {%if $value[loop].opra == 104%}selected="selected"{%/if%}>小于</option>
													<option value="300" {%if $value[loop].opra == 300%}selected="selected"{%/if%}>为空</option>
													<option value="301" {%if $value[loop].opra == 301%}selected="selected"{%/if%}>不为空</option>
												{%/if%}
											</select>
										</td>
										<td width='25%' style="text-align:center" id="text{%$value[loop].numstr%}">
											{%if $value[loop].type == 3%}
												{%if $value[loop].opra == 200 || $value[loop].opra == 201 %}
													<div class='input-append date form_date'><input name='birth' onclick='calendar()' size='16' type='text' value="{%$value[loop].ti%}" ><span class='add-on'><i class='icon-th'></i></span></div>	
												{%else if $value[loop].opra == 202 || $value[loop].opra == 203%}
													<div class='input-append date form_date'><input style='width:206px' name='birth1' onclick='calendar()' size='16' type='text' value="{%$value[loop].time1%}" ><span class='add-on'><i class='icon-th'></i></span></div><div>--</div><div class='input-append date form_date'><input style='width:206px' name='birth2' onclick='calendar()' size='16' type='text' value="{%$value[loop].time2%}" ><span class='add-on'><i class='icon-th'></i></span></div>
												{%/if%}
											{%else%}
												{%if $value[loop].opra != 300 && $value[loop].opra != 301%}
													<input type="text" value="{%$value[loop].ti%}" name='ti[]' style="width:227px;font-size:12px">
												{%/if%}
											{%/if%}
										</td>
										{%if $value[loop].jostr == 0%}
											<td width='15%' style="text-align:center"><a class='add icon-plus btn btn-small' href='javascript:addcon()'></a></td>
										{%else%}
											<td width='15%' style="text-align:center"><a class='add icon-plus btn btn-small' href='javascript:addcon()'></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:del({%$value[loop].numstr%})' class='icon-minus btn btn-small'></a></td>
										{%/if%}
									</tr>
								{%/section%}
							{%/if%}
						</table>
					</div>
							
					<input type="hidden" name="numstr2" value="" id="numstr2" />
					<input type="hidden" name="jostr2" value="" id="jostr2" />
					<input type="hidden" name="sname2" value="" id="sname2" />
					<input type="hidden" name="ti2" value="" id="ti2" />
					<input type="hidden" name="opra2" value="" id="opr2" />
					<input type="hidden" name="type2" value="" id="type2" />
					<input type="hidden" name="num2" value="" id="num2" />
				</div>
				
				<div style="margin-top:15px;">
					<a id="btn1" onclick="filtersearch('btn1')" class="btn" style="font-size: 12px; margin-left: 5px; height: 18px;"><i class="icon-zoom-in"></i>&nbsp;搜索</a>
					&nbsp;&nbsp;&nbsp;&nbsp;
					{%if $role == "stasker" || $role == "tasker"%}
					<input type="button" value="保存检索条件" class="btn" id="btn6" style="margin-left: 5px; height: 28px; padding-right: 12px;font-size:12px;"/>&nbsp;&nbsp;&nbsp;&nbsp;
					{%/if%}	
					<a id="resetsearch" class="btn" style="margin-left:5px; font-size:12px;height: 18px;width: 38px;">重置</a>
				</div>	
			</div>
			</form>
			<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<form action="/contact/addperson" method="post" id="myform">
					<div class="modal-header">
						<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
						<h3 id="myModalLabel" class="text-left">新增联系人</h3>
					</div>
					<div  class="modal-body">
						<h5>基本信息</h5>
						<table width="100%">
							<tr>
								<td style="width:20%">邮箱：</td>
								<td style="width:50%"><input id="mailbox" type="text" name="mailbox" /><span style="color:red;">*</span></td>
								<td id="td1">邮箱不能重复</td>
							</tr>
							<tr>
								<td>姓名：</td>
								<td><input type="text" name="username" /></td>
								<td></td>
							</tr>
							<tr>
								<td>性别：</td>
								<td><input type="radio" name="sex" value="1"  checked style="margin-top:0px" />男&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="sex" value="2" style="margin-top:0px" />女</td>
								<td></td>
							</tr>
							<tr>
								<td>出生日期：</td>
								<td><div class="input-append date form_date"><input name="birth" size="16" type="text" value="" style="width:185px" ><span class="add-on"><i class="icon-th"></i></span></div></td>
								<td id="birth"></td>
							</tr>
							<tr>
								<td>手机：</td>
								<td><input type="text" name="tel" /></td>
								<td id="td3"></td>
							</tr>
						</table>
						<h5>扩展信息</h5>
						<table id="extension">
							{%section name=loop loop=$extension%}
								<tr>
									<td width="20%">{%$extension[loop].showname%}：</td>
									<td width="40%">
										{%if $extension[loop].type == 3%}
											<div class="input-append date form_date"><input name="{%$extension[loop].name%}" id="{%$extension[loop].type%}" size="16" type="text" value="" ><span class="add-on"><i class="icon-th"></i></span></div>
										{%else%}
											<input type="text" id="{%$extension[loop].type%}" name="{%$extension[loop].name%}" />
										{%/if%}	
									</td>
									<td></td>
								</tr>
							{%/section%}
						</table>
						<h5>组信息</h5>
						<table width="100%">
							<tr>
								<td style="width:20%">新建组：</td>
								<td style="width:40%">
									<div><input style="width:209px" maxlength="10"  id="newgname" type="text" name="newgname" /></div>
								</td>
								<td id="prompt" style="width:40%">
									
								</td>
							</tr>
						</table>
						{%section name=loop loop=$group%}
									<label class="checkbox inline" style="width:145px;margin:0;height:25px;">
										<a title="{%$group[loop].gname%}"><input type="checkbox" name="gname[]" value="{%$group[loop].id%}" /><span style="font-size:12px;">{%if $group[loop].gm%}{%$group[loop].gm%}{%else%}{%$group[loop].gname%}{%/if%}</span></a>
									</label>
						{%/section%}
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" id="bt" value="保存" />
					</div>
				</form>
			</div>
		</div>
		<div class="widget-box" style="padding-bottom: 10px; border-bottom: 1px solid #cdcdcd;">
			<div class="widget-title">
				<div style="float:left">
					<span class="icon">
						<i class="icon-th"></i>
					</span>
					{%if $groupname%}
						<h5>分组：{%$groupname%}</h5>
					{%else%}
						<h5>联系人</h5>
					{%/if%}
				</div>
				<div style="float:right;">
							{%if $role == "stasker" || $role == "tasker"%}
							<div style="float:left;margin-right: 10px;margin-top: 6px;">
								<form action="exportview" method="post" enctype="multipart/form-data" >
									<input type="submit" class="btn" style="font-size:12px;margin-left:30px;border-top-width:1px;height:26px;padding-top:3px;" value="下载导入格式">
								</form>
							</div> 
							<div style="float:left;margin-top: 6px;margin-right: 10px;">
								<input type="button" onclick="getgid()" href="#myModal4" role="button" data-toggle="modal" class="btn" style="font-size:12px;height:26px;padding-top:3px;" value="导入联系人" />
							</div>
							<div style="float:left;margin-right: 10px;margin-top: 6px;">
									<a href="#myModal7" class="btn" data-toggle="modal" style="font-size:12px;padding-bottom: 0px;padding-top:3px;">导出联系人</a>
							</div>
							<div style="float:left;margin-top: 6px;margin-right: 5px;">
								<a href="#myModal" style="font-size:12px;height:17px;text-align:center；padding-bottom:3px;padding-top:2px" role="button" class="btn" data-toggle="modal">新增联系人</a>
							</div>
							{%/if%}
				</div>
			</div>
		<div class="widget-content nopadding">
			<table width="80%" align="center"  class="table table-bordered table-striped">
				<thead>
					<tr>
						{%if $role == "stasker" || $role == "tasker"%}
						<th style="text-align:center;border-left-width: 0px;width: 16%;"><input type="checkbox" id="checkAll" >&nbsp;本页全选&nbsp;&nbsp;<input type="checkbox" id="all" >&nbsp;全选</th>
						{%else%}
						<th style="text-align:center; width: 10%;">序号</th>
						{%/if%}
						<th style="text-align:center; width: 26%;">邮箱</th>
						<th style="text-align:center; width: 18%;">姓名</th>
						<th style="text-align:center; width: 20%;">手机</th>
						{%if $role == "stasker" || $role == "tasker"%}
						<th style="text-align:center;">操作</th>
						{%/if%}
					</tr>
				</thead>
				{%$a = 1%}
				{%section name=loop loop=$data%}
					<tr>
						{%if $role == "stasker" || $role == "tasker"%}
						<td style="text-align:center;border-left-width: 0px;"><input type="checkbox" name="ck[]" value="{%$data[loop].id%}" /></td>
						{%else%}
						<td style="text-align:center">{%$a++%}</td>
						{%/if%}
						{%if $data[loop].mine==1%}
						<td style="text-align:center">{%$data[loop].mailbox%} *</td>
						{%else%}
						<td style="text-align:center">{%$data[loop].mailbox%}</td>
						{%/if%}
						<td style="text-align:center">{%$data[loop].username%}</td>
						<td style="text-align:center">{%$data[loop].tel%}</td>
						{%if $role == "stasker" || $role == "tasker"%}
						<td style="text-align:center">
							{%if $data[loop].mine == 1%}
							<span onclick="getinfo({%$data[loop].id%},'{%$data[loop].mailbox%}')"><a href="#myModal2" role="button" data-toggle="modal">
							<i class="icon-edit">编辑</i>
							</a></span>
							{%else%}
							<i class="icon-edit" style="color:#BBBBBB">编辑</i>
							{%/if%}&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
							{%if $data[loop].tag == 1 || $data[loop].tag == 2%}
							<a onclick="delone({%$data[loop].id%},{%if $id!='' %}{%$id%}{%else%}0{%/if%})" style="cursor:pointer;" class="icon-remove">删除</a>
							{%else%}
							<i class="icon-remove" style="color:#BBBBBB">删除</i>
							{%/if%}
						</td>
						{%/if%}
					</tr>
				{%/section%}
				<input type="hidden" name="status" value="{%$id%}" />
			</table>
		</div>
		<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<form action="/contact/updateperson" method="post" id="myform2">
					<div class="modal-header">
						<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
						<h3 id="myModalLabel" class="text-left">编辑联系人</h3>
					</div>
					<div  class="modal-body">
						<h5>基本信息</h5>
						<table>
							<tr>
								<td style="width:20%">邮箱：</td>
								<td style="width:50%"><input id="mb" type="text" name="mailbox" value="" /><span style="color:red;">*</span></td>
								<td id="td2">邮箱不能重复</td>
								<input type="hidden" id="hi" name="id" value="123" />
							</tr>
							<tr>
								<td style="width:20%">姓名：</td>
								<td><input id="un" type="text" name="username" /></td>
								<td></td>
							</tr>
							<tr>
								<td>性别：</td>
								<td><input type="radio" id="sex1" name="sex" value="1" style="margin-top:0px" />男&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="sex2" name="sex" value="2"  style="margin-top:0px" />女</td>
								<td></td>
							</tr>
							<tr>
								<td>出生日期：</td>
								<td><div class="input-append date form_date"><input name="birth" id="bi" style="width:185px" size="16" type="text" value="" ><span class="add-on"><i class="icon-th"></i></span></div></td>
								<td id="birth_edit"></td>
							</tr>
							<tr>
								<td>手机：</td>
								<td><input type="text" name="tel" id="te" /></td>
								<td id="td4"></td>
							</tr>
						</table>
						<h5>扩展信息</h5>
						<table id="expand">
							{%section name=loop loop=$extension%}
								<tr>
									<td width="20%">{%$extension[loop].showname%}：</td>
									<td width="40%">
										{%if $extension[loop].type == 3%}
											<div class="input-append date form_date"><input name="{%$extension[loop].name%}" status="{%$extension[loop].type%}" id="{%$extension[loop].name%}" size="16" type="text" value="" ><span class="add-on"><i class="icon-th"></i></span></div>
										{%else%}
											<input type="text" id="{%$extension[loop].name%}" status="{%$extension[loop].type%}" name="{%$extension[loop].name%}" />
										{%/if%}
									</td>
									<td></td>
								</tr>
							{%/section%}
						</table>
						<h5>组信息</h5>
						<table>
							<tr>
								<td style="width:20%">新建组：</td>
								<td style="width:50%">
									<div><input style="width:209px" id="newgname2" type="text" name="newgname2" /></div>
								</td>
								<td id="prompt2">
									
								</td>
							</tr>
						</table>
						<div  id="checkgroup">
							{%section name=loop loop=$group%}
										<label class="checkbox inline" style="width:145px;margin:0;">
											<a title="{%$group[loop].gname%}"><input type="checkbox" id="{%$group[loop].id%}" name="gname[]" value="{%$group[loop].id%}" /><span style="font-size:12px">{%if $group[loop].gm%}{%$group[loop].gm%}{%else%}{%$group[loop].gname%}{%/if%}</span></a>
										</label>
							{%/section%}
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" id="bt2" value="保存" />
					</div>
				</form>
			</div>
		<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="margin-top: 5px;">
				
				<div id="myModal3" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
						<h3 id="myModalLabel" class="text-left">联系组成员检查</h3>
					</div>
					<div class="modal-body">
						<div>总条数：<span id="total"></span>条</div>
						<div>合法数：<span id="legal"></span>条</div>
						<div>非法数：<a href="" id="illegal"></a>条</div>
					</div>
					<div class="modal-footer">
						<input class="btn" data-dismiss="modal" aria-hidden="true" type="button" value="确定" />
					</div>
				</div>
				<div class="row-fluid">
					<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
						{%if $role == "stasker" || $role == "tasker"%}
						<div id="alldel" style="float:left; margin-left: 5px;width: 7%;"><input id="delall" type="button" class="btn" value="批量删除" style="margin-top: 2px;font-size:12px;" /></div>
						<div id="alladd" style="float:left; margin-left: 5px;width: 7%;"><a href="#myModal5" role="button" data-toggle="modal" ><input id="addall" type="button" class="btn" value="批量添加进组" style="margin-top: 2px;font-size:12px;" /></a></div>
						{%/if%}
						<div style="float:right;padding-top: 3px; width: 100px;margin-right: 15px" align="center">
							<label style="height: 30px;width: 106px;">
								<form action="/contact/personlist" method="GET" style="width:106px">
									<select name="num" size="1" aria-controls="DataTables_Table_0" style="width:60px">
										<option {%if $num==5 %}selected="selected"{%/if%} value="5">5</option>
										<option {%if $num==10 %}selected="selected"{%/if%} value="10">10</option>
										<option {%if $num==25 %}selected="selected"{%/if%} value="25">25</option>
										<option {%if $num==50 %}selected="selected"{%/if%} value="50">50</option>
										<option {%if $num==100 %}selected="selected"{%/if%} value="100">100</option>
									</select>
									<input type="submit" style="font-size:12px;height:26px" value="GO" />
								</form>
							</label>
						</div>
						<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="float:right; text-align: center; width: 70%;">
							{%$page%}
						</div>
					</div>
    			</div>
			</div>
		</div>
	</div>
</div>
<div id="myModal5" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="addallperson" method="post" id="myform4">
		<div class="modal-header">
			<button class="close" id="cs" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h3 id="myModalLabel" class="text-left">批量添加进组</h3>
		</div>
		<input type="hidden" name="gpid" value="" />
		<input type="hidden" name="ids" value="{%$ids%}" />
		<p> </p>
		<div  class="modal-body">
			<div>新建组&nbsp;&nbsp;<input type="text" name="newgroup" /><span id="tx"></span></div>
			<p> </p>
			<div>已有组</div>
			<div>
				{%section name=loop loop=$group%}
					<label class="checkbox inline" style="width:150px;margin:0;">
						<a title="{%$group[loop].gname%}"><input type="checkbox" name="gname[]" value="{%$group[loop].id%}">{%if $group[loop].gm%}{%$group[loop].gm%}{%else%}{%$group[loop].gname%}{%/if%}</a>
					</label>
				{%/section%}
			</div>
		</div>
		<p> </p>
		<div class="modal-footer">
			<input type="button" class="btn" id="btn4" value="保存" />
		</div>
	</form>
</div>
<div id="myModal6" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="doaddfilter" method="post" id="myform3">
		<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h3 id="myModalLabel" class="text-left">添加筛选器</h3>
		</div>
		<input type="hidden" name="numstr" value="" id="numstr" />
		<input type="hidden" name="jostr" value="" id="jostr" />
		<input type="hidden" name="sname" value="" id="sname" />
		<input type="hidden" name="ti" value="" id="ti" />
		<input type="hidden" name="opra" value="" id="opra" />
		<p> </p>
		<div  class="modal-body">
			<table>
				<tr>
					<td>筛选器名称 ：</td>
					<td><input id="filtername" type="text" name="filtername" style="width: 250px;">&nbsp;&nbsp;&nbsp;<span style="color:red;">*</span></td>
				</tr>
				<tr>
					<td>描述：</td>
					<td><textarea rows="5" style="width: 250px;" cols="10" name="description"></textarea></td>
				</tr>
			</table>
		</div>
		<p> </p>
		<div class="modal-footer">
			<input type="button" class="btn" id="btn2" value="保存" />
		</div>
	</form>
</div>
<div id="myModal4" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="import" method="post" enctype="multipart/form-data" id="myform5">
		<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h3 id="myModalLabel" class="text-left">导入联系人</h3>
		</div>
		<p> </p>
		<div  class="modal-body">
			<table>
			<input type="hidden" name="import" value="import" />
				<tr>
					<td>选择联系人组</td>
					<td>
						<select name="importgname" id="importgname">
						<option value="" selected="selected">请选择联系人组</option>
						{%section name=loop loop=$group%}
							<option value="{%$group[loop].id%}">{%$group[loop].gname%}</option>
						{%/section%}
						</select>
					</td>
					<td id="td5"></td>
				</tr>
				<tr height="40px">
					<td width="100px"><b>文件导入：</b></td>
					<td><input style="height:28px;" type="file" name="file" id="exportemail"/></td>
				</tr>
				<tr>
					<td></td>
					<td>导入文件的类型是xls，xlsx</td>
				</tr>
			</table>
		</div>
		<p> </p>
		<div class="modal-footer">
			<input type="button" class="btn" id="bt5" value="导入" />
		</div>
	</form>
</div>
<div id="myModal7" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="export" method="post" enctype="multipart/form-data" id="myform7">
		<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h3 id="myModalLabel" class="text-left">导出联系人</h3>
		</div>
		<p> </p>
		<div  class="modal-body">
			<table>
			<input type="hidden" name="import" value="import" />
				<tr>
					<td>选择联系人组</td>
					<td>
						<select name="exportgname" id="exportgname">
						<option value="" selected="selected">请选择联系人组</option>
						{%section name=loop loop=$group%}
							<option value="{%$group[loop].id%}">{%$group[loop].gname%}</option>
						{%/section%}
						</select>
					</td>
				</tr>
			</table>
		</div>
		<p> </p>
		<div class="modal-footer">
			<input type="button" class="btn" id="bt7" value="导出" />
		</div>
	</form>
</div>
<input type="hidden" value="{%$per_num%}" id="per_num" />
<input type="hidden" value="{%$search_cont%}" id="search_cont" />
<input type="hidden" value="{%$total_page%}" id="total_page" />
<input type="hidden" value="{%$cur_page%}" id="cur_page" />
<input type="hidden" value="{%$page_type%}" id="page_type" />
<input type="hidden" value="" id="checklist" name="checklist" />
<input type="hidden" value="{%$sel_status%}" id="sel_status" name="sel_status" />
<input type="hidden" value="{%$msg%}" id="msg" name="" />
<!--Footer-part-->
<script type="text/javascript">
	$(function(){
		var msg = $('#msg').val();
		if(msg){
			art.dialog.alert('导入数据不正确，请重新导入');
		}
	})
    $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayBtn: true,
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
        pickerPosition: "bottom-left"
    });
    $(function(){
		if($("#sel2").val()==0){
			$("#judge").hide();
		}else{
			$("#judge").show();
		}
    })
    $(function(){
		$("#bt5").click(function(){
			var filename=$("#exportemail").val(); 
			var extStart=filename.lastIndexOf("."); 
			var ext=filename.substring(extStart,filename.length).toUpperCase(); 
			if(ext != ".XLS" && ext != ".XLSX"){ 
				$('#myModal4').modal('hide');
					art.dialog.alert("导入文件格式不正确，文件限于xls，xlsx格式！",function(){$('#myModal4').modal('show');});
					return false;
			}
			var importgname = $("#importgname option:selected").val();//alert(importgname);
			if(importgname == ""){
				$("#td5").text("请选择联系人组");
				$("#td5").attr("class","col");
				return false;
			}else{
				$("#td5").text("");
			}
			$("#myform5").submit();
		})
		$("#delall").click(function(){
			var ck = $("input[name='ck[]']:checked");
			var ids = "";
			$.each(ck,function(){
				ids += $(this).val()+",";
			})
			var gid = $("input[name='status']").val();
			if(ids == ""){
				art.dialog.alert("请选择批量删除的条目");
				return false;
			}
			art.dialog.confirm("您将要删除所选中的条目，是否继续？",function(){
				$.ajax({
					type:"post",
					url:"ajaxdelall",
					data:{'ids':ids,'gid':gid},
					success:function(data){
						if(data == "error"){
							art.dialog.alert("含有其他成员数据,仅删除本账户创建的!");
							window.setTimeout(function(){ location.reload();},3000); 	
						}else{
							location.reload();	
						}
						
					}
				})
			})
		})
    })
    $(function(){
		$("#bt7").click(function(){
			var gid = $("#exportgname").find("option:selected").val();
			if(gid == ""){
				art.dialog.alert("请选择要导出的联系人组!");
				return false;	
			}else{
				$("#myform7").submit();
				$(".close").click();
			}
			
		})
		$("#resetsearch").click(function(){
			$("#tbone").empty();
			var pretermit = "";
			pretermit += "<tr><td width='10%' style='text-align:center'><input type='text' custom='0' value='筛选条件' disabled=true style='width:73px;border:0px;text-align:center;font-size:12px' name='jo[]'></td>";
			pretermit += "<td width='10%' style='text-align:center'><b><input type='text' value='1' disabled=true style='width:73px;border:0px;text-align:center;font-size:12px' name='number[]'></b></td>";
			pretermit += "<td width='18%' style='text-align:center'><select name='showname[]' style='width:150px;font-size:12px' onchange='changevalue(\"selopr(1)\",\"opra(1)\",\"text(1)\")' id='selopr(1)'>";
			function redata(){
				var result;
				$.ajax({
					data:"post",
					dataType:"json",
					url:"getexpansion",
					async:false,
					success:function(data){
						result = data;
					}
				})
				return result;
			}
			dataobj = redata();
			$.each(dataobj,function(i,result){
				pretermit += "<option value="+result.id+" type="+result.type+">"+result.showname+"</option>";
			})
			pretermit += "</select></td>";
			var type = $("#showname option:selected").attr("type");
			var opr = $("#op option:selected").val();
			var arr = new Array();
			var brr = new Array();
			var crr = new Array();
			arr = {"0":"是","1":"否","2":"包含","3":"不包含","4":"开始字符","5":"结束字符"};
			brr = {"100":"等于","101":"不等于","102":"大于","103":"不大于","104":"小于","105":"不小于"};
			crr = {"200":"之前","201":"之后","202":"介于","203":"不介于"};
			pretermit += "<td width='15%' style='text-align:center'><select name='opra[]' onchange='changetime(\"selopr(1)\",\"opra(1)\",\"text(1)\")' style='width:120px;font-size:12px' id='opra(1)'>";
				$.each(arr,function(i,result){
					if(opr == i){
						pretermit += "<option value='"+i+"' selected='selected'>"+result+"</option>";
					}else{
						pretermit += "<option value='"+i+"'>"+result+"</option>";
					}
				})
				if(opr == 300){
					pretermit += "<option value='300' selected='selected'>为空</option>";
				}else{
					pretermit += "<option value='300'>为空</option>";
				}
				if(opr == 301){
					pretermit += "<option value='301' selected='selected'>不为空</option>";
				}else{
					pretermit += "<option value='301'>不为空</option>";
				}
			pretermit += "</select></td>";
			pretermit += "<td width='25%' style='text-align:center' id='text1'><input type='text' value='' style='border:1px solid #ccc;width:227px;font-size:12px' name='str[]'></td><td width='15%' style='text-align:center'><a class='add icon-plus btn btn-small' href='javascript:addcon()'></a></td></tr>";
			$("#tbone").append(pretermit);
		})
    })
	
	$("#btn6").click(function(){
		if(checkfilter()){
			$('#myModal6').modal('show');
		}else{
			$('#myModal6').modal('hide');
			 art.dialog.alert("筛选条件不能为空！");
		}
	});
	
	function checkfilter(){
		var result = false;
		var i = 0;
		var n=$("input[name='str[]']").length;
		$("input[name='str[]']").each(function(){	
			if ($(this).val() != "") {
				i += 1;
			}
		});  
		if(n == i){
			return true;
		}else{
			return false;
		}	
	}	
	//列表搜索
	var s_data=$('#s_data').val();
	if(s_data == 'yes'){
		$("#dis").css("display","block");
	    $("#quicknav").children().children("i").attr("class",'icon-chevron-up');
	}
</script>
{%include file="footer.php"%}
