<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Trở về danh sách phân loại đối tác" width="20" height="20"><span>Trở về danh sách</span></a>

</div>
 <div class="tabtitle">
 Thông tin Tools làm việc </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{linkedin_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td width="22%" height="25" >Tools:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="linkedin_name" notnull = "1" type="text" size="70" alt="Loại khách hàng" value="{linkedin_name}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Pass:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="pass" notnull = "1" type="text" size="70" alt="Pass" value="{pass}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Email:</td>
    <td width="78%">
    <!-- DO ComboFromTable("email_id", "tbl_emails", "email_id", "email_name", "email_id", 0, "Chọn Email" , "0" , "1 and active = 1 order by  email_name" , "", 1) --> 
    </td>
  </tr>
  <tr >
    <td width="22%" height="25" >Số điện thoại:</td>
    <td width="78%">
    <!-- DO ComboFromTable("tel_id", "tbl_tels", "tel_id", "tel_name", "tel_id", 0, "Chọn SĐT" , "0" , "1 and active = 1 order by  priority" , "", 1) --> 
    </td>
  </tr>
  <tr >
    <td width="22%" height="25" >Ghi chú:<font color="#FF0000">*</font></td>
    <td width="78%"><textarea rows="20" cols="100" name="ghichu">{ghichu}</textarea></td>
  </tr>
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label kind="fp1"> Active</label></td>
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
	mainForm.linkedin_name.focus()
	mainForm.tel_id.value 	= {tel_id}
	mainForm.email_id.value  = {email_id}
</Script>