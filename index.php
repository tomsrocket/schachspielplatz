<?php
		
// make changes at free will - but beware: all path & url options need trailing slash!
$CONF['datadir']		= 'data/';
$CONF['bakdir']			= 'bak/';
$CONF['commentdir']		= 'comments/';
$CONF['url']			= 'http://schach.bankofhongkong.de/';

// important! Fill this out, and check that nobody enters wrong email adresses and sends silly spam mails around!!
$CONF['adminEmail']		= 'schachinfo@holychao.de';



// private config
$CONF['i_am']='index.php';


$info="";

$password='';
if ($_GET['pw']) $password=$_GET['pw']; 
if($_POST['pw']) $password=$_POST['pw'];

if ($_GET['selectgame']) {
	$filename=$_GET['selectgame'];
}

if ($_GET['newgame']) {
	$filename=preg_replace('/[^a-z0-9_]/','',strtolower($_GET['newgame']) );

	$handle = fopen($CONF['datadir'].$filename.'.txt', "wb");
	if (!fwrite($handle, '')) {
	}
	fclose ($handle);
	mail ($CONF['adminEmail'],'[Schachspielplatz] Admin - New game started',"Partie: $filename\n\n");	
}
		
if ($_POST['gamename'] ) {
	$filename=$_POST['gamename']; 	

}

if (!$filename) {	// Partie auswählen
	$partien=array();
	$handle=opendir($CONF['datadir']);
	while ($file = readdir ($handle)) {
	   if ($file != "." && $file != "..") {
		   $partien[]=$file; 
	   }
	}
	closedir($handle);
    include('header.php');	
				
	$re= '

<div class="main">
<br><br>
	<div class="optionboxs">
	<form action="'.$CONF['i_am'].'" method="GET">
		<h3>Gespeicherte Schach-Partie laden</h3>
		Partie auswählen:<br>
		<select name="selectgame" style="font-family:Courier New;font-size:11px;><option value=""> -- Bitte auswählen -- </option>';
	foreach ($partien as $partie) {

		$pw = '';
		if (file_exists( $opf = $CONF['commentdir'].substr($partie,0,-4).'.opt' ) ) {
			$handle = @fopen($opf, "rb");
			$p_string='';
			while (!feof($handle)) {
				$p_string.= fgets($handle, 4096);
			}
			fclose ($handle);
			$ptmp = unserialize($p_string);
			$pw = ($ptmp['p_password']) ? ' *':'';

		}
		$wann = date('d.m. H:i',filemtime($CONF['datadir'].$partie) );
		$was = str_replace(' ','&nbsp;',sprintf('% 20s',substr(substr($partie,0,-4),0,20) ).' | '.$wann.' Uhr');
		$re.= '<option value="'.substr($partie,0,-4).'"> '.$was.$pw.' </option>';
	}
	$re.='</select>

		<br>
		<input type="submit" value="Gespeicherte Partie laden"></form>
	
	</div>		

	<div class="optionboxs">
		<form action="'.$CONF['i_am'].'" method="GET">
		<h3>Neue Schach-Partie starten</h3>
		Name für neue Partie eingeben: <br>
		(Im Namen sind nur Buchstaben und Zahlen erlaubt)		<br>
		<input type="text" name="newgame" value=""><br>
		<input type="submit" name="a" value="Neue Partie anlegen">
		</form>
	</div>
	<div class="optionboxs">
		<form action="'.$CONF['i_am'].'" method="POST">
		<h3>Talk</h3>
		';
		
		$comments='';
		if ($handle = @fopen($CONF['commentdir'].'talk.txt', "rb") ) {
			while (!feof($handle)) {
				$comments.= fgets($handle, 4096);
			}
			fclose ($handle);
		}


		if ($_POST['t_comment']) {

				$gcomment = strip_tags(stripslashes($_POST['t_comment']) );
				if (!$_POST['t_name'] ) $comment_name = 'anonym';
				else $comment_name =stripslashes($_POST['t_name'] );
				if (strpos( strtolower( $comment_name.$gcomment ), 'http' ) !== false ) {
					header( "Location: http://www.beleidigungsgenerator.de" ); 
					die('<script>document.location.href="http://www.beleidigungsgenerator.de";</script>'); 
				}

				$gcomment='<div class="gcomment"><span class="commentName">'.$comment_name.':</span> '.$gcomment.'</div>'."\n";
				$comments = $gcomment.$comments;

				$handle = fopen($CONF['commentdir'].'talk.txt', "wb");
				if (!fwrite($handle, $comments)) {
				   print "alert('Kann in die Datei nicht schreiben');\n";
				}
				fclose($handle);
				$gcomment='';
	
		}	

	$re.=$comments.'<br>
		Kommentar: <br>
		<textarea name="t_comment" cols="40" rows="3"></textarea><br>
		Name: <input class="textfeld" type="text" name="t_name"> &nbsp; 
		<input type="submit" value="Kommentar abschicken">
		</form>
	</div>
	<div class="optionboxs">
		<h3>Info</h3>
		<ul>
		<li>Die Züge werden nicht auf Korrektheit überprüft, d.h. man kann auch Züge eingeben, bei
		denen der Bauer wie eine Dame durch die Gegend schweift, bitte also selbst darauf achten,
		dass die Züge erlaubt sind. </li>
		<li> Bei einer Rochade einfach beide Züge eingeben (z.B. erst den Turm, dann den König)
		und nach dem ersten Zug einen Punkt machen. Beispiel:
		<div style="text-align:left;margin:0 auto 0 auto;width:50px;">
			a1-d1.<br>
			e1-c1 
		</div>
		</li></ul>
	</div>
<br><br>
</div>

	';
	echo $re; 
	include ('footer.php');
	exit; 
} 


// Prüfen ob Dateiname existiert; 
if (!file_exists( $CONF['datadir'].$filename.'.txt' ) ) {
	include('header.php');
	echo "Fehlerhafter Partie-Name $filename";
	include('footer.php');
	exit;
}

/*				E I N S T E L L U N G E N	s p e i c h e r n 	*/

$optionsfile = $CONF['commentdir'].$filename.'.opt';

if ($_POST['p_save'] ) {
	$pconf = array(
		'p_password' => $_POST['p_password'],
		'p_blackmail' => $_POST['p_blackmail'],	
		'p_whitemail' => $_POST['p_whitemail']
	);
	$p_string = serialize($pconf);
	$handle = fopen($optionsfile, "wb");
	if (!fwrite($handle, $p_string)) {
	   print "alert('Kann in die Einstellungen-Datei nicht schreiben');\n";
	}
	fclose($handle);
	$info="Einstellungen wurden gespeichert. <br>"; 

	mail ($CONF['adminEmail'],'[Schachspielplatz] Admin - Options changed',"Partie: $filename\n\nEinstellungen:\n$p_string");
	
}


/*				E I N S T E L L U N G E N	l a d e n  	*/	
$pconf=array(); 

if ($handle = @fopen($optionsfile, "rb") ) {
	while (!feof($handle)) {
		$p_string.= fgets($handle, 4096);
	}
	fclose ($handle);
	$pconf = unserialize($p_string); 
}




/*				P a s s w o r t     c h e c k e n		*/

if ($pconf['p_password'] AND $password != $pconf['p_password'] ) {
    include('header.php');
	
	$pwi='';
	if ($password) $pwi='<font color="yellow">Ähm.. das war falsch.</font><br><br>';

	$re= '

	<div class="main">
	<br><br><br><br>	<br><br><br><br>
		<div class="optionboxs">
			<form action="'.$CONF['i_am'].'" method="POST">
			<input type="hidden" name="gamename" value="'.$filename.'">			
				<h3>Passwort eingeben</h3><br>
				Die gewählte Partie ist Passwortgeschützt.<br>'.$pwi.'
				Bitte Passwort eingeben.<br><br>
				Passwort: <input type="text" name="pw">
				<input type="submit" value="Weiter &gt;&gt; ">
			</form>
		</div>		
	<br><br><br><br>	<br><br><br><br>
	</div>
	';
	echo $re; 
	include ('footer.php');
	exit; 
} 






/*				P A R T I E  -  K O M M E N T A R E				*/

$comments='';
if ($handle = @fopen($CONF['commentdir'].$filename.'.txt', "rb") ) {
	while (!feof($handle)) {
		$comments.= fgets($handle, 4096);
	}
	fclose ($handle);
}
$comment=stripslashes($_POST['comment']);

if ($_POST['post_comment'] OR $_POST['savegame']) {	//Kommentar speichern

	if ($_POST['comment']) {

		$comment = strip_tags(stripslashes($_POST['comment']) );
		if (!$_POST['comment_name'] ) $comment_name = 'anonym';
		else $comment_name =stripslashes($_POST['comment_name'] );
		$comment='<div class="comment"><span class="commentName">'.$comment_name.':</span> '.$comment.'</div>'."\n";
		$comments = $comment.$comments;

		$handle = fopen($CONF['commentdir'].$filename.'.txt', "wb");
		if (!fwrite($handle, $comments)) {
		   print "alert('Kann in die Datei nicht schreiben');\n";
		}
		fclose($handle);
		$comment='';
	}

}	



/*				S C H A C H Z Ü G E					*/

$blackOrWhiteCount=0;
$sendmail=false; 

if ($_POST['schachmoves']) {	// Zugfolge wurde eingegeben

	$schachmoves=$_POST['schachmoves'];
	
	if ($_POST['savegame']) {	// Zugfolge soll gespeichert werden
			
			//prüfen ob bestehender zug geändert wurde
			$schachmoves_alt='';
			$handle = fopen ($CONF['datadir'].$filename.'.txt', "rb");
			while (!feof($handle)) {
				$schachmoves_alt.= fgets($handle, 4096);
			}
			fclose ($handle);

			$moves_alt = explode("\n",$schachmoves_alt);
			$moves = explode("\n",$schachmoves); 
			$changed_moves=array();
			foreach ($moves_alt AS $nr => $move) {
				if (trim($move) ) {
					if (trim($moves[$nr] )  != trim($move) ) $changed_moves[$nr+1]=$move.' ändern zu '.$moves[$nr];
				}
			}
			if (count($changed_moves) ) {	// Zug wurde unerlaubt geändert

				$info='<span class="forbidden">Zug wurde *nicht* gespeichert.</span> Bestehende Postition wurde unerlaubt verändert: <br>';

				$blackOrWhiteCount=1;	// wenn Zug noch nicht gezogen, ist man noch am Zug.  
				
				foreach($changed_moves AS $nr=>$move) {
					$info.="$nr.: \"$move\" nicht erlaubt.<br>";
				}
			} else {	// Alles OK

					copy($CONF['datadir'].$filename.'.txt',$CONF['bakdir'].$filename.time().'.txt');

					$handle = fopen($CONF['datadir'].$filename.'.txt', "wb");
					if (!fwrite($handle, $schachmoves)) {
					   print "alert('Kann in die Datei nicht schreiben');\n";
					}
					fclose ($handle);

					$info="Zug wurde gespeichert!";
					$sendmail=true; 

			}
			
	} else {

		$blackOrWhiteCount=1;
		$info='Zug wird angezeigt, wurde aber noch nicht gespeichert. Zum Abspeichern auf "Zugfolge anzeigen & speichern" klicken!';
	}

} else {	// Partie laden und anzeigen

	$schachmoves='';
	$handle = fopen ($CONF['datadir'].$filename.'.txt', "rb");
	while (!feof($handle)) {
		$schachmoves.= fgets($handle, 4096);
	}
	fclose ($handle);

	$info.= 'Letzter Zug am '.date('d.m.Y',filemtime($CONF['datadir'].$filename.'.txt') ).' um '.date('H:i:s',filemtime($CONF['datadir'].$filename.'.txt') ).' Uhr';

}

	$moves= explode("\n",$schachmoves);

	$movelist=array();

	foreach ($moves AS $nr => $move) {
		if ($move) {
			list($von,$zu)=explode('-',$move);
			if ($von && $zu) {
				$movelist[]=tonumber($von);
				$movelist[]=tonumber($zu);
			}
			if (!strpos($zu,'.') )$blackOrWhiteCount++;
		}
	}
	$am_zug = ($blackOrWhiteCount % 2 == 0) ? 'Weiß':'Schwarz';
	$info .= '<br>'.$am_zug." am Zug.<br>"; 

	if (!$comment_name) $comment_name = $am_zug; 

	function tonumber($xy) {
		$x = substr($xy,0,1);
		$y = substr($xy,1);
		$x = (ord($x)-97);
		return $x + (8-$y)*8; 
	}

	$str='';
	foreach ($movelist as $val) {
		$str.=$val.',';
	}
		
$js='m0[0] = new Array('.substr($str,0,-1).');'."\n";


// mail an Schachpartner schicken
if ($sendmail) {
	$sendto = ($blackOrWhiteCount % 2 == 0) ? 'p_whitemail':'p_blackmail';

	if ($email = $pconf[$sendto] ) {
		$pw='';
		if ($pw = $pconf['p_password'] ) $pw = '&pw='.$pw;
		mail( $email , "[Schachspielplatz] Du bist dran!", 
		"Hi!\n\nDein Schachpartner hat seinen Zug gemacht, jetzt bist Du wieder dran.\n\nDu kannst die Partie unter diesem Link abrufen:\n".
		$CONF['url'].$CONF['i_am'].'?selectgame='.$filename.$pw."\n\n\n[Diese Mail wurde automatisch erstellt]");
	}

}


include('header.php');
?>


<div class="kopfzeile">
<?php echo '<h3>Partie "'.$filename.'"</h3>'.$info; ?>
</div>

<div class="main">

<center>

<form action="index.php" method="post">

<table border="0" cellpadding="6">
<TR>
<td align="right" valign="top">

Kommentare:
<div class="comments">
<?php echo ($comments)?$comments:'Es wurden noch keine Kommentare zu dieser Partie abegegeben.'; ?>
</div>
Kommentar eingeben:<br>
<textarea name="comment" style="height:50px;font-size:12px;font-family:Tahoma,Verdana" class="textfeld" rows="3" cols="15"><?php echo $comment; ?></textarea><br>
Name: <input type="text" class="input" name="comment_name" size="10" value="<?php echo $comment_name; ?>" class="textfeld"><br>
<input type="submit" name="post_comment" value="Kommentar abschicken" class="standardbutton">	
</td>
<td valign="top">

	
		<TABLE border="1" bordercolordark="#999999" bordercolorlight="#dddddd">
		<TR>
		<TD>


		<IMG SRC="gif/brw.gif" name='grinis_nowikg0.htm' width="32" height="32"><IMG SRC="gif/bnb.gif" width="32" height="32"><IMG SRC="gif/bbw.gif" width="32" height="32"><IMG SRC="gif/bqb.gif" width="32" height="32"><IMG SRC="gif/bkw.gif" width="32" height="32"><IMG SRC="gif/bbb.gif" width="32" height="32"><IMG SRC="gif/bnw.gif" width="32" height="32"><IMG SRC="gif/brb.gif" width="32" height="32"><BR><IMG SRC="gif/bpb.gif" width="32" height="32"><IMG SRC="gif/bpw.gif" width="32" height="32"><IMG SRC="gif/bpb.gif" width="32" height="32"><IMG SRC="gif/bpw.gif" width="32" height="32"><IMG SRC="gif/bpb.gif" width="32" height="32"><IMG SRC="gif/bpw.gif" width="32" height="32"><IMG SRC="gif/bpb.gif" width="32" height="32"><IMG SRC="gif/bpw.gif" width="32" height="32"><BR><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><BR><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><BR><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><BR><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><IMG SRC="gif/b.gif" width="32" height="32"><IMG SRC="gif/w.gif" width="32" height="32"><BR><IMG SRC="gif/wpw.gif" width="32" height="32"><IMG SRC="gif/wpb.gif" width="32" height="32"><IMG SRC="gif/wpw.gif" width="32" height="32"><IMG SRC="gif/wpb.gif" width="32" height="32"><IMG SRC="gif/wpw.gif" width="32" height="32"><IMG SRC="gif/wpb.gif" width="32" height="32"><IMG SRC="gif/wpw.gif" width="32" height="32"><IMG SRC="gif/wpb.gif" width="32" height="32"><BR><IMG SRC="gif/wrb.gif" width="32" height="32"><IMG SRC="gif/wnw.gif" width="32" height="32"><IMG SRC="gif/wbb.gif" width="32" height="32"><IMG SRC="gif/wqw.gif" width="32" height="32"><IMG SRC="gif/wkb.gif" width="32" height="32"><IMG SRC="gif/wbw.gif" width="32" height="32"><IMG SRC="gif/wnb.gif" width="32" height="32"><IMG SRC="gif/wrw.gif" width="32" height="32"></TD></TR></TABLE>

		<br>
<center>

		<input type=button value=" Start " onClick="b0=GoStart(m0,n0,0,b0, 'grinis_nowikg0.htm'); GMS(m0,n0,0,b0, 'grinis_nowikg0.htm');">

		<input type=button value=" &lt;&lt; " onClick="MB50();" onKeyPress="MB50();" onDblClick="MB50();">

		<input type=button value=" &lt; " onClick="MB0();" onKeyPress="MB0();" onDblClick="MB0();">

		<input type=button value=" &gt; " onClick="MF0();" onKeyPress="MF0();" onDblClick="MF0();">

		<input type=button value=" &gt;&gt; " onClick="MF50();" onKeyPress="MF50();" onDblClick="MF50();">

		<input type=button value=" Ende " onClick="b0=GoEnd(m0,n0,0,b0, 'grinis_nowikg0.htm'); GMS(m0,n0,0,b0, 'grinis_nowikg0.htm');">

</center>

		<DIV id="igrinis_nowik0"></DIV>
		<ILAYER><LAYER width=350></LAYER></ILAYER><BR>

</TD>
<TD valign="top">


	<textarea name="schachmoves" cols=8 rows=20 class="textfeld" style="font-weight:bold;height:266px;"><?php echo $schachmoves; ?></textarea>
	<br>
	<input type="hidden" name="gamename" value="<?php echo $filename; ?>">
	<input type="hidden" name="pw" value="<?php echo $password; ?>">			
	<input type="submit" class="standardbutton" value="Zugfolge anzeigen"><br>



</TD></TR></TABLE>



<input type="submit" name="savegame" value="Zugfolge speichern!" onClick="return confirm('Achtung: Der Zug kann nach dem Speichern nicht zurückgenommen werden!\n\nIst der Zug auch korrekt?\nSoll der Zug endgültig eingeloggt werden?');">
</form>


</div>

<div class="optionbox">
<h3>Partie-Einstellungen</h3>
	<div style="width:15px; height:1px;overflow:hidden">
		<form action="index.php" method="post">
		<input type="hidden" name="gamename" value="<?php echo $filename; ?>">
		<input type="hidden" name="pw" value="<?php echo $password; ?>">
	</div>

<table cellpadding="0" cellspacing="0" width="700"><tr><td>

		<table cellpadding="1" cellspacing="0"><tr>
			<td> Zugangspasswort: </td>
			<td> <input type="text" size="10" class="optionfeld" value="<?php echo $pconf['p_password'] ?>" name="p_password"> </td>
		</tr><tr>
			<td> Emailadresse&nbsp;Weiss: </td>
			<td> <input type="text" size="20" class="optionfeld" value="<?php echo $pconf['p_whitemail'] ?>" name="p_whitemail"> </td>	
		</tr><tr>
			<td> Emailadresse&nbsp;Schwarz: </td>
			<td> <input type="text" size="20" class="optionfeld" value="<?php echo $pconf['p_blackmail'] ?>" name="p_blackmail"> </td>	
		</tr></table>
		
</td><td>&nbsp;&nbsp;&nbsp;&nbsp;
</td><td>
	Falls ein Zugangspasswort gesetzt ist, kann man diese Partie nur über die Startseite aufrufen, wenn man das Passwort weiß.
	Wenn Du Deine E-Mail-Adresse einträgst, bekommst Du immer eine Mail geschickt, wenn Dein Schachpartner einen Zug macht. 
<br>
	<input type="submit" value="Einstellungen speichern" name="p_save">

</td></tr></table>

</form>
<span class="small">Info: Das Schachbrett-Javascript wurde freundlicher Weise zur Verfügung gestellt von <a target="_blank" href="http://www.8ung.at/schachschule/" class="small">Yevgen Grinis' Schachschule</a></span>
</div>


<script language="JavaScript" type="text/javascript">
<!--
 b0=GoEnd(m0,n0,0,b0, 'grinis_nowikg0.htm'); GMS(m0,n0,0,b0, 'grinis_nowikg0.htm');
//-->
</script>

<?php include('footer.php'); ?>
