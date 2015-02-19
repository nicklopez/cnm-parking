<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" />

		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/visitor-search.js"></script>

		<title>Visitor Search</title>
	</head>
	<body>
		<form id="visitorSearchForm" method="post" action="../php/controllers/visitor-search-meta.php">
			<div class="form-group">
				<label class="control-label">Search By</label>
				<label class="radio-inline">
					<input type="radio" name="visitorSearchOptions" id="radioVisitorSearchByName" value="name" checked/>Name
				</label>
				<label class="radio-inline">
					<input type="radio" name="visitorSearchOptions" id="radioVisitorSearchByEmail" value="email"/>Email Address
				</label>
				<label class="radio-inline">
					<input type="radio" name="visitorSearchOptions" id="radioVisitorSearchByPlate" value="plate"/>License Plate Number
				</label>
			</div>
			<div class="form-group">
				<input class="form-control" type="text" id="textVisitorSearchInput" name="textVisitorSearchInput" maxlength="270" placeholder="Search Criteria"/>
			</div>
			<button id="visitorSearchSubmit" type="submit">Search</button>
		</form>
		<div id="outputArea"></div>
	</body>
</html>
