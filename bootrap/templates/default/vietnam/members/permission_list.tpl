<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
	  <a href="#" onClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>

</div>
<div class="tabtitle"><span class="header">Set permission for member "{login_name}"</span></div>
<div style="overflow:auto; height:80%">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
<form method="POST" action="main.php" name="mainForm">
<input type="hidden" name="l" value="{language_id}">
<input type="hidden" name="id" value="{member_id}">
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="mode" value="permission_save">
<tr class="header">
    <td width="5%"  align="center">#</td>
	<td width="15%" >Right</td>
    <td width="80%" align="left" style="padding-left: 10">Functions</td>
</tr>
<!-- BEGIN list -->
  <tr class="{list.className}">
    <td width="5%"  align="center">{list.order}</td>
    <td width="15%"  align="center" > 
      <input type="checkbox" name="dung{list.code}"  value="{list.code}" size="20" {list.checked}></td>
       <td width="80%"  style="padding-left: 10">{list.func_name}
    </td>
  </tr>	
<!-- END list -->
</form>  
 
</table>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
function returnToList()
{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
}

function doSave()
{	document.mainForm.submit() 
}
</Script>