<?php
// hello again php, old friend

die("This script has been disabled to conserve resources.");

$contour_raw_path = "FM_service_contour_current.txt";
$output_path = "contour-import.txt";
$polygon_count = 360.0;
$polygon_desired_count = 90.0;
if ($polygon_count < $polygon_desired_count)
  throw new Exception('Polygon desired count must be less than polygon count.');
$cell_delimiter = "|";
$coord_delimiter = ",";
$json_array_delimiter = ", "; // between the latlon constructs in the dropin json

$rawpos_application_id = 0;
$rawpos_service_type = 1;
$rawpos_callfile = 2;
$rawpos_locus = 3;
$rawpos_poly_start = 4;
$rawpos_poly_end = 363;

class FMContourObject
{
  public $application_id = false;
  public $service_type = "FM";
  public $call_sign = false;
  public $application_file = false;
  public $locus_raw;  // post-pipe-delimited raw coordinates with commas
  public $locus_json; // cooked coords for Marker drop-in to Google Maps API
  public $poly_raw = array();  // post-pipe-delimited raw Polygon with commas
  public $poly_json; // cooked coords for Polygon drop-in to Google Maps API

  function make_json_coordinates()
  {
    global $polygon_count, $polygon_desired_count, $json_array_delimiter;

    // Begin the json string here:
    $this->poly_json = "[ ";
    $poly_counter = 0.0; // controls when to skip coordinates in order to reduce
    foreach($this->poly_raw as $rawpoint)
    {
      // Append each pair as a json pair, plus the delimiter.
      if ($poly_counter >= 1.0)
      {
        $poly_counter -= 1.0;
        // And do nothing.
      }
      else
      {
        $poly_counter += ($polygon_count / $polygon_desired_count) - 1.0;
        $this->poly_json .= $this->piped_to_gjson($rawpoint) . $json_array_delimiter;
      }
    }

    $this->poly_json = rtrim($this->poly_json, ", \r\n\t\0\x0B"); // Remove commas and spaces from the end of the json.

    $this->poly_json .= " ]; "; // "Close" the json array.

    $this->locus_json = $this->piped_to_gjson($this->locus_raw); // Don't forget to jsonize the locus.
  }

  function piped_to_gjson($comma_pair)
  {
    // convert comma-separated pair to google maps format
    $boom = explode(",",$comma_pair);
    return "{lat: ". trim($boom[0]) .", lng: ". trim($boom[1]) . "}";
  }

  function print_data()
  {
    $poly_coords_short = $this->poly_raw[0]." | ".$this->poly_raw[1]." | <i>[...]</i>";
    $poly_json_short = substr($this->poly_json, 0, 100)." <i>[...]</i>";
    echo "<table border='1'><thead style='background-color:papayawhip'><tr><th colspan='2'>{$this->call_sign} {$this->service_type}</th></tr></thead><tbody>\n";
    echo "<tr><td><b>Application ID &amp; File #</b></td><td>{$this->application_id} / {$this->application_file}</td></tr>\n";
    echo "<tr><td><b>Call sign &amp; Service</b></td><td>{$this->call_sign} {$this->service_type}</td></tr>\n";
    echo "<tr><td><b>Locus (raw FCC)</b></td><td>{$this->locus_raw}</td></tr>\n";
    echo "<tr><td><b>Locus (JSON/GMaps)</b></td><td>{$this->locus_json}</td></tr>\n";
    echo "<tr><td><b>Polygon (raw FCC)</b></td><td>{$poly_coords_short}</td></tr>\n";
    echo "<tr><td><b>Polygon (JSON/GMaps)</b></td><td>{$poly_json_short}</td></tr>\n";
    echo "</tbody></table><br /><br />\n\n";
  }

  function insert_row($db)
  {
    $db->query("
      INSERT INTO `fmradius`.`polygons` (`application_id`, `service_type`, `call_sign`, `application_file`, `locus_json`, `poly_json`)
      VALUES                            (:application_id,  :service_type,  :call_sign,  :application_file,  :locus_json,  :poly_json)",
      [
        ":application_id" => $this->application_id,
        ":service_type" => $this->service_type,
        ":call_sign" => $this->call_sign,
        ":application_file" => $this->application_file,
        ":locus_json" => $this->locus_json,
        ":poly_json" => $this->poly_json
      ]
    );
  }
}

if (($f_in = file($contour_raw_path)) && ($f_out = fopen($output_path, "w+")) )
{

 // prepare database
  require('db.php');
  $db = new DBConn;

  $db->query("DROP TABLE IF EXISTS `polygons`;",
    [],
    "There was a problem dropping the table.");

  $db->query("CREATE TABLE `fmradius`.`polygons` ( `application_id` INT NOT NULL , `service_type` VARCHAR(10) NOT NULL DEFAULT 'FM' , `call_sign` VARCHAR(10) NOT NULL , `application_file` VARCHAR(16) NOT NULL , `locus_json` TEXT NOT NULL , `poly_json` TEXT NOT NULL ) ENGINE = MyISAM;",
    [],
    "There was a problem creating the table.");

  foreach ($f_in as $line_num => $line)
  {
    //echo "<h3>Line {$line_num}</h3>";
    $raw_line = explode($cell_delimiter, $line);
    /*foreach ($raw_line as $cell_num => $cell)
    {
      echo "<p><tt>Cell {$cell_num}: </tt><b>" . htmlspecialchars($cell) . "</b></p>";
    }*/

    $myfmcon = new FMContourObject;
    $myfmcon->application_id = trim($raw_line[$rawpos_application_id]);
    $myfmcon->mode = trim($raw_line[$rawpos_mode]);
    $myfmcon->service_type = trim($raw_line[$rawpos_service_type]);

    $callfile = explode(" ",trim($raw_line[$rawpos_callfile]));  // This cell contains the call sign and the app file number.
    $myfmcon->application_file = array_pop($callfile);
    $myfmcon->call_sign = array_pop($callfile);

    $myfmcon->locus_raw = trim($raw_line[$rawpos_locus]);


    for ($i = $rawpos_poly_start; $i <= $rawpos_poly_end; $i++)
    {
      $myfmcon->poly_raw[$i-$rawpos_poly_start] = $raw_line[$i];
    }


    $myfmcon->make_json_coordinates();
    $myfmcon->print_data();
    $myfmcon->insert_row($db);
  }
  echo "<h2>Full JSON from last record</h2>";
  echo $myfmcon->poly_json;
  echo "<br /><br />\n\n\n<h2>Full raw FCC data from last record</h2>\n\n\n";
  foreach($myfmcon->poly_raw as $pt)
  {
    echo $pt . "|";
  }



}
else {
  echo "Error opening the file...";
}

?>
