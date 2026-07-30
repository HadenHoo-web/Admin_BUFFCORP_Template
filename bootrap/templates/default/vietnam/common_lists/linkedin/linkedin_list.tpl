<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<div class="toolbar">
<a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new"><span>Thêm mới</span></a>
</div>

<div class="tabtitle">Danh sách Tools</div>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="40" align="center" >#</td>
      <td width="300" align="left" >Tools</td>
      <td width="200" align="left" >Email or Phone</td>
      <td width="100" align="left" >Pass</td>
      <td width="100" align="left" >SĐT</td>
      <td width="200" align="left" >Ghi chú</td>
      <td width="60" align="left" >Active</td>
      <td  colspan="4" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td style="vertical-align:middle"><a href="{list.linkedin_name}" target="_blank">{list.linkedin_name}</a></td>
      <td style="vertical-align:middle">{list.email_name}</td>
      <td style="vertical-align:middle">{list.pass}</td>
      <td style="vertical-align:middle">{list.tel_name}</td>
      <td width="70" style="vertical-align:middle">{list.ghichu}</td>
      <td width="70" align="center" style="vertical-align:middle"><span style="display:{list.active}"><font linkedin="Wingdings"><b><font size="3" color="#008000">ü</font></b></font></span></td>
      <td width="20" align="center"><a href="?option={funname}&mode=up&id={list.linkedin_id}&l={LANGUAGEID}"  target="_self"> <img border="0" src="templates/{skin}/images/up.png" width="16" height="16" style="{list.up}"></a> </td>
      <td width="20" align="center"><a href="?option={funname}&mode=down&id={list.linkedin_id}&l={LANGUAGEID}" target="_self"> <img  style="{list.down}" border="0" src="templates/{skin}/images/down_blue.png" width="16" height="16"></a></td>
      <td width="24" align="center"><a href="JavaScript:updateItem({list.linkedin_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="35" align="center"><a href="JavaScript:deleteItem({list.linkedin_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
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
</Script>

