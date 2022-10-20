{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	$.validator.setDefaults({
		errorClass: "val_error"
	});
	jQuery.validator.addMethod("ismailbox", function(value, element) {    
		return this.optional(element) || (/@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/.test(value));    
	}, "邮箱格式不正确");
	var validator = $("#authform").validate({ 
		rules: {
			mailbox: {    
			    required: true,
				ismailbox: true
			}
		},			   
		messages: {    
			mailbox: {    
				required: '请输入邮箱地址！',
				ismailbox: '请输入正确的邮箱地址！'
			}
		}    
	});
	$("#save").click(function(event) {
		if(validator.form()){
			var userid = $("input[name='userid']").val();
			var mail = $("input[name='mailbox']").val();
			var desc = $("#desc").val();;
			if(userid == ''){
				event.preventDefault();
				$.post("/setting/checkinterceptuser", { mail : mail}, function(data){
					if($.trim(data) == 'no'){
						art.dialog.alert("该用户邮箱已经存在，请重新填写！");
						return false;
					}else{
						if (desc.length > 30) {
							art.dialog.alert("用户描述不能大于30个字符，请重新填写！");
							return false;
						}
						document.userintercept.action = '/setting/addinterceptuser';   
						document.userintercept.submit(); 
					}
				});
						
			}else{
				if (desc.length > 30) {
					art.dialog.alert("用户描述不能大于30个字符，请重新填写！");
					return false;
				}
				var mailbox = $("#mailbox").val();
				if (!checkEmail(mailbox)) {
					art.dialog.alert("输入邮箱有误，请重新输入！");
					return false;
				}
				document.userintercept.action = '/setting/addinterceptuser';   
				document.userintercept.submit(); 
			}  
		}
	});
	
	function checkEmail(emails) {
		if ( emails.indexOf( "\r\n" ) >= 0 ) {	
			var addrs = emails.split("\r\n");
		} else if ( emails.indexOf( "\n" ) >= 0 ) {
			var addrs = emails.split("\n");
		} else {
			var addrs = emails.split("\r");
		}
		var email_patt = /^([a-zA-Z0-9_-]+)+[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
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

	$("#add").click(function(){
		$("#mailbox").val("");
		$("#desc").val("");
		$("#userid").val("");
		$('#setModal').modal('show');
	});
});

function geteachinfo (id) {
	$.get("/setting/getinterceptuser", { userid: id}, function(data){
		var strs = eval('('+data+')');
		console.log(strs);
		$("#userid").val(strs.id);
		$("#mailbox").val(strs.mailbox);
		$("#desc").val(strs.desc);
		$('#setModal').modal('show');
	});
}
function delinfo () {
	var value_str = "";
	var checkbox = $('tbody input:checkbox');
	checkbox.each(function() {
		if (this.checked) {
			$(this).closest('.checker > span').addClass('checked');
			value_str += this.value+"@";
		}
	});
	$("#checklist").val(value_str);
	if (value_str == "") {
		art.dialog.alert("请选择要删除的条目！");
		return false;
	}
	art.dialog({
		lock: true,
		background: 'black', 
		opacity: 0.87,	
		content: '您将要删除所选中的条目，是否继续？？',
		icon: 'error',
		ok: function () {
			document.listform.action = "/setting/delinterceptuser";
			document.listform.submit();
		},
		cancel: true
	});
}
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
	<a href="#" class="">参数设置</a>
	<a href="/setting/userintercept" class="current">拦截用户列表</a>
	</div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-list"></i></span>
            <h5>拦截用户列表</h5>
			<div style="float:right;width:120px;margin-top:4px;height:28px;"><a id="add" href="#" role="button" class="btn" data-toggle="modal" style="width:100px;padding:0;width:90px;height:26px;line-height: 26px;padding: 0px 10px; font-size:12px;"> + 添加拦截用户</a></div>
		  </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10%;text-align:center"><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" />&nbsp;&nbsp;全选</th>
                  <th style="width: 35%;text-align:center">用户邮箱</th>
                  <th style="text-align:center" colspan="2">描述</th>
                </tr>
              </thead>
              <tbody>
			  <form name="listform" method="post" action="">
				{%section name=info loop=$infos%}
                <tr class="gradeX">
                  <td style="text-align:center"><input style="margin-left:2px;" type="checkbox" id="infoid" name="infoid" value="{%$infos[info].id%}" /></td>
                  <td style="text-align:center">
				  <a onclick="geteachinfo('{%$infos[info].id%}')" href="javascript:void(0);" >{%$infos[info].mailbox%}</a>
				  </td>
				  <td style="text-align:center" colspan="2">{%$infos[info].desc%}</td>
				 </tr>
				{%/section%}
			    <input type="hidden" value="" id="checklist" name="infolist" />
			  </form>
				<tr>
					<td style="text-align:center;width: 74px;border-right: 0px;">
						<input style="margin-top: 2px;font-size:12px;width: 48px;" value="批量删除" class="btn" id="delinfo" onclick="javascript:void(delinfo())" />
					</td>
					<td style="text-align:center;border-left: 0px;" colspan="2">
						<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
							<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
								{%$page%}
							</div>
						</div>
					</td>
					<td style="padding-left: 12px;padding-top: 12px;border-left: 0px;width: 115px;">
						<center><label style="height: 30px; width: 105px;">
							<form action="/setting/userintercept" method="get" style="width:106px">
								<select name="num" size="1" style="width:60px">
									<option {%if $anum==5 %}selected="selected"{%/if%} value="5">5</option>
									<option {%if $anum==10 %}selected="selected"{%/if%} value="10">10</option>
									<option {%if $anum==25 %}selected="selected"{%/if%} value="25">25</option>
									<option {%if $anum==50 %}selected="selected"{%/if%} value="50">50</option>
									<option {%if $anum==100 %}selected="selected"{%/if%} value="100">100</option>
								</select>
									<input type="submit" value="GO" style="font-size:12px;height:26px;" />
							</form>		
							</label>
						</center>
					</td>
				</tr>
              </tbody>
            </table>
          </div>
        </div>
	  </div>	
	 </div>		
   </div>
</div>
			<div id="setModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-left: -330px; width: 50%;">
				<form name="userintercept" id="authform" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel" style="font-size: 14px;font-weight:bold;">用户信息</h3>
					</div>
				    <div class="modal-body">					
						<table class="table table-bordered table-striped">
						 <thead>
							<tr>
							  <th>属性</th>
							  <th>属性值</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
							  <td style="width:25%;">用户邮箱</td>
							  <td> 
							  <input style="width:46%;" id="mailbox" name="mailbox" type="text" value="{%$item['mailbox']%}" placeholder="邮箱" /></td>
							</tr>
							<tr>
							  <td style="width:25%;">描述</td>
							  <td>
							  <textarea style="width:46%;" name="desc" id="desc" placeholder="描述..." cols="30">{%$item['desc']%}</textarea>&nbsp;&nbsp;<span>（描述在30个字以内）</span>
							 </td>
							</tr>
							<input type="hidden" id="userid" name="userid" value="" />
						  </tbody>
						</table>
					</div>										
					<div class="modal-footer">
						<button id="save" class="btn btn-primary" style="margin-right: 10px;">保存
						<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
					</div>
				</form>
			</div>
{%include file="footer.php"%}