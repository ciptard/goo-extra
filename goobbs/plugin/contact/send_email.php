<?php
     /*
    ** Header
    */     
	$autoResponse = true; //if set to true auto response email will be sent, if you don't want autoresponse set it to false
	$autoResponseSubject = "Prise de contact";
	$autoResponseMessage = "Merci de votre message.";
	$autoResponseHeaders = "De: webmaster@site.com";  

    //we need to get our variables first
	$email_to =   'webmaster@site.com'; //the address to which the email will be sent
    $subject  =   $_POST['subject'];
    $name     =   $_POST['name'];
    $email    =   $_POST['email'];
    $msg      =   $_POST['message'];

    $message  = "De: $name \r\nEmail: $email \r\nType: $subject \r\nMessage: \r\n$msg";
    echo $email_to;
    /*the $header variable is for the additional headers in the mail function,
     we are asigning 2 values, first one is FROM and the second one is REPLY-TO.
     That way when we want to reply the email gmail(or yahoo or hotmail...) will know
     who are we replying to. */
    $headers  = "From: $name\r\n";
    $headers .= "Reply-To: $email\r\n";

    if(mail($email_to, $subject, $message, $headers)){
    	if($autoResponse === true){
    		mail($email, $autoResponseSubject, $autoResponseMessage, $autoResponseHeaders);
    	}
        echo 'Message Envoy&eacute;!'; // we are sending this text to the ajax request telling it that the mail is sent..
    }else{
        echo 'Votre message ne peut &ecirc;tre envoy&eacute;';// ... or this one to tell it that it wasn't sent
    }
?>