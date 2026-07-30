<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar"> <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Tạo mới</span></a>
</div>
<div class="tabtitle">&nbsp;Danh sách sản phẩm 
<!-- DO ComboFromTable("product_type_id", "tbl_product_types", "product_type_id", "product_type_name", "product_type_id", 0, " Chọn hãng sản xuất" , "0" , "1 order by product_type_name" , "reShow()", 1) -->
 {von} : {loi}
</div>
<Script Language="JavaScript">
product_type_id.value = {product_type_id}
function doCreate()
{	
	document.location='?option={funname}&mode=info&l={LANGUAGEID}'
}
function reShow()
{	
	document.location='?option={funname}&mode=list&l={LANGUAGEID}&product_type_id=' + product_type_id.value
}
function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}
function deleteItem(id)
{	if (confirm ("Are you sure you want to delete?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id + '&product_type_id=' + {product_type_id}
}	
</Script>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="50" align="center" >#</td>
      <td width="120" align="left" >Mã SP</td>
      <td width="250" align="center" >Tên SP</td>
      <td width="93" align="center" >&nbsp;</td>
      <td width="93" align="center" >Giá bán</td>
      <td width="60" align="center" >Xem</td>
      <td width="60" align="center" >Số lượng</td>
      <td width="60" align="center" >Adv</td>
      <td  colspan="2" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td style="vertical-align:middle">{list.product_code}</td>
      <td align="center" style="vertical-align:middle">{list.product_name}</td>
      <td align="center" style="vertical-align:middle">{list.old_price}</td>
      <td align="center" style="vertical-align:middle">{list.price}</td>
      <td>{list.view}</td>
      <td style{list.bgcolor}="background:#FF0000;">{list.soluong}</td>
      <td><img border="0" src="templates/{skin}/images/check.gif" alt="Update" width="20" height="20" style="display:{list.isadv}"></td>
      <td align="center"><a href="JavaScript:updateItem({list.product_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="41" align="center"><a href="JavaScript:deleteItem({list.product_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>

