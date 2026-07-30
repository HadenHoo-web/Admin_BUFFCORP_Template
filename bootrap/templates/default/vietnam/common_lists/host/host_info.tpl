<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="js/jscalendar/calendar-system.css" title="system" />
<script type="text/javascript" src="js/jscalendar/calendar.js"></script>
<script type="text/javascript" src="js/jscalendar/calendar-vn.js"></script>
<script type="text/javascript" src="js/jscalendar/calendar-setup.js"></script>
<style>
	.host-renew-box {
		display: {renew_display};
		margin: 10px 0;
		padding: 12px;
		border: 1px solid #f0c36d;
		background: #fff8e6;
		border-radius: 6px;
	}
	.host-renew-title {
		margin-bottom: 8px;
		color: #b45309;
		font-weight: bold;
		font-size: 14px;
	}
	.host-renew-grid {
		width: 100%;
	}
	.host-renew-options {
		margin-bottom: 10px;
	}
		.host-renew-option {
			display: inline-block;
			width: 455px;
			margin: 0 10px 10px 0;
			padding: 8px;
			vertical-align: top;
			border: 1px solid #ead19a;
			background: #fffdf5;
			border-radius: 5px;
		}
		.host-renew-account {
			display: inline-block;
			width: 215px;
			margin-right: 8px;
			vertical-align: top;
			text-align: center;
		}
		.host-renew-account-title {
			font-weight: bold;
			color: #003366;
		}
		.host-renew-account-note {
			font-size: 11px;
			color: #666;
		}
		.host-renew-option img {
			width: 155px;
			height: 155px;
			border: 1px solid #ddd;
			background: #fff;
		}
	.host-renew-line {
		margin: 6px 0;
	}
		.host-renew-copy {
			width: 430px;
			font-weight: bold;
			color: #003366;
		}
	.host-renew-action {
		margin-top: 5px;
	}
	.host-renew-action button {
		margin-right: 4px;
	}
	.host-renew-message {
		color: #cc0000;
		font-weight: bold;
	}
</style>

<div class="toolbar">
	<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
	<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Trở về danh sách phân loại đối tác" width="20" height="20"><span>Trở về danh sách</span></a>
</div>

<div class="tabtitle">
	Thông tin Host làm việc
</div>

<div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm">
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{host_id}">
	<input type="hidden" name="mode" value="save">

	<table width="100%">
		<tr>
			<td width="22%" height="25">Host:<font color="#FF0000">*</font></td>
			<td width="78%"><input name="host_name" notnull="1" type="text" size="70" alt="Loại khách hàng" value="{host_name}" /></td>
		</tr>

		<tr>
			<td height="25">Url:<font color="#FF0000">*</font></td>
			<td><input name="url" notnull="1" type="text" size="70" alt="Url" value="{url}" /></td>
		</tr>

		<tr>
			<td width="22%" height="25">Giá:<font color="#FF0000">*</font></td>
			<td width="78%">
				<input name="price" type="text" size="20" alt="Price" value="{price}" />
				Ngày Mua
				<input name="order_date" id="order_date" type="text" notnull="1" alt="Ngày" size="10" value="{order_date}" placeholder="dd/mm/yyyy" {allow_edit}>
				<img id="{allow_edit}img_order_date" src="templates/{skin}/images/cal.png" width="20" height="20" align="absmiddle" style="border:0px; margin:0px;"> ( yyyy-mm-dd )
				&nbsp;&nbsp; Ngày Hết Hạn <font color="#FF0000">*</font>
				<input name="end_date" id="end_date" type="text" notnull="1" alt="Ngày" size="10" value="{end_date}" placeholder="dd/mm/yyyy" {allow_edit}>
				<img id="{allow_edit}img_end_date" src="templates/{skin}/images/cal.png" width="20" height="20" align="absmiddle" style="border:0px; margin:0px;"> ( yyyy-mm-dd )
			</td>
		</tr>

		<tr>
			<td width="22%" height="25">Username:</td>
			<td width="78%">
				<input name="username" type="text" size="20" alt="Số lượng" value="{username}" />
				Pass
				<input name="pass" notnull="1" type="text" size="35" alt="Pass" value="{pass}" />
				IP
				<input name="ip_host" notnull="1" type="text" size="20" alt="IP Host" value="{ip_host}" />
			</td>
		</tr>

		<tr>
			<td width="22%" height="25">Thông tin Server:</td>
			<td width="78%">
				<!-- DO ComboFromTable("server_id", "tbl_server", "server_id", "server_name", "server_id", 0, "Chọn Server" , "0" , "1 and active = 1 order by  server_name" , "", 1) -->
				
				Chọn Gói Hosting
				<!-- DO ComboFromTable("package_id", "tbl_packages", "package_id", "package_name", "package_id", 0, "Chọn Package" , "0" , "1 and active = 1 order by  package_name" , "", 1) -->
				
				&nbsp;&nbsp;&nbsp;&nbsp;
				Phân loại Hosting
				<select name="hosting_type" id="hosting_type" style="min-width:180px;" {allow_edit}>
					<option value="0">-- Chọn phân loại --</option>
					<option value="1">Customer Hosting</option>
					<option value="2">Demo Hosting</option>
					<option value="3">Internal System Hosting</option>
				</select>
			</td>
		</tr>

		<tr>
			<td width="22%" height="25">Khách hàng:</td>
			<td width="78%">
				<!-- DO ComboFromTable("customer_id", "tbl_customer", "customer_id", "customer_name", "customer_id", 0, "Chọn Khách hàng" , "0" , "1 and active = 1 and customer_type_id = 8 order by  customer_name" , "", 1) -->
			</td>
		</tr>

		<tr>
			<td width="22%" height="25">NV QL:</td>
			<td width="78%">
				<select size="1" name="member_id" alt="Member" {allow_edit}>
					<option value="0">NV QL</option>
					<!-- BEGIN member_list -->
					<option value="{member_list.member_id}">{member_list.member_name}</option>
					<!-- END member_list -->
				</select>
				Share user ID :
				<input name="share_user_id" type="text" size="20" alt="Số lượng" value="{share_user_id}" />
				(T.A:34, Hằng:50, Tú:63, Xuyến:60, Ngân:61, Duy:65, Huy:71)
			</td>
		</tr>

		<tr>
			<td width="22%" height="25">Email QL:</td>
			<td width="78%">
				<!-- DO ComboFromTable("email_id", "tbl_emails", "email_id", "email_name", "email_id", 0, "Chọn Email" , "0" , "1 and active = 1 order by  email_name" , "", 1) -->
			</td>
		</tr>

		<tr>
			<td width="22%" height="25">Gia hạn Hosting:</td>
			<td width="78%">
				<div class="host-renew-box">
					<div class="host-renew-title">Hosting gần đến kỳ gia hạn: {renew_due_text}</div>
					<div class="host-renew-grid">
						<div class="host-renew-options">
							<!-- BEGIN payment_option -->
							<div class="host-renew-option">
								<div class="host-renew-line"><b>{payment_option.months} tháng</b></div>
								<div class="host-renew-line">Số tiền: <b>{payment_option.amount} đ</b></div>
								<div class="host-renew-line">
									<div class="host-renew-account">
										<div class="host-renew-account-title">Có VAT</div>
										<div class="host-renew-account-note">CÔNG TY TNHH BUFF CORP</div>
										<img id="renew_qr_vat_{host_id}_{payment_option.months}" style="display:{payment_option.vat_qr_display}" src="{payment_option.vat_qr_url}" alt="QR có VAT hosting {payment_option.months} tháng">
										<div class="host-renew-action">
											<button type="button" onclick="copyQrImage('renew_qr_vat_{host_id}_{payment_option.months}')">Copy QR VAT</button>
										</div>
									</div>
									<div class="host-renew-account">
										<div class="host-renew-account-title">Không VAT</div>
										<div class="host-renew-account-note">HO KINH DOANH</div>
										<img id="renew_qr_no_vat_{host_id}_{payment_option.months}" style="display:{payment_option.no_vat_qr_display}" src="{payment_option.no_vat_qr_url}" alt="QR không VAT hosting {payment_option.months} tháng">
										<div class="host-renew-action">
											<button type="button" onclick="copyQrImage('renew_qr_no_vat_{host_id}_{payment_option.months}')">Copy QR không VAT</button>
										</div>
									</div>
								</div>
								<div class="host-renew-line">Nội dung:</div>
								<div class="host-renew-line"><input type="text" id="renew_content_{host_id}_{payment_option.months}" class="host-renew-copy" readonly value="{payment_option.content}" onclick="this.select()"></div>
								<div class="host-renew-action">
									<button type="button" onclick="copyInputValue('renew_content_{host_id}_{payment_option.months}')">Copy nội dung</button>
								</div>
							</div>
							<!-- END payment_option -->
						</div>
						<div class="host-renew-info">
							<div class="host-renew-line host-renew-message">{payment_message}</div>
							<div class="host-renew-line">
								Nhân viên nhập nội dung thanh toán:
								<input type="text" name="payment_content" id="payment_content" size="55" value="">
								<input type="button" value="Xác nhận gia hạn" onclick="doRenewHosting()">
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>

		<tr>
			<td width="22%" height="25">Ghi chú:<font color="#FF0000">*</font></td>
			<td width="78%"><textarea rows="20" cols="100" name="ghichu">{ghichu}</textarea></td>
		</tr>

		<tr>
			<td></td>
			<td>
				<input type="checkbox" name="active" value="1" id="fp1" {active} />
				<label kind="fp1"> Active</label>
			</td>
		</tr>

		<tr style="visibility:{allow}">
			<td colspan="2">&nbsp;</td>
		</tr>

		<tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
			<td width="20%" style="padding-left: 10" height="23">Create date:</td>
			<td width="80%" height="23"><input type="text" name="T1" size="33" class="is_label" readonly value="{created_date}"></td>
		</tr>

		<tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
			<td width="20%" style="padding-left: 10" height="23">Create by:</td>
			<td width="80%" height="23"><input type="text" name="created_by" size="33" class="is_label" readonly value="{created_by}"></td>
		</tr>

		<tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
			<td width="20%" style="padding-left: 10" height="23">Last_modified</td>
			<td width="80%" height="23">
				<input type="text" name="last_modified" size="33" class="is_label" readonly value="{last_modified}">
			</td>
		</tr>

		<tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
			<td width="20%" style="padding-left: 10" height="23">Modified by</td>
			<td width="80%" height="23"><input type="text" name="modified_by" size="33" class="is_label" readonly value="{modified_by}"></td>
		</tr>
	</table>
</form>

<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
</div>

<Script Language="JavaScript">
	function doSave()
	{
		mainForm.mode.value = "save";
		normalizeHostingDateField("order_date");
		normalizeHostingDateField("end_date");
		if (verify(mainForm))
			mainForm.submit()
	}

	function doRenewHosting()
	{
		var paymentContent = document.getElementById("payment_content");
		if (!paymentContent || paymentContent.value.replace(/^\s+|\s+$/g, "") == "") {
			alert("Vui lòng nhập nội dung thanh toán");
			if (paymentContent) paymentContent.focus();
			return;
		}
		if (!confirm("Xác nhận gia hạn hosting theo nội dung thanh toán này?")) return;
		mainForm.mode.value = "renew";
		mainForm.submit();
	}

	function copyInputValue(inputId)
	{
		var input = document.getElementById(inputId);
		if (!input) return;
		input.select();
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(input.value);
		} else {
			document.execCommand('copy');
		}
		alert('Đã copy nội dung thanh toán');
	}

	function copyQrImage(imageId)
	{
		var image = document.getElementById(imageId);
		if (!image || !image.src) return;
		if (navigator.clipboard && window.ClipboardItem && window.fetch) {
			fetch(image.src).then(function(response) {
				return response.blob();
			}).then(function(blob) {
				return navigator.clipboard.write([
					new ClipboardItem({ 'image/png': blob })
				]);
			}).then(function() {
				alert('Đã copy hình QR');
			}).catch(function() {
				window.open(image.src, '_blank');
			});
		} else {
			window.open(image.src, '_blank');
		}
	}

	function normalizeHostingDateValue(value)
	{
		value = value.replace(/^\s+|\s+$/g, "");
		if (value == "") return "";

		var vnDate = value.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
		if (vnDate) {
			var dd = parseInt(vnDate[1], 10);
			var mm = parseInt(vnDate[2], 10);
			var yyyy = parseInt(vnDate[3], 10);
			var date = new Date(yyyy, mm - 1, dd);
			if (date.getFullYear() == yyyy && date.getMonth() == mm - 1 && date.getDate() == dd) {
				return yyyy + "-" + (mm < 10 ? "0" + mm : mm) + "-" + (dd < 10 ? "0" + dd : dd);
			}
		}

		var sqlDate = value.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
		if (sqlDate) {
			var yyyy2 = parseInt(sqlDate[1], 10);
			var mm2 = parseInt(sqlDate[2], 10);
			var dd2 = parseInt(sqlDate[3], 10);
			var date2 = new Date(yyyy2, mm2 - 1, dd2);
			if (date2.getFullYear() == yyyy2 && date2.getMonth() == mm2 - 1 && date2.getDate() == dd2) {
				return yyyy2 + "-" + (mm2 < 10 ? "0" + mm2 : mm2) + "-" + (dd2 < 10 ? "0" + dd2 : dd2);
			}
		}

		return value;
	}

	function normalizeHostingDateField(fieldId)
	{
		var field = document.getElementById(fieldId);
		if (field) field.value = normalizeHostingDateValue(field.value);
	}

	function bindHostingDateField(fieldId)
	{
		var field = document.getElementById(fieldId);
		if (!field) return;
		field.onblur = function() {
			normalizeHostingDateField(fieldId);
		};
		field.onpaste = function() {
			window.setTimeout(function() {
				normalizeHostingDateField(fieldId);
			}, 0);
		};
	}

	bindHostingDateField("order_date");
	bindHostingDateField("end_date");

	Calendar.setup({
		inputField  : "end_date",      // id of the input field
		ifFormat    : "%Y-%m-%d",      // format of the input field
		button      : "img_end_date",  // trigger for the calendar (button ID)
		align       : "Tl",            // alignment (defaults to "Bl")
		singleClick : true
	});

	Calendar.setup({
		inputField  : "order_date",      // id of the input field
		ifFormat    : "%Y-%m-%d",        // format of the input field
		button      : "img_order_date",  // trigger for the calendar (button ID)
		align       : "Tl",              // alignment (defaults to "Bl")
		singleClick : true
	});

	function returnToList()
	{
		document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}

	mainForm.host_name.focus()

	mainForm.email_id.value    = {email_id}
	mainForm.package_id.value  = {package_id}
	mainForm.server_id.value   = {server_id}
	mainForm.customer_id.value = {customer_id}
	mainForm.member_id.value   = {member_id}

	// NEW: set selected hosting_type when edit
	if (mainForm.hosting_type) {
		mainForm.hosting_type.value = '{hosting_type}';
	}
</Script>
