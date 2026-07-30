<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
	  <a href="#" onClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
  	  <a href="#" onClick="returnToList()"><img border="0" src="templates/{skin}/images/back.gif" width="20" height="20" alt="Quay về "><span>Quay về </span></a>
</div>
<div class="tabtitle">Cập nhật thông tin chủ đề
</div>
  <table  width="100%"  >
  
 
<form method="POST" action="main.php" name="mainForm" OnSubmit="return checkform()">
<input type="hidden" name="l" value="{language_id}">
<input type="hidden" name="cid" value="{cat_id}">
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="mode" value="cat_update">

  <tr>
      <td width="20%" style="padding-left: 10">T&ecirc;n th&#432; m&#7909;c:</td>
      <td width="80%"><input name="cat_name" size="46" notnull alt="Tên thư mục" type="text" value="{cat_name}"></td>
  </tr>
</form>  
</table>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
mainForm.cat_name.focus()



function returnToList()
{	document.location='?option={funname}&mode=image_list&l={LANGUAGEID}&cid={cat_id}'
}

function doSave()
{	if (verify(mainForm) )
		document.mainForm.submit() 
}
</Script>