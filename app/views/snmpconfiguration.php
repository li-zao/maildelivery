{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	var actiontype = $("#actiontype").val();
	if (actiontype == "enable") {
		$.gritter.add({
			title:	'启用SNMP服务',
			text:	'您已成功启用SNMP服务',
			sticky: false
		});	
	} else if (actiontype == "disable") {
		$.gritter.add({
			title:	'禁用SNMP服务',
			text:	'您已成功禁用SNMP服务',
			sticky: false
		});	
	}
	
	var ifuse = $("#ifuse").val();
	var version = $("#version").val();
	if (ifuse == "enable") {
		$(".versions").show();
		if (version == "2") {
			$(".v3").hide();
		} else if (version == "3" || version == "") {
			$(".v2").hide();
		}
	} else if (ifuse == "disable" || ifuse == "") {
		$(".ifuse").hide();
	}
	
	$("input[name='snmpserver']").click(function(){
		if ($(this).val() == 'enable') {
			$(".versions").show();
			if (version == "2") {
				$(".v3").hide();
				$(".v2").fadeIn();
			} else if (version == "3" || version == "") {
				$(".v2").hide();
				$(".v3").fadeIn();
			}
		} else if ($(this).val() == 'disable' || ifuse == "") {
			$(".ifuse").fadeOut ("slow");
		}
	});
	
	$("input[name='versions']").click(function(){
		if ($(this).val() == '2') {
			version = 2;
			$(".v3").hide();
			$(".v2").fadeIn();
		} else if ($(this).val() == '3' || ifuse == "") {
			version = 3;
			$(".v2").hide();
			$(".v3").fadeIn();
		}
	});
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	var validator = $("#snmp").validate({ 
		rules: {
			connstr: {
				required: true
			},
			iparea: {
				required: true
			},
			username: {
				required: true
			},
			pwd: {
				required: true
			}
		},			   
		messages: {
			connstr: {
				required: "请输入SNMP连接字符串"
			},
			iparea: {
				required: "请输入SNMP管理IP区间"
			},
			username: {
				required: "请输入用户名"
			},
			pwd: {
				required: "请输入密码"
			}
		}    
	});
	$("#save").click(function(){
		if(validator.form()){
			document.snmp.action='/setting/addsnmp';   
			document.snmp.submit();
		}
	});
	
});
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">网络设置</a> 
	<a href="/setting/snmpconfiguration" class="current">SNMP管理</a> 
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
		   <form name="snmp" id="snmp" method="post">            
		   <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th>属性值</th>
                </tr>
              </thead>
			  <tbody>
                <tr>
                  <td style="width:20%;">SNMP服务</td>
                  <td>
				  <input name="snmpserver" type="radio" {%if $ifuse == "enable"%}checked{%/if%} value="enable" style="vertical-align:top" />启用&nbsp;&nbsp;&nbsp;
				  <input name="snmpserver" type="radio" {%if $ifuse != "enable"%}checked{%/if%} value="disable" style="vertical-align:top" />禁用
				  </td>
                </tr>
                <tr class="ifuse versions">
                  <td style="width:20%;">SNMP版本</td>
                  <td> 
				  <input name="versions" type="radio" value="2" {%if $version == "2"%}checked{%/if%} style="vertical-align:top" />V2.0&nbsp;&nbsp;&nbsp;
				  <input name="versions" type="radio" value="3" {%if $version != "2"%}checked{%/if%} style="vertical-align:top" />V3.0
				  </td>
                </tr>
                <tr class="ifuse v2">
                  <td style="width:20%;">SNMP连接字符串</td>
                  <td>
				  <input style="width:33%;" id="connstr" name="connstr" type="text" value="{%$connection_str%}" />
				  </td>
                </tr>
				 <tr class="ifuse v2">
                  <td style="width:20%;">SNMP管理IP区间</td>
                  <td><input style="width:33%;" id="iparea" name="iparea" type="text" value="{%$ip_area%}" /></td>
                </tr>
				<tr class="ifuse v3">
                  <td style="width:20%;">用户</td>
                  <td><input style="width:33%;" id="username" name="username" type="text" value="{%$snmpuser%}" /></td>
                </tr>
				<tr class="ifuse v3">
                  <td style="width:20%;">密码</td>
                  <td><input style="width:33%;" id="pwd" name="pwd" type="password" value="{%$password%}" /></td>
                </tr>
				<tr class="ifuse v3">
                  <td style="width:20%;">认证方式</td>
                  <td>
				  <select id="authtype" name="authtype" style="width: 201px;">
							<option value="MD5" {%if $auth_type == "MD5"%}checked{%/if%} >MD5</option>
							<option value="SHA" {%if $auth_type == "SHA"%}checked{%/if%} >SHA</option>
						</select>
				  </td>
                </tr>
				<tr class="ifuse v3">
                  <td style="width:20%;">加密方式</td>
                  <td>
				  <select id="encryption" name="encryption" style="width: 201px;">
							<option value="DES" {%if $encryption == "DES"%}checked{%/if%} >DES</option>
							<option value="AES" {%if $encryption == "AES"%}checked{%/if%} >AES</option>
						</select>
				  </td>
                </tr>
              </tbody>
            </table>
			<input type="hidden" value="{%$ifuse%}" id="ifuse" name="ifuse" />
			<input type="hidden" value="{%$version%}" id="version" name="version" />
			<input type="hidden" value="{%$actiontype%}" id="actiontype" name="actiontype" />
			</form>
          </div>
        </div>
		<center><button class="btn btn-primary" id="save">保存</button></center>
	  </div>	
	 </div>	
   </div>
</div>
{%include file="footer.php"%}
