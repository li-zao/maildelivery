{%include file="header.php"%}
<script type="text/javascript" src="/dist/js/ajaxfileupload.js"></script>
<script type="text/javascript">
function deleteMachineInfoFile(){
	window.location.href = "/setting/downloadmdapkey";	    
}
function restartDserverce () {
	art.dialog({
		lock: true,
		background: 'black', 
		opacity: 0.87,	
		content: "您将要重启投递服务",
		icon: 'error',
		ok: function () {
			//window.location.href = "/setting/resetsetting?actiontype=rds";
			var updateurl = "/setting/resetsetting"; 
			$.get(updateurl, {actiontype: "rds"});
			$("#mask").css("height",$(document).height());  
			$("#mask").css("width",$(document).width());  
			$("#mask").show(); 
			setTimeout(function(){
				window.location.href = "/setting/license";
			}, 20000);
		},
		cancel: true
	});
}
</script>
<div id="mask" class="mask" style="display:none;">
	<div class="loadp">
	<font size="5">正在重启投递服务,请稍后... ...</font><div class="loading"></div>
	</div>
</div> 
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">系统设置</a> 
	<a href="/setting/license" class="current">授权管理</a> 
	</div>
  </div>
   <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>授权基本信息</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th>属性值</th>
                </tr>
              </thead>
			  <tbody>
                <tr>
                  <td style="width:20%;">单日最大发送量</td>
                  <td>
				 {%$infos['capacity']%}
				  </td>
                </tr>
				 <tr>
                  <td style="width:20%;">授权状态</td>
                  <td>
				  {%if $infos['store_ok'] == 1%}
				  授权正常
				  {%else%}
				  授权错误
				  {%/if%}
				  </td>
                </tr>
				<tr>
                  <td style="width:20%;">授权时间</td>
                  <td>
				  始于：{%$infos['start_year']%}年{%$infos['start_month']%}月{%$infos['start_date']%}日，
				  止于：{%$infos['end_year']%}年{%$infos['end_month']%}月{%$infos['end_date']%}日。
				  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>授权基本操作</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th>属性值</th>
                </tr>
              </thead>
			  <form method="post" action="#" name="licenseform">
			  <tbody>
                <tr>
                  <td style="width:20%;">机器码信息</td>
                  <td>
				 <input class="btn input-xlarge" onclick="deleteMachineInfoFile()" id="downloadmdapkey" value="下载机器码信息文件" style="margin:0 0 0 0" />
				  </td>
                </tr>
				 <tr>
                  <td style="width:20%;">导入授权文件</td>
                  <td>
				                   	<div>
                  	<input id="inputfile" class="span4" name="files" type="file" multiple style="border:1px solid #ccc;height: 25px; padding-bottom: 0px; width: 296px;">
                  	<a class="btn" id="ups" onclick="return ajaxFileUpload();" >上传</a>
                 	 </div>
                 
<script type="text/javascript">
	function ajaxFileUpload()
  {
    $.ajaxFileUpload
    (
      {
        url:'/setting/uploadlicese',
        type:'post',
        secureuri:false,
        fileElementId:'inputfile',
        dataType: 'text',
        // data:{name:'logan', id:'id'},
        success: function (data)
        {
            if(data == "success"){
              art.dialog.alert("导入授权文件成功");
            } else {
				alert("导入出错");
			}
        },
        error: function (data, status, e)
        {
          alert(e);
        }
      }
    )
    return false;
  }
</script>
				 
				 
				 
				 
				 </td>
                </tr>
				<tr>
                  <td style="width:20%;">重启投递服务</td>
                  <td>
				  <input class="btn input-xlarge" onclick="restartDserverce()" value="重启投递服务" style="margin:0 0 0 0" />
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
{%include file="footer.php"%}
