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
			domains: {    
			    required: true,
				isDomain: true
			},
			ips: {    
				required: true
			},
			description: {
				required: true
			}
		},			   
		messages: {    
			domains: {    
				required: '请输入域名',
				isDomain: '输入域名格式有误'
			},
			ips: {    
				required: '请输入IP地址'
			},
			description: {
				 required: '请输入描述内容',
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
			var arr = "";
			var temips = $("#temipsid").val();
			if ( ips.indexOf( "\r\n" ) >= 0 ) {	
				arr = ips.split("\r\n");
			} else if ( ips.indexOf( "\n" ) >= 0 ) {	
				arr = ips.split("\n");
			}
			if ( arr != "" ) {
				var nary = arr.sort();
				for (var i=0; i<arr.length; i++ ) {
					if ( nary[i] == nary[i+1] ){
						art.dialog.alert("IP地址：" + nary[i] + "重复，请在输入框中去除！");
						return false;
					}
				}
			}
			
			if ( temips != ips ) {
				var url = "/setting/checktrustip";
				result = $.ajax({
					type: 'POST',
					url: url,
					data: {ips: ips, temips: temips},
					async: false
				}).responseText;
				if (result != "pass") {
					art.dialog.alert("IP地址：" + result + " 已被分配，请您核对！");
					return false;
				}
			}
		}
	});
	$("#changeid").click(function() {		
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
			art.dialog.alert("请选择要转移的条目！");
			return false;
		}
		var userinfolist = $("#userlistid").val();
		$("#userinfolist").val(userinfolist);
		art.dialog({
			lock: true,
			background: 'black', 
			opacity: 0.87,	
			content: '您将要转移所选中的条目的归属，是否继续？？',
			icon: 'error',
			ok: function () {
				document.listform.action = "/setting/changebelong";
				document.listform.submit();
			},
			cancel: true
		});
	});
	
	$("#changebelong").click(function(){
		$('#changeModal').modal('show');
		
	});
	$("#add").click(function(){
		$('#optionsRadios1').attr('checked','checked');
			$("#cdomainid").val("");
			$("#customtime").hide();
			//$("#userlistid1").attr('disabled',false);
			$("#userlistid1").val("admin@0");
		$("#ipsid").val("");
		$("#rtid").val("");
		$("#descriptionid").val("");
		$('#setModal').modal('show');
	});
});

function routingtableinfo (id) {
	$.get("/setting/gettrustiptableinfo", { rtid: id}, function(data){
		var strs = eval('('+data+')');
		$("#rtid").val(strs.id);
		if(strs.domain == "*"){
			$('#optionsRadios1').attr('checked','checked');
			$("#cdomainid").val("");
			$("#customtime").hide();
		}else{
			$('#optionsRadios2').attr('checked','checked');
			$("#customtime").show();
			$("#cdomainid").val(strs.domain);

		}
		$("#userlistid1").val(strs.uname + "@" + strs.belong);
		//$("#userlistid1").attr('disabled',true);
		$("#ipsid").val(strs.ips);
		$("#temipsid").val(strs.ips);
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
			document.listform.action = "/setting/deltrustiptable";
			document.listform.submit();
		},
		cancel: true
	});
	
}

	function changetimescople (val) {
		if (val == "*") {
			$("#customtime").hide();
			$("#cdomainid").val("*");
		} else {
			if ($("#cdomainid").val() == "*") {
				$("#cdomainid").val("");
			}
			$("#customtime").show();
		}
	}
$(function(){
	var flip = 0;
	$("#searchcond").click(function(){
		$("#searchcondtable").toggle(10, function(){
			if (flip++ % 2 == 0) {
				$("#searchcond").children().children("i").attr("class",'icon-chevron-up');
			} else {
				$("#searchcond").children().children("i").attr("class",'icon-chevron-down');
			}
		});
	})
	var has_val = {%$has_val%};
	if ( has_val == 'yes' ) {
		$("#searchcondtable").show();
	}
	$("#resetcond").click(function(){
		$("#domainid, #unameid").val("all");
		$("#ipid").val("");
	})
	$("#searchbtn").click(function(){
		var url = "/setting/trustiptable?";
		var domain = $("#domainid").val();
		if ( domain != "" ) {
			url += "domain=" + domain + "&";
		}
		var ip = $("#ipid").val();
		if ( ip != "" ) {
			url += "ip=" + encodeURIComponent(ip) + "&";
		}
		var uname = $("#unameid").val();
		if ( uname != "" ) {
			url += "uname=" + uname + "&";
		}
		url += "mode=" + "search" + "&";
		window.location.href = url;
	});
});
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
	<a href="#" class="">参数设置</a>
	<a href="/setting/trustiptable" class="current">信任来源地址</a>
	</div>
  </div>
  <div class="container-fluid">
  
  
  <div href="#collapseG3" id="searchcond" data-toggle="collapse" class="widget-title bg_lo"> 
       <h5 style="float: right;">检索条件
		 <i class="icon-chevron-down"></i>
	   </h5>
    </div>
          <div id="searchcondtable" style="display:none;">
            <form name="deliveryparam" id="auth-form" method="post">
			<table class="table table-bordered">
			  <tbody>
               <tr>
					<td style="width:7%">
					发信域名
					</td>
					<td>
					 <select name="domain" id="domainid" style="width:213px;font-size:13px;">
					  <option value="all" selected>全部</option>
					  {%section name=info loop=$domain%}
					   {%if $domain[info].domain == "*"%}
					<option value="{%$domain[info].domain%}" {%if $view_domain == $domain[info].domain%}selected{%/if%}>所有域</option>
						{%else%}
					<option value="{%$domain[info].domain%}" {%if $view_domain == $domain[info].domain%}selected{%/if%}>{%$domain[info].domain%}</option>
						{%/if%}
					{%/section%}
					</select>
					</td>
					<td style="width:7%">
					中继IP<br/>地址
					</td>
					<td>
					 <input type="text" value="{%$view_ip%}" name="ip" id="ipid" />
					</td>
					<td style="width:7%">
					所属人
					</td>
					<td>
					<select name="uname" id="unameid" style="width:213px;font-size:13px;">
					  <option value="all" selected>全部</option>
					  {%section name=info loop=$username%}
						  {%if $username[info].uname != ""%}
					<option value="{%$username[info].uname%}" {%if $view_uname == $username[info].uname%}selected{%/if%}>{%$username[info].uname%}</option>
						{%/if%}
					{%/section%}
					</select>
					</td>
					<td>
					<center>
					<a href="#" class="btn  btn-mini btn-primary" id="searchbtn">查询</a>
					<a href="#" class="btn  btn-mini btn-primary" id="resetcond">重置</a>
					</center>
					</td>
               </tr>
              </tbody>
            </table>
			<input type="hidden" value="{%$anum%}" name="num" id="numid" />
			<input type="hidden" value="{%$curpage%}" name="page" id="pageid" />
			</form>
          </div>
  
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-list"></i></span>
            <h5>信任中继地址配置列表</h5>
			<div style="float:right;width:120px;margin-top:4px;height:28px;"><a id="add" href="#" role="button" class="btn" data-toggle="modal" style="width:90px;padding:0;width:90px;height:26px;line-height: 26px;padding: 0px 10px; font-size:12px;"> + 添加设置</a></div>
		  </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width:70px;text-align:center"><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" />&nbsp;&nbsp;全选</th>
				 <th style="width:20%;text-align:center" >中继IP地址</th>                 
				 <th style="width:20%;text-align:center">发信域名</th>
				  <th style="width:20%;text-align:center" >所属人</th>
				  <th style="text-align:center" colspan="2">描述</th>
                </tr>
              </thead>
              <tbody>
			  <form name="listform" method="post" action="">
				{%section name=info loop=$infos%}
                <tr class="gradeX">
                  <td style="text-align:center"><input style="margin-left:2px;" type="checkbox" id="infoid" name="infoid" value="{%$infos[info].id%}" /></td>
                   <td style="text-align:center"  title="{%$infos[info].ips%}">
                  	<a onclick="routingtableinfo('{%$infos[info].id%}')" href="#">{%$infos[info].ips%}</a>
                  </td>
                 <td style="text-align:center">
                  	{%if $infos[info].domain=="*" %}
					  <a onclick="routingtableinfo('{%$infos[info].id%}')" href="#" >所有域</a>
				  {%else%}
					  <a onclick="routingtableinfo('{%$infos[info].id%}')" href="#" >{%$infos[info].domain%}</a>
				  {%/if%}
				  </td>
				  <td style="text-align:center"  title="{%$infos[info].ips%}">
                  	<a onclick="routingtableinfo('{%$infos[info].id%}')" href="#">{%$infos[info].uname%}</a>
                  </td>
				  <td style="text-align:center" colspan="2" title="{%$infos[info].description%}">{%$infos[info].description%}</td>
				</tr>
				{%/section%}
			    <input type="hidden" value="" id="checklist" name="infolist" />
				<input type="hidden" value="" id="userinfolist" name="userinfolist" />
			  </form>
				<tr>
					<td colspan="2" style="text-align:left;width: 74px;border-right: 0px;">
						<input style="margin-top: 2px;font-size:12px;width: 48px;" value="批量删除" class="btn" id="delinfo" onclick="javascript:void(delinfo())" />
						<!--<input style="margin-top: 2px;font-size:12px;width: 48px;" value="归属转移" class="btn" id="changebelong" />-->
					</td>
					<td colspan="3" style="text-align:center;border-left: 0px;">
						<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
							<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="margin-left:-120px;text-align: center;">
								{%$page%}
							</div>
						</div>
					</td>
					<td style="padding-left: 12px;padding-top: 12px;border-left: 0px;width: 115px;">
						<center><label style="height: 30px; width: 105px;">
							<form action="/setting/trustiptable" method="get" style="width:106px">
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
				<form name="routingtableform" action="/setting/addtrustiptable" id="authform" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">信任中继地址配置</h3>
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
							  <td style="width:25%;">中继IP地址</td>
							  <td>
							  <textarea rows="5" style="width:68%;" name="ips" id="ipsid" type="text" value="{%$item['ips']%}" placeholder="如果有多个IP地址，请用“Enter”键隔开。" ></textarea>
							  <input type="hidden" name="temips" value="{%$item['ips']%}" id="temipsid" />
							  </td>
							</tr>
							<tr>
							  <td style="width:25%;">发信域名</td>
							  <td> 

								<label for="optionsRadios1">
									<input type="radio" name="domain" id="optionsRadios1" value="*" checked onclick="changetimescople(this.value)">
										所有发信域
								</label>
								<label for="optionsRadios2" style="margin-left: 5px;">
									<input type="radio" name="domain" id="optionsRadios2" value="" onclick="changetimescople(this.value)">
										指定发信域
								</label>

								<span id="customtime" style="display:none;line-height: 20px;">
								  <input style="width:68%;margin-top:5px;" name="domains" id="cdomainid" type="text" value="{%$item['domain']%}" placeholder="发信域名" />
								</span>

							  </td>
							</tr>
							
							<tr>
							  <td style="width:25%;">所属人</td>
							  <td> 
								  <select name="userlist1" id="userlistid1" style="width:69.7%">
								  <option value="admin@0" >admin</option>
								 {%section name=info loop=$userlist%}
								 <option value="{%$userlist[info].username|cat:"@"|cat:$userlist[info].id%}" >{%$userlist[info].username%}</option>
								 {%/section%}
								  </select>
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
			
			<div id="changeModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:0;margin-left: -330px; width: 50%;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">IP归属更改</h3>
					</div>
				    <div class="modal-body">					
						<table class="table table-bordered table-striped">
						  <tbody>
							<tr>
							  <td style="width:25%;">选中条目批量转移给</td>
							  <td> 
							  <select name="userlist" id="userlistid" style="width:53%">
							   <option value="admin@0" >admin</option>
								 {%section name=info loop=$userlist%}
								 <option value="{%$userlist[info].username|cat:"@"|cat:$userlist[info].id%}" >{%$userlist[info].username%}</option>
								 {%/section%}
								  </select>
							  </td>
							</tr>
						  </tbody>
						</table>
					</div>										
					<div class="modal-footer">
						<button id="changeid" class="btn btn-primary" style="margin-right: 10px;">转移
						<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
					</div>
			</div>
			
			
			
{%include file="footer.php"%}
