<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<script src="js/ckeditor/_samples/sample.js" type="text/javascript"></script>
<link href="js/ckeditor/_samples/sample.css" rel="stylesheet" type="text/css" />
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về danh sách" width="20" height="20"><span>Về danh sách</span></a>
<a href="#" onClick="doSaveCopy()"><img border="0" src="templates/{skin}/images/button-save.gif" alt="Save & Copy" width="20" height="20"><span>Save & Copy</span></a>
<a href="#" onClick="doDelete({product_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" alt="List pages" width="20" height="20"><span>Xóa bài</span></a>
</div>
 <div class="tabtitle">
 Thông tin sản phẩm </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{product_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="ismain" value="1" id="fp1" {ismain} /> <b>Chính thức</b> <input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label for="fp1"> Kích hoạt</label> <input type="checkbox" name="isadv" value="1" id="fp1" {isadv} /> Is adv   <input type="checkbox" name="isvip" value="1" id="vip" {isvip} /> isVip   <input name="view" alt="Xem"  size="3" value="{view}"> Đã xem ; Nhân viên đăng : {created_by}  Thứ tự <input name="priority" alt="Thứ tự"  size="3" value="{priority}"></td>
  </tr>
  <tr>
    <td>Hãng sản xuất:<font color="#FF0000">*</font> </td>
    <td>
    <select size="1" name="product_type_id" id="myselect" onchange="typeChange(document.getElementById('myselect'))">
		<option value="0" selected> Chọn chuyên mục</option>
        <!-- BEGIN catlist -->
        <option value="{catlist.product_type_id}">{catlist.product_type_name} - {catlist.product_type_code}</option>
        <!-- END catlist -->
      </select>
<!-- DO ComboFromTable("product_kind_id", "tbl_product_kinds", "product_kind_id", "product_kind_name", "product_kind_id", 0, " Chọn loại SP" , "0" , "active = 1 order by priority" , "", 1) -->
<!-- DO ComboFromTable("skin_id", "tbl_skin", "skin_id", "skin_name", "skin_id", 0, " Chọn loại Da" , "0" , "active = 1 order by priority" , "", 1) -->
<!-- DO ComboFromTable("color_id", "tbl_color", "color_id", "color_name", "color_id", 0, " Chọn màu sắc" , "0" , "active = 1 order by priority" , "", 1) -->
<select name="source">
    	<option value="0">Chọn nguồn hàng</option>
        <option value="1">Hoa Cà</option>
		<option value="2">A Minh</option>
		<option value="3">A Nhã</option>
		<option value="4">A Thành</option>
		<option value="5">A Hậu</option>
		<option value="6">T Hưng</option>
    </select>
<!-- DO ComboFromTable("gift_id", "tbl_gifts", "gift_id", "gift_name", "gift_id", 0, " Chọn quà tặng" , "0" , "active = 1 order by priority" , "", 1) -->
	</td>
  </tr>
 <script>
 	mainForm.product_type_id.value 	= {product_type_id}
	mainForm.product_kind_id.value 	= {product_kind_id}
	mainForm.skin_id.value 			= {skin_id}
	mainForm.color_id.value 		= {color_id}
	mainForm.gift_id.value 			= {gift_id}
	mainForm.source.value 			= {source}
 </script>
  <tr >
    <td height="25" >Mã SP:<font color="#FF0000">*</font></td>
    <td><input name="product_code" notnull = 1 type="text" size="7" alt="Mã SP" value="{product_code}"> Tên SP: <font color="#FF0000">*</font> <input name="product_name" size="100" notnull = 1 alt="Tên SP" value="{product_name}"></td>
  </tr>
  <tr>
    <td >Kích cỡ:</td>
    <td ><input name="size" alt="Kích cỡ"  size="12" value="{size}"> <span style="display:{isadmin}">Giá web: <input name="old_price" type="text" size="7" value="{old_price}" /></span> Giá bán: <input name="price" type="text" size="7" value="{price}"> Giá cũ: <input name="sale_price" type="text" size="7" value="{sale_price}"> Số lượng <input name="soluong" type="text" size="1" value="{soluong}" number = 1 {readonly}> Mã Hoa Cà <input name="hoaca_code" type="text" size="5" alt="Mã Hoa Cà" value="{hoaca_code}"> CK <input name="chietkhau" type="text" size="5" alt="CK" value="{chietkhau}"></td>
  </tr>
  <tr>
    <td >Link gốc:</td>
    <td ><input name="website" type="text" size="100" value="{website}"></td>
  </tr>
  <tr>
    <td width="20%" >Mô tả:</td>
    <td width="82%" >  
<textarea cols="80" id="description" name="description" rows="10">{description}</textarea>
<script type="text/javascript">CKEDITOR.replace( 'description',{height : 400});</script>
	</td>
  </tr>
  <tr>
    <td>Hình nhỏ:</td>
    <td><input name="image0" type="file" size="25" value="{image0}" />     </td>
  </tr>
  <tr style="display:{allow_image0}">
    <td >Xem hình 1:</td>
    <td ><input name="old_image0" size="41" value="{image0}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image0}')" />
      &nbsp;
      <input type="checkbox" name="remove_image0" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 1:</td>
    <td><input name="image1" type="file" size="25" value="{image1}" /> Chú thích hình 1 <input name="alt_img1" type="text" size="30" alt="Chú thích cho hình 1" value="{alt_img1}" />    </td>
  </tr>
  <tr style="display:{allow_image1}">
    <td >Xem hình 1:</td>
    <td ><input name="old_image1" size="41" value="{image1}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image1}')" />
      &nbsp;
      <input type="checkbox" name="remove_image1" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 2:</td>
    <td><input name="image2" type="file" size="25" value="{image2}" /> Chú thích hình 2 <input name="alt_img2" type="text" size="30" alt="Chú thích cho hình 2" value="{alt_img2}" />   </td>
  </tr>
  <tr style="display:{allow_image2}">
    <td >Xem hình 2:</td>
    <td ><input name="old_image2" size="41" value="{image2}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image2}')" />
      &nbsp;
      <input type="checkbox" name="remove_image2" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 3:</td>
    <td><input name="image3" type="file" size="25" value="{image3}" /> Chú thích hình 3 <input name="alt_img3" type="text" size="30" alt="Chú thích cho hình 3" value="{alt_img3}" />  </td>
  </tr>
  <tr style="display:{allow_image3}">
    <td >Xem hình 3:</td>
    <td ><input name="old_image3" size="41" value="{image3}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image3}')" />
      &nbsp;
      <input type="checkbox" name="remove_image3" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 4:</td>
    <td><input name="image4" type="file" size="25" value="{image4}" /> Chú thích hình 4 <input name="alt_img4" type="text" size="30" alt="Chú thích cho hình 4" value="{alt_img4}" />  </td>
  </tr>
  <tr style="display:{allow_image4}">
    <td >Xem hình 4:</td>
    <td ><input name="old_image4" size="41" value="{image4}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image4}')" />
      &nbsp;
      <input type="checkbox" name="remove_image4" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 5:</td>
    <td><input name="image5" type="file" size="25" value="{image5}" /> Chú thích hình 5 <input name="alt_img5" type="text" size="30" alt="Chú thích cho hình 5" value="{alt_img5}" />  </td>
  </tr>
  <tr style="display:{allow_image5}">
    <td >Xem hình 5:</td>
    <td ><input name="old_image5" size="41" value="{image5}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image5}')" />
      &nbsp;
      <input type="checkbox" name="remove_image5" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 6:</td>
    <td><input name="image6" type="file" size="25" value="{image6}" /> Chú thích hình 6 <input name="alt_img6" type="text" size="30" alt="Chú thích cho hình 6" value="{alt_img6}" />  </td>
  </tr>
  <tr style="display:{allow_image6}">
    <td >Xem hình 6:</td>
    <td ><input name="old_image6" size="41" value="{image6}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image6}')" />
      &nbsp;
      <input type="checkbox" name="remove_image6" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 7:</td>
    <td><input name="image7" type="file" size="25" value="{image7}" /> Chú thích hình 7 <input name="alt_img7" type="text" size="30" alt="Chú thích cho hình 7" value="{alt_img7}" />  </td>
  </tr>
  <tr style="display:{allow_image7}">
    <td >Xem hình 7:</td>
    <td ><input name="old_image7" size="41" value="{image7}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image7}')" />
      &nbsp;
      <input type="checkbox" name="remove_image7" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 8:</td>
    <td><input name="image8" type="file" size="25" value="{image8}" /> Chú thích hình 8 <input name="alt_img8" type="text" size="30" alt="Chú thích cho hình 8" value="{alt_img8}" />  </td>
  </tr>
  <tr style="display:{allow_image8}">
    <td >Xem hình 8:</td>
    <td ><input name="old_image8" size="41" value="{image8}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image8}')" />
      &nbsp;
      <input type="checkbox" name="remove_image8" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 9:</td>
    <td><input name="image9" type="file" size="25" value="{image9}" /> Chú thích hình 9 <input name="alt_img9" type="text" size="30" alt="Chú thích cho hình 9" value="{alt_img9}" />  </td>
  </tr>
  <tr style="display:{allow_image9}">
    <td >Xem hình 9:</td>
    <td ><input name="old_image9" size="41" value="{image9}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image9}')" />
      &nbsp;
      <input type="checkbox" name="remove_image9" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 10:</td>
    <td><input name="image10" type="file" size="25" value="{image10}" /> Chú thích hình 10 <input name="alt_img10" type="text" size="30" alt="Chú thích cho hình 10" value="{alt_img10}" />  </td>
  </tr>
  <tr style="display:{allow_image10}">
    <td >Xem hình 10:</td>
    <td ><input name="old_image10" size="41" value="{image10}" readonly />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image10}')" />
      &nbsp;
      <input type="checkbox" name="remove_image10" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr >
    <td height="25" >Slug:<font color="#FF0000">*</font></td>
    <td width="88%"><input name="slug" notnull = 1 type="text" size="70" alt="Slug" value="{slug}" /></td>
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
  <tr >
    <td height="25" >Lượt Comment</td>
    <td width="88%"><input name="num_comment" alt="Comment"  size="3" value="{num_comment}"></td>
  </tr>
<tr style="visibility:{allow}">
      <td  colspan="2" >&nbsp;</td>
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
   
</form>
</div>
<Script Language="JavaScript">
	function doSave()
	{	
		if(mainForm.product_type_id.value==0)
		{	alert('Chọn hãng sản xuất')
			mainForm.product_type_id.focus()
			return
		}
		if(mainForm.product_kind_id.value==0)
		{	alert('Chọn loại SP')
			mainForm.product_kind_id.focus()
			return
		}
		if(mainForm.skin_id.value==0)
		{	alert('Chọn loại da')
			mainForm.skin_id.focus()
			return
		}
		if(mainForm.color_id.value==0)
		{	alert('Chọn màu sắc')
			mainForm.color_id.focus()
			return
		}		
		if (verify(mainForm))	
		{		
			mainForm.submit()
		}
	}
	function doSaveCopy()
	{	
		mainForm.mode.value = 'savecopy'
		if(mainForm.product_type_id.value==0)
		{	alert('Chọn hãng sản xuất')
			mainForm.product_type_id.focus()
			return
		}
		if(mainForm.product_kind_id.value==0)
		{	alert('Chọn loại SP')
			mainForm.product_kind_id.focus()
			return
		}
		if(mainForm.skin_id.value==0)
		{	alert('Chọn loại da')
			mainForm.skin_id.focus()
			return
		}
		if(mainForm.color_id.value==0)
		{	alert('Chọn màu sắc')
			mainForm.color_id.focus()
			return
		}
		try{	
				mainForm.description.value =  oEdit1.getHTMLBody()					
		} catch(err){ return;}		
		if (verify(mainForm))	
		{	mainForm.submit()
		}
	}
	function doDelete(id)
	{	if (confirm ("Are you sure you want to delete?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
	}	
	function returnToList()
	{	document.location='?option={funname}&mode=list&product_type_id={product_type_id}&l={LANGUAGEID}'
	}
	function returnToFeatureList()
	{	document.location='?option=products/product_feature&mode=list&l={LANGUAGEID}&id={product_id}'
	}
	function typeChange(selectobj)
	{	
		var sitename="Welcome to JavaScript Kit"
		var text1 = selectobj.options[selectobj.selectedIndex].text;
		var text2 = mainForm.product_code.value
		//alert("string".substring("string".length,"string".length-1))
		code = 	text1.substring(text1.length,text1.length-3);
		so	=	text2.substring(text2.length,text2.length-5);
		mainForm.product_code.value = code + so
	}
	function reShow()
	{	
		mainForm.mode.value = 'reshow';
		try{	
				mainForm.description.value =  oEdit1.getHTMLBody()					
		} catch(err){ return;}
		mainForm.submit();
	}
</Script>