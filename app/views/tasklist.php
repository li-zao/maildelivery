{%include file="header.php"%}
<style>
    .table th, .table td {
        text-align: center;
    }
	.draft_box_left{
		text-align: center;
	}
	.draft_box_td{
		text-align: left;
	}
</style>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 投递任务管理</a><a href="#" class="current">任务列表</a></div>
  </div>
  <div class="container-fluid">
		<!--{%if $search != "" ||  $task_name != "" || $subject != '' || $username != '' || $cid != '' || $stattime != '' || $lasttime != '' || $status != '' %}
		 <input id="s_data" type="hidden" value="yes" name="s_data">
		{%else%}
		 <input id="s_data" type="hidden" value="" name="s_data">
		{%/if%}	-->
	  	<div href="#collapseG3" id="quicknav" data-toggle="collapse" class="widget-title bg_lo" style="border-bottom:1px dotted #cdcdcd;">
			<h5 style="float: right;">检索条件
			<i class="icon-chevron-down"></i>
			</h5>
	  	</div>
	 	<p></p>
		<div id="dis" style="display:none;">
	  		<form id="searchform" action="" method="get" >
	  			<div style="height:35px;">
		  			<div style="float:left;">
		  			任务名称：
						{%if $task_name != ""%}	
							<input type="text" name="taskname" id="taskname" value="{%$task_name%}" placeholder="请输入任务名称" style="height: 22px; line-height: 25px; padding: 2px 0px 2px 2px; width:230px;font-size: 12px;">
						{%else%}
							<input type="text" name="taskname" id="taskname" placeholder="请输入任务名称" style="height: 22px; line-height: 25px; padding: 2px 0px 2px 2px; width:230px;font-size: 12px;">
						{%/if%}	
					</div>
					<div style="float:left;margin-left:10px;">
					邮件主题：
							<input type="text" name="subject" id="subject" {%if $subject%}value="{%$subject%}"{%/if%} placeholder="请输入任务主题" style="height: 22px; line-height: 25px; padding: 2px 0px 2px 2px; width:230px;font-size: 12px;" />
					</div>
					<div style="float:left;margin-left:10px;">
					创建人：
							<input type="text" name="uname" id="uname" {%if $username%}value="{%$username%}"{%/if%} placeholder="请输入任务创建人" style="height: 22px; line-height: 25px; padding: 2px 0px 2px 2px; width: 160px;font-size: 12px;" />
					</div>
					<div style="float:left;margin-left:10px;">
					分类：
						<select name="cid" id="cid" style="width: 170px; height: 28px; line-height: 25px; padding: 4px 0px 2px; font-size: 12px;">
							<option value="0">请选择分类</option>
							{%section name=loop loop=$catdata%}
								{%if $catdata[loop].id == $cid%}
									<option value="{%$catdata[loop].id%}" selected="selected">{%$catdata[loop].vocation_name%}</option>
								{%else%}
									<option value="{%$catdata[loop].id%}">{%$catdata[loop].vocation_name%}</option>
								{%/if%}
							{%/section%}
						</select>
					</div>
				</div>
				<div style="height:35px;">
					<div style="float:left;">
						<!-- 创建时间：
							<div class="input-append date form_datetime">
								{%if $stattime != ""%}	
									<input size="16" id="create_time_s" type="text" name="stattime" value="{%$stattime%}" style="height: 22px; line-height: 25px;width: 235px;padding: 2px 0px\9;">
								{%else%}
									<input size="16" id="create_time_s" type="text" name="stattime" value="" style="height: 22px; line-height: 25px;  width: 235px;padding: 2px 0px\9;">
								{%/if%}
									<span class="add-on" style="padding-bottom: 2px; height: 19px; padding-top: 5px;"><i class="icon-calendar"></i></span>
							</div>&nbsp;--
							<div class="input-append date form_datetime">
								{%if $lasttime != ""%}	
									<input id="create_time_e" name="lasttime" size="16" type="text" value="{%$lasttime%}" style="height: 22px; line-height: 25px;width: 235px;padding: 2px 0px\9;">
								{%else%}
									<input id="create_time_e" name="lasttime" size="16" type="text" value="" style="height: 22px; line-height: 25px;width: 235px;padding: 2px 0px\9;"> 
								{%/if%}
									<span class="add-on" style="padding-bottom: 2px; height: 19px; padding-top: 5px;"><i class="icon-calendar"></i></span>
							</div> -->
							队列时间：
							<select name="settime" id="settimeid" style="width: 100px; height: 28px; line-height: 28px; padding: 4px 0px 2px; font-size: 12px;" onchange="changetimescople(this.value)">
								<option value="day" selected >今天</option>
								<option value="month" {%if $settime == 'month'%}selected{%/if%}>本月</option>
								<option value="custom" {%if $settime == 'custom'%}selected{%/if%}>自定义</option>
							</select>
							<span id="customtime" style="display:none">
								<span style="margin-left: 5px;margin-right: 1px;">从</span>
								<div class="input-append date form_datetime"><input type="text" style="width:160px;" value="{%$sendtime1%}" name="sendtime1" id="sendtime1id" /><span class="add-on"><i class="icon-calendar"></i></span></div>
								<span style="margin-left: 10px;">至</span>
								<div class="input-append date form_datetime" style="margin-left: 1px;"><input type="text" style="width:160px;" value="{%$sendtime2%}" name="sendtime2" id="sendtime2id" /><span class="add-on"><i class="icon-calendar"></i></span></div>
							</span>
					</div>	
					<div style="float:right;margin-right:5px;">
					<button id="searchtype" class="btn" style="margin-right: 35px; height: 28px; font-size:12px;"><i class="icon-zoom-in"></i>&nbsp;搜索</button>
					<a id="resetsearch" class="btn" style="margin-right:24px; height: 18px;font-size:12px;width: 38px;">重置</a>
					</div>
					<div style="float:right;margin-right:52px;">
					状态：
						<select name="status" id="statusid" style="width: 165px; height: 28px; line-height: 28px; padding: 4px 0px 2px; font-size: 12px;">
							<option value="all">请选择状态</option>
							{%foreach from=$statusdata key=k item=val%}
								{%if $k == $status%}
									<option value="{%$k%}" selected="selected">{%$val%}</option>
								{%else%}	
									<option value="{%$k%}" >{%$val%}</option>
								{%/if%}
							{%/foreach%}	
						</select>
					</div>
					
				</div>
				</div>
				<input type="hidden" value="{%$anum%}" name="num" id="numid" />
				<input type="hidden" value="{%$curpage%}" name="page" id="pageid" />
			</form>
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"><i class="icon-inbox"></i></span>
						<h5>任务列表</h5>
					</div>
					<div class="widget-content" style="padding: 0px;">
						<div>
							<table class="table table-bordered table-striped" style="border: 0px solid #e4e4e4;border-collapse:separate;margin-bottom: 0px;">
								<thead>
									<tr>
										<th style="text-align:center;"><input type="checkbox" id="checkAll">&nbsp;&nbsp;全选</th>
										<th style="width:20%;">任务名称</th>
										<th style="width:20%;">邮件主题</th>
										<th style="width:10%;">创建人</th>
										<th style="width:10%;">投递用户数</th>
										<th style="width:8%;">状态</th>
										<th style="width:15%;">创建时间</th>
										<th style="width:25%;">操作</th>
									</tr>
								</thead>
								<tbody>
								{%section name=loop loop=$taskdata%}
									<tr id="list_{%$taskdata[loop].id%}">
										<td style="text-align:center"><input type="checkbox" name="checktask[]" value="{%$taskdata[loop].id%}" /></td>
										<td style="text-align:left;"><a href="#mail{%$taskdata[loop].id%}" name="mail{%$taskdata[loop].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo(this)" title="{%$taskdata[loop].task_name%}">{%$taskdata[loop].task_name%}</a></td>
										<td style="text-align:left;"><a title="{%$taskdata[loop].subject%}">{%$taskdata[loop].subject|truncate:20:"..."%}</a></td>
										{%if $taskdata[loop].username == "" %}
											<td>创建人已删除</td>
										{%else%}
											<td>{%$taskdata[loop].username%}</td>
										{%/if%}
										<td>{%$taskdata[loop].total%}</td>
										<td><a href="javascript:void(0);" class="list_show">{%$taskdata[loop].taskstatus%}</a></td>
										<td>{%$taskdata[loop].createtime%}</td>
										<td style="text-align:center;padding-left:8px;">
											{%if $taskdata[loop].status == 6 %}
												{%if $taskdata[loop].role == 'stasker' || $role == 'tasker'%}
													<a href="/task/edittask/id/{%$taskdata[loop].id%}" class="edittask"><i class="icon-edit"></i>&nbsp;编辑&nbsp;&nbsp;&nbsp;</a>
												{%else%}
													<a href="/task/viewtask/id/{%$taskdata[loop].id%}" class="viewtask"><i class="icon-zoom-in"></i>&nbsp;查看&nbsp;&nbsp;&nbsp;</a>
												{%/if%}
												<a  href="javascript:void(0);" class="deltask"><i class="icon-remove"></i>&nbsp;删除</a>
											{%else%}
												<a href="/task/viewtask/id/{%$taskdata[loop].id%}" class="viewtask"><i class="icon-zoom-in"></i>&nbsp;查看&nbsp;&nbsp;&nbsp;</a>
												{%if $taskdata[loop].status == 1%}
													{%if $role == 'stasker' || $role == 'tasker'%}
														<a  href="#{%$taskdata[loop].id%}" class="checkup" role="button" data-toggle="modal"><i class="icon-check-empty"></i>&nbsp;待审核</a>
													{%else%}
														<a  href="javascript:void(0);" class="checkin"><i class="icon-ban-circle"></i>&nbsp;审核中</a>
													{%/if%}
												{%elseif $taskdata[loop].status == 2 && $taskdata[loop].checkpass == 3%}
													<a  href="javascript:void(0);" class="checkout"><i class="icon-check"></i>&nbsp;不审核</a>
												{%elseif $taskdata[loop].status == 2%}
													<a  href="javascript:void(0);" class="checkout"><i class="icon-check"></i>&nbsp;已审核</a>
												{%else%}
													<a href="/statistics/singletask/id/{%$taskdata[loop].id%}" class="generaltask"><i class="icon-bar-chart"></i>&nbsp;报告</a>
												{%/if%}
											{%/if%}
										</td>
									</tr>
										<div id="{%$taskdata[loop].id%}" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:38%;text-align:left">
											<form id="formcheck" action="" method="post" class="">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">审核任务：{%$taskdata[loop].task_name%}</h3>
												</div>
												<div class="modal-body">					
													<p style="margin-top: 20px;">	
														<label for="inputname" style="display: inline;font-weight:bold;padding-left: 30px;">任务名称：</label>
														<b>{%$taskdata[loop].task_name%}</b>	
													</p>
													<p>
													<label for="inputname" style="display: inline;font-weight:bold;padding-left: 30px;">审&nbsp;&nbsp;核：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
													<input type="radio" name="checkpass" value="1"> 通过 &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="checkpass" value="2"> 不通过</p>
													<p style="margin-top: 15px; margin-bottom: 20px;">
														<label for="inputcon" style="display: inline;font-weight:bold;padding-left: 30px;">审核原因：</label>
														<textarea id="checkinfo_{%$taskdata[loop].id%}" value="" style="padding: 5px; width: 280px;" rows="5" cols="10" name="checkinfo"></textarea>
													</p>
												</div>										
												<div class="modal-footer">
													<button type="button" class="reserve" class="btn" style="margin-right: 10px;">提交</button>
													<button type="button" type="reset" class="btn">重置</button>
												</div>
											</form>
										</div>
								{%/section%}
								<tr>
									<td style="border-left: 0px;"><input id="stop" type="button" class="btn" value="停止任务" style="margin-top: 2px;font-size:12px;" /></td>
									<td style="border-left: 0px;"><input id="open" type="button" class="btn" value="启动任务" style="margin-top: 2px;font-size:12px;margin-left:-118px;" /></td>
									<td style="border-left: 0px;"><input id="delall" type="button" class="btn" value="批量删除" style="margin-top: 2px;font-size:12px;;margin-left:-340px" /></td>
									
									<td colspan="4" style="border-left: 0px;">
										<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
											<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="margin-left: -200px;">
											{%$page%}
											</div>
										</div>
									</td>
									<td style="padding-left: 12px;padding-top: 12px;border-left: 0px;">
										<center><label style="height: 30px; width: 105px;">
											
												<select name="num" id="gonumid" size="1" style="width:60px">
													<option {%if $anum==5 %}selected="selected"{%/if%} value="5">5</option>
													<option {%if $anum==10 %}selected="selected"{%/if%} value="10">10</option>
													<option {%if $anum==25 %}selected="selected"{%/if%} value="25">25</option>
													<option {%if $anum==50 %}selected="selected"{%/if%} value="50">50</option>
													<option {%if $anum==100 %}selected="selected"{%/if%} value="100">100</option>
												</select>
												<input type="button" value="GO" id="gobtn" style="font-size:12px;height:26px;" />
												
										</label></center>
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
</div>
	<div id="showinfo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:38%;text-align:left">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">查看信息：</h3>
				</div>
				<div class="modal-body" style="font-size: 12px;">					
					<p style="margin-top: 10px;padding-left: 25px;padding-right: 18px;">	
						<label for="inputname" style="display: inline;font-size: 12px;font-weight:bold;">任务名称：</label>
						<b><span id="taskname"></span></b>	
					</p>
					<p style="padding-left: 25px;padding-right: 18px;">
						<label for="inputname" style="display: inline;font-size: 12px;font-weight:bold;">消息内容：</label>
						<span id="infocontent"></span>
					</p>
				</div>	
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
				</div>				
	</div>		

      <div  class="modal hide fade mail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 600px;">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">邮件情况</h3>
      </div>
      <div class="modal-body mailonce" >
      <p>
        <table style="width: 580px;">
           <tr><td>日志:</td></tr>
		   <tr>
            <td><div id="send_log" style="work-break:break-all;overflow:auto;margin-left:20px;"></div></td>
          </tr>
        </table> 
      </p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
      </div>
      </div>	
<!--Footer-part-->
<script type="text/javascript">
$(function(){
	 $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        autoclose: true,
        todayBtn: true,
		startDate: '1900-01-01 00:00:00',
		endDate: '2099-12-31 23:59:59',
        pickerPosition: "bottom-left"
    });
	
	//列表搜索
	var flip = 0;
	$("#quicknav").click(function(){
		$("#dis").toggle(10, function(){
			if (flip++ % 2 == 0) {
				$("#quicknav").children().children("i").attr("class",'icon-chevron-up');
			} else {
				$("#quicknav").children().children("i").attr("class",'icon-chevron-down');
			}
		});
	});
	
	var default_show = parseSearchCon("noclick", "no");
	if (default_show) {
		$("#dis").show();
		$("#quicknav").children().children("i").attr("class",'icon-chevron-up');
	}
	
	//任务列表搜索表单
	$('#searchtype').click(function(){
		var status = parseSearchCon("click", "no");
		hrefUrl(status);
	});
	
	$("#gobtn").click(function(){
		var status = parseSearchCon("click", "go");
		hrefUrl(status);
	});
	
    function hrefUrl (urlStr) {
        var url = "/task/listtask?";
        if (urlStr !== false) {
            url += urlStr;
			window.location.href = url;
        }
    }
    
	function parseSearchCon (type, gonum) {
		var parameter = "";
		var has_val = false;
		var taskname = $("#taskname").val();
		if (taskname != "" && taskname != null) {
			if (parameter == "") {
				parameter = "taskname=" + taskname; 
			} else {
				parameter += "&taskname=" + taskname; 
			}
			has_val = true;
		}
		var subject = $("#subject").val();
		if (subject != "" && subject != null) {
			if (parameter == "") {
				parameter = "subject=" + subject; 
			} else {
				parameter += "&subject=" + subject; 
			}
			has_val = true;
		}
		var username = $("#username").val();
		if (username != "" && username != null) {
			if (parameter == "") {
				parameter = "username=" + username; 
			} else {
				parameter += "&username=" + username; 
			}
			has_val = true;
		}
		var cid = $("#cid").val();
		if (cid != "" && cid != null && cid != '0') {
			if (parameter == "") {
				parameter = "cid=" + cid; 
			} else {
				parameter += "&cid=" + cid; 
			}
			has_val = true;
		}
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
			var num = $("#numid").val();
			if (num != "" && num != null) {
				if (parameter == "") {
					parameter = "num=" + num; 
				} else {
					parameter += "&num=" + num; 
				}
				parameter += "&mode=" + "search"; 
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
	
	//清空搜索表单
	$("#resetsearch").click(function(){
		$("#taskname, #subject, #username, #cid, #settimeid, #sendtime1id, #sendtime2id").val("");
		$("#statusid").val("all");
	});
});	
	function changetimescople (val) {
		if (val == "custom") {
			$("#customtime").show();
		} else {
			$("#customtime").hide();
		}
	}
	$("#checkAll").click(function () {
		if ($(this).attr("checked")) { // 全选
			$("input[name='checktask[]']").each(function () {
			$(this).attr("checked", true);
			});
		}
		else { // 取消全选
			$("input[name='checktask[]']").each(function () {
			$(this).attr("checked", false);
			});
		}
	});
	
	//批量删除
	$("#delall").click(function () {
		var checktask = $("input[name='checktask[]']:checked");
			var tasks = "";
			$.each(checktask,function(i,result){
				tasks += $(this).val()+",";
			})
			if(tasks == ""){
				art.dialog.alert("请选择批量删除的任务");
				return false;
			}
			art.dialog.confirm('确定删除该任务？', function () {	
				$.post('/task/deltask',{'tids':tasks},function(data){
					//alert(typeof data)
					if(data == "ok"){
						location.reload();
					}
				})
			})
	})
	
	//停止任务
	$("#stop").click(function () {
		var checktask = $("input[name='checktask[]']:checked");
			var tasks = "";
			$.each(checktask,function(i,result){
				tasks += $(this).val()+",";
			})
			if(tasks == ""){
				art.dialog.alert("请选择要停止的任务");
				return false;
			}
			art.dialog.confirm('确定停止该任务？', function () {		
				$.post('/task/stop',{'tids':tasks},function(data){
					if(data == "ok"){
						location.reload();
					}
				})
			})
	})
	
	//启动任务
	$("#open").click(function () {
		var checktask = $("input[name='checktask[]']:checked");
			var tasks = "";
			$.each(checktask,function(i,result){
				tasks += $(this).val()+",";
			})
			if(tasks == ""){
				art.dialog.alert("请选择要启动的任务");
				return false;
			}
			art.dialog.confirm('确定启动该任务？', function () {		
				$.post('/task/open',{'tids':tasks},function(data){
					if(data == "ok"){
						location.reload();
					}
				})
			})
	})
	
/* 	//查看任务
	$(function(){
		$('.viewtask').on('click',function(){
				var tid=$(this).parent().parent().attr('id').substr(5);
				location.href="/task/viewtask/id/"+tid;
		})	
	}) 	
	
	//任务报告
	$(function(){
		$('.generaltask').on('click',function(){
				var tid=$(this).parent().parent().attr('id').substr(5);
				location.href="/statistics/singletask/id/"+tid;
		})	
	}) 
	
	//编辑任务
	$(function(){
		$('.edittask').on('click',function(){
				var tid=$(this).parent().parent().attr('id').substr(5);
				location.href="/task/edittask/id/"+tid;
		})	
	})  */
	
	//删除任务
	$(function(){
		$('.deltask').on('click',function(){
				var tid=$(this).parent().parent().attr('id');
				art.dialog.confirm('确定删除该任务？', function () {
					$.post('/task/deltask',{'tid':tid},function(data){
						if(data == "1"){
							location.reload();
						}
					});
				})
		})	
	})

	//审核
	$('.reserve').on('click',function(){
		var tid=$(this).parent().parent().attr("id");
		var checkpass=$('input[name="checkpass"]:checked').val();
		var checkinfo=$('#checkinfo_'+tid).val();
		if(checkpass == null){
			$('#'+tid).modal('hide');
			art.dialog.alert('审核选项不能为空',function(){$('#'+tid).modal('show');});
			return false;
		}
		if(checkpass == 2 && checkinfo == ""){
			$('#'+tid).modal('hide');
			art.dialog.alert('内容不能为空',function(){$('#'+tid).modal('show');});
			return false;			
		}
		
		$.post('/task/audittask',{'tid':tid,'checkpass':checkpass,'checkinfo':checkinfo},function(data){
            if(data == 'ok'){
				$('#'+tid).modal('hide');
				if(checkpass == 1){
					art.dialog.alert('审核完成，请等待发送!',function(){$('#'+tid).modal('show');});
				}else{
					art.dialog.alert('该任务没有通过审核，请查看!',function(){$('#'+tid).modal('show');});
				}
				location.reload();
			}
		});
	});
	
	$('.list_show').on('click',function(){
		var tid=$(this).parent().parent().attr('id').substr(5);
		//alert(tid);
		$.post('/task/auditinfo',{'tid':tid},function(data){
				var o = eval("("+data+")");
				if(o.status == '5' || o.status == '7' || o.status == '8'){
					location.href="/statistics/singletask/id/"+tid;
				}
				if(o.status == '2' || o.status == '6'){
					var str='';
					$("#taskname").html(o.task_name);
					if(o.checkpass == '1'){
						var str = '恭喜您，任务名为：' + o.task_name + ' 的任务已经通过了管理员的审核，很快会为您发送该任务！';
						$("#infocontent").html(str);
					}else if(o.checkpass == '3'){
						var str = '您好，任务名为：' + o.task_name + ' 的任务正在等待发送，很快会为您发送该任务！';
						$("#infocontent").html(str);
					}else{
						var str = '对不起，任务（' + o.task_name + ' ）未通过审核，未通过原因：' + o.checkinfo;
						$("#infocontent").html(str);
					}
					$('#showinfo').modal('show');	
				}
		});
		
	});
	
	function mailsinfo(obj){
        var mailsid = obj.name;
        var mailsinfoid = obj.parentNode.parentNode
        var mailval = $(mailsinfoid).find('input').val();
        $('.mail').attr('id',mailsid);
        $('.mailonce').find('p').css({'line-height':'20px',"margin":'0','word-break':'break-all'});
        $.post('/task/checkbuffer',{'tid':mailval},function(data){
		//	alert(data);exit;
            if(data){
              $('#send_log').html(data);
            }else{
				$('#send_log').html('任务正在发送中');
			} 
		},"json");
    }
  
</script>  
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />
{%include file="footer.php"%}