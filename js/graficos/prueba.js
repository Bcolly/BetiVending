
function drawGrafo() {
  var densityData = {
    label: 'Density of Planets (kg/m3)',
    data: [5427, 5243, 5514, 3933, 1326, 687, 1271, 1638]
  };

  //Chart.defaults.global.defaultFontFamily = "Lato";
  //Chart.defaults.global.defaultFontSize = 18;

  var ctx = document.getElementById("grafico").getContext("2d");
  var graficoGeneral = new Chart(ctx, {
      type: 'bar',
  	data: {
  		labels: ["Mercury", "Venus", "Earth", "Mars", "Jupiter", "Saturn", "Uranus", "Neptune"],
  		datasets: [densityData]
  	}
  });
}

function crearData() {
  var d = new Data();
  var N = diasDelMes(d.getMonth(), d.getFullYear());

  var data = {
    labels: Array.apply(null, {length: N}).map(Number.call, Number),
    datasets: [densityData]
  }
}

funtion crearDataSet(){
  var lista;

  var dataset = {
    label: '',
    data: lista;
  };
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
