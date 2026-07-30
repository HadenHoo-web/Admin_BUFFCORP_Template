<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css">
  .hosting-type{
    display:inline-block;
    padding:3px 8px;
    border-radius:12px;
    font-size:11px;
    font-weight:bold;
    line-height:1.2;
    white-space:nowrap;
    color:#fff;
  }
  .hosting-type.customer{
    background:#c62828; /* đỏ */
  }
  .hosting-type.demo{
    background:#f8caca; /* hồng nhạt */
    color:#8a2a2a;
  }
	  .hosting-type.internal{
	    background:#2e7d32; /* xanh lá */
	  }
	  .host-renew-global{
	    margin:4px 0;
	    padding:5px 8px;
	    border:1px solid #d6cdae;
	    background:#fffdf3;
	    font-weight:bold;
	  }
	  .host-renew-global input[type="text"]{
	    width:520px;
	  }
	</style>

<div class="toolbar">
  <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new"><span>Create new</span></a>
	</div>

	<form action="main.php" method="POST" name="renewForm" id="renewForm" class="host-renew-global">
	  <input type="hidden" name="option" value="{funname}">
	  <input type="hidden" name="mode" value="renew">
	  <input type="hidden" name="l" value="{LANGUAGEID}">
	  <input type="hidden" name="return_to" value="list">
	  <input type="hidden" name="id" id="renew_host_id" value="">
	  Gia hạn nhanh:
	  <input type="text" name="payment_content" id="renew_payment_content" value="" placeholder="Dán nội dung thanh toán vào đây">
	  <input type="button" value="Xác nhận gia hạn" onclick="submitRenewGlobal()">
	</form>

<div class="tabtitle">
  <form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" id="mainForm" style="margin:0px;">
    <input type="hidden" name="option" id="option" value="{funname}">
    <input type="hidden" name="mode" id="mode" value="list">
    Danh sách Host.

    <select name="member_id1" onchange="doReShow()" style="display: {allow_member};">
      <option value="0">All NV</option>
      <!-- BEGIN member_list -->
      <option value="{member_list.member_id}">{member_list.member_name}</option>
      <!-- END member_list -->
    </select>

    &nbsp;&nbsp;
    <select name="hosting_type_filter" onchange="doReShow()">
      <option value="0">Tất cả phân loại</option>
      <option value="1">Customer Hosting</option>
      <option value="2">Demo Hosting</option>
      <option value="3">Internal System Hosting</option>
    </select>
  </form>
</div>

<script>
  if (mainForm.member_id1) mainForm.member_id1.value = '{member_id}';
  if (mainForm.hosting_type_filter) mainForm.hosting_type_filter.value = '{hosting_type_filter}';
</script>

<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%" class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="30" align="center">#</td>
      <td width="150" align="left">Host</td>
      <td width="140" align="left">Phân loại</td>
      <td width="50" align="left">Price</td>
      <td width="190" align="left">Khách Hàng</td>
      <td width="90" align="left">Hết Hạn</td>
      <td width="80" align="left">IP</td>
      <td width="180" align="left">Url</td>
      <td width="100" align="left">User</td>
      <td width="170" align="left">Pass</td>
      <td width="80" align="left">NV QL</td>
      <td width="30" align="left">Active</td>
		      <td colspan="4"></td>
    </tr>

    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td style="vertical-align:middle">{list.host_name}</td>

      <!-- NEW: cột phân loại hosting -->
      <td style="vertical-align:middle">
        <span class="{list.hosting_type_class}">{list.hosting_type_text}</span>
      </td>

      <td style="vertical-align:middle">{list.price}</td>
      <td style="vertical-align:middle">{list.customer_name}</td>
      <td style="vertical-align:middle;color:{list.bg_host}">{list.end_date} {list.days}</td>
      <td style="vertical-align:middle">{list.ip_host}</td>
      <td style="vertical-align:middle"><a href="{list.url}" target="_blank">{list.url}</a></td>
      <td style="vertical-align:middle">{list.username}</td>
      <td style="vertical-align:middle">{list.pass}</td>
      <td style="vertical-align:middle">{list.member_name}</td>
      <td width="70" align="center" style="vertical-align:middle">
        <span style="display:{list.active}">
          <font face="Wingdings"><b><font size="3" color="#008000">ü</font></b></font>
        </span>
      </td>

      <td width="20" align="center">
        <a href="?option={funname}&mode=up&id={list.host_id}&l={LANGUAGEID}" target="_self">
          <img border="0" src="templates/{skin}/images/up.png" width="16" height="16" style="{list.up}" style="display:{list.is_owner}">
        </a>
      </td>

      <td width="20" align="center">
        <a href="?option={funname}&mode=down&id={list.host_id}&l={LANGUAGEID}" target="_self">
          <img style="{list.down}" border="0" src="templates/{skin}/images/down_blue.png" width="16" height="16" style="display:{list.is_owner}">
        </a>
      </td>

	      <td width="24" align="center">
	        <a href="JavaScript:updateItem({list.host_id})">
	          <img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20" style="display:{list.is_owner}">
	        </a>
	      </td>

		      <td width="35" align="center">
		        <a href="JavaScript:deleteItem({list.host_id})">
		          <img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete" style="display:{list.is_owner}">
		        </a>
		      </td>
		    </tr>
		    <!-- END list -->
  </table>
</div>

<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>

<Script Language="JavaScript">
  function doReShow(){
    mainForm.submit();
  }

  function doCreate()
  {
    document.location='?option={funname}&mode=info&l={LANGUAGEID}'
  }

  function updateItem(id)
  {
    document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
  }

		  function deleteItem(id)
		  {
		    if (confirm("Are you sure you want to delete ?."))
		      document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
		  }

		  function submitRenewGlobal()
		  {
		    var input = document.getElementById('renew_payment_content');
		    if (!input || input.value.replace(/^\s+|\s+$/g, '') == '') {
		      alert('Vui lòng nhập nội dung thanh toán');
		      if (input) input.focus();
		      return;
		    }
		    if (!confirm('Xác nhận gia hạn hosting theo nội dung thanh toán này?')) return;
		    document.getElementById('renew_host_id').value = '';
		    document.getElementById('renewForm').submit();
		  }
	</Script>
