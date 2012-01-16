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

// check if the user input correct type of files
function checkExdForSoilfile() {
	var form1 = document.getElementById("form1");
	var file1 = document.getElementById("FilePath").value;
	var patt1=new RegExp(".+[\\.][Ss][Oo][Ll]");
	
	if (file1 == "") {
		alert("Please select a soil data file.");
	} else if (patt1.test(file1)) {
		form1.submit();
	} else {
		alert("Please confirm the extension name of files!");
	}
}

// check if the user input correct type of files
function checkExdForSoilfile() {
	var form1 = document.getElementById("form1");
	var file1 = document.getElementById("FilePath").value;
	var patt1=new RegExp(".+[\\.][Ss][Oo][Ll]");
	
	if (file1 == "") {
		alert("Please select a soil data file.");
	} else if (patt1.test(file1)) {
		form1.submit();
	} else {
		alert("Please confirm the extension name of files!");
	}
}

// check if the user input correct type of files
function checkExdForWthfile() {
	var form1 = document.getElementById("form1");
	var file1 = document.getElementById("FilePath").value;
	var patt1=new RegExp(".+[\\.][Ww][Tt][Hh]");
	
	if (file1 == "") {
		alert("Please select a weather data file.");
	} else if (patt1.test(file1)) {
		form1.submit();
	} else {
		alert("Please confirm the extension name of files!");
	}
}

// check if the user input correct type of files
function checkExdForObvfile() {
	var form1 = document.getElementById("form1");
	var fileT= document.getElementById("FilePath_T").value;
	var fileA = document.getElementById("FilePath_A").value;
	var pattT=new RegExp(".+[\\.][a-zA-Z0-9]{2}[Tt]");
	var pattA=new RegExp(".+[\\.][a-zA-Z0-9]{2}[Aa]");
	var upload_file = document.getElementById("upload_file");
	
	if (upload_file.value=="1") {
		form1.submit();
		return;
	}
	
	//if (fileT == "" && fileA == "") {
	//	alert("Please at least select a TFile or AFile.");
	//} else
	if (fileT != "" && !pattT.test(fileT)) {
		alert("Please confirm the extension name of Tfile!");
	} else if (fileA != "" && !pattA.test(fileA)) {
		alert("Please confirm the extension name of Afile!");
	} else {
		form1.submit();
	}
}

// check if the user input correct type of files
function checkExdForExpfile(subType) {
	var form1 = document.getElementById("form1");
	var file1 = document.getElementById("FilePath").value;
	var patt1=new RegExp(".+[\\.][a-zA-Z0-9]{2}[xX]");
	var submitType = document.getElementById("submitType");
	var upload_file = document.getElementById("upload_file");
	
	if (upload_file.value=="1") {
		submitType.value = subType;
		form1.submit();
		return;
	}
	
	if (file1 == "") {
		alert("Please select a experiment data file.");
	} else if (patt1.test(file1)) {
		submitType.value = subType;
		form1.submit();
	} else {
		alert("Please confirm the extension name of files!");
	}
}

// check if the user input correct type of files
function checkExdForSoilfile(subType, idStr) {
	var form1 = document.getElementById("form1");
	var patt1=new RegExp(".+[\\.][Ss][Oo][Ll]");
	var submitType = document.getElementById("submitType");
	var flg = 0;
	var file1;
	var upload_file_id;
	var upload_file = document.getElementById("upload_file");
	
	if (upload_file.value=="1") {
		submitType.value = subType;
		form1.submit();
		return;
	}
	
	ids = idStr.split(",");
	for (i=0; i<ids.length; i++) {
		upload_file_id = document.getElementById("upload_file_"+ ids[i]);
		file1 = document.getElementById("FilePath_"+ ids[i]);
		if (upload_file_id.value == "0" && !(file1.disabled)) {
			if (file1.value == "") {
				flg = 1; break;
			} else if (!patt1.test(file1.value)) {
				flg = 2; break;
			}
		}
	}
	if (flg == 0) {
		submitType.value = subType;
		form1.submit();
	} else if (flg == 1) {
		alert("Please select a soil data file.");
	} else {
		alert("Please confirm the extension name of files!");
	}
}

// check if the user input correct type of files
function checkExdForWthfile(subType, idStr) {
	var form1 = document.getElementById("form1");
	var patt1=new RegExp(".+[\\.][WwMm][Tt][HhGg]");
	var submitType = document.getElementById("submitType");
	var flg = 0;
	var file1;
	var upload_file_id;
	var upload_file = document.getElementById("upload_file");
	
	if (upload_file.value=="1") {
		submitType.value = subType;
		form1.submit();
		return;
	}
	
	ids = idStr.split(",");
	for (i=0; i<ids.length; i++) {
		upload_file_id = document.getElementById("upload_file_"+ ids[i]);
		file1 = document.getElementById("FilePath_"+ ids[i]);
		if (upload_file_id.value == "0" && !(file1.disabled)) {
			if (file1.value == "") {
				flg = 1; break;
			} else if (!patt1.test(file1.value)) {
				flg = 2; break;
			}
		}
	}
	if (flg == 0) {
		submitType.value = subType;
		form1.submit();
	} else if (flg == 1) {
		alert("Please select a weather data file.");
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

function changeActiveStatus(idStr) {
	var chk = document.getElementById("check_"+idStr);
	var filePath = document.getElementById("FilePath_"+idStr);
	var fileId = document.getElementById("FileId_"+idStr);
	if (chk.checked) {
		filePath.disabled = false;
		fileId.disabled = false;
	} else {
		filePath.disabled = true;
		fileId.disabled = true;
	}
}

function changeToUpload() {
	var upload_file = document.getElementById("upload_file");
	var file1 = document.getElementById("FilePath").value;
	if (file1 != "") {
		upload_file.value = "0";
	}
}

function changeToUploadById(idStr) {
	var upload_file = document.getElementById("upload_file");
	var upload_file_id = document.getElementById("upload_file_" + idStr);
	var file1 = document.getElementById("FilePath_" + idStr).value;
	if (file1 != "") {
		upload_file.value = "0";
		upload_file_id.value = "0";
	}
}