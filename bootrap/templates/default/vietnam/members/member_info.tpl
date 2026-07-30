<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body topmargin="0" leftmargin="0">
<div class="toolbar">
	  <a href="#" onClick="doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
  	  <a href="#" onClick="returnToList()"><img border="0" src="templates/{skin}/images/back.gif" width="20" height="20" alt="Return to member list"><span>Return to member list</span></a>

</div>
<div class="tabtitle"><span class="header">Member information</span></div>
<div style="overflow:auto; height:80%" align="left">
  <table  width="100%" >
  <form method="POST" action="main.php" name="mainForm"> 
<input type="hidden" name="l" value="{language_id}">
<input type="hidden" name="member_id" value="{member_id}">
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="mode" value="save">
  <tr>
    <td width="20%" style="padding-left: 10">Loginname:<font color="#FF0000">*</font></td>
    <td width="80%">
    <input type="text" name="loginname" size="35" value="{loginname}" notnull alt="User name"></td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Full name:<font color="#FF0000">*</font></td>
    <td width="80%">
    <input type="text" name="fullname" size="35" value="{fullname}" notnull alt="Full name"> Lương <input type="text" name="luong" size="16" value="{luong}"> Trách nhiệm <input type="text" name="trach_nhiem" size="16" value="{trach_nhiem}"></td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Address:</td>
    <td width="80%">
    <input type="text" name="address" size="69" value="{address}"></td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Phone:</td>
    <td width="80%" >
    <input type="text" name="phone" size="16" value="{phone}"></td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Điểm cộng:</td>
    <td width="80%" >
    <input type="text" name="cellphone" size="16" value="{cellphone}"></td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Email:<font color="#FF0000">*</font></td>
    <td width="80%">
    <input type="text" name="email" size="35" value="{email}" email notnull alt="Email"></td>
  </tr>
  <tr >
    <td width="22%" height="25" >Note:</td>
    <td width="78%"><textarea name="note" cols="40" rows="5">{note}</textarea></td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Phòng ban chính:</td>
    <td width="80%" >
      <select size="1" name="member_type_id">
        <option value=0>Chọn Phòng ban</option>
        <!-- BEGIN member_type_list -->
        <option value="{member_type_list.member_type_id}">{member_type_list.member_type_name}</option>
        <!-- END member_type_list -->
      </select>
    </td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Phòng ban kiêm nhiệm:</td>
    <td width="80%">
      <select size="1" name="extra_member_type_id">
        <option value=0>Chọn Phòng ban</option>
        <!-- BEGIN member_extra_type_list -->
        <option value="{member_extra_type_list.member_type_id}" {member_extra_type_list.extra_selected}>{member_extra_type_list.member_type_name}</option>
        <!-- END member_extra_type_list -->
      </select>
      <font color="#666666"> Chọn nếu nhân viên quản lý thêm một phòng ban khác.</font>
    </td>
  </tr>
  <tr>
    <td colspan="2" height="25" style="background:#808080;color:#FFFFFF;font-weight:bold;padding-left:10">Thông tin BHXH</td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Mã số BHXH:</td>
    <td width="80%"><input type="text" name="bhxh_code" size="25" value="{bhxh_code}"></td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Lương đóng BHXH:</td>
    <td width="80%"><input type="text" name="bhxh_salary" size="16" value="{bhxh_salary}"></td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Ngày bắt đầu:</td>
    <td width="80%"><input type="date" name="bhxh_start_date" value="{bhxh_start_date}"></td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Trạng thái BHXH:</td>
    <td width="80%">
      <select size="1" name="bhxh_status">
        <option value="0">Chưa tham gia / Đã ngưng</option>
        <option value="1">Đang tham gia</option>
      </select>
    </td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Ghi chú BHXH:</td>
    <td width="80%"><textarea name="bhxh_note" cols="40" rows="3">{bhxh_note}</textarea></td>
  </tr>
  <tr>
    <td width="20%" style="padding-left: 10">Status:</td>
    <td width="80%">
    <input type="radio" value="1" checked name="active" id="fp1" ><label for="fp1">Active </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" value="0"  {active} name="active" id="fp2" ><label for="fp2">Deactive</label></td>
  </tr>
   <tr style="display:none">
    <td width="20%" style="padding-left: 10">Role:</td>
    <td width="80%">{role}
    
  </tr>
</form>  
</table>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>

<Script Language="javaScript">
function returnToList()
{	document.location = '?option={funname}&mode=list&l={LANGUAGEID}'
}

function doSave()
{	if (verify(document.mainForm)) 
		document.mainForm.submit()
}
mainForm.member_type_id.value  = {member_type_id}
mainForm.bhxh_status.value  = {bhxh_status}
</Script>
