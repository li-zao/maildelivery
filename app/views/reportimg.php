<!DOCTYPE html>
<html class="js js rgba multiplebgs boxshadow cssgradients generatedcontent boxsizing" lang="en">
<title>邮件投递系统</title>
<meta name="renderer" content="webkit">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<!-- <link href="/dist/img/111.jpg" type="image/x-icon" rel="shortcut icon" /> -->
<!-- 
<link rel="stylesheet" href="/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="/dist/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/dist/css/uniform.css" />
<link rel="stylesheet" href="/dist/css/select2.css" />
<link rel="stylesheet" href="/dist/css/jquery.gritter.css" />
<link rel="stylesheet" href="/dist/css/matrix-style.css" />
<link rel="stylesheet" href="/dist/css/matrix-media.css" />
<link rel="stylesheet" href="/dist/css/templet.css" />
<link rel="stylesheet" href="/dist/css/jquery.fileupload-ui.css" />
<link href="/dist/font-awesome/css/font-awesome.css" rel="stylesheet" /> -->
<script type="text/javascript" src="/dist/js/jquery.min.js"></script>
<!-- <script type="text/javascript" src="/dist/js/jquery.validate.js"></script> -->
<!-- <script type="text/javascript" src="/dist/js/jquery.artDialog.source.js?skin=black"></script> -->
<!-- <script type="text/javascript" src="/dist/js/jquery-1.8.3.min.js"></script> -->
<!-- <script type="text/javascript" src="/dist/js/jquery-1.9.1.min.js"></script> -->

<!-- <script type="text/javascript" src="/dist/js/ckeditor/ckeditor.js"></script> -->
<!-- <script type="text/javascript" src="/dist/js/ckeditor/ckfinder/ckfinder.js"></script> -->
<!-- <script type="text/javascript" src="/dist/js/bootstrap-paginator.min.js"></script> -->

<!-- <link rel="stylesheet" href="/dist/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"/> -->
<!-- <script type="text/javascript" src="/dist/js/bootstrap-datetimepicker.js"></script> -->
<link href="/dist/css/jquery.vector-map.css" media="screen" rel="stylesheet" type="text/css" />
<!-- <script type="text/javascript" src="/dist/js/jPages.js"></script> -->
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
	margin: 0;
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
  .holder span { margin: 0 0px; }
</style>
				<div class="widget-box" style="border:0; width: 100%; margin:0 auto;">
					  <div class="widget-content tab-content" style="padding: 0px;border:0;overflow:hidden;width: 600px; margin: 0px auto;">
						<div class="tab-pane active" id="tab1">
							<div class="row-fluid" style="margin-top: 10px;">
							  <div class="span6">
								<div class="widget-box">
								  <div class="widget-title" > <span class="icon"> <i class="icon-signal"></i> </span>
									<h5 >邮件发送情况分析</h5>
								  </div>
								  <div class="widget-content">
									  <div id="pie_task01" style="width:499px;height:300px;"></div>
								  </div>
								</div>
							  </div>
							  <div class="span6">
								<div class="widget-box">
								  <div class="widget-title" > <span class="icon"> <i class="icon-signal"></i> </span>
									<h5 >邮件反馈情况分析</h5>
								  </div>
								  <div class="widget-content">
									   <div id="chart_task01" style="width:499px;height:300px;"></div>
								  </div>
								</div>
							  </div>
							 
							</div>
						</div>
					  </div>
				</div>
<input type="hidden" value="{%$sendresult.data%}" name="sentdata" id="sentdata">
<input type="hidden" value="{%$backresult.data%}" name="backdata" id="backdata">
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
            plotShadow: false,
            width:600
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
	 $('#chart_task01').highcharts({
		colors: ["#1C8B3F", "#8FC450","#D6DE29", "#D7711F", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
		"#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
        chart: {
            type: 'pyramid',
            marginRight: 100,
            width:600
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
            enabled: true
        },
        series: [{
            name: 'Task number',
            data: back
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
<!--Footer-part-->
