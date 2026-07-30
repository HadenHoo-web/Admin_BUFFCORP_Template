function verify(theForm)
{	for (i = 0; i < theForm.length; i++)
		{	if (typeof(theForm.elements[i].notnull) != "undefined")
			{	if (theForm.elements[i].value == "")
				{	alert("Please enter value for '"+theForm.elements[i].alt+"' field.")
					theForm.elements[i].focus()
					return false
				}
			}		
			if (typeof(theForm.elements[i].email) != "undefined")
			{	if (!EmailValid(theForm.elements[i].value))
				{	alert("Invalid email address.")
					theForm.elements[i].focus()
					return false
				}
			}
			if (typeof(theForm.elements[i].number) != "undefined")
			{	if (isNull(Trim(theForm.elements[i].value)))
					theForm.elements[i].value = 0
				else
				if (!NumberValid(theForm.elements[i]))
				{	alert("Invalid number")
					theForm.elements[i].focus()
					if (typeof(theForm.elements[i].defaultvalue) != "undefined")
						theForm.elements[i].value = theForm.elements[i].defaultvalue
					else
						theForm.elements[i].value = "0"
					return false
				} 
				else theForm.elements[i].value = parseInt(theForm.elements[i].value)
			}			

			if (typeof(theForm.elements[i].float) != "undefined")
			{	if (!FloatValid(theForm.elements[i]))
				{	alert("invalid float number.")
					theForm.elements[i].focus()
					return false
				} 
				else theForm.elements[i].value = parseFloat(theForm.elements[i].value)
			}

			if (typeof(theForm.elements[i].date) != "undefined")
			{	if (!dateValid(theForm.elements[i]))
				{	alert("Invalid date format.")
					theForm.elements[i].focus()
					return false
				} 
			}

			if (typeof(theForm.elements[i].pwdconfirm) != "undefined")
			{	if (theForm.elements[i].value != theForm.password.value)
				{	alert("The confirm password is invalid.\r\n Please try againt.")
					theForm.elements[i].focus()
					return false
				}
			}
		}
		return true
	}

function trim(str)
	{	var i, j
		for (i = 0; i < str.length; i++)
			if (str.charAt(i) != ' ') break;
		for (j = str.length - 1; j >= 0; j--)
			if (str.charAt(j) != ' ') break;
		return str.substr(i, j - i + 1)
	}

function isNumeric(str)
{	var iLen;
	iLen=str.length;
	var c ;
	for(var i=0; i<iLen; i++)
	{	c = str.charAt(i);
		if ((c<'0') || (c>'9'))
			return false;
	}
	return true;
}

function isFloat(str)
{	var f = str.indexOf('.')
	var l = str.lastIndexOf('.')
	if (f != l)
		return false
	var iLen;
	iLen=str.length;
	var c ;
	for(var i = 0; i<iLen; i++)
	{	c = str.charAt(i);
		if (((c < '0') || (c > '9')) && (c != '.'))
			return false;
	}
	return true;
}

function isNull(str)
{	
	if(str==null)
		return true;
	var iLen = str.length;
	for (var i = 0; i < iLen; i++)
		if (str.charAt(i)!= ' ')
			return false;
	return true;
}

function Trim(str)
{
	while((str.length > 0) && (str.charAt(0) == ' '))
			str = str.substring(1,str.length);
	while((str.length > 0) && (str.charAt(str.length-1) == ' '))
			str = str.substring(0,str.length-1);
	return str;
}

function NumberValid(thefield)
{	var NumStr = Trim(thefield.value)
	if (isNull(NumStr))
	{
		return true
	}
	if (isNaN(parseInt(NumStr)))
		return false
	return true
}

function FloatValid(thefield)
{	var NumStr = Trim(thefield.value)
	if (isNull(NumStr))
		return false
	if (isNaN(parseFloat(NumStr)))
		return false
	return true
}

function dateValid(thefield)
{	
	var dateStr = Trim(thefield.value);
	if (isNull(dateStr))
		return true;
	var i1 = dateStr.indexOf("-");
	var j1 = dateStr.indexOf("-", i1 + 1);
	if ((i1 == -1) || (j1 == -1))
		return false;
	var day = parseInt(dateStr.substr(0, i1), 10);
	var month = parseInt(dateStr.substr(i1 + 1, j1 - i1 - 1), 10);
	var year = parseInt(dateStr.substr(j1 + 1), 10);
	if ((month < 1) || (month > 12))
		return false;
	if (isNaN(day) || isNaN(month) || isNaN(year) || (year < 0)) 		
		return false;

	if (year < 30)
		year += 2000;
	else if (year < 100)
		year += 1900;
	var DOM = 31;
	switch(month)
	{
		case 2:
			DOM = ((((year % 4) == 0) && ((year % 100) != 0)) || ((year % 400) == 0)) ? 29 : 28;
			break;
		case 4:
		case 6:
		case 9:
		case 11:
			DOM = 30;break;
		default:
			DOM = 31;
	}
	if ((day < 1) || (day > DOM))
		return false;
	thefield.value = ((day<10)?"0"+day:day)+"-"+((month < 10)?"0"+month:month)+"-"+year;
	return true;
}

function timeValid(thefield)
{	var timeStr = Trim(thefield.value);
	var i1 = timeStr.indexOf(":");
	var j1 = timeStr.indexOf(":", i1 + 1);
	if ((i1 == -1) || (j1 == -1))
		return false;
	var day = parseInt(dateStr.substr(0, i1), 10);
	var month = parseInt(dateStr.substr(i1 + 1, j1 - i1 - 1), 10);
	var year = parseInt(dateStr.substr(j1 + 1), 10);

	var hour = parseInt(timeStr.substr(0, i1), 10)
	var minute = parseInt(timeStr.substr(i1+1, j1 - i1 - 1), 10)
	var second = parseInt(timeStr.substr(j1+1), 10)

	if (isNaN(hour) || isNaN(minute) || isNaN(second)) 		
		return false;
	if(hour < 0 || hour >12)
		return false;
	if(minute < 0 || hour >59)
		return false;
	if(second < 0 || second >59)
		return false;
	return true
}

function EmailValid(str)
{	if(isNull(str))
		return true;
	var chrs = "~`!#$%^&*()+=|\{}[]':;<>\",/?";
	for(var i = 0; i < chrs.length; i++)
		if(str.indexOf(chrs.charAt(i)) < 0)
			continue;
		else
			return false;	
	var i = str.indexOf("@"), j = str.indexOf("."), k = str.indexOf("@", i + 1)
	if ((i == -1) || (j == -1) || (str.charAt(str.length-1) == '.') || (k >= 0))
		return false;	
	return true;
}