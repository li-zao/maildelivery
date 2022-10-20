<!DOCTYPE html>
	{%if $role != "sadmin" && $role != "admin"%}
	<ul class="quick-actions" style="width: 100%;">
		<li class="bg_lo" style="width: 17.4%;"> <a href="/contact/personlist"> <i class="icon-user"></i>联系人检索</a> </li>
		<li class="bg_lg" style="width: 17.4%;"> <a href="/systemmonitor/searchlogs"> <i class="icon-cog"></i> 日志查询</a> </li>
		<li class="bg_ly" style="width: 17.4%;"> <a href="/task/listtask"> <i class="icon-inbox"></i>任务列表 </a> </li>
		<li style="width: 26.5%;" class="bg_lo"> <a href="/task/addtask"> <i class="icon-inbox"></i>创建任务</a> </li>
		<li style="margin-right: 0px;width: 17.4%;" class="bg_ls span2"> <a href="/templet/createtempl"> <i class="icon-fullscreen"></i>模板布局</a> </li>
	</ul>
	<ul class="quick-actions" style="width: 100%;">
		<li class="bg_lb" style="width: 17.4%;"> <a href="/contact/contactlist"> <i class="icon-user"></i> 联系人分组 </a> </li>
		<li style="width: 26.5%;" class="bg_ls"> <a href="/task/addtask"> <i class="icon-inbox"></i>快速创建邮件</a></li>
		<li class="bg_lb" style="width: 17.4%;"> <a href="/statistics/singletask"> <i class="icon-pencil"></i>统计报告</a> </li>
		<li class="bg_lg" style="width: 17.4%;"> <a href="/task/drafttask"> <i class="icon-inbox"></i> 任务草稿箱</a> </li>
		<li style="margin-right: 0px;width: 17.4%;" class="bg_lr span2"> <a href="/contact/subscribe"> <i class="icon-user"></i> 订阅管理</a> </li>
	</ul>
	{%else%}
	<ul class="quick-actions" style="width: 100%;">
		{%if $access->getPersonListAccess() %}
		<li class="bg_lo" style="width: 17.4%;"> <a href="/contact/personlist"> <i class="icon-user"></i>联系人检索</a> </li>
		{%else%}
		<li class="bg_lo" style="width: 17.4%;"> <a href="#"> <i class="icon-user"></i>未定</a> </li>
		{%/if%}
		{%if $access->getMailQueueAccess() %}
		<li class="bg_lg" style="width: 17.4%;"> <a href="/systemmonitor/mailqueue"> <i class="icon-cog"></i> 正常邮件队列</a> </li>
		{%else%}
		<li class="bg_lg" style="width: 17.4%;"> <a href="#"> <i class="icon-cog"></i> 未定</a> </li>
		{%/if%}
		{%if $access->getListTaskAccess() %}
		<li class="bg_ly" style="width: 17.4%;"> <a href="/task/listtask"> <i class="icon-inbox"></i>任务列表 </a> </li>
		{%else%}
		<li class="bg_ly" style="width: 17.4%;"> <a href="#"> <i class="icon-inbox"></i>未定 </a> </li>
		{%/if%}
		{%if $access->getSearchLogsAccess() %}
		<li style="width: 26.5%;" class="bg_lo"> <a href="/systemmonitor/searchlogs"> <i class="icon-cog"></i>日志查询</a> </li>
		{%else%}
		<li style="width: 26.5%;" class="bg_lo"> <a href="#"> <i class="icon-cog"></i>未定</a> </li>
		{%/if%}
		{%if $access->getLicenseAccess() %}
		<li style="margin-right: 0px;width: 17.4%;" class="bg_ls span2"> <a href="/setting/license/"> <i class="icon-fullscreen"></i>授权管理</a> </li>
		{%else%}
		<li style="margin-right: 0px;width: 17.4%;" class="bg_ls span2"> <a href="#"> <i class="icon-fullscreen"></i>未定</a> </li>
		{%/if%}
	</ul>
	<ul class="quick-actions" style="width: 100%;">
		{%if $access->getContactListAccess() %}
		<li class="bg_lb" style="width: 17.4%;"> <a href="/contact/contactlist"> <i class="icon-user"></i> 联系人分组 </a> </li>
		{%else%}
		<li class="bg_lb" style="width: 17.4%;"> <a href="#"> <i class="icon-user"></i> 未定 </a> </li>
		{%/if%}
		{%if $access->getSecurityParamAccess() %}
		<li style="width: 26.5%;" class="bg_ls"> <a href="/setting/securityparam/"> <i class="icon-tint"></i>投递参数管理</a></li>
		{%else%}
		<li style="width: 26.5%;" class="bg_ls"> <a href="#"> <i class="icon-tint"></i>未定</a></li>
		{%/if%}
		{%if $access->getSingleTaskAccess() %}
		<li class="bg_lb" style="width: 17.4%;"> <a href="/statistics/singletask"> <i class="icon-pencil"></i>统计报告</a> </li>
		{%else%}
		<li class="bg_lb" style="width: 17.4%;"> <a href="#"> <i class="icon-pencil"></i>未定</a> </li>
		{%/if%}
		{%if $access->getNetworkSetAccess() %}
		<li class="bg_lg" style="width: 17.4%;"> <a href="/setting/networksetting/"> <i class="icon-signal"></i> 网卡配置</a> </li>
		{%else%}
		<li class="bg_lg" style="width: 17.4%;"> <a href="#"> <i class="icon-signal"></i> 未定</a> </li>
		{%/if%}
		{%if $access->getNetworkToolAccess() %}
		<li style="margin-right: 0px;width: 17.4%;" class="bg_lr span2"> <a href="/setting/networktool/"> <i class="icon-signal"></i>网络工具</a> </li>
		{%else%}
		<li style="margin-right: 0px;width: 17.4%;" class="bg_lr span2"> <a href="#"> <i class="icon-signal"></i> 未定</a> </li>
		{%/if%}
	</ul>
	{%/if%}