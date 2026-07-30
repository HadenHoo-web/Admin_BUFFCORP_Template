<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>
<!-- Begin of template category_info -->
<!-- Please do not make any change in this template file -->
<div class="toolbar">
  <a href="#" OnClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
  <a href="?option={funname}&mode=list&l={LANGUAGEID}&pr={parent_id}"><img border="0" src="templates/{skin}/images/back.gif" alt="List column" width="20" height="20"><span> 
  Category list</span></a>
</div>
<div class="tabtitle">&nbsp;Page category information&nbsp;&nbsp;
<!-- BEGIN catChain -->
  <font face="Arial" color="#00FFFF">»</font>&nbsp;&nbsp;<a href="?option={funname}&mode=list&pr={catChain.cat_id}">{catChain.cat_name}</a>&nbsp;
<!-- END catChain -->  
</div>
<form method="POST" action="main.php" name=mainForm enctype="multipart/form-data">
  <input type="hidden" name="option" value="{funname}">
  <input type="hidden" name="mode" value="save">
  <input type="hidden" name="language_id" value="{LANGUAGEID}">
  <input type="hidden" name="l" value="{LANGUAGEID}">  
  <input type="hidden" name="parent_id" value="{parent_id}">
  <input type="hidden" name="id" value="{cat_id}">
  <table width="100%">
    <tr>
      <td width="20%" style="padding-left: 10" height="23">Category name:</td>
      <td width="80%" height="23"><input type="text" name="cat_name" size="61" value="{cat_name}" notnull alt="Column name"></td>
    </tr>
    <tr>
      <td width="20%" style="padding-left: 10" height="23">Parent category:</td>
      <td width="80%" height="23">
        <select size="1" name="pr">
<!-- BEGIN catlist -->    
          <option value="{catlist.cat_id}">{catlist.cat_name}</option>
<!-- END catlist -->
        </select>
      </td>
    </tr>
    <tr>
      <td width="20%" style="padding-left: 10" height="23">Code:</td>
      <td width="80%" height="23">
        <input type="text" name="alias" size="20" value="{alias}" alt="Column code"> View <input name="view" alt="Xem"  size="3" value="{view}"></td>
    </tr>
    <tr>
      <td width="20%" style="padding-left: 10" height="23">&nbsp;</td>
      <td width="80%" height="23">
        <input type="checkbox" name="visible" value="1" id="fp2" {visible}>
      <label for="fp2">Show</label>      </td>
    </tr>
    <tr>
      <td width="20%" style="padding-left: 10" height="23">&nbsp;</td>
      <td width="80%" height="23">
        <input type="checkbox" name="lock" value="1" id="fp1" {lock}><label for="fp1"> Noindex</label>
      </td>
    </tr>
	<tr style="display:expression(('{image}' == '') ? 'none' : '')">
    <td width="20%" style="padding-left: 10" >Current image:</td>
    <td width="80%">
    <input name="old_img" size="41" value="{image}" Readonly><input type="button" value="View image" name="B1" style="border: 1px solid #C0C0C0" OnClick="imgview('{imgPath}')">&nbsp; <input name="img_remove" type="checkbox" id="img_remove" value="1">
    Delete</td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Upload new image:</td>
    <td width="80%">
    <input type="file" name="new_img" size="41" style="border: 1px solid #C0C0C0"></td>
  </tr>
  
  <tr>
      <td width="20%" style="padding-left: 10" height="23">Slug:</td>
      <td width="80%" height="23"><input type="text" name="slug" size="150" value="{slug}" alt="Slug"></td>
  </tr>
  <tr>
      <td width="20%" style="padding-left: 10" height="23">Title Seo:</td>
      <td width="80%" height="23"><input type="text" name="title_seo" size="150" value="{title_seo}" alt="Title Seo"></td>
  </tr>
  <tr>
      <td width="20%" style="padding-left: 10" height="23">Meta key:</td>
      <td width="80%" height="23"><input type="text" name="meta_key" size="150" value="{meta_key}" alt="Meta key"></td>
  </tr>
  <tr>
      <td width="20%" style="padding-left: 10" height="23">Meta des:</td>
      <td width="80%" height="23"><input type="text" name="meta_des" size="150" value="{meta_des}" alt="Meta des"></td>
  </tr>
  <tr >
    <td height="25" >Meta Schema:<font color="#FF0000">*</font></td>
    <td width="88%"><textarea name="meta_schema" cols="140" rows="10" readonly="readonly">{meta_schema}</textarea></td>
  </tr>
    <tr style="display:none">
      <td width="20%" style="padding-left: 10" height="23" valign="top">Description:</td>
      <td width="80%" height="23">
	<!-- begin: create hidden field for editor -->
	<pre id="Edit0" style="display:none; margin-top:3; margin-bottom:0">{description}</pre>
	<input type="hidden" name="description" value="">
	<script language="JavaScript">
	var oEdit0 = new InnovaEditor("oEdit0");
	InitEditor(oEdit0, Edit0, 'js/editor/scripts/', '90%', 200) 
    </Script>			
	<!-- end: create hidden field for editor -->	  	  	        

	</td>
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
</form>
<Script Language="JavaScript">
function doSave()
{	try{	
				mainForm.description.value = oEdit0.getHTMLBody()
		} catch(err){ return;}		

	if (verify(mainForm))
		mainForm.submit();
}
mainForm.pr.value = '{parent_id}'
document.mainForm.cat_name.focus()
</Script>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<!-- End of template category_info -->