<?php
//MachSim configuration file. Do not sync with git - that means that you should add it to the .gitignore file!

//Constant definition
//Set the maximum number of parameters that can be set per machine:
$MAX_NUM_PARAMETERS = 5;

//MachSim database related information. This is not the database where data is to be sent. That is further down below.
$config_db_name = "zodiak_mach"; 
$config_db_user = "zodiak_mach";
$config_db_server = "localhost";
$config_db_pass = "deerDsdfkj_98s";

//Database where data is to be sent
$data_db_name = "zodiak_mach_data";
$data_db_user = "zodiak_mach";
$data_db_server = "localhost";
$data_db_pass = "deerDsdfkj_98s";

//be careful, this next one must be unique
$location_id = 1;

?>