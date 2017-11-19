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


    static public function findAll() 
    {
        $db = DatabaseKra23::getConnection();
        $tableName = get_called_class();
        $sql = 'SELECT * FROM ' . $tableName;
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        $recordsSet =  $statement->fetchAll();
        return $recordsSet;
    }


    static public function findOne($id) 
    {
        $db = DatabaseKra23::getConnection();
        $tableName = get_called_class();
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        $recordsSet =  $statement->fetchAll();
        return $recordsSet[0];
    }
}

class accounts extends collection
{
    protected static $modelName = 'account';
}


class todos extends collection
{
    protected static $modelName = 'todo';
}


class model 
{
    protected $tableName;
    
    public function save()
    {
        
    }
    
    private function insert() 
    {
        
    }
    
    private function update() 
    {
        
    }
    
    public function delete() 
    {
        
    }
}


class account extends model 
{

    public $email = 'email';
    public $fname = 'fname';
    public $lname = 'lname';
    public $phone =  'phone';
    public $birthday = 'birthday';
    public $gender= 'gender';
    public $password = 'password';
    static $tableName = 'accounts';
    static $id = '3';
    static $data = array('riya@kjit.edu','Riya','Ray','1234',NULL,'Female','riyaray');
    static $updateColumn = 'email';
    static $update ='ray123@kjit.edu';

}

class todo extends model 
{
    
    public $owneremail = 'owneremail';
    public $ownerid = 'ownerid';
    public $createddate = 'createddate';
    public $duedate = 'duedate';
    public $message = 'message';
    public $isdone = 'isdone';
    static $tableName = 'todos';
    static $id = '2';
    static $data = array('xyz@yahoo.com','8','2017-11-18','2017-12-12 03:00:21','incomplete','0');          
    static $updateColumn = 'isdone';
    static $update ='1';
}

    public function __construct()
    {
        $this->tableName = 'todos';
    
    }

}

$result = accounts::findAll(); // to put in index page for accounts 
print_r($result);

$result = todos::findAll(); // to put in index page for todos
print_r($result);

$result = accounts::findOne(1);// to get one result and is used for showing one result or updating one result for accounts
print_r($result);

$result = todos::findOne(1);// to get one result and is used for showing one result or updating one result for todos
print_r($result);