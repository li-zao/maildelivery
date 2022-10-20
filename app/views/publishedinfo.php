<!-- header -->
{%include "header.php"%}
<script type="text/javascript" src="/dist/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/dist/js/matrix.tables.js"></script>
<script type="text/javascript">
function addinfo () {
	window.location = "/setting/addinfo";
}

function delinfo () {
	var value_str = "";
	var checkbox = $('tbody input:checkbox');
	checkbox.each(function() {
		if (this.checked) {
			$(this).closest('.checker > span').addClass('checked');
			value_str += this.value+"@";
		}
	});
	$("#checklist").val(value_str);
	if (value_str == "") {
		art.dialog.alert("请选择要删除的条目！");
		return false;
	}
	document.listform.action = "/setting/delinfo";
	document.listform.submit();
}
$(function(){
	var flip = 0;
	$("#searchcond").click(function(){
		$("#searchcondtable").toggle(10, function(){
			if (flip++ % 2 == 0) {
				$("#searchcond").children().children("i").attr("class",'icon-chevron-up');
			} else {
				$("#searchcond").children().children("i").attr("class",'icon-chevron-down');
			}
		});
	})
	
	$(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd HH:ii",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"
    });
	
	var default_show = parseSearchCon("noclick","no");
	if (default_show) {
		$("#searchcondtable").show();
		$("#searchcond").children().children("i").attr("class",'icon-chevron-up');
	}
	// search
	$("#searchbtn").click(function(){
		var url = "/setting/publishedinfo?";
		url += parseSearchCon("click","on");
		window.location.href = url;
	});
	
	function parseSearchCon (type, gonum) {
		var parameter = "";
		var has_val = false;
		var title = $("#titleid").val();
		if (title != "" && title != null) {
			parameter = "title=" + encodeURIComponent(title);
			has_val = true;
		}
		var content = $("#contentid").val();
		if (content != "" && content != null) {
			if (parameter == "") {
				parameter = "content=" + encodeURIComponent(content); 
			} else {
				parameter += "&content=" + encodeURIComponent(content); 
			}
			has_val = true;
		}
		var creator = $("#creatorid").val();
		if (creator != "" && creator != null) {
			if (parameter == "") {
				parameter = "creator=" + encodeURIComponent(creator); 
			} else {
				parameter += "&creator=" + encodeURIComponent(creator); 
			}
			has_val = true;
		}
		var createtime1id = $("#createtime1id").val();
		var createtime2id = $("#createtime2id").val();
		if (createtime1id != "" && createtime1id != null) {
			if (parameter == "") {
				parameter = "createtime1=" + createtime1id; 
			} else {
				parameter += "&createtime1=" + createtime1id; 
			}
			has_val = true;
		}
		if (createtime2id != "" && createtime2id != null) {
			if (parameter == "") {
				parameter = "createtime2=" + createtime2id; 
			} else {
				parameter += "&createtime2=" + createtime2id; 
			}
			has_val = true;
		}
		var num = $("#numid").val();
		if (num != "" && num != null) {
			if (parameter == "") {
				parameter = "num=" + num; 
			} else {
				parameter += "&num=" + num; 
			}
		}
		var curpage = $("#pageid").val();
		if (curpage != "" && curpage != null) {
			if (parameter == "") {
				parameter = "page=" + curpage; 
			} else {
				parameter += "&page=" + curpage; 
			}
		}
		if (gonum == "on") {
			parameter += "&mode=" + "search"; 
		} 
		if (type == "click") {
			return parameter;
		} else {
			return has_val;
		}
	}
	
	$("#resetcond").click(function(){
		$("#titleid, #contentid, #creatorid, #createtime1id, #createtime2id").val("");
	})
});
</script>
<div id="content">
	<div id="content-header">
    	<div id="breadcrumb">
    		<a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i>返回首页</a> <a href="#">系统设置</a> <a href="/setting/publishedinfo" class="current"></i>信息公告</a>
    	</div>
	</div>			
 <div class="container-fluid">
 
 
 
 <div href="#collapseG3" id="searchcond" data-toggle="collapse" class="widget-title bg_lo"> 
       <h5 style="float: right;">检索条件
		 <i class="icon-chevron-down"></i>
	   </h5>
    </div>
          <div id="searchcondtable" style="display:none;">
            <form name="deliveryparam" id="auth-form" method="post">
			<table class="table table-bordered">
			  <tbody>
			   <tr class="searchcondition">
					<td style="width:10%">
					标题
					</td>
					<td>
					 <input type="text" value="{%$title%}" name="title" id="titleid" />
					</td>
					<td style="width:10%">
					内容
					</td>
					<td>
					<input type="text" value="{%$content%}" name="content" id="contentid" />
					</td>
					<td style="width:10%">
					创建者
					</td>
					<td>
					 <input type="text" value="{%$creator%}" name="creator" id="creatorid" />
					</td>
               </tr>
               <tr>
					<td style="width:10%" class="searchcondition">
					创建时间
					</td>
					<td colspan="" class="searchcondition">
					<div class="	input-append date form_datetime"><input type="text" value="{%$createtime1%}" name="createtime1" id="createtime1id" style="width: 186px;"/><span class="add-on"><i class="icon-calendar"></i></span></div></td>
					<td>	&nbsp;&nbsp;至&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>
					<div class="input-append date form_datetime"><input type="text" value="{%$createtime2%}" name="createtime2" id="createtime2id" style="width: 186px;"/><span class="add-on"><i class="icon-calendar"></i></span></div>
					</td>
					<td colspan="2">
					<center>
					<a href="#" class="btn" style="height: 18px;margin-left:5px;font-size:12px;" id="searchbtn"><i class="icon icon-search"></i>&nbsp;搜索</a>
					<a href="#" class="btn" style="margin-left:5px; height: 18px;font-size:12px;width: 38px;" id="resetcond">重置</a>
					</center>
					</td>
               </tr>
              </tbody>
            </table>
			<input type="hidden" value="{%$num%}" name="num" id="numid" />
			<input type="hidden" value="{%$curpage%}" name="page" id="pageid" />
			</form>
          </div>
 
 
 
 
 
    <div class="row-fluid">
      <div class="span12">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>历史信息栏</h5>
			
          <button class="btn " id="addinfo" onclick="javascript:void(addinfo())" style="float:right;margin:3px 10px;">新建信息</button>
		  </div>
          <div class="widget-content nopadding">
			<form name="listform" method="post" action="">
            <table width="80%" align="center"  class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="text-align:center;"><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" />&nbsp;&nbsp;全选</th>
                  <th style="text-align:center">序号</th>
				  <th style="text-align:center" >标题</th>
                  <th style="text-align:center">创建者</th>
                  <th style="text-align:center">创建时间</th>
                </tr>
              </thead>
              <tbody>
				{%$a = 1%}
				{%section name=info loop=$infos%}
                <tr class="gradeX0">
                  <td style="text-align:center"><input style="margin-left:2px;" type="checkbox" id="infoid" name="infoid" value="{%$infos[info].id%}" /></td>
                  <td style="text-align:center">{%$a++%}</td>
				  <td style="text-align:center"><a href="/setting/addinfo?infoid={%$infos[info].id%}">{%$infos[info].title%}</a></td>
                  <td style="text-align:center">{%$infos[info].creator%}</td>
                  <td style="text-align:center">{%$infos[info].createtime%}</td>
                </tr>
				{%/section%}
              </tbody>
            </table>
			<input type="hidden" value="" id="checklist" name="infolist" />
			</form>
          </div>
		  <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
				<div class="row-fluid" style="text-align:right; margin-top: 0px;">
					<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="margin-top: 5px;">
						<button class="btn " id="delinfo" onclick="javascript:void(delinfo())" style="float:left;margin:3px 13px;">删除</button>
						<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;float:left;margin-left:20%;">
							{%$page%}
						</div>
						<div id="DataTables_Table_0_length" class="dataTables_length" style="float:right;width:100px;margin-right:18px;">
							<label style="height:30px;">
								<form action="/setting/publishedinfo" method="GET" style="width:106px">
									<select name="num" size="1" aria-controls="DataTables_Table_0" style="width:60px">
										<option {%if $num==5 %}selected="selected"{%/if%} value="5">5</option>
										<option {%if $num==10 %}selected="selected"{%/if%} value="10">10</option>
										<option {%if $num==25 %}selected="selected"{%/if%} value="25">25</option>
										<option {%if $num==50 %}selected="selected"{%/if%} value="50">50</option>
										<option {%if $num==100 %}selected="selected"{%/if%} value="100">100</option>
									</select>
									<input type="submit" value="GO" style="font-size:12px;height:26px;" />
								</form>		
							</label>
						</div>
					</div>
    			</div>
			</div>
        </div>
	  </div>	
	 </div>	
   </div>
</div>
{%include "footer.php"%}