<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar"> <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Tạo mới</span></a> <a href="JavaScript:doRefesh()"><img border="0" src="templates/{skin}/images/reload.png" alt="Xem lại"><span>Xem lại</span></a>
</div>
<Script Language="JavaScript">

function doCreate()
{	
	document.location='?option={funname}&mode=info&l={LANGUAGEID}'
}
function doRefesh()
{	
	document.location='?option={funname}&mode=list'
}
function doReShow()
{	
	//document.location='?option={funname}&mode=list&l={LANGUAGEID}&month=' + mainForm.month.value + '&banhang_kind_id=' + mainForm.banhang_kind_id.value
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
Danh sách: <span style="display:{allow_display};">{sum}</span>Năm <select name="year" size="1" onchange="doReShow()" {allow_month}><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option></select> Tháng <select name="month" size="1" onchange="doReShow()" {allow_month}><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>
<!-- DO ComboFromTable("banhang_kind_id", "tbl_banhang_kind", "banhang_kind_id", "banhang_kind_name", "banhang_kind_id", 0, " All " , "0" , "1 and active = 1 order by banhang_kind_id" , "doReShow()", 1) -->
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
<!-- DO ComboFromTable("member_id", "tbl_member", "member_id", "fullname", "member_id", 0, " All " , "0" , "member_id NOT IN ( 1, 31, 33 ) and active = 1 order by member_id" , "doReShow()", 1) -->
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Xem theo ngày: <input name="from_date" id="from_date" type="text" alt="Ngày bán" size="8" value="{from_date}" style="height:17px;"> -> <input name="to_date" id="to_date" type="text" notnull="1" alt="Ngày bán" size="8" value="{to_date}" style="height:17px;"> 
	  &nbsp; <input type="checkbox" name="isimage" value="1" {isimage} /> Hình &nbsp;<input name="" type="submit" value="Search" style="height:18px; margin-top:4px;" /> <font color="#808080">{loinhuan}</font>
</form>
</div>
<script>mainForm.year.value = '{year}';mainForm.month.value = '{month}';mainForm.banhang_kind_id.value = '{banhang_kind_id}';mainForm.member_id.value = '{member_id}'</script>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="1101"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="20" align="center" >#</td>
      <td width="58" align="left" >Ngày</td>
      <td width="42" align="left" >Giờ</td>
      <td width="52" align="left" >Số tiền</td>
      <td width="{allow_ln}" align="left" ></td>
      <td width="{allow_ln}" align="left" ></td>
      <td width="250" align="left" >Sản phẩm</td>
      <td width="160" align="left" >Khách hàng</td>
      <td width="160" align="left" >Ghi Chú</td>
      <td width="70" align="left" >Nhân viên</td>
      <td width="60" align="left" >Hình</td>
      <td  colspan="2" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle;background:{list.bg}">{list.order}</td>
      <td style="vertical-align:middle">{list.ngay}</td>
      <td style="vertical-align:middle">{list.gio}</td>
      <td style="vertical-align:middle; text-align:right; color:#FF0000; font-weight:bold; ">{list.banhang_name}</td>
      <td style="vertical-align:middle; text-align:right; color:#FF0000; font-weight:bold;">{list.giagoc}</td>
      <td style="vertical-align:middle; text-align:right; color:#FF0000; font-weight:bold;">{list.loinhuan}</td>
      <td style="vertical-align:middle; text-align:right;"><a href="{list.slug}" target="_blank" rel=nofollow>{list.product_name}</a> (<font color="#0000FF">{list.soluong}</font>)</td>
      <td style="vertical-align:middle"><a href="main.php?option=customer/customer&mode=info&id={list.customer_id}" target="_blank">{list.customer_name} - {list.tel}</a></td>
      <td style="vertical-align:middle">{list.banhang_code}</td>
      <td style="vertical-align:middle">{list.created_by}</td>
      <td style="vertical-align:middle"><img src="https://bopda.net/images/product/{list.image0}" width="80" style="display:{allow_image};"  /></td>
      <td width="30" align="center"><a href="JavaScript:updateItem({list.banhang_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="42" align="center"><a href="JavaScript:deleteItem({list.banhang_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>