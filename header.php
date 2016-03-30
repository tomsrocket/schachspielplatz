<html><head>
<TITLE>bankofhongkong.de Schachspielplatz </TITLE>
<link rel="shortcut icon" href="http://schach.bankofhongkong.de/favicon.ico" />

<?php 
if ($filename) {

	//wenn partie angezeigt wird, javascript einbinden ,dass regelmässig checkt ob der andere gezogen hat. 
		
?>

<script language="JavaScript" type="text/javascript">
<!--
var keepSessionRefresh = 1000*30;	//30 sekudnen
var myCommand ="try {loadContent('jswrapper.php?file=<?php echo $filename; ?>&pw=<?php echo $password; ?>&size=' + size)} catch (e) {}";

var size = 0; 

window.setTimeout(myCommand,keepSessionRefresh);

			
function loadContent(file){
	var scriptTag = document.getElementById('loadScript');
	var head = document.getElementsByTagName('head').item(0)
	if(scriptTag) head.removeChild(scriptTag);
	script = document.createElement('script');
	script.src = file;
	script.type = 'text/javascript';
	script.id = 'loadScript';
	head.appendChild(script)
	window.setTimeout(myCommand,keepSessionRefresh);

}


function refreshme() {
		document.location.href="<?php echo $CONF['i_am'].'?selectgame='.$filename; ?>"; 
}
//-->
</script>

<?php
}

	// ab hier das javascript fürs schachbrett
?>



<script language="JavaScript">
<!--

var m0 = new Array(1)
<?php echo $js; ?>
m0[0].base = new Array();
m0[0].root =0;
m0[0].move =0;

var n0 = new Array();
var b0 = -1;
var pieces = "KDSLT ";

function g0(a,b)
{	gm(m0,n0,0,b0,'grinis_nowikg0.htm',a,b);}
function MB50()
{b0=MB5(m0,n0,0,b0,'grinis_nowikg0.htm'); GMS(m0,n0,0,b0,'grinis_nowikg0.htm');}
function MB0()
{b0=MB(m0,n0,0,b0,'grinis_nowikg0.htm'); GMS(m0,n0,0,b0,'grinis_nowikg0.htm');}
function MF50()
{b0=MF5(m0,n0,0,b0,'grinis_nowikg0.htm'); GMS(m0,n0,0,b0,'grinis_nowikg0.htm');}
function MF0()
{b0=MF(m0,n0,0,b0,'grinis_nowikg0.htm',true); igrinis_nowik0.value=GMS(m0,n0,0,b0,'grinis_nowikg0.htm');}

var nMoves = new Array(0,0);
var nLevels = new Array(0,0);
var nNameCount = new Array(0,0);
var nOld = new Array(-1,-1);
baseName = 'grinis_nowik';
var EmptyWhitePath = "gif/w.gif";
var EmptyBlackPath = "gif/b.gif";

function InitGames()
{
	GMS(m0, n0, 0, b0, 'grinis_nowikg0.htm');
}
function Init( path )
{ gifs = new Array(6);
	for ( var i=0; i< 26; i++)
		gifs[i]= new Image();
	gifs[0].src = path + "b.gif";
	gifs[1].src = path + "bbb.gif";
	gifs[2].src = path + "bbw.gif";
	gifs[3].src = path + "bkb.gif";
	gifs[4].src = path + "bkw.gif";
	gifs[5].src = path + "bqb.gif";
	gifs[6].src = path + "bqw.gif";
	gifs[7].src = path + "brb.gif";
	gifs[8].src = path + "brw.gif";
	gifs[9].src = path + "bnb.gif";
	gifs[10].src = path + "bnw.gif";
	gifs[11].src = path + "wbb.gif";
	gifs[12].src = path + "wbw.gif";
	gifs[13].src = path + "wrb.gif";
	gifs[14].src = path + "wrw.gif";
	gifs[15].src = path + "wqb.gif";
	gifs[16].src = path + "wqw.gif";
	gifs[17].src = path + "wkb.gif";
	gifs[18].src = path + "wkw.gif";
	gifs[19].src = path + "wnb.gif";
	gifs[20].src = path + "wnw.gif";
	gifs[21].src = path + "wpb.gif";
	gifs[22].src = path + "wpw.gif";
	gifs[23].src = path + "bpb.gif";
	gifs[24].src = path + "wpb.gif";
	gifs[25].src = path + "b.gif";
}
function SyncPicture( base, basename )
{	if ( base > -1 ) return base;
	for ( i=0; i < document.images.length; i++ )
	{	if ( document.images[i].name != "" )
			if ( document.images[i].name == basename )
				return i;
			else if ( document.images[i].name.substring(0,3) == basename.substring(0,3) )
				i+= 63;
	}
	return -1;
}
function MF5( moves, names, nm, base, basename )
{	base = SyncPicture( base, basename );
	for ( i=0; nMoves[nm] < moves[nLevels[nm]].length / 2 && i < 10; i++ )
		MF( moves, names, nm, base, false );
	return base;
}
function MB5( moves, names, nm, base, basename )
{	base = SyncPicture( base, basename );
	for ( i=0; ( nMoves[nm] > 0 || nLevels[nm] > 0 ) && i < 10; i++ )
		MB( moves, names, nm, base );
	return base;
}
function GoStart( moves, names, nm, base, basename )
{	base = SyncPicture( base, basename );
	while ( nMoves[nm] > 0 || nLevels[nm] > 0 )
		MB( moves, names, nm, base );
	return base;
}
function GoEnd( moves, names, nm, base, basename )
{	base = SyncPicture( base, basename );
	while ( nMoves[nm] < moves[nLevels[nm]].length / 2 )
		MF( moves, names, nm, base, false );
	return base;
}
function MF( moves, names, nm, base, basename, bCheckV )
{	base = SyncPicture( base, basename );
	if ( bCheckV )
	{	var b = false;
		for ( i=0; i < moves.length; i++ )
			if ( i != nLevels[nm] && moves[i].root == nLevels[nm] && moves[i].move == nMoves[nm] )
				b = true;
		if ( b )
		{	var vf = window.open('', 'Frank', 'status=no,scrollbars=no,menubar=no,toolbar=no,width=240,height=160')
			with ( vf.document )
			{	open();
				write('<HTML><HEAD><TITLE>Varianten</TITLE></HEAD><BODY><DIV ID="disp"></DIV><LAYER id="NS"></LAYER></BODY></HTML>')
				close()
			}
			vf.erzeuger = self;
			var ihtml="<a href=\"javascript:erzeuger.MF(erzeuger.m" + nm +
						 ",erzeuger.n" + nm + "," + nm + ",erzeuger.b" + nm + ",'" + basename + "',false);";
			ihtml = ihtml + "erzeuger.GMS(erzeuger.m" + nm +
						",erzeuger.n" + nm + "," + nm + ",erzeuger.b" + nm + ",'" + basename + "');";
			ihtml = ihtml + "self.close()\">";
			ihtml = ihtml + "Hauptvariante - Main line" + "<\a>";
			ihtml = ihtml + "<BR>";
			var nCurrentLevel=nLevels[nm];
			var nMoveNum=nMoves[nm];
			for ( j=0; j < moves.length; j++ )
			{	if ( j != nCurrentLevel && moves[j].root == nCurrentLevel && moves[j].move == nMoveNum )
				{	gm( moves,names,nm,base,basename,1,j);
					var moveStr = GMS2( moves, names, nm, base, 1, j, basename );
					ihtml = ihtml + "<a href=\"javascript:erzeuger.gm(erzeuger.m" + nm +
						",erzeuger.n" + nm + "," + nm + ",erzeuger.b" + nm + ",'" + basename + "',";
					ihtml = ihtml + "1," + j + ");self.close()\">";
					ihtml = ihtml + moveStr + "<\a>";
					ihtml = ihtml + "<BR>";
					gm( moves,names,nm,base,basename,nMoveNum,nCurrentLevel);
				}
			}
			if ( document.all )
				vf.document.all.disp.innerHTML=ihtml
			else if ( document.layers )
			{	vf.document.layers[0].document.clear();
				vf.document.layers[0].document.write(ihtml);
				vf.document.layers[0].document.close();
			}
			return base;
		}
	}
	if ( nMoves[nm] < moves[nLevels[nm]].length / 2 )
	{	var n = nMoves[nm]*2;
		var from = moves[nLevels[nm]][ n ] & 0x3f;
		var to = moves[nLevels[nm]][ n+1 ] & 0x3f;
		names[ nNameCount[nm]   ] = document.images[ base + from ].src;
		names[ nNameCount[nm]+1 ] = document.images[ base + to ].src;
		var len = names[nNameCount[nm]].length;
		var nn = (Math.floor(( to / 8 )) + ( to % 8 ));
		var dest = ( nn % 2 ) == 1 ? "b" : "w";
		var piece = names[nNameCount[nm]].substring(len-6,len-5);
		if (( moves[nLevels[nm]][ n ] & 0x380 ) == 0x80 )
			piece = "q";
		else if (( moves[nLevels[nm]][ n ] & 0x380 ) == 0x100 )
			piece = "n";
		else if (( moves[nLevels[nm]][ n ] & 0x380 ) == 0x180 )
			piece = "b";
		else if (( moves[nLevels[nm]][ n ] & 0x380 ) == 0x200 )
			piece = "r";
		document.images[ base + to ].src = names[nNameCount[nm]].substring(0,len-6) + piece + dest + names[nNameCount[nm]].substring(len-4,len);
		nn = (Math.floor(( from / 8 )) + ( from % 8 ));
		if (( nn % 2 ) == 1 )
			document.images[ base + from ].src = EmptyBlackPath;
		else
			document.images[ base + from ].src = EmptyWhitePath;
		nMoves[nm]++;
		nNameCount[nm] = nNameCount[nm] + 2;
		if ( nMoves[nm] < moves[nLevels[nm]].length / 2 && ( moves[nLevels[nm]][ nMoves[nm]*2 ] & 0x40 ) == 0x40 )
			MF( moves, names, nm, base, bCheckV );
	}
	return base
}
function MB( moves, names, nm, base, basename )
{	base = SyncPicture( base, basename );
	if ( nMoves[nm] > 0 )
	{	nMoves[nm]--;
		nNameCount[nm] -= 2;
		var from = moves[nLevels[nm]][ nMoves[nm]*2 ] & 0x3f;
		var to = moves[nLevels[nm]][ nMoves[nm]*2+1 ] & 0x3f;
		document.images[ base + from ].src = names[ nNameCount[nm] ];
		document.images[ base + to ].src = names[ nNameCount[nm]+1 ];
		if (( moves[nLevels[nm]][ nMoves[nm]*2 ] & 0x40 ) == 0x40 )
			MB( moves, names, nm, base );
	}
	if ( nMoves[nm] == 0 )
		if ( nLevels[nm] > 0 )
		{	nMoves[nm] = moves[nLevels[nm]].move;
			nLevels[nm] = moves[nLevels[nm]].root;
			if (( moves[nLevels[nm]][nMoves[nm]*2] & 0x40 ) == 0x40 )
				nMoves[nm]--;
		}
	return base;
}
function GMS2(  moves, names, nm, base, nMoveNumber, nLevel, basename )
{	if ( nMoveNumber > 0 )
	{	var n = (nMoveNumber-1)*2;
		var off=1;
		for ( i=0; i <= n; i += 2 )
			if (( moves[nLevel][i] & 0x40 ) == 0x40 )
				off = off+1;
		var from = moves[nLevel][ n ] & 0x3f;
		var to = moves[nLevel][ n+1 ] & 0x3f;
		var len = document.images[ base + to ].src.length;
		var piece = document.images[ base + to ].src.substring(len-6,len-5);
		if ( piece == "q" )
			piece = pieces.substring(1,2);
		else if ( piece == "r" )
			piece = pieces.substring(4,5);
		else if ( piece == "b" )
			piece = pieces.substring(3,4);
		else if ( piece == "n" )
			piece = pieces.substring(2,3);
		else if ( piece ==  "k" )
			piece = pieces.substring(0,1);
		else piece = pieces.substring(5,6);
		var piece2 = "";
		if (( moves[nLevel][ n ] & 0x380 ) == 0x80 )
			piece2 = pieces.substring(1,2);
		else if (( moves[nLevel][ n ] & 0x380 ) == 0x100 )
			piece2 = pieces.substring(2,3);
		else if (( moves[nLevel][ n ] & 0x380 ) == 0x180 )
			piece2 = pieces.substring(3,4);
		else if (( moves[nLevel][ n ] & 0x380 ) == 0x200 )
			piece2 = pieces.substring(4,5);
		var lines = "abcdefgh";
		var rows = "87654321";
		var fromLine = from%8;
		var fromRow = Math.floor(from/8);
		var toLine = to%8;
		var toRow = Math.floor(to/8);
		var moveNumber = gmn( moves, nLevel ) + nMoveNumber-off;
		var result = (Math.floor((moveNumber)/2)+1).toString() + ". ";
		if ( document.images[ base + to ].src.substring(len-7,len-6) == "b" )
			result = result + "... ";
		if ( n >= 2 && (( moves[nLevel][n] & 0x40 ) == 0x40 ))
		{	if (( moves[nLevel][n-1] == 62 ) || ( moves[nLevel][n-1] == 6 ))
				result = result + "0-0"
			else if (( moves[nLevel][n-1] == 2 + 7 * 8 ) || ( moves[nLevel][n-1] == 2 + 0 * 8 ))
				result = result + "0-0-0"
			else
			{	var sep = "x";
				from = moves[nLevel][ n-2 ] & 0x3f;
				to = moves[nLevel][ n-1 ] & 0x3f;
				fromLine = from%8;
				fromRow = Math.floor(from/8);
				toLine = to%8;
				toRow = Math.floor(to/8);
				result = result	+ lines.substring( fromLine, fromLine+1 ) + rows.substring( fromRow, fromRow+1 )
										+ sep
										+ lines.substring( toLine, toLine+1 ) + rows.substring( toRow, toRow+1 )
										+ piece2 + " ep";
			}
		}
		else
		{	var len = names[ nNameCount[nm]-1 ].length;
			var substr = names[ nNameCount[nm]-1 ].substring( len-6, len );
			var sep = (( substr == "/w.gif" ) || ( substr == "\\w.gif" ) ||
						  ( substr == "/b.gif" ) || ( substr == "\\b.gif" )) ? "-" : "x";
			if ( piece2 != "" ) piece = "";
			result = result + piece.toUpperCase() + lines.substring( fromLine, fromLine+1 ) + rows.substring( fromRow, fromRow+1 )
				+ sep
				+ lines.substring( toLine, toLine+1 ) + rows.substring( toRow, toRow+1 )
				+ piece2;
		}
	}
	else
		result='';
/*
	if (document.all)
	{	name = 'i' + baseName + nm;
		if ( result == '' )
			document.all.tags( "DIV" )[name].innerHTML = "Startposition";
		else
			document.all.tags( "DIV" )[name].innerHTML = "Position after " + result;
	}
	else if ( document.layers )
	{	document.layers[nm].document.layers[0].document.clear();
		var gesamt = "<center>Position after " + result + "</center>";
		document.layers[nm].document.layers[0].document.write(gesamt);
		document.layers[nm].document.layers[0].document.close();
	}
	*/
	return result;
}
function GMS( moves, names, nm, base, basename )
{	base = SyncPicture( base, basename );
	var nLevel = nLevels[nm];
	var nMoveNumber = nMoves[nm];
	if ( nMoveNumber >= 0 && nMoveNumber <= moves[nLevel].length / 2 )
	{	if ( document.all )
		{	if ( nOld[nm] != -1 )
//				document.anchors[nOld[nm]].style.background="#FFFFCC";
			if ( nMoveNumber > 0 )
			{	nOld[nm] = moves[nLevel].base[nMoveNumber-1];
//				document.anchors[nOld[nm]].style.background="gray";
			}
			else
				nOld[nm] = -1;
		}
		return GMS2( moves, names, nm, base, nMoveNumber, nLevel, basename );
	}
	else
		return "??";
}
function gm( moves, names, nm, base, basename, n, m )
{	base = SyncPicture( base, basename );
	GoStart(moves,names,nm,base,basename );
	gm_sub( moves,names,nm,base,basename,n,m);
	GMS( moves, names, nm, base, basename );
}
function gmn( moves, m )
{	if ( m > 0 )
	{	var off=0;
		var n2 = moves[m].move;
		var m2 = moves[m].root;
		for ( i=0; i <= n2*2; i += 2 )
			if (( moves[m2][i] & 0x40 ) == 0x40 )
				off = off+1;
		return gmn( moves, m2 ) + ( n2 - off );
	}
	return 0;
}
function gm_sub( moves, names, nm, base, basename, n, m )
{	if ( m > 0 )
	{	var off=0;
		var n2 = moves[m].move;
		var m2 = moves[m].root;
		for ( i=0; i <= n2*2; i += 2 )
			if (( moves[m2][i] & 0x40 ) == 0x40 )
				off = off+1;
		gm_sub( moves,names,nm,base, basename, n2-off, m2  );
	}
	nLevels[nm]=m;
	nMoves[nm]=0;
	for ( i=0; i < n; i++ )
		MF(moves,names,nm,base,basename, false);
}

//-->
</script>

<link rel="STYLESHEET" type="text/css" href="schachpartie.css">
</HEAD>
<body onload="Init('gif/'); InitGames();" bgcolor="#ffffff" text="#eeeeee" link="#8888ff" vlink="#ff6699;" alink="#ff6600">

<center>
<table border=0 cellpadding=0 cellspacing=0 height="100%">
<tr><td valign="top">

<div class="logo">
<a href="<?php echo $CONF['i_am']; ?>"><img src="img/logo2.gif" width="716" height="119" class="main"></a>
</div>

