<Script Language="JavaScript">
function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}
function deleteItem(id)
{	if (confirm ("Are you sure you want to delete?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
}	
function doReShow()
{	
	mainForm.submit();
}
</Script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="tabtitle">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" id="mainForm" style="margin:0px;" >
	<input type="hidden" name="option" id="option" value="{funname}">
	<input type="hidden" name="mode" id="mode" value="list">
&nbsp;Danh sách đặt hàng : Năm <select name="year" size="1" onchange="doReShow()" {allow_month}><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option></select> Tháng <select name="month" size="1" onchange="doReShow()" {allow_month}><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select> Tổng cộng : <span style="color:#FF3535; font-weight:bold; font-size:20px;">{sum}</span> | Thành công : <span style="color:#CCFF66; font-weight:bold; font-size:20px;">{suc} ( {tilesuc}% )</span>
</form> 
</div>
<script>mainForm.year.value = '{year}'; mainForm.month.value = '{month}';</script>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="20" align="center" >#</td>
      <td width="70" align="left" >Sản phẩm</td>
      <td width="50" align="left" >Tên khách hàng</td>
      <td width="100" align="center" >Địa chỉ</td>
      <td width="50" align="center" >Tel</td>
      <td width="70" align="center" >Email</td>
      <td width="60" align="center" >Kết quả</td>
      <td width="50" align="center" >Ngày đặt hàng </td>
      <td width="40" align="center" >Mã giảm giá</td>
      <td width="40" align="center" >NV Quang Cáo</td>
      <td width="40" align="center" >NV liên hệ</td>
      <td width="6%" colspan="3" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td style="vertical-align:middle" class="{list.cancle}"><a href="https://casauhoaca.com/{list.slug}.htm" target="_blank">{list.product_name}</a></td>
      <td style="vertical-align:middle" class="{list.cancle}">{list.your_name}</td>
      <td align="center" style="vertical-align:middle">{list.address}</td>
      <td align="center" style="vertical-align:middle">{list.tel}</td>
      <td align="center" style="vertical-align:middle">{list.email}</td>
      <td align="center" style="vertical-align:middle">{list.ketqua}</td>
      <td align="center" style="vertical-align:middle">{list.created_date}</td>
      <td align="center" style="vertical-align:middle">{list.coupon}</td>
      <td align="center" style="vertical-align:middle"><span style="font-weight:bold; color:#F00; size:15px;">{list.created_by}</span></td>
      <td align="center" style="vertical-align:middle">{list.modified_by}</td>
      <td><img border="0" src="templates/{skin}/images/check.gif" alt="Update" width="20" height="20" style="{list.checked}"></td>
      <td width="5" align="center"><a href="JavaScript:updateItem({list.book_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="5" align="center"><a href="JavaScript:deleteItem({list.book_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>