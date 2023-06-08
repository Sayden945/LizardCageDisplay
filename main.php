<?php
// MAIN, Homepage for the project
// 9785 - 23 - 05 | Capstone Project | Lizard Cage Display | 2023
// Capstone Project for the University of Canberra
// Programmed by Ayden Smith & Anthony Walker

//If the button labeled CAGE is clicked, redirect user to the Cage page
if (isset($_POST['CAGE'])) {
    $url = "cage.php";
    header("Location: $url"); // redirect the user to the cage.php script
}

//If the button labeled ROOM is clicked, redirect user to the Cage page
else if (isset($_POST['ROOM'])) {
    $url = "room.php";
    header("Location: $url"); // redirect the user to the room.php script
}

?>

<html>
<head>
<title>LCD - Main</title>
<style>
        body {
            background-color: black;
            color: white;
            width: 100%;
        }


        #roomAndCage {
            display: flex;
            justify-content: center;
            align-items: center;
            /*height: 100%;*/
        }

        #roomAndCage form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: auto;
        }

        #roomAndCage input[type="submit"] {
            width: 400px;
            /*max-width: 400px;*/
            height: 300px;
            font-size: 32px;
            box-sizing: border-box;
            padding: 0;
            margin: auto;
           /* margin-bottom: 20px;*/
        }

        #main {
            text-align: center;
            margin: auto;
        }

        #exit {
            display: inline-block;
            position: absolute;
            margin:auto;
        }

 </style>

</head>

<!-- BEGINNING OF VISIBLE OUTPUT -->
<div id="main">
<h1>Lizard Cage Display</h1>
<p>Capstone Project | 9785-23-05 | 2023</p>
<p>Please select which you would like to be displayed: </br></p>
</div>

<!-- roomAndCage ties the CSS of both buttons together-->
<div id="roomAndCage">
    <form method="post" action="room-result.php">
    <input type="submit" value="ROOM">
    </form>

    <form method="post" action="cage-result.php">
    <input type="submit" value="CAGE">
    </form>
</div>

</html>
<!-- END OF PROGRAM -->