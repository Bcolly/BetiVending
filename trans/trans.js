function carga(){
	var to = document.getElementById("to").value;
	var de = document.getElementById("from").value;
	if (to.length < 15) {
		document.getElementById('new').style.display = 'block';
	}
	else {
		document.getElementById('new').style.display = 'none';
	}
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){ 
			document.getElementById("lines").innerHTML=xhttp.responseText;
		}
	};
	//esto hay que cambiarlo en el host
	xhttp.open("GET","http://zurgaia.net/myvending/trans/lista.php?len="+to+"&from="+de, true);
	xhttp.send();
}

function nueva() {
	document.getElementById("lines").innerHTML = document.getElementById("lines").innerHTML
	+"<div class='row'>"
		+"<div class='col col-md-4'>"
			+"<input type='text' value='' name='from'/>"
		+"</div>"
		+"<div class='flecha col col-md-3 align-self-end'><h3>=></h3></div>"
		+"<div class='col col-md-4'>"
			+"<input type='text' value='' name='to'/>"
		+"</div>"
	+"</div>";
}

function save() {
	var lan = document.getElementById("to").value;
	var fromlan = document.getElementById("from").value;
	var fromnl = document.getElementsByName("from");
	
	var fromarr = [];
	var tonl = document.getElementsByName("to");
	var toarr = [];
	for (i = 0; i < fromnl.length; i++){
		fromarr.push(fromnl[i].value);
		toarr.push(tonl[i].value);
	}
	if (fromarr.length > 0) {
		xhttp = new XMLHttpRequest();
		/*xhttp.onreadystatechange = function(){
			if ((xhttp.readyState==4)&&(xhttp.status==200)){ 
				alert(xhttp.responseText);
				document.getElementById("message").innerHTML=xhttp.responseText;
			}
		};*/
		xhttp.open("GET","http://zurgaia.net/myvending/trans/save.php?from="+serialize(fromarr)+"&lan="+lan+"&to="+serialize(toarr)+"&fromlang="+fromlan, true);
		xhttp.send();
	}
}

function serialize(arr)
{
	var res = 'a:'+arr.length+':{';
	for(i=0; i<arr.length; i++){
	res += 'i:'+i+';s:'+arr[i].length+':"'+arr[i]+'";';
	}
	res += '}';
	 
	return res;
}

function newlan(lan){
	xhttp = new XMLHttpRequest();
	alert("a2");
	xhttp.onreadystatechange = function(){
		alert(xhttp.readyState+"/"+xhttp.status);
		if ((xhttp.readyState==4)&&(xhttp.status==200)){ 
			alert(xhttp.responseText);
		}
	};
	xhttp.open("GET","http://zurgaia.net/myvending/trans/newlan.php?lan="+lan, true);
	xhttp.send();
}