{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	$(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd HH:ii:ss",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"
    });
	
	$("#searchbtn").click(function(){
		var url = "/setting/accesslog?";
		var status = parseSearchCon("click");
		if (status !== false) {
			url += status;
			window.location.href = url;
		}
		window.location.href = url;
	});
	$("#resetcond").click(function(){
		$("#ipid, #subjectid, #descriptionid, #starttimeid, #endtimeid").val("");
	});
	$("#gobtn").click(function(){
		var url = "/setting/accesslog?&style=go";
		var status = parseSearchCon("click");
		if (status !== false) {
			url += status;
			window.location.href = url;
		}
		window.location.href = url;
	});
	function parseSearchCon (type) {
		var num = $('#gonumid').val();
		if( num != ""){
			url = "&num=" + num + "&";
		}
		var ip_patt = /^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/;
		var ip = $("#ipid").val();
		if (ip != "" && ip != null) {
			if (!ip_patt.test(ip)) { 
				art.dialog.alert("操作IP输入格式有误！");
				return false;
	        }
			url += "ip=" + ip + "&";
		}
		var userid = $("#userid").val();
		if (userid != "" && userid != null) {
			url += "userid=" + userid + "&";
		}
		var subject = $("#subjectid").val();
		if (subject != "" && subject != null) {
			url += "subject=" + subject + "&";
		}
		var description = $("#descriptionid").val();
		if (description != "" && description != null) {
			url += "description=" + description + "&";
		}
		var starttime = $("#starttimeid").val();
		if (starttime != "" && starttime != null) {
			url += "starttime=" + starttime + "&";
		}
		var endtime = $("#endtimeid").val();
		if (endtime != "" && endtime != null) {
			url += "endtime=" + endtime + "&";
		}
		if (type == "click") {
			return url;
		} 
	}
});
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">账户管理</a> 
	<a href="/setting/deliveryparam" class="current">管理员&nbsp;{%$curuser.username%}&nbsp;操作日志</a> 
	</div>
  </div>
  <div class="container-fluid">
          <div id="searchcondtable">
			<h6>&nbsp;</h6>
            <form name="deliveryparam" id="auth-form" method="get">
			<table class="table table-bordered">
			  <tbody>
			   <tr class="searchcondition">
					<td style="width:10%">
					操作IP
					</td>
					<td>
					 <input type="text" value="{%$ip%}" name="ip" id="ipid" />
					</td>
					<td style="width:10%">
					主题
					</td>
					<td>
					<input type="text" value="{%$subject%}" name="subject" id="subjectid" />
					</td>
					<td style="width:10%">
					描述
					</td>
					<td>
					 <input type="text" value="{%$description%}" name="description" id="descriptionid" />
					</td>
               </tr>
               <tr>
					<td style="width:10%" class="searchcondition">
					操作时间范围
					</td>
					<td colspan="3" class="searchcondition">
					<div class="input-append date form_datetime"><input type="text" value="{%$starttime%}" name="starttime" id="starttimeid" /><span class="add-on"><i class="icon-calendar"></i></span></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;至&nbsp;&nbsp;&nbsp;&nbsp;
					<div class="input-append date form_datetime"><input type="text" value="{%$endtime%}" name="endtime" id="endtimeid" /><span class="add-on"><i class="icon-calendar"></i></span></div>
					</td>
					<td colspan="2">
					<center>
					<a href="#" class="btn" id="searchbtn" style="height:18px;margin-left:5px;font-size:12px;"><i class="icon-zoom-in"></i>&nbsp;搜索</a>
					<a href="#" class="btn" id="resetcond" style="height:18px;margin-left:5px;font-size:12px;width: 38px;">重置</a>
					</center>
					</td>
               </tr>
			   	<input type="hidden" value="{%$anum%}" name="num" id="num2" />
              </tbody>
            </table>
			<input type="hidden" value="{%$num%}" name="num" id="numid" />
			<input type="hidden" value="{%$userid%}" name="userid" id="userid" />
			<input type="hidden" value="{%$curpage%}" name="page" id="pageid" />
			</form>
          </div>
	
	<div class="row-fluid">
      <div class="span12">
		<div class="widget-box" style="padding-bottom: 10px;border-bottom: 1px solid #cdcdcd;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-tasks"></i>
				</span>
				<h5>日志详细</h5>
			</div>
			<div class="widget-content nopadding">
				<table width="80%" align="center"  class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="5%" style="text-align:center">操作时间</th>
							<th width="5%" style="text-align:center">IP</th>
							<th style="text-align:center" width="10%">主题</th>
							<th width="15%" style="text-align:center">描述</th>
						</tr>
					</thead>
					<tbody>
					{%section name=loop loop=$infos%}
						<tr>
							<td style="text-align:center">
							{%$infos[loop].accesstime%}
							</td>
							<td style="text-align:center">
							{%$infos[loop].ip%}
							</td>
							<td style="text-align:center" title="{%$infos[loop].subject%}">
							{%$infos[loop].subject|truncate:30:"...":true%}
							</td>
							<td style="text-align:center" title="{%$infos[loop].description%}">
							{%$infos[loop].description|truncate:30:"...":true%}
							</td>
						</tr>
					{%/section%}
					</tbody>
					<tr class="odd hasnodata" id="hasnodata">
					<td class="dataTables_empty" align="center" valign="top" colspan="5">
						没有找到任何数据
					</td>
			  </tr>
				</table>
			</div>
			<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<form action="/contact/edit" id="myform2" method="post">
					<div class="modal-header">
						<button type="button" class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
						<h3 id="myModalLabel" class="text-left">编辑联系人组</h3>
					</div>
					<div class="modal-body">
						<p> </p>
						<table>
							<tr height="40px">
								<td>组名称：</td>
								<td><input id="gn" type="text" name="gname"/><span style="color:red;">*</span></td>
							</tr>
							<tr>
								<td id="prompt2" colspan="2" >组名称不能与已有组重复</td>
								<input type="hidden" id="hi" name="id" value="123" />
							</tr>
							<tr>
								<td>组描述：</td>
								<td>
									<textarea name="remark" id="re"></textarea>
								</td>
							</tr>
						</table>
						<p> </p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" id="edit" value="保存" />
					</div>
				</form>
			</div>
			<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
				<div class="row-fluid" style="text-align:right; margin-top: 0px;">
					<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="margin-top: 5px;">
						<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;float:left;margin-left:30%;">
							{%$page%}
						</div>
						<div id="DataTables_Table_0_length" class="dataTables_length" style="float:right;width:100px;margin-right:18px;">
							<label style="height:30px;">
								<form method="GET" style="width:106px">
									<input type="hidden" value="{%$uid%}" name="userid" id="userid"/>
									<select name="num" id="gonumid" size="1" aria-controls="DataTables_Table_0" style="width:60px">
										<option {%if $anum==5 %}selected="selected"{%/if%} value="5">5</option>
										<option {%if $anum==10 %}selected="selected"{%/if%} value="10">10</option>
										<option {%if $anum==25 %}selected="selected"{%/if%} value="25">25</option>
										<option {%if $anum==50 %}selected="selected"{%/if%} value="50">50</option>
										<option {%if $anum==100 %}selected="selected"{%/if%} value="100">100</option>
									</select>
									<input type="button" value="GO" id="gobtn" style="font-size:12px;height:26px;" />
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
