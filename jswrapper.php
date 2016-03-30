<?php

/**
* wird per Javascript nachgeladen und checkt, ob sich die dateigröße der data-file geändert hat.
**/ 
		
if ($file = $_GET['file'] ) {

	$file = 'data/'.$file.'.txt';
	$newsize = filesize($file); 
	$oldsize = $_GET['size']; 

	if (!$newsize) {
//		echo "alert('Datei $file nicht gefunden ');\n";
	}
	
	if ($oldsize AND $newsize != $oldsize) {
 		echo 'if (confirm(" Offensichtlich hat Dein Schach-Partner in der Zwischenzeit gezogen. \n Soll der Zug angezeigt werden? \n") ) refreshme(); ';
	}
	echo "\nsize = '$newsize'; \n\n"; 
		
} else {
		
	echo 'alert("Keine Datei angegeben");';

}

?>
