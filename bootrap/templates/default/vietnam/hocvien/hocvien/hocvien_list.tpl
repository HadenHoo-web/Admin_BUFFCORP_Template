<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<div class="toolbar">
<a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new"><span>Thêm mới</span></a>
</div>
<Script Language="JavaScript">
function doReShow()
{	
	mainForm.submit();
}
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
function productList(id)
{	
	document.location = '?option=hocvien/product&mode=list&l={LANGUAGEID}&id=' + id
}	
function certificateList(id)
{	
	document.location = '?option=hocvien/certificate&mode=list&l={LANGUAGEID}&id=' + id
}		
</Script>
<div class="tabtitle">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" id="mainForm" style="margin:0px;" >
	<input type="hidden" name="option" id="option" value="{funname}">
	<input type="hidden" name="mode" id="mode" value="list">
	<input type="hidden" name="issearch" id="issearch" value="1">
Danh sách Học Viên: SN <select name="sinhnhat" size="1" {allow_month}><option value="0">All</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>
<!-- DO ComboFromTable("hocvien_type", "tbl_hocvien_type", "hocvien_type_id", "hocvien_type_name", "hocvien_type_id", 0, " All " , "0" , "1 order by hocvien_type_id" , "doReShow()", 1) --> 
NV Quản lý : 
<!-- DO ComboFromTable("member", "tbl_member", "member_id", "fullname", "member_id", 0, " All " , "" , "active=1 order by member_id" , "doReShow()", 1) -->
 SĐT <input name="tel1" type="text" size="10" alt="Điện thoại" value="{tel}" />
<input type="checkbox" name="ishc" value="1" {ishc} />HC <input type="checkbox" name="iskh" value="1" {iskh} />KH <input type="checkbox" name="istp" value="1" {istp} />TP
	  &nbsp;&nbsp; Sum : {sum} &nbsp;&nbsp; Actived : <font color="#FF0000">{actived}%</font> &nbsp;&nbsp; <input name="" type="submit" value="Search" style="height:18px; margin-top:4px;" />
</form>
</div>
<script>mainForm.hocvien_type_id.value = '{hocvien_type_id}';mainForm.member_id.value = '{member_id}';</script>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="30" align="center" >#</td>
      <td width="150" align="left" >Khách hàng</td>
      <td width="300" align="left" >Địa chỉ</td>
      <td width="65" align="left" >Điện thoại </td>
      <td width="20" align="left" >Face</td>
      <td width="65" align="left" >Sinh nhật</td>
      <td width="20" align="left" >Món</td>
      <td width="60" align="center" >Điểm</td>
      <td width="20" align="left" >HC</td>
      <td width="20" align="left" >KH</td>
      <td width="20" align="left" >TP</td>
      <td width="60" align="left" >Ngày tạo</td>
      <td width="70" align="left" >NV QL</td>
      <td  colspan="2" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.active}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td style="vertical-align:middle">{list.hocvien_name}</td>
      <td style="vertical-align:middle">{list.address}</td>
      <td align="center" style="vertical-align:middle">{list.tel}</td>
      <td align="center" style="vertical-align:middle">{list.face}</td>
      <td align="center" style="vertical-align:middle">{list.sinhnhat}</td>
      <td align="center" style="vertical-align:middle">{list.lan}</td>
      <td align="right">{list.tongcong}</td>
      <td align="center" style="vertical-align:middle"><img border="0" src="templates/{skin}/images/check.gif" alt="Update" width="20" height="20" style="display:{list.ishc}"></td>
      <td align="center" style="vertical-align:middle"><img border="0" src="templates/{skin}/images/check.gif" alt="Update" width="20" height="20" style="display:{list.iskh}"></td>
      <td align="center" style="vertical-align:middle"><img border="0" src="templates/{skin}/images/check.gif" alt="Update" width="20" height="20" style="display:{list.istp}"></td>
      <td align="center" style="vertical-align:middle">{list.created_date}</td>
      <td align="center" style="vertical-align:middle">{list.quanly}</td>
      <td width="20" align="center"><a href="?option={funname}&mode=info&l={LANGUAGEID}&id={list.hocvien_id}"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="25" align="center"><a href="JavaScript:deleteItem({list.hocvien_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>