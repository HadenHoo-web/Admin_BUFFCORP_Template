<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>
 <div class="toolbar">
<a href="JavaScript:doSend()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Send"><span>Send</span></a></div>
 <div class="tabtitle">
 Mail Form </div>
 <div style="overflow:auto; height:80%">
<form  action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{template_id}">
	<input type="hidden" name="mode" value="send">
<table width="100%"> 
  <tr >
    <td width="18%" height="25" style="padding-left: 10">Tùy chọn:<font color="#FF0000">*</font> </td>
    <td width="82%"><input type="checkbox" name="test" value="1" checked="checked"> Test <select name="tinhtrang"><option value="0">All</option><option value="1">Actived</option><option value="2">No active</option></select> Tình trạng</td>
  </tr>
  <tr >
    <td width="18%" height="25" style="padding-left: 10">Gởi đến:<font color="#FF0000">*</font> </td>
    <td width="82%">
    <input name="tomail" size="78" alt="Gởi đến"></td>
  </tr>
  <tr >
    <td width="18%" height="25" style="padding-left: 10">Chủ đề:<font color="#FF0000">*</font> </td>
    <td width="82%">
    <input name="subject" size="78" alt="Chủ đề"></td>
  </tr>
  <tr>
    <td width="12%" height="23" valign="top" style="padding-left: 10">Nội dung:</td>
    <td width="88%" height="23"><!-- begin: create hidden field for editor -->
        <pre id="Edit0" style="display:none; margin-top:3; margin-bottom:0">{description}</pre>
        <input type="hidden" name="description" value="" alt="Nội dung"/>
        <script language="JavaScript" type="text/javascript">
	var oEdit0 = new InnovaEditor("oEdit0");
	InitEditor(oEdit0, Edit0, 'js/editor/scripts/', '100%', 400) 
    </script>
        <!-- end: create hidden field for editor -->    </td>
  </tr>  
<tr style="visibility:{allow}">
      <td  colspan="2" >&nbsp;</td>
    </tr>
  </table>
   
</form>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<p align="center"><font color="#FF0000"><b>{order} Đã gởi</b></font></p>
<Script Language="JavaScript">
	function doSend()
	{	
		try{	
				mainForm.description.value = oEdit0.getHTMLBody()
		} catch(err){ return;}	
		if (verify(mainForm))	
		{	mainForm.submit()
		}
	}	
</Script>