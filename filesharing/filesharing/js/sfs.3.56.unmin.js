/**************
 *
 * SFS V3.56
 *  August 2nd 2020
 *  https://1.envato.market/sfs
 *
 * TOC
 *  1. some helperlies and presets
 *  2. Get Variables-Values-Parser
 *  3. some essential PHP-Values into JS Variables
 *  4. Contact form
 *  5. File uploader
 *  6. Admin-login Autofocus
 *  7. Admin: Datatables
 *  8. Admin: Flot Charts
 *  9. Wait X Seconds before download Countdowner
 * 10. SINGLE: Password protect download links
 * 11. Verify password protected download links 
 * 12. Abuse form
 * 13. MULTI: Password protect download links
 * 14. Filesgroup options
 * 15. don't leave site willy-nilly
 * 16. prevent of re-clicking download button
 * 17. SINGLE: autodelete after X days - set by uploader
 * 18. MULTI: autodelete after X days - set by uploader
 * 19. CAPTCHA: just to reload the image 
 * 20. optical correction for superlong filenames on download and deletion pages 
 * 21. optical correction for superlong filenames on admin dashboard's "The Last File"-section 
 * 22. add files to current upload session
 * 23. back to the uploaded file(s)
 * 24. SINGLE: handle file descriptions
 * 25. MULTI: handle file descriptions
 * 26. ADMIN: settings form(s)
 *
 **************/


 $(document).ready(function() { 

   /******
   * 1. Some helperlies and presets [+]
   ******/ 

    //object dumper
    function dump(obj) {
      var op = '';
      for (var i in obj) {
          op += i + ": " + obj[i] + "\n";
      }
     alert(op);
    }
    //checks if value in array
    function in_array(needle,arr) {
      for(i=0;i<arr.length;i++) {
        if (needle == arr[i]) {
          return true;
        }
      }
      return false;
    }

  //bootstrap tooltips
  $('[data-toggle="tooltip"]').tooltip()


  //http://upshots.org/javascript/jquery-copy-style-copycss
  //out-commented modification to run just with bootstrap-tagsinput
   $.fn.copyCSS = function(source){
      var dom = $(source).get(0);
      var style;
      var dest = {};
      if(window.getComputedStyle){
          var camelize = function(a,b){
              return b.toUpperCase();
          };
          style = window.getComputedStyle(dom, null);
          for(var i = 0, l = style.length; i < l; i++){
              var prop = style[i];
              // if (!prop.match(/^border/) && !prop.match(/^padding/) && !prop.match(/^margin/) && !prop.match(/^font/)) {
                // continue;
              // }
              var camel = prop.replace(/\-([a-z])/g, camelize);
              var val = style.getPropertyValue(prop);
              dest[camel] = val;
          };
          return this.css(dest);
      };
      if(style = dom.currentStyle){
          for(var prop in style){
              dest[prop] = style[prop];
          };
          return this.css(dest);
     };
     if(style = dom.style){
        for(var prop in style){
          if(typeof style[prop] != 'function'){
            dest[prop] = style[prop];
          };
        };
      };
      return this.css(dest);
    };


    //get JS working directory (relative to installation directory)
    function dirname(path) {
      if (!path) return false;
      return path.replace(/\\/g,'/').replace(/\/[^\/]*$/, '');;
    }
    function basename(path) {
      if (!path) return false;
      return path.replace(/\\/g,'/').replace( /.*\//, '' );
    }
    function setRelDir(cwd) {
      if (!cwd) return "";
      if (cwd == "download" || cwd == "filesgroup" || cwd == "ucp" || cwd == "gal") return "../";
      return "";
    }
    var relDir = setRelDir(basename(dirname(document.location.pathname)));

    //get human readable filesizes
    function fsize(size) {
      size = parseInt(size);
      kb = 1024;
      mb = kb*1024;
      gb = mb*1024;
      tb = gb*1024;
      pb = tb*1024;
      if (size < kb) return size + " B";
      else if (size < mb) return Math.round(100*size/kb)/100 + " KB";
      else if (size < gb) return Math.round(100*size/mb)/100 + " MB";
      else if (size < tb) return Math.round(100*size/gb)/100 + " GB";
      else if (size < pb) return Math.round(100*size/tb)/100 + " TB";
      else return Math.round(100*size/pb)/100 + " PB";
    }

    //add leading zero if needed
    function addZero(int) {
      if (int < 10) return "0"+int;
      else return int;
    }

    //seconds to minutes and hours if needed
    function secConv(sec) {
      if (sec < 60) return "00:" + addZero(sec);
      else if (sec < 3600) {
        return addZero(parseInt(sec/60)) + ":" + addZero(parseInt(sec%60));
      } else {
        return parseInt(sec/3600) + ":" + addZero(parseInt((sec%3600)/60)) + ":" + addZero(parseInt(sec%60));
      }
    }

    //for responsive purpose
    var smallDevice = false;
    if ($(document).width() < 768) {
      smallDevice = true;
    }

    //randomizer (phpjs)
    function mt_rand(min, max) {
      var argc = arguments.length;
      if (argc === 0) {
        min = 0;
        max = 2147483647;
      } else if (argc === 1) {
        throw new Error('Warning: mt_rand() expects exactly 2 parameters, 1 given');
      } else {
        min = parseInt(min, 10);
        max = parseInt(max, 10);
      }
      return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    //open link in external window without the usage of target='_blank'
    $("a.open-link-external").click(function() {
      window.open($(this).attr("href"));
      return false;
    });



   /******
   * 1. Some helperlies and presets [-]
   ******/ 

  /******
   * 2. Get Variables-Values-Parser [+]
   ******/
  function getGetVars(url,varName){
    var urlA = url.split("?"); 
    var vars = urlA[1].split("&"); 
    for (var i=0;i<vars.length;i++) {   
          var pair = vars[i].split("=");
          //remove anchor
          if(pair[0] == varName) {
            return pair[1].replace(/#.*$/,'');
          }
     }
  }
  /******
   * 2. Get Variables-Values-Parser [-]
   ******/

  
  /******
   * 3. some essential PHP-Values into JS Variables [+]
   ******/
  var jsVars
  $.ajax({
    url: relDir + "js.vars.php",
    type: 'post',
    data:{"return":"json"},
    dataType: 'json',
    async: false,
    success: function(data) {
        jsVars = data;
    }
  });

 /******
  * 3. some essential PHP-Values into JS Variables [-]
  ******/


  /******
   * 4. Contact form [+]
   ******/
  $("form#contactf").submit(function() {
    $("#cnote").slideUp();
    var str = $(this).serialize();
    $.ajax({
      type: "POST",
      url: "functions.ajax.php",
      data: str,
      success: function(msg){ 
        if(msg == 'OK') {
          $("#cnote").html('<div class="alert alert-success">' + jsVars.lang_success_mess_sent + '<\/div>');
          $("#cnote").slideDown();
          $("form#contactf").fadeOut();
        } else {
          $("#cnote").html(msg);
          $("#cnote").slideDown();
        }
      }
    });
    return false;
  });
  /******
   * 4. Contact form [-]
   ******/


  /******
   * 5. File uploader [+]
   ******/

  if ($("#fileupload").length) {

    if (jsVars.isMSIE && jsVars.MSIE_version < 9) {
      $(".fileinput-button input").fadeTo(0,1);
      $(".fileinput-button span").text("");
    }


    if (jsVars.maxRcpt > 1) {


      $("input.js-tagsinput").tagsinput({
        tagClass: function(item) {
          var tagClass = 'label label-primary text-white';
          $.ajax({
            type: "POST",
            url: "functions.ajax.php",
            dataType: 'json',
            async: false,
            data: {"action":"validateEmail","email":item},
            success: function(msgObj) {
              if (msgObj.isValid) {
                tagClass = 'bg-success label text-success';
              } else {
                tagClass = 'bg-danger label text-danger';
              }
            }
          });
          return tagClass;
        },
        confirmKeys: [13, 44, 32],
        maxTags: jsVars.maxRcpt
      });
      //style corrections
      if (!jsVars.isMSIE) {
        $('.bootstrap-tagsinput').copyCSS($("input[name='mailFrom']")).css({"min-height":$("input[name='mailFrom']").css("height"),"height":"auto"});
      }
      // $('.bootstrap-tagsinput input').focus(function() {
      //   $('.bootstrap-tagsinput').addClass("bootstrap-tagsinput-focus");
      // }).blur(function() {
      //   $('.bootstrap-tagsinput').removeClass("bootstrap-tagsinput-focus");
      // });

      $(".bootstrap-tagsinput input").on("keydown",function() {
        $(this).css("width",$(this).val().length +"em");
      });
    }

    //for the dropdowns [+]
    $.fn.followLink = function() {
      var URL = $(this).closest("div").parent().find("input").val();
      $(this).closest('.dropdown-menu').dropdown("toggle");
      window.open(URL);
      return false;
    }

    $.fn.generateQRCode = function() {
      var URL = $(this).closest("div").parent().find("input").val();
      bootbox.alert({title: jsVars.lang_hl_qr_code,message: '<p class="text-center"><img src="https://chart.googleapis.com/chart?chs=150&amp;cht=qr&amp;chl=' + encodeURI(URL) + '&amp;choe=UTF-8&amp;chld=|0" alt="' + URL + '" />'});
      $(this).closest('.dropdown-menu').dropdown("toggle");
      return false;
    }

    $.fn.shortenURL = function() {
      if ($(this).hasClass("js-URL-shortened")) {
        return false;
      }
      $(this).addClass("js-URL-shortened"); 
      var URL = $(this).closest("div").parent().find("input").val();
      var INPUT = $(this).closest("div").parent().find("input");
      var LINK = $(this);
      $.ajax({
        type: "POST",
        url: "functions.ajax.php",
        dataType: 'json',
        async: false,
        data: {"action":"shortenURL","url":URL},
        success: function(msgObj) {
          if (msgObj.error) {
            new PNotify({title: jsVars.lang_errors_occurred,text: msgObj.error, type: "error"});
          } else {
            $(INPUT).val(msgObj.shortURL);
            $(LINK).html("<s class='text-muted'>" + $(LINK).html() + "</s>");
          }
        }
      });
      $(this).closest('.dropdown-menu').dropdown("toggle");
      return false;
    }

    $.fn.ENcopy2Clipboard = function() {
      if (Clipboard.isSupported()) {
        
        var clipboard = new Clipboard("li.js-clipboard-holder a", 
          { 
            text: 
              function(trigger) {
                var URL = $(trigger).closest("div").parent().find("input").val();
                return URL;
              }
          }
        );

        clipboard.on('success', function(e) {
          new PNotify({title: "Copy success",text: 'successfully added <code>' + e.text + '</code> to your clipboard',type: "success",width:"auto"});
          e.clearSelection();
        });
        clipboard.on('error', function(e) {
          new PNotify({title: "Copy Issues",text: 'Please use <kbd>CTRL</kbd>+<kbd>C</kbd> to copy the URL to your clipboard',type: "info",width:"auto"});
        });
      } else {
        $("li.js-clipboard-holder").hide();
      }
    }

    $.fn.shareOnFacebook = function() {
      var URL = $(this).closest("div").parent().find("input").val();
      var sharingURL = 'https://www.facebook.com/sharer.php?u=' + URL;
      window.open(sharingURL, (jsVars.isMSIE && jsVars.MSIE_version < 9) ? '' : 'SFS-FB-Sharing', 'left=100,top=100,width=600,height=400,personalbar=0,toolbar=0,scrollbars=1,resizable=1');
      $(this).closest('.dropdown-menu').dropdown("toggle");
      return false;
    }

    $.fn.shareOnTwitter = function() {
      var URL = $(this).closest("div").parent().find("input").val();
      // var sharingURL = 'http://twitter.com/home?status=' + encodeURI("Check out my new upload on " + URL);
      var sharingURL = "https://twitter.com/intent/tweet?url=" + encodeURI(URL) + "&amp;text=Check out my new upload on " + $($.parseHTML(jsVars.siteName)).text();

      window.open(sharingURL, (jsVars.isMSIE && jsVars.MSIE_version < 9) ? '' : 'SFS-Twitter-Sharing', 'left=100,top=100,width=600,height=400,personalbar=0,toolbar=0,scrollbars=1,resizable=1');
      $(this).closest('.dropdown-menu').dropdown("toggle");
      return false;
    }

    //for the dropdowns [-]

    $(document).bind('drop dragover', function (e) {
        e.preventDefault();
    });
    $(function () {
      var DownLink; var DelLink; var abortCnt = 0; var initTime; var thisTime; var speed; var timeElapsed = 0; var filesUpped; var filesUppedTotal;
      $("#fileupload,.js-btn-remote-url").fileupload({
        formData: {u_key: $("input[name='u_key']").val()},
        add: function (e, data) {
            var extErrors = new Array(); var sizeErrors = new Array(); var extAllowedErrors = new Array(); 
            $.each(data.originalFiles, function (index, file) {
                //extensions check - for all
                var ext = file.name.split(".").pop().toLowerCase();
                if (jsVars.extDenied.length && ext && in_array(ext,jsVars.extDenied)) {
                  extErrors.push(file.name);
                }
                if (jsVars.extAllowed.length && ext && !in_array(ext,jsVars.extAllowed)) {    
                  extAllowedErrors.push(file.name);
                }
                //filesize check for all
                if (file.size > jsVars.maxFileSizeB) {
                 sizeErrors.push(file.name);
              }
            });
            if ($("#singleUploader").is(":hidden")) {
              //allowed to add files to current upload session
              if (jsVars.addAnotherFiles) {
                $("#singleUploadSucceeded,#multiUploadSucceeded").slideUp(function() {
                  $("#singleUploader").slideDown();
                  $(".js-btn-backto").fadeIn();
                  $(".cancelUpload,.progress,.speedIndicator").hide();
                  $(".fileinput-button,#uploadInfo").show();
                });
              } 
              //NOT allowed to add files to current upload session
              else {
                new PNotify({title: "Upload Error",text: jsVars.lang_error_continue_session, type: "error"});
                return false;                
              }
            }
            filesCount = data.originalFiles.length;
            if (!jsVars.multiUpload && filesCount > 1) {
              new PNotify({title: "File Count Error",text: jsVars.lang_error_just_one_file, type: "error"});
              if (filesUpped == 1) $(".js-btn-backto").fadeIn();
              return false;
            } else if (jsVars.multiUpload && filesCount > jsVars.maxMultiFiles) {
              new PNotify({title: "File Count Error",text: jsVars.lang_error_max_files, type: "error"});
              if (filesUpped == 1) $(".js-btn-backto").fadeIn();
              return false;
            } else if (sizeErrors.length) {
              if (filesCount > 1) new PNotify({title: "File Size Error",text: jsVars.lang_error_max_size_multi + sizeErrors.join(", "),type: "error"});
              else new PNotify({title: "File Size Error",text: jsVars.lang_error_max_size,type: "error"});
              if (filesUpped == 1) $(".js-btn-backto").fadeIn();
              return false;
            } else if (extErrors.length) {
              if (filesCount > 1) new PNotify({title: "File Extension Error",text: jsVars.lang_error_extension_denied_multi + extErrors.join(", "),type: "error"});
              else new PNotify({title: "File Extension Error",text: jsVars.lang_error_extension_denied,type: "error"});
              if (filesUpped == 1) $(".js-btn-backto").fadeIn();
              return false;
            } else if (extAllowedErrors.length) {                
              if (filesCount > 1) new PNotify({title: "File Extension Error",text: jsVars.lang_error_extension_denied_multi + extAllowedErrors.join(", "),type: "error"});
              else new PNotify({title: "File Extension Error",text: jsVars.lang_error_extension_denied,type: "error"});
              if (filesUpped == 1) $(".js-btn-backto").fadeIn();
              return false;
            } else {
              var uploader = data.submit();
            }
            $('button.cancelUpload').click(function (e) {
              abortCnt++;
              if (filesUpped == 1) {
                $(".js-btn-backto").fadeIn();
              }
              if(typeof uploader != 'undefined') {
                uploader.abort();
                if (!abortCnt) new PNotify({title: "Upload Aborted",type: "warning"});
                return false;
              }
          });
        },
        start: function (e) {
          filesUpped = parseInt($("input[name='filesUpped']").val());
          filesUppedTotal = parseInt($("input[name='filesUppedTotal']").val());
          initTime = new Date().getTime();
          $(".js-btn-backto").fadeOut();
          $(".fileinput-button").fadeOut(function() { $(".cancelUpload").fadeIn() } );
          $("#uploadInfo").slideUp(function() {$(".progress,.speedIndicator").fadeIn();});
        },
        dataType: "json",
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $(".progress .progress-bar").css("width",progress + "%");
            if (progress < 20) {
              $(".progress .progress-bar").not(".progress-bar-danger").addClass("progress-bar-danger");            
            } else if (progress < 40) {
              $(".progress .progress-bar").not(".progress-bar-warning").addClass("progress-bar-warning").removeClass("progress-bar-danger");            
            } else if (progress < 60) {
              $(".progress .progress-bar").not(".progress-bar-info").addClass("progress-bar-info").removeClass("progress-bar-warning");            
            } else if (progress < 80) {
              $(".progress .progress-bar").not(".progress-bar-primary").addClass("progress-bar-primary").removeClass("progress-bar-info");            
            } else {
              $(".progress .progress-bar").not(".progress-bar-success").addClass("progress-bar-success").removeClass("progress-bar-primary");
            }
            var thisTime = new Date().getTime();
            var timeElapsed = (thisTime- initTime) / 1000;
            var timeTotal = parseInt(data.total / (data.loaded / timeElapsed));
            var speed = fsize(data.loaded / timeElapsed) + "/s";
            if (progress < 99) {
              if ($("div.visible-xs").is(":visible")) {
                $(".progress .pct").html(fsize(data.loaded) + " / " + fsize(data.total));
              } else {
                $(".progress .pct").html(progress + "% <small>" + fsize(data.loaded) + " / " + fsize(data.total) + " </small>");
              }
            } else {
              //99 and 100% ... great jump - but okay :)
              $(".progress .pct").html(jsVars.lang_descr_finishing_upload);
              $(".progress .progress-bar").css("width","100%");
            }
            $(".speedIndicator span.upload-speed").text(speed);
            $(".speedIndicator span.upload-time").text(secConv(parseInt(timeElapsed)) + " / " + secConv(timeTotal));
        },
        stop: function () {
          if (!abortCnt) {
            $("#landingInfoRow").fadeOut();
            if (filesCount > 1 || filesUpped > 0) {
                $("#singleUploader").fadeOut(function(){$("#multiUploadSucceeded").fadeIn()});
            }
            $("input[name='filesUppedTotal']").val(filesUppedTotal);
            $("input[name='filesUpped']").val(1);
            $(document).ENcopy2Clipboard();
          } else {
            $(".progress,button.cancelUpload,.speedIndicator").fadeOut( function() { $(".fileinput-button").fadeIn(); $("#uploadInfo").slideDown()});
            abortCnt = 0;
          }
          // new PNotify({title: "Upload success",text: "all files upped",type: "success"});
        },
        done: function (e, data) { //each file done
          if (!abortCnt) {
            $.each(data.result.files, function (index, file) {
              if (file.error) {
                new PNotify({title: "Upload API error",text: file.error,type: "error"});
                // $(".cancelUpload").fadeOut(function() { $(".fileinput-button").fadeIn() } );
                // $(".progress,.speedIndicator").fadeOut(function() {$("#uploadInfo").slideDown();});
                $(".cancelUpload").trigger("click");
              } else {
                filesUppedTotal++;
                new PNotify({title: "Upload success",text: file.realName + " upped",type: "success"});
                var DownLink = jsVars.shortUrlbaseDownloadUrl + file.fileKey;
                var DelLink = jsVars.baseDeleteUrl + file.delFileKey + ".html";
                if (filesUpped == 0 && filesCount == 1) {
                  $("#singleUploadSucceeded .susFName .js-susDataHeadline").html(file.realName + ' <i class="small">' + file.niceSize + '</i>');
                  $("#singleUploadSucceeded input[name='susDownloadLink']").val(DownLink);
                  $("#singleUploadSucceeded input[name='susDeleteLink']").val(DelLink);                  
                  $("#singleUploadSucceeded button.btndown").click( function() { window.open(DownLink); return false; } );
                  $("#singleUploadSucceeded button.btndel").click( function() { window.open(DelLink); return false; } );
                  $("#singleUploadSucceeded .js-follow-link").click(function(){ return $(this).followLink(); });
                  $("#singleUploadSucceeded .js-generate-QR").click(function(){ return $(this).generateQRCode(); });
                  $("#singleUploadSucceeded .js-shorten-URL").click(function(){ return $(this).shortenURL(); });
                  $("#singleUploadSucceeded .js-share-facebook").click(function() { return $(this).shareOnFacebook(); });
                  $("#singleUploadSucceeded .js-share-twitter").click(function() { return $(this).shareOnTwitter(); });
                  if (smallDevice) $("#singleUploadSucceeded .input-group").removeClass("input-group-lg");
                  $("#singleUploader").fadeOut(function(){$("#singleUploadSucceeded").fadeIn()});
                }
                //for multiple files now on single uploads too - because of the possibility to add files to current upload sessions
                var multiItem = $("#multiUploadSucceeded .multiItem.js-hide").clone();
                $(multiItem).find(".musFName .js-musDataHeadline").html(file.realName + ' <i class="small">' + file.niceSize + '</i>');
                $(multiItem).removeClass("js-hide");
                $(multiItem).find("input[name='musDownloadLink']").val(DownLink);
                $(multiItem).find("input[name='musDeleteLink']").val(DelLink);
                $(multiItem).find("button.btndown").click( function() { window.open(DownLink); return false; } );
                $(multiItem).find("button.btndel").click( function() { window.open(DelLink); return false; } );
                $(multiItem).find("input[name='musDownloadLink']").click( function(){ $(this).select(); } );
                $(multiItem).find("input[name='musDeleteLink']").click( function(){ $(this).select(); } );
                $(multiItem).find(".js-follow-link").click(function(){ return $(this).followLink(); });
                $(multiItem).find(".js-generate-QR").click(function(){ return $(this).generateQRCode(); });
                $(multiItem).find(".js-shorten-URL").click(function(){ return $(this).shortenURL(); });
                $(multiItem).find(".js-share-facebook").click(function() { return $(this).shareOnFacebook(); });
                $(multiItem).find(".js-share-twitter").click(function() { return $(this).shareOnTwitter(); });
                if (!$("#multiUploadSucceeded .multiItem.multiItemFirst").length) {
                  $(multiItem).addClass("multiItemFirst");
                }
                $("#multiUploadSucceeded .multiItems").append(multiItem);
                if (smallDevice) $("#multiUploadSucceeded .input-group").removeClass("input-group-lg");
              }
            });
          }
        }
      })
      .on('fileuploaddrop', function (e, data) {
        if (jsVars.addAnotherFiles == 0 && filesUppedTotal > 0) {
          new PNotify({title: "Upload Error",text: jsVars.lang_error_continue_session, type: "error"});
          return false;                
        }
        if (jsVars.multiUpload == 0 && data.files.length > 1) {
          new PNotify({title: "File Count Error",text: jsVars.lang_error_just_one_file, type: "error"});
          return false;
        }
        if (jsVars.multiUpload == 1 && data.files.length > jsVars.maxMultiFiles) {
          new PNotify({title: "File Count Error",text: jsVars.lang_error_max_files, type: "error"});
          return false;
        }
      });
      $("#singleUploadSucceeded input[name='susDownloadLink'],#singleUploadSucceeded input[name='susDeleteLink'],#multiUploadSucceeded input[name='musGroupLink']").click( function(){ $(this).select(); } );
      $("#multiUploadSucceeded button.btngrp").click( function() { window.open($("#multiUploadSucceeded input[name='musGroupLink']").val()); return false; } );
      $("#multiUploadSucceeded .js-follow-link").click(function(){ return $(this).followLink() });
      $("#multiUploadSucceeded .js-generate-QR").click(function(){ return $(this).generateQRCode() });
      $("#multiUploadSucceeded .js-shorten-URL").click(function(){ return $(this).shortenURL() });
      $("#multiUploadSucceeded .js-share-facebook").click(function() { return $(this).shareOnFacebook(); });
      $("#multiUploadSucceeded .js-share-twitter").click(function() { return $(this).shareOnTwitter(); });
      $("#multiUploadSucceeded .js-shorten-URL-all").click(function() {
        $("#multiUploadSucceeded .js-shorten-URL").each(function() {
          $(this).trigger("click");
        })
        return false;
      });


      $("#agree2terms").change(function() {
        if ($(this).is(":checked")) {
          $(".sendLinkInfo").attr("disabled",false);
        } else {
          $(".sendLinkInfo").attr("disabled",true);
        }
     });
      $("#agree2termsMulti").change(function() {
        if ($(this).is(":checked")) {
          $(".sendLinkInfoMulti").attr("disabled",false);
        } else {
          $(".sendLinkInfoMulti").attr("disabled",true);
        }
     });
     $("a[href='#terms']").click(function() { 
      bootbox.dialog({"message":$("#terms").html(),
        buttons: {"cancel": { className: "btn-default", label: "OK", callback: function() { bootbox.hideAll(); } }
      }});
      return false;
     });


      /****** increase sharing form for MULTI and SINGLE uploads ******/
      $(".addmessage").on("click", function() {
        $(this).fadeOut("fast",
          function() {
            $(this).closest(".row").find(".js-message-wrapper").fadeIn();
          });
        return false;
      });


      /****** SINGLE Send Linkinfos to sender and receipient ******/
      $(".sendLinkInfo").on("click",function () {

        var thisSendBtn = $(this);

        $("#singleUploadSucceeded .sendLinkMsgs").hide();

        var DownLink = $("#singleUploadSucceeded input[name='susDownloadLink']").val();
        var DelLink = $("#singleUploadSucceeded input[name='susDeleteLink']").val();
        var mailFrom = $("#singleUploadSucceeded input[name='mailFrom']").val();
        var mailTo = $("#singleUploadSucceeded input[name='mailTo']").val();
        var message = $("#singleUploadSucceeded textarea[name='message']").val();
        var u_key = $("input[name='u_key']").val();
        var show_message = $("#singleUploadSucceeded input[name='show_message']").is(":checked")?1:0;

        if (mailTo && mailFrom) {

          $(thisSendBtn).attr("disabled",true);
          $(thisSendBtn).find("i").removeClass("fa-send").addClass("fa-circle-o-notch fa-spin");

          $.ajax({
           type: "POST",
           url: "functions.ajax.php",
           data: {"action":"sendFileInfo","DownLink":DownLink,"DelLink":DelLink,"mailFrom":mailFrom,"mailTo":mailTo,"message":message,"show_message":show_message,"u_key":u_key},
           success: function(msg){

            $(thisSendBtn).attr("disabled",false);
            $(thisSendBtn).find("i").addClass("fa-send").removeClass("fa-circle-o-notch fa-spin");

            if(msg == "OK") {
              $("#sendLinkInfoForm").append("<div class='alert alert-success js-hide span8 mt20 successmess'><button type='button' class='close'>&times;</button>" + jsVars.lang_success_info_sent + "</div>");
              $("#sendLinkInfoForm form").fadeOut( function() {
                $("#sendLinkInfoForm .successmess").fadeIn();
              });
              $("#singleUploadSucceeded .close").on("click", function() {
                $("#singleUploadSucceeded input[name='mailFrom'],#singleUploadSucceeded input[name='mailTo'],#singleUploadSucceeded textarea[name='message']").val("");
                $("#singleUploadSucceeded .js-message-wrapper").fadeOut();
                $("#singleUploadSucceeded #agree2terms, #singleUploadSucceeded input[name='show_message']").attr("checked",false);
                $("#singleUploadSucceeded .sendLinkInfo").attr("disabled",true);
                $(this).parent().fadeOut();
                $("#singleUploadSucceeded .addmessage").fadeIn();
                $("#sendLinkInfoForm form").fadeIn();
                $(this).parent().remove();
              });
              if (jsVars.maxRcpt > 1) {
                $("input.js-tagsinput").tagsinput('removeAll');
              }
            } else {
              $("#singleUploadSucceeded .sendLinkMsgs").text(msg);
              $("#singleUploadSucceeded .sendLinkMsgs").fadeIn();
            }
          }
         });
        } else {
           $("#singleUploadSucceeded .sendLinkMsgs").text(jsVars.lang_error_both_fields_required);
           $("#singleUploadSucceeded .sendLinkMsgs").fadeIn();
        }
        return false;
      });

      /****** MULTI Send Linkinfos to sender and receipient ******/
      $(".sendLinkInfoMulti").on("click",function () {

        var thisSendBtn = $(this);

        $("#multiUploadSucceeded .sendLinkMsgs").hide();

        var mailFrom = $("#sendLinkInfoFormMulti input[name='mailFrom']").val();
        var mailTo = $("#sendLinkInfoFormMulti input[name='mailTo']").val();
        var message = $("#sendLinkInfoFormMulti textarea[name='message']").val();
        var u_key = $("#sendLinkInfoFormMulti input[name='multi_u_key']").val();
        var show_message = $("#sendLinkInfoFormMulti input[name='show_message']").is(":checked")?1:0;

        if (mailTo && mailFrom) {
          
          $(thisSendBtn).attr("disabled",true);
          $(thisSendBtn).find("i").removeClass("fa-send").addClass("fa-circle-o-notch fa-spin");

          $.ajax({
           type: "POST",
           url: "functions.ajax.php",
           data: {"action":"sendMultiFileInfo","u_key":u_key,"mailFrom":mailFrom,"mailTo":mailTo,"message":message,"show_message": show_message},
           success: function(msg){

            $(thisSendBtn).attr("disabled",false);
            $(thisSendBtn).find("i").addClass("fa-send").removeClass("fa-circle-o-notch fa-spin");

            if(msg == "OK") {
              $("#sendLinkInfoFormMulti").append("<div class='alert alert-success js-hide span8 mt20 successmess'><button type='button' class='close'>&times;</button>" + jsVars.lang_success_info_sent + "</div>");
              $("#sendLinkInfoFormMulti form").fadeOut( function() {
                $("#sendLinkInfoFormMulti .successmess").fadeIn();
              });
              $("#multiUploadSucceeded .close").on("click", function() {
                $("#multiUploadSucceeded input[name='mailFrom'],#multiUploadSucceeded input[name='mailTo'],#multiUploadSucceeded textarea[name='message']").val("");
                $("#multiUploadSucceeded .js-message-wrapper").fadeOut();
                $("#agree2termsMulti, #multiUploadSucceeded input[name='show_message']").attr("checked",false);
                $(".sendLinkInfoMulti").attr("disabled",true);
                $(this).parent().fadeOut();
                $("#multiUploadSucceeded .addmessage").fadeIn();
                $("#sendLinkInfoFormMulti form").fadeIn();
                $(this).parent().remove();
              });
              if (jsVars.maxRcpt > 1) {
                $("input.js-tagsinput").tagsinput('removeAll');
              }
            } else {
              $("#multiUploadSucceeded .sendLinkMsgs").text(msg);
              $("#multiUploadSucceeded .sendLinkMsgs").fadeIn();
            }
          }
         });
        } else {
           $("#multiUploadSucceeded .sendLinkMsgs").text(jsVars.lang_error_both_fields_required);
           $("#multiUploadSucceeded .sendLinkMsgs").fadeIn();
        }
        return false;
      });


    });

  }
  /******
   * 5. File uploader [-]
   ******/

  /******
   * 6. Admin-login Autofocus  [+]
   ******/
  if ($("#loginF").length) {
    if (!$("input[name='user']").val()) {
      $("input[name='user']").focus();
    } else {
      $("input[name='pass']").focus();
    }
  }
  /******
   * 6. Admin-login Autofocus  [-]
   ******/


  /******
   * 7. Admin: Datatables  [+]
   ******/
  if ($("table#filesDataTable").length) {
    /** paginginfo plugin **/
    $.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings ) {
      return {
        "iStart":         oSettings._iDisplayStart,
        "iEnd":           oSettings.fnDisplayEnd(),
        "iLength":        oSettings._iDisplayLength,
        "iTotal":         oSettings.fnRecordsTotal(),
        "iFilteredTotal": oSettings.fnRecordsDisplay(),
        "iPage":          oSettings._iDisplayLength === -1 ? 0 : Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
        "iTotalPages":    oSettings._iDisplayLength === -1 ? 0 : Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
      };
    };
    /****** the datatable ******/
    var toolsCellWidth = "135px";
    if ($("body#BS-cosmo,body#BS-journal,body#BS-readable,body#BS-superhero").length) toolsCellWidth = "145px";
    else if ($("body#BS-yeti").length) toolsCellWidth = "155px";

    var oTable = $('#filesDataTable').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "admin.files.data.php",
        "aaSorting": [[ 0, "desc" ]],
        "sDom": "<'row mb20'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
          "sProcessing": "<i class='fa fa-clock-o'></i> Loading Files Data"
        },
        "aoColumns": [
          { "sWidth": "50px"},
          {  },
          { "sWidth": "120px"},
          { "sWidth": "180px" },
          { "sWidth": "100px"},
          { "sWidth": toolsCellWidth, "sClass": "tac"},
        ],
        "aoColumnDefs": [
          { "bSearchable": false, "aTargets": [0,2] },
          { "bSortable": false, "aTargets": [0,5] } ],
        "fnDrawCallback": function() {

          //initially show file removal infos
          if ($(".js-btn-showhide-cleanup-info").data("show") == 1) {
            $("table#filesDataTable div.cleanup-info").show();
          }

          /** tooltips [+] **/
          $('#filesDataTable tr>td:first-child + td').each( function() {
            $(this).attr("title",$(this).find("span").html());
          });
          $('#filesDataTable tr>td:first-child + td').tooltip({container:"body",html:true});
          /** tooltips [-] **/

          $('.dataTables_filter input').attr("placeholder","minlength:3");
          /****** admin function:delete file ******/
          $(".delFile").click( function() {
            var indexOfRow = oTable.fnGetPosition( $(this).closest('tr').get(0) );
            var url = $(this).attr("href");
            var fid = getGetVars(url,"fid");
            var thisTR = $(this).closest('tr');

            $(thisTR).addClass("warning");

            function delFile(fid,indexOfRow) {
              $.ajax({
                 type: "POST",
                 url: "functions.ajax.php",
                 data: {"action":"delFile","fid":fid},
                 success: function(msg) {
                  if(msg == "OK") {
                    new PNotify({title: "Operations completed",text: "The file was removed successfully.", type: "success"});
                    var page = oTable.fnPagingInfo().iPage;
                    oTable.fnDeleteRow(indexOfRow, function(){oTable.fnPageChange(page);}, false);
                  } else {
                    new PNotify({title: jsVars.lang_errors_occurred,text: msg, type: "error"});
                  }
                }
              });
            }
            if ($("#TempbypassConfirming").is(":checked")) {
              delFile(fid,indexOfRow);
              return false;
            }
            bootbox.confirm("<h4>Are you sure to delete this file?</h4><p>This Action cannot be undone!<br /><div class='checkbox'><label class='checkbox'><input type='checkbox' name='bypassConfirming' id='bypassConfirming' value='1' />Don't ask again for this action for the next times.</label></div></p>", 
              function(stat) {
                if (stat) {
                  if ($("#bypassConfirming").is(":checked")) {
                    $("#TempbypassConfirming").prop("checked",true);
                  }
                  delFile(fid,indexOfRow);
                } else {
                  $(thisTR).removeClass("warning");            
                }
            });
            return false;
          });
          $(".js-btn-lockFile").click( function() {
            var indexOfRow = oTable.fnGetPosition( $(this).closest('tr').get(0) );
            var url = $(this).attr("href");
            var fid = getGetVars(url,"fid");
            var action = getGetVars(url,"action");
            var thisTR = $(this).closest('tr');

            var thisBtn = this;

            $(thisTR).addClass("warning");

            $.ajax({
              type: "POST",
              url: "functions.ajax.php",
              data: {"action":"handleFileLock","lockAction":action,"fid":fid},
              success: function(msg) {
                if(msg == "OK") {
                  new PNotify({title: "Operations completed",text: action == "lockFile" ? "The file was locked successfully." : "The file was unlocked successfully.", type: "success",before_close: function() { $(thisTR).removeClass("warning"); } });
                  if (action == "lockFile") {
                    $(thisBtn).addClass("btn-warning").removeClass("btn-default").attr({"href":url.replace("=lockFile","=unlockFile"),"title":"PROTECTED: click to enable autodeletion of this file"});
                    $(thisBtn).find("i").addClass("fa-unlock").removeClass("fa-lock");
                  } else if (action == "unlockFile") {
                    $(thisBtn).addClass("btn-default").removeClass("btn-warning").attr({"href":url.replace("=unlockFile","=lockFile"),"title":"NOT PROTECTED: click to protect from autodeleting this file"});
                    $(thisBtn).find("i").addClass("fa-lock").removeClass("fa-unlock");
                  }
                } else {
                  new PNotify({title: jsVars.lang_errors_occurred,text: msg, type: "error"});
                }
              }
            });
            return false;
          });
          //get QR Code for download page
          $(".js-adm-get-qrcode").click( function() {
            var URL = $(this).data("url");
            bootbox.alert({title: jsVars.lang_hl_qr_code,message: '<p class="text-center"><img src="https://chart.googleapis.com/chart?chs=150&amp;cht=qr&amp;chl=' + encodeURI(URL) + '&amp;choe=UTF-8&amp;chld=|0" alt="' + URL + '" />'});
            return false;
          });


        }
      });
      
      //show/hide file removal infos
      $(".js-btn-showhide-cleanup-info").click(function() {
        if ($(this).data("show") == 1) {
          $("table#filesDataTable div.cleanup-info").slideUp();
          $(this).data("show",0);
        } else {
          $("table#filesDataTable div.cleanup-info").slideDown();
          $(this).data("show",1)
        }
      });
    /****** searchform Class ******/
    // $.extend( $.fn.dataTableExt.oStdClasses, {
    //   "sWrapper": "dataTables_wrapper form-inline"
    // });
      $('div.dataTables_filter input,div.dataTables_wrapper select').addClass('form-control input-sm');
  }
  /******
   * 7. Admin: Datatables  [-]
   ******/


  /******
   * 8. Admin: Flot Charts [+]
   ******/
  if ($(".flot").length) {
    /****** fill jsvars with charts data ******/
    var chartsData;
    $.ajax({
      url: "admin.charts.data.php",
      type: 'get',
      dataType: 'json',
      async: false,
      success: function(data) {
        chartsData = data;
      }
    });
    /****** filetypes - PieDonut Chart ******/
    if (chartsData.pieData != null) {
      $.plot($('#filetypes .flot'), chartsData.pieData, {
        grid : { hoverable : false }, 
        series : { pie: { show: true, radius: 1, tilt: 0.5, innerRadius: 0.5, label: { show: true, background: { color: "#fff", opacity: 0.8 }, formatter: function(label) { return "<div class='flotdescr'>" + label + "</div>"; } } } },
        yaxis: {tickDecimals:0, max: 6214.8}, legend: {noColumns:4,margin:0,backgroundColor:'#efefef'}
      });
    }
   
    /****** Uploads and Sizes - Linegraph ******/
    if (chartsData.lineData != null) {
      $("#updownloads").css("padding","0 25px");
      $.plot($('#updownloads .flot'), chartsData.lineData, { 
            xaxis: {ticks: chartsData.lineXticks,tickDecimals:0 }
      });
    

      $(".flot-periodizer select").change(function() {
        var period = $(this).val();
        var unit = $(this).data("unit");
        if (period) {
          //redraw
          var chartsData;
          $.ajax({
            url: "admin.charts.data.php",
            data: {period:period, unit:unit},
            type: 'get',
            dataType: 'json',
            async: false,
            success: function(data) {
              chartsData = data;

              $.plot($('#updownloads .flot'), chartsData.lineData, { 
                xaxis: {ticks: chartsData.lineXticks,tickDecimals:0 }
              });
            }
          });
          $(".flot-periodizer select").not(this).val("");
        }

      });





    }





    /****** panel hiding of inactive ones, they have to be active for flot printing first ******/
    var cnt = 0;
    $(".SFSCharts div.panel-collapse").each( function() {
      if (cnt) {
        $(this).removeClass("in");  
      }
      cnt++;
    });
  }
  /******
   * 8. Admin: Flot Charts [-]
   ******/


  /******
   * 9. Wait X Seconds before download Countdown [+]
   ******/
   if ($(".btn-download #dlCD").length && jsVars.downloadSeconds > 0) {
    $(".btn-download").click(function() {
     if ($(this).hasClass("disabled")) return false;
    });
    var dwnBtn = $(".btn-download");
    var dwnBtnText = $(".btn-download .dwnin");
    $('#dlCD span').countDown({
        startFontSize: 8,
        endFontSize: 17.5,
        startNumber: jsVars.downloadSeconds,
        callBack: function(me) {
          $(dwnBtn).removeClass("disabled").addClass("btn-success").removeClass("btn-warning");
          $(dwnBtn).find("i").attr("class","fa fa-download fa-fw");
          $(dwnBtnText).text("Download");
        }
      });
   }
  /******
   * 9. Wait X Seconds before download Countdown [-]
   ******/


  /******
   * 10. SINGLE: Password protect download links [+]
   ******/
  $("input#passwordProtection").change(function() {
    $(".susPassword").removeClass("muted text-info text-danger").hide();
    var pwdProtection = $(this).is(":checked");
    var downloadLink = $("input[name='susDownloadLink']").val();
    $.ajax({
       type: "POST",
       url: "functions.ajax.php",
       dataType: 'json',
       async: false,
       data: {"action":"pwdProtection","downloadLink":downloadLink,"protection":pwdProtection},
       success: function(msgObj) {
        if (msgObj.error) {
          $(".susPassword").text(msgObj.error);
          $(".susPassword").addClass("text-danger").fadeIn();
        } else {
          $(".susPassword").text(msgObj.statmess);
          if (msgObj.protection == 1) $(".susPassword").addClass("text-info").fadeIn();
          else $(".susPassword").addClass("muted").fadeIn();
        }
      }
     });
  });
  /******
   * 10. SINGLE: Password protect download links [-]
   ******/


  /******
   * 11. Verify password protected download links [+]
   ******/
  $(".btn-download.pwd-protected").click(function() {
    if ($(this).hasClass("disabled")) return false;
    var downloadLink = $(this).attr("href");
    bootbox.dialog({
      title: jsVars.lang_password_modal_hl,
      message: "<div class='form-inline'>"+
        "<div class='text-center pwdChecker'><div class='alert alert-danger js-hide'></div><input type='password' name='password' placeholder='" + jsVars.lang_password_modal_placeholder + "' required class='form-control input-lg'/></div></div>",
     buttons: {
        "cancel" : { label: jsVars.lang_cancel, className : "btn-default", callback: function() { bootbox.hideAll(); } },
        "verify" : { label: jsVars.lang_verify_pwd, className : "btn-primary submitData", callback: function() { 
            $(".pwdChecker .alert").hide();

            var proceed = false;
            var pwd = $(".modal-body input[name='password']").val();
            if (!pwd) {
              $('.pwdChecker .alert').text(jsVars.lang_error_enter_password).fadeIn();
            } else {
              $.ajax({
               type: "POST",
               url: relDir + "functions.ajax.php",
               dataType: 'json',
               async: false,
               data: {"action":"verifyPwd","downloadLink":downloadLink,"pwd":pwd},
               success: function(msgObj) {
                if (msgObj.error) {
                  $('.pwdChecker .alert').text(msgObj.error).fadeIn();
                } else {
                  if (msgObj.verified) {
                    window.location.href=downloadLink;
                    proceed = true;
                  } else {
                    $('.pwdChecker .alert').text("something went wrong?!?").fadeIn();
                  }
                }
              }
             });
            }
            return proceed;
          } 
        }
      }
     });
    return false;
  });
  /******
   * 11. Verify password protected download links [-]
   ******/


  /******
   * 12. Abuse form [+]
   ******/
  $("form#abusef").submit(function() {
    $("#cnote").slideUp();
    var str = $(this).serialize();
    $.ajax({
      type: "POST",
      url: "functions.ajax.php",
      data: str,
      success: function(msg){ 
        if(msg == 'OK') {
          $("#cnote").html('<div class="alert alert-success">' + jsVars.lang_success_mess_sent + '<\/div>');
          $("#cnote").slideDown();
          $("form#abusef").fadeOut();
        } else {
          $("#cnote").html(msg);
          $("#cnote").slideDown();
        }
      }
    });
    return false;
  });
  /******
   * 12. Abuse form [-]
   ******/


  /******
   * 13. MULTI: Password protect download links [+]
   ******/
  $("input#passwordProtectionMulti").change(function() {
    $(".musPassword").removeClass("muted text-danger text-info").hide();
    var pwdProtection = $(this).is(":checked");
    var u_key = $("input[name='u_key']").val();
    $.ajax({
       type: "POST",
       url: "functions.ajax.php",
       dataType: 'json',
       async: false,
       data: {"action":"pwdProtectionMulti","u_key":u_key,"protection":pwdProtection},
       success: function(msgObj) {
        if (msgObj.error) {
          $(".musPassword").text(msgObj.error);
          $(".musPassword").addClass("text-danger").fadeIn();
        } else {
          $(".musPassword").text(msgObj.statmess);
          if (msgObj.protection == 1) $(".musPassword").addClass("text-info").fadeIn();
          else $(".musPassword").addClass("muted").fadeIn();
        }
      }
     });
  });
  /******
   * 13. MULTI: Password protect download links [-]
   ******/


  /******
   * 14. Filesgroup - options [+]
   ******/
  $(".filesgroup input.gDownloadLink").click( function(){ $(this).select(); } );
  $(".filesgroup button.btndown").click( function() {
    var URL = $(this).closest("div").parent().find("input").val();
    $(this).closest('.dropdown-menu').dropdown("toggle");
    window.open(URL);
    return false;
  });
  /******
   * 14. Filesgroup - options [-]
   ******/


  /******
   * 15. don't leave site willy-nilly [+]
   ******/
  $(window).bind("beforeunload",function(event) {
     if ($("#singleUploadSucceeded,#multiUploadSucceeded").is(":visible")) return jsVars.lang_leaving_site_info;
  });
  /******
   * 15. don't leave site willy-nilly [-]
   ******/


  /******
   * 16. prevent of re-clicking download button [+]
   ******/
  $(".download-button-wrapper .btn-download").click(function() {
    if ($(this).hasClass("disabled")) return false;
    else {
      $(this).addClass("disabled btn-default").html(jsVars.lang_download_has_started).removeClass("btn-success").blur();
      if ($(this).hasClass("pwd-protected")) return true;
      if (jsVars.isMSIE) {
        window.location.href=$(this).attr("href"); 
        return false;
      } else {
        return true;
      }
    }
    return false
  });
  /******
   * 16. prevent of re-clicking download button [-]
   ******/


  /******
   * 17. SINGLE: autodelete after X days or downloads - set by uploader [+]
   ******/
  $(".delXdays,.delXdownloads").fadeTo(0,0.5);

  //days
  $("select[name='delXdays']").change(function() {
    var delXdays = $(this).val();
    var downloadLink = $("input[name='susDownloadLink']").val();
    $(".delXdays").fadeTo("fast",delXdays == -1 ? 0.5 : 1);
    $.ajax({
       type: "POST",
       url: "functions.ajax.php",
       dataType: 'json',
       async: false,
       data: {"action":"setDelXdays","downloadLink":downloadLink,"delXdays":delXdays},
       success: function(msgObj) {
        if (msgObj.error) {
          $(".delXdays").text(msgObj.error);
          $(".delXdays").addClass("text-danger").fadeIn();
        }
      }
     });
  });

  //downloads
  $("select[name='delXdownloads']").change(function() {
    var delXdownloads = $(this).val();
    var downloadLink = $("input[name='susDownloadLink']").val();
    $(".delXdownloads").fadeTo("fast",delXdownloads == -1 ? 0.5 : 1);
    $.ajax({
       type: "POST",
       url: "functions.ajax.php",
       dataType: 'json',
       async: false,
       data: {"action":"setDelXdownloads","downloadLink":downloadLink,"delXdownloads":delXdownloads},
       success: function(msgObj) {
        if (msgObj.error) {
          $(".delXdownloads").text(msgObj.error);
          $(".delXdownloads").addClass("text-danger").fadeIn();
        }
      }
     });
  });
  /******
   * 17. SINGLE: autodelete after X days or downloads - set by uploader [-]
   ******/


  /******
   * 18. MULTI: autodelete after X days or downloads - set by uploader [+]
   ******/
  $(".delXdaysMulti,.delXdownloadsMulti").fadeTo(0,0.5);

  //days
  $("select[name='delXdaysMulti']").change(function() {
    var delXdays = $(this).val();
    var u_key = $("input[name='u_key']").val();
    $(".delXdaysMulti").fadeTo("fast",delXdays == -1 ? 0.5 : 1);
    $.ajax({
       type: "POST",
       url: "functions.ajax.php",
       dataType: 'json',
       async: false,
       data: {"action":"setDelXdaysMulti","u_key":u_key,"delXdays":delXdays},
       success: function(msgObj) {
        if (msgObj.error) {
          $(".delXdaysMulti").text(msgObj.error);
          $(".delXdaysMulti").addClass("text-danger").fadeIn();
        }
      }
     });
  });

  //downloads
  $("select[name='delXdownloadsMulti']").change(function() {
    var delXdownloads = $(this).val();
    var u_key = $("input[name='u_key']").val();
    $(".delXdownloadsMulti").fadeTo("fast",delXdownloads == -1 ? 0.5 : 1);
    $.ajax({
       type: "POST",
       url: "functions.ajax.php",
       dataType: 'json',
       async: false,
       data: {"action":"setDelXdownloadsMulti","u_key":u_key,"delXdownloads":delXdownloads},
       success: function(msgObj) {
        if (msgObj.error) {
          $(".delXdownloadsMulti").text(msgObj.error);
          $(".delXdownloadsMulti").addClass("text-danger").fadeIn();
        }
      }
     });
  });  
  /******
   * 18. MULTI: autodelete after X days or downloads - set by uploader [-]
   ******/


 
  /******
   * 19. CAPTCHA: just to reload the image [+]
   ******/
   $(".btn-captcha-refresh").click(function() {
    if ($(".img-captcha").length) {
      var imgSrc = $(".img-captcha").attr("src").replace(/\?.*$/,'');
      $(".img-captcha").fadeTo(500,0,function() {
        $(".img-captcha").attr("src",imgSrc + "?" + new Date().getTime()).load(function() { 
          $(".img-captcha").fadeTo(500,1);
        });
      });
    }
   });

  /******
   * 19. CAPTCHA: just to reload the image [-]
   ******/


  /******
   * 20. optical correction for superlong filenames on download and deletion pages [+]
   ******/

  $.fn.reStyleDFNAME = function() {
    var w = $(".dfname").closest("div[class^=col-]").width()-40;
    var ext = $(".dfname").html().replace(/<small.*$/,'').split(".").pop().toLowerCase();
    $(".dfname").attr("style","text-overflow:'..."+ext+"';max-width:"+w+"px;");
  }
  if ($(".dfname").length) {
    $(".dfname").reStyleDFNAME();
    $(window).resize(function () { 
      $(".dfname").reStyleDFNAME();
    });
  }

  /******
   * 20. optical correction for superlong filenames on download and deletion pages  [-]
   ******/


  /******
   * 21. optical correction for superlong filenames on admin dashboard's "The Last File"-section [+]
   ******/

  if ($(".js-truncate-width").length) {
    $(window).on("load resize",function(e) {
      $(".filename-truncate strong").fadeOut(0, function() {
          $(".filename-truncate strong").width($(".js-truncate-width").width()).fadeIn();
        });
    });
  }

  /******
   * 21. optical correction for superlong filenames on admin dashboard's "The Last File"-section [-]
   ******/


  /******
   * 22. add files to current upload session [+]
   ******/
   $("#multiUploadSucceeded .js-btn-add-files").click(function(){
      $("#multiUploadSucceeded").fadeOut(function(){$("#singleUploader").fadeIn()});
      $(".cancelUpload").fadeOut(function() { $(".fileinput-button").fadeIn() } );
      $(".progress,.speedIndicator").fadeOut(function() {$("#uploadInfo").slideDown();});
      $("input[name='backto']").val("multi");
      $(".js-btn-backto").fadeIn();
      return false;
   });

   $("#singleUploadSucceeded .js-btn-add-files").click(function(){
      $("#singleUploadSucceeded").fadeOut(function(){$("#singleUploader").fadeIn()});
      $(".cancelUpload").fadeOut(function() { $(".fileinput-button").fadeIn() } );
      $(".progress,.speedIndicator").fadeOut(function() {$("#uploadInfo").slideDown();});
      $("input[name='backto']").val("single");
      $(".js-btn-backto").fadeIn();
      return false;
   });

  /******
   * 22. add files to current upload session [-]
   ******/


  /******
   * 23. back to the uploaded file(s) [+]
   ******/
  $(".js-btn-backto").click(function() {
    $("#singleUploader").fadeOut(
      function() {
        if ($("input[name='backto']").val() == "single") {
          $("#singleUploadSucceeded").fadeIn();
        } else {
          $("#multiUploadSucceeded").fadeIn();          
        }
      });
  });
  /******
   * 23. back to the uploaded file(s) [-]
   ******/


  /******
   * 24. SINGLE: handle file descriptions [+]
   ******/

  /****** show file description field /hide field/ remove file description ******/
  $("#singleUploadSucceeded .js-btn-add-file-description").on("click", function() {
    var thisBtn = this;
    /** show field **/
    if ($(this).hasClass("btn-primary")) {
      $("#singleUploadSucceeded .js-file-description-wrapper").slideDown(400,function() {
        $(thisBtn).find("span").text(jsVars.lang_remove_file_description);
        $(thisBtn).removeClass("btn-primary").addClass("btn-default");
        $("#singleUploadSucceeded input[name='susFileDescription']").focus();
      });
    } else
    /** remove description/hide field **/
    if ($(this).hasClass("btn-default")) {
      var cnt=0;
      $(".js-file-description-wrapper").slideUp(400,function() {
        $("#singleUploadSucceeded input[name='susFileDescription']").val("");
        $(".js-btn-save-file-description").addClass("disabled btn-default").removeClass("btn-primary");
        $(thisBtn).find("span").text(jsVars.lang_add_file_description);
        $(thisBtn).addClass("btn-primary").removeClass("btn-default");
        var downloadLink = $("input[name='susDownloadLink']").val();
        if (!cnt++) {
          $.ajax({
            type: "POST",
            url: "functions.ajax.php",
            dataType: 'json',
            async: false,
            data: {"action":"updateFileDescription","downloadLink":downloadLink,"fileDescription":""},
            success: function(msgObj) {
              if (msgObj.error) {
                new PNotify({title: jsVars.lang_errors_occurred,text: msgObj.error, type: "error"});
              } else {
                new PNotify({text: jsVars.lang_success_removed_file_description, type: "success"});
                //update for the first multi item [+]
                $("#multiUploadSucceeded .multiItem.multiItemFirst input[name='musFileDescription']").val("");
                $("#multiUploadSucceeded .multiItem.multiItemFirst .js-file-description-wrapper").addClass("js-hide");
                $("#multiUploadSucceeded .multiItem.multiItemFirst .js-btn-add-file-description").find("span").text(jsVars.lang_add_file_description);
                $("#multiUploadSucceeded .multiItem.multiItemFirst .js-btn-add-file-description").removeClass("btn-default").addClass("btn-primary");
                //update for the first multi item [-]
              }
            }
          });
        }
      });
    }
    return false;
  });

  /** field observation **/
  $("#singleUploadSucceeded input[name='susFileDescription']").on("input", function() {
    if ($("#singleUploadSucceeded .js-btn-save-file-description").hasClass("disabled")) {
      $("#singleUploadSucceeded .js-btn-save-file-description").removeClass("disabled btn-default").addClass("btn-primary");
    }
  });

  /** update file description on pressing enter triggering click event some lines below **/
  $("#singleUploadSucceeded input[name='susFileDescription']").on("keydown",function(e) {
    if (!$("#singleUploadSucceeded .js-btn-save-file-description").hasClass("disabled") && e.keyCode == 13) { //Enter Key
       $('#singleUploadSucceeded .js-btn-save-file-description').trigger('click');
    }
  });
  /** update file description itself **/
  $("#singleUploadSucceeded .js-btn-save-file-description").on("click", function() {
    var fileDescription = $("#singleUploadSucceeded input[name='susFileDescription']").val();
    var downloadLink = $("input[name='susDownloadLink']").val();
    $.ajax({
       type: "POST",
       url: "functions.ajax.php",
       dataType: 'json',
       async: false,
       data: {"action":"updateFileDescription","downloadLink":downloadLink,"fileDescription":fileDescription},
       success: function(msgObj) {
        if (msgObj.error) {
          new PNotify({title: jsVars.lang_errors_occurred,text: msgObj.error, type: "error"});
        } else {
          new PNotify({text: jsVars.lang_success_updated_file_description, type: "success"});
          //update for the first multi item [+]
          $("#multiUploadSucceeded .multiItem.multiItemFirst input[name='musFileDescription']").val(msgObj.descr_long);
          $("#multiUploadSucceeded .multiItem.multiItemFirst .js-file-description-wrapper").removeClass("js-hide");
          $("#multiUploadSucceeded .multiItem.multiItemFirst .js-btn-add-file-description").find("span").text(jsVars.lang_remove_file_description);
          $("#multiUploadSucceeded .multiItem.multiItemFirst .js-btn-add-file-description").removeClass("btn-primary").addClass("btn-default");
          //update for the first multi item [-]
          $("#singleUploadSucceeded .js-btn-save-file-description").removeClass("btn-primary").addClass("btn-success");
          $("#singleUploadSucceeded .js-btn-save-file-description i").removeClass("fa-save").addClass("fa-check");
          setTimeout(function() {
            $("#singleUploadSucceeded .js-btn-save-file-description").addClass("disabled btn-default").removeClass("btn-success");
            $("#singleUploadSucceeded .js-btn-save-file-description i").addClass("fa-save").removeClass("fa-check");
          }, 750);
        }
      }
     });
    return false;
  });

  /******
   * 24. SINGLE: handle file descriptions [-]
   ******/



  /******
   * 25. MULTI: handle file descriptions [+]
   ******/

  /****** show file description field /hide field/ remove file description ******/
  $("#multiUploadSucceeded").on("click",".js-btn-add-file-description", function() {
    var thisBtn = this;
    var thisMutliItem = $(this).closest(".multiItem");
    /** show field **/
    if ($(this).hasClass("btn-primary")) {
      $(thisMutliItem).find(".js-file-description-wrapper").slideDown(400,function() {
        $(thisBtn).find("span").text(jsVars.lang_remove_file_description);
        $(thisBtn).removeClass("btn-primary").addClass("btn-default");
        $(thisMutliItem).find("input[name='musFileDescription']").focus();
      });
    }
    /** remove description/hide field **/
    if ($(this).hasClass("btn-default")) {
      $(thisMutliItem).find(".js-file-description-wrapper").slideUp(400,function() {
        $(thisMutliItem).find("input[name='musFileDescription']").val("");
        $(thisMutliItem).find(".js-btn-save-file-description").addClass("disabled btn-default").removeClass("btn-primary");
        $(thisBtn).find("span").text(jsVars.lang_add_file_description);
        $(thisBtn).addClass("btn-primary").removeClass("btn-default");
        var downloadLink = $(thisMutliItem).find("input[name='musDownloadLink']").val();
        $.ajax({
          type: "POST",
          url: "functions.ajax.php",
          dataType: 'json',
          async: false,
          data: {"action":"updateFileDescription","downloadLink":downloadLink,"fileDescription":""},
          success: function(msgObj) {
            if (msgObj.error) {
              new PNotify({title: jsVars.lang_errors_occurred,text: msgObj.error, type: "error"});
            } else {
              new PNotify({text: jsVars.lang_success_removed_file_description, type: "success"});
            }
          }
        });
      });
    }
    return false;
  });

  /** field observation **/
  $("#multiUploadSucceeded").on("input", "input[name='musFileDescription']", function() {
    var saveButton = $(this).parent().find(".js-btn-save-file-description");
    if ($(saveButton).hasClass("disabled")) {
      $(saveButton).removeClass("disabled btn-default").addClass("btn-primary");
    }
  });

  /** update file description on pressing enter triggering click event some lines below **/
  $("#multiUploadSucceeded").on("keydown", "input[name='musFileDescription']", function(e) {
    var saveButton = $(this).parent().find(".js-btn-save-file-description");
    if (!$(saveButton).hasClass("disabled") && e.keyCode == 13) { //Enter Key
       $(saveButton).trigger('click');
    }
  });
  /** update file description itself **/
  $("#multiUploadSucceeded").on("click", ".js-btn-save-file-description", function() {
    var thisBtn = this;
    var thisMutliItem = $(this).closest(".multiItem");
    var fileDescription = $(thisMutliItem).find("input[name='musFileDescription']").val();
    var downloadLink = $(thisMutliItem).find("input[name='musDownloadLink']").val();
    $.ajax({
       type: "POST",
       url: "functions.ajax.php",
       dataType: 'json',
       async: false,
       data: {"action":"updateFileDescription","downloadLink":downloadLink,"fileDescription":fileDescription},
       success: function(msgObj) {
        if (msgObj.error) {
          new PNotify({title: jsVars.lang_errors_occurred,text: msgObj.error, type: "error"});
        } else {
          new PNotify({text: jsVars.lang_success_updated_file_description, type: "success"});
          $(thisBtn).removeClass("btn-primary").addClass("btn-success");
          $(thisBtn).find("i").removeClass("fa-save").addClass("fa-check");
          setTimeout(function() {
            $(thisBtn).addClass("disabled btn-default").removeClass("btn-success");
            $(thisBtn).find("i").addClass("fa-save").removeClass("fa-check");
          }, 750);
        }
      }
     });
    return false;
  });

  /******
   * 25. MULTI: handle file descriptions [-]
   ******/


  /******
   * 26. ADMIN: settings form(s) [+]
   ******/

  //panels
  if ($(".panel-sfs-settings").length) {
    // hide panel-bodies if there are more than one item
    if ($(".panel-sfs-settings").length > 1) {
      $(".panel-sfs-settings:not(:first) > .panel-body").hide();
      $(".panel-sfs-settings:not(:first) > .panel-title i[class*='chevron']").removeClass("fa-chevron-up").addClass("fa-chevron-down");
    }
    $(".panel-sfs-settings > .panel-heading").click(function() {
      var thisPanel = $(this).closest(".panel-sfs-settings");
      if ($(".panel-body",thisPanel).is(":visible")) {
        $(".panel-body",thisPanel).slideUp(400,function() {
          $(".panel-title i[class*='chevron']",thisPanel).removeClass("fa-chevron-up").addClass("fa-chevron-down");
        });
      } else {
        $(".panel-body",thisPanel).slideDown(400,function(){
          $(".panel-title i[class*='chevron']",thisPanel).removeClass("fa-chevron-down").addClass("fa-chevron-up");
        });
      }
    });
  }


   //Timezone Settings
   if ($("form#fConfigTimezone").length) {

      $("select.chosen").chosen({placeholder_text_single: "--- Please select ---",search_contains: true,width:"100%"});

      //timezone helper
      $(".js-btn-open-tz-helper").click(function() {
        var bbMessage;
        //get timezone details
        $.ajax({
          type: "POST",
          url: "functions.ajax.php",
          dataType: 'json',
          data: {action:"getTZhelper"},
          async: false,
          success: function(msgObj) {
            if (msgObj.tzData) {

              bbMessage = '<div class="col-xs-12">'+
                   '<div class="panel panel-info">'+
                     '<div class="panel-heading"><h3 class="panel-title">Webserver</h3></div>'+
                     '<div class="panel-body">'+
                       '<dl class="dl-horizontal">'+
                         '<dt>Datetime</dt><dd>' + msgObj.tzData['wsdate'] + '</dd>'+
                         '<dt>Timezone</dt><dd>' + msgObj.tzData['date_default_timezone_get'] + '</dd>'+
                       '</dl>'+
                     '</div>'+
                     '<div class="panel-heading"><h3 class="panel-title">Database Server</h3></div>'+
                     '<div class="panel-body">'+
                       '<dl class="dl-horizontal">'+
                         '<dt>Datetime</dt><dd>' + msgObj.tzData['dbdate'] + '</dd>'+
                         '<dt>Timezone</dt><dd>' + msgObj.tzData['dbtz'] + '</dd>'+
                       '</dl>'+
                     '</div>'+
                   '</div>'+
                  msgObj.tzHint +
               '</div>'+
               '<div class="clearfix"></div>';
            }
          }
        });
        if (!bbMessage) {
          bbMessage = '<div class="alert alert-danger">Something went wrong<br />Please consult the webservers error_log</div>';
        }
        bootbox.alert({
            title: "SFS Timezone Helper",
            message: bbMessage
        });
        return false;
      });

    //timezone changer
    $("select[name='timezone']").change(function() {
      $('.js-btn-save-timezone').fadeIn();
    });
    $('.js-btn-save-timezone').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var timezone = $("select[name='timezone']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_timezone",timezone:timezone},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          } else if(msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });
      
      return false;
    });


    //timezone correction
    $(".btn-group-select-timezone-correction ul a").click(function() {
      var thisBtnGroup = $(this).closest(".btn-group-select-timezone-correction");
      var fieldName = $(thisBtnGroup).attr("data-field-name");
      var fieldValue = $(this).closest("li").attr("data-value");

      $('ul li',thisBtnGroup).removeClass("active");
      $("button .selected-option",thisBtnGroup).html($(this).html());
      $(this).closest("li").addClass("active");

      $('ul',thisBtnGroup).dropdown('toggle');
      $("input[name='" + fieldName + "']").val(fieldValue);

      $('.js-btn-save-db_timezoneCorrection').fadeIn();

      return false;
    });

    $('.js-btn-save-db_timezoneCorrection').click(function() {
      var thisContainer = $(this).closest(".save-status-block");

      var db_timezoneCorrection_direction = $("input[name='db_timezoneCorrection_direction']").val();
      var db_timezoneCorrection_hours = $("input[name='db_timezoneCorrection_hours']").val();
      var db_timezoneCorrection_minutes = $("input[name='db_timezoneCorrection_minutes']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_db_timezoneCorrection",direction:db_timezoneCorrection_direction,hours:db_timezoneCorrection_hours,minutes:db_timezoneCorrection_minutes},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          } else if(msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });
      
      return false;

    });
   
   }  //form#fConfigTimezone




  //Download settings
  if ($("form#fConfigDownload").length) {
    //XSendfile Settings
    $(".js-btn-xsendfile").click(function() {
      var thisBtn = $(this);
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_xsendfile"},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});
            if (msgObj.XSendFile == 1) {
              $(thisBtn).addClass("btn-success").removeClass("btn-default");
              $("i",thisBtn).addClass("fa-check-square-o").removeClass("fa-square-o");
            } else {              
              $(thisBtn).addClass("btn-default").removeClass("btn-success");
              $("i",thisBtn).addClass("fa-square-o").removeClass("fa-check-square-o");
            }
          }
        }
      });
      
      return false;
    });

    //bandwidth changer
    $("input[name='kbps']").on("change keydown",function() {
      $('.js-btn-save-kbps').fadeIn();
    });
    $('.js-btn-save-kbps').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var kbps = $("input[name='kbps']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_kbps",kbps:kbps},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='kbps']").val(msgObj.kbps);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });

    //Expiration days
    $("input[name='delDays']").on("change keydown",function() {
      $('.js-btn-save-deldays').fadeIn();
    });
    $('.js-btn-save-deldays').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var delDays = $("input[name='delDays']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_deldays",delDays:delDays},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='delDays']").val(msgObj.delDays);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });

    //Deletion depending on
    $("select[name='delOn'].chosen").chosen({disable_search: true,width:"100%"});
    $("select[name='delOn']").change(function() {
      $('.js-btn-save-delon').fadeIn();
    });
    $('.js-btn-save-delon').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var delOn = $("select[name='delOn']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_delon",delOn:delOn},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });
      
      return false;
    });


    //download number list
    $("input[name='delDownloadsNumbers']").on("change keydown",function() {
      $('.js-btn-save-deldownloadsnumbers').fadeIn();
    });
    $('.js-btn-save-deldownloadsnumbers').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var delDownloadsNumbers = $("input[name='delDownloadsNumbers']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_deldownloadsnumbers",delDownloadsNumbers:delDownloadsNumbers},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='delDownloadsNumbers']").val(msgObj.delDownloadsNumbers);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          } else if (msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });      
      return false;
    });

    //user deletion settings Settings
    $(".js-btn-delsettingsbyuploader").click(function() {
      var thisBtn = $(this);
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_delsettingsbyuploader"},
        success: function(msgObj) {
          if(msgObj.success) {
            new PNotify({text: msgObj.success, type: "success",history: false});
            if (msgObj.delSettingsByUploader == 1) {
              $(thisBtn).addClass("btn-success").removeClass("btn-default");
              $("i",thisBtn).addClass("fa-check-square-o").removeClass("fa-square-o");
            } else {              
              $(thisBtn).addClass("btn-default").removeClass("btn-success");
              $("i",thisBtn).addClass("fa-square-o").removeClass("fa-check-square-o");
            }
          }
        }
      });
      
      return false;
    });

    //File Access Protection
    $("select[name='downloadProtection'].chosen").chosen({disable_search: true,width:"100%"});
    $("select[name='downloadProtection']").change(function() {
      $('.js-btn-save-downloadprotection').fadeIn();
    });
    $('.js-btn-save-downloadprotection').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var downloadProtection = $("select[name='downloadProtection']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_downloadprotection",downloadProtection:downloadProtection},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          } else if (msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });
      
      return false;
    });

    //enable/disable password protection
    $(".js-btn-passwordprotection").click(function() {
      var thisBtn = $(this);
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_passwordprotection"},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});
            if (msgObj.passwordProtection == 1) {
              $(thisBtn).addClass("btn-success").removeClass("btn-default");
              $("i",thisBtn).addClass("fa-check-square-o").removeClass("fa-square-o");
            } else {              
              $(thisBtn).addClass("btn-default").removeClass("btn-success");
              $("i",thisBtn).addClass("fa-square-o").removeClass("fa-check-square-o");
            }
          }
        }
      });
      
      return false;
    });

    //download waiting seconds
    $("input[name='downloadSeconds']").on("change keydown",function() {
      $('.js-btn-save-downloadseconds').fadeIn();
    });
    $('.js-btn-save-downloadseconds').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var downloadSeconds = $("input[name='downloadSeconds']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_downloadseconds",downloadSeconds:downloadSeconds},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='downloadSeconds']").val(msgObj.downloadSeconds);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });

  } //form#fConfigDownload

  //Upload settings
  if ($("form#fConfigUpload").length) {
    //max upload size changer
    $("input[name='maxFileSize']").on("change keydown",function() {
      $('.js-btn-save-maxfilesize').fadeIn();
    });
    $('.js-btn-save-maxfilesize').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var maxFileSize = $("input[name='maxFileSize']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_maxfilesize",maxFileSize:maxFileSize},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='maxFileSize']").val(msgObj.maxFileSize);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });

    //enable/disable multiple file uploads
    $(".js-btn-multiupload").click(function() {
      var thisBtn = $(this);
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_multiupload"},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});
            if (msgObj.multiUpload == 1) {
              $(thisBtn).addClass("btn-success").removeClass("btn-default");
              $("i",thisBtn).addClass("fa-check-square-o").removeClass("fa-square-o");
              $(".additional-multi-upload-settings").slideDown();
            } else {              
              $(thisBtn).addClass("btn-default").removeClass("btn-success");
              $("i",thisBtn).addClass("fa-square-o").removeClass("fa-check-square-o");
              $(".additional-multi-upload-settings").slideUp();
            }
          }
        }
      });
      
      return false;
    });

    //max multi files changer
    $("input[name='maxMultiFiles']").on("change keydown",function() {
      $('.js-btn-save-maxmultifiles').fadeIn();
    });
    $('.js-btn-save-maxmultifiles').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var maxMultiFiles = $("input[name='maxMultiFiles']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_maxmultifiles",maxMultiFiles:maxMultiFiles},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='maxMultiFiles']").val(msgObj.maxMultiFiles);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });


    //enable/disable adding of additional files
    $(".js-btn-addanotherfiles").click(function() {
      var thisBtn = $(this);
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_addanotherfiles"},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});
            if (msgObj.addAnotherFiles == 1) {
              $(thisBtn).addClass("btn-success").removeClass("btn-default");
              $("i",thisBtn).addClass("fa-check-square-o").removeClass("fa-square-o");
            } else {              
              $(thisBtn).addClass("btn-default").removeClass("btn-success");
              $("i",thisBtn).addClass("fa-square-o").removeClass("fa-check-square-o");
            }
          }
        }
      });
      
      return false;
    });

    //allowed extensions
    $("input[name='extAllowed']").on("change keydown",function() {
      $('.js-btn-save-extallowed').fadeIn();
    });
    $('.js-btn-save-extallowed').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var extAllowed = $("input[name='extAllowed']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_extallowed",extAllowed:extAllowed},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='extAllowed']").val(msgObj.extAllowed);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });

    //denied extensions
    $("input[name='extDenied']").on("change keydown",function() {
      $('.js-btn-save-extdenied').fadeIn();
    });
    $('.js-btn-save-extdenied').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var extDenied = $("input[name='extDenied']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_extdenied",extDenied:extDenied},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='extDenied']").val(msgObj.extDenied);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });


    //max recipients changer
    $("input[name='maxRcpt']").on("change keydown",function() {
      $('.js-btn-save-maxrcpt').fadeIn();
    });
    $('.js-btn-save-maxrcpt').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var maxRcpt = $("input[name='maxRcpt']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_maxrcpt",maxRcpt:maxRcpt},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='maxRcpt']").val(msgObj.maxRcpt);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });



    //enable/disable image previews
    $(".js-btn-imagepreview").click(function() {
      var thisBtn = $(this);
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_imagepreview"},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});
            if (msgObj.imagePreview == 1) {
              $(thisBtn).addClass("btn-success").removeClass("btn-default");
              $("i",thisBtn).addClass("fa-check-square-o").removeClass("fa-square-o");
              $(".additional-image-dimensions-settings").slideDown();
            } else {              
              $(thisBtn).addClass("btn-default").removeClass("btn-success");
              $("i",thisBtn).addClass("fa-square-o").removeClass("fa-check-square-o");
              $(".additional-image-dimensions-settings").slideUp();
            }
          }
        }
      });
      
      return false;
    });



    //image dimensions changer
    $("input[name='prevWidth'],input[name='prevHeight']").on("change keydown",function() {
      $('.js-btn-save-image-dimensions').fadeIn();
    });
    $('.js-btn-save-image-dimensions').click(function() {
      var thisContainer = $(this).closest(".save-status-block");
      var prevWidth = $("input[name='prevWidth']").val();
      var prevHeight = $("input[name='prevHeight']").val();

      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_imagedimensions",prevHeight:prevHeight,prevWidth:prevWidth},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='prevWidth']").val(msgObj.prevWidth);
                $("input[name='prevHeight']").val(msgObj.prevHeight);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });



    //enable/disable admin uploads
    $(".js-btn-adminonlyuploads").click(function() {
      var thisBtn = $(this);
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_adminonlyuploads"},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});
            if (msgObj.adminOnlyUploads == 1) {
              $(thisBtn).addClass("btn-success").removeClass("btn-default");
              $("i",thisBtn).addClass("fa-check-square-o").removeClass("fa-square-o");
            } else {              
              $(thisBtn).addClass("btn-default").removeClass("btn-success");
              $("i",thisBtn).addClass("fa-square-o").removeClass("fa-check-square-o");
            }
          }
        }
      });
      
      return false;
    });

  } //form#fConfigUpload


  //Short Urls Settings
  if ($("form#fConfigShortUrls").length) {
    //shortUrls system changer
    $("select[name='shortUrls']").on("change",function() {
      var shortUrls = $("select[name='shortUrls']").val();
      if (shortUrls == 0) {
        $('.disable-shortener .js-btn-save-shortener').fadeIn();
        $(".shortener-block").slideUp();
      } else {
        $('.disable-shortener .js-btn-save-shortener').fadeOut();
        $(".shortener-block:not([data-shortener='" + shortUrls + "'])").slideUp(400,function() {
          $(".shortener-block[data-shortener='" + shortUrls + "']").slideDown();
          $(".shortener-block[data-shortener='" + shortUrls + "'] .js-btn-save-shortener").fadeIn();
        });
      }
    });
    var shortUrls = $("select[name='shortUrls']").val();
    $(".shortener-block[data-shortener='" + shortUrls + "'] input,.shortener-block[data-shortener='" + shortUrls + "'] select").on("change keydown",function() {
      $(".shortener-block[data-shortener='" + shortUrls + "'] .js-btn-save-shortener").fadeIn();
    });

    $('.js-btn-save-shortener').click(function() {
      var shortUrls = $("select[name='shortUrls']").val();
      var thisBtn = $(this);
      var thisContainer = $(this).closest(".save-status-block");
      var bitlyUser = $("input[name='bitlyUser']").val();
      var bitlyKey = $("input[name='bitlyKey']").val();
      var adflyUid = $("input[name='adflyUid']").val();
      var adflyKey = $("input[name='adflyKey']").val();
      var adflyAdvertType = $("select[name='adflyAdvertType']").val();
      var connectionMethod = "curl";
      if (shortUrls == "bitly" || shortUrls == "adfly") {
        connectionMethod = $(".shortener-block[data-shortener='" + shortUrls + "'] select[name='connectionMethod']").val();
      }

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_shorturls", shortUrls:shortUrls, bitlyUser:bitlyUser, bitlyKey:bitlyKey, adflyUid:adflyUid, adflyKey:adflyKey, adflyAdvertType:adflyAdvertType, connectionMethod:connectionMethod},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });

  } //form#fConfigShortUrls


  // Mail Address Settings
  if ($("form#fConfigMail").length) {

    //admin mail changer
    $("input[name='admin_mail']").on("change keydown",function() {
      $('.js-btn-save-admin_mail').fadeIn();
    });
    $('.js-btn-save-admin_mail').click(function() {
      var thisBtn = $(this);
      var thisContainer = $(this).closest(".save-status-block");

      var admin_mail = $("input[name='admin_mail']").val();
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_admin_mail", admin_mail:admin_mail},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='admin_mail']").val(msgObj.admin_mail);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          } else if (msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });      
      return false;
    });

    //automailer address changer
    $("input[name='automaileraddr']").on("change keydown",function() {
      $('.js-btn-save-automaileraddr').fadeIn();
    });
    $('.js-btn-save-automaileraddr').click(function() {
      var thisBtn = $(this);
      var thisContainer = $(this).closest(".save-status-block");

      var automaileraddr = $("input[name='automaileraddr']").val();
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_automaileraddr", automaileraddr:automaileraddr},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='automaileraddr']").val(msgObj.automaileraddr);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          } else if (msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });      
      return false;
    });

    //contact target mail changer
    $("input[name='contact_mail']").on("change keydown",function() {
      $('.js-btn-save-contact_mail').fadeIn();
    });
    $('.js-btn-save-contact_mail').click(function() {
      var thisBtn = $(this);
      var thisContainer = $(this).closest(".save-status-block");

      var contact_mail = $("input[name='contact_mail']").val();
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_contact_mail", contact_mail:contact_mail},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='contact_mail']").val(msgObj.contact_mail);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          } else if (msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });      
      return false;
    });

    //mail params changer
    $("input[name='mailParams']").on("change keydown",function() {
      $('.js-btn-save-mailparams').fadeIn();
    });
    $('.js-btn-save-mailparams').click(function() {
      var thisBtn = $(this);
      var thisContainer = $(this).closest(".save-status-block");

      var mailParams = $("input[name='mailParams']").val();
      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"save_mailparams", mailParams:mailParams},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).fadeOut(400,function() {
              $(".text-success",thisContainer).fadeIn(400,function() {
                $("input[name='mailParams']").val(msgObj.mailParams);
                setTimeout(function(){
                  $(".text-success",thisContainer).fadeOut();
                }, 1500); // milliseconds
              });
            });
          }
        }
      });      
      return false;
    });

  } //form#fConfigMail


  /** Modules [+] **/
  if ($(".table-sfs-mods").length) {

    //install mod
    $(".js-btn-mod-install").click(function() {
      var thisRow = $(this).closest("tr");
      var modname = $(thisRow).data("modname");
      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"install_mod", modname:modname},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            $(thisBtn).closest("li").hide();
            $(".js-btn-mod-disable,.js-btn-mod-uninstall,.js-btn-mod-healthcheck",thisRow).closest("li").show();
            $(".modState .stat_inst",thisRow).text("installed (V" + msgObj.version.toFixed(2) + ")");
            $(".modState .stat_stat",thisRow).addClass("text-success").removeClass("text-danger").fadeIn();
            $(".modState .stat_stat span",thisRow).text("enabled");

            $(".list-group-modules-sidemen a.list-group-item[data-modname='" + modname + "']").removeClass("disabled");

            $("td.table-cell-modname-link",thisRow).wrapInner("<a href='" + $(thisRow).data("href") + "'></a>");

            if (msgObj.installationInstructions) {
              bootbox.dialog({
                title: "Additional Installation Instructions",
                message: msgObj.installationInstructions,
                className: "bb-800",
                buttons: {
                  "close" : { label: "OK", className : "btn-primary dont-focus" }
                } 
              });
            }

          } else if (msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });      

      $(thisBtn).closest('.dropdown-menu').dropdown("toggle");

      return false;
    });

    //uninstall mod
    $(".js-btn-mod-uninstall").click(function() {
      var thisRow = $(this).closest("tr");
      var modname = $(thisRow).data("modname");
      var thisBtn = $(this);

      bootbox.confirm(
        "<h4>Are you sure to uninstall this module?</h4>" +
          "<div class='alert alert-info'>This Action cannot be undone!</div>" +
          "When starting the uninstallation process the mod the corresponding tables and fields in the database and maybe some files will be removed or modified and last information to completely uninstall the mod will be displayed.",
        function(stat) {
          if (stat) {

            $.ajax({
             type: "POST",
              async: false,
              dataType: "json",
              url: "functions.ajax.php",
              data: {action:"uninstall_mod", modname:modname},
              success: function(msgObj) {
                if(msgObj.success) {

                  new PNotify({text: msgObj.success, type: "success",history: false});

                  $(thisBtn).closest("li").hide();
                  $(".js-btn-mod-disable,.js-btn-mod-enable,.js-btn-mod-healthcheck",thisRow).closest("li").hide();
                  $(".js-btn-mod-install",thisRow).closest("li").show();
                  $(".modState .stat_inst",thisRow).text("not installed");
                  $(".modState .stat_stat",thisRow).fadeOut();

                  $(".list-group-modules-sidemen a.list-group-item[data-modname='" + modname + "']").addClass("disabled");

                  $("td.table-cell-modname-link",thisRow).find("a").contents().unwrap();

                  if (msgObj.uninstallInstructions) {
                    bootbox.dialog({
                      title: "Additional Uninstall Instructions",
                      message: msgObj.uninstallInstructions,
                      className: "bb-800",
                      buttons: {
                        "close" : { label: "OK", className : "btn-primary dont-focus" }
                      } 
                    });
                  }

                } else if (msgObj.error) {
                  new PNotify({text: msgObj.error, type: "error",history: false});
                }
              }
            }); 
          }
      });
      $(thisBtn).closest('.dropdown-menu').dropdown("toggle");

      return false;
    });


    //enable/disable mod
    $(".js-btn-mod-disable,.js-btn-mod-enable").click(function() {
      var thisRow = $(this).closest("tr");
      var modname = $(thisRow).data("modname");
      var thisBtn = $(this);
      var status = $(thisBtn).data("status");

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"change_mod_status", modname:modname, status:status},
        success: function(msgObj) {
          if(msgObj.success) {

            new PNotify({text: msgObj.success, type: "success",history: false});

            if (status == 1) {
              $(".modState .stat_stat span",thisRow).text("enabled");
              $(".modState .stat_stat",thisRow).addClass("text-success").removeClass("text-danger");
              $(".js-btn-mod-disable",thisRow).closest("li").show();
              $(".js-btn-mod-enable",thisRow).closest("li").hide();
            } else {
              $(".modState .stat_stat span",thisRow).text("disabled");
              $(".modState .stat_stat",thisRow).addClass("text-danger").removeClass("text-success"); 
              $(".js-btn-mod-disable",thisRow).closest("li").hide();
              $(".js-btn-mod-enable",thisRow).closest("li").show();
            }

          } else if (msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });      

      $(thisBtn).closest('.dropdown-menu').dropdown("toggle");

      return false;
    });


    //remove mod
    $(".js-btn-mod-remove").click(function() {
      var thisRow = $(this).closest("tr");
      var modname = $(thisRow).data("modname");
      var thisBtn = $(this);

      bootbox.confirm(
        "<h4>Are you suer to remove this module?</h4>" +
          "<div class='alert alert-info'>This Action cannot be undone!</div>" +
          "When starting the removal process the mod the corresponding tables and fields in the database and maybe some files will be removed or modified and last information to completely remove the mod displayed.",
        function(stat) {
          if (stat) {

            $.ajax({
             type: "POST",
              async: false,
              dataType: "json",
              url: "functions.ajax.php",
              data: {action:"remove_mod", modname:modname},
              success: function(msgObj) {
                if(msgObj.success) {

                  new PNotify({text: msgObj.success, type: "success",history: false});

                  $(".js-btn-mod-disable,.js-btn-mod-enable,.js-btn-mod-healthcheck",thisRow).closest("li").hide();
                  $(".js-btn-mod-install",thisRow).closest("li").show();
                  $(".modState .stat_inst",thisRow).text("not installed");
                  $(".modState .stat_stat",thisRow).fadeOut();

                  $(".list-group-modules-sidemen a.list-group-item[data-modname='" + modname + "']").addClass("disabled");

                  $("td.table-cell-modname-link",thisRow).find("a").contents().unwrap();

                  if (msgObj.removalInstructions) {
                    bootbox.dialog({
                      title: "Additional Removal Instructions",
                      message: msgObj.removalInstructions,
                      className: "bb-800",
                      buttons: {
                        "close" : { label: "OK", className : "btn-primary dont-focus" }
                      } 
                    });
                  }

                } else if (msgObj.error) {
                  new PNotify({text: msgObj.error, type: "error",history: false});
                }
              }
            }); 
          }
      });
      $(thisBtn).closest('.dropdown-menu').dropdown("toggle");
      return false;
    });


    //mod healthcheck
    $(".js-btn-mod-healthcheck").click(function() {
      var thisRow = $(this).closest("tr");
      var modname = $(thisRow).data("modname");
      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"healthcheck_mod", modname:modname},
        success: function(msgObj) {
          if(msgObj.healthCheckResults) {
            if (msgObj.healthCheckResults) {
              bootbox.dialog({
                title: "Mod Health Check Results",
                className: "bb-800",
                message: msgObj.healthCheckResults,
                buttons: {
                  "close" : { label: "OK", className : "btn-primary dont-focus" }
                } 
              });
            }

          } else if (msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });      

      $(thisBtn).closest('.dropdown-menu').dropdown("toggle");

      return false;
    });

    //mod updatecheck
    // $(".js-btn-mod-updates-check").click(function() {
    //   var thisRow = $(this).closest("tr");
    //   var modname = $(thisRow).data("modname");
    //   var modUpdateServerBaseUrl = "http://cors.io/?http://sfs.envato.homac.at/SFSMods/";

    //   $.getJSON(modUpdateServerBaseUrl + modname + ".latest.json",function(a){
    //     alert(a.version);
    //   });
    //   return false;
    //   $.ajax({
    //    type: "GET",
    //     async: false,
    //     dataType: "jsonp",
    //     url: modUpdateServerBaseUrl + modname + ".latest.json",
    //     success: function(modLatest) {
            
    //         alert(modLatest.version);

    //       // } else if (msgObj.error) {
    //       //   new PNotify({text: msgObj.error, type: "error",history: false});
    //       // }
    //     }
    //   });      


    //   return false;
    // });


    //display mod manual
    $(".js-btn-mod-manual").click(function() {
      var thisRow = $(this).closest("tr");
      var modname = $(thisRow).data("modname");
      var thisBtn = $(this);

      $.ajax({
       type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"mod_manual", modname:modname},
        success: function(msgObj) {
          if(msgObj.modManual) {
            if (msgObj.modManual) {
              bootbox.dialog({
                className: "bb-800",
                title: "Mod Manual",
                message: msgObj.modManual,
                buttons: {
                  "close" : { label: "OK", className : "btn-primary dont-focus" }
                } 
              });
            }

          } else if (msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      });      

      $(thisBtn).closest('.dropdown-menu').dropdown("toggle");

      return false;
    });



  } //.table-sfs-mods

  //The Error Log Entries
  if ($(".list-group-error-log").length) {
    
    //show/hide error_log entries
    $("li[data-loggroup]").hide();
    $(".js-btn-toggle-logitems-view").click(function() {
      var logGroup = $(this).data("loggroup-target");
      var thisBtn = $(this);
      $("i",thisBtn).toggleClass("fa-chevron-down fa-chevron-up");
      $("li[data-loggroup='" + logGroup + "']").slideToggle(400,function() {
        if ($(this).is(":visible")) {
          $("span",thisBtn).text($(thisBtn).data("text-less"));
        } else {
          $("span",thisBtn).text($(thisBtn).data("text-more"));
        }
      });
    });

    
    //remove set of error_log entries
    $(".js-btn-remove-logitems").click(function() {
      var logGroup = $(this).data("loggroup-delkey");
      var thisListItem = $(this).closest("li.list-group-item");
      $.ajax({
        type: "POST",
        async: false,
        dataType: "json",
        url: "functions.ajax.php",
        data: {action:"removeLogEntries", logGroup:logGroup},
        success: function(msgObj) {
          if(msgObj.success) {
            $("li[data-loggroup='" + logGroup + "']").fadeOut(400,function() {
              $(this).remove();
            });
            $(thisListItem).fadeOut(400,function() {
              $(this).remove();
              if ($("ul.list-group-error-log li").length === 0) {
                $("ul.list-group-error-log").fadeOut();
                $(".alert-no-log-entries").fadeIn();
              }
            });
            new PNotify({text: msgObj.success, type: "success",history: false});
          } else if (msgObj.error) {
            new PNotify({text: msgObj.error, type: "error",history: false});
          }
        }
      }); 
    });

  } //.list-group-error-log




  /** Modules [-] **/


  /******
   * 26. ADMIN: settings form(s) [-]
   ******/

});