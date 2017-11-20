<?php

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);
define('DATABASE', 'kra23');
define('USERNAME', 'kra23');
define('PASSWORD', 'z4QhaWbRd');
define('CONNECTION', 'sql1.njit.edu');
class database{
    //variable to hold connection object.
    protected static $databasekra23;
    //private construct - class cannot be instatiated externally.
    public function __construct() {
        try {
            // assign PDO object to databasekra23 variable
            self::$databasekra23 = new PDO( 'mysql:host=' . CONNECTION .';dbname=' . DATABASE, USERNAME, PASSWORD );
            self::$databasekra23->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            //echo '<h3>Database connection successfull<h3><br>';
        }
        catch (PDOException $e) {
            //Output error - would normally log this to error file rather than output to user.
            echo "Connection Error: " . $e->getMessage();
        }
    }
    // get connection function. Static method - accessible without instantiation
    public static function getConnection() {
        //Guarantees single instance, if no connection object exists then create one.
        if (!self::$databasekra23) {
            //new connection object.
            new database();
        }
        //return connection.
        return self::$databasekra23;
    }
}

/*class DatabaseKra23
{
// initiation of connection variables
private $servername = "mysql:dbname=kra23;host=sql1.njit.edu";
private $username = "kra23";
private $password = "z4QhaWbRd";
// end 
private $databasekra23;// database handler
private $error;// for errors
private static $instance;// to maintain single instance and eliminate mutilple connections open
public function __construct()
{
$connect = $this->servername;
$options= array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
try
{
 $this->databasekra23 = new PDO($connect, $this->username, $this->password, $options);
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
*/
class collection
{
    static public function create()
      {
        $model = new static::$modelName;
        return $model;
      }
       
    public  function findAll()
      {
         $databasekra23 = database::getConnection();
         $tableName = get_called_class();
         $sql = 'SELECT * FROM ' . $tableName;
         $statement = $databasekra23->prepare($sql);
         $statement->execute();
            
         $class = static::$modelName;
         $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        
         $recordsSet =  $statement->fetchAll();
         return $recordsSet;
      }
    public  function findOne($id)
      {
         $databasekra23 = database::getConnection();
         $tableName = get_called_class();
         $sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
         $statement = $databasekra23->prepare($sql);
         $statement->execute();
         $class = static::$modelName;
         $statement->setFetchMode(PDO::FETCH_CLASS,$class);
         $recordsSet  =  $statement->fetchAll();
         return $recordsSet;
      }
}
      
class accounts extends collection
{
    protected static $modelName='accounts';
}
class todos extends collection
{
    protected static $modelName='todos';
}
class model
{
          static $columnString;
          static $valueString;
       
          public function save()
           {
             if (static::$id == '')
              {
               $databasekra23=database::getConnection();
               $array = get_object_vars($this);
               static::$columnString = implode(', ', $array);
               static::$valueString = implode(', ',array_fill(0,count($array),'?'));
               $sql = $this->insert();
               $stmt=$databasekra23->prepare($sql);
               $stmt->execute(static::$data);
              }

             else
              {
               $databasekra23=database::getConnection();
               $array = get_object_vars($this);
               $sql = $this->update();
               $stmt=$databasekra23->prepare($sql);
               $stmt->execute();
              }
           }

           private function insert()
            {
                $databasekra23=database::getConnection();
                $sql = "Insert into ".static::$tableName." (". static::$columnString . ") values(". static::$valueString . ") ";
                return $sql;
            }

           private function update()
            {
                $databasekra23=database::getConnection();
                $sql = "Update ".static::$tableName. " set ".static::$columnUpdate."='".static::$change."' where id=".static::$id;
                return $sql;
             }
                    
                   
           public function delete()
             {
                $databasekra23=database::getConnection();
                $sql = 'Delete From '.static::$tableName.' WHERE id='.static::$id;
                $stmt=$databasekra23->prepare($sql);
                $stmt->execute();
                echo'I just deleted record which has ID :'.static::$id;
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
              static $id = '7';
              static $columnUpdate = 'birthday';
              static $change ='1987-04-03';
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
               static $id = '5';
               static $columnUpdate = 'owneremail';
               static $change ='jane@njit.edu';
}

class HtmlTable
{
        static  function displayTable($result)
        {
            echo '<table>';
            echo "<table cellpadding='10px' border='5px' style='border-solid black'>";
            foreach($result as $align)
            {
                echo '<tr>';
                foreach($align as $row)
                   {
                     echo '<td>';
                     echo $row;
                     echo '</td>';
                   }
                echo '</tr>';
            }
            echo '</table>';
        }
}

         echo '<h1>Select all records from todos Table</h1>';
         $records = todos::create();
         $result = $records->findAll();
         HtmlTable::displayTable($result);
         echo '<br>';
         echo '<hr>';

         echo '<h1>Select all records from Accounts Table</h1>';
         $records = accounts::create();
         $result = $records->findAll();
         HtmlTable::displayTable($result);
         echo '<br>';
         echo '<hr>';
       
         echo '<h1>Select record from Accounts Table where ID is : 10</h1>';
         $result = $records->findOne(10);
         HtmlTable::displayTable($result);
         echo '<br>';
         echo '<hr>';
         
         echo '<h1>Select record from Todos Table where ID:3<h1>';
         $result = $records->findOne(3);
         HtmlTable::displayTable($result);
         echo '<br>';
         echo '<hr>';

         echo '<h1>Inserting a row in accounts table</h1>';
         $obj = new account;
         $obj->save();
         $records = accounts::create();
         $result = $records->findAll();
         HtmlTable::displayTable($result);
         echo '<br>';
         echo '<hr>';

         echo '<h1>Inserting a row in todos table</h1>';
         $obj = new todo;
         $obj->save();
         $records = todos::create();
         $result= $records->findAll();
         HtmlTable::displayTable($result);
         echo '<br>';
         echo '<hr>';

         echo '<h1>Updating birthday in accounts Table</h1>';
         $obj = new account;
         $obj->save();
         $records = accounts::create();
         $result = $records->findOne(7);
         HtmlTable::displayTable($result);
         echo '<br>';
         echo '<hr>';
        
         echo '<h1>Updating owneremail Column in todos Table</h1>';
         $obj = new todo;
         $obj->save();
         $records = todos::create();
         $result = $records->findOne(5);
         HtmlTable::displayTable($result);
         echo '<br>';
         echo '<hr>';

         echo '<h1>Delete record from Todos Table</h1>';
         $obj = new todo;
         $obj->delete();
         $records = todos::create();
         $result = $records->findOne(7);
         echo '<hr>';

         echo '<h1>Delete record from accounts Table</h1>';
         $obj = new todo;
         $obj->delete();
         $records = todos::create();
         $result = $records->findOne(5);
         echo '<hr>';
?>