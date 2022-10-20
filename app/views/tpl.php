<!-- header -->
{%include "header.php"%}

<!-- content -->
<script type="text/javascript" src="/dist/js/tplpage.js"></script>
<script type="text/javascript" src="/dist/js/searchmytemplpage.js"></script>
<div id="content">
	<div id="content-header">
    	<div id="breadcrumb">
    		<a href="/createtem/firstpage" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">邮件模板管理</a> <a href="#" class="current"></i>基础布局</a>
    	</div>
    	<!-- <hr> -->
	</div>	

 	<div id="container-fluid">
 		<div class="row-fluid" style="padding:20px;">
 			<div class="span12"><h3><i class="icon-th-large"></i>基础布局分类</h3></div>
 			<div class="span12" style="padding: 5px;">    
 				
			    <a href="/createtem/addtpls" role="button" class="btn btn-primary" data-toggle="modal">添加布局</a>
			    
 			</div>
 		</div>	
 		<div class="row-fluid" style="width:980px;margin-left:90px;">
		{%section name=loop loop=$tpls%}
				<div class="choseone">	
			<dl class="inline" style="">
				<dt class="text-center">{%$tpls[loop].tpl_name%}</dt>
				<dd style="margin-right: 15px;" class="actions">
					<span class="hov" href="createtempl/tid/{%$tpls[loop].id%}"> <img src="/dist/img/{%$tpls[loop].tpl_img%}" alt="" class="img-rounded">
					</span>
				</dd>
			</dl>
			<div class="chose" >
				<a href="/templet/tplview/relid/{%$tpls[loop].id%}" target="_blank" title="预览"><i class="icon-eye-open icon-large"></i></a>
				<a href="/templet/createtempl/tid/{%$tpls[loop].id%}" title="创建"><i class="icon-file icon-large"></i></a>
				<a href="/createtem/addtpls/rela/{%$tpls[loop].id%}" title="编辑"><i class="icon-edit icon-large"></i></a>
				<a href="/templet/index/doid/{%$tpls[loop].id%}" title="下载"><i class="icon-download-alt icon-large"></i></a>
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

<!-- footer -->
{%include "footer.php"%}