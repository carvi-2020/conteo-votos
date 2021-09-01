function actualizar(idcampo, idreg, url) {
	irA(idcampo, idreg, url, 'UPD');
}

function insertar(idcampo, idreg, url) {
	irA(idcampo, idreg, url, 'INS');
}

function eliminar(idcampo, idreg, url) {
	irA(idcampo, idreg, url, 'DEL');
}

function eliminarConfirm(idcampo, idreg, url) {
	if(confirm('Esta seguro que desea borrar el registro seleccionado?'))
		irA(idcampo, idreg, url, 'DEL');
	else
		return false;
}

function addCarrito(idcampo1, idval1, url) {
	var cantidadField = "cantidad" + idval1;
	document.forms[0].action = url;	
	document.getElementById(idcampo1).value = idval1;
	document.getElementById('cantidad').value = document.getElementById(cantidadField).value;
	document.getElementById('accion').value = 'UPD';
	document.forms[0].submit();	
}

function irA(idcampo, idreg, url, accion) {
	document.forms[0].action = url;	
	document.getElementById(idcampo).value = idreg;
	document.getElementById('accion').value = accion;
	document.forms[0].submit();
}

function addAccionForm(accionUrl) {
	
	document.forms[0].action += accionUrl;
	return true;
}

function setAccionForm(accionUrl) {
	document.forms[0].setAttribute('target', '_self');
	document.forms[0].action = accionUrl;
	return true;
}

function addAccionFormConfirm(accionUrl, accionMsg) {
	if(confirm('Esta seguro que desea ' + accionMsg + '?')) {
		document.forms[0].action = accionUrl;
		return true;
	} else return false;
}

function displayLOV(urlLOV, idLOV, targets, jsFunction, ancho, alto ) {
	var anchoLOV = 500;
	var altoLOV = 600;
	var objs = '';
	if(ancho != null)
		anchoLOV = ancho;
		
	var jsFun = '';
	if(jsFunction != null)
		jsFun = '&jsFunction='+jsFunction;
		
	if(alto != null)
		altoLOV = alto;
		
	for(cnt = 0; cnt < targets.length; cnt++) {
		objs += targets[cnt] + '|';
	}
	window.open(urlLOV + '?objs='+objs+'&formu='+document.forms[0].id+jsFun, 'LOV' + idLOV, 'status=1,location=0,toolbar=0,menubar=0,height='+altoLOV+',width='+anchoLOV);
}

function setLOVValues(objs, valores, closeWin) {
	var arrObjs = objs.split('|');
	var mensi = '';
	for(cnt = 0; cnt < arrObjs.length; cnt++) {
		mensi += arrObjs[cnt] + '\n';
		if(arrObjs[cnt] != null || arrObjs[cnt] != '') {
			if(window.opener.document.getElementById(arrObjs[cnt]) != null) {
				mensi += valores[cnt] + '\n';
				window.opener.document.getElementById(arrObjs[cnt]).value=valores[cnt];
			}	
		}	
	}
	
}

function isDecimalRestrict(evt, elemento, maxDecimals)
{
    try {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
            return false;
        }
        else {
            //Validamos si no esta excediendo la cantidad de decimales especificada
            if(elemento.value.indexOf('.') != -1) {
                var valor = elemento.value;
                var currdecimals = valor.substring(valor.indexOf('.') + 1, valor.length);
                //Validamos que no hayan mas decimales que los permitidos cuando quiera escribir decimales,
                //es decir que el cursor este despues del punto decimal
                if(currdecimals.length + 1 > maxDecimals && charCode != 8 && caretPosition(elemento) > valor.indexOf('.'))
                    return false;
                //Validaciones respecto al punto decimal
                if(charCode == 46 && valor.indexOf('.') != -1) { //Si ya tenia un punto decimal, no permitir otro
                    return false;
                }
            }
        }
    }catch(e){}
    return true;
}

function restrictNumDigits(evt, elemento, maxDigits) {
    try {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57) ) {
            return false;
        }
        else {
            var valor = elemento.value;
            var currdigits = valor.substring(0, valor.length);
            //Validamos que no hayan mas decimales que los permitidos cuando quiera escribir decimales,
            //es decir que el cursor este despues del punto decimal
            if(currdigits.length + 1 > maxDigits && charCode != 8)
                return false;
        }
    }catch(e){}
    return true;
}

function verifyDecimalDot(elemento) {
if(elemento.value.length -1 == elemento.value.indexOf('.'))
	elemento.value += '0';
}

function restrictNumDigitsDecimals(evt, elemento, maxDigits, maxDecimals)
{
    var arrCaret = caretPosition(elemento);
    try {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
            return false;
        }
        else {
            //Validamos si no esta excediendo la cantidad de decimales especificada
            if( (parseInt(arrCaret[1]) - parseInt(arrCaret[0])) == parseInt(arrCaret[2]) )
                return true;
            if(elemento.value.indexOf('.') != -1) {
                var valor = elemento.value;
                var currdecimals = valor.substring(valor.indexOf('.') + 1, valor.length);
                var currdigits = 0;
                if(valor.indexOf('.') != -1)
                    currdigits = valor.substring(0, valor.indexOf('.'));
                else
                    currdigits = valor.substring(0, valor.length);
                //Validamos que no hayan mas decimales que los permitidos cuando quiera escribir decimales,
                //es decir que el cursor este despues del punto decimal
                if(currdigits.length + 1 > maxDigits && charCode != 8 &&
                        (arrCaret[1] <= valor.indexOf('.') || valor.indexOf('.') == -1))
                    return false;
                if(currdecimals.length + 1 > maxDecimals && charCode != 8 && arrCaret[1] > valor.indexOf('.'))
                    return false;
                //Validaciones respecto al punto decimal
                if(charCode == 46 && valor.indexOf('.') != -1) { //Si ya tenia un punto decimal, no permitir otro
                    return false;
                }
            }
            else {
                currdigits = elemento.value.substring(0, elemento.value.length);
                if(currdigits.length + 1 > maxDigits && charCode != 8 && charCode != 46)
                    return false;
            }
        }
    }catch(e){}
    return true;
}

function caretPosition(elemento)
{
        var campo = elemento;
        if (document.selection) {// IE Support
                campo.focus();                                        // Set focus on the element
                var oSel = document.selection.createRange();        // To get cursor position, get empty selection range
                oSel.moveStart('character', -campo.value.length);    // Move selection start to 0 position
                campo.selectionEnd = oSel.text.length;
                oSel.setEndPoint('EndToStart', document.selection.createRange() );
                campo.selectionStart = oSel.text.length; // The caret position is selection length
        }
        var arrRet = new Array(3);
        arrRet[0] = campo.selectionStart;
        arrRet[1] = campo.selectionEnd;
        arrRet[2] = elemento.value.length;
        return arrRet;
}
