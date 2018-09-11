$( function()
{
  $( "#exec_date" ).change( ExecDateChange );

  $('.alink').unbind('click').bind('click', alink_click );

  $( "#tagsByDSE" ).change( DSENameChange );  
  $( "#aim_select" ).change( AimSelectChange );

  $( "#MaterialKind" ).change( MaterialKindChange );
  $( "#MaterialSubKind" ).change( MaterialSubKindChange );  
  $( "#MaterialType" ).change( MaterialTypeChange );    
  
  $( "#count" ).bind( 'keyup', KeyUp );
  $( "#labor_times_for_item" ).bind( 'keyup', KeyUp );
  $( "#OtherMaterial" ).bind( 'blur', OtherMaterialBlur );

   $('[id ^= plan_price-]').unbind( 'keyup' ).bind( 'keyup', MainFormPriceCheck );
   $('[id ^= work_price-]').unbind( 'keyup' ).bind( 'keyup', MainFormPriceCheck );
   $('[id ^= fact_price-]').unbind( 'keyup' ).bind( 'keyup', MainFormPriceCheck );

// Dialog window adjust V
  
  $( "#dialog-confirm" ).dialog({
      resizable: false,
      autoOpen: false,
      height: "auto",
      width: 850,
      height: 'auto',
      modal: true,
      buttons: 
  [
    {
      id : 'OK',
      text: "Сохранить",
      icons: { primary: "ui-icon-check"},
      click: function() 
      {
        SaveOrder();
        $( this ).dialog( "close" );
      }
      ,
      disabled: true
    },
/*    
    {
      id : 'Check',
      text: "Проверить",
      icons: { primary: "ui-icon-cancel"},
      click: function() 
      {
        CheckComplete();
      },
      disabled: false    
    },    
*/    
    {
      id : 'Cancel',
      text: "Отмена",
      icons: { primary: "ui-icon-cancel"},
      click: function() 
      {
        $( this ).dialog( "close" );
      },
      disabled: false    
    }
  ] 
 });  

  $("#OK").button("disable");
  $( "button.close_but" ).click( close_but_click );

// Dialog window adjust A

  $('.close_but').button( { icon: "ui-icon-calculator", iconPosition: "beginning" } ).end();

// Autocomplete adjust V


// Отправляем запрос
  $.ajax({    
        url: '/project/orders/coop_orders/ajax.get_orders.php',
        type: 'POST',
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
              var tagsByOrderName = [];
              var tagsByOrderDSEName = [];
            
              for( var i = 0 ; i < respond.length ; i ++ )
                {
                  var dse_name = respond[ i ][ 'dse_name' ] + ' : ' + respond[ i ][ 'tid' ] + ' ' + respond[ i ][ 'name' ];
                  var name = respond[ i ][ 'tid' ] + ' - ' + respond[ i ][ 'name' ];
                  var id = respond[ i ][ 'id' ];
                
                  tagsByOrderDSEName[ i ] =  { label : dse_name, value : dse_name, name : name,         id : id };
                  tagsByOrderName[ i ]    =  { label : name,     value : name,     dse_name : dse_name, id : id };
                }
              
                                                        source: tagsByOrderDSEName,
              $( "#tagsByOrderDSEName" ).bind('keydown',function( e ){ CheckKeys( e, 'dse' ); CheckComplete();}).autocomplete({
                                                        source: tagsByOrderDSEName,
                                                        select : OrderDSENameChange
                                                      });
/*                                                      
              $( "#tagsByOrderName" ).bind('keydown',function( e ){ CheckKeys( e, 'dse' );}).autocomplete({ 
                                                        source: tagsByOrderName,
                                                        select : OrderNameChange
                                                    });
*/                                                    
              
              $("#MaterialKind option[value='-1']").attr("selected", "selected");                                                    
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
          },
          error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
    });

// Autocomplete adjust A

   
});

function AimSelectChange()
{
  var val = $('#aim_select').val();
  if( val == -1 )
    $('#aim_select').addClass('error');
     else
        $('#aim_select').removeClass('error');
  
  CheckComplete();
}


function ExecDateChange()
{
  var val = $('#exec_date').val();
  if( val )
    $('#exec_date').removeClass('error');
     else
      $('#exec_date').addClass('error');
  
  CheckComplete();
}

function ResetAll()
{
  $("#MaterialKind").addClass('error').attr( 'disabled', false );
  $("#MaterialSubKind").html("<option value='-1'>...</option>").addClass('error').attr( 'disabled', false );
  $("#MaterialType").html("<option value='-1'>...</option>").addClass('error').attr( 'disabled', false );
  $("#MaterialKind option[value='-1']").attr("selected", "selected").addClass('error');    
  $('#OtherMaterial').val( '' ).addClass('allowed');  
  
  $("#aim_select option[value='-1']").attr("selected", "selected").addClass('error');    
  
  $("#tagsByDSE").html("<option value='-1'>...</option>").addClass('error');;
  
  $('#count').val( 0 ).addClass('error');
  $('#exec_date').val('').addClass('error');;
 
  $("#ProcKind").html("<option value='-1'>...</option>");
  $("#ProcType").html("<option value='-1'>...</option>");
  
  $('#labor_times_for_item').val( 0 ).addClass('error');  
  $('#labor_times_for_group').val( 0 ).addClass('error');  
  $("#notes").val('');
  CheckComplete();  
}


function OrderDSENameChange( event, ui )
{
  $( "#tagsByOrderName" ).autocomplete().val( ui.item.name );
  $('#curindex').val( ui.item.id );
  ResetAll();
  MakeDSE( ui.item.id );
  $( "#tagsByOrderDSEName" ).removeClass('error');
//  $( "#tagsByOrderName" ).removeClass('error');  
  CheckComplete();
}

/*
function OrderNameChange( event, ui )
{
  $( "#tagsByOrderDSEName" ).autocomplete().val( ui.item.dse_name );
  $('#curindex').val( ui.item.id );
  ResetAll();  
  MakeDSE( ui.item.id );  
  $( "#tagsByOrderDSEName" ).removeClass('error');
//  $( "#tagsByOrderName" ).removeClass('error');  
}
*/

function close_but_click( event )
{
      event.preventDefault();
      var result = confirm( 'Закрыть заявку?');
 
      if( result )
 {
      var full_id_arr = $( this ).attr("id").split('-');
      var id = full_id_arr[1];

// Отправляем запрос
  $.ajax({    
        url: '/project/orders/coop_orders/ajax.state_update.php',
        type: 'POST',
        data : { 
                   "id" : id , 
                   "state" : '1' 
               },
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
//                alert( respond.result );
                $('tr.order_row-' + id ).remove();
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
          },
          error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
    });

 }      
}

function alink_click()
{
  $("#tagsByOrderDSEName").val('');
//  $("#tagsByOrderName").val('');

  $("#OK").button("disable");
  
  ResetAll();
  
  $( "#dialog-confirm" ).dialog('open');
}


function MakeDSE( id )
{

  $.ajax({    
        url : '/project/orders/coop_orders/ajax.get_dse.php',
        type : 'POST',
        data : { "id" : id },
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
              var tagsByDSEName = [];

              var option = "<option value='-1'>...</option>";

              for( var i = 0 ; i < respond.length ; i ++ )
                {
                  var dse_name = respond[ i ][ 'dse_name' ] ;                 
                  if( dse_name == '' )
                    continue;
                  var dse_draw = respond[ i ][ 'draw' ];
                  var full_name = dse_name + ' : ' + dse_draw ;
                  var id = respond[ i ][ 'id' ];
                  
                  option += "<option data-draw='" + dse_draw + "' data-name='" + dse_name + "' value='" + id + "'>" + full_name + "</option>";

                }

              $( "#tagsByDSE" ).empty().html( option );
              CheckComplete();
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
          },
          error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
        
    });
}


function MakeOperationKinds()
{
    var operations = ["...","Заготовка","Сборка-сварка","Механообработка","Сборка","Термообработка","Упаковка","Окраска","Прочее"];

    var options = "";
    for( var i = 0 ; i < operations.length; i ++ )
        options += "<option value='" + ( i == 0 ? -1 : i ) + "'>" + operations[ i ] + "</options>";

    $('#ProcKind').html( options ).unbind('change').bind( 'change', ProcKindChange );
    $('#ProcType').unbind('change').bind( 'change', ProcTypeChange );
}

function DSENameChange()
{
  var cur_dse = $( this ).val() ;
  $('#curdse').val( cur_dse );
 
  GetZnZag( cur_dse );
  $('#tagsByDSE').removeClass('error');
  
  $('#count').val( 0 ).addClass('error');      
  $('#labor_times_for_item').val( 0 ).addClass('error');      
  $('#labor_times_for_group').val( 0 ).addClass('error');    
  
  MakeOperationKinds();
  CheckComplete();
}


function ProcKindChange( )
{
  var op_index = $('#ProcKind').val() ;
  if( op_index != -1 )
  {
  $('#ProcKind').removeClass('error') ;
  
  $.ajax({    
        url : '/project/orders/coop_orders/ajax.get_operations.php',
        type : 'POST',
        data : { "id" : op_index },
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {

              var option = "<option value='-1'>...</option>";

              for( var i = 0 ; i < respond.length ; i ++ )
                {
                  var oper_name = respond[ i ][ 'oper_name' ] ;
                  var oper_id = respond[ i ][ 'id' ] ;                  
                  option += "<option value='" + oper_id + "'>" + oper_name + "</option>";
                }

              $( "#ProcType" ).html( option );
                                                      
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
          },
          error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
    });
  
  }
  else
    $('#ProcKind').addClass('error') ;

CheckComplete();
    
}

function GetZnZag( id )
{
  $.ajax({    
        url : '/project/orders/coop_orders/ajax.get_zn_zag.php',
        type : 'POST',
        data : { "id" : id },
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
              var id = respond['id'];
              var id_mat = respond['id_mat'];
              var id_mat_cat = respond['id_mat_cat'];
              var id_mat_cat_pid = respond['id_mat_cat_pid'];

              $('#zn_zag').val( id );

              MakeMaterialKind( id_mat_cat_pid );
              
              if( id_mat_cat )
                 MakeMaterialSubKind( id_mat_cat, id_mat_cat_pid );
                   else
                    $("#MaterialSubKind").html("<option value='-1'>...</option>").addClass('error');

//               $("#MaterialType").delay( 1 ); // ?????

              if( id_mat )
                 MakeMaterialType( id_mat, id_mat_cat );
                   else
                    $("#MaterialType").html("<option value='-1'>...</option>").addClass('error');
            
              CheckComplete();
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
          },
          error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
    });

CheckComplete();

}

function MakeMaterialKind( cur_id )
{
  $.ajax({    
        url : '/project/orders/coop_orders/ajax.get_mat_kind.php',
        type : 'POST',
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
              var option = "<option value='-1'>...</option>";

              for( var i = 0 ; i < respond.length ; i ++ )
                {
                  var mat_cat_name = respond[ i ][ 'mat_cat_name' ];
                  var id = respond[ i ][ 'id' ];
                  
                  option += "<option value='" + id + "'>" + mat_cat_name + "</option>";
                }

              $( "#MaterialKind" ).html( option );
              
              if( cur_id )
               {
                $("#MaterialKind option[value='" + cur_id + "']").attr("selected", "selected");
                $('#MaterialKind').removeClass('error');
               }
                  else
                  {
                    $("#MaterialKind option[value='-1']").attr("selected", "selected");
                    $("#MaterialKind").addClass('error');
                  }

              CheckComplete();              
              
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
          },
          error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
    });
    
  CheckComplete();
}

function MakeMaterialSubKind( id_mat_cat, id_mat_cat_pid )
{
  $.ajax({    
        url : '/project/orders/coop_orders/ajax.get_mat_sub_kind.php',
        type : 'POST',
        data : { "pid" : id_mat_cat_pid },
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
              var option = "<option value='-1'>...</option>";

              for( var i = 0 ; i < respond.length ; i ++ )
                {
                  var mat_cat_name = respond[ i ][ 'mat_cat_name' ];
                  var id = respond[ i ][ 'id' ];
                  
                  option += "<option value='" + id + "'>" + mat_cat_name + "</option>";
                }


              if( id_mat_cat == -1 )
                $( "#MaterialSubKind" ).addClass('error');
                  else
                    $( "#MaterialSubKind" ).removeClass('error');

              $( "#MaterialSubKind" ).html( option );
              $( "#MaterialSubKind option[value='" + id_mat_cat + "']").attr("selected", "selected");
              $( "#MaterialType" ).html( "<option value='-1'>...</option>" ).addClass('error');
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
            CheckComplete();            
          },
          error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
    });
  CheckComplete();    
}

function MakeMaterialType( id_mat, id_mat_cat )
{
  $.ajax({    
        url : '/project/orders/coop_orders/ajax.get_mat_type.php',
        type : 'POST',
        data : { "id_mat_cat" : id_mat_cat },
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
              var option = "<option value='-1'>...</option>";

              for( var i = 0 ; i < respond.length ; i ++ )
                {
                  var mat_name = respond[ i ][ 'mat_name' ];
                  var id = respond[ i ][ 'id' ];
                  
                  option += "<option value='" + id + "'>" + mat_name + "</option>";
                }

              if(  id_mat == -1 )
                $( "#MaterialType" ).html( option ).addClass('error');
                  else
                    $( "#MaterialType" ).html( option ).removeClass('error');
                    
              $( "#MaterialType option[value='" + id_mat + "']").attr("selected", "selected");
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
          },
          error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
    });
  
  CheckComplete();
}

function MaterialKindChange()
{
 var val = $("#MaterialKind :selected").val(); 

 if( val == -1 )
    $('#MaterialKind').addClass('error') ;
     else
      $('#MaterialKind').removeClass('error') ;

 MakeMaterialSubKind( -1, val )
 
 CheckComplete();
}

function MaterialSubKindChange()
{
 var val = $("#MaterialSubKind :selected").val(); 

 if( val == -1 )
    $('#MaterialSubKind').addClass('error') ;
     else
      $('#MaterialSubKind').removeClass('error') ;
 
 MakeMaterialType( -1, val )
 CheckComplete();
}


function KeyUp() 
{
var value = this.value;
if (/^\.|\d+\..*\.|[^\d\.{1}]/.test(value))
	        this.value = value.slice(0,-1);
  InputChange();
  
  CheckComplete();  
}

function InputChange()
{
  var count = $( "#count" ).val();
  
  if( count == 0 )
    $( "#count" ).addClass('error');
     else
      $( "#count" ).removeClass('error');
  
  var times = $( "#labor_times_for_item" ).val();

  if( times == 0 )
    $( "#labor_times_for_item" ).addClass('error');
     else
      $( "#labor_times_for_item" ).removeClass('error');
  
  var group_times = count * times ;

  if( group_times == 0 )
    $( "#labor_times_for_group" ).addClass('error');
     else
      $( "#labor_times_for_group" ).removeClass('error');

  $( "#labor_times_for_group" ).val( group_times );
}

function CheckComplete()
{
 //if( ! $( "#tagsByOrderName" ).hasClass( 'error' ) && ! $( "#tagsByOrderDSEName" ).hasClass( 'error' ) )
 
 
if( 
  $( "#tagsByOrderDSEName" ).hasClass( 'error' ) ||
  $( "#aim_select" ).hasClass( 'error' ) ||  
  
  $( "#tagsByDSE" ).hasClass( 'error' ) ||
  $( "#count" ).hasClass( 'error' ) ||
  $( "#ProcKind" ).hasClass( 'error' ) ||
  $( "#ProcType" ).hasClass( 'error' ) ||  
  
  $( "#exec_date" ).hasClass( 'error' ) ||  
  
  $( "#MaterialKind" ).hasClass( 'error' ) ||  
  $( "#MaterialSubKind" ).hasClass( 'error' ) ||  
  $( "#MaterialType" ).hasClass( 'error' ) ||
  $( "#labor_times_for_item" ).hasClass( 'error' )
  ) 
    {
      $("#OK").button("disable","disable");  
    }
      else
      {
        $("#OK").button("enable");
//        alert('Good! : ' + $( "#exec_date" ).hasClass('error') );        
      }
}

function CheckKeys( e , arg )
{
// alert( e.keyCode );
 
  if( e.keyCode > 40 || e.keyCode == 32 || e.keyCode == 8 )
  {
    if( arg == 'dse' )
    {
      $( "#tagsByOrderDSEName" ).attr('data-completed','0').addClass('error');
//      $( "#tagsByOrderName" ).attr('data-completed','0').addClass('error');  
    }
    else
      $( this ).attr('data-completed','0').addClass('error');  
  }
}

function ProcTypeChange( )
{
  var op_index = $('#ProcType').val() ;
  if( op_index == -1 )
    $('#ProcType').addClass('error') ;
     else
      $('#ProcType').removeClass('error') ;
  CheckComplete();      
}

function MaterialTypeChange( )
{
  var val = $('#MaterialType').val() ;
  if( val == -1 )
    $('#MaterialType').addClass('error') ;
     else
      $('#MaterialType').removeClass('error') ;
  CheckComplete();      
}

function OtherMaterialBlur()
{
  var val = $('#OtherMaterial').val();
  if( val.length )
  {
    $('#OtherMaterial').removeClass('allowed');
    $('#MaterialKind').removeClass('error').attr('disabled','disabled');
    $('#MaterialSubKind').removeClass('error').attr('disabled','disabled');
    $('#MaterialType').removeClass('error').attr('disabled','disabled');
  }
  else
  {
    $('#OtherMaterial').addClass('allowed');

    $('#MaterialKind').attr( 'disabled', false );
    if( $('#MaterialKind').val() == -1 ) 
      $('#MaterialKind').addClass('error');
       else
        $('#MaterialKind').removeClass('error');

    $('#MaterialSubKind').attr( 'disabled', false );
    if( $('#MaterialSubKind').val() == -1 ) 
      $('#MaterialSubKind').addClass('error');
       else
        $('#MaterialSubKind').removeClass('error');

    $('#MaterialType').attr( 'disabled', false );
    if( $('#MaterialType').val() == -1 ) 
      $('#MaterialType').addClass('error');
       else
        $('#MaterialType').removeClass('error');

    
  }

  CheckComplete();  
}

function SaveOrder()
{
  var order_name = $('#tagsByOrderName').val();
  var order_id = $('#curindex').val();
  var aim_select = $("#aim_select").val();

  var dse_name = $('#tagsByDSE option:selected').data('name');
  var dse_draw = $('#tagsByDSE option:selected').data('draw');
  
  var proc_kind_name = $('#ProcKind option:selected').text();
  var proc_type_name = $('#ProcType option:selected').text();  
  var exec_date = $('#exec_date').val();
  
  var material_type_name = $('#MaterialType option:selected').text();
  var other_material_name = $('#OtherMaterial').val();
  var count = $('#count').val();
  var labor_times_for_item = $('#labor_times_for_item').val();
  var notes = $('#notes').val();

 
/*  
  alert( 
          'Название заказа: ' +  order_name  + '\n' +
          'Название ДСЕ: ' + dse_name +        '\n' +
          
          'Вид обработки: ' + proc_kind_name +        '\n' +             
          'Тип обработки: ' + proc_type_name +        '\n' +          
          
          'Тип материала: ' + material_type_name +  '\n' +
          'Иной материал: ' + other_material_name +  '\n' +
          'Количество: ' + count +  '\n' +
          'Нормочасы на единицу: ' + labor_times_for_item +  '\n' +
          'Примечание: ' + notes +  '\n' + 
          'Чертеж: ' + dse_draw
       );
*/
$.post(
  '/project/orders/coop_orders/ajax.save_order.php',
  {
                  user_id               : user_id,
                  order_name            : order_name,
                  order_id              : order_id,                  
                  dse_name              : dse_name,                  
                  dse_draw              : dse_draw,
                  proc_kind_name        : proc_kind_name,
                  proc_type_name        : proc_type_name,
                  exec_date             : exec_date,
                  material_type_name    : material_type_name,
                  other_material_name   : other_material_name,
                  count                 : count ,
                  labor_times_for_item  : labor_times_for_item,
                  notes                 : notes,
                  aim_select            : aim_select
                  
  },
  insertOrderServerResponse
);    
}

// Jquery AJAX-ответ
function insertOrderServerResponse( data )
{
 //   alert( data );

    var old_body = $('#tbl tbody').html();
    $('#tbl tbody').html( data + old_body );
  
    $( "button.close_but" ).unbind('click').bind( 'click', but_click );
    $('button.close_but:first').button( { icon: "ui-icon-calculator", iconPosition: "beginning" } ).end();

}

function MainFormPriceCheck()
{
    var value = this.value;
    var full_id_arr = $( this ).attr("id").split('-');
    var id = full_id_arr[1];
  
    if ( /^\.|\d+\..*\.|[^\d\.{1}]/.test(value) || this.value == '00' )
      this.value = value.slice( 0, -1 );
      else
      {
          var labor_times_total = 1 * $('#labor_times_total-' + id ).html();
          var plan_price = 1 * $( '#plan_price-' + id ).val() ;

          var work_price =  ( labor_times_total * plan_price ).toFixed(2);
          $( '#work_price-' + id ).html( work_price ) ;
          
          var fact_price = 1 * $( '#fact_price-' + id ).val() ;
          
          var fact_price_without_nds = ( fact_price / 1.18 ).toFixed(2);
          $( '#fact_price_without_nds-' + id ).html( fact_price_without_nds ) ;
      
//          if( this.value == '' )
//            this.value = '0';

          // Effectivity calculation
          var eff = ( work_price - fact_price_without_nds ).toFixed(2);
          
          $( '#eff-' + id ).html( eff );

          
// Отправляем запрос
  $.ajax({    
        url: '/project/orders/coop_orders/ajax.price_update.php',
        type: 'POST',
        data : { 
                   "id" : id , 
                   "labor_times_total" : labor_times_total, 
                   "plan_price" : plan_price, 
                   "work_price" : work_price, 
                   "fact_price" : fact_price, 
                   "eff" : eff 
               },
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
//              alert( respond.result );
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
          },
          error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
    });


      }
}

