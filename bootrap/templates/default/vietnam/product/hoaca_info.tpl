<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>
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
    <td>Hãng sản xuất:<font color="#FF0000">*</font> </td>
    <td>
    <select size="1" name="product_type_id" id="myselect">
    	<option value="0" selected> Chọn chuyên mục</option>
<!-- BEGIN product_type_list -->
    	<option value="{product_type_list.product_type_id}">{product_type_list.product_type_name} - {product_type_list.product_type_code}</option>
<!-- END product_type_list -->
	</select>
<!-- DO ComboFromTable("product_kind_id", "tbl_product_kinds", "product_kind_id", "product_kind_name", "product_kind_id", 0, " Chọn loại SP" , "0" , "1 order by product_kind_name" , "", 1) -->
<!-- DO ComboFromTable("skin_id", "tbl_skin", "skin_id", "skin_name", "skin_id", 0, " Chọn loại Da" , "0" , "1 order by skin_name" , "", 1) -->
<!-- DO ComboFromTable("color_id", "tbl_color", "color_id", "color_name", "color_id", 0, " Chọn màu sắc" , "0" , "1 order by color_name" , "", 1) -->
	</td>
  </tr>
  <tr >
    <td height="25" >Mã SP:<font color="#FF0000">*</font></td>
    <td><input name="product_code" notnull = 1 type="text" size="7" alt="Mã SP" value="{product_code}"> Tên SP: <font color="#FF0000">*</font> <input name="product_name" size="100" notnull = 1 alt="Tên SP" value="{product_name}"></td>
  </tr>
  <tr>
    <td >Kích cỡ:</td>
    <td ><input name="size" alt="Kích cỡ"  size="10" value="{size}"> <span style="display:{isadmin}">Giá web: <input name="old_price" type="text" size="10" value="{old_price}" /></span> Giá bán: <input name="price" type="text" size="10" value="{price}"> Số lượng <input name="soluong" type="text" size="10" value="{soluong}" number = 1 {readonly}></td>
  </tr>
  <tr>
    <td >Link gốc:</td>
    <td ><input name="website" type="text" size="100" value="{website}"></td>
  </tr>
  <tr>
    <td width="20%" >Mô tả:</td>
    <td width="82%" >  
<!-- begin: create hidden field for editor -->
	<pre id="Edit1" style="display:none; margin-top:3; margin-bottom:0">{description}</pre>
	<input type="hidden" name="description" value="">
<script language="JavaScript">
	var oEdit1 = new InnovaEditor("oEdit1");
	InitEditor(oEdit1, Edit1, 'js/editor/scripts/', '100%', 150) 
    </Script>			
<!-- end: create hidden field for editor --></td>
  </tr>
  <tr>
    <td>Hình nhỏ:</td>
    <td><input name="image0" type="file" size="25" value="{image0}" />    </td>
  </tr>
  <tr style="display:{allow_image0}">
    <td >Xem hình 1:</td>
    <td ><input name="old_image0" size="41" value="{image0}" readonly="Readonly" />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image0}')" />
      &nbsp;
      <input type="checkbox" name="remove_image0" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 1:</td>
    <td><input name="image1" type="file" size="25" value="{image1}" />    </td>
  </tr>
  <tr style="display:{allow_image1}">
    <td >Xem hình 1:</td>
    <td ><input name="old_image1" size="41" value="{image1}" readonly="Readonly" />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image1}')" />
      &nbsp;
      <input type="checkbox" name="remove_image1" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 2:</td>
    <td><input name="image2" type="file" size="25" value="{image2}" />    </td>
  </tr>
  <tr style="display:{allow_image2}">
    <td >Xem hình 2:</td>
    <td ><input name="old_image2" size="41" value="{image2}" readonly="Readonly" />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image2}')" />
      &nbsp;
      <input type="checkbox" name="remove_image2" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 3:</td>
    <td><input name="image3" type="file" size="25" value="{image3}" />    </td>
  </tr>
  <tr style="display:{allow_image3}">
    <td >Xem hình 3:</td>
    <td ><input name="old_image3" size="41" value="{image3}" readonly="Readonly" />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image3}')" />
      &nbsp;
      <input type="checkbox" name="remove_image3" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td>Hình 4:</td>
    <td><input name="image4" type="file" size="25" value="{image4}" />    </td>
  </tr>
  <tr style="display:{allow_image4}">
    <td >Xem hình 4:</td>
    <td ><input name="old_image4" size="41" value="{image4}" readonly="Readonly" />
        <input type="button" value="Xem" name="B1"  onclick="imgview(' ../images/product/{image4}')" />
      &nbsp;
      <input type="checkbox" name="remove_image4" value="1" id="fp1" />
      <label for="fp1">Xóa</label></td>
  </tr>
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label for="fp1"> Kích hoạt</label> <input type="checkbox" name="isadv" value="1" id="fp1" {isadv} /> Is adv   <input name="view" alt="Xem"  size="3" value="{view}"> Đã xem</td>
  </tr>
<tr style="visibility:{allow}">
      <td  colspan="2" ></td>
    </tr>
  </table>
   
</form>
</div>
<Script Language="JavaScript">
	function doSave()
	{	
		try{	
				mainForm.description.value =  oEdit1.getHTMLBody()					
		} catch(err){ return;}		
		if (verify(mainForm))	
		{	mainForm.submit()
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
	{	document.location='?option={funname}&mode=product_list&l={LANGUAGEID}'
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
	mainForm.product_type_id.value 	= {product_type_id}
	mainForm.product_kind_id.value 	= {product_kind_id}
	mainForm.skin_id.value 			= {skin_id}
	mainForm.color_id.value 		= {color_id}
	mainForm.product_name.focus()
</Script>