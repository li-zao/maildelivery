{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	var update = $("#update").val();
	if (update == "yes") {
		$.gritter.add({
			title:	'管理端口设置更新',
			text:	'您已成功更新管理端口设置内容',
			sticky: false
		});	
	} else if (update == "no") {
		$.gritter.add({
			title:	'管理端口设置更新',
			text:	'管理端口设置失败，请检查端口配置或其他项！',
			sticky: false
		});	
	}
	var update2 = $("#update2").val();
	if (update2 == "yes") {
		$.gritter.add({
			title:	'服务端口设置更新',
			text:	'您已成功更新服务端口设置内容',
			sticky: false
		});	
	} else if (update2 == "no") {
		$.gritter.add({
			title:	'服务端口设置更新',
			text:	'服务端口设置失败，请检查端口配置或其他项！',
			sticky: false
		});	
	}
	$("#btn1").click(function(){
		art.dialog({
			lock: true,
			background: 'black', 
			opacity: 0.87,	
			content: "确定保存当前配置吗?",
			icon: 'error',
			ok: function () {
				var updateurl = "/setting/updateconsole"; 
				$.post(updateurl, {
								setport: $("#setportid").val(),
								encryption: $("input[name='encryption']:checked").val(),
								port: $("#portid").val(),
								sessiontime: $("select[name='sessiontime']").val(),
								consoleid: $("#consoleid").val(),
								});
				$("#mask").css("height",$(document).height());  
				$("#mask").css("width",$(document).width());  
				$("#mask").show(); 
				setTimeout(function(){
					window.location.href = "/setting/consolesetting";
				}, 10000);
			},
			cancel: true
		});
	})
	$("#btn2").click(function(){
		art.dialog({
			lock: true,
			background: 'black', 
			opacity: 0.87,	
			content: "确定保存当前配置吗?",
			icon: 'error',
			ok: function () {
				var updateurl = "/setting/updateconsole"; 
				$.post(updateurl, {
								setport: $("input[name='setserviceport']").val(),
								// servicencryption: $("input[name='servicencryption']:checked").val(),
								domainname: $("input[name='domainname']").val(),
								serviceport: $("input[name='serviceport']").val(),
								consoleid: $("#consoleid").val(),
								});
				$("#mask").css("height",$(document).height());  
				$("#mask").css("width",$(document).width());  
				$("#mask").show(); 
				setTimeout(function(){
					window.location.href = "/setting/consolesetting";
				}, 10000);
			},
			cancel: true
		});
	})
});
</script>
<div id="mask" class="mask" style="display:none;">
	<div class="loadp">
	<font size="5">正在提交配置信息,请稍后... ...</font><div class="loading"></div>
	</div>
</div> 
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">系统设置</a> 
	<a href="/setting/consolesetting" class="current">管理界面配置</a> 
	</div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>管理端口设置</h5>
          </div>
          <div class="widget-content nopadding">
          <form method="post" name="consolesetting" id="consolesetting">
		   <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th>选项值</th>
                </tr>
              </thead>
			  <tbody>
                <tr>
                  <td style="width:20%;">HTTPS</td>
                  <td>
				  <input name="encryption" {%if $infos.https == "1"%}checked{%/if%} type="radio" value="1" style="vertical-align:top" />启用&nbsp;&nbsp;&nbsp;
				  <input name="encryption" {%if $infos.https != "1"%}checked{%/if%}  type="radio" value="0" style="vertical-align:top" />禁用
				  </td>
                </tr>
                <tr>
                  <td style="width:20%;">端口</td>
                  <td><input style="width:13%;" name="port" id="portid" type="text" value="{%$infos.port%}" /></td>
                </tr>
                <tr>
                  <td style="width:20%;">端口超时时间</td>
                  <td>
				  <select name="sessiontime" style="width:13%">
				  <option value="5" {%if $infos.sessiontime == '5'%}selected{%/if%}>5分钟</option>
				  <option value="10" {%if $infos.sessiontime == '10'%}selected{%/if%}>10分钟</option>
				  <option value="15" {%if $infos.sessiontime == '15'%}selected{%/if%}>15分钟</option>
				  <option value="30" {%if $infos.sessiontime == '30'%}selected{%/if%}>30分钟</option>
				  </select>
				  </td>
				  <input type="hidden" id="consoleid" name="consoleid" value="{%$infos.id%}" />
				  <input type="hidden" id="update" name="update" value="{%$update%}" />
				  <input type="hidden" name="setport" id="setportid" value="setport" />
                </tr>
              </tbody>
            </table>
            </form>
          </div>
        </div>
        <center><button class="btn" id="btn1">保存</button></center>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>订阅服务端口设置</h5>
          </div>
          <div class="widget-content nopadding">
          <form method="post" name="serviceconsolesetting" id="serviceconsolesetting">
		   <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th>选项值</th>
                </tr>
              </thead>
			  <tbody>
                <!-- <tr>
                  <td style="width:20%;">HTTPS</td>
                  <td>
				  <input name="servicencryption" {%if $infos.https == "1"%}checked{%/if%} type="radio" value="1" style="vertical-align:top" />启用&nbsp;&nbsp;&nbsp;
				  <input name="servicencryption" {%if $infos.https != "1"%}checked{%/if%}  type="radio" value="0" style="vertical-align:top" />禁用
				  </td>
                </tr> -->
                <tr>
                  <td style="width:20%;">域名 / IP</td>
                  <td><input style="width:13%;" name="domainname" type="text" value="{%$infos.domainname%}" />
				  <a style="float:right;margin-left:-10%"id="example" data-content="修改域名或IP需重启系统生效，之前的任务统计信息不再更新！" data-placement="left" data-toggle="popover" class="btn btn-success" href="#" data-original-title="注意！"><i class="icon-info-sign"></i></a>
				  </td>
				</tr>
                <tr>
                  <td style="width:20%;">服务端口</td>
                  <td><input style="width:13%;" name="serviceport" type="text" value="{%$infos.serviceport%}" /></td>
                </tr>
				  <input type="hidden" id="consoleid" name="consoleid" value="{%$infos.id%}" />
					<input type="hidden" id="update2" name="update" value="{%$serviceupdate%}" />
					<input type="hidden" name="setserviceport" value="setserviceport" />
              </tbody>
            </table>
            </form>
          </div>
        </div>
		<center><button class="btn" id="btn2">保存</button></center>
	  </div>	
	 </div>	
   </div>	
</div>
{%include file="footer.php"%}
