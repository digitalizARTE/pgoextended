Validation.get('IsEmpty').error = 'Ha fracasado la validaci�n .';
Validation.get('required').error = 'Este es un campo obligatorio.';
Validation.get('validate-number').error = 'Por favor, introduzca un n�mero v�lido en este campo.';
Validation.get('validate-digits').error = 'Por favor, utilice s�lo n�meros en este campo, evite espacios u otros caracteres, tales como puntos o comas.';
Validation.get('validate-alpha').error = 'Por favor, utilice s�lo letras (A-Z) en este campo.';
Validation.get('validate-alphanum').error = 'Por favor, utilice s�lo letras (A-Z) o n�meros (0-9) en este campo. No est�n permitidos espacios u otros caracteres.';
Validation.get('validate-date').error = 'Por favor, introduzca una fecha v�lida.';
Validation.get('validate-email').error = 'Por favor, introduzca una direcci�n de correo electr�nico v�lida. Por ejemplo fred@domain.com.';
Validation.get('validate-date-au').error = 'Por favor, use este formato de fecha: dd/mm/aaaa. Por ejemplo 17/03/2006 para el 17 de marzo del 2006.';
Validation.get('validate-currency-dollar').error = 'Por favor, introduzca un importe de $ v�lido. Por ejemplo: $ 100,00.';
Validation.get('validate-selection').error = 'Por favor haga una selecci�n';
Validation.get('validate-one-required').error = 'Por favor, seleccione una de las opciones anteriores.';
Validation.add('validate-datetime', 'Por favor, introduzca una fecha y hora v�lida.', function(v) {                
                if(Validation.get('validate-date-au').test(v)) return true;
				//var regex = /(0?[1-9]|[12][0-9]|3[01])[\- \/.](0?[1-9]|1[012])[\- \/.](18|19|20|21)?[0-9]{2}\s{1}(0?[1-9]|1[1-9]|2[0-3]):([0-5][1-9]):([012345][1-9])/;
                var regex = /^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})$/;
				if(!regex.test(v)) return false;
				var d = new Date(v.replace(regex, '$2/$1/$3 $4:$5:$6'));
				return ( parseInt(RegExp.$2, 10) == (1+d.getMonth()) ) && 
							(parseInt(RegExp.$1, 10) == d.getDate()) && 
							(parseInt(RegExp.$3, 10) == d.getFullYear() ) && 
							(parseInt(RegExp.$4, 10) == d.getHours() ) && 
							(parseInt(RegExp.$5, 10) == d.getMinutes() ) && 
							(parseInt(RegExp.$6, 10) == d.getSeconds() );
			});