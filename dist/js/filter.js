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

//	function changevalue(str1,str2,str3){
//		//alert($("#op option:selected").text());
//		var type = $("#"+str1+" option:selected").attr("type");
//		if($("#"+str2+" option:selected").text() == "为空" || $("#"+str2+" option:selected").text() == "不为空"){
//			$("#"+str3).empty();
//		}else{
//			if(type == 3){
//				if($("#"+str2+" option:selected").text() == "介于" || $("#"+str2+" option:selected").text() == "不介于"){
//					var time = "<div class='input-append date form_date'><input style='width:206px' name='birth1' onclick='calendar()' size='16' type='text' value='' ><span class='add-on'><i class='icon-th'></i></span></div><div>--</div><div class='input-append date form_date'><input style='width:206px' name='birth2' onclick='calendar()' size='16' type='text' value='' ><span class='add-on'><i class='icon-th'></i></span></div>";
//				}else{
//					var time = "<div class='input-append date form_date'><input name='birthday' onclick='calendar()' size='16' type='text' value='' ><span class='add-on'><i class='icon-th'></i></span></div>";
//				}
//				$("#"+str3).empty();
//				$("#"+str3).append(time);
//				//$("#sp div").addClass("form_date");
//			}else{
//				$("#"+str3).empty();
//				var str = "<input type='text' id='str' name='str' />";
//				$("#"+str3).append(str);
//			}
//		}	
//	}
	
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
			pretermit += "<td width='25%' style='text-align:center' id='text"+(num+1)+"'><input type='text' value='' style='border:1px solid #ccc;width:227px;font-size:12px' name='str[]'></td><td width='15%' style='text-align:center'><a class='add icon-plus btn btn-small' href='javascript:addcon()'></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:del("+(num+1)+")' class='icon-minus  btn btn-small'></a></td></tr>";
			$("#tbone").append(pretermit);
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
			var pretermit = "";
			num = $("#tbone").children().children().length;
			if(num > 1){
				
			}
			pretermit += "<tr><td width='10%' style='text-align:center'><select name='jo[]' style='width:80px'><option value='2'>与(and)</option><option value='1'>或(or)</option></select></td>";
			pretermit += "<td width='10%' style='text-align:center'><b><input type='text' value='"+(num+1)+"' disabled=true style='width:73px;border:0px;text-align:center;' name='number[]'></b></td>";
			pretermit += "<td width='18%' style='text-align:center'><select name='showname[]' style='width:150px' onchange='changevalue(\"selopr"+(num+1)+"\",\"opra"+(num+1)+"\",\"text"+(num+1)+"\")' id='selopr"+(num+1)+"'>";
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
			pretermit += "<td width='15%' style='text-align:center'><select name='opra[]' onchange='changetime(\"selopr"+(num+1)+"\",\"opra"+(num+1)+"\",\"text"+(num+1)+"\")' style='width:120px' id='opra"+(num+1)+"'>";
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
			pretermit += "<td width='30%' style='text-align:center' id='text"+(num+1)+"'><input type='text' value='' style='border:1px solid #ccc;width:227px;' name='str[]'></td><td width='15%' style='text-align:center'><a class='add icon-plus btn-small' href='javascript:addcon()'></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:del("+(num+1)+")' class='icon-minus btn-small'></a></td></tr>";
			//$("#tbone").append(pretermit);
			var jiansuo = "<select name='special_relation' style='width:80px;' id='jo'><option value='2'>与(and)</option><option value='1'>或(or)</option></select>&nbsp;&nbsp;"
			$("#jiansuo").empty();
			$("#jiansuo").html(jiansuo);
			var arr = new Array();
			var brr = new Array();
			var crr = new Array();
			arr = {"0":"是","1":"否","2":"包含","3":"不包含","4":"开始字符","5":"结束字符"};
			brr = {"100":"等于","101":"不等于","102":"大于","103":"不大于","104":"小于","105":"不小于"};
			crr = {"200":"之前","201":"之后","202":"介于","203":"不介于"};
			if($("#str").val() == "" || $("input[name='birthday']").val() == "" || $("input[name='birth1']").val() == "" || $("input[name='birth2']").val() == ""){
				art.dialog.alert("请设置参数的值");
				$("#str").focus();
				return false;
			}
			var showname = $("#showname option:selected").val();
			var type = $("#showname option:selected").attr("type");
			if(type == 4){
				if($("#str").val()){
					if(isNaN($("#str").val())){
						art.dialog.alert("请设置正确的参数格式");
						return false;
					}
				}
			}else if(type == 3){
				var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
				if($("input[name='birth1']").val()){
					if(!reg.test($("input[name='birth1']").val())){
						art.dialog.alert("请保证输入日期格式为yyyy-mm-dd");
						return false;
					}
				}
				if($("input[name='birth2']").val()){
					if(!reg.test($("input[name='birth2']").val())){
						art.dialog.alert("请保证输入日期格式为yyyy-mm-dd");
						return false;
					}
				}
				if($("input[name='birthday']").val()){
					if(!reg.test($("input[name='birthday']").val())){
						art.dialog.alert("请保证输入日期格式为yyyy-mm-dd");
						return false;
					}
				}
			}else if(type == 2){
				if(showname == 3){
					if($("#str").val()){
						if($("#str").val() != "男" && $("#str").val() != "女"){
							art.dialog.alert("请输入正确的性别");
							return false;
						}
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
			var opr = $("#op option:selected").val();
			var app = "";
			var num = "";
			num = $("#tb tr").length;
			var dataobj = "";
			if($("#tb tr").text() == ""){
				app += "<tr><td width='10%' style='text-align:center'><input type='text' custom='0' value='检索条件' disabled=true style='width:73px;border:0px' name='jo[]'></td>";
				app += "<td width='10%' style='text-align:center'><b><input type='text' value="+(num+1)+" disabled=true style='width:73px;border:0px' name='number[]'></b></td>";
			}else{
				app += "<tr><td width='10%' style='text-align:center'><select name='jo[]' style='width:80px'><option value='2'>与(and)</option><option value='1'>或(or)</option></select></td>";
				app += "<td width='10%' style='text-align:center'><b><input type='text' value="+(num+1)+" disabled=true style='width:73px;border:0px' name='number[]'></b></td>";
			}
			app += "<td width='18%' style='text-align:center'><select name='showname[]' id='sn"+(num+1)+"' onchange=\"oprator('sn"+(num+1)+"','op"+(num+1)+"')\" style='width:150px'>";
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
				if(result.id == showname){
					app += "<option value="+result.id+" type="+result.type+" selected='selected'>"+result.showname+"</option>";
				}else{
					app += "<option value="+result.id+" type="+result.type+">"+result.showname+"</option>";
				}
			})
			app += "</select></td>";
			app += "<td width='15%' style='text-align:center'><select name='opra[]' style='width:120px' id='op"+(num+1)+"'>";
			if(type == 1){
				$.each(arr,function(i,result){
					if(opr == i){
						app += "<option value='"+i+"' selected='selected'>"+result+"</option>";
					}else{
						app += "<option value='"+i+"'>"+result+"</option>";
					}
				})
				if(opr == 300){
					app += "<option value='300' selected='selected'>为空</option>";
				}else{
					app += "<option value='300'>为空</option>";
				}
				if(opr == 301){
					app += "<option value='301' selected='selected'>不为空</option>";
				}else{
					app += "<option value='301'>不为空</option>";
				}
				//app += "<option value='300'>为空</option><option value='301'>不为空</option>";	
			}else if(type == 2){
				//alert($("#showname option:selected").val());
				if($("#showname option:selected").val() == 3){
					if(opr == 0){
						app += "<option value='0' selected='selected'>是</option>";
					}else{
						app += "<option value='0'>是</option>";
					}
					if(opr == 1){
						app += "<option value='1' selected='selected'>否</option>";
					}else{
						app += "<option value='1'>否</option>";
					}
					//app += "<option value='0'>是</option><option value='1'>否</option>";
				}else{
					$.each(brr,function(i,result){
						if(opr == i){
							app += "<option value='"+i+"' selected='selected'>"+result+"</option>";
						}else{
							app += "<option value='"+i+"'>"+result+"</option>";
						}
					})
				}
				if(opr == 300){
					app += "<option value='300' selected='selected'>为空</option>";
				}else{
					app += "<option value='300'>为空</option>";
				}
				if(opr == 301){
					app += "<option value='301' selected='selected'>不为空</option>";
				}else{
					app += "<option value='301'>不为空</option>";
				}
				//app += "<option value='300'>为空</option><option value='301'>不为空</option>";	
			}else if(type == 3){
				$.each(crr,function(i,result){
					if(opr == i){
						app += "<option value='"+i+"' selected='selected'>"+result+"</option>";
					}else{
						app += "<option value='"+i+"'>"+result+"</option>";
					}
				})
				if(opr == 300){
					app += "<option value='300' selected='selected'>为空</option>";
				}else{
					app += "<option value='300'>为空</option>";
				}
				if(opr == 301){
					app += "<option value='301' selected='selected'>不为空</option>";
				}else{
					app += "<option value='301'>不为空</option>";
				}
				//app += "<option value='300'>为空</option><option value='301'>不为空</option>";
			}else{
				if(opr == 102){
					app += "<option value='102' selected='selected'>大于</option>";
				}else{
					app += "<option value='102'>大于</option>";
				}
				if(opr == 104){
					app += "<option value='104' selected='selected'>小于</option>";
				}else{
					app += "<option value='104'>小于</option>";
				}
				if(opr == 300){
					app += "<option value='300' selected='selected'>为空</option>";
				}else{
					app += "<option value='300'>为空</option>";
				}
				if(opr == 301){
					app += "<option value='301' selected='selected'>不为空</option>";
				}else{
					app += "<option value='301'>不为空</option>";
				}
			}
			app += "</select></td>";
//			app += "<td width='18%'><input type='text' custom='"+cshowname+"' value='"+showname+"' disabled=true style='width:73px;border:0px' name='showname[]'></td>";
//			app += "<td width='15%'><input type='text' custom='"+coprator+"' value='"+oprator+"' disabled=true style='width:73px;border:0px' name='opra[]'></td>";
			if($("#op option:selected").text() == "介于" || $("#op option:selected").text() == "不介于"){
				var time1 = $("input[name='birth1']").val();
				var time2 = $("input[name='birth2']").val();
				var time = time1+"至"+time2;
				app += "<td width='30%' style='text-align:center'><input type='text' value='"+time+"' style='border:0px' name='ti[]'></td><td width='15%' style='text-align:center'><a href='javascript:del("+(num+1)+")'>删除</a></td></tr>";
				
			}else if($("#op option:selected").text() == "之前" || $("#op option:selected").text() == "之后"){
				var time = $("input[name='birthday']").val();
				app += "<td width='30%' style='text-align:center'><input type='text' value='"+time+"' style='border:0px' name='ti[]'></td><td width='15%' style='text-align:center'><a href='javascript:del("+(num+1)+")'>删除</a></td></tr>";
			}else if($("#op option:selected").text() == "为空" || $("#op option:selected").text() == "不为空"){
				app += "<td width='30%' style='text-align:center'><input type='text' value='' style='border:0px' name='ti[]'></td><td width='15%' style='text-align:center'><a href='javascript:del("+(num+1)+")'>删除</a></td></tr>";
			}else{
				var str = $("#str").val();
				app += "<td width='30%' style='text-align:center'><input type='text' value='"+str+"' style='border:0px' name='ti[]'></td><td width='15%' style='text-align:center'><a href='javascript:del("+(num+1)+")'>删除</a></td></tr>";
			}
			$("#tb").append(app);
			//$("#number2").empty();
			$("#number2").attr("value",(num+2));
		})
	})
	
	function del(num){
		if($("#tbone tr").length>1){
			// alert(num);
			var count = $("#tbone tr").length;
			// alert(count);
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
	
	function filtersearch(str){
		// 获取当前选中的分页数
		var page = $("select[name='num'] option:selected").val()
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
		var type = "";
		var sname2 = new Array();
		var type2 = new Array();
 		$.each($("select[name='showname[]'] option:selected"),function(i,result){
			sname += $(this).attr("value")+",";
			sname2[i] = $(this).attr("value");
			type2[i] = $(this).attr("type");
		})
		var ti = "";
		$.each($("input[name='number[]']"),function(i,result){
			
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
		$.each($("select[name='opra[]']"),function(i,result){
			//alert($(this).attr("value"));
			opra += $(this).attr("value")+",";
			opra2[i] = $(this).attr("value");
		})
		var type = "";
		$.each($("select[name='showname[]'] option:selected"),function(){
			type += $(this).attr("type")+",";
		})
		var TAG = true;
		$.each(numstr2,function(i,result){
			if( ti2[i] == "" ){
				art.dialog.alert("请输入搜索条件");
				TAG = false;
			}
			if(type2[i] == 1){
				if(sname2[i] == 2){
					var search_str = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
					if(opra2[i] == 0 || opra2[i] == 1){
						if(!search_str.test(ti2[i])){
							art.dialog.alert("请输入正确的邮箱格式");
							TAG = false;
						}
					}
				}else if(sname2[i] == 5){
					var telrule = /^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8}$/;
					if(opra2[i] == 0 || opra2[i] == 1){
						if(!telrule.test(ti2[i])){
							art.dialog.alert("请输入正确的手机格式");
							TAG = false;
						}
					}
				}
			}else if(type2[i] == 2){
				if(sname2[i] == 3){
					if(ti2[i]){
						if(ti2[i] != "男" && ti2[i] != "女" && ti2[i] != "undefined"){
							art.dialog.alert("请输入正确的性别");
							TAG = false;
						}
					}
				}
			}else if(type2[i] == 3){
				var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
				var time = ti2[i].split("至");
				$.each(time,function(j,res){
					if(time[j] != "" && time[j] != "undefined"){
						if(!reg.test(time[j])){
							art.dialog.alert("请保证输入日期格式为yyyy-mm-dd");
							TAG = false;
						}
					}
				})
				}else if(type2[i] == 4){
				if(!$.isNumeric(ti2[i])){
					art.dialog.alert("请输入正确的数字");
					TAG = false;
				}
			}
		})
		$("#numstr2").attr("value",numstr);
		$("#jostr2").attr("value",jostr);
		$("#sname2").attr("value",sname);
		$("#ti2").attr("value",ti);
		$("#opr2").attr("value",opra);
		$("#type2").attr("value",type);
		$("#num2").attr("value",page);
		if(TAG){
			$("#mf").submit();
		}
	}
	
	function checkValue(type){
		if($("#str").val() == "" || $("input[name='birthday']").val() == "" || $("input[name='birth1']").val() == "" || $("input[name='birth2']").val() == ""){
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
			if($("input[name='birthday']").val()){
				if(!reg.test($("input[name='birthday']").val())){
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
	
	$(function(){
		$("#btn2").click(function(){
			var filtername = $("#filtername").val();
			if($("#filtername").val() == ""){
				alert("筛选器名称不能为空");
				$("#filtername").focus();
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
			//jostr += $("#jo option:selected").val()+",";
			var sname = "";
			var sname2 = new Array();
			var type = new Array();
			$.each($("select[name='showname[]'] option:selected"),function(i,result){
				sname += $(this).attr("value")+",";
				sname2[i] = $(this).attr("value");
				type[i] = $(this).attr("type");
			})
			//sname += $("#showname option:selected").val()+",";
			var ti = "";
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
			$.each($("select[name='opra[]']"),function(i,result){
				//alert($(this).attr("value"));
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
								$("#myModal6").hide();
								art.dialog.alert("请输入正确的邮箱格式",function(){$("#myModal6").show()});
								//art.dialog.alert("请输入正确的邮箱格式");
								TAG = false;
								//return true;
							}
						}
					}else if(sname2[i] == 5){
						var telrule = /^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8}$/;
						if(opra2[i] == 0 || opra2[i] == 1){
							if(!telrule.test(ti2[i])){
								$("#myModal6").hide();
								art.dialog.alert("请输入正确的手机格式",function(){$("#myModal6").show()});
								//art.dialog.alert("请输入正确的手机格式");
								TAG = false;
								//return true;
							}
						}
					}
				}else if(type[i] == 2){
					if(sname2[i] == 3){
						if(ti2[i] != "男" && ti2[i] != "女"){
							$("#myModal6").hide();
							art.dialog.alert("请输入正确的性别",function(){$("#myModal6").show()});
							//art.dialog.alert("请输入正确的性别");
							TAG = false;
							//return true;
						}
					}
				}else if(type[i] == 3){
					var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
					var time = ti2[i].split("至");
					$.each(time,function(j,res){
						if(time[j] != ""){
							if(!reg.test(time[j])){
								$("#myModal6").hide();
								art.dialog.alert("请保证输入日期格式为yyyy-mm-dd",function(){$("#myModal6").show()});
								//art.dialog.alert("请保证输入日期格式为yyyy-mm-dd");
								TAG = false;
								//return true;
							}
						}
					})
 				}else if(type[i] == 4){
					if(!$.isNumeric(ti2[i])){
						$("#myModal6").hide();
						art.dialog.alert("请输入正确的数字",function(){$("#myModal6").show()});
						//art.dialog.alert("请输入正确的数字");
						TAG = false;
						//return true;
					}
				}
			})
			$("#numstr").attr("value",numstr);
			$("#jostr").attr("value",jostr);
			$("#sname").attr("value",sname);
			$("#ti").attr("value",ti);
			$("#opra").attr("value",opra);
			$.post("/contact/ajaxfiltername",{'fname':filtername},function(data){
				if(data == 1){
					$('#myModal6').modal('hide');
					art.dialog.alert("筛选器名称已经存在");
					return false;
				}
				if(TAG){
					$("#myform3").submit();
				}
			});
		})
	})
	
	$(function(){
		$("#addall").click(function(){
			//alert(1111);
			var ids = "";
			var ck = $("input[name='ck[]']:checked");
			$.each(ck,function(){
				ids += $(this).val()+",";
			})
			//$("input[name='ids']").attr("value",ids);
			if(ids == ""){
				art.dialog.alert("请选择批量添加的成员");
				//$("#myModal5").hide();
				//art.dialog.alert("请选择批量添加的成员",function(){$("#myModal5").show()});
				return false;
			}
		})
		//$("input[name='gpid']").attr("value",$("#sel2 option:selected").val());
		$("#btn4").click(function(){
			var gname = $("input[name='newgroup']").val();//alert(gname);
			if(gname == ""){
				$("#tx").text("");
			}
			var gids = "";
			$.each($("input[name='gname[]']:checked"),function(i,result){
				gids += $(this).val()+",";
			})
			$("input[name='gpid']").attr("value",gids);
			var uids = "";
			var ck = $("input[name='ck[]']:checked");
			$.each(ck,function(i,result){
				uids += $(this).val()+",";
			})
			if($("#all").attr("checked") != "checked"){
				$("input[name='ids']").attr("value",uids);
			}
			if(gname == "" && gids == ""){
				$("#myModal5").hide();
				art.dialog.alert("请选择组或新建组",function(){$("#myModal5").show()});
				return false;
			}
/* 			var ck = $("input[name='ck[]']:checked");
			var ids = "";
			$.each(ck,function(){
				ids += $(this).val()+",";
			})
			$("input[name='ids']").attr("value",ids);
			if(ids == ""){
				$("#myModal5").hide();
				art.dialog.alert("请选择批量添加的成员",function(){$("#myModal5").show()});
				return false;
			} */
			$.ajax({
				type:"post",
				url:"ajaxgroup",
				data:{
					'gname':gname
				},
				dataType:"json",
				success:function(data){
					if(data == 1){
						$("#tx").text("该组已经存在");
						$("#tx").attr("class","col");
						return false;
					}else{
						if(gname){
							$("#tx").text("组名称合法");
							$("#tx").removeClass("col");
						}
						$("#myform4").submit();
					}
				}
			})
		})
	})
	
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
	
	$(function(){
		$("#reset").click(function(){
			window.location.href="/contact/personlist";
		})
		$("#btn6").click(function(){
			
		})
	})
	
	
	
	
	
	
	
	
	
	