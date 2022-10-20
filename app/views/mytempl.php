<!-- header -->
{%include "header.php"%}
<!-- content -->
<!-- <script type="text/javascript" src="/dist/js/jquery.artDialog.js?skin=blue"></script> -->
<script type="text/javascript" src="/dist/js/mytemplpage.js"></script>
<script type="text/javascript" src="/dist/js/searchmytemplpage.js"></script>

<div id="content">
	<div id="content-header">
    	<div id="breadcrumb">
    		<a href="/createtem/firstpage" title="首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#">邮件内容管理</a> <a href="#" class="current"></i>模板管理</a>
    	</div>
    	<!-- <hr> -->
	</div>	

 	<div class="container-fluid">
 		<div class	="row-fluid">
 			<div class="span12">
 			<div class="widget-box">
	          <div class="widget-title"> <span class="icon"> <i class="icon-tasks"></i> </span>
	            <h5>模板管理</h5>
	          </div>
	          <div class="widget-content">
	          	<div class="row-fluid">
	            <div id="container" >
        			<!-- <div > -->
 					<div class="span12">
				<p style="border-bottom: 1px solid #A4A4A4;display: block;height: 30px;width: 100%;"><span style="border-radius: 5px 5px 0 0;background: none repeat scroll 0 0 #A4A4A4;color: #FFFFFF;display: block;font-size: 12px;font-weight: bold;height:30px;line-height: 30px;text-align:center;width: 10%;float: left;">任务分类</span>
				<!--<a href="#myModal" role="button" class="btn " style="float: right;" data-toggle="modal">+新增任务分类</a>-->
				</p>
					<!-- mymodal -->
					<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 590px;">
						<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">管理</h3>
						</div>
						<div class="modal-body">
						<p>
							<form action="addvocation" method="get" class="form-inline">
								<label for="inputuser">分类名称:</label>
								<input type="text" name="names" id="inputuser" class="input-small" maxlength="10">&nbsp; 
								<label for="inputinput">分类描述:</label>
								<input type="text" id="inputinput" name="content" class="input-medium" maxlength="15"  style="width: 194px;margin-right: 6px;">&nbsp;
								<input type="submit" id="sudu" value="快速创建" class="btn" >
                         		 <div class="style_prl" style="color:red;font-weight: 700;width: 110px;display:none;">分类名不能为空</div>

							</form>
						</p>
							<table class="table table-hover table-border" width="100%" style="z-index:8888" >
								<th width="20%">分类编号</th>
								<th width="20%">分类名称</th>
								<th width="40%">分类描述</th>
								<th width="60%">操作</th>
								<tbody id='tables'>
								{%section name=val loop=$vals%}
								<tr id="{%$vals[val].id%}">
									<td>{%$vals[val].id%}</td>
									<td>{%$vals[val].vocation_name%}</td>
									<td>{%$vals[val].vocation_body%}</td>
									<td>
										<a href="/templet/editvocation?id={%$vals[val].id%}" ><i class="icon-pencil"></i></a>
										<a href="/templet/deletevo/void/{%$vals[val].id%}" class="icon-remove ids">
										</a>
									</td>
								</tr>	
								{%/section%}
								</tbody>
							</table>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
						</div>
					</div>
 			</div>
 			<div class="span12" style="margin-left: 0px;">    
 				<ul style="width: 100%;margin-top: 10px;" id="contents">
 				{%section name=val loop=$vals%}
				<li style="float: left;height: 26px; margin-right: 10px;overflow: hidden;text-overflow:ellipsis;white-space:nowrap; width: 11%;">
 				<label class="checkbox inline">
				    <input type="checkbox" class="cs" id="{%$vals[val].id%}" value="{%$vals[val].id%}" name="cssp[]"> {%$vals[val].vocation_name%}
			    </label>
			    </li>
			    {%/section%}
			   	</ul> 
 			</div>
 		<!-- </div>	 -->
 		{%if !empty($tpls)%}
 		<div class="span12" style="margin: 0 auto;margin-top: 50px;">
 			<p style="border-bottom: 1px solid #A4A4A4;display: block;height: 30px;width: 100%;"><span style="border-radius: 5px 5px 0 0;background: none repeat scroll 0 0 #A4A4A4;color: #FFFFFF;display: block;font-size: 12px;font-weight: bold;height:30px;line-height: 30px;text-align:center;width: 10%;float: left;">模板内容</span>
			</p>
		</div>
 		<div id="ajaxHtml">
		<div class="row-fluid"  style="width:980px;margin-left:70px;">
			{%section name=loop loop=$tpls%}
				<div class="choseone" id="{%$tpls[loop].id%}">	
				<dl class="inline" >
					<dt class="text-center">{%$tpls[loop].tpl_name%}</dt>
					<dd style="margin-right: 15px; height: 140px; width: 110px" class="actions"><span href="createtempl/tid/{%$tpls[loop].id%}"> <img src="/dist/thumb_images/{%$tpls[loop].tpl_img%}" style="width: 104px;height: 133px;" alt="" class="img-rounded"></span></dd>
				</dl>
				<div class="chose" >
				<a href="/templet/tplview/relid/{%$tpls[loop].id%}" target="_blank" title="预览" style="margin-left:10px"><i class="icon-eye-open icon-large"></i></a>
					{%if $tpls[loop].mine == 1%}
					<a href="/templet/createtempl/tid/{%$tpls[loop].id%}" style="margin-left:5px" title="编辑"><i class="icon-edit icon-large"></i></a>
					{%/if%}
				<a href="/templet/mytempl/doid/{%$tpls[loop].id%}" style="margin-left:5px" title="下载"><i class="icon-download-alt icon-large"></i></a>
				<a href="javascript:void(0)"  class="deltpls" val="{%$tpls[loop].id%}" title="删除"><i class="icon-remove icon-large"></i></a>
			</div>
			</div>
			{%/section%}
		</div>
   		<div class="row-fluid">
			<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
						<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
							{%$page%}
						</div>
			</div>
    	</div>
    	</div>
    	{%/if%}
	            	
	            </div>
	          </div>
	      </div>
	        </div>
 			</div>	
    	</div>
	</div>
</div>
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />

<!-- footer -->

<script>
	$('#sudu').click(function(){
		var inputname=$('#inputuser').val();
		var inputcontent=$('#inputinput').val();
		if(inputname==""){
			$('.style_prl').slideDown(500);	
			return false;
		}else{
			$.get('/templet/addvocation',{'names':inputname,'content':inputcontent},function(data){
            if(data=="error"){
                $('.style_prl').text("分类已存在");
                $('.style_prl').slideDown(500);

            }else{
            	var ht = "<li style='float:left;height: 26px; margin-right: 10px;overflow: hidden;text-overflow:ellipsis;white-space:nowrap; width: 11%;'><label class='checkbox inline'><input type='checkbox' class='cs' id="+data+" value="+data+" name='cssp[]'>"+inputname+" </label></li>";
            	var hd = "<tr id="+data+"><td>"+data+"</td><td>"+inputname+"</td><td>"+inputcontent+"</td><td><a href='/templet/editvocation?id="+data+"' ><i class='icon-pencil'></i></a><a href='/templet/deletevo/void/"+data+"' class='icon-remove ids'></a></td></tr>";
            	// alert(ht);
            	$('#contents').prepend(ht);
            	$('#tables').prepend(hd);

            	// location.href="/templet/mytempl";
            }
          });
        		  return false;

		}
	});	
	$('#inputuser').blur(function(){
    var inputnames=$("#inputuser").val();
    if(inputnames!=""){
      $('.style_prl').slideUp(500);
    }
    		
	});

	function changeOver(obj){
		 obj.style.border="3px solid #46BCF2";
		 obj.nextSibling.style.display="block";	
		 obj.nextSibling.onmouseover=function(){
			this.style.display="block";
			this.previousSibling.style.border="3px solid #46BCF2";
		}
	}

	function changeOut(obj){
		 obj.style.border="1px solid #ccc";
		 obj.nextSibling.style.display="none";
	}

	$('.deltpls').click(function(){
		var del_id=$(this).attr('val');
		var fid=$(this).parent().parent().attr('id');
		$.post('/templet/deltpls',{'delid':del_id},function(data){
			if(data==1){
				$('#'+fid).remove();
			}
		});
	});

	function deltpls(obj){
		var fid=obj.parentNode.parentNode;
		var val=fid.getAttribute('id');
		$.post('/templet/deltpls',{'delid':val},function(data){
			if(data==1){
				fid.parentNode.removeChild(fid);
			}
		});
	}
</script>
{%include "footer.php"%}