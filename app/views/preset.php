<!-- header -->
{%include "header.php"%}
<!-- content -->
<!-- <script type="text/javascript" src="/dist/js/presetpage.js"></script> -->
<script type="text/javascript" src="/dist/js/searchmytemplpage.js"></script>
<div id="content">
	<div id="content-header">
    	<div id="breadcrumb">
    		<a href="/createtem/firstpage" title="首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#">邮件内容管理</a> <a href="#" class="current"></i>预设模板</a>
    	</div>
    	<!-- <hr> -->
	</div>	

 	<div class="container-fluid">
 		<div class="row-fluid" >
			<div class="span12">
 			<div class="widget-box">
	          <div class="widget-title"> <span class="icon"> <i class="icon-tasks"></i> </span>
	            <h5>预设模板</h5>
	          </div>
	          <div class="widget-content">
	          	<div id='container'>
	          	<div class='row-fluid'>
		          	<div class="span12" style="padding: 5px;">  
		          		<p style="border-bottom: 1px solid #A4A4A4;display: block;height: 30px;width: 100%;"><span style="border-radius: 5px 5px 0 0;background: none repeat scroll 0 0 #A4A4A4;color: #FFFFFF;display: block;font-size: 12px;font-weight: bold;height:30px;line-height: 30px;text-align:center;width: 10%;float: left;">模板内容</span>
						    <a href="/createtem/addtpls?rel=nection" class="btn " id="addpre" style='float: right;'>+新增预设</a>
						</p>
					</div>

		<div class="row-fluid" style="width:980px;margin-left:70px;">
		{%section name=loop loop=$preset%}
			<div class="choseone" id="{%$preset[loop].id%}">	
			<dl class="inline" >
				<dt class="text-center">{%$preset[loop].tpl_name%}</dt>
				<dd style="margin-right: 15px; height: 140px; width: 110px" class="actions"><span href="createtempl/tid/{%$preset[loop].id%}"> <img src="/dist/thumb_images/{%$preset[loop].tpl_img%}" alt="" class="img-rounded" style="width: 104px;height: 133px;"></span></dd>
			</dl>
			<div class="chose" style="text-align:center;">
				<a href="/templet/tplview/relid/{%$preset[loop].id%}" target="_blank" title="预览"><i class="icon-eye-open icon-large"></i></a>
				<!--{%if $role=='stasker' || $role=='tasker'%}
				<a href="/templet/createtempl/tid/{%$preset[loop].id%}" title="创建"><i class="icon-file icon-large"></i></a>
				{%/if%}-->
				<a href="/createtem/addtpls/rel/nection/rela/{%$preset[loop].id%}" title="编辑"><i class="icon-edit icon-large"></i></a>
				<a href="/templet/preset/doid/{%$preset[loop].id%}" title="下载"><i class="icon-download-alt icon-large"></i></a>
				{%if $preset[loop].id >49 %}
					<a href="javascript:void(0)"  class="deltpls" val="{%$preset[loop].id%}" title="删除"><i class="icon-remove icon-large"></i></a>
				{%/if%}
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
$('.deltpls').click(function(){
	var del_id=$(this).attr('val');
	var fid=$(this).parent().parent().attr('id');
	$.post('/templet/deltpls',{'delid':del_id},function(data){
		if(data==1){
			$('#'+fid).remove();
		}
	});
});
</script>
{%include "footer.php"%}