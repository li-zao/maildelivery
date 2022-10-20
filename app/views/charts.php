{%include file="header.php"%}
<link rel='stylesheet' type='text/css' href='/dist/css/fancybox.css' />
<script type='text/javascript' src='/dist/js/jquery.fancybox-1.3.1.pack.js'></script>
<script type="text/javascript" src="/dist/js/highcharts305.js"></script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
      <!-- <a href="" class="current">邮件投递系统</a> -->
    </div>
    <!-- <h1>快捷导航</h1> -->
  </div>
  <div class="container-fluid">
  
  
  
  <div  class="modal hide fade pmail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 600px;">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">信息详情</h3>
      </div>
      <div class="modal-body pmailonce" >
      <p>
        <table style="width: 580px;">
          <tr>
            <td><center><strong><p id="ptitle"></p><strong></center></td>
          </tr>
		   <tr>
            <td><p></p></td>
          </tr>
          <tr>
            <td><p id="pcontent"></p></td>
          </tr>
        </table> 
      </p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
      <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
  </div>

    <div class="quick-actions_homepage span=12" style="width: 100%;margin: 0 auto; margin-left: 0px;">
		{%include file="navigation.php"%}
    </div>
  {%if $role != "sadmin" && $role != "admin"%}
    <div class="row-fluid" width="100%">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-tasks"></i> </span>
            <h5>快速导航栏</h5>
          </div>
          <div class="widget-content" style="height: 160px;">
                <span style="width: 130px;height:152px;float: left;display: block;margin-left: 40px;margin-right: 10px;">
                  <a href="/contact/personlist" style="width: 130px;height:150px;float: left;display: block;">
                    <img  alt="创建联系人" style="height:160px;" src="/dist/img/1.jpg">
                  </a>
               </span>
                <div style="width: 69px; height: 61px; float: left; margin: 40px 12px 1px 40px;background: url('/dist/img/arrow.png') no-repeat">
                </div>

                <span style="width: 130px;height:152px;float: left;display: block;margin-left: 20px;margin-right: 10px;"><a href="/templet/createtempl" style="width: 130px;height:150px;float: left;display: block;"><img  alt="内容附件" style="height:160px;" src="/dist/img/2.jpg"></a></span>
                <div style="width: 69px; height: 61px; float: left; margin: 40px 12px 1px 40px;background: url('/dist/img/arrow.png') no-repeat "></div>

                <span style="width: 130px;height:152px;float: left;display: block;margin-left: 20px;margin-right: 10px;"><a href="/task/addtask" style="width: 130px;height:150px;float: left;display: block;"><img  alt="任务发布" style="height:160px;" src="/dist/img/3.jpg"></a></span>
                <div style="width: 69px; height: 61px; float: left; margin: 40px 12px 1px 40px;background: url('/dist/img/arrow.png') no-repeat "></div>
                <span style="width: 130px;height:152px;float: left;display: block;margin-left: 20px;margin-right: 10px;"><a href="/statistics/singletask" style="width: 130px;height:150px;float: left;display: block;"><img  alt="统计分析" style="height:160px;" src="/dist/img/4.jpg"></a></span>
          </div>
        </div>
      </div>
    </div>
	{%/if%}
    <div class="row-fluid">
      <div class="span5" style="">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
            <h5>登录账户信息</h5>
          </div>
          <div class="widget-content" style="padding: 0;">
            <div>
                  <ul class="recent-posts">
                    <li style="margin-top: 7px;">
                      <div class="user-thumb" style="margin-top: -7px;"> <img width="40" height="40" src="/dist/img/av1.jpg" alt="User"> </div>
                      <div class="article-post">
                        <p style="margin-top: 5px;"> 账户名称： <span style="color: #FE201D;"><strong>{%$uname%}</strong></span></p>
                      </div>
                    </li>
                    <li style="margin-top: 7px;">
                      <div class="user-thumb" style="margin-top: -7px;"> <img width="40" height="40" src="/dist/img/av2.jpg" alt="User"> </div>
                      <div class="article-post">
                        <!--<p style="margin-top: 5px;"> 上次登录时间:2 Auf 2014 / Time:14:39 PM IP: <span style="color: #FE201D;">127.0.0.1</span></p>-->
                        <p style="margin-top: 5px;"> 上次登录时间：{%$logintime%} &nbsp; IP： <span style="color: #FE201D;">{%$ip%}</span></p>
                      </div>
                    </li>
                    <li style="margin-top: 7px;">
                      <div class="user-thumb" style="margin-top: -7px;"> <img width="40" height="40" src="/dist/img/av3.jpg" alt="User"> </div>
                      <div class="article-post">
                        <p style="margin-top: 5px;"> 累加发送量：<span style="color: #FE201D;">{%$tnum%}</span> 封</p>
                      </div>
                    </li>
                    <li style="margin-top: 7px;margin-bottom: 3px;border-bottom:0;">
                      <div class="user-thumb"  style="margin-top: -7px;"> <img width="40" height="40" src="/dist/img/av4.jpg" alt="User"> </div>
                      <div class="article-post">
                        <p style="margin-top: 5px;"> 日均发送量：<span style="color:#FE201D;">{%$avg%}</span> 封</p>
                      </div>
                    </li>
                  </ul>

            </div>
          </div>
        </div>
      </div>


      <div class="span2">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
            <h5>联系人情况</h5>
          </div>
          <div class="widget-content" style="padding: 0;">
            <div>
                  <ul class="recent-posts">
                    <li style="padding-top: 20px;padding-bottom: 21px;text-align: center;">全部联系人 <span style="color: #FE0200;font-weight:700 ;">{%$person_num%}</span> 个</li>
                    <li style="padding-top: 20px;padding-bottom: 22px;text-align: center;">联系人组 <span style="color: #27B779;font-weight:700 ;">{%$group_num%}</span> 个</li>
                    <li style="padding-top: 20px;padding-bottom: 23px;text-align: center;">任务数量 <span style="color: #23ACE2;font-weight:700 ;">{%$tasknum%}</span> 个</li>
                    <li style="padding-top: 20px;padding-bottom: 25px;text-align: center;border-bottom:0;">模版数量 <span style="color: #FFB649;font-weight:700 ;">{%$tplnum%}</span> 个</li>
                  </ul>
            </div>
          </div>
        </div>
      </div>


      <div class="span5">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-tasks"></i> </span>
            <h5>最新任务执行情况</h5>
          </div>
          <div class="widget-content" style="padding: 0;">
            <div>
              <ul class="unstyled" style="margin-top: 5px; margin-bottom: 8px;">
			  {%if $task1 != null %}
              <li style="width: 100%;min-height: 60px;"> 
                <div>
                  <div style="width:23%;min-height: 50px;float: left;line-height: 50px;padding-left: 10px;text-align: left;"><a title="{%$task1.task_name%}">{%$task1.task_name|truncate:7:"..."%}：</a></div>
                <div class="progress progress-danger progress-striped " style="float: left;width: 73%;min-height: 20px;margin-bottom: 5px;margin-top: 12px;">
                 <div class="bar" style="width: {%$task1.progess%};"></div>
                </div>
                <div style="width: 73%;min-height: 20px;float: left;">
					<span class="icon24 icomoon-icon-arrow-up-2 green">{%$task1.progess%}进度;</span><span class="icon24 icomoon-icon-arrow-up-2 green">{%$task1.completion_rate%}成功率</span><span class="pull-right strong">总量{%$task1.total%} &nbsp;&nbsp;&nbsp; 成功{%$task1.success%}</span>
                </div>
                </div>
              </li>
			 {%else%}
			 <li style="width: 100%;min-height: 60px;"> 
			 <div>
				<div style="width:23%;min-height: 50px;float: left;line-height: 50px;padding-left: 10px;text-align: left;">未创建任务:</div>
				<div class="progress progress-inverse progress-striped " style="float: left;width: 73%;min-height: 20px;margin-bottom: 5px;margin-top: 12px;">
					<div class="bar" style="width: 0%;"></div>
				</div>
				<div style="width: 73%;min-height: 20px;float: left;">
				<span class="icon24 icomoon-icon-arrow-up-2 green">0%进度;</span><span class="icon24 icomoon-icon-arrow-up-2 green">0%成功率</span><span class="pull-right strong">总量0 &nbsp;&nbsp;&nbsp;&nbsp;成功0</span>
				 </div>
			 </div>
			 </li>
			{%/if%}
			{%if $task2 != null %}
            <li style="width: 100%;min-height: 60px;"> 
                <div>
                  <div style="width:23%;min-height: 50px;float: left;line-height: 50px;padding-left: 10px;text-align: left;"><a title="{%$task2.task_name%}">{%$task2.task_name|truncate:7:"..."%}：</a></div>
                <div class="progress progress-success progress-striped " style="float: left;width: 73%;min-height: 20px;margin-bottom: 5px;margin-top: 12px;">
                 <div class="bar" style="width: {%$task2.progess%};"></div>
                </div>
                <div style="width: 73%;min-height: 20px;float: left;">
                <span class="icon24 icomoon-icon-arrow-up-2 green">{%$task2.progess%}进度;</span><span class="icon24 icomoon-icon-arrow-up-2 green">{%$task2.completion_rate%}成功率</span><span class="pull-right strong">总量{%$task2.total%} &nbsp;&nbsp;&nbsp; 成功{%$task2.success%}</span>
                </div>
                </div>
            </li>
			{%else%}
			<li style="width: 100%;min-height: 60px;"> 
			<div>
				<div style="width:23%;min-height: 50px;float: left;line-height: 50px;padding-left: 10px;text-align: left;">未创建任务:</div>
				<div class="progress progress-inverse progress-striped " style="float: left;width: 73%;min-height: 20px;margin-bottom: 5px;margin-top: 12px;">
					<div class="bar" style="width: 0%;"></div>
				</div>
				<div style="width: 73%;min-height: 20px;float: left;">
				<span class="icon24 icomoon-icon-arrow-up-2 green">0%进度;</span><span class="icon24 icomoon-icon-arrow-up-2 green">0%成功率</span><span class="pull-right strong">总量0 &nbsp;&nbsp;&nbsp;&nbsp;成功0</span>
				 </div>
			</div>
			</li>
			{%/if%}
		    {%if $task3 != null %}
			<li style="width: 100%;min-height: 60px;"> 
                <div>
                  <div style="width:23%;min-height: 50px;float: left;line-height: 50px;padding-left: 10px;text-align: left;"><a title="{%$task3.task_name%}">{%$task3.task_name|truncate:7:"..."%}：</a></div>
                <div class="progress progress-warning progress-striped " style="float: left;width: 73%;min-height: 20px;margin-bottom: 5px;margin-top: 12px;">
                 <div class="bar" style="width: {%$task3.progess%};"></div>
                </div>
                <div style="width: 73%;min-height: 20px;float: left;">
                <span class="icon24 icomoon-icon-arrow-up-2 green">{%$task3.progess%}进度;</span><span class="icon24 icomoon-icon-arrow-up-2 green">{%$task3.completion_rate%}成功率</span><span class="pull-right strong">总量{%$task3.total%} &nbsp;&nbsp;&nbsp; 成功{%$task3.success%}</span>
                </div>
                </div>
              </li>
			  {%else%}
			  <li style="width: 100%;min-height: 60px;"> 
				<div>
				   <div style="width:23%;min-height: 50px;float: left;line-height: 50px;padding-left: 10px;text-align: left;">未创建任务:</div>
					<div class="progress progress-inverse progress-striped " style="float: left;width: 73%;min-height: 20px;margin-bottom: 5px;margin-top: 12px;">
					 <div class="bar" style="width: 0%;"></div>
					</div>
					<div style="width: 73%;min-height: 20px;float: left;">
					<span class="icon24 icomoon-icon-arrow-up-2 green">0%进度;</span><span class="icon24 icomoon-icon-arrow-up-2 green">0%成功率</span><span class="pull-right strong">总量0 &nbsp;&nbsp;&nbsp;&nbsp;成功0</span>
				   </div>
				</div>
			  </li>
			  {%/if%}
			  {%if $task4 != null %}
              <li style="width: 100%;min-height: 60px;"> 
                <div>
                  <div style="width:23%;min-height: 50px;float: left;line-height: 50px;padding-left: 10px;text-align: left;"><a title="{%$task4.task_name%}">{%$task4.task_name|truncate:7:"..."%}：</a></div>
                <div class="progress progress-inverse progress-striped " style="float: left;width: 73%;min-height: 20px;margin-bottom: 5px;margin-top: 12px;">
                 <div class="bar" style="width: {%$task4.progess%};"></div>
                </div>
                <div style="width: 73%;min-height: 20px;float: left;">
                <span class="icon24 icomoon-icon-arrow-up-2 green">{%$task4.progess%}进度;</span><span class="icon24 icomoon-icon-arrow-up-2 green">{%$task4.completion_rate%}成功率</span><span class="pull-right strong">总量{%$task4.total%} &nbsp;&nbsp;&nbsp; 成功{%$task4.success%}</span>
                </div>
                </div>
              </li>
			  {%else%}
			  <li style="width: 100%;min-height: 60px;"> 
				<div>
				   <div style="width:23%;min-height: 50px;float: left;line-height: 50px;padding-left: 10px;text-align: left;">未创建任务:</div>
					<div class="progress progress-inverse progress-striped " style="float: left;width: 73%;min-height: 20px;margin-bottom: 5px;margin-top: 12px;">
					 <div class="bar" style="width: 0%;"></div>
					</div>
					<div style="width: 73%;min-height: 20px;float: left;">
					<span class="icon24 icomoon-icon-arrow-up-2 green">0%进度;</span><span class="icon24 icomoon-icon-arrow-up-2 green">0%成功率</span><span class="pull-right strong">总量0 &nbsp;&nbsp;&nbsp;&nbsp;成功0</span>
				   </div>
				</div>
			  </li>
			  {%/if%}
            </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

  {%if $role == "sadmin" || $role == "admin"%}
    <div class="row-fluid" >
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-tasks"></i> </span>
            <h5>邮件总发送情况</h5>
          </div>
          <div class="widget-content">
            <div id="container" style="height: 350px;"></div>
          </div>
        </div>
    </div>
    <script>
    $(function () {
		$.post('/createtem/gettotalmail',function(data){
			if(data){
			  var datas = eval('('+data+')');
			  odata = datas;
			 $('#container').highcharts({
					colors:["#006ccd","#48afcd"],
					chart: {
						type: 'column'
					},
					title: {
						text: ''
					},
					xAxis: {
						categories: [
							datas[1].hour,datas[2].hour,datas[3].hour,datas[4].hour,datas[5].hour,datas[6].hour,datas[7].hour,datas[8].hour,
							datas[9].hour,datas[10].hour,datas[11].hour,datas[12].hour,datas[13].hour,datas[14].hour,datas[15].hour,datas[16].hour,
							datas[17].hour,datas[18].hour,datas[19].hour,datas[20].hour,datas[21].hour,datas[22].hour,datas[23].hour,datas[24].hour
						]
					},
					yAxis: {
						title: {
							text: ''
						},
						min: 0
					},
					credits : { 
						enabled : false
					},
					tooltip: {
						enabled: true,
						formatter: function() {
							return '<b>'+ this.series.name +'</b><br/>'+this.x +':00--'+(Number(this.x)+1)+':00 <br />'+ this.y;
						}
					},
					plotOptions: {
						column: {
						groupPadding: 0.1,
						pointWidth: 10,
						borderWidth: 1,
						showInLegend: true,
						colorByPoint: false,
					   },
					},
					series: [
					
					{
						name: '转发邮件',
						data: [
							datas[1].smtp,datas[2].smtp,datas[3].smtp,datas[4].smtp,datas[5].smtp,datas[6].smtp,datas[7].smtp,datas[8].smtp,
							datas[9].smtp,datas[10].smtp,datas[11].smtp,datas[12].smtp,datas[13].smtp,datas[14].smtp,datas[15].smtp,datas[16].smtp,
							datas[17].smtp,datas[18].smtp,datas[19].smtp,datas[20].smtp,datas[21].smtp,datas[22].smtp,datas[23].smtp,datas[24].smtp
						]
					}, {
						name: '任务邮件',
						data: [
							datas[1].task,datas[2].task,datas[3].task,datas[4].task,datas[5].task,datas[6].task,datas[7].task,datas[8].task,
							datas[9].task,datas[10].task,datas[11].task,datas[12].task,datas[13].task,datas[14].task,datas[15].task,datas[16].task,
							datas[17].task,datas[18].task,datas[19].task,datas[20].task,datas[21].task,datas[22].task,datas[23].task,datas[24].task
						]
					}]
				});
			} 
		});
    });
</script>
    {%/if%}
  </div>
</div>
<script type="text/javascript">
function mailsinfo(id){
        var mailsid = "mail" + id;
        $('.pmail').attr('id',mailsid);
        $('.pmailonce').find('p').css({'line-height':'20px',"margin":'0','word-break':'break-all'});
       $.get('/setting/showinfo',{'infoid':id},function(data){
            if(data){
				var strs = eval('('+data+')');
				$('#ptitle').text(strs.title);
				$('#pcontent').html(strs.content);
            } 
        });
  }
</script> 
 
{%include file="footer.php"%}
