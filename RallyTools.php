<?php


class Rally {

    protected $_secToken;
    
    private $_debug = true;
    
	private $_agent = 'PHP - Rally Api - 2.0';
	
	protected $_ch;
	
	public $_objectId;
	
	public function __construct($username, $password) 
	{
       	$url = "https://rally1.rallydev.com/slm/webservice/v2.0/security/authorize";
       
       	$this->_ch = curl_init();
		curl_setopt($this->_ch, CURLOPT_URL, $url);
		curl_setopt($this->_ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($this->_ch, CURLOPT_HTTPHEADER, array('Content-Type: text/javascript'));
		curl_setopt($this->_ch, CURLOPT_VERBOSE, true);
		curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->_ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($this->_ch, CURLOPT_USERAGENT, $this->_agent);
		curl_setopt($this->_ch, CURLOPT_HEADER, 0);
		curl_setopt($this->_ch, CURLOPT_COOKIEFILE, 0);
		curl_setopt($this->_ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt ($this->_ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($this->_ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt ($this->_ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt ($this->_ch, CURLOPT_POSTFIELDS, '');
		
		$result = curl_exec($this->_ch);
		header('Content-type: application/json');
		
		$decoded = json_decode($result,JSON_PRETTY_PRINT);
		
		//var_dump($decoded); 
		
		$this->_securityToken =  $decoded->OperationResult->SecurityToken;
		
		if ( curl_errno($this->_ch) ) {
    		$result = 'cURL ERROR -> ' . curl_errno($this->_ch) . ': ' . curl_error($this->_ch);
    		echo "ERROR! " . $result;
		} 
		else 
		{
    		$returnCode = (int)curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);
    	   	switch($returnCode)
    	   	{
        		case 200:
            		break;
        		default:
            		$result = 'HTTP ERROR -> ' . $returnCode;
            		break;
			}
    	}
	}	
	
	public function createDefect($object, $params) {
			
		$url = "https://rally1.rallydev.com/slm/webservice/v2.0/defect/create?key=" . $this->_securityToken;
	
		$payload = json_encode(array('Content' => $params));
		curl_setopt ($this->_ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($this->_ch, CURLOPT_URL, $url);
		curl_setopt ($this->_ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		
		$result = curl_exec($this->_ch);  
		
        $decoded = json_decode($result);
        $objectId = $decoded->CreateResult->Object->ObjectID;
        $defectId = $decoded->CreateResult->Object->FormattedID;

    	return $defectId;
			
	
		//print "Defect Created and id is ....." . $defectId . "\r\n";
		
	}

	public function createStories($params) {
			
		$url = "https://rally1.rallydev.com/slm/webservice/v2.0/HierarchicalRequirement/create?key=" . $this->_securityToken;
	
		$payload = json_encode(array('Content' => $params));
		curl_setopt ($this->_ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($this->_ch, CURLOPT_URL, $url);
		curl_setopt ($this->_ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		
		$result = curl_exec($this->_ch);  
		
        $decoded = json_decode($result);
	}
		
	public function getobjectid($defectID) {
		
	    $query = '(FormattedID = \"' . $defectID . '\")';
	    $params = array(
	      'query' => $query,
	      'fetch' => 'true',
	   	  'pagesize' => 100,
	      'start' => 1,
	    );
	
		$url = "https://rally1.rallydev.com/slm/webservice/v2.0/defect" .'?'.http_build_query($params, '', '&');

	    curl_setopt ($this->_ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt ($this->_ch, CURLOPT_POSTFIELDS, '');
		curl_setopt($this->_ch, CURLOPT_URL, $url);

		$results = curl_exec($this->_ch);
	
		$decoded = json_decode($results);
	    
		$this->_objectId = $decoded->QueryResult->Results[0]->ObjectID;
		
		return $this->_objectId;
}
	
	public function updateDefect($object, array $params) {
			
		
		$url = "https://rally1.rallydev.com/slm/webservice/v2.0/defect/" . $object . "?key=" . $this->_securityToken;

			$payload = json_encode(array('Content' => $params));
			
			curl_setopt ($this->_ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($this->_ch, CURLOPT_URL, $url);
			curl_setopt ($this->_ch, CURLOPT_CUSTOMREQUEST, 'POST');
			
			$result = curl_exec($this->_ch);
			
			$decoded = json_decode($result);

	}
	
	
	public function findDefect($query) {

		$url = "https://rally1.rallydev.com/slm/webservice/v2.0/defect";
		    
		    $params = array(
		      'query' => $query,
		      'fetch' => 'true',
		   'pagesize' => 100,
		      'start' => 1,
		    );
			
		$url = $url.'?'.http_build_query($params, '', '&');
		
		
		curl_setopt($this->_ch, CURLOPT_URL, $url);
		curl_setopt ($this->_ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt ($this->_ch, CURLOPT_POSTFIELDS, '');
		
		$result = curl_exec($this->_ch);
		
		$decoded = json_decode($result, JSON_PRETTY_PRINT);
		
		return($decoded);
		
	}
	
	public function deleteDefect($objectID) {
			
		$url = "https://rally1.rallydev.com/slm/webservice/v2.0/defect/" . $objectID . "?key=" . $this->_securityToken;
		
		curl_setopt ($this->_ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($this->_ch, CURLOPT_URL, $url);
		
		print "Deleting the object";
		$result = curl_exec($this->_ch);		
	}	
}

?>