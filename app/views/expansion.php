{%include file="header.php"%}
<style>
        .col{color:red;}
</style>
<script type="text/javascript">
	$(function(){
		var TAG = true;
		$("#bt").click(function(){
			var showname = document.getElementById("showname").value;
			var name = document.getElementById("name1").value;
			var str = /[a-zA-Z_]./;
			if(!str.test(name)){
				alert("请输入英文");
				$("#name1").focus();
				return false;
			}
			//var gid = document.getElementById("gid").value;
			$.ajax({
				type:"post",
				url:"ajaxextension",
				data:{
					'showname':showname,
					'name':name
				},
				dataType:"json",
				success:function(data){
					var tag1 = true;
					var tag2 = true;
					if(data==1){
						if(showname == ''){
							$("#td1").text("字段名称重复");
							$("#td1").attr("class","col");
							tag1 = false;
						}else{
							$("#td1").text("字段名称合法");
							$("#td1").removeClass("col");
							tag1 = true;
						}
						if(name == ''){
							$("#td2").text("字段名称英文不能为空");
							$("#td2").attr("class","col");
							tag2 = false;
						}else{
							if(name!='username'&&name!='mailbox'&&name!='sex'&&name!='birth'&&name!='tel'){
								$("#td2").text("字段名称英文合法");
								$("#td2").removeClass("col");
								tag2 =true;
							}else{
								$("#td2").text("字段名称不能为基本字段");
								$("#td2").attr("class","col");
								tag2 =false;
							}
						}
//						if(gid == 0){
//							$("#td3").text("请选择所属组");
//							$("#td3").attr("class","col");
//							tag3 = false;
//						}else{
//							$("#td3").text("");
//							$("#td3").removeClass("col");
//							tag3 =true;
//						}
						if(tag1 && tag2){
							$("#myform").submit();
						}
					}else{
						var json = eval(data);
							var tag = true;
							if(showname == data.showname){
								$("#td1").text("字段名称重复");
								$("#td1").attr("class","col");
								tag1 = false;
							}else{
								if(showname==''){
									$("#td1").text("字段名称不能为空");
									$("#td1").attr("class","col");
									tag1 = false;
								}else{
									$("#td1").text("字段名称合法");
									$("#td1").removeClass("col");
									tag1 = true;
								}
							}				
							if(name == data.name){
								$("#td2").text("对不起，该字段名称英文已经存在");
								$("#td2").attr("class","col");
								tag2 = false;
							}else{
								if(name == ''){
									$("#td2").text("字段名称英文不能为空");
									$("#td2").attr("class","col");
									tag2 = false;
								}else{
									if(name!='username'&&name!='mailbox'&&name!='sex'&&name!='birth'&&name!='tel'){
										$("#td2").text("字段名称英文合法");
										$("#td2").removeClass("col");
										tag2 = true;
									}else{
										$("#td2").text("字段名称不能为基本字段");
										$("#td2").attr("class","col");
										tag2 =false;
									}
								}
							}
//							if(gid == 0){
//								$("#td3").text("请选择所属组");
//								$("#td3").attr("class","col");
//								tag3 = false;
//							}else{
//								$("#td3").text("");
//								$("#td3").removeClass("col");
//								tag3 =true;
//							}
							if(tag1 && tag2){
								$("#myform").submit();
							}
					}
				}
			});		
		});
	})
	
	//获取编辑自定义字段组信息
	function getinfo(id){
		$(function(){
			$("#hi").val(id);
			$.ajax({
				data:{'id':id,'status':'expansion'},
				type:'post',
				dataType:'json',
				url:'getinfo',
				success:function(data){
					$("#showname2").val(data.showname);
					$("#name2").val(data.name);
					if(data.type == 1){
						$("#radio1").attr("checked","checked");
					}else if(data.type == 2){
						$("#radio2").attr("checked","checked");
					}else if(data.type == 3){
						$("#radio3").attr("checked","checked");
					}else if(data.type == 4){
						$("#radio4").attr("checked","checked");
					}
				}
			})
			var TAG = true;
			$("#bt2").click(function(){
				var showname = document.getElementById("showname2").value;
				var name = document.getElementById("name2").value;
//				var gid = document.getElementById("gid2").value;
				$.ajax({
					type:"post",
					url:"ajaxextension",
					data:{
						'showname':showname,
						'name':name,
						'id':id,
						'status':'editextension'
					},
					dataType:"json",
					success:function(data){
						var tag1 = true;
						var tag2 = true;
						if(data==1){
							if(showname == ''){
								$("#td4").text("字段名称重复");
								$("#td4").attr("class","col");
								tag1 = false;
							}else{
								$("#td4").text("字段名称合法");
								$("#td4").removeClass("col");
								tag1 = true;
							}
							if(name == ''){
								$("#td5").text("字段名称英文不能为空");
								$("#td5").attr("class","col");
								tag2 = false;
							}else{
								if(name!='username'&&name!='mailbox'&&name!='sex'&&name!='birth'&&name!='tel'){
									$("#td5").text("字段名称英文合法");
									$("#td5").removeClass("col");
									tag2 =true;
								}else{
									$("#td5").text("字段名称不能为基本字段");
									$("#td5").attr("class","col");
									tag2 =false;
								}
							}
//							if(gid == 0){
//								$("#td6").text("请选择所属组");
//								$("#td6").attr("class","col");
//								tag3 = false;
//							}else{
//								$("#td6").text("");
//								$("#td6").removeClass("col");
//								tag3 =true;
//							}
							if(tag1 && tag2){
								$("#myform2").submit();
							}
						}else{
							var json = eval(data);
								var tag = true;
								if(showname == data.showname){
									$("#td4").text("字段名称重复");
									$("#td4").attr("class","col");
									tag1 = false;
								}else{
									if(showname==''){
										$("#td4").text("字段名称不能为空");
										$("#td4").attr("class","col");
										tag1 = false;
									}else{
										$("#td4").text("字段名称合法");
										$("#td4").removeClass("col");
										tag1 = true;
									}
								}				
								if(name == data.name){
									$("#td5").text("对不起，该字段名称英文已经存在");
									$("#td5").attr("class","col");
									tag2 = false;
								}else{
									if(name == ''){
										$("#td5").text("字段名称英文不能为空");
										$("#td5").attr("class","col");
										tag2 = false;
									}else{
										if(name!='username'&&name!='mailbox'&&name!='sex'&&name!='birth'&&name!='tel'){
											$("#td5").text("字段名称英文合法");
											$("#td5").removeClass("col");
											tag2 = true;
										}else{
											$("#td5").text("字段名称不能为基本字段");
											$("#td5").attr("class","col");
											tag2 =false;
										}
									}
								}
//								if(gid == 0){
//									$("#td6").text("请选择所属组");
//									$("#td6").attr("class","col");
//									tag3 = false;
//								}else{
//									$("#td6").text("");
//									$("#td6").removeClass("col");
//									tag3 =true;
//								}
								if(tag1 && tag2){
									$("#myform2").submit();
								}
						}
					}
				});		
			});
		})
	}
	$(function(){
		$("#selectAll").click(function(){
			if($("#selectAll").attr("checked")){
				$.each($("input[name='selexpansion[]']"),function(i,result){
					$(this).attr("checked",true);
				})
			}else{
				$.each($("input[name='selexpansion[]']"),function(i,result){
					$(this).attr("checked",false);
				})
			}
		})
		
		$('#delall').click(function(){
			var id="";
			$("input[name='selexpansion[]']:checked").each(function () {
				id = id+','+$(this).val();				
			});
			if(id == ""){
				art.dialog.alert("请选择要删除的条目");
				return false;
			}
			art.dialog.confirm("您将要删除所选中的条目，是否继续？",function(){
				$.post('/contact/deletexpansion',{'del_str_id':id},function(data){
					if(data){
						location.reload();
					}
				});
			})
		});
	});
	
	function del(id){
		confirm_ = art.dialog.confirm('确定删除该属性？',function(){
			$.post('/contact/deletexpansion',{'id':id},function(data){
					location.reload();
			});
		})
	}
</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#" class="">联系人管理</a><a href="#" class="current">自定义属性</a> </div>
	</div>
	<div class="container-fluid">
		<div style="width:100%;">
			<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<form action="/contact/addexpansion" id="myform" method="post">
					<div class="modal-header">
						<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
						<h3 id="myModalLabel" class="text-left">新增属性</h3>
					</div>
					<div class="modal-body">
						<p> </p>
						<table>
							<tr height="40px">
								<td>字段名称：</td>
								<td><input type="text" id="showname" maxlength="20" name="showname" />&nbsp;&nbsp;&nbsp;<span style="color:red;">*</span></td>
								<td id="td1">20个字符以内</td>
							</tr>
							<tr>
								<td>字段英文名：</td>
								<td><input type="text" id="name1" maxlength="20" name="name" />&nbsp;&nbsp;&nbsp;<span style="color:red;">*</span></td>
								<td id="td2">20个字符以内</td>
							</tr>
							<tr height="40px">
								<td>字段类型：</td>
								<td>
									<input type="radio" name="type" value="1" style="margin-top:3px;" checked />字符&nbsp;&nbsp;&nbsp;
									<input type="radio" name="type" value="2" style="margin-top:3px;" />整型&nbsp;&nbsp;&nbsp;
									<input type="radio" name="type" value="3" style="margin-top:3px;" />日期&nbsp;&nbsp;&nbsp;
									<input type="radio" name="type" value="4" style="margin-top:3px;" />小数
								</td>
								<td></td>
							</tr>
							<!-- <tr>
								<td>所属组：</td>
								<td>
									<select name="gid" id="gid">
										<option value="0">请选择所属组</option>
										{%section name=loop loop=$data%}
											<option value="{%$data[loop].id%}">{%$data[loop].gname%}</option>
										{%/section%}
									</select>
									&nbsp;&nbsp;&nbsp;<span style="color:red;">*</span>
								</td>
								<td id="td3"></td>
							</tr> -->
						</table>
						<p> </p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" id="bt" value="保存" />
					</div>
				</form>
			</div>
		</div>
		<div class="widget-box" style="margin-top: 35px;padding-bottom: 10px; border-bottom: 1px solid #cdcdcd;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>自定义属性</h5>
				<div id="create" style="float: right; margin-top: 3px; margin-right: 5px;"><a href="#myModal" style="font-size:12px;" role="button" class="btn" data-toggle="modal">新增属性</a></div>
			</div>
			<!-- <div class="">
				<div id="DataTables_Table_0_length" class="dataTables_length">
					<label>
						<form action="/contact/expansion" method="GET" style="width:106px">
							<select name="num" size="1" aria-controls="DataTables_Table_0" style="width:60px">
								<option {%if $num==5 %}selected="selected"{%/if%} value="5">5</option>
								<option {%if $num==10 %}selected="selected"{%/if%} value="10">10</option>
								<option {%if $num==25 %}selected="selected"{%/if%} value="25">25</option>
								<option {%if $num==50 %}selected="selected"{%/if%} value="50">50</option>
								<option {%if $num==100 %}selected="selected"{%/if%} value="100">100</option>
							</select>
							<input type="submit" value="GO" style="font-size:12px;height:26px;" />
						</form>
					</label>
				</div>
			</div> -->
		<div class="widget-content nopadding" style="border-bottom: 0px;">
			<table width="80%" align="center" class="table table-bordered table-striped" style="border-bottom: 1px solid #cdcdcd;">
				<thead>
					<tr>
						<th style="text-align:center"><input type="checkbox" id="selectAll" onclick="selectall(this);" style="margin-right: 10px;">&nbsp;&nbsp;全选</th>
						<th style="text-align:center">字段别名</th>
						<th style="text-align:center">字段英文名</th>
						<th style="text-align:center">字段类型</th>
						<th style="text-align:center">操作</th>
					</tr>
				</thead>
				<tbody>
				{%section name=loop loop=$res%}
					<tr>
						<td style="text-align:center"><input type="checkbox" name="selexpansion[]" value="{%$res[loop].id%}" /></td>
						<td style="text-align:center">{%$res[loop].showname%}</td>
						<td style="text-align:center">{%$res[loop].name%}</td>
						<td style="text-align:center">{%$res[loop].typename%}</td>
						<td style="text-align:center"><span onclick="getinfo({%$res[loop].id%})"><a href="#myModal2" data-toggle="modal"><i class="icon-edit">编辑</i></a></span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a onclick="del({%$res[loop].id%})" style="cursor:pointer;"><i class="icon-remove">删除</i></a></td>
					</tr>
				{%/section%}
				</tbody>
			</table>
			<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<form action="/contact/editexpansion" id="myform2" method="post">
					<div class="modal-header">
						<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
						<h3 id="myModalLabel" class="text-left">编辑自定义字段</h3>
					</div>
					<div class="modal-body">
						<p> </p>
						<table>
							<tr height="40px">
								<td>字段名称：</td>
								<td><input type="text" id="showname2" maxlength="20" name="showname" />&nbsp;&nbsp;&nbsp;<span style="color:red;">*</span></td>
								<td id="td4">20个字符以内</td>
							</tr>
							<tr>
								<td>字段英文名：</td>
								<td><input type="text" id="name2" maxlength="20" name="name" />&nbsp;&nbsp;&nbsp;<span style="color:red;">*</span></td>
								<td id="td5">20个字符以内</td>
								<input type="hidden" id="hi" name="id" value="123" />
							</tr>
							<tr height="40px">
								<td>字段类型：</td>
								<td>
									<input type="radio" id="radio1" name="type" value="1" style="margin-top:3px;" />字符&nbsp;&nbsp;&nbsp;
									<input type="radio" id="radio2" name="type" value="2" style="margin-top:3px;" />整型&nbsp;&nbsp;&nbsp;
									<input type="radio" id="radio3" name="type" value="3" style="margin-top:3px;" />日期&nbsp;&nbsp;&nbsp;
									<input type="radio" id="radio4" name="type" value="4" style="margin-top:3px;" />小数
								</td>
								<td></td>
							</tr>
							<!-- <tr>
								<td>所属组：</td>
								<td>
									<select name="gid2" id="gid2">
										<option value="0">请选择所属组</option>
										{%section name=loop loop=$data%}
											<option value="{%$data[loop].id%}">{%$data[loop].gname%}</option>
										{%/section%}
									</select>
									&nbsp;&nbsp;&nbsp;<span style="color:red;">*</span>
								</td>
								<td id="td6"></td>
							</tr> -->
						</table>
						<p> </p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" id="bt2" value="保存" />
					</div>
				</form>
			</div>
			
			<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="margin-top: 5px;">
				<div class="row-fluid" style="margin-top:0px;">
					<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
						<div id="" class="" style="float:left;margin-left:8px;">
							<input style="margin-top: 2px; font-size: 12px; width: 60px; padding: 3px 5px;" value="批量删除" class="btn" id="delall"/>
						</div>
						<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;float:left;margin-left:20%;">
								{%$page%}
							</div>
						<div id="DataTables_Table_0_length" class="dataTables_length" style="float:right;width:100px;margin-right:15px">
							<label>
								<form action="/contact/expansion" method="GET" style="width:106px">
									<select name="num" size="1" aria-controls="DataTables_Table_0" style="width:60px">
										<option {%if $num==5 %}selected="selected"{%/if%} value="5">5</option>
										<option {%if $num==10 %}selected="selected"{%/if%} value="10">10</option>
										<option {%if $num==25 %}selected="selected"{%/if%} value="25">25</option>
										<option {%if $num==50 %}selected="selected"{%/if%} value="50">50</option>
										<option {%if $num==100 %}selected="selected"{%/if%} value="100">100</option>
									</select>
									<input type="submit" value="GO" style="font-size:12px;height:26px;" />
								</form>
							</label>
						</div>
					</div>
    			</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" value="{%$per_num%}" id="per_num" />
<input type="hidden" value="{%$search_cont%}" id="search_cont" />
<input type="hidden" value="{%$total_page%}" id="total_page" />
<input type="hidden" value="{%$cur_page%}" id="cur_page" />
<input type="hidden" value="{%$page_type%}" id="page_type" />
<input type="hidden" value="" id="checklist" name="checklist" />
<!--Footer-part-->
{%include file="footer.php"%}