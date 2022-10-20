{%include file="header.php"%}
<link href="/dist/css/jquery.vector-map.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/dist/js/jPages.js"></script>
<style>
  .widget-title .nav-tabs li a{
	 border-left:1px solid #DDDDDD !important;
	 border-right:1px solid #DDDDDD;
	 border-top:1px solid #DDDDDD;
	 background:#fff;
	 padding: 9px 20px 8px;
  }
  .widget-title .nav-tabs li a:hover{
	background-color:#fff !important;
	border-color:#D6D6D6;
	border-width: 1px 1px;
	padding: 9px 20px 8px;
  }
  .widget-title .nav-tabs li .active > a{
	background:#EFEFEF;
	border-top:1px solid #DDDDDD;
	border-left:1px solid #DDDDDD !important;
	border-right:1px solid #DDDDDD;
	border-bottom:medium none;
	padding: 9px 20px 8px;
  }
  .widget-title .nav-tabs li.active a{
	background-color:#EFEFEF !important;
	border-top:1px solid #DDDDDD;
	border-bottom:medium none;
	border-left:1px solid #DDDDDD !important;
	padding: 9px 20px 8px;
  }
  .widget-content table tr th{
	text-align:center;
  }
  .widget-content table tr td{
	text-align:center;
  }
  #choices input{
	margin:4px 5px 0 0;
  }
  .margin_top5{
	margin-top:5px;
  }
  .font12_bold{
	font-size:12px;
	font-weight:bold;
  }
  .widget-content .taskDesc{
	text-align:left;
  }
  
    .col{color:red;}
  .holder {
    margin: 15px 0;
  }
    .holder a {
	background: none repeat scroll 0 0 #F5F5F5;
    border-color: #ddd;
    border-style: solid;
    border-width: 1px;
    color: #333333;
    display: inline-block;
    font-size: 12px;
    line-height: 16px;
    padding: 4px 10px !important;
    text-shadow: 0 1px 0 #FFFFFF;
	margin: 0 0 0 0;
  }
  .holder a:hover {
    background-color: #E8E8E8;
    color: #222222;
  }
  .holder a.jp-first {border-radius: 4px 0 0 4px;}
  .holder a.jp-previous { margin-right: 0px; }
  .holder a.jp-current, a.jp-current:hover {
	background-color: #26B779;color: #FFFFFF;
	width: 20px;
    font-weight: bold;
	text-align:center;
	margin-right: 0px;
  }
  .holder a.jp-last {border-radius: 0 4px 4px 0;margin-left: 0px;}
  .holder a.jp-current, a.jp-current:hover,
  .holder a.jp-disabled, a.jp-disabled:hover {
    cursor: default;
    
  }
  .holder span { margin: 0 5px; }
</style>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a><a href="#"> 统计分析</a><a href="#" class="current">按任务分类统计</a></div>
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
				<div class="widget-box" style="border:0;">
					<div style="background-color:#F9F9F9;height:37px;width: 98%; padding: 10px 0px 0px 10px;">
						<input type="hidden" value="{%$timescope%}" name="selectTasktime" id="selectTasktime">
					    <form method="get" action="/statistics/taskclassification" id="general_from">
						<div style="float:left">
							<span style="display:block;" class="float_left margin_top5 font12_bold">任务分类：</span>
						</div>
						<div style="float:left">
							<select id="selecttype" class="float_left margin_right5 mb" name="cid" style="height: 28px;">
								<option value="">请选择分类</option>
									{%section name=loop loop=$catdata%}
										{%if $catdata[loop].id == $cid%}
											<option value="{%$catdata[loop].id%}" selected="selected">{%$catdata[loop].vocation_name%}</option>
										{%else%}
											<option value="{%$catdata[loop].id%}">{%$catdata[loop].vocation_name%}</option>
										{%/if%}
									{%/section%}
							</select>
						</div>
						<div style="float:left;margin-left:10px;">
							<span class="font12_bold">时间范围：</span>
							<select id="timescope" style="width: 140px; padding-top: 2px; padding-bottom: 2px; height: 28px;" name="timescope">
							{%foreach $arrdate as $key=>$val%}
								{%if $timescope == $key%}
									<option value="{%$key%}" selected>{%$val%}</option>
								{%else%}
									<option value="{%$key%}">{%$val%}</option>
								{%/if%}
							{%/foreach%}
							</select>
						</div>
						<div style="float:left;margin-left:5px;">
							<div id="selecttime" style="display: none;">
									<div class="input-append date form_date">从 <input size="16" id="start_time_s" type="text" name="stattime" value="{%$stattime%}" style="height: 22px; line-height: 25px;width: 196px;padding: 2px 0px\9;"/><span class="add-on" style="padding-bottom: 2px; height: 19px; padding-top: 5px;"><i class="icon-calendar"></i></span></div>&nbsp;至 <div class="input-append date form_date"><input id="last_time_e" name="lasttime" size="16" type="text" value="{%$lasttime%}" style="height: 22px; line-height: 25px;  width: 196px;padding: 2px 0px\9;" /><span class="add-on" style="padding-bottom: 2px; height: 19px; padding-top: 5px;"><i class="icon-calendar"></i></span></div>	
							</div>
						</div>
						</form>
						<div style="float:left;margin-left:5px;">
						<button id="sub_plan_id" class="btn" style="height: 28px;font-size:12px;"><i class="icon-zoom-in"></i>&nbsp;搜索</button>
						</div>
					</div>
				</div>					
			{%if $taskdata.status != 3%}
				<div class="widget-box" style="background:#EFEFEF;border:0;">
				<input type="hidden" value="{%$sendresult.data%}" name="sentdata" id="sentdata">
				<input type="hidden" value="{%$backresult.data%}" name="backdata" id="backdata">
					 <div class="widget-title" style="color:#66666">
						<ul class="nav nav-tabs">
							  <li class="active"><a href="#tab1" data-toggle="tab">综合概览</a></li>
							  <li class=""><a href="#tab2" data-toggle="tab">任务状态统计</a></li>
							  <li class=""><a href="#tab4" data-toggle="tab">收信域统计</a></li>
							  {%if false%}
							  <li class=""><a href="#tab3" data-toggle="tab">接收区域分布统计</a></li>
							  <li class=""><a href="#tab5" data-toggle="tab">客户端统计</a></li>
							  {%/if%}
						</ul>
					  </div>
					  <div class="widget-content tab-content" style="padding: 0px;border:0;overflow:hidden;">
						<div class="tab-pane active" id="tab1">
							<div class="row-fluid" style="margin-top: 10px;">
							  <div class="span6">
								<div class="widget-box">
								  <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
									<h5>邮件发送情况分析</h5>
								  </div>
								  <div class="widget-content">
									  <div id="pie_task01" style="width:499px;height:300px;"></div>
								  </div>
								</div>
							  </div>
							  <div class="span6">
								<div class="widget-box">
								  <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
									<h5>邮件反馈情况分析</h5>
								  </div>
								  <div class="widget-content">
									   <div id="chart_task01" style="width:499px;height:300px;"></div>
								  </div>
								</div>
							  </div>
							  <div class="span6" style="margin-left: 0px;">
									<div class="widget-box">
									  <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
										<h5>邮件发送情况分析表</h5>
									  </div>
									  <div class="widget-content nopadding">
										<table class="table table-bordered table-striped">
										  <thead>
											<tr>
											  <th>发送情况</th>
											  <th>数量</th>
											  <th>发送比例</th>
											</tr>
										  </thead>
										  <tbody>
											<tr class="odd gradeX">
											  <td>发送总量</td>
											  <td>{%$sendresult.total%}</td>
											  <td>{%$sendresult.totalpercent%}</td>
											</tr>
											<tr class="even gradeC">
											  <td>成功到达数量/比例</td>
											  <td>{%$sendresult.success%}</td>
											  <td>{%$sendresult.successpercent%}</td>
											</tr>
											<tr class="odd gradeA">
											  <td>硬退回数量/比例</td>
											  <td>{%$sendresult.hardFail%}</td>
											  <td>{%$sendresult.hardFailpercent%}</td>
											</tr>
											<tr class="even gradeA">
											  <td>软退回数量比例</td>
											  <td>{%$sendresult.softFail%}</td>
											  <td>{%$sendresult.softFailpercent%}</td>
											</tr>
										  </tbody>
										</table>
									  </div>
									</div>
							  </div>
							  <div class="span6">
									<div class="widget-box">
									  <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
										<h5>邮件反馈情况分析表</h5>
									  </div>
									  <div class="widget-content nopadding">
										<table class="table table-bordered table-striped">
										  <thead>
											<tr>
											  <th>反馈情况</th>
											  <th>数量</th>
											  <th>发送比例</th>
											</tr>
										  </thead>
										  <tbody>
											<tr class="odd gradeX">
											  <td>发送总量</td>
											  <td>{%$backresult.total%}</td>
											  <td>{%$backresult.totalpercent%}</td>
											</tr>
											<tr class="odd gradeX">
											  <td>到达数量</td>
											  <td>{%$backresult.numberAt%}</td>
											  <td>{%$backresult.numberAtpercent%}</td>
											</tr>
											<tr class="even gradeC">
											  <td>打开数量</td>
											  <td>{%$backresult.openNum%}</td>
											  <td>{%$backresult.openNumpercent%}</td>
											</tr>
											<tr class="odd gradeA">
											  <td>点击数量</td>
											  <td>{%$backresult.clickNum%}</td>
											  <td>{%$backresult.clickNumpercent%}</td>
											</tr>
										  </tbody>
										</table>
									  </div>
									</div>
							  </div>
							</div>
						</div>
						<div class="tab-pane" id="tab2">
							<div class="row-fluid" style="margin-top: 10px;">
								<div class="span12">
									<div class="widget-box">
									  <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
										<h5>邮件任务发送状态统计</h5>
									  </div>
									  <div class="widget-content nopadding">
									  <input type="hidden" id="tnum" value="{%$fpage.total%}">
										<table class="table table-bordered table-striped">
										  <thead>
											<tr>
											  <th>任务名称</th>
											  <th>接收人</th>
											  <th>执行时间</th>
											  <th>投递状态</th>
											  <th>日志</th>
											</tr>
										  </thead>
										  <tbody id="itemContainer">
										  {%section name=loop loop=$statusresult%}
											<tr>
											  <td>{%$statusresult[loop].task_name%}</td>
											  <td>{%$statusresult[loop].forward%}</td>
											  <td>{%$statusresult[loop].runtime%}</td>
											  <td>{%$statusresult[loop].status%}</td>
											  <td><a href="#mail{%$statusresult[loop].id%}" name="mail{%$statusresult[loop].id%}" id="{%$statusresult[loop].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo(this)">查看</a></td>
											</tr>
										  {%/section%}
										  </tbody>
										  <tr><td colspan="5" style="text-align:center">
											<div id="pageId" style="height: 30px; margin-left: 0px; padding-left: 250px;"><div style="float:left; margin-top: 9px; margin-left: 30px;" class="fenpages">共有<b>{%$fpage.total%}</b>条记录  <b>{%$fpage.currentPage%}</b>/<b>{%$fpage.pageMax%}</b>&nbsp;&nbsp;</div><div style="float: left; margin-top: 5px; margin-bottom: 0px; -moz-user-select: none;" class="holder">
											<a class="jp-first jp-disabled" href="javascript:void(0);" onclick="page1(1)" >首 页</a>
											{%if $fpage.currentPage !=1 %}
											<a class="jp-previous jp-disabled" href="javascript:void(0);" onclick="page1({%$fpage.currentPage-1%})">上一页</a>
											{%else%}
												<a class="jp-previous jp-disabled" href="javascript:void(0);" onclick="page1(1)">上一页</a>
											{%/if%}
											{%assign var=i value=0%}
											{%section name=loop loop=$fpage.pageMax %}
											{%assign var=i value=$i+1%}
												{%if $i <= 6%}
													{%if $fpage.currentPage == $i %}
														<a class="jp-current" href="javascript:void(0);" onclick="page1({%$i%})">{%$i%}</a>
													{%else%}
														<a class="" href="javascript:void(0);" onclick="page1({%$i%})">{%$i%}</a>
													{%/if%}
													
												{%/if%}
											{%/section%}
											{%if $fpage.currentPage < 1 || $fpage.currentPage < $fpage.pageMax%}
											<a class="jp-next jp-disabled" href="javascript:void(0);" onclick="page1({%$fpage.currentPage+1%})" >下一页</a>
											{%else%}
												<a class="jp-next jp-disabled" href="javascript:void(0);" onclick="page1({%$fpage.pageMax%})" >下一页</a>
											{%/if%}
											<a class="jp-last jp-disabled" href="javascript:void(0);" onclick="page1({%$fpage.pageMax%})">尾 页</a></div></div>
										  </td></tr>
										</table>
									  </div>
									</div>
								</div>
							</div>
						</div>
					    <div  class="modal hide fade mail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 600px;">
						  <div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						  <h3 id="myModalLabel">邮件情况</h3>
						  </div>
						  <div class="modal-body mailonce" >
						  <p>
							<table style="width: 580px;">
							   <tr><td style="text-align:left;">日志:</td></tr>
							   <tr>
								<td style="text-align:left;"><div id="send_log" style="work-break:break-all;overflow:auto;margin-left:20px;"></div></td>
							  </tr>
							</table>
						  </p>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">关闭</button>
						  </div>
						 </div>
						<div class="tab-pane" id="tab3">
						  <div class="row-fluid" style="margin-top: 10px;">
							  <div class="span8">
								<div class="widget-box">
								  <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
									<h5>邮件接收情况分析</h5>
								  </div>
								  <div class="widget-content" style="height: 430px;background-color:#fff;">
									  <div id="clientmap" style="background-color:#fff;width:685px;height: 420px;padding-top:10px;"></div>
								  </div>
								</div>
							  </div>
							  <div class="span4">
								  <div class="widget-box">
									  <div class="widget-title"> <span class="icon"><i class="icon-time"></i></span>
										<h5>接收地区分布统计表</h5>
									  </div>
									  <div class="widget-content nopadding">
										<table class="table table-striped table-bordered">
										  <thead>
											<tr>
											  <th>地区</th>
											  <th>数量</th>
											  <th>百分比</th>
											</tr>
										  </thead>
										  <tbody>
										  	{%section name=ar loop=$Areas_Datas%}
											<tr>
											  <td class="taskDesc"><i class="icon-info-sign"></i>{%$Areas_Datas[ar].name%}</td>
											  <td class="taskStatus"><span class="in-progress">{%$Areas_Datas[ar].count%}</span></td>
											  <td class="taskOptions">{%$Areas_Datas[ar].ratio%}</td>
											</tr>
											{%/section%}
										  </tbody>
										</table>
									  </div>
								  </div>
							  </div>
						  </div>
 						</div>
						<div class="tab-pane" id="tab4">
							<div class="row-fluid" style="margin-top: 10px;">
								  <div class="span4">
									<div class="widget-box">
									  <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
										<h5>收信域TOP10比例图</h5>
									  </div>
									  <div class="widget-content">
										  <div id="pie_task02" style="width:312px;height:368px;"></div>
									  </div>
									  <!-- <div class="widget-content nopadding">
											<table class="table table-striped table-bordered">
											  <thead>
												<tr>
												  <th>收信域</th>
												  <th>比例</th>
												</tr>
											  </thead>
											  <tbody>
												{%section name=vals loop=$Final_Resoult_Mails%}
												<tr>
												  <td class="taskDesc">{%$Final_Resoult_Mails[vals].0%} </td>
												  <td class="taskStatus"><span class="in-progress">{%$Final_Resoult_Mails[vals].1%} %</span></td>
												</tr>
												{%/section%}
											  </tbody>
											</table>
									  </div> -->
									</div>
								  </div>
								  <div class="span8">
								  <div class="widget-box">
									  <div class="widget-title"> <span class="icon"><i class="icon-time"></i></span>
										<h5>收信域统计表</h5>
									  </div>
									  <div class="widget-content nopadding">
										<table class="table table-striped table-bordered">
										  <thead>
											<tr>
											  <th>收信域</th>
											  <th>发送总量</th>
											  <th>占总数比例</th>
											  <th>软退回数量</th>
											  <th>硬退回数量</th>
											  <th>到达率</th>
											  <th>打开数量</th>
											  <th>点击数量</th>
											</tr>
										  </thead>
										  <tbody>
											{%section name=vals loop=$resoult%}
											<tr>
											  <td class="taskDesc"><i class="icon-info-sign"></i>{%$resoult[vals].sendto%}</td>
											  <td class="taskStatus"><span class="in-progress">{%$resoult[vals].totalsend%}</span></td>
											  <td class="taskOptions">{%$resoult[vals].proportion%} %</td> 
											  <td class="taskOptions">{%$resoult[vals].soft%}</td> 
											  <td class="taskStatus"><span class="in-progress">{%$resoult[vals].hard%}</span></td>
											  <td class="taskOptions">{%$resoult[vals].success%} %</td>	
											  <td class="taskStatus"><span class="in-progress">{%$resoult[vals].hasread%}</span></td>
											  <td class="taskOptions">{%$resoult[vals].readcount%}</td>
											</tr>
												{%/section%}
										  </tbody>
										</table>
									  </div>
								  </div>
							  </div>
							</div>
						</div>
						<div class="tab-pane" id="tab5">
							<div class="row-fluid" style="margin-top: 10px;">
								<div class="span12">
									<div class="widget-box">
									  <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
										<h5>客户端操作系统情况统计图</h5>
									  </div>
									  <div class="widget-content">
										  <div id="bar_task03" style="height: 300px;width: 1057px;"></div>
									  </div>
									</div>
								</div>
								<div class="span12" style="margin-left:0;">
									<div class="widget-box">
									  <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
										<h5>客户端操作系统情况统计表</h5>
									  </div>
									  <div class="widget-content nopadding">
										<table class="table table-bordered table-striped">
										  <thead>
											<tr>
											  <th>操作系统</th>
											  <th>浏览总数</th>
											  <th>净浏览数</th>
											</tr>
										  </thead>
										  <tbody>
											{%section name=os loop=$Os_Resoult%}
											<tr class="odd gradeX">
											  <td>{%$Os_Resoult[os].os%}</td>
											  <td>{%$Os_Resoult[os].readcount%}</td>
											  <td>{%$Os_Resoult[os].hasread%}</td>
											</tr>
											{%/section%}
										  </tbody>
										</table>
									  </div>
									</div>
								</div>

							<div class="span12"  style="margin-left: 0px;">
									<div class="widget-box">
									  <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
										<h5>客户端接入商情况统计图</h5>
									  </div>
									  <div class="widget-content">
										  <div id="bar_task04" style="height: 300px;width: 1057px;"></div>
									  </div>
									</div>
							</div>
							<div class="span12" style="margin-left:0;">
									<div class="widget-box">
									  <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
										<h5>客户端接入商情况统计表</h5>
									  </div>
									  <div class="widget-content nopadding">
										<table class="table table-bordered table-striped">
										  <thead>
											<tr>
											  <th>接入商</th>
											  <th>浏览总数</th>
											  <th>净浏览数</th>
											</tr>
										  </thead>
										  <tbody>
											{%section name=sp loop=$Sp_Resoult%}
											<tr class="odd gradeX">
											  <td>{%$Sp_Resoult[sp].sp%}</td>
											  <td>{%$Sp_Resoult[sp].readcount%}</td>
											  <td>{%$Sp_Resoult[sp].hasread%}</td>
											</tr>
											{%/section%}
										  </tbody>
										</table>
									  </div>
									</div>
								</div>

								<div class="span12"  style="margin-left: 0px;">
									<div class="widget-box">
									  <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
										<h5>客户端浏览器情况统计图</h5>
									  </div>
									  <div class="widget-content">
										  <div id="bar_task05" style="height: 300px;width: 1057px;"></div>
									  </div>
									</div>
								</div>
								<div class="span12" style="margin-left:0;">
									<div class="widget-box">
									  <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
										<h5>客户端浏览器情况统计表</h5>
									  </div>
									  <div class="widget-content nopadding">
										<table class="table table-bordered table-striped">
										  <thead>
											<tr>
											  <th>浏览器</th>
											  <th>浏览总数</th>
											  <th>净浏览数</th>
											</tr>
										  </thead>
										  <tbody>
											{%section name=br loop=$Br_Resoult%}
											<tr class="odd gradeX">
											  <td>{%$Br_Resoult[br].browser%}</td>
											  <td>{%$Br_Resoult[br].readcount%}</td>
											  <td>{%$Br_Resoult[br].hasread%}</td>
											</tr>
											{%/section%}
										  </tbody>
										</table>
									  </div>
									</div>
								</div>



							</div>
						</div> 
					  </div>
				</div>
			{%/if%}
			</div>
		</div>
	</div>
</div>
<input type="hidden" value="{%$li_menu%}" id="li_menu" name="li_menu" />
<input type="hidden" id="area_json" value='{%$Final_Areas_Json%}'>	
<input type="hidden" value='{%$Receiver_Resoult_Json%}' id="receiver_area"/> 
<input type="hidden" value='{%$Os_Str%}' id="Os_Str"/> 
<input type="hidden" value='{%$Sp_Str%}' id="Sp_Str"/> 
<input type="hidden" value='{%$Br_Str%}' id="Br_Str"/> 
<script>
	$("#collapseG3").on("show", function() {
		var up=$("#quicknav").children("i").attr("class",'icon-chevron-up');
	});
	$("#collapseG3").on("hide", function() {
		var up=$("#quicknav").children("i").attr("class",'icon-chevron-down');
	});
</script>
<script type="text/javascript">
$(function () {
	var sentdata=$('#sentdata').val();
	if(sentdata == ""){
		var sent = [];
	}else{
		var sent = eval('('+sentdata+')');
	}
	
	//alert(sentdata);exit;
    $('#pie_task01').highcharts({
		colors: ["#38700F", "#DA4B0F","#EDAE09", "#960A0D", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
		"#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
				showInLegend: true
            }
        },
        series: [{
            type: 'pie',
            name: 'Task number',
            data: sent
        }]
    });
});
	
$(function () {	
	//三角形
	var backdata=$('#backdata').val();
    if(backdata == ""){
		var back = [];
	}else{
		var back = eval('('+backdata+')');
	}
	//var back = eval('('+backdata+')');
	 $('#chart_task01').highcharts({
		colors: ["#1C8B3F", "#8FC450","#D6DE29", "#D7711F", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
		"#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
        chart: {
            type: 'pyramid',
            marginRight: 100
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b> ({point.y:,.0f})',
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                    softConnector: true
                }
            }
        },
        legend: {
            enabled: false
        },
        series: [{
            name: 'Task number',
            data: back
        }]
    });
});

$(function () {	
	var Receiverdata=$('#receiver_area').val();
	if(Receiverdata == ""){
		var Datas = [];
	}else{
		var Datas = eval('('+Receiverdata+')');
	}
	$('#pie_task02').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            height:360
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
		
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
				showInLegend: true
            }
        },
        series: [{
            type: 'pie',
            name: 'Mail Proportion',
            data: Datas
        }]
    });
});

$(function () {	
	var osdata=$('#Os_Str').val();
	if(osdata == ""){
		var os_str = [];
	}else{
		var os_str = eval('('+osdata+')');
	}
	$('#bar_task03').highcharts({
				chart: {
                    type: 'column'
                },
				colors: ['#5051F9'],
                title: {
            text: null
        },
        xAxis: {
            categories: [
                'Windows XP',
                'Windows 7',
                'Windows 8',
                'Windows NT',
                'Linux',
                'Unix',
                'Ipad',
                'Android',
                'Iphone',
                'Macintosh',
                'Other',
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: null
            },
            max:100
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                groupPadding: 0.1,
                pointWidth: 50,
                borderWidth: 1,
				showInLegend: false,
           		 colorByPoint: true,
       		  colors:["#1C8B3F", "#8FC450","#D6DE29", "#D7711F", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
			"#55BF3B", "#DF5353", "#7798BF", "#aaeeee"]
            },
        },
        series: [{
        	name:'比例',
        	// color:['#0033CC'],
        	// data:[28.9, 18.8, 19.3, 21.4, 27.0, 28.3, 39.0, 39.6, 32.4, 45.2, 39.3]
        	data:os_str
        }
        ]
    });
});

$(function () {	
	var spdata=$('#Sp_Str').val();
	if(spdata == ""){
		var sp_str = [];
	}else{
		var sp_str = eval('('+spdata+')');
	}
	$('#bar_task04').highcharts({
				chart: {
                    type: 'column'
                },
				colors: ['#5051F9'],
                title: {
            text: null
        },
        xAxis: {
            categories: [
                '联通',
                '电信',
                '方正网络',
                '零鱼沸点网络',
                '其他'
                
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: null
            },
            max:100
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                pointWidth: 120,
                borderWidth: 0,
				showInLegend: false,
				colorByPoint: true,
       		  colors:["#1C8B3F", "#8FC450","#D6DE29", "#D7711F"]
            }
        },
        series: [{
            name: '比例',
            // data: [48.9, 38.8, 39.3, 41.41,10.2]
            data: sp_str

        }]
    });
});

$(function () {	
	var brdata=$('#Br_Str').val();
	if(brdata == ""){
		var br_str = [];
	}else{
		var br_str = eval('('+brdata+')');
	}
	$('#bar_task05').highcharts({
				chart: {
                    type: 'column'
                },
				colors: ['#5051F9'],
                title: {
            text: null
        },
        xAxis: {
            categories: [
                'Firefox',
                'MSIE',
                'Chrome',
                'Safari',
                'Opera',
                'Other',
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: null
            },
            max:100
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                pointWidth: 100,
                borderWidth: 0,
				showInLegend: false,
				colorByPoint: true,
       		  colors:["#1C8B3F", "#8FC450","#D6DE29", "#D7711F", "#7798BF", "#aaeeee"]
            }
        },
        series: [{
            name: '比例',
            // data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3]
            data: br_str

        }]
    });
});
</script>
<script type="text/javascript" src="/dist/js/statisticsjs/highcharts.js"></script>
<script type="text/javascript" src="/dist/js/statisticsjs/exporting.js"></script>
<script type="text/javascript" src="/dist/js/statisticsjs/funnel.js"></script>
<script type="text/javascript" src="/dist/js/statisticsjs/drilldown.js"></script>
<script src="/dist/js/statisticsjs/jquery.vector-map.js" type="text/javascript"></script>
<script src="/dist/js/statisticsjs/china-zh.js" type="text/javascript"></script>
<script type="text/javascript">
	//时间日期插件
 	$('.form_date').datetimepicker({
		format: "yyyy-mm-dd hh:ii",
        autoclose: true,
        todayBtn: true,
		startDate: '1900-01-01 00:00:00',
		endDate: '2099-12-31 23:59:59',
        pickerPosition: "bottom-left"
    });
        $(function () {
        	var  areas_id = $('#area_json').val();
        	if(areas_id){
        		var json_areas = eval('('+areas_id+')');
        	}else{
        		var json_areas = "";
        	}
            //数据可以动态生成，格式自己定义，cha对应china-zh.js中省份的简称
           var dataStatus = json_areas
        
            $('#clientmap').vectorMap({ map: 'china_zh',
               // color: "#B4B4B4", //地图颜色
                color: "#B4B4B4", //地图颜色
                onLabelShow: function (event, label, code) {//动态显示内容
                    $.each(dataStatus, function (i, items) {
                        if (code == items.cha) {
                            label.html(items.name + items.des);
                        }
                    });
                }
            })
            $.each(dataStatus, function (i, items) {		
                if (items.des.indexOf('个') != -1) {//动态设定颜色，此处用了自定义简单的判断
                    var josnStr = "{" + items.cha + ":'#00FF00'}";
					//alert(eval('(' + josnStr + ')'));
                    $('#clientmap').vectorMap('set', 'colors', eval('(' + josnStr + ')'));
                }
            });
            $('.jvectormap-zoomin').click(); //放大
        });
	//发送日志
	function mailsinfo(obj){
		//alert(obj.id);exit;
        var mailsid = obj.id;
        var mailval = obj.name;
        $('.mail').attr('id',mailval);
        $('.mailonce').find('p').css({'line-height':'20px',"margin":'0','word-break':'break-all'});
        $.post('/task/checkbuffer',{'valsid':mailsid},function(data){
            if(data){
              $('#send_log').html(data);
            }else{
				$('#send_log').html('任务发送成功');
			} 
		},"json");
  }
 
	function page1(current){
		var current=current; 
		var cid=$("#selecttype").val();
		var stattime=$("#start_time_s").val();
		var lasttime=$("#last_time_e").val();
		var timescope=$("#timescope").val();
		$.get('/statistics/taskclassification',{'page':current,'cid':cid,'timescope':timescope,'stattime':stattime,'lasttime':lasttime},function(data){
			var con='';
			$.each(data,function(i,result){
				con += ' <tr><td>'+result.task_name+'</td><td>'+result.forward+'</td><td>'+result.runtime+'</td><td>'+result.status+'</td><td><a href="#mail'+result.id+'" name="mail'+result.id+'" id="'+result.id+'" role="button"  data-toggle="modal"  onclick="mailsinfo(this)">查看</a></td></tr>';
			});
			$('#itemContainer').html(con);
		},"json");
		
			var total=$('#tnum').val();
			var pageSize=10; 
			var currentPage=current;
			var pageMax=Math.ceil(total/pageSize);
			if(currentPage > 1){
				var prev=currentPage-1;
			}else{
				var prev=1;
			}
			if(currentPage < 1 || currentPage < pageMax){
				var next=currentPage+1;
				//alert(next);
			}else{
				var next=pageMax;
			}
			var number='';
			var inum = 5;
			for(var i=inum-1;i>=1;i--){
				var page = currentPage-i;
				if(page <= 0){
					continue;
				}
				number += '<a class="" href="javascript:void(0);" onclick="page1('+page+')">'+page+'</a>';
			}
			number += '<a class="jp-current" href="javascript:void(0);" onclick="page1('+currentPage+')">'+currentPage+'</a>';
			for(var i=1;i<inum;i++){
				page = currentPage+i;
				if( page<=pageMax ){
					number += '<a class="" href="javascript:void(0);" onclick="page1('+page+')">'+page+'</a>';
				}else{
					break;
				}
			}
			//alert(number);exit;
			var str='<div style="float:left; margin-top: 9px; margin-left: 30px;" class="fenpages">共有<b>'+total+'</b>条记录  <b>'+currentPage+'</b>/<b>'+pageMax+'</b>&nbsp;&nbsp;</div><div style="float: left; margin-top: 5px; margin-bottom: 0px; -moz-user-select: none;" class="holder"><a class="jp-first jp-disabled" href="javascript:void(0);" onclick="page1(1)" >首 页</a><a class="jp-previous jp-disabled" href="javascript:void(0);" onclick="page1('+prev+')">上一页</a>'+number+'<a class="jp-next jp-disabled" href="javascript:void(0);" onclick="page1('+next+')" >下一页</a><a class="jp-last jp-disabled" href="javascript:void(0);" onclick="page1('+pageMax+')">尾 页</a></div>';
			$('#pageId').html(str);
	}

	var selectTasktime=$("#selectTasktime").val();
	if(selectTasktime == 'setval'){
		$('#selecttime').css("display","inline");
	}
	 
	$("#selecttype").change(function(){
		$("#start_time_s").val('');
		$("#last_time_e").val('');
		$("#timescope").val('');
		$('#selecttime').css("display","none");
	});
	
	$("#timescope").change(function(){
		var select_val = $(this).children('option:selected').val();
		//alert(select_val);
		if(select_val== 'setval'){
			$('#selecttime').css("display","inline");
		}else{
			$("#start_time_s").val('');
			$("#last_time_e").val('');
			$("#selectTasktime").val(select_val);
			$('#selecttime').css("display","none");
		}
    });
	//提交搜索表单
	$('#sub_plan_id').click(function (){
		var cid=$("#selecttype").val();
		if(cid == ''){
			art.dialog.alert('任务分类不能为空');
			return false;
		}
		//alert(cid);exit;
		var start_time=$("#start_time_s").val();
		var last_time=$("#last_time_e").val();
		var timescope=$("#timescope").val();
		if(timescope == 'setval'){
			if(start_time == ''){
				art.dialog.alert('开始日期不能为空');
				return false;
			}
			
			if(last_time == ''){
				art.dialog.alert('结束日期不能为空');
				return false;
			}
		}
		$('#general_from').submit();
	});
	//onchange="$('#general_from').submit();"
</script>
<!--Footer-part-->
{%include file="footer.php"%}
