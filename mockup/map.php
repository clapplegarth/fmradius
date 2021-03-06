<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>fmradi.us - Map FM Radio Stations </title>
	<meta name="description" content="A map of FM radio stations in the US showing approximate coverage, station info and transmitter stats.">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--<link href="css/normalize.css" rel="stylesheet" media="all">-->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" media="all">
	<link href="css/styles.css" rel="stylesheet" media="all">
	<!--[if lt IE 9]><script src="js/html5shiv-printshiv.js" media="all"></script><![endif]-->
</head>
<body id="map-body">
	<div id="map">
		<h2>Locating station...<br />If this message persists, <a href="index.html">go back and try again</a>.</h2>
	</div>

	<div id="searchbox" class="col-lg-3" tabindex="-1" role="search">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<form class="" action="map.php" method="GET">
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Call sign..." name="search" id="search"></input>
							<span class="input-group-btn">
								<button type="submit" class="btn btn-success">
									<span class="glyphicon glyphicon-search" aria-label="Search" name="search-submit" id="search-submit"></span>
								</button>
							</span>
						</div>
					</div>
				</form>
			</div>

			<?php include("map_getdata.php"); ?>

			<div class="databar panel-footer">
				<div class="databar">
					<h2 class="callsign"><?php echo htmlentities($_GET["search"]); ?> </h2>
					<table class="table table-hover">
						<thead>
							<tr>
								<th colspan="2"><span class="glyphicon glyphicon-info-sign"></span>&ensp;Station Info</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Website</td>
								<td><a href="#">Not available</a></td>
							</tr>
							<tr>
								<td>Slogan</td>
								<td><i>Not available</i></td>
							</tr>
						</tbody>
					</table>
					<table class="table table-hover">
						<thead>
							<tr>
								<th colspan="2"><span class="glyphicon glyphicon-headphones"></span>&ensp;Streams</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>M3U Stream</td>
								<td><i>Not available</i></td>
							</tr>
							<tr>
								<td>Third Party Streams</td>
								<td><a href="#">Not available</a>
								<br /><a href="#">Not available</a></td>
							</tr>
							<tr>
								<td>Station Website</td>
								<td><a href="http://wceiradio.com/">Not available</a></td>
							</tr>
						</tbody>
					</table>
						<div id="morebutton">
							<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#moreStationInfo" aria-expanded="false" aria-controls="moreStationInfo" id="moreInfoButton" onclick='document.getElementById("moreInfoButton").style.display = "none";'>
							  More Info
							</button>
						</div>
						<div class="collapse" id="moreStationInfo">
						<table class="table table-hover">
							<thead>
								<tr>
									<th colspan="2"><span class="glyphicon glyphicon-equalizer"></span>&ensp;Transmitter Data</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><a href="https://www.fcc.gov/radiofm">Frequency</a></a></td>
									<td><strong>Not available</strong> <span class="glyphicon glyphicon-search"></span></td>
								</tr>
								<tr>
									<td><a href="https://en.wikipedia.org/wiki/Power_rating">Power</a></td>
									<td>Not available</td>
								</tr>
								<tr>
									<td><acronym title="Height Above Average Terrain"><a href="https://en.wikipedia.org/wiki/Height_above_average_terrain">HAAT</a></acronym></td>
									<td>Not available</td>
								</tr>
								<tr>
									<td>Approximate Radius</td>
									<td>Not available</td>
								</tr>
								<tr>
									<td><a href="https://www.fcc.gov/encyclopedia/fm-broadcast-station-classes-and-service-contours">Class</a></a></td>
									<td>Not available</td>
								</tr>
							</tbody>
						</table>


						<table class="table table-hover">
							<thead>
								<tr>
									<th colspan="2"><span class="glyphicon glyphicon-globe"></span>&ensp;Location Data</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Coordinates</td>
									<td><code><?php if ($station) { echo $station['locus_lat'] . ", " . $station['locus_lng']; } ?></code></td>
								</tr>
								<tr>
									<td>More Maps</td>
									<td><a href="#">Google Maps</a>
										<br /><a href="#">Not available</a></td>
								</tr>
								<tr>
									<td>Advanced Spatial</td>
									<td><a href="#">Not available</a>
										<br /><a href="#"><tt>geo://</tt> URI</a></td>
								</tr>
							</tbody>
						</table>
					</div>
			</div>
			</div>

		</div>
	</div>

<script type="text/javascript">
<?php
if ($station)
{
	echo("var callsign = '{$station['call_sign']}'; \r\n");
	echo("var latlng = {$station['locus_json']}; \r\n");
	echo("var polyjson = {$station['poly_json']}; \r\n");
}
?>
</script>
<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha256-Sk3nkD6mLTMOF0EOpNtsIry+s1CsaqQC1rVLTAy+0yc= sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="js/map-show.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCc_llU8ar99ImwCSzxa2uRX3Uflt4TllY&signed_in=true&callback=initMap"
		async defer></script>

</body>
</html>
