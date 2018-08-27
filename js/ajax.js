//var url = 'http://zurgaia.net/myvending/';
var url = 'http://localhost/betiV/';

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

function abrir(u, width = 650, height = 600) {
	window.open(u,'new_window','top=200,left=200,width='+width+',height='+height);
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

function bajadis(dis) {
		document.getElementById("id").value = dis;
		document.getElementById("md").value = 'd';
}

function bajamaq(maq) {
		document.getElementById("id").value = maq;
		document.getElementById("md").value = 'm';
}
