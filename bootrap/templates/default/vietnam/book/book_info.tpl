<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>
<div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Return to book list" width="20" height="20"><span>Return to book list</span></a>
</div>
 <div class="tabtitle">
 Book information </div>
 <div style="overflow:auto; height:80%">
<form action="index.php" method="POST" enctype="multipart/form-data" name="mainForm">
<input type="hidden" name="l" value="{LANGUAGEID}" />
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="id" value="{book_id}">
<input type="hidden" name="mode" value="save">
	<table width="100%">
  <tr >
    <td height="25" ></td>
    <td><input type="checkbox" name="checked" value=1 {checked}><label for="fp1"> Check</label> <input type="checkbox" name="issuc" value=1 {issuc}><label for="fp1"> Thành công</label></td>
  </tr>
  <tr >
    <td width="20%" height="25" >Kết quả : </td>
    <td width="82%"><textarea name="ketqua" cols="50" rows="5">{ketqua}</textarea></td>
  </tr>
  <tr >
    <td width="20%" height="25" >Người gởi : </td>
    <td width="82%">{your_name}</td>
  </tr>
  <tr>
    <td >Địa chỉ : </td>
    <td >{address}</td>
  </tr>
  <tr>
    <td >Email : </td>
    <td >{email}</td>
  </tr>
  <tr>
    <td >Tel : </td>
    <td >{tel}</td>
  </tr>
  <tr>
    <td >Nội dung : </td>
    <td >{note}</td>
  </tr>
  <tr>
    <td >Hình thức thanh toán: </td>
    <td >{paytype}</td>
  </tr>
  <tr>
    <td >Sản phẩm: </td>
    <td ><a href="https://casauhoaca.com/{slug}.htm" target="_blank">{product_name}</a></td>
  </tr>
  <tr>
    <td >Ngày tạo: </td>
    <td >{created_date}</td>
  </tr>
  <tr>
    <td >Website: </td>
    <td >{web}</td>
  </tr>
  <tr>
    <td >Nhân viên QC: </td>
    <td >{created_by}</td>
  </tr>
  <tr>
    <td >Nhân viên xử lý: </td>
    <td >{modified_by}</td>
  </tr>
  <tr>
    <td >Mã giảm giá: </td>
    <td ><strong>{coupon}</strong></td>
  </tr>
  </table>
</form>
</div>
<Script Language="JavaScript">
function doSave()
{	
	mainForm.submit()		
}
function returnToList()
{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
}
</Script>