{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	jQuery.validator.addMethod("isDomain", function(value, element) {    
		return this.optional(element) || (/^[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/.test(value));    
	}, "输入无效");
	jQuery.validator.addMethod("isNumber", function(value, element) { 
		return this.optional(element) || (/^[0-9]*[1-9][0-9]*$/.test(value));    
	}, "输入无效");
	jQuery.validator.addMethod("isMyIP", function(value, element) { 
		return this.optional(element) || (/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/.test(value));    
	}, "输入服务器地址格式错误");
			
	var validator = $("#authform").validate({ 
		rules: {
			domain: {    
			    required: true,
				isDomain: true
			},
			server: {    
				required: true,
				isMyIP: true
			},
			port: {    
				required: true,
				isNumber: true  
			},
			maxtime: {    
				required: true,    
				isNumber: true    
			}
		},			   
		messages: {    
			domain: {    
				required: '请输入认证域名',
				isDomain: '输入域名格式有误'
			},
			server: {    
				required: '请输入认证服务器地址',    
				isMyIP: '输入服务器地址格式错误'    
			},
			port: {    
				required: '请输入认证端口号',
				isNumber: '输入端口号无效'
			},
			maxtime: {    
				required: '请输入认证超时时间',    
				isNumber: '输入时间无效'    
			}
		}    
	});
	$("#save").click(function() {
		if(validator.form()){
			var pro = $("input[name=protocol]:checked").val();
			if (pro == "3") {
				var ad_str = $("#ad_dn").val();
				if (ad_str == "" || ad_str == null) {
					art.dialog.alert("AD BaseDN 此项不能为空");
					return false;
				}
			}
			document.authsetting.action = '/setting/addauthsetting';   
			document.authsetting.submit();    
		}
	});
	
	$("#add").click(function(){
		$("input[name='asid']").val("");
		$("input[name='domain']").val("");
		$("input[name='server']").val("");
		$("input[name='port']").val("");
		$("input[name='maxtime']").val("");
		$("input[name='ad_dn']").val("");
		$("input[name='ad_admin']").val("");
		$("input[name='ad_admin_pwd']").val("");
		$("input[name='protocol']:eq(0)").attr('checked', true);
		$(".adbasedn input").attr("disabled", true);
		$('#setModal').modal('show');
	});
});
function switchProtocol (pro) {
	if (pro == "3") {
		$(".adbasedn input").attr("disabled", false);
	} else {
		$(".adbasedn input").attr("disabled", true);
	}
}
function geteachinfo (id) {
	$.get("/setting/getauthsettinginfo", { asid: id}, function(data){
		var strs = eval('('+data+')');
		if ( strs.protocol == "3" ) {
			$(".adbasedn input").attr("disabled", false);
		} else {
			$(".adbasedn input").attr("disabled", true);
		}
		$("input[name='asid']").val(strs.id);
		$("input[name='domain']").val(strs.domain);
		$("input[name='server']").val(strs.server);
		$("input[name='port']").val(strs.port);
		$("input[name='maxtime']").val(strs.maxtime);
		$("input[name='protocol']:eq(" + strs.protocol + ")").attr('checked', true);
		$("input[name='ad_dn']").val(strs.ad_dn);
		$("input[name='ad_admin']").val(strs.ad_admin);
		$("input[name='ad_admin_pwd']").val(strs.ad_admin_pwd);
		$('#setModal').modal('show');
	});
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
			document.listform.action = "/setting/delauthsetting";
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
	<a href="#" class="">安全参数</a>
	<a href="/setting/authsetting" class="current">用户认证设置</a>
	</div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-list"></i></span>
            <h5>用户认证设置列表</h5>
			<div style="float:right;width:120px;margin-top:4px;height:28px;"><a id="add" href="#" role="button" class="btn" data-toggle="modal" style="width:90px;padding:0;width:90px;height:26px;line-height: 26px;padding: 0px 10px; font-size:12px;"> + 添加设置</a></div>
		  </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="text-align:center"><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" />&nbsp;&nbsp;全选</th>
                  <th style="text-align:center">用户域名</th>
                  <th style="text-align:center">用户认证服务器</th>
				  <th style="text-align:center">用户认证端口号</th>
				  <th style="text-align:center">超时时间（秒）</th>
				  <th style="text-align:center">认证协议</th>
                </tr>
              </thead>
              <tbody>
			  <form name="listform" method="post" action="">
				{%section name=info loop=$infos%}
                <tr class="gradeX">
                  <td style="text-align:center"><input style="margin-left:2px;" type="checkbox" id="infoid" name="infoid" value="{%$infos[info].id%}" /></td>
                  <td style="text-align:center">
				  <a onclick="geteachinfo('{%$infos[info].id%}')" href="#" >{%$infos[info].domain%}</a>
				  </td>
				  <td style="text-align:center">{%$infos[info].server%}</td>
				  <td style="text-align:center">{%$infos[info].port%}</td>
                  <td style="text-align:center">{%$infos[info].maxtime%}</td>
                  {%if $infos[info].protocol == 0%}
					<td style="text-align:center">ESMTP</td>
				  {%elseif $infos[info].protocol == 1%}
					<td style="text-align:center">POP3</td>
				  {%elseif $infos[info].protocol == 2%}
					<td style="text-align:center">IMAP4</td>
				  {%elseif $infos[info].protocol == 3%}
					<td style="text-align:center">Active Directory</td>
				  {%/if%}
				 </tr>
				{%/section%}
			    <input type="hidden" value="" id="checklist" name="infolist" />
			  </form>
				<tr>
					<td style="text-align:center;width: 74px;border-right: 0px;">
						<input style="margin-top: 2px;font-size:12px;width: 48px;" value="批量删除" class="btn" id="delinfo" onclick="javascript:void(delinfo())" />
					</td>
					<td style="text-align:center;border-left: 0px;" colspan="4">
						<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
							<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
								{%$page%}
							</div>
						</div>
					</td>
					<td style="padding-left: 12px;padding-top: 12px;border-left: 0px;width: 115px;">
						<center><label style="height: 30px; width: 105px;">
							<form action="/setting/authsetting" method="get" style="width:106px">
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
				</tr>
              </tbody>
            </table>
          </div>
        </div>
	  </div>	
	 </div>		
   </div>
</div>
			<div id="setModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-left: -330px; width: 50%;">
				<form name="authsetting" id="authform" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">用户认证设置基本信息</h3>
					</div>
				    <div class="modal-body">					
						<table class="table table-bordered table-striped">
						 <thead>
							<tr>
							  <th>属性</th>
							  <th>属性值</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
							  <td style="width:25%;">认证域名</td>
							  <td> 
							  <input style="width:48%;" name="domain" type="text" value="{%$item['hellodomain']%}" placeholder="域名" /></td>
							</tr>
							<tr>
							  <td style="width:25%;">认证服务器</td>
							  <td>
							  <input style="width:48%;" name="server" type="text" value="{%$item['timeout']%}" placeholder="IP地址" />
							 </td>
							</tr>
							<tr>
							  <td style="width:25%;">认证端口号</td>
							  <td>
							  <input style="width:48%;" name="port" type="text" value="{%$item['attempts']%}" placeholder="有效端口号" /></td>
							</tr>
							<tr>
							  <td style="width:25%;">认证超时时间（秒）</td>
							  <td>
							  <input style="width:48%;" name="maxtime" type="text" value="{%$item['maxiis']%}" placeholder="单位：秒" /></td>
							</tr>
							<tr style="height:42px;">
							  <td style="width:25%;">用户认证协议</td>
							  <td> 
							  <input name="protocol" {%if $item['protocol'] == '0' or $item['protocol'] == '' or $item['protocol'] == null %}checked{%/if%} type="radio" style="vertical-align:top" value="0" onclick="switchProtocol(0);" />&nbsp;ESMTP&nbsp;&nbsp;&nbsp;
							  <input name="protocol" {%if $item['protocol'] == '1' %}checked{%/if%} type="radio" style="vertical-align:top" value="1" onclick="switchProtocol(1);" />&nbsp;POP3&nbsp;&nbsp;&nbsp;&nbsp;
							  <input name="protocol" {%if $item['protocol'] == '2' %}checked{%/if%} type="radio" style="vertical-align:top" value="2" onclick="switchProtocol(2);" />&nbsp;IMAP4&nbsp;&nbsp;&nbsp;
							  <input name="protocol" {%if $item['protocol'] == '3' %}checked{%/if%} type="radio" style="vertical-align:top" value="3" onclick="switchProtocol(3);" />&nbsp;Active Directory &nbsp;&nbsp;&nbsp;&nbsp;
							  </td>
							</tr>
							<tr class="adbasedn">
							  <td colspan="2" style="width:25%;">
							  如果选择采用Active Directory方式认证，请填写以下AD信息。如果AD允许匿名查询，则可以不填管理员信息。
							  </td>
							</tr>
							<tr class="adbasedn">
							  <td style="width:25%;">AD BaseDN</td>
							  <td>
							  <input style="width:48%;" id="ad_dn" name="ad_dn" type="text" value="{%$item['attempts']%}" /></td>
							</tr>
							<input type="hidden" id="asid" name="asid" value="" />
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