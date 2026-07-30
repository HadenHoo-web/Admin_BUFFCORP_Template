<Script Language="JavaScript">
function doDelete(id)
{	if (confirm ("Are you sure you want to delete this page ?."))
	{	document.location = "?option=pages/pages&mode=delete&l={LANGUAGEID}&page_id=" + id
	}
}	
function a()
{
	document.location = "?option=pages/pages&mode=search&l={LANGUAGEID}&image=" + image.value
}
</Script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- Begin of template category_list -->
<!-- Please do not make any change in this template file -->
<div class="toolbar">
  <a href="?option={funname}&mode=info&cid={cat_id}&l={LANGUAGEID}"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new page"><span>Create new page</span></a>    
	  <a href="?option=pages/categories&mode=list&pr={cat_id}&l={LANGUAGEID}">
      <img border="0" src="templates/{skin}/images/back.gif" alt="List columns"><span>Category list</span></a><span>
	  <a href="javascript:a()">
      <span>Tìm bài có image </span></a><input name="image" type="text" />
</div>
<div class="tabtitle">&nbsp;Page list&nbsp;
<!-- BEGIN catChain -->
  <font face="Arial" color="#00FFFF">»</font>&nbsp;&nbsp;<a href="?option={funname}&mode=list&cid={catChain.cat_id}&l={LANGUAGEID}">{catChain.cat_name}</a>&nbsp;
<!-- END catChain -->  
</div>
<div style="overflow:auto; height:80%">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
  <tr class="header">
    <td width="9%" height="22">#</td>
    <td width="15%" height="22">Người gởi</td>	
    <td width="15%">Nguồn tin</td>
    <td width="38%" height="22">Title</td>
    <td width="12%">image</td>
    <td colspan="2" height="22">&nbsp;</td>
  </tr>
<!-- BEGIN list -->
  <tr class="{list.className}{list.status}">
    <td width="9%" align="center" style="vertical-align: middle">{list.order}</td>
    <td width="15%" style="vertical-align: middle">{list.send_by}</td>
    <td width="15%" style="vertical-align: middle">{list.source}</td>
    <td width="38%" style="vertical-align: middle">{list.title}</td>
    <td width="12%" style="vertical-align: middle">{list.image}</td>
    <td width="3%">
      <a href="?option={funname}&mode=info&cid={cat_id}&id={list.page_id}&l={LANGUAGEID}">
      <img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update information column" width="20" height="20"></a>    </td>
    <td width="8%">
      <a OnClick="doDelete({list.page_id})">
      <img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete column"></a>    </td>
  </tr>
<!-- END list -->  
</table>
</div>  
  <p align="center"><font color="#FF0000"><b><span lang="en-us">{MESSAGE}</span></b></font></p>
 <blockquote style="display:none">
  <p>Page&#39;s status:&nbsp;&nbsp;</span><span style="display:inline-block" class="stat1"><a href="?option={funname}&mode=list_all&l={LANGUAGEID}&stat=1">1 - 
  Being created</a></span>&nbsp;&nbsp;<span style="display:inline-block" class="stat2"><a href="?option={funname}&mode=list_all&l={LANGUAGEID}&stat=2">2 - 
  Being edited</a></span>&nbsp;&nbsp;<span style="display:inline-block" class="stat3"><a href="?option={funname}&mode=list_all&l={LANGUAGEID}&stat=3">3 
  - Available for approval 
    </a></span>&nbsp;&nbsp;<span style="display:inline-block" class="stat4"><a href="?option={funname}&mode=list_all&l={LANGUAGEID}&stat=4">4 - Awaiting 
  Deployment</a></span>&nbsp;&nbsp;<span style="display:inline-block" class="stat5"><a href="?option={funname}&mode=list_all&l={LANGUAGEID}&stat=5">5 - 
  Being Published</a></span></p>
</blockquote>
<!-- End of template category_list -->