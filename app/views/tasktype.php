{%include file="header.php"%}
<style>
    .table th, .table td {
        text-align: center;
    }
</style>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 投递任务管理</a><a href="#" class="current">任务分类</a></div>
  </div>
  <div class="container-fluid">
		 {%if $search != "" ||  $typename != "" || $description != ''%}
		  <input id="s_data" type="hidden" value="yes" name="s_data">
		 {%else%}
		  <input id="s_data" type="hidden" value="" name="s_data">
		 {%/if%}	
		  <div id="quicknav" class="widget-title bg_lo" style="border-bottom:1px dotted #cdcdcd;width:100%;" data-toggle="collapse" href="#collapseG3">
				<h5 style="float: right;" id="qv">
				检索条件
				<i class="icon-chevron-down"></i>
				</h5>
		  </div>
		  <p></p>
		  <div id="dis" style="display:none;">
			<form action="/task/typetask" method="get" id="searchform" onkeydown="if(event.keyCode==13){return false;}">
					分类名称：<input type="text" name="typename" id="typename" value="{%$typename%}" placeholder="请输入任务分类名称" style="width:240px; height: 22px; margin-right: 5px;font-size: 12px;">
					分类描述：<input type="text" name="description" id="description" value="{%$description%}" placeholder="请输入任务分类描述" style="width:240px; height: 22px;font-size: 12px;">
					<input type="hidden" name="search" value="search"/>
					<button id="searchtype" class="btn" style="height:28px;font-size:12px;margin-left: 5px;"><i class="icon-zoom-in"></i>&nbsp;搜索</button>
					<a id="resetsearch" class="btn" style="height:18px;font-size:12px;margin-left: 5px;width: 38px;">重置</a>
					<input type="hidden" value="{%$num%}" name="num" id="num2" />
			</form>
		 </div>
 		<div class="row-fluid" style="margin-top:0px;">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> <span class="icon"><i class="icon-inbox"></i></span>
						<h5>任务分类</h5>
						{%if $role == "stasker" || $role == "tasker"%}
						<div style="float:right;width:100px;margin-top:4px;height:28px;"><a href="#myModal" role="button" class="btn" data-toggle="modal" style="font-size:12px;"> + 新建分类</a></div>
						{%/if%} 
					</div>
					<div class="widget-content" style="padding:0px">
										<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:42%;">
											<form id="formtype" action="/templet/addvocation" method="get" class="">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">添加任务分类</h3>
												</div>
												<div class="modal-body">					
													<p>
														<table width="510" style="border-left:0;border-top:0 ;margin:0 10px;">
														  <tr>
															<td style="border-left:0;border-top:0 ;">分类名称:</td>
															<td style="border-left:0;border-top:0 ;padding:8px 0;"><input type="text" name="names" id="inputuser" class="input-large" style="height: 24px;" maxlength="10">
															  <span id="namestyle" style="color: red">*</span>
															  <span>(10字以内)</span>
															  <div class="style_type" style="color:#999999;font-size:12px;">分类名称不能与已有分类重复</div>
															</td>
														  </tr> 
														  <tr>
															<td style="border-left: 0; border-top: 0">分类描述:</td>
															<td style="border-left:0;border-top:0 ;padding:8px 0;">
															  <textarea name="content" id="inputinput" cols="250" rows="2" style="resize:none;" maxlength="15"></textarea>
															  <span>(15字以内)</span>
															
															</td>
														  </tr>
														</table> 
													</p>
												</div>										
												<div class="modal-footer">
													<button type="button" id="sudu" class="btn" style="margin-right: 10px;">创建
													<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
												</div>
											</form>
										</div>
						<div>
							<table class="table table-bordered table-striped" style="border: 0px solid #e4e4e4;border-collapse:separate;font-size:12px;margin-bottom: 0px;">
								<thead>
									<tr>
										{%if $role == "stasker" ||  $role == "tasker"%}
										<th style="text-align:center"><input type="checkbox" id="checkAll">&nbsp;&nbsp;全选</td>
										{%else%}
										<th style="width:10%;text-align:center">序号</td>
										{%/if%}
										<th style="width:20%;">分类名称</th>
										<th style="width:25%;">描述</th>
										<th style="width:15%;">创建人</th>
										<th style="width:20%;">任务数量</th>
										<th style="width:25%;">操作</th>
									</tr>
								</thead>
								<tbody>
								{%$a = 1%}
								{%section name=loop loop=$catdata%}
									<tr>
										{%if $role == "stasker" ||  $role == "tasker"%}
										<td style="text-align:center"><input type="checkbox" name="checktype[]" value="{%$catdata[loop].id%}" /></td>
										{%else%}
										<td style="text-align:center">{%$a++%}</td>
										{%/if%}
										<td><a title="{%$catdata[loop].vocation_name%}">{%$catdata[loop].vocation_name|truncate:20:"..."%}</a></td>
										<td><a title="{%$catdata[loop].vocation_body%}">{%$catdata[loop].vocation_body|truncate:30:"..."%}</a></td>
										<td><a title="{%$catdata[loop].username%}">{%$catdata[loop].username|truncate:30:"..."%}</a></td>
										<td>{%$catdata[loop].draftnum%} 草稿 + {%$catdata[loop].tasknum%} 任务 共 {%$catdata[loop].total%} 个</td>
										<td>
										{%if $role == "stasker" ||  $role == "tasker"%}
											{%if $catdata[loop].mine == 1%}
											<a href="#{%$catdata[loop].id%}" role="button" class="editcat" data-toggle="modal"><i class="icon-edit"></i>&nbsp;编辑</a>
											{%else%}
											<i class="icon-edit" style="color:#BBBBBB"></i>&nbsp;<span style="color:#BBBBBB">编辑</span>
											{%/if%}
										<div id="{%$catdata[loop].id%}" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:42%;text-align:left">
											<form id="edittype" action="" method="post" class="">
												<input type="hidden" value="{%$catdata[loop].id%}" name="void">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">编辑任务分类：{%$catdata[loop].vocation_name%}</h3>
												</div>
												<div class="modal-body">					
													<p>
														<table width="510" style="border-left:0;border-top:0 ;margin:0 10px;">
														  <tr>
															<td style="border-left:0;border-top:0 ;">分类名称:</td>
															<td style="border-left:0;border-top:0 ;padding:8px 0;text-align:left;">
															  <input type="text" name="names" value="{%$catdata[loop].vocation_name%}" id="inputname_{%$catdata[loop].id%}" class="input-large" style="height: 24px;" maxlength="10" />
															  <span id="namestyle" style="color: red">*</span>
															  <span>(10字以内)</span>
															  <div class="style_prl_{%$catdata[loop].id%}" style="color:#999999;font-size:12px;">分类名称不能与已有分类重复</div>
															</td>
														  </tr> 
														  <tr>
															<td style="border-left: 0; border-top: 0">分类描述:</td>
															<td style="border-left:0;border-top:0 ;padding:8px 0;text-align:left;">
																<textarea name="contents" id="inputcon_{%$catdata[loop].id%}" cols="250" rows="2" style="resize:none;" maxlength="15" >{%$catdata[loop].vocation_body%}</textarea>
																<span>(15字以内)</span>
															</td>
														  </tr>
														</table> 
													</p>
												</div>										
												<div class="modal-footer">
													<button type="button" class="reserve" class="btn" style="margin-right: 10px;">保存</button>
													<button type="button" type="reset" class="btn">重置</button>
												</div>
											</form>
										</div>
										&nbsp;&nbsp;&nbsp;
										<!--<a href="javascript:void(0);" class="delcat"><i class="icon-remove"></i>&nbsp;删除</a>-->
										{%/if%}
										{%if $catdata[loop].tasknum != '0' %}
										<a href="/statistics/taskclassification?cid={%$catdata[loop].id%}" class="generaltask"><i class="icon-bar-chart"></i>&nbsp;报告</a>
										{%else%}
										<i class="icon-edit" style="color:#BBBBBB"></i>&nbsp;<span style="color:#BBBBBB">报告</span>
										{%/if%}
										</td>
									</tr>
								{%/section%}
								<tr>
								{%if $role == "stasker" || $role == "tasker"%}
								<td style="text-align:center;"><input id="delall" type="button" class="btn" value="批量删除" style="margin-top: 2px;font-size:12px;" /></td>
								{%else%}
								<td></td>
								{%/if%}
								<td colspan="4" style="border-left: 0px;">
										<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
										<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
										{%$page%}
										</div>
										</div>
								</td>
								<td style="padding-left: 12px;border-left: 0px;">
									<center><label style="height: 30px; width: 105px;">
										<form method="GET" style="width:106px">
											<select name="num" id="gonumid" size="1" style="width:60px">
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
<script>
$(function(){
	$('#sudu').on('click',function(){
		//alert(123);exit;
	    var inputname=$('#inputuser').val();
		var inputcontent=$('#inputinput').val();
		if(inputname == ""){
			$('.style_type').html("<font color='red'>任务分类名称不能为空</font>");
			return false;
		}else{
			$.get('/templet/addvocation',{'names':inputname,'content':inputcontent},function(data){
            if(data == "error" ){
                $('.style_type').html("<font color='red'>该任务分类名称已存在</font>");
                //$('.style_prl').slideDown(500);
				//alert(12345);
				return false;

            }else{
				$('.style_type').text("恭喜，该任务分类名称可以使用！");
            	location.href="/task/typetask";
            }  
          });
        	return false;

		}  
	})
})

	$(function(){
		//编辑任务分类
		$('.reserve').on('click',function(){
			var id=$(this).parent().parent().parent().attr('id');
			var inputname=$('#inputname_'+id).val();
			var inputcontent=$('#inputcon_'+id).val();
			var tag='task';					
			if(inputname==""){
				$('.style_prl_'+id).html("<font color='red'>任务分类名称不能为空</font>");
				return false;
			}else{
				$.post('/templet/doupdatevo',{'tag':tag,'void':id,'names':inputname,'contents':inputcontent},function(data){
				if(data =="error" ){
					$('.style_prl_'+id).html("<font color='red'>该任务分类名称已存在</font>");
					return false;

				}else{
					$('.style_prl_'+id).text("修改成功，该任务分类名称可以使用！");
					location.href="/task/typetask";
				}
					
			  });
				return false;

			}  
		
		})	
		
		//删除任务分类
		$('.delcat').on('click',function(){
			//confirm_ = confirm('确定删除该分类？')
			//if(confirm_){
			var cid=$(this).prev().attr('id');
				//alert(cid);exit;
			art.dialog.confirm('确定删除该分类？', function () {
				$.post('/task/deltaskcat',{'cid':cid},function(data){
					//alert(data);
					if(data == "1"){
						$("#"+cid).parent().parent().remove();
						
					}
				});
			});
			//}
		})
		
	}) 
	
	//分类搜索
	var s_data=$('#s_data').val();
	if(s_data == 'yes'){
		$("#dis").css("display","block");
	    $("#quicknav").children().children("i").attr("class",'icon-chevron-up');
	}
	
	$('#resetsearch').on('click',function(){
			$(':input','#searchform')
			.not(':button,:hidden')
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
	})
	$("#checkAll").click(function () {
		var aa=$(this).val();
		//alert(aa);
		if ($(this).attr("checked")) { // 全选
			$("input[name='checktype[]']").each(function () {
			$(this).attr("checked", true);
			});
		}
		else { // 取消全选
			$("input[name='checktype[]']").each(function () {
			$(this).attr("checked", false);
			});
		}
	});
	
	//批量删除
	$("#delall").click(function () {
		var checktype = $("input[name='checktype[]']:checked");
			var types = "";
			$.each(checktype,function(i,result){
				types += $(this).val()+",";
			})
			if(types == ""){
				art.dialog.alert("请选择批量删除的分类");
				return false;
			}
			art.dialog.confirm('确定删除该分类？', function () {	
				$.post('/task/deltaskcat',{'cids':types},function(data){
					//alert(typeof data)
					if(data == "ok"){
						location.reload();
					}
				});
			});
	});
	
	$("#gobtn").click(function(){
		var url = "/task/typetask?&style=go";
		var num = $('#gonumid').val();
		if( num != ""){
			url += "&num=" + num;
		}
		var typename = $('#typename').val();
		if( typename != "" ){
			url += "&typename=" + typename;
		}
		var description = $('#description').val();
		if( description != "" ){
			url += "&description=" + description;
		}
		window.location.href = url;
	});
	

</script>
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />
<!--Footer-part-->
{%include file="footer.php"%}
