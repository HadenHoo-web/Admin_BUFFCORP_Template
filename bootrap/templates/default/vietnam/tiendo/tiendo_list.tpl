<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<div class="toolbar"> <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Tạo mới</span></a>
</div>
<Script Language="JavaScript">
function doCreate()
{	
	document.location='?option={funname}&mode=info&l={LANGUAGEID}'
}
function doReShow()
{	
	//document.location='?option={funname}&mode=list&l={LANGUAGEID}&month=' + month.value + '&thuchi=' + thuchi.value
	mainForm.submit();
}

function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}

function deleteItem(id)
{	if (confirm ("Are you sure you want to delete ?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
}	
</Script>
<div class="tabtitle"><form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" id="mainForm" style="margin:0px;" >
	<input type="hidden" name="option" id="option" value="{funname}">
	<input type="hidden" name="mode" id="mode" value="list">
    Danh sách: Sum = {sum} => Tháng <select name="month" size="1" onchange="doReShow()"><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select><select name="thuchi" onchange="doReShow()"><option value="2">All</option><option value="0">Thu</option><option value="1">Chi</option></select></form></div><script>mainForm.month.value = '{month}';mainForm.thuchi.value = '{thuchi}';</script>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="30" align="center" >#</td>
      <td width="100" align="left" >Ngày</td>
      <td width="100" align="left" >Số tiền</td>
      <td width="400" align="left" >Lý do</td>
      <td  colspan="4" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td style="vertical-align:middle">{list.ngay}</td>
      <td style="vertical-align:middle; text-align:right;">{list.thuchi} {list.tiendo_name}</td>
      <td style="vertical-align:middle">{list.tiendo_code}</td>
      <td width="23" align="center"><a href="?option={funname}&mode=up&id={list.tiendo_id}&l={LANGUAGEID}"  target="_self"> <img border="0" src="templates/{skin}/images/up.png" width="16" height="16" style="{list.up}"></a> </td>
      <td width="28" align="center"><a href="?option={funname}&mode=down&id={list.tiendo_id}&l={LANGUAGEID}" target="_self"> <img  style="{list.down}" border="0" src="templates/{skin}/images/down_blue.png" width="16" height="16"></a></td>
      <td width="30" align="center"><a href="JavaScript:updateItem({list.tiendo_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="42" align="center"><a href="JavaScript:deleteItem({list.tiendo_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>