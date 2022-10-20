$(document).ready(function(){
	var per_num = $("#per_num").val();//从controller传来的值
	var search_cont = $("#search_cont").val();//从controller传来的值
	var total_page = $("#total_page").val();//从controller传来的值
	if (total_page == 0 || total_page == "" || total_page == null) {
		$("#hasnodata").removeClass("hasnodata");
	}
	$("#hdmcsearch").val(search_cont);
	$("#pernumselect").val(per_num);
	$("#searchbtn").click(function(){//#searchbtn 搜索按钮
		var search_cont = $("#hdmcsearch").val();//#hdmcsearch 搜索框
		window.location.href = "/templet/index?per_num=" + per_num + "&search_cont=" + search_cont;
	});
	
	
});
function subspernum (val) {
	var per_num = document.getElementById("per_num").value;
	if (per_num == "" || per_num == null) {
		per_num = 10;
	}
	if (val != "" && val != null) {
		per_num = val;
	}
	var target = document.getElementById("hdmcsearch");
	var search_cont = "";
	if (target != null) {
		search_cont = target.value;
	}
	window.location.href = "/templet/index?per_num=" + per_num + "&search_cont=" + search_cont;
}
function goPage (type) {
	var target = document.getElementById("cur_page");
	var cur_page = 1;
	var url = "?";
	if (target != null) {
		cur_page = target.value;
	}
	url += "cur_page="+cur_page+"&";
	var per_num = 10;
	target = document.getElementById("per_num");
	if (target != null) {
		per_num = target.value;
	}
	url += "per_num="+per_num+"&";
	var search_cont = "";
	target = document.getElementById("search_cont");
	if (target != null) {
		search_cont = target.value;
	}
	url += "search_cont="+search_cont+"&";
	switch (type) {
		case "first":
			url += "page_type=first";
			break;
		case "pre":
			url += "page_type=pre";
			break;
		case "next":
			url += "page_type=next";
			break;
		case "last":
			url += "page_type=last";
			break;
		default:
			break;
	}
	window.location.href = "/templet/index"+url;
}
function aaaaa () {
	var a = document.getElementById("checklist").value;
	var b = "";
	if (a == "") {
		var mybox = $('tr td:first-child input:checkbox');	
		mybox.each(function() {
			if (this.checked == true) {
				b += this.value+"@";
			}
		});
	}
	alert(b);
}






















