function readFile(txt) {
	var data;
	var b_version=navigator.appVersion;
	var version=parseFloat(b_version);
	var filePath;
	var form1 = document.getElementById("form1");

  try {
		var ip = document.getElementById("FilePath");
		// if browser is not latest version
		if(version<4) {
			filePath = ip.value;
			
		// if brower is IE8 or other browser with newest version
		// TODO: only support IE8 or other browser with old version
		} else {
			ip.select();
			filePath = document.selection.createRange().text;
		}
    var fso = new ActiveXObject("Scripting.FileSystemObject");
    var file = fso.OpenTextFile(filePath, 1);

    data = file.ReadAll();
    alert("Data from file: " + data);
    file.close();
    form1.submit();
    
  }
  catch(e) {
    if (e.number == -2146827859) {
        // This is what we get if the browser's security settings forbid
        // the use of the FileSystemObject ActiveX control   
        alert('Unable to access local files due to browser security settings. To overcome this, go to Tools->Internet Options->Security->Custom Level. Find the setting for "Initialize and script ActiveX controls not marked as safe" and change it to "Enable" or "Prompt"');
    }
    else if (e.number == -2146828218) {
        // This is what we get if the browser can't access the file
        // because of file permissions
        alert("Unable to access local file '" + fileName + "' because of file permissions. Make sure the file and/or parent directories are readable.");
    }
    else {
    	alert("Please use Internet Explorer to try this pages, other browser are not supported yet.");
    	throw e;
    }
  }
}

// check if the user input correct type of files
function checkExdForExpfile() {
	var form1 = document.getElementById("form1");
	var file1 = document.getElementById("FilePath").value;
	var patt1=new RegExp(".+[\\.][a-zA-Z0-9]{2}[xX]");
	
	if (file1 == "") {
		alert("Please select a experiment data file.");
	} else if (patt1.test(file1)) {
		form1.submit();
	} else {
		alert("Please confirm the extension name of files!");
	}
}

// go back the last page of history
function goBack() {
	window.history.back();
}

// Move to pointed address
function goto(adr) {
	window.location = adr;
}

// check the checkBox have been checked at least one before submit
function checkChkbox(idStr) {
	var ids = new Array();
	var errFlg = true;
	
	ids = idStr.split(",");
	for (i=0; i<=ids.length-2; i++) {
		var chk = document.getElementById(ids[i]);
		if (chk.checked == true) {
			errFlg = false;
		}
	}
	if (errFlg) {
		alert ("Please select at least one treatment to save!");
	} else {
		document.getElementById("form1").submit();
	}
}