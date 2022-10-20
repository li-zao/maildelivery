{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	var ifuse = $("#ifuse").val();
	if (ifuse == '1') {
		$(".ifuse").show ();
	} else {
		$(".ifuse").hide ();
	}
	var actiontype = $("#actiontype").val();
	if (actiontype == "enable") {
		$.gritter.add({
			title:	'启用SMTP服务',
			text:	'您已成功启用SMTP服务',
			sticky: false
		});	
	} else if (actiontype == "disable") {
		$.gritter.add({
			title:	'禁用SMTP服务',
			text:	'您已成功禁用SMTP服务',
			sticky: false
		});	
	}
	
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	jQuery.validator.addMethod("isMyIP", function(value, element) { 
		return this.optional(element) || (/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/.test(value));    
	}, "输入IP格式有误，请您核对！");
	
	var validator = $("#smtpform").validate({ 
		rules: {
			smtpip: {
				required: true,
				isMyIP: true
			},
			smtparea: {
				required: true
			}
		},			   
		messages: {
			smtpip: {
				required: "请输入IP地址",
				isMyIP: "输入IP格式有误，请您核对！"
			},
			smtparea: {
				required: "请输入范围"
			}
		}
	});
	$("#save").click(function(){
		if(validator.form()){
			document.smtpform.action='/setting/configsmtpd';   
			document.smtpform.submit();
		}
	});
	
	$("input[name='enablesmtp']").click(function(){
		if ($(this).val() == '1') {
			$(".ifuse").fadeIn ("slow");
		} else if ($(this).val() == '0') {
			$(".ifuse").fadeOut ("slow");
		}
	});
	
});
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">转发设置</a> 
	<a href="/setting/smtpforward" class="current">转发管理配置</a> 
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
		   <form name="smtpform" id="smtpform" method="post">            
		   <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th>属性值</th>
                </tr>
              </thead>
			  <tbody>
                <tr>
                  <td style="width:20%;">SMTP服务</td>
                  <td>
				  <input name="enablesmtp" type="radio" {%if $ifuse == "1"%}checked{%/if%} value="1" style="vertical-align:top" />启用&nbsp;&nbsp;&nbsp;
				  <input name="enablesmtp" type="radio" {%if $ifuse != "1"%}checked{%/if%} value="0" style="vertical-align:top" />禁用
				  </td>
                </tr>
                <tr class="ifuse">
                  <td style="width:20%;">SMTP接收IP</td>
                  <td>
				  <input style="width:33%;" id="smtpip" name="smtpip" type="text" value="{%$smtpip%}" />
				  </td>
                </tr>
				 <tr class="ifuse">
                  <td style="width:20%;">SMTP转发范围</td>
                  <td>
				   <textarea style="width:46%;" name="smtparea" id="smtparea" rows="7">{%$smtparea%}</textarea>
				  <a style="float:right;margin-left:-10%"id="example" data-content="多个范围请用“Enter”键分隔" data-placement="left" data-toggle="popover" class="btn btn-success" href="#" data-original-title="小贴士"><i class="icon-info-sign"></i></a>
				  </td>
                </tr>
              </tbody>
            </table>
			<input type="hidden" name="ifuse" value="{%$ifuse%}" id="ifuse" />
			<input type="hidden" name="actiontype" value="{%$actiontype%}" id="actiontype" />
			</form>
          </div>
        </div>
		<center><button class="btn" id="save">保存</button></center>
	  </div>	
	 </div>	
   </div>
</div>
{%include file="footer.php"%}
