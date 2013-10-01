<?php
  
  namespace StructuredDynamics\structwsf\ws\auth\lister\interfaces; 
  
  use \StructuredDynamics\structwsf\ws\framework\SourceInterface;
  use \StructuredDynamics\structwsf\framework\Subject;
  
  class DefaultSourceInterface extends SourceInterface
  {
    function __construct($webservice)
    {   
      parent::__construct($webservice);
      
      $this->compatibleWith = "1.0";
    }
    
    public function processInterface()
    {  
      // Make sure there was no conneg error prior to this process call
      if($this->ws->conneg->getStatus() == 200)
      {
        if(strtolower($this->ws->mode) == "dataset")
        {
          $query =
            " prefix wsf: <http://purl.org/ontology/wsf#>
              select distinct ?dataset 
              from <". $this->ws->wsf_graph ."> 
              where 
              { 
                <". $this->ws->headers['OSF-USER-URI'] ."> a wsf:User ;
                  wsf:hasGroup ?group .
                  
                ?access wsf:groupAccess ?group ;
                        wsf:datasetAccess ?dataset .
              }";

          $resultset =
            @$this->ws->db->query($this->ws->db->build_sparql_query(str_replace(array ("\n", "\r", "\t"), " ", $query), array(),
              FALSE));

          if(odbc_error())
          {
            $this->ws->conneg->setStatus(500);
            $this->ws->conneg->setStatusMsg("Internal Error");
            $this->ws->conneg->setStatusMsgExt($this->ws->errorMessenger->_300->name);
            $this->ws->conneg->setError($this->ws->errorMessenger->_300->id, $this->ws->errorMessenger->ws,
              $this->ws->errorMessenger->_300->name, $this->ws->errorMessenger->_300->description, odbc_errormsg(),
              $this->ws->errorMessenger->_300->level);
            return;
          }

          $subject = new Subject("bnode:".md5(microtime()));
          $subject->setType("rdf:Bag");
          
          while(odbc_fetch_row($resultset))
          {
            $dataset = odbc_result($resultset, 1);
            
            $subject->setObjectAttribute("rdf:li", $dataset, null, "void:Dataset");
          }
          
          $this->ws->rset->addSubject($subject);            
        }
        elseif(strtolower($this->ws->mode) == "ws")
        {
          $query =
            "  select distinct ?ws from <" . $this->ws->wsf_graph
            . ">
                  where
                  {
                    ?wsf a <http://purl.org/ontology/wsf#WebServiceFramework> ;
                          <http://purl.org/ontology/wsf#hasWebService> ?ws .
                  }";

          $resultset =
            @$this->ws->db->query($this->ws->db->build_sparql_query(str_replace(array ("\n", "\r", "\t"), " ", $query), array(),
              FALSE));

          if(odbc_error())
          {
            $this->ws->conneg->setStatus(500);
            $this->ws->conneg->setStatusMsg("Internal Error");
            $this->ws->conneg->setStatusMsgExt($this->ws->errorMessenger->_301->name);
            $this->ws->conneg->setError($this->ws->errorMessenger->_301->id, $this->ws->errorMessenger->ws,
              $this->ws->errorMessenger->_301->name, $this->ws->errorMessenger->_301->description, odbc_errormsg(),
              $this->ws->errorMessenger->_301->level);
            return;
          }
          
          $subject = new Subject("bnode:".md5(microtime()));
          $subject->setType("rdf:Bag");          

          while(odbc_fetch_row($resultset))
          {
            $ws = odbc_result($resultset, 1);
      
            $subject->setObjectAttribute("rdf:li", $ws, null, "wsf:WebService");
          }
          
          $this->ws->rset->addSubject($subject);    
        }
        elseif(strtolower($this->ws->mode) == "groups")
        {
          $query =
            " prefix wsf: <http://purl.org/ontology/wsf#>
              select distinct ?group 
              from <". $this->ws->wsf_graph ."> 
              where 
              { 
                ?group a wsf:Group .
              }";

          $resultset =
            @$this->ws->db->query($this->ws->db->build_sparql_query(str_replace(array ("\n", "\r", "\t"), " ", $query), array(),
              FALSE));

          if(odbc_error())
          {
            $this->ws->conneg->setStatus(500);
            $this->ws->conneg->setStatusMsg("Internal Error");
            $this->ws->conneg->setStatusMsgExt($this->ws->errorMessenger->_300->name);
            $this->ws->conneg->setError($this->ws->errorMessenger->_300->id, $this->ws->errorMessenger->ws,
              $this->ws->errorMessenger->_300->name, $this->ws->errorMessenger->_300->description, odbc_errormsg(),
              $this->ws->errorMessenger->_300->level);
            return;
          }

          $subject = new Subject("bnode:".md5(microtime()));
          $subject->setType("rdf:Bag");
          
          while(odbc_fetch_row($resultset))
          {
            $group = odbc_result($resultset, 1);
            
            $subject->setObjectAttribute("rdf:li", $group, null, "wsf:Group");
          }
          
          $this->ws->rset->addSubject($subject);           
        }
        elseif(strtolower($this->ws->mode) == "group_users")
        {
          $query =
            " prefix wsf: <http://purl.org/ontology/wsf#>
              select distinct ?user 
              from <". $this->ws->wsf_graph ."> 
              where 
              { 
                ?user wsf:hasGroup <". $this->ws->group ."> .
              }";

          $resultset =
            @$this->ws->db->query($this->ws->db->build_sparql_query(str_replace(array ("\n", "\r", "\t"), " ", $query), array(),
              FALSE));

          if(odbc_error())
          {
            $this->ws->conneg->setStatus(500);
            $this->ws->conneg->setStatusMsg("Internal Error");
            $this->ws->conneg->setStatusMsgExt($this->ws->errorMessenger->_300->name);
            $this->ws->conneg->setError($this->ws->errorMessenger->_300->id, $this->ws->errorMessenger->ws,
              $this->ws->errorMessenger->_300->name, $this->ws->errorMessenger->_300->description, odbc_errormsg(),
              $this->ws->errorMessenger->_300->level);
            return;
          }

          $subject = new Subject("bnode:".md5(microtime()));
          $subject->setType("rdf:Bag");
          
          while(odbc_fetch_row($resultset))
          {
            $user = odbc_result($resultset, 1);
            
            $subject->setObjectAttribute("rdf:li", $user, null, "wsf:User");
          }
          
          $this->ws->rset->addSubject($subject);             
        }
        elseif(strtolower($this->ws->mode) == "user_groups")
        {
          $query =
            " prefix wsf: <http://purl.org/ontology/wsf#>
              select distinct ?group 
              from <". $this->ws->wsf_graph ."> 
              where 
              { 
                <". $this->ws->headers['OSF-USER-URI'] ."> wsf:hasGroup ?group .
              }";

          $resultset =
            @$this->ws->db->query($this->ws->db->build_sparql_query(str_replace(array ("\n", "\r", "\t"), " ", $query), array(),
              FALSE));

          if(odbc_error())
          {
            $this->ws->conneg->setStatus(500);
            $this->ws->conneg->setStatusMsg("Internal Error");
            $this->ws->conneg->setStatusMsgExt($this->ws->errorMessenger->_300->name);
            $this->ws->conneg->setError($this->ws->errorMessenger->_300->id, $this->ws->errorMessenger->ws,
              $this->ws->errorMessenger->_300->name, $this->ws->errorMessenger->_300->description, odbc_errormsg(),
              $this->ws->errorMessenger->_300->level);
            return;
          }

          $subject = new Subject("bnode:".md5(microtime()));
          $subject->setType("rdf:Bag");
          
          while(odbc_fetch_row($resultset))
          {
            $group = odbc_result($resultset, 1);
            
            $subject->setObjectAttribute("rdf:li", $group, null, "wsf:Group");
          }
          
          $this->ws->rset->addSubject($subject);          
        }
        else
        { 
          if(strtolower($this->ws->mode) == "access_user")
          { 
            $query = "  select ?access ?datasetAccess ?create ?read ?update ?delete ?group ".($this->ws->targetWebservice == "all" ? "?webServiceAccess" : "")."
                    from <" . $this->ws->wsf_graph . ">
                    where
                    {
                      <".$this->ws->headers['OSF-USER-URI']."> a <http://purl.org/ontology/wsf#User> ;
                            <http://purl.org/ontology/wsf#hasGroup> ?group .
                            
                      ?access a <http://purl.org/ontology/wsf#Access> ;
                            <http://purl.org/ontology/wsf#create> ?create ;
                            <http://purl.org/ontology/wsf#read> ?read ;
                            <http://purl.org/ontology/wsf#update> ?update ;
                            <http://purl.org/ontology/wsf#delete> ?delete ;
                            <http://purl.org/ontology/wsf#datasetAccess> ?datasetAccess ;
                            ".($this->ws->targetWebservice == "all" ? "<http://purl.org/ontology/wsf#webServiceAccess> ?webServiceAccess ;" : "")."
                            ".($this->ws->targetWebservice != "none" && $this->ws->targetWebservice != "all" ? "<http://purl.org/ontology/wsf#webServiceAccess> <".$this->ws->targetWebservice."> ;" : "")."
                            <http://purl.org/ontology/wsf#groupAccess> ?group .
                    }"; 
          }
          else // access_dataset
          {
            $query = "  select ?access ?group ?create ?read ?update ?delete ".($this->ws->targetWebservice == "all" ? "?webServiceAccess" : "")." 
                    from <" . $this->ws->wsf_graph
              . ">
                    where
                    {
                      ?access a <http://purl.org/ontology/wsf#Access> ;
                            <http://purl.org/ontology/wsf#groupAccess> ?group ;
                            <http://purl.org/ontology/wsf#create> ?create ;
                            <http://purl.org/ontology/wsf#read> ?read ;
                            <http://purl.org/ontology/wsf#update> ?update ;
                            <http://purl.org/ontology/wsf#delete> ?delete ;
                            ".($this->ws->targetWebservice == "all" ? "<http://purl.org/ontology/wsf#webServiceAccess> ?webServiceAccess ;" : "")."
                            ".($this->ws->targetWebservice != "none" && $this->ws->targetWebservice != "all" ? "<http://purl.org/ontology/wsf#webServiceAccess> <".$this->ws->targetWebservice."> ;" : "")."
                            <http://purl.org/ontology/wsf#datasetAccess> <".$this->ws->dataset."> .
                    }";
          }

          $resultset =
            @$this->ws->db->query($this->ws->db->build_sparql_query(str_replace(array ("\n", "\r", "\t"), " ", $query), array(),
              FALSE));

          if(odbc_error())
          {
            $this->ws->conneg->setStatus(500);
            $this->ws->conneg->setStatusMsg("Internal Error");

            if(strtolower($this->ws->mode) == "access_user" || strtolower($this->ws->mode) == "access_dataset")
            {
              $this->ws->conneg->setStatusMsgExt($this->ws->errorMessenger->_302->name);
              $this->ws->conneg->setError($this->ws->errorMessenger->_302->id, $this->ws->errorMessenger->ws,
                $this->ws->errorMessenger->_302->name, $this->ws->errorMessenger->_302->description, odbc_errormsg(),
                $this->ws->errorMessenger->_302->level);
            }
            else
            {
              $this->ws->conneg->setStatusMsgExt($this->ws->errorMessenger->_303->name);
              $this->ws->conneg->setError($this->ws->errorMessenger->_303->id, $this->ws->errorMessenger->ws,
                $this->ws->errorMessenger->_303->name, $this->ws->errorMessenger->_303->description, odbc_errormsg(),
                $this->ws->errorMessenger->_303->level);
            }

            return;
          }
          
          $accessPreviousId = "";
          
          $subject = null;

          while(odbc_fetch_row($resultset))
          {
            $accessId = odbc_result($resultset, 1);
            
            if($accessPreviousId != $accessId)
            {
              if($subject != null)
              {
                $this->ws->rset->addSubject($subject);
              }
              
              $subject = new Subject($accessId);
              $subject->setType("wsf:Access"); 
              
              $accessPreviousId = $accessId;
            
              $lastElement = "";

              if(strtolower($this->ws->mode) == "access_user")
              {                
                $subject->setObjectAttribute("wsf:datasetAccess", odbc_result($resultset, 2), null, "void:Dataset");  
                $subject->setDataAttribute("wsf:create", odbc_result($resultset, 3));
                $subject->setDataAttribute("wsf:read", odbc_result($resultset, 4));
                $subject->setDataAttribute("wsf:update", odbc_result($resultset, 5));
                $subject->setDataAttribute("wsf:delete", odbc_result($resultset, 6));
                $subject->setObjectAttribute("wsf:groupAccess", odbc_result($resultset, 7), null, 'wsf:Group');
                                                    
                if($this->ws->targetWebservice == "all")
                {                                                    
                  $subject->setObjectAttribute("wsf:webServiceAccess", odbc_result($resultset, 8), null, "wsf:WebService");  
                }
              }
              else // access_dataset
              {
                $subject->setObjectAttribute("wsf:groupAccess", odbc_result($resultset, 2), null, 'wsf:Group');
                $subject->setDataAttribute("wsf:create", odbc_result($resultset, 3));
                $subject->setDataAttribute("wsf:read", odbc_result($resultset, 4));
                $subject->setDataAttribute("wsf:update", odbc_result($resultset, 5));
                $subject->setDataAttribute("wsf:delete", odbc_result($resultset, 6));
                                                                                                        
                                                    
                if($this->ws->targetWebservice == "all")
                {                                                    
                  $subject->setObjectAttribute("wsf:webServiceAccess", odbc_result($resultset, 7), null, "wsf:WebService");                    
                }
              }            
            }
            else
            {
              if(strtolower($this->ws->mode) == "access_user")
              {              
                $subject->setObjectAttribute("wsf:webServiceAccess", odbc_result($resultset, 8), null, "wsf:WebService");  
              }
              else // access_dataset
              {
                $subject->setObjectAttribute("wsf:webServiceAccess", odbc_result($resultset, 7), null, "wsf:WebService");  
              }
            }
          }
          
          // Add the last subject
          $this->ws->rset->addSubject($subject);
        }
      }    
    }  
  }
?>
