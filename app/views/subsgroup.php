{%include file="header.php"%}

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="返回主页" class="tip-bottom"><i class="icon-home"></i> 主页</a> <a href="#" class="current">联系人组</a> </div>
   
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>联系人列表</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
				  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" /></th>
                  <th>组名</th>
                  <th>描述</th>
                  <th>创建时间</th>
                  <th>修改时间</th>
				  <th>操作</th>
                </tr>
              </thead>
              <tbody>
				{%section name=groups loop=$groups%}
                <tr class="gradeX">
				  <td><input type="checkbox" value="{%$groups[groups].id%}" /></td>
                  <td>{%$groups[groups].id%}</td>
                  <td>{%$groups[groups].description%}</td>
                  <td>{%$groups[groups].createtime%}</td>
                  <td>{%$groups[groups].lastmodify%}</td>
				  <td><div class="pull-center"> <a class="tip" href="#" title="编辑"><i class="icon-pencil"></i></a> <a class="tip" href="#" title="删除"><i class="icon-remove"></i></a> </div></td>
                </tr>
				{%/section%}
				
              </tbody>
			  <tr class="odd hasnodata" id="hasnodata">
					<td class="dataTables_empty" align="center" valign="top" colspan="5">
						没有找到任何数据
					</td>
			  </tr>
            </table>
			{%include file="pagination.php"%}
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

<!--Footer-part-->
{%include file="footer.php"%}
