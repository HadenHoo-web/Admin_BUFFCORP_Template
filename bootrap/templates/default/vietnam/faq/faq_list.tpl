<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<div class="toolbar"> <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Tạo mới</span></a>
</div>

<div class="tabtitle">Các câu hỏi thường gặp | Trích lọc <select name="faq_type_id" onchange="reShow()">
<option value="0">All</option>
<!-- BEGIN faq_type_list -->
	<option value="{faq_type_list.faq_type_id}">{faq_type_list.faq_type_name}</option>
<!-- END faq_type_list -->
</select></div>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="73" align="center" >#</td>
      <td width="150" align="left" >Câu hỏi</td>
      <td width="379" align="left" >Trả lời</td>
      <td  colspan="4" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td width="73" align="center" style="vertical-align:middle">{list.order}</td>
      <td width="150" style="vertical-align:middle">{list.q}</td>
      <td width="379" style="vertical-align:middle">{list.a}</td>
      <td width="18" align="center"><a href="?option={funname}&mode=up&id={list.faq_id}&l={LANGUAGEID}"  target="_self"> <img border="0" src="templates/{skin}/images/up.png" width="16" height="16" style="{list.up}"></a> </td>
      <td width="20" align="center"><a href="?option={funname}&mode=down&id={list.faq_id}&l={LANGUAGEID}" target="_self"> <img  style="{list.down}" border="0" src="templates/{skin}/images/down_blue.png" width="16" height="16"></a></td>
      <td width="23" align="center"><a href="JavaScript:updateItem({list.faq_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="30" align="center"><a href="JavaScript:deleteItem({list.faq_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">

function doCreate()
{	
	document.location='?option={funname}&mode=info&l={LANGUAGEID}'
}

function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}

function deleteItem(id)
{	if (confirm ("Are you sure you want to delete ?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
}	
function reShow()
{	
	document.location = '?option={funname}&mode=list&l={LANGUAGEID}&faq_type_id=' + faq_type_id.value
}
faq_type_id.value = {faq_type_id}
</Script>

