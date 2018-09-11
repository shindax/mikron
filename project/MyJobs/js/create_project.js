function checkNewProjectFill()
{
   var proj_name = $("#proj_name").val();

   var executor = $('#exec_list').val() ;
   var checker = $('#check_list').val() ;
   var from_date = $('#p2_Input').val();


   if( proj_name.length && executor && checker && from_date.length )
        $("#create_button").prop("disabled", false );
            else
                $("#create_button").prop("disabled", true );
}

function CreateProject()
{
/*    
   var proj_name  = $("#proj_name").val();
   var proj_descr = encodeURIComponent( $('#proj_descr').val() ) ;
   var date = $('[name="p1"]').val() ;
      
   var executor   = $('#exec_list').val() ;
   var checker    = $('#check_list').val() ;

   var str = location + '&p0=' + proj_name + '&p1=' + proj_descr + '&p2=' + date + '&p3=' + executor + '&p4=' + checker ;
 
   alert( str );
 
   document.location.href = str ;
*/
}

$( function()
{
    $( "#exec_list" ).combobox();
    $( "#check_list" ).combobox();
    $( ".custom-combobox-input" ).css('width','150px');
    $( "ul.ui-autocomplete" ).addClass('scroll');

  $('input#proj_name').click(checkNewProjectFill).keydown(checkNewProjectFill).keyup(checkNewProjectFill);
  $('input#proj_name').bind({cut :checkNewProjectFill, paste :checkNewProjectFill, copy :checkNewProjectFill});
  $( ".ui-widget" ).autocomplete({ change: checkNewProjectFill });
  

});