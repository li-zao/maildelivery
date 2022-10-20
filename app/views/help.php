<!DOCTYPE html>
<html class="js js rgba multiplebgs boxshadow cssgradients generatedcontent boxsizing" lang="en">
<title>MailData电子邮件分发投递系统</title>
<head>
<meta name="renderer" content="webkit">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<link href="/dist/img/111.jpg" type="image/x-icon" rel="shortcut icon" />
<link rel="stylesheet" href="/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="/dist/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/dist/css/matrix-style.css" />
<link rel="stylesheet" href="/dist/css/matrix-media.css" />
<link href="/dist/font-awesome/css/font-awesome.css" rel="stylesheet" />
<script type="text/javascript" src="/dist/js/jquery.min.js"></script>
<script src="/dist/js/bootstrap.min.js"></script> 
<script src="/dist/js/jquery.ui.custom.js"></script> 
<style>
	#sidebar ul li.current {background: #28b779;}
	#sidebar ul li.current a {color: #fff;}
	.pstyle{text-indent:2em;}
	.back{float: right;font-size: 12px;margin-top: 15px;width: 120px;height:30px;}
	.back a{color: #f2683e;}
	.back a:hover{color: #f30;}
	.return_home{margin-right: 15px;};
</style>
<script>
$(function(){
	$("#sidebar li").click(function(){
		var cl = $(this).attr("class");
		if(cl == "current"){
			// $(this).removeClass("current");
			return true;
		}else{
			$(this).addClass("current").siblings("li").removeClass("current");
		}
	});	
})
</script>

</head>
<body>
<div id="header">
  <h1 id='top'><a href="dashboard.html">邮件投递系统</a></h1>
</div>

<div id="user-nav" class="navbar navbar-inverse" style="left: 219px;">
    
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">欢迎&nbsp;{%$uname%}</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="/index/logout/"><i class="icon-share-alt"></i>退出</a></li>
      </ul>
    </li>
	<li class=""><a title="" target="_blank" href="/index/help" > <span class="text">在线帮助</span></a></li>
</ul>
</div>
<div id="sidebar">
{%include file="helpMenu.php"%}
</div>
<div id="content">
	<div class="container-fluid">
		<div class="row-fluid">
				<div class="span10 offset1">
					<a name='01'></a>
						<div  id="answer1">
							<h3>MailData投递系统管理员登录</h3>
							<p class="pstyle" text-indet：2em>MailData邮件投递系统采用B/S架构，全局采用WEB方式进行管理和操作，无需安装任何额外插件及客户端即可实现随时随地对邮件投递系统的维护和使用。
							</p>
							<p class="pstyle">
								进入方式：使用IE等浏览器，在地址栏输入 http://投递系统管理IP地址:9090 ，如图1-1所示，界面即MailData邮件投递系统管理员登录界面，默认用户：admin ，密码：admin，验证码：随机出现的验证码信息。
							</p>
							</br>
							<center><img class="myimage" src="/dist/img/adminhelp/1-1.png" style="">	
							</br>图1-1</center>
							</br>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆验证码中出现的字母不区分大小写，如“看不清验证码，可点击登录页面中验证码图片处，换着图片；</br>                           
							☆ 管理端口如“不希望使用默认的9090端口，可登录后进行修改；</br>
							☆ 用户可自行选择中文界面或英文界面进行MailData邮件归档系统的管理配置；</br>
							☆ 端口必须在1024～65535之间。<br>
							<p ><strong>【忘记密码】</strong></p>
							<p class="pstyle">
								提供已创建登录用户的密码重置功能（admin除外）。点击图1-1中的“忘记密码”按钮，出现如图1-2所示窗口，输入用户名、及邮箱地址，点击“发送邮件”按钮，系统会将新的密码发送至邮箱。点击“返回登录”，输入用户名、新的密码即可登录成功。</p>
								<br>
							<center><img class="myimage" src="/dist/img/adminhelp/1-2.png" style="">	
							</br>图1-2</center>
							</br>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆邮箱地址必须为用户创建时所用邮箱地址。</br>                           
							☆ 新的密码为系统随机生成的，为确保安全，用户登录后，修改密码。</br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer2">
							<a namee='02'></a>
							<h3>首页</h3>
							<p class="pstyle" text-indet：2em>【首页】模块通过当前登录管理员信息、联系人情况、最新执行任务情况、邮件总发送情况、信息公告和计划任务模块的细分，使系统管理员能快速、有效的了解MailData邮件投递系统当前登录信息，投递、转发邮件的数量以及联系人、订阅、退订用户数量等相关信息。
							</p>
							<p class="pstyle">
								如图1-3所示，主要是快速链接常用的几个模块，方便系统管理员使用。
							</p>
							<p class="pstyle">
								"联系人组"——链接联系人管理的联系人分组模块；
							</p>
							<p class="pstyle">
								"任务缓冲区监控"——链接系统监控的任务缓冲区监控模块；
							</p>
							<p class="pstyle">
								"任务列表"——链接投递任务管理的任务列表模块；
							</p>
							<p class="pstyle">
								"转发统计分析"——链接转发设置的转发统计分析模块；
							</p>
							<p class="pstyle">
								"授权管理"——链接系统设置的授权管理模块；
							</p>
							<p class="pstyle">
								"联系人检索"——链接联系人管理的联系人库模块；
							</p>
							<p class="pstyle">
								"安全参数管理"——链接安全设置的安全参数管理模块；
							</p>
							<p class="pstyle">
								"统计报告"——链接统计分析的按单次任务统计模块；
							</p>
							<p class="pstyle">
								"网卡配置"——链接网络设置的网卡配置模块；
							</p>
							<p class="pstyle">
								"网络工具"——链接网络设置的网络工具模块。
							</p>							
							</br>
							<center><img class="myimage" src="/dist/img/adminhelp/1-3.png" style=" ">	
							</br>图1-3</center>

							<p class="pstyle">■ 管理员信息</p>
						<p class="pstyle">【管理员信息】模块提供当前登录用户信息、及实时统计系统全部邮件累加、日均发送量的信息。如图1-4所示。即登录用户名称、上次登录时间、IP地址；系统当前递送邮件的累加发送量和日均发送量。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-4.png" style=" ">	
							</br>图1-4</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆累加、日均发送量，系统管理员统计系统全部投递邮件数量，包括任务投递邮件、转发投递邮件。</br> <br>                          
							<p class="pstyle">■ 联系人情况</p>
						<p class="pstyle">【联系人情况】提供系统联系人库、组、订阅、退订数量的统计。如图1-5所示，即包括系统全部联系人数量、联系人组数量、订阅、退订用户数量。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-5.png" style=" ">	
							</br>图1-5</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 全部联系人、联系人组、订阅、退订用户的数量是对整个投递系统的统计，即全部系统管理员及任务发布员下的联系人、联系人组、订阅、退订用户的数量。<br>
							<br>
							<p class="pstyle">■ 最新任务执行情况</p>
						<p class="pstyle">【最新任务执行情况】对系统中最新创建的四个投递任务的发送完成情况，进行实时统计。如图1-6所示，包括每一个投递任务的任务名称、当前任务完成比率、总发送量及已完成量。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-6.png" style=" ">	
							</br>图1-6</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 系统管理员统计全部任务中最新创建的四条任务；</br>  
							☆ 任务名称过长时，省略显示，鼠标停放任务名称处时，会悬浮显示任务全称。
							<br>
							<br>
							<p class="pstyle">■ 邮件总发送情况</p>
						<p class="pstyle">【邮件总发送情况】提供系统最近24小时投递邮件数量统计。如图1-7所示，包括任务发送量、转发发送量、及两者邮件总量。点击图1-7中下方的“任务发送量”、“转发发送量”、“邮件总量”，可分别查看最近24小时邮件数量。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-7.png" style=" ">	
							</br>图1-7</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 新设备安装完，默认是从20：00到20：00。
							<br>
							<br>
							<p class="pstyle">■ 信息公告栏</p>
						<p class="pstyle">【信息公告栏】提供管理员发布信息、公告等，便于其它登录用户熟知、查看。管理员在“系统设置—信息公告”模块中发布的信息、公告。如图1-8所示，默认显示最新的5条信息（系统默认有5条信息），点击“信息标题”即可查看详细信息。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-8.png" style=" ">	
							</br>图1-8</center>
							<br>
							<br>
							<p class="pstyle">■ 计划任务</p>
						<p class="pstyle">【计划任务】提供登录用户记录、查看信息，便于对登录用户重要事件的提醒，类似于便签。如图1-9所示，点击任意一日期，可添加“新建事件”。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-9.png" style=" ">	
							</br>图1-9</center>
						<p class="pstyle">如图1-10所示，添加日程内容，最多六个字（包括数量、字母、汉字），选择开始时间、结束时间，点击“确定”按钮后，添加事件成功。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-10.png" style=" ">	
							</br>图1-10</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 每天最多添加2条事件；<br>
							☆ 添加成功的事件，可以删除，但不可修改。
							<br>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer3">
							<a name="03"></a>
							<h3>系统监控</h3>
							<p class="pstyle" text-indet：2em>【系统监控】模块提供MailData邮件投递系统运行状态、邮件投递发送情况的实时监控展示。管理员通过该模块了解系统运行情况及最近24小时邮件投递情况。
							</p>	
						</div>
						<div id="answer4">
							<a name="04"></a>
							<h3>系统状态监控</h3>
							<p class="pstyle" text-indet：2em>【系统状态监控】提供MailData邮件投递系统的CPU、内存及各分区存储状态信息的监控显示。
							</p>	
							<p class="pstyle">■ CPU、RAM状态</p>
						<p class="pstyle">CPU、RAM状态的使用率均为当前时刻系统的占用率。如图1-11所示，点击【系统状态监控】即可对CPU、内存状态的占用率进行刷新。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-11.png" style=" ">	
							</br>图1-11</center>
							<p class="pstyle">■ 各分区状态</p>
						<p class="pstyle">各分区存储状态记录MailData邮件投递系统后台磁盘各分区总量、已使用量和使用率。如图1-12所示，当鼠标在蓝色条位置时，会显示此分区的“总量”、“已使用量”；当鼠标在白色条位置时，会显示此分区的“使用率”。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-12.png" style=" ">	
							</br>图1-12</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆当分区的占用率达到管理员设定的分区告警线时，会以告警信的形式，提醒管理员及时清理不必要的数据，保证各个分区有足够的使用空间。
							<br>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer7">
							<a name='07'></a>	
							<h3>任务缓冲区监控</h3>
							<p class="pstyle" text-indet：2em>【任务缓冲区监控】模块通过邮件数量统计图和邮件发送缓冲列表，对MailData邮件投递系统最近24小时任务缓冲区的邮件发送情况，进行监控统计。
							</p>	
							<p class="pstyle">■最近24小时缓冲区邮件数量统计图</p>
						<p class="pstyle">该图提供任务缓冲区最近24小时邮件发送情况的数量统计。如图1-13所示，即邮件发送量、邮件发送成功量和邮件等待量的统计图。点击下图1-13中的“发送量”、“成功”、“等待”，可分别查看最近24小时的邮件数量。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-13.png" style=" ">	
							</br>图1-13</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆正常最右侧是当前时间，默认是20：00到20：00。
							<br>
							<br>
							<p class="pstyle">■缓冲区邮件</p>
						<p class="pstyle">【缓冲区列表】统计所有投递任务中未投递完成的邮件情况。如图1-14所示，详细显示所有投递任务中每一封邮件的邮箱地址、所属任务名称、重发次数、最新执行时间、执行状态、及日志。点击日志列中的“查看”，可详细了解某一邮件的投递情况。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-14.png" style=" ">	
							</br>图1-14</center>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer5">
							<a name='05'></a>	
							<h3>转发缓冲区监控</h3>
							<p class="pstyle" text-indet：2em>【转发缓冲区监控】模块通过邮件数量统计图和邮件发送缓冲列表，对MailData邮件投递系统最近24小时转发缓冲区的邮件发送情况，进行监控统计。
							</p>	
							<p class="pstyle">■最近24小时缓冲区邮件数量统计图</p>
						<p class="pstyle">该图提供最近24小时转发缓冲区邮件发送情况的数量统计。如图1-15所示，即邮件发送量、邮件发送成功量和邮件等待量的统计图。点击下图1-15中的“发送量”、“成功”、“等待”，可分别查看最近24小时的数量统计。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-15.png" style=" ">	
							</br>图1-15</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆正常最右侧是当前时间，默认是20：00到20：00。
							<br>
							<br>
							<p class="pstyle">■缓冲区邮件</p>
						<p class="pstyle">【缓冲区列表】统计所有转发邮件中未转发完成的邮件情况。如图1-16所示，列表详细显示所有转发邮件中每一封邮件的邮箱地址、主题、大小、邮件最新执行时间、重发次数、及缓冲原因。点击邮件列表中的任意一项，可以查看某一邮件的详细情况。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-16.png" style=" ">	
							</br>图1-16</center>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer6">
							<a name='06'></a>	
							<h3>管理界面设置</h3>
							<p class="pstyle" text-indet：2em>【系统设置】功能模块提供MailData邮件投递系统的基本配置、管理设置等功能，管理员通过该模块功能配置MailData邮件投递系统的系统级操作配置。
							</p>	
							<p class="pstyle">■管理端口设置</p>
						<p class="pstyle">【管理端口设置】功能提供MailData邮件投递系统管理界面访问方式的配置管理功能。如图1-17所示，系统管理员可配置管理端口的端口号、端口超时时间以及是否启用HTTPS。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-17.png" style=" ">	
							</br>图1-17</center>	
							<p class="pstyle">■ 订阅服务端口设置</p>
						<p class="pstyle">【订阅服务端口设置】功能提供MailData邮件投递系统邮件接收用户订阅、退订等服务界面访问方式的配置管理功能。如图1-18所示，系统管理员可配置服务端口的端口号、域名（或者IP地址）以及是否启用HTTPS。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-18.png" style=" ">	
							</br>图1-18</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆域名：可以填写域名；也可填写IP地址。默认是设备出库IP地址。
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer8">
							<a name='08'></a>	
							<h3>报告发送配置</h3>
							<p class="pstyle" text-indet：2em>【报告发送设置】功能提供配置MailData邮件投递系统发送告警、报告等邮件信息邮箱地址的功能。如图1-19所示，输入SMTP服务器（邮件服务器）的IP地址、SMTP服务器端口（ESMTP为25、POP3为110、IMAP4为143）、认证用户邮箱及认证邮箱的密码。“保存”成功后，报告发送配置即设置成功。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-19.png" style=" ">	
							</br>图1-19</center>	
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer11">
							<a name='11'></a>	
							<h3>系统告警配置</h3>
							<p class="pstyle" text-indet：2em>通过设置【系统告警配置】功能，系统管理员在CPU使用率、存储使用率、转发队列数量、投递失败数量达到告警线时，及时以告警邮件方式通知系统管理员，对MailData邮件投递设备做出处理，保证系统的正常运行。如图1-20所示，设置完各项告警阀值，及告警邮件的接收人后，点击“提交”按钮，系统通知功能即会生效。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-20.png" style=" ">	
							</br>图1-20</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆使用率：最大不能超过95%；<br>
							☆ 当系统达到指定告警阀值时，系统会每隔15分钟发一封告警邮件通知管理员。
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer12">
							<a name='12'></a>	
							<h3>存储路径管理</h3>
							<p class="pstyle" text-indet：2em>【存储路径管理】功能提供MailData邮件投递系统临时邮件存储路径、临时邮件保存时间的设置，及附件存储路径、转发邮件日志保存时间的设置管理功能。如图1-21所示，管理员设置各项，“保存”成功后即会生效。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-21.png" style=" ">	
							</br>图1-21</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆邮件临时存放路径：邮件转发缓冲区中未转发成功邮件的临时存放路径；<br>
							☆上传附件存放路径：创建投递任务时等上传的附件存放路径；<br>
							☆ 邮件临时保存时间：临时存放路径中邮件的保存时间。默认保存3天，系统自动会在每天1点45分清除3天前的邮件；<br>
							☆ 转发日志保存时间：转发邮件生成日志的保存时间。默认保存60天，系统自动会在每天1点45分清除60天前的转发日志。<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer14">
							<a name='14'></a>	
							<h3>时间配置</h3>
							<p class="pstyle" text-indet：2em>【时钟设置】功能提供自定义设置MailData邮件投递系统时钟功能，或通过同步特定时钟服务器时间进行系统时间设置的管理功能。如图1-22所示，自定义设置好系统时间后，点击“提交”按钮即生效；而若通过时钟服务同步功能设置MailData邮件投递系统的时间，则选择“启用”，并在时钟服务器IP地址栏输入要进行同步的时钟服务器的IP地址，MailData邮件投递系统即会同步到所设时钟服务器的时间。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-22.png" style=" ">	
							</br>图1-22</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆“启用”同步时钟服务功能后，MailData邮件投递系统默认每小时的第5分钟同步一次时钟服务器的时间。<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer15">
							<a name='15'></a>	
							<h3>授权管理</h3>
							<p class="pstyle" text-indet：2em>授权管理记录了MailData邮件投递设备的授权信息。包括授权单日最大发送量、授权状态、授权时间以及授权导入等功能。
							</p>	
							<p class="pstyle">■授权基本信息</p>
						<p class="pstyle">如图1-23所示，单日最大发送量显示当前系统授权限定的每日最大发送量，授权状态显示当前系统授权是否正常，是否超额发送显示系统当前发送量是否超过单日授权发送量，授权时间表示当前系统授权的起始时间。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-23.png" style=" ">	
							</br>图1-23</center>	
							<p class="pstyle">■授权基本操作</p>
							<p class="pstyle">如授权过期或需对授权进行更换时，可通授权基本操作功能完成，如图1-24所示：</p>
							<p class="pstyle">【下载机器码信息文件】：点击【下载机器码信息文件】后，将下载该设备唯一对应的机器码信息，将该机器码信息交由供货商，即可生成授权文件。：</p>
							<p class="pstyle">【导入授权文件】：新的授权文件可通过该功能进行导入。点击 “浏览”按钮，选择要导入的授权文件，上传成功后即载入授权文件。</p>
							<p class="pstyle">【重启投递服务】：重新载入授权后，点击“重启投递服务”按钮，新的授权将会生效。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-24.png" style=" ">	
							</br>图1-24</center>	
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer99">
							<a name='99'></a>	
							<h3>系统维护</h3>
							<p class="pstyle" text-indet：2em>MailData邮件投递系统支持系统维护的在线重启、关闭等功能。如图1-25所示，当需要对MailData邮件投递设备进行在线重启或关闭时，则可通过点击“设备重启”或“设备关闭”实现；当需要对MailData邮件投递系统核心服务进行重启的时候，则可以通过点击“重启服务”实现。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-25.png" style=" ">	
							</br>图1-25</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆当需要对投递设备进行关闭时，请使用设备维护功能安全关闭设备，请勿直接关闭电源开关，避免造成数据丢失。<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer53">
							<a name='53'></a>	
							<h3>信息公告</h3>
							<p class="pstyle" text-indet：2em>【信息公告】功能提供系统管理员实现发布通知、公告等信息的功能。管理员通过【信息公告】发布的信息将会显示在首页【信息公告栏】中。
							<p class="pstyle" text-indet：2em>【检索条件】如图1-26所示界面上，管理员用户根据需求自定义设置完标题、内容、创建者、创建时间单条件或复合条件后，然后点击“查询”按钮，即可检索出符合条件的信息。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-26.png" style=" ">	
							</br>图1-26</center>	
							<p class="pstyle" text-indet：2em>【新建信息】如图1-26所示界面上，点击“新建信息”按钮，则会出现如下图1-27所示的界面，管理员设置完信息标题、信息内容后，点击“保存”后，即可完成信息发布。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-27.png" style=" ">	
							</br>图1-27</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ "<strong style="font-size:14px;color: red;">*</strong>"（信息标题）为必填项。<br>
							<p class="pstyle" text-indet：2em>【删除】如图1-26所示界面上，选中历史信息栏中序号前的复选框，然后点击“删除”按钮，即可删除所选的信息。</p>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer54">
							<a name='54'></a>	
							<h3>网卡配置</h3>
							<p class="pstyle" text-indet：2em>【网络设置】模块提供配置MailData邮件投递系统管理IP地址、SNMP管理的功能，及网络工具。
							<p class="pstyle" text-indet：2em>通过【网卡配置】功能，可配置MailData投递系统设备管理IP地址。如图1-28所示，默认选择网卡是Console，配置完IP地址、子网掩码信息后，点击“保存”按钮，MailData邮件投递系统将重启网络服务，重启完成后，系统通过Console口使用新的IP地址提供管理功能。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-28.png" style=" ">	
							</br>图1-28</center>	
							<p class="pstyle" text-indet：2em>选择网卡是LAN时，如图1-29所示，配置完IP地址、子网掩码、默认网关、DNS信息、IP地址池信息后,点击“提交”按钮，MailData邮件投递系统将重启网络服务，重启完成后，系统将使用新配置的IP地址提供管理功能，而用户也只有使用新的IP地址才能登录管理界面及其它相应的界面。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-29.png" style=" ">	
							</br>图1-29</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ IP地址池：只能添加同一网段的IP地址。最多绑定250个有效的IP地址。<br>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer55">
							<a name='55'></a>	
							<h3>SNMP管理</h3>
							<p class="pstyle" text-indet：2em>【SNMP管理】功能提供管理员采用集中式监控、管理邮件投递设备的功能，而且MailData邮件投递系统全面支持SNMPv2、v3版本的简单网络管理协议，如图1-30所示，选择“启用”SNMP服务，则设置相应的SNMP版本及其对应的功能选项后，点击“提交”即生效。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-30.png" style=" ">	
							</br>图1-30</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 当使用SNMP V3版本时，请自定义设置用户和密码，密码长度需要超过8位。<br>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer13">
							<a name='13'></a>	
							<h3>网络工具</h3>
							<p class="pstyle" text-indet：2em>通过【网络工具】功能，管理员可进行网络之间连通性测试。目前MailData邮件投递系统支持ping测试、Tranceroute测试、Telnet测试三种网络测试方式，如图1-31所示：
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-31.png" style=" ">	
							</br>图1-31</center>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer19">
							<a name='19'></a>	
							<h3>投递参数管理</h3>
							<p class="pstyle" text-indet：2em>管理员通过【安全设置】模块的配置以确保MailData邮件投递设备的全方位安全性设置。同时，通过投递安全策略，可以提高邮件的投递效率、投递成功率，实现邮件投递的可控性。
							</p>	
							<p class="pstyle" text-indet：2em>【投递参数管理】功能提供MailData邮件投递系统对特殊域名投递邮件策略设置的功能。</p>
							<p class="pstyle" text-indet：2em>【检索条件】如图1-32所示界面上，管理员用户根据需求自定义设置完域名、创建人单条件或复合条件后，然后点击“搜索”按钮，即可检索出符合条件的信息。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-32.png" style=" ">	
							</br>图1-32</center>	
							<p class="pstyle" text-indet：2em>【投递参数设置】如图1-32所示界面上，点击“投递参数设置”按钮，则会出现如下图1-33所示的界面，管理员设置完特殊域名系统投递时默认使用的Hello域名、连接超时时间、临时性错误最多尝试次数、单个IP最大并发数、单封邮件大小、单封邮件附件大小、不可使用的IP地址、投递频率（单个IP）等投递参数，点击“保存”按钮后，即可完成特殊域名投递参数的设置。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-33.jpg" style=" ">	
							</br>图1-33</center>
							<p class="pstyle" text-indet：2em>【批量删除】如图1-32所示界面上，选中投递设置列表中序号前的复选框，然后点击“批量删除”按钮，即可删除所选的信息。</p>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer40">
							<a name='40'></a>	
							<h3>安全参数管理</h3>
							<p class="pstyle" text-indet：2em>【安全参数管理】功能提供配置MailData邮件投递系统邮件投递安全参数的功能。如图1-34所示，管理员设置完每分钟发送邮件数量、是否允许发送附件、邮件整体大小、单个IP地址每秒最大投递数、单个IP并发连接数等条件信息，点击“保存”按钮后，邮件投递安全参数设置完成即可生效。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-34.png" style=" ">	
							</br>图1-34</center>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer9">
							<a name='9'></a>	
							<h3>转发管理配置</h3>
							<p class="pstyle" text-indet：2em>【转发设置】模块是MailData邮件投递系统的一种核心邮件投递方式。管理员通过【转发设置】模块的配置实现邮件服务器通过MailData邮件投递设备进行邮件投递转发的工作。
							</p>	
							<p class="pstyle" text-indet：2em>【转发管理配置】功能提供MailData邮件投递系统转发功能的配置。如图1-35所示，管理点击“启用”SMTP服务后，设置完SMTP的接受IP、允许转发范围后，点击“保存”按钮，转发配置即会生效。SMTP服务系统默认是禁用。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-35.png" style=" ">	
							</br>图1-35</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ SMTP接收IP默认一般填写投递系统的IP地址；<br>	
							☆ SMTP转发范围指允许通过投递设备转发的IP地址。<br>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer10">
							<a name='10'></a>	
							<h3>转发统计分析</h3>
							<p class="pstyle" text-indet：2em>【转发统计分析】模块详细统计邮件服务器通过MailData邮件投递系统转发投递的邮件统计信息。
							</p>	
							<p class="pstyle" text-indet：2em>【检索条件】如图1-36所示界面上，管理员用户根据需求自定义设置完邮件来源、邮件大小范围、邮件标题、收件人、投递状态、投递时间等单条件或复合条件后，然后点击“查询”按钮，即可检索出符合条件的转发邮件信息。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-36.png" style=" ">	
							</br>图1-36</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ “检索条件”默认是隐藏的。需要时，点击“检索条件”即可展开。<br>	
							<p class="pstyle" text-indet：2em>【转发统计分析】列表中，系统管理员通过点击某一转发邮件，可查看转发邮件当前时刻的邮件详细情况。显示如图1-37所示转发邮件的详细情况。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-37.png" style=" ">	
							</br>图1-37</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 入队列时间：即此转发邮件进入转发缓冲区队列时间；<br>	
							☆ 队列中邮箱是指还未转发的邮箱，在转发缓冲队列中的邮箱；<br>	
							☆ 点击“成功邮箱”的地址，可查看转发的详细情况。<br>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer57">
							<a name='57'></a>	
							<h3>联系人库</h3>
							<p class="pstyle" text-indet：2em>【联系人管理】模块提供MailData邮件投递系统联系人库的管理功能。管理员通过【联系人管理】模块可导入、导出联系人，实现联系人分组，联系人筛选条件保存等功能，以确保管理员对系统联系人有效、便捷的管理。
							</p>	
							<p class="pstyle" text-indet：2em>【联系人库】功能提供管理员对MailData邮件投递系统联系人库的查询、导入、导出、新增、批量添加、批量删除等功能。</p>
							<p class="pstyle" text-indet：2em>【检索条件】如图1-38所示，管理员可根据需求自定义选择联系人组、筛选条件中姓名、手机、邮箱等进行单条件或综合条件查询。设置好检索条件后，点击“搜索”按钮，系统即会检索出满足条件的系统联系人。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-38.png" style=" ">	
							</br>图1-38</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ “检索条件”默认是隐藏的。需要时，点击“检索条件”即可展开。<br>	
							<p class="pstyle" text-indet：2em>【保存当前检索条件】：在图1-38所示的界面上，通过点击“保存检索条件”按钮，用户可将较常使用的检索条件保存到MailData邮件投递系统的筛选器，以便日后需要进行相同查询时，可把检索条件直接恢复到联系人库的检索条件界面进行查询操作。如图1-39所示。为保存的检索条件输入名称、描述，点击“保存”按钮后，生成的筛选器将保存在筛选器列表中。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-39.png" style=" ">	
							</br>图1-39</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 如果筛选条件为空时，点击“保存当前检索条件”按钮进行保存的时候，系统会提示用户无法完成保存，必须填写筛选条件，才能完成筛选条件的保存。<br>	
							<p class="pstyle" text-indet：2em>【重置】：在图1-38所示的界面上，通过点击“重置”按钮，可以将设置的所有检索条件进行清空，恢复到检索条件的初始。</p>
							<p class="pstyle" text-indet：2em>【下载导入格式】：支持下载导入格式。在图1-38所示的界面上，点击“下载导入格式”按钮，在打开的下载页面，选择下载文件存放的地址，下载即可。</p>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 下载格式是Excel表格；<br>	
							☆ 支持office2007、office2010及更高的WPS办公软件。<br>
							<p class="pstyle" text-indet：2em>【联系人检索】：提供快速、方便导入大量联系人数据到联系人库的功能。在图1-38所示的界面上，点击“联系人检索”按钮，打开如图1-40所示窗口，选择导入的联系人组，点击“浏览”按钮选择导入的文件，然后点击“导入”按钮，即可实现将文件中信息导入指定联系人组。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-40.png" style=" ">	
							</br>图1-40</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 导入的文件必须是正确的格式，比如：Excel；。<br>	
							☆ 当导入的邮箱地址，在联系人组中已存在时，不重复导入；<br>	
							☆ 导入文件中，不正确的邮箱地址自动过滤，不会导入到联系人库。<br>	
							<p class="pstyle" text-indet：2em>【导出联系人】：提供用户快速、方便导出大量联系人数据的功能。在图1-38所示的界面上，点击“导出联系人”按钮，打开如图1-41所示窗口，选择需要导出的联系人组，点击“导出”按钮，即可实现导出指定联系人组的联系人。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-41.png" style=" ">	
							</br>图1-41</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 导出的文件默认是Excel格式。<br>	
							<p class="pstyle" text-indet：2em>【新增联系人】：在图1-38所示的界面上，点击“新增联系人”按钮，则会出现如图1-42所示窗口，用户设置完邮箱、姓名、出生日期、手机、性别等信息后，选择已有或新建联系人组，点击“保存”按钮后，即可添加联系人成功。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-42.png" style=" ">	
							</br>图1-42</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 图1-42中，“<span style="color: red;">*</span>”为必填项；<br>	
							☆ 所有用户，不可添加联系人库中已有的邮箱联系人；<br>
							☆ 组信息可新建组，或选择显示的联系人分组，但新建组不能与已有组名重复；<br>
							☆ 图1-42中，扩展信息为【自定义属性】中添加的属性信息。<br>
							<p class="pstyle" text-indet：2em>【联系人列表】：如图1-38所示，联系人列表分页显示MailData邮件投递系统中全部联系人。点击“全选”或“本页全选”提供联系人列表的全选、及本页全选功能。点击操作列中的“编辑”按钮，提供联系人的信息修改功能。点击“删除”按钮，提供联系人删除功能。。</p>
							<p class="pstyle" text-indet：2em>【批量删除】：如图1-38所示界面上，选中联系人列表中邮箱前的复选框，然后点击“批量删除”按钮，即可删除所选联系人。</p>
							<p class="pstyle" text-indet：2em>【批量添加】：如图1-38所示界面上，选中联系人列表中邮箱前的复选框，然后点击“批量添加”按钮，即可将选联系人添加到某已有组或新建组。</p>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer56">
							<a name='56'></a>	
							<h3>自定义属性</h3>
							<p class="pstyle" text-indet：2em>【自定义属性】功能提供新增联系人添加自定义属性，以使联系人信息更加详细、完整。如图1-43所示，管理员通过列表操作的“编辑”、“删除”，实现对自定义属性信息的修改、删除功能。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-43.png" style=" ">	
							</br>图1-43</center>	
							<p class="pstyle" text-indet：2em>【新增属性】在图1-43所示的界面中，点击“新增属性”按钮，则会弹出如图1-44所示的窗口，用户设置完自定义属性的字段名称、字段英文名、字段类型信息后，点击“保存”按钮，新增自定义属性成功。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-44.png" style=" ">	
							</br>图1-44</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ “<span style="color: red;">*</span>”为必填项；<br>	
							☆ 字段名称、字段英文名，确保在20个字符以内。	<br>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer17">
							<a name='17'></a>	
							<h3>联系人分组</h3>
							<p class="pstyle" text-indet：2em>【联系人分组】功能提供MailData邮件投递系统联系人组新建、合并等功能。如图1-45所示，管理员通过列表操作的“编辑”、“删除”、“合并”，实现对联系人组信息的修改、删除、及分组之间的合并。
							</p>	
							<p class="pstyle" text-indet：2em>【检索条件】如图1-45所示，管理员可根据需求自定义联系人组名称、描述、创建人、创建时间等进行单条件或综合条件查询。设置好检索条件后，点击“搜索”按钮，系统即会检索出满足条件的系统联系人组。
							</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-45.png" style=" ">	
							</br>图1-45</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ “检索条件”默认是隐藏的。需要时，点击“检索条件”即可展开；<br>	
							☆ 删除联系人组时，只删除联系人组，不删除组内联系人。	<br>
							<p class="pstyle" text-indet：2em>【新增联系人组】：在图1-45中，点击“新增联系人组”按钮，则会弹出如图1-46所示窗口，用户设置联系人组名称、描述的信息后，点击“保存”按钮即可新建联系人组完成。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-46.png" style=" ">	
							</br>图1-46</center>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 组名称不可与已有的组名称重复，组名限制10个字（包括字母、数字、汉字）。<br>	
							<br>
							<p class="pstyle" text-indet：2em>【批量删除】：如图1-45所示界面上，选中联系人组列表中组名称前的复选框，然后点击“批量删除”按钮，即可删除所选联系人组。
							</p>
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 批量删除联系人组时，只删除联系人组，不删除组内联系人。<br>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer16">
							<a name='16'></a>	
							<h3>联系人筛选</h3>
							<p class="pstyle" text-indet：2em>【联系人筛选】功能提供保存联系人筛选器、筛选器条件的重复使用、及创建投递任务时对投递联系人条件的筛选过滤等功能。如图1-47所示，用户点击筛选器名称链接联系人库的检索条件，点击“搜索”按钮，实现条件的重复使用，以方便用户便捷、快速检索联系人库。任务发布员根据需要通过筛选器对投递任务的联系人进行筛选过滤，确保邮件投递的有效性和准确性。用户还可以通过列表操作的“编辑”、“删除”，实现筛选器信息的修改、删除功能。
							</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-47.png" style=" ">	
							</br>图1-47</center>	
							<p class="pstyle" text-indet：2em>【新增筛选器】：在图1-47中，点击“新增筛选器”按钮，则会出现如图1-48所示界面，用户设置筛选器名称、描述，自定义设置姓名、手机、邮箱等筛选条件后，点击“保存”按钮，新的筛选器即可保存完成。
							</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-48.png" style=" ">	
							</br>图1-48</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ “<span style="color: red;">*</span>”为必填项；<br>	
							☆ 筛选器条件为空时，不可保存筛选器。	<br>
							<p class="pstyle" text-indet：2em>【批量删除】：如图1-47所示界面上，选中筛选器列表中名称前的复选框，然后点击“批量删除”按钮，即可删除所选筛选器。
							</p>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer18">
							<a name='18'></a>	
							<h3>预设模板</h3>
							<p class="pstyle" text-indet：2em>【邮件内容管理】模块系统内置49个预设模板，以供用户下载使用。另外，提供MailData邮件投递系统邮件内容的创建、编辑，及附件、图片的上传等模板相关功能。</p>	
							<p class="pstyle" text-indet：2em>【预设模板】模块中系统提供49个预设模板，供用户下载、编辑等使用。如图1-49所示，用户可编辑、预览、下载列表中显示的预设模板。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-49.png" style=" ">	
							</br>图1-49</center>	
							<p class="pstyle" text-indet：2em>【新增预设】：在图1-49中，点击“新增预设”按钮，打开图1-50所示页面，填写模板名称，用户可通过读取URL地址、导入模板文件获取模板文件，或在文本编辑框中编辑获取的模板文件，或自定义编辑新的模板文件，点击“保存”按钮，即可保存预设模板。点击“模板预览”按钮，可查看文本编辑框中模板样式。
							</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-50.png" style=" ">	
							</br>图1-50</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ “<span style="color: red;">*</span>”为必填项；<br>	
							☆ 模板名称限制9个字以内；<br>
							☆ 所读取URL地址，URL页面中除带有JavaScript、Flash、内嵌窗口等大部分URL模板；<br>
							☆ 导入模板文件，上传的模板文件仅限于HTML格式。<br>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer20">
							<a name='20'></a>	
							<h3>附件管理</h3>
							<p class="pstyle" text-indet：2em>【附件管理】功能提供MailData邮件投递系统中附件的上传、下载等功能。如图1-51所示，用户通过“上传附件”按钮，实现附件的上传功能。通过列表操作的“删除”、“下载”实现附件的删除、下载功能。</p>	
							<p class="pstyle" text-indet：2em>【检索条件】如图1-51所示，管理员可根据需求自定义附件名称、描述等进行单条件或综合条件查询。设置好检索条件后，点击“搜索”按钮，系统即会检索出满足条件的附件。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-51.png" style=" ">	
							</br>图1-51</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ “检索条件”默认是隐藏的。需要时，点击“检索条件”即可展开。<br>	
							<p class="pstyle" text-indet：2em>【批量删除】：如图1-51所示界面上，选中附件列表中附件名称前的复选框，然后点击“批量删除”按钮，即可删除所选附件。</p>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer21">
							<a name='21'></a>	
							<h3>图片管理</h3>
							<p class="pstyle" text-indet：2em>【图片管理】功能提供MailData邮件投递系统中图片的下载、删除等功能。如图1-52所示，用户通过列表操作的 “删除”、“下载”，实现图片的删除、下载功能。</p>	
							<p class="pstyle" text-indet：2em>【检索条件】如图1-52所示，管理员可根据需求自定义图片名称查询。设置好检索条件后，点击“搜索”按钮，系统即会检索出满足条件的图片。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-52.png" style=" ">	
							</br>图1-52</center>	
							<p class="pstyle" text-indet：2em>【批量删除】：如图1-52所示界面上，选中图片列表中图片名称前的复选框，然后点击“批量删除”按钮，即可删除所选图片。</p>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer22">
							<a name='22'></a>	
							<h3>任务列表</h3>
							<p class="pstyle" text-indet：2em>【任务列表】功能提供MailData邮件投递系统创建投递任务执行情况的显示。如图1-53所示，用户同列表操作的“查看”、“报告”或状态列，实现投递任务新的查看、投递任务的执行情况。</p>	
							<p class="pstyle" text-indet：2em>【检索条件】如图1-53所示，管理员可根据需求自定义任务名称、邮件主题、创建人、任务分类、创建时间、任务执行状态等进行单条件或综合条件查询。设置好检索条件后，点击“搜索”按钮，系统即会检索出满足条件的任务。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-53.png" style=" ">	
							</br>图1-53</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ 点击某一任务名称，可查看任务邮件的日志；<br>	
							☆ “检索条件”默认是隐藏的。需要时，点击“检索条件”即可展开。<br>
							<p class="pstyle" text-indet：2em>【批量删除】：如图1-53所示界面上，选中任务列表中任务名称前的复选框，然后点击“批量删除”按钮，即可删除所选投递任务。</p>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer41">
							<a name='41'></a>	
							<h3>任务分类</h3>
							<p class="pstyle" text-indet：2em>【任务分类】功能提供MailData邮件投递系统任务分类编辑、新建、及分类数量统计等功能。如图1-54所示，用户通过列表操作中 “编辑”、“删除”，实现任务分类信息的修改、删除功能。用户通过任务分类列表中任务数量列，查看分类中草稿、任务的数量统计。</p>	
							<p class="pstyle" text-indet：2em>【检索条件】如图1-54所示，管理员可根据需求自定义任务分类名称、描述等进行单条件或综合条件查询。设置好检索条件后，点击“搜索”按钮，系统即会检索出满足条件的任务分类。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-54.png" style=" ">	
							</br>图1-54</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆ “检索条件”默认是隐藏的。需要时，点击“检索条件”即可展开。<br>	
							<p class="pstyle" text-indet：2em>【新增分类】：在图1-54中，点击“新增分类”按钮，页面弹出图1-55所示窗口，用户设置任务分类名称、描述等信息后，点击“创建”按钮，即可新增任务分类完成。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-55.png" style=" ">	
							</br>图1-55</center>
							<p class="pstyle" text-indet：2em>【批量删除】：如图1-54所示界面上，选中任务分类列表中分类名称前的复选框，然后点击“批量删除”按钮，即可删除所选任务分类。</p>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer42">
							<a name='42'></a>	
							<h3>按单次任务统计</h3>
							<p class="pstyle" text-indet：2em>【统计分析】模块从全局角度，对MailData邮件投递系统全部邮件投递的详细统计分析。系统管理员通过【统计分析】模块按单次任务、任务分类、发布员、全部任务、全部转发等不同方式统计邮件投递情况。</p>	
							<p class="pstyle" text-indet：2em>【按单次任务统计】功能详细统计了MailData邮件投递系统某一次任务的投递完成情况。默认显示最新一次发送完成的任务情况。如图1-56所示，用户自定义设置任务名称，点击“匹配”可匹配类似任务名称的任务，选择任务的统计方式、时间范围、单次任务执行时间，设置好检索条件后，点击“搜索”按钮，系统即会统计检索的任务。通过综合概览、任务状态统计、接收区域分布统计、收信域统计、客户端统计五个模块详细统计投递任务的完成情况。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-56.png" style=" ">	
							</br>图1-56</center>	
							<p class="pstyle" text-indet：2em>【综合概览】：如图1-57所示，通过图、表的形式统计邮件发送情况、邮件反馈情况。邮件发送情况统计任务发送总量、成功到达数量/比例、硬退回数量/比例、软退回数量/比例情况。邮件反馈情况统计任务发送的点击数量、打开数量、达到数量、发送数量。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-57.png" style=" ">	
							</br>图1-57</center>	
							<p class="pstyle" text-indet：2em>【任务状态统计】：如图1-58所示，通过表格的形式统计任务中邮件发送状态。包括邮件接收人、执行时间、投递状态。点击“日志”可以查看邮件的日志情况。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-58.png" style=" ">	
							</br>图1-58</center>
							<p class="pstyle" text-indet：2em>【接受区域分布统计】：如图1-59所示，通过地图、表格的形式统计某一任务的邮件接收情况。接收区域分布统计表中，通过地区、数量、及百分比的情况详细统计。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-59.png" style=" ">	
							</br>图1-59</center>
							<p class="pstyle" text-indet：2em>【接受区域分布统计】：如图1-60所示，通过图、表的形式统计任务收信域的情况。收信域TOP10比例图显示任务邮件中TOP10收信域及所占百分比。收信域统计表中详细统计任务邮件的收信域、数量、占总数比例、软退、硬退数量、到达率、打开量、点击量情况。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-60.png" style=" ">	
							</br>图1-60</center>
							<p class="pstyle" text-indet：2em>【客户端统计】：如图1-61所示，通过图、表的形式按百分比统计任务邮件接收客户端的操作系统、客户端接入商、客户端浏览器的情况。</p>
							<center><img class="myimage" src="/dist/img/adminhelp/1-61.png" style=" ">	
							</br>图1-61</center>
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer23">
							<a name='23'></a>	
							<h3>按任务分类统计</h3>
							<p class="pstyle" text-indet：2em>【按任务分类统计】功能详细统计了MailData邮件投递系统某一次任务分类的任务投递情况。默认显示最新一次发送完成的任务分类的统计情况。如图1-62所示，用户自定义设置任务分类名称、时间范围等信息，点击“搜索”按钮，系统即会统计符合搜索条件的任务分类。通过综合概览、任务状态统计、接收区域分布统计、收信域统计、客户端统计五个模块详细统计任务分类的详细情况。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-62.png" style=" ">	
							</br>图1-62</center>	
							<img class="myimage" src="/dist/img/adminhelp/img_icon.png">注意：</br>
							☆图1-62中，综合概览、任务状态统计、接收区域分布统计、收信域统计、客户端统计的图、表统计情况，参考“按单次任务统计”的统计情况描述。<br>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer24">
							<a name='24'></a>	
							<h3>按发布人员统计</h3>
							<p class="pstyle" text-indet：2em>【按发布人员统计】功能统计某一任务发布员某日、某周、某月、某年的任务发送情况，如图1-63所示，用户自定义选择发布任务人员、选择时间范围通过表格形式统计某日、某周、某月、某年的任务发送情况。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-63.png" style=" ">	
							</br>图1-63</center>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer25">
							<a name='25'></a>	
							<h3>按全部任务统计</h3>
							<p class="pstyle" text-indet：2em>【按全部任务统计】功能按日、按周、按月、按年的统计全部投递任务的发送情况，如图1-64所示，用户自定义选择时间范围通过表格形式统计某日、某周、某月、某年全部任务的发送情况。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-64.png" style=" ">	
							</br>图1-64</center>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer26">
							<a name='26'></a>	
							<h3>按全部转发统计</h3>
							<p class="pstyle" text-indet：2em>【按全部转发统计】功能按日、按周、按月、按年的统计全部转发邮件的发送情况，如图1-65所示，用户自定义选择时间范围通过表格形式统计某日、某周、某月、某年全部转发邮件的发送情况。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-65.png" style=" ">	
							</br>图1-65</center>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
						<div id="answer27">
							<a name='27'></a>	
							<h3>帐号管理</h3>
							<p class="pstyle" text-indet：2em>【账号管理】功能提供管理员账号的管理功能，其中系统管理员可以创建和删除普通管理员、任务发布员。同时给不同的普通管理员赋予不同的功能管理操作权限，及提供详细的管理日志信息。</p>	
							<p class="pstyle" text-indet：2em>如图1-66所示的所有账号状态，可以通过该状态查看帐号类型、使用状态、修改时间、本次访问时间及详细操作日志等。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-66.png" style=" ">	
							</br>图1-66</center>	
							<p class="pstyle" text-indet：2em>【查看操作日志】：在图1-66所示的图形界面上，通过点击帐号日志中的“放大镜”图标，则可对该帐号的操作日志进行详细的查看、查询操作，如图1-67所示。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-67.png" style=" ">	
							</br>图1-67</center>
							<p class="pstyle" text-indet：2em>【修改管理员账号信息】：在图1-66所示的界面上点击某账号的“铅笔”图标，可修改该账号密码、拥有的系统功能操作管理权限、以及信任IP地址等信息，如图1-68所示。设置信任IP地址，则该账号只允许所设置信任IP地址的用户登录。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-68.png" style=" ">	
							</br>图1-68</center>
							<p class="pstyle" text-indet：2em>【创建管理员账号】：如需要创建新的账号，则可在图1-66所示的界面上，通过点击“新增帐号”按钮完成。点击后，如创建系统管理员可通过勾选方式，为新管理员赋予自定义的功能管理模块权限，如图1-69所示。</p>	
							<center><img class="myimage" src="/dist/img/adminhelp/1-69.png" style=" ">	
							</br>图1-69</center>
							<p class="pstyle" text-indet：2em>【删除管理员账号】：在图1-66所示的界面上，通过账号后的“差号”图标删除的账号信息。</p>	
							<br>
							<div class="back"><span class="return_home"><a href="/createtem/firstpage">返回首页</a></span><span class="main"><a href="#Top">返回顶部</a></span></div>
						</div>
				</div>	
		</div>
	</div>		
</div>
</body>
</html>
