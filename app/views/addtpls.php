<!-- header -->
{%include "header.php"%}
<!-- content -->
<script type="text/javascript" src="/dist/js/ajaxfileupload.js"></script>
<div id="content">
	<div id="content-header">
    	<div id="breadcrumb">
    		<a href="/createtem/firstpage" title="首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#">邮件模板管理</a> 
        {%if isset($rel)%}
        <a href="#" class="current"></i>创建预设布局</a>
        {%else%}
        <a href="#" class="current"></i>创建基本布局</a>
        {%/if%}
    	</div>
	</div>	
				
 <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>创建布局模板</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
               <form action="/createtem/doaddtpls" method="post" id="form1" enctype="multipart/form-data">
			  <tbody>
                <tr>
                  <td >模板名称</td>
                  <td>
          <input  type="text" style="height: 24px;" class="span4" id="inputtplnames"  name="tplname" value="{%$tplname%}" maxlength="9"/><span class="help-inline" style="color:red">*</span> ( 9字以内 )
				  </td>
                </tr>
                <tr>
                  <td style="width:20%;">读取URL地址</td>
                  <td>
                  	<input class="span4" style="height: 24px;" id="focusedInput" name="tplurl" type="text" value="http://">
                	  <a id="reads" class="btn " style="margin-bottom: 0px;">读取</a><span style="margin-left: 10px;"><span style="color:red;">*</span> ( 除带有Javascript、Flash、内嵌窗口等大部分URL )</span>
				  </td>
                </tr>
				 <tr>
                  <td style="width:20%;">导入模板文件</td>
                  <td>
                  	<div>
                  	<input id="inputfile" class='span4' name="files" type="file" multiple style="border:1px solid #ccc;height:30px">
                  	<a class="btn " id="ups" onclick="return ajaxFileUpload();" >上传</a><span style="margin-left: 10px;"><span style="color:red;">*</span> ( 格式限制为：HTML )</span>
                 	 </div>
                 
<script>
	function ajaxFileUpload()
  {

     if($('#inputfile').val()==""){
              art.dialog.alert("文件不能为空");
             return false;
    }else{
      var  filename = $('#inputfile').val();
      var  exp = /\.[^\.]+/.exec(filename);
      
      if(exp == '.html'){
            $.ajaxFileUpload
            (
              {
                url:'/templet/uploadtem',
                secureuri:false,
                fileElementId:'inputfile',
                dataType: 'text',
                success: function (data)
                {
                    if(data){
                      CKEDITOR.instances.texts.setData(data) ;
                      CKEDITOR.instances.texts.getData();
                    }
                 },
                error: function (data, status, e)
                {
                  alert(e);
                }
              }
            )
        }else{
              art.dialog.alert("文件格式不正确，请采用正确格式文件");
              return false;
      }
  }
    return false;
  }
</script>
                </td>
                </tr>
				<tr rowspan="2">
                  <td colspan="2">
                    <textarea name="factcontent" id="texts" cols="30" rows="10">{%$tplbody%}</textarea>
                  </td>
        </tr>
				<tr>
          <td colspan="2" style="text-align: center;">
            <input type="hidden" name="rel" value="{%$rel%}" >
            <input type="hidden" name="realid" value="{%$tplid%}" id="editors">
            <a href="javascript:void(0);"  class="btn" style="float: left;" id="external">模板预览</a>
				  <button type="button" id="bt" class="btn">保存</button>
				  </td>
        </tr>
             	</tbody>
               </form>
            </table>
          </div>
        </div>
	  </div>	
	 </div>	
   </div>
</div>
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />
<!-- footer -->
<script>
  CKEDITOR.replace('texts');
   $("body").keydown(function(event) {
             if (event.keyCode == "13") {//keyCode=13是回车键
                 $('#bt').click();
             }
         });
   
  $('#bt').click(function(){
      var valuesname=$("#inputtplnames").val();
      var editors = $('#editors').val();
	  var content =CKEDITOR.instances.texts.getData();
		  if(valuesname == ""){
			  art.dialog.alert("模板名称不能为空");
			  return false;
		  }else{
			  $.post('/templet/checktpl',{'tplnames':valuesname,'realid':editors},function(data){
				  if(data=="nopass"){
					  art.dialog.alert("模板名称重复");
					  return false;
				  }
			  });
			  if(content=="" || content==null){
				  art.dialog.alert("模板内容不能为空");
			      return false;
			  }
			  $('#form1').submit();
		  }
        return false; 
  });
  $('#bt2').click(function(){
      var inputname=$('#inputname').val();
      var inputcontent=$('#inputcontent').val();
      if(inputname==""){
          $('.style_prl').slideDown(500);
          return false;
      }else{
          $.post('/templet/addvocation',{'inputname':inputname,"inputcontent":inputcontent},function(data){
            if(data=="success"){
              location.reload();
            }else if(data=="error"){
                $('.style_prl').text("分类已存在");
                $('.style_prl').slideDown(500);
            }
          });

      }

      return false;
  });
  $('#inputname').blur(function(){
    var inputnames=$("#inputname").val();
    if(inputnames!=""){
      $('.style_prl').slideUp(500);
    }
	});

  $('#reads').click(function(){
          var values=$('#focusedInput').val();
          $.post('/templet/ajaxweb',{'files_contents':values},function(data){
            if(data){
              CKEDITOR.instances.texts.setData(data) ;
              CKEDITOR.instances.texts.getData();
            }
          });
  });

  $("#external").click(function(){
    var content=CKEDITOR.instances.texts.getData();
      $.post('/templet/preview',{'templets':content},function(data){
                  if(data=='111'){
                    window.open('/templet/previewone');
                  }
      }); 

  });
</script> 
{%include "footer.php"%}