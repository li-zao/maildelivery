{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	$(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd HH:ii:ss",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"
    });
	
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
	var default_show = parseSearchCon("noclick", "no");
	if (default_show) {
		$("#searchcondtable").show();
		$("#searchcond").children().children("i").attr("class",'icon-chevron-up');
	}
	
	$("#searchbtn").click(function(){
		var url = "/systemmonitor/mailqueue?";
		var status = parseSearchCon("click", "no");
		if (status !== false) {
			url += status;
			window.location.href = url;
		}
	});

	$("#gobtn").click(function(){
		var url = "/systemmonitor/mailqueue?";
		var status = parseSearchCon("click", "go");
		if (status !== false) {
			url += status;
			window.location.href = url;
		}
	});
	
	function parseSearchCon (type, gonum) {
		var parameter = "";
		var has_val = false;
		var pnumber = /^(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/;
		var statusid = $("#statusid").val();
		if (statusid != "" && statusid != null) {
			if (parameter == "") {
				parameter = "status=" + statusid; 
			} else {
				parameter += "&status=" + statusid; 
			}
			if (statusid != "all") {
				has_val = true;
			}
		}
		var srcIPid = $("#srcIPid").val();
		if (srcIPid != "" && srcIPid != null) {
			if (parameter == "") {
				parameter = "srcIP=" + encodeURIComponent(srcIPid); 
			} else {
				parameter += "&srcIP=" + encodeURIComponent(srcIPid); 
			}
			has_val = true;
		}
		var titleid = $("#titleid").val();
		if (titleid != "" && titleid != null) {
			if (parameter == "") {
				parameter = "title=" + encodeURIComponent(titleid); 
			} else {
				parameter += "&title=" + encodeURIComponent(titleid); 
			}
			has_val = true;
		}
		//var myemail = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
		var recieveid = $("#recieveid").val();
		if (recieveid != "" && recieveid != null) {
			if (parameter == "") {
				parameter = "forward=" + encodeURIComponent(recieveid); 
			} else {
				parameter += "&forward=" + encodeURIComponent(recieveid); 
			}
			has_val = true;
		}
		var sendfromid = $("#sendfromid").val();
		if (sendfromid != "" && sendfromid != null) {
			if (parameter == "") {
				parameter = "sendfrom=" + encodeURIComponent(sendfromid); 
			} else {
				parameter += "&sendfrom=" + encodeURIComponent(sendfromid); 
			}
			has_val = true;
		}
		var delilvertypeid = $("#delilvertypeid").val();
		if (delilvertypeid != "" && delilvertypeid != null) {
			if (parameter == "") {
				parameter = "delilvertype=" + delilvertypeid; 
			} else {
				parameter += "&delilvertype=" + delilvertypeid; 
			}
			if (delilvertypeid != "all") {
				has_val = true;
			}
		}
		var sendtime1id = $("#sendtime1id").val();
		var sendtime2id = $("#sendtime2id").val();
		if (sendtime1id != "" && sendtime1id != null) {
			if (parameter == "") {
				parameter = "sendtime1=" + sendtime1id; 
			} else {
				parameter += "&sendtime1=" + sendtime1id; 
			}
			has_val = true;
		}
		if (sendtime2id != "" && sendtime2id != null) {
			if (parameter == "") {
				parameter = "sendtime2=" + sendtime2id; 
			} else {
				parameter += "&sendtime2=" + sendtime2id; 
			}
			has_val = true;
		}
		var settimeid = $("#settimeid").val();
		if (settimeid != "" && settimeid != null) {
			if (parameter == "") {
				parameter = "settime=" + settimeid; 
			} else {
				parameter += "&settime=" + settimeid; 
			}
			if (settimeid == "custom") {
				if (sendtime1id == "" || sendtime2id == "") {
					art.dialog.alert("请输入完整的自定义起始时间！");
					return false;
				}
			}
			if (settimeid != "day") {
				has_val = true;
			}
			if (settimeid == "custom") {
				$("#customtime").show();
			}
		}
		if (gonum == "go") {
			var num = $("#gonumid").val();
			if (num != "" && num != null) {
				if (parameter == "") {
					parameter = "num=" + num; 
				} else {
					parameter += "&num=" + num; 
				}
			}
		} else {
			parameter += "&mode=" + "search"; 
			var num = $("#numid").val();
			if (num != "" && num != null) {
				if (parameter == "") {
					parameter = "num=" + num; 
				} else {
					parameter += "&num=" + num; 
				}
			}
		}
		var curpage = $("#pageid").val();
		if (curpage != "" && curpage != null) {
			if (parameter == "") {
				parameter = "page=" + curpage; 
			} else {
				parameter += "&page=" + curpage; 
			}
		}
		if (type == "click") {
			return parameter;
		} else {
			return has_val;
		}
	}
	$("#resetcond").click(function(){
		//$("#titleid, #recieveid, #sendfromid, #inqueuetime1id, #inqueuetime2id, #srcIPid").val("");
		//$("#overseasid, #srcipid, #settimeid, #mailsize1id, #mailsize2id, #mailtitleid, #recieveid, #sendfromid, #sendtime1id, #sendtime2id").val("");
		
		$("#titleid, #recieveid, #sendtime1id, #sendtime2id, #sendfromid, #srcIPid").val("");
		$("#statusid").val("all");
		$("#delilvertypeid").val("all");
	});
});
function opinfo (type) {
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
		art.dialog.alert("请选择要操作的条目！");
		return false;
	}
	var content = "";
	var oper = "";
	if (type == 'retry') {
		content = "您将要重试投递所选中的条目，是否继续？";
		oper = 4;
	} else if (type == 'enable') {
		content = "您将要放行所选中的条目，是否继续？";
		oper = 2;
	} else if (type == 'disable') {
		content = "您将要禁止所选中的条目，是否继续？";
		oper = 3;
	} else {
		content = "您将要删除所选中的条目，是否继续？";
		oper = 1;
	}
	art.dialog({
			lock: true,
			background: 'black', 
			opacity: 0.87,	
			content: content,
			icon: 'error',
			ok: function () {
				var baseurl = "/systemmonitor/operateeml";
				var ajax = {
					 url: baseurl, type: 'POST', 
					 data:{
						'infolist':value_str, oper: oper
						},
						dataType: 'html', cache: false, success: function(html) {
						location.reload();
					 }
				};
				$.ajax(ajax);
			},
			cancel: true
		});
}
function changetimescople (val) {
	if (val == "custom") {
		$("#customtime").show();
	} else {
		$("#customtime").hide();
	}
}
function smtptasklog (id) {
	$.get("/systemmonitor/getsmtptasklog", { id: id}, function(data){
		var strs = eval('('+data+')');
		if (strs.taskid > 0) {
			$('#delilvertype').text("任务");
		} else {
			$('#delilvertype').text("转发");
		}
		$('#sendfromids').text(strs.sendfrom);
		$('#forwardids').text(strs.forward.replace(/;/g,"; "));
		$('#sizeid').text(strs.size);
		$('#msgid').text(strs.msgid);
		var log = strs.log.replace(/;/g,"; ");
		$('#log').html(log.replace(/",/g,"<br>");
		$('#setModal').modal('show');
	});
}
</script>
<div id="setModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:0;margin-left: -330px; width: 50%;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">正常邮件队列投递日志</h3>
					</div>
				    <div class="modal-body">					
						<table class="table table-bordered table-striped">
						  <tbody>
							<tr>
							  <td style="width:25%;">任务&nbsp;/&nbsp;转发</td>
							  <td> 
							 <div id="delilvertype" style="work-break:break-all;overflow:auto;"></div>
							  </td>
							</tr>
							<tr>
							  <td style="width:25%;">发件人</td>
							  <td> 
							 <div id="sendfromids" style="work-break:break-all;overflow:auto;"></div>
							  </td>
							</tr>	
							<tr>
							  <td style="width:25%;">收件人</td>
							  <td> 
							 <div id="forwardids" style="work-break:break-all;overflow:auto;"></div>
							  </td>
							</tr>
							<tr>
							  <td style="width:25%;">邮件大小</td>
							  <td> 
							 <div id="sizeid" style="work-break:break-all;overflow:auto;"></div>
							  </td>
							</tr>
							<tr>
							  <td style="width:25%;">MessageID</td>
							  <td> 
							 <div id="msgid" style="work-break:break-all;overflow:auto;"></div>
							  </td>
							</tr>
							<tr>
							  <td style="width:25%;">投递结果</td>
							  <td>
							  <div id="log" style="work-break:break-all;overflow:auto;"></div>
							  </td>
							</tr>
						  </tbody>
						</table>
					</div>	
			</div>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">系统监控</a> 
	<a href="/systemmonitor/mailqueue" class="current">正常邮件队列</a> 
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
			   <tr class="searchcondition">
					<td style="width:10%">
					发件人
					</td>
					<td>
					 <input type="text" value="{%$sendfrom%}" name="sendfrom" id="sendfromid" />
					</td>
					<td style="width:10%">
					收件人
					</td>
					<td>
					 <input type="text" value="{%$forward%}" name="recieve" id="recieveid"/>
					</td>
					<td style="width:10%">
					邮件来源
					</td>
					<td>
					 <input type="text" value="{%$srcIP%}" name="srcIP" id="srcIPid"/>
					</td>
               </tr>
			   <tr class="searchcondition">
					<td style="width:10%">
					投递状态
					</td>
					<td>
					<select name="status" id="statusid" style="width:210px">
					  <option value="all" selected>全部</option>
					  <!--<option value="0" {%if $status == '0'%}selected{%/if%}>开始投递</option>-->
					  <option value="1" {%if $status == '1'%}selected{%/if%}>投递中</option>
					  <!--<option value="2" {%if $status == '2'%}selected{%/if%}>投递成功</option>-->
					  <option value="3" {%if $status == '3'%}selected{%/if%}>硬退</option>
					  <option value="4" {%if $status == '4'%}selected{%/if%}>重试</option>
					  <option value="5" {%if $status == '5'%}selected{%/if%}>软退</option>
					  <option value="6" {%if $status == '6'%}selected{%/if%}>拦截</option>
					  <option value="7" {%if $status == '7'%}selected{%/if%}>停止</option>
					  <!-- option value="9" {%if $status == '9'%}selected{%/if%}>待审核</option>-->
					</select>
					</td>
					<td style="width:10%">
					邮件主题
					</td>
					<td>
					 <input type="text" value="{%$title%}" name="title" id="titleid"/>
					</td>
					{%if ($role=='admin' or $role=='sadmin')%}
					<td style="width:10%">
					任务&nbsp;/&nbsp;转发
					</td>
					<td>
					<select name="delilvertype" id="delilvertypeid" style="width:213px">
					  <option value="all" {%if $delilvertype == 'all' or $delilvertype == null%}selected{%/if%}>全部</option>
					  <option value="1" {%if $delilvertype == '1'%}selected{%/if%}>任务</option>
					  <option value="0" {%if $delilvertype == '0'%}selected{%/if%}>转发</option>
					</select>
					</td>
					{%else%}
					<td  colspan="2"></td>
					{%/if%}
               </tr>
               <tr>
					<td style="width:10%" class="searchcondition">
					队列时间
					</td>
					<td colspan="3" class="searchcondition">
					<select name="settime" id="settimeid" style="width:15%;" onchange="changetimescople(this.value)">
					  <option value="day" selected>今天</option>
					  <option value="month" {%if $settime == 'month'%}selected{%/if%}>本月</option>
					  <option value="custom" {%if $settime == 'custom'%}selected{%/if%}>自定义</option>
					</select>
					<span id="customtime" style="display:none">
					<span style="margin-left: 13px;margin-right: 1px;">从&nbsp;</span>
					<div class="input-append date form_datetime"><input type="text" style="width:90%;" value="{%$sendtime1%}" name="sendtime1" id="sendtime1id" /><span class="add-on"><i class="icon-calendar"></i></span></div>
					<span style="margin-left: 12px;">&nbsp;至&nbsp;</span>
					<div class="input-append date form_datetime" style="margin-left: 1px;"><input type="text" style="width:90%;" value="{%$sendtime2%}" name="sendtime2" id="sendtime2id" /><span class="add-on"><i class="icon-calendar"></i></span></div>
					</span>
					</td>
					<td colspan="2" style="text-align:center">
					<a href="#" class="btn  btn-mini btn-primary" id="searchbtn">查询</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="#" class="btn  btn-mini btn-primary" id="resetcond">重置</a>
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
		<div class="widget-box" style="padding-bottom: 10px;border-bottom: 1px solid #cdcdcd;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>正常邮件</h5>
			</div>
			<div class="widget-content nopadding">
				<table width="80%" align="center" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="text-align:center;" width="7%"><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" /> 全选</th>
							<th style="text-align:center;" width="15%">发件人</th>
							<th style="text-align:center;" width="18%">收件人</th>
							<th style="text-align:center;" width="23%">邮件主题</th>
							<th style="text-align:center;">队列时间</th>
							<th style="text-align:center;">邮件来源</th>
							<th style="text-align:center;">投递状态</th>
							<th style="text-align:center;">任务&nbsp;/&nbsp;转发</th>
						</tr>
					</thead>
					<tbody>
					 <form name="listform" method="post" action="">
					{%$num = 1%}
					{%section name=loop loop=$infos%}
						<tr>
							<td style="text-align:center"><input style="margin-left:2px;" type="checkbox" id="infoid" name="infoid" value="{%$infos[loop].id%}" /></td>
							<!--<td style="text-align:center">{%$num++%}</td>-->
							<td style="text-align:center">
							<a href="#" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="smtptasklog('{%$infos[loop].id%}')">{%$infos[loop].sendfrom|truncate:30:"...":true%}</a>							
							</td>
							<td style="text-align:center">
							<a href="#" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="smtptasklog('{%$infos[loop].id%}')" title="{%$infos[loop].forward%}">{%$infos[loop].forward|truncate:30:"...":true%}</a>
							</td>
							<td style="text-align:center">
							<a href="#" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="smtptasklog('{%$infos[loop].id%}')">{%$infos[loop].title|truncate:30:"...":true%}</a>
							</td>
							<td style="text-align:center">
							<a href="#" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="smtptasklog('{%$infos[loop].id%}')">{%$infos[loop].inqueuetime|truncate:30:"...":true%}</a>
							</td>
							<td style="text-align:center">
							<a href="#" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="smtptasklog('{%$infos[loop].id%}')">{%$infos[loop].srcIP|truncate:30:"...":true%}</a>
							</td>
							<td style="text-align:center">
							<a href="#mail{%$infos[loop].id%}" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="smtptasklog('{%$infos[loop].id%}')">							
							{%if $infos[loop].status == 3%}
							硬退
							{%elseif $infos[loop].status == 5%}
							软退
							{%elseif $infos[loop].status == 7%}
							停止
							{%elseif $infos[loop].status == 6%}
							拦截
							{%elseif $infos[loop].status == 9%}
							待审核
							{%elseif $infos[loop].status == 0%}
							开始投递
							{%elseif $infos[loop].status == 4%}
							重试
							{%elseif $infos[loop].status == 1%}
							投递中
							{%/if%}
							</a>
							</td>
							<td style="text-align:center">
							<a href="#" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="smtptasklog('{%$infos[loop].id%}')">
							{%if $infos[loop].taskid > 0%}
							任务
							{%else%}
							转发
							{%/if%}
							</a>
							</td>
						</tr>
					{%/section%}
					<input type="hidden" value="" id="checklist" name="infolist" />
			  </form>
					</tbody>
					<tr class="odd hasnodata" id="hasnodata">
					<td class="dataTables_empty" align="center" valign="top" colspan="8">
						没有找到任何数据
					</td>
			  </tr>
				</table>
			</div>
			<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
				<div class="row-fluid" style="text-align:right; margin-top: 0px;">
					<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="margin-top: 5px;">
						<div id="" class="" style="float:left;margin-left:8px;">
						<input style="margin-top: 2px; font-size: 12px; width: 40px; padding: 3px 5px;" value="删除" class="btn" id="delinfo" onclick="javascript:void(opinfo('del'))" />
						<input style="margin-top: 2px; font-size: 12px; width: 40px; padding: 3px 5px;" value="重试" class="btn" id="retry" onclick="javascript:void(opinfo('retry'))" />
						{%if ($role=='audit')%}
						<input style="margin-top: 2px; font-size: 12px; width: 40px; padding: 3px 5px;" value="停止" class="btn" id="disable" onclick="javascript:void(opinfo('disable'))" />
						{%/if%}
						</div>
						<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;float:left;margin-left:20%;">
							{%$page%}
						</div>
						<div id="DataTables_Table_0_length" class="dataTables_length" style="float:right;width:100px;margin-right:18px;">
							<label style="height:30px;">
								<form  method="GET" style="width:106px">
									<select name="num" id="gonumid" size="1" aria-controls="DataTables_Table_0" style="width:60px">
										<option {%if $anum==5 %}selected="selected"{%/if%} value="5">5</option>
										<option {%if $anum==10 %}selected="selected"{%/if%} value="10">10</option>
										<option {%if $anum==25 %}selected="selected"{%/if%} value="25">25</option>
										<option {%if $anum==50 %}selected="selected"{%/if%} value="50">50</option>
										<option {%if $anum==100 %}selected="selected"{%/if%} value="100">100</option>
									</select>
									<input type="button" id="gobtn" value="GO" style="font-size:12px;height:26px;" />
								</form>		
							</label>
						</div>
					</div>
    			</div>
			</div>
		</div>
	  </div>	
	 </div>	
   </div>
</div>
{%include file="footer.php"%}
