{%include file="header.php"%}
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> <a href="#" title="返回主页" ><strong> 合并组</strong></a></div>
	</div>
	<hr>
	<div class="container-fluid">
		<div style="height:30px"><strong>当前组：</strong>{%$thisgroup%}</div>
		<div>
			<div style="height:30px"><strong>合并目标组</strong></div>
			<form action="/contact/combine" method="post">
			<div style="height:30px">
				<select>
					<option>选择并合并目标组</option>
					{%section name=loop loop=$data%}
						<option value="">{%$data[loop].gname%}</option>
					{%/section%}
				</select>
			</div>
			<div style="height:15px"></div>
			<div style="height:30px"><input type="submit" value="确定" /><input type="button" value="取消" /></div>
			</form>
		</div>
	</div>
</div>
{%include file="footer.php"%}