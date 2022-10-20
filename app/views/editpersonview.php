{%include file="header.php"%}
<style>
.col{color:red;}
</style>
<script type="text/javascript">
$(function(){
	$("#new").click(function(){
		$("#newgname").val('');
		if(this.checked){
			$("#expand").hide();
		}else{
			$("#expand").show();
			$("#newgname").val('');
		}
	})
	$("#bt").click(function(){
		var mailbox = $("#mailbox").val();
		var id = $("input[name='id']").val();
		var search_str = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
		var gid = $("#sel").val();
		var gname = $("#newgname").val();
		if(!search_str.test(mailbox)){
			$("#td1").text("请输入正确的邮箱格式");
			$("#td1").attr("class","col");
			$("#mailbox").focus();
		    return false;
		}
//		if(gname!='' && gid==0){
			$.ajax({
				type:"post",
				url:"ajaxgroup",
				data:{'gname':gname,'mailbox':mailbox,'status':'update','id':id},
				dataType:"json",
				success:function(data){
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
					if(gname!='' && gid!=0){
						$("#prompt").text("不能同时提交多组");
						$("#prompt").attr("class","col");
						return false;
					}
					if(gname=='' && gid==0){
						$("#prompt").text("请选择组");
						$("#prompt").attr("class","col");
						return false;
					}
					$("#myform").submit();
				}
			})		
	})
	
})

function getExpand(){
	var id = $("#sel").val();
	$("#expand").empty();
	$.ajax({
		type:"post",
		url:"ajaxexpand",
		data:{'id':id},
		dataType:"json",
		success:function(data){
			var dv = ' ';
			$.each(data,function(i,result){
				dv =dv+ "<div>"+result.showname+":<input type='text' name='"+result.name+"' /></div>";
			})
				$("#expand").append(dv);
		}
	})
}

</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="#" title="返回主页" class="tip-bottom"><i class="icon-home"></i> 主页</a> <a href="#" class="current">联系人组</a> </div>
	</div>
	<hr>
	<div class="container-fluid">
		<div align="center">
			<form action="/contact/updateperson" method="post" id="myform">
					<div  class="modal-body">
						<h5>基本信息</h5>
						<table>
							<tr>
								<td style="width:20%">邮箱：</td>
								<td><input id="mailbox" type="text" name="mailbox" value="{%$result.mailbox%}" /><span style="color:red;">*</span></td>
								<input type="hidden" name="id" value="{%$result.id%}" />
								<td id="td1">邮箱不能重复</td>
							</tr>
							<tr>
								<td>姓名：</td>
								<td><input type="text" name="username" value="{%$result.username%}" /></td>
								<td></td>
							</tr>
							<tr>
								<td>性别：</td>
								<td><input type="radio" {%if $result.sex==1 %} checked="checked" {%/if%} name="sex" value="1" style="margin-top:0px" />男&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" {%if $result.sex==0 %} checked="checked" {%/if%} name="sex" value="0"  style="margin-top:0px" />女</td>
								<td></td>
							</tr>
							<tr>
								<td>出生日期：</td>
								<td><div class="input-append date form_datetime"><input name="birth" size="16" type="text" value="{%$result.birth%}" readonly><span class="add-on"><i class="icon-th"></i></span></div></td>
								<td></td>
							</tr>
							<tr>
								<td>手机：</td>
								<td><input type="text" name="tel" value="{%$result.tel%}" /></td>
								<td></td>
							</tr>
						</table>
						<h5>组信息</h5>
						<table>
							<tr>
								<td style="width:20%">分组：</td>
								<td>
									<div><input type="checkbox" id="new">新建组&nbsp;&nbsp;&nbsp;<input style="width:100px" id="newgname" type="text" name="gname" /></div>
								</td>
								<td>
									<select name="gid" id="sel" style="width:90%" onchange="getExpand()">
										<option value="0" selected="selected">请选择已有组</option>
										{%section name=loop loop=$group%}
										<option value="{%$group[loop].id%}">{%$group[loop].gname%}</option>
										{%/section%}
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="3" id="prompt"></td>
							</tr>
						</table>
						<div>
						<h5>扩展信息</h5>
						<div id="expand">
						
						</div>
						</div>
					</div>
					<div>
						<input type="button" class="btn" id="bt" value="保存" /><input class="btn" data-dismiss="modal" aria-hidden="true" type="button" value="取消" />
					</div>
				</form>
		</div>
	</div>
</div>
<script type="text/javascript">
    $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd hh:ii:ss",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"
    });
</script>
{%include file="footer.php"%}