<?php
//Responsible for generating data depending on rules and requests and placing that data on the
//data database. For now it runs every 15 seconds. Frequency may be increased in the future.

include 'AuxFunctions.php';

//Just go and run this.
lets_go();

function lets_go(){

    global $location_id;

    //cycle through the defined machines:
    $machine_ids = get_array_of_machine_ids();
    if($machine_ids){
        //we have machines, lets see if and how we should generate the next value for each parameter on each machine
        //format is $machine_ids["id"] = id;
        //Get current data for each machine on the list unless machine is off.
        $machine_number = 1;
        foreach($machine_ids as $key => $value){
            $machine_data = get_machine_data($value);
            //if($machine_data[0]['is_running']){
                //The machine is running we should generate data, proceed
                for($aux = 1; $aux <= $machine_data[0]['num_parameters']; $aux++){
                    $p_id = $machine_data[$aux]['id'];
                    //$output[$machine_number][$aux]['name'] = $machine_data[$aux]['name'];
                    $p_setpoint = $machine_data[$aux]['setpoint'];
                    $p_normal_min = $machine_data[$aux]['normal_min'];
                    $p_normal_max = $machine_data[$aux]['normal_max'];
                    $p_abnormal_min = $machine_data[$aux]['abnormal_min'];
                    $p_abnormal_max = $machine_data[$aux]['abnormal_max'];
                    $p_relation_to_id = $machine_data[$aux]['relation_to_id'];
                    $p_relation_type = $machine_data[$aux]['relation_type'];

                    $value = 0.0;
                    $fault_message = "none";
                    if(!$machine_data[0]['has_fault_trigger']){
                        //we do not have any fault triggers generate normal values
                        $value = generate_normal_value($p_id, $p_normal_min, $p_normal_max, $p_relation_to_id, $p_relation_type, $p_setpoint, $machine_data[0]['is_running']);
                    }
                    else{
                        //we have a fault trigger, generate value with fault with high probability
                        $fault_message = generate_status($p_id);
                        //$value = generate_abnormal_value();
                        $value = generate_normal_value($p_id, $p_normal_min, $p_normal_max, $p_relation_to_id, $p_relation_type, $p_setpoint, $machine_data[0]['is_running']);
                    }
                    //place value on data database:
                    data_to_db(date("Y-m-d H:i:s", time()), $p_id, $value, $p_normal_min, $p_normal_max, $p_setpoint, $fault_message, $machine_data[0]['is_running']);
                }
                //For now no faults are allowed at start up and shutdown, to take care of that first:
                //***later
            //}
            $machine_number++;
            echo "OK";
        }
    }
}

function generate_normal_value($p_id, $p_normal_min, $p_normal_max, $p_relation_to_id, $p_relation_type, $p_setpoint, $is_running){

    global $location_id;

    $output = 0.0;
    //check the previous value, generate new within the normal range but not widely different
    $last_value = get_last_parameter_value($location_id, $p_id);
    if($last_value && $is_running){
        //$last_value is an array $last_value[0 to 4] = last 5 values + $last_value['trend'] = "up" or "down".
        $randomness = (float) rand() / (float) getrandmax(); //number between 0 and 1

        $last_value = explode("-%%%-", reset($last_value));

        if ($last_value[0] - $p_setpoint > 0)
            $output = $last_value[0] + ($p_setpoint - $last_value[0]) * $randomness - ($p_setpoint/ 4) * $randomness;
        else
            $output = $last_value[0] + ($p_setpoint - $last_value[0]) * $randomness + ($p_setpoint/ 4) * $randomness;
    }
    else{
        //we do not have a last value defined for this parameters, just set to the normal_min
        $output = $p_normal_min;
    }
    //prioritize increase until normal max is reached, then prioritize decrease
    //check associated value go up if it goes up, down if it goes down
    //echo " ".$output."<br ./>";
    return $output;
    //echo "The value this time: ".$output;
}

function generate_abnormal_value(){
    // We can make it easy and create a "out of range" fault status here.
}
function generate_status($p_id){
    global $location_id;
    $fault_message = "none";
    //Here you can simulate the status of parameters your machine may generate at a given random frequency.
    //Check if we have a previous fault trigger and choose one randomnly if not. Continue the provious fault if it is present.
    $last_value = get_last_parameter_value($location_id, $p_id);

    $last_value = explode("-%%%-", reset($last_value));

    //$last_value[1] contains the last fault message - codes are below:
    $errors = array("xgroove", "slackrope", "uppersoft", "lowersoft", "upperhard", "lowerhard");

    $fault_message = $last_value[1];
    if($last_value[1] == "none" || $last_value[1] == ""){
        //no fault previously recorded, select one randomly
        $fault_message = array_rand(array_flip($errors), 1);
        //$fault_message = "xgroove";
    }

    //Some status may generate a fault state and a message
    //x-groove active/ not active < generates fault too (fault_message = "xgroove")
    //slack rope active/ not active < generates fault too (fault_message = "slackrope")
    //Upper soft limit reached/ not reached < generates fault too (fault_message = "uppersoft")
    //Lower soft limit reached/ not reached < generates fault too (fault_message = "lowersoft")
    //Upper hardlimit reached/ not reached < generates fault too (fault_message = "upperhard")
    //Lower hardlimit reached/ not reached < generates fault too(fault_message = "lowerhard")
    return $fault_message;
}

function data_to_db($timestamp, $p_id, $value, $p_normal_min, $p_normal_max, $p_setpoint, $fault_message, $is_running){

    global $location_id;

    save_parameter_value($location_id, $timestamp, $p_id, $value, $p_normal_min, $p_normal_max, $p_setpoint, $fault_message, $is_running);

}

?>