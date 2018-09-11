function SetFileUploadProcs()
{

  $('img.view_file').click( view_image );
  $('img.del_file').click( delete_file );

   $('img.load_file').click( function()
   {
      event.stopPropagation();
      $('#upload_file_input').click();
      $('#upload_file_input').data('id', $( this ).data('id') ) ;
   }); 
}


function startLoadingAnimation() // - функция запуска анимации
{
  var imgObj = $("#loadImg").show();
 
  var centerY = $(window).height() / 2  - imgObj.height()/2 ;
  var centerX = $(window).width()  / 2  - imgObj.width()/2;
 
  // установка координат изображения:
  imgObj.offset( { top: centerY, left: centerX } );
}
 
function stopLoadingAnimation() // - функция останавливающая анимацию
{
  $("#loadImg").hide();
}

$( function()
{
//    $("#loading").bind("ajaxSend", startLoadingAnimation ).bind("ajaxComplete", stopLoadingAnimation);
    
    SetFileUploadProcs();
    
$('#upload_file_input').change( function()
{
    var files;
    var id = $('#upload_file_input').data('id') ;
    
    event.stopPropagation(); // Остановка происходящего
    event.preventDefault();  // Полная остановка происходящего
 
    files = this.files;
 
    // Создадим данные формы и добавим в них данные файлов из files
 
    var data = new FormData();
    $.each( files, function( key, value )
    {
        data.append( key, value );
    });
 
    // Добавить id проекта
    data.append( 'id', id );

  startLoadingAnimation();
 
    // Отправляем запрос
  $.ajax({    
        url: '/project/MyJobs/UploadProjectFilesAJAX.php?uploadfiles',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function( respond, textStatus, jqXHR )
            {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
                var resp_val = respond['image_name'] ;
// Изменить данные иконки
                $('img.view_file[data-id=' + id + ']').attr({ 'data-image':resp_val }).removeClass('hidden');
                $('img.del_file[data-id=' + id + ']').attr({ 'data-image':resp_val }).removeClass('hidden');
                $('img.load_file[data-id=' + id + ']').addClass('hidden');                
                stopLoadingAnimation();
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
        },
        error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
        }
    });
  });
});


function view_image()
{
   event.stopPropagation();
   window.open( $(this).attr('data-image'),'_blank');
}


function delete_file()
{
  event.stopPropagation();

  var id = $( this ).attr('data-id');
  var img = $( this ).attr('data-image');
  startLoadingAnimation();

$.post(
  "project/MyJobs/DeleteImageAJAX.php",
  {
    proj_id   : id ,
    img : img 
  },
  function( data )
  { 
// Изменить данные иконки
    $('img.view_file[data-id=' + id + ']').addClass('hidden');
    $('img.del_file[data-id=' + id + ']').addClass('hidden');
    $('img.load_file[data-id=' + id + ']').removeClass('hidden');                
    stopLoadingAnimation();    
  }
  );
}
