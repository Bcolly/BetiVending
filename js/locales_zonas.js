//var url = 'http://zurgaia.net/myvending/';
var url = 'http://localhost/betiV/';

function filtroRut() {
	var rut = document.getElementById("ruta").value;

	var res = ""
	if (rut != "") {
		res= res+"rut="+rut;
	}
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
			document.getElementById("tabla").innerHTML=xhttp.responseText;
		}
	};
	xhttp.open("GET",url+"users/methods/tabla_maquinas.php?js=js&"+res, true);
	xhttp.send();
}


function addZone(id){
  document.getElementById("mid").value=id;
	document.getElementById("formsruta2").style.display="none";
  document.getElementById("formsruta").style.display="inline";
}

function addZone2(rut){
	var seleccion = document.getElementById("sruta2");
	var str = cargaLocales(rut);
	var opciones = str.split("*/*");
	for (var i=0; i < opciones.length-1; i++) {
		var op = opciones[i].split("@*");
		var num = parseInt(op[0]);
		var txt = op[1];
		var option = document.createElement("option");
		option.value = num;
		option.text = txt;
		seleccion.add(option, i);
	}
	document.getElementById("formsruta").style.display="none";
  document.getElementById("formsruta2").style.display="inline";
}

function addZone3(mid, loc){
	xhttp = new XMLHttpRequest();
	xhttp.open("GET",url+"users/methods/locales_Ruta2.php?mid="+mid+"&loc="+loc, true);
	xhttp.send();
	document.getElementById("formsruta").style.display="none";
  document.getElementById("formsruta2").style.display="none";
	document.getElementById("formsrutab2").style.display="none";
	filtroRut();
}

function addPZone(rut){
	var seleccion = document.getElementById("srutab2");
	var str = cargaLocales(rut);
	var opciones = str.split("*/*");
	for (var i=0; i < opciones.length-1; i++) {
		var op = opciones[i].split("@*");
		var num = parseInt(op[0]);
		var txt = op[1];
		var option = document.createElement("option");
		option.value = num;
		option.text = txt;
		seleccion.add(option, i);
	}
  document.getElementById("formsrutab2").style.display="inline";
}

function cargaLocales(rut){
	var res = '';
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
			res = xhttp.responseText;
			return res;
		}
	};
	xhttp.open("GET",url+"users/methods/locales_Ruta.php?rut="+rut, false);
	xhttp.send();
	return res;
}

function quitarRuta(idmaquina){
	xhttp = new XMLHttpRequest();
	xhttp.open("GET",url+"users/methods/quitarRuta.php?id="+idmaquina, true);
	xhttp.send();
	filtroRut();
}

function cambiaRuta(){
	var idmaquina = document.getElementById('mid').value;
	var idruta = document.getElementById('sruta').value;
	xhttp = new XMLHttpRequest();
	xhttp.open("GET",url+"users/methods/cambiarRuta.php?mid="+idmaquina+"&rid="+idruta, true);
	xhttp.send();
	filtroRut();
}
