      stop: function( event, ui ) 
      {
          let cur_tr = ui.item ;
          let cur_id = $( cur_tr ).data('id') ;
          let base_level = $( cur_tr ).data('level');
          let level =  parseInt( ( ui.position.left ) / LEVEL_SHIFT ) * LEVEL_SHIFT ;

          if( level < 0 )
            level = 0;

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

          let down_tr = null
          let trs = $( ui.item ).nextAll()

          $.each( trs , function( key, item )
          {
            // cons( $( item ).data('level') + ' : ' + level )
            
            if( 1 * $( item ).data('level') >= 1 * level )
              return true;
              else
              {
                down_tr = $( item );
                return false ;
              }
          });

          let down_level = 0 ;
          
          if( down_tr )
              down_level = parseInt( $( down_tr ).data('level') );

          $( cur_tr ).removeClass('dragged').addClass( 'level_' + level ).data('level', level ).attr('data-level', level)

            // cons( up_level + ' : ' + level  + ' : ' + down_level )
            // cons( $( up_tr ).find('.dse_name').text() + ' : ' + $( cur_tr ).find('.dse_name').text()  + ' : ' + $( down_tr ).find('.dse_name').text() )

// Вставка между одинаковыми строками, или непосредственно под родительску строку

          if( up_level < level )
          {

            if( level < down_level )
            {
              cause ='cond 1';
            }
            if( level == down_level )
            {
              let parent_id = 0 ;
              parent_id = $( down_tr ).data('parent-id')
              renumber_id = parent_id ;
              base_id = $( up_tr ).data('base-id')
              cause ='cond 2';              
            }
            if( level > down_level || isNaN( down_level ) )
            {
              let loc_level = $( up_tr ).data('level') + LEVEL_SHIFT ;
              let cur_level = $( cur_tr ).data('level') ;
              parent_id = $( up_tr ).data('id')
              base_id = $( up_tr ).data('base-id')
              renumber_id = parent_id
              $( cur_tr ).removeClass('level_' + cur_level ).addClass('level_' + loc_level ).data('level', loc_level).attr('data-level', loc_level)
              level = $( up_tr ).data('level') + LEVEL_SHIFT;
              insert_arrow( parent_id );
              cause ='cond 3';
            }

          }

// Вставка под родительску строку, межну нею и родительскими элементами
          if( up_level == level )
          {
            if( level < down_level )
              {
                cause ='cond 4';
                parent_id = $( up_tr ).data('parent-id')
                base_id = $( up_tr ).data('base-id')
                let id = $( up_tr ).data('id') ;
                $( 'tr[data-parent-id=' + id + ']').last().after( $( cur_tr ))
                
                renumber_id = $( up_tr ).data('parent-id') ;
                base_id = $( up_tr ).data('base-id') ;
              }
            if( level == down_level || isNaN( down_level ) )
              {
                parent_id = $( up_tr ).data('parent-id');
                base_id = $( up_tr ).data('base-id');
                renumber_id = parent_id ;
                $( search_last_tr( up_id, cur_id ) ).after( cur_tr )
                cause ='cond 5';
              }
            if( level > down_level )
              {
                let id = search_up( cur_id, level )
                parent_id = $( 'tr[data-id=' + id + ']' ).data('parent-id');
                base_id = $( 'tr[data-id=' + id + ']' ).data('base-id');
                $( search_last_tr( up_id, cur_id ) ).before( cur_tr )
                renumber_id = parent_id ;
                cause ='cond 6 : ' + id;
              }
          }          

          if( up_level > level )
          {
            if( level < down_level )
                {
                  let id = search_up( up_id, level )
                  $( search_last_tr( id, cur_id )).before( cur_tr )
                  parent_id = $( 'tr[data-id=' + id + ']' ).data('parent-id');
                  base_id = $( 'tr[data-id=' + id + ']' ).data('base-id');
                  renumber_id = parent_id ;                  
                  cause ='cond 7';                  
                }          
            if( level > down_level )
                {
                  let loc_parent_id = search_up( up_id, level );
                  parent_id = $( 'tr[data-id=' + loc_parent_id + ']').data('parent-id');
                  base_id = $( 'tr[data-id=' + loc_parent_id + ']').data('base-id');
                  let last_tr = search_last_tr( loc_parent_id, cur_id )

                  $( last_tr ).before( cur_tr )
                  renumber_id = parent_id ;

                  cause ='cond 8 : ' + base_id + ' down_level : ' +  down_level ;
                }
            if( level == down_level || isNaN( down_level ) )
                {
                  parent_id = $( down_tr ).data('parent-id');
                  base_id = $( down_tr ).data('base-id');
                  renumber_id = parent_id ;
                  cause ='cond 9';
                }
          }// if( up_level > level )

          $( cur_tr ).data('parent-id', parent_id ).data('base-id', base_id ).attr('data-parent-id', parent_id ).attr('data-base-id', base_id )

          $( cur_tr ).find('.deb_par_id').text( parent_id )
          $( cur_tr ).find('.deb_cause').text( cause + ' : ' + up_level + ' : ' + level + ' : ' + down_level )
          
          renumberRows( renumber_id );

          if( level == 0 )
          {
            base_id = cur_id ;
          }
          
          move_childs( cur_tr, base_level - level, base_id, $( cur_tr ).data( 'state' ) )

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

        
          if( level == 0 )
          {
            insert_expand_all_icon( cur_id )
            $( cur_tr ).data('base-id', cur_id ).attr('data-base-id', cur_id )
          }
          adjust_ui();
      }//stop: function( event, ui ) 

function updateOrder(data) 
{
    $.ajax({
        url:"/project/dss/ajax.dragAndDropUpdate.php",
        type:'post',
        data:{ position : data },
        success:function()
        {
        }
    })
}
