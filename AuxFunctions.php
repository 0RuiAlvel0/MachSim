<?php
//Defines frequently called functions

//Start items related to the configuration database

include 'Config.php';

function connect_to_config_db(){

    global $config_db_server;
    global $config_db_user;
    global $config_db_pass;
    global $config_db_name;

    // Create connection
    $conn = new mysqli($config_db_server, $config_db_user, $config_db_pass, $config_db_name);

    // Check connection
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
    else
    	return $conn;
}

function get_array_of_machine_ids(){
    //Gets an array of ids for all currently defined machines
    $conn = connect_to_config_db();

    $sql = "SELECT * FROM machine_info";
    $result = mysqli_query($conn, $sql);
    $output = array();
    $conn->close();
    if (mysqli_num_rows($result) > 0){
        //We have at least 1 machine on the database, create array of machine ids to return
        //from this function
        while($row = $result->fetch_assoc()) {
            $output[$row["id"]] = $row["id"];
        }
        return $output;
    }
    else
        return false;
}

function get_machine_data($machine_id){
    //Consider for now that we will only be here if the machine actually exists, no
    //tests are done inside.
    $conn = connect_to_config_db();
    $sql1 = "SELECT * FROM machine_info WHERE id = ".$machine_id;
    $sql2 = "SELECT * FROM machine_parameters WHERE machine_id = ".$machine_id;

    //This next variable is what we are returning.
    $machine_data = array();

    //First, deal with machine data:
    $result = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result) > 0){
        while($row = $result->fetch_assoc()) {
            $machine_data[0]["id"] = $row["id"];
            $machine_data[0]["name"] = $row["name"];
            $machine_data[0]["is_running"] = $row["is_running"];
            $machine_data[0]["has_fault_trigger"] = $row["has_fault_trigger"];
            $machine_data[0]["num_parameters"] = 0;
            $machine_data[0]["last_start_time"] = $row["last_start_time"];
            $machine_data[0]["last_stop_time"] = $row["last_stop_time"];
            $machine_data[0]["start_duration"] = $row["start_duration"];
            $machine_data[0]["stop_duration"] = $row["stop_duration"];
        }
    }

    //Second collect information from the machine parameters.
    $result = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result) > 0){
        while($row = $result->fetch_assoc()) {
            $machine_data[0]["num_parameters"]++;
            $machine_data[$machine_data[0]["num_parameters"]]["id"] = $row['id'];
            $machine_data[$machine_data[0]["num_parameters"]]["name"] = $row['name'];
            $machine_data[$machine_data[0]["num_parameters"]]["setpoint"] = $row['setpoint'];
            $machine_data[$machine_data[0]["num_parameters"]]["normal_min"] = $row['normal_min'];
            $machine_data[$machine_data[0]["num_parameters"]]["normal_max"] = $row['normal_max'];
            $machine_data[$machine_data[0]["num_parameters"]]["abnormal_min"] = $row['abnormal_min'];
            $machine_data[$machine_data[0]["num_parameters"]]["abnormal_max"] = $row['abnormal_max'];
            $machine_data[$machine_data[0]["num_parameters"]]["relation_to_id"] = $row['relation_to_id'];
            $machine_data[$machine_data[0]["num_parameters"]]["relation_type"] = $row['relation_type'];
        }
    }

    $conn->close();

    if(!empty($machine_data))
        return $machine_data;
    else
        return false;
}

function save_machine_value($machine_id, $is_running, $has_fault_trigger){

    $conn = connect_to_config_db();

    $sql = "UPDATE machine_info SET is_running = '$is_running', has_fault_trigger = '$has_fault_trigger' WHERE id = '$machine_id'";

    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

//End "Items related to the configuration database

//Start items related to the database where generated data is to be stored

function connect_to_data_db(){

    global $data_db_server;
    global $data_db_user;
    global $data_db_pass;
    global $data_db_name;

    // Create connection
    $conn = new mysqli($data_db_server, $data_db_user, $data_db_pass, $data_db_name);

    // Check connection
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
    else
    	return $conn;
}

function get_last_parameter_value($location_id, $p_id){
    //gets the last parameter value and trend
    $conn = connect_to_data_db();

    $sql = "SELECT * FROM sensor_data WHERE (location_id = ".$location_id." AND sensor_id = ".$p_id.") ORDER BY `time` DESC LIMIT 5";

    $result = $conn->query($sql);

    $output = array();
    $conn->close();

    if ($result && mysqli_num_rows($result) > 0){

        $counter = 0;
        while($row = $result->fetch_assoc()) {
            $output[$counter] = $row["value"]."-%%%-".$row["fault_message"];
            $counter++;
        }
        echo "<br />";

        //of we have at least x values, we can guess a trend (just subtract the first from the last. If negative we're up if positive, we're down):
        //if($counter >= 2){
            //if(reset($output) - end($output) <= 0)
                //$output['trend'] = "up";
            //else
                //$output['trend'] = "down";
        //}

        return $output;
    }
    else
        return false;
}

function save_parameter_value($location_id, $timestamp, $p_id, $value, $p_normal_min, $p_normal_max, $p_setpoint, $fault_message, $is_running){

    $conn = connect_to_data_db();

    //echo "Will add ts: $timestamp, l_id: $location_id, s_id: $p_id, val: $value, nmin: $p_normal_min, nmax: $p_normal_max, setpoint: $p_setpoint, fault: $fault_message, is_running: $is_running";

    $sql = "INSERT INTO sensor_data (time, location_id, sensor_id, value, normal_min, normal_max, setpoint, fault_message, is_running) VALUES ('$timestamp', '$location_id', '$p_id', '$value', '$p_normal_min', '$p_normal_max', '$p_setpoint', '$fault_message', '$is_running')";

    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

//End "Items related to the database where generated data is to be stored"

?>