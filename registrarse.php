    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h2><?php echo __('Registration form', $lang) ?></h2><p><?php echo __('Fill in the access form to be able to use the free telemetry betiVENDING.', $lang) ?></p>
        <!--<p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>-->
      </div>
    </div>   
    <div class="container">
		<div id="error"></div>
		<form role="form" action="control.php" method="POST" 
		onsubmit="return comprobar(this.user.value, this.mail.value, this.psw1.value, this.psw2.value)">
			<div class="form-group">
				<label for="ejemplo_usuario_1"><?php echo __('Username', $lang) ?></label>
				<input type="text" class="form-control" id="ejemplo_usuario_1" name="user"
					   placeholder="<?php echo __('Enter your username', $lang) ?>"/>
			</div>
			<div class="form-group">
				<label for="ejemplo_email_1">Email</label>
				<input type="email" class="form-control" id="ejemplo_email_1" name="mail"
					   placeholder="<?php echo __('Enter your email', $lang) ?>"/>
			</div>
			<div class="form-group">
				<label for="ejemplo_password_1"><?php echo __('Password', $lang) ?></label>
				<input type="password" class="form-control" id="ejemplo_password_1" name="psw1" 
				placeholder="<?php echo __('Password', $lang) ?>"/>
			</div>
			<div class="form-group">
				<label for="ejemplo_password_2"><?php echo __('Repeat the password', $lang) ?></label>
				<input type="password" class="form-control" id="ejemplo_password_2" name="psw2"
				placeholder="<?php echo __('Password', $lang) ?>"/>
			</div>
			<button type="submit" class="btn btn-default" name="ACC" value="ENVIAR"><?php echo __('Send', $lang) ?></button>
		</form>