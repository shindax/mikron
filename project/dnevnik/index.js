			-function listen(){
			
			prepTabs = function (t){
				var itr = getUrlVars()['itr'];
				var arch = getUrlVars()['arch'];
				var itrtitl = document.getElementById('itrtitl');
				if (itr == '1' || !itr){
					if (!arch) {
						itrtitl.innerHTML = '������� ���';
					}
					if (arch == '1') {
						itrtitl.innerHTML = '������� ��� - �����';
					}
				}
				if (itr == '2'){
					if (!arch) {
						itrtitl.innerHTML = '������� �� ����';
					}
					if (arch == '2') {
						itrtitl.innerHTML = '������� �� ���� - �����';
					}
				}
				if (itr == '3'){
					if (!arch) {
						itrtitl.innerHTML = '�������� ����������';
					}
					if (arch == '3') {
						itrtitl.innerHTML = '�������� ���������� - �����';
					}
				}
				if (itr == '4'){
					if (!arch) {
						itrtitl.innerHTML = '�������������� ���������';
					}
					if (arch == '4') {
						itrtitl.innerHTML = '�������������� ��������� - �����';
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
