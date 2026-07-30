<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <div class="toolbar">
<a href="JavaScript:doRefresh()"><img border="0" src="templates/{skin}/images/reload.png" width="20" height="20" alt="Send"><span>Refresh</span></a></div>
 <div class="tabtitle">
 Nhiệm vụ </div>
 <div style="overflow:auto; height:80%">
<table width="100%"> 
  <tr>
	<td></td><td>Đã thực hiện</td>
  </tr>
<!-- BEGIN member_list -->

  <tr>
    <td width="12%" height="23" valign="top" style="padding-left: 10">{member_list.name} :</td>
    <td width="88%" height="23">
	<table width="100%" border="0">
<!-- BEGIN nhiemvu_list -->
  <tr>
    <td>{member_list.nhiemvu_list.nhiemvu_name} : <img src="templates/{skin}/images/vote_lcap.gif" border=0 align="absmiddle"><img border="0" src="templates/{skin}/images/voting_bar.gif" width="{member_list.nhiemvu_list.rong}" height="13" alt="Send" align="absmiddle"><img src="templates/{skin}/images/vote_rcap.gif" border=0 align="absmiddle" /> &nbsp; {member_list.nhiemvu_list.dem} lần </td>
  </tr>
<!-- END nhiemvu_list -->
</table>
	</td>
  </tr>  
<!-- END member_list -->
  </table>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
var sURL = unescape(window.location);
function doRefresh()
{	
	document.location = sURL;
}	
</Script>