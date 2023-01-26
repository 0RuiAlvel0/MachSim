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
        $machine_number = 1;
        foreach($machine_ids as $key => $value){
            $machine_data = get_machine_data($value);

            //machine data
            $output[$machine_number]['id'] = $value;
            $output[$machine_number]['name'] = $machine_data[0]['name'];
            if($machine_data[0]['is_running'])
                $output[$machine_number]['is_running'] = '<button type="button" class="btn btn-danger">Running</button>';
            else
                $output[$machine_number]['is_running'] = '<button type="button" class="btn btn-success">Stopped</button>';
            $output[$machine_number]['num_parameters'] = $machine_data[0]['num_parameters'];
            $output[$machine_number]['last_start_time'] = $machine_data[0]['last_start_time'];
            $output[$machine_number]['last_stop_time'] = $machine_data[0]['last_stop_time'];
            $output[$machine_number]['start_duration'] = $machine_data[0]['start_duration'];
            $output[$machine_number]['stop_duration'] = $machine_data[0]['stop_duration'];

            //machine parameter data
            for($aux = 1; $aux <= $output[$machine_number]['num_parameters']; $aux++){
                $output[$machine_number][$aux]['id'] = $machine_data[$aux]['id'];
                $output[$machine_number][$aux]['name'] = $machine_data[$aux]['name'];
                $output[$machine_number][$aux]['setpoint'] = $machine_data[$aux]['setpoint'];
                $output[$machine_number][$aux]['normal_min'] = $machine_data[$aux]['normal_min'];
                $output[$machine_number][$aux]['normal_max'] = $machine_data[$aux]['normal_max'];
                $output[$machine_number][$aux]['abnormal_min'] = $machine_data[$aux]['abnormal_min'];
                $output[$machine_number][$aux]['abnormal_max'] = $machine_data[$aux]['abnormal_max'];
                $output[$machine_number][$aux]['relation_to_id'] = $machine_data[$aux]['relation_to_id'];
                $output[$machine_number][$aux]['relation_type'] = $machine_data[$aux]['relation_type'];
            }
            $machine_number++;
        }

    }

    echo json_encode($output);
}

?>