<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
.member-edit{font-family:Manrope,"Segoe UI",Arial,sans-serif;background:#fff;border:1px solid #d9e6f3;border-radius:8px;box-shadow:0 12px 28px rgba(25,72,120,.08);overflow:hidden}
.member-edit *{box-sizing:border-box}
.member-toolbar{display:flex;align-items:center;justify-content:flex-end;gap:10px;padding:18px 20px;border-bottom:1px solid #d9e6f3;background:#fff}
.member-toolbar a{display:inline-flex;min-height:46px;align-items:center;justify-content:center;gap:9px;padding:0 18px;border:1px solid #d9e6f3;border-radius:8px;background:#fff;color:#17324d;text-decoration:none;font-weight:900;font-size:15px}
.member-toolbar a:first-child{border-color:#2e6cbf;background:#2e6cbf;color:#fff}
.member-toolbar svg{width:18px;height:18px;flex:0 0 auto}
.member-title{padding:20px 24px 4px;color:#163e6d;font-size:24px;font-weight:900;line-height:32px}
.member-help{margin:0;padding:0 24px 18px;color:#71839a;font-size:14px;line-height:22px;border-bottom:1px solid #e6eef7}
.member-form{display:grid;gap:22px;padding:22px 24px 26px;background:#fff}
.member-section{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;padding:16px;border:1px solid #d9e6f3;border-radius:8px;background:#fbfdff}
.member-section-title{grid-column:1 / -1;margin:0 0 2px;color:#163e6d;font-size:17px;font-weight:900;line-height:24px}
.member-field{display:flex;flex-direction:column;gap:7px;margin:0;color:#17324d;font-size:14px;line-height:20px;font-weight:900}
.member-field--wide{grid-column:span 2}
.member-field--full{grid-column:1 / -1}
.member-field input:not([type="radio"]),.member-field select,.member-field textarea{width:100%;min-height:46px;border:1px solid #d9e6f3;border-radius:8px;background:#fff;color:#17324d;font:700 15px/22px Manrope,"Segoe UI",Arial,sans-serif;outline:0;padding:0 13px}
.member-field textarea{min-height:116px;padding:12px 13px;resize:vertical}
.member-field input:focus,.member-field select:focus,.member-field textarea:focus{border-color:#2e6cbf;box-shadow:0 0 0 4px rgba(46,108,191,.12)}
.member-required{color:#dc2626}
.member-note{color:#71839a;font-size:13px;font-weight:700;line-height:19px}
.member-radio-row{display:flex;min-height:46px;align-items:center;gap:18px}
.member-radio-row label{display:inline-flex;align-items:center;gap:7px;margin:0;font-weight:800}
.member-hidden{display:none!important}
.member-message{padding:0 24px 22px;margin:0;text-align:center;color:#dc2626;font-weight:900}
@media (max-width:1280px){.member-section{grid-template-columns:repeat(2,minmax(0,1fr))}.member-field--wide,.member-field--full{grid-column:1 / -1}}
@media (max-width:760px){.member-toolbar{flex-wrap:wrap;justify-content:flex-start}.member-section{grid-template-columns:1fr}.member-field--wide,.member-field--full{grid-column:1}.member-toolbar a{width:100%}}
</style>

<div class="member-edit">
  <div class="member-toolbar">
    <a href="#" onClick="doSave();return false;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span>Lưu</span></a>
    <a href="#" onClick="returnToList();return false;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg><span>Trở về</span></a>
  </div>
  <div class="member-title">Thông tin thành viên</div>
  <p class="member-help">Cập nhật tài khoản, phòng ban, lương và thông tin BHXH của nhân viên.</p>
  <form method="POST" action="main.php" name="mainForm" class="member-form">
    <input type="hidden" name="l" value="{LANGUAGEID}">
    <input type="hidden" name="member_id" value="{member_id}">
    <input type="hidden" name="option" value="{funname}">
    <input type="hidden" name="mode" value="save">

    <section class="member-section">
      <h3 class="member-section-title">Thông tin tài khoản</h3>
      <label class="member-field"><span>Login name <b class="member-required">*</b></span><input type="text" name="loginname" value="{loginname}" notnull alt="User name"></label>
      <label class="member-field"><span>Full name <b class="member-required">*</b></span><input type="text" name="fullname" value="{fullname}" notnull alt="Full name"></label>
      <label class="member-field"><span>Lương</span><input type="text" name="luong" value="{luong}"></label>
      <label class="member-field"><span>Trách nhiệm</span><input type="text" name="trach_nhiem" value="{trach_nhiem}"></label>
      <label class="member-field member-field--wide"><span>Address</span><input type="text" name="address" value="{address}"></label>
      <label class="member-field"><span>Phone</span><input type="text" name="phone" value="{phone}"></label>
      <label class="member-field"><span>Điểm cộng</span><input type="text" name="cellphone" value="{cellphone}"></label>
      <label class="member-field member-field--wide"><span>Email <b class="member-required">*</b></span><input type="text" name="email" value="{email}" email notnull alt="Email"></label>
      <label class="member-field member-field--wide"><span>Phòng ban chính</span><select size="1" name="member_type_id"><option value="0">Chọn Phòng ban</option><!-- BEGIN member_type_list --><option value="{member_type_list.member_type_id}">{member_type_list.member_type_name}</option><!-- END member_type_list --></select></label>
      <label class="member-field member-field--wide"><span>Phòng ban kiêm nhiệm</span><select size="1" name="extra_member_type_id"><option value="0">Chọn Phòng ban</option><!-- BEGIN member_extra_type_list --><option value="{member_extra_type_list.member_type_id}" {member_extra_type_list.extra_selected}>{member_extra_type_list.member_type_name}</option><!-- END member_extra_type_list --></select><small class="member-note">Chọn nếu nhân viên quản lý thêm một phòng ban khác.</small></label>
      <label class="member-field member-field--full"><span>Note</span><textarea name="note">{note}</textarea></label>
    </section>

    <section class="member-section">
      <h3 class="member-section-title">Thông tin BHXH</h3>
      <label class="member-field"><span>Mã số BHXH</span><input type="text" name="bhxh_code" value="{bhxh_code}"></label>
      <label class="member-field"><span>Lương đóng BHXH</span><input type="text" name="bhxh_salary" value="{bhxh_salary}"></label>
      <label class="member-field"><span>Ngày bắt đầu</span><input type="date" name="bhxh_start_date" value="{bhxh_start_date}"></label>
      <label class="member-field"><span>Trạng thái BHXH</span><select size="1" name="bhxh_status"><option value="0">Chưa tham gia / Đã ngưng</option><option value="1">Đang tham gia</option></select></label>
      <label class="member-field member-field--full"><span>Ghi chú BHXH</span><textarea name="bhxh_note">{bhxh_note}</textarea></label>
    </section>

    <section class="member-section">
      <h3 class="member-section-title">Trạng thái</h3>
      <div class="member-field"><span>Status</span><div class="member-radio-row"><label><input type="radio" value="1" checked name="active" id="fp1"> Active</label><label><input type="radio" value="0" {active} name="active" id="fp2"> Deactive</label></div></div>
      <div class="member-field member-hidden"><span>Role</span>{role}</div>
    </section>
  </form>
  <p class="member-message">{MESSAGE}</p>
</div>

<script type="text/javascript">
function returnToList()
{	document.location = '?option={funname}&mode=list&l={LANGUAGEID}'
}

function doSave()
{	if (verify(document.mainForm))
		document.mainForm.submit()
}
mainForm.member_type_id.value = {member_type_id};
mainForm.bhxh_status.value = {bhxh_status};
</script>
