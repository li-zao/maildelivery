{%include file="header.php"%}
<style>
	.draft_box_td{text-align:left;border:0;font-weight: bold;padding:0px;}
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
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 投递任务管理</a><a href="#" class="current">任务草稿箱</a></div>
  </div>
  <div class="container-fluid">
  		{%if $search != "" ||  $task_name != "" || $cid != '' || $stattime != '' || $lasttime != ''%}
		 <input id="s_data" type="hidden" value="yes" name="s_data">
		{%else%}
		 <input id="s_data" type="hidden" value="" name="s_data">
		{%/if%}	
  		<div id="quicknav" class="widget-title bg_lo" style="border-bottom:1px dotted #cdcdcd;" data-toggle="collapse" href="#collapseG3">
		<h5 style="float: right;" id="qv">
		检索条件
		<i class="icon-chevron-down"></i>
		</h5>
  </div>
  <p></p>
  <div id="dis" style="display:none;">
  	<form action="/task/drafttask" method="get" id="searchform" onkeydown="if(event.keyCode==13){return false;}">
	<input type="hidden" name="search" value="search"/>
	<input type="hidden" value="{%$num%}" name="num" id="num2" />
	<div style="height:45px;">
		任务名称：</td>
		{%if $task_name != ""%}	
			<input type="text" name="taskname" id="taskname" value="{%$task_name%}" placeholder="请输入任务名称" style="height: 22px; line-height: 25px; padding: 2px 0px 2px 2px;width: 182px; padding-right: 0px;font-size: 12px;">
		{%else%}
			<input type="text" name="taskname" id="taskname" placeholder="请输入任务名称" style="height: 22px; line-height: 25px; padding: 2px 0px 2px 2px;width: 182px;padding-right: 0px;font-size: 12px;">
		{%/if%}	
		&nbsp;分类：
			<select name="cid" id="cid" style="width:100px;height: 28px; font-size: 12px; line-height: 25px; padding: 4px 0px 2px;">
				<option value="0">请选择分类</option>
				{%section name=loop loop=$catdata%}
					{%if $catdata[loop].id == $cid%}
						<option value="{%$catdata[loop].id%}" selected="selected">{%$catdata[loop].vocation_name%}</option>
					{%else%}
						<option value="{%$catdata[loop].id%}">{%$catdata[loop].vocation_name%}</option>
					{%/if%}
				{%/section%}
			</select>
		&nbsp;创建时间：
			<div class="input-append date form_datetime">
				{%if $stattime != ""%}	
					<input size="16" id="create_time_s" type="text" name="stattime" value="{%$stattime%}" style="height: 22px; line-height: 25px;width: 180px;padding: 2px 0px\9;">
				{%else%}
					<input size="16" id="create_time_s" type="text" name="stattime" value="" style="height: 22px; line-height: 25px;  width: 180px;padding: 2px 0px\9;">
				{%/if%}
					<span class="add-on" style="padding-bottom: 2px; height: 19px; padding-top: 5px;"><i class="icon-calendar"></i></span>
			</div>&nbsp;--
			<div class="input-append date form_datetime">
				{%if $lasttime != ""%}	
					<input id="create_time_e" name="lasttime" size="16" type="text" value="{%$lasttime%}" style="height: 22px; line-height: 25px;  width: 180px;padding: 2px 0px\9;">
				{%else%}
					<input id="create_time_e" name="lasttime" size="16" type="text" value="" style="height: 22px; line-height: 25px;  width: 180px;padding: 2px 0px\9;"> 
				{%/if%}
					<span class="add-on" style="padding-bottom: 2px; height: 19px; padding-top: 5px;"><i class="icon-calendar"></i></span>
			</div>
			<button id="searchtype" class="btn" style="height:27px;font-size:12px;margin-left: 3px;padding:0 8px;"><i class="icon-zoom-in"></i>&nbsp;搜索</button>
			<a id="resetsearch" style="height:17px;font-size:12px;margin-left: 3px;padding-left: 8px; padding-right: 8px;width: 38px;" class="btn" >重置</a>
	</div>
	</form>
  </div>
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"><i class="icon-inbox"></i></span>
						<h5>草稿任务</h5>
					</div>
					<div class="widget-content" style="padding: 0px;">
						<div>
							<table class="table table-bordered table-striped" style="border: 0px solid #e4e4e4;border-collapse:separate;margin-bottom: 0px;">
								<thead>
									<tr>
										<th style="text-align:center;width:8%;"><input type="checkbox" id="checkAll">&nbsp;全选</td>
										<th style="width:22%;">任务名称</th>
										<th style="width:15%;">邮件主题</th>
										<th style="width:8%;">创建人</th>
										<th style="width:8%;">发送数量</th>
										<th style="width:20%;">创建时间 </th>
										<th style="width:15%;">操作</th>
									</tr>
								</thead>
								<tbody>
								{%section name=loop loop=$draftdata%}
									<tr id="draft_{%$draftdata[loop].id%}">
										<input type="hidden" value="{%$catdata[loop].id%}" name="catId">
										<td style="text-align:center"><input type="checkbox" name="checktask[]" value="{%$draftdata[loop].id%}" /></td>
										<td style="text-align:left;"><a title="{%$draftdata[loop].task_name%}">{%$draftdata[loop].task_name%}</a></td>
										<td style="text-align:left;"><a title="{%$draftdata[loop].subject%}">{%$draftdata[loop].subject|truncate:20:"..."%}</a></td>
										{%if $draftdata[loop].username == "" %}
										<td>创建人已删除</td>
										{%else%}
										<td>{%$draftdata[loop].username%}</td>
										{%/if%}
										<td>{%$draftdata[loop].total%}</td>
										<td>{%$draftdata[loop].createtime%}</td>
										<td>
										{%if $draftdata[loop].mine == 1 %}
										<a href="/task/edittask/id/{%$draftdata[loop].id%}" class="edittask"><i class="icon-edit"></i>&nbsp;编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="javascript:void(0);" class="subtask"><i class="icon-ok"></i>&nbsp;提交</a>
										{%else%}
										<i class="icon-edit" style="color:#BBBBBB"></i>&nbsp;<span style="color:#BBBBBB">编辑</span>&nbsp;&nbsp;&nbsp;&nbsp;
										<i class="icon-ok" style="color:#BBBBBB"></i>&nbsp;<span style="color:#BBBBBB">提交</span>
										{%/if%}
										<div id="{%$draftdata[loop].id%}" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:42%;text-align:left;">
											<form action="" method="post">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">复制【{%$draftdata[loop].task_name%}】为新任务</h3>
												</div>
												<div class="modal-body">
													<label for="inputname" style="display: inline;padding-left: 0px; margin-bottom: 0px;">请输入新任务名称：</label>
													<input type="text" name="task_name" value="{%$draftdata[loop].task_name%}" style="width:350px;height:30px;border:1px solid #A9A9A9;padding:0;">
												</div>										
												<div class="modal-footer">
													<button type="button" class="reserve btn btn-primary" class="btn" style="margin-right: 10px;">保存</button>
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>
											</form>
										</div><br/>
										{%if $draftdata[loop].mine == 1 %}
										<a href="#{%$draftdata[loop].id%}" role="button" class="copytask" data-toggle="modal"><i class="icon-copy"></i>&nbsp;复制</a>&nbsp;&nbsp;&nbsp;&nbsp;
										{%else%}
										<i class="icon-copy" style="color:#BBBBBB"></i>&nbsp;<span style="color:#BBBBBB">复制</span>&nbsp;&nbsp;&nbsp;&nbsp;
										{%/if%}
										<a href="javascript:void(0);" class="deltask"><i class="icon-remove"></i>&nbsp;删除</a>
										</td>
									</tr>
								{%/section%}
								<tr>
								<td style="text-align:center;"><input id="delall" type="button" class="btn" value="批量删除" style="margin-top: 2px;font-size:12px;" /></td>
								<td colspan="5" style="border-left: 0px;">
										<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
										<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
										{%$page%}
										</div>
										</div>
								</td>
								<td style="padding-left: 120px;padding-top: 12px;border-left: 0px;">
									<center><label style="height: 30px; width: 105px;">
										<form method="GET" style="width:106px">
											<select name="num" size="1" id="gonumid" style="width:60px">
												<option {%if $num==5 %}selected="selected"{%/if%} value="5">5</option>
												<option {%if $num==10 %}selected="selected"{%/if%} value="10">10</option>
												<option {%if $num==25 %}selected="selected"{%/if%} value="25">25</option>
												<option {%if $num==50 %}selected="selected"{%/if%} value="50">50</option>
												<option {%if $num==100 %}selected="selected"{%/if%} value="100">100</option>
											</select>
											<input type="button" value="GO" id="gobtn" style="font-size:12px;height:26px;" />
										</form>		
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
<!--Footer-part-->
<script type="text/javascript">
	//时间日期插件
    $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        autoclose: true,
        todayBtn: true,
		startDate: '1900-01-01 00:00:00',
		endDate: '2099-12-31 23:59:59',
        pickerPosition: "bottom-left"
    });
	
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
	
	//操作草稿任务
	$(function(){
		//复制任务
		$('.reserve').on('click',function(){
				var tid=$(this).parent().parent().parent().attr('id');
				var name=$(this).parent().siblings().children("input").val();
				//var inputname=$('#inputname').val();
				//alert(tid);
				if(name == ""){
					//alert('任务名称不能为空');
					$('#'+tid).modal('hide');
					art.dialog.alert('任务名称不能为空',function(){$('#'+tid).modal('show');});	
					return false;
				}
				$.post('/task/copytask',{'tid':tid,'task_name':name},function(data){
					//alert(data);
					if(data == "1"){
						 $('#'+tid).modal('hide');
						 art.dialog.alert("复制新任务成功！",function () {
                             location.href="/task/drafttask";
                         });
					}else if(data =="error"){
						// art.dialog.alert({content:'该任务名已经存在',opacity:0.9,z-index:9999!important});
						$('#'+tid).modal('hide')
						art.dialog.alert('该任务名已经存在',function(){$('#'+tid).modal('show');});
					} 
				}) 
		})	
	
		//提交任务
		$('.subtask').on('click',function(){
			//confirm_ = confirm('确定要提交该任务？');
			var tid=$(this).parent().parent().attr('id').substr(6);
			art.dialog.confirm('确定要提交该任务？', function () {	
				$.post('/task/subtask',{'tid':tid},function(data){
					//alert(typeof data)
					if(data == "1"){
						 art.dialog.alert("恭喜您，提交任务成功，管理员会尽快为您审核，请耐心等待。",function () {
                             location.reload();
                         });
					}else{
						 art.dialog.alert("提交失败，您的任务信息不完整，请重新填写。");
					}
				})
			})
		})	 
	
		//删除任务
		$('.deltask').on('click',function(){
			var tid=$(this).parent().parent().attr('id').substr(6);
			var fid=$(this).parent().parent().attr('id');
			var type = "draft";
			art.dialog.confirm('确定删除该任务？', function () {	
				$.post('/task/deltask',{'tid':tid,'type':type},function(data){
					//alert(typeof data)
					if(data == "1"){
						 $("#"+fid).remove();		
					}
				})
			})
			
		})	
	})
	
	//批量删除
	$("#delall").click(function () {
		var checktask = $("input[name='checktask[]']:checked");
		var type = "draft";
		var tasks = "";
		$.each(checktask,function(i,result){
			tasks += $(this).val()+",";
		})
		if(tasks == ""){
			art.dialog.alert("请选择批量删除的任务");
			return false;
		}
		art.dialog.confirm('确定删除该任务？', function () {	
			$.post('/task/deltask',{'tids':tasks,'type':type},function(data){
				//alert(typeof data)
				if(data == "ok"){
					location.reload();
				}
			})
		})
	})
	
	//列表搜索
	var s_data=$('#s_data').val();
	if(s_data == 'yes'){
		$("#dis").css("display","block");
	    $("#quicknav").children().children("i").attr("class",'icon-chevron-up');
	}
	
	//任务列表搜索表单
	$('#searchtype').on('click',function(){
		 var starttime=$("#create_time_s").val();
		 var lasttime=$("#create_time_e").val();
		 //alert(starttime);
		 //var reg = /^\d{4}-(0\d|1[0-2])-([0-2]\d|3[01])( ([01]\d|2[0-3])\:[0-5]\d\:[0-5]\d)$/;
		 var reg = /^\d{4}-(0\d|1[0-2])-([0-2]\d|3[01])( ([01]\d|2[0-3])\:[0-5]\d)$/;
		 if(starttime != ""){
			if(!reg.test(starttime)) {
				art.dialog.alert('不合法的日期格式或者日期超出限定范围,请重新输入！');
				return false;
			} 
		 } 
		 if(lasttime != ""){
			if(!reg.test(lasttime)) {
				art.dialog.alert('不合法的日期格式或者日期超出限定范围,请重新输入！');
				return false;
			}
		 }   
		$('#searchform').submit();		
	})
	//清空搜索表单
	$('#resetsearch').on('click',function(){
		$(':input','#searchform')
		.not(':button,:submit,:reset,:hidden')
		.val('')
		.removeAttr('checked')
		.removeAttr('selected'); 
	})
	
	$("#qv").click(function(){
				if($("#dis").is(":hidden")){
					$("#dis").css("display","block");
					$("#quicknav").children().children("i").attr("class",'icon-chevron-up');
				}else{
					$("#dis").css("display","none");
					$("#quicknav").children().children("i").attr("class",'icon-chevron-down');
				}
	});
	
	$("#gobtn").click(function(){
		var url = "/task/drafttask?&style=go";
		var num = $('#gonumid').val();
		if( num != ""){
			url += "&num=" + num;
		}
		var taskname = $('#taskname').val();
		if( taskname != "" ){
			url += "&taskname=" + taskname;
		}
		var cid = $('#cid').val();
		if( cid != "" ){
			url += "&cid=" + cid;
		}
		var stattime = $('#create_time_s').val();
		if( stattime != "" ){
			url += "&stattime=" + stattime;
		}
		var lasttime = $('#create_time_e').val();
		if( lasttime != "" ){
			url += "&lasttime=" + lasttime;
		}
		window.location.href = url;
	});
</script>  
{%include file="footer.php"%}
