{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	$("#test1").click(function(){
		$("#test2").attr("disabled", true);
		$("#test3").attr("disabled", true);
		document.networktool.action = '/setting/pingtest';   
		document.networktool.submit();
	});
	$("#test2").click(function(){
		$("#test1").attr("disabled", true);
		$("#test3").attr("disabled", true);
		document.networktool.action = '/setting/tranceroute';   
		document.networktool.submit();
	});
	$("#test3").click(function(){
		$("#test2").attr("disabled", true);
		$("#test1").attr("disabled", true);
		document.networktool.action = '/setting/telnettest';   
		document.networktool.submit();
	});
});

</script>
<div id="content">
 <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="#" class="">网络设置</a> 
	<a href="/setting/networktool" class="current">网络工具</a> 
	</div>
  </div>
   <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>小工具</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="networktool" method="post">
			<table class="table table-bordered table-striped">
			  <tbody>
                <tr>
                  <td style="width:15%;">Ping测试</td>
                  <td>
				  <input style="width:33%;" type="text" value="" id="pingtest" name="pingtest" placeholder=""/>
				  </td>
				  <td style="width:15%;">
				  <input style="width:75%;" type="button" id="test1" class="btn btn-primary" value="测试" onclick="this.value='loading....'" />
				  </td>
                </tr>
                <tr>
                  <td style="width:15%;">Traceroute测试</td>
                  <td><input style="width:33%;" type="text" value="" id="traceroutetest" name="traceroutetest" placeholder=""/></td>
				   <td style="width:15%;">
				   <input style="width:75%;" type="button" id="test2" class="btn btn-primary" value="测试" onclick="this.value='loading....'" />
				  </td>
                </tr>
                <tr>
                  <td style="width:15%;">Telnet测试</td>
                  <td>
				  <input style="width:33%;" type="text" value="" id="telnettest" name="telnettest" placeholder=""/>&nbsp;&nbsp;&nbsp;端口号&nbsp;&nbsp;&nbsp;<input style="width:10%;" name="telnetport" type="text" value="" />
				  </td>
				   <td style="width:15%;">
				  <input style="width:75%;" type="button" id="test3" class="btn btn-primary" value="测试" onclick="this.value='loading....'" />
				  </td>
                </tr>
				<tr>
                  <td style="width:15%;">测试结果</td>
                  <td colspan="2">
				  <textarea style="width:75%;" rows="7" readonly="readonly">{%$a%}{%$b%}{%$c%}{%section name=index loop=$d%}{%$d[index]%}{%/section%}</textarea>
				  </td>
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
<!--Footer-part-->
{%include file="footer.php"%}
