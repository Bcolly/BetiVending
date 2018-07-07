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

  //alert('drawing');
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
  //alert('creating data');
  var d = new Date();
  var N = diasDelMes(d.getMonth(), d.getFullYear());

  var label = [];
  for (i = 1; i<N; i++){
    label.push(i);
  }

  var dataset = crearDataSetMes(list);
  /*var dataset = {
    label: 'Density of Planets (kg/m3)',
    data: [5427, 5243, 5514, 3933, 1326, 687, 1271, 1638]
  };*/
  var data = {
    labels: label,
    datasets: [dataset]
  }
  return data;
}

function crearDataAño(list) {
  //alert('creating data');
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
  //alert('creating dataset');
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

/*$(document).ready(function(){

		var datos = {
			labels : ["Enero", "Febrero", "Marzo", "Abril", "Mayo"],
			datasets : [{
				label : "datos 1",
				backgroundColor : "rgba(220,220,220,0.5)",
				data : [4, 12, 9, 7, 5]
			},
			{
				label : "datos 2",
				backgroundColor : "rgba(151,187,205,0.5)",
				data : [10,7,1,6,5]
			},
			{
				label : "datos 3",
				backgroundColor : "rgba(151,100,205,0.5)",
				data : [9,6,15,6,17]
			}
			]
		};


		var canvas = document.getElementById('grafico').getContext('2d');
		window.bar = new Chart(canvas, {
			type : "bar",
			data : datos,
			options : {
				elements : {
					rectangle : {
						borderWidth : 0,
						borderColor : "rgb(0,0,0)",
						borderSkipped : 'bottom'
					}
				},
				responsive : true,
				title : {
					display : true,
					text : "Prueba de grafico de barras"
				}
			}
		});

		/*setInterval(function(){
			var newData = [
				[getRandom(),getRandom(),getRandom(),getRandom()*-1,getRandom()],
				[getRandom(),getRandom(),getRandom(),getRandom(),getRandom()],
				[getRandom(),getRandom(),getRandom(),getRandom(),getRandom()],
			];

			$.each(datos.datasets, function(i, dataset){
				dataset.data = newData[i];
			});
			window.bar.update();
		}, 5000);

		function getRandom(){
			return Math.round(Math.random() * 100);
		}
	});*/
