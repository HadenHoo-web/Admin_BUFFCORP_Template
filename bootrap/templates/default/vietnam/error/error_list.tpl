<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<div class="toolbar"> <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new"><span>Create new</span></a>
</div>

<div class="tabtitle">Danh sách Lỗi </div>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="40" align="center" >#</td>
      <td width="450" align="left" >Link bị lỗi </td>
      <td width="450" align="left" >Link trước đó</td>
      <td width="110" align="left" >Thời gian</td>
      <td width="60" align="left" >IP khách</td>
      <td></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td style="vertical-align:middle"><a href="{list.url_error}" target="_blank">{list.url_error}</a></td>
      <td style="vertical-align:middle"><a href="{list.pre_url_error}" target="_blank">{list.pre_url_error}</a></td>
      <td style="vertical-align:middle">{list.time_error}</td>
      <td style="vertical-align:middle">{list.ip_error}</td>
      <td width="42" align="center"><a href="JavaScript:deleteItem({list.error_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">

function doCreate()
{	
	document.location='?option={funname}&parent_id={parent_id}&mode=info&l={LANGUAGEID}'
}

function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}

function subItem(id)
{	document.location = '?option={funname}&mode=list&l={LANGUAGEID}&parent_id=' + id
}

function deleteItem(id)
{	if (confirm ("Are you sure you want to delete ?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
}	
</Script>

