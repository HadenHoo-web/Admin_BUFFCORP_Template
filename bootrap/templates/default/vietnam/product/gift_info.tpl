<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về danh sách" width="20" height="20"><span>Về danh sách</span></a>

</div>
 <div class="tabtitle">
 Loại sản phẩm </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{gift_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td width="22%" height="25" >Gói quà:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="gift_name" notnull = 1 type="text" size="70" alt="Thương hiệu" value="{gift_name}" /> <input name="view" type="text" size="5" alt="Xem" value="{view}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >ID Sản phẩm tặng:</td>
    <td width="78%"><input name="product_id" type="text" size="70" value="{product_id}" /></td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#F0F0F0" height="25" style="padding-top: 3">Mô tả:</td>
    <td width="88%" bgcolor="#F0F0F0" height="25" valign="top">
<!-- begin: create hidden field for editor -->
	<pre id="Edit0" style="display:none; margin-top:3; margin-bottom:0">{intro_text}</pre>
	<input type="hidden" name="intro_text" value="">
<script language="JavaScript">
	var oEdit0 = new InnovaEditor("oEdit0");
	InitEditor(oEdit0, Edit0, 'js/editor/scripts/', '90%', 170) 
    </Script>			
<!-- end: create hidden field for editor -->    </td>
  </tr>
  <tr>
    <td width="20%">
    Cập nhật hình:</td>
    <td width="80%">
   <input type="file" name="new_image" size="41"  alt="Logo"></td>
  </tr>
	
	 <tr style="display:{allow}">
	 <td width="20%"></td>
		<td width="80%">
		{imgPath}
	  </tr>
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label for="fp1"> Active</label></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Slug:</td>
    <td width="78%"><input name="slug" notnull = 1 type="text" size="70" alt="Slug" value="{slug}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Meta key:</td>
    <td width="78%"><input name="meta_key" notnull = 1 type="text" size="70" alt="Meta key" value="{meta_key}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Meta des:</td>
    <td width="78%"><input name="meta_des" notnull = 1 type="text" size="70" alt="Meta des" value="{meta_des}" /></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Title seo:</td>
    <td width="78%"><input name="title_seo" notnull = 1 type="text" size="70" alt="title_seo" value="{title_seo}" /></td>
  </tr>
<tr style="visibility:{allow}">
      <td  colspan="2" >&nbsp;</td>
    </tr>
  </table>
   
</form><p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
</div>
<Script Language="JavaScript">

	function doSave()
	{	
		try{	
				mainForm.intro_text.value = oEdit0.getHTMLBody()
				//mainForm.main_text.value = oEdit1.getHTMLBody()
				//mainForm.quotation.value = oEdit2.getHTMLBody()				
		} catch(err){ return;}
		if (verify(mainForm))	
			mainForm.submit()
	}

	function returnToList()
	{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}
	mainForm.gift_name.focus()
</Script>