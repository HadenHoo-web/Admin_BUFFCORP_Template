<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar"> <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Tạo mới"><span>Nhập kho ban đầu</span></a>
</div>
<Script Language="JavaScript">
function doCreate()
{	
	document.location='?option={funname}&mode=info&l={LANGUAGEID}'
}
function doReShow()
{	
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
<div class="tabtitle">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" id="mainForm" style="margin:0px;" >
<input type="hidden" name="option" id="option" value="{funname}">
	<input type="hidden" name="mode" id="mode" value="list">
Tồn kho : 
<!-- DO ComboFromTable("loaikho_id", "tbl_loaikho", "loaikho_id", "loaikho_name", "loaikho_id", 0, " All " , "0" , "1 order by loaikho_id" , "doReShow()", 1) -->
 {von} : {loi}
</form>
</div>
<script>mainForm.loaikho_id.value = {loaikho_id}</script>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="1101"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="20" align="center" >#</td>
      <td width="60" align="left" >Kho</td>
      <td width="40" align="left" >ID SP</td>
      <td width="300" align="left" >Sản phẩm</td>
      <td width="50" align="left" >&nbsp;</td>
      <td width="50" align="left" >Giá bán</td>
      <td width="50" align="left" >SL( {sum} )</td>
      <td width="250" align="left" >Ghi chú</td>
      <td  colspan="4" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle;background:{list.bg}">{list.order}</td>
      <td style="vertical-align:middle; text-align:right;">{list.loaikho_name}</td>
      <td style="vertical-align:middle; text-align:right;">{list.product_id}</td>
      <td style="vertical-align:middle; text-align:right;"><a href="../_{list.product_id}_detail.htm" target="_blank">{list.product_name}</a></td>
      <td style="vertical-align:middle; text-align:right;">{list.giagoc}</td>
      <td style="vertical-align:middle; text-align:right;">{list.giaban}</td>
      <td style="vertical-align:middle; text-align:right;">{list.soluong}</td>
      <td>{list.noidung}</td>
      <td width="23" align="center"><a href="?option={funname}&mode=up&id={list.kho_id}&l={LANGUAGEID}"  target="_self"> <img border="0" src="templates/{skin}/images/up.png" width="16" height="16" style="{list.up}"></a> </td>
      <td width="28" align="center"><a href="?option={funname}&mode=down&id={list.kho_id}&l={LANGUAGEID}" target="_self"> <img  style="{list.down}" border="0" src="templates/{skin}/images/down_blue.png" width="16" height="16"></a></td>
      <td width="30" align="center"><a href="JavaScript:updateItem({list.kho_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="42" align="center"><a href="JavaScript:deleteItem({list.kho_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>