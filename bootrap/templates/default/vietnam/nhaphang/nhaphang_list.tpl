<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar"> <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Tạo mới</span></a>
</div>
<Script Language="JavaScript">
function doCreate()
{	
	document.location='?option={funname}&mode=info&l={LANGUAGEID}'
}
function doReShow()
{	
	//document.location='?option={funname}&mode=list&l={LANGUAGEID}&month=' + mainForm.month.value + '&product_type_id=' + mainForm.product_type_id.value
	mainForm.submit();
}

function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}

function deleteItem(id)
{	if (confirm ("Are you sure you want to delete ?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
}	
</Script>
<div class="tabtitle">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" id="mainForm" style="margin:0px;" >
	<input type="hidden" name="option" id="option" value="{funname}">
	<input type="hidden" name="mode" id="mode" value="list">
Danh sách nhập hàng: Tháng <select name="month" size="1" onchange="doReShow()" {allow_month}><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>
<select size="1" name="product_id" id="myselect" onchange="doReShow()">
    	<option value="0" selected> Chọn sản phẩm</option>
<!-- BEGIN list_product -->
        <option value="{list_product.product_id}">{list_product.hoaca_code} - {list_product.product_name} - Giá {list_product.price} vnđ (<font color="#FF0000">{list_product.soluong}</font>)</option>
        <!-- END list_product -->
	</select>
<!-- DO ComboFromTable("member_id", "tbl_member", "member_id", "fullname", "member_id", 0, " All " , "0" , "member_id NOT IN ( 1, 28 ) order by member_id" , "doReShow()", 1) -->
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Xem theo ngày: <input name="from_date" id="from_date" type="text" alt="Ngày bán" size="8" value="{from_date}" style="height:17px;"> -> <input name="to_date" id="to_date" type="text" notnull="1" alt="Ngày bán" size="8" value="{to_date}" style="height:17px;"> 
	  &nbsp;&nbsp;<input name="" type="submit" value="Search" style="height:18px; margin-top:4px;" />
</form>
</div>
<script>mainForm.month.value = '{month}';mainForm.product_id.value = '{product_id}';mainForm.member_id.value = '{member_id}'</script>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="1101"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="20" align="center" >#</td>
      <td width="60" align="left" >Ngày</td>
      <td width="50" align="left" >Giờ</td>
	  <td width="150" align="left" >Sản phẩm</td>
      <td width="100" align="left" >Số lượng ( {sum} )</td>
      <td width="450" align="left" >Lý do</td>
      <td width="90" align="left" >Nhân viên</td>
      <td  colspan="2" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle;background:{list.bg}">{list.order}</td>
      <td style="vertical-align:middle">{list.ngay}</td>
      <td style="vertical-align:middle">{list.gio}</td>
	  <td style="vertical-align:middle; text-align:right;"><a href="{website}/{list.slug}.htm" target="_blank">{list.product_name}</a></td>
      <td style="vertical-align:middle; text-align:right; color:#FF0000; font-weight:bold; ">{list.nhap_xuat} {list.nhaphang_name}</td>
      <td style="vertical-align:middle">{list.nhaphang_code}</td>
      <td style="vertical-align:middle">{list.created_by}</td>
      <td width="30" align="center"><a href="JavaScript:updateItem({list.nhaphang_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="42" align="center"><a href="JavaScript:deleteItem({list.nhaphang_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>