<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
	  <a href="?option={funname}&mode=info"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new"><span>Create new</span></a>
</div>
<div class="tabtitle">
List admin tools</div>
<div style="overflow:auto; height:80%">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
  <tr class="header">
    <td width="4%"  align="center">#</td>
      <td width="18%" >Code</td>
  <td width="38%" >Tool name</td>
    <td width="28%" >Description</td>
    <td width="12%" colspan="3" >&nbsp;</td>
   
  </tr>
<!-- BEGIN list -->  
  <tr class="{list.className}">
    <td width="4%"  align="center">{list.order}</td>
    <td width="18%">
    {list.code}
	</td>
    <td width="38%">{list.func_name}</td>
    <td width="28%">{list.description}</td>
    <td width="4%"><a href="?option={funname}&mode=permission_list&id={list.code}"><img border="0" src="templates/{skin}/images/DB_user.png" alt="Permission" width="20" height="20"></a></td>
    <td width="4%"><a href="?option={funname}&mode=info&code={list.code}&id={list.code}"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
    <td width="4%"><a href="#"  onClick="if(confirm ('Do you want to delete ?.'))
	{	document.location = '?option={funname}&mode=delete&code={list.code}' 
	}" ><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a> </td>
  </tr>
<!-- END list -->  
 </table>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
function doDelete(id)
{	if (confirm ("Do you want to delete ?."))
	{	document.location = "?option={funname}&mode=delete&code=" + id
	}
}	
</Script>
