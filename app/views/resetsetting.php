{%include file="header.php"%}
<script type="text/javascript">
function syshandle (val) {
	var str = "";
	if (val == 'r') {
		str = "您将要重启设备，是否继续？";
	} else if (val == 's') {
		str = "您将要关闭设备，是否继续？";
	} else if (val == 'rdd') {
		str = "您将要重启数据库服务，是否继续？";
	} else if (val == 'rdt') {
		str = "您将要重启SMTP服务，是否继续？";
	} else if (val == 'rds') {
		str = "您将要重启邮件投递服务，是否继续？";
	} else if (val == 'rda') {
		str = "您将要重启信任服务，是否继续？";
	} else if (val == 'rdc') {
		str = "您将要重启转换服务，是否继续？";
	} else if (val == 'rdf') {
		str = "您将要重启邮件过滤服务，是否继续？";
	}
	art.dialog({
		lock: true,
		background: 'black', 
		opacity: 0.87,	
		content: str,
		icon: 'error',
		ok: function () {
			//window.location.href = "/setting/resetsetting?actiontype=" + val;
			var updateurl = "/setting/resetsetting"; 
			$.get(updateurl, {actiontype: val});
			$("#mask").css("height",$(document).height());  
			$("#mask").css("width",$(document).width());  
			$("#mask").show(); 
			setTimeout(function(){
				window.location.href = "/setting/resetsetting";
			}, 20000);
		},
		cancel: true
	});
}
function loadservice () {
	var url = '/setting/checkroutingip?mode=2';
		var ajax = {
			url: url, type: 'GET', dataType: 'html', cache: false, success: function(data){
					var statuslist = data.split("@");
					if (statuslist[0] != "0") {
						$("#rdd").text('异常');
						$("#rdd").css("color","red");
					} else {
						$("#rdd").text('正常');
						$("#rdd").css("color","blue");
					}
					if (statuslist[1] != "0") {
						$("#rdt").text('异常');
						$("#rdt").css("color","red");
					} else {
						$("#rdt").text('正常');
						$("#rdt").css("color","blue");						
					}
					if (statuslist[2] != "0") {
						$("#rds").text('异常');
						$("#rds").css("color","red");
					} else {
						$("#rds").text('正常');
						$("#rds").css("color","blue");
					}
					if (statuslist[3] != "0") {
						$("#rda").text('异常');
						$("#rda").css("color","red");
					} else {
						$("#rda").text('正常');
						$("#rda").css("color","blue");
					}
					if (statuslist[4] != "0") {
						$("#rdc").text('异常');
						$("#rdc").css("color","red");
					} else {
						$("#rdc").text('正常');
						$("#rdc").css("color","blue");
					}						
					/*AUDIT_CLOSED if (statuslist[4] != "0") {
						$("#rdf").text('异常');
						$("#rdf").css("color","red");
					} else {
						$("#rdf").text('正常');
						$("#rdf").css("color","blue");
					}*/
				}					
		};
		jQuery.ajax(ajax);
}
$(function(){
	loadservice();
});
</script>
<div id="mask" class="mask" style="display:none;">
	<div class="loadp">
	<font size="5">正在重启服务,请稍后... ...</font><div class="loading"></div>
	</div>
</div> 
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">系统设置</a> 
	<a href="/setting/resetsetting" class="current">系统维护</a> 
	</div>
  </div>
   <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>基本操作</h5>
          </div>
          <div class="widget-content nopadding">
			<form method="post" name="sysform">
            <table class="table table-bordered table-striped">
			  <tbody>
				<tr>
                  <td style="width:20%;">数据库服务</td>
				  <td style="width:5%;text-align: center;"><span id="rdd"></span></td>
                  <td><a class="btn btn-primary" style="margin-left: 20px;" onclick="javascript:void(syshandle('rdd'))">重启服务</a></td>
                </tr>
				<tr>
                  <td style="width:20%;">SMTP服务</td>
				  <td style="width:5%;text-align: center;"><span id="rdt"></span></td>
                  <td><a class="btn btn-primary" style="margin-left: 20px;" onclick="javascript:void(syshandle('rdt'))">重启服务</a></td>
                </tr>
				<tr>
                  <td style="width:20%;">投递服务</td>
				  <td style="width:5%;text-align: center;"><span id="rds"></span></td>
                  <td><a class="btn btn-primary" style="margin-left: 20px;" onclick="javascript:void(syshandle('rds'))">重启服务</a></td>
                </tr>
				<tr>
                  <td style="width:20%;">信任服务</td>
				  <td style="width:5%;text-align: center;"><span id="rda"></span></td>
                  <td><a class="btn btn-primary" style="margin-left: 20px;" onclick="javascript:void(syshandle('rda'))">重启服务</a></td>
                </tr>
				<tr>
                  <td style="width:20%;">转换服务</td>
				  <td style="width:5%;text-align: center;"><span id="rdc"></span></td>
                  <td><a class="btn btn-primary" style="margin-left: 20px;" onclick="javascript:void(syshandle('rdc'))">重启服务</a></td>
                </tr>
				<!--AUDIT_CLOSED
				<tr>
                  <td style="width:20%;">邮件过滤服务</td>
				  <td style="width:5%;text-align: center;"><span id="rdf"></span></td>
                  <td><a class="btn btn-primary" style="margin-left: 20px;" onclick="javascript:void(syshandle('rdf'))">重启服务</a></td>
                </tr>
				!-->
                <tr>
                  <td style="width:20%;">设备重启</td>
				  <td style="width:5%;text-align: center;"><span>-</span></td>
                  <td><a class="btn btn-primary" style="margin-left: 20px;" onclick="javascript:void(syshandle('r'))">设备重启</a></td>
                </tr>
                <tr>
                  <td style="width:20%;">设备关闭</td>
				  <td style="width:5%;text-align: center;"><span>-</span></td>
                  <td><a class="btn btn-primary" style="margin-left: 20px;" onclick="javascript:void(syshandle('s'))">设备关闭</a></td>
                </tr>
              </tbody>
            </table>
			</form>
          </div>
        </div>
	  </div>	
	 </div>	
   </div>
</div>
{%include file="footer.php"%}
