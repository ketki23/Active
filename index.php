<?php
class DatabaseKra23
{
// initiation of connection variables
private $servername = "mysql:dbname=kra23;host=sql1.njit.edu";
private $username = "kra23";
private $password = "z4QhaWbRd";
// end 

private $db;// database handler
private $error;// for errors

private static $instance;// to maintain single instance and eliminate mutilple connections open

public function __construct()
{
$connect = $this->servername;
$options= array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
try
{
 $this->db = new PDO($connect, $this->username, $this->password, $options);
} 
catch(PDOException $e)
{
$this->error= $e->getMessage();
}
}

static function getconnected()
{
    if(!self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
}
}

class collection 
{
    static public function create()
    {
      $model = new static::$modelName;
      return $model;
    }
}