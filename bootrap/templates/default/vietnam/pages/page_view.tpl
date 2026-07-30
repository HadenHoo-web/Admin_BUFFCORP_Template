<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>	
<div class="toolbar">
  <a href="#" OnClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
  <a href="#" onClick="returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="List columns" width="20" height="20"><span>Category list</span></a>
</div>
<div class="tabtitle"><span class="header">Page information</span></div>


<div style="overflow:auto; height:80%">
<table width="100%" bgcolor="#F0F0F0">
  <tr>
    <td width="15%" bgcolor="#F0F0F0" height="25">Page&#39;s title:</td>
    <td width="85%" bgcolor="#F0F0F0" height="25">
    {title}</td>
  </tr>
  <tr>
    <td width="15%" bgcolor="#F0F0F0" height="25">Page code:</td>
    <td width="85%" bgcolor="#F0F0F0" height="25">
    {alias}</td>
  </tr>
  <tr> 
    <td width="15%" valign="top" bgcolor="#F0F0F0" height="25" style="padding-top: 3">Intro text:</td>
    <td width="85%" bgcolor="#F0F0F0" height="25" valign="top">{intro_text}</td>
  </tr>
  <tr>
    <td width="15%" valign="top" bgcolor="#F0F0F0" height="25" style="padding-top: 3">
    Main text:</td>
    <td width="85%" valign="top" bgcolor="#F0F0F0" height="25">{main_text}</td>
  </tr>
  </table>
</div>
<Script Language="JavaScript">
	function doSave()
	{	try{	
				mainForm.intro_text.value = oEdit0.getHTMLBody()
				mainForm.main_text.value = oEdit1.getHTMLBody()			
		} catch(err){ return;}		
		if (verify(mainForm))	
		{	mainForm.submit()
		}
	}

	function returnToList()
	{	if ('{status_id}'=='')
			document.location='?option={funname}&mode=list&l={LANGUAGEID}&cid={cat_id}'
		else
			document.location='?option={funname}&mode=list_all&l={LANGUAGEID}&stat={status_id}'
	}
</Script>
<p align="center" class="message">{MESSAGE}</p>