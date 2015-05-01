<?php
include 'clases.php'; //----> to insert in DATABSE this is apart 
$eventId="INSERT_CVENT_EVENT_ID";
$timeCond = " date > 0";			
$filterCondCode = "true";
$condition = "$filterCondCode and  $timeCond ";
$dbobject = new ConnectCvent_Registered; 

//YOUR SERVER IP NEEDS TO BE WHITE LISTED IN CVENT $host= gethostname(); $ip = gethostbyname($host); echo "<br/> IP to be whitelisted: ".$ip."<br/>";
 ini_set('memory_limit','2048M');
require('CventClient.class.php');
$cc = new CventClient();
$account = "INSERT_CVENT_API_ACCOUNT";
$username = "INSERT_CVENT_API_USER";
$password= "INSERT_CVENT_API_PASSWORD";
$cc->Login($account, $username, $password);
//$events = $cc->GetUpcomingEvents();
	function objectToArray($d) {
		if (is_object($d)) {			
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {		
			return array_map(__FUNCTION__, $d);
		}
		else {		
			return $d;
		}
	}

	$ArrayOfContact = $cc->GetContactsRegistrationDataByEventId($eventId);
	$ArrayContactsMULTY=objectToArray($ArrayOfContact);/*count($ArrayContactsMULTY)*/
	
	$UsersDB = $dbobject->getData($condition);
	for($i = 0; $i < count($ArrayContactsMULTY); $i++) {		
		$User = $dbobject->GetAss($UsersDB);		
		$QuestionDetails =$ArrayContactsMULTY[$i][EventSurveyDetail];
		$NeedsHotel = $QuestionDetails[1][AnswerText];	//print_r($NeedsHotel);

		
		if($User['EmailAddress'] != $ArrayContactsMULTY[$i][EmailAddress]){						
			$data=array(
			"EventId"=>$ArrayContactsMULTY[$i][EventId]
			,"ConfirmationNumber"=>$ArrayContactsMULTY[$i][ConfirmationNumber]
			,"Status"=>$ArrayContactsMULTY[$i][Status]	
			,"RequiresHotel"=>$NeedsHotel
			,"FirstName"=>$ArrayContactsMULTY[$i][FirstName]     
			,"LastName"=>$ArrayContactsMULTY[$i][LastName]  
			,"Company"=>$ArrayContactsMULTY[$i][Company]  
			,"Title"=>$ArrayContactsMULTY[$i][Title]  
			,"EmailAddress"=>$ArrayContactsMULTY[$i][EmailAddress] 
			,"WorkPhone"=>$ArrayContactsMULTY[$i][WorkPhone]
			,"Address1"=>$ArrayContactsMULTY[$i][Address1] 
			,"Address2"=>$ArrayContactsMULTY[$i][Address2] 
			,"Address3"=>$ArrayContactsMULTY[$i][Address3] 
			,"City"=>$ArrayContactsMULTY[$i][City] 
			,"State"=>$ArrayContactsMULTY[$i][State] 
			,"StateCode"=>$ArrayContactsMULTY[$i][StateCode] 
			,"PostalCode"=>$ArrayContactsMULTY[$i][PostalCode] 
			,"TargetedListName"=>$ArrayContactsMULTY[$i][TargetedListName]
			,"TargetedListId"=>$ArrayContactsMULTY[$i][TargetedListId] 
			,"RegistrationType"=>$ArrayContactsMULTY[$i][RegistrationType] 	  
			,"Participant"=>$ArrayContactsMULTY[$i][Participant]           
			);    
			$dbobject->insertRecord($data);	
		}else{
			if( $User['Email'] == $ArrayContactsMULTY[$i][EmailAddress] &&  $User['RegistrationType'] != $ArrayContactsMULTY[$i][RegistrationType]){
			
				$data=array(
				"EventId"=>$ArrayContactsMULTY[$i][EventId]
				,"ConfirmationNumber"=>$ArrayContactsMULTY[$i][ConfirmationNumber]
				,"Status"=>$ArrayContactsMULTY[$i][Status]	
				,"RequiresHotel"=>$NeedsHotel
				,"FirstName"=>$ArrayContactsMULTY[$i][FirstName]     
				,"LastName"=>$ArrayContactsMULTY[$i][LastName]  
				,"Company"=>$ArrayContactsMULTY[$i][Company]  
				,"Title"=>$ArrayContactsMULTY[$i][Title]  
				,"EmailAddress"=>$ArrayContactsMULTY[$i][EmailAddress] 
				,"WorkPhone"=>$ArrayContactsMULTY[$i][WorkPhone]
				,"Address1"=>$ArrayContactsMULTY[$i][Address1] 
				,"Address2"=>$ArrayContactsMULTY[$i][Address2] 
				,"Address3"=>$ArrayContactsMULTY[$i][Address3] 
				,"City"=>$ArrayContactsMULTY[$i][City] 
				,"State"=>$ArrayContactsMULTY[$i][State] 
				,"StateCode"=>$ArrayContactsMULTY[$i][StateCode] 
				,"PostalCode"=>$ArrayContactsMULTY[$i][PostalCode] 
				,"TargetedListName"=>$ArrayContactsMULTY[$i][TargetedListName]
				,"TargetedListId"=>$ArrayContactsMULTY[$i][TargetedListId] 	  
				,"RegistrationType"=>$ArrayContactsMULTY[$i][RegistrationType]  
				,"Participant"=>$ArrayContactsMULTY[$i][Participant]          
				);    
				$dbobject->updateRecord($data,$User['id']);	

			}
		}
	}
	
	?>
