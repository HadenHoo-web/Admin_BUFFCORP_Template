<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/editor/scripts/editor.js"></script>

<div class="toolbar">
  <a href="#" onClick="doSave(); return false;"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Lưu</span></a>
  <a href="#" onClick="returnToList(); return false;"><img border="0" src="templates/{skin}/images/back.gif" width="20" height="20" alt="List"><span>Trở về</span></a>
</div>

<div class="tabtitle">Thông tin menu function: <span class="itemName">{sup_fun_name}</span></div>

<form method="POST" action="main.php" name="mainForm" enctype="multipart/form-data">
  <input type="hidden" name="l" value="{LANGUAGEID}">
  <input type="hidden" name="option" value="{funname}">
  <input type="hidden" name="mode" value="save">
  <input type="hidden" name="sup_id" value="{sup_id}">
  <input type="hidden" name="id" value="{id}">
  <input type="hidden" name="old_image" value="{old_image}">

  <table width="100%" class="function-menu-form" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2">Thông tin danh mục</td>
    </tr>
    <tr>
      <td width="22%">Code:<font color="#FF0000">*</font></td>
      <td width="78%"><input name="code" size="50" value="{code}" notnull alt="Code" type="text"></td>
    </tr>
    <tr>
      <td>Function name:<font color="#FF0000">*</font></td>
      <td><input type="text" name="fun_name" size="70" notnull alt="Function name" value="{fun_name}"></td>
    </tr>
    <tr>
      <td>Link:<font color="#FF0000">*</font></td>
      <td><input type="text" name="link" size="90" notnull alt="Link" value="{link}"></td>
    </tr>
    <tr style="display:{allow}">
      <td>View image:</td>
      <td>
        <input name="old_image_display" size="50" value="{image}" readonly="readonly">
        <input type="button" value="View" name="B1" onclick="imgview('{image_path}')">
        <input type="checkbox" name="remove_image" value="1" id="fp1">
        <label for="fp1">Delete</label>
      </td>
    </tr>
    <tr>
      <td>Update image:</td>
      <td><input type="file" name="new_image" size="50"></td>
    </tr>
    <tr>
      <td>Description:</td>
      <td><textarea name="description" style="width:100%;max-width:760px;height:130px" alt="Description">{description}</textarea></td>
    </tr>
  </table>
</form>

<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>

<script language="JavaScript">
function returnToList()
{
  document.location='?option={funname}&mode=list&sup_id={sup_id}&l={LANGUAGEID}';
}

function doSave()
{
  if (verify(mainForm)) {
    document.mainForm.submit();
  }
}
</script>
