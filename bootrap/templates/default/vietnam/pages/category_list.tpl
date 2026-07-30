<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- Begin of template category_list -->
<!-- Please do not make any change in this template file -->
<div class="toolbar">
  <a style="display:expression(('{isROOT}'=='1')?'none':'')" href="?option={funname}&mode=info&id={cat_id}&l={LANGUAGEID}"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update column content"><span>Edit 
  category information</span></a>
  <a href="?option={funname}&mode=info&pr={cat_id}&l={LANGUAGEID}"><img border="0" src="templates/{skin}/images/button-category-create.gif" alt="Create new column"><span>Create 
  sub-category</span></a>
  <a href="?option=pages/pages&mode=list&cid={cat_id}&l={LANGUAGEID}"><img border="0" src="templates/{skin}/images/button-article-list.gif" alt="List pages"><span>Page 
  list</span></a>
  <a href="?option=pages/pages&mode=info&cid={cat_id}&l={LANGUAGEID}"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new page"><span>Create new page</span></a>    
</div>
<div class="tabtitle">&nbsp;Category List
<!-- BEGIN catChain -->
<font face="Arial" color="#00FFFF">»</font>&nbsp;&nbsp;<a href="?option={funname}&mode=list&pr={catChain.cat_id}&l={LANGUAGEID}">{catChain.cat_name}</a>&nbsp;
<!-- END catChain -->  
</div>
<div style="overflow:auto; height:80%">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
  <tr class="header">
    <td width="5%" height="22">#</td>
    <td width="30%" height="22">Category name</td>	
    <td width="13%" height="22">Create date</td>
    <td width="20%" height="22">Create by</td>
    <td width="32%" colspan="8" height="22">&nbsp;</td>
  </tr>
<!-- BEGIN list -->
  <tr class="{list.className}">
    <td width="5%" align="center" style="vertical-align: middle">{list.order}</td>
    <td width="30%" style="vertical-align: middle">{list.cat_name}</td>
    <td width="13%" style="vertical-align: middle">{list.created_date}</td>
    <td width="20%" style="vertical-align: middle">{list.created_by}</td>
    <td width="4%" style="vertical-align: middle" align="center">
      <a href="?option={funname}&mode=moveup&id={list.cat_id}&pr={list.parent_id}&l={LANGUAGEID}" target="_self">
      <img border="0" src="templates/{skin}/images/up.png" width="16" height="16" style="{list.up}"></a>
    </td>
    <td width="4%" style="vertical-align: middle" align="center">
      <a href="?option={funname}&mode=movedown&id={list.cat_id}&pr={list.parent_id}&l={LANGUAGEID}" target="_self">
      <img  style="{list.down}" border="0" src="templates/{skin}/images/down_blue.png" width="16" height="16"></a>
    </td>
    <td width="4%">
      <a href="?option={funname}&mode=info&pr={list.cat_id}&l={LANGUAGEID}" target="_self">
      <img border="0" src="templates/{skin}/images/button-category-create.gif" alt="Create sub-category" width="20" height="20"></a>
    </td>
    <td width="4%">
      <a href="?option={funname}&pr={list.cat_id}&mode=list&l={LANGUAGEID}" target="_self">
      <img border="0" src="templates/{skin}/images/button-category-perms.gif" width="20" height="20" alt="Sub category list"></a>
    </td>
    <td width="4%">
<a href="?option=pages/pages&mode=info&cid={list.cat_id}&pr={list.parent_id}&l={LANGUAGEID}">
      <img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new page" width="20" height="20"></a>
    </td>
    <td width="4%">
      <a href="?option=pages/pages&mode=list&cid={list.cat_id}&pr={list.parent_id}&l={LANGUAGEID}"><img border="0" src="templates/{skin}/images/button-article-list.gif" width="20" height="20" alt="Category's pages"></a>
    </td>
    <td width="4%">
      <a href="?option={funname}&mode=info&id={list.cat_id}&pr={list.parent_id}&l={LANGUAGEID}">
      <img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update category information" width="20" height="20"></a>
    </td>
    <td width="4%">
      <a href="#" OnClick="doDelete({list.cat_id})">
      <img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete category	"></a>
    </td>
  </tr>
<!-- END list -->  
</table>
</div>  
  <p align="center" style="margin-top: 5; margin-bottom: 0"><font color="#FF0000"><b><span lang="en-us">{MESSAGE}</span></b></font></p>

<Script Language="JavaScript">
function doDelete(id)
{	if (confirm ("Are you sure you want to delete this category ?."))
	{	document.location = "?option=pages/categories&mode=delete&l={LANGUAGEID}&id=" + id
	}
}	
</Script>
<!-- End of template category_list -->