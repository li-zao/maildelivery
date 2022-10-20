{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	// username 
	var username = $("#username").val();
	if (username != "") {
		$("#username").attr("readonly", true);
	}
	$("#save").click(function(){
		var pwd = $("#password").val();
		var pwd1 = $("#password1").val();
		if (pwd != pwd1) {
			art.dialog.alert("两次输入的密码不一致，请重新输入！");
			return false;
		}
		document.addadminform.action = "/setting/updatepwd";
		document.addadminform.submit ();
	});
});
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回主页" class="tip-bottom"><i class="icon-home"></i>主页</a> 
	<a href="/setting/accountmanage" class="tip-bottom" title="账户管理">账户管理</a> 
	<a href="/setting/admincreation" class="tip-bottom current" title="修改密码">修改密码</a> 
	</div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
	    <form name="addadminform" id="addadminform" method="post">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>修改密码</h5>
          </div>
          <div class="widget-content">
            <table class="table table-bordered table-striped with-check">
				<thead>
                <tr>
                  <th>属性</th>
                  <th>属性值</th>
                </tr>
              </thead>			 
			 <tbody>
                <tr>
                  <td style="width:20%;">用户名</td>
                  <td>
				  <input style="width:33%;" name="username" id="username" type="text" value="{%$item.username%}" />
				  </td>
                </tr>
                <tr>
                  <td style="width:20%;">密码</td>
                  <td>
				  <input style="width:33%;" name="password" id="password" type="password" value="**********" />
				  </td>
                </tr>
				 <tr>
                  <td style="width:20%;">确认密码</td>
                  <td><input style="width:33%;" name="password1" id="password1" type="password" value="**********" /></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
		<input type="hidden" value="{%$item.id%}" name="uid" id="uid" />
		<center><button class="btn btn-primary" id="save">保存</button></center>
		</form>
	  </div>
	</div>
  </div>
</div>
{%include file="footer.php"%}

