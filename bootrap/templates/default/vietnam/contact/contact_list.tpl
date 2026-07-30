<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<Script Language="JavaScript">
function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}
function deleteItem(id)
{	if (confirm ("Are you sure you want to delete?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
}	
</Script>
<div class="tabtitle">
&nbsp;Contact list 
</div>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="3%" align="center" >#</td>
      <td width="17%" align="left" >Sender</td>
      <td width="20%" align="center" >Email</td>
      <td width="14%" align="center" >Phone</td>
      <td width="14%" align="center" >Ngày tạo </td>
      <td width="9%" colspan="3" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td width="11%" align="center" style="vertical-align:middle">{list.order}</td>
      <td width="16%" style="vertical-align:middle" class="{list.cancle}">{list.your_name}</td>
      <td width="17%" align="center" style="vertical-align:middle">{list.email}</td>
      <td width="17%" align="center" style="vertical-align:middle">{list.phone}</td>
      <td width="17%" align="center" style="vertical-align:middle">{list.created_date}</td>
      <td><img border="0" src="templates/{skin}/images/check.gif" alt="Update" width="20" height="20" style="{list.checked}"></td>
      <td width="5" align="center"><a href="JavaScript:updateItem({list.contact_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="5" align="center"><a href="JavaScript:deleteItem({list.contact_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>