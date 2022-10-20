{%include file="header.php"%}
<script type="text/javascript">
function delaccount (id) {
	art.dialog.confirm('你确定要删除这条消息吗？', function () {
		window.location.href = "/setting/delaccount?id=" + id;
	}, function () {
		return function (){};
	});
}	
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> 
	<a href="/setting/accountmanage" class="tip-bottom current" title="账户管理">账户管理</a> 
	</div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>账户管理</h5>
          </div>
          <div class="widget-content ">
            <table class="table table-bordered table-striped with-check">
              <thead>
                <tr>
                  <th style="width:6%">序号</th>
                  <th>账号</th>
                  <th>类型</th>
				  <!--<th>创建所属</th>-->
				  {%if $role == "stasker" || $role == "tasker"%}
				  <th>是否审批</th>
				  {%/if%} 
                  <th>使用状态</th>
                  <th>修改时间</th>
				  <th>本次访问时间</th>
				  <th style="width:12%">操作</th>
                </tr>
              </thead>
              <tbody>
				{%$a = 1%}
				{%section name=user loop=$userlist%}
                <tr>
				  <td> {%$a++%}</td>
                  <td>{%$userlist[user].username%}</td>
				  {%if $userlist[user].role == 'sadmin'%}
                  <td>系统管理员</td>
				  {%elseif $userlist[user].role == 'admin'%}
				  <td>普通管理员</td>
				  {%elseif $userlist[user].role == 'stasker'%}
				  <td>任务发布员</td>
				  {%else%}
				  <td>普通任务发布员</td>
				  {%/if%}
				  <!--<td>{%$userlist[user].parentid%}</td>-->
				  {%if $role == "stasker" || $role == "tasker"%}
					  {%if $userlist[user].audit == '1'%}
					  <td>审批</td>
					  {%else%}
					  <td>不审批</td>
					  {%/if%}
				  {%/if%} 
				  {%if $userlist[user].lock == '0'%}
				  <td>禁用</td>
				  {%else%}
				  <td>启用</td>
				  {%/if%}
                  <td>{%$userlist[user].lastmodify%}</td>
				  <td>{%$userlist[user].lastaccess%}</td>
				  <td>
					  <a href="/setting/accesslog?userid={%$userlist[user].id%}" class="tip-bottom" data-original-title="操作日志"><i class="icon-search"></i></a>
					  <span style="padding-left :30px">
					  <a href="/setting/editaccount?id={%$userlist[user].id%}" class="tip-bottom" data-original-title="编辑"><i class="icon-pencil"></i></a>
					  </span>
					  <span style="padding-left :30px">
					  {%if $role != $userlist[user].role || $uname != $userlist[user].username %}
					  <a href="#" onclick="javascript:void(delaccount('{%$userlist[user].id%}'))" class="tip-bottom" data-original-title="删除"><i class="icon-remove"></i></a>
					  {%/if%}
					  </span>
				  </td>
				</tr>
				{%/section%}
              </tbody>
            </table>
          </div>
        </div>
		{%if $access->getAdminCreationAccess() && $role != 'admin' && $role != 'tasker'%}
		<center><a class="btn" href="/setting/admincreation">新增账号</a></center>
		{%/if%}
	  </div>
	</div>
  </div>
</div>
{%include file="footer.php"%}

