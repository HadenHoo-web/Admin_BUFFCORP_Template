<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
  <a href="?option={funname}&mode=info&sup_id={sup_id}"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Thêm danh mục"><span>Thêm danh mục</span></a>
  <a href="#" style="{back_style}" onClick="returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Trở về" width="20" height="20"><span>Trở về</span></a>
</div>
<div class="tabtitle">
Quản lý cây menu <span class="itemName">{sup_fun_name}</span>
</div>
<div>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="selector function-menu-selector" style="border-collapse:collapse">
  <tr class="header">
    <td width="5%" align="center">#</td>
    <td width="32%">Danh mục</td>
    <td width="18%">Mã chức năng</td>
    <td width="25%">Description</td>
    <td width="10%" align="center">Danh mục con</td>
    <td width="15%" align="center">Thao tác</td>
  </tr>
<!-- BEGIN list -->
  <tr class="{list.className}">
    <td width="5%" align="center">{list.order}</td>
    <td width="32%">
      <a class="menu-tree-link {list.node_class}" href="{list.node_link}">
        <span>{list.fun_name}</span>
      </a>
    </td>
    <td width="18%"><code class="menu-code">{list.code}</code></td>
    <td width="25%">{list.description}</td>
    <td width="10%" align="center"><span class="menu-child-count">{list.child_count}</span></td>
    <td width="15%" align="center">
      {list.permission_action}
      <a href="?option={funname}&mode=info&id={list.fun_id}&sup_id={sup_id}"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Sửa" width="20" height="20"></a>
      <a href="#" onClick="if(confirm('Bạn có chắc muốn xóa danh mục này?')) { document.location='?option={funname}&mode=delete&id={list.fun_id}&sup_id={sup_id}' }"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Xóa"></a>
    </td>
  </tr>
<!-- END list -->
</table>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<script type="text/javascript">
function returnToList()
{
  document.location='?option={funname}&mode=list&l={LANGUAGEID}&sup_id={back_id}';
}
</script>
