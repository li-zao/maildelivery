{%include file="header.php"%}
<style>
        .col{color:red;}
        #dv1{float:left;}
        #dv2{float:left;}
</style>
<script type="text/javascript">

</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb">
		<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
		<a href="#" class="">联系人管理</a>
		<a href="/setting/subscribe" class="current">订阅管理</a> 
		</div>
	</div>
	<div class="container-fluid" >
			<div class="widget-box" style="background:#FFFFFF;margin-top:35px;">
				<div class="widget-title"> <span class="icon"><i class="icon-inbox"></i></span>
					<h5>订阅管理</h5>
				</div>
				<div class="widget-content">
					<div style="width:90%;text-align:center;"><p style="text-align:center; line-height:40px;margin-left: 40px;background:#F1F1F1;border-radius:3px;margin-bottom: 20px; margin-top: 10px; padding-top: 5px;">您还没有创建表单，<a href="/contact/createform" style="text-decoration:underline;color:#0066FF;">点此创建</a></p></div>
					<div style="text-align:center;height: 430px;margin-left: 20px; ">
						<div id="dv1" style="margin-left: 20px;float:left;"><img src="/dist/img/explame.jpg"></div>
						<div id="dv2" style="margin-left: 20px;float:left;"><img src="/dist/img/buzhou.jpg"></div>
					</div>
				</div>
			</div>
	</div>			
</div>
<input type="hidden" value="{%$per_num%}" id="per_num" />
<input type="hidden" value="{%$search_cont%}" id="search_cont" />
<input type="hidden" value="{%$total_page%}" id="total_page" />
<input type="hidden" value="{%$cur_page%}" id="cur_page" />
<input type="hidden" value="{%$page_type%}" id="page_type" />
<input type="hidden" value="" id="checklist" name="checklist" />
<!--Footer-part-->
{%include file="footer.php"%}