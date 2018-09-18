//var url = 'http://zurgaia.net/myvending/';
var url = 'http://localhost/betiV/';

function filtro(ord) {
	var disp = document.getElementById("dispositivo").value;
	var zona = document.getElementById("zona").value;

	var res = ""
	if (disp != "") {
		res= res+"disp="+disp;
	}
	if (zona != "") {
		if (res != "") { res = res+"&"; }
		res = res+"zona="+zona;
	}
	if (ord !== undefined) {
		if (disp != "" || zona != "") { res = res+"&"; }
		res = res+"ordb="+ord;
	}
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
			document.getElementById("tabla").innerHTML=xhttp.responseText;
		}
	};
	xhttp.open("GET",url+"users/methods/tabla_dispositivos.php?js=js&"+res, true);
	xhttp.send();
}

function selfiltro(id){
	var sel = document.getElementById("seleccion").value;
	var prod = document.getElementById("producto").value;
	document.getElementById("seleccion").text = ""; //deberia vaciar los cuadros de texto
	document.getElementById("producto").value = "";
	var res = "id="+id;
	if (sel != "") {
		res= res+"&sel="+sel;
	}
	if (prod != "") {
		res = res+"&prod="+prod;
	}
	res = res+"&ocultos="+document.getElementById("ocultos").checked;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
			document.getElementById("tabla").innerHTML=xhttp.responseText;
		}
	};
	xhttp.open("GET",url+"users/methods/tabla_selecciones.php?js=js&"+res, true);
	xhttp.send();
}

function ocultar(id){
	$('input[type=checkbox]').each(function() {
		if ($(this).prop("id") != "ocultos") {
			res="id="+id+"&sel="+$(this).prop("id")+"&vis="+$(this).prop("checked");
			xhttp = new XMLHttpRequest();
			xhttp.open("GET",url+"users/methods/ocultar.php?"+res, true);
			xhttp.send();
		}
	});
	selfiltro(id);
}

function prodfiltro() {
	var prod = document.getElementById("producto").value;
	var fam = document.getElementById("familia").value;

	var res = ""
	if (prod != "") {
		res= res+"prod="+prod;
	}
	if (fam != "") {
		if (res != "") { res = res+"&"; }
		res = res+"fam="+fam;
	}
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
			document.getElementById("tabla").innerHTML=xhttp.responseText;
		}
	};
	xhttp.open("GET",url+"users/methods/tabla_productos.php?"+res, true);
	xhttp.send();
}

function filtrolocal(direccion, ruta) {
	var res = ""
	if (direccion != "") {
		res= res+"dir="+direccion;
	}
	if (ruta != "") {
		if (res != "") { res = res+"&"; }
		res = res+"ruta="+ruta;
	}
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
			document.getElementById("tabla").innerHTML=xhttp.responseText;
		}
	};
	xhttp.open("GET",url+"users/methods/tabla_locales.php?js=js&"+res, true);
	xhttp.send();
}

function filtrobajadisp() {
	var d = document.getElementById("dispositivo").value;

	var res = ""
	if (d != "") {
		res= res+"disp="+d;
	}
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
			document.getElementById("tabladis").innerHTML=xhttp.responseText;
		}
	};
	xhttp.open("GET",url+"users/methods/baja_dispositivos.php?js=js&"+res, true);
	xhttp.send();
}

function filtrobajamaq() {
	var m = document.getElementById("maquina").value;

	var res = ""
	if (m != "") {
		res= res+"maq="+m;
	}
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
			document.getElementById("tablamaq").innerHTML=xhttp.responseText;
		}
	};
	xhttp.open("GET",url+"users/methods/baja_maquinas.php?js=js&"+res, true);
	xhttp.send();
}
