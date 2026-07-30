<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
<div class="toolbar"> <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Tạo mới</span></a>
</div>

<div class="tabtitle">Danh sách</div>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="30" align="center" >#</td>
      <td width="160" align="left" ><div align="center">Thương hiệu</div></td>
      <td width="30" align="left" ><div align="center">CODE</div></td>
      <td width="30" align="left" ><div align="center">Nam</div></td>
      <td width="30" align="left" ><div align="center">Nữ</div></td>
      <td width="30" align="left" ><div align="center">Active</div></td>
      <td width="40" align="left" ><div align="center">Xem</div></td>
      <td width="70" align="left" ><div align="center">SL tại CH</div></td>
      <td width="70" align="left" ><div align="center">SL Web</div></td>
      <td width="{width_dinhmuc}" align="left" ></td>
      <td width="30" align="center" >Priority</td>
      <td width="120" align="center" >Slug</td>
      <td  colspan="4" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td styl{list.parent_id}e="vertical-align:middle;font-weight:bold;color:#FF0000;">{list.parent_id}{list.product_type_name}</td>
      <td style="vertical-align:middle">{list.product_type_code}</td>
      <td style="vertical-align:middle">{list.isnam}</td>
      <td style="vertical-align:middle">{list.isnu}</td>
      <td style="vertical-align:middle">{list.active}</td>
      <td align="right" style="vertical-align:middle">{list.view}</td>
      <td align="right" style="vertical-align:middle;background:{list.bg};font-weight:bold;color:#FF0000;">{list.soluong}</td>
      <td align="right" bgcolor="{list.bg}" style="vertical-align:middle;font-weight:bold;color:#FF0000;">{list.dem}</td>
      <td align="right" style="vertical-align:middle;background:{list.bg_dinhmuc};font-weight:bold;color:#FF0000;">{list.dinhmuc}</td>
      <td align="right" style="vertical-align:middle;background:{list.bg_dinhmuc};font-weight:bold;color:#FF0000;">{list.priority}</td>
      <td align="right" style="vertical-align:middle;background:{list.bg_dinhmuc};font-weight:bold;color:#FF0000;">{list.slug}</td>
      <td width="23" align="center"><a href="?option={funname}&mode=up&id={list.product_type_id}&l={LANGUAGEID}"  target="_self"> <img border="0" src="templates/{skin}/images/up.png" width="16" height="16" style="{list.up}"></a> </td>
      <td width="28" align="center"><a href="?option={funname}&mode=down&id={list.product_type_id}&l={LANGUAGEID}" target="_self"> <img  style="{list.down}" border="0" src="templates/{skin}/images/down_blue.png" width="16" height="16"></a></td>
      <td width="30" align="center"><a href="JavaScript:updateItem({list.product_type_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="42" align="center"><a href="JavaScript:deleteItem({list.product_type_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>