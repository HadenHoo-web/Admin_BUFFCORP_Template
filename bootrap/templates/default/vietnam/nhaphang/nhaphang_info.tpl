<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="js/jscalendar/calendar-system.css" title="system" />
  <script type="text/javascript" src="js/jscalendar/calendar.js"></script>
  <script type="text/javascript" src="js/jscalendar/calendar-vn.js"></script>
  <script type="text/javascript" src="js/jscalendar/calendar-setup.js"></script> 	
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về danh sách" width="20" height="20"><span>Về danh sách</span></a>

</div>
 <div class="tabtitle"> Nhập xuất hàng </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{nhaphang_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td width="22%" height="25" >Ngày:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="ngay" id="ngay" type="text" notnull="1" alt="Ngày" size="10" readonly="1" value="{ngay}"> 
	  	<img id="img_ngay" src="templates/{skin}/images/cal.png" width="20" height="20" align="absmiddle" style="border:0px; margin:0px;display:none;">
		<span style="display:{allow_display};">
		<select size="1" name="product_type_id" id="myselect" onchange="typeChange(document.getElementById('myselect'))" style="display:none;">
    	<option value="0" selected> Chọn chuyên mục</option>
<!-- BEGIN product_type_list -->
    	<option value="{product_type_list.product_type_id}">{product_type_list.parent_id} {product_type_list.product_type_name} - {product_type_list.product_type_code}</option>
<!-- END product_type_list -->
	</select>
	<Script Language="JavaScript">mainForm.product_type_id.value 	= {product_type_id}</Script>
		</span> 
		{product_type_name}
		</td>
  </tr>
  <tr >
    <td width="22%" height="25" >Sản phẩm:<font color="#FF0000">*</font></td>
    <td width="78%"> 
	  	
		<span style="display:{allow_display};">
		<select size="1" name="product_id" id="myselect" onchange="typeChange(document.getElementById('myselect'))">
    	<option value="0" selected> Chọn sản phẩm</option>
<!-- BEGIN list_product -->
        <option value="{list_product.product_id}">{list_product.hoaca_code} - {list_product.product_name} - Giá {list_product.price} vnđ (<font color="#FF0000">{list_product.soluong}</font>)</option>
        <!-- END list_product -->
	</select>
	<Script Language="JavaScript">mainForm.product_id.value 	= {product_id}</Script>
		</span> 
		<a href="{website}/{slug}.htm" target="_blank">{product_name}</a>
		</td>
  </tr>
  <tr >
    <td width="22%" height="25" >Nhập xuất:<font color="#FF0000">*</font></td>
    <td width="78%">
    <select size="1" name="nhap_xuat" {disabled}>
    	<option value="0" selected> Nhập hàng </option>
		<option value="1"> Xuất hàng </option>
	</select>
	<Script Language="JavaScript">mainForm.nhap_xuat.value 	= {nhap_xuat}</Script>
    Số lượng SP <input name="nhaphang_name" notnull = 1 type="text" size="23" value="{nhaphang_name}" {readonly}/>  
	</td>
  </tr>
  <tr >
    <td width="22%" height="25" >Nội dung chi tiết:</td>
    <td width="78%"><textarea name="nhaphang_code" cols="60" rows="10" {readonly_nd}>{nhaphang_code}</textarea></td>
  </tr> 
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label for="fp1"> Duyệt</label></td>
  </tr>
<tr style="visibility:{allow}">
      <td  colspan="2" >&nbsp;</td>
    </tr>
  </table>
   
</form><p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
</div>
<Script Language="JavaScript">

	function doSave()
	{	if (verify(mainForm))	
			mainForm.submit()
	}

	function returnToList()
	{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}
	mainForm.nhaphang_name.focus()
Calendar.setup({
  	inputField     :    "ngay",     // id of the input field
    ifFormat       :    "%d-%m-%Y",      // format of the input field
    button         :    "img_ngay",  // trigger for the calendar (button ID)
    align          :    "Tl",           // alignment (defaults to "Bl")
    singleClick    :    true
});
mainForm.product_type_id.value = {product_type_id}
</Script>