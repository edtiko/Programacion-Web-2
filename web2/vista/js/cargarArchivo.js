/*
 filedrag.js - HTML5 File Drag & Drop demonstration
 Featured on SitePoint.com
 Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
 */
$(function() {	
    function FileSelectHandler(e) {


        // fetch FileList object
        var files = e.target.files || e.dataTransfer.files;

        // process all File objects
        for (var i = 0, f; f = files[i]; i++) {
            UploadFile(f);
        }

    }
    // upload TXT files
    function UploadFile(file) {


        var xhr = jQuery.ajaxSettings.xhr(),
                data = new FormData();
        if (xhr.upload) {
            // start upload
            xhr.open("POST", "control/Controller.php", false);
            // xhr.setRequestHeader("Cache-Control", "no-cache");
            //xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            //xhr.setRequestHeader("X-File-Name", file.name);
            data.append("file", file);
            xhr.send(data);
            if (xhr.responseText != null) {
                document.getElementById("divtxt").innerHTML = xhr.responseText;
                //$("#sv_art").css("display","block");
            }
        }

    }

    // call initialization file
    if (window.File && window.FileList && window.FileReader) {
        var file = document.getElementById('filetxt');
        file.addEventListener("change", FileSelectHandler, false);

    }


});