<script src="js/Chart.min.js"></script>
<Script Language="JavaScript">
var sURL = unescape(window.location);
function doRefresh()
{	
	document.location = sURL;
}	
function chitiet(id)
{
	if(document.getElementById(id).style.display == 'none'){
		document.getElementById(id).style.display = '';
		lat = 2;
	}
	else if(document.getElementById(id).style.display == ''){
		document.getElementById(id).style.display = 'none';
	}
	
}
</Script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <div class="toolbar">
<a href="JavaScript:doRefresh()"><img border="0" src="templates/{skin}/images/reload.png" width="20" height="20" alt="Send"><span>Refresh</span></a></div>
 <div class="tabtitle"> Kênh thông tin tổng hợp</div>
 <div style="overflow:auto; height:90%">
 <table width="100%" border="0">
  <tr>
    <td>
        <!-- bar chart canvas element -->
        <canvas id="income" width="1050" height="200"></canvas> 
<script>
	// bar chart data
	var barData = {
	labels : ["01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"],
	datasets : [
		{
			fillColor : "#339966",
			strokeColor : "#48A4D1",
			data : [
			<!-- BEGIN list_hoaca -->
			{list_hoaca.loinhuan},
			<!-- END list_hoaca -->
			]
		},
		{
			fillColor : "#FF0000",
			strokeColor : "#48A4D1",
			data : [
			<!-- BEGIN list_kieuhung -->
			{list_kieuhung.loinhuan},
			<!-- END list_kieuhung -->
			]
		},
		{
			fillColor : "#00FFFF",
			strokeColor : "#48A4D1",
			data : [
			<!-- BEGIN list_cao -->
			{list_cao.loinhuan},
			<!-- END list_cao -->
			]
		},
		]
	}

	// get bar chart canvas
	var income = document.getElementById("income").getContext("2d");

	// draw bar chart
	new Chart(income).Bar(barData);
</script>   
    </td>
  </tr>
</table>
<table width="100%" border="0">
  <tr>
    <td>
    <script src="js/canvasjs.min.js"></script>
    <script type="text/javascript">
window.onload = function () {
	var chart = new CanvasJS.Chart("chartContainer",
	{
		title:{
			text: "Tỉ lệ"
		},
		data: [
		{
			type: "pie",
			dataPoints: [
				{ y: {ln_hc}},
				{ y: {ln_kh}},
				{ y: {ln_cao}},
			]
		}
		]
	});
	chart.render();
}
	</script>
	<div id="chartContainer" style="height: 400px; width: 100%;"></div> 
    </td>
  </tr>
</table>
<table width="50%" cellspacing="5">   
<!-- BEGIN giaoviec_list -->
  <tr>
    <td width="124" colspan="4"> + {giaoviec_list.giaoviec_name} ( <a href="JavaScript:chitiet('id{giaoviec_list.giaoviec_id}')">Chi tiết</a> )
    <div id="id{giaoviec_list.giaoviec_id}" style="margin:10px; display:none; background-color:#FFFFFF; padding:10px;">{giaoviec_list.chitiet}</div>
    </td>
  </tr>  
<!-- END giaoviec_list -->
  </table>
 <table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:none">
  <tr>
    <td>
	<table width="650" cellspacing="0">   
  <tr>
	<td width="20"></td>
	<td width="135" align="right">Bài đăng trong tháng <script type="text/javascript">var d = new Date();document.write(d.getMonth()+1);</script></td>
	<td width="20"></td>
	<td>Sản phẩm đăng trong tháng <script type="text/javascript">var d = new Date();document.write(d.getMonth()+1);</script></td>
	</tr>
<!-- BEGIN post_list -->
  <tr>
    <td valign="top" style="padding-left: 10"> <font color="{post_list.mausac}" size="+1" >&Omicron; </font> {post_list.created_by} :</td>
	<td><div style="text-align:right">{post_list.post_page}</div></td>
	<td></td>
    <td height="23"><img src="templates/{skin}/images/vote_lcap.gif" border=0 align="absmiddle"><img border="0" src="templates/{skin}/images/voting_bar.gif" width="{post_list.post_width}" height="13" alt="Send" align="absmiddle"><img src="templates/{skin}/images/vote_rcap.gif" border=0 align="absmiddle" /> &nbsp; ( {post_list.dem} )</td>
	</tr>  
<!-- END post_list -->
  </table>
	</td>
  </tr>
</table>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>