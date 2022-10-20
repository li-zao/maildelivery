{%include file="header.php"%}
<style>
	.widget-content .radio{
		width: 20%;
	}
	label, input, button, select, textarea {
		font-size:12px;
	}
	.btn{
		font-size:12px;
	}
</style>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 统计分析</a><a href="#" class="current">按全部任务统计</a></div>
  </div>
  <div class="container-fluid">
		<div class="quick-actions_homepage span=12" style="margin: 0 auto;">
			<div width="100%" style="margin-top: 20px; border-bottom:1px dotted #ccc; height: 20px;"><span id="quicknav" href="#collapseG3" data-toggle="collapse" class="widget-title bg_lo collapsed" style="font-weight:bold;color:#444444;float:right;font-size:12px; height: 18px;border:0;">快捷导航栏<i class="icon-chevron-down"></i></span></div>
		         <div id="collapseG3" class="widget-content nopadding updates collapse" style="border:0;">
					{%include file="navigation.php"%}
				 </div>
		</div>
 		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box" style="margin-bottom: 10px;">
					 <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
							<h5>全部任务统计报告</h5>
					 </div>
					 <div class="widget-content nopadding" style="height:95px">
					 <form method="get" action="/statistics/alltaskstatistics" id="general_from">
						<table style="height: 90px;width:100%; margin-top: 10px; margin-bottom: 10px;">
							<!--<tr>
								<td style="text-align:right; width: 14%;">选择设备进行统计：</td>
								<td style="padding-left: 20px;"><select onchange="" name="plan_class">
										<option value="1">本机</option>
										<option value="2">admin1</option>
										<option value="3">admin2</option>
										<option value="4">admin3</option>
												
									</select>
								</td>
							</tr>-->
							<input type="hidden" name="count" value="count" />
							<tr>
								<td style="padding-left: 42px;width:120px;">选择统计时间范围：</td>
								<td>
									<div class="input-append date form_datetime" style="margin-right: 1px;margin-top: -4px;">
										<input size="16" name="start_time" id="start_time" type="text" style="width:200px;" {%if $start_time%}value="{%$start_time%}"{%/if%}><span class="add-on"><i class="icon-calendar"></i></span>
									</div>&nbsp;至&nbsp;
									<div class="input-append date form_datetime" style="margin-right: 1px;margin-top: -4px;">
										<input size="16" name="last_time" id="last_time" type="text" style="width:200px;" {%if $last_time%}value="{%$last_time%}"{%/if%}><span class="add-on"><i class="icon-calendar"></i></span>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="padding-left: 42px;">
									统计周期：
									<label class="radio" style="width:210px;margin-left:60px;"><input type="radio" name="statisticstime" id="" style="margin-left: 0px; margin-right: 10px;" value="1" checked>按日统计	</label>
									<label class="radio" style="width:210px"><input type="radio" name="statisticstime" id="" style="margin-left: 0px; margin-right: 10px;" value="2" {%if $statisticstime == 2%}checked{%/if%}>按周统计</label>
									<label class="radio" style="width:210px"><input type="radio" name="statisticstime" id="" style="margin-left: 0px; margin-right: 10px;" value="3"  {%if $statisticstime == 3%}checked{%/if%}>按月统计</label>
									<!--<label class="radio" style="width:210px"><input type="radio" name="statisticstime" id="" style="margin-left: 0px; margin-right: 10px;" value="4"  {%if $statisticstime == 4%}checked{%/if%}>按季统计</label>-->
									<label class="radio" style="width:210px"><input type="radio" name="statisticstime" id="" style="margin-left: 0px; margin-right: 10px;" value="5" {%if $statisticstime == 5%}checked{%/if%}>按年统计</label>
								</td>
							</tr>
							<tr><td colspan="2" style="text-align:center"><button type="button" class="btn" type="submit" style="padding-left: 20px; padding-right: 20px;">开始统计</button></td></tr>
						</table>
					</form>
					 </div>
				</div>
			</div>
			<!--<div class="span12"  style="margin-left:0;">
				<div class="widget-box" style="margin-bottom: 10px;">
					<div class="widget-content" style="padding:5px;">
						<div style="height:140px;width:98.6%;background:#fff;padding-left:15px;">
							{%section name=loop loop=$taskdata%}
							<div style="width:98.6%;height:48px;float:left;border-bottom:2px dotted #ccc;">
								<div style="padding-top: 20px;font-size: 16px; "><span style="font-weight:bold;color:#444444;">邮件主题：</span><span style="color:#66666">{%$taskdata[loop].subject%}</span></div>		
							</div>
							<div style="width:40%;float:left;padding-top: 5px;">
								<div style="padding-top: 10px; "><span style="font-weight:bold;color:#444444;">任务发布人：</span><span style="color:#66666">{%$taskdata[loop].sender%}</span></div>
								<div style="padding-top: 10px; "><span style="font-weight:bold;color:#444444;">回复地址：</span><<span style="color:#66666">{%$taskdata[loop].replyemail%}</span>></div>
							</div>	
							<div style="width:40%;float:left;padding-top: 5px;">
								<div style="padding-top: 16px; "><span style="font-weight:bold;color:#444444;">统计时段：</span><span style="color:#66666">2014-05-12 至 2014-05-20</span></div>
								<div style="padding-top: 10px; "><span style="font-weight:bold;color:#444444;">发送时间：</span><span style="color:#66666">{%$taskdata[loop].sendtime%}</span></div>
							</div>	
							{%/section%}
						</div>
					</div>
				</div>
			</div>-->
			<div class="span12"  style="margin-left:0;">
				<div class="span12">
					<div class="widget-box">
						<div class="widget-title"> <span class="icon"><i class="icon-time"></i></span>
							<h5>设备统计报表</h5>
						 </div>
						<div class="widget-content nopadding">
							<table class="table table-striped table-bordered">
								<thead>
									<tr>
										<th style="text-align:center">日期（{%if $statisticstime == 5%}按年{%else if $statisticstime == 2%}按周{%else if $statisticstime == 3%}按月{%else if $statisticstime == 4%}按季{%else%}按日{%/if%}）</th>
										<th style="text-align:center">发送总量</th>
										<th style="text-align:center">软退回数量</th>
										<th style="text-align:center">硬退回数量</th>
										<th style="text-align:center">到达总量</th>
										<th style="text-align:center">打开总量</th>
										<th style="text-align:center">点击总量</th>
										<th style="text-align:center">退订数量</th>
										<!--<th style="text-align:center">垃圾邮件投诉数量</th>-->
									</tr>
								</thead>
								<tbody>
									{%if $infos%}
									{%foreach item=temp from=$infos%}
										<tr>
											<td class="taskDesc" style="text-align:center"><i class="icon-info-sign"></i>{%if $statisticstime == 1%}{%$temp.days%}{%else if $statisticstime == 2%}{%$temp.time%}{%else if $statisticstime == 3%}{%$temp.months%}{%else if $statisticstime == 4%}{%$temp.quarter%}{%else%}{%$temp.years%}{%/if%}</td>
											<td class="taskStatus"><span class="in-progress">{%if $temp.COUNT != ""%}{%$temp.COUNT%}{%else%}0{%/if%}</span></td>	
											<td class="taskStatus"><span class="in-progress">{%if $temp.soft_failure != ""%}{%$temp.soft_failure%}{%else%}0{%/if%}</span></td>	
											<td class="taskStatus"><span class="in-progress">{%if $temp.failure != ""%}{%$temp.failure%}{%else%}0{%/if%}</span></td>	
											<td class="taskStatus"><span class="in-progress">{%if $temp.done != ""%}{%$temp.done%}{%else%}0{%/if%}</span></td>	
											<td class="taskStatus"><span class="in-progress">{%if $temp.open != ""%}{%$temp.open%}{%else%}0{%/if%}</span></td>	
											<td class="taskStatus"><span class="in-progress">{%if $temp.skip != ""%}{%$temp.skip%}{%else%}0{%/if%}</span></td>	
											<td class="taskStatus"><span class="in-progress">{%if $temp.unsubscribe != ""%}{%$temp.unsubscribe%}{%else%}0{%/if%}</span></td>	
											<!--<td class="taskStatus"><span class="in-progress">{%if $temp.trashmail != ""%}{%$temp.trashmail%}{%else%}0{%/if%}</span></td>-->	
										</tr>
									{%/foreach%}
								{%/if%}				
								</tbody>
							</table>
						</div>
					</div>					
				</div>
			</div>
		</div>
  </div>
</div>
<script>
	 $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"
    });
	$('.form_date').datetimepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayBtn: true,
	    weekStart: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
        pickerPosition: "bottom-left"
    });
	$("#collapseG3").on("show", function() {
		var up=$("#quicknav").children("i").attr("class",'icon-chevron-up');
	});
	$("#collapseG3").on("hide", function() {
		var up=$("#quicknav").children("i").attr("class",'icon-chevron-down');
	});
</script>
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />
<!--Footer-part-->
{%include file="footer.php"%}
