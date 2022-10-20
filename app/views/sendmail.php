{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	var actiontype = $("#actiontype").val();
	if (actiontype == "insert") {
		$.gritter.add({
			title:	'添加设置',
			text:	'您已成功添加报告发送配置',
			sticky: false
		});	
	} else if (actiontype == "update") {
		$.gritter.add({
			title:	'更新设置',
			text:	'您已成功更新报告发送配置',
			sticky: false
		});	
	}
	
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	jQuery.validator.addMethod("isMyIP", function(value, element) { 
		return this.optional(element) || (/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/.test(value));    
	}, "IP地址格式有误，请您核对！");
	jQuery.validator.addMethod("isMyPort", function(value, element) { 
		return this.optional(element) || (/^([0-9]|[1-9]\d|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[0-4]\d{2}|655[0-2]\d|6553[0-5])$/.test(value));    
	}, "端口输入范围有误，请您核对！");
	jQuery.validator.addMethod("isMyEmail", function(value, element) { 
		return this.optional(element) || (/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/.test(value));    
	}, "邮箱输入格式有误，请您核对！");
	var validator = $("#sendmailform").validate({ 
		rules: {
			smtpserver: {
				required: true,
				isMyIP: true
			},
			smtpserverport: {
				required: true,
				isMyPort: true
			},
			authuser: {
				required: true,
				isMyEmail: true
			},
			authpwd: {
				required: true
			}
		},			   
		messages: {
			smtpserver: {
				required: "请输入SMTP服务器IP",
				isMyIP: "IP地址格式有误，请您核对！"
			},
			smtpserverport: {
				required: "请输入SMTP服务器端口",
				isMyPort: "端口输入范围有误，请您核对！"
			},
			authuser: {
				required: "请输入认证用户邮箱",
				isMyEmail: "邮箱输入格式有误，请您核对！"
			},
			authpwd: {
				required: "请输入认证密码"
			}
		}    
	});

	$("#save").click(function(){
		if(validator.form()){
			document.sendmailform.action = "/setting/updatesmtp";
			document.sendmailform.submit ();
		}
	});
});
</script>
<div id="content">
 <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">系统设置</a> 
	<a href="/setting/sendmail" class="current">报告发送配置</a> 
	</div>
  </div>
   <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>基本信息设置</h5>
          </div>
          <div class="widget-content nopadding">
			<form name="sendmailform" id="sendmailform" method="post">
            <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th>属性值</th>
                </tr>
              </thead>
			  <tbody>
			   <tr>
                  <td style="width:20%;">SMTP服务器</td>
                  <td>
				  <input style="width:33%;" type="text" name="smtpserver" id="smtpserver" value="{%$infos.smtpserver%}" />
				  </td>
                </tr>
				<tr>
                  <td style="width:20%;">SMTP服务器端口</td>
                  <td>
				  <input style="width:33%;" type="text" name="smtpserverport" id="smtpserverport" value="{%$infos.smtpserverport%}" />
				  <a style="float:right;margin-left:-10%"id="example" data-content="填写端口时务必慎重，避免与系统其他端口冲突！" data-placement="left" data-toggle="popover" class="btn btn-success" href="#" data-original-title="注意！"><i class="icon-info-sign"></i></a>
				  </td>
                </tr>
				<tr>
                  <td style="width:20%;">认证用户邮箱</td>
                  <td>
				  <input style="width:33%;" type="text" name="authuser" id="authuser" value="{%$infos.authuser%}" />
				  </td>
                </tr>
				<tr>
                  <td style="width:20%;">认证密码</td>
                  <td>
				  <input style="width:33%;" type="password" name="authpwd" id="authpwd" value="{%$infos.authpwd%}" />
				  </td>
                </tr>
              </tbody>
            </table>
			<input type="hidden" value="{%$infos.id%}" id="smtpid" name="smtpid" />
			<input type="hidden" value="{%$actiontype%}" name="actiontype" id="actiontype" />
			</form>
          </div>
        </div>
		<center><button class="btn" id="save">保存</button></center>
	  </div>	
	 </div>	
   </div>
</div>
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />
<!--Footer-part-->
{%include file="footer.php"%}
