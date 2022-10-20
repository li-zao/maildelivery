{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	var actiontype = $("#actiontype").val();
	if (actiontype == "insert") {
		$.gritter.add({
			title:	'添加设置',
			text:	'您已成功添加邮件回送设置',
			sticky: false
		});	
	} else if (actiontype == "update") {
		$.gritter.add({
			title:	'更新设置',
			text:	'您已成功更新邮件回送设置',
			sticky: false
		});	
	}
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	var validator = $("#alertform1").validate({ 
		rules: {
			cpu: {    
				required: false,
				pnumber: true,
				max:99,
				digits:true 
			},
			mqueue: {
				required: false,
				pnumber: true,
				digits:true 
			},
			subarea: {
				required: false,
				pnumber: true,
				max:99,
				digits:true 
			},
			dqueue: {
				required: false,
				pnumber: true,
				digits:true 
			}
		},			   
		messages: {    
			cpu: {    
				required: "",
				pnumber: "请输入非负整数",
				max:jQuery.validator.format("请输入100以内的值"),
				digits:'数值必须为整数'
			},
			mqueue: {
				required: "",
				pnumber: "请输入非负整数",
				digits:'数值必须为整数'
			},
			subarea: {
				required: "",
				pnumber: "请输入非负整数",
				max:jQuery.validator.format("请输入100以内的值"),
				digits:'数值必须为整数'
			},
			dqueue: {
				required: "",
				pnumber: "请输入非负整数",
				digits:'数值必须为整数'
			}
		}    
	});
	
	$("#save").click(function(){
		if (validator.form()) {
			var str = "";
			var cpu = $("#cpu").val();
			if (cpu != "") {
				str = "has value";
			}
			var mqueue = $("#mqueue").val();
			if (mqueue != "") {
				str = "has value";
			}
			var subarea = $("#subarea").val();
			if (subarea != "") {
				str = "has value";
			}
			var dqueue = $("#dqueue").val();
			if (dqueue != "") {
				str = "has value";
			}
			if (str == "") {
				art.dialog.alert("请至少输入一项内容！");
				return false;
			}
			var recipients1 = $("#recipients1").val();
            var addrs = [];
            if ( recipients1.indexOf( "\r\n" ) >= 0 ) {
                var addrs = recipients1.split("\r\n");
            } else if ( recipients1.indexOf( "\n" ) >= 0 ) {
                var addrs = recipients1.split("\n");
            }
            if (addrs.length > 0) {
                var rntArray=[], temp, hasValue;
                for (var i in addrs) {
                    temp = addrs[i];
                    hasValue = false;
                    for (var j in rntArray) {
                        if (temp === rntArray[j]) {
                            hasValue = true;
                            break;
                        }
                    }
                    if (hasValue === false) {
                        rntArray.push(temp);
                    }
                }
                recipients1 = rntArray.join("\r\n");
                document.getElementById("recipients1").value = recipients1;
            }
			if (!checkEmail(recipients1)) {
				art.dialog.alert("输入邮箱有误，请重新输入！");
				return false;
			}
			document.alertform.action = "/setting/addalertsetting";
			document.alertform.submit ();
		}
	});
});

function checkEmail(emails) {
	if ( emails.indexOf( "\r\n" ) >= 0 ) {	
		var addrs = emails.split("\r\n");
	} else if ( emails.indexOf( "\n" ) >= 0 ) {
		var addrs = emails.split("\n");
	} else {
		var addrs = emails.split("\r");
	}
	var email_patt = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
	if (addrs.length > 0) {
	    for (var i = 0; i < addrs.length; i++) {
	        if (addrs[i] == "") {
	            continue;
	        }
	        if (!email_patt.test(addrs[i])) {
	            return false;
	        }
	    }
	}
	return true;
}
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">系统设置</a> 
	<a href="/setting/alertsetting" class="current">系统报告配置</a> 
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
            <form name="alertform" id="alertform1" method="post">
			<table class="table table-bordered table-striped">
			  <tbody>
                <tr>
                  <td style="width:12%;">CPU告警 (%)</td>
                  <td style="width:36%;">
				  <input name="cpu" id="cpu" type="text" value="{%$infos.cpu%}" />
				  </td>
				  <td style="width:16%;">投递队列数量告警 (封)</td>
                  <td style="width:36%;">
				  <input name="mqueue" id="mqueue" type="text" value="{%$infos.mqueue%}" />
				  </td>
                </tr>
				 <tr>
                  <td style="width:12%;">存储告警 (%)</td>
                  <td style="width:36%;">
				  <input name="subarea" id="subarea" type="text" value="{%$infos.subarea%}" />
				  </td>
				  <td style="width:16%;">投递失败数量告警 (封)</td>
                  <td style="width:36%;">
				  <input name="dqueue" id="dqueue" type="text" value="{%$infos.dqueue%}" />
				  </td>
                </tr>
				<tr>
                  <td style="width:12%;">告警邮件接收人</td>
                  <td colspan="3">
				  <textarea style="width:75%;" name="recipients" id="recipients1" rows="7">{%$infos.recipients%}</textarea>
				  <a style="float:right;margin-left:-10%;%"id="example" data-content="多名接收人以“Enter”键分隔" data-placement="left" data-toggle="popover" class="btn btn-success" href="#" data-original-title="小贴士"><i class="icon-info-sign"></i></a> 
				  </td>
                </tr>
              </tbody>
            </table>
			<input type="hidden" value="{%$actiontype%}" name="actiontype" id="actiontype" />
			<input type="hidden" value="{%$infos.id%}" name="id" />
			</form>
          </div>
        </div>
		<center> <button type="button" class="btn" id="save">保存</button></center>
	  </div>	
	 </div>	
   </div>
</div>
{%include file="footer.php"%}
