<?php
    //Main user interface file for Machine Simulator by https://PFconsult.net
    require ("Config.php");
?>
<!doctype html> 
<html lang="en">
<head> 
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />

    <!-- Load MachSim js handler.js-->
    <script
        src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
        crossorigin="anonymous"></script>
    <script src="handler.js"></script>

    <title>MachSim Machine Simulator</title>
</head>
<body>

    <!--Variables required for displaying information-->
    <input type="hidden" id="max_num_parameters" value="<?php echo $MAX_NUM_PARAMETERS+2; ?>" />

    <!-- Image and text -->
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="Logo.png" class="me-2" height="30" alt="Logo"/>
                <small>MachSim Machine Simulator pfconsult.net </small>
            </a>
        </div>
    </nav>

    <div class="container">
        <a href="Worker.php" target="_blank">Manual trigger</a>
    </div>

    <div class="container">
        <table id="current_machines_table" class="table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <?php for($counter = 1; $counter <= $MAX_NUM_PARAMETERS; $counter++): ?>
                    <th scope="col">P<?php echo $counter; ?></th>
                    <?php endfor; ?>
                    <th scope="col">State</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

   
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>