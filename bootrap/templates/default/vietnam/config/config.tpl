<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar"> <a href="#" onClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a></div>
<div class="tabtitle"><span class="header">Cấu hình hệ thống :</span></div>
<form method="POST" action="main.php" name="mainForm" OnSubmit="return checkform()">
    <input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="languageid" value="{LANGUAGEID}">
    <input type="hidden" name="mode" value="save">
    <input type="hidden" name="config_id" value="{config_id}">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table  width="100%" id="config" style="display:" >
    <tr>
      <td style="padding-left: 10"><FIELDSET style="PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 6px; MARGIN: 0px 5px 5px; PADDING-TOP: 10px; width:300;">
        <LEGEND>Thông tin liên hệ</LEGEND>
        <table  width="100%"  >
          <tr>
            <td width="33%" style="padding-left: 10" >Tên công ty:</td>
            <td width="67%"  ><input name="company_name" size="30" notnull=1 alt="Tên công ty" value="{company_name}"></td>
          </tr>
          <tr>
            <td width="33%" style="padding-left: 10">Địa chỉ:</td>
            <td width="67%"><input name="address" size="30" notnull=1 alt="Địa chỉ" value="{address}"></td>
          </tr>
          <tr>
            <td width="33%" style="padding-left: 10">Điện Thoại:</td>
            <td width="67%"><input name="phone" size="15" notnull=1 alt="Điện Thoại" value="{phone}"></td>
          </tr>
		  <tr>
            <td width="33%" style="padding-left: 10">Fax :</td>
            <td width="67%"><input name="fax" size="15" notnull="1" alt="Fax" value="{fax}" /></td>
          </tr>
          <tr>
            <td width="33%" style="padding-left: 10">Email:</td>
            <td width="67%"><input name="email" size="30" notnull=1 email=1 alt="Email" value="{email}"></td>
          </tr>
          <tr>
            <td width="33%" style="padding-left: 10">Website:</td>
            <td width="67%"><input name="website" size="30" notnull=1 alt="Website" value="{website}"></td>
          </tr>
        </table>
        </FIELDSET></td>
    </tr>
    <tr>
      <td style="padding-left: 10"><FIELDSET style="PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 6px; MARGIN: 0px 5px 5px; PADDING-TOP: 10px; width:300px;">
        <LEGEND>Hệ Thống</LEGEND>
        <table  width="100%"  >
          <tr>
            <td width="20%" style="padding-left: 10" >Tên website:</td>
            <td width="80%"  ><input name="website_name" size="30" notnull=1 alt="Tên website" value="{website_name}"></td>
          </tr>
          <tr>
            <td width="20%" style="padding-left: 10">Url:</td>
            <td width="80%"><input name="url" size="30" notnull=1 alt="Url" value="{url}"></td>
          </tr>
          <tr>
            <td width="20%" style="padding-left: 10">Email admin:</td>
            <td width="80%"><input name="email_admin" size="30" notnull=1 email=1 alt="Email admin" value="{email_admin}"></td>
          </tr>
		  <tr>
            <td width="20%" style="padding-left: 10">Skin:</td>
            <td width="80%"><input name="skin" size="30" notnull=1 alt="Skin" value="{skin}"></td>
          </tr>
		  <tr>
            <td width="20%" style="padding-left: 10">IMGPATH:</td>
            <td width="80%"><input name="IMGPATH" size="30" notnull=1 alt="Thư mục hình của skin" value="{IMGPATH}"></td>
          </tr>
		  <tr>
            <td width="20%" style="padding-left: 10">Main Css:</td>
            <td width="80%"><input name="maincss" size="30" notnull=1 alt="Main Css" value="{maincss}"></td>
          </tr>
		  <tr>
            <td width="20%" style="padding-left: 10">IMG_PATH:</td>
            <td width="80%"><input name="IMG_PATH" size="30" notnull=1 alt="IMG_PATH" value="{IMG_PATH}"></td>
          </tr>
		  <tr>
            <td width="20%" style="padding-left: 10">TEMPLATE_PATH:</td>
            <td width="80%"><input name="TEMPLATE_PATH" size="30" notnull=1 alt="TEMPLATE_PATH" value="{TEMPLATE_PATH}"></td>
          </tr>
        </table>
        </FIELDSET></td>	  
    </tr>
</table></td>
    <td valign="top"><FIELDSET style="PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 6px; MARGIN: 0px 5px 5px; PADDING-TOP: 10px; width:400px;">
        <LEGEND>Thông tin</LEGEND>
        <table  width="100%"  >
          <tr>
            <td width="50%" style="padding-left: 10" >Cat Thế giới bóp da:</td>
            <td width="50%"  ><input name="cat_bopda" size="30" alt="Cat Thế giới bóp da" value="{cat_bopda}"></td>
          </tr>
          <tr>
            <td width="50%" style="padding-left: 10" >Cat Thế giới hàng hiệu:</td>
            <td width="50%"  ><input name="cat_hanghieu" size="30" alt="Cat Thế giới hàng hiệu" value="{cat_hanghieu}"></td>
          </tr>
          <tr>
            <td width="50%" style="padding-left: 10" >Cat Tư vấn khách hàng:</td>
            <td width="50%"  ><input name="cat_khachhang" size="30" alt="Cat Tư vấn khách hàng" value="{cat_khachhang}"></td>
          </tr>
          <tr>
            <td width="50%" style="padding-left: 10" >Cat Thư giãn:</td>
            <td width="50%"  ><input name="cat_thugian" size="30" alt="Cat Thư giãn" value="{cat_thugian}"></td>
          </tr>
          <tr>
            <td width="50%" style="padding-left: 10" >Số ký tự trên URL:</td>
            <td width="50%"  ><input name="num_url" size="30" alt="Số ký tự trên URL" value="{num_url}"></td>
          </tr>
        </table>
        </FIELDSET>
		<FIELDSET style="PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 6px; MARGIN: 0px 5px 5px; PADDING-TOP: 10px; width:400px;">
        <LEGEND>Cấu hình từ khóa</LEGEND>
        <table  width="100%"  >
          <tr>
            <td width="50%" style="padding-left: 10" >Default Title:</td>
            <td width="50%"  ><input name="default_title" size="30" alt="Tiêu đề website" value="{default_title}"></td>
          </tr>
          <tr>
            <td width="50%" style="padding-left: 10" >Default keyword:</td>
            <td width="50%"  ><textarea name="default_keyword" cols="34" rows="8">{default_keyword}</textarea></td>
          </tr>
          <tr>
            <td width="50%" style="padding-left: 10" >Default description:</td>
            <td width="50%"  ><textarea name="default_description" cols="34" rows="8">{default_description}</textarea></td>
          </tr>
        </table>
        </FIELDSET></td>
  </tr>
</table>
</form>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
function doSave()
{	if (verify(document.mainForm)) 
		document.mainForm.submit() 
}
</Script>
