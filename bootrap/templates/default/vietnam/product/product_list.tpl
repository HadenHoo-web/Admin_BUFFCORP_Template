<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar"> <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Tạo mới</span></a><a href="JavaScript:doSlug()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Slug</span></a><a href="JavaScript:doComment()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Tạo Sao</span></a>
</div>
<Script Language="JavaScript">
function doCreate()
{	
	document.location='?option={funname}&mode=info&l={LANGUAGEID}'
}
function doSlug()
{	if (confirm ("Are you sure you want to delete Slug?."))	
	document.location='?option={funname}&mode=slug'
}
function doComment()
{	if (confirm ("Are you sure you want to delete Comment?."))
	document.location='?option={funname}&mode=comment'
}
function reShow()
{	
	mainForm.submit();
}
function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}
function deleteItem(id)
{	if (confirm ("Are you sure you want to delete?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id + '&product_type_id=' + {product_type_id}
}	
</Script>
<div class="tabtitle">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" id="mainForm" style="margin:0px;" >
	<input type="hidden" name="option" id="option" value="{funname}">
	<input type="hidden" name="mode" id="mode" value="list">
&nbsp;Danh sách sản phẩm 
<select size="1" name="product_type_id1" id="myselect">
    	<option value="0" selected> Chọn chuyên mục</option>
<!-- BEGIN product_type_list -->
    	<option value="{product_type_list.product_type_id}">{product_type_list.parent_id} {product_type_list.product_type_name} - {product_type_list.product_type_code}</option>
<!-- END product_type_list -->
	</select>
 {von} : {loi} <input type="checkbox" name="ismain" value="1" {ismain} /> Chính thức <input type="checkbox" name="isvip" value="1" {isvip} /> Theo dõi <input type="checkbox" name="issl" value="1" {issl} /> Hết hàng <input name="hoaca_code1" id="hoaca_code1" type="text" notnull="1" alt="Mã Hoa Cà" value="{hoaca_code}" style="height:17px;" size="5"> Mã HC <input type="button" value="Search" onclick="reShow()" />
 </form>
<script language="javascript">mainForm.product_type_id1.value = {product_type_id};</script>
</div>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="25" align="center" >#</td>
      <td width="60" align="left" >Mã SP</td>
      <td width="40" align="left" >Mã HC</td>
      <td width="300" align="center" >Tên SP</td>
      <td width="93" align="center" >&nbsp;</td>
      <td width="93" align="center" >Giá bán</td>
      <td width="50" align="center" >Xem</td>
      <td width="50" align="center" >Comment</td>
      <td width="60" align="center" >Số lượng</td>
      <td width="60" align="center" >Order</td>
      <td width="40" align="center" >Adv</td>
      <td width="60" align="center" >NV đăng</td>
      <td  colspan="2" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td style="vertical-align:middle">{list.product_code}</td>
      <td align="right">{list.hoaca_code}</td>
      <td style="vertical-align:middle"><a href="{list.slug}" target="_blank" rel="nofollow">{list.product_name}</a></td>
      <td align="center" style="vertical-align:middle">{list.old_price}</td>
      <td align="center" style="vertical-align:middle">{list.price}</td>
      <td align="right">{list.view}</td>
      <td align="right">{list.num_comment}</td>
      <td style{list.bgcolor}="background:#FF0000;" align="right">{list.soluong}</td>
      <td style{list.bgcolor}="background:#FF0000;" align="right">{list.priority}</td>
      <td><img border="0" src="templates/{skin}/images/check.gif" alt="Update" width="20" height="20" style="display:{list.isadv}"></td>
      <td>{list.created_by}</td>
      <td align="center"><a href="?option={funname}&mode=info&l={LANGUAGEID}&id={list.product_id}"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="41" align="center"><a href="JavaScript:deleteItem({list.product_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>

