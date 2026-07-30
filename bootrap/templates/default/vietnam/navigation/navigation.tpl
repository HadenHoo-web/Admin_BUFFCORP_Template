<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Navigation bar</title>
<Script Language="JavaScript">
var arrNavigation=
<!-- CODE echo mosNavigation(0, "Root"); -->

var num_properties = 3
function reDrawNode()
{	init()
	drawNode(arrNavigation)
}

function drawNode(obj)
{	var nodeType = obj[0];
	if (obj.length > num_properties)
		document.write('<div class="ct"  onClick="expand (this)">')
	else
		document.write('<div class="ct">')
	if (nodeType == 1)
	{	if (obj.length > num_properties)
				document.write('<img border="0" src="templates/{skin}/images/m.gif" class="tc" width="16" height="16"><a target="main" href="?option=pages/pages&mode=list&l={LANGUAGEID}&cid='+obj[1]+'">' + obj[2] + '</a>')
		else
				document.write('<img border="0" src="templates/{skin}/images/m.gif" width="16" height="16"><a target="main" href="?option=pages/pages&mode=list&l={LANGUAGEID}&cid='+obj[1]+'">'  + obj[2] + '</a>')

		if (obj.length > num_properties)
		{	document.write('<div class="subdir">')
			for (var i = num_properties; i < obj.length; i++)
			{		drawNode(obj[i]);	
			}
			document.write('</div>')
		}
	} else
	{	
	document.write( '&nbsp;<a target="main" href="?option=pages/pages&mode=info&l={LANGUAGEID}&id='+obj[1]+'">'  + obj[2] + ' </a><img border="0" src="templates/{skin}/images/spacer.gif" width="16" height="16">')
	}
	document.write('</div>')
}
</Script>
<base target="main">
<body topmargin="0" leftmargin="0" bgcolor="#008080" style="font-family: Arial; font-size: 10px">

<div width="210" height="100%">
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; background-color: #ECE9D8" bordercolor="#FFFFFF" class="leftnav">
 <tr>
        <td width="100%" align="center">
        <p style="margin-top: 0; margin-bottom: 0">
        <img border="0" src="../images/logo.gif"></p>
        <p style="margin-top: 0; margin-bottom: 3; margin-right:4" align="right">Site: 
<!-- DO ComboFromTable("language_id", "tbl_languages", "language_id", "language_name", "language_id", 0, "" , "" , "isactive = 1" , "reShow(this.value)", 1) -->
        </td>
   </tr>
   <tr>
   <td align="right"><span style="display:{allow_menu}"><a target="main" href="?option=functionmenu/functionmenu&mode=list&id=0"> Menu | </a></span><a href="logout.php">Log out | </a><span class="membername">{membername} (<a href="main.php?option=common_lists/giaoviec&mode=list&l=2"><span style="color: red;font-size: 14px;font-weight: bold;">{giaoviec_sum}</span></a>)</span></td>
   </tr>
   <tr>
   <td valign='top' id='' class='normalrow' OnClick='changeClass(this)'><div class='header'><form method="POST" action="?option=product/product&mode=info" name="search" style="margin:0px;" target="main"><strong>Quản Lý Tin Tức</strong>
   
   &nbsp;&nbsp;
        <img src="templates/{skin}/images/reload.png" border="0" align="absmiddle" OnClick="refresh()"> IDpage : <input name="id" type="text"  maxlength="6" size="2"  /></form></div>
		
   <div class=subdir id="main" width="100%" height="100%">
<Script language="javascript">
	drawNode(arrNavigation);
</Script>
	</div>
   </td>
   </tr>
<!-- CODE echo mosFunctionMenu(0, "Root"); -->
</table>
</div>
<Script>
	var currentRow = document.all.staticpages

	function changeClass(what)
	{ 
	   if (what.className == 'normalrow')
	   {	what.className = 'mainrow'
   			currentRow.className = 'normalrow'
	   		currentRow = what
	   }
	}
function expand (obj)
{	cn = obj.className;
	cn = ((cn == "ct_hidden") ? "ct" : "ct_hidden")
	if ((event.srcElement.tagName == "IMG") && (event.srcElement.className == "tc"))
	{	if (obj.className == "ct_hidden")
		{	obj.className = cn;
			event.srcElement.src = "templates/{skin}/images/m.gif";
		}
		else
		{	obj.className = cn;
			event.srcElement.src = "templates/{skin}/images/p.gif";
		}
		event.cancelBubble = true;
	}
}	
function reShow(language_id)
{	parent.document.location="main.htm?l="+language_id
}
language_id.value = '{LANGUAGEID}'
var sURL = unescape(window.location);
function refresh()
{	document.location = sURL;
}
function searchPage()
{	
	document.urlPage.href = "#";
}
</Script>
