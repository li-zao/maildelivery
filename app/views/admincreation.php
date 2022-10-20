{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	var uid = $("#uid").val();
	if (uid == "" || uid == null) {
		$("#password, #password1").val("");
	}
	var is_self = $("#is-self").val();
	if (is_self == "yes") {
		$("input[name='lock']").attr("disabled", true);
		//$("input[name='usertype']").attr("disabled", true);
		$("input[type='checkbox']").attr("disabled", true);
		$("input[name='audit']").attr("disabled", true);
		//$("input[name='belong']").attr("disabled", true);
	}
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	var validator = $("#addadminform").validate({ 
		rules: {
			username: {    
				required: true
			},
			password: {
				required: true
			},
			password1: {
				required: true
			},
			mail: {
				required: true,
				email: true
			}
		},			   
		messages: {    
			username: {    
				required: "请输入用户名"
			},
			password: {
				required: "请输入密码"
			},
			password1: {
				required: "请输入密码"
			},
			mail: {
				required: "请输入您的邮箱",
				email: "输入邮箱格式有误，请您核对！"
			}
		}    
	});
	$("#save").click(function(){
		if(validator.form()){
			var pwd = $("#password").val();
			var pwd1 = $("#password1").val();
			if (pwd.length < 6 || pwd.length > 32){
				art.dialog.alert("密码长度只能为6-32位！");
				return false;
			}
			if (pwd != pwd1) {
				art.dialog.alert("两次输入的密码不一致，请重新输入！");
				return false;
			}
			var uid = $("#uid").val();
			if (uid == "" || uid == null) {
				//判断重复用户名
				var url = "/setting/checkuname";
				var result = $.ajax({
					type: 'POST',
					url: url,
					data: {username: $("#username").val()},
					async: false
				}).responseText;
				if (result != "pass") {
					art.dialog.alert(result);
					return false;
				}
				//判断重复邮箱
				var url = "/setting/checkmail";
				var result2 = $.ajax({
					type: 'POST',
					url: url,
					data: {mail: $("#mail").val()},
					async: false
				}).responseText;
				if (result2 != "pass") {
					art.dialog.alert(result2);
					return false;
				}			
			}
			
			var trustip = $("#trustip").val();
			if (trustip != "") {
				if (!checkIP (trustip)) {
					art.dialog.alert("IP地址有误，请重新输入！");
					return false;
				}
			}
			//创建所属
			/*var usertype = $("input[name='usertype']:checked").val();
			var belongs = $("input[name='belongs']:checked").val();
			if ( usertype == 'task'){
				if (belongs == "" || belongs == null) {
					art.dialog.alert("必须选定创建所属人！");
					return false;
				}
				belongs = $("#belongs").val();
			}*/
			//document.addadminform.action = "/setting/addadmin";
			//document.addadminform.submit ();
		}
	});
	
	/* var usertype = $("input[name='usertype']:checked").val();
	if (usertype == "stask" || usertype == "task") {
		$("#manager-access").hide();
	} else {
		$("#manager-access").show();
	} */
	// audit
	var role = $("#role").val();
	var editrole = $("#editrole").val();
	if (role == "sadmin" || role == "admin") {
		$("#audit").hide();
		//$("#belong").hide();
	} else if( role == "stasker"){
		$("#audit").show();
		//$("#belong").hide();
	} else if( role == "tasker"){
		$("#audit").show();
		//$("#belong").show();
	} 
	if ( editrole == "sadmin" || editrole == "admin") {
		$("#audit").hide();
		//$("#belong").hide();
	} else if( editrole == "stasker" ) {
		$("#audit").show();
		//$("#belong").hide();
	}else if( editrole == "tasker" ) {
		$("#audit").show();
		//$("#belong").show();
	}
	
	// username 
	var username = $("#username").val();
	if (username != "") {
		$("#username").attr("readonly", true);
	}
	/* $("input[name='usertype']").click(function(){
		if ($(this).val() == "sys") {
			$("#manager-access").show();
			$("#audit").hide();
			//$("#belong").hide();
		} else if($(this).val() == "stask"){
			$("#manager-access").hide();
			$("#audit").show();
			//$("#belong").hide();
		}else {
			$("#manager-access").hide();
			$("#audit").show();
			//$("#belong").show();
		}
	}); */
});	

function checkIP(IP) {	
	if ( IP.indexOf( "\r\n" ) >= 0 ) {				
		var addrs = IP.split("\r\n");
	} else if ( IP.indexOf( "\n" ) >= 0 ) {
		var addrs = IP.split("\n");
	} else {
		var addrs = IP.split("\r");
	}
	var ip_patt = /^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/;
	if (addrs.length > 0) {
	    for (var i = 0; i < addrs.length; i++) {
	        if (addrs[i] == "") {
	            continue;
	        }
	        if (!ip_patt.test(addrs[i])) {
	            return false;
	        }
	    }
	}
	return true;
}
function selectAll(checked) {
	var t = document.addadminform.elements;
	for(var i=0;i<t.length;i++){  
		if(t[i].type == "checkbox" && t[i].name != "selectall"){  
			t[i].checked = checked;
		}
	}
}

</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回主页" class="tip-bottom"><i class="icon-home"></i>主页</a> 
	{%if $access->getAdminCreationAccess() and $role != 'tasker' %}
	<a href="/setting/accountmanage" class="tip-bottom" title="账户管理">账户管理</a> 
		{%if $editaccount == 'yes' %}
		<a href="#" class="tip-bottom" title="个人信息">个人信息</a> 
		{%else%}
		<a href="/setting/admincreation" class="tip-bottom current" title="账号管理">新增账号</a> 
		{%/if%}
	{%else%}
	<a href="#" class="tip-bottom" title="个人信息">个人信息</a> 
	{%/if%}
	</div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
	    <form name="addadminform" id="addadminform" method="post" action="/setting/addadmin">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>账号信息</h5>
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
                <!--<tr style="display:none">
                  <td style="width:20%;">用户类型</td>
                  <td>
				  {%if $is_sadmin %}
				  <input name="usertype" {%if $item.role == '' || $item.role == 'sadmin'%}checked{%/if%} type="radio" value="sys" />&nbsp;系统管理员&nbsp;&nbsp;&nbsp;
				  {%elseif $role == "stasker"%}
				  <input name="usertype" {%if $item.role == '' || $item.role == 'stasker'%}checked{%/if%} type="radio" value="task" />&nbsp;二级任务发布员
				  {%/if%} 
				  </td>
                </tr>
				<tr id="belong">
                  <td style="width:20%;">创建所属</td>
                  <td style="padding: 8px 400px 8px 8px;">
				  {%section name=info loop=$infos%}
				  <span><input type="radio" name="belongs" id="belongs" {%if $infos[info]['username'] == $item.belongs %}checked{%/if%} value="{%$infos[info]['username']%}" />&nbsp;{%$infos[info]['username']%}&nbsp;&nbsp;&nbsp;</span>
				  {%/section%}
				  </td>
                </tr>-->
				<tr id="audit">
                  <td style="width:20%;">是否审批</td>
                  <td>
				  <input name="audit" {%if $item.audit == '1' %}checked{%/if%} type="radio" value="1" />审批&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input name="audit" {%if $item.audit != '1' %}checked{%/if%} type="radio" value="0" />不审批
				  <a style="float: right; margin-left: -10%; padding: 0px 10px; height: 18px;" id="example" data-content="当发布员发布任务时，是否需要审批" data-placement="left" data-toggle="popover" class="btn btn-success" href="#" data-original-title="小贴士"><i class="icon-info-sign"></i></a> 
				  </td>
                </tr>
				<tr>
                  <td style="width:20%;">使用状态</td>
                  <td>
				  <input name="lock" {%if $item.lock != "0"%}checked{%/if%} type="radio" value="1" />启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input name="lock" {%if $item.lock == "0"%}checked{%/if%} type="radio" value="0" />禁用	 
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
				<tr>
                  <td style="width:20%;">邮箱</td>
                  <td><input style="width:33%;" name="mail"  id="mail" type="text" value="{%$item.mail%}" /></td>
                </tr>
				<tr>
                  <td style="width:20%;">信任IP</td>
                  <td>
				  <textarea style="width:45%;" id="trustip" name="trustip" rows="7">{%$item.trustip%}</textarea>
				  <a style="float: right; margin-left: -10%; padding: 0px 10px; height: 18px;"id="example" data-content="每一个IP请用“Enter”键分隔！" data-placement="left" data-toggle="popover" class="btn btn-success" href="#" data-original-title="小贴士"><i class="icon-info-sign"></i></a> 
				  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
		{%if $is_sadmin %}
		<div class="widget-box" id="manager-access">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>权限管理</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped with-check">
			 <tbody>
                <tr>
                  <td style="width:13%;">&nbsp;&nbsp;&nbsp;选择模块&nbsp;&nbsp;<br/>&nbsp;&nbsp;（<input type="checkbox" name="checkall" id="checkall" onclick="selectAll(this.checked)" />&nbsp;全选）</td>
                  <td>
					<fieldset>
					  <legend>首页</legend>
					  <span><input type="checkbox" name="firstpage" {%if $item.access[0] == 1%}checked{%/if%}/>&nbsp;首页</span> 
					</fieldset>
					<fieldset>
					  <legend>系统监控</legend>
					  <span><input type="checkbox" name="systemmonitor" {%if $item.access[2] == 1%}checked{%/if%}/>&nbsp;系统状态监控</span>
					  <span><input type="checkbox" name="mailstatistics" {%if $item.access[3] == 1%}checked{%/if%}/>&nbsp;24小时邮件数量</span> 
					  <span><input type="checkbox" name="mailqueue" {%if $item.access[4] == 1%}checked{%/if%}/>&nbsp;正常邮件队列</span> 
					  <span><input type="checkbox" name="searchlogs" {%if $item.access[5] == 1%}checked{%/if%}/>&nbsp;日志查询</span> 
					  <span><input type="checkbox" name="denyaccess" {%if $item.access[6] == 1%}checked{%/if%}/>&nbsp;连接日志</span> 
					</fieldset>
					<fieldset>
					  <legend>系统设置</legend>
					  <span><input type="checkbox" name="consolesetting" {%if $item.access[8] == 1%}checked{%/if%}/>&nbsp;管理界面配置</span> 
					  <span><input type="checkbox" name="sendmail" {%if $item.access[9] == 1%}checked{%/if%}/>&nbsp;报告发送配置</span> 
					  <span><input type="checkbox" name="alertsetting" {%if $item.access[10] == 1%}checked{%/if%}/>&nbsp;系统告警配置</span>  
					  <span><input type="checkbox" name="sysclocksetting" {%if $item.access[11] == 1%}checked{%/if%}/>&nbsp;时间配置</span>
					  <span><input type="checkbox" name="workingreport" {%if $item.access[12] == 1%}checked{%/if%}/>&nbsp;运行情况报告</span>
					  <span><input type="checkbox" name="license" {%if $item.access[13] == 1%}checked{%/if%}/>&nbsp;授权管理</span>
					  <span><input type="checkbox" name="resetsetting" {%if $item.access[14] == 1%}checked{%/if%}/>&nbsp;系统维护</span>
					  <span><input type="checkbox" name="publishedinfo" {%if $item.access[15] == 1%}checked{%/if%}/>&nbsp;信息公告</span>
					</fieldset>
					<fieldset>
					  <legend>网络设置</legend>
					  <span><input type="checkbox" name="networksetting" {%if $item.access[17] == 1%}checked{%/if%}/>&nbsp;网卡配置</span> 
					  <span><input type="checkbox" name="networktool" {%if $item.access[18] == 1%}checked{%/if%}/>&nbsp;网络工具</span> 
					  <span><input type="checkbox" name="snmpconfiguration" {%if $item.access[19] == 1%}checked{%/if%}/>&nbsp;SNMP管理</span> 
					</fieldset>
					<fieldset>
					  <legend>参数设置</legend>
					  <span><input type="checkbox" name="securityparam" {%if $item.access[21] == 1%}checked{%/if%}/>&nbsp;投递参数管理</span> 
					  <span><input type="checkbox" name="singledomain" {%if $item.access[22] == 1%}checked{%/if%}/>&nbsp;单域参数设置</span> 
					  <span><input type="checkbox" name="trustiptable" {%if $item.access[23] == 1%}checked{%/if%}/>&nbsp;信任来源地址</span> 
					  <span><input type="checkbox" name="staticmx" {%if $item.access[24] == 1%}checked{%/if%}/>&nbsp;静态中继路由</span> 
					  <span><input type="checkbox" name="userintercept" {%if $item.access[25] == 1%}checked{%/if%}/>&nbsp;拦截用户列表</span>
					</fieldset>
					<fieldset>
					  <legend>联系人管理</legend>
					  <span><input type="checkbox" name="personlist" {%if $item.access[27] == 1%}checked{%/if%}/>&nbsp;联系人库</span> 
					  <input type="hidden" name="expansion" value="0"/><!--&nbsp;自定义属性-->
					  <span><input type="checkbox" name="contactlist" {%if $item.access[29] == 1%}checked{%/if%}/>&nbsp;联系人分组</span> 
					  <input type="hidden" name="filter" value="0"/><!--&nbsp;联系人筛选-->
					  <input type="hidden" name="formlist" value="0"/><!--&nbsp;订阅管理-->
					</fieldset>
					<fieldset>
					  <legend>邮件内容管理</legend>
					  <input type="hidden" name="createtempl" value="0"/><!--&nbsp;创建模板-->
					  <input type="hidden" name="mytempl" value="0" /><!--&nbsp;我的模板-->
					  <span><input type="checkbox" name="preset" {%if $item.access[35] == 1%}checked{%/if%}/>&nbsp;预设模板</span> 
					  <span><input type="checkbox" name="mgattach" {%if $item.access[36] == 1%}checked{%/if%}/>&nbsp;附件管理</span> 
					  <span><input type="checkbox" name="imgattach" {%if $item.access[37] == 1%}checked{%/if%}/>&nbsp;图片管理</span> 
					</fieldset>
					<fieldset>
					  <legend>投递任务管理</legend>
					  <input type="hidden" name="create" value="0" /><!--&nbsp;创建任务向导-->
					  <input type="hidden" name="addtask" value="0" /><!--&nbsp;创建任务-->
					  <input type="hidden" name="drafttask" value="0" /><!--&nbsp;任务草稿箱-->
					  <span><input type="checkbox" name="listtask" {%if $item.access[42] == 1%}checked{%/if%}/>&nbsp;任务列表</span> 
					  <span><input type="checkbox" name="typetask" {%if $item.access[43] == 1%}checked{%/if%}/>&nbsp;任务分类</span>
					</fieldset>
					<fieldset>
					  <legend>统计分析</legend>
					  <span><input type="checkbox" name="singletask" {%if $item.access[45] == 1%}checked{%/if%}/>&nbsp;按单次任务统计</span> 
					  <span><input type="checkbox" name="taskclassification" {%if $item.access[46] == 1%}checked{%/if%}/>&nbsp;按任务分类统计</span> 
					  <span><input type="checkbox" name="releaseperson" {%if $item.access[47] == 1%}checked{%/if%}/>&nbsp;按发布人员统计</span> 
					  <span><input type="checkbox" name="alltaskstatistics" {%if $item.access[48] == 1%}checked{%/if%}/>&nbsp;按全部任务统计</span>
					  <span><input type="checkbox" name="allforwardstatistics" {%if $item.access[49] == 1%}checked{%/if%}/>&nbsp;按全部转发统计</span> 
					</fieldset>
					<fieldset>
					   <legend>账号管理</legend>
					  <span><input type="checkbox" name="accountmanage" {%if $item.access[51] == 1%}checked{%/if%}/>&nbsp;账号管理</span> 
					</fieldset>
				  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
		{%/if%}
		<input type="hidden" value="{%$item.id%}" name="uid" id="uid" />
		<input type="hidden" value="{%$item.role%}" name="editrole" id="editrole" />
		<input type="hidden" value="{%$is_self%}" name="is-self" id="is-self" />
		<input type="hidden" value="{%$role%}" name="role" id="role" />
		<center><button type="button" class="btn" id="save">保存</button></center>
		</form>
	  </div>
	</div>
  </div>
</div>
{%include file="footer.php"%}

