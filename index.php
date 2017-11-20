<?php
//turn on debugging messages
//ini_set('display_errors', 'On');
error_reporting(E_ERROR );
define('DATABASE', '');
define('USERNAME', '');
define('PASSWORD', '');
define('CONNECTION', '');
class dbConn{
	//variable to hold connection object.
	protected static $db;
	//private construct - class cannot be instatiated externally.
	private function __construct() {
		try {
			// assign PDO object to db variable
			self::$db = new PDO( 'mysql:host=' . CONNECTION .';dbname=' . DATABASE, USERNAME, PASSWORD );
			self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		catch (PDOException $e) {
			//Output error - would normally log this to error file rather than output to
user.
			echo "Connection Error: " . $e->getMessage();
		}
	}		
	// get connection function. Static method - accessible without instantiation
	public static function getConnection() {
		//Guarantees single instance, if no connection object exists then create one.
		if (!self::$db) {
			//new connection object.
			new dbConn();
		}
		//return connection.
		return self::$db;
	}
}
class collection {
	static public function create() {
		$model = new static::$modelName;
		return $model;
	}
	static public function findAll() {
		$db = dbConn::getConnection();
		$tableName = get_called_class();
		$sql = 'SELECT * FROM ' . $tableName;
		$statement = $db->prepare($sql);
		$statement->execute();
		$class = static::$modelName;
		$statement->setFetchMode(PDO::FETCH_CLASS, $class);
		$recordsSet =  $statement->fetchAll();
		echo " Print full table:" .$tableName;
		return $recordsSet;
	}
	static public function findOne($id) {
		$db = dbConn::getConnection();
		$tableName = get_called_class();
		$sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
		$statement = $db->prepare($sql);
		$statement->execute();
		$class = static::$modelName;
		$statement->setFetchMode(PDO::FETCH_CLASS, $class);
		$recordsSet =  $statement->fetchAll();
		echo "Print one record :". $tableName;
		return $recordsSet[0];
	}
}
class accounts extends collection {
	protected static $modelName = 'account';
}
class todos extends collection {
	protected static $modelName = 'todo';
}
class model {
	//protected $id;
	public function save()
	{
		if ($this->id = '') {
			$sql = $this->insert();
		} else {
			$sql = $this->update();
		}
		$db = dbConn::getConnection();
		$statement = $db->prepare($sql);
		$statement->execute();
		$tableName = get_called_class();
		$array = get_object_vars($this);
		$columnString = implode(',', $array);
		$valueString = ":".implode(',:', $array);
		echo "INSERT INTO $tableName (" . $columnString . ") VALUES (" .$valueString . ")</br>";
		echo 'I just saved record: ' . $this->id;
	}
}
class account extends model {
	public $id;
	public $email;
	public $fname;
	public $lname;
	public $phone;
	public $birthday;
	public $gender;
	public $password;
	protected static $modelName = 'account';
	public static function getTablename(){
		$tableName='accounts';
		return $tableName;
	}
}
class todo extends model {
	public $id;
	public $owneremail;
	public $ownerid;
	public $createddate;
	public $duedate;
	public $message;
	public $isdone;
	
	protected static $modelName = 'todo';
	public static function getTablename(){
		$tableName='todos';
		return $tableName;
	}
}

class htmlTable{
	public function genarateTable($record){
		$tableGen = '<table border="1" cellpadding="2" cellspacing="3">';

			foreach($record as $row => $innerArray){
			$tableGen .= '<tr>';
			foreach($innerArray as $innerRow => $value){

				$tableGen .= '<td>' . $value.'</td>';
			}
			$tableGen.='</tr>';
		}
		}
		$tableGen .= '</table>';
		print_r($tableGen);
	}
}
$obj = new htmlTable();
$obj = new main();
class main
{
	public function __construct()
	{
		$records = todos::findAll();
		$tableGen = htmlTable::genarateTable($records);
		$id=124;
		$records = todos::findOne($id);
		$tableGen = htmlTable::genarateTable($records);
		//insert one record
		$record = new todo();
		$record->id='';
		$record->owneremail="";
		$record->ownerid=;
		$record->createddate="";
		$record->duedate="";
		$record->message="";
		$record->isdone='';
		$record->save();
		$records = todos::findAll();
		$tableGen = htmlTable::genarateTable($records);
		//update one record
		$record = new todo();
		$id=4;
		$record->owneremail="";
		$record->message="";
		$record->save();
		$records = todos::findAll();
		$tableGen = htmlTable::genarateTable($records);
		//delete one record
		$record= new todo();
		$id=145;
		$record->delete();
		$records = todos::findAll();
		$obj->genarateTable($records);
		$tableGen = htmlTable::genarateTable($records);
	}
}
?>
