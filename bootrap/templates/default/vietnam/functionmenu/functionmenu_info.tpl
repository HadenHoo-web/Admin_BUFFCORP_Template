<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>
<div class="toolbar">
	  <a href="#" onClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
  	  <a href="#" onClick="returnToList()"><img border="0" src="templates/{skin}/images/back.gif" width="20" height="20" alt="List"><span>List</span></a>

</div>
<div class="tabtitle">
Update information menu function:&quot;<span class="itemName">{sup_fun_name}</span>&quot;</div>
  <table  width="100%"  >
   
<form method="POST" action="main.php" name="mainForm"  enctype="multipart/form-data">
<input type="hidden" name="l" value="{language_id}">
<input type="hidden" name="code" value="{code}">
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="mode" value="save">
<input type="hidden" name="sup_id" value="{sup_id}">
<input type="hidden" name="id" value="{id}">
<input type="hidden" name="old_image" value="{old_image}">
  <tr>
      <td width="20%" style="padding-left: 10">Code:<font color="#FF0000">*</font></td>
      <td width="80%"><input name="code" size="30" value="{code}" notnull alt="Code" type="text"  /></td>
  </tr>
  <tr>
      <td width="20%" style="padding-left: 10">Function name:<font color="#FF0000">*</font></td>
      <td width="80%"><input type="text" name="fun_name" size="50" notnull alt="Function name"  value="{fun_name}"></td>
  </tr>
<tr>
      <td width="20%" style="padding-left: 10">Link:<font color="#FF0000">*</font></td>
      <td width="80%"><input type="text" name="link" size="50" notnull alt="Link"  value="{link}"></td>
  </tr>
<tr style="display:{allow}" >
  <td >View image:</td>
  <td ><input name="old_image" size="41" value="{image}" readonly="Readonly" />
      <input type="button" value="View" name="B1"  onclick="imgview('{image_path}')" />
    &nbsp;
    <input type="checkbox" name="remove_image" value="1" id="fp1" />
    <label for="fp1">Delete</label></td>
</tr>
<tr>
    <td width="20%" style="padding-left: 10">Update image:</td>
    <td width="80%">
    <input type="file" name="new_image" size="41" ></td>
  </tr > 
  <tr>
      <td width="20%" style="padding-left: 10">Description:</td>
      <td width="80%"><textarea  name="description"  style="width:300;height:100"  alt="Description"   >{description}</textarea></td>
  </tr>
</form>  
</table>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">

function returnToList()
{	document.location='?option={funname}&mode=list&sup_id={sup_id}&l={LANGUAGEID}'
}

function doSave()
{	if (verify(mainForm) )
		document.mainForm.submit() ;
}
</Script>
