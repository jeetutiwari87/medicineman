jQuery(document).ready(function(e){e("#commentform").attr("enctype","multipart/form-data"),e("#do_uploadFile").click(function(){e("#ywar-uploadFile").click()}),e("#ywar-uploadFile").on("change",function(){var e=document.getElementById("ywar-uploadFile");if(attach.limit_multiple_upload>0&&e.files.length>attach.limit_multiple_upload)return alert("Too many files selected."),void(this.value="");for(var t=document.getElementById("uploadFileList"),i=function(e,t){var i=new FileReader;i.readAsDataURL(e[t]),i.onload=function(e){document.getElementById("img_preview"+t).src=e.target.result}};t.hasChildNodes();)t.removeChild(t.firstChild);for(var l=0;l<e.files.length;l++){var a=document.createElement("li");a.innerHTML='<div style="display: inline;"><img id="img_preview'+l+'" style="width: 100px; height: 100px;"></div>',i(e.files,l),t.appendChild(a)}if(!t.hasChildNodes()){var a=document.createElement("li");a.innerHTML="No Files Selected",t.appendChild(a)}}),e("#commentform").submit(function(){var e=document.getElementById("ywar-uploadFile");return attach.limit_multiple_upload>0&&e.files.length>attach.limit_multiple_upload?!1:void 0})});