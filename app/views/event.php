<script type='text/javascript' src='/dist/js/jquery.form.min.js'></script>
<script type="text/javascript">
$(function(){
	$(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd HH:ii:00",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"
    });
	
	$("#del_event").click(function(){
		$("#myaction").val("del");
	});
	
	$('#add_form').ajaxForm({
		beforeSubmit: showRequest, //表单验证
        success: showResponse //成功返回
    });
	
	function showRequest(){
		var events = $("#titleid").val();
		if(events==''){
			alert("请输入日程内容！");
			$("#titleid").focus();
			return false;
		}
		var starttime = $("#starttime").val();
		if(starttime==''){
			alert("请输入开始日期！");
			$("#starttime").focus();
			return false;
		}
		var endtime = $("#endtime").val();
		if(endtime==''){
			alert("请输入结束日期！");
			$("#endtime").focus();
			return false;
		}
		var starttimestamp = datetime_to_unix(starttime);
		var endtimestamp = datetime_to_unix(endtime);
		if(starttimestamp > endtimestamp){
			alert("请输入合理的日期范围！");
			$("#endtime").focus();
			return false;
		}
		var action = $("#myaction").val();
		
		var url = "/createtem/checkevent";
		var result = $.ajax({
			type: 'POST',
			url: url,
			data: {s:starttime, e:endtime},
			async: false
		}).responseText;
		if (result != "0") {
			if (action != "del") {
				alert("该时间段事件超过2条，不许添加！");
				return false;
			}
		}
		return true;
	}

	function showResponse(responseText, statusText, xhr, $form){
		if(statusText=="success"){	
			if(responseText==1){
				$.fancybox.close();
				$('#calendar').fullCalendar('refetchEvents'); //重新获取所有事件数据
			}else{
				alert(responseText);
			}
		}else{
			alert(statusText);
		}
	}
	
	function datetime_to_unix(datetime){
		var tmp_datetime = datetime.replace(/:/g,'-');
		tmp_datetime = tmp_datetime.replace(/ /g,'-');
		var arr = tmp_datetime.split("-");
		var now = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
		return parseInt(now.getTime()/1000);
	}
	
});
</script>
<div class="fancy">
	<h3>新建事件</h3>
    <form id="add_form" name="addform" action="/createtem/addcalendar" method="post">
    <input type="hidden" name="action" id="myaction" value="add">
	<input type="hidden" name="eventid" value="{%$info['id']%}">
    <p>日程内容：<input type="text" class="input" name="titlename" id="titleid" style="width:320px" value="{%$info['title']%}" maxlength="6" placeholder="简要记录一件事（6字内）"></p>
    <p>开始时间：<span class="input-append date form_datetime"><input type="text" value="{%$info['starttime']%}" name="starttime" id="starttime" /><span class="add-on"><i class="icon-calendar"></i></span></span>
    <p>结束时间：<span class="input-append date form_datetime"><input type="text" value="{%$info['endtime']%}" name="endtime" id="endtime" /><span class="add-on"><i class="icon-calendar"></i></span></span>
    <div class="sub_btn">{%if $info['id'] != "" and $info['id'] != null%}<span class="del"><input type="submit" class="btn btn_del" id="del_event" value="删除"></span> {%/if%}<input type="submit" class="btn btn_ok" value="确定"> <input type="button" class="btn btn_cancel" value="取消" onClick="$.fancybox.close()"></div>
    </form>
</div>
