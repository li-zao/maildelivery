<!-- header -->
{%include "header.php"%}
<script type="text/javascript" src="/dist/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/dist/js/searchmytemplpage.js"></script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb">
			<a href="/createtem/firstpage" title="首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#">邮件内容管理</a> <a href="#" class="current"></i>创建模板</a>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
						<h5>创建模板</h5>
					</div>
					<div class="widget-content nopadding">
						<table class="table table-bordered table-striped">
							<form action="/templet/docreates" method="post" id="form1" enctype="multipart/form-data" onkeydown="javascript:return chkchk(event);" onsubmit="javascript:return chkform(this);">
								<tbody>
									<tr>
										<td>模板名称</td>
										<td>
											<input type="text" style="height: 24px;" class="span4" id="inputtplnames" name="tplname" value="{%$row['tpl_name']%}" maxlength="9" /><span class="help-inline" style="color:red">*</span> ( 9字以内 )
										</td>
									</tr>
									<tr>
										<td>任务分类</td>
										<td><select name="tpltype" id="selects" style="height: 30px;" class='span4'>
												<option value="" selected="selected">请选择分类</option>
												{%section name=vals loop=$vocation%}
												{%if $vocation[vals].id==$rows['id']%}
												<option value="{%$vocation[vals].id%}" selected>{%$vocation[vals].vocation_name%}</option>
												{%else%}
												<option value="{%$vocation[vals].id%}">{%$vocation[vals].vocation_name%}</option>
												{%/if%}
												{%/section%}
											</select>

											<a href="#creattpl" data-toggle="modal" role="button" class="btn">快速创建</a>

											<div id="creattpl" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: :;x;">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel" class="text-left">快速创建</h3>
												</div>
												<div class="modal-body">
													<p>
														<table width="600" style="border-left:0;border-top:0 ;">
															<tr>
																<td style="border-left:0;border-top:0 ;">分类名称:</td>
																<td style="border-left:0;border-top:0 ;"><input type="text" name="inputname" id="inputname" style="height: 24px;" maxlength="10">
																	<span id="namestyle" style="color: red">*</span>
																	<span>(10字以内)</span>
																	<div class="style_prl" style="color:red;font-weight: 700;width: 90px;display:none;">不能为空</div>
																</td>
															</tr>
															<tr>
																<td style="border-left: 0; border-top: 0">分类描述:</td>
																<td style="border-left:0;border-top:0 ;">
																	<textarea name="inputcontent" id="inputcontent" cols="250" rows="2" style="resize:none;" maxlength="15"></textarea>
																	<span>(15字以内)</span>

																</td>
															</tr>
														</table>
													</p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
													<a class="btn" id="bt2">创建</a>

												</div>
											</div>
											<!-- </form> -->
										</td>
									</tr>
									<tr>
										<td style="width:20%;">读取URL地址</td>
										<td>
											<input class="span4" style="height: 24px;" id="focusedInput" name="tplurl" type="text" value="http://">
											<a id="reads" class="btn" style="margin-bottom: 0px;">读取</a> <span style="margin-left: 10px;"><span style="color:red;">*</span> ( 除带有Javascript、Flash、内嵌窗口等大部分URL )</span>
										</td>
									</tr>
									<tr>
										<td style="width:20%;">导入模板文件</td>
										<td>
											<div>
												<input id="inputfile" class="span4" name="files" type="file" multiple style="border:1px solid #ccc;height:30px">
												<a class="btn" id="ups" onclick="return ajaxFileUpload();">上传</a><span style="margin-left: 10px;"><span style="color:red;">*</span> ( 格式限制为：HTML )</span>
											</div>

											<script>
												function ajaxFileUpload() {
													if ($('#inputfile').val() == "") {
														art.dialog.alert("文件不能为空");
														return false;
													} else {
														var filename = $('#inputfile').val();
														var exp = /\.[^\.]+/.exec(filename);

														if (exp == '.html') {
															$.ajaxFileUpload({
																url: '/templet/uploadtem',
																type: 'post',
																secureuri: false,
																fileElementId: 'inputfile',
																dataType: 'text',
																// data:{name:'logan', id:'id'},
																success: function(data) {
																	if (data) {
																		CKEDITOR.instances.texts.setData(data);
																		CKEDITOR.instances.texts.getData();
																	}
																},
																error: function(data, status, e) {
																	alert(e);
																}
															})
														} else {
															art.dialog.alert("文件格式不正确，请采用正确格式文件");
															return false;
														}
													}
													return false;
												}
											</script>
										</td>
									</tr>
									<tr rowspan="2">
										<td colspan="2">
											<textarea name="factcontent" id="texts" cols="30" rows="10">{%$row.tpl_body%}</textarea>
										</td>
									</tr>
									<tr>
										<td colspan="2" style="text-align: center;">
											<input type="hidden" name="tid" value="{%$row.id%}" id="editors">
											<input type="hidden" name="uid" value="{%$row.uid%}">
											<a href="javascript:void(0);" class="btn" style="float: left;" id="external">模板预览</a>
											<button id="bt" class="btn">保存</button>
										</td>
									</tr>
								</tbody>
							</form>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />
<!-- footer -->
<script>
	$(function() {
		CKEDITOR.replace('texts');
	});
	document.onkeypress = function(event) {
		if (event.keyCode == 13) {
			return false;
		}
	}

	function chkchk(e) {
		if (window.event) {
			if (e.keyCode == 13) {
				alert(e.keyCode);
				return false;
			}
			return true;
		} else {
			if (e.which == 13) {
				e.preventDefault();
				return false;
			}
			return true;
		}
	}

	$('#bt').click(function() {
		var valuesname = $("#inputtplnames").val();
		var values_sel = $("#selects").val();
		var editors = $('#editors').val();
		var content = CKEDITOR.instances.texts.getData();
		if (valuesname == "") {
			art.dialog.alert("模板名称不能为空");
			return false;
		} else {
			$.post('/templet/checktpl', {
				'tplnames': valuesname
			}, function(data) {
				if (data == "nopass") {
					art.dialog.alert("模板名称重复");
					return false;
				}
			});
			if (values_sel == "" || values_sel == null) {
				art.dialog.alert("模板分类不能为空");
				return false;
			}
			if (content == "" || content == null) {
				art.dialog.alert("模板内容不能为空");
				return false;
			}
			$('#form1').submit();
		}
		return false;

	});

	$('#bt2').click(function() {
		var inputname = $('#inputname').val();
		var inputcontent = $('#inputcontent').val();
		// alert(inputname);
		if (inputname == "") {
			$('.style_prl').slideDown(500);
			return false;
		} else {
			$.post('/templet/addvocation', {
				'inputname': inputname,
				"inputcontent": inputcontent
			}, function(data) {
				if (data != 0) {
					// alert(data);
					$('#selects').get(0).add(new Option(inputname, data), null)
					$('#creattpl').modal('hide');
					$('#creattpl').on('hidden', function() {
						$('#inputname').val("");
						$('#inputcontent').val("");
					});
					art.dialog.alert('添加成功');

					// location.reload();
				} else {
					$('.style_prl').text("分类已存在");
					$('.style_prl').slideDown(500);
				}
			});

		}

		return false;
	});
	$('#inputname').blur(function() {
		var inputnames = $("#inputname").val();
		if (inputnames != "") {
			$('.style_prl').slideUp(500);
		}
	});

	// $('#focusedInput')
	$('#reads').click(function() {
		var values = $('#focusedInput').val();
		if (values == "http://" || values == "") {
			art.dialog.alert('输入正确的网址');
		} else {
			$.post('/templet/ajaxweb', {
				'files_contents': values
			}, function(data) {
				if (data) {
					CKEDITOR.instances.texts.setData(data);
					CKEDITOR.instances.texts.getData();
				}
			});
		}

	});

	$("#external").click(function() {
		var content = CKEDITOR.instances.texts.getData();
		$.post('/templet/preview', {
			'templets': content
		}, function(data) {
			if (data == '111') {
				window.open('/templet/previewone');
			}
		});
	});
</script>
{%include "footer.php"%}