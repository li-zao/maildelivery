{%include file="header.php"%}
<style>
 ul li{list-style:none}
</style>
<script type="text/javascript">
	function tab(str1,str2){
		$(function(){
			//alert($("div[name='box"+str+"']").css("display"));
			if($("div[name='box"+str1+str2+"']").css("display")=="none"){
				$("div[name='box"+str1+str2+"']").css("display","block");
				if(str1 == '001'){
					$("div[name='box002"+str2+"']").css("display","none");
					$("div[name='box003"+str2+"']").css("display","none");
				}else if(str1 == '002'){
					$("div[name='box001"+str2+"']").css("display","none");
					$("div[name='box003"+str2+"']").css("display","none");
				}else{
					$("div[name='box001"+str2+"']").css("display","none");
					$("div[name='box002"+str2+"']").css("display","none");
				}
			}else{
				$("div[name='box"+str1+str2+"']").css("display","none");
				if(str1 == '001'){
					$("div[name='box002"+str2+"']").css("display","none");
					$("div[name='box003"+str2+"']").css("display","none");
				}else if(str1 == '002'){
					$("div[name='box001"+str2+"']").css("display","none");
					$("div[name='box003"+str2+"']").css("display","none");
				}else{
					$("div[name='box001"+str2+"']").css("display","none");
					$("div[name='box002"+str2+"']").css("display","none");
				}
			}
		})
	}
	function packup(str){
		$(function(){
			$("div[name='"+str+"']").css("display","none");
		})
	}
	function del(id){
		var id = id;
		if(confirm("删除后无法恢复，你确定要删除吗?")){
			window.location.href="/contact/delform?id="+id;
		}
	}
</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#" class="current">订阅管理</a><a href="#" class="current">表单列表</a></div>
	</div>
	<div class="container-fluid" >
		<div style="margin-top: 35px;">
			<form action='/contact/doedittemplate' method='post'>
				<table>
					<tr>
						<td><b>编辑HTML</b></td>
					</tr>
					<input type="hidden" name="usid" value="{%$usid%}" />
					<input type="hidden" name="type" value="{%$type%}" />
					<tr>
						<td><textarea name="factcontent" id="texts" cols="30" rows="10">{%$text%}</textarea></td>
					</tr>
					<tr>
						<td><input type="submit" class="btn" value="保存" /></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<div class="row-fluid" style="text-align:right">
		<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
			<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
				{%$page%}
			</div>
		</div>
    </div>
</div>
<script>
CKEDITOR.replace('texts');
//$('#inputname').blur(function(){
//  var inputnames=$("#inputname").val();
//  if(inputnames!=""){
//    $('.style_prl').slideUp(500);
//  }
//	});
//
//// $('#focusedInput')
//$('#reads').click(function(){
//        var values=$('#focusedInput').val();
//        // alert(values);
//        $.post('/templet/ajaxweb',{'files_contents':values},function(data){
//          if(data){
//            CKEDITOR.instances.texts.setData(data) ;
//            CKEDITOR.instances.texts.getData();
//          }
//        });
//});
//
//$("#external").click(function(){
//  var content=CKEDITOR.instances.texts.getData();
//    $.post('/templet/preview',{'templets':content},function(data){
//           // var obj = window.open("about:blank");  
//                if(data=='111'){
//                  window.open('/templet/previewone');
//                }
//    }); 
//
//});
</script>
<input type="hidden" value="{%$per_num%}" id="per_num" />
<input type="hidden" value="{%$search_cont%}" id="search_cont" />
<input type="hidden" value="{%$total_page%}" id="total_page" />
<input type="hidden" value="{%$cur_page%}" id="cur_page" />
<input type="hidden" value="{%$page_type%}" id="page_type" />
<input type="hidden" value="" id="checklist" name="checklist" />
<!--Footer-part-->
{%include file="footer.php"%}