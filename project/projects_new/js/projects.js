// Actions after full page loading
$( function()
{
    $( 'button' ).unbind('click').bind( 'click', newProjectClick );

});

function newProjectClick( e )
{
  e.preventDefault();
}