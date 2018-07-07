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
	xhttp.open("GET",url+"users/methods/tabla_selecciones.php?"+res, true);
	xhttp.send();
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

function ocultar(id){
	$('input[type=checkbox]').each(function() {
		if ($(this).prop("id") != "ocultos") {
			res="id="+id+"&sel="+$(this).prop("id")+"&vis="+$(this).prop("checked");
			xhttp = new XMLHttpRequest();
			xhttp.open("GET",url+"users/methods/tabla_selecciones.php?"+res, true);
			xhttp.send();
		}
	});
	selfiltro(id);
}

function editar(){
	$('input[type=text]').each(function() {
		if ($(this).prop("disabled")) {
			$(this).prop("disabled", false);
		}
	});
	document.getElementById("grabar").style.display="inline";
	document.getElementById("borrar").style.display="inline";
	document.getElementById("cerrar").style.display="inline";
	document.getElementById("edit").style.display="none";
}

function grabar(){
	$('input[type=text]').each(function() {
		if ($(this).prop("disabled")) {
			alert("a")
		}
	});
}

function abrir(u) {
	window.open(u,'new_window','top=200,left=200,width=650,height=600');
}

function limpiar(id){
	xhttp = new XMLHttpRequest();
	xhttp.open("GET",url+"users/methods/clear.php?id="+id, false);
	xhttp.send();
	window.location.reload()
}

function vaciar(id, sel){
	xhttp = new XMLHttpRequest();
	xhttp.open("GET",url+"users/methods/vaciarseleccion.php?id="+id+"&sel="+sel, false);
	xhttp.send();
	window.location.reload()
}

function llenar(id, sel, cant) {
	xhttp = new XMLHttpRequest();
	xhttp.open("GET",url+"users/methods/llenarseleccion.php?id="+id+"&sel="+sel+"&cant="+cant, false);
	xhttp.send();
	window.location.reload()
}

function llenarAll(id) {
	xhttp = new XMLHttpRequest();
	xhttp.open("GET",url+"users/methods/llenarseleccion.php?id="+id+"&all=1", false);
	xhttp.send();
	window.location.reload()
}

function cambiarflag(id){
	xhttp = new XMLHttpRequest();
	xhttp.open("GET",url+"users/methods/solicitudenvio.php?id="+id, true);
	xhttp.send();
}

function addtomach(prod) {
	var mach = document.getElementById(prod).value;
	abrir('producttomachine.php?id='+mach+'&prod='+prod);
}

function mostrarprod(prod){
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
			document.getElementById("products").style.display='block';
			document.getElementById("products").innerHTML=xhttp.responseText;
		}
	};
	xhttp.open("GET",url+"users/methods/lista_prod.php?prod="+prod, true);
	xhttp.send();
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
