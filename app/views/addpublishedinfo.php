<!-- header -->
{%include "header.php"%}
<script type="text/javascript">
$(function(){
	$("#bt").click(function(){
		var title = $("#infotitle").val();
		var content = $("texts").val();
		if (title == "" || content == "") {
			art.dialog.alert("信息标题或信息内容不能为空！");
			return false;
		}
		document.infoform.action = "/setting/updateinfo";
		document.infoform.submit ();
	});
	
});
</script>
<div id="content">
	<div id="content-header">
    	<div id="breadcrumb">
    		<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>返回首页</a> <a href="#">系统设置</a><a href="/setting/publishedinfo"></i>信息公告</a><a href="/setting/addinfo" class="current"></i>新建信息</a>
    	</div>
	</div>	
				
 <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>新建信息</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
               <form name="infoform" id="myform" method="post">
			  <tbody>
                <tr>
                  <td >信息标题</td>
                  <td>
					<input type="text" style="height: 24px;" class="input-xlarge" id="infotitle"  name="title" value="{%$infos['title']%}"/><span class="help-inline" style="color:red">*</span>
				  </td>
                </tr>
				<tr rowspan="2">
                  <td colspan="2">
                    <textarea name="content" id="texts" cols="30" rows="10">{%$infos['content']%}</textarea>
                  </td>
				</tr>
				<tr>
				  <td colspan="2" style="text-align: center;">
					<button type="button" id="bt" class="btn">保存</button>
				  </td>
				</tr>
             	</tbody>
				<input type="hidden" value="{%$infos['id']%}" name="infoid" />
               </form>
            </table>
          </div>
        </div>
	  </div>	
	 </div>	
   </div>
</div>

<!-- footer -->
<script>
  CKEDITOR.replace('texts');
</script> 
{%include "footer.php"%}