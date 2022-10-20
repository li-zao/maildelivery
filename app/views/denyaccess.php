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
		//alert(default_show);
		$("#searchcondtable").show();
		$("#searchcond").children().children("i").attr("class",'icon-chevron-up');
	}
	
	$("#searchbtn").click(function(){
		var url = "/systemmonitor/denyaccess?";
		var status = parseSearchCon("click", "no");
		if (status !== false) {
			url += status;
			window.location.href = url;
		}
	});

	function parseSearchCon (type, gonum) {
		var parameter = "";
		var has_val = false;
		var srcIPid = $("#srcIPid").val();
		if (srcIPid != "" && srcIPid != null) {
			if (parameter == "") {
				parameter = "srcIP=" + encodeURIComponent(srcIPid); 
			} else {
				parameter += "&srcIP=" + encodeURIComponent(srcIPid); 
			}
			has_val = true;
		}
		var denytypeid = $("#denytypeid").val();
		if (denytypeid != "" && denytypeid != null && denytypeid != "all") {
			if (parameter == "") {
				parameter = "denytype=" + denytypeid; 
			} else {
				parameter += "&denytype=" + denytypeid; 
			}
			has_val = true;
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
		var num = $("#numid").val();
		if (num != "" && num != null) {
			if (parameter == "") {
				parameter = "num=" + num; 
			} else {
				parameter += "&num=" + num; 
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
		if (gonum == "no") {
			parameter += "&mode=" + "search"; 
		}
		if (type == "click") {
			return parameter;
		} else {
			return has_val;
		}
	}
	$("#resetcond").click(function(){
		$("#srcIPid, #sendtime1id, #sendtime2id").val("");
		$("#denytypeid").val("all");
	})
});
/* function opinfo () {
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
	var content = content = "您将要删除所选中的条目，是否继续？";
	var oper = 5;
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
} */
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">系统监控</a> 
	<a href="/systemmonitor/denyaccess" class="current">连接日志</a> 
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
					来源IP
					</td>
					<td>
					 <input type="text" value="{%$srcIP%}" name="srcIP" id="srcIPid"/>
					</td>
					<td style="width:10%">
					类型
					</td>
					<td>
					<select name="denytype" id="denytypeid" style="width:213px;font-size:13px;">
					  <option value="all" selected>全部</option>
					  <option value="0" {%if $denytype == '0'%}selected{%/if%}>正常</option>
					  <option value="1" {%if $denytype == '1'%}selected{%/if%}>异常</option>
					</select>
					</td>
               </tr>
               <tr>
					<td style="width:7%" class="searchcondition">
					操作时间
					</td>
					<td colspan="3" class="searchcondition">
					<div style="border:0;padding:0;margin-right:5.4%" class="input-append date form_datetime"><input style="width:230px;" type="text" value="{%$sendtime1%}" name="sendtime1" id="sendtime1id" /><span class="add-on"><i class="icon-calendar"></i></span></div>至
					<div style="border:0;padding:0;margin-left:5.8%;margin-right:2%" class="input-append date form_datetime"><input style="width:230px;" type="text" value="{%$sendtime2%}" name="sendtime2" id="sendtime2id" /><span class="add-on"><i class="icon-calendar"></i></span></div>
					
					<a href="#" class="btn  btn-mini btn-primary" id="searchbtn">查询</a>
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
				<h5>连接日志列表</h5>
			</div>
			<div class="widget-content nopadding">
				<table width="80%" align="center"  class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="text-align:center;width:5%">序号</th>
							<th style="text-align:center;width:16%">操作时间</th>
							<th style="text-align:center;width:15%">来源IP</th>
							<th style="text-align:center;width:10%" >类型</th>
							<th style="text-align:center">日志</th>
						</tr>
					</thead>
					<tbody>
					 <form name="listform" method="post" action="">
					{%$num = 1%}
					{%section name=loop loop=$infos%}
						<tr>
							<td style="text-align:center">{%$num++%}</td>
							<td style="text-align:center">
							<a href="#mail{%$infos[loop].id%}" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal" style="cursor:default">							
							{%$infos[loop].logtime%}
							</a>
							</td>
							<td style="text-align:center">
							<a href="#mail{%$infos[loop].id%}" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal" style="cursor:default">							
							{%$infos[loop].srcIP%}
							</a>
							</td>
							<td style="text-align:center">
							<a href="#" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal" style="cursor:default">
							{%if $infos[loop].type == 1%}
							异常
							{%else%}
							正常
							{%/if%}
							</a>
							</td>
							<td style="text-align:center;word-wrap:break-word;word-break:break-all;width:500px">
							<a href="#" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal" style="cursor:default">{%$infos[loop].log|replace:"<":"&lt;"|replace:">":"&gt;"%}</a>
							</td>
						</tr>
					{%/section%}
					<input type="hidden" value="" id="checklist" name="infolist" />
			  </form>
					</tbody>
					<tr class="odd hasnodata" id="hasnodata">
						<td class="dataTables_empty" align="center" valign="top" colspan="5">
							没有找到任何数据
						</td>
					</tr>
				</table>
			</div>
			<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
				<div class="row-fluid" style="text-align:right; margin-top: 0px;">
					<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="margin-top: 5px;">
						<div id="" class="" style="float:left;margin-left:10px;">
						<!--<input style="margin-top: 2px;font-size:12px;width: 48px;" value="批量删除" class="btn" id="delinfo" onclick="javascript:void(opinfo())" />-->
						</div>
						<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;float:left;margin-left:25%;">
							{%$page%}
						</div>
						<div id="DataTables_Table_0_length" class="dataTables_length" style="float:right;width:100px;margin-right:18px;">
							<label style="height:30px;">
								<form action="/systemmonitor/denyaccess" method="GET" style="width:106px">
									<select name="num" size="1" aria-controls="DataTables_Table_0" style="width:60px">
										<option {%if $anum==5 %}selected="selected"{%/if%} value="5">5</option>
										<option {%if $anum==10 %}selected="selected"{%/if%} value="10">10</option>
										<option {%if $anum==25 %}selected="selected"{%/if%} value="25">25</option>
										<option {%if $anum==50 %}selected="selected"{%/if%} value="50">50</option>
										<option {%if $anum==100 %}selected="selected"{%/if%} value="100">100</option>
									</select>
									<input type="submit" value="GO" style="font-size:12px;height:26px;" />
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