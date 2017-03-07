(function ($, Drupal, settings) {

  "use strict";
  	$('#name_element.folder p').click(function(){
  		var id_folder = $(this).attr("name");
  		
  		
  		$.ajax({
   			url: '/document_mgmt/get_data',
    		type: 'get',
    		dataType: 'json',
    		async: false,
    		data : {'id_folder' : id_folder},
    		success : function(data){
        		    $('#table_document').html(data);
    		}
		});


  	});
   
    Drupal.behaviors.document_modal_form_js = {
    attach: function (context, settings) {
      var dataT = "";

      /////////// + Show Menu For Folder + //////////////
  
      $(document.body).on('click', '#name_element.folder span.triangle', function(){
      var Url = $(this).attr("url");
      var Name = "Folder '"+$(this).attr("name")+"' menu."; 
        $.ajax({
                url: Url,
                type: 'get',
                dataType: 'json',
                async: false,
                data : {'id_folder' : 0},
                  success : function(data){
                    dataT = data;
                }
            });

        Show_Custom_Modal (dataT, Name);
      });

      /////////// - Show Menu For Folder - //////////////

     $(document.body).on('click', '#update-file .form-submit', function(e){
        e.preventDefault();
        var files = document.getElementById('edit-document-upload').files[0].name;
        console.log(files);

        
     });

      /////////// + Show Menu For Document + //////////////

      $(document.body).on('click', '#name_element.document span.triangle', function(){
      var Url = $(this).attr("url");
      var Name = "Document '"+$(this).attr("name")+"' menu."; 
        $.ajax({
                url: Url,
                type: 'get',
                dataType: 'json',
                async: false,
                data : {'id_folder' : 0},
                  success : function(data){
                    dataT = data;
                }
            });

        Show_Custom_Modal (dataT, Name);
      });

      /////////// - Show Menu For Document - //////////////


      $(document.body).on('click', '.document-action', function(){
        
        var Action = $(this).attr("id");
        var Name_File = $(this).attr("name-file");
        var Id_Document = $(this).attr("id-file");

        var Url = '/document_mgmt/getdatefordocument/'+Action+"/"+Id_Document+"/"+Name_File; 
        $.ajax({
                url: Url,
                type: 'get',
                dataType: 'html',
                async: false,
                  success : function(data){
                   
                   $("#ui-id-1").html(data);
                }
            });
      });






      /////////// + Function Show Modal + //////////////


      function Show_Custom_Modal (content, name){
        Drupal.dialog(content, {
        title: name,
        dialogClass: 'front-modal',
        width: 500,
        height: 400,
        autoResize: true,
        close: function (event) {
          $(event.target).remove();
        },
        buttons: [
          {
            text: 'Close the window',
            icons: {
              primary: 'ui-icon-close'
            },
            click: function () {
              $(this).dialog('close');
            }
          }
        ]
      }).showModal();
      }
      /////////// - Function Show Modal - //////////////
    }
  }



})(jQuery, Drupal, drupalSettings);


