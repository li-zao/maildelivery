{%include file="header.php"%}
<script type="text/javascript">
$(function(){
	$(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd HH:ii:ss",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"
    });
	
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
	var default_show = parseSearchCon("noclick");
	if (default_show) {
		$("#searchcondtable").show();
		$("#searchcond").children().children("i").attr("class",'icon-chevron-up');
	}
	
	$("#searchbtn").click(function(){
		var url = "/setting/mailqueue?";
		url += parseSearchCon("click");
		window.location.href = url;
	});

	function parseSearchCon (type) {
		var parameter = "";
		var has_val = false;
		var srcip = $("#srcipid").val();
		if (srcip != "" && srcip != null) {
			parameter = "srcip=" + srcip;
			has_val = true;
		}
		var mailsize1id = $("#mailsize1id").val();
		var mailsize2id = $("#mailsize2id").val();
		if (mailsize1id != "" && mailsize1id != null) {
			if (parameter == "") {
				parameter = "mailsize1=" + mailsize1id; 
			} else {
				parameter += "&mailsize1=" + mailsize1id; 
			}
			has_val = true;
		}
		if (mailsize2id != "" && mailsize2id != null) {
			if (parameter == "") {
				parameter = "mailsize2=" + mailsize2id; 
			} else {
				parameter += "&mailsize2=" + mailsize2id; 
			}
			has_val = true;
		}
		var mailtitleid = $("#mailtitleid").val();
		if (mailtitleid != "" && mailtitleid != null) {
			if (parameter == "") {
				parameter = "title=" + mailtitleid; 
			} else {
				parameter += "&title=" + mailtitleid; 
			}
			has_val = true;
		}
		var recieveid = $("#recieveid").val();
		if (recieveid != "" && recieveid != null) {
			if (parameter == "") {
				parameter = "forward=" + recieveid; 
			} else {
				parameter += "&forward=" + recieveid; 
			}
			has_val = true;
		}
		var sendfromid = $("#sendfromid").val();
		if (sendfromid != "" && sendfromid != null) {
			if (parameter == "") {
				parameter = "sendfrom=" + sendfromid; 
			} else {
				parameter += "&sendfrom=" + sendfromid; 
			}
			has_val = true;
		}
		var statusid = $("#statusid").val();
		if (statusid != "" && statusid != null) {
			if (parameter == "") {
				parameter = "status=" + statusid; 
			} else {
				parameter += "&status=" + statusid; 
			}
			if (statusid != "all") {
				has_val = true;
			}
		}
		var sendtime1id = $("#sendtime1id").val();
		var sendtime2id = $("#sendtime2id").val();
		if (sendtime1id != "" && sendtime1id != null) {
			if (parameter == "") {
				parameter = "sendtime1=" + sendtime1id; 
			} else {
				parameter += "&sendtime1=" + sendtime1id; 
			}
			has_val = true;
		}
		if (sendtime2id != "" && sendtime2id != null) {
			if (parameter == "") {
				parameter = "sendtime2=" + sendtime2id; 
			} else {
				parameter += "&sendtime2=" + sendtime2id; 
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
		if (type == "click") {
			return parameter;
		} else {
			return has_val;
		}
	}
	$("#resetcond").click(function(){
		$("#srcipid, #mailsize1id, #mailsize2id, #mailtitleid, #recieveid, #sendfromid, #sendtime1id, #sendtime2id").val("");
		$("#statusid").val("all");
	})
});
</script>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="/createtem/firstpage" title="????????????" class="tip-bottom"><i class="icon-home"></i>??????</a> 
	<a href="#" class="">????????????</a> 
	<a href="/setting/mailqueue">??????????????????</a> 
	<a href="#" class="current">????????????</a> 
	</div>
  </div>
  <div class="container-fluid">
	
 <div  class="modal hide fade mail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 600px;">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
      <h3 id="myModalLabel">????????????</h3>
      </div>
      <div class="modal-body mailonce" >
      <p>
        <table style="width: 580px;">
          <tr>
            <td width="15%">??????:</td>
            <td><p id="title" ></p></td>
          </tr>
          <tr>
            <td>???????????????:</td>
            <td><p id="sendfrom" ></p></td>
          </tr>
          <tr>
            <td>???????????????:</td>
            <td><p id="sendto" ></p></td>
          </tr>
          <tr>
            <td>??????:</td>
            <td><p id="sendcc" ></p></td>
          </tr>
          <tr>
            <td>??????:</td>
            <td><p id="sendbcc" ></p></td>
          </tr>
          <tr>
            <td>???????????????:</td>
            <td><p id="iqueue" ></p></td>
          </tr>
          <tr>
            <td>????????????:</td>
            <td><p id="sendtime" ></p></td>
          </tr>
          <!-- <tr>
            <td>?????????:</td>
            <td><p id="send_total" ></p></td>
          </tr>
          <tr>
            <td>??????:</td>
            <td><p id="send_progress" ></p></td>
          </tr>
          <tr>
            <td>?????????:</td>
            <td><p id="send_fail" ></p></td>
          </tr>
          <tr>
            <td>????????????:</td>
            <td><p id="send_failed" style="work-break:break-all;overflow:auto;"></p></td>
          </tr>
          <tr>
            <td>????????????:</td>
            <td><p id="send_successed" style="work-break:break-all;overflow:auto;" ></p></td>
          </tr>
          <tr>
            <td>???????????????:</td>
            <td><p id="send_waited" style="work-break:break-all;overflow:auto;"></p></td>
          </tr>   -->
          <tr>
            <td>??????:</td>
            <td><p id="send_log" style="work-break:break-all;overflow:auto;"></p></td>
          </tr>  
        </table> 


      </p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">??????</button>
      <!-- <button class="btn btn-primary">Save changes</button> -->
      </div>
      </div>
	
	<div href="#collapseG3" id="searchcond" data-toggle="collapse" class="widget-title bg_lo"> 
       <h5 style="float: right;">????????????
		 <i class="icon-chevron-down"></i>
	   </h5>
    </div>
          <div id="searchcondtable" style="display:none;">
            <form name="deliveryparam" id="auth-form" method="post">
			<table class="table table-bordered">
			  <tbody>
			   <tr class="searchcondition">
					<td style="width:10%">
					????????????
					</td>
					<td>
					 <input type="text" value="{%$srcip%}" name="srcip" id="srcipid" />
					</td>
					<td style="width:10%">
					????????????
					</td>
					<td>
					 <input type="text" style="width:80px" value="{%$mailsize1%}" name="mailsize1" id="mailsize1id" />&nbsp;&nbsp;???&nbsp;
					 <input type="text" style="width:80px" value="{%$mailsize2%}" name="mailsize2" id="mailsize2id" />&nbsp;Kb
					</td>
					<td style="width:10%">
					????????????
					</td>
					<td>
					 <input type="text" value="{%$title%}" name="mailtitle" id="mailtitleid" />
					</td>
               </tr>
			   
			   
			    <tr class="searchcondition">
					<td style="width:10%">
					?????????
					</td>
					<td>
					 <input type="text" value="{%$forward%}" name="recieve" id="recieveid"/>
					</td>
					<td style="width:10%">
					?????????
					</td>
					<td>
					 <input type="text" value="{%$sendfrom%}" name="sendfrom" id="sendfromid" />
					</td>
					<td style="width:10%">
					????????????
					</td>
					<td>
					<select name="status" id="statusid" style="width:93%">
					  <option value="all" selected>????????????</option>
					  <option value="0" {%if $status == '0'%}selected{%/if%}>????????????</option>
					  <option value="1" {%if $status == '1'%}selected{%/if%}>?????????</option>
					  <option value="2" {%if $status == '2'%}selected{%/if%}>????????????</option>
					  <option value="3" {%if $status == '3'%}selected{%/if%}>????????????</option>
					  <option value="4" {%if $status == '4'%}selected{%/if%}>??????</option>
					</select>
					</td>
               </tr>
               <tr>
					<td style="width:10%" class="searchcondition">
					????????????
					</td>
					<td colspan="3" class="searchcondition">
					<div class="input-append date form_datetime"><input type="text" value="{%$sendtime1%}" name="sendtime1" id="sendtime1id" /><span class="add-on"><i class="icon-calendar"></i></span></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;???&nbsp;&nbsp;&nbsp;&nbsp;
					<div class="input-append date form_datetime"><input type="text" value="{%$sendtime2%}" name="sendtime2" id="sendtime2id" /><span class="add-on"><i class="icon-calendar"></i></span></div>
					</td>
					<td colspan="2">
					<center>
					<a href="#" class="btn btn-mini btn-primary" id="searchbtn">??????</a>
					<a href="#" class="btn btn-mini btn-primary" id="resetcond">??????</a>
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
		<div class="widget-box" style="padding-bottom: 10px;border-bottom: 1px solid #cdcdcd;">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>????????????</h5>
			</div>
			<div class="widget-content nopadding">
				<table width="80%" align="center"  class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="5%" style="text-align:center">??????</th>
							<th style="text-align:center">?????????</th>
							<th style="text-align:center">??????</th>
							<th style="text-align:center">????????????</th>
							<th style="text-align:center">??????</th>
							<th width="10%" style="text-align:center">????????????</th>
							<th style="text-align:center">??????</th>
						</tr>
					</thead>
					<tbody>
					{%$num = 1%}
					{%section name=loop loop=$infos%}
						<tr>
							<td style="text-align:center">{%$num++%}</td>
							<td style="text-align:center">
<a href="#mail{%$infos[loop].id%}" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo('{%$infos[loop].id%}')">{%$infos[loop].sendto|truncate:30:"...":true%}</a>							
							</td>
							<td style="text-align:center">
							<a href="#mail{%$infos[loop].id%}" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo('{%$infos[loop].id%}')">{%$infos[loop].title|truncate:30:"...":true%}</a>
							</td>
							<td style="text-align:center">
							<a href="#mail{%$infos[loop].id%}" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo('{%$infos[loop].id%}')">{%$infos[loop].mailsize%}B</a>
							</td>
							<td style="text-align:center">
							<a href="#mail{%$infos[loop].id%}" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo('{%$infos[loop].id%}')">{%$infos[loop].runtime%}</a>
							</td>
							<td style="text-align:center">
							<a href="#mail{%$infos[loop].id%}" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo('{%$infos[loop].id%}')">{%$infos[loop].retries%}</a>
							</td>
							<td style="text-align:center">
<a href="#mail{%$infos[loop].id%}" name="mail{%$infos[loop].id%}" role="button"  data-toggle="modal"  onclick="mailsinfo('{%$infos[loop].id%}')">							
							{%if $infos[loop].status == 0%}
							????????????
							{%elseif $infos[loop].status == 1%}
							?????????
							{%elseif $infos[loop].status == 3%}
							????????????
							{%elseif $infos[loop].status == 4%}
							<font color="red">????????????</font>
							{%elseif $infos[loop].status == 5%}
							??????
							{%elseif $infos[loop].status == 6%}
							????????????
							{%elseif $infos[loop].status == 2%}
							????????????
							{%/if%}
							</a>
							</td>
							
						</tr>
					{%/section%}
					</tbody>
					<tr class="odd hasnodata" id="hasnodata">
					<td class="dataTables_empty" align="center" valign="top" colspan="5">
						????????????????????????
					</td>
			  </tr>
				</table>
			</div>
			<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<form action="/contact/edit" id="myform2" method="post">
					<div class="modal-header">
						<button type="button" class="close" aria-hidden="true" data-dismiss="modal" type="button">??</button>
						<h3 id="myModalLabel" class="text-left">??????????????????</h3>
					</div>
					<div class="modal-body">
						<p> </p>
						<table>
							<tr height="40px">
								<td>????????????</td>
								<td><input id="gn" type="text" name="gname"/><span style="color:red;">*</span></td>
							</tr>
							<tr>
								<td id="prompt2" colspan="2" >?????????????????????????????????</td>
								<input type="hidden" id="hi" name="id" value="123" />
							</tr>
							<tr>
								<td>????????????</td>
								<td>
									<textarea name="remark" id="re"></textarea>
								</td>
							</tr>
						</table>
						<p> </p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" id="edit" value="??????" />
					</div>
				</form>
			</div>
			<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
				<div class="row-fluid" style="text-align:right; margin-top: 0px;">
					<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="margin-top: 5px;">
						<div id="DataTables_Table_0_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" style="text-align: center;float:left;margin-left:30%;">
							{%$page%}
						</div>
						<div id="DataTables_Table_0_length" class="dataTables_length" style="float:right;width:100px;margin-right:18px;">
							<label style="height:30px;">
								<form action="/setting/mailqueue" method="GET" style="width:106px">
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
<script type="text/javascript">
 function mailsinfo(id){
        var mailsid = "mail" + id;
        $('.mail').attr('id',mailsid);
        $('.mailonce').find('p').css({'line-height':'20px',"margin":'0','word-break':'break-all'});
        $.post('/task/checkforward',{'valsid':id},function(data){
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
</script>
{%include file="footer.php"%}
