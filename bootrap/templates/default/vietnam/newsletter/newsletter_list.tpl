<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<div class="toolbar">
<a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new"><span>Create new</span></a>
</div>

<div class="tabtitle">Template newsletter</div>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="83" align="center" >#</td>
      <td width="440" align="left" >Email</td>
      <td width="123" align="left" >IP</td>
      <td  colspan="4" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td width="83" align="center" style="vertical-align:middle">{list.order}</td>
      <td width="440" style="vertical-align:middle">{list.email}</td>
      <td width="70" align="center" style="vertical-align:middle">{list.ip}</td>
      <td width="20" align="center"><a href="?option={funname}&mode=up&id={list.newsletter_id}&l={LANGUAGEID}"  target="_self"> <img border="0" src="templates/{skin}/images/up.png" width="16" height="16" style="{list.up}"></a> </td>
      <td width="20" align="center"><a href="?option={funname}&mode=down&id={list.newsletter_id}&l={LANGUAGEID}" target="_self"> <img  style="{list.down}" border="0" src="templates/{skin}/images/down_blue.png" width="16" height="16"></a></td>
      <td width="24" align="center"><a href="JavaScript:updateItem({list.newsletter_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="35" align="center"><a href="JavaScript:deleteItem({list.newsletter_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">

function doCreate()
{	
	document.location='?option={funname}&mode=info&l={LANGUAGEID}'
}

function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}

function deleteItem(id)
{	if (confirm ("Are you sure you want to delete ?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
}	

function donewsletterList()
{	
	document.location = '?option=common/newsletter&mode=list&l={LANGUAGEID}'
}	

function doKindList()
{	
	document.location = '?option=common/kind&mode=list&l={LANGUAGEID}'
}	
</Script>

