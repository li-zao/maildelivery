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
	$(function(){
		$("#bt").click(function(){
			var showname = document.getElementById("showname").value;
			var name = document.getElementById("name1").value;
			var id = document.getElementsByName("id")[0].value;
			$.ajax({
				type:"post",
				url:"ajaxextension",
				data:{
					'showname':showname,
					'name':name,
					'id':id
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
										tag2 =true;
									}else{
										$("#td2").text("字段名称不能为基本字段");
										$("#td2").attr("class","col");
										tag2 =false;
									}
								}
							}
							if(tag1 && tag2){
								$("#myform").submit();
							}
					}
				}
			});		
		});
	})
</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="#" title="返回主页" class="tip-bottom"><i class="icon-home"></i> 主页</a> <a href="#" class="current">联系人组</a> </div>
	</div>
	<hr>
	<div class="container-fluid">
		<div align="center">
			<form action="/contact/editexpansion" method="post" id="myform">
				<div>
				<table>
							<tr>
								<td>字段名称：</td>
								<td><input type="text" id="showname" name="showname" value="{%$result.showname%}" />&nbsp;&nbsp;&nbsp;<span style="color:red;">*</span></td>
								<td id="td1"></td>
							</tr>
							<input type="hidden" name="id" value="{%$result.id%}" />
							<tr>
								<td>字段英文名：</td>
								<td><input type="text" id="name1" name="name" value="{%$result.name%}" />&nbsp;&nbsp;&nbsp;<span style="color:red;">*</span></td>
								<td id="td2"></td>
							</tr>
							<tr>
								<td>字段类型：</td>
								<td>
									<input type="radio" {%if $result.type==1 %}checked="checked"{%/if%} name="type" value="1" style="margin-top:0px;" />字符&nbsp;&nbsp;&nbsp;
									<input type="radio" {%if $result.type==2 %}checked="checked"{%/if%} name="type" value="2" style="margin-top:0px;" />整型&nbsp;&nbsp;&nbsp;
									<input type="radio" {%if $result.type==3 %}checked="checked"{%/if%} name="type" value="3" style="margin-top:0px;" />日期&nbsp;&nbsp;&nbsp;
									<input type="radio" {%if $result.type==4 %}checked="checked"{%/if%} name="type" value="4" style="margin-top:0px;" />小数
								</td>
								<td></td>
							</tr>
				</table>
				</div>
				<div>
						<input type="button" class="btn" id="bt" value="保存" /><input type="button" class="btn" value="取消" /></a>
				</div>
			</form>
		</div>
	</div>
</div>
{%include file="footer.php"%}