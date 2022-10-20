<!DOCTYPE html>
<html class="js js rgba multiplebgs boxshadow cssgradients generatedcontent boxsizing" lang="en">
<head>
<meta name="renderer" content="webkit">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<link href="" type="image/x-icon" rel="shortcut icon" />
<link rel="stylesheet" href="/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="/dist/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/dist/css/uniform.css" />
<link rel="stylesheet" href="/dist/css/select2.css" />
<link rel="stylesheet" href="/dist/css/jquery.gritter.css" />
<link rel="stylesheet" href="/dist/css/matrix-style.css" />
<link rel="stylesheet" href="/dist/css/matrix-media.css" />
<link rel="stylesheet" href="/dist/css/templet.css" />
<link rel="stylesheet" href="/dist/css/jquery.fileupload-ui.css" />
<link href="/dist/font-awesome/css/font-awesome.css" rel="stylesheet" />
<script type="text/javascript" src="/dist/js/jquery.min.js"></script>
<script type="text/javascript" src="/dist/js/jquery.validate.js"></script>
<script type="text/javascript" src="/dist/js/jquery.artDialog.source.js?skin=black"></script>
<script type="text/javascript" src="/dist/js/ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="/dist/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"/>
<script type="text/javascript" src="/dist/js/bootstrap-datetimepicker.js"></script>
</head>
<body style="background:#FCFCFC;">

<script type="text/javascript" src="/dist/js/highcharts.src.js"></script>
<script type="text/javascript" src="/dist/js/highcharts-3d.src.js"></script>

 
  <!--住图-->
    <div class="row-fluid">
       <div class="span6">
		 <div class="widget-box">
		    <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
				<h5>转发邮件数量统计图（{%$timespan%}小时柱状图）</h5>
		    </div>
		    <div class="widget-content">
			    <div id="overseas" style="width:100%;height:300px;"></div>
		    </div>
		</div>
	   </div>
	   <div class="span6">
			<div class="widget-box">
			  <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
				<h5>任务邮件数量统计图（{%$timespan%}小时柱状图）</h5>
			  </div>
			  <div class="widget-content">
				   <div id="domestic" style="width:100%;height:300px;"></div>
			  </div>
			</div>
	   </div>
    </div>
	<!--饼图-->
	<div class="row-fluid" style="margin-top:-10px">
       <div class="span6" style="margin-top:-10px">
		 <div class="widget-box" style="margin-top:-10px">
		    <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
				<h5>转发邮件数量统计图（{%$timespan%}小时饼状图）</h5>
			</div>
			<div class="widget-content">
				<div id="overseaspie" style="width:100%;height:300px;"></div>
			</div>
		 </div>
		</div>
	    <div class="span6" style="margin-top:-10px">
		  <div class="widget-box" style="margin-top:-10px">
		    <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
			  <h5>任务邮件数量统计图（{%$timespan%}小时饼状图）</h5>
		    </div>
		    <div class="widget-content">
			     <div id="domesticpie" style="width:100%;height:300px;"></div>
		    </div>
		  </div>
	    </div>
      </div>
	<div class="row-fluid" style="margin-top:-10px">
       <div class="span6"  style="margin-top:-10px">
									<div class="widget-box" style="margin-top:-10px">
									  <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
										<h5>转发邮件发送数量分析表</h5>
									  </div>
									  <div class="widget-content nopadding">
										<table class="table table-bordered table-striped">
										  <thead>
											<tr>
											  <th>数量统计详情</th>
											  <th>本小时</th>
											  <th>{%$timespan%}小时总计</th>
											  <th>累计总量</th>
											</tr>
										  </thead>
										  <tbody>
											<tr class="even gradeC">
											  <td>成功</td>
											  <td id="sth1">{%$smtp_success%}</td>
											  <td id="sph1"></td>
											  <td id="sat1">{%$total_smtp_success%}</td>
											</tr>
											<tr class="odd gradeA">
											  <td>硬退</td>
											  <td id="fth1">{%$smtp_fail%}</td>
											  <td id="fph1"></td>
											  <td id="fat1">{%$total_smtp_fail%}</td>
											</tr>
											<tr class="odd gradeA">
											  <td>软退</td>
											  <td id="sfth1">{%$smtp_softfailure%}</td>
											  <td id="sfph1"></td>
											  <td id="sfat1">{%$total_smtp_softfailure%}</td>
											</tr>
											<tr class="odd gradeX">
											  <td>合计</td>
											  <td id="tth1">{%$smtp_total_data%}</td>
											  <td id="tph1"></td>
											  <td id="tat1">{%$total_smtp_total_data%}</td>
											</tr>
										  </tbody>
										</table>
									  </div>
									</div>
							  </div>
							  <div class="span6" style="margin-top:-10px">
									<div class="widget-box" style="margin-top:-10px">
									  <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
										<h5>任务邮件发送数量分析表</h5>
									  </div>
									  <div class="widget-content nopadding">
										<table class="table table-bordered table-striped">
										  <thead>
											<tr>
											   <th>数量统计详情</th>
											  <th>本小时</th>
											  <th>{%$timespan%}小时总计</th>
											  <th>累计总量</th>
											</tr>
										  </thead>
										  <tbody>
											<tr class="odd gradeX">
											  <td>成功</td>
											  <td id="sth0">{%$task_success%}</td>
											  <td id="sph0"></td>
											  <td id="sat0">{%$total_task_success%}</td>
											</tr>
											<tr class="even gradeC">
											  <td>硬退</td>
											  <td id="fth0">{%$task_fail%}</td>
											  <td id="fph0"></td>
											  <td id="fat0">{%$total_task_fail%}</td>
											</tr>
											<tr class="even gradeC">
											  <td>软退</td>
											  <td id="sfth0">{%$task_softfailure%}</td>
											  <td id="sfph0"></td>
											  <td id="sfat0">{%$total_task_softfailure%}</td>
											</tr>
											<tr class="odd gradeX">
											  <td>合计</td>
											  <td id="tth0">{%$task_total_data%}</td>
											  <td id="tph0"></td>
											  <td id="tat0">{%$total_task_total_data%}</td>
											</tr>
										  </tbody>
										</table>
									  </div>
									</div>
							  </div>
    </div>
  

<input type="hidden" value="{%$timespan%}" id="timespan" />
<script type="text/javascript">
$(function () {
	var datas = "";
	var timespan = $("#timespan").val();
	$.post('/systemmonitor/getdiffstats',{timespan:timespan},function(data){
        if (data) {
			datas = eval('('+data+')');
			// smtp
			datas[1].hour = eval('['+datas[1].hour+']');
			datas[1].success = eval('['+datas[1].success+']');
			datas[1].failure = eval('['+datas[1].failure+']');
			datas[1].softfailure = eval('['+datas[1].softfailure+']');
			// task
			datas[0].hour = eval('['+datas[0].hour+']');
			datas[0].success = eval('['+datas[0].success+']');
			datas[0].failure = eval('['+datas[0].failure+']');
			datas[0].softfailure = eval('['+datas[0].softfailure+']');
		    $('#overseas').highcharts({
				colors:["#5ab75a","#db4e48","#fba732"],
				chart: {
					type: 'column'
				},
				title: {
					text: '',
					style: {"fontSize": "15px"}
				},
				xAxis: {
					categories: datas[1].hour
				},
				yAxis: {
					min: 0,
					title: {
						text: ''
					},
					stackLabels: {
						enabled: false,
						style: {
							fontWeight: 'bold',
							color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
						}
					}
				},
				credits : { 
					enabled : false
				},
				tooltip: {
					formatter: function() {
						return '<b>'+ this.x +'</b><br/>'+
							this.series.name +': '+ this.y +'<br/>'+
							'总数: '+ this.point.stackTotal;
					}
				},
				plotOptions: {
					column: {
						stacking: 'normal',
						dataLabels: {
							enabled: false,
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
							style: {
								textShadow: '0 0 3px black, 0 0 3px black'
							}
						}
					}
				},
				series: [{
					name: '成功',
					data: datas[1].success
				}, {
					name: '硬退',
					data: datas[1].failure
				}, {
					name: '软退',
					data: datas[1].softfailure
				}]
			});
			// type:bar, overseas:0
			$('#domestic').highcharts({
				colors:["#5ab75a","#db4e48","#fba732"],
				chart: {
					type: 'column'
				},
				title: {
					text: '',
					style: {"fontSize": "15px"}
				},
				xAxis: {
				 categories: datas[0].hour
				},
				yAxis: {
					min: 0,
					title: {
						text: ''
					},
					stackLabels: {
						enabled: false,
						style: {
							fontWeight: 'bold',
							color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
						}
					}
				},
				credits : { 
					enabled : false
				},
				tooltip: {
					formatter: function() {
						return '<b>'+ this.x +'</b><br/>'+
							this.series.name +': '+ this.y +'<br/>'+
							'总数: '+ this.point.stackTotal;
					}
				},
				plotOptions: {
					column: {
						stacking: 'normal',
						dataLabels: {
							enabled: false,
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
							style: {
								textShadow: '0 0 3px black, 0 0 3px black'
							}
						}
					}
				},
				series: [{
					name: '成功',
					data: datas[0].success
				}, {
					name: '硬退',
					data: datas[0].failure
				}, {
					name: '软退',
					data: datas[0].softfailure
				}]
			});
			// type:pie, overseas:1
			$('#overseaspie').highcharts({
				colors:["#5ab75a","#db4e48","#fba732"],
				chart: {
					type: 'pie',
					options3d: {
						enabled: true,
						alpha: 45,
						beta: 0
					}
				},
				title: {
					text: ''
				},
				credits : { 
					enabled : false
				},
				tooltip: {
					pointFormat: '总量:' + datas[3].total + ',{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						depth: 35,
						dataLabels: {
							enabled: true,
							format: '{point.name}'
						}
					}
				},
				series: [{
					type: 'pie',
					name: '占比',
					data: [
						['成功',   datas[3].success],
						['硬退',   datas[3].failure],
						['软退',   datas[3].softfailure]
					]
				}]
			});		
			// type:pir, overseas:0
			$('#domesticpie').highcharts({
				colors:["#5ab75a","#db4e48","#fba732"],
				chart: {
					type: 'pie',
					options3d: {
						enabled: true,
						alpha: 45,
						beta: 0
					}
				},
				title: {
					text: ''
				},
				credits : { 
					enabled : false
				},
				tooltip: {
					pointFormat: '总量:' + datas[2].total + ',{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						depth: 35,
						dataLabels: {
							enabled: true,
							format: '{point.name}'
						}
					}
				},
				series: [{
					type: 'pie',
					name: '占比',
					data: [
						['成功',   datas[2].success],
						['硬退',   datas[2].failure],
						['软退',   datas[2].softfailure]
					]
				}]
			});	
			// past 24 hour Detailed statistics
			if (datas[2].success == '0.1') {
				$("#fph0").text(0);
				$("#sfph0").text(0);
				$("#wph0").text(0);
				$("#sph0").text(0);
				$("#tph0").text(0);
			} else {
				$("#fph0").text(datas[2].failure);
				$("#sfph0").text(datas[2].softfailure);
				$("#wph0").text(datas[2].wait);
				$("#sph0").text(datas[2].success);
				$("#tph0").text(datas[2].total);
			}
			if (datas[3].success == '0.1') {
				$("#fph1").text(0);
				$("#sfph1").text(0);
				$("#wph1").text(0);
				$("#sph1").text(0);
				$("#tph1").text(0);
			} else {
				$("#fph1").text(datas[3].failure);
				$("#sfph1").text(datas[3].softfailure);
				$("#wph1").text(datas[3].wait);
				$("#sph1").text(datas[3].success);
				$("#tph1").text(datas[3].total);
			}
		} 
		//$("text[text-anchor='end']").hide();
    });
});
</script>
{%include file="footer.php"%}