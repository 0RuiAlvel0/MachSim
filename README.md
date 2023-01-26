# MachSim
General machine operation simulator

##Current release status
Import the mach_dbs.sql script to a mySQL db server. This will created 2 databases, only is for machine definitions (the script comes with one machine defined) and another database for data (it comes with a couple of lines as example). Make the Worker.php run with cron. For my tests, I was running it every 5 minutes - don't want to have an hyper-huge database on the tests phase. You can always run the script manually from a browser adn then reloading it. All this assumes that you have apache and php and mysql on your machine and know how to set a folder served from apache. Create a file called "Config.php" - note the capital C - with the following contents (fill with your information):

<?php
//MachSim configuration file. Do not sync with git - that means that you should add it to the .gitignore file!

//Constant definition
//Set the maximum number of parameters that can be set per machine:
$MAX_NUM_PARAMETERS = 5;

//MachSim database related information. This is not the database where data is to be sent. That is further down below.
$config_db_name = "your_data";
$config_db_user = "your_data";
$config_db_server = "your_data";
$config_db_pass = "your_data";

//Database where data is to be sent
$data_db_name = "your_data";
$data_db_user = "your_data";
$data_db_server = "your_data";
$data_db_pass = "your_data";

//be careful, this next one must be unique
$location_id = 1;

?>
 
##Description
MachSim is a php app that simulates the operation of a configurable amount of general virtual machines and allows for the introduction of operational disturbances. The simulation is by variation of the following parameters:

1. Current (float)
2. Voltage (float)
3. Temperature (float)
4. Machine on/ machine off (boolean)

For each machine and for each of the parameters above (except number 4.), a range of normal operational values is set. A range of abnormal parameters is also set.

It is assumed that, by default, a virtual machine is working normally - i.e. all parameters vary within the normal range values according to the following inter-relations and rules:

1. Values are only recorded and used when the machine is on.
2. When voltage increases, current increases and vice-versa.
3. When voltage and/ or current increases, temperature increases with a delay.
4. When voltage and/ or current decreases, temperature decreases with a delay.

Faults are then manually triggered or automatically triggered (at a given set frequency). Although the faults are to be determined externally by another program, they are tipically of the following types:

1. One or more of the parameters was detected out of normal range values.
2. The rules and parameter inter-relations do not hold true.

##Technical requirements

To run MachSim you will need a local or remote environment with Apache, PHP7.4 and MySQL 8 (only tested with these versions, may work with others).