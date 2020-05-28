# Utility Scripts

This extension is a front-end for running "one-off" scripts after civi has been bootstrapped. 
It creates a small interface that scans a local directory for php files, includes them and allows you to choose to run them. This is particularly useful for things that you only need to do once, but don't want to do manually. A loop of items that spawn 3 api calls each. This would be tedious to do by hand, and potentially dangerous to do directly in the database, especially on a production site. UtilityScripts lets you run a test on a local machine and know that once you upload the final version is will run exactly the same code as it ran on the local instance.

Output from scripts is buffered into a console like output, so you can create nice text reporting for errors and summary of output.



### Usage
* Install this extension
* Drop a script in the scripts sub-folder of this extension. 
* Navigate to civicrm/utils/scripts (or use the menu: Administer->Administration Console->Utility Scripts)
* Select your script
* Run the script

### Options
The interface gives you the ability to set the timeout if a script is going to process many records or do heavy lifting, you can set the timeout at runtime. 

### Example
Examples can be found in the scripts sub-directory

General usage works like this:
```php
$UtilityScripts["Example"] = function() {
  //Do whatever, Access the API, Include other files, libraries as needed, Call BAO or other core functions, etc.
};
```

#### UpdateExistingMembershipType
This is the only really useful example included. It was designed to update an existing membership type, that already has memberships created, such that the UI doesn't allow modification of the associated relationship type. 
e.g. `"You cannot modify relationship type because there are membership records associated with this membership type."`
It has params at the top for the membership type and relationship type. It updates the membership type, and then does a loop through existing relationships and saves them without modification, thus triggering a recalculation of the inherited memberships. In the original task it was written for, it processed 4300 relationships, and triggered new memberships for 90% of them in a matter of minutes.

