<!DOCTYPE html>
<html class="js js rgba multiplebgs boxshadow cssgradients generatedcontent boxsizing" lang="en">
<title>MailData电子邮件分发投递系统</title>
<head>
<meta name="renderer" content="webkit">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<link href="/dist/img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
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
<!-- <script type="text/javascript" src="/dist/js/jquery-1.8.3.min.js"></script> -->
<!-- <script type="text/javascript" src="/dist/js/jquery-1.9.1.min.js"></script> -->

<script type="text/javascript" src="/dist/js/ckeditor/ckeditor.js"></script>
<!-- <script type="text/javascript" src="/dist/js/ckeditor/ckfinder/ckfinder.js"></script> -->
<!-- <script type="text/javascript" src="/dist/js/bootstrap-paginator.min.js"></script> -->

<link rel="stylesheet" href="/dist/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"/>
<script type="text/javascript" src="/dist/js/bootstrap-datetimepicker.js"></script>
<style type="text/css">
.hasnodata {
display: none		
}
</style>


</head>
<body>
<!--Header-part-->
<div id="header">
  <h1><a href="dashboard.html">邮件投递系统</a></h1>
</div>
<!--close-Header-part--> 

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse" style="left: 219px;">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">欢迎&nbsp;{%$uname%}</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="/setting/editaccount?id={%$uid%}"><i class="icon-user"></i>个人信息</a></li>
		<li class="divider"></li>
		<li><a href="/setting/modifypwd?id={%$uid%}"><i class="icon-key"></i>修改密码</a></li>
		<li class="divider"></li>
        <li><a href="/index/logout/"><i class="icon-share-alt"></i>退出</a></li>
      </ul>
    </li>
	<li class=""><a title="" target="_blank" href="/index/help" > <span class="text">在线帮助</span></a></li>
</ul>
</div>
<script type="text/javascript">
$(function(){
	$("li#{%$li_menu%}").parents('li').addClass('open');
	$("li#{%$li_menu%}").addClass('active');
	$(".modal").attr("data-backdrop", "static");

});

function pageforwoard (val) {
	if (val != "") {
		window.location.href = val;
	}
}
</script>
<!--sidebar-menu-->
<div id="sidebar">
  <ul>
	{%if $access->getFirstPageAccess()%}
	<li id="charts" class=""> <a href="/createtem/firstpage"><i class="icon-home"></i></i> <span>首页</span></a>
	</li>
	{%/if%}
	{%if 1>2%}
		{%if $access->getSystemMonitorAccess() || $access->getMailQueueAccess() || $access->getMailStatisticsAccess() || $access->getSearchLogsAccess() || $access->getDenyAccessAccess()%}
		  <li class="submenu"> <a href="#"><i class="icon icon-cog"></i> <span>系统监控123</span></a>
		 <ul>
		  {%if $access->getSystemMonitorAccess()%}
		  <li id="systemmonitor"><a href="/systemmonitor/systemmonitor" style="padding-left: 58px;">系统状态监控</a></li>
		  {%/if%}
		  {%if $access->getMailStatisticsAccess()%}
	      <li id="mailstatistics"><a href="/systemmonitor/mailstatistics" style="padding-left: 58px;">24小时邮件数量</a></li>
	      {%/if%}
		  {%if $access->getMailQueueAccess()%}
	      <li id="mailqueue"><a href="/systemmonitor/mailqueue" style="padding-left: 58px;">正常邮件队列</a></li>
		  {%/if%}
		  {%if $access->getSearchLogsAccess()%}
	      <li id="searchlogs"><a href="/systemmonitor/searchlogs" style="padding-left: 58px;">日志查询</a></li>
	      {%/if%}
		  {%if $access->getDenyAccessAccess()%}
	      <li id="denyaccess"><a href="/systemmonitor/denyaccess" style="padding-left: 58px;">连接日志</a></li>
	      {%/if%}
		 </ul>
		</li>
	    {%/if%}
    {%/if%}
	{%if $access->getConsoleSetAccess() || $access->getSendMailAccess() || $access->getAlertSetAccess() || $access->getSysClockSetAccess() || $access->getWorkingReportAccess() || $access->getLicenseAccess() || $access->getResetAccess() || $access->getPublishedInfoAccess() %}
	<li class="submenu"> <a href="#"><i class="icon icon-cog"></i> <span>系统设置</span></a> 
	 <ul>
	  {%if $access->getConsoleSetAccess() %}
	  <li id="consolesetting"><a href="/setting/consolesetting/" style="padding-left: 58px;">管理界面配置</a></li>
	  {%/if%}
	  {%if $access->getSendMailAccess() %}
	  <li id="sendmail"><a href="/setting/sendmail/" style="padding-left: 58px;">报告发送配置</a></li>
	  {%/if%}
	  {%if $access->getAlertSetAccess() %}
	  <li id="alertsetting"><a href="/setting/alertsetting/"  style="padding-left: 58px;">系统告警配置</a></li>
	  {%/if%}
       {%if $access->getWorkingReportAccess() %}
	  <li id="workingreport"><a href="/setting/workingreport/" style="padding-left: 58px;">运行情况报告</a></li>
	  {%/if%}
	  {%if $access->getSysClockSetAccess() %}
	  <li id="sysclocksetting"><a href="/setting/sysclocksetting/" style="padding-left: 58px;">时间配置</a></li>
	  {%/if%}
	  {%if $access->getLicenseAccess() %}
	  <li id="license"><a href="/setting/license/" style="padding-left: 58px;">授权管理</a></li>
	  {%/if%}
	  {%if $access->getResetAccess() %}
	  <li id="resetsetting"><a href="/setting/resetsetting/" style="padding-left: 58px;">系统维护</a></li>
	  {%/if%}
	  {%if $access->getPublishedInfoAccess() %}
	  <li id="publishedinfo"><a href="/setting/publishedinfo/" style="padding-left: 58px;">信息公告</a></li>
	  {%/if%}
	 </ul>
	</li>
	{%/if%}
	{%if $access->getNetworkSetAccess() || $access->getNetworkToolAccess() || $access->getSNMPConfigAccess() %}
	<li class="submenu"> <a href="#"><i class="icon icon-signal"></i> <span>网络设置</span></a> 
	 <ul>
	  {%if $access->getNetworkSetAccess() %}
	  <li id="networksetting"><a href="/setting/networksetting/" style="padding-left: 58px;">网卡配置</a></li>
	  {%/if%}
	  {%if $access->getNetworkToolAccess() %}
	  <li id="networktool"><a href="/setting/networktool/" style="padding-left: 58px;">网络工具</a></li>
	  {%/if%}
	  {%if $access->getSNMPConfigAccess() %}
	  <li id="snmpconfiguration"><a href="/setting/snmpconfiguration/" style="padding-left: 58px;">SNMP管理</a></li>
	  {%/if%}
	 </ul>
	</li>
	{%/if%}
	{%if $access->getSecurityParamAccess() || $access->getSingleDomainAccess() || $access->getTrustipTableAccess() || $access->getStaticMxAccess() || $access->getAuthSettingAccess() || $access->getUserInterceptAccess()%}
	<li class="submenu"> <a href="#"><i class="icon icon-tint"></i> <span>参数设置</span></a> 
	 <ul>
	  {%if $access->getSecurityParamAccess() %}
	  <li id="securityparam"><a href="/setting/securityparam/" style="padding-left: 58px;">投递参数管理</a></li>
	  {%/if%}
	  {%if $access->getSingleDomainAccess() %}
	  <li id="singledomain"><a href="/setting/singledomain/" style="padding-left: 58px;">单域参数设置</a></li>
	  {%/if%}
	  {%if $access->getTrustipTableAccess() %}
	  <li id="trustiptable"><a href="/setting/trustiptable/" style="padding-left: 58px;">信任来源地址</a></li>
	  {%/if%}
	  {%if $access->getStaticMxAccess() %}
	  <li id="staticmx"><a href="/setting/staticmx/" style="padding-left: 58px;">静态中继路由</a></li>
	  {%/if%}
	  {%if $access->getAuthSettingAccess() %}
	 <!--<li id="authsetting"><a href="/setting/authsetting/" style="padding-left: 58px;">用户认证设置</a></li>-->
	  {%/if%}
	  {%if $access->getUserInterceptAccess() %}
	  <li id="userintercept"><a href="/setting/userintercept/" style="padding-left: 58px;">拦截用户列表</a></li>
	  {%/if%}
	 </ul>
	</li>
	{%/if%}
	{%if $access->getPersonListAccess() || $access->getExpansionAccess() || $access->getContactListAccess() || $access->getFilterAccess() || $access->getFormListAccess()%}
	<li class="submenu"> <a href="#"><i class="icon icon-user"></i> <span>联系人管理</span></a> 
	 <ul>
	 	{%if $access->getPersonListAccess() %}
        <li id="personlist"><a href="/contact/personlist" style="padding-left: 58px;">联系人库</a></li>
        {%/if%}
        {%if $access->getExpansionAccess() && $role == 'stasker'%}
		<li id="expansion"><a href="/contact/expansion" style="padding-left: 58px;">自定义属性</a></li>
		{%/if%}
		{%if $access->getContactListAccess() %}
        <li id="contactlist"><a href="/contact/contactlist" style="padding-left: 58px;">联系人分组</a></li>
        {%/if%}
        {%if $access->getFilterAccess() %}
		<li id="filter"><a href="/contact/filter" style="padding-left: 58px;">联系人筛选</a></li>
		{%/if%}
		{%if $access->getFormListAccess() %}
		<li id="subscribe"><a href="/contact/subscribe" style="padding-left: 58px;">订阅管理</a></li>
		{%/if%}
      </ul>
	</li>
	{%/if%}
    {%if $access->getCreateTemplAccess() || $access->getMyTemplAccess() || $access->getPresetTemplAccess() || $access->getMgAttachAccess() || $access->getMgImagesAccess() %}
	<li class="submenu"> <a href="#"><i class="icon icon-list-alt"></i> <span>邮件内容管理</span></a> 
     <ul>
		{%if $access->getCreateTemplAccess() %}
		<li id="createtempl"><a href="/templet/createtempl" style="padding-left: 58px;">创建模板</a></li>
		{%/if%}
		{%if $access->getMyTemplAccess() %}
		<li id="mytempl"><a href="/templet/mytempl" style="padding-left: 58px;">我的模板</a></li>
		{%/if%}
		{%if $access->getPresetTemplAccess() %}
        <li id="preset"><a href="/templet/preset" style="padding-left: 58px;">预设模板</a></li>
		{%/if%}
		{%if $access->getMgAttachAccess() %}
        <li id="mgattach"><a href="/createtem/mgattach" style="padding-left: 58px;">附件管理</a></li>
		{%/if%}
		{%if $access->getMgImagesAccess() %}
        <li id="mdimages"><a href="/createtem/mdimages" style="padding-left: 58px;">图片管理</a></li>
		{%/if%}
     </ul>
    </li>
    {%/if%}
    {%if $access->getCreateTaskAccess() || $access->getAddTaskAccess() || $access->getDraftTaskAccess() || $access->getListTaskAccess() || $access->getTypeTaskAccess() %}
	<li class="submenu"> <a href="#"><i class="icon icon-inbox"></i> <span>投递任务管理</span></a> 
	  <ul>
		{%if $access->getCreateTaskAccess() %}
		<li id="create"><a href="/task/create/" style="margin-left:0px;padding-left: 58px;">创建任务向导</a></li>
		{%/if%}
		{%if $access->getAddTaskAccess() %}
        <li id="addtask"><a href="/task/addtask/" style="padding-left: 58px;">创建任务</a></li>
		{%/if%}
		{%if $access->getDraftTaskAccess() %}
        <li id="drafttask"><a href="/task/drafttask/" style="padding-left: 58px;">任务草稿箱</a></li>
		{%/if%}
		{%if $access->getListTaskAccess() %}
		<li id="listtask"><a href="/task/listtask/" style="padding-left: 58px;">任务列表</a></li>
		{%/if%}
		{%if $access->getTypeTaskAccess() %}
		<li id="typetask"><a href="/task/typetask/" style="padding-left: 58px;">任务分类</a></li>
		{%/if%}
      </ul>
	</li>
	{%/if%}
	{%if $access->getSingleTaskAccess() || $access->getTaskClassificationAccess() || $access->getReleasePersonAccess() || $access->getAllTasksAccess() || $access->getAllForwardAccess() %}
	<li class="submenu"> <a href="#" ><i class="icon icon-pencil"></i> <span>统计分析</span></a>
		<ul>
		{%if $access->getSingleTaskAccess() %}
		<li id="singletask"><a href="/statistics/singletask/" style="padding-left: 58px;">按单次任务统计</a></li>
		{%/if%}	
		{%if $access->getTaskClassificationAccess() %}
		<li id="taskclassification"><a href="/statistics/taskclassification/" style="padding-left: 58px;">按任务分类统计</a></li>
		{%/if%}		
		{%if $access->getReleasePersonAccess() %}
		<li id="releaseperson"><a href="/statistics/releaseperson/" style="padding-left: 58px;">按发布人员统计</a></li>
		{%/if%}	
		{%if $access->getAllTasksAccess() %}
		<li id="alltaskstatistics"><a href="/statistics/alltaskstatistics/" style="padding-left: 58px;">按全部任务统计</a></li>
		{%/if%}	
		{%if $access->getAllForwardAccess() %}
		<li id="allforwardstatistics"><a href="/statistics/allforwardstatistics/" style="padding-left: 58px;">按全部转发统计</a></li>
		{%/if%}
      </ul>	
	</li>
	{%/if%}
	{%if $access->getAdminCreationAccess() and $role != 'tasker' %}
	<li class="submenu" id="accountmanage"> <a href="#" onclick="javascript:void(pageforwoard('/setting/accountmanage/'))"><i class="icon icon-user"></i> <span>账户管理</span></a> </li>
	{%else%}
	<!--<li class="submenu" id="accountmanage"> <a href="#" onclick="javascript:void(pageforwoard('/setting/editaccount?id={%$uid%}'))"><i class="icon icon-signal"></i> <span>个人信息</span></a> </li>-->
	<li class="submenu" id="accountmanage"> <a href="#" onclick="javascript:void(pageforwoard('/setting/accountmanage/'))"><i class="icon icon-signal"></i> <span>个人信息</span></a> </li>
	{%/if%}
    <li class="content" style="padding-left: 20px;"> <span>每日邮件发送量百分比</span>
      <div class="progress progress-mini progress-danger active progress-striped">
        <div id="dm" style="width:{%($ratio)%}%;background:#1E242B" class="bar"></div>
      </div> <span style="float: right;margin-top: -15px;">{%$ratio%} %</span>
      <div class="stat">{%$curtotal%} / {%$curdaytotal%} </div>
    </li>
  </ul>
</div>
