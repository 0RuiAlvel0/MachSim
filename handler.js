//handler.js handles data transfer and display options

//General functions
function get_current_machines_state() {
	$.ajax({
		type: "POST",
		url: "Handler.php",
		data: {method: "get_current_machines_state"},
		dataType: "json",
		async: false,
		success: function (data) {
			if (!data['error']) {
				if (data['num_machines'] > 0) {
					//populate the table with static machine data (machine and parameter information, not values).
					for (i = 1; i <= data['num_machines']; i++) {
						table_string = '<tr>';

						table_string = table_string + '<td>';
						table_string = table_string + data[i]['name'] + ' - id: ' + data[i]['id'] + '<br />';
						table_string = table_string + '<small>';
						table_string = table_string + '</small>';
						table_string = table_string + '</td>';

						for (j = 1; j <= $("#max_num_parameters").val() - 2; j++) {
							if (j <= data[i]['num_parameters']) {
								table_string = table_string + '<td> 234.4 <br />';
								table_string = table_string + '<small>';
								table_string = table_string + data[i][j]['name'] + '<br />';
								table_string = table_string + 'Nmin: ' + data[i][j]['normal_min'];
								table_string = table_string + ' Nmax: ' + data[i][j]['normal_max'];
								table_string = table_string + '<br/>Abmin: ' + data[i][j]['abnormal_min'];
								table_string = table_string + ' Abmax: ' + data[i][j]['abnormal_max'];
								table_string = table_string + '</small></td>';
							}
							else
								table_string = table_string + '<td>NA</td>';
                        }

						table_string = table_string + '<td>';
						table_string = table_string + data[i]['is_running'];
						table_string = table_string + data[i]['has_fault'];
						table_string = table_string + '</td>';

						table_string = table_string + '</tr>';

						$('#current_machines_table tbody').append(table_string);
						
					}
					
				}
				else {
					//No machines are defined
					no_machines_warning = '<div class="alert alert-warning" role="alert">No machines defined, add below.</div>';
					$("#current_machines_table").find("tr:gt(0)").remove();
					$('#current_machines_table tbody:last-child').after('<tr><td colspan="'+$("#max_num_parameters").val()+'">' + no_machines_warning + '</td></tr>');
                }
			}
		}
	});
	return false;
};

function manage_running_button(machine_id) {
	//toggle running status
	machine_id = machine_id.split("_");
	$("#m_" + machine_id[1] + "_div").html("Loading...");
	$.ajax({
		type: "POST",
		url: "Handler.php",
		data: { method: "toggle_running_status", machine_id: machine_id[1]},
		dataType: "json",
		async: false,
		success: function (data) {
			if (data['current_status'] == 1) {
				//current status is on, change the button to on
				$("#m_" + machine_id[1] + "_div").html('<button type="button" class="btn btn-danger running_button" id="m_' + machine_id[1] + '">Running</button>');
			};
			if (data['current_status'] == 0) {
				//current statis is off, change the button to off
				$("#m_" + machine_id[1] + "_div").html('<button type="button" class="btn btn-success running_button" id="m_' + machine_id[1] + '">Stopped</button>');
            }
		}
	});
	return false;
}

function manage_fault_button(machine_id) {
	//toggle running status
	machine_id = machine_id.split("_");
	$("#m_" + machine_id[1] + "_div_fault").html("Loading...");
	$.ajax({
		type: "POST",
		url: "Handler.php",
		data: { method: "toggle_fault_status", machine_id: machine_id[1] },
		dataType: "json",
		async: false,
		success: function (data) {
			if (data['current_fault_status'] == 1) {
				//current status is on, change the button to on
				$("#m_" + machine_id[1] + "_div_fault").html('<button type="button" class="btn btn-danger fault_button" id="m_' + machine_id[1] +'_fault">Has fault</button>');
			};
			if (data['current_fault_status'] == 0) {
				//current statis is off, change the button to off
				$("#m_" + machine_id[1] + "_div_fault").html('<button type="button" class="btn btn-success fault_button" id="m_' + machine_id[1] + '_fault">No fault</button>');
			}
		}
	});
	return false;
}


//Fill in everything after document loads
$(document).ready(function () {
	//get current machine states
	get_current_machines_state();

	$(".running_button_div").click(function () {
		manage_running_button(this.id);
	});

	$(".fault_button_div").click(function () {
		manage_fault_button(this.id);
	});


});