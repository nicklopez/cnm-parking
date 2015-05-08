<?php
$title = "Manage Parking";
$headerTitle = "Manage locations & placards";
require_once("../php/lib/header.php");
require_once("../php/classes/location.php");
require_once("../php/classes/placardassignment.php");

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
		<a id="logout" href="//cnmparking.com/php/controllers/admin-logout.php" class="btn btn-primary navbar-btn pull-right">Log Out</a>
		<p id="welcome" class="navbar-text pull-right">Welcome back, <?php echo $_SESSION["adminFirstName"]; ?></p>
		<ul class="nav navbar-nav">
			<li role="presentation" class="active"><a class="navbar-brand" href="//cnmparking.com/php/portal-home/index.php">Home</a></li>
			<li role="presentation"><a href="//cnmparking.com/create-pass">Create Parking Pass</a></li>
			<li role="presentation"><a href="//cnmparking.com/send-invite">Manage Invites</a></li>
			<li role="presentation"><a href="//cnmparking.com/manage-parking">Manage Parking</a></li>
			<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Reports <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="//cnmparking.com/reports/vp.php">Visitor Parking Data</a></li>
					<li><a href="//cnmparking.com/reports/log.php">Parking Pass Log</a></li>
				</ul>
			</li>
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
				<button id="addSpotsButton" type="button" class="btn btn-primary" onclick="addSpots(document.getElementById('start').value, document.getElementById('end').value, document.getElementById('modalLocationId').value);">Add Placard(s)</button>
				<button id="deleteSpotsButton" value="delete" type="button" class="btn btn-primary" onclick="deleteSpots(document.getElementById('start').value, document.getElementById('end').value, document.getElementById('modalLocationId').value, this.value);">Delete Placard(s)</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="locationSpotModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Location & Placard(s)</h4>
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
				<button type="button" class="btn btn-primary" onclick="addLocationSpots(document.getElementById('locationName').value, document.getElementById('locationDescription').value, document.getElementById('locationSpotStart').value, document.getElementById('locationSpotEnd').value);">Save</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="placardAssignmentModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="placardAssignmentTitle">Placard Assignment</h4>
			</div>
			<div class="modal-body">
				<form id="placardAssignmentForm">
					<input hidden="hidden" type="text" id="assignId" name="assignId">
					<input hidden="hidden" type="text" id="locationId" name="locationId">
					<input hidden="hidden" type="date" id="returnDate" name="returnDate">
					<input hidden="hidden" type="text" id="adminProfileId" name="adminProfileId" value="<?php echo $_SESSION["adminProfileId"]; ?>">
					<div class="row container-fluid">
						<div class="form-group">
							<label for="firstName">First Name</label>
							<input class="form-control" type="text" id="firstName" name="firstName">
						</div>
						<div class="form-group">
							<label for="lastName">Last Name</label>
							<input class="form-control" type="text" id="lastName" name="lastName">
						</div>
					</div>
					<div class="row container-fluid">
						<div class="form-group col-xs-6">
							<label for="startDate">Start Date</label>
							<input class="form-control" type="date" id="startDate" name="startDate" placeholder="mm/dd/yyyy">
						</div>
						<div class="form-group col-xs-6">
							<label for="endDate">End Date</label>
							<input class="form-control" type="date" id="endDate" name="endDate" placeholder="mm/dd/yyyy">
						</div>
						<div class="form-group col-xs-6">
							<label for="placard">Placard</label>
							<p id="placardText"></p>
							<select class="form-control" id="availablePlacards" name="availablePlacards">
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10">
							<div class="form-group">
								<label>
									<input id="returned" type="checkbox"> Placard Returned?
								</label>
							</div>
						</div>
					</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<p id="placardAssignmentOutputArea"></p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="saveAssignmentButton" onclick="insertPlacardAssignment();">Save</button>
				<button type="button" class="btn btn-primary" id="updateAssignmentButton" onclick="updatePlacardAssignment();">Update</button>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="container-fluid">
	<table id="example" class="hover row-border" width="100%">
		<thead>
			<th>Location &nbsp; <span class="glyphicon glyphicon-plus-sign"></span>&nbsp;<a data-toggle="modal" data-target="#locationSpotModal">Add a Location</a></th>
			<th>Placard #</th>
			<th></th>
			<th>Placard Assigned To</th>
		</thead>
		<tbody>

			<?php
			foreach($objects as $object) {
				$locationId = $object["locationId"];
				$locationNote = $object["locationNote"];
				$locationDesc = $object["locationDescription"];
				$placard = $object["placardNumber"];
				$parkingSpotId = $object["parkingSpotId"];

				// query placard assignment details and throw results into table element below
				$placardAssignment = PlacardAssignment::getPlacardAssignmentByParkingSpotId($pdo, $parkingSpotId);
				$name = $placardAssignment["name"];
				$assignId = $placardAssignment["assignId"];

				if($placardAssignment["startDateTime"] !== null) {
					$startDate = DateTime::createFromFormat("Y-m-d H:i:s", $placardAssignment["startDateTime"]);
					$formattedStartDate = $startDate->format("m-d-Y");
					$endDate = DateTime::createFromFormat("Y-m-d H:i:s", $placardAssignment["endDateTime"]);
					$formattedEndDate = $endDate->format("m-d-Y");
					$assignedDates = $formattedStartDate . " thru " . $formattedEndDate . "&nbsp;&nbsp;&nbsp;<a id='updateAssignmentLink' data-toggle='modal' data-target='#placardAssignmentModal' data-assign-id='$assignId' data-spot-id='$parkingSpotId' data-location-id='$locationId'><span class='glyphicon glyphicon-pencil'></span></a>";
					$today = new DateTime();
					if($endDate < $today) {
						$name = "<span id='redFlag' class='glyphicon glyphicon-flag'></span>&nbsp;&nbsp;" . $name;
					}
				} else {
					$assignedDates = null;
				}
				$row = <<< EOF
		<tr><td width="25%"></td><td>$placard</td><td>$locationDesc - $locationNote&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-plus-sign"></span>&nbsp;<a id="addLink" data-toggle="modal" data-target="#myModal" onclick="document.getElementById('modalLocationId').value = $locationId">Add</a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-minus-sign"></span>&nbsp;<a id="deleteLink" data-toggle="modal" data-target="#myModal" onclick="document.getElementById('modalLocationId').value = $locationId">Delete</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<a id="addAssignmentLink" data-toggle="modal" data-target="#placardAssignmentModal" data-assignment="new" data-location-id='$locationId'>Assign a Placard</a>
		</td><td>$name &nbsp; - &nbsp; $assignedDates</td></tr>
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
