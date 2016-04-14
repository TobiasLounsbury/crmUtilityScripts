<?php

/**
 * This is an example of a script that updates an existing membership
 * type, that already has data associated with it, such that the UI will
 * not allow modification of the relationship type.
 *
 * It sets the membership type to be inherited by the employees
 * and triggers recalculation of the inherited memberships by
 * running an update on the individual relationships.
 *
 * The original use case for this script processed 4500 relationships
 * and triggered the creation of new membership for all of them in roughly 2 minutes.
 */

//Give the script a name that will be shown in the interface.
$UtilityScripts["UpdateExistingMembershipType"] = function() {

  /*
   * Get a reference to the Employee/Employer relationship type
   */
  $relationshipType = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Employee of', 'id', 'name_a_b');

  /*
   * A reference variable for the relationship direction
   */
  $relationshipDirection = "b_a";

  /*
   * There is no membership type in the civi demo data that
   * exactly fits the niche for which this script was made.
   * So I'm setting it to 1 in case someone wants to run this
   * script to see what it does. That is by default the General
   * Membership Type
   */
  $membershipTypeId = 1;

  /*
   * Only process the relationships if the membership type has been successfully updated
   */
  $update = false;

  try {
    //Update the membership type to use "employer of" relationship type
    // for inherited memberships
    $result = civicrm_api3('MembershipType', 'create', array(
      'sequential' => 1,
      'id' => $membershipTypeId,
      'relationship_type_id' => $relationshipType,
      'relationship_direction' => $relationshipDirection,
    ));
    if($result['is_error'] == 0) {
      //Everything worked, lets go ahead and process the relationships
      $update = true;
    }
  } catch (Exception $e) {
    CRM_Core_Session::setStatus($e->getMessage(), "Error", "error");
    return;
  }

  if($update) {

    //Fetch all the current employee/employer relationships
    $relationships = civicrm_api3('Relationship', 'get', array(
      'sequential' => 1,
      'relationship_type_id' => $relationshipType,
      'options' => array('limit' => 0),
    ));

    if ($relationships['is_error'] == "0" && $relationships['count'] > 0) {

      $pass = 0;
      $fail = 0;

      foreach($relationships['values'] as $relationship) {

        try {
          //Update the relationship with no changes to the data
          //This triggers a recalculation of the inherited membership
          civicrm_api3('Relationship', 'create', $relationship);
          $pass++;
        } catch (Exception $e) {
          echo $relationship['id'].": ". $e->getMessage() ."\n";
          $fail++;
        }
      }

      //Display the summary of results in the console
      echo "\n------------------------------\n";
      echo "Successfully processed $pass records\n";
      echo "Encountered $fail errors";

      //Set a status to indicate the overall result
      if($fail == 0) {
        //If no errors show a success type notification
        CRM_Core_Session::setStatus("Job Completed Successfully", "Success", "success", array("expires" => 0));
      } else {
        //If there are some errors display a warning style notification
        CRM_Core_Session::setStatus("Job Completed with errors. See console for details", "Complete", array("expires" => 0));
      }

    } else {
      CRM_Core_Session::setStatus("No Employee relationships found to update", "Error", "error");
    }
  }
};