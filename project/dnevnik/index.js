			-function listen(){
			
			prepTabs = function (t){
				var itr = getUrlVars()['itr'];
				var arch = getUrlVars()['arch'];
				var itrtitl = document.getElementById('itrtitl');
				if (itr == '1' || !itr){
					if (!arch) {
						itrtitl.innerHTML = 'Задания мне';
					}
					if (arch == '1') {
						itrtitl.innerHTML = 'Задания мне - архив';
					}
				}
				if (itr == '2'){
					if (!arch) {
						itrtitl.innerHTML = 'Задания от меня';
					}
					if (arch == '2') {
						itrtitl.innerHTML = 'Задания от меня - архив';
					}
				}
				if (itr == '3'){
					if (!arch) {
						itrtitl.innerHTML = 'Контроль выполнения';
					}
					if (arch == '3') {
						itrtitl.innerHTML = 'Контроль выполнения - архив';
					}
				}
				if (itr == '4'){
					if (!arch) {
						itrtitl.innerHTML = 'Информационные сообщения';
					}
					if (arch == '4') {
						itrtitl.innerHTML = 'Информационные сообщения - архив';
					}
				}
			}
			
			window.onload = prepTabs
			}()

			function getUrlVars() {
				var vars = {};
				var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
					vars[key] = value;
				});
				return vars;
			}	
