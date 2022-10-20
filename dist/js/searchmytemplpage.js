$(function(){
	if($.browser.msie){
		$('input:checkbox').click(function(){
			this.blur();
			this.focus();
		})
	}	

	// }
	$('.cs').change(function(){
			var id="";
			$('input[type=checkbox]:checked').each(function(){
					 id=$(this).val()+","+id;
			});
			if(id!=""){
			 $.post('/templet/searchvo',{"ids":id},function(data){
					 	$('#ajaxHtml').children().remove();
					 	$('#ajaxHtml').append(data);
					 });
			}else{
				location.reload();
			}
	});


});


function goPages(currentpage){
		cp=currentpage;
		var id="";
			$('input[type=checkbox]:checked').each(function(){
					 id=$(this).val()+","+id;
			});
			if(id!=""){
				$.post('/templet/searchvo',{'currentPage':cp,'ids':id},function(data){
					 	$('#ajaxHtml').children().remove();
					 	$('#ajaxHtml').append(data);
				})
			}
		

}

$(function(){
	$('dl').mouseover(function(){
		$(this).css('border','3px solid #46BCF2').next().css('display','block').hover(
			function(){
				$(this).css('display','block').prev().css('border','3px solid #46BCF2');
			},
			function(){
				
			}
		);
	});
	$('dl').mouseout(function(){
		$(this).css('border','1px solid #ccc');
		$(this).next().css('display','none');
	});
});

$(function(){
	$('#load_public_template').click(function(){
		var style=-1;
		$.post('/task/selecttpl',{'style_id':style},function(data){
				if(data){
					$('#replace_tpl').children().remove();
					$('#replace_tpl').append(data);
				}
		});
	});

	$('#default').click(function(){
		var style=-1;
		$.post('/task/selecttpl',{'style_id':style},function(data){
				if(data){
					$('#replace_tpl').children().remove();
					$('#replace_tpl').append(data);
				}
		});
	});
	$('#default2').click(function(){
		var style=-2;
		$.post('/task/selecttpl',{'style_id':style},function(data){
				if(data){
					$('#replace_tpl').children().remove();
					$('#replace_tpl').append(data);
				}
		});
	})
	$('.default3').click(function(){
		var style=$(this).attr('style');
		// alert(style);
		$.post('/task/selecttpl',{'style_id':style},function(data){
				if(data){
					$('#replace_tpl').children().remove();
					$('#replace_tpl').append(data);
				}
		});
	});;
});

function selPages(currentpage){
		cp=currentpage;
		var id=$('#sel_tpl').attr('style');;
			if(id!=""){
				$.post('/task/selecttpl',{'currentPage':cp,'style_id':id},function(data){
					 	$('#replace_tpl').children().remove();
					 	$('#replace_tpl').append(data);
				})
			}
}

/*选择附件*/
$(function(){
   $('#attachments').click(function(){
			$('#attachs').on('shown',function(){
			$(function(){
				$("div.holder").jPages("destroy");
				$('#attachmentlist').show();
				$("div.holder").jPages({
					containerID:"attachmentlist",
					perPage    : 5,
					first      : "首 页", 
					previous : "上一页",
					next : "下一页",
					last   :"尾 页",
					delay      : 0,
					callback   : function(pages,items){
						$(".fenpages").html("共有<b>" + items.count +"</b>条记录  <b>" + pages.current + "</b>/<b>" +pages.count +"</b>&nbsp;&nbsp;");
					}
				});
			});
			});
   	// 	});
   }); 
 })


   function attachPages(currentpage){
		cp=currentpage;
   		var message="attachlist";		
				$.post('/task/attachlist',{'currentPage':cp,'message':message},function(data){
					 	$('#appendattach').children().remove();
					 	$('#appendattach').append(data);
				})
}

function selectall(obj){
	var checkeds=obj.getAttribute('checked');
		if(checkeds==null || checkeds=="null"){ // 全选
					$("input[name='selattach[]']").each(function () {
					$(this).attr("checked", true);
					});
					obj.setAttribute('checked',true);

			}else{ // 取消全选
				$("input[name='selattach[]']").each(function () {
				$(this).attr("checked", false);
				});
					obj.setAttribute('checked',null);
			}

}


