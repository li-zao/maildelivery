
$(document).ready(function(){
	var total_page = $("#total_page").val();
	var cur_page = $("#cur_page").val();
	if (total_page == 'undefined' || total_page == ""  || total_page == null) {
		total_page = "0";
	}
	if (cur_page == 'undefined' || cur_page == "" || cur_page == null) {
		cur_page = "0";
	}
	$('.data-table').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"sDom": '<""l>t<"F"fp>',
		"oLanguage": {
			"sLengthMenu": "每页 _MENU_ 条"
		}
	});
	
	$("#title-table-checkbox").click(function() {
		var value_str = "";
		var checkedStatus = this.checked;
		var checkbox = $(this).parents('.widget-box').find('tr td:first-child input:checkbox');		
		checkbox.each(function() {
			this.checked = checkedStatus;
			if (checkedStatus == this.checked) {
				$(this).closest('.checker > span').removeClass('checked');
			}
			if (this.checked) {
				$(this).closest('.checker > span').addClass('checked');
				value_str += this.value+"@";
			}
		});
		$("#checklist").val(value_str);
	});	
});
