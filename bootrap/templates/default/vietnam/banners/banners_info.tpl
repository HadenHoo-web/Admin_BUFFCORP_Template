<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
  <a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
  <a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về danh sách " width="20" height="20"><span>Về danh sách </span></a>
</div>
<div class="tabtitle">
 Tạo &amp; cập nhật banner
</div>
<div style="overflow:auto; height:80%">
<form action="main.php" name="mainForm" method="POST"  enctype="multipart/form-data">
<input type="hidden" name="l" value="{LANGUAGEID}">
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="mode" value="save">
<input type="hidden" name="id" value="{banner_id}">

<table  width="100%">
  <tr>
    <td width="109">Tiêu đề1:</td>
    <td><input type="text" name="title" value="{title}"  size="50" alt="Tiêu đề" notnull>
    </td>
  </tr>
<tr>
    <td>Link:</td>
    <td><input type="text" name="link" value="{link}"  size="50" alt="Link" notnull></td> 
</tr>
<tr>
    <td>Vị trí:</td>
    <td><select name="place">
  <option value="1">top</option>
  <option value="2">right</option>
</select></td> 
</tr>
<tr style="visibility:{allow} ">
    <td>Tên banner:</td>
    <td><input type="text" name="old_banner" value="{banner_name}" class="is_label"></td>
  </tr>
 <tr style="display:{display}">
 <td></td>
    <td>
    <a href="#" onClick="{imgPathLarge}" >{imgPath}</a>
  </tr>

   <tr>
      <td colspan=2>&nbsp;</td>
    </tr>
    <tr  style="visibility:{allow} ">
      <td  colspan="2"><b>Thông tin quản lý</b>
        <hr></td>
    </tr>
    <tr  style="visibility:{allow} ">
      <td>Ngày tạo:</td>
      <td><input type="text" name="posted_date" size="33" class="is_label" readonly value="{posted_date}"></td>
    </tr>
    <tr  style="visibility:{allow} ">
      <td>Người tạo:</td>
      <td><input type="text" name="posted_by" size="33" class="is_label" readonly value="{posted_by}"></td>
    </tr>
</table>
</form>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
	function doSave()
	{	
	if (verify(mainForm))	
		{	mainForm.submit()
		}
	}
	function returnToList()
	{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}
	mainForm.place.value = {place}
</Script>