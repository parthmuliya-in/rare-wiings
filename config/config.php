<?php

$conn = mysqli_connect("localhost","root","","rare-wiing-new");
if(!$conn){
 die("Connection failed: " . mysqli_connect_error());
}

// Razorpay API Keys (Test Mode)
// ⚠️ Replace with your actual Razorpay Test Keys from Dashboard
$keyId     = "rzp_test_RIFafEnGmhFF4V";  
$keySecret = "iz8m99FyYimsyFkESzBm2z52";

// Base URL of your site (for redirects)
$base_url = "http://localhost/rare-wiing-new/rare-wiing-new/";
?>