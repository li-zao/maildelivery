{%include file="header.php"%}
<style>
        .col{color:red;}
		input[disabled],select[disabled], textarea[disabled], input[readonly], select[readonly], textarea[readonly]{ background-color:#F9F9F9}
		#tbone td{padding:4px;}
</style>
<script type="text/javascript">
	function oprator(str1,str2){
		var arr = new Array();
		var brr = new Array();
		var crr = new Array();
		arr = {"0":"是","1":"否","2":"包含","3":"不包含","4":"开始字符","5":"结束字符"};
		brr = {"100":"等于","101":"不等于","102":"大于","103":"不大于","104":"小于","105":"不小于"};
		crr = {"200":"之前","201":"之后","202":"介于","203":"不介于"};
		var type = $("#"+str1+" option:selected").attr("type");
		var str = "";
		$("#"+str2).empty();
		if(type == 1){
			$.each(arr,function(i,result){
				str += "<option value='"+i+"'>"+result+"</option>";
			})
			str += "<option value='300'>为空</option><option value='301'>不为空</option>";
			$("#"+str2).append(str);
		}else if(type == 2){
			//alert($("#showname option:selected").val());
			if($("#"+str1+" option:selected").val() == 3){
				str += "<option value='0'>是</option><option value='1'>否</option>";
			}else{
				$.each(brr,function(i,result){
					str += "<option value='"+i+"'>"+result+"</option>";
				})
			}
			str += "<option value='300'>为空</option><option value='301'>不为空</option>";
			$("#"+str2).append(str);
		}else if(type == 3){
			$.each(crr,function(i,result){
				str += "<option value='"+i+"'>"+result+"</option>";
			})
			str += "<option value='300'>为空</option><option value='301'>不为空</option>";
			$("#"+str2).append(str);
		}else{
			str += "<option value='102'>大于</option><option value='104'>小于</option>";
			str += "<option value='300'>为空</option><option value='301'>不为空</option>";
			$("#"+str2).append(str);
		}
	}

	function changevalue(id1,id2,id3){
		//alert($("#op option:selected").text());
		//alert(id1);alert(id2);
		var arr = new Array();
		var brr = new Array();
		var crr = new Array();
		var str = "";
		arr = {"0":"是","1":"否","2":"包含","3":"不包含","4":"开始字符","5":"结束字符"};
		brr = {"100":"等于","101":"不等于","102":"大于","103":"不大于","104":"小于","105":"不小于"};
		crr = {"200":"之前","201":"之后","202":"介于","203":"不介于"};
		var type = $("#"+id1+" option:selected").attr("type");
		$("#"+id2).empty();
		if(type == 1){
			$.each(arr,function(i,result){
				str += "<option value='"+i+"'>"+result+"</option>";
			})
			str += "<option value='300'>为空</option><option value='301'>不为空</option>";
			$("#"+id2).append(str);
		}else if(type == 2){
			//alert($("#showname option:selected").val());
			if($("#"+id1+" option:selected").val() == 3){
				str += "<option value='0'>是</option><option value='1'>否</option>";
			}else{
				$.each(brr,function(i,result){
					str += "<option value='"+i+"'>"+result+"</option>";
				})
			}
			str += "<option value='300'>为空</option><option value='301'>不为空</option>";
			$("#"+id2).append(str);
		}else if(type == 3){
			$.each(crr,function(i,result){
				str += "<option value='"+i+"'>"+result+"</option>";
			})
			str += "<option value='300'>为空</option><option value='301'>不为空</option>";
			$("#"+id2).append(str);
		}else{
			str += "<option value='102'>大于</option><option value='104'>小于</option>";
			str += "<option value='300'>为空</option><option value='301'>不为空</option>";
			$("#"+id2).append(str);
		}

		if($("#"+id2+" option:selected").text() == "为空" || $("#"+id2+" option:selected").text() == "不为空"){
			$("#"+id3).empty();
		}else{
			if(type == 3){
				if($("#"+id2+" option:selected").text() == "介于" || $("#"+id2+" option:selected").text() == "不介于"){
					var time = "<div class='input-append date form_date'><input style='width:206px' name='birth1' onclick='calendar()' size='16' type='text' value='' ><span class='add-on'><i class='icon-th'></i></span></div><div>--</div><div class='input-append date form_date'><input style='width:206px' name='birth2' onclick='calendar()' size='16' type='text' value='' ><span class='add-on'><i class='icon-th'></i></span></div>";
				}else{
					var time = "<div class='input-append date form_date'><input name='birth' onclick='calendar()' size='16' type='text' value='' ><span class='add-on'><i class='icon-th'></i></span></div>";
				}
				$("#"+id3).empty();
				$("#"+id3).append(time);
				//$("#sp div").addClass("form_date");
			}else{
				$("#"+id3).empty();
				var str = "<input type='text' id='str' style='width:227px;' name='str[]' />";
				$("#"+id3).append(str);
			}
		}
	}

	function changetime(id1,id2,id3){
		if($("#"+id1+" option:selected").attr("type") == 3){
			if($("#"+id2+" option:selected").text() == "介于" || $("#"+id2+" option:selected").text() == "不介于"){
				var time = "<div class='input-append date form_date'><input style='width:206px' name='birth1' onclick='calendar()' size='16' type='text' value='' ><span class='add-on'><i class='icon-th'></i></span></div><div>--</div><div class='input-append date form_date'><input style='width:206px' name='birth2' onclick='calendar()' size='16' type='text' value='' ><span class='add-on'><i class='icon-th'></i></span></div>";
			}else if($("#"+id2+" option:selected").text() == "之后" || $("#"+id2+" option:selected").text() == "之前"){
				var time = "<div class='input-append date form_date'><input name='birth' onclick='calendar()' size='16' type='text' value='' ><span class='add-on'><i class='icon-th'></i></span></div>";
			}
			$("#"+id3).empty();
			$("#"+id3).append(time);
		}else{
			if($("#"+id2+" option:selected").text() == "为空" || $("#"+id2+" option:selected").text() == "不为空"){
				$("#"+id3).empty();
			}else{
				$("#"+id3).empty();
				var str = "<input type='text' id='str' style='width:227px;' name='str[]' />";
				$("#"+id3).append(str);
			}
		}
	}
	
	$(function(){
		if($("#tbone tr").length == 0){
			var pretermit = "";
			pretermit += "<tr><td width='10%' style='text-align:center'><input type='text' custom='0' value='筛选条件' disabled=true style='width:73px;border:0px;text-align:center;font-size:12px' name='jo[]'></td>";
			pretermit += "<td width='10%' style='text-align:center'><b><input type='text' value='1' disabled=true style='width:73px;border:0px;text-align:center;font-size:12px' name='number[]'></b></td>";
			pretermit += "<td width='18%' style='text-align:center'><select name='showname[]' style='width:150px;font-size:12px' onchange='changevalue(\"selopr1\",\"opra1\",\"text1\")' id='selopr1'>";
			function redata(){
				var result;
				$.ajax({
					data:"post",
					dataType:"json",
					url:"getexpansion",
					async:false,
					success:function(data){
						result = data;
					}
				})
				return result;
			}
			dataobj = redata();
			$.each(dataobj,function(i,result){
				pretermit += "<option value="+result.id+" type="+result.type+">"+result.showname+"</option>";
			})
			pretermit += "</select></td>";
			var type = $("#showname option:selected").attr("type");
			var opr = $("#op option:selected").val();
			var arr = new Array();
			var brr = new Array();
			var crr = new Array();
			arr = {"0":"是","1":"否","2":"包含","3":"不包含","4":"开始字符","5":"结束字符"};
			brr = {"100":"等于","101":"不等于","102":"大于","103":"不大于","104":"小于","105":"不小于"};
			crr = {"200":"之前","201":"之后","202":"介于","203":"不介于"};
			pretermit += "<td width='15%' style='text-align:center'><select name='opra[]' onchange='changetime(\"selopr1\",\"opra1\",\"text1\")' style='width:120px;font-size:12px' id='opra1'>";
				$.each(arr,function(i,result){
					if(opr == i){
						pretermit += "<option value='"+i+"' selected='selected'>"+result+"</option>";
					}else{
						pretermit += "<option value='"+i+"'>"+result+"</option>";
					}
				})
				if(opr == 300){
					pretermit += "<option value='300' selected='selected'>为空</option>";
				}else{
					pretermit += "<option value='300'>为空</option>";
				}
				if(opr == 301){
					pretermit += "<option value='301' selected='selected'>不为空</option>";
				}else{
					pretermit += "<option value='301'>不为空</option>";
				}
			pretermit += "</select></td>";
			pretermit += "<td width='25%' style='text-align:center' id='text1'><input type='text' value='' style='border:1px solid #ccc;width:227px;font-size:12px' name='str[]'></td><td width='15%' style='text-align:center'><a class='add icon-plus btn btn-small' href='javascript:addcon()' style='cursor:pointer'></a></td></tr>";
			$("#tbone").append(pretermit);
		}
		$(".add").click(function(){
			/*var pretermit = "";
			num = $("#tbone").children().children().length;
			if(num > 1){
				
			}
			pretermit += "<tr><td width='10%' style='text-align:center'><select name='jo[]' style='width:80px;font-size:12px'><option value='2'>与(and)</option><option value='1'>或(or)</option></select></td>";
			pretermit += "<td width='10%' style='text-align:center'><b><input type='text' value='"+(num+1)+"' disabled=true style='width:73px;border:0px;text-align:center;font-size:12px' name='number[]'></b></td>";
			pretermit += "<td width='18%' style='text-align:center'><select name='showname[]' style='width:150px;font-size:12px' onchange='changevalue(\"selopr"+(num+1)+"\",\"opra"+(num+1)+"\",\"text"+(num+1)+"\")' id='selopr"+(num+1)+"'>";
			function redata(){
				var result;
				$.ajax({
					data:"post",
					dataType:"json",
					url:"getexpansion",
					async:false,
					success:function(data){
						result = data;
					}
				})
				return result;
			}
			dataobj = redata();
			$.each(dataobj,function(i,result){
				pretermit += "<option value="+result.id+" type="+result.type+">"+result.showname+"</option>";
			})
			pretermit += "</select></td>";
			var type = $("#showname option:selected").attr("type");
			var opr = $("#op option:selected").val();
			var arr = new Array();
			var brr = new Array();
			var crr = new Array();
			arr = {"0":"是","1":"否","2":"包含","3":"不包含","4":"开始字符","5":"结束字符"};
			brr = {"100":"等于","101":"不等于","102":"大于","103":"不大于","104":"小于","105":"不小于"};
			crr = {"200":"之前","201":"之后","202":"介于","203":"不介于"};
			pretermit += "<td width='15%' style='text-align:center'><select name='opra[]' onchange='changetime(\"selopr"+(num+1)+"\",\"opra"+(num+1)+"\",\"text"+(num+1)+"\")' style='width:120px;font-size:12px' id='opra"+(num+1)+"'>";
				$.each(arr,function(i,result){
					if(opr == i){
						pretermit += "<option value='"+i+"' selected='selected'>"+result+"</option>";
					}else{
						pretermit += "<option value='"+i+"'>"+result+"</option>";
					}
				})
				if(opr == 300){
					pretermit += "<option value='300' selected='selected'>为空</option>";
				}else{
					pretermit += "<option value='300'>为空</option>";
				}
				if(opr == 301){
					pretermit += "<option value='301' selected='selected'>不为空</option>";
				}else{
					pretermit += "<option value='301'>不为空</option>";
				}
			pretermit += "</select></td>";
			pretermit += "<td width='30%' style='text-align:center' id='text"+(num+1)+"'><input type='text' value='' style='border:1px solid #ccc;width:227px;font-size:12px' name='str[]'></td><td width='15%' style='text-align:center'><a class='add icon-plus' href='javascript:addcon()'></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:del("+(num+1)+")' class='icon-minus'></a></td></tr>";
				
			$("#tbone").append(pretermit);*/
		})
		

		$("#btn").click(function(){
			var filtername = $("#filtername").val();
			if($("#filtername").val() == ""){
				art.dialog.alert("筛选器名称不能为空");
				$("#filtername").focus();
				return false;
			}
			
			if(checkfilter() == false){	
				art.dialog.alert("筛选条件不能为空！");
				return false;
			}
			var numstr = "";
			var numstr2 = new Array();
			$.each($("input[name='number[]']"),function(i,result){
				numstr += $(this).val()+",";
				numstr2[i] = $(this).val();
			})
			var jostr = " ,";
			$.each($("select[name='jo[]']"),function(){
				jostr += $(this).attr("value")+",";
			})
			var sname = "";
			var sname2 = new Array();
			var type = new Array();
			$.each($("select[name='showname[]'] option:selected"),function(i,result){
				sname += $(this).attr("value")+",";
				sname2[i] = $(this).attr("value");
				type[i] = $(this).attr("type");
			})
			var ti = "";
			var ti2 = new Array();
//			$.each($("input[name='str[]']"),function(){
//				ti += $(this).val()+",";
//			})
			$.each($("input[name='number[]']"),function(i,result){
				//alert($("#text"+(i+1)+" input").val());
				if($("#text"+(i+1)+" input").length > 1){
					$.each($("#text"+(i+1)+" input"),function(j,result){
						if(j == 0){
							ti += $(this).val()+"至";
						}else{
							ti += $(this).val();
						}
					})
					ti += ",";
				}else{
					ti += $("#text"+(i+1)+" input").val()+",";
				}	
			})
			ti2 = ti.split(",");
			var opra = "";
			var opra2 = new Array();
			$.each($("select[name='opra[]'] option:selected"),function(i,result){
				opra += $(this).attr("value")+",";
				opra2[i] = $(this).attr("value");
			})
			var TAG = true;
			$.each(numstr2,function(i,result){
				if(type[i] == 1){
					if(sname2[i] == 2){
						var search_str = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
						if(opra2[i] == 0 || opra2[i] == 1){
							if(!search_str.test(ti2[i])){
								art.dialog.alert("请输入正确的邮箱格式");
								TAG = false;
								return true;
							}
						}
					}else if(sname2[i] == 5){
						var telrule = /^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8}$/;
						if(opra2[i] == 0 || opra2[i] == 1){
							if(!telrule.test(ti2[i])){
								art.dialog.alert("请输入正确的手机格式");
								TAG = false;
								return true;
							}
						}
					}
				}else if(type[i] == 2){
					if(sname2[i] == 3){
						if(ti2[i] != "男" && ti2[i] != "女" && ti2[i] != "undefined"){
							art.dialog.alert("请输入正确的性别");
							TAG = false;
							return true;
						}
					}
				}else if(type[i] == 3){
					var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
					var time = ti2[i].split("至");
					$.each(time,function(j,res){
						if(time[j] != "" && time[j] != "undefined"){
							if(!reg.test(time[j])){
								art.dialog.alert("请保证输入日期格式为yyyy-mm-dd");
								TAG = false;
								return true;
							}
						}
					})
 				}else if(type[i] == 4){
					if(!$.isNumeric(ti2[i])){
						art.dialog.alert("请输入正确的数字");
						TAG = false;
						return true;
					}
				}
			})
			//return TAG;
			$("#numstr").attr("value",numstr);
			$("#jostr").attr("value",jostr);
			$("#sname").attr("value",sname);
			$("#ti").attr("value",ti);
			$("#opra").attr("value",opra);
			var fid = $("input[name='id']").val();
			$.post("/contact/ajaxfiltername",{'fname':filtername,'fid':fid},function(data){
				if(data == 1){
					art.dialog.alert("筛选器名称已经存在");
					return false;
				}else{
					if(TAG){
						$("#myform").submit();
					}
				}
			});
			//$("#myform").submit();
		});
	})
	
	function checkfilter(){
		var result = false;
		var i = 0;
		var n=$("input[name='str[]']").length;
		$("input[name='str[]']").each(function(){	
			if ($(this).val() != "") {
				i += 1;
			}
		});  
		if(n == i){
			return true;
		}else{
			return false;
		}	
	}	
	
	function addcon(){
			var pretermit = "";
			num = $("#tbone").children().children().length;
			if(num > 1){
				
			}
			pretermit += "<tr><td width='10%' style='text-align:center'><select name='jo[]' style='width:80px;font-size:12px'><option value='2'>与(and)</option><option value='1'>或(or)</option></select></td>";
			pretermit += "<td width='10%' style='text-align:center'><b><input type='text' value='"+(num+1)+"' disabled=true style='width:73px;border:0px;text-align:center;font-size:12px' name='number[]'></b></td>";
			pretermit += "<td width='18%' style='text-align:center'><select name='showname[]' style='width:150px;font-size:12px' onchange='changevalue(\"selopr"+(num+1)+"\",\"opra"+(num+1)+"\",\"text"+(num+1)+"\")' id='selopr"+(num+1)+"'>";
			function redata(){
				var result;
				$.ajax({
					data:"post",
					dataType:"json",
					url:"getexpansion",
					async:false,
					success:function(data){
						result = data;
					}
				})
				return result;
			}
			dataobj = redata();
			$.each(dataobj,function(i,result){
				pretermit += "<option value="+result.id+" type="+result.type+">"+result.showname+"</option>";
			})
			pretermit += "</select></td>";
			var type = $("#showname option:selected").attr("type");
			var opr = $("#op option:selected").val();
			var arr = new Array();
			var brr = new Array();
			var crr = new Array();
			arr = {"0":"是","1":"否","2":"包含","3":"不包含","4":"开始字符","5":"结束字符"};
			brr = {"100":"等于","101":"不等于","102":"大于","103":"不大于","104":"小于","105":"不小于"};
			crr = {"200":"之前","201":"之后","202":"介于","203":"不介于"};
			pretermit += "<td width='15%' style='text-align:center'><select name='opra[]' onchange='changetime(\"selopr"+(num+1)+"\",\"opra"+(num+1)+"\",\"text"+(num+1)+"\")' style='width:120px;font-size:12px' id='opra"+(num+1)+"'>";
				$.each(arr,function(i,result){
					if(opr == i){
						pretermit += "<option value='"+i+"' selected='selected'>"+result+"</option>";
					}else{
						pretermit += "<option value='"+i+"'>"+result+"</option>";
					}
				})
				if(opr == 300){
					pretermit += "<option value='300' selected='selected'>为空</option>";
				}else{
					pretermit += "<option value='300'>为空</option>";
				}
				if(opr == 301){
					pretermit += "<option value='301' selected='selected'>不为空</option>";
				}else{
					pretermit += "<option value='301'>不为空</option>";
				}
			pretermit += "</select></td>";
			pretermit += "<td width='25%' style='text-align:center' id='text"+(num+1)+"'><input type='text' value='' style='border:1px solid #ccc;width:227px;font-size:12px' name='str[]'></td><td width='15%' style='text-align:center'><a class='add icon-plus btn btn-small' href='javascript:addcon()'></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:del("+(num+1)+")' class='icon-minus btn btn-small'></a></td></tr>";
				
			$("#tbone").append(pretermit);
		}
	
	function del(num){
		if($("#tbone tr").length>1){
			//alert(num);
			var count = $("#tbone tr").length;
			//var number = num-1;
			var number2 = $("#number2").val();
			if(num==1){
				//$("#tb tr")[number].remove();
				$.each($("#tbone tr"),function(i,result){
					if(i == num-1){
						$(this).remove();
					}
				})
				$.each($("input[name='number[]']"),function(i,result){
					$(this).attr("value",i+1);
				})
				$.each($("select[name='jo[]']"),function(i,result){
					if(i==0){
						//$(this).parent().empty();
						$(this).parent().html("<input type='text' custom='0' value='筛选条件' disabled=true style='width:73px;border:0px;text-align:center' name='jo[]'>");
					}
				})
				$.each($("#tbone tr a"),function(i,result){
					$(this).attr("href","javascript:del("+(i+1)+")");
				})
				//$("#number2").attr("value",(number2-1));
			}else if(num<count){
				//alert($("input[name='number[]']")[num].nextAll().length);
				$.each($("input[name='number[]']"),function(i,result){
					if(i==num-1){
						var obj = $(this).parent().parent().parent().nextAll();
						//$("#tb tr")[number].remove();
						$.each($("#tbone tr"),function(i,result){
							if(i == num-1){
								$(this).remove();
							}
						})
						$.each(obj,function(j,res){
							var objnum = $(this).children().first().next().children().children().attr("value");
							$(this).children().first().next().children().children().attr("value",objnum-1);
							//alert($(this).children().last().children().attr("href"));
							$(this).children().last().children().attr("href","javascript:del("+(objnum-1)+")");
							//alert($(this).getChildern("input[name='number[]']"));
						})
					}
				})
				//$("#number2").attr("value",(number2-1));
			}else{
				//$("#tb tr")[number].remove();
				$.each($("#tbone tr"),function(i,result){
					if(i == num-1){
						$(this).remove();
					}
				})
				//$("#number2").attr("value",(number2-1));
			}
		}
	}
</script>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="/createtem/firstpage" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#" class="">联系人筛选</a><a href="#" class="current">{%if $id %}编辑联系人筛选{%else%}添加联系人筛选{%/if%}</a> </div>
	</div>
	<div class="container-fluid">
		<div class="widget-box" style="margin-top: 35px;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>{%if $id %}编辑联系人筛选{%else%}添加联系人筛选{%/if%}</h5>
			</div>
			<div class="widget-content">
			<form action="/contact/doaddfilter" method="post" id="myform">
				<div>
					<span style="height:25px;background-color:#CCCCCC"><h5>筛选器信息</h5></span>
					<div>
						<table>
							<tr>
								<td>筛选器名称：</td>
								<td><input type="text" style="width: 280px;" id="filtername" name="filtername" {%if $result.name != "" %}value="{%$result.name%}"{%/if%}>&nbsp;&nbsp;<span style="color:red;">*</span></td>
							</tr>
							<tr>
								<td valign="top">描述：</td>
								<td><textarea rows="5" style="width: 280px;" cols="10" name="description">{%if $result.description != "" %}{%$result.description%}{%/if%}</textarea></td>
							</tr>
						</table>
					</div>
				</div>
				<p></p>
				<div>
					<h5>筛选器条件</h5>
					
					<div>
						<table width="100%" class="table table-bordered table-striped" id="tbone">
							{%if $id != ""%}
								{%section name=loop loop=$data%}
									<tr>
										<td width='10%' style="text-align:center">
											<!-- {%if $data[loop].joiname != "" || $data[loop].num != 1%}
												<select name="jo[]" style='width:73px;'>
													<option value="2" {%if $data[loop].join == 2%}selected="selected"{%/if%}>与(and)</option>
													<option value="1" {%if $data[loop].join == 1%}selected="selected"{%/if%}>或(or)</option>
												</select>
											{%else%}
												<input type="text" value="筛选条件" readonly="readonly" style="width:73px;align:center"/>
											{%/if%} -->
											{%if $data[loop].num == 1%}
												<input type="text" value="筛选条件" readonly="readonly" style="width:60px;align:center;border:0px;font-size:12px"/>
											{%else%}
												<select name="jo[]" style='width:73px;font-size:12px'>
													<option value="2" {%if $data[loop].join == 2%}selected="selected"{%/if%}>与(and)</option>
													<option value="1" {%if $data[loop].join == 1%}selected="selected"{%/if%}>或(or)</option>
												</select>
											{%/if%}
										</td>
										<td width='10%' style="text-align:center"><b><input type="text" value="{%$data[loop].num%}" disabled=true style="width:73px;border:0px;text-align:center;font-size:12px" name="number[]"></b></td>
										<td width='18%' style="text-align:center;">
											<select name="showname[]" id="sname{%$data[loop].num%}" onchange="changevalue('sname{%$data[loop].num%}','oprator{%$data[loop].num%}','text{%$data[loop].num%}')" style="width:150px;font-size:12px">
												{%section name=loop2 loop=$arr%}
													<option value="{%$arr[loop2].id%}" type="{%$arr[loop2].type%}" {%if $arr[loop2].id == $data[loop].field%} selected="selected" {%/if%}>{%$arr[loop2].showname%}</option>
												{%/section%}
											</select>
										</td>
										<td width='15%' style="text-align:center">
											<select name="opra[]" id="oprator{%$data[loop].num%}" onchange="changetime('sname{%$data[loop].num%}','oprator{%$data[loop].num%}','text{%$data[loop].num%}')" style="width:120px;font-size:12px">
												{%if $data[loop].type == 1 %}
													<option value="0" {%if $data[loop].operator == 0%}selected="selected"{%/if%}>是</option>
													<option value="1" {%if $data[loop].operator == 1%}selected="selected"{%/if%}>否</option>
													<option value="2" {%if $data[loop].operator == 2%}selected="selected"{%/if%}>包含</option>
													<option value="3" {%if $data[loop].operator == 3%}selected="selected"{%/if%}>不包含</option>
													<option value="4" {%if $data[loop].operator == 4%}selected="selected"{%/if%}>开始字符</option>
													<option value="5" {%if $data[loop].operator == 5%}selected="selected"{%/if%}>结束字符</option>
													<option value="300" {%if $data[loop].operator == 300%}selected="selected"{%/if%}>为空</option>
													<option value="301" {%if $data[loop].operator == 301%}selected="selected"{%/if%}>不为空</option>
												{%else if $data[loop].type == 2 %}
													{%if $data[loop].fieldname == "性别"%}
														<option value="0" {%if $data[loop].operator == 0%}selected="selected"{%/if%}>是</option>
														<option value="1" {%if $data[loop].operator == 1%}selected="selected"{%/if%}>否</option>
														<option value="300" {%if $data[loop].operator == 300%}selected="selected"{%/if%}>为空</option>
														<option value="301" {%if $data[loop].operator == 301%}selected="selected"{%/if%}>不为空</option>
													{%else%}
														<option value="100" {%if $data[loop].operator == 100%}selected="selected"{%/if%}>等于</option>
														<option value="101" {%if $data[loop].operator == 101%}selected="selected"{%/if%}>不等于</option>
														<option value="102" {%if $data[loop].operator == 102%}selected="selected"{%/if%}>大于</option>
														<option value="103" {%if $data[loop].operator == 103%}selected="selected"{%/if%}>不大于</option>
														<option value="104" {%if $data[loop].operator == 104%}selected="selected"{%/if%}>小于</option>
														<option value="105" {%if $data[loop].operator == 105%}selected="selected"{%/if%}>不小于</option>
														<option value="300" {%if $data[loop].operator == 300%}selected="selected"{%/if%}>为空</option>
														<option value="301" {%if $data[loop].operator == 301%}selected="selected"{%/if%}>不为空</option>
													{%/if%}
												{%else if $data[loop].type == 3 %}
													<option value="200" {%if $data[loop].operator == 200%}selected="selected"{%/if%}>之前</option>
													<option value="201" {%if $data[loop].operator == 201%}selected="selected"{%/if%}>之后</option>
													<option value="202" {%if $data[loop].operator == 202%}selected="selected"{%/if%}>介于</option>
													<option value="203" {%if $data[loop].operator == 203%}selected="selected"{%/if%}>不介于</option>
													<option value="300" {%if $data[loop].operator == 300%}selected="selected"{%/if%}>为空</option>
													<option value="301" {%if $data[loop].operator == 301%}selected="selected"{%/if%}>不为空</option>
												{%else%}
													<option value="102" {%if $data[loop].operator == 102%}selected="selected"{%/if%}>大于</option>
													<option value="104" {%if $data[loop].operator == 104%}selected="selected"{%/if%}>小于</option>
													<option value="300" {%if $data[loop].operator == 300%}selected="selected"{%/if%}>为空</option>
													<option value="301" {%if $data[loop].operator == 301%}selected="selected"{%/if%}>不为空</option>
												{%/if%}
											</select>
										</td>
										<td width='25%' style="text-align:center" id="text{%$data[loop].num%}">
										{%if $data[loop].type == 3%}
											{%if $data[loop].opratorname == '之前' || $data[loop].opratorname == '之后' %}
												<div class='input-append date form_date'><input name='birth' onclick='calendar()' size='16' type='text' value="{%$data[loop].value%}" ><span class='add-on'><i class='icon-th'></i></span></div>	
											{%else if $data[loop].opratorname == '介于' || $data[loop].opratorname == '不介于'%}
												<div class='input-append date form_date'><input style='width:206px' name='birth1' onclick='calendar()' size='16' type='text' value="{%$data[loop].value1%}" ><span class='add-on'><i class='icon-th'></i></span></div><div>--</div><div class='input-append date form_date'><input style='width:206px' name='birth2' onclick='calendar()' size='16' type='text' value="{%$data[loop].value2%}" ><span class='add-on'><i class='icon-th'></i></span></div>
											{%/if%}
										{%else%}
											{%if $data[loop].opratorname != "为空" && $data[loop].opratorname != "不为空"%}
												<input type="text" value="{%$data[loop].value%}" name='ti[]' style="width:227px;font-size:12px">
											{%/if%}
										{%/if%}
										</td>
										{%if $data[loop].num == 1%}
											<td width='15%' style="text-align:center"><a class='add icon-plus btn' href='javascript:addcon()'></a></td>
										{%else%}
											<td width='15%' style="text-align:center"><a class='add icon-plus btn btn-small' href='javascript:addcon()'></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:del({%$data[loop].num%})' class='icon-minus btn btn-small'></a></td>
										{%/if%}
									</tr>
								{%/section%}
							{%/if%}
						</table>
					</div>
					{%if $id != ""%}
						<input type='hidden' name='id' value="{%$id%}" />
					{%/if%}
					<input type="hidden" name="numstr" value="" id="numstr" />
					<input type="hidden" name="jostr" value="" id="jostr" />
					<input type="hidden" name="sname" value="" id="sname" />
					<input type="hidden" name="ti" value="" id="ti" />
					<input type="hidden" name="opra" value="" id="opra" />
				</div>
				<!-- <div> 
					<h5>条件样式</h5>
					<div>
						<textarea rows="4" cols="10" style="width:500px" id="sty" name="sty">{%if $result.condition%}{%$result.condition%}{%/if%}</textarea>
					</div>
					<div>请设置完全部条件后再修改条件样式</div>
				</div>-->
				<div>
					<!--<input type="button" id="add" class="btn add" style="color:#CC0000;border-radius: 3px; padding-top: 5px;" value="+添加条件" />-->
				</div>
				<div style="margin-top: 10px;">
					<input type="button" id="btn" value="保存" class="btn" style="border-radius: 3px; padding-top: 5px;"/>
				</div> 
			</form>
		</div>
	</div>
</div>
</div>
<input type="hidden" value="{%$per_num%}" id="per_num" />
<input type="hidden" value="{%$search_cont%}" id="search_cont" />
<input type="hidden" value="{%$total_page%}" id="total_page" />
<input type="hidden" value="{%$cur_page%}" id="cur_page" />
<input type="hidden" value="{%$page_type%}" id="page_type" />
<input type="hidden" value="" id="checklist" name="checklist" />
<!--Footer-part-->
<script type="text/javascript">
function calendar(){
	$('.form_date').datetimepicker({
	    format: "yyyy-mm-dd",
	    autoclose: true,
	    todayBtn: true,
	    weekStart: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
	    pickerPosition: "bottom-left"
	});
}
function checkValue(type){
	if($("#str").val() == "" || $("input[name='birth']").val() == "" || $("input[name='birth1']").val() == "" || $("input[name='birth2']").val() == ""){
		art.dialog.alert("请设置参数");
		return false;
	}
	if(type == 2){
		var showname = $("#showname").val();
		if(showname == 3){
			if($("#str").val()){
				if($("#str").val() != "男" && $("#str").val() != "女"){
					art.dialog.alert("请输入正确的性别");
					return 0;
				}
			}
		}
	}else if(type == 3){
		var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
		if($("input[name='birth1']").val()){
			if(!reg.test($("input[name='birth1']").val())){
				art.dialog.alert("请保证输入日期格式为yyyy-mm-dd");
				return 0;
			}
		}
		if($("input[name='birth2']").val()){
			if(!reg.test($("input[name='birth2']").val())){
				art.dialog.alert("请保证输入日期格式为yyyy-mm-dd");
				return 0;
			}
		}
		if($("input[name='birth']").val()){
			if(!reg.test($("input[name='birth']").val())){
				art.dialog.alert("请保证输入日期格式为yyyy-mm-dd");
				return 0;
			}
		}
	}else if(type == 4){
		if($("#str").val()){
			if(isNaN($("#str").val())){
				art.dialog.alert("请设置正确的参数格式");
				return 0;
			}
		}
	}else{
		var name = $("#showname").val();
		var op = $("#op").val();
		var search_str = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
		var telrule = /^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8}$/;
		if(name == 2){
			if(op == 0 || op == 1){
				if(!search_str.test($("#str").val())){
					art.dialog.alert("请设置正确的邮箱格式");
					return 0;
				}
			}
		}else if(name == 5){
			if(op == 0 || op == 1){
				if(!telrule.test($("#str").val())){
					art.dialog.alert("请设置正确的手机号码");
					return 0;
				}
			}
		}
	}
}
</script>
{%include file="footer.php"%}