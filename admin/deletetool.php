<?php include_once("resources/setupinfo.php"); ?>

<?php

// Setup variables
$name = "";
$asset = $_GET['asset'];
$error = FALSE;
$success = FALSE;

// Check name
if (isset($_POST['deleteButton'])) {
    $name = "Temp";
}

// Submit info to DB
if ($error == FALSE && $name != "") {

    // Set DB login info
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "unispace";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // prepare sql and bind parameters
        $stmt = $conn->prepare("DELETE FROM tools WHERE asset = :asset");
        
        // Prepare row
        $stmt->bindParam(':asset', $assetInsert);

        // Insert row
        $assetInsert = $asset;

        // Execute statement
        $stmt->execute();

        $success = TRUE;

    } catch(PDOException $e) {
        //$error = TRUE;
    }

    // Close connection to DB
    $conn = null;
}

// Set DB login info
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "unispace";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM tools WHERE asset = :asset"); 

    // Prepare row
    $stmt->bindParam(':asset', $assetInsert);

    // Insert row
    $assetInsert = $asset;

    $stmt->execute();

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    $data = $stmt->fetchAll();

    $name_field = $data[0]['name'];
    $asset_field = $data[0]['asset'];

}
catch(PDOException $e) {}

// Close connection to database
$conn = null;

?>

<!DOCTYPE html>
<html>
    <head>

        <!-- CSS -->
        <?php if ($system_theme == "dark"): ?>
            <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/darkly/bootstrap.min.css" rel="stylesheet" integrity="sha384-rCA2D+D9QXuP2TomtQwd+uP50EHjpafN+wruul0sXZzX/Da7Txn4tB9aLMZV4DZm" crossorigin="anonymous">
        <?php else: ?>
            <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/flatly/bootstrap.min.css" rel="stylesheet" integrity="sha384-yrfSO0DBjS56u5M+SjWTyAHujrkiYVtRYh2dtB3yLQtUz3bodOeialO59u5lUCFF" crossorigin="anonymous">
        <?php endif; ?>
        
        <!-- FontAwesome -->
        <script src="https://kit.fontawesome.com/369f05b1b5.js" crossorigin="anonymous"></script>

        <!-- Other Meta -->
        <title><?php echo $system_name; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

    </head>
    <body>

        <?php include_once("resources/navigation.php"); ?>

        <br />

        <div class="container">

            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <br />
                        <?php if ($error == TRUE): ?>
                            <div class="alert alert-danger" role="alert">
                                An error occured. Please try again later. <a href="managetools.php">Go back to manage tools page</a>
                            </div>
                        <?php elseif ($success == TRUE): ?>
                            <div class="alert alert-success" role="alert">
                                The tool has been successfully deleted. <a href="managetools.php">Go back to manage tools page</a>
                            </div>
                        <?php else: ?>
                            <br />
                            <div style="text-align: center;">
                                <h4>Delete Tool</h4>
                                <br />
                                    <p>Are you sure that you want to remove <b><?php echo $name_field; ?> (Asset # <?php echo $asset_field; ?>)</b>?</p>
                                <br />                  
                                <form action="deletetool.php?asset=<?php echo $asset; ?>" method="POST">
                                        <a href="managetools.php" class="btn btn-primary">Cancel</a>
                                        <button type="submit" class="btn btn-danger" name="deleteButton">Delete Tool</button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                <div class="col-sm-3"></div>
            </div>
                
        </div>

        <br />

        <?php include_once("resources/footer.php"); ?>

        <!-- JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script>

            function downloadCSV(csv, filename) {
                var csvFile;
                var downloadLink;
                // CSV file
                csvFile = new Blob([csv], {type: "text/csv"});
                // Download link
                downloadLink = document.createElement("a");
                // File name
                downloadLink.download = filename;
                // Create a link to the file
                downloadLink.href = window.URL.createObjectURL(csvFile);
                // Hide download link
                downloadLink.style.display = "none";
                // Add the link to DOM
                document.body.appendChild(downloadLink);
                // Click download link
                downloadLink.click();
            }

            function downloadSheet() {
                var csv = [];
                var rows = document.querySelectorAll("table tr");
                
                for (var i = 0; i < rows.length; i++) {
                    var row = [], cols = rows[i].querySelectorAll("td, th");
                    
                    for (var j = 0; j < cols.length; j++) 
                        row.push(cols[j].innerText);
                    
                    csv.push(row.join(","));        
                }
                // Download CSV file
                downloadCSV(csv.join("\n"), "toolsout.csv");
            }

        </script>
    </body>
</html>