<?php
// require header variables
$title = "Admin Registration";
$headerTitle = "CNM Parking Admin Registration";
require_once("../php/lib/header.php");
require_once("../php/lib/csrf.php");

// start session
session_start();

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-registration" method="post" action="../php/controllers/admin-registration-post.php" novalidate="novalidate">
				<?php echo generateInputTags(); ?>
				<div class="form-group">
					<label for="adminFirstName">First Name:</label>
					<input type="text" class="form-control name" id="adminFirstName" name="adminFirstName" size="128" maxlength="128" placeholder="First Name"><br>
				</div>
				<div class="form-group">
					<label for="AdminLastName">Last Name:</label>
					<input type="text" class="form-control name" id="adminLastName" name="adminLastName" size="128" maxlength="128" placeholder="Last Name"><br>
				</div>
				<div class="form-group">
					<label for="adminEmail">Email:</label>
					<input type="text" class="form-control email" id="adminEmail" name="adminEmail" size="128" maxlength="128" placeholder="name@example.com"><br>
				</div>
				<div class="form-group">
					<label for="password">Password:</label>
					<input type="password" class="form-control name" id="password" name="password" size="128" maxlength="128" placeholder="********"><br>
				</div>
<!--				<div class="form-group">-->
<!--					<label for="cpassword">Confirm Password:</label>-->
<!--					<input type="password" class="form-control name" id="cpassword" name="cpassword" size="128" maxlength="128" placeholder="********"><br>-->
<!--				</div>-->
				<button id="register" class="btn btn-primary" type="submit">Register</button>
			</form>
		</div>
	</div>
</div>
	<p id="outputArea"></p>

<?php
require_once("../php/lib/footer.php");
?>