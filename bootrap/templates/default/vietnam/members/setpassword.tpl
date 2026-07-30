<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
	  <a href="#" onClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
</div>
<div class="tabtitle"><span class="header">Reset password for user &quot;{loginname}&quot</span></div>
  <table  width="100%"  >  
<form method="POST" action="main.php" name="mainForm" OnSubmit="return checkform()">
<input type="hidden" name="l" value="{language_id}">
<input type="hidden" name="id" value="{member_id}">
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="mode" value="setpass">

  <tr>
      <td width="20%" style="padding-left: 10">New password:</td>
      <td width="80%"><input name="new_password" size="46" notnull=1 alt="New password" type="password"></td>
  </tr>
  <tr>
      <td width="20%" style="padding-left: 10">Confirm password:</td>
      <td width="80%"><input name="confirm_password" size="46" notnull=1 alt="Confirm password" type="password"></td>
  </tr>
</form>  
</table>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
mainForm.new_password.focus()

function checkform()
{	if  (mainForm.new_password.value == '')
	{	alert("Password can not be empty. Please try again!")
		return false
	}
	if (mainForm.new_password.value != mainForm.confirm_password.value)
	{	alert("The password was not correctly confirmed! Please ensure that the password and confirmation match exactly.")
		return false
	}
	return true	
}

function returnToList()
{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
}

function doSave()
{	if (checkform()) 
		document.mainForm.submit() 
}
</Script>