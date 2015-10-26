<?php
set_include_path("../data/");

include("db.php");
$db = new DBConn;

$res = $db->query(
  "SELECT `application_id`,`call_sign`,`locus_json`,`locus_lat`,`locus_lng`,`poly_json` FROM polygons WHERE `call_sign` = :callsign OR `call_sign` = :callsignfm OR `call_sign` = :callsignlp ;",
  [":callsign"     => $_GET["search"] ,
  ":callsignfm"    => $_GET["search"] . "-FM",
  ":callsignlp"    => $_GET["search"] . "-LP" ],
  "There was an error selecting from the database.");

$rows = $res->fetchAll();
if (count($rows) < 1)
{
  echo("couldn't find that station.");
  $station = false;
}
else
{
  $station = array_pop($rows);
}


//print_r($station);
?>
