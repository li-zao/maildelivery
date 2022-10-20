{%include "header.php"%}
<!-- <script type="text/javascript" src="/dist/js/jquery.artDialog.js?skin=green"></script> -->
<div id="content">
	<div id="content-header">
		<div id="breadcrumb">
			<a href="/setting/networksetting" title="首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="/templet/mytempl">邮件内容管理</a> <a href="#" class="current"></i>编辑任务分类</a>	
		</div>	
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span8">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon"><i class="icon-edit" style="font-size:16px"></i></span>	
						<h5>编辑任务分类</h5>
					</div>	
					<div class="widget-content">
						<form  method="post" class="form-horizontal">
								<div class="control-group">
									<label class="control-label" for="inputname">任务名称:</label>
									<div class="controls">
										<input type="text" id="inputname" value="{%$name%}" name="names" maxlength="9">	
										<span class="help-inline" style="color:red">*</span> ( 9字以内 )
									</div>	
								</div>	
								<div class="control-group">
									<label class="control-label" for="inputcon">任务描述:</label>
									<div class="controls">
										<textarea name="contents" id="inputcon" cols="30" rows="3" style="resize:none" maxlength="15">{%$body%}</textarea>
										<span class="help-inline" style="">*</span> ( 15字以内 )
									</div>	
								</div>
								<div class="control-group">
									<div class="controls">
										<input type="hidden" value="{%$void%}" name="void" id="vocaid">
										<input class="btn" type="submit" value="保存" id="bt">	
										<a href="/templet/mytempl" class="btn">返回</a>
									</div>	
								</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />
<script>
	$('#bt').click(function(){
		var value=$('#inputname').val();
		var vocaid = $('#vocaid').val();
		var getc = $('#inputcon').val();
		// alert(getc);exit;
		if(value==""){
			$.dialog({content:"<span style='font-size:16px;blod'>模板名称不能为空</span>",width:250,height:100,lock:true,fixed:true,drag:false});
         	 return false;
		}else{
			$.ajax({
				type:'post',
				url:'/templet/doupdatevo/',
				data:{'void':vocaid,'names':value,'tag':'task','contents':getc},
				success:function(data){
					//alert(data);
					if(data =="error" ){
						art.dialog.alert("该任务分类名称已存在");
							return false;

					}else{
						art.dialog.confirm('修改成功',function(){
							location.href="/templet/mytempl";
						});
					}
					return false;

				}
			})
		}
					return false;

	});
</script>
{%include "footer.php"%}
