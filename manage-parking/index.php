<?php
$title = "Manage Parking";
$headerTitle = "Manage locations & parking spots";
require_once("../php/lib/header.php");
require_once("../php/classes/location.php");
require_once("../php/classes/parkingspot.php");

// start a PHP session
session_start();

// assign url to session variable
$_SESSION['url'] = $_SERVER['REQUEST_URI'];

// check for active session
if(!isset($_SESSION["adminProfileId"])) {
	header("location: ../admin-login/index.php");
}
?>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<a id="logout" href="../php/controllers/admin-logout.php" class="btn btn-primary navbar-btn pull-right">Log Out</a>
		<p id="welcome" class="navbar-text pull-right">Welcome back, <?php echo $_SESSION["adminFirstName"]; ?></p>
		<ul class="nav navbar-nav">
			<li role="presentation" class="active"><a class="navbar-brand" href="../php/test-portal/test-portal.php">Home</a></li>
			<li role="presentation"><a href="../create-pass">Create Parking Pass</a></li>
			<li role="presentation"><a href="../send-invite">Manage Invites</a></li>
			<li role="presentation"><a href="../manage-parking">Manage Parking</a></li>
			<li role="presentation"><a href="../reports">Reports</a></li>
		</ul>
	</div>
</nav>

<?php
// require the encrypted configuration functions
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");

try {
// now retrieve the configuration parameters
$configFile = "/home/cnmparki/etc/mysql/cnmparking.ini";
$configArray = readConfig($configFile);

// first, connect to mySQL
$host = $configArray["hostname"];
$db = $configArray["database"];
$dsn = "mysql:host=$host;dbname=$db";
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$objects = Location::getAllLocationsAndParkingSpots($pdo);

?>

<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="title"></h4>
			</div>
			<div class="modal-body">
				<div class="row container-fluid">
					<div class="col-xs-6">
						<label for="start">Placard Start #</label>
						<input class="form-control" type="text" id="start" name="start">
					</div>
					<div class="col-xs-6">
						<label for="end">Placard End #</label>
						<input class="form-control" type="text" id="end" name="end">
						<input type="text" hidden="hidden" id="modalLocationId" name="modalLocationId">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<p id="outputArea"></p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button id="addSpotsButton" type="button" class="btn btn-primary" onclick="addSpots(document.getElementById('start').value, document.getElementById('end').value, document.getElementById('modalLocationId').value);">Add Spots</button>
				<button id="deleteSpotsButton" value="delete" type="button" class="btn btn-primary" onclick="deleteSpots(document.getElementById('start').value, document.getElementById('end').value, document.getElementById('modalLocationId').value, this.value);">Delete Spots</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="locationSpotModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="title">Add Location & Spot(s)</h4>
			</div>
			<div class="modal-body">
				<div class="row container-fluid">
					<div class="form-group">
						<label for="locationName">Location Name</label>
						<input class="form-control" type="text" id="locationName" name="locationName">
					</div>
					<div class="form-group">
						<label for="locationDescription">Location Description</label>
						<input class="form-control" type="text" id="locationDescription" name="locationDescription">
					</div>
				</div>
				<div class="row container-fluid">
					<div class="col-xs-6">
						<label for="start">Placard Start #</label>
						<input class="form-control" type="text" id="locationSpotStart" name="locationSpotStart">
					</div>
					<div class="col-xs-6">
						<label for="end">Placard End #</label>
						<input class="form-control" type="text" id="locationSpotEnd" name="locationSpotEnd">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<p id="LocationSpotOutputArea"></p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="addLocationSpots(document.getElementById('locationName').value, document.getElementById('locationDescription').value, document.getElementById('locationSpotStart').value, document.getElementById('locationSpotEnd').value);">Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="container-fluid">
	<table id="example" class="hover row-border" width="100%">
		<thead>
			<th>Location &nbsp; <a data-toggle="modal" data-target="#locationSpotModal"><span class="glyphicon glyphicon-plus"></span>Add a Location</a></th>
			<th>Placard#</th>
			<th></th>
		</thead>
		<tbody>

			<?php
			foreach($objects as $object) {
				$locationId = $object["locationId"];
				$locationNote = $object["locationNote"];
				$locationDesc = $object["locationDescription"];
				$placard = $object["placardNumber"];
				$row = <<< EOF
		<tr><td width="25%"></td><td>$placard</td><td>$locationDesc - $locationNote&nbsp;&nbsp;&nbsp;<a id="addLink" cursor data-toggle="modal" data-target="#myModal" onclick="document.getElementById('modalLocationId').value = $locationId"><span class="glyphicon">+</span>Add Spots</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="deleteLink" data-toggle="modal" data-target="#myModal" onclick="document.getElementById('modalLocationId').value = $locationId"><span class="glyphicon glyphicon-minus"></span>Delete Spots</a></td></tr>
EOF;
				echo $row;
			}
			echo "</tbody>";
			echo "</table>";
			echo "</div>";

			} catch(Exception $exception) {
				echo "<td><tr class=\"alert alert-danger\" colspan=\"3\">Exception: " . $exception->getMessage() . "</td></tr>";
			}

			require_once("../php/lib/footer.php");
			?>
