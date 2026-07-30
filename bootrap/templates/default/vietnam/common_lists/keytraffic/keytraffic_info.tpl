<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Trở về danh sách phân loại đối tác" width="20" height="20"><span>Trở về danh sách</span></a>

</div>
 <div class="tabtitle">
 Thông tin từ khoá </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{keytraffic_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td width="22%" height="25" >Keyword:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="keyword" notnull = "1" type="text" size="70" alt="Keyword" value="{keyword}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Volume:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="volume" notnull = "1" type="text" size="70" alt="Volume" value="{volume}" /></td>
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
	mainForm.keyword.focus()
</Script>
