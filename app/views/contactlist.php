{%include file="header.php"%}
<style>
        .col{color:red;}
</style>
<script type="text/javascript">
	$(function(){
		$("#sear").click(function(){
			var dis = document.getElementById("dis");
			$("#dis").slideToggle();
		});
	})
	//验证联系人组添加信息
	$(function(){
		var TAG = true;
		$("#bt").click(function(){
			var gname = document.getElementById("gname").value;
			var remark = $("#remark").val();
			if(remark.length>20){
				$("#rm").css({"color":"red"});
				return false;
			}
			$.ajax({
				type:"post",
				url:"ajaxgroup",
				data:{
					'gname':gname
				},
				dataType:"json",
				success:function(data){
					if(data == 1){
						$("#prompt").text("该组已经存在");
						$("#prompt").attr("class","col");
						TAG = false;
					}else{
						if(gname == ''){
							$("#prompt").text("组名称不能为空");
							$("#prompt").attr("class","col");
							TAG = false;
						}else{
							$("#prompt").text("组名称合法");
							$("#prompt").removeClass("col");
							TAG = true;
						}
					}
					if(TAG == true){
						$("#myform").submit();
					}
				},
			});
			
		});
	})
	
	//验证添加联系人组是否重复
	function checkgname(){
		$(function(){
			var TAG = true;
			$("#bt").click(function(){
				var gname = document.getElementById("gname").value;
				$.ajax({
					type:"post",
					url:"ajaxgroup",
					data:{
						'gname':gname
					},
					dataType:"json",
					success:function(data){
						if(data == 1){
							$("#prompt").text("该组已经存在");
							$("#prompt").attr("class","col");
							TAG = false;
						}else{
							if(gname == ''){
								$("#prompt").text("组名称不能为空");
								$("#prompt").attr("class","col");
								TAG = false;
							}else{
								$("#prompt").text("组名称合法");
								$("#prompt").removeClass("col");
								TAG = true;
							}
						}
						if(TAG == true){
							$("#myform").submit();
						}
					},
				});
				
			});
		})
	}
	
	//获取编辑联系人组信息
	function getInfo(id){
		$(function(){
			$("#hi").val(id);
			$.ajax({
				data:{'id':id,'status':'group'},
				type:'post',
				dataType:'json',
				url:'getinfo',
				success:function(data){
					$("#gn").val(data.gname);
					$("#re").val(data.remark);
				}
			})
			var TAG = true;
			$("#edit").click(function(){
				var gname = document.getElementById("gn").value;
				$.ajax({
					type:"post",
					url:"ajaxgroup",
					data:{
						'id':id,
						'gname':gname,
						'status':'editgroup'
					},
					dataType:"json",
					success:function(data){
						if(data == 1){
							$("#prompt2").text("该组已经存在");
							$("#prompt2").attr("class","col");
							TAG = false;
						}else{
							if(gname == ''){
								$("#prompt2").text("组名称不能为空");
								$("#prompt2").attr("class","col");
								TAG = false;
							}else{
								$("#prompt2").text("组名称合法");
								$("#prompt2").removeClass("col");
								TAG = true;
							}
						}
						if(TAG == true){
							$("#myform2").submit();
						}
					},
				});
				
			});
		})
	}

	function combinegroup(obj){
		$(function(){
			$("#groupname").blur(function(){
				
			})
			$('#group_tr').hide();
			$("#thisgroup").text(obj);
			$("#targetgroup").attr("value",obj);
			var str = "";
			$("#dv").empty();
			$.ajax({
				type:"post",
				url:"getgroups",
				data:{'gname':obj,'status':"combine"},
				dataType:"json",
				success:function(data){
					$.each(data,function(i,result){
						// str += "<label class='checkbox inline' style='width:100px;margin-left: 47px;'><input type='checkbox' name='gname[]' value='"+result.id+"' /><span style='font-size:12px;'>"+result.gname+"</span></label>";
						str += "<label class='checkbox' style='width:100px;display:inline-block'><input type='checkbox' name='gname[]' value='"+result.id+"' /><span style='font-size:12px;'>"+result.gname+"</span></label>";
					})
					$("#dv").append(str);
				}
			})
			$("#combine").click(function(){
				var result = false;
				if($("input[name='gname[]']:checked").length == 0){
					art.dialog.alert("请选择要合并的组");
					return false;
				}
				var groupname = $("#groupname").val();
				var r = document.getElementsByName("gname[]"); 
				var targetgroup = $("#targetgroup").val();
				var gids = "";
				for(var i=0;i<r.length;i++){
					if(r[i].checked){
					gids += r[i].value +  ","
				    }
				} 
				$.ajax({
					async:false,
					type:"post",
					url:"combinegroup",
					data:{
						'gids':gids,
						'targetgroup':targetgroup
					},
					success:function(data){
						if(data == 1){
							if( groupname == ""){
								art.dialog.alert("请设置新合并组的名称!");
								result = false;
							}else{
								result = true;
							}
						}else{
							result = true;
						}
					},
				});
				if( result== false ){
					return result;
				}
				
				$.ajax({
					type:"post",
					url:"ajaxgroup",
					data:{
						'gname':groupname
					},
					dataType:"json",
					success:function(data){
						if(data == 1){
							$("#sp").text("该组已经存在");
							$("#sp").attr("class","col");
							return false;
						}else{
								$("#sp").text("组名称合法");
								$("#sp").removeClass("col");
						}
						$("#myform3").submit();
					},
				});
			})
			
		})
	}

	$(function(){
		$("#delall").click(function(){
			var ck = $("input[name='ck[]']:checked");
			var ids = "";
			$.each(ck,function(i,result){
				ids += $(this).val()+",";
			})
			if(ids == ""){
				art.dialog.alert("请选择批量删除的条目");
				return false;
			}
			art.dialog.confirm("您将要删除所选中的条目，是否继续？",function(){
				$.ajax({
					type:"post",
					url:"ajaxdelallgroups",
					data:{'ids':ids},

					success:function(data){
						location.reload();
					}
				})
			})
		})
	})
	
	$(function(){
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
		$("#resetsearch").click(function(){
			$(':input','#searchform')
			.not(':button,:submit,:reset,:hidden')
			.val('')
			.removeAttr('checked')
			.removeAttr('selected'); 
		})
		
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
		})
	})
	
	function del(id){
		confirm_ = art.dialog.confirm('确定删除该组？',function(){
			$.post('/contact/delete',{'id':id},function(data){
					location.reload();
			});
		})	
	}
	
</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#" class="">联系人管理</a><a href="#" class="current">联系人分组</a> </div>
	</div>
	<div class="container-fluid">
		{%if $gname != "" || $remark != '' || $createman != '' || $createtime != ''%}
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
		<div id="dis" style="display:none;">
			<div style="margin-left: 20px;">
				<form action="" method="get" id="searchform">
					组名称：<input type="text" id="gname2" name="gname" value="{%$gname%}" style="width:160px" />
					组描述：<input type="text" id="remark2" name="remark" value="{%$remark%}" style="width:160px"  />
					创建人：<input type="text" name="createman" {%if $createman%}value="{%$createman%}"{%/if%} style="width:160px" />
					创建时间：<a class="input-append date form_date"><input name="createtime" size="16" type="text" style="width:160px"  {%if $createtime%}value="{%$createtime%}"{%/if%}><span class="add-on"><i class="icon-th"></i></span></a>
					<input type="hidden" name="search" value="ser" />
					<input type="hidden" name="target" value="{%$search%}" />
					<button id="searchtype" class="btn" style="height:27px;font-size:12px;margin-left: 5px;"><i class="icon-zoom-in"></i>&nbsp;搜索</button>
					<a id="resetsearch" class="btn" style="height:17px;font-size:12px;margin-left: 5px;width: 38px;">重置</a>
				</form>
			</div>
		</div>
		<div style="width:100%;">
			<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<form action="/contact/addgroup" id="myform" method="post">
					<div class="modal-header">
						<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
						<h3 id="myModalLabel" class="text-left">新增联系人组</h3>
					</div>
					<div class="modal-body">
						<p> </p>
						<table>
							<tr height="40px">
								<td>组名称：</td>
								<td><input id="gname" maxlength="10" type="text" name="gname"/><span style="color:red;">*</span></td>
								<td><span>&nbsp;&nbsp;&nbsp;组名称在10个字以内</span></td>
							</tr>
							<tr>
								<td id="prompt" colspan="2" >组名称不能与已有组重复</td>
							</tr>
							<tr>
								<td>组描述：</td>
								<td>
									<textarea name="remark" id="remark"></textarea>
								</td>
								<td><span id="rm">&nbsp;&nbsp;&nbsp;组描述在20个字以内</span></td>
							</tr>
						</table>
						<p> </p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" id="bt" value="保存" />
					</div>
				</form>
			</div>
		</div>
		<div class="widget-box" style="padding-bottom: 10px;border-bottom: 1px solid #cdcdcd;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>联系人组</h5>
				{%if $role == "stasker" || $role == "tasker"%}
				<div id="create" style="float: right; height: 25px; margin-top: 1px; margin-right: 8px;"><a href="#myModal" style="margin-top:2px;font-size:12px;" role="button" class="btn" data-toggle="modal">新增联系人组</a></div>
				{%/if%}
			</div>
			<div class="widget-content nopadding">
				<table width="80%" align="center"  class="table table-bordered table-striped">
					<thead>
						<tr>
							{%if $role == "stasker" || $role == "tasker"%}
							<th width="10%" style="text-align:center"><input type="checkbox" id="checkAll">&nbsp;&nbsp;全选</th>
							{%else%}
							<th style="text-align:center; width: 10%;">序号</th>
							{%/if%}
							<th style="text-align:center">名称</th>
							<th style="text-align:center">成员数量</th>
							<th style="text-align:center" width="20%">组描述</th>
							<th style="text-align:center">创建时间</th>
							<th style="text-align:center">创建人</th>
							{%if $role == "stasker" || $role == "tasker"%}
							<th style="text-align:center">操作</th>
							{%/if%}
						</tr>
					</thead>
					<tbody>
					{%$a = 1%}
					{%section name=loop loop=$data%}
						<tr>
							{%if $role == "stasker" || $role == "tasker"%}
							<td style="text-align:center"><input type="checkbox" name="ck[]" value="{%$data[loop].id%}" /></td>
							{%else%}
							<td style="text-align:center">{%$a++%}</td>
							{%/if%}
							
							<td style="text-align:center">
							<a href="/contact/personlist?id={%$data[loop].id%}" title="{%$data[loop].gname%}">
							{%if $data[loop].gm%}
								{%if $data[loop].mine==1%}
									{%$data[loop].gm%} *
								{%else%}
									{%$data[loop].gm%}
								{%/if%}
							{%else%}
								{%if $data[loop].mine==1%}
									{%$data[loop].gname%} *
								{%else%}
									{%$data[loop].gname%}
								{%/if%}
							{%/if%}
							</a>
							</td>
							<td style="text-align:center">{%$data[loop].count%}</td>
							<td style="text-align:center"><a title="{%$data[loop].remark%}">{%if $data[loop].rk%}{%$data[loop].rk%}{%else%}{%$data[loop].remark%}{%/if%}</a></td>
							<td style="text-align:center">{%$data[loop].createtime%}</td>
							{%if $data[loop].createperson == ""%}
								<td style="text-align:center">创建人已删除</td>
							{%else%}
								<td style="text-align:center">{%$data[loop].createperson%}</td>
							{%/if%}
							{%if $role == "stasker" || $role == "tasker"%}
							<td style="text-align:center">
							{%if $data[loop].mine == 1 %}
							<span onclick="getInfo({%$data[loop].id%})" ><a href="#myModal2" data-toggle="modal"><i class="icon-edit">编辑</i></a></span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
							<a onclick="del({%$data[loop].id%})" style="cursor:pointer;"><i class="icon-remove">删除</i></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
							{%else%}
							<i class="icon-edit" style="color:#BBBBBB">编辑</i>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
							<i class="icon-remove" style="color:#BBBBBB">删除</i>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
							{%/if%}
							<a href="#myModal3" onclick='combinegroup("{%$data[loop].gname%}")' data-toggle="modal"><i class="icon-arrow-right">合并</i></a>
							</td>
							{%/if%}
						</tr>
					{%/section%}
					</tbody>
					<tr class="odd hasnodata" id="hasnodata">
					<td class="dataTables_empty" align="center" valign="top" colspan="5">
						没有找到任何数据
					</td>
			  </tr>
				</table>
			</div>
			<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<form action="/contact/edit" id="myform2" method="post">
					<div class="modal-header">
						<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
						<h3 id="myModalLabel" class="text-left">编辑联系人组</h3>
					</div>
					<div class="modal-body">
						<p> </p>
						<table>
							<tr height="40px">
								<td>组名称：</td>
								<td><input id="gn" type="text" maxlength="20" name="gname"/><span style="color:red;">*</span></td>
								<td><span>&nbsp;&nbsp;&nbsp;组名称在20个字以内</span></td>
							</tr>
							<tr>
								<td id="prompt2" colspan="2" >组名称不能与已有组重复</td>
								<input type="hidden" id="hi" name="id" value="123" />
							</tr>
							<tr>
								<td>组描述：</td>
								<td>
									<textarea name="remark" maxlength="20" id="re"></textarea>
								</td>
								<td><span>&nbsp;&nbsp;&nbsp;组描述在20个字以内</span></td>
							</tr>
						</table>
						<p> </p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" id="edit" value="保存" />
					</div>
				</form>
			</div>
			<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
				<div class="row-fluid" style="text-align:right; margin-top: 0px;">
					<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="margin-top: 5px;">
						{%if $role == "stasker" || $role == "tasker"%}
						<div id="alldel" style="margin-left: 16px;float:left;margin-top:2px;"><input id="delall" type="button" class="btn" value="批量删除" style="margin-top: 2px;font-size:12px;" /></div>
						{%/if%}
						<div id="DataTables_Table_0_length" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="float:right;padding-top: 3px; width: 100px;margin-right: 15px" >
							<label style="height:30px;">
								<form action="/contact/contactlist" method="GET" style="width:106px">
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
						<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="float:right; text-align: center; width: 70%;">
							{%$page%}
						</div>
					</div>
    			</div>
			</div>
		</div>
	</div>
</div>
<div id="myModal3" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="/contact/combine" id="myform3" method="post">
		<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h3 id="myModalLabel" class="text-left">联系人组合并</h3>
		</div>
		<div class="modal-body">
			<table>
				<tr>
					<td style="width:20%"><b>当前组</b>：</td>
					<td><span id="thisgroup"></span></td>
				</tr>
				<tr>
					<td><b>合并组</b>：</td>
					<td><div id="dv"></td>
				</tr>
				<tr>
					<td><b>是否合并为新组</b>：</td>
					<td>
						<input type="radio" name="create_type" value="1" onclick="getCreateType(this.value)">是&nbsp;&nbsp;&nbsp;
						<input type="radio" name="create_type" value="0" onclick="getCreateType(this.value)" checked="checked">否
					</td>
				</tr>
				<tr id="group_tr">
					<td><b>新组名称</b>：</td>
					<td>
						<input type="type" name="groupname" id="groupname" />&nbsp;&nbsp;&nbsp;<span id="sp"></span>
					</td>
				</tr>
				<tr>
					<td><b>提示</b>：</td>
					<td>xxxxxxx</td>
				</tr>
			</table>
			<input type="hidden" name="targetgroup" id="targetgroup" value="" />
		</div>
		<div class="modal-footer">
			<input type="button" class="btn" id="combine" value="确定" />
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
<script>
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
})

var target = $("input[name='target']").val();
if(target == "ser"){
	$("#dis").css("display","block");
    $("#quicknav").children().children("i").attr("class",'icon-chevron-up');
}

function getCreateType(type) {
	$('#groupname').val('');
	if (type == '1') {
		$('#group_tr').show();
	} else {
		$('#group_tr').hide();
	}
}
</script>
{%include file="footer.php"%}