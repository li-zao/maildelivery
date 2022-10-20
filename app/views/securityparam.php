{%include file="header.php"%}
<script type="text/javascript">
$(function(){
$.validator.setDefaults({
		errorClass: "val_error"
	});
jQuery.validator.addMethod("isDomain", function(value, element) {    
		return this.optional(element) || (/^[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/.test(value));    
	}, "输入无效");	
	var validator = $("#securityparamid").validate({ 
		rules: {
			mainfield: {    
				required: true,
				isDomain: true  
			},
			threadnum: {    
				required: true,
				pnumber: true  
			},
			queuetime: {    
				required: false,
				pnumber: true  
			},
			logtime: {    
				required: false,    
				pnumber: true    
			}
		},			   
		messages: { 
			mainfield: {    
				required: "请输入域名",
				isDomain: "输入格式错误，请输入正确域名"    
			},
			threadnum: {    
				required: "投递进程并发数",
				pnumber: "输入格式错误，请输入非负整数"    
			},
			queuetime: {    
				required: "",
				pnumber: "输入格式错误，请输入非负整数"  
			},
			logtime: {    
				required: "",    
				pnumber: "输入格式错误，请输入非负整数"    
			}
		}    
	});
	$("#save").click(function() {
		if(validator.form()){
			document.securityparam.action = '/setting/addsecurityparam';   
			document.securityparam.submit();        
		}
	});	
})
</script>
<div id="mask" class="mask" style="display:none;">
	<div class="loadp">
	<font size="5">正在配置,请稍后... ...</font><div class="loading"></div>
	</div>
</div> 
<div id="content">
 <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">参数设置</a> 
	<a href="/setting/securityparam" class="current">投递参数设置</a> 
	</div>
  </div>
   <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>基本信息设置</h5>
          </div>
          <div class="widget-content nopadding">
			<form name="securityparam" id="securityparamid" method="post">
            <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th colspan="2">属性值</th>
                </tr>
              </thead>
			  <tbody>
                <tr>
                  <td style="width:25%;">系统主域声明</td>
                  <td colspan="2">
				  <input style="width:24%;" type="text" value="{%$info[0].mainfield%}" name="mainfield" />
				  </td>
                </tr>
				<tr>
                  <td style="width:25%;">主机名称</td>
                  <td colspan="2"><input style="width:24%;" type="text" value="{%$info[0].hostname%}" name="hostname" /></td>
                </tr>
				<tr>
                  <td style="width:25%;">投递进程并发数</td>
                  <td colspan="2"><input style="width:24%;" type="text" value="{%$threadnum%}" name="threadnum" /></td>
                </tr>
                <tr>
                  <td style="width:25%;">正常队列保存时间</td>
                  <td><input style="width:33%;" type="text" value="{%$info[0].queuetime%}" name="queuetime" />&nbsp;&nbsp;天</td>
                </tr>
                <tr>
                  <td style="width:25%;">日志保存时间</td>
                  <td>
				  <input style="width:33%;" type="text" value="{%$info[0].logtime%}" name="logtime" />&nbsp;&nbsp;天
				  </td>
                </tr>
				 <tr style="height:36px;">
                  <td style="width:25%;">投递邮件退信</td>
                  <td colspan="2">
				  <input type="radio" value="1" {%if $bounce == "1"%}checked{%/if%} name="bounce" />开启&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="radio" value="0" {%if $bounce != "1"%}checked{%/if%} name="bounce" />关闭
				  </td>
                </tr>
				<input type="hidden" name="spid" value="{%$info[0].id%}" />
              </tbody>
            </table>
			</form>
          </div>
        </div>
		<input type="hidden" value="{%$bounce%}" name="returnaddrinput" id="returnaddrinput" />
		<center><button class="btn btn-primary" id="save">保存</button></center>
	  </div>	
	 </div>	
   </div>
</div>
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />
<!--Footer-part-->
{%include file="footer.php"%}
