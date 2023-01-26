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
						table_string = table_string + 'Tstart: ' + data[i]['time_to_start'];
						table_string = table_string + ' Tstop: ' + data[i]['time_to_stop'];
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
								table_string = table_string + ' Abmax: ' + data[i][j]['abnormal_min'];
								table_string = table_string + '</small></td>';

							}
							else
								table_string = table_string + '<td>NA</td>';
                        }

						table_string = table_string + '<td>';
						table_string = table_string + data[i]['is_running'];
						table_string = table_string + ' <button type="button" class="btn btn-outline-success">Healthy</button>';
						table_string = table_string + '</td>';

						table_string = table_string + '</tr>';
						$('#current_machines_table tbody:last-child').after(table_string);
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

//Fill in everything after document loads
$(document).ready(function () {
	//get current machine states
	get_current_machines_state();
});