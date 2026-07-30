<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
	  <a href="#" onClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
  	  <a href="#" onClick="returnToList()"><img border="0" src="templates/{skin}/images/back.gif" width="20" height="20" alt="List"><span>List</span></a>

</div>
<div class="tabtitle">
Permission access admin tool "{func_name}"
</div>
<div style="background:#808080;color:#fff;font-weight:bold;padding:5px;border:1px solid #666">
<form method="GET" action="main.php" name="filterForm" style="margin:0">
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="mode" value="permission_list">
<input type="hidden" name="id" value="{member_id}">
<input type="hidden" name="l" value="{LANGUAGEID}">
Phòng ban
<select name="department_id1" style="height:22px;color:#000080">
  <option value="0">Tất cả phòng ban</option>
  <!-- BEGIN department_list -->
  <option value="{department_list.department_id}" {department_list.selected}>{department_list.department_name}</option>
  <!-- END department_list -->
</select>
<input type="submit" value="Sort" style="height:22px;color:#000080;font-weight:bold">
</form>
</div>
<div style="overflow:auto; height:80%">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
<form method="POST" action="main.php" name="mainForm">
<input type="hidden" name="l" value="{language_id}">
<input type="hidden" name="id" value="{member_id}">
<input type="hidden" name="department_id1" value="{department_id}">
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="mode" value="permission_save">
<tr class="header">
    <td width="5%"  align="center">#</td>
	<td width="15%" >Permission</td>
    <td width="50%" align="left" style="padding-left: 10">Members</td>
    <td width="30%" align="left" style="padding-left: 10">Phòng ban</td>
</tr>
<!-- BEGIN list -->
  <tr class="{list.className}">
    <td width="5%"  align="center">{list.order}</td>
    <td width="15%"  align="center" > 
      <input type="checkbox" name="dung{list.code}"  value="{list.code}" size="20" {list.checked}></td>
       <td width="50%"  style="padding-left: 10">{list.member_name}
    </td>
       <td width="30%"  style="padding-left: 10">{list.department_name}
    </td>
  </tr>	
<!-- END list -->
</form>  
 
</table>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
function returnToList()
{	document.location='?option=functionmenu/functionmenu&mode=list&l={LANGUAGEID}'
}

function doSave()
{	document.mainForm.submit() 
}
</Script>
