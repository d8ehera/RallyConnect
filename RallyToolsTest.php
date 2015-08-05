<?php

/***
 * This is a test file for testing rally api's. 
 * Create Defects.
 * Create UserStories for a specific project.
 * Query a particular defect/user story.
 * delete a defect.
 * 
 */
include 'RallyTools.php';

$username = "user+_Rally@testemail.com";
$password = "xxx";

//Initialize the object.
$rally = new Rally($username, $password);

//Create the defect.
$params = array(
      'Project'     => '38367403919',
	  'Name'        => 'Test Defect2',
	  'Description' => 'This is a test defect from backend PHP code',
	  'Environment' => 'Development',
	  'Severity'    => 'Cosmetic',
	);
	
	
 $result = $rally->createDefect('defect', $params);
 
 $result = $rally->createStories($params);

//Get Object Id.
$defectID = "DE163461";
$ObjectId = $rally->getobjectid($defectID); 



//Update a defect.

$params = array(
  "Description" => "Updated the Defect and update working.",
  "state" => "Open" 
);
			
$rally->updateDefect($ObjectId, $params);

//Find a defect.

$query = '(FormattedID = "DE163221")';

$results = $rally->findDefect($query);

print_r($results);



$rally->deleteDefect($ObjectId)


?>