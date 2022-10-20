{%include file="header.php"%}
<style>
.col{color:red;}
</style>
<script type="text/javascript">
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
//			error: function(XMLHttpRequest, textStatus, errorThrown) {  //#3这个error函数调试时非常有用，如果解析不正确，将会弹出错误框
//                alert(XMLHttpRequest.status);
//                alert(XMLHttpRequest.readyState);
//                alert(textStatus); // paser error;
//            },
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
			<form action="/contact/edit" method="post" id="myform">
				<div>&nbsp;&nbsp;&nbsp;组名称：<input type="text" id="gname" name="gname" value="{%$data.gname%}" /><span style="color:red;">&nbsp;*</span></div>
				<div id="prompt">组名称不能重复&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div style="height:15px;"></div>
				<div>组描述：<input type="text" name="remark" value="{%$data.remark%}" /></div>
				<div><input type="hidden" name="id" value="{%$data.id%}"></div>
				<div><input type="button" id="bt" value="修改" /><input type="button" value="取消" /></div>
			</form>
		</div>
	</div>
</div>
{%include file="footer.php"%}