{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	var actiontype = $("#actiontype").val();
	if (actiontype == "custom") {
		$.gritter.add({
			title:	'时间设置',
			text:	'您已成功设置系统时间',
			sticky: false
		});	
	} else if (actiontype == "ntp") {
		$.gritter.add({
			title:	'启用时钟服务',
			text:	'时钟服务设置成功！',
			sticky: false
		});	
	} else if(actiontype == "no"){
		$.gritter.add({
			title:	'禁用时钟服务',
			text:	'禁用时钟服务设置成功！',
			sticky: false
		});	
	}
	
	// refresh the sys time at every second
	function refreshtime () {
		var url = '/setting/refreshsysclock';
		var ajax = {
			url: url, type: 'POST', dataType: 'html', cache: false, success: function(html){
					$("#timespan").html(html);
				}					
		};
		jQuery.ajax(ajax);
	}
	setInterval(refreshtime,1000);
	
	 $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd HH:ii:ss",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"
    });
	// the sys clock setting
	$("#customtime").click(function(){
		var cus_time = $("#settime").val();
		var reg = /^((((1[6-9]|[2-9]\d)\d{2})-(0?[13578]|1[02])-(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})-(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))(\s(([01]\d{1})|(2[0123])):([0-5]\d):([0-5]\d))?$/;
		if (cus_time == "") {
			art.dialog.alert("请输入时间！");
			return false;
		} else {
			if (!reg.test(cus_time)) {
				art.dialog.alert("输入时间格式错误！");
				return false;
			}
		}
		document.sysclock.action='/setting/customtime';   
		document.sysclock.submit();
	});
	
	var ntpstatus = $("#ntpstatus").val();
	if (ntpstatus == 0) {
		$(".extentntp").hide();
	} else if (ntpstatus == 1) {
		$(".extentntp").show();
		$("#special").attr("rowspan", 4);
	}
	
	$("input[name='status']").click(function(){
		if ($(this).val() == '1') {
			$(".extentntp").fadeIn ("slow");
			$("#special").attr("rowspan", 4);
		} else if ($(this).val() == '0') {
			$(".extentntp").fadeOut ("slow");
		}
	});
	
	
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	jQuery.validator.addMethod("isMyIP", function(value, element) { 
		return this.optional(element) || (/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/.test(value));    
	}, "IP地址格式有误，请您核对！");
	var validator = $("#sysclock").validate({ 
		rules: {
			ip: {
				required: true,
				isMyIP: true
			}
		},			   
		messages: {
			ip: {
				required: "请输入时钟服务器IP",
				isMyIP: "IP地址格式有误，请您核对！"
			}
		}    
	});
	$("#save").click(function(){
		if(validator.form()){
			document.sysclock.action='/setting/updatentp';   
			document.sysclock.submit();
		}
	});
});
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">系统设置</a> 
	<a href="/setting/sysclocksetting" class="current">时间设置</a> 
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
		   <form name="sysclock" id="sysclock" method="post">
            <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th>属性值</th>
				  <th style="width:10%;">操作</th>
                </tr>
              </thead>
			  <tbody>
                <tr>
                  <td style="width:20%;">当前系统时间</td>
                  <td>
				  <span id="timespan">{%$systime%}</span>
				  </td>
				  <td>
				  </td>
                </tr>
                <tr>
                  <td style="width:20%;">系统时间设置</td>
                  <td>
				  <div class="input-append date form_datetime"><input type="text" value="" name="settime" id="settime" /><span class="add-on"><i class="icon-calendar"></i></span></div>
				  <a style="float:right;margin-left:-10%"id="example" data-content="时间格式：年年年年-月月-日日【空格】时时:分分:秒秒，精确时间可自行手动填写！" data-placement="left" data-toggle="popover" class="btn btn-success" href="#" data-original-title="小贴士"><i class="icon-info-sign"></i></a> 
				 </td>
				  <td><button class="btn" id="customtime">提交</button></td>
                </tr>
                <tr>
                  <td style="width:20%;">是否启用时钟服务</td>
                  <td>
					<input name="status" {%if $ntp.status == "1"%}checked{%/if%} type="radio" value="1" style="vertical-align:top" />启用&nbsp;&nbsp;&nbsp;
					<input name="status" {%if $ntp.status != "1"%}checked{%/if%} type="radio" value="0" style="vertical-align:top" />禁用
				  </td>
				  <td id="special">
				  <button class="btn" id="save">提交</button>
				  </td>
                </tr>
				
				<tr class="extentntp">
                  <td style="width:20%;">时钟服务器IP</td>
                  <td>
					<input style="width:33%;" name="ip" type="text" value="{%$ntp.ip%}" />
				  </td>
                </tr>
              </tbody>
            </table>
			<input type="hidden" value="{%$ntp.status%}" name="ntpstatus" id="ntpstatus" />
			<input type="hidden" value="{%$ntp.id%}" name="ntpid" id="ntpid" />
			<input type="hidden" value="{%$actiontype%}" name="actiontype" id="actiontype" />
			</form>
          </div>
        </div>
	  </div>	
	 </div>	
   </div>
</div>
{%include file="footer.php"%}
