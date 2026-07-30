/***********************************************************
	Copyright © 2003, InnovaStudio.com. All rights reserved.
************************************************************/
	
function drawPlate()
	{
	document.write("<table cellpadding=0 cellspacing=0 style='position:relative;width:164;height:122'><tr><td>")
		
	arr1 = [["00FF","33FF","66FF","99FF","CCFF","FFFF"],
			["00CC","33CC","66CC","99CC","CCCC"],
			["0099","3399","6699","9999","FFCC"],
			["0066","3366","6666","CC99"],
			["0033","3333","9966","FF99"],
			["0000","6633","CC66"],
			["3300","9933","FF66"],
			["6600","CC33"],
			["9900","FF33"],
			["CC00"],
			["ff00"]]

	arrComp1 = new Array("FF","CC","99")

	for(var i=0;i<arr1.length;i++)
		{
		document.write("<table id='id1"+i+"' cellpadding=0 cellspacing=0 style='table-layout:fixed;position:absolute'><tr>")
		for(var j=0;j<arr1[i].length;j++)
			{
			for(var k=0;k<arrComp1.length;k++)
				{
				C=arr1[i][j]+arrComp1[k]+"";
				document.write("<td onclick=\"doClick('"+C+"');\" onmouseover=\"doMouseOver('"+C+"');this.style.border='#333333 1 solid'\" onmouseout=\"this.style.border=''\" style='cursor:hand;width:9;height:10;background-color:#"+C+";'></td>")
				}
			}
		document.write("</tr></table>")
		}
		
	arr2 = [["FF00"],
			["CC00"],
			["FF33","9900"],
			["CC33","6600"],
			["FF66","9933","3300"],
			["CC66","6633","0000"],
			["FF99","9966","3333","0033"],
			["CC99","6666","3366","0066"],
			["FFCC","9999","6699","3399","0099"],
			["CCCC","99CC","66CC","33CC","00CC"],
			["FFFF","CCFF","99FF","66FF","33FF","00FF"]]
				
	arrComp1 = new Array("00","33","66")

	for(var i=0;i<arr2.length;i++)
		{
		document.write("<table id='id2"+i+"' cellpadding=0 cellspacing=0 style='table-layout:fixed;position:absolute'><tr>")
		for(var j=0;j<arr2[i].length;j++)
			{
			for(var k=0;k<arrComp1.length;k++)
				{
				C=arr2[i][j]+arrComp1[k]+"";
				document.write("<td onclick=\"doClick('"+C+"');\" onmouseover=\"doMouseOver('"+C+"');this.style.border='#333333 1 solid'\" onmouseout=\"this.style.border=''\" style='cursor:hand;width:9;height:10;background-color:#"+C+";'></td>")
				}
			}
		document.write("</tr></table>")
		}
		
	document.write("</td></tr></table>")
		
	id10.style.top=0
	id10.style.left=0
	id11.style.top=10
	id11.style.left=0
	id12.style.top=20
	id12.style.left=0
	id13.style.top=30
	id13.style.left=0
	id14.style.top=40
	id14.style.left=0
	id15.style.top=50
	id15.style.left=0
	id16.style.top=60
	id16.style.left=0
	id17.style.top=70
	id17.style.left=0
	id18.style.top=80
	id18.style.left=0
	id19.style.top=90
	id19.style.left=0
	id110.style.top=100
	id110.style.left=0
		
	id20.style.top=0+10+2		
	id20.style.left=135+2
	id21.style.top=10+10+2
	id21.style.left=135+2
	id22.style.top=20+10+2
	id22.style.left=108+2
	id23.style.top=30+10+2
	id23.style.left=108+2
	id24.style.top=40+10+2
	id24.style.left=81+2
	id25.style.top=50+10+2
	id25.style.left=81+2
	id26.style.top=60+10+2
	id26.style.left=54+2
	id27.style.top=70+10+2
	id27.style.left=54+2
	id28.style.top=80+10+2
	id28.style.left=27+2
	id29.style.top=90+10+2
	id29.style.left=27+2
	id210.style.top=100+10+2	
	id210.style.left=0+2
	}
	
function doMouseOver(color)
	{
	idPreview.style.backgroundColor=color
	idPreviewText.innerText=color
	idRed.innerText=convertHexToDec(color.substr(0,2))
	idGreen.innerText=convertHexToDec(color.substr(2,2))
	idBlue.innerText=convertHexToDec(color.substr(4,2))
	}
	
function doClick(color)
	{
	idSelColorPreview.style.backgroundColor=color
	idSelColorText.innerText=color
	idSelColorRange.innerHTML=colorRange(color)
	}
	
function updateSelColor()
	{
	idSelColorPreview.style.backgroundColor=idSelColorText.value;
	idSelColorRange.innerHTML=colorRange(idSelColorText.value)
	}
	
function convertHexToDec(hex)
	{
	return parseInt(hex,16);
	}
	
function convertDecToHex2(dec)
	{
 	var tmp = parseInt(dec).toString(16);
 	if(tmp.length == 1) tmp = ("0" +tmp);
 	return tmp.toUpperCase();
	}
	
function convertDecToHex3(dec)
	{
 	var tmp = parseInt(dec).toString(16);

 	if(tmp.length == 1) tmp = ("00000" +tmp);
 	if(tmp.length == 2) tmp = ("0000" +tmp);
 	if(tmp.length == 3) tmp = ("000" +tmp);
 	if(tmp.length == 4) tmp = ("00" +tmp);
 	if(tmp.length == 5) tmp = ("0" +tmp);

 	tmp = tmp.substr(4,1) + tmp.substr(5,1) + tmp.substr(2,1) + tmp.substr(3,1) + tmp.substr(0,1) + tmp.substr(1,1)
 	return tmp.toUpperCase();
	}		
	
function colorRange(sHex)
	{
	if(sHex==""){return "";}
	if(sHex.substr(0,1)=="#"){sHex=sHex.substr(1)}
	nR=convertHexToDec(sHex.substr(0,2));
	nG=convertHexToDec(sHex.substr(2,2));
	nB=convertHexToDec(sHex.substr(4,2));

	if(nR.toString()=="NaN")return "";
	if(nG.toString()=="NaN")return "";
	if(nB.toString()=="NaN")return "";
	

	nInterval=25.5//34//17

	currR=nR;currG=nG;currB=nB;
	var sHTML=""
	while(currR!=0||currG!=0||currB!=0)
		{
		if(currR>=1)
			{
			currR=currR*1-nInterval;
			if(currR<=0)currR=0;
			}
		else currR=0;
		if(currG>=1)
			{
			currG=currG*1-nInterval;
			if(currG<=0)currG=0;
			}
		else currG=0;
		if(currB>=1)
			{
			currB=currB*1-nInterval;
			if(currB<=0)currB=0;
			}
		else currB=0;
		currHex=""+convertDecToHex2(currR)+convertDecToHex2(currG)+convertDecToHex2(currB)
		sHTML="<tr><td onclick=\"doClick('"+currHex+"');\" onmouseover=\"doMouseOver('"+currHex+"')\" style=\"cursor:hand;width:50;height:5;background-color:rgb("+currR+", "+currG+", "+currB+");\"></td></tr>" + sHTML;
		}
	sHTML=sHTML+"<tr><td onclick=\"doClick('"+sHex+"');\" onmouseover=\"doMouseOver('"+sHex+"')\" style=\"cursor:hand;border-top:black 1 solid;border-bottom:black 1 solid;width:50;height:5;background-color:rgb("+nR+", "+nG+", "+nB+");\"></td></tr>";
	currR=nR;currG=nG;currB=nB;
	while(currR!=255||currG!=255||currB!=255)
		{
		if(currR<=254)
			{
			currR=currR*1+nInterval;
			if(currR>=255)currR=255;
			}
		else currR=255;
		if(currG<=254)
			{
			currG=currG*1+nInterval;
			if(currG>=255)currG=255;
			}
		else currG=255;
		if(currB<=254)
			{
			currB=currB*1+nInterval;
			if(currB>=255)currB=255;
			}
		else currB=255;
			
		currHex=""+convertDecToHex2(currR)+convertDecToHex2(currG)+convertDecToHex2(currB)
		sHTML=sHTML+"<tr><td onclick=\"doClick('"+currHex+"');\" onmouseover=\"doMouseOver('"+currHex+"')\" style=\"cursor:hand;width:50;height:5;background-color:rgb("+currR+", "+currG+", "+currB+");\"></td></tr>";
		}
		
	sHTML="<table cellpadding=0 cellspacing=0 border=0 width=100% height=100%>" + sHTML + "</table>"
	return sHTML;
	}
	