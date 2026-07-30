<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
	  <a href="?option={funname}&mode=info"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new"><span>Create new member</span></a>
</div>
<div class="tabtitle"><span class="header">Member List</span></div>
<div style="overflow:auto; height:80%">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
  <tr class="header">
    <td width="10"  align="center">#</td>
    <td width="70" >User name</td>
    <td width="70" >Full name</td>
    <td width="50" >Phone</td>
    <td width="50" >Phòng ban</td>
    <td width="200" >Email</td>
    <td width="50" >Active</td>
    <td width="100" colspan="4" >&nbsp;</td>
  </tr>
<!-- BEGIN list -->  
  <tr class="{list.className}">
    <td align="center" style="vertical-align:middle">{list.order}</td>
    <td style="vertical-align:middle">{list.loginname}</td>
    <td style="vertical-align:middle">{list.fullname}</td>
    <td style="vertical-align:middle">{list.phone}</td>
    <td style="vertical-align:middle">{list.member_type_name}</td>
    <td style="vertical-align:middle">{list.email}</td>
    <td style="vertical-align:middle">{list.active}</td>
    <td><a href="?option={funname}&mode=setpass&id={list.member_id}"><img border="0" src="templates/{skin}/images/icon_securityroles_32px.gif" alt="Create password" width="20" height="20"></a></td>
    <td> <a href="?option={funname}&mode=permission_list&id={list.member_id}"><img border="0" src="templates/{skin}/images/DB_user.png" alt="Permission" width="20" height="20"></a></td>
    <td><a href="?option={funname}&mode=info&id={list.member_id}"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
    <td width="4%" style="vertical-align:middle"><a href="#" onClick="doDelete({list.member_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a> </td>
  </tr>
<!-- END list -->  
 </table>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
function doDelete(id)
{	if (confirm ("Are you sure you want to delete this member ?."))
	{	document.location = "?option={funname}&mode=delete&id=" + id
	}
}	
</Script>