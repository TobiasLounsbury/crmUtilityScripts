<?php

//Give the script a name that will be shown in the interface.
$UtilityScripts["Example"] = function() {

  CRM_Core_Session::setStatus("This is status output from the Example Script", "Example", "success", array("expires" => 0));
  echo "Here we go with some nice console output.";
};