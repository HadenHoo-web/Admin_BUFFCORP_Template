
	var key_tab = 9;
	var basic = true;

	function openWindow(theURL,winName,features) {
		if (winName.window) winName.focus;
		popupWin = window.open(theURL,winName,features)
		popupWin.focus;
	}
	function deleteConfirm(what,formName,theURL) {
		if (confirm("Are you sure you want to delete "+ what +"? ")) {
			if (formName != null) {
				formName.submit()
			} else {
				window.location.href = theURL
			}
		}
	}

	function processTab() {
		if (window.event.keyCode == key_tab) 
		{
		var s = document.selection;
		var tr = s.createRange();
		if ( tr != null ) 
			tr.text = "\t";
			window.event.returnValue=false;
		}
	}

	_editor_url = "htmlarea/";
	var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
	if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
	if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
	if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
	if (win_ie_ver >= 5.5) {
		document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
		document.write(' language="Javascript1.2"></scr' + 'ipt>');  
	} else {document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>');}
	



