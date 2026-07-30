<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<script src="js/ckeditor/_samples/sample.js" type="text/javascript"></script>
<link href="js/ckeditor/_samples/sample.css" rel="stylesheet" type="text/css" />
<div class="toolbar">
  <a href="#" onClick="doUpdate()"><img border="0" src="templates/{skin}/images/back.gif" alt="List pages" width="20" height="20"><span>Update</span></a>
</div>

<div style="overflow:auto; height:80%">
<table width="100%">
<form action="main.php" name="mainForm" method="POST" enctype="multipart/form-data">
<input type="hidden" name="l" value="{LANGUAGEID}">
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="mode" value="save">
<input type="hidden" name="id"  value="{page_id}">
<input type="hidden" name="stat"  value="{status_id}">
  <tr>
    <td width="100%" valign="top" bgcolor="#F0F0F0" height="25">      
<textarea cols="800" id="noidung" name="noidung" rows="100">{noidung}</textarea>

</td>
  </tr>  
  </form>
  </table>
</div>
<Script Language="JavaScript">
	function doUpdate()
	{				
		document.location='?option={funname}&mode=update&l={LANGUAGEID}'
	}
</Script>
<p align="center" class="message">{num_link} Link</p>
<p align="center" class="message">{MESSAGE}</p>