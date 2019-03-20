function cons( arg )
{
  console.log( arg )
}

const LEVEL_SHIFT = 20 ;
const COL_SM_12_PADDING = 15 ;
const COL_SM_12_MARGIN = 20 ;
const MAGIC_NUMBER = 50 ;

//var collapse = '/uses/svg/arrow-up.svg'
//var expand = '/uses/svg/arrow-down.svg';

//var collapse = '/uses/svg/collapse_sharp.svg'
//var expand = '/uses/svg/expand_sharp.svg';

var collapse = '/uses/svg/arrow-left-up.svg'
var expand = '/uses/svg/arrow-right-down.svg';
var empty = '/uses/svg/spinner-2.svg';

var tinyMCE = 0 ;

$( function()
{

// ********************************* Диалог с участниками *********************************	
	$( "#user_job_dialog" ).dialog({
      resizable: false,
      height: "auto",
      width: 500,
      height : 300,
      modal: true,
      autoOpen : false,
      buttons: 
      [
        {
        id : "apply",
        // Применить
        text: "\u041f\u0440\u0438\u043c\u0435\u043d\u0438\u0442\u044c",
        disabled : true,
        click : function() 
        {
        	 let id = 	$( "#user_job_dialog" ).data('id');
			     let list = $( '#user_select_to option' )
			     let member_arr = []

    			$.each( list , function( key, item )
        		{
    				  member_arr.push( $( item ).val() )
        		});

    			var member_list = member_arr.join(',')
    			$.post(
                          '/project/dss/ajax.update_members.php',
                          {
                              id  : id,
                              list : member_list
                          },
                          function( data )
                          {
                          	member_arr = []
                          	member_id_arr = []

                						$.each( data , function( key, item )
                			    		{
                			    			member_arr.push( item )
                			    			member_id_arr.push( key )

                			    		});

                						member_arr = member_arr.sort();
                						member_list = member_arr.join('\n');
                						member_id_list = member_id_arr.join(',');

                						var tr = $( 'tr[data-id=' + id + ']' ).find('.member_div');
                						$( tr ).attr('title',member_list)
                						$( tr ).data('member-list', member_id_list).attr('data-member-list', member_id_list)
                						$( tr ).find('.member_count').text( member_id_arr.length )
                						adjust_ui();
                          }
                          ,'json'
                    );			

              	$( this ).dialog( "close" );
          }
        },
        // закрыть
        {
          id : "close",
          text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
          click : function() 
          {
            $( this ).dialog( "close" );
          }
        }
      ],
      open : function()
      {
      },
      classes:
      {
      	"ui-dialog-titlebar" : "user_job_dialog_title"
      }
    });// .dialog({ classes : { "ui-dialog-titlebar" : "user_job_dialog_title" }});


// ********************************* Удаление записи *********************************
	$( "#delete_row_dialog" ).dialog({
      resizable: false,
      width: 500,
      height: "auto",
      modal: true,
      autoOpen : false,
      buttons: 
      {
      	// Добавить документ
      	"\u0423\u0434\u0430\u043b\u0438\u0442\u044c": function() 
        {
        	var tr = $( "#delete_row_dialog" ).data('tr' )
 			    var id = $( "#delete_row_dialog" ).data('id' ) 	
 			    var el = this 

    			$.post(
    			         '/project/dss/ajax.delete_row.php',
    			            {
    			                id  : id,
    			            },
    			            function( data )
    			            {
    			              var parent_id = $( tr ).data('parent-id')
    			              var level = $( tr ).data('level')
    			              
                        $( tr ).remove();
    			              
                        var trs = $( 'tr[data-parent-id=' + parent_id + '][data-level=' + level + ']');
    			             
                        if( trs.length == 0 )
                            remove_arrow( parent_id )

                        renumberRows( parent_id );
    						        $( el ).dialog( "close" );
                        adjust_ui();
    			            }
    			      );

        },
        "\u0417\u0430\u043a\u0440\u044b\u0442\u044c": function() 
        {
          $( this ).dialog( "close" );
        }
      },
      classes:
      {
      	"ui-dialog-titlebar" : "delete_record"	
      }
    });

// ********************************* Работа с документами *********************************
	$( "#picture_job_dialog" ).dialog({
      resizable: false,
      height: "auto",
      width: 800,
      height : 300,
      modal: true,
      autoOpen : false,
      buttons: 
      {
      	// Добавить документ
      	"\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442": function() 
        {
        	$('#upload_file_input').click();
        },
      	// Применить
   //    	"\u041f\u0440\u0438\u043c\u0435\u043d\u0438\u0442\u044c": function() 
   //      {
   //      	var id = $( "#user_job_dialog" ).data('id');
			// var list = $( '#user_select_to option' )
   //        	$( this ).dialog( "close" );
   //      },
        "\u0417\u0430\u043a\u0440\u044b\u0442\u044c": function() 
        {
          $( this ).dialog( "close" );
        }
      },
      classes:
      {
      	"ui-dialog-titlebar" : "user_job_dialog_title"	
      }
    });

var height = $( '#vpdiv' ).height() * 9 / 10 ;
var width = $( '#vpdiv' ).width() * 9 / 10 ;

// ********************************* Обсуждения *********************************
$( "#discussions_job_dialog" ).dialog({
      resizable: false,
      height: "auto",
      width: 1600,
      height : height,
      modal: true,
      autoOpen : false,
      buttons: 
      [
       {
        // Предложить решение
        id : "theme_decide",
        text : "\u{41F}\u{440}\u{435}\u{434}\u{43B}\u{43E}\u{436}\u{438}\u{442}\u{44C} \u{440}\u{435}\u{448}\u{435}\u{43D}\u{438}\u{435}",
        disabled : true, 
        click : function() 
        {
          let id = $( this ).data('id')
          let member_list = $( "#discussions_job_dialog" ).data('member-list')

          $('.theme_decision_textarea').val('');
          $( '.theme_decision_theme').text( $('.discussion_selected') .find('span').eq(0).text() )
          $( '.theme_decision_author').text( $('.discussion_selected') .find('span').eq(1).text())

          $.post(
              "project/dss/ajax.get_member_options.php",
              {
                  list   : member_list,
                  res_id : res_id
              },
              function( data )
              {
                $('#confirm_select_from').html('').append( data )
                $('#confirm_select_to').empty();
                $('#discussions_dialog_theme_decide').data('id',id ).dialog( "open" );
              }
          );
        }
      },
      {
        id : "add_discussion",
        // Добавить обсуждение
        text : "\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c \u043e\u0431\u0441\u0443\u0436\u0434\u0435\u043d\u0438\u0435",
        disabled : true,
        click : function() 
        {
          var id = $( this ).data('id')
          $('.new_theme_input').val('');
          $('.new_theme_textarea').val('');
          $('#discussions_dialog_new_theme').dialog( "open" ).data('id',id);
        }
       },
       { 
        text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
        click : function() 
        {
          $( this ).dialog( "close" );
        }
      }
      ],
      classes:
      {
      	"ui-dialog-titlebar" : "user_job_dialog_title"	
      }
    });

// ********************************* Ответ *********************************
$( "#discussions_dialog_response" ).dialog({
      resizable: false,
      width: 600,
      height : 240,
      modal: true,
      autoOpen : false,
      buttons: 
      [
        {
          id : 'response_button',
          disabled : true,
          text : "\u041e\u0442\u0432\u0435\u0442\u0438\u0442\u044c",
          click : function() 
          {
            var id = $( this ).data( 'id' )
            var message =  $('.resp_textarea').val()

            $.post(
               '/project/dss/ajax.add_response.php',
                  {
                      id  : id,
                      message : message,
                      res_id : res_id
                  },
                  function( data )
                  {

                    $.post(
                             '/project/dss/ajax.get_discussions.php',
                                {
                                  id : data,
                                  res_id : res_id
                                },
                                function( data )
                                {
                                  $( '.discussions' ).html( data );
                                  adjust_ui();
                                }
                          );
                  }
            );


            $( this ).dialog( "close" );
          }
        },
        {
          text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
          click : function() 
          {
            $( this ).dialog( "close" );
          }
        }
      ],
      classes:
      {
      	"ui-dialog-titlebar" : "user_job_dialog_title"
      }
    });


// ********************************* Новая тема *********************************
$( "#discussions_dialog_new_theme" ).dialog({
      resizable: false,
      width: 600,
      height : 300,
      modal: true,
      autoOpen : false,
      buttons: 
      [
          {
        // Создать новую тему
        text : "\u0421\u043e\u0437\u0434\u0430\u0442\u044c",
        id : "create_new_theme",
        disabled : true, 
        click : function() 
          {
            var id = $( this ).data('id')
            var theme = $('.new_theme_input') .val();
            var message = $('.new_theme_textarea').val();

            $.post(
           '/project/dss/ajax.create_new_theme.php',
              {
                  id  : id,
                  res_id : res_id,
                  theme : theme,
                  message : message
              },
              function( data )
              {
              $.post(
                     '/project/dss/ajax.get_discussion_themes.php',
                        {
                          id : id,
                          res_id : res_id
                        },
                        function( data )
                        {
                          var total = 1 * $('.dss_table tr[data-id=' + id + ']').find('.disc_total').text()
                          $('.dss_table tr[data-id=' + id + ']').find('.disc_total').text( total + 1 )

                          var sel_id = $('.discussion_selected').data('id')
                          $( '.discussions_themes' ).html( data );
                          $( "#discussions_job_dialog" ).dialog('open').data('id', id );
                          $('div[data-id = ' + sel_id + ']').addClass('discussion_selected')
                          adjust_ui();
                        }
                  );
              }
        );

           $( this ).dialog( "close" );
          }
        },
        {
        
        text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
        click : function() 
          {
            $( this ).dialog( "close" );
          }
        }
      ],
      classes:
      {
        "ui-dialog-titlebar" : "user_job_dialog_title"  
      }
    });

// ********************************* Принять решение *********************************
$( "#discussions_dialog_theme_decide" ).dialog({
      resizable: false,
      width: 600,
      height : 300,
      modal: true,
      autoOpen : false,
      buttons: 
      [
          {
        // Предложить решение
        text : "\u{41F}\u{440}\u{435}\u{434}\u{43B}\u{43E}\u{436}\u{438}\u{442}\u{44C} \u{440}\u{435}\u{448}\u{435}\u{43D}\u{438}\u{435}",
        id : "final_theme_decide",
        disabled : true, 
        open : function()
        {
        },
        click : function() 
          {
            var id = $('.discussion_selected').data('id')
            var message = $('.theme_decision_textarea').val();
            var el = this 

            let conf = $('#confirm_select_to option')
            let arr = []

            $.each( conf , function( key, item )
            {
              arr.push( $( item ).data('id') );
            });


            $.post(
               '/project/dss/ajax.make_decision.php',
                  {
                      id  : id,
                      message : message,
                      res_id : res_id,
                      arr : arr
                  },
                  function( data )
                  {
                    var project_id = $('.discussion_selected').data('project_id')
                    var solved = 1 * $('.dss_table tr[data-id=' + project_id + ']').find('.disc_solved').text()
                    $('.dss_table tr[data-id=' + project_id + ']').find('.disc_solved').text( solved + 1 )
                    $('.discussion_selected').data('solved', 1 ).find('span').eq(2).text('[*]') 
                    $('.discussions').html( data )
                    adjust_ui()
                    $( el ).dialog( "close" );                    
                  }
            );
        }
      }
      ,
        {
        text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
        click : function() 
          {
            $( this ).dialog( "close" );
          }
        }
      ],
      open: function()
      {
      },
      classes:
      {
        "ui-dialog-titlebar" : "user_job_dialog_title"  
      }
    });


// ********************************* Создание нового проекта *********************************
$( "#project_create_dialog" ).dialog({
      resizable: false,
      width: width,
      height : height,
      modal: true,
      autoOpen : false,
      buttons: 
      [
          {
        // Создать проект
        text : "\u0421\u043e\u0437\u0434\u0430\u0442\u044c",
        id : "project_create",
        disabled : true, 
        create : function()
        {
            tinymce.init(
                { 
                  selector:'.project_create_textarea',
                  statusbar: false,
                  language: 'ru',
                  height : '70%',
                  width : '100%',
                  
                  // plugins : "advlist,anchor,autolink,autoresize,autosave,bbcode,charmap,code,codesample,colorpicker,contextmenu,directionality,emoticons,example,example_dependency,fullpage,fullscreen,hr,image,imagetools,importcss,insertdatetime,layer,legacyoutput,link,lists,media,nonbreaking,noneditable,pagebreak,paste,preview,print,save,searchreplace,spellchecker,tabfocus,table,template,textcolor,textpattern,visualblocks,visualchars,wordcount",
                  
                 plugins : "table,hr",

         // theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
         // theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,preCode,anchor,image,uploads_image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
         // theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
         // theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
         // theme_advanced_toolbar_location : "top",
         // theme_advanced_toolbar_align : "left",
         // theme_advanced_statusbar_location : "top",
         // theme_advanced_resizing : true,


                  menu: {
                      // file: {title: 'File', items: 'newdocument'},
                      edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
                      
                      // insert: {title: 'Insert', items: 'link media | template hr'},
                      insert: {title: 'Insert', items: 'hr'},                      
                      
                      // view: {title: 'View', items: 'visualaid'},
                      format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
                      table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
                      // tools: {title: 'Tools', items: 'spellchecker code'}
                      tools: {title: 'Tools', items: 'code'}
                    }

                });

            tinyMCE = tinymce;
          },
        click : function() 
          {
              var project_name = $('#new_project_name_input').val();
              var short_description = $('#new_project_short_name_input').val();              
              var html = tinymce.activeEditor.getContent();
              var el = this ;
              var id = $( this ).data( 'id' )

              $.post(
                   '/project/dss/ajax.project_create_update.php',
                      {
                          id : id,
                          project_name : project_name,
                          short_description : short_description,
                          html : html,
                          res_id : res_id
                      },
                      function( data )
                      {
                        if( id )
                        {
                          var tr = $( 'tr[data-id=' + id + ']')
                          $( tr ).find( '.dse_name' ).text( project_name )
                          $( tr ).find( '.dse_description' ).text( short_description )
                          $( el ).dialog( "close" );                          
                        }
                        else
                        {
                          $.post(
                            '/project/dss/ajax.get_row.php',
                            {
                                id  : data,
                                res_id : res_id,
                                level : 0
                            },
                            function( data )
                            {
                               $( '.dss_table .draggable' ).append( data );
                               adjust_ui();
                               $( el ).dialog( "close" );
                               renumberRows( 0 );
                            }
                          );

                        }
                      }
                );              
          }
      }
      ,
        {
          // close
          text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
          click : function() 
          {
            $( this ).dialog( "close" );
          }
        }
      ], // buttons
      classes:
      {
        "ui-dialog-titlebar" : "user_job_dialog_title"
//        ,"ui-dialog-buttonpane" : "ui-dialog-buttonpane-custom"
      }
    });


	$( '#add_to_team' ).unbind('click').bind('click', add_to_team_click )
	$( '#remove_from_team' ).unbind('click').bind('click', remove_from_team_click )


    $( "#refresh" ).button({
      "showLabel": true
    }).click( function(){ $('#project_create').click() } );

    $( "#close" ).button({
      "showLabel": true
    }).click( function(){ $( "#project_create_dialog" ).dialog('close'); } );
	
	adjust_ui()

  if( prj_id && disc_id )
  {
    let el = $( '#' + prj_id )
    let disc = $('.disc_theme[data-id=' + disc_id + ']')
      discussion_job( el )
  }

});


// *********************************************************************************************
function adjust_ui()
{
  $('#add_to_confirm').unbind('click').unbind('click').bind('click', add_to_confirm_click );
  $('#confirm_select_from').unbind('dblclick').bind('dblclick', add_to_confirm_click );

  $('#remove_from_confirm').unbind('click').bind('click', remove_from_confirm_click ); 
  $('#confirm_select_to').unbind('dblclick').bind('dblclick', remove_from_confirm_click ); 

	$('.expand').unbind('click').bind('click', expand_click );
	$('.expand_all').unbind('click').bind('click', expand_all_click );

	$('.ref_div').unbind('click').bind('click', ref_div_click );
	$('#user_job_dialog option').unbind('dblclick').bind('dblclick', user_job_dialog_select_dblclick );
	$('.edited').unbind('click').bind('click', edited_click );
	$('.input').unbind('keyup').bind('keyup', input_keyup );
	$('.input').unbind('blur').bind('blur', input_blur );	
	$('.add_project').unbind('click').bind('click', add_project_click );	
	$('#upload_file_input').unbind('change').bind('change', upload_file_input_change );	
	$('.del_img').unbind('click').bind('click', del_img_click );
	$('.img_comment').unbind('keyup').bind('keyup', img_comment_keyup );
  $('.discussions_themes div').unbind('click').bind('click', discussions_themes_click ); 
  $('.new_theme_input').unbind('keyup').bind('keyup', new_theme_change );
  $('.new_theme_textarea').unbind('keyup').bind('keyup', new_theme_change );
  $('.theme_decision_textarea').unbind('keyup').bind('keyup', theme_decision_change );  
  $('.resp_textarea').unbind('keyup').bind('keyup', resp_textarea_change );
  $('.resp_span').unbind('click').bind('click', resp_span_click );
  $('#new_project_name_input').unbind('keyup').bind('keyup', new_project_name_input_keyup );
  $('.head').unbind('dblclick').bind('dblclick', head_dblclick );
  $('.del_row').unbind('click').bind('click', del_row_click );

  $('.confirm_solution').unbind('click').bind('click', confirm_solution_click );
  $('.delete_solution').unbind('click').bind('click', delete_solution_click );  


  $( ".draggable" ).sortable({
      delay: 1000,
      start: function(e, ui) 
       {
          let id = $( ui.item ).data('id');
          let level = $( ui.item ).data('level');          
          let parent_id = $( ui.item ).data('parent-id');

// Отключена ссылка на родительскую строку
          $( ui.item ).data('parent-id',0).attr('data-parent-id','');
          
// Список оставшихся у бывшего родителя строк
          let trs = $('tr[data-parent-id=' + parent_id + ']' )

// Если строк не осталось, убирается символ expand/collapse
          if( trs.length == 0 )
            remove_arrow( parent_id )

// Добавляется CSS перетаскиваемой строки
          $( ui.item ).find('.expand_all').remove();
          $( ui.item ).removeClass( 'level_' + $( ui.item ).data('level') ).addClass('dragged');
          
// Список дочерник строк, если имеются          
          trs = $( ui.item ).nextAll()
          let ids = [];

          $.each( trs , function( key, item )
          {
            // фильтрация по уровню вложенности
              let loc_level = $( item ).data('level')

              if( ! loc_level ) // Продолжение цикла, если нет loc_level ???
                return true ;

              // Если является вложенной, то сохраняем id
              if( loc_level > level )
              {
                ids.unshift( $( item ).data('id') )
                $( item ).hide()
              }
                else // Иначе, выход из цикла
                  return false ;
          });

          renumberRows( parent_id );
          $( ui.item ).data('childs', ids )   
       }, // start: function(e, ui) 
      stop: function( event, ui ) 
      {
          let cur_tr = ui.item ;
          let cur_id = $( cur_tr ).data('id') ;
          let base_level = $( cur_tr ).data('level');
          let level =  parseInt( ( ui.position.left ) / LEVEL_SHIFT ) * LEVEL_SHIFT ;

          let renumber_id = 0 ;
          let parent_id = 0 ;
          let base_id = 0 ;
          let cause = 'unknown';

          let up_tr = $( ui.item ).prev();
          let up_level = 0 ;
          let up_id = 0 ;

          if( up_tr )
          {
            up_level = parseInt( $( up_tr ).data('level') );
            up_id = parseInt( $( up_tr ).data('id') );
          }

          if( level <= 0 || ! up_id )
          {
            level = 0;
            base_id = cur_id ;
            insert_expand_all_icon( cur_id )
            $( cur_tr ).data('base-id', cur_id ).attr('data-base-id', cur_id )
          }

          $( cur_tr ).removeClass('dragged');

// ***************************************************************************
          if( up_level < level )
          {
              parent_id = $( up_tr ).data('id')
              base_id = $( up_tr ).data('base-id')
              renumber_id = parent_id;
              level = up_level + LEVEL_SHIFT;
              insert_arrow( up_id );
              cause ='cond 1';
          } // if( up_level < level )

// ***************************************************************************
          if( up_level == level )
          {
              if( level )
              {
                parent_id = $( up_tr ).data('parent-id')
                base_id = $( up_tr ).data('base-id')
                renumber_id = parent_id;
              }
              else
              {
                parent_id = 0;
                renumber_id = 0;
                base_id = cur_id;                
                $( search_last_tr( up_id, cur_id ) ).after( cur_tr );
              } 

              cause ='cond 2';              
          } // if( up_level == level )

// ***************************************************************************
          if( up_level > level )
          {
              let first_tr_id = search_up( cur_id, level )
              let first_tr = $( 'tr[data-id=' + first_tr_id + ']');
              let last_tr = search_last_tr( first_tr_id, cur_id );
              $( last_tr ).before( cur_tr );
              parent_id = $( first_tr ).data('parent-id')
              base_id = $( first_tr ).data('base-id')
              renumber_id = parent_id;
 
              cause ='cond 3';
          }// if( up_level > level )

          $( cur_tr ).find('.deb_par_id').text( 'PAR_ID : ' + parent_id )
          $( cur_tr ).find('.deb_cause').text( cause + ' : ' + up_level + ' : ' + level )
          $( cur_tr ).find('.deb_base_id').text( 'BASE_ID : ' + base_id )          
          
          move_childs( cur_tr, base_level - level, base_id, $( cur_tr ).data( 'state' ) )

          $( cur_tr ).data('parent-id', parent_id ).data('base-id', base_id ).attr('data-parent-id', parent_id ).attr('data-base-id', base_id ).addClass('level_' + level ).data('level', level ).attr('data-level', level )

          renumberRows( renumber_id )

          $.post(
                   '/project/dss/ajax.update_fields.php',
                      {
                          id  : cur_id,
                          field : null,
                          value : null,
                          field_arr : ['base_id','parent_id'],
                          value_arr : [ base_id, parent_id ]
                      },
                      function( data )
                      {
                        // cons( data )
                      }
                );

          adjust_ui();
      }//stop: function( event, ui ) 
  });

} // function adjust_ui()

// ***************************************************************************************************
function expand_click()
{
	var tr = $( this ).closest('tr');
	var state = 1 * $( tr ).data( 'state' )
	var id = $( tr ).data( 'id' )
	var level = $( tr ).data( 'level' )
	var role = $( this ).data('role')
	
	if( state )
	{
		set_state( tr, 0 );

		if( role == 'project_exp_coll' ) 						// Hide project section
		{
			var trs = $( tr ).nextAll();
			$.each( trs , function( key, item )
    		{
      			var loc_level = $( item ).data('level');
      			if( loc_level <= level )
      				return false;
      					else
      						$( item ).hide() 
    		});
		}

	}
	else
	{
		state = 1

		if( role == 'project_exp_coll' )							// Show project section
		{		
				if( $( tr ).data('changed') ) // Ветка уже создавалась? Да, раскрываем дерево
				{
		      		let loc_state = 1;
					let loc_level = level;
					trs = $( tr ).nextAll(); 
							
					$.each( trs , function( key, item )
		    		{
						var tmp_level = $( item ).data('level');
		      			var tmp_state = $( item ).data('state');

		      			if( $( item ).data('level') <= level )
		      				return false;
		      				else
		      				{
								if(  tmp_level < loc_level )
								{
									loc_state = tmp_state
									loc_level = tmp_level									
									$( item ).show(); //.css('background','red')
								}

								if(  tmp_level == loc_level )
								{
									loc_state = tmp_state
									$( item ).show(); //.css('background','cyan')
								}

								if(  tmp_level > loc_level && loc_state )
								{
									loc_state = tmp_state
									loc_level = tmp_level
									$( item ).show(); //.css('background','lime')									
								}
	      					}
		    		});
		    		set_state( tr, 1 );
				}
				 else // Ветка уже создавалась? Нет, создается новая строка
				 {
				 	$( tr ).data('changed', 1 ).attr('data-changed', '1' )
		         $.post(
                    '/project/dss/ajax.get_childs.php',
                    {
                        id  : id,
                        res_id : res_id,
                        level : level
                    },
                    function( data )
                    {
                    	 $( tr ).after( data );
				               set_state( tr, 1 );                          	
                    	 adjust_ui();
                    }
              );
             }
		}
	}

	$( this ).siblings('span').removeClass('hidden');
	$( this ).siblings('input').addClass('hidden');
}

// ***************************************************************************************************
function ref_div_click()
{
  let role = $( this ).data( 'role' );

	switch( role )
	{
		case 'users_job' : 	user_job( this ); break ;
		case 'pict_job' : picture_job( this ); break ;
		case 'disc_job' : discussion_job( this ); break ;
		case 'dse_job' : add_dse( this ); break ;		
	}
}

// ***************************************************************************************************
function set_state( el, state )
{
	$( el ).data( 'state', state ).attr( 'data-state', state )
	var icon = $( el ).find('img.expand, img.coll');	
		
	if( 1 * state )
		$( icon ).attr( 'src', collapse ).addClass('expand').removeClass('coll')
		 else
			$( icon ).attr( 'src', expand ).addClass('coll').removeClass('expand')
}

// ***************************************************************************************************
function add_to_team_click()
{
	var list = $( '#user_select_from option:selected' )
	move_selected_options( '#user_select_to' , list )
}

// ***************************************************************************************************
function remove_from_team_click()
{
	var list = $( '#user_select_to option:selected' )
	move_selected_options( '#user_select_from' , list )
}

function user_job_dialog_select_dblclick()
{
	var cls = $( this ).parent().attr('id');

  if( res_id == $( this ).val() )
    return;

	if( cls == 'user_select_from')
		$('#user_select_to').append( this )
			else
				$('#user_select_from').append( this )
	
	$( this ).prop('selected', false )

	sort_select ( '#user_select_from' )
	sort_select ( '#user_select_to' )
}

function move_selected_options( cls, list )
{
	$.each( list , function( key, item )
    {
      if( $( item ).val() != res_id )
        $( cls ).append( $( item ) )
    });

    sort_select ( cls )
    $( cls + ' option' ).attr('selected', false)
}

function sort_select ( select )
{
    var options = jQuery.makeArray( $( select ).find('option') );
    var sorted = options.sort(function(a, b) 
    {
        return (jQuery(a).text() > jQuery(b).text()) ? 1 : -1;
    });
    $( select ).append(jQuery( sorted )).attr('selectedIndex', 0);
};

function user_job( el )
{
	let id = $( el ).closest('tr').data('id');
  let creator_id = $( el ).closest('tr').data('creator-id');

	// Очистить список участников
	let list = $( '#user_select_to option' )
	move_selected_options( '#user_select_from' , list )

	// Перенести членов обсуждения в список участников
	let member_list = String( $( el ).data('member-list') );
	let member_arr = member_list.split(',');

	if( member_arr.length )
	{
		member_arr.forEach(function(item, i, arr) 
		{
			var option = $( 'option[value=' + item + ']' )
			$( '#user_select_to' ).append( $( option ) )
		});

		sort_select ( '#user_select_to' )
	}

	$( "#user_job_dialog" ).data('id',id );

  if( res_id == creator_id )
    $('#apply').button( { disabled : false } )    

	$( "#user_job_dialog" ).dialog('open');
}

function picture_job( el )
{
	var id = $( el ).closest('tr').data('id');
	$( "#picture_job_dialog" ).data('id',id );

	$.post(
           '/project/dss/ajax.get_pictures_table.php',
              {
                  id  : id,
              },
              function( data )
              {
              	$('#picture_job_dialog div').html( data );
              	adjust_ui();
				        $( "#picture_job_dialog" ).dialog('open');
              }
        );
}

function add_dse( el )
{
	var tr = $( el ).closest('tr');
	var state = $( tr ).data('state')
	var level = $( tr ).data('level')
	var parent_id = $( tr ).data('id')
	var id = $( tr ).data('id')
	var base_id = $( tr ).data('base-id')

	if( ! state )
		{
			$( tr ).find('.expand').click()
			$( tr ).find('.head_wrap .icon').eq(0).addClass('expand').attr('src', collapse ).data('role','project_exp_coll')
			$( tr ).data('changed',1).data('state',1 )
		}

	$.post(
          '/project/dss/ajax.create_child.php',
          {
              parent_id  : id,
              base_id : base_id,
              level : level,
              res_id : res_id
          },
          function( data )
          {
          	var inserted = 0 ;
          	
          	var level = $( data ).data('level');
          	var ord = Number( $( data ).data('ord')) - 1 ;
			      var trs = $( $('tr[data-parent-id=' + id + '][data-level=' + level + '][data-ord=' + ord + ']') ).nextAll()

            let before = 0 ;

          	if( trs.length )
          	{
                let target_tr = 0;

        				$.each( trs , function( key, item )	
        				{
        					var loc_level = $( item ).data('level');
                  target_tr = item

        					if( loc_level <= level )
                      {
                        before = 1 ;
            	      		return false;
                      }
        				});

              if( before )
                $( target_tr ).before( data )
                  else
                  $( target_tr ).after( data )
  			   }
  				else
  				{
  					if( ord )
  						tr = $( $('tr[data-parent-id=' + id + '][data-level=' + level + '][data-ord=' + ord + ']') )

  					$( tr ).after( data )
  				}

            insert_arrow( parent_id )
           	adjust_ui();
          }
    );
}

function edited_click()
{
	$( this ).find('.dse_name,.dse_description').addClass('hidden');
	$( this ).find('input').removeClass('hidden').focus();

}

function input_blur( )
{
  $( this ).siblings('.dse_name,.dse_description').removeClass('hidden');
  $( this ).addClass('hidden');
}

function input_keyup()
{
	var id = $( this ).closest('tr').data('id');
	var field = $( this ).data('field');
	var value = $( this ).val();		

	$( this ).siblings('span').text( value );
	$.post(
           '/project/dss/ajax.update_fields.php',
              {
                  id  : id,
                  field : field,
                  value : value,
                  field_arr : [],
                  value_arr : []
              },
              function( data )
              {
//                cons( data )
              }
        );
}

function add_project_click()
{
  $('#new_project_name_input').val('');
  $('#new_project_short_name_input').val( '' );

  $('#project_create').button( { disabled : true } ).button( "option" , "label" , "\u0421\u043e\u0437\u0434\u0430\u0442\u044c" ) 
  $('#refresh').button( { disabled : true } ).button( "option" , "label" , "\u0421\u043e\u0437\u0434\u0430\u0442\u044c" ) 

  tinyMCE.activeEditor.setContent('');  
  $( "#project_create_dialog" ).data( 'id', 0 ).dialog({ title : '\u0421\u043e\u0437\u0434\u0430\u043d\u0438\u0435 \u043d\u043e\u0432\u043e\u0433\u043e \u043f\u0440\u043e\u0435\u043a\u0442\u0430' }).dialog('open'); 
}

function upload_file_input_change()
{
	var id = $( "#picture_job_dialog" ).data('id');
	var files = this.files;
    if( files.length == 0 )
        return;

    // Создадим данные формы и добавим в них данные файлов из files
    var data = new FormData();

    $.each( files, function( key, value )
    {
        data.append( key, value );
    });

    // Добавить id 
    data.append( 'id', id );

	startLoadingAnimation();

	$.ajax({
          	url : '/project/dss/ajax.upload_image.php',
          	type: 'post',
          	dataType: 'text',
          	cache: false,
          	contentType: false,
          	processData: false,
            data : data,
            success: function( response )
            	{
            		stopLoadingAnimation();
            		// load updated data
				 	$.post(
				           '/project/dss/ajax.get_pictures_table.php',
				              {
				                  id  : id,
				              },
				              function( data )
				              {
				              	var count = $( data ).data('count');
				              	$('tr[data-id=' + id + ']').find('.pictures_count').text( count )
				              	$('#picture_job_dialog div').html( data )
				              	adjust_ui();
				              }
				        );
            		adjust_ui();
          		},
     		error: function(xhr, ajaxOptions, thrownError) 
     			{
       				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    			}
    		});
}

function startLoadingAnimation() // - функция запуска анимации
{
  // найдем элемент с изображением загрузки и уберем невидимость:
  var imgObj = $("#loadImg");
  imgObj.show();
 
  var centerY = $(window).height() / 2  - imgObj.height()/2 ;
  var centerX = $(window).width()  / 2  - imgObj.width()/2;
 
  // поменяем координаты изображения на нужные:
  imgObj.offset( { top: centerY, left: centerX } );

}
 
function stopLoadingAnimation() // - функция останавливающая анимацию
{
  $("#loadImg").hide();
}

function del_img_click()
{
	var id = $( "#picture_job_dialog" ).data('id');
	var name = $( this ).closest('tr').find('.file_name').text();

	startLoadingAnimation();

	$.post(
           '/project/dss/ajax.delete_picture.php',
              {
                  id  : id,
                  name : name
              },
              function( data )
              {
              	// load updated data
				 	$.post(
				           '/project/dss/ajax.get_pictures_table.php',
				              {
				                  id  : id,
				              },
				              function( data )
				              {
         					    var count = $( data ).data('count');

				              	$('tr[data-id=' + id + ']').find('.pictures_count').text( count )
				              	$('#picture_job_dialog div').html( data )
				              	stopLoadingAnimation()
				              	adjust_ui();
				              }
				        );
              	adjust_ui();
              }
        );
}
function img_comment_keyup()
{
	var comment = $( this ).val()
	var id = $( "#picture_job_dialog" ).data('id');
	var name = $( this ).closest('tr').find('.file_name').text()

	$.post(
           '/project/dss/ajax.update_picture_comment.php',
              {
                  id  : id,
                  comment : comment,
                  name : name
              },
              function( data )
              {
              }
        );	
}

function discussion_job( el )
{
  var tr = $( el ).closest('tr');
  var id = $( tr ).data( 'id' )
  var member_list = $( tr ).find( '.member_div' ).data('member-list')

  $('.discussions_themes').html('');
  $('.discussions').html('');  

  $.post(
           '/project/dss/ajax.get_discussion_themes.php',
              {
                id : id,
                res_id : res_id                
              },
              function( data )
              {

                $( '.discussions_themes' ).html( data );
                $( "#discussions_job_dialog" ).dialog('open').data('id', id ).data('member-list', member_list );
                adjust_ui();
                if( $( data ).filter('.can_add').length )
                {
                    $('#theme_decide').button( { disabled : false } )     
                    $('#add_discussion').button( { disabled : false } )     
                }
                
                if( disc_id )
                {
                  let disc = $('.disc_theme[data-id=' + disc_id + ']')
                  // cons( disc.length )
                  if( disc.length )
                    $( disc ).click()
                }
                  else
                    $('.disc_theme').eq(0).click()
              }
        );
}


function discussions_themes_click()
{
  $( this ).find('.conv_icon_span').remove()
  let id = $( this ).data( 'id' )
  let el = this ;
  let solved = $( this ).data( 'solved' ) ;

  if( id == undefined )
    return ;

  $( this ).find( '.conv_icon' ).hide()

  $.post(
           '/project/dss/ajax.get_discussions.php',
              {
                id : id,
                res_id : res_id
              },
              function( data )
              {
                let serv_span = $( data ).filter( '.serv_span' )
                let new_disc_count = $( serv_span ).text()
                if( new_disc_count != -1 )
                  {
                     id = $( "#discussions_job_dialog" ).data('id' )
                     let span = $( '#' + id ).find( '.disc_new' )
                     let val = $( span ).text() - new_disc_count;
                     if( val == 0 )
                        $( '#' + id ).find( '.new_mess' ).removeClass('new_mess');
                     $( span ).text( val )
                  }

                $( '.discussions' ).html( data );
                $('.discussion_selected').removeClass('discussion_selected');
                $( el ).addClass('discussion_selected')

                // if( solved )
                //   $('#theme_decide').button( { disabled : true } )
                //     else
                //       $('#theme_decide').button( { disabled : false } )

                adjust_ui();
              }
        );
}

function new_theme_change()
{
  var theme = $('.new_theme_input') .val();
  var message = $('.new_theme_textarea').val();
  if( theme.length && message.length )
      $('#create_new_theme').button( { disabled : false } )
        else
          $('#create_new_theme').button( { disabled : true } )
}

function theme_decision_change()
{
  decision_enable_disable()
}

function resp_span_click()
{
 var id = $( this ) .parent().data('id')
 $('.resp_textarea').val('')
 $('#discussions_dialog_response').dialog('open').data('id', id )
}

function resp_textarea_change()
{
  var message = $('.resp_textarea').val();
  if( message.length )
      $('#response_button').button( { disabled : false } )
        else
          $('#response_button').button( { disabled : true } )
}

function new_project_name_input_keyup()
{
  var message = $('#new_project_name_input').val();
  if( message.length )
  {
      $('#project_create').button( { disabled : false } )
      $('#refresh').button( { disabled : false } )
  }
        else
        {
          $('#project_create').button( { disabled : true } )
          $('#refresh').button( { disabled : true } )          
        }
}

function head_dblclick()
{
  var tr = $( this ).closest( 'tr' )
  var id = $( tr ).data( 'id' )
  var name = $( tr ).find('span.dse_name').text()
  var description = $( tr ).find('span.dse_description').text()
  
  $('#new_project_name_input').val( name );
  $('#new_project_short_name_input').val( description );
  tinyMCE.activeEditor.setContent('');

$.post(
         '/project/dss/ajax.get_project_html.php',
            {
              id : id
            },
            function( data )
            {
                if( !data.length )
                  data = "&nbsp;";
                tinyMCE.activeEditor.execCommand("mceInsertContent", false, data ); 
                $('#new_project_name_input').focus();
                adjust_ui();
            }
      );
  
  $('#project_create').button( { disabled : false } ).button( "option" , "label" , "\u041e\u0431\u043d\u043e\u0432\u0438\u0442\u044c" ) 
  $('#refresh').button( { disabled : false } ).button( "option" , "label" , "\u041e\u0431\u043d\u043e\u0432\u0438\u0442\u044c" ) 
  $( "#project_create_dialog" ).data( 'id', id ).dialog({ title : '\u0420\u0435\u0434\u0430\u043a\u0442\u0438\u0440\u043e\u0432\u0430\u043d\u0438\u0435 \u043f\u0440\u043e\u0435\u043a\u0442\u0430' }).dialog('open');
}

function del_row_click()
{
  	var tr = $( this ).closest( 'tr' )
  	var id = $( tr ).data( 'id' )  
 
 	$( "#delete_row_dialog" ).data('tr', tr )
 	$( "#delete_row_dialog" ).data('id', id ) 	
	$( "#delete_row_dialog" ).dialog('open');
}

function expand_all_click()
{
	var el = this ;
	var tr = $( this ).closest('tr')
	var id = $( tr ).data('id')

	var state = Number( $( this ).data('state') )

  if( state )
	{
		var trs = $('tr[data-base-id=' + id +']')
		$.each( trs , function( key, item )
		{
			if( $( item ).data('parent-id') )
				$( item ).hide();
		});
      	$( el ).attr('src','/uses/svg/expand_sharp.svg').data('state', 0 )
        $( el ).siblings('.icon').attr('src', expand )
	}
	else
	    $.post(
	       '/project/dss/ajax.get_full_tree.php',
	          {
	              id  : id,
	              res_id : res_id
	          },
	          function( data )
	          {
//              cons( data )

	          	var trs = $('tr[data-base-id=' + id +']')
      				$.each( trs , function( key, item )
      	    		{
      					if( $( item ).data('parent-id') )
      						$( item ).remove();
      	    		});

      	          	$( 'tr[data-id=' + id + ']' ).after( data )
      	          	$( el ).attr('src','/uses/svg/collapse_sharp.svg').data('state', 1 )
                    $( el ).siblings('.icon').attr('src', collapse )
      				      adjust_ui();
      	     }
	    );	
}

function renumberRows( id )
{
  let line = 1 ;
  let trs = $('tr[data-parent-id=' + id + ']')
  let values = [];

  $.each( trs , function( key, item )
        {
          $( item ).data('ord', line ).attr('data-ord', line ).find('.ord').text( line );
          values[ line ] = $( item ).data('id') ;
          line ++ ;          
        }); 

    // cons( values )

    $.ajax({
        url:"/project/dss/ajax.renumberRows.php",
        type:'post',
        data:
        { 
          values : values
        },
        success:function( data )
        {
          // cons( data );
        }
    })
}

function move_childs( tr, level, base_id, state )
{
  let trs = $( tr ).data('childs');
  let ids = [];
  let change_base_id = false ;

  $.each( trs , function( key, id )
  {
      let item = $( 'tr[data-id=' + id + ']');
      ids.push( id );

      let loc_level = $( item ).data('level');
      let new_level = loc_level - level;

      $( item ).removeClass('level_' + loc_level).addClass('level_' + new_level).data('level', new_level ).attr('data-level', new_level).data('base-id', base_id).attr('data-base-id', base_id);

      $( tr ).after( item );
      
      if( state )
        $( item ).show();
  });

    $.post(
             '/project/dss/ajax.move_childs.php',
                {
                  ids : ids,
                  base_id : base_id
                },
                function( data )
                {
                  // cons( 'new base_id : ' + base_id );
                }
          );
}

function remove_arrow( id )
{
  let tr = $('tr[data-id='+ id +']');
  let creator_id = $( tr ).data('creator-id')
  let del_div = $( tr ).find('.head_wrap').last()

    // Иконка родительской строки
  let target_img = $( tr ).find( '.head_wrap').eq(0).find('.icon')

  $( target_img ).removeClass('expand').attr('src', empty ).data('src',expand ).removeAttr('data-src').removeAttr('data-role');

   $( del_div ).html($('<img>', {
                                    class :  'icon del_row',
                                    'data-role' : 'del_row',
                                    src : '/uses/del.png'  
                                  })
                      )
}

function insert_expand_all_icon( id )
{
  let target_img = $( 'tr[data-id=' + id + ']' ).find( '.head_wrap').eq(0).find('.icon')
 
  $( target_img ).before($('<img>', {
                                    class :  'expand_all',
                                    'data-role' : 'project_exp_coll',
                                    src : '/uses/svg/collapse_sharp.svg'  
                                  })
                      )
}


function insert_arrow( id )
{
    $( 'tr[data-id=' + id + ']' ).find( '.head_wrap').eq(0).find('.icon').addClass('expand').attr('src', collapse ).data('src',expand).attr('data-src', expand ).data('role','project_exp_coll').attr('data-role','project_exp_coll');
}

function search_last_tr( id, exclude_id )
{
  let cur_tr = $( 'tr[data-id=' + id + ']');
  let level = $( cur_tr ).data('level');
  let trs = $( cur_tr ).nextAll();
  let last_tr = 0 ;

  $.each( trs , function( key, item )
        {
          last_tr = item ;

          if( $( item ).data('id') == exclude_id )
              return true;

          if( $( item ).data('level') <= level )
              return false;
        });
 
  return last_tr;
}


function search_up( id, level )
{
  let trs = $( 'tr[data-id=' + id + ']').prevAll();
  let res_id = 0 ;

  $.each( trs , function( key, item )
        {
          if( $( item ).data('level') == level )
            {
              res_id = $( item ).data('id')
              return false;
            }
        });
  
  return res_id;
}

function add_to_confirm_click()
{
  let option = $( '#confirm_select_from option:selected' )
  if( option )
  {
    $( '#confirm_select_to' ).append( $( option ))
    conf_sort('#confirm_select_to')
  }
  decision_enable_disable()    
}

function remove_from_confirm_click()
{
  let option = $( '#confirm_select_to option:selected' )
  if( option )
  {
    $( '#confirm_select_from' ).append( $( option ))
    conf_sort('#confirm_select_from')
  }

  decision_enable_disable()  
}

function conf_sort( id )
{
  let options = $( id + " option");
  options.detach().sort(function(a,b) 
  {
    let at = $(a).text();
    let bt = $(b).text();         
    return (at > bt)?1:((at < bt)?-1:0);            // Tell the sort function how to order
  });

  options.appendTo( id );
}

function confirm_solution_click()
{
  let solution_id = $( this ).data('solution_id')
  let that = this

  $.post(
        "project/dss/ajax.confirm_decision.php",
        {
            id   : solution_id ,
            res_id : res_id 
        },
        function( data )
        {
          let obj = JSON.parse( data )

          let title = "";
          let confirmed = 1 

          $.each(obj, function(index, value) 
          {
            title += value.name + " : "
            if( value.confirmed == 1 )
              title += "\u{43F}\u{43E}\u{434}\u{442}\u{432}."
              else
              {
                title += "\u{43D}\u{435} \u{43F}\u{43E}\u{434}\u{442}\u{432}."
                confirmed = 0
              }

              title += "\n"
          });

          $('.solved_theme_span').attr( 'title', title )
          $('.need_confirm').attr( 'title', title )

          $( that ).remove()
          $( '.discussions_themes .discussion_selected .need_conf').removeClass('need_conf')

          if( confirmed )
              {
                $( '.need_confirm' ).removeClass('need_confirm').addClass('confirmed').text(" \u{41F}\u{43E}\u{434}\u{442}\u{432}\u{435}\u{440}\u{436}\u{434}\u{435}\u{43D}\u{43E} ")
              }
        }
    );
}

function delete_solution_click()
{
  let solution_id = $( this ).data('solution_id')
  let that = this

  $.post(
        "project/dss/ajax.delete_decision.php",
        {
            id   : solution_id
        },
        function( data )
        {
          $( that ).remove()
          $( '.solved_theme_span, .confirmed, .need_confirm' ).remove()
          // cons( data )
        }
    );
 
}

function decision_enable_disable()
{
let option = $('#confirm_select_to option')
  let message = $('.theme_decision_textarea').val();
  if( message.length && option.length )
      $('#final_theme_decide').button( { disabled : false } )
        else
          $('#final_theme_decide').button( { disabled : true } )
}