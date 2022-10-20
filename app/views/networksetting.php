{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	var card = $("#cardval").val();
	if (card == 'eth0') {
		$(".eth0").fadeIn();
		$(".eth1").hide();
	} else {
		$(".eth1").fadeIn();
		$(".eth0").hide();
	}
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	jQuery.validator.addMethod("isMyIP", function(value, element) { 
		return this.optional(element) || (/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/.test(value));    
	}, "IP地址格式有误，请您核对！");
	var validator = $("#networkform").validate({ 
		rules: {
			eth0ip: {required: true, isMyIP: true},
			eth0mask: {required: true, isMyIP: true},
			eth0gw: {required: true, isMyIP: true},
			firstdns: {required: true, isMyIP: true},
			seconddns: {required: false, isMyIP: true},
			eth1ip: {required: true, isMyIP: true},
			eth1mask: {required: true, isMyIP: true},
			eth1gw: {required: true, isMyIP: true}
		},			   
		messages: {    
			eth0ip: {required: "请输入IP地址", isMyIP: "IP地址格式有误，请您核对！"},
			eth0mask: {required: "请输入子网掩码", isMyIP: "IP地址格式有误，请您核对！"},
			eth0gw: {required: "请输入网关", isMyIP: "IP地址格式有误，请您核对！"},
			firstdns: {required: "请输入首选DNS服务器", isMyIP: "IP地址格式有误，请您核对！"},
			seconddns: {required: "", isMyIP: "IP地址格式有误，请您核对！"},
			eth1ip: {required: "请输入IP地址", isMyIP: "IP地址格式有误，请您核对！"},
			eth1mask: {required: "请输入子网掩码", isMyIP: "IP地址格式有误，请您核对！"},
			eth1gw: {required: "请输入网关", isMyIP: "IP地址格式有误，请您核对！"}
		}   
	});
	$("#save").click(function(){
		if (validator.form()) {
			var eth1vip = $("#eth1vip").val();
			if (eth1vip != "" && eth1vip != "undefined") {
				if (!checkIP (eth1vip)) {
					art.dialog.alert("IP地址池中IP输入有误，请重新输入！");
					return false;
				}
			}
			art.dialog({
				lock: true,
				background: 'black', 
				opacity: 0.87,	
				content: '您将要修改网络配置，是否继续？？',
				icon: 'error',
				ok: function () {
					confignetwork();
				},
				cancel: true
			});
		}
	});
	
	$("input[name='usevip']").click(function(){
		if ($(this).val() == '1') {
			$("#eth1vip").attr("disabled", false);
		} else {
			$("#eth1vip").attr("disabled", true);
		}
	});
	var uservipview = $("#uservipview").val();
	if (uservipview == '1') {
		$("#eth1vip").attr("disabled", false);
	} else {
		$("#eth1vip").attr("disabled", true);
	}
	
});
function confignetwork () {
	var updateurl = "/setting/updatenetworksetting"; 
	$.post(updateurl, $("#networkform").serialize());
	$("#mask").css("height",$(document).height());  
    $("#mask").css("width",$(document).width());  
    $("#mask").show(); 
	setTimeout(function(){
		window.location.href = "/setting/networksetting";
	}, 12000);
}
function checkIP(ip) {
	if ( ip.indexOf( "\r\n" ) >= 0 ) {	
		var addrs = ip.split("\r\n");
	} else if ( ip.indexOf( "\n" ) >= 0 ) {
		var addrs = ip.split("\n");
	} else {
		var addrs = ip.split("\r");
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
function witchcardfunc (card) {
	if (card == 'eth1') {
		$(".eth1").show();
		$(".eth0").hide();
	} else {
		$(".eth0").show();
		$(".eth1").hide();
	}
}

</script>
<div id="mask" class="mask" style="display:none;">
	<div class="loadp">
	<font size="5">正在配置,请稍后... ...</font><div class="loading"></div>
	</div>
</div> 
<div id="content">
 <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">网络设置</a> 
	<a href="/setting/networksetting" class="current">网卡配置</a> 
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
			<form name="networkform" id="networkform" method="post">
            <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th>属性值</th>
                </tr>
              </thead>
			  <tbody>
			   <tr>
                  <td style="width:20%;">选择网卡</td>
                  <td>
				  <select name="witchcard" style="width:34%" onchange="javascript:void(witchcardfunc(this.value))">
				  <option value="eth1" {%if $witchcard != 'eth0'%}selected{%/if%}>eth1</option>
				  <option value="eth0" {%if $witchcard == 'eth0'%}selected{%/if%}>eth0</option>
				  </select>
				  </td>
                </tr>
				<!--eth0 configurtion-->
                <tr class="eth0">
                  <td style="width:20%;">IP地址（eth0）</td>
                  <td>
				  <input style="width:33%;" type="text" value="{%$eth0addr.ip%}" name="eth0ip" />
				  </td>
                </tr>
                <tr class="eth0">
                  <td style="width:20%;">子网掩码（eth0）</td>
                  <td><input style="width:33%;" type="text" value="{%$eth0addr.mask%}" name="eth0mask" /></td>
                </tr>
                
				<!--eth1 configurtion-->
				 <tr class="eth1">
                  <td style="width:20%;">IP地址（eth1）</td>
                  <td>
				  <input style="width:33%;" type="text" value="{%$eth1addr.ip%}" name="eth1ip" />
				  </td>
                </tr>
                <tr class="eth1">
                  <td style="width:20%;">子网掩码（eth1）</td>
                  <td><input style="width:33%;" type="text" value="{%$eth1addr.mask%}" name="eth1mask" /></td>
                </tr>
                <tr class="eth1">
                  <td style="width:20%;">默认网关（eth1）</td>
                  <td>
				  <input style="width:33%;" type="text" value="{%$eth1gw%}" name="eth1gw" />
				  </td>
                </tr>				
				 <tr class="">
                  <td style="width:20%;">首选DNS服务器</td>
                  <td><input style="width:33%;" type="text" value="{%$dns[0]%}" name="firstdns" /></td>
                </tr>
				<tr class="">
                  <td style="width:20%;">备用DNS服务器</td>
                  <td><input style="width:33%;" type="text" value="{%$dns[1]%}" name="seconddns" /></td>
                </tr>
				<tr class="eth1">
                  <td style="width:20%;">启用IP地址池</td>
                  <td>
				  <input name="usevip" type="radio" {%if $uservipview == "1"%}checked{%/if%} value="1" style="vertical-align:top" />启用&nbsp;&nbsp;&nbsp;
				  <input name="usevip" type="radio" {%if $uservipview != "1"%}checked{%/if%} value="0" style="vertical-align:top" />禁用
				  </td>
                </tr>
				<tr class="eth1">
                  <td style="width:20%;">IP地址池</td>
                  <td>
				  <textarea style="width:45%;" name="eth1vip" id="eth1vip" rows="7">{%$eth1vip%}</textarea>
				  <a style="float:right;margin-left:-10%"id="example3" data-content="多个IP请用“Enter”键分隔" data-placement="left" data-toggle="popover" class="btn btn-success" href="#" data-original-title="小贴士"><i class="icon-info-sign"></i></a> 
				  </td>
                </tr>
              </tbody>
            </table>
			<input type="hidden" value="{%$uservipview%}" id="uservipview" name="uservipview" />
			<input type="hidden" value="{%$witchcard%}" id="cardval" name="cardval" />
			</form>
          </div>
        </div>
		
		<center><button class="btn btn-primary" id="save">保存</button></center>
	  </div>	
	 </div>	
   </div>
</div>
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />
<!--Footer-part-->
{%include file="footer.php"%}
