<?php

//include_once('path.php');
//
//include_once(__DIR__.'/php_shared/common/functions.php');

//$server = '{imap.gmail.com:993/ssl}';

$server = '{imap.gmail.com:993/ssl/novalidate-cert}';
$login = 'Danack.Ackroyd@gmail.com';
$password = 'shinystuff';


//deleteAllEmails();
//$numberOfEmails = getNumberOfEmailsInInbox();

//echo "number of emails = $numberOfEmails \r\n ";

//getVerificationLink(FALSE);
//listFolders();

function	listFolders(){

	$connection = imap_open($GLOBALS['server'], $GLOBALS['login'], $GLOBALS['password']);

	$mailboxes = imap_list($connection, $GLOBALS['server'], '*');

	imap_close($connection);

	var_dump($mailboxes);
}

function dumpString($string){
	
	$fileHandle = fopen('debug.txt', 'w+');
	
	fwrite($fileHandle, $string);
	
	fclose($fileHandle);
}


function	findLinkFromEmailBody($bodyText){

	//dumpString($bodyText);

	$lines = explode("\n", $bodyText);	
	
	$linkTypes = array( 'http://', 'https://' );	
	
	foreach($lines as $line){
	
		//echo "testing line: $line\r\n";
		
		foreach($linkTypes as $linkType){		
		
			$linkPosition = mb_stripos($line, $linkType);
			
			if($linkPosition !== FALSE){		
				$spacePosition = mb_stripos($line, ' ', $linkPosition);
				
				if($spacePosition === FALSE){
					return trim(mb_substr($line, $linkPosition));			
				}
				else{
					return trim(mb_substr($line, $linkPosition, ($spacePosition - $linkPosition)));
				}			
			}	
		}
		
	}
	
	return FALSE;	
}




function	findEmailWithSubject($connection, $needle){

	$count = imap_num_msg($connection);
	//echo "There are $count messages<br/>\r\n";

	for($i = 1; $i <= $count; $i++) {
		$header = imap_headerinfo($connection, $i);
		$raw_body = imap_body($connection, $i);		
		
		//echo "\r\nTesting ".$header->Subject ." for ".$needle."<br/>\r\n";
		
		if(mb_strpos($header->Subject, $needle) !== FALSE){
		
			//echo "found<br/>\r\n";
		
			$result = array();
			
			$result['count'] 	= $i;
			$result['header'] 	= $header;
			$result['body'] 	= $raw_body;
			
			return $result;
		}
	}

	return FALSE;
}	


function	getVerificationLink($deleteEmail, $retry = FALSE){

    $link = FALSE;
    
	if($retry !== FALSE){
		$retriesLeft = $retry;
	}
	else{
		$retriesLeft = 0;
	}

	$connection = imap_open($GLOBALS['server'], $GLOBALS['login'], $GLOBALS['password']);

	$finished = FALSE;
	
	while($finished == FALSE){
	
		$finished = TRUE;
	
		$result = findEmailWithSubject($connection, 'verification');
		
		if($result == FALSE){
			$result = findEmailWithSubject($connection, 'Forgotten');
		}


		
		if($result == FALSE){
				//no link found
		}
		else{
			//echo "email found, body is:\r\n";
			//var_dump($result['body']);		
			$link = findLinkFromEmailBody($result['body']);		
			
			
			if($deleteEmail == TRUE){
				if($link !== FALSE){
					//echo "link is '".$link."'\r\n";		
					deleteMessage($connection, $result['count']);
				}
			}
		}
	
		if($link == FALSE){
			if($retriesLeft > 0){
				$retriesLeft--;
				echo "retriesLeft = $retriesLeft";
				sleep(5);
				
				set_time_limit(20);
				
				$finished = FALSE;
			}
		}
	}

	imap_close($connection);

	return $link;
}

function	deleteMessage($connection, $msgno){
	$result = imap_mail_move($connection, "$msgno:$msgno", '[Gmail]/Trash');
    
    if ($result === false) {
        throw new \Exception("Failed to move message.");
    }
	//echo "Delete result = ".$result."\r\n";
}



function deleteAllEmails(){

	$connection = imap_open($GLOBALS['server'], $GLOBALS['login'], $GLOBALS['password']);

	$count = imap_num_msg($connection);	

	for($i = 1; $i <= $count; $i++) {
		deleteMessage($connection, $i);
	}
	
	$count = imap_num_msg($connection);

	imap_close($connection);
	
	
	if($count != 0){
		throw new Exception("Tried to delete all emails but there are still $count in the inbox.");
	}	
}

function getNumberOfEmailsInInbox(){

	$connection = imap_open($GLOBALS['server'], $GLOBALS['login'], $GLOBALS['password']);
	
	$count = imap_num_msg($connection);
	
	imap_close($connection);
	
	return $count;
}


// http://www.electrictoolbox.com/open-mailbox-other-than-inbox-php-imap/




