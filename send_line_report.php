<?php
session_start();
if(isset($_POST['line_token'])){

    $sToken = trim($_POST['line_token']);

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	date_default_timezone_set("Asia/Bangkok");

	// $sToken = "j8SkspLB1sWq4kmSVx2VoZDZI6LSkb9UH7";
	$sMessage = "ค่าไฟ......";

	
	$chOne = curl_init(); 
	curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt( $chOne, CURLOPT_POST, 1); 
	curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
	$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
	curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
	$result = curl_exec( $chOne ); 

	//Result error 
	if(curl_error($chOne)) 
	{ 
		echo 'error:' . curl_error($chOne); 
	} 
	else { 
		// $result_ = json_decode($result, true);   
		// echo "status : ".$result_['status']; echo "message : ". $result_['message'];
        echo "<script>";
            echo "window.history.back()";
        echo "</script>";
	} 
	curl_close( $chOne );
}else{


    Header("Location: index.php"); //user & password incorrect back to login again

}
?>