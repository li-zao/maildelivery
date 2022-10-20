{%include file="header.php"%}
<script type="text/javascript" src="/dist/js/jPages.js"></script>
<style type="text/css">
  .col{color:red;}
  .holder {
    margin: 5px 0;
  }
    .holder a {
	background: none repeat scroll 0 0 #F5F5F5;
    border-color: #ddd;
    border-style: solid;
    border-width: 1px;
    color: #333333;
    display: inline-block;
    font-size: 12px;
    line-height: 16px;
    padding: 4px 10px !important;
    text-shadow: 0 1px 0 #FFFFFF;
	margin: 0 0px 0 0px;
  }
  .holder a:hover {
    background-color: #E8E8E8;
    color: #222222;
  }
  .holder a.jp-first {border-radius: 4px 0 0 4px;}
  .holder a.jp-previous { margin-right: 0px; }
  .holder a.jp-current, a.jp-current:hover {
	background-color: #26B779;color: #FFFFFF;
	width: 20px;
    font-weight: bold;
	text-align:center;
	margin-right: 0px;
  }
  .holder a.jp-last {border-radius: 0 4px 4px 0;margin-left: 0px;}
  .holder a.jp-current, a.jp-current:hover,
  .holder a.jp-disabled, a.jp-disabled:hover {
    cursor: default;
    
  }
 
  .holder span { margin: 0 5px; }
  </style>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 投递任务管理</a><a href="#" class="current">创建向导任务</a></div>
  </div>
  <div class="container-fluid">
   		{%if $search != "" || $gname != "" || $remark != '' || $createtime != ''%}
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
			<div style="">
				<form action="/task/create" method="get" id="searchform" onkeydown="if(event.keyCode==13){return false;}">
					<input type="hidden" name="search" value="search"/>
					组名称：<input type="text" id="gname" name="gname" value="{%$gname%}" placeholder="请输入组名称" style="width:230px;height: 22px; line-height: 25px; padding: 2px 0px 2px 2px;margin-right: 5px;font-size: 12px;" />
					组描述：<input type="text" id="remark" name="remark" value="{%$remark%}" placeholder="请输入组描述" style="width:230px;height: 22px; line-height: 25px; padding: 2px 0px 2px 2px;margin-right: 5px;font-size: 12px;"/>
					创建时间：<a class="input-append date form_date" style="margin-right: 5px;"><input id="createtime" name="createtime" size="16" type="text" style="width: 196px; height: 22px; line-height: 25px; padding: 2px 3px; font-size: 12px;" value="{%$createtime%}" placeholder="请输入创建时间"><span class="add-on" style="padding: 5px 2px 2px; height: 21px; padding-bottom: 0px;"><i class="icon-th"></i></span></a>
					<button id="searchtype" class="btn" style="height: 28px;margin-left:5px;font-size:12px;"><i class="icon-zoom-in"></i>&nbsp;搜索</button><a id="resetsearch" class="btn" style="margin-left:5px; height: 18px;font-size:12px;width: 38px;">重置</a>
					<input type="hidden" value="{%$pagenum%}" name="pagenum" id="num2" />
				</form>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"><i class="icon-inbox"></i></span>
						<h5>选择联系人组</h5>
					</div>
					<div class="widget-content nopadding">
						<form id="formId" action="/task/inserttask" method="post">
						<input id="act" type="hidden" value="save_draft" name="act">
						<input type="hidden" value="one" name="step">
						<input type="hidden" value="{%$confirm%}" name="old_step">
						<input id="tid" type="hidden" value="{%$tid%}" name="tid">
						<input id="pagenum" type="hidden" value="{%$pagenum%}" name="pagenum">
						<table width="80%" align="center"  class="table table-bordered table-striped" style="margin-bottom: 0px;">
							<thead>
								<tr>
								{%if !empty($gid) && $gid == 'all'%}
									<th width="8%" style="text-align:center"><input type="checkbox" name="gnames[]" id="checkAll" value="all" checked=checked>&nbsp;&nbsp;全选</th>
								{%else%}
									<th width="8%" style="text-align:center"><input type="checkbox" name="gnames[]" id="checkAll" value="all">&nbsp;&nbsp;全选</th>
								{%/if%}
									<th width="20%" style="text-align:center">名称</th>
									<th width="15%" style="text-align:center">成员数量</th>
									<th width="25%" style="text-align:center" width="20%">组描述</th>
									<th style="text-align:center">创建时间</th>
								</tr>
							</thead>
							<tbody id="itemContainer">
								{%section name=loop loop=$data%}
									{%if !empty($gid) && $gid == 'all'%}
										<tr>
												<td style="text-align:center">
													<input type="checkbox" name="groupnames[]" checked=checked value="{%$data[loop].id%}"> 
												</td>
												<td style="text-align:center">{%$data[loop].gname%}</td>
												<td style="text-align:center">{%$data[loop].person_num%}</td>
												<td style="text-align:center">{%if $data[loop].remark == ''%}&nbsp;&nbsp;{%else%}{%$data[loop].remark%}{%/if%}</td>
												<td style="text-align:center">{%$data[loop].createtime%}</td>
										</tr>
									{%else%}
										{%if !empty($arrgroups) && in_array($data[loop].id,$arrgroups)%}
											<tr>
												<td style="text-align:center">
													<input type="checkbox" name="groupnames[]" checked=checked value="{%$data[loop].id%}"> 
												</td>
												<td style="text-align:center">{%$data[loop].gname%}</td>
												<td style="text-align:center">{%$data[loop].person_num%}</td>
												<td style="text-align:center">{%if $data[loop].remark == ''%}&nbsp;&nbsp;{%else%}{%$data[loop].remark%}{%/if%}</td>
												<td style="text-align:center">{%$data[loop].createtime%}</td>
											</tr>
										{%else%}
											<tr>
												<td style="text-align:center">
													<input type="checkbox" name="groupnames[]"  value="{%$data[loop].id%}"> 
												</td>
												<td style="text-align:center">{%$data[loop].gname%}</td>
												<td style="text-align:center">{%$data[loop].person_num%}</td>
												<td style="text-align:center">{%if $data[loop].remark == ''%}&nbsp;&nbsp;{%else%}{%$data[loop].remark%}{%/if%}</td>
												<td style="text-align:center">{%$data[loop].createtime%}</td>
											</tr>
										{%/if%}
									{%/if%}
								{%/section%}
							<tbody>
							<tr>
								<td valign="bottom" height="30">
										<select name="fid" id="f_id" style="width:170px;border:1px solid #BFBFBF;height:25px;line-height: 25px;padding-left:5px;">
											<option value="">[选择一个筛选器]</option>
											{%section name=loop loop=$filterdata%}
												{%if $filterdata[loop].id == $fid %}
													<option value="{%$filterdata[loop].id%}" selected="selected">{%$filterdata[loop].name%}</option>
												{%else%}
													<option value="{%$filterdata[loop].id%}">{%$filterdata[loop].name%}</option>
												{%/if%}
											{%/section%}
										</select>	
								</td>
								<td colspan="3" style="text-align:center;border-left: 0px;">
									<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;float:left;margin-left:20%;">
										{%$page%}
									</div>
								</td>
								</form>
								<td style="padding-left: 12px;padding-top: 12px;border-left: 0px;">
										<center><label style="height: 30px; width: 105px;">
											<form id="fpageform" method="get" style="width:106px">
												<select id="fpageselect" name="pagenum" size="1" aria-controls="DataTables_Table_0" style="width:60px">
													<option {%if $pagenum==5 %}selected="selected"{%/if%} value="5">5</option>
													<option {%if $pagenum==10 %}selected="selected"{%/if%} value="10">10</option>
													<option {%if $pagenum==25 %}selected="selected"{%/if%} value="25">25</option>
													<option {%if $pagenum==50 %}selected="selected"{%/if%} value="50">50</option>
													<option {%if $pagenum==100 %}selected="selected"{%/if%} value="100">100</option>
												</select>
												<input type="button" id="gofpage" value="GO" style="font-size:12px;height:26px;" />
											</form>		
										</label></center>
								</td>
							</tr>
							<tr>
								<td colspan="5" style="text-align: left;padding:8px 8px 8px 9px;border:0;">
								{%if $confirm == ''%}
									<button id="nextbtn" class="btn" type="button" style="width:113px;height:30px;background:url(/dist/img/next_but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;"></button>
								{%else%}
									<button type="button" id="confirm" style="width:85px;height:27px;background:url(/dist/img/but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;border-width: 0px;">保存</button>
								{%/if%}
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>

  </div>
</div>
<!--Footer-part-->
<script type="text/javascript">
$(function(){
    $(".form_date").datetimepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayBtn: true,
	    weekStart: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		startDate: '1900-01-01 00:00:00',
		endDate: '2099-12-31 23:59:59',
        pickerPosition: "bottom-left"
    });
	
	//点击button判断checkbox是否为空后提交表单
	$('#nextbtn').on('click', function(){
		checkform();
	});
	
	$("#confirm").on('click', function(){
		checkform();
	}); 
	
	function checkform(){
		var checkNum = 0;
		//判断chenckbox的值是否为空
		var str=""; 
		$('input:checkbox[name="groupnames[]"]:checked').each(function(){
			str+=$(this).val()+",";
		});
		if(str==''){
			art.dialog.alert('对不起,您没有选择收件人组');
			return false;
		}else{
			$('#formId').submit();
		} 
	}
	
	//列表搜索
	var s_data=$('#s_data').val();
	if(s_data == 'yes'){
		$("#dis").css("display","block");
	    $("#quicknav").children().children("i").attr("class",'icon-chevron-up');
	}
	
	//清空搜索表单
	$('#resetsearch').on('click',function(){
		$(':input','#searchform')
		.not(':button,:submit,:reset,:hidden')
		.val('')
		.removeAttr('checked')
		.removeAttr('selected'); 
	})	
}) 
	$(function(){
			$("#qv").click(function(){
				if($("#dis").is(":hidden")){
					$("#dis").css("display","block");
					$("#quicknav").children().children("i").attr("class",'icon-chevron-up');
				}else{
					$("#dis").css("display","none");
					$("#quicknav").children().children("i").attr("class",'icon-chevron-down');
				}
			})
	})
	
	$("#checkAll").click(function () {
		var aa=$(this).val();
		//alert(aa);
		if ($(this).attr("checked")) { // 全选
			$("input[name='groupnames[]']").each(function () {
			$(this).attr("checked", true);
			});
		}
		else { // 取消全选
			$("input[name='groupnames[]']").each(function () {
			$(this).attr("checked", false);
			});
		}
	});
	
	$("#gofpage").click(function(){
		var url = "/task/create?";
		var pagenum = $('#fpageselect').val();
		if( pagenum != ""){
			url += "&pagenum=" + pagenum;
		}
		var gname = $('#gname').val();
		if( gname != "" ){
			url += "&gname=" + gname;
		}
		var remark = $('#remark').val();
		if( remark != "" ){
			url += "&remark=" + remark;
		}
		var createtime = $('#createtime').val();
		if( createtime != "" ){
			url += "&createtime=" + createtime;
		}
		window.location.href = url;
	});
</script>
{%include file="footer.php"%}