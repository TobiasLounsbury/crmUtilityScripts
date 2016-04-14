# Utility Scripts

This extension is a front-end for running "one-off" scripts after civi has been bootstrapped. 
It creates a small interface that scans a local directory for php files, includes them and allows you to choose to run them. This is particularly useful for things that you only need to do once, but don't want to do manually. A loop of items that spawn 3 api calls each. This would be tedious to do by hand, and potentially dangerous to do directly in the database, especially on a production site. UtilityScripts lets you run a test on a local machine and know that once you upload the final version is will run exactly the same code as it ran on the local instance.

Output from scripts is buffered into a console like output, so you can create nice text reporting for errors and summary of output.



###Usage
* Install this extension
* Drop a script in the scripts sub-folder of this extension. 
* Navigate to civicrm/utils/scripts (or use the menu: Administer->Administration Console->Utility Scripts)
* Select your script
* Run the script

###Options
The interface gives you the ability to set the timeout if a script is going to process many records or do heavy lifting, you can set the timeout at runtime. 

### Example
General usage works like this:
```php
$UtilityScripts["Example"] = function() {
  //Do whatever, Access the API, Include other files, libraries as needed, etc.
};
```
See scripts/example.php
