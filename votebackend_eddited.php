<?php
/*
Осъществява връзка с базата данни. Ако има зададен url параметър 'vote', се взима последната му стойност , и се търси кандидат с такова  име в базата данни.
Ако такъв кандидат съществува, броят гласове за него се увеличава с 1. Накрая се изкарва съобщение, дали операциите са протекли успешно.
*/

	$link = @mysqli_connect("localhost", "root", "", "securitycheck");
	if(!$link)
	{
		echo "Database maintenance. Please try again later.";
		exit;
    }
    
    if(isset($_GET['vote'])&& preg_match("/^[a-zA-Z]{1,5}$/", $_GET['vote']))
    {
		$sqlvt = "SELECT id,candidate, votes
        FROM results
        WHERE candidate = ?";
		
		$statement = mysqli_stmt_init($link);
		if(!mysqli_stmt_prepare($statement,$sqlvt)) 
				{
					//fail
					echo "Database maintenance 1. Please try again later.";
					exit(); 
				}
		mysqli_stmt_bind_param($statement, "s", $_GET['vote'] );
		mysqli_stmt_execute($statement);
		mysqli_stmt_bind_result($statement, $result_id, $result_candidate, $result_votes);
		mysqli_stmt_fetch($statement);
		if(!isset($result_id))
		{
			echo "Invalid vote.";
			exit;
		}

        $newvotes=$result_votes+1;

        $sqlup = "UPDATE results SET votes= ? WHERE candidate = ?"; 

		$link = @mysqli_connect("localhost", "root", "", "securitycheck");
		$statementup = mysqli_stmt_init($link);
		if(!mysqli_stmt_prepare($statementup,$sqlup)) 
				{
					//fail
					echo "Database maintenance 3. Please try again later.";
					exit(); 
				}
		mysqli_stmt_bind_param($statementup, "is", $newvotes, $result_candidate);
		mysqli_stmt_execute($statementup);

        echo "You successfuly voted for candidate : ".$result_candidate."<br>";

    }
	else
	{
		echo "Unacceptable parameter.";
        exit; 
	}
	exit;
?>

