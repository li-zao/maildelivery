{%include file="header.php"%}
<style type="text/css">
	/*确认信息confirmtask页样式*/
	.confirminfo_box{background: none repeat scroll 0 0 #F1F1F1;border: 1px solid #DDDDDD;border-radius: 5px;float: left;height: 33px;line-height: 18px;margin-top: 10px;margin-bottom:10px;padding: 10px;width: 90%;}
	.float_left{float: left;}
	.font14{font-size: 14px;}
	.font12_color{color: #999999;}
	.btn_new{float:right; margin-top:5px;width: 10%;height: 27px;line-height: 20px;font-size:12px;text-align: center;border: 0 none;background:url(/dist/img/but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;}
	.btn_box{float: left;height: 33px;line-height: 18px;margin-bottom:10px;padding: 10px;width: 90%;padding-left: 0px;}
	.bj_nr{float:left;background: none repeat scroll 0 0 #F1F1F1;margin-bottom: 10px;padding: 20px;width: 90%;font-size: 12px;}
	.cemail{border: 1px solid #BFBFBF;height: 25px;line-height: 25px;padding-left: 5px;width: 30%;font-size:12px;float: left;margin-right: 5px;}
	#btn_fasong{width: 10%;height: 25px;line-height:22px;font-size:12px;text-align: center;border: 0 none;background:url(/dist/img/but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;padding:0 12px;}
</style>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 投递任务管理</a><a href="#" class="current">创建向导任务</a></div>
  </div>
  <div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"><i class="icon-inbox"></i></span>
						<h5>确认信息</h5>
					</div>
					<div class="widget-content"  style="height: 440px;">
						<form id="conform" method="post" action="/task/inserttask">
							<input id="act" type="hidden" value="" name="act">
							<input id="step" type="hidden" value="" name="step">
							<input id="old_step" type="hidden" value="" name="old_step">
							<input id="tid" type="hidden" name="tid" value="{%$tid%}">
							<input id="tnum" type="hidden" name="tnum" value="{%$arrtask['tnum']%}">
							<div class="confirminfo_box">
								<p class="float_left"><strong class="font14">联系人组</strong><br>
								<span class="font12_color">当前活动将要发送给
									{%if !empty($gid) && $gid == 'all'%}
										【所有】联系人组(共{%$arrtask['tnum']%}人)
									{%else%}
										{%section name=loop loop=$gnames%}【{%$gnames[loop].gname%}】联系人组(共{%$gnames[loop].person_num%}人){%/section%}
									{%/if%}
								</span>
								</p>
								<button type="button" class="btn_new" id="edit_contact" style="width: 85px;">编辑</button>
							</div>			
							<div class="confirminfo_box">
								<p class="float_left"><strong class="font14">任务设置</strong><br><span class="font12_color">任务名称：【{%$arrtask['task_name']%}】</span></p>
								<button type="button" class="btn_new" id="edit_task" style="width: 85px;">编辑</button>
							</div>			
							<div class="confirminfo_box">
								<p class="float_left"><strong class="font14">内容设计</strong><br><span class="font12_color">所有的回复将发给 {%$arrtask['sendemail']%}</span></p>
								<button type="button" class="btn_new" id="edit_content" style="width: 85px;">编辑</button>
							</div>
							<div class="btn_box">
								<button id="send_btn" class="btn" type="button" style="width:113px;height:30px;background:url(/dist/img/zjfs_but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;margin-right: 5px;"></button>
								<button id="draft_btn" class="btn" type="button" style="width:113px;height:30px;background:url(/dist/img/bccg_but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;margin-right: 5px;"></button>
								<button id="test_btn" class="btn" type="button" style="width:113px;height:30px;background:url(/dist/img/cs_but.jpg) no-repeat;border-radius: 3px 3px 3px 3px;"></button>
							</div>
							<div style="display: none;line-height:30px;" id="testbox" class="bj_nr">
								<strong class="font14 float_left">测试邮箱：</strong><input id="testemail" class="cemail" type="text" value="" placeholder="请输入测试的邮箱" name="test_email" style="width:315px;height: 25px;  padding:0 3px;margin-left: 13px;"><button id="testsend" class="btn" type="button"  class="but float_left" style="padding-bottom: 2px; padding-top: 2px;  border-radius: 3px;margin-top: -6px;">发送</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

  </div>
</div>
<script type="text/javascript">
$(function(){
	var tid=$('#tid').val();
	//alert(tid);
	//跳转编辑联系人edit_contact
	$('#edit_contact').on('click', function(){
		$("#act").val('edit');
		$("#step").val('one');
		$("#old_step").val('confirm'); 
		$("#conform").submit(); 
	})
	
	//跳转编辑任务
	$('#edit_task').on('click', function(){
		$("#act").val('edit');
		$("#step").val('two');
		$("#old_step").val('confirm'); 
		$("#conform").submit(); 
	})
	
	//跳转编辑邮件内容
	$('#edit_content').on('click', function(){
		$("#act").val('edit');
		$("#step").val('three');
		$("#old_step").val('confirm'); 
		$("#conform").submit();
	})
	
	var tnum=$('#tnum').val();
	//点击button判断form表单是的值后提交表单
	$('#send_btn').on('click', function(){
		$("#act").val('confirm');
		if(tnum == 0 || tnum == null){
			art.dialog.alert('对不起，您不可以提交，因为您目前的任务中的收件人数量为0,请添加收件人!');
			return false;
		}
		var confirm=$('#act').val();
		$.post('/task/inserttask',{'tid':tid,'act':confirm},function(data){
			//alert(data);exit;
			if(data ==  'Success'){
				art.dialog.alert('恭喜您，提交任务成功，请等待管理员审核！');
				window.location.href="/task/listtask"; 
			}
		})
	});
	
	//保存到草稿
	$('#draft_btn').on('click', function(){
		$("#act").val('draft');
		var tid=$('#tid').val();
		var draft=$('#act').val();
		if(tnum == 0 || tnum == null){
			art.dialog.alert('对不起，您不可以提交，因为您目前的任务中的收件人数量为0,请添加收件人!');
			return false;
		}
		//将任务保存的草稿
		$.post('/task/inserttask',{'tid':tid,'act':draft},function(data){
			//alert(data);exit;
			if(data ==  'Success'){
				art.dialog.alert('保存草稿成功！');
				window.location.href="/task/drafttask"; 
			}
		})
	});
	
	//发送测试
	$('#test_btn').on('click', function(){
		$('#testbox').toggle();
	});
	
	//验证测试邮箱后提交
	$('#testsend').on('click', function(){
		var tid=$('#tid').val();
		var testemail=$('#testemail').val();
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
		if(testemail == ""){
			art.dialog.alert('邮箱不能为空');
			return false;			
		}
		if(!reg.test(testemail)) {
			art.dialog.alert('您填写的邮箱格式不正确,请重新填写!');
			return false;
		}
		$.post('/task/inserttask',{'testemail':testemail,'tid':tid,'act':'test_send'},function(data){
			//alert(data);exit;
			if(data ==  'Success'){
				art.dialog.alert('测试邮件发送成功，请注意查收！');
			}else if(data ==  'Error'){
				art.dialog.alert('测试邮件发送失败,请检查报告发送配置！');
			}else{
				art.dialog.alert('sorry,发送测试邮件需要先在系统设置里设置报告发送配置,请检查系统设置！');
			}
		})
	});
})
</script>
<!--Footer-part-->
{%include file="footer.php"%}
