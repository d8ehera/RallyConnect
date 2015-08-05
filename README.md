# RallyConnect

Installation

PHP 5.3+ with JSON and CuRL. Rally 2.x. 

Description
The Rally connect interface is a php libraries that uses Curl to connect to Rally. 
1. Initialize and get the security token.
2. For create/update/delete transaction need to pass the security token.
3. For creating userstories, url is 
       $url = "https://rally1.rallydev.com/slm/webservice/v2.0/defect/create?key=" . $this->_securityToken;
4. For creating defects url is 
       $url = "https://rally1.rallydev.com/slm/webservice/v2.0/HierarchicalRequirement/create?key=" . $this->_securityToken;
       
5. Methods used are
   Initialize to get security token.
   Create User Stories
   Create defects.
   Get Object Id from defect Id
   Update Defect
   Delete Defect/User Stories
   Find Defect
   Find User stories
   
More rally tools for diffent languagues (node/java/php.. ) are at 
https://github.com/rallytools
   ß
