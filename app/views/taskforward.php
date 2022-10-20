{%include file="header.php"%}
  <script type="text/javascript" src="/dist/js/highcharts305.js"></script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="/createtem/firstpage" title="首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
      <a href="" class="">系统监控</a><a href="" class="current">任务缓冲区监控</a>
    </div>
  </div>


  
  <div class="container-fluid">
		<div class="quick-actions_homepage span=12" style="margin: 0 auto;">
			<div width="100%" style="margin-top: 20px; border-bottom:1px dotted #ccc; height: 20px;"><span id="quicknav" href="#collapseG3" data-toggle="collapse" class="widget-title bg_lo collapsed" style="font-weight:bold;color:#444444;float:right;font-size:12px; height: 18px;border:0;">快捷导航栏<i class="icon-chevron-down"></i></span></div>
		         <div id="collapseG3" class="widget-content nopadding updates collapse" style="border:0;">
					{%if $role != "sadmin" && $role != "admin"%}
					<ul class="quick-actions" style="width: 100%;">
						<li class="bg_lo" style="width: 17.4%;"> <a href="/contact/personlist"> <i class="icon-th-list"></i>联系人检索</a> </li>
						<li class="bg_lg" style="width: 17.4%;"> <a href="/task/taskbuffer"> <i class="icon-signal"></i> 任务缓冲区监控</a> </li>
						<li class="bg_ly" style="width: 17.4%;"> <a href="/task/listtask"> <i class="icon-inbox"></i>任务列表 </a> </li>
						<li style="width: 26.5%;" class="bg_lo"> <a href="/task/create"> <i class="icon-th"></i>创建向导任务</a> </li>
						<li style="margin-right: 0px;width: 17.4%;" class="bg_ls span2"> <a href="/templet/createtempl"> <i class="icon-fullscreen"></i>模板布局</a> </li>
					</ul>
					<ul class="quick-actions" style="width: 100%;">
						<li class="bg_lb" style="width: 17.4%;"> <a href="/contact/personlist"> <i class="icon-dashboard"></i> 联系人分组 </a> </li>
						<li style="width: 26.5%;" class="bg_ls"> <a href="/task/addtask"> <i class="icon-tint"></i>快速创建邮件</a></li>
						<li class="bg_lb" style="width: 17.4%;"> <a href="/statistics/singletask"> <i class="icon-pencil"></i>统计报告</a> </li>
						<li class="bg_lg" style="width: 17.4%;"> <a href="/task/drafttask"> <i class="icon-calendar"></i> 草稿</a> </li>
						<li style="margin-right: 0px;width: 17.4%;" class="bg_lr span2"> <a href="/contact/subscribe"> <i class="icon-info-sign"></i> 订阅管理</a> </li>
					</ul>
					{%else%}
					<ul class="quick-actions" style="width: 100%;">
						{%if $access->getPersonListAccess() %}
						<li class="bg_lo" style="width: 17.4%;"> <a href="/contact/personlist"> <i class="icon-th-list"></i>联系人检索</a> </li>
						{%else%}
						<li class="bg_lo" style="width: 17.4%;"> <a href="#"> <i class="icon-th-list"></i>未定</a> </li>
						{%/if%}
						{%if $access->getTaskBufferAccess() %}
						<li class="bg_lg" style="width: 17.4%;"> <a href="/task/taskbuffer"> <i class="icon-signal"></i> 任务缓冲区监控</a> </li>
						{%else%}
						<li class="bg_lg" style="width: 17.4%;"> <a href="#"> <i class="icon-signal"></i> 未定</a> </li>
						{%/if%}
						{%if $access->getListTaskAccess() %}
						<li class="bg_ly" style="width: 17.4%;"> <a href="/task/listtask"> <i class="icon-inbox"></i>任务列表 </a> </li>
						{%else%}
						<li class="bg_ly" style="width: 17.4%;"> <a href="#"> <i class="icon-inbox"></i>未定 </a> </li>
						{%/if%}
						{%if $access->getAuthSettingAccess() %}
						<li style="width: 26.5%;" class="bg_lo"> <a href="/setting/authsetting"> <i class="icon-th"></i>用户认证设置</a> </li>
						{%else%}
						<li style="width: 26.5%;" class="bg_lo"> <a href="#"> <i class="icon-th"></i>未定</a> </li>
						{%/if%}
						{%if $access->getLicenseAccess() %}
						<li style="margin-right: 0px;width: 17.4%;" class="bg_ls span2"> <a href="/setting/license/"> <i class="icon-fullscreen"></i>授权管理</a> </li>
						{%else%}
						<li style="margin-right: 0px;width: 17.4%;" class="bg_ls span2"> <a href="#"> <i class="icon-fullscreen"></i>未定</a> </li>
						{%/if%}
					</ul>
					<ul class="quick-actions" style="width: 100%;">
						{%if $access->getContactListAccess() %}
						<li class="bg_lb" style="width: 17.4%;"> <a href="/contact/contactlist"> <i class="icon-dashboard"></i> 联系人分组 </a> </li>
						{%else%}
						<li class="bg_lb" style="width: 17.4%;"> <a href="#"> <i class="icon-dashboard"></i> 未定 </a> </li>
						{%/if%}
						{%if $access->getSecurityParamAccess() %}
						<li style="width: 26.5%;" class="bg_ls"> <a href="/setting/securityparam/"> <i class="icon-tint"></i>安全参数管理</a></li>
						{%else%}
						<li style="width: 26.5%;" class="bg_ls"> <a href="#"> <i class="icon-tint"></i>未定</a></li>
						{%/if%}
						{%if $access->getSingleTaskAccess() %}
						<li class="bg_lb" style="width: 17.4%;"> <a href="/statistics/singletask"> <i class="icon-pencil"></i>统计报告</a> </li>
						{%else%}
						<li class="bg_lb" style="width: 17.4%;"> <a href="#"> <i class="icon-pencil"></i>未定</a> </li>
						{%/if%}
						{%if $access->getNetworkSetAccess() %}
						<li class="bg_lg" style="width: 17.4%;"> <a href="/setting/networksetting/"> <i class="icon-calendar"></i> 网卡配置</a> </li>
						{%else%}
						<li class="bg_lg" style="width: 17.4%;"> <a href="#"> <i class="icon-calendar"></i> 未定</a> </li>
						{%/if%}
						{%if $access->getNetworkToolAccess() %}
						<li style="margin-right: 0px;width: 17.4%;" class="bg_lr span2"> <a href="/setting/networktool/"> <i class="icon-calendar"></i>网络工具</a> </li>
						{%else%}
						<li style="margin-right: 0px;width: 17.4%;" class="bg_lr span2"> <a href="#"> <i class="icon-calendar"></i> 未定</a> </li>
						{%/if%}
					</ul>
					{%/if%}
				 </div>
		</div>
  

    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
            <h5>24小时缓冲区邮件数量统计图</h5>
          </div>
          <div class="widget-content">
            <div id="container"></div>
            
          </div>
        </div>
      </div>
    </div>


    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>缓冲区邮件</h5>
          </div>
          <div class="widget-content ">
            <table class="table table-bordered table-striped with-check">
              <thead>
                <tr>
                  <!-- <th><input type="checkbox" name="title-table-checkbox" ></th> -->
                  <th style="width: 15%;">收件人</th>
                  <th>主题</th>
                  <th>大小</th>
                  <th>时间</th>
                  <th>重发次数</th>
                  <th>原因</th>
                </tr>
              </thead>
              <tbody>
                {%section name=vals loop=$mails%}
                <tr>
                  <!-- <td> -->
                  <input type="hidden" name="title-table-checkbox" value="{%$mails[vals].id%}">
                  <!-- </td> -->
                  <td><a href="#mail{%$mails[vals].id%}" name="mail{%$mails[vals].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo(this)">{%$mails[vals].sendto|truncate:30:"...":true%}</a></td>
                  <td><a href="#mail{%$mails[vals].id%}" name="mail{%$mails[vals].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo(this)">{%$mails[vals].title|truncate:30:"...":true%}</a></td>
                  <td><a href="#mail{%$mails[vals].id%}" name="mail{%$mails[vals].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo(this)">{%$mails[vals].mailsize%}</a></td>
                  <td><a href="#mail{%$mails[vals].id%}" name="mail{%$mails[vals].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo(this)">{%$mails[vals].runtime%}</a></td>
                  <td><a href="#mail{%$mails[vals].id%}" name="mail{%$mails[vals].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo(this)">{%$mails[vals].retries%}</a></td>
                  <td><a href="#mail{%$mails[vals].id%}" name="mail{%$mails[vals].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo(this)">{%$mails[vals].status%}</a></td>
                </tr>
                {%/section%}
              </tbody>
            </table>
              <div class="row-fluid">
			  <div style="width: 957px;float:left;">
                   <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
                        <div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;">
                          {%$page%}
                        </div>
                   </div>
				 </div>
				<div style="width: 100px; float: left; margin-top: 5px;">
                   <label style="height:30px;">
                  <form style="width:106px" method="GET" action="/task/forwardtask">
                    <select style="width:60px" aria-controls="DataTables_Table_0" size="1" name="num">
                      {%if $setnum==5 %}
                      <option value="5" selected='selected'>5</option>
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                      {%/if%}
                      {%if $setnum==10 %}
                      <option value="5">5</option>
                      <option value="10" selected='selected'>10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                      {%/if%}
                      {%if $setnum==25 %}
                      <option value="5">5</option>
                      <option value="10">10</option>
                      <option value="25" selected='selected'>25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                      {%/if%}
                      {%if $setnum==50 %}
                      <option value="5">5</option>
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50" selected='selected'>50</option>
                      <option value="100">100</option>
                      {%/if%}
                      {%if $setnum==100%}
                      <option value="5">5</option>
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100" selected='selected'>100</option>
                      {%/if%}
                    </select>
                    <input type="submit" style="font-size:12px;height:26px;" value="GO">
                  </form>   
                </label>
				</div>
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
          <tr>
            <td width="15%">标题:</td>
            <td><p id="title" ></p></td>
          </tr>
          <tr>
            <td>邮件发送者:</td>
            <td><p id="sendfrom" ></p></td>
          </tr>
          <tr>
            <td>邮件接收者:</td>
            <td><p id="sendto" ></p></td>
          </tr>
          <tr>
            <td>抄送:</td>
            <td><p id="sendcc" ></p></td>
          </tr>
          <tr>
            <td>密送:</td>
            <td><p id="sendbcc" ></p></td>
          </tr>
          <tr>
            <td>入队列时间:</td>
            <td><p id="iqueue" ></p></td>
          </tr>
          <tr>
            <td>发送时间:</td>
            <td><p id="sendtime" ></p></td>
          </tr>
          <!-- <tr>
            <td>发送量:</td>
            <td><p id="send_total" ></p></td>
          </tr>
          <tr>
            <td>进度:</td>
            <td><p id="send_progress" ></p></td>
          </tr>
          <tr>
            <td>失败量:</td>
            <td><p id="send_fail" ></p></td>
          </tr>
          <tr>
            <td>失败邮箱:</td>
            <td><p id="send_failed" style="work-break:break-all;overflow:auto;"></p></td>
          </tr>
          <tr>
            <td>成功邮箱:</td>
            <td><p id="send_successed" style="work-break:break-all;overflow:auto;" ></p></td>
          </tr>
          <tr>
            <td>队列中邮箱:</td>
            <td><p id="send_waited" style="work-break:break-all;overflow:auto;"></p></td>
          </tr>   -->
          <tr>
            <td>日志:</td>
            <td><div id="send_log" style="work-break:break-all;overflow:auto;"></div></td>
          </tr>  
        </table> 


      </p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">关闭</button>
      <!-- <button class="btn btn-primary">Save changes</button> -->
      </div>
      </div>
   <input type="hidden" id="maildatas" class="mailval" value='{%$final_data_str%}' />
   <input type="hidden" id="max_total" class="" value='{%$total_max%}' />

</div>
<script>
  //导航菜单
  $("#collapseG3").on("show", function() {
    var up=$("#quicknav").children().children("i").attr("class",'icon-chevron-up');
  });
  $("#collapseG3").on("hide", function() {
    var up=$("#quicknav").children().children("i").attr("class",'icon-chevron-down');
  });

$(function(){
  $(document).ready(function(){
      var vals= $('#maildatas').val();
      var max_vals= $('#max_total').val();
      // alert(max_vals);
          if(max_vals == 0 || max_vals == ''){
             var max_total = parseInt(max_vals)+parseInt(100);
          }else{
             var max_total = null;
          }
      var  datasall = eval('('+vals+')');
      // var time_length = datasall[0].length;
          Highcharts.setOptions({ 
                global: 
                { 
                  useUTC: false 
                } 
              }); 
            $('#container').highcharts({ 
                  chart: { type: 'spline' }, 
                  title: { text: null }, 
                   xAxis: { type: 'datetime', 
                      tickPixelInterval:60,
                       minRange:24*3600*1000,
                      dateTimeLabelFormats: { 
                         month: '%e. %b',
                          year: '%b' 
                         }
                      }, 
                      credits : { 
                                enabled : false
                            },
                yAxis: { 
                  title: { text: null }, 
                  min: 0 ,
                  max:max_total,
                  allowDecimals:false
                 },
                 tooltip: {
                   formatter: function() { 
                  return '<b>'+ this.series.name +'</b><br>'+ Highcharts.dateFormat('%Y-%m-%d %H:%M', this.x) +'<br>'+ Highcharts.numberFormat(this.y,false);
          } 
                 }, 
                series: [
                  { 
                    name: '发送量', 
                   data: datasall[0]
                  }, 
                 { 
                  name: '成功',
                   data: datasall[1]
                 }, 
                  {
                   name: '等待', 
                   data: datasall[2]
                   }] 
              }); 
  });
})

  function mailsinfo(obj){
        var mailsid = obj.name;
        var mailsinfoid = obj.parentNode.parentNode
        var mailval = $(mailsinfoid).find('input').val();
        $('.mail').attr('id',mailsid);
        $('.mailonce').find('p').css({'line-height':'20px',"margin":'0','word-break':'break-all'});
        $.post('/task/checkforward',{'valsid':mailval},function(data){
            if(data){
              var strs = eval('('+data+')');
              $('#title').text(strs[1].title);
              $('#sendfrom').text(strs[1].sendfrom);
              $('#sendto').text(strs[0].sendto);
              $('#sendcc').text(strs[1].cc);
              $('#sendbcc').text(strs[1].bcc);
              $('#iqueue').text(strs[1].inqueuetime);
              $('#sendtime').text(strs[1].sendtime);
              // $('#send_total').text(strs[1].total);
              // $('#send_progress').text(strs[1].progress);
              // $('#send_fail').text(strs[1].failure);
              // $('#send_failed').text(strs[1].failed);
              // $('#send_successed').text(strs[1].successed);
              // $('#send_waited').text(strs[1].waited);
              $('#send_log').html(strs[0].log);
            } 
        });
  }

  function  getinterval(){
        $.post('/task/taskforward/',function(data){
            if(data){
              return data;
            }
        });
  }
 
</script>
{%include file="footer.php"%}
