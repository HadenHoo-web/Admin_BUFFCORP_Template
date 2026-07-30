<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về danh sách" width="20" height="20"><span>Về danh sách</span></a>

</div>
 <div class="tabtitle">
 Loại Da </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{skin_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td width="22%" height="25" >Loại Da:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="skin_name" notnull = 1 type="text" size="70" alt="Loại Da" value="{skin_name}" /></td>
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
  <tr >
    <td width="22%" height="25" >Slug:</td>
    <td width="78%"><input name="slug" notnull = 1 type="text" size="70" alt="Slug" value="{slug}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Meta key:</td>
    <td width="78%"><input name="meta_key" notnull = 1 type="text" size="70" alt="Meta key" value="{meta_key}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Meta des:</td>
    <td width="78%"><input name="meta_des" notnull = 1 type="text" size="70" alt="Meta des" value="{meta_des}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Title seo:</td>
    <td width="78%"><input name="title_seo" notnull = 1 type="text" size="70" alt="title_seo" value="{title_seo}" /></td>
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
	mainForm.skin_name.focus()
</Script>