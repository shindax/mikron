var tips = document.getElementsByName('itrzadan_tips');
var intext, text = '';

for ( var idtip = 0; idtip < tips.length; idtip ++ )
{
  intext = tips[idtip].innerText ;
  switch( intext ) 
  {
    case '0': text = 'ÂÕ'   ; break ;
    case '1': text = 'ÈÑÕ'  ; break ;    
    case '2': text = 'ÏÐ'   ; break ;        
    case '9':
    case '5': text = ''     ; break ;
    default : text = intext ; break ;
  }
    tips[idtip].innerText = text ;
}

/*
var tips = document.getElementsByName('itrzadan_tips');
for (var idtip=0; idtip < tips.length; idtip++){
	if (tips[idtip].innerText == '0'){
		tips[idtip].innerText = 'ÂÕ';
	}
	if (tips[idtip].innerText == '1'){
		tips[idtip].innerText = 'ÈÑÕ';
	}
	if (tips[idtip].innerText == '2'){
		tips[idtip].innerText = 'ÏÐ';
	}
	if (tips[idtip].innerText == '5')
	{
		tips[idtip].innerText = '';
	}
	if (tips[idtip].innerText == '9')
	{
		tips[idtip].innerText = '';
	}
}
*/
