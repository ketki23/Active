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
        if (static::$id == '')
              {

               $db=DatabaseKra23::getConnection();
               $array = get_object_vars($this);
               static::$columnString = implode(', ', $array);
               static::$valueString = implode(', ',array_fill(0,count($array),'?'));
               $sql = $this->insert();
               $stmt=$db->prepare($sql);
               $stmt->execute(static::$data);

              }

             else
              {

               $db=DatabaseKra23::getConnection();
               $array = get_object_vars($this);
               $sql = $this->update();
               $stmt=$db->prepare($sql);
               $stmt->execute();

              }
    }
    
    private function update() 
    {

      $db=DatabaseKra23::getConnection();  
      $sql = "Update ".static::$tableName. " Set ".static::$updateColumn."='".static::$update."' Where id=".static::$id;
      return $sql;  
    
    }
    
    private function delete() 
    {

       $db=DatabaseKra23::getConnection();
       $sql = 'Delete From '.static::$tableName.' WHERE id='.static::$id;
       $stmt=$db->prepare($sql);
       $stmt->execute();
       echo'Deleting record with ID :'.static::$id; 

    }
    
    public function insert() 
    {
        $db=DatabaseKra23::getConnection();
        $sql = "Insert into ".static::$tableName." (". static::$updateColumn . ") values(". static::$valueString . ") ";
        return $sql;
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

class HtmlTable
{
    echo '<table>'
    echo "<table border='3'>";
    foreach($result as $column)
            {
                echo '<tr>';
                        foreach($column as $row)
                        {
                            echo '<td>';
                            echo $row;
                            echo '</td>';
                        }
                echo '</tr>';
            }
    echo '</table>';
}

echo 'Select all records from the table Accounts';// printing all the records for Accounts
$records = accounts::create();
$result = accounts::findAll(); // to put in index page for accounts 
table::createTable($result);

echo '<br><br>';

echo 'Select all records from the table todos';// printing all the records for Accounts
$records = accounts::create();
$result = todos::findAll(); // to put in index page for todos
table::createTable($result);

echo '<br><br>';

echo '<h2>Select record from table Todos where ID=3 <h2>';
$result = accounts::findOne(3);// to get one result and is used for showing one result or updating one result for accounts
table::createTable($result);


echo '<br><br>';

echo '<h2>Select record from table Todos where ID=1 <h2>';
$result = todos::findOne(1);// to get one result and is used for showing one result or updating one result for todos
table::createTable($result);
