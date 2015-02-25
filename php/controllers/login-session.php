<?php
if (@isset($_POST["adminProfileId"]) === true) {
	$adminProfileId = filter_input(INPUT_POST, "adminProfileId", FILTER_VALIDATE_INT);
	if ($adminProfileId !== false) {
		session_start();
		$_SESSION["adminProfileId"] = $adminProfileId;
	}
}