<div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về danh sách" width="20" height="20"><span>Về danh sách</span></a>
</div>
 <div class="tabtitle"> Nhập kho ban đầu </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
    <input type="hidden" name="id" value="{kho_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td width="22%" height="25" >Kho:<font color="#FF0000">*</font></td>
    <td width="78%">
		<select size="1" notnull=1 name="loaikho_id" id="loaikho_id">
        <option value="0" selected> Chọn kho</option>
    	<option value="1" selected> Zalora</option>
        <option value="2" selected> Lazada</option>
        <option value="3" selected> Modernlife</option>
	</select>
	<Script Language="JavaScript">mainForm.loaikho_id.value 	= {loaikho_id}</Script>
		</span> 
		ID SP: <input name="product_id" id="product_id" notnull = 1 type="text" size="10" value="{product_id}"/> Số lượng SP <input name="soluong" notnull = 1 type="text" size="10" value="{soluong}" {readonly}/>
		</td>
  </tr>
  <tr >
    <td width="22%" height="25" >Nội dung ghi chu:</td>
    <td width="78%"><textarea name="noidung" id="noidung" cols="60" rows="10" {readonly_nd}>{noidung}</textarea></td>
  </tr> 
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label for="fp1"> Khóa</label></td>
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
		if(mainForm.loaikho_id.value==0)
		{	alert('Chọn kho')
			mainForm.loaikho_id.focus()
			return
		}
		if (verify(mainForm))	
			mainForm.submit()
	}
	function returnToList()
	{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}
	mainForm.product_id.focus()
</Script>