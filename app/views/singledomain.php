{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	jQuery.validator.addMethod("isUrl", function(value, element) {    
		return this.optional(element) || (/\w*\.\w+$/.test(value));    
	}, "输入无效");
	jQuery.validator.addMethod("isMyIP", function(value, element) { 
		return this.optional(element) || (/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/.test(value));    
	}, "输入服务器地址格式错误");
	jQuery.validator.addMethod("isDomain", function(value, element) { 
		return this.optional(element) || (value == "全局") || (value == "*") || (/\w*\.\w+$/.test(value));    
	}, "输入域名格式有误，请重新输入！");		
	var validator = $("#authform").validate({ 
		rules: {
			domain: {    
			    required: true,
				isDomain: true
			},
			timeout: {    
			    required: true,
				pnumber: true
			},
			reconnect: {    
			    required: true,
				pnumber: true
			},
			sipmaximum: {    
			    required: true,
				pnumber: true
			},
			maxusersinbatch: {    
			    required: true,
				pnumber: true
			},
			smailsize: {    
			    required: true,
				pnumber: true
			},
			smailattchsize: {    
			    required: true,
				pnumber: true
			}
		},			   
		messages: {    
			domain: {    
			    required: "请输入域名",
				isDomain: "域名格式输入有误，请重新输入"
			},
			timeout: {    
			    required: "请输入连接超时设置",
				pnumber: "输入内容有误，请重新输入"
			},
			reconnect: {    
			    required: "请输入最大重试次数",
				pnumber: "输入内容有误，请重新输入"
			},
			sipmaximum: {    
			    required: "请输入单IP最大投递量",
				pnumber: "输入内容有误，请重新输入"
			},
			maxusersinbatch: {    
			    required: "请输入单次投递最大用户数",
				pnumber: "输入内容有误，请重新输入"
			},
			smailsize: {    
			    required: "请输入单封邮件大小",
				pnumber: "输入内容有误，请重新输入"
			},
			smailattchsize: {    
			    required: "请输入单封邮件附件大小",
				pnumber: "输入内容有误，请重新输入"
			}
		}    
	});
	$("#save").click(function() {		
		if(validator.form()){
			var unusedip = $("#unavailableip").val();
			if ( unusedip != "" && unusedip != "undefined" ) {
				if (!checkIP (unusedip)) {
					art.dialog.alert("IP地址有误，请重新输入！");
					return false;
				}
			}
			var rtid = $("#rtid").val();
			var url = "/setting/checksingledomain";
			if ( rtid != "" && rtid != null ) {
				result = $.ajax({
					type: 'POST',
					url: url,
					data: {domain: $("#domainid").val(),rtid: rtid},
					async: false
				}).responseText;
				if (result != "pass") {
					art.dialog.alert("该域名已存在，请您核对！");
					return false;
				}
			} else {
				result = $.ajax({
					type: 'POST',
					url: url,
					data: {domain: $("#domainid").val()},
					async: false
				}).responseText;
				if (result != "pass") {
					art.dialog.alert("该域名已存在，请您核对！");
					return false;
				}
			}
			document.routingtableform.action = '/setting/addsingledomain';   
			document.routingtableform.submit();    
		}
	});
	
	$("#add").click(function(){
		$("#domainid").show();
		$("#domainid").attr("readonly", false);
		$("#domainid").val("");
		$("#timeoutid").val("");
		$("#reconnectid").val("");
		$("#sipmaximumid").val("");
		$("#maxusersinbatchid").val("");
		$("#smailsizeid").val("");
		$("#smailattchsizeid").val("");
		$("#unavailableip").html("");
		$("#rtid").val("");
		$('#setModal').modal('show');
	});
	$("#test1").click(function(){
		var ip = $("#serveripid").val();
		if (ip == "") {
			art.dialog.alert("请输入服务器IP！");
			return false;
		}
		var url = '/setting/checkroutingip?ip=' + ip;
		var ajax = {
			url: url, type: 'GET', dataType: 'json', cache: false, success: function(data){
					if (data == 0) {
						$("#test1").val("连通正常");
					} else {
						$("#test1").val("连通异常");
					}
				}					
		};
		jQuery.ajax(ajax);
	});	
});

function routingtableinfo (id) {
	$.get("/setting/getsingledomain", { rtid: id}, function(data){
		var strs = eval('('+data+')');
		$("#rtid").val(strs.id);
		var utype = $("#utypeid").val();
		if(strs.domain == "*" || strs.domain == "全局"){
			if ( utype == 1 ) {
				$("#utypetable input").attr("readonly", "readonly");
				$("#unavailableip").attr("readonly", "readonly");
			}
			$('#optionsRadios1').attr('checked','checked');
			$("#domainid").attr("readonly", "readonly");
			$("#domainid").val("全局");
		}else{
			$("#domainid").attr("readonly", false);
			$("#domainid").val(strs.domain);
			$("#domainid").show();

		}
		$("#timeoutid").val(strs.timeout);
		$("#reconnectid").val(strs.reconnect);
		$("#sipmaximumid").val(strs.sipmaximum);
		$("#maxusersinbatchid").val(strs.maxusersinbatch);
		$("#smailsizeid").val(strs.smailsize);
		$("#smailattchsizeid").val(strs.smailattchsize);
		$("#unavailableip").html(strs.unavailableip);
		$('#setModal').modal('show');
	});
}

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

function changescople (val) {
	if (val == "*") {
		$("#domainid").hide();
		$("#domainid").val("*");
	} else {
		if ($("#domainid").val() == "*") {
			$("#domainid").val("");
		}
		$("#domainid").show();
	}
}

function delinfo () {
	var value_str = "";
	var checkbox = $('tbody input:checkbox');
	checkbox.each(function() {
		if (this.checked) {
			$(this).closest('.checker > span').addClass('checked');
			value_str += this.value+"@";
		}
	});
	$("#checklist").val(value_str);
	if (value_str == "") {
		art.dialog.alert("请选择要删除的条目！");
		return false;
	}
	art.dialog({
		lock: true,
		background: 'black', 
		opacity: 0.87,	
		content: '您将要删除所选中的条目，是否继续？？',
		icon: 'error',
		ok: function () {
			document.listform.action = "/setting/delsingledomain";
			document.listform.submit();
		},
		cancel: true
	});
	
}
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
	<a href="#" class="">参数设置</a>
	<a href="/setting/singledomain" class="current">单域参数设置</a>
	</div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-list"></i></span>
            <h5>单域名参数设置列表</h5>
			<div style="float:right;width:120px;margin-top:4px;height:28px;"><a id="add" href="#" role="button" class="btn" data-toggle="modal" style="width:90px;padding:0;width:90px;height:26px;line-height: 26px;padding: 0px 10px; font-size:12px;"> + 添加设置</a></div>
		  </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width:6%;text-align:center"><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" />全选</th>
                  <th style="width:10%;text-align:center">域名</th>
                  <th style="width:12%;text-align:center" >连接超时时间设置</th>
				  <th style="width:15%;text-align:center" >临时性错误最多尝试次数</th>
				  <th style="width:16%;text-align:center" >单域名每分钟最多投递次数</th>
				  <th style="width:13%;text-align:center" >单次投递最大用户数</th>
				  <th style="width:12%;text-align:center" >单封邮件大小限制</th>
				  <th style="width:14%;text-align:center" >单封邮件附件大小限制</th>
                </tr>
              </thead>
              <tbody>
			  <form name="listform" method="post" action="">
				{%section name=info loop=$infos%}
                <tr class="gradeX">
                  <td style="text-align:center">
				  {%if $infos[info].domain != '*' %}
				  <input style="margin-left:2px;" type="checkbox" id="infoid" name="infoid" value="{%$infos[info].id%}" />
				  {%/if%}
				  </td>
                  <td style="text-align:center">
				  {%if $infos[info].domain == '*' %}
				  <a onclick="routingtableinfo('{%$infos[info].id%}')" href="#" >全局</a>
				  {%else%}
				   <a onclick="routingtableinfo('{%$infos[info].id%}')" href="#" >{%$infos[info].domain%}</a>
				  {%/if%}
				  </td>
                  <td style="text-align:center"  title="{%$infos[info].serverip%}">{%$infos[info].timeout%}</td>
				  <td style="text-align:center"  title="{%$infos[info].reconnect%}">{%$infos[info].reconnect%}</td>
				  <td style="text-align:center"  title="{%$infos[info].sipmaximum%}">{%$infos[info].sipmaximum%}</td>
				  <td style="text-align:center"  title="{%$infos[info].maxusersinbatch%}">{%$infos[info].maxusersinbatch%}</td>
				  <td style="text-align:center"  title="{%$infos[info].smailsize%}">{%$infos[info].smailsize%}</td>
				  <td style="text-align:center"  title="{%$infos[info].smailattchsize%}">{%$infos[info].smailattchsize%}</td>
				</tr>
				{%/section%}
			    <input type="hidden" value="" id="checklist" name="infolist" />
			  </form>
				<tr>
					<td style="text-align:center;width: 74px;border-right: 0px;">
						<input style="margin-top: 2px;font-size:12px;width: 48px;" value="批量删除" class="btn" id="delinfo" onclick="javascript:void(delinfo())" />
					</td>
					<td colspan="6" style="text-align:center;border-left: 0px;">
						<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
							<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
								{%$page%}
							</div>
						</div>
					</td>
					<td style="padding-left: 12px;padding-top: 12px;border-left: 0px;width: 115px;">
						<center><label style="height: 30px; width: 105px;">
							<form action="/setting/singledomain" method="get" style="width:106px">
								<select name="num" size="1" style="width:60px">
									<option {%if $anum==5 %}selected="selected"{%/if%} value="5">5</option>
									<option {%if $anum==10 %}selected="selected"{%/if%} value="10">10</option>
									<option {%if $anum==25 %}selected="selected"{%/if%} value="25">25</option>
									<option {%if $anum==50 %}selected="selected"{%/if%} value="50">50</option>
									<option {%if $anum==100 %}selected="selected"{%/if%} value="100">100</option>
								</select>
									<input type="submit" value="GO" style="font-size:12px;height:26px;" />
							</form>		
							</label>
						</center>
					</td>
					<input type="hidden" value="{%$utype%}" id="utypeid" />
				</tr>
              </tbody>
            </table>
          </div>
        </div>
	  </div>	
	 </div>		
   </div>
</div>
			<div id="setModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:0;margin-left: -330px; width: 50%;">
				<form name="routingtableform" id="authform" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">单域名配置</h3>
					</div>
				    <div class="modal-body">					
						<table class="table table-bordered table-striped" id="utypetable">
						 <thead>
							<tr>
							  <th>属性</th>
							  <th>属性值</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
							  <td style="width:26%;">域名</td>
							  <td> 
							  <input style="width:60%;" name="domain" id="domainid" type="text" value="" placeholder="域名" /></td>
							</tr>
							<tr>
							  <td style="width:26%;">连接超时时间设置</td>
							  <td> 
							  <input style="width:60%;" name="timeout" id="timeoutid" type="text" value="" placeholder="非负整数" /></td>
							</tr>
							<tr>
							  <td style="width:26%;">临时性错误最多尝试次数</td>
							  <td> 
							  <input style="width:60%;" name="reconnect" id="reconnectid" type="text" value="" placeholder="非负整数" /></td>
							</tr>
							<tr>
							  <td style="width:26%;">单域名每分钟最多投递次数</td>
							  <td> 
							  <input style="width:60%;" name="sipmaximum" id="sipmaximumid" type="text" value="" placeholder="非负整数" /></td>
							</tr>
							<tr>
							  <td style="width:26%;">单次投递最大用户数</td>
							  <td> 
							  <input style="width:60%;" name="maxusersinbatch" id="maxusersinbatchid" type="text" value="" placeholder="非负整数" /></td>
							</tr>
							<tr>
							  <td style="width:26%;">单封邮件大小限制</td>
							  <td> 
							  <input style="width:60%;" name="smailsize" id="smailsizeid" type="text" value="" placeholder="非负整数，单位：KB" /></td>
							</tr>
							<tr>
							  <td style="width:26%;">单封邮件附件大小限制</td>
							  <td> 
							  <input style="width:60%;" name="smailattchsize" id="smailattchsizeid" type="text" value="" placeholder="非负整数，单位：KB" /></td>
							</tr>
							<tr>
							  <td style="width:26%;">不可使用IP地址列表</td>
							  <td> 
							  <textarea name="unavailableip" id="unavailableip" style="width:60%;" rows="5"></textarea>
							  <a style="float:right;margin-left:-10%"id="example" data-content="每个IP地址以Enter键分隔。" data-placement="left" data-toggle="popover" class="btn btn-success" href="#" data-original-title="小贴士"><i class="icon-info-sign"></i></a>
							  </td>
							</tr>
							<input type="hidden" id="rtid" name="rtid" value="" />
						  </tbody>
						</table>
					</div>										
					<div class="modal-footer">
						<button id="save" class="btn btn-primary" style="margin-right: 10px;">保存
						<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
					</div>
				</form>
			</div>
{%include file="footer.php"%}