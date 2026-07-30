<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>
<Script Language="JavaScript">
	function doExport()
	{		
		if (verify(mainForm))	
		{	mainForm.submit()
		}
	}
	function doSite()
	{		
		document.location = '?option={funname}&mode=site'
	}
</Script>
 <div class="toolbar">
 <a href="JavaScript:doExport()"><img border="0" src="templates/{skin}/images/con_address.png" alt="Return to Template free list" width="20" height="20"><span>Export Template Free</span></a><a href="JavaScript:doSite()"><img border="0" src="templates/{skin}/images/con_address.png" alt="Return to Template free list" width="20" height="20"><span>Export Sitemap</span></a></div>
 <div class="tabtitle">
 <form  action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" style="margin:0px;" >
 <input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="mode" value="info">
 Export theo : <select name="orderby" size="1" onchange="mainForm.submit();">
   <option value="">Bình thường</option>
   <option value="img">Tag IMG</option>
 </select> Chuyên mục 
 <!-- DO ComboFromTable("product_type_id", "tbl_product_types", "product_type_id", "product_type_name", "product_type_id", 0, " Chọn phân loại" , "0" , "1 order by product_type_name" , "doExport()", 1) -->
 <input name="web" type="text" value="{web}" size="30" />
 <script>
 mainForm.orderby.value = '{orderby}'
 mainForm.product_type_id.value = '{product_type_id}'
 </script>
   <label>
   <input type="checkbox" name="desc" value="1">
   DESC</label>
 </form> </div>
 <div style="overflow:auto; height:80%">
<table width="100%">
  <tr>
    <td height="23"><!-- begin: create hidden field for editor -->
        <pre id="Edit0" style="display:none; margin-top:3; margin-bottom:0">{content}</pre>
        <input type="hidden" name="description" value="" />
        <script language="JavaScript" type="text/javascript">
	var oEdit0 = new InnovaEditor("oEdit0");
	InitEditor(oEdit0, Edit0, 'js/editor/scripts/', '100%', 430) 
    </script>
        <!-- end: create hidden field for editor -->    </td>
  </tr>
<tr style="visibility:{allow}">
      <td  colspan="2" >&nbsp;</td>
    </tr>
  </table>
</div>