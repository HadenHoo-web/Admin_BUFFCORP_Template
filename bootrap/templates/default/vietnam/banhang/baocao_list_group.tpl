<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="js/jscalendar/calendar-system.css" title="system" />
  <script type="text/javascript" src="js/jscalendar/calendar.js"></script>
  <script type="text/javascript" src="js/jscalendar/calendar-vn.js"></script>
  <script type="text/javascript" src="js/jscalendar/calendar-setup.js"></script>
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
	<input type="hidden" name="mode" id="mode" value="baocao">
Báo Cáo Bán Hàng Gởi HC: <span style="display:{allow_display};">{sum_price}</span>    
<!-- DO ComboFromTable("banhang_kind_id", "tbl_banhang_kind", "banhang_kind_id", "banhang_kind_name", "banhang_kind_id", 0, " All " , "0" , "1 and active = 1 order by banhang_kind_id" , "doReShow()", 1) -->
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
<!-- DO ComboFromTable("member_id", "tbl_member", "member_id", "fullname", "member_id", 0, " All " , "0" , "member_id NOT IN ( 1, 31, 32, 33 ) order by member_id" , "doReShow()", 1) -->
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Xem theo ngày: Từ 
 <input name="from_date" id="from_date" type="text" notnull="1" alt="Ngày" size="10" readonly="1" value="{from_date}"> 
	  	<img id="img_from_date" src="templates/{skin}/images/cal.png" width="20" height="20" align="absmiddle" style="border:0px; margin:0px;"> -> <input name="to_date" id="to_date" type="text" notnull="1" alt="Ngày" size="10" readonly="1" value="{to_date}"> 
	  	<img id="img_to_date" src="templates/{skin}/images/cal.png" width="20" height="20" align="absmiddle" style="border:0px; margin:0px;"> &nbsp; <input type="checkbox" name="isimage" value="1" {isimage} /> Hình <input type="checkbox" name="isdang" value="1" {isdang} /> Dạng &nbsp;<input name="" type="submit" value="Search" style="height:18px; margin-top:4px;" /> <font color="#808080">{loinhuan}</font>
</form>
</div>
<script>mainForm.year.value = '{year}';mainForm.month.value = '{month}';mainForm.banhang_kind_id.value = '{banhang_kind_id}';mainForm.member_id.value = '{member_id}'</script>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="1101"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="20" align="center" >#</td>
      <td width="30" align="left" >Mã HC</td>
      <td width="200" align="left" >Sản phẩm</td>
      <td width="20" align="left" >SL</td>
      <td width="30" align="left" >Đơn giá</td>
      <td width="30" align="left" >Thành Tiền</td>
      <td width="30" align="left" >Đơn giá vốn</td>
      <td width="30" align="left" >Nộp</td>
      <td width="10%" align="left" >&nbsp;</td>
    </tr>
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle;">Sum</td>
      <td style="vertical-align:middle">{list.hoaca_code}</td>
      <td style="vertical-align:middle; text-align:left; color:#FF0000; font-weight:bold; "><a href="https://casauhoaca.com/{list.slug}.htm" target="_blank">{list.product_name}</a></td>
      <td style="vertical-align:middle; text-align:right;">{soluong}</td>
      <td style="vertical-align:middle; text-align:right;">{list.price}</td>
      <td style="vertical-align:middle; text-align:right;">{sum_price}</td>
      <td style="vertical-align:middle; text-align:right;">&nbsp;</td>
      <td style="vertical-align:middle; text-align:right;">{sum_gia_von}</td>
      <td style="vertical-align:middle; text-align:right;">&nbsp;</td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle;">{list.order}</td>
      <td style="vertical-align:middle">{list.hoaca_code}</td>
      <td style="vertical-align:middle; text-align:left; color:#FF0000; font-weight:bold; "><a href="https://casauhoaca.com/{list.slug}.htm" target="_blank">{list.product_name}</a></td>
      <td style="vertical-align:middle; text-align:right;">{list.sl}</td>
      <td style="vertical-align:middle; text-align:right;">{list.price}</td>
      <td style="vertical-align:middle; text-align:right;">{list.tri_gia}</td>
      <td style="vertical-align:middle; text-align:right;">{list.old_price}</td>
      <td style="vertical-align:middle; text-align:right;">{list.tri_gia_von}</td>
      <td style="vertical-align:middle; text-align:right;">&nbsp;</td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
	Calendar.setup({
  	inputField     :    "from_date",     // id of the input field
    ifFormat       :    "%Y-%m-%d",      // format of the input field
    button         :    "img_from_date",  // trigger for the calendar (button ID)
    align          :    "Tl",           // alignment (defaults to "Bl")
    singleClick    :    true
});
	Calendar.setup({
  	inputField     :    "to_date",     // id of the input field
    ifFormat       :    "%Y-%m-%d",      // format of the input field
    button         :    "img_to_date",  // trigger for the calendar (button ID)
    align          :    "Tl",           // alignment (defaults to "Bl")
    singleClick    :    true
});
</Script>