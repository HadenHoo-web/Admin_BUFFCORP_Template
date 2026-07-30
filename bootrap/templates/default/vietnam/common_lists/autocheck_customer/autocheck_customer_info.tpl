<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Trở về danh sách" width="20" height="20"><span>Trở về danh sách</span></a>
</div>

<div class="tabtitle">Thông tin lịch sử khách hàng</div>
<div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm">
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{autocheck_customer_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr>
    <td width="22%" height="25">Tên:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="customer_name" notnull="1" type="text" size="70" alt="Tên" value="{customer_name}" /></td>
  </tr>
  <tr>
    <td width="22%" height="25">Số điện thoại:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="phone" notnull="1" type="text" size="70" alt="Số điện thoại" value="{phone}" /></td>
  </tr>
  <tr>
    <td width="22%" height="25">Email:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="email" notnull="1" type="text" size="70" alt="Email" value="{email}" /></td>
  </tr>
</table>
</form><p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
</div>
<Script Language="JavaScript">

	function doSave()
	{
		if (verify(mainForm))
			mainForm.submit()
	}

	function returnToList()
	{
		document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}
	mainForm.customer_name.focus()
</Script>
