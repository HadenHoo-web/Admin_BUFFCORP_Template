<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="js/editor/scripts/editor.js"></script>
<div class="toolbar">
  <a href="JavaScript:doSend()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Gửi mail"><span>Gửi mail</span></a>
</div>
<div class="tabtitle">Gửi mail</div>
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm">
  <input type="hidden" name="l" value="{LANGUAGEID}">
  <input type="hidden" name="option" value="{funname}">
  <input type="hidden" name="id" value="{template_id}">
  <input type="hidden" name="mode" value="send">
  <table width="100%" class="mail-form-table">
    <tr>
      <td width="18%">Đối tượng nhận:<font color="#FF0000">*</font></td>
      <td width="82%">
        <label><input type="checkbox" name="test" value="1" checked="checked"> Gửi thử</label>
        <select name="tinhtrang">
          <option value="0">Tất cả</option>
          <option value="1">Đang hoạt động</option>
          <option value="2">Không hoạt động</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Gửi đến:<font color="#FF0000">*</font></td>
      <td><input name="tomail" size="78" alt="Gửi đến"></td>
    </tr>
    <tr>
      <td>Chủ đề:<font color="#FF0000">*</font></td>
      <td><input name="subject" size="78" alt="Chủ đề"></td>
    </tr>
    <tr class="mail-content-row">
      <td valign="top">Nội dung:</td>
      <td>
        <pre id="Edit0" style="display:none">{description}</pre>
        <input type="hidden" name="description" value="" alt="Nội dung">
        <script type="text/javascript">
        var oEdit0 = new InnovaEditor("oEdit0");
        InitEditor(oEdit0, Edit0, 'js/editor/scripts/', '100%', 260);
        </script>
      </td>
    </tr>
  </table>
</form>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<p align="center"><font color="#FF0000"><b>{order} đã gửi</b></font></p>
<script type="text/javascript">
function doSend()
{
  try {
    document.mainForm.description.value = oEdit0.getHTMLBody();
  } catch (err) {
    return;
  }
  if (verify(document.mainForm)) document.mainForm.submit();
}
</script>
