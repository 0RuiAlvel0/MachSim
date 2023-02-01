<?php
//Deals with things related to diplayind data to the user on the web interface

//Load some things we know we'll need
include 'AuxFunctions.php';

//Get data from the ajax caller
//Script section to run
$method = $_POST['method'];

if ($method == "get_current_machines_state"){
    //Call information for each machine that is defined and send it all out on condensed way
    $machine_ids = get_array_of_machine_ids();
    $output['num_machines'] = sizeof($machine_ids);
    if($machine_ids){
        //we have results, so we have to prepare the reply
        //format is $machine_ids["id"] = id;
        //Get current data for each machine on the list unless machine is off.
        $machine_counter = 1;
        foreach($machine_ids as $key => $value){
            $machine_data = get_machine_data($value);

            //machine data
            $output[$machine_counter]['id'] = $value;
            $output[$machine_counter]['name'] = $machine_data[0]['name'];

            if($machine_data[0]['is_running'])
                $output[$machine_counter]['is_running'] = '<div id="m_'.$value.'_div" class="running_button_div"><button type="button" class="btn btn-danger running_button" id="m_'.$value.'">Running</button></div>';
            else
                $output[$machine_counter]['is_running'] = '<div id="m_'.$value.'_div" class="running_button_div"><button type="button" class="btn btn-success running_button" id="m_'.$value.'">Stopped</button></div>';

            if($machine_data[0]['has_fault_trigger'])
                $output[$machine_counter]['has_fault'] = '<div id="m_'.$value.'_div_fault" class="fault_button_div"><button type="button" class="btn btn-danger fault_button" id="m_'.$value.'"_fault>Has fault</button></div>';
            else
                $output[$machine_counter]['has_fault'] = '<div id="m_'.$value.'_div_fault" class="fault_button_div"><button type="button" class="btn btn-success fault_button" id="m_'.$value.'_fault">No fault</button></div>';

            $output[$machine_counter]['num_parameters'] = $machine_data[0]['num_parameters'];

            //machine parameter data
            for($aux = 1; $aux <= $output[$machine_counter]['num_parameters']; $aux++){
                $output[$machine_counter][$aux]['id'] = $machine_data[$aux]['id'];
                $output[$machine_counter][$aux]['name'] = $machine_data[$aux]['name'];
                $output[$machine_counter][$aux]['setpoint'] = $machine_data[$aux]['setpoint'];
                $output[$machine_counter][$aux]['normal_min'] = $machine_data[$aux]['normal_min'];
                $output[$machine_counter][$aux]['normal_max'] = $machine_data[$aux]['normal_max'];
                $output[$machine_counter][$aux]['abnormal_min'] = $machine_data[$aux]['abnormal_min'];
                $output[$machine_counter][$aux]['abnormal_max'] = $machine_data[$aux]['abnormal_max'];
                $output[$machine_counter][$aux]['relation_to_id'] = $machine_data[$aux]['relation_to_id'];
                $output[$machine_counter][$aux]['relation_type'] = $machine_data[$aux]['relation_type'];

                //get_last_parameter_value($location_id, $p_id)
                //$output[$machine_counter][$aux]['current_value'] =
            }
            $machine_counter++;
        }

    }

    echo json_encode($output);
}

if($method == "toggle_running_status"){
    $output['error_message'] = "";
    $machine_id = $_POST['machine_id'];
    $output['current_status'] = "0";
    if($machine_data = get_machine_data($machine_id)){
        if($machine_data[0]["is_running"]){
            //Machine is running, set to not running
            save_machine_value($machine_id, 0, $machine_data[0]["is_running"]);
            $output['current_status'] = "0";
        }
        else{
            //Machine is not running, set to running
            save_machine_value($machine_id, 1, $machine_data[0]["is_running"]);
            $output['current_status'] = "1";
        }
    }
    else
        $output['error_message'] = "No such machine";

    echo json_encode($output);
}

if($method == "toggle_fault_status"){
    $output['error_message'] = "";
    $machine_id = $_POST['machine_id'];
    $output['current_fault_status'] = "0";
    if($machine_data = get_machine_data($machine_id)){
        if($machine_data[0]["has_fault_trigger"]){
            //Machine has fault, disable that state
            save_machine_value($machine_id, $machine_data[0]["is_running"], 0);
            $output['current_fault_status'] = "0";
        }
        else{
            //Machine has no fault, set to faulty
            save_machine_value($machine_id, $machine_data[0]["is_running"], 1);
            $output['current_fault_status'] = "1";
        }
    }
    else
        $output['error_message'] = "No such machine";

    echo json_encode($output);
}

?>