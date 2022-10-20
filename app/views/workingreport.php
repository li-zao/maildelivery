{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	jQuery.validator.addMethod("isDomain", function(value, element) {    
		return this.optional(element) || (/^[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/.test(value));    
	}, "输入无效");
	var validator = $("#workingreportid").validate({ 
		rules: {
			domain: {    
			    required: true,
				isDomain: true
			},
			recipient: {    
				required: true,
				email: true
			},
			timespan: {    
				required: true,
				pnumber: true
			},
			reporttime: {    
				required: true
			}
		},			   
		messages: {    
			domain: {    
				required: '请输入域名',
				isDomain: '输入域名格式有误'
			},
			recipient: {    
				required: '请输入接收人邮箱',   
				email: "输入邮箱格式有误"
			},
			timespan: {    
				required: '请输入时间跨度',   
				pnumber: "请输入非负整数"
			},
			reporttime: {    
				required: '请输入发送时间'
			}
		}    
	});
	$("#save").click(function() {
		if(validator.form()){
            $("#setModal").hide();
            art.dialog.alert("请求成功，请稍后！");
		}
	});
	
	$("#add").click(function(){
		$("#domainid").val("");
		$("#recipientid").val("");
		$("#timespanid").val("");
		$("#wrid").val("");
		$("#reporttimeid").val("01");
		$('#setModal').modal('show');
	});
});

function workingreporinfo (id) {
	$.get("/setting/getworkingreportinfo", { wrid: id}, function(data){
		var strs = eval('('+data+')');
		$("#wrid").val(strs.id);
		$("#recipientid").val(strs.recipient);
		$("#domainid").val(strs.domain);
		$("#timespanid").val(strs.timespan);
		$("#reporttimeid").val(strs.reporttime);
		$('#setModal').modal('show');
	});
}

function checkIP(ip) {
	var ip_patt = /^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/;
	if ( ip.indexOf( ";" ) >= 0 ) {	
		var addrs = ip.split(";");
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
			document.listform.action = "/setting/delworkingreport";
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
	<a href="#" class="">系统设置</a>
	<a href="/setting/workingreport" class="current">运行情况报告</a>
	</div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-list"></i></span>
            <h5>运行情况邮件报告</h5>
			<div style="float:right;width:120px;margin-top:4px;height:28px;"><a id="add" href="#" role="button" class="btn" data-toggle="modal" style="width:90px;padding:0;width:90px;height:26px;line-height: 26px;padding: 0px 10px; font-size:12px;"> + 新增报告</a></div>
		  </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width:4%;text-align:center"><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" />&nbsp;&nbsp;全选</th>
                  <th style="width:6%;text-align:center">序号</th>
				  <th style="width:20%;text-align:center">报告发送邮件域</th>
                  <th style="text-align:center">报告接收人</th>
				  <th style="text-align:center">报告发送时间</th>
				  <th style="width:14%;ttext-align:center">报告时间跨度</th>
                </tr>
              </thead>
              <tbody>
			  <form name="listform" method="post" action="">
				{%$num = 1%}
				{%section name=info loop=$infos%}
				
                <tr class="gradeX">
                  <td style="text-align:center"><input style="margin-left:2px;" type="checkbox" id="infoid" name="infoid" value="{%$infos[info].id%}" /></td>
                  <td style="text-align:center;">{%$num++%}</td>
				  <td style="text-align:center">
				  <a onclick="workingreporinfo('{%$infos[info].id%}')" href="#" >{%$infos[info].domain%}</a>
				  </td>
				  <td style="text-align:center">{%$infos[info].recipient%}</td>
                  <td style="text-align:center">每天&nbsp;{%$infos[info].reporttime%}&nbsp;时发送</td>
				  <td style="text-align:center">{%$infos[info].timespan%}&nbsp;个小时</td>
				</tr>
				{%/section%}
			    <input type="hidden" value="" id="checklist" name="infolist" />
			  </form>
				<tr>
					<td style="text-align:center;width: 74px;border-right: 0px;">
						<input style="margin-top: 2px;font-size:12px;width: 48px;" value="批量删除" class="btn" id="delinfo" onclick="javascript:void(delinfo())" />
					</td>
					<td colspan="4" style="text-align:center;border-left: 0px;">
						<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
							<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
								{%$page%}
							</div>
						</div>
					</td>
					<td style="padding-left: 12px;padding-top: 12px;border-left: 0px;width: 115px;">
						<center><label style="height: 30px; width: 105px;">
							<form action="/setting/workingreport" method="get" style="width:106px">
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
			<div id="setModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:0;margin-left: -330px; width: 45%;">
				<form name="workingreport" id="workingreportid" action="/setting/addworkingreport" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">运行情况报告配置</h3>
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
							  <td style="width:25%;">报告发送邮件域</td>
							  <td> 
							  <input style="width:55%;" name="domain" id="domainid" type="text" value="{%$item['domain']%}" placeholder="域名" /></td>
							</tr>
							<tr>
							  <td style="width:25%;">报告接收人</td>
							  <td>
							  <input style="width:55%;" name="recipient" id="recipientid" type="text" value="{%$item['recipient']%}" placeholder="Email地址" />
							  </td>
							</tr>
							<tr>
							  <td style="width:25%;">报告发送时间</td>
							  <td>
							  每天的&nbsp;<select name="reporttime" id="reporttimeid" style="width:33%">
									  <option value="01" {%if $infos.sessiontime == '5'%}selected{%/if%}>01时</option>
									  <option value="02" {%if $infos.sessiontime == '10'%}selected{%/if%}>02时</option>
									  <option value="03" {%if $infos.sessiontime == '15'%}selected{%/if%}>03时</option>
									  <option value="04" {%if $infos.sessiontime == '30'%}selected{%/if%}>04时</option>
									  <option value="05" {%if $infos.sessiontime == '5'%}selected{%/if%}>05时</option>
									  <option value="06" {%if $infos.sessiontime == '10'%}selected{%/if%}>06时</option>
									  <option value="07" {%if $infos.sessiontime == '15'%}selected{%/if%}>07时</option>
									  <option value="08" {%if $infos.sessiontime == '30'%}selected{%/if%}>08时</option>
									  <option value="09" {%if $infos.sessiontime == '5'%}selected{%/if%}>09时</option>
									  <option value="10" {%if $infos.sessiontime == '10'%}selected{%/if%}>10时</option>
									  <option value="11" {%if $infos.sessiontime == '15'%}selected{%/if%}>11时</option>
									  <option value="12" {%if $infos.sessiontime == '30'%}selected{%/if%}>12时</option>
									  <option value="13" {%if $infos.sessiontime == '5'%}selected{%/if%}>13时</option>
									  <option value="14" {%if $infos.sessiontime == '10'%}selected{%/if%}>14时</option>
									  <option value="15" {%if $infos.sessiontime == '15'%}selected{%/if%}>15时</option>
									  <option value="16" {%if $infos.sessiontime == '30'%}selected{%/if%}>16时</option>
									  <option value="17" {%if $infos.sessiontime == '5'%}selected{%/if%}>17时</option>
									  <option value="18" {%if $infos.sessiontime == '10'%}selected{%/if%}>18时</option>
									  <option value="19" {%if $infos.sessiontime == '15'%}selected{%/if%}>19时</option>
									  <option value="20" {%if $infos.sessiontime == '30'%}selected{%/if%}>20时</option>
									  <option value="21" {%if $infos.sessiontime == '5'%}selected{%/if%}>21时</option>
									  <option value="22" {%if $infos.sessiontime == '10'%}selected{%/if%}>22时</option>
									  <option value="23" {%if $infos.sessiontime == '15'%}selected{%/if%}>23时</option>
									  <option value="00" {%if $infos.sessiontime == '30'%}selected{%/if%}>00时</option>
									 </select>&nbsp;发送
							  </td>
							</tr>
							<tr>
							  <td style="width:25%;">报告时间跨度</td>
							  <td>
							  <input style="width:55%;" name="timespan" id="timespanid" type="text" value="{%$item['timespan']%}" placeholder="正整数（单位：小时）" />
							  </td>
							</tr>
							<input type="hidden" id="wrid" name="wrid" value="" />
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