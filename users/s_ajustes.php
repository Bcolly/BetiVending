<script>
	function pascompare(n, rn){
		var patt = new RegExp(".{6,}");
    if (patt.test(n) && patt.test(rn))
			return n.localeCompare(rn) == 0;
		else
			return false;
	}
</script>

<?php require ("s_index.php");

if (isset($_POST["actual"])) {
	$con = 1;
	$basededatos=conectardb();

	$actual = sha1($_POST["actual"]);
	$sql = query("SELECT *
		FROM v_user
		WHERE id = $userid and passwd = '$actual';", $basededatos, $con);
	$con++;

	if ($sql->rowCount() > 0) {
		if (strcmp ($_POST["n"] , $_POST["rn"]) == 0){
			$passcript = sha1($_POST["n"]);
			execute("UPDATE v_user SET passwd = '$passcript' WHERE id = $userid", $basededatos, $con);

			echo "<div class='alert alert-success'>";
			echo __('Your password has been changed', $lang, '../');
			echo "</div>";
		} else {
			echo "<div class='alert alert-danger'>";
			echo __('The new password are not equals', $lang, '../');
			echo "</div>";
		}
	} else {
		echo "<div class='alert alert-danger'>";
		echo __('Your password is incorrect', $lang, '../');
		echo "</div>";
	}
	$con++;
}

?>
	<div class="container">
		<div class="row">
			<div class="col col-md-5">
				<h3><?php echo __('Change yor password', $lang, '../') ?></h3>
				<form class="navbar-form navbar-left" role="form" method="POST" onsubmit="return pascompare(n.value, rn.value);">
					<div class="form-group">
						<label for="actual"><?php echo __('Password', $lang, '../') ?>: </label>
						<input type="password" class="form-control" id="actual" name="actual" required>
					</div>
					<br/><br/>
					<div class="form-group">
						<label for="n"><?php echo __('New password', $lang, '../') ?>: </label>
						<input type="password" class="form-control" id="n" name="n" pattern=".{6,}" required>
					</div>
					<div class="form-group">
						<label for="rn"><?php echo __('Repeat new password', $lang, '../') ?>: </label>
						<input type="password" class="form-control" id="rn" name="rn" pattern=".{6,}" required>
					</div>
					<br/>
					<input type="submit" class="btn btn-primary" value="<?php echo __('CHANGE', $lang, '../') ?>" />
				</form>
			</div>
		</div>
	</div>
<?php require ("s_footer.php"); ?>
