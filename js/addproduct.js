function mostrar(prod){
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if ((xhttp.readyState==4)&&(xhttp.status==200)){ 
			document.getElementById("products").style.display='block';
			document.getElementById("products").innerHTML=xhttp.responseText;
		}
	};
	xhttp.open("GET","http://zurgaia.net/myvending/users/methods/lista_prod.php?prod="+prod, true);
	xhttp.send();
}