<!DOCTYPE html>
<html lang="en">
<head>
    <title>MailData电子邮件分发投递系统</title><meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">	
<!--[if lte IE 8]>    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> <![endif]--> 
    <link rel="stylesheet" href="/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/dist/css/matrix-login.css" />
	<link rel="stylesheet" href="/dist/css/jquery.gritter.css" />
    <link href="/dist/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="/dist/img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
</head>
    <body>
	  <div id="loginmaxbox">
		<div id="loginboxup"><img src="/dist/img/og.jpg" alt="Logo" /></div>
        <div id="loginbox">  
            <form id="loginform" class="form-vertical" method="POST" action="/index/login/">
            <div class="loginboxdown">
                <img src="/dist/img/og_str.jpg" alt="Logo" />
            </div>
				 <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lg" ><i class="icon-user" style="padding-left: 11px;"></i></span><input tabindex="1" style="font-size:12px" type="text" name="Username" placeholder="用户名" value="{%$username|escape:'html'%}"/>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ly"><i class="icon-lock"  style="padding-left: 11px;"></i></span><input tabindex="2" style="font-size:12px" type="password" name="Password" placeholder="密码" autocomplete="off" value="{%$password|escape:'html'%}"/>
                        </div>
                    </div>
                </div>
				<div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lr"><i class="icon-picture"  style="padding-left: 10px;"></i></span><input tabindex="3" style="width:47%;font-size:12px" type="text" id="captcha" name="captcha" placeholder="验证码" /><img style="vertical-align:top;" id="iimg" name="iimg" title="{%$Lang.CLICK_CHANGE_IMG%}" onclick="javascript:void(changecaptch())" src="/index/captcha"></img>
					   </div>
                    </div>
                </div>
                <div class="form-actions">
                    <span class="pull-left" style="padding-left: 55px;"><a href="javascript:void(0);" id="to-recover" style="font-size:12px;">忘记密码？</a></span>
                    <span style="display: inline;margin-right: auto;margin-left: auto;">
					<!-- <a href="javascript:void(0);" class="flip-link " onclick="changeLang('zh')" style="color:#78787c;font-size:12px">简体中文</a>&nbsp;<span style="color:#78787c">|</span> -->
					<!-- <a href="javascript:void(0);" class="flip-link " onclick="changeLang('en')" style="color:#78787c;font-size:12px">English</a> -->
					</span>
                    <span class="pull-right" style="padding-right: 48px;"><a href="javascript:void(0);" tabindex="4" style="font-size:12px;" onclick="document.getElementById('loginform').submit();">登录</a></span>
                </div>
				<div class="footer"><a href="http://www.maildata.cn" target="_blank">北京朗阁信息技术有限公司</a>&nbsp;|&nbsp;<span class="gray">©2010 - 2020 LongGer Inc. All Rights Reserved.</span></div>
			</form>
            <form id="recoverform" action="#" class="form-vertical">
			<div class="loginboxdown"><img src="/dist/img/og_str.jpg" alt="Logo" /></div>
				<p class="normal_text" style="background:#000">请输入您的用户名及邮箱地址，以接收新密码！</p>
					 <div class="control-group">
					<div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_og1" style="background:#c5111a"><i class="icon-user" style="padding-left: 11px;"></i></span><input style="font-size:12px;" type="text" placeholder="用户名" name="username1" id="username1" />
                        </div>
                    </div>
					 </div>
				 <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_og1" style="background:#c5111a"><i class="icon-envelope" style="padding-left: 11px;"></i></span><input style="font-size:12px;" type="text" placeholder="邮箱地址" name="mailbox" id="mailbox" />
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <span class="pull-left" style="padding-left: 55px;"><a href="javascript:void(0);" id="to-login" style="font-size:12px;">&laquo;返回登录</a></span>
                    <span class="pull-right" style="padding-right: 48px;"><a class="" style="font-size:12px;" href="javascript:void(0);" onclick="javascript:void(retrievepwd())" />发送邮件</a></span>
                </div>
            </form>
        </div>
	  </div>	
        <input type="hidden" name="error" id="error" value="{%$error%}">
        <script src="/dist/js/jquery.min.js"></script>  
        <script src="/dist/js/matrix.login.js"></script> 
		<script src="/dist/js/jquery.gritter.min.js"></script>
		<script type="text/javascript" src="/dist/js/jquery.artDialog.source.js?skin=black"></script>
		<script type="text/javascript">
		$(function(){
			var uname = $("input[name='Username']").val();
			var pwd   = $("input[name='Password']").val();
			if (uname != "" && pwd != "") {
				$("input[name='captcha']").focus();
			} else {
				$("input[name='Username']").focus();
			}
		})
		var SubmitOrHidden = function(evt){
			evt = window.event || evt;
			if(evt.keyCode == 13){//如果取到的键值是回车
				  document.getElementById('loginform').submit();
			 }			 
		}
		window.document.onkeydown=SubmitOrHidden;//当有键按下时执行函数
		$(function(){
			var error = $("#error").val();
			if (error != "") {
				$.gritter.add({
				title:	'{%$Lang.PROMPTINFO%}',
				text:	error,
				sticky: false
		});	
			}
		});
		function retrievepwd () {
			var uname = $("#username1").val();
			var mailbox = $("#mailbox").val();
			if (uname == "" || mailbox == "") {
				$("#gritter-notice-wrapper").remove();
				$.gritter.add({
					title:	'{%$Lang.PROMPTINFO%}',
					text:	'{%$Lang.ERROR_WARNING%}',
					sticky: false
				});	
			}else{
				var patt = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
				if (!patt.test(mailbox)) {
					$("#gritter-notice-wrapper").remove();
					$.gritter.add({
						title:	'{%$Lang.PROMPTINFO%}',
						text:	'{%$Lang.ISEMAIL%}',
						sticky: false
					});	
				} else {
					var url = "/index/retrievepwd";
					$.ajax({
						type: 'POST',
						url: url,
						timeout: 5000,
						data: {mailbox: mailbox, username: uname,'longger':$("input[name='longger']").val()},
						success:function(data){ //请求成功的回调函数
					　　　	var result = data;
						  	$("#gritter-notice-wrapper").remove();
							$.gritter.add({
								title:	'{%$Lang.PROMPTINFO%}',
								text:	 result,
								sticky: false
							});	
					　　},
					   error:function(data){ //请求成功的回调函数
					　　　 var result = '{%$Lang.REQUEST_TIME_LIMIT%}';
						  $("#gritter-notice-wrapper").remove();
						  $.gritter.add({
								title:	'{%$Lang.PROMPTINFO%}',
								text:	 result,
								sticky: false
						  });	
					　 }
					});
				}
			}
		}
		function changecaptch () {
			var target = document.getElementById('iimg');
			if (target != null) {
				target.setAttribute('src',"/index/captcha?"+Math.random());
			}
		}
        function changeLang(language) {
			var orgin = window.location.href;
			if (language == "zh") {
				if (orgin.indexOf("lang=zh") != -1) { 
					return;
				} else if (orgin.indexOf("lang=") != -1) {
					window.location.href = orgin.replace("lang=en", "lang=zh");
				} else {
					if (orgin.indexOf("?") != -1) {
						window.location.href = orgin + "&lang=" + language;
					} else {
						window.location.href = orgin + "?lang=" + language;
					}
				}
			} else if (language == "en") {
				if (orgin.indexOf("lang=en") != -1) { 
					return;
				} else if (orgin.indexOf("lang=") != -1) {
					window.location.href = orgin.replace("lang=zh", "lang=en");
				} else {
					if (orgin.indexOf("?") != -1) {
						window.location.href = orgin + "&lang=" + language;
					} else {
						window.location.href = orgin + "?lang=" + language;
					}
				}
			}
		}
		</script>
    </body>

</html>
