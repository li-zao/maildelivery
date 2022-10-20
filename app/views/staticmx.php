{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	jQuery.validator.addMethod("isDomain", function(value, element) {    
		return this.optional(element) || (/^[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/.test(value));    
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
			ips: {    
				required: true
			}
		},			   
		messages: {    
			domain: {    
				required: '请输入域名',
				isDomain: '输入域名格式有误'
			},
			ips: {    
				required: '请输入IP地址'
			}
		}    
	});
	$("#save").click(function() {
		if(validator.form()){
			var ips = $("textarea[name='ips']").val();
			if (ips != "" && ips != null) {
				var status = checkIP (ips);
				if (status !== true) {
					art.dialog.alert("IP地址：" + status + " 格式有误");
					return false;
				}
			}
			var url = "/setting/checkstaticmx";
			var rtid = $("#rtid").val();
			if ( rtid != "" && rtid != null ) {
				result = $.ajax({
					type: 'POST',
					url: url,
					data: {domain: $("#domainid").val(), rtid: rtid},
					async: false
				}).responseText;
				if ($.trim(result) != "pass") {
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
				if ($.trim(result) != "pass") {
					art.dialog.alert("该域名已存在，请您核对！");
					return false;
				}
			}
		}
	});
	
	$("#add").click(function(){
		$("#rtid").val("");
		$("#domainid").val("");
		$("#ipsid").val("");
		$("#descriptionid").val("");
		$('#setModal').modal('show');
	});
});

function routingtableinfo (id) {
	$.get("/setting/getstaticmxinfo", { rtid: id}, function(data){
		var strs = eval('('+data+')');
		$("#rtid").val(strs.id);
		$("#domainid").val(strs.domain);
		$("#ipsid").val(strs.ips);
		$("#descriptionid").val(strs.description);
		$('#setModal').modal('show');
	});
}

function checkIP(ip) {
	var ip_patt = /^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/;
	if ( ip.indexOf( "\r\n" ) >= 0 ) {	
		var addrs = ip.split("\r\n");
			if (addrs.length > 0) {
			for (var i = 0; i < addrs.length; i++) {
				if (addrs[i] == "") {
					continue;
				}
				if (!ip_patt.test(addrs[i])) {
					 return addrs[i];
				}
			}
		}
	} else if ( ip.indexOf( "\n" ) >= 0 ) {	
		var addrs = ip.split("\n");
			if (addrs.length > 0) {
			for (var i = 0; i < addrs.length; i++) {
				if (addrs[i] == "") {
					continue;
				}
				if (!ip_patt.test(addrs[i])) {
					 return addrs[i];
				}
			}
		}
	} else {
		var addrs = ip;
		if (!ip_patt.test(addrs)) {
			return addrs;
	    }
	}
	
	return true;
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
			document.listform.action = "/setting/delstaticmxtable";
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
	<a href="/setting/trustiptable" class="current">静态中继路由</a>
	</div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-list"></i></span>
            <h5>静态中继路由列表</h5>
			<div style="float:right;width:120px;margin-top:4px;height:28px;"><a id="add" href="#" role="button" class="btn" data-toggle="modal" style="width:90px;padding:0;width:90px;height:26px;line-height: 26px;padding: 0px 10px; font-size:12px;"> + 添加设置</a></div>
		  </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered" id="table-1">
              <thead>
                <tr>
                  <th style="width:6%;text-align:center"><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" />&nbsp;&nbsp;全选</th>
                  <th style="width:20%;text-align:center">域名</th>
                  <th style="width:20%;text-align:center" >IP地址</th>
				  <th style="text-align:center" colspan="2">描述</th>
                </tr>
              </thead>
              <tbody>
			  <form name="listform" method="post" action="">
				{%section name=info loop=$infos%}
                <tr class="gradeX">
                  <td style="text-align:center"><input style="margin-left:2px;" type="checkbox" id="infoid" name="infoid" value="{%$infos[info].id%}" /></td>
                  <td style="text-align:center">
				  <a onclick="routingtableinfo('{%$infos[info].id%}')" href="javascript:void(0);" >{%$infos[info].domain%}</a>
				  </td>
                  <td style="text-align:center"  title="{%$infos[info].ips%}">{%$infos[info].ips%}</td>
				  <td style="text-align:center" colspan="2" title="{%$infos[info].description%}">{%$infos[info].description%}</td>
				</tr>
				{%/section%}
			    <input type="hidden" value="" id="checklist" name="infolist" />
			  </form>
				<tr>
					<td style="text-align:center;width: 74px;border-right: 0px;">
						<input style="margin-top: 2px;font-size:12px;width: 48px;" value="批量删除" class="btn" id="delinfo" onclick="javascript:void(delinfo())" />
					</td>
					<td colspan="3" style="text-align:center;border-left: 0px;">
						<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
							<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
								{%$page%}
							</div>
						</div>
					</td>
					<td style="padding-left: 12px;padding-top: 12px;border-left: 0px;width: 115px;">
						<center><label style="height: 30px; width: 105px;">
							<form name="gonum" action="/setting/staticmx" method="get" style="width:106px">
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
			<div id="setModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:0;margin-left: -330px; width: 50%;">
				<form name="routingtableform" id="authform" action="/setting/addstaticmx" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">静态中继路由配置</h3>
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
							  <td style="width:25%;">域名</td>
							  <td> 
							  <input style="width:68%;" name="domain" id="domainid" type="text" value="{%$item['domain']%}" placeholder="域名" /></td>
							</tr>
							<tr>
							  <td style="width:25%;">IP地址</td>
							  <td>
							  <textarea rows="5" style="width:68%;" name="ips" id="ipsid" type="text" value="{%$item['ips']%}" placeholder="如果有多个IP地址，请用“Enter”键隔开。" ></textarea>
							  </td>
							</tr>
							<tr>
							  <td style="width:25%;">描述</td>
							  <td>
							  <input style="width:68%;" name="description" id="descriptionid" type="text" value="{%$item['description']%}" placeholder="描述" />
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
