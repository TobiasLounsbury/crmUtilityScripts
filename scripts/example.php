<?php

$UtilityScripts["ScriptExample"] = function() {

  CRM_Core_Session::setStatus("This is status output from the Example Script", "Example", "success");
  echo "Here we go with some nice console output.";
};