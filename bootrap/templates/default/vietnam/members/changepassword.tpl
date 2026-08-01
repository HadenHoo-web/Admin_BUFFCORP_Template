<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
  <a href="#" onClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu mật khẩu"><span>Lưu mật khẩu</span></a>
</div>
<div class="tabtitle">
Đổi mật khẩu
</div>
<form method="POST" action="main.php" name="mainForm" onSubmit="return checkform()">
  <input type="hidden" name="l" value="{LANGUAGEID}">
  <input type="hidden" name="id" value="{member_id}">
  <input type="hidden" name="option" value="{funname}">
  <input type="hidden" name="mode" value="save">
  <table width="100%">
    <tr>
      <td width="28%">Mật khẩu hiện tại:</td>
      <td width="72%"><input name="old_password" size="46" notnull="1" alt="Mật khẩu hiện tại" type="password" autocomplete="current-password"></td>
    </tr>
    <tr>
      <td>Mật khẩu mới:</td>
      <td><input name="new_password" size="46" notnull="1" alt="Mật khẩu mới" type="password" autocomplete="new-password"></td>
    </tr>
    <tr>
      <td>Xác nhận mật khẩu mới:</td>
      <td><input name="confirm_password" size="46" notnull="1" alt="Xác nhận mật khẩu mới" type="password" autocomplete="new-password"></td>
    </tr>
  </table>
</form>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<script type="text/javascript">
document.mainForm.old_password.focus();

function checkform()
{
  if (document.mainForm.old_password.value === '') {
    alert('Vui lòng nhập mật khẩu hiện tại.');
    return false;
  }
  if (document.mainForm.new_password.value === '') {
    alert('Vui lòng nhập mật khẩu mới.');
    return false;
  }
  if (document.mainForm.new_password.value !== document.mainForm.confirm_password.value) {
    alert('Mật khẩu xác nhận chưa trùng khớp.');
    return false;
  }
  return true;
}

function doSave()
{
  if (checkform()) document.mainForm.submit();
}
</script>
