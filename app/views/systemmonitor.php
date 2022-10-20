{%include file="header.php"%}
<script src="/dist/js/raphael.2.1.0.min.js"></script>
<script src="/dist/js/justgage.1.0.1.js"></script>
<script type="text/javascript">
	$(function(){
		var subarea_str = $("#sysstr").val();
		var subarea_str_ = subarea_str.split("@");
		var tr_f = '<tr><td style="width:130px;">';
		var tr_f_1 = '</td>';
        var td_f = '<td><div class="progress progress-striped active';
		var td_f_1 = '><div class="bar"';
		var td_f_2 = '></div>';		    
		var tr_e = '</div></td></tr>';
		var html_str = "";
		for (var i=0; i<subarea_str_.length; i++) {
			if (subarea_str_[i] != "") {
				var x_str = subarea_str_[i].split("&");
				var width_val = x_str[2].replace("%", "");
				width_val = addDefineClass(width_val);
				html_str = html_str + tr_f + x_str[3] + tr_f_1;
				var title_str = "总量:" + x_str[0] + "/使用量:" + x_str[1];
				if (width_val != "") {
					html_str = html_str + td_f + " " + width_val + '" title="使用率:' + x_str[2] + '"' +td_f_1;
					html_str = html_str + ' style="width:' + x_str[2] + '"; title="' + title_str + '"' + td_f_2;
					html_str = html_str + tr_e;
				} else {
					html_str = html_str + td_f + '" title="使用率:' + x_str[2] + '"' + td_f_1;
					html_str = html_str + ' style="width:' + x_str[2] + '"; title="' + title_str + '"' + td_f_2;
					html_str = html_str + tr_e;
				}
			}
		}
		$("#systeminfo").html(html_str);			
		var infostr = getSysInfo();
		var str = infostr.split("&");
		var cpu = str[0];
		var ram = str[1];
        var cpustats = new JustGage({
          id: "cpustats", 
          value: getRandomInt(cpu, cpu), 
          min: 0,
          max: 100,
          title: "CPU",
          label: "",
          levelColorsGradient: false
        });
        
        var ramstats = new JustGage({
          id: "ramstats", 
          value: getRandomInt(ram, ram),
          min: 0,
          max: 100,
          title: "RAM",
          label: "",
          levelColorsGradient: false
        });
        
        setInterval(function() {
			var infostr = getSysInfo();
			var str = infostr.split("&");
			var cpu = str[0];
			var ram = str[1];
            cpustats.refresh(getRandomInt(cpu, cpu));
            ramstats.refresh(getRandomInt(ram, ram));  
        }, 60000);
	});
	function addDefineClass (val) {
		var defineclass = "";
		if (val >= 25 && val < 50) {
			defineclass = "progress-success";
		}
		if (val >= 50 && val < 75) {
			defineclass = "progress-warning";
		}
		if (val >= 75 && val <= 100) {
			defineclass = "progress-danger";
		}
		return defineclass;
	}

	function getSysInfo () {
		var url = "/systemmonitor/getsysinfo";
		var result = $.ajax({
			url: url,
			async: false
		}).responseText;
		result = result.replace(/(^\s+)|(\s+$)/g, "");
		return result;
	}
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
	<a href="#" class="">系统监控</a>
	<a href="/systemmonitor/systemmonitor" class="current">系统状态监控</a>
	</div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
            <h5>CPU 状态</h5>
          </div>
          <div class="widget-content">
            <div class="pie" style="height:250px;"><div id="cpustats" style="width:100%; height:270px;"></div></div>
          </div>
        </div>
      </div>
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
            <h5>RAM 状态</h5>
          </div>
          <div class="widget-content">
            <div class="bars" style="height:250px;"><div id="ramstats" style="width:100%; height:270px;"></div></div>
          </div>
        </div>
      </div>
    </div>
	<div class="row-fluid">
		<div class="widget-box">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>分区名称</th>
                  <th>分区状态</th>
                </tr>
              </thead>
              <tbody id="systeminfo">
              </tbody>
            </table>
			<input type="hidden" name="sysstr" id="sysstr" value="{%$sysstr%}" />
         </div>
    </div>
  </div>
</div>
{%include file="footer.php"%}