<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
  <a href="#" onClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu quyền"><span>Lưu quyền</span></a>
  <a href="#" onClick="returnToList()"><img border="0" src="templates/{skin}/images/back.gif" width="20" height="20" alt="Trở về cây menu"><span>Trở về cây menu</span></a>
</div>
<div class="tabtitle">
Phân quyền người dùng cho “{func_name}”
</div>
<form method="GET" action="main.php" name="filterForm">
  <input type="hidden" name="option" value="{funname}">
  <input type="hidden" name="mode" value="permission_list">
  <input type="hidden" name="id" value="{member_id}">
  <input type="hidden" name="l" value="{LANGUAGEID}">
  <label for="permission-department">Phòng ban</label>
  <select id="permission-department" name="department_id1">
    <option value="0">Tất cả phòng ban</option>
    <!-- BEGIN department_list -->
    <option value="{department_list.department_id}" {department_list.selected}>{department_list.department_name}</option>
    <!-- END department_list -->
  </select>
  <input type="submit" value="Lọc">
</form>
<form method="POST" action="main.php" name="mainForm">
  <input type="hidden" name="l" value="{LANGUAGEID}">
  <input type="hidden" name="id" value="{member_id}">
  <input type="hidden" name="department_id1" value="{department_id}">
  <input type="hidden" name="option" value="{funname}">
  <input type="hidden" name="mode" value="permission_save">
  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="selector permission-selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="6%" align="center">#</td>
      <td width="14%" align="center">Cho phép</td>
      <td width="48%" align="left">Nhân viên</td>
      <td width="32%" align="left">Phòng ban</td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}">
      <td width="6%" align="center">{list.order}</td>
      <td width="14%" align="center">
        <input type="checkbox" name="dung{list.code}" value="{list.code}" {list.checked} aria-label="Cấp quyền cho {list.member_name}">
      </td>
      <td width="48%">{list.member_name}</td>
      <td width="32%">{list.department_name}</td>
    </tr>
    <!-- END list -->
  </table>
</form>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<script type="text/javascript">
function returnToList()
{
  document.location='?option=functionmenu/functionmenu&mode=list&l={LANGUAGEID}';
}

function doSave()
{
  document.mainForm.submit();
}
</script>
