<?php 
require_once('config.php');

class Default_Table
{
    var $tablename;         // table name
    var $dbname;            // database name
    var $rows_per_page;     // used in pagination
    var $pageno;            // current page number
    var $lastpage;          // highest page number
    var $fieldlist;         // list of fields in this table
    var $data_array;        // data from the database
    var $errors;            // array of error messages

  
    function Default_Table ()
      {
        $this->tablename       = 'default';
        $this->dbname          = 'default';
        $this->rows_per_page   = 10;
        
        $this->fieldlist = array('column1', 'column2', 'column3');
        $this->fieldlist['column1'] = array('pkey' => 'y');
      } // constructor
    
    
       function getData ($where)
       {
          $this->data_array = array();
          $pageno          = $this->pageno;
          $rows_per_page   = $this->rows_per_page;
          $this->numrows   = 0;
          $this->lastpage  = 0;
    
    
    
          global $dbconnect, $query;
          $dbconnect = db_connect($this->dbname) or trigger_error("SQL", E_USER_ERROR);
    
    
    
         if (empty($where)) {
             $where_str = NULL;
          } else {
             $where_str = "WHERE $where";
          } // if
    
    
    
          $query = "SELECT * FROM $this->tablename $where_str";
          $result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
    	  
    	  
      
       
          return $result;
          
       } // getData
       
       
     function updateRecord($array, $userID) {
            
    	 	
    		global $dbconnect, $query;
    		$dbconnect = db_connect($this->dbname) or trigger_error("SQL", E_USER_ERROR);
    
    
            /*Assuming array keys are = to database fileds*/
            if (count($array) > 0) {
                foreach ($array as $key => $value) {
    
                    $value = mysql_real_escape_string($value); // this is dedicated to @Jon
                    $value = "'$value'";
                    $updates[] = "$key = $value";
                }
            }
            $implodeArray = implode(', ', $updates);
            $sql = ("Update user where id=".$userID." SET".$implodeArray);
            $result = @mysql_query($sql, $dbconnect);		
            return $result;
    }
       
       
       
    function insertRecord ($fieldarray)
    {
    	$this->errors = array();
    	global $dbconnect, $query;
    	$dbconnect = db_connect($this->dbname) or trigger_error("SQL", E_USER_ERROR);
    	$first = true; $part1 = $part2 = "";
    	foreach($fieldarray as $Field=>$Val) {
    	if( !$first ) {
    		$part1 .= ",";
    		$part2 .= ",";
    	}
    	$part1 .= $Field;
    	// Stripslashes if magic quotes is on
    	if (get_magic_quotes_gpc()) {
    	$Val = stripslashes($Val);
    	}
    	$Val = "'" . mysql_real_escape_string($Val) . "'";	
    	$part2 .= $Val;
    	$first = false;
    	}
    
    	$query = "Insert into $this->tablename (". $part1 .") values (". $part2 .")";
    
    	$result = @mysql_query($query, $dbconnect);
    
    return $result;
    
    } // insertRecord
    
    /*REPORTS*/
    
    
    function GetCompleteReportsSurvey($Condition) {
    	$query = "SELECT * FROM  $this->tablename where $Condition";
    	$result = @mysql_query($query, $dbconnect); 
    	return $result;
    }
    
    function GetSurvey($Condition){
    	$query = "Select * from $this->tablename $Condition";
    	$result =@mysql_query($query, $dbconnect);
    	return $result;
    }
    
    function GetReportsSurvey($Condition) {
    	$query = "Select * from $this->tablename $Condition";
    	$result = @mysql_query($query, $dbconnect);
    	return $result;
    }
    
    function  GetSurveyAnswer ($Condition) {
    	$query = "Select * from $this->tablename $Condition";
    	$result = @mysql_query($query, $dbconnect);
    	return $result;
    }
    
    function GetQuestionCounts($QID) {
    	$query = "Select Count(*) as Count, q{$QID} as Response from $this->tablename group by q{$QID} order by Count DESC";
    	$result = @mysql_query($query, $dbconnect);
    	return $result;
    }
    
    function GetQuestionCountsThird() {
    	$query = "Select ThirdParty, ThirdPartyEmail as Response from survey where ThirdParty = 'Yes' group by ThirdPartyEmail order by ThirdPartyEmail DESC";
    	$result = @mysql_query($query, $dbconnect);
    	return $result;
    }
    
    
    
    function GetAss($R){
    return mysql_fetch_assoc($R);
    }

}
?>
