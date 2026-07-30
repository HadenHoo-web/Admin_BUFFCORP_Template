<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- Begin of template category_list -->
<!-- Please do not make any change in this template file -->
<div class="toolbar">
	  <a href="?option=pages/categories&mode=list&pr={cat_id}&l={LANGUAGEID}">
      <img border="0" src="templates/{skin}/images/back.gif" alt="List column"><span>Category list</span></a>
</div>
<div class="tabtitle"><span class="header">
	<Script language="JavaScript">
function WriteStatus(status_id)
{	
	if(status_id=='1')
		document.write('Being created pages')
	else if (status_id=='2')
		document.write('Being edited pages')
	else if(status_id=='3')
		document.write('Available for approval pages')
	else if(status_id=='4')
		document.write('Awaiting deployment pages')
	else if(status_id=='5')
		document.write('Being published pages')
}
WriteStatus('{status_id}')
</Script>
</span></div>

<div style="overflow:auto; height:80%">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
  <tr class="header">
    <td width="3%" height="22">#</td>
    <td width="20%" height="22">Người gởi</td>	
    <td width="20%">Nguồn tin</td>
    <td width="37%" height="22">Title</td>
    <td width="6%" height="22">Status</td>
    <td width="16%" colspan="2" height="22">&nbsp;</td>
  </tr>
<!-- BEGIN list -->
  <tr class="{list.className}{list.status}">
    <td width="5%" align="center" style="vertical-align: middle">{list.order}</td>
    <td width="17%" style="vertical-align: middle">{list.send_by}</td>
    <td width="17%" style="vertical-align: middle">{list.source}</td>
    <td width="47%" style="vertical-align: middle">{list.title}</td>
    <td width="15%" style="vertical-align: middle">{list.status}</td>
    <td width="4%">
      <a href="?option={funname}&mode=info&cid={cat_id}&id={list.page_id}&stat={status_id}&l={LANGUAGEID}">
      <img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update information column" width="20" height="20"></a>    </td>
    <td width="4%">
      <a OnClick="doDelete({list.page_id})">
      <img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete column"></a>    </td>
  </tr>
<!-- END list -->  
</table>
</div>  
  <p align="center"><font color="#FF0000"><b><span lang="en-us">{MESSAGE}</span></b></font></p>
  <blockquote style="display:none">
  <p>Status:&nbsp;&nbsp;</span><span style="display:inline-block" class="stat1"><a href="?option={funname}&mode=list_all&l={LANGUAGEID}&stat=1">1 - Being created</a></span>&nbsp;&nbsp;<span style="display:inline-block" class="stat2"><a href="?option={funname}&mode=list_all&l={LANGUAGEID}&stat=2">2 - Being edited</a></span>&nbsp;&nbsp;<span style="display:inline-block" class="stat3"><a href="?option={funname}&mode=list_all&l={LANGUAGEID}&stat=3">3 - Available for Approval</a></span>&nbsp;&nbsp;<span style="display:inline-block" class="stat4"><a href="?option={funname}&mode=list_all&l={LANGUAGEID}&stat=4">4 - Awaiting Deployment</a></span>&nbsp;&nbsp;<span style="display:inline-block" class="stat5"><a href="?option={funname}&mode=list_all&l={LANGUAGEID}&stat=5">5 - Being published</a></span></p>
</blockquote>
<Script Language="JavaScript">

function doDelete(id)
{	if (confirm ("Are you sure you want to delete this page ?."))
	{	document.location = "?option=pages/pages&mode=delete&stat={status_id}&l={LANGUAGEID}&page_id=" + id
	}
}	
</Script>
<!-- End of template category_list -->