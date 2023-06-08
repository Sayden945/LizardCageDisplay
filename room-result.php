<!DOCTYPE html>

<!-- 

// ROOM-RESULT, Page for outputting the results from an entered RoomID for the project
// 9785 - 23 - 05 | Capstone Project | Lizard Cage Display | 2023
// Capstone Project for the University of Canberra
// Programmed by Ayden Smith & Anthony Walker

-->

<!-- BEGINNING OF PROGRAM -->
<html lang="">
<head>
    <title>LCD - Room</title>

    <!-- Buttons for Return (Go back to Homepage), Reset (Refresh the page for new input) -->
    <div id="return">
    <form method="post" action="main.php">
        <input type="submit" value="RETURN">
    </form>
    </div>

    <div id="reset">
    <form method="post" action="room-result.php">
        <input type="submit" value="RESET">
    </form>
    </div>

    <!-- CSS STYLING -->
    <style>
        body {
            background-color: black;
            color: white;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        h2 b {
            color: white;
            align-items: center;
        }

        h1 b {
            color: white;
            align-items: center;
        }

        table td {
            border: 1px solid white;
            padding: 5px;
            font-size: 27px;
        }

        /* Forced the font size larger for Room, as its intended to be viewed on a 24 inch screen*/
        table th {
            background-color: white;
            color: black;
            font-size: 27px;
        }

        #return {
            display:flex;
            position: absolute;
            top: 2px;
            left: 16px;
            height: 20px;
            width: 40px;
            padding-bottom: 2%;
            margin-bottom: 2%;
        }

        #reset {
            display: inline-block;
            position: absolute;
            top: 2px;
            right: 30px;
            height: 20px;
            width: 40px;
            padding-bottom: 2%;
            margin-bottom: 2%;
        }

        #input, #input2 {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding-top: 2%;
            font-size: 20px;
        }

        #flexTable {
            display: flex;
            justify-content: center;
            align-items: center;
            border-collapse: collapse;
            width: 100%;
            padding-top: 30px;
        }

        td, th {
            text-transform: capitalize;
        }

    </style>
</head>
<body>

<!-- BEGINNING OF PHP -->
<?php
// Connect to the database
//Deleted DB login information
$hostname = "";
$username = "";
$password = "";
$db = "";
$dbconnect = mysqli_connect($hostname, $username, $password, $db);

// If datbase connection failed, halt all further commands
if (!$dbconnect) {
    die("Database connection failed: " . mysqli_connect_error());
}

//If the ID entered is not found, output error message and refresh page
if (isset($_GET['error'])) {
    echo "<h2 id='input2'>Incorrect value entered. Please try again.</h2>";
}

//Main function for getting the RoomID Input
if (isset($_GET['RoomID'])) {
    $RoomID = mysqli_real_escape_string($dbconnect, $_GET['RoomID']);

    echo "<table id='flexTable'>";
    echo "<tr>";
    echo "<th colspan=5>RoomID: {$RoomID}</th>";
    echo "</tr>";
    echo "<tr>";
    echo "<th colspan=5>Project Support: Ayisha/Lisa | Facility Support: Chelsea Steele | AEC Approval Number: AEC4562</th>"; 
    echo "</tr>";
            

    //SQL Query
    //Selects the NewCageID from H_cages that matches the user inputted CageID
    //Calls from A_specimens the Genus, Species, and Purpose
    //Separate call to cageList to get the cageListType by matching CageID with the Z_cagelist CageID
   $query = "SELECT H_cages.NewCageID AS CageID,
   COALESCE(A_specimens.Genus, 'N/A') AS Genus, COALESCE(A_specimens.Species, 'N/A') AS Species, H_cages.Purpose AS Purpose, (SELECT Z_cagelist.`Type`
                  FROM Z_cagelist
                  WHERE Z_cagelist.Cage = H_cages.NewCageID) AS cageListType FROM H_cages 
    LEFT JOIN A_specimens ON H_cages.SpecimenID = A_specimens.SpecimenID WHERE H_cages.RoomID = '$RoomID' ORDER BY CageID";

    //Calls to the query, using the details from dbconnect
    $result = mysqli_query($dbconnect, $query);

    //If the entered Room ID is not found, reset page
    if (mysqli_num_rows($result) == 0) {
        $url = "room-result.php?error=1";
        header("Location: $url");
    }

    //If for whatever reason the query doesn't work, catch it
    else if (!$result) {
        die("Query failed: " . mysqli_error($dbconnect));
    }

    //Function for the RETURN button to go back to the homepage
    else if (isset($_POST['RETURN'])) {
        $url = "main.php";
        header("Location: $url"); // redirect the user to the room.php script
    }
    
    //Function for the RESET button to refresh the page for new input
    else if (isset($_POST['RESET ROOM'])) {
        $url = "room-result.php";
        header("Location: $url"); // redirect the user to the room.php script
    }

    //Sets up the table headings
    echo "<tr>";
    echo "<th>CageID</th>";
    echo "<th>Number of Specimens</th>";
    echo "<th>Species/Genus</th>";
    echo "<th>Size</th>";
    echo "<th>Purpose</th>";
    echo "</tr>";

    $prevCageID = null; //initialize previous CageID to null
    $lizardCount = 0;
    mysqli_data_seek($result, 0);

    //While loop retrieves the data from $result until a condition is met
    while ($row = mysqli_fetch_array($result)) {
        $cageID = $row['CageID'];

        //if the CageID is different from the previous CageID, display the table headers for the new CageID
        if ($cageID != $prevCageID) {

            //if there are any rows displayed, close the table
            if ($prevCageID != null) {
                echo "<tr>";
                echo "<td>{$prevCageID}</td>";
                echo "<td>{$lizardCount}</td>";
                echo "<td>{$prevGenus}/{$prevSpecies}</td>";
                echo "<td>{$prevSize}</td>";
                echo "<td>{$prevPurpose}</td>";
                echo "</tr>";
            }
    
            //Reset the Lizard Counter for each table
            $lizardCount = 0;
    
            //Update previous CageID to current CageID
            $prevCageID = $cageID;
            $prevGenus = $row['Genus'];
            $prevSpecies = $row['Species'];
            $prevSize = $row['cageListType'];
            $prevPurpose = $row['Purpose'];
        }
    
        //Increment the lizard count for total specimens per entry
        $lizardCount++; 
    }

    //if there are any rows displayed, close the table for the last time
    if ($prevCageID != null) {
        echo "</tr>";
        echo "</table>";
    }

    mysqli_close($dbconnect);

//Input Form Below
} else {

    echo "<p id='input'>Please enter a Room ID below: </p>";
    echo "<form id='input' method='GET' action=''>";
    echo "<label for='RoomID'>Enter a New Room ID: </label>";
    echo "<input type='text' name='RoomID' id='RoomID'><br>";
    echo "<input id='inputButton' type='submit' value='Submit'>";
    echo "</form>";   
    echo"</div>";
}

?>
</script>
</form>
</div>

</body>
</html>
<!-- END OF PROGRAM -->