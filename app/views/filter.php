{%include file="header.php"%}
<style>
        .col{color:red;}
</style>
<script type="text/javascript">

</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#" class="">联系人管理</a><a href="#" class="current">联系人筛选</a> </div>
	</div>
	<div class="container-fluid">
		<div class="widget-box" style="margin-top: 35px;padding-bottom: 10px;border-bottom: 1px solid #cdcdcd;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>联系人筛选</h5>
				<div style="widht:120px;float:right; margin-top: 1px; margin-right: 5px;">
					<a href="/contact/addfilter" class="btn" style="margin-top:2px;font-size:12px;">新增筛选器</a>
				</div>
			</div>
			<!-- <div class="">
				<div id="DataTables_Table_0_length" class="dataTables_length">
					<label style="height:30px;">
						<form action="/contact/filter" method="GET" style="width:106px">
							<select name="num" size="1" aria-controls="DataTables_Table_0" style="width:60px">
								<option {%if $num==5 %}selected="selected"{%/if%} value="5">5</option>
								<option {%if $num==10 %}selected="selected"{%/if%} value="10">10</option>
								<option {%if $num==25 %}selected="selected"{%/if%} value="25">25</option>
								<option {%if $num==50 %}selected="selected"{%/if%} value="50">50</option>
								<option {%if $num==100 %}selected="selected"{%/if%} value="100">100</option>
							</select>
							<input type="submit" value="GO" />
						</form>		
					</label>
				</div>
			</div> -->
			<div class="widget-content nopadding">
				<table width="80%" align="center"  class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="10%" style="text-align:center"><input type="checkbox" id="checkAll" >&nbsp;&nbsp;全选</th>
							<th width="25%" style="text-align:center">名称</th>
							<th width="30%" style="text-align:center">描述</th>
							<th width="15%" style="text-align:center">创建人</th>
							<th width="25%" style="text-align:center">操作</th>
						</tr>
					</thead>
					<tbody>
					{%section name=loop loop=$data%}
						<tr>
							<td style="text-align:center"><input type="checkbox" name="ck[]" value="{%$data[loop].id%}" /></td>
							<td width="25%" style="text-align:center"><a href="/contact/personlist?fid={%$data[loop].id%}">{%$data[loop].name%}</a></td>
							<td width="25%" style="text-align:center">{%$data[loop].description%}</td>
							{%if $data[loop].uid == ""%}
								<td style="text-align:center">创建人已删除</td>
							{%else%}
								<td style="text-align:center">{%$data[loop].uid%}</td>
							{%/if%}
							<td width="25%" style="text-align:center"><span><a href="/contact/addfilter?id={%$data[loop].id%}"><i class="icon-edit">编辑</i></a></span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a onclick="del({%$data[loop].id%})" style="cursor:pointer;"><i class="icon-remove">删除</i></a></td>
						</tr>
					{%/section%}
					</tbody>
					<tr class="odd hasnodata" id="hasnodata">
					<td class="dataTables_empty" align="center" valign="top" colspan="5">
						没有找到任何数据
					</td>
			  </tr>
				</table>
			</div>
			<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="margin-top: 5px;">
				<!-- <div id="DataTables_Table_0_filter" class="dataTables_filter">
					<a href="/contact/addfilter" class="btn" style="margin-top: -3px;">+添加筛选器</a>
				</div> -->
				<div class="row-fluid" style="text-align:right; margin-top: 0px;">
					<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
						<div id="alldel" style="margin-left: 16px;float:left;margin-top:2px;"><input id="delall" type="button" class="btn" value="批量删除" style="margin-top: 2px;font-size:12px;" /></div>
						<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;center;float:left;margin-left:300px;">
							{%$page%}
						</div>
						<div id="DataTables_Table_0_length" class="dataTables_length" style="float:right;width:100px;margin-right:15px">
							<label>
								<form action="/contact/filter" method="GET" style="width:106px">
									<select name="num" size="1" aria-controls="DataTables_Table_0" style="width:60px">
										<option {%if $num==5 %}selected="selected"{%/if%} value="5">5</option>
										<option {%if $num==10 %}selected="selected"{%/if%} value="10">10</option>
										<option {%if $num==25 %}selected="selected"{%/if%} value="25">25</option>
										<option {%if $num==50 %}selected="selected"{%/if%} value="50">50</option>
										<option {%if $num==100 %}selected="selected"{%/if%} value="100">100</option>
									</select>
									<input type="submit" value="GO" style="font-size:12px;height:26px;" />
								</form>		
							</label>
						</div>
					</div>
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
<script>
$(function(){
	$("#checkAll").click(function(){
		if($("#checkAll").attr("checked")){
			$.each($("input[name='ck[]']"),function(i,result){
				$(this).attr("checked",true);
			})
		}else{
			$.each($("input[name='ck[]']"),function(i,result){
				$(this).attr("checked",false);
			})
		}
	})
})

$(function(){
		$("#delall").click(function(){
			var ck = $("input[name='ck[]']:checked");
			var ids = "";
			$.each(ck,function(i,result){
				ids += $(this).val()+",";
			})
			if(ids == ""){
				art.dialog.alert("请选择批量删除的条目");
				return false;
			}
			art.dialog.confirm("您将要删除所选中的条目，是否继续？",function(){
				$.ajax({
					type:"post",
					url:"ajaxdelallfilter",
					data:{'ids':ids},

					success:function(data){
						location.reload();
					}
				})
			})
		})
})

function del(id){
		confirm_ = art.dialog.confirm('确定删除该筛选器？',function(){
			$.post('/contact/delfilter',{'id':id},function(data){
					location.reload();
			});
		})
}
</script>
<!--Footer-part-->
{%include file="footer.php"%}