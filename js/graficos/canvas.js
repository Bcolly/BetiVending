var c = document.getElementById("myChart");
var ctx = c.getContext("2d");
	ctx.fillStyle = 'grey';
	ctx.fillRect(0, 0, 300, 300);

ctx.moveTo(0,0);
ctx.lineTo(300,100);
ctx.stroke();