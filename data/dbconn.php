<?php

// Thank you to E-Oreo for this code.
// Retrieved from http://forums.devshed.com/php-faqs-stickies-167/program-basic-secure-login-system-using-php-mysql-891201.html
// No shame in this game

if (!isset($db))
{
    try
    {
      include('dbcredentials.php'); // This should be a script that sets $username, $password, $host and $dbname.
    }
    catch (Exception $ex)
    {
      die("Couldn't find the database credentials file: " . $ex->getMessage());
    }

    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

    try
    {
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
    }
    catch(PDOException $ex)
    {
        die("Failed to connect to the database: " . $ex->getMessage());
    }

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
    {
        function undo_magic_quotes_gpc(&$array)
        {
            foreach($array as &$value)
            {
                if(is_array($value))
                {
                    undo_magic_quotes_gpc($value);
                }
                else
                {
                    $value = stripslashes($value);
                }
            }
        }

        undo_magic_quotes_gpc($_POST);
        undo_magic_quotes_gpc($_GET);
        undo_magic_quotes_gpc($_COOKIE);
    }

    //header('Content-Type: text/html; charset=utf-8');
}
