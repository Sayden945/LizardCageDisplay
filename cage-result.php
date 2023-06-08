<!DOCTYPE html>
<!-- 

// CAGE-RESULT, Page for outputting the results from an entered CageID for the project
// 9785 - 23 - 05 | Capstone Project | Lizard Cage Display | 2023
// Capstone Project for the University of Canberra
// Programmed by Ayden Smith & Anthony Walker

-->

<!-- BEGINNING OF PROGRAM -->
<html lang="">
<head>
    <title>LCD - Cage</title>

<!-- Buttons for Return (Go back to Homepage), Reset (Refresh the page for new input) -->
<div id="return">
    <form method="post" action="main.php">
        <input type="submit" value="RETURN">
    </form>
</div>

 <div id="reset">
    <form method="post" action="cage-result.php">
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
            position: absolute;
            align-items: center;
            top: 8px;
            color: white;
        }

        table td {
            border: 1px solid white;
            padding: 5px;
        }
        table th {
            background-color: white;
            color: black;
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

        td, tr {
            text-transform: capitalize;
            font-size: 18px;
        }

        #flexTable {
            display: flex;
            justify-content: center;
            align-items: center;
            border-collapse: collapse;
            width: 98%;
            height:30%;
            padding-top: 60px;
        }

        #input, #input2 {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding-top: 2%;
            font-size: 20px;
        }

    </style>
</head>
<body>

<!-- BEGINNING OF PHP SCRIPT -->
<?php

// Connect to the database
//Deleted DB login information
$hostname = "";
$username = "";
$password = "";
$db = "";
$dbconnect = mysqli_connect($hostname, $username, $password, $db);
if (!$dbconnect) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_GET['error'])) {
    echo "<h2 id='input2'>Incorrect value entered. Please try again.</h2>";
}

if (isset($_GET['NewCageID'])) {
    //$roomID = mysqli_real_escape_string($dbconnect, $_GET['RoomID']);
    $newCageID = mysqli_real_escape_string($dbconnect, $_GET['NewCageID']);

    //SQL Query Below
    //Selects the NewCageID from H_cages that matches the user inputted CageID
    //Then calls A_specimens for SpecimenID, Family, Genus, Genotype, Species, Sex, Maturity and Purpose
    $query = "SELECT H_cages.NewCageID AS CageID, 
    A_specimens.SpecimenID AS Lizard, COALESCE(A_specimens.Family, 'N/A') AS Family, COALESCE(A_specimens.Genus, 'N/A') AS Genus, COALESCE(A_specimens.Genotype, 'N/A') AS Genotype, COALESCE(A_specimens.Species, 'N/A') AS Species, COALESCE(A_specimens.Sex, 'N/A') AS Sex, COALESCE(A_specimens.Maturity, 'N/A') AS Maturity, H_cages.Purpose AS Purpose FROM H_cages 
    LEFT JOIN A_specimens ON H_cages.SpecimenID = A_specimens.SpecimenID WHERE H_cages.NewCageID = '$newCageID'";
    

    //Calls to the query, using the details from dbconnect
    $result = mysqli_query($dbconnect, $query);

    //If the entered Room ID is not found, reset page
    if (mysqli_num_rows($result) == 0) {
        $url = "cage-result.php?error=1";
        header("Location: $url");
    }

    //If for whatever reason the query doesn't work, catch it
    if (!$result) {
        die("Query failed: " . mysqli_error($dbconnect));
    }

    //Function for the RETURN button to go back to the homepage
    else if (isset($_POST['RETURN'])) {
        $url = "main.php";
        header("Location: $url"); // redirect the user to the room.php script
    }
    
    //Function for the RESET button to refresh the page for new input
    else if (isset($_POST['RESET CAGE'])) {
        $url = "cage-result.php";
        header("Location: $url"); // redirect the user to the room.php script
    }

    $row = mysqli_fetch_array($result);

    //Beginning of table, starting with the CageID
    echo "<table id='flexTable'>";
    echo "<tr>";
    echo "<th colspan=7>CageID: {$row['CageID']}</th>";
    echo "<tr>";

    //Subheadings with hardcoded values
    echo "<tr>";
    echo "<th colspan=2>Project Support: Ayisha/Lisa</th>"; 
    echo "<th colspan=2>Facility Support: Chelsea Steele</th>"; 
    echo "<th colspan=2>AEC: AEC4562</th>"; 
    echo "</tr>";

    //Initialize the rows
        echo "<tr>";
        echo "<th>SpecimenID</th>";
        echo "<th>Genus/Species</th>";
        echo "<th>Sex</th>";
        echo "<th>Genotype</th>";
        echo "<th>Maturity</th>";
        echo "<th>Purpose</th>";
        echo "</tr>";


        //Sets current data to read in as 0, was skipping by 1 before adding this
        mysqli_data_seek($result, 0);

    //While loop retrieves the data from $result until a condition is met
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>{$row['Lizard']}</td>";
        echo "<td>{$row['Genus']}/{$row['Species']}</td>";
        echo "<td>{$row['Sex']}</td> ";
        echo "<td>{$row['Genotype']}</td>";
        echo "<td>{$row['Maturity']}</td>";
        echo "<td>{$row['Purpose']}</td>";
        echo "</tr>";
    }

   echo "</table>";

    mysqli_close($dbconnect);


//Input Form Below
} else {

    echo "<p id='input'>Please enter a Cage ID below: </p>";
    echo "<form id='input' method='GET' action=''>";
    echo "<label for='NewCageID'>Enter a New Cage ID: </label>";
    echo "<input type='text' name='NewCageID' id='NewCageID'><br>";
    echo "<input id='inputButton' type='submit' value='Submit'>";
    echo "</form>";
}

?>

</body>
</html>
<!-- END OF PROGRAM -->