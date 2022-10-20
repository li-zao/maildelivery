{%include file="header.php"%}
<script type="text/javascript" src="/dist/js/searchmytemplpage.js"></script>
<script type="text/javascript" src="/dist/js/vendor/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/dist/js/vendor/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="/dist/js/vendor/jquery.fileupload.js"></script>
<style>
	.draft_box_td {
		text-align: left;
		border: 0;
		font-weight: bold;
		padding: 0px;
	}

	.table th,
	.table td {
		text-align: center;
	}
</style>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"><a href="/createtem/firstpage" title="首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 邮件内容管理</a><a href="#" class="current">附件管理</a></div>
	</div>


	<div class="container-fluid">
		<!-- <div class="row-fluid"> -->
		<div id="quicknav" href="#collapseG3" data-toggle="collapse" class="widget-title bg_lo collapsed" style="border-bottom:1px dotted #cdcdcd;">
			<h5 style="float: right;"> 检索条件 <i class="icon-chevron-down"></i></h5>
		</div>
		<!-- </div> -->
		<div id="collapseG3" class="widget-content nopadding updates collapse" style="border:none;">
			<div style="padding: 10px 10px 0;font-size:12px;margin-left: 10px;">
				<form action="/createtem/mgattach" method="get" id="searchform">
					<strong>附件名称：</strong>
					<input type="text" name="attachname" id="attachname" value="{%$attachname%}" class="input-large" style="width:240px;height:22px;" placeholder="请输入附件名称">
					<strong>任务名称：</strong>
					<input type="text" name="taskname" id="taskname" value="{%$taskname%}" class='input-large' style="width:240px;height:22px;" placeholder="请输入任务名称">
					<button type="button" id="searchtype" class="btn" style="height: 28px;margin-left:5px;font-size:12px;"><i class="icon icon-search"></i>&nbsp;搜索</button>
					<a id="resetsearch" class="btn" style="margin-left:5px;font-size:12px; height: 19px;width: 38px;">重置</a>
					<input type="hidden" value="{%$setnum%}" name="num" id="num2" />
				</form>
			</div>

		</div>
		<!-- <hr> -->
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box" style="padding-bottom: 10px;border-bottom: 1px solid #cdcdcd;">
					<div class="widget-title"> <span class="icon"><i class="icon-inbox "></i></span>
						<h5>附件管理</h5>
						{%if $role == "stasker" || $role == "tasker"%}
						<div style="float:right;width:100px;margin-top:4px;margin-bottom:4px;height:28px;"><input type="file" style="display:none;" name="files" id="fileupload" data-url="/createtem/uploadattchs" multiple /><a href="javascript:void(0);" class="btn" onclick="return getupload()" style="width: 70px;font-size:12px;color:#000;height:20;line-height:20px;padding-top: 3px; padding-bottom: 3px;" /> + 上传附件</a></div>
						{%/if%}
					</div>
					<div class="widget-content nopadding">
						<table width="80%" align="center" class="table table-bordered table-striped">
							<thead>
								<tr>
									<label for="selectAll">
										<th style="text-align:center;width:8%"><input type="checkbox" id="selectAll" onclick="selectall(this);" style="margin-right: 10px;">全选</th>
									</label>
									<th style="width:22%;">附件名称</th>
									<th style="width:20%;">任务名称</th>
									<th style="width:8%;">上传者</th>
									<th style="width:15%;">上传时间</th>
									<th style="width:8%;">大小</th>
									<th style="width:15%;">操作</th>
								</tr>
							</thead>
							<tbody>
								{%section name=vals loop=$rows%}
								<tr id="{%$rows[vals].id%}">
									<td><input type="checkbox" name="selattach[]" style="text-align:center" value="{%$rows[vals].id%}"></td>
									<td style="text-align:center" title="{%$rows[vals].truename%}">{%$rows[vals].truename|truncate:30:"...":true%}</td>
									<td style="text-align:center">{%$rows[vals].task_name|truncate:30:"...":true%}</td>
									<td style="text-align:center">{%$rows[vals].username%}</td>
									<td style="text-align:center">{%$rows[vals].createtime%}</td>
									<td style="text-align:center">{%$rows[vals].filesize%}</td>
									<td>
										<a href="javascript:void(0);" class="delattach" value="{%$rows[vals].id%}">
											<i class="icon icon-remove "></i>删除
										</a>
										<a href="/createtem/mgattach/doid/{%$rows[vals].id%}" title="下载" style='margin-left: 30px;'><i class="icon-download-alt icon-large"></i>下载</a>
									</td>
								</tr>
								{%/section%}
							</tbody>
						</table>
					</div>
					<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
						<div class="row-fluid" style="text-align:right; margin-top: 0px;">
							<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="margin-top: 5px;">
								<div id="" class="" style="float:left;margin-left:8px;">
									<input style="margin-top: 2px; font-size: 12px; width: 60px; padding: 3px 5px;" value="批量删除" class="btn" id="delall" />
								</div>
								<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;float:left;margin-left:20%;">
									{%$page%}
								</div>
								<div id="DataTables_Table_0_length" class="dataTables_length" style="float:right;width:100px;margin-right:18px;">
									<label style="height:30px;">
										<form method="GET" style="width:106px">
											<select name="num" id="gonumid" size="1" aria-controls="DataTables_Table_0" style="width:60px">
												<option {%if $setnum==5 %}selected="selected" {%/if%} value="5">5</option>
												<option {%if $setnum==10 %}selected="selected" {%/if%} value="10">10</option>
												<option {%if $setnum==25 %}selected="selected" {%/if%} value="25">25</option>
												<option {%if $setnum==50 %}selected="selected" {%/if%} value="50">50</option>
												<option {%if $setnum==100 %}selected="selected" {%/if%} value="100">100</option>
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
<input type="hidden" value="{%$slide%}" id="slides" />
<!--Footer-part-->
<script type="text/javascript">
	function getupload() {
		$('#fileupload').click();
		return false;
	}
	$('#fileupload').fileupload({
		dataType: "json",
		progressall: function(e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#progress').css('display', 'block');
			$('#progress .bar').css({
				'display': 'block',
				'width': progress + '%'
			});
		},
		done: function(e, data) {
			var arr = data.result;
			art.dialog.alert(arr.filename + '上传完毕');
			location.reload();
		}
	});
	var slide = $('#slides').val();
	if (slide == 'down') {
		$('#collapseG3').attr('class', 'widget-content nopadding updates collapsed in');
	} else {
		$('#collapseG3').attr('class', 'widget-content nopadding updates collapse');
	}
	var flip = 0;
	$("#quicknav").click(function() {
		$("#collapseG3").on("show", function() {
			var up = $("#quicknav").children().children("i").attr("class", 'icon-chevron-up');
		});
		$("#collapseG3").on("hide", function() {
			var up = $("#quicknav").children().children("i").attr("class", 'icon-chevron-down');
		});
	});
	$("#collapseG3").show();
	var up = $("#quicknav").children().children("i").attr("class", 'icon-chevron-up');

	//清空搜索表单
	$('#resetsearch').click(function() {
		$(':input', '#searchform').val('');
		$(':input').focus();
	});

	//删除附件
	$('.delattach').click(function() {
		var id = $(this).attr('value');
		var des = $(this).parent().parent().attr('id');
		art.dialog.confirm('确定删除吗?', function() {
			$.post('/createtem/delattach', {
				'id': id
			}, function(data) {
				if (data != "") {
					// alert(data);
					$("#" + des).remove();
				}
			})
		});
	});

	$('#delall').click(function() {
		var id = "";
		$("input[name='selattach[]']:checked").each(function() {
			id = id + ',' + $(this).val();
		});
		if (id == "") {
			art.dialog.alert("请选择要删除的条目");
			return false;
		}
		art.dialog.confirm("您将要删除所选中的条目，是否继续？", function() {
			$.post('/createtem/delattach', {
				'del_str_id': id
			}, function(data) {
				if (data) {
					location.reload();
				}
			});
		})
	});

	$("#gobtn").click(function() {
		var url = "/createtem/mgattach?&style=go";
		var num = $('#gonumid').val();
		if (num != "") {
			url += "&num=" + num;
		}
		var attachname = $('#attachname').val();
		if (attachname != "") {
			url += "&attachname=" + attachname;
		}
		var taskname = $('#taskname').val();
		if (taskname != "") {
			url += "&taskname=" + taskname;

		}
		window.location.href = url;
	});
</script>
{%include file="footer.php"%}