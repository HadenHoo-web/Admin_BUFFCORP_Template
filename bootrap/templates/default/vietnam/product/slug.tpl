<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar"> <a href="JavaScript:doSlug()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Slug</span></a>
</div>
<Script Language="JavaScript">
function doCreate()
{	
	document.location='?option={funname}&mode=info&l={LANGUAGEID}'
}
function doSlug()
{	
	document.location='?option={funname}&mode=slug'
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
<select size="1" name="product_type_id" id="myselect">
    	<option value="0" selected> Chọn chuyên mục</option>
<!-- BEGIN product_type_list -->
    	<option value="{product_type_list.product_type_id}">{product_type_list.parent_id} {product_type_list.product_type_name} - {product_type_list.product_type_code}</option>
<!-- END product_type_list -->
	</select>
 {von} : {loi} <input type="checkbox" name="isvip" value="1" {isvip} /> Theo dõi <input type="button" value="Search" onClick="reShow()" />
 </form>
<script language="javascript">mainForm.product_type_id.value = {product_type_id};</script>
</div>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="20" align="center" >#</td>
      <td width="80" align="left" >Mã SP</td>
      <td width="300" align="center" >Tên SP</td>
      
      <td width="300" align="center" >Slug</td>
      
      
      <td width="40" align="center" >Adv</td>
      <td width="60" align="center" >NV đăng</td>
      <td  colspan="2" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td style="vertical-align:middle">{list.product_code}</td>
      <td style="vertical-align:middle">{list.product_name}</td>
      
      <td align="left" style="vertical-align:middle">{list.slug}</td>
      
      
      <td><img border="0" src="templates/{skin}/images/check.gif" alt="Update" width="20" height="20" style="display:{list.isadv}"></td>
      <td>{list.created_by}</td>
      <td align="center"><a href="JavaScript:updateItem({list.product_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="41" align="center"><a href="JavaScript:deleteItem({list.product_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>

