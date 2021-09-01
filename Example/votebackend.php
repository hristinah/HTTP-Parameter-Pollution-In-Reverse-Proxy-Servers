<?php
/*
Осъществява връзка с базата данни. Ако има зададен url параметър 'vote', се взима последната му стойност , и се търси кандидат с такова  име в базата данни.
Ако такъв кандидат съществува, броят гласове за него се увеличава с 1. Накрая се изкарва съобщение, дали операциите са протекли успешно.
*/

	$link = @mysqli_connect("localhost", "root", "", "securitycheck");
	if(!$link){
		echo "Database maintenance. Please try again later.";
		exit;
    }
    
    if(isset($_GET['vote'])) 
    {
        $sqlvt = "SELECT id, candidate, votes
        FROM results
        WHERE candidate = '".$_GET['vote']."'"; 
		
        $resultvt = @mysqli_multi_query($link, $sqlvt);
        if(!$resultvt){
            echo "Database maintenance 1. Please try again later.";
            exit;
        }
		
		$resultvt = @mysqli_store_result($link);
        $row = @mysqli_fetch_assoc($resultvt);

        if(!isset($row['id']) ){
            echo "Invalid vote.";
            exit;
        }

        $newvotes=$row['votes']+1;

        $sqlup = "UPDATE results SET votes='".$newvotes."' WHERE candidate = '".$row['candidate']."'"; 

        $resultup = @mysqli_query($link, $sqlup);
        if(!$resultup){
            echo "Database maintenance 2. Please try again later.";
            exit;
        }

        echo "You successfuly voted for candidate : ".$row['candidate']."<br>";

    }
	
	exit;
?>

