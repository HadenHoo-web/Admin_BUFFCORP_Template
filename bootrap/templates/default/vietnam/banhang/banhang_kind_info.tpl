<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về danh sách" width="20" height="20"><span>Về danh sách</span></a>

</div>
 <div class="tabtitle">
 Loại Bán </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{banhang_kind_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td width="22%" height="25" >Loại bán:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="banhang_kind_name" notnull = 1 type="text" size="70" alt="Loại bán" value="{banhang_kind_name}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Màu sắc thể hiện:</td>
    <td width="78%"><input name="color" type="text" size="70" alt="Màu sắc" value="{color}" /></td>
  </tr>
  <tr>
    <td width="20%">
    Cập nhật hình:</td>
    <td width="80%">
   <input type="file" name="new_image" size="41"  alt="Logo"></td>
  </tr>
	 <tr style="display:{allow}">
	 <td width="20%"></td>
		<td width="80%">
		{imgPath}
	  </tr>
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label for="fp1"> Active</label></td>
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
	mainForm.banhang_kind_name.focus()
</Script>