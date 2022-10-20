{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	$("#save").click(function(){
		var domains = $("#specialdomains").val();
		if (domains != "") {
			if (!checkDomain (domains)) {
				art.dialog.alert("域名输入有误，请重新输入！");
				return false;
			}
		}
		document.domainform.action = "/setting/updatespecialdomain";
		document.domainform.submit ();
	});
});

function checkDomain(domains) {
	if ( domains.indexOf( "\r\n" ) >= 0 ) {	
		var addrs = domains.split("\r\n");
	} else if ( domains.indexOf( "\n" ) >= 0 ) {
		var addrs = domains.split("\n");
	} else {
		var addrs = domains.split("\r");
	}
	var domain_patt = /\w*\.\w+$/;
	if (addrs.length > 0) {
	    for (var i = 0; i < addrs.length; i++) {
	        if (addrs[i] == "") {
	            continue;
	        }
	        if (!domain_patt.test(addrs[i])) {
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
	<a href="#" class="">网络设置</a> 
	<a href="/setting/specialdomain" class="current">地址池管理</a> 
	</div>
  </div>
   <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>地址池管理</h5>
          </div>
          <div class="widget-content nopadding">
		    <form name="domainform" method="post">
            <table class="table table-bordered table-striped">
			 <thead>
                <tr>
                  <th>属性</th>
                  <th>属性值</th>
                </tr>
              </thead>
			  <tbody>
				<tr>
                  <td style="width:20%;">特殊域名</td>
                  <td>
				  <textarea name="specialdomain" id="specialdomains" style="width:75%;" rows="7">{%$infos.specialdomain%}</textarea>
				  <a style="float:right;margin-left:-10%"id="example" data-content="每条域名以Enter键分隔。" data-placement="left" data-toggle="popover" class="btn btn-success" href="#" data-original-title="小贴士"><i class="icon-info-sign"></i></a> 
				  </td>
                </tr>
              </tbody>
            </table>
			<input type="hidden" name="sdid" value="{%$infos.id%}" />
			</form>
          </div>
        </div>
		<center><button class="btn btn-primary" id="save">保存</button></center>
	  </div>	
	 </div>	
   </div>
</div>
{%include file="footer.php"%}
