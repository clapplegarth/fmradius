<?php
class DBConn
{
  function __construct($path="")
  {
    include('dbconn.php');
    $this->db = $db;
  }
  function query($query, $params=[], $emessage="There was a problem handling a database request.", $returnresult = false)
  {
    try
    {
      $stmt = $this->db->prepare($query);
      $result = $stmt->execute($params);
      if ($returnresult)
      {
        return $result;
      }
      else
      {
        return $stmt;
      }
    }
    catch(PDOException $ex)
    {
        die($emessage . "\r\n<br />" . $ex->getMessage());
    }
  }
}
?>
