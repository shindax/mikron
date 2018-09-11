$( function()
{
	adjust_ui()
});


function adjust_ui()
{
	$('.expand').unbind('click').bind('click', expand_click );
	$('.add').unbind('click').bind('click', add_click );
	$('.ref_div').unbind('click').bind('click', ref_div_click );
}


function add_click()
{
	var cls = $( this ).attr('class')
	cls = cls.replace(/icon/, "");
	cls = cls.replace(/add/, "");
	cls = cls.replace(/\s/g, '');

	switch( cls )
	{
		case 'discussion_add' : alert( 'discussion add' ) ; break ;
		case 'dse_add' : alert( 'dse add' ) ; break ;
	}
}

function expand_click()
{
	var state = 1 * $( this ).data( 'state' )
	var id =  $( this ).closest('tr').data( 'id' )
	var role = $( this ).data('role')

	// switch( cls )
	// {
	// 	case 'discussion_exp_coll' : what = 'discussion_exp_coll'; break ;
	// 	case 'dse_exp_coll' : what = 'dse_exp_coll'; break ;
	// 	case 'project_exp_coll' : what = 'project_exp_coll'; break ;
	// }


	if( state )
	{
		$( this ).data( 'state', 0 );							// change state		
		$( this ).attr( 'src','/uses/svg/arrow-down.svg' );		// and arrow symbol

		if( role == 'project_exp_coll' ) 						// Hide project section
		{
			$( 'tr[data-id=' + id + ']').addClass('hidden'); 	// with data-id = id
			$( this ).closest('tr').removeClass('hidden'); 		//except project head
		}

//		if( role == 'discussion_exp_coll' ) 						// Hide discussion section
//		if( role == 'dse_exp_coll' ) 							// Hide dse section
	}
	else
	{
		$( this ).data( 'state', 1 ); 							// change state
		$( this ).attr( 'src','/uses/svg/arrow-up.svg' );		// and arrow symbol

		if( role == 'project_exp_coll' )							// Show project section
			$( 'tr[data-id=' + id + ']').removeClass('hidden');

//		if( role == 'discussion_exp_coll' ) 						// Show discussion section
//		if( role == 'dse_exp_coll' ) 							// Show dse section
	}
}

function ref_div_click()
{
	var role = $( this ).data( 'role' );

	switch( role )
	{
		case 'users_add_rem' : alert( 'users_add_rem' ); break ;
		case 'pict_add_rem' : alert( 'pict_add_rem' ); break ;
	}
}