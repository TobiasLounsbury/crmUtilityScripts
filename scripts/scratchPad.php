<?php

/**
 * This is just an example of one way I have used Utility Scripts in the past.
 * I keep a throw-away file in my Project that I can use for testing the output
 * of small snippets of code in isolated scopes.
 **/ 
$UtilityScripts['ScratchPad'] = function() {
  echo "The Employee relatioinshipt_type_id is: ". CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Employee of', 'id', 'name_a_b'). "\n";

  echo "The Primary Contact relationship_type_id is: ". CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Primary Contact of', 'id', 'name_a_b'). "\n";

};
