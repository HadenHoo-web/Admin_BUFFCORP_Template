<Script Language="JavaScript">
function doDelete(id)
{	if (confirm ("Are you sure you want to delete this page ?."))
	{	document.location = "?option=pages/pages&mode=delete&l={LANGUAGEID}&page_id=" + id
	}
}
  function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}
</Script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- Begin of template category_list -->
<!-- Please do not make any change in this template file -->
<div class="toolbar">
  <a href="?option={funname}&mode=info&cid={cat_id}&l={LANGUAGEID}"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new page"><span>Tạo bài viết</span></a>    
	  <a href="?option=pages/categories&mode=list&pr={cat_id}&l={LANGUAGEID}">
      <span>..</span></a>
</div>
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" id="mainForm" style="margin:0px;" >
	<input type="hidden" name="option" id="option" value="{funname}">
	<input type="hidden" name="mode" id="mode" value="list">
  <input type="hidden" name="cid" id="mode" value="{cat_id}">
<div class="tabtitle">&nbsp;Page list&nbsp;
<!-- BEGIN catChain -->
  <font face="Arial" color="#00FFFF">»</font>&nbsp;&nbsp;<a href="?option={funname}&mode=list&cid={catChain.cat_id}&l={LANGUAGEID}">{catChain.cat_name}</a>&nbsp;
<!-- END catChain --> 
 <input value="{tungay}" name="tungay" size="6" type="text"/> -> <input value="{denngay}" name="denngay" size="6" type="text"/> <input type="submit" value="Search"/> S = <font color="blue"><b></b>{sumword}</b></font>
</div>
</form>
<div style="overflow:auto; height:80%">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
  <tr class="header">
    <td width="20" height="22">#</td>
    <td width="20" height="22">Mã Bài</td>	
    <td width="30%" height="22">Tiêu đề</td>
    <td width="50">Xem</td>
    <td width="50">Website</td>
    <td width="50">Ngày</td>
    <td width="50" height="22">Tác Giả</td>
    <td colspan="2" width="40" height="22">&nbsp;</td>
  </tr>
<!-- BEGIN list -->
  <tr class="{list.className}{list.status}">
    <td align="center" style="vertical-align: middle">{list.order}</td>
    <td style="vertical-align: middle">{list.alias}</td>
    <td style="vertical-align: middle"><a href="#/{list.slug}.htm" target="_blank">{list.title}</a></td>
    <td style="vertical-align: middle">{list.slug} ({list.countword})</td>
    <td style="vertical-align: middle">{list.website_name}</td>
    <td style="vertical-align: middle">{list.ngay}</td>
    <td style="vertical-align: middle">{list.created_by}</td>
    <td width="4%">
      <a OnClick="updateItem({list.page_id})">
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