<?php
class DBConn
{
  function __construct()
  {
    include('dbconn.php');
    $this->db = $db;
  }
  function query($query, $params=[], $emessage="There was a problem handling a database request.")
  {
    try
    {
      $stmt = $this->db->prepare($query);
      $result = $stmt->execute($params);
      return $result;
    }
    catch(PDOException $ex)
    {
        die(error_alert($ex->getMessage()));
    }
  }
}
?>
