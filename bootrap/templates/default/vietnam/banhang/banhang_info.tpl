<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="js/jscalendar/calendar-system.css" title="system" />
  <script type="text/javascript" src="js/jscalendar/calendar.js"></script>
  <script type="text/javascript" src="js/jscalendar/calendar-vn.js"></script>
  <script type="text/javascript" src="js/jscalendar/calendar-setup.js"></script>
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về danh sách" width="20" height="20"><span>Về danh sách</span></a>

</div>
 <div class="tabtitle">
 Thương Hiệu </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{banhang_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td width="22%" height="25" >Sản phẩm:<font color="#FF0000">*</font></td>
    <td width="78%">
    <input name="product_id" type="text" size="23" alt="MaSP" onchange="checkSP()" {readonly} value="{product_id}" />
    <select size="1" name="product_code" {disabled}>
		<option value="0" selected> Chọn Sản Phẩm</option>
        <!-- BEGIN list_product -->
        <option value="{list_product.product_id}">{list_product.product_id} - {list_product.hoaca_code} - {list_product.product_name} - Giá {list_product.price} vnđ (<font color="#FF0000">{list_product.soluong}</font>)</option>
        <!-- END list_product -->
    </select> 
    </td>
  </tr>
  <tr >
    <td width="22%" height="25" >Khách hàng:<font color="#FF0000">*</font></td>
    <td width="78%">
    <input name="sdt" type="text" size="23" alt="SDT" onchange="checkTel()" {readonly} value="{tel}" />
    <select size="1" name="tel" disabled>
    	<option value="0" selected> Chọn Khách Hàng</option>
        <!-- BEGIN list_customer -->
		<option value="{list_customer.tel}">{list_customer.tel} - {list_customer.customer_name}</option>
        <!-- END list_customer -->
    </select> 
    </td>
  </tr>
  <tr >
    <td width="22%" height="25" >Loại khách:<font color="#FF0000">*</font></td>
    <td width="78%">
    <!-- DO ComboFromTable("banhang_kind_id", "tbl_banhang_kind", "banhang_kind_id", "banhang_kind_name", "banhang_kind_id", 1, "" , "0" , "1 and active = 1 order by  priority" , "", 1) --> 
    Số tiền <input name="banhang_name" notnull = 1 type="text" size="23" alt="Số tiền" value="{banhang_name}" {readonly}/></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Ghi chú:</td>
    <td width="78%"><textarea name="banhang_code" cols="60" rows="10">{banhang_code}</textarea></td>
  </tr>
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label for="fp1"> Duyệt</label> <input type="checkbox" name="nokho" value="1" {nokho} />
        <label for="fp1"> Không trừ kho</label></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Ngày:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="ngay" id="ngay" type="text" notnull="1" alt="Ngày" size="10" readonly="1" value="{ngay}"> 
	  	<img id="img_ngay" src="templates/{skin}/images/cal.png" width="20" height="20" align="absmiddle" style="border:0px; margin:0px;display:'';"></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Nhân Viên:<font color="#FF0000">*</font></td>
    <td width="78%">
    <select size="1" name="member_id" {disabled}>
		<option value="0" selected> Chọn NV Bán Hàng</option>
        <!-- BEGIN list_member -->
        <option value="{list_member.member_id}">{list_member.fullname}</option>
        <!-- END list_member -->
    </select>
    </td>
  </tr>
<tr style="visibility:{allow}">
      <td  colspan="2" >&nbsp;</td>
    </tr>
  </table>
   
</form><p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
</div>
<Script Language="JavaScript">

	function doSave()
	{	
		if(mainForm.product_code.value == 0 || mainForm.product_code.value == '')
		{	alert('Chọn Sản Phẩm')
			mainForm.product_code.focus()
			return
		}
		if(mainForm.tel.value == 0 || mainForm.tel.value == '')
		{	alert('Chọn Khách Hàng')
			mainForm.tel.focus()
			return
		}
		if (verify(mainForm)){
			alert('Bạn chắc chắn muốn Lưu')
			mainForm.submit()
		}
	}

	function returnToList()
	{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}
	function checkTel()
	{	
		mainForm.tel.value = mainForm.sdt.value
	}
	function checkSP()
	{	
		mainForm.product_code.value = mainForm.product_id.value
	}
	mainForm.banhang_name.focus()
	mainForm.tel.value = '{tel}';
	mainForm.banhang_kind_id.value = {banhang_kind_id}
	mainForm.product_code.value = {product_code}
	mainForm.member_id.value = {member_id}
	
	Calendar.setup({
  	inputField     :    "ngay",     // id of the input field
    ifFormat       :    "%d-%m-%Y",      // format of the input field
    button         :    "img_ngay",  // trigger for the calendar (button ID)
    align          :    "Tl",           // alignment (defaults to "Bl")
    singleClick    :    true
	});
</Script>