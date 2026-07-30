<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>
<div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Return to comment list" width="20" height="20"><span>Về danh sách</span></a>
</div>
 <div class="tabtitle">
 Nội dung Comment </div>
 <div style="overflow:auto; height:80%">
<form action="index.php" method="POST" enctype="multipart/form-data" name="mainForm">
<input type="hidden" name="l" value="{LANGUAGEID}" />
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="id" value="{comment_id}">
<input type="hidden" name="mode" value="save">
	<table width="100%">
  <tr >
    <td height="25" ></td>
    <td><input type="checkbox" name="checked" value=1 {checked}><label for="fp1"> Check</label></td>
  </tr>
  <tr >
    <td width="20%" height="25" >Người gởi : </td>
    <td width="82%">{your_name}</td>
  </tr>
  <tr>
    <td >Email : </td>
    <td >{email}</td>
  </tr>

  <tr>
    <td >Nội dung : </td>
    <td ><textarea name="content" cols="80" rows="8">{content}</textarea></td>
  </tr>
  <tr>
    <td >Loại: </td>
    <td >{comment_type}</td>
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
    <td >Nhân viên xử lý: </td>
    <td >{modified_by}</td>
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