<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
	  <a href="?option={funname}&mode=info&sup_id={sup_id}"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new"><span>Create new</span></a> 
	<a href="#" onClick="returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Back" width="20" height="20"><span>Back</span></a>   

</div>
<div class="tabtitle">
List menu functions <span class="itemName">{sup_fun_name}</span>
</div>
<div style="overflow:auto; height:80%">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
  <tr class="header">
    <td width="4%"  align="center">#</td>
      <td width="18%" >Code</td>
  <td width="38%" >Function name</td>
    <td width="20%" >Description</td>
    <td width="18%" colspan="6" >&nbsp;</td>
   
  </tr>
<!-- BEGIN list -->  
  <tr class="{list.className}">
    <td width="4%"  align="center">{list.order}</td>
    <td width="18%">
    <a href="{link_sub}={list.fun_id}">{list.code}<a>
	</td>
    <td width="38%"><a href="{link_sub}={list.fun_id}">{list.fun_name}</td></a>
    <td width="28%">{list.description}</td>
    <td width="5%" align="center" valign="middle">
	<a href="?option={funname}&mode=fun_up&id={list.fun_id}&sup_id={sup_id}&l={LANGUAGEID}" target="_self">
      <img border="0" src="templates/{skin}/images/up.png" width="16" height="16" style="{list.up}" ></a>	</td>
    <td width="5%" align="center" valign="middle">
	<a href="?option={funname}&mode=fun_down&id={list.fun_id}&sup_id={sup_id}&l={LANGUAGEID}" target="_self">
      <img  style=" {list.down}" border="0" src="templates/{skin}/images/down_blue.png" width="16" height="16"></a>	</td>
	  <td width="4%" align="center"><a href="?option=functions/functions&mode=permission_list&id={list.code}"><img border="0" src="templates/{skin}/images/DB_user.png" width="20" height="20" alt="Permission"></a></td>
	  <td width="4%" align="center"><a href="{link_sub}={list.fun_id}"><img border="0" src="templates/{skin}/images/button-article-list.gif" width="20" height="20" alt="Detail"></a></td>
    <td width="4%"><a href="?option={funname}&mode=info&id={list.fun_id}&sup_id={sup_id}"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
    <td width="4%"><a href="#"  onClick="if(confirm ('Are you sure you want to delete ?.'))
	{	document.location = '?option={funname}&mode=delete&id={list.fun_id}&sup_id={sup_id}' 
	}" ><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a> </td>
  </tr>
<!-- END list -->  
 </table>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
function doDelete(id)
{	if (confirm ("Are you sure you want to delete ?."))
	{	document.location = "?option={funname}&mode=delete&code=" + id
	}
}	

function returnToList()
{	document.location='?option={funname}&mode=list&l={LANGUAGEID}&sup_id={back_id}'
}
</Script>