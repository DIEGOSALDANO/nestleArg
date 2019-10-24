
//Video en pestaña "Prioridades del mes"
    $('.video').magnificPopup({
      type: 'iframe',
      
      
      iframe: {
         markup: '<div class="mfp-iframe-scaler">'+
                    '<div class="mfp-close"></div>'+
                    '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
                    '<div class="mfp-title">Some caption</div>'+
                  '</div>'
      },
      callbacks: {
        markupParse: function(template, values, item) {
         values.title = item.el.attr('title');
        }
      }
      
      
    });

    // Aviso de deshabilitado o proximamente.

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })


    // Preview charge image Web

    if (window.FileReader) {
    
      var readerWeb = new FileReader(), rFilter = /^(image\/bmp|image\/cis-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x-cmu-raster|image\/x-cmx|image\/x-icon|image\/x-portable-anymap|image\/x-portable-bitmap|image\/x-portable-graymap|image\/x-portable-pixmap|image\/x-rgb|image\/x-xbitmap|image\/x-xpixmap|image\/x-xwindowdump)$/i; 
      
      readerWeb.onload = function (oFREvent) { 
        preview = document.getElementById("uploadWeb")
        preview.src = oFREvent.target.result;  
        preview.style.display = "block";
      };  
  
      function webTest() {
        
        if (document.getElementById("fileWeb").files.length === 0) { return; }  
        var file = document.getElementById("fileWeb").files[0];  
        if (!rFilter.test(file.type)) { alert("Formato de imagen no válido!"); return; }  
        readerWeb.readAsDataURL(file); 
      }
        
  } else {
    alert("FileReader object not found :( \nTry using Chrome, Firefox or WebKit");
  }

  
  // Preview charge image in your App

  if (window.FileReader) {
    
    var readerApp = new FileReader(), rFilter = /^(image\/bmp|image\/cis-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x-cmu-raster|image\/x-cmx|image\/x-icon|image\/x-portable-anymap|image\/x-portable-bitmap|image\/x-portable-graymap|image\/x-portable-pixmap|image\/x-rgb|image\/x-xbitmap|image\/x-xpixmap|image\/x-xwindowdump)$/i; 
    
    readerApp.onload = function (oFREvent) { 
      preview = document.getElementById("uploadApp")
      preview.src = oFREvent.target.result;  
      preview.style.display = "block";
    };  

    function appTest() {
      
      if (document.getElementById("fileApp").files.length === 0) { return; }  
      var file = document.getElementById("fileApp").files[0];  
      if (!rFilter.test(file.type)) { alert("You must select a valid image file!"); return; }  
      readerApp.readAsDataURL(file); 
    }
      
} else {
  alert("FileReader object not found :( \nTry using Chrome, Firefox or WebKit");
}


    
   
    