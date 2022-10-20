$(function(){
	var input = $("input[type='text']");
	$.each(input,function(i,result){
		$(this).focus(function(){
			$(this).attr("value","");
		})
	})
	
	$("#btn").click(function(){
		if($("input[name='mailbox']").val()=='' || $("input[name='mailbox']").val() == '邮箱'){
			alert("邮箱不能为空");
			return false;
		}
		var search_str = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
		var mailbox = $("input[name='mailbox']").val();
		if(!search_str.test(mailbox)){
			alert("请输入正确的邮箱格式");
			$("input[name='mailbox']").focus();
		    return false;
		}
		var code = $("#captcha").val();
		
		var id = $("#usid").val();
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/contact/ajaxmailbox",
			data:{'id':id,'mailbox':mailbox},
			success:function(data){
				if(data==0){
					$.ajax({
						type:'post',
						dataType:'json',
						url:'/contact/ajaxcode',
						data:{'code':code},
						success:function(data){
							if(data == 1){
								alert("验证码不正确");
								return false;
							}else{
								$("#myform").submit();
							}
						}
					})
					
				}else{
					alert("该邮箱已经订阅");
				}
			}
		})
		//$("#myform").submit();
	})
	
})
function changeimg(){
		var img = document.getElementById("iimg");
		img.src = "/index/captcha?"+Math.random();
}

