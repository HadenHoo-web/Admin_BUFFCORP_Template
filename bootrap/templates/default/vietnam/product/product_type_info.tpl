<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<script src="js/ckeditor/_samples/sample.js" type="text/javascript"></script>
<link href="js/ckeditor/_samples/sample.css" rel="stylesheet" type="text/css" />
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về danh sách" width="20" height="20"><span>Về danh sách</span></a>

</div>
 <div class="tabtitle">
 Thương Hiệu </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{product_type_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td height="25" >Cấp cha<font color="#FF0000">*</font></td>
    <td width="88%">
    <!-- DO ComboFromTable("parent_id", "tbl_product_types", "product_type_id", "product_type_name", "product_type_id", 0, " Chọn loại SP" , "0" , "1 order by priority" , "", 1) --></td>
  </tr>
  <tr >
    <td height="25" >Thương hiệu:<font color="#FF0000">*</font></td>
    <td width="88%"><input name="product_type_name" notnull = 1 type="text" size="40" alt="Thương hiệu" value="{product_type_name}" /> Code <input name="product_type_code" notnull = 1 type="text" size="3" alt="Mã thương hiệu" value="{product_type_code}" /> Slug <input name="slug" notnull = 1 type="text" size="20" alt="Slug" value="{slug}" /> Bài liên quan<input name="post_page" type="text" size="3" value="{post_page}" /> Priority<input name="priority" type="text" size="3" value="{priority}" /></td>
  </tr>
  <tr >
    <td height="25" >Xem:</td>
    <td width="88%"><input name="view" type="text" size="5" alt="Xem" value="{view}" /> Price <input name="price" type="text" size="5" alt="Price" value="{price}" /> Số lượng tồn kho <input name="soluong" type="text" size="5" alt="Số lượng tồn kho" value="{soluong}" {readonly} /> Định mức tồn kho <input name="dinhmuc" type="text" size="5" alt="Định mức kho" value="{dinhmuc}" {readonly} /> Tồn kho đầu kỳ <input name="tondau" type="text" size="5" alt="Tồn kho đầu kỳ" value="{tondau}" {readonly} /></td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#F0F0F0" height="25" style="padding-top: 3">Top Text:</td>
    <td width="88%" bgcolor="#F0F0F0" height="25" valign="top">
<textarea cols="80" id="top_text" name="top_text" rows="10">{top_text}</textarea>
<script type="text/javascript">CKEDITOR.replace( 'top_text',{height : 400});</script>
	</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#F0F0F0" height="25" style="padding-top: 3">Footer Text:</td>
    <td width="88%" bgcolor="#F0F0F0" height="25" valign="top">
<textarea cols="80" id="intro_text" name="intro_text" rows="10">{intro_text}</textarea>
<script type="text/javascript">CKEDITOR.replace( 'intro_text',{height : 400});</script>
	</td>
  </tr>
  <tr >
    <td height="25" >Meta Title:<font color="#FF0000">*</font></td>
    <td width="88%"><textarea name="title_seo" cols="140" rows="2">{title_seo}</textarea></td>
  </tr>
  <tr >
    <td height="25" >Meta Key:<font color="#FF0000">*</font></td>
    <td width="88%"><textarea name="meta_key" cols="140" rows="2">{meta_key}</textarea></td>
  </tr>
  <tr >
    <td height="25" >Meta Descirption:<font color="#FF0000">*</font></td>
    <td width="88%"><textarea name="meta_des" cols="140" rows="5">{meta_des}</textarea></td>
  </tr>
  <tr >
    <td height="25" >Meta Schema:<font color="#FF0000">*</font></td>
    <td width="88%"><textarea name="meta_schema" cols="140" rows="10" readonly="readonly">{meta_schema}</textarea></td>
  </tr>
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label for="fp1"> Active</label> <input type="checkbox" name="isnu" value="1" id="fp1" {isnu} /> <label for="fp1"> Dành cho nữ</label> <input type="checkbox" name="isnam" value="1" id="fp1" {isnam} /> <label for="fp1"> Dành cho nam</label></td>
  </tr>
  <tr>
    <td width="12%">
    Cập nhật logo:</td>
    <td width="88%">
   <input type="file" name="logo" size="40"  alt="Logo"></td>
  </tr>
  <tr style="display:{allow_logo}">
    <td >Xem Logo :</td>
    <td ><input name="old_logo" size="41" value="{logo}" readonly="Readonly" />
      <input type="checkbox" name="remove_logo" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr style="display:{allow}">
	 <td width="12%"></td>
		<td width="88%">
		{logoPath}	  </tr>
  <tr>
    <td width="12%">
    Cập nhật hình:</td>
    <td width="88%">
   <input type="file" name="image" size="40"  alt="Image"></td>
  </tr>
  <tr style="display:{allow}">
    <td >Xem hình :</td>
    <td ><input name="old_image" size="41" value="{image}" readonly="Readonly" />
      <input type="checkbox" name="remove_image" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr style="display:{allow}">
	 <td width="12%"></td>
		<td width="88%">
		{imgPath}	  </tr>
  <tr>
    <td width="12%">
    Cập nhật hình Hoa Cà:</td>
    <td width="88%">
   <input type="file" name="image_hoaca" size="40"  alt="Image Hoa Cà"></td>
  </tr>
  <tr style="display:{allow_hoaca}">
    <td >Xem hình :</td>
    <td ><input name="old_image_hoaca" size="41" value="{image_hoaca}" readonly="Readonly" />
      <input type="checkbox" name="remove_image_hoaca" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr style="display:{allow_hoaca}">
	 <td width="12%"></td>
		<td width="88%">
		{imgHoaCaPath}	  </tr>
<tr style="visibility:{allow}">
      <td  colspan="2" >&nbsp;</td>
    </tr>
    <tr >
    <td height="25" >Script:<font color="#FF0000">*</font></td>
    <td width="88%"><textarea cols="80" id="script" name="script" rows="10">{script}</textarea>
<script type="text/javascript">CKEDITOR.replace( 'script',{height : 400});</script></td>
  </tr>
    <tr style="display:none">
      <td width="20%" style="padding-left: 10" height="23">Template (look &amp; feel):</td>
      <td width="80%" height="23">{template_list}</td>
    </tr>
    <tr>
      <td width="100%" style="padding-left: 10" colspan="2" height="23">&nbsp;</td>
    </tr>
    <tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
      <td width="100%" style="padding-left: 10" colspan="2" height="23"><b>Administration</b><hr></td>
    </tr>
    <tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
      <td width="20%" style="padding-left: 10" height="23">Create date:</td>
      <td width="80%" height="23"><input type="text" name="T1" size="33" class="is_label" readonly value="{created_date}"></td>
    </tr>
    <tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
      <td width="20%" style="padding-left: 10" height="23">Create by:</td>
      <td width="80%" height="23"><input type="text" name="created_by" size="33" class="is_label" readonly value="{created_by}"></td>
    </tr>
    <tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
      <td width="20%" style="padding-left: 10" height="23">Last_modified</td>
      <td width="80%" height="23"> 
        <input type="text" name="last_modified" size="33" class="is_label" readonly value="{last_modified}">
      </td>
    </tr>
    <tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
      <td width="20%" style="padding-left: 10" height="23">Modified by</td>
      <td width="80%" height="23"><input type="text" name="modified_by" size="33" class="is_label" readonly value="{modified_by}"></td>
    </tr>
  </table>
   
</form><p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
</div>
<Script Language="JavaScript">

	function doSave()
	{	
		if (verify(mainForm))	
			mainForm.submit()
	}

	function returnToList()
	{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}
	mainForm.parent_id.value = {parent_id};
</Script>