<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>
<div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về Danh Sách" width="20" height="20"><span>Về Danh Sách</span></a>

</div>
 <div class="tabtitle">
 Thông tin câu hỏi  </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{faq_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td height="15%" >Loại câu hỏi:<font color="#FF0000">*</font></td>
    <td width="85%" bgcolor="#F0F0F0" height="25" valign="top">
<select name="faq_type_id">
<!-- BEGIN faq_type_list -->
	<option value="{faq_type_list.faq_type_id}">{faq_type_list.faq_type_name}</option>
<!-- END faq_type_list -->
</select>
	</td>
  </tr>
  
  <tr >
    <td height="15%" >Câu hỏi:<font color="#FF0000">*</font></td>
    <td width="85%" bgcolor="#F0F0F0" height="25" valign="top">
<!-- begin: create hidden field for editor -->
	<pre id="Edit0" style="display:none; margin-top:3; margin-bottom:0">{q}</pre>
	<input type="hidden" name="q" value="">
<script language="JavaScript">
	var oEdit0 = new InnovaEditor("oEdit0");
	InitEditor(oEdit0, Edit0, 'js/editor/scripts/', '90%', 150) 
    </Script>			
<!-- end: create hidden field for editor -->    </td>
  </tr>
  <tr >
    <td width="15%" height="25" >Trả lời:<font color="#FF0000">*</font></td>
    <td width="85%" valign="top" bgcolor="#F0F0F0" height="25">  
<!-- begin: create hidden field for editor -->
	<pre id="Edit1" style="display:none; margin-top:3; margin-bottom:0">{a}</pre>
	<input type="hidden" name="a" value="">
<script language="JavaScript">
	var oEdit1 = new InnovaEditor("oEdit1");
	InitEditor(oEdit1, Edit1, 'js/editor/scripts/', '90%', 200) 
    </Script>			
<!-- end: create hidden field for editor -->    </td>
  </tr>
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label for="fp1"> Active</label></td>
  </tr>
<tr style="visibility:{allow}">
      <td  colspan="2" >&nbsp;</td>
    </tr>
  </table>
</form><p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
</div>
<Script Language="JavaScript">
	function doSave()
	{	try{	
				mainForm.q.value = oEdit0.getHTMLBody()
				mainForm.a.value = oEdit1.getHTMLBody()			
		} catch(err){ return;}		
		if (verify(mainForm))	
		{	mainForm.submit()
		}
	}

	function returnToList()
	{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}
	mainForm.faq_type_id.value = {faq_type_id}
</Script>