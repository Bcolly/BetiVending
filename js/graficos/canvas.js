var grafico;

function cargarGrafoMaq() {
  var vista = document.getElementById("vista").value;
  var maquina = document.getElementById("maquinas").value;
  if (vista == 'ventas')
    var tit = 'Total sales of the machine this month';
  else
    var tit = 'Total earnings of the machine this month';

  xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
      clear();
			drawGrafo(JSON.parse(xhttp.responseText), 'mes', tit);
		}
	};
	//esto hay que cambiarlo en el host
	//xhttp.open("GET","http://zurgaia.net/myvending/users/methods/estadisticas_lg.php?maq="+maquina+"&vista="+vista, true);
	xhttp.open("GET","http://localhost/betiV/users/methods/estadisticas_lg.php?maq="+maquina+"&vista="+vista, true);
	xhttp.send();
}

function cargarGrafoProd() {
  var vista = document.getElementById("x").value;
  var maquina = document.getElementById("maquinas").value;
  if (vista == 'ventas')
    var tit = 'Total sales of the machine this month';
  else
    var tit = 'Total earnings of the machine this month';

  xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){
      clear();
			drawGrafo(JSON.parse(xhttp.responseText), 'mes', tit);
		}
	};
	//esto hay que cambiarlo en el host
	//xhttp.open("GET","http://zurgaia.net/myvending/users/methods/estadisticas_lg.php?maq="+maquina+"&vista="+vista, true);
	xhttp.open("GET","http://localhost/betiV/users/methods/estadisticas_lg.php?maq="+maquina+"&vista="+vista, true);
	xhttp.send();
}

function drawGrafo(list, esc, tit) {
  var ctx = document.getElementById("grafico").getContext("2d");

  var datos;
  if (esc == 'mes') datos = crearDataMes(list);
  else datos = crearDataAño(list);

  grafico = new Chart(ctx, {
    type: 'bar',
  	data: datos,
    options : {
      responsive : true,
      title : {
        display : true,
        text : tit
      }
  }
  });
}

function crearDataMes(list) {
  var d = new Date();
  var N = diasDelMes(d.getMonth(), d.getFullYear());

  var label = [];
  for (i = 1; i<N; i++){
    label.push(i);
  }

  var dataset = crearDataSetMes(list);
  var data = {
    labels: label,
    datasets: [dataset]
  }
  return data;
}

function crearDataAño(list) {
  var d = new Date();
  var label = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

  var dataset = crearDataSetAño(list);
  var data = {
    labels: label,
    datasets: [dataset]
  }
  return data;
}

function crearDataSetMes(l){
  var aux = JSON.stringify(l);
  aux = aux.substring(1, aux.length-1);
  var lista = aux.split(",");
  var datasetdata = [];
  var antv = 0;

  for (i=0; i<lista.length; i++) {
    var d = lista[i].split(":");
    var date = d[0].substring(1, d[0].length-1);
    var day = parseInt(date.substring(8));
    var vent = parseInt(d[1].substring(1, d[1].length-1));
    var cant = vent - antv;
    antv = vent;
    datasetdata[day-1] = cant;
  }

  var dataset = {
    label: '',
    data: datasetdata,
    backgroundColor: 'rgba(99, 132, 0, 0.6)'
  };
  return dataset;
}

function crearDataSetAño(l){
  //alert('creating dataset');
  var aux = JSON.stringify(l);

  for (i=0; i<lista.length; i++) {
    //TO_DO
  }

  var dataset = {
    label: '',
    data: datasetdata
  };
  return dataset;
}

function clear() {
  var gr = document.getElementById("grafico");
  var ctx = gr.getContext("2d");
  grafico.destroy();
}

function diasDelMes(month, year) {
  return new Date(year || new Date().getFullYear(), month, 0).getDate();
}
