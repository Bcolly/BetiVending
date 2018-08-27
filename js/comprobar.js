var pmail = /^(\w+)@(\D+)\.(\D+)$/;

function comprobar(user, mail, psw1, psw2){
		var bien = true;
		var error = "<div class='alert alert-danger'>";
		if(user.length < 1){
			bien = false;
			//error +="<?php echo __('Username: is empty', $lang) ?><br/>";
			error +="Username: is empty<br/>";
		}
		if((mail.length < 1)){
			bien = false;
			//error +="<?php echo __('Email: is empty', $lang) ?><br/>";
			error +="Email: is empty<br/>";
		}
		else if(!pmail.test(mail)){
			bien = false;
			//error +="<?php echo __('Email: is not a valid email', $lang) ?><br/>";
			error +="Email: is not a valid email<br/>";
		}
		if(psw1.length < 5){
			bien = false;
			//error +="<?php echo __('Password: too short a minimum of 6 characters is required', $lang) ?><br/>";
			error +="Password: too short a minimum of 6 characters is required<br/>";
		}
		if(psw2.localeCompare(psw1) != 0){
			bien = false;
			//error +="<?php echo __('Password: the passwords are not equals', $lang) ?>";
			error +="Password: the passwords are not equals";
		}
		if (!bien) {
			error +="</div>";
			document.getElementById("error").innerHTML=error;
		} else {
			document.getElementById("error").innerHTML="";
		}
		alert(error);
		return bien;
}
