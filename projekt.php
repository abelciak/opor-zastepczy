<? include('./config.php'); ?>
<title>Obliczanie oporu zastępczego</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css" />
<center>
<h1><a href='projekt.php'>Projekt "Obliczanie oporu zastępczego"</a></h1>



<?
$aktualnadata=date('Y-m-d H:i:s');
//tworzenie nowego projektu
if ($_POST['projekt']=='utworz')
{
	$queryUtworz="INSERT INTO projekty(dataProjekt) VALUES('{$aktualnadata}') ";
	mysql_query($queryUtworz);
	
	
	//pobranie id nowego projektu
	$ostatniid = mysql_fetch_array(mysql_query("SELECT idProjekt, dataProjekt from projekty ORDER BY idProjekt DESC LIMIT 1"));
	echo "<font color='blue'>Utworzono nowy projekt o godzinie {$ostatniid['dataProjekt']} ! ID Twojego projektu: {$ostatniid['idProjekt']}";
	$_SESSION['wybor']=$ostatniid['idProjekt'];
	
	
}
else if (!empty($_SESSION['wybor'])) 
{
	//sprawdzenie aktualnego projektu
	$aktualnyprojekt = mysql_fetch_array(mysql_query("SELECT idProjekt, dataProjekt from projekty WHERE idProjekt='{$_SESSION['wybor']}' LIMIT 1"));
	echo "Twój osobisty ID projektu: {$_SESSION['wybor']} . Projekt utworzony w dniu {$aktualnyprojekt['dataProjekt']}";
}
else
{
	echo "<br><br><center><h2><b><font color='red'>Błąd ! Nie znaleziono żadnego projektu! Wróc na <a href='index.php'>stronę główną</a> .</font></b></h2></center>";
	exit;	
}
?>
<br><br>

<?
if (!empty($_SESSION['wybor'])) 
{
	
	
	
//sprawdzenie czy galezie istnieja, zeby moc je edytowac
$sprawdzgalaz = mysql_query("SELECT count(*) from galezie where projektGalaz='{$_SESSION['wybor']}' AND pozycjaGalaz!='dol' and glownaGalaz='0'");
$istgalaz=mysql_result($sprawdzgalaz, 0);
//formularz dodanie galezi i podgalezi
?>
<table border='1'>
<tr><td><b>Dodaj połączenie szeregowe</b></td><td><b>Dodaj połączenie równoległe</b></td></tr>
<tr><td>
<form method='post' width='100' action='?dodaj=galaz#galaz' >
<INPUT type="hidden" NAME="polaczenie" VALUE="szereg">
<select  name='galaz'>
<? 
	
		$nastepna=$istgalaz+1;
		echo "<option value='{$nastepna}'>Gałąź {$nastepna}</option>";

?>
</select><br>

<b>Opory oporników</b><br>
<?php
    for ($iopor=1; $iopor<=13 ; $iopor++)
	{
	?>
	R<?=$iopor;?>: <? if ($iopor<=9) echo "&nbsp;"; ?>	<input type="text" value="0" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporA[<?=$iopor;?>]" maxlength="7" size="6" required><b>.</b><input type="text" value="0" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporB[<?=$iopor;?>]" maxlength="2" size="1" required>Ω<br>
	<?
	}
?>
<br>

<button type="submit"  value="">Dodaj gałąź szeregową!</button></form>

</td>


<td>
<form method='post' width='100' action='?dodaj=galaz#galaz' >
<INPUT type="hidden" NAME="polaczenie" VALUE="rownoleg">
<select  name='galaz'>
<? 
	
		$nastepna=$istgalaz+1;
		echo "<option value='{$nastepna}'>Gałąź {$nastepna}</option>";

?>
</select><br>

<b>Opory oporników</b><br>
<i>Gałąź górna</i><br>
<?php
    for ($iopor=1; $iopor<=5 ; $iopor++)
	{
	?>
	R<?=$iopor;?>: <input type="text" value="0" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporGoraA[<?=$iopor;?>]" maxlength="7" size="6" required><b>.</b><input type="text" value="0" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporGoraB[<?=$iopor;?>]" maxlength="2" size="1" required>Ω<br>
	<?
	}
?>
<br>
<i>Gałąź dolna</i><br>
<?php
    for ($iopor=1; $iopor<=5 ; $iopor++)
	{
	?>
	R<?=$iopor;?>: <input type="text" value="0" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporDolA[<?=$iopor;?>]" maxlength="7" size="6" required><b>.</b><input type="text" value="0" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporDolB[<?=$iopor;?>]" maxlength="2" size="1" required>Ω<br>
	<?
	}
?>

<br>
<button type="submit"  value="">Dodaj gałąź równoległą!</button></form>

</td>


</td></tr>
</table>

<?
if ($_GET['dodaj']=='galaz') 
{ 
	
	if (!empty($_POST['galaz'])) 
	{
		echo "<br><div id='galaz'><h3><font color='green'><b>Pomyślnie dodano gałąź {$_POST['galaz']} !</b></font></h3></div>";
		
		
		//gdy wybrano polaczenie szeregowe
		if ($_POST['polaczenie']=='szereg')
		{
			$queryUtworzGalazSZ="INSERT INTO galezie(projektGalaz, polaczenieGalaz, dataGalaz) VALUES('{$_SESSION['wybor']}','{$_POST['polaczenie']}','{$aktualnadata}') ";
			mysql_query($queryUtworzGalazSZ);
			
			
			// ID dodanego rekordu    
			$ostatniidSZ = mysql_insert_id(); 

			for($liczop=1;$liczop<=13;$liczop++)
			{
			$opA=$_POST['oporA'][$liczop];
			$opB=$_POST['oporB'][$liczop];
			
			if ($opA==0 && $opB==0)
			{	}
			else
			{
				$queryUtworzOporniki="INSERT INTO oporniki(galazOpornik, opornikA, opornikB) VALUES('{$ostatniidSZ}','{$opA}','{$opB}') ";
				mysql_query($queryUtworzOporniki);
			}
			
			}
		} //koniec polaczenia szeregowego
		
		//gdy wybrano polaczenie rownolegle
		else if ($_POST['polaczenie']=='rownoleg')
		{
			
		$queryUtworzGalaz="INSERT INTO galezie(projektGalaz, polaczenieGalaz, dataGalaz, pozycjaGalaz) VALUES('{$_SESSION['wybor']}','{$_POST['polaczenie']}','{$aktualnadata}','gora') ";
		mysql_query($queryUtworzGalaz);
		$ostatniidROWGora = mysql_insert_id(); 
		
		$queryUtworzGalaz2="INSERT INTO galezie(projektGalaz, polaczenieGalaz, dataGalaz, pozycjaGalaz) VALUES('{$_SESSION['wybor']}','{$_POST['polaczenie']}','{$aktualnadata}','dol') ";
		mysql_query($queryUtworzGalaz2);
		$ostatniidROWDol = mysql_insert_id(); 

			
			//galaz gorna
			for($liczop=1;$liczop<=5;$liczop++)
			{
			$opA=$_POST['oporGoraA'][$liczop];
			$opB=$_POST['oporGoraB'][$liczop];
			
			if ($opA==0 && $opB==0)
			{	}
			else
			{
				$queryUtworzOpornikiG="INSERT INTO oporniki(galazOpornik, opornikA, opornikB, polozenie) VALUES('{$ostatniidROWGora}','{$opA}','{$opB}','gora') ";
				mysql_query($queryUtworzOpornikiG);
			}
			}
			
			
			//galaz dolna
			for($liczop=1;$liczop<=5;$liczop++)
			{
			$opA=$_POST['oporDolA'][$liczop];
			$opB=$_POST['oporDolB'][$liczop];
			
			if ($opA==0 && $opB==0)
			{	}
			else
			{
				$queryUtworzOpornikiD="INSERT INTO oporniki(galazOpornik, opornikA, opornikB, polozenie) VALUES('{$ostatniidROWDol}','{$opA}','{$opB}','dol') ";
				mysql_query($queryUtworzOpornikiD);
			}
			
			}
		} //koniec polaczenia rownoleglego
		
		
		echo "<meta http-equiv='Refresh' content='3; url=projekt.php' />";
	}
	//start edycja gałązi głównych
}

else if ($_GET['usun']=='galaz') 
{
	if (!empty($_GET['id'])) 
	{
		echo "<br><div id='usun'><font color='green'><b>Gałąź zostałą usunięta !</b></font></div>";
		$queryUsun1="DELETE FROM oporniki WHERE galazOpornik='{$_GET['id']}'";
		$queryUsun2="DELETE FROM galezie WHERE idGalaz='{$_GET['id']}'";
		$queryUsun3="DELETE FROM galezie WHERE glownaGalaz='{$_GET['id']}'";
		
		if (!empty($_GET['idrow'])) 
		{
		$queryUsun11="DELETE FROM oporniki WHERE galazOpornik='{$_GET['idrow']}'";
		$queryUsun22="DELETE FROM galezie WHERE idGalaz='{$_GET['idrow']}'";
		$queryUsun33="DELETE FROM galezie WHERE glownaGalaz='{$_GET['idrow']}'";
		mysql_query($queryUsun11);
		mysql_query($queryUsun22);
		mysql_query($queryUsun33);
		}
		
		mysql_query($queryUsun1);
		mysql_query($queryUsun2);
		mysql_query($queryUsun3);
	}
	echo "<meta http-equiv='Refresh' content='3; url=projekt.php' />";
}

else if ($_GET['edytuj']=='galazszereg')  //edycja gałązi start
{
	
	if (!empty($_POST['aktualnagalazedit'])) 
	{
		echo "<br><div id='podgalaz'><font color='green'><b>Pomyślnie zaktualizowano gałąź {$_POST['podgalazGLedit']} !</b></font></div>";
		
			$queryUsunOporniki="DELETE FROM oporniki WHERE galazOpornik='{$_POST['aktualnagalazedit']}'";
			mysql_query($queryUsunOporniki);
			//gdy wybrano polaczenie szeregowe
			if ($_POST['polaczeniegledit']=='szereg')
			{
			for($liczop=1;$liczop<=13;$liczop++)
			{
			$opA=$_POST['oporA'][$liczop];
			$opB=$_POST['oporB'][$liczop];
			
			if (($opA==0) && ($opB==0))
			{	}
			else
			{
				$queryUtworzOporniki="INSERT INTO oporniki(galazOpornik, opornikA, opornikB) VALUES('{$_POST['aktualnagalazedit']}','{$opA}','{$opB}') ";
				mysql_query($queryUtworzOporniki);
			}
			
			}
			} //koniec polaczenia szeregowego
			//gdy wybrano polaczenie rownolegle
			else if ($_POST['polaczeniegledit']=='rownoleg')
			{
				$queryUsunOporniki2="DELETE FROM oporniki WHERE galazOpornik='{$_POST['aktualnagalaz2edit']}'";
				mysql_query($queryUsunOporniki2);
				
				for($liczop=1;$liczop<=5;$liczop++)
				{
				$opA=$_POST['oporGoraA'][$liczop];
				$opB=$_POST['oporGoraB'][$liczop];
				
				if ($opA==0 && $opB==0)
				{	}
				else
				{
					$queryUtworzOpornikiG="INSERT INTO oporniki(galazOpornik, opornikA, opornikB, polozenie) VALUES('{$_POST['aktualnagalazedit']}','{$opA}','{$opB}','gora') ";
					mysql_query($queryUtworzOpornikiG);
				}
				}
				//galaz dolna
				for($liczop=1;$liczop<=5;$liczop++)
				{
				$opA=$_POST['oporDolA'][$liczop];
				$opB=$_POST['oporDolB'][$liczop];
				
				if ($opA==0 && $opB==0)
				{	}
				else
				{
					$queryUtworzOpornikiD="INSERT INTO oporniki(galazOpornik, opornikA, opornikB, polozenie) VALUES('{$_POST['aktualnagalaz2edit']}','{$opA}','{$opB}','dol') ";
					mysql_query($queryUtworzOpornikiD);
				}
				
				}
				
			}
			//koniec wyboru polaczenia rownoleglego
			
	echo "<meta http-equiv='Refresh' content='3; url=projekt.php' />";
	}
}
//edycja gałęzi koniec

//WYSWIETLENIE STRUKTURY PROJEKTU
echo "<br><font color='blue'><h2>Twój projekt</h2></font><hr>";

if ($istgalaz==0)
{
	echo "<b><font color='red'>Nie można wyświetlić symulacji gałęzi, ponieważ nie ma żadnych zdefiniowanych. Musisz je najpierw utworzyć..</font></b>";
}
else
{
	
	//wyswietlanie obecnych galezi
	echo "<table width='1200px' border='0'>";
	$wyswietl=mysql_query("SELECT * from galezie where projektGalaz='{$_SESSION['wybor']}' and glownagalaz='0' order by idGalaz ASC");
	$liczgalaz=1;
	while($roww=mysql_fetch_array($wyswietl))
	{
		$idGalazNAST=0;
		$idGalaz=$roww["idGalaz"];
		$pozycjaGalaz=$roww["pozycjaGalaz"];
		$polaczenieGalaz=$roww["polaczenieGalaz"];
		$dataGalaz=$roww["dataGalaz"];
		if ($pozycjaGalaz=='dol')
		{
		$liczgalaz=$liczgalaz-1;
		}
		else
		{
		echo "<tr><td>";
		
		$wyswietlnast=mysql_query("SELECT * from galezie where projektGalaz='{$_SESSION['wybor']}' and glownagalaz='0' AND pozycjaGalaz='dol' AND dataGalaz='{$dataGalaz}' order by idGalaz ASC");
		while($rownast=mysql_fetch_array($wyswietlnast))
		{
			$idGalazNAST=$rownast["idGalaz"];
		}
		//[{$polaczenieGalaz}] id {$idGalaz}
		echo "<b>{$liczgalaz} gałąź ";
		
		//if ($polaczenieGalaz=='rownoleg')
		//{
		//	echo " i dolna {$idGalazNAST}";
		//}
		
		echo "</b>";
		
		//START edycja gałązi głównej szregowej 
		if ($polaczenieGalaz=='szereg')
		{
			$rysunekgl='sz';
					?>
					
					<div id="edytujGLsz1[<?=$idGalaz;?>]" style="display:block;"  onclick="document.getElementById('edytujGLsz2[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujGLsz1[<?=$idGalaz;?>]').style.display='none';">
					<b><font color='#006633'>Kliknij tutaj aby edytować gałąź <u>szeregową</u></font></b>
					</div>
					<div id="edytujGLsz2[<?=$idGalaz;?>]" style="display:none;">
							
					<table border='1'>
					<tr><td>
					<a href='?usun=galaz&id=<?=$idGalaz;?>#usun'><font color='red'>Usuń gałąź!</font></a>
					<form method='post' width='100'action='?edytuj=galazszereg#galaz' >
					<INPUT type="hidden" NAME="aktualnagalazedit" VALUE="<?=$idGalaz;?>">
					<INPUT type="hidden" NAME="polaczeniegledit" VALUE="szereg">
					<INPUT type="hidden" NAME="wybormiejscegledit" VALUE="gora">
					<select  name='podgalazGLedit'>
					<? 
							echo "<option value='{$liczgalaz}'>Edytujesz {$liczgalaz} gałąź</option>";
					?>
					</select><br>

					<?
						$idIncludeEdycjaSZ=$idGalaz;
						include 'include/edytuj_szereg.php';
					?>

					<div onclick="document.getElementById('edytujGLsz1[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujGLsz2[<?=$idGalaz;?>]').style.display='none';">
							<font color='red'><b>Kliknij tutaj aby zwinąć menu edycji podgałęzi</b></font>
						</div>
					</div>
					<?
		}
		//EDYCJA koniec gałązi głównej szregowej 
		//EDYCJA start gałązi głównej równoległej 
		elseif ($polaczenieGalaz=='rownoleg')
		{
			$rysunekgl='row';
			?>
					<div id="edytujGLrow1[<?=$idGalaz;?>]" style="display:block;"  onclick="document.getElementById('edytujGLrow2[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujGLrow1[<?=$idGalaz;?>]').style.display='none';">
					<b><font color='#006633'>Kliknij tutaj aby edytować gałąź <u>równoległą</u></font></b>
					</div>
					<div id="edytujGLrow2[<?=$idGalaz;?>]" style="display:none;">
							
					<table border='1'>
					<tr><td>
					<a href='?usun=galaz&id=<?=$idGalaz;?>&idrow=<?=$idGalazNAST;?>#usun'><font color='red'>Usuń gałąź!</font></a>
					<form method='post' width='100'action='?edytuj=galazszereg#galaz' >
					<INPUT type="hidden" NAME="aktualnagalazedit" VALUE="<?=$idGalaz;?>">
					<INPUT type="hidden" NAME="aktualnagalaz2edit" VALUE="<?=$idGalazNAST;?>">
					<INPUT type="hidden" NAME="polaczeniegledit" VALUE="rownoleg">
					<INPUT type="hidden" NAME="wybormiejscegledit" VALUE="gora">
					<select  name='podgalazGLedit'>
					<? 
							echo "<option value='{$liczgalaz}'>Edytujesz {$liczgalaz} gałąź</option>";
					?>
					</select><br>

					<?
					
						$idIncludeEdycjaROWGora=$idGalaz;
						$idIncludeEdycjaROWDol=$idGalazNAST;
						include 'include/edytuj_rownoleg_podwojny.php';
					?>

					<div onclick="document.getElementById('edytujGLrow1[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujGLrow2[<?=$idGalaz;?>]').style.display='none';">
							<font color='red'><b>Kliknij tutaj aby zwinąć menu edycji podgałęzi</b></font>
						</div>
					</div>
			<?
		}
		//EDYCJA koniec gałązi głównej równoległej 
		
		//druga kolumna na podgałęzie start
		echo "</td><td>";
		
		
		if ($polaczenieGalaz=='szereg')
		{
			//sprawdzenie czy podgalezie istnieje gdy glowna galaz jest szeregowa
			$sprawdzpodgalazszereg = mysql_query("SELECT count(*) from galezie where glownaGalaz='{$idGalaz}'");
			$istpodgalazszereg=mysql_result($sprawdzpodgalazszereg, 0);
			
			if ($istpodgalazszereg==0) //dodanie gałęzi gdy nie ma podgałęzi
			{
				$rysunekp='no';
				include 'include/galaz_szereg.php';
			}
			else
			{	
				if ($polaczenieGalaz=='szereg') //gdy główna gałąź jest szeregowa
				{
				//sprawdzenie jaka podgałąź gdy główna gałąź jest szeregowa
				$pokazgalazszereg=mysql_query("SELECT * from galezie where projektGalaz='{$_SESSION['wybor']}' and glownagalaz='{$idGalaz}'");
				while($rowgalazszereg=mysql_fetch_array($pokazgalazszereg))
				{
					$polaczenieGalazSzereg=$rowgalazszereg["polaczenieGalaz"];
					$idGalazSzereg=$rowgalazszereg["idGalaz"];
				}
				if ($polaczenieGalazSzereg=='szereg')
				{
					$rysunekp='sz';
					//edycja gałęzi szeregowej pojedynczej
					?>
						
					<div id="edytujsz1[<?=$idGalaz;?>]" style="display:block;"  onclick="document.getElementById('edytujsz2[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujsz1[<?=$idGalaz;?>]').style.display='none';">
					<b><font color='darkblue'>Kliknij tutaj aby edytować podgałąź szeregową</font></b>
					</div>
					<div id="edytujsz2[<?=$idGalaz;?>]" style="display:none;">
							
					<table border='1'>
					<tr><td>
					<a href='?usun=galaz&id=<?=$idGalazSzereg;?>#usun'><font color='red'>Usuń podgałąź!</font></a>
					<form method='post' width='100' action='<?=$_SERVER['REQUEST_URI'];?>#podgalaz' >
					<INPUT type="hidden" NAME="aktualnagalazedit" VALUE="<?=$idGalaz;?>">
					<INPUT type="hidden" NAME="polaczeniegedit" VALUE="szereg">
					<INPUT type="hidden" NAME="wybormiejsceedit" VALUE="gora">
					<select  name='podgalazedit'>
					<? 
							echo "<option value='{$liczgalaz}'>Edytujesz {$liczgalaz}.1 podgałąź</option>";
					?>
					</select><br>

					<?
						$idIncludeEdycjaSZ=$idGalazSzereg;
						include 'include/edytuj_szereg.php';
					?>

					<div onclick="document.getElementById('edytujsz1[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujsz2[<?=$idGalaz;?>]').style.display='none';">
							<font color='red'><b>Kliknij tutaj aby zwinąć menu edycji podgałęzi</b></font>
						</div>
					</div>
					<?
					//koniec edycji gałęzi szeregowej pojedynczej
				}
				else if ($polaczenieGalazSzereg=='rownoleg')//start edycji gałęzi równoległej pojedynczej
				{
					$rysunekp='row';
					?>
					<div id="pokazrow1[<?=$idGalaz;?>]" style="display:block;"  onclick="document.getElementById('pokazrow2[<?=$idGalaz;?>]').style.display='block';document.getElementById('pokazrow1[<?=$idGalaz;?>]').style.display='none';">
						<b><font color='darkblue'>Kliknij tutaj aby edytować podgałąź równoległą</font></b>
					</div>

					<div id="pokazrow2[<?=$idGalaz;?>]" style="display:none;">

					<table border='1'>
					<tr><td>
					<a href='?usun=galaz&id=<?=$idGalazSzereg;?>#usun'><font color='red'>Usuń podgałąź!</font></a>
					<form method='post' width='100' action='<?=$_SERVER['REQUEST_URI'];?>#podgalaz' >
					<INPUT type="hidden" NAME="aktualnagalazedit" VALUE="<?=$idGalaz;?>">
					<INPUT type="hidden" NAME="polaczeniegedit" VALUE="rownoleg">
					<select  name='podgalazedit'>
					<? 
					echo "<option value='{$liczgalaz}'>Edytujesz {$liczgalaz}.1 podgałąź</option>";
					?>
					</select><br>
					<?
					$idIncludeEdycjaROW=$idGalazSzereg;
					include 'include/edytuj_rownoleg.php';
					?>

					<div onclick="document.getElementById('pokazrow1[<?=$idGalaz;?>]').style.display='block';document.getElementById('pokazrow2[<?=$idGalaz;?>]').style.display='none';">
							<font color='red'><b>Kliknij tutaj aby zwinąć menu edycji podgałęzi</b></font>
						</div>
					</div>
					<?
				}//koniec edycji gałęzi równoległej pojedynczej
				}//koniec gdy gałąź główna jest szeregowa
				else if ($polaczenieGalaz=='rownoleg') //gdy główna gałąź jest równoległa, mamy podgałaź dolną i górną
				{
					
				} //koniec gdy główna gałąź jest równoległa
				
			}
		}
		else if ($polaczenieGalaz=='rownoleg')
		{
			//sprawdzenie czy górna gałąź istnieje
			$sprawdzpodgalazgorna = mysql_query("SELECT count(*) from galezie where glownaGalaz='{$idGalaz}'");
			$istpodgalazgorna=mysql_result($sprawdzpodgalazgorna, 0);
			//sprawdzenie czy dolna gałąź istnieje
			$sprawdzpodgalazdolna = mysql_query("SELECT count(*) from galezie where glownaGalaz='{$idGalazNAST}'");
			$istpodgalazdolna=mysql_result($sprawdzpodgalazdolna, 0);
			
			//GORA
			if ($istpodgalazgorna!=0)
			{
				$galazgorna = mysql_fetch_array(mysql_query("SELECT * FROM galezie WHERE glownaGalaz='{$idGalaz}' LIMIT 1"));
				
				
				if ($galazgorna[polaczenieGalaz]=='szereg')
				{
					$rysunekpg='sz';
										//edycja gałęzi szeregowej gornej
					?>
						
					<div id="edytujszgora1[<?=$idGalaz;?>]" style="display:block;"  onclick="document.getElementById('edytujszgora2[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujszgora1[<?=$idGalaz;?>]').style.display='none';">
					<b><font color='darkblue'>Kliknij tutaj aby edytować podgałąź <u>górną szeregową</u></font></b>
					</div>
					<div id="edytujszgora2[<?=$idGalaz;?>]" style="display:none;">
							
					<table border='1'>
					<tr><td>
					<a href='?usun=galaz&id=<?=$galazgorna[idGalaz];?>#usun'><font color='red'>Usuń podgałąź!</font></a>
					<form method='post' width='100' action='<?=$_SERVER['REQUEST_URI'];?>#podgalaz' >
					<INPUT type="hidden" NAME="aktualnagalazedit" VALUE="<?=$idGalaz;?>">
					<INPUT type="hidden" NAME="aktualnagalaz2edit" VALUE="<?=$galazgorna[idGalaz];?>">
					<INPUT type="hidden" NAME="polaczeniegedit" VALUE="szereg">
					<INPUT type="hidden" NAME="wybormiejsceedit" VALUE="gora">
					<select  name='podgalazedit'>
					<? 
							echo "<option value='{$liczgalaz}'>Edytujesz {$liczgalaz}.1 podgałąź</option>";
					?>
					</select><br>

					<?
						$idIncludeEdycjaSZ=$galazgorna[idGalaz];
						include 'include/edytuj_szereg.php';
					?>

					<div onclick="document.getElementById('edytujszgora1[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujszgora2[<?=$idGalaz;?>]').style.display='none';">
							<font color='red'><b>Kliknij tutaj aby zwinąć menu edycji podgałęzi</b></font>
						</div>
					</div>
					<?
					//koniec edycji gałęzi szeregowej gornej
				}
				//start edycja gałęzi równoległej górnej
				else if ($galazgorna[polaczenieGalaz]=='rownoleg')
				{
					$rysunekpg='row';
					?>
					<div id="edytujrowgora1[<?=$idGalaz;?>]" style="display:block;"  onclick="document.getElementById('edytujrowgora2[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujrowgora1[<?=$idGalaz;?>]').style.display='none';">
					<b><font color='darkblue'>Kliknij tutaj aby edytować podgałąź <u>górną równoległą</u></font></b>
					</div>
					<div id="edytujrowgora2[<?=$idGalaz;?>]" style="display:none;">
							
					<table border='1'>
					<tr><td>
					<a href='?usun=galaz&id=<?=$galazgorna[idGalaz];?>#usun'><font color='red'>Usuń podgałąź!</font></a>
					<form method='post' width='100' action='<?=$_SERVER['REQUEST_URI'];?>#podgalaz' >
					<INPUT type="hidden" NAME="aktualnagalazedit" VALUE="<?=$idGalaz;?>">
					<INPUT type="hidden" NAME="aktualnagalaz2edit" VALUE="<?=$galazgorna[idGalaz];?>">
					<INPUT type="hidden" NAME="polaczeniegedit" VALUE="rownoleg">
					<INPUT type="hidden" NAME="wybormiejsceedit" VALUE="gora">
					<select  name='podgalazedit'>
					<? 
							echo "<option value='{$liczgalaz}'>Edytujesz {$liczgalaz}.1 podgałąź</option>";
					?>
					</select><br>

					<?
						$idIncludeEdycjaROW=$galazgorna[idGalaz];
						include 'include/edytuj_rownoleg.php';
					?>

					<div onclick="document.getElementById('edytujrowgora1[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujrowgora2[<?=$idGalaz;?>]').style.display='none';">
							<font color='red'><b>Kliknij tutaj aby zwinąć menu edycji podgałęzi</b></font>
						</div>
					</div>
					
					<?
				}//koniec edycja gałęzi równoległej górnej 
				
				//if (($istpodgalazgorna!=0) &&  ($istpodgalazdolna!=0)) { echo "<br>"; }
			}
			else
			{
				$rysunekpg='no';
				
				include 'include/galaz_gorna.php';
			}
			//DOL
			if ($istpodgalazdolna!=0)
			{
				$galazdolna = mysql_fetch_array(mysql_query("SELECT * FROM galezie WHERE glownaGalaz='{$idGalazNAST}' LIMIT 1"));
				
				
				if ($galazdolna[polaczenieGalaz]=='szereg')
				{
					$rysunekpd='sz';
					//edycja gałęzi szeregowej dolnej
					?>
						
					<div id="edytujszdol1[<?=$idGalaz;?>]" style="display:block;"  onclick="document.getElementById('edytujszdol2[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujszdol1[<?=$idGalaz;?>]').style.display='none';">
					<b><font color='darkblue'>Kliknij tutaj aby edytować podgałąź <u>dolną szeregową</u></font></b>
					</div>
					<div id="edytujszdol2[<?=$idGalaz;?>]" style="display:none;">
							
					<table border='1'>
					<tr><td>
					<a href='?usun=galaz&id=<?=$galazdolna[idGalaz];?>#usun'><font color='red'>Usuń podgałąź!</font></a>
					<form method='post' width='100' action='<?=$_SERVER['REQUEST_URI'];?>#podgalaz' >
					<INPUT type="hidden" NAME="aktualnagalazedit" VALUE="<?=$idGalaz;?>">
					<INPUT type="hidden" NAME="aktualnagalaz2edit" VALUE="<?=$galazdolna[idGalaz];?>">
					<INPUT type="hidden" NAME="polaczeniegedit" VALUE="szereg">
					<INPUT type="hidden" NAME="wybormiejsceedit" VALUE="dol">
					<select  name='podgalazedit'>
					<? 
							echo "<option value='{$liczgalaz}'>Edytujesz {$liczgalaz}.1 podgałąź</option>";
					?>
					</select><br>

					<?
						$idIncludeEdycjaSZ=$galazdolna[idGalaz];
						include 'include/edytuj_szereg.php';
					?>

					<div onclick="document.getElementById('edytujszdol1[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujszdol2[<?=$idGalaz;?>]').style.display='none';">
							<font color='red'><b>Kliknij tutaj aby zwinąć menu edycji podgałęzi</b></font>
						</div>
					</div>
					<?
					//koniec edycji gałęzi szeregowej dolnej 
				}
				else if ($galazdolna[polaczenieGalaz]=='rownoleg')
				{
					$rysunekpd='row';
					?>
					<div id="edytujrowdol1[<?=$idGalaz;?>]" style="display:block;"  onclick="document.getElementById('edytujrowdol2[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujrowdol1[<?=$idGalaz;?>]').style.display='none';">
					<b><font color='darkblue'>Kliknij tutaj aby edytować podgałąź <u>dolną równoległą</u></font></b>
					</div>
					<div id="edytujrowdol2[<?=$idGalaz;?>]" style="display:none;">
							
					<table border='1'>
					<tr><td>
					<a href='?usun=galaz&id=<?=$galazgorna[idGalaz];?>#usun'><font color='red'>Usuń podgałąź!</font></a>
					<form method='post' width='100' action='<?=$_SERVER['REQUEST_URI'];?>#podgalaz' >
					<INPUT type="hidden" NAME="aktualnagalazedit" VALUE="<?=$idGalaz;?>">
					<INPUT type="hidden" NAME="aktualnagalaz2edit" VALUE="<?=$galazdolna[idGalaz];?>">
					<INPUT type="hidden" NAME="polaczeniegedit" VALUE="rownoleg">
					<INPUT type="hidden" NAME="wybormiejsceedit" VALUE="dol">
					<select  name='podgalazedit'>
					<? 
							echo "<option value='{$liczgalaz}'>Edytujesz {$liczgalaz}.1 podgałąź</option>";
					?>
					</select><br>

					<?
						$idIncludeEdycjaROW=$galazdolna[idGalaz];
						include 'include/edytuj_rownoleg.php';
					?>

					<div onclick="document.getElementById('edytujrowdol1[<?=$idGalaz;?>]').style.display='block';document.getElementById('edytujrowdol2[<?=$idGalaz;?>]').style.display='none';">
							<font color='red'><b>Kliknij tutaj aby zwinąć menu edycji podgałęzi</b></font>
						</div>
					</div>
					<?
				}
				
				
			}
			else
			{
				$rysunekpd='no';
				include 'include/galaz_dolna.php';
			}
		}
		
		
		
		
		//dodanie podgałęzi do gałęzi START
		if (!empty($_POST['podgalaz'])) 
		{
			
				if (($_POST['aktualnagalaz']==$idGalaz) || ($_POST['aktualnagalaz']==$idGalazNAST))
				{
					
						
					echo "<br><div id='podgalaz'><font color='green'><b>Pomyślnie dodano podgałąź {$_POST['podgalaz']}.1 gałęzi {$_POST['podgalaz']} !</b></font></div><br>";
					$queryUtworzGalaz="INSERT INTO galezie(glownaGalaz, projektGalaz, polaczenieGalaz, dataGalaz) VALUES('{$_POST['aktualnagalaz']}','{$_SESSION['wybor']}','{$_POST['polaczenieg']}','{$aktualnadata}') ";
					mysql_query($queryUtworzGalaz);
					
		//pobranie ID gałęzi, którą dodano
		$wyswietlID=mysql_query("SELECT * from galezie where dataGalaz='{$aktualnadata}'");
		while($rowid=mysql_fetch_array($wyswietlID))
		{
			$idmojapodgalaz=$rowid["idGalaz"];
		}			
		//gdy wybrano polaczenie szeregowe
		if ($_POST['polaczenieg']=='szereg')
		{
			for($liczop=1;$liczop<=13;$liczop++)
			{
			$opA=$_POST['oporA'][$liczop];
			$opB=$_POST['oporB'][$liczop];
			
			if ($opA==0 && $opB==0)
			{	}
			else
			{
				$queryUtworzOporniki="INSERT INTO oporniki(galazOpornik, opornikA, opornikB) VALUES('{$idmojapodgalaz}','{$opA}','{$opB}') ";
				mysql_query($queryUtworzOporniki);
			}
			
			}
		} //koniec polaczenia szeregowego
		
		//gdy wybrano polaczenie rownolegle
		else if ($_POST['polaczenieg']=='rownoleg')
		{
			//galaz gorna
			for($liczop=1;$liczop<=5;$liczop++)
			{
			$opA=$_POST['oporGoraA'][$liczop];
			$opB=$_POST['oporGoraB'][$liczop];
			
			if ($opA==0 && $opB==0)
			{	}
			else
			{
				$queryUtworzOpornikiG="INSERT INTO oporniki(galazOpornik, opornikA, opornikB, polozenie) VALUES('{$idmojapodgalaz}','{$opA}','{$opB}','gora') ";
				mysql_query($queryUtworzOpornikiG);
			}
			}
			
			
			//galaz dolna
			for($liczop=1;$liczop<=5;$liczop++)
			{
			$opA=$_POST['oporDolA'][$liczop];
			$opB=$_POST['oporDolB'][$liczop];
			
			if ($opA==0 && $opB==0)
			{	}
			else
			{
				$queryUtworzOpornikiD="INSERT INTO oporniki(galazOpornik, opornikA, opornikB, polozenie) VALUES('{$idmojapodgalaz}','{$opA}','{$opB}','dol') ";
				mysql_query($queryUtworzOpornikiD);
			}
			
			}
		} //koniec polaczenia rownoleglego
					
					
					
					
					echo "<meta http-equiv='Refresh' content='3; url=projekt.php' />";
					}
				}
		//dodanie podgałęzi do gałęzi KONIEC
		
		//EDYCJA podgałęzi START
		else if (!empty($_POST['podgalazedit'])) 
		{
			
				if (($_POST['aktualnagalazedit']==$idGalaz) || ($_POST['aktualnagalazedit']==$idGalazNAST))
				{
					
				 if (empty($_POST['aktualnagalaz2edit'])) 
				 {
					echo "<br><div id='podgalaz'><font color='green'><b>Pomyślnie zaktualizowano podgałąź {$_POST['podgalazedit']}.1 gałęzi {$_POST['podgalazedit']} !</b></font></div><br>";
					
					
					
					
			$queryUsunOporniki="DELETE FROM oporniki WHERE galazOpornik='{$idGalazSzereg}'";
			mysql_query($queryUsunOporniki);
		//gdy wybrano polaczenie szeregowe
		if ($_POST['polaczeniegedit']=='szereg')
		{
			for($liczop=1;$liczop<=13;$liczop++)
			{
			$opA=$_POST['oporA'][$liczop];
			$opB=$_POST['oporB'][$liczop];
			
			if (($opA==0) && ($opB==0))
			{	}
			else
			{
				$queryUtworzOporniki="INSERT INTO oporniki(galazOpornik, opornikA, opornikB) VALUES('{$idGalazSzereg}','{$opA}','{$opB}') ";
				mysql_query($queryUtworzOporniki);
			}
			
			}
		} //koniec polaczenia szeregowego
		else if ($_POST['polaczeniegedit']=='rownoleg')
		{
			//galaz gorna
			for($liczop=1;$liczop<=5;$liczop++)
			{
			$opA=$_POST['oporGoraA'][$liczop];
			$opB=$_POST['oporGoraB'][$liczop];
			
			if ($opA==0 && $opB==0)
			{	}
			else
			{
				$queryUtworzOpornikiG="INSERT INTO oporniki(galazOpornik, opornikA, opornikB, polozenie) VALUES('{$idGalazSzereg}','{$opA}','{$opB}','gora') ";
				mysql_query($queryUtworzOpornikiG);
			}
			}
			//galaz dolna
			for($liczop=1;$liczop<=5;$liczop++)
			{
			$opA=$_POST['oporDolA'][$liczop];
			$opB=$_POST['oporDolB'][$liczop];
			
			if ($opA==0 && $opB==0)
			{	}
			else
			{
				$queryUtworzOpornikiD="INSERT INTO oporniki(galazOpornik, opornikA, opornikB, polozenie) VALUES('{$idGalazSzereg}','{$opA}','{$opB}','dol') ";
				mysql_query($queryUtworzOpornikiD);
			}
			
			}
		} //koniec polaczenia szeregowego
		
			echo "<meta http-equiv='Refresh' content='3; url=projekt.php' />";
					}
					
					//edycja gałęzi gdy główna gałąź jest równoległa
					else
					{
							echo "<br><div id='podgalaz'><font color='green'><b>Pomyślnie zaktualizowano podgałąź {$_POST['podgalazedit']}.1 gałęzi {$_POST['podgalazedit']} !</b></font></div><br>";
							$queryUsunOporniki="DELETE FROM oporniki WHERE galazOpornik='{$_POST['aktualnagalaz2edit']}'";
							mysql_query($queryUsunOporniki);
							
							//gdy wybrano polaczenie szeregowe
							if ($_POST['polaczeniegedit']=='szereg')
							{
							for($liczop=1;$liczop<=13;$liczop++)
							{
							$opA=$_POST['oporA'][$liczop];
							$opB=$_POST['oporB'][$liczop];
							
							if (($opA==0) && ($opB==0))
							{	}
							else
							{
								$queryUtworzOporniki="INSERT INTO oporniki(galazOpornik, opornikA, opornikB) VALUES('{$_POST['aktualnagalaz2edit']}','{$opA}','{$opB}') ";
								mysql_query($queryUtworzOporniki);
							}
							}
							} //koniec polaczenia szeregowego
							
							else if ($_POST['polaczeniegedit']=='rownoleg')
							{
											
								//galaz gorna
								for($liczop=1;$liczop<=5;$liczop++)
								{
								$opA=$_POST['oporGoraA'][$liczop];
								$opB=$_POST['oporGoraB'][$liczop];
								
								if ($opA==0 && $opB==0)				{	}
								else
								{
									$queryUtworzOpornikiG="INSERT INTO oporniki(galazOpornik, opornikA, opornikB, polozenie) VALUES('{$_POST['aktualnagalaz2edit']}','{$opA}','{$opB}','gora') ";
									mysql_query($queryUtworzOpornikiG);
								}
								}
								//galaz dolna
								for($liczop=1;$liczop<=5;$liczop++)
								{
								$opA=$_POST['oporDolA'][$liczop];
								$opB=$_POST['oporDolB'][$liczop];
								
								if ($opA==0 && $opB==0)
								{	}
								else
								{
									$queryUtworzOpornikiD="INSERT INTO oporniki(galazOpornik, opornikA, opornikB, polozenie) VALUES('{$_POST['aktualnagalaz2edit']}','{$opA}','{$opB}','dol') ";
									mysql_query($queryUtworzOpornikiD);
								}
								
								}
							}
					echo "<meta http-equiv='Refresh' content='3; url=projekt.php' />";
					}
					
		}
					
						
				}
		//EDYCJA podgałęzi do gałęzi KONIEC 
		
		
		
		echo "</td></tr>"; //koniec kolumny na podgałęzie
		
		
		
		echo "<tr><td colspan='2'><center>";
		
		
		
		
		//RYSUNEK START
		
		if ($rysunekgl=='sz')
		{
			echo "<img src='images/gl_szereg.png'>";
		}
		else if ($rysunekgl=='row')
		{
			echo "<img src='images/gl_rownoleg.png'>";
		}
		
		
		if (($rysunekpg=='no') && ($rysunekpd=='no'))
		{
			echo "<img src='images/pgl_no_no_row.png'>";
		}
		
		if (($rysunekpg=='no') && ($rysunekpd=='row'))
		{
			echo "<img src='images/pgl_no_row.png'>";
		}
		if (($rysunekpg=='no') && ($rysunekpd=='sz'))
		{
			echo "<img src='images/pgl_no_sz.png'>";
		}
		if (($rysunekpg=='row') && ($rysunekpd=='row'))
		{
			echo "<img src='images/pgl_row_row.png'>";
		}
		if (($rysunekpg=='row') && ($rysunekpd=='no'))
		{
			echo "<img src='images/pgl_row_no.png'>";
		}
		if (($rysunekpg=='row') && ($rysunekpd=='sz'))
		{
			echo "<img src='images/pgl_row_sz.png'>";
		}
		if (($rysunekpg=='sz') && ($rysunekpd=='no'))
		{
			echo "<img src='images/pgl_sz_no.png'>";
		}
		if (($rysunekpg=='sz') && ($rysunekpd=='row'))
		{
			echo "<img src='images/pgl_sz_row.png'>";
		}
		if (($rysunekpg=='sz') && ($rysunekpd=='sz'))
		{
			echo "<img src='images/pgl_sz_sz.png'>";
		}
		if ($rysunekp=='sz')
		{
			echo "<img src='images/pgl_sz.png'>";
		}
		if ($rysunekp=='row')
		{
			echo "<img src='images/pgl_row.png'>";
		}
		if ($rysunekp=='no')
		{
			echo "<img src='images/pgl_no_no_sz.png'>";
		}
		//echo "test 1: {$rysunekpg}  test 2: {$rysunekp} test 3: {$rysunekpd} ";
		//RYSUNEK KONIEC
		$rysunekpg='';
		$rysunekp='';
		$rysunekpd='';
		
		//WYLICZANIE OPORU START
		
		
		//echo "<b>gałąź główna: {$polaczenieGalaz}  podgałąź {$polaczenieGalazSzereg}<br>2 podgałązie: górna: {$galazgorna[polaczenieGalaz]}  dolna: {$galazdolna[polaczenieGalaz]} istnieje{$istpodgalazszereg}</b>";
		
		
		
		if ($polaczenieGalaz=='szereg' && $polaczenieGalazSzereg=='szereg') //gdy gałąź główna to szereg i podgałąź jedna szereg
		{
			
					
					//podgałaź
					$policzoporP=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalazSzereg}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporP+=$opornikAsz.".".$opornikBsz;
						
					}
					//gałąź
					$policzoporG=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalaz}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporG+=$opornikAsz.".".$opornikBsz;
						
					}
					$policzopor=$policzoporP+$policzoporG;
					echo "<br><font color='navy'>Opór zastępczy w tej gałęzi wynosi {$policzopor} Ω</font>";
					$oporcalkowity+=(1/$policzopor);
					
		} //gdy gałąź główna to szereg i podgałąź jedna szereg
		
		else if ($polaczenieGalaz=='szereg' && $polaczenieGalazSzereg=='rownoleg') //gdy gałąź główna to szereg i podgałąź jedna równoleg
		{
					if ($istpodgalazszereg==0)
					{
						$wynikoporzast=0;
					}
					else
					{
					//podgałęzie
					$policzoporgora=0;
					$policzopordol=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik='{$idGalazSzereg}' order by polozenie DESC");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						
						$opornikAsz=$rowoporniki["opornikA"];
						$polozeniepodgalaz=$rowoporniki["polozenie"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
								
						if ($polozeniepodgalaz=='gora')
						{
							$opornikAsz.".".$opornikBsz;
							$policzoporgora+=$opornikAsz.".".$opornikBsz;
						}
						else if ($polozeniepodgalaz=='dol')
						{
							$opornikAsz.".".$opornikBsz;
							$policzopordol+=$opornikAsz.".".$opornikBsz;
						}
					
					$oporzastepczyrow=((1/$policzoporgora)+(1/$policzopordol));
					$wynikoporzast=round(pow($oporzastepczyrow,-1),2);
					
					}
					}
					//główna gałąź
					$policzoporG=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalaz}");
					
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						
						$policzoporG+=$opornikAsz.".".$opornikBsz;
						
						
					}
					$policzopor=$wynikoporzast+$policzoporG;
					echo "<br><font color='navy'>Opór zastępczy w tej gałęzi wynosi {$policzopor} Ω</font>";
					$oporcalkowity+=(1/$policzopor);
					
					
		}//gdy gałąź główna to szereg i podgałąź jedna równoleg
		
		else if (($polaczenieGalaz=='rownoleg') && ($galazgorna[polaczenieGalaz]=='szereg') && ($galazdolna[polaczenieGalaz]=='szereg')) // gałąź główna równoległa i podgałęzie są szeregowe
		{	
			
					if ($istpodgalazszereg==0)
					{
							$policzoporP1=0;
							$policzoporP2=0;
					}
					else
					{
					//podgałaź górna szeregowa
					$policzoporP1=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$galazgorna[idGalaz]}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporP1+=$opornikAsz.".".$opornikBsz;
					}
					//podgałaź dolna szeregowa
					$policzoporP2=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$galazdolna[idGalaz]}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporP2+=$opornikAsz.".".$opornikBsz;
					}
					
					}
					
					//podgałaź górna rownolegla
					$policzoporG1=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalaz}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporG1+=$opornikAsz.".".$opornikBsz;
					}
					
						
					$oporgora=$policzoporG1+$policzoporP1;
					
					//podgałaź górna rownolegla
					$policzoporG2=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalazNAST}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporG2+=$opornikAsz.".".$opornikBsz;
					}
					$opordol=$policzoporG2+$policzoporP2;
					//opor calkowity dla tej galezi
					$oporzastepczyrow=((1/$oporgora)+(1/$opordol));
					$wynikoporzast=round(pow($oporzastepczyrow,-1),2);
					echo "<br><font color='navy'>Opór zastępczy w tej gałęzi wynosi {$wynikoporzast} Ω</font>";
					$oporcalkowity+=(1/$wynikoporzast);
		}
		else if (($polaczenieGalaz=='rownoleg') && ($galazgorna[polaczenieGalaz]=='szereg') && ($galazdolna[polaczenieGalaz]=='rownoleg'))
		{
					//podgałaź górna szeregowa
					$policzoporP1=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$galazgorna[idGalaz]}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporP1+=$opornikAsz.".".$opornikBsz;
					}
					
					//podgałęz dolna(rownolegla)
					$policzoporgora=0;
					$policzopordol=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$galazdolna[idGalaz]} order by polozenie DESC");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						
						$opornikAsz=$rowoporniki["opornikA"];
						$polozeniepodgalaz=$rowoporniki["polozenie"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
								
						if ($polozeniepodgalaz=='gora')
						{
							$opornikAsz.".".$opornikBsz;
							$policzoporgora+=$opornikAsz.".".$opornikBsz;
						}
						else if ($polozeniepodgalaz=='dol')
						{
							$opornikAsz.".".$opornikBsz;
							$policzopordol+=$opornikAsz.".".$opornikBsz;
						}
					
					$oporzastepczyrow=((1/$policzoporgora)+(1/$policzopordol));
					$policzoporP2=round(pow($oporzastepczyrow,-1),2);
					
					}
					
					
					//gałaź górna oporniki
					$policzoporG1=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalaz}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporG1+=$opornikAsz.".".$opornikBsz;
					}
					
					//gałaź dolna oporniki
					$policzoporG2=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalazNAST}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporG2+=$opornikAsz.".".$opornikBsz;
					}
					
					$galazGora=$policzoporG1+$policzoporP1;
					$galazDol=$policzoporG2+$policzoporP2;
					//opor calkowity dla tej galezi
					$oporzastepczyrow=((1/$galazGora)+(1/$galazDol));
					$wynikoporzast=round(pow($oporzastepczyrow,-1),2);
					echo "<br><font color='navy'>Opór zastępczy w tej gałęzi wynosi {$wynikoporzast} Ω</font>";
					$oporcalkowity+=(1/$wynikoporzast);
		}
		else if (($polaczenieGalaz=='rownoleg') && ($galazgorna[polaczenieGalaz]=='rownoleg') && ($galazdolna[polaczenieGalaz]=='rownoleg')) //główna gałąź równoległa i pozostałe też równoległe
		{
			
			
					//podgałęz górna(rownolegla)
					$policzoporgora=0;
					$policzopordol=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$galazgorna[idGalaz]} order by polozenie DESC");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						
						$opornikAsz=$rowoporniki["opornikA"];
						$polozeniepodgalaz=$rowoporniki["polozenie"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
								
						if ($polozeniepodgalaz=='gora')
						{
							$opornikAsz.".".$opornikBsz;
							$policzoporgora+=$opornikAsz.".".$opornikBsz;
						}
						else if ($polozeniepodgalaz=='dol')
						{
							$opornikAsz.".".$opornikBsz;
							$policzopordol+=$opornikAsz.".".$opornikBsz;
						}
					
					$oporzastepczyrowg=((1/$policzoporgora)+(1/$policzopordol));
					$policzoporP1=round(pow($oporzastepczyrowg,-1),2);
					
					}
					
					//podgałęz dolna(rownolegla)
					$policzoporgora=0;
					$policzopordol=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$galazdolna[idGalaz]} order by polozenie DESC");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						
						$opornikAsz=$rowoporniki["opornikA"];
						$polozeniepodgalaz=$rowoporniki["polozenie"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
								
						if ($polozeniepodgalaz=='gora')
						{
							$opornikAsz.".".$opornikBsz;
							$policzoporgora+=$opornikAsz.".".$opornikBsz;
						}
						else if ($polozeniepodgalaz=='dol')
						{
							$opornikAsz.".".$opornikBsz;
							$policzopordol+=$opornikAsz.".".$opornikBsz;
						}
					
					$oporzastepczyrow=((1/$policzoporgora)+(1/$policzopordol));
					$policzoporP2=round(pow($oporzastepczyrow,-1),2);
					
					}
					
					
					//gałaź górna oporniki
					$policzoporG1=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalaz}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporG1+=$opornikAsz.".".$opornikBsz;
					}
					
					//gałaź dolna oporniki
					$policzoporG2=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalazNAST}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporG2+=$opornikAsz.".".$opornikBsz;
					}
					
					$galazGora=$policzoporG1+$policzoporP1;
					$galazDol=$policzoporG2+$policzoporP2;
					//opor calkowity dla tej galezi
					$oporzastepczyrowcalk=((1/$galazGora)+(1/$galazDol));
					$wynikoporzastcalk=round(pow($oporzastepczyrowcalk,-1),2);
					if ($wynikoporzastcalk==INF) { $wynikoporzastcalk=0; }
					echo "<br><font color='navy'>Opór zastępczy w tej gałęzi wynosi {$wynikoporzastcalk} Ω</font>";
					
					$oporcalkowity+=(1/$wynikoporzastcalk);
		}
		
		else if (($polaczenieGalaz=='rownoleg') && ($galazgorna[polaczenieGalaz]=='rownoleg') && ($galazdolna[polaczenieGalaz]=='szereg')) //główna gałąź równoległa i pozostałe też równoległe
		{

			
			 if ($istpodgalazszereg==0)
			 {
				 $policzoporP1=0;
				 $policzoporP2=0;
				 echo 'tak';
			 }
			 else
			 {
				
					//podgałęz górna(rownolegla)
					$policzoporgora=0;
					$policzopordol=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$galazgorna[idGalaz]} order by polozenie DESC");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						
						$opornikAsz=$rowoporniki["opornikA"];
						$polozeniepodgalaz=$rowoporniki["polozenie"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
								
						if ($polozeniepodgalaz=='gora')
						{
							$opornikAsz.".".$opornikBsz;
							$policzoporgora+=$opornikAsz.".".$opornikBsz;
						}
						else if ($polozeniepodgalaz=='dol')
						{
							$opornikAsz.".".$opornikBsz;
							$policzopordol+=$opornikAsz.".".$opornikBsz;
						}
					
					$oporzastepczyrow=((1/$policzoporgora)+(1/$policzopordol));
					$policzoporP1=round(pow($oporzastepczyrow,-1),2);
					
					}
					
					
					//podgałaź dolna szeregowa
					$policzoporP2=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$galazdolna[idGalaz]}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporP2+=$opornikAsz.".".$opornikBsz;
					}
			 }
					
					
					//gałaź górna oporniki
					$policzoporG1=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalaz}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporG1+=$opornikAsz.".".$opornikBsz;
					}
					
					//gałaź dolna oporniki
					$policzoporG2=0;
					$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik={$idGalazNAST}");
					while($rowoporniki=mysql_fetch_array($opornikipokaz))
					{
						$opornikAsz=$rowoporniki["opornikA"];
						$opornikBsz=sprintf("%02d",  $rowoporniki["opornikB"]);	
						$policzoporG2+=$opornikAsz.".".$opornikBsz;
					}
					
					$galazGora=$policzoporG1+$policzoporP1;
					$galazDol=$policzoporG2+$policzoporP2;
					//opor calkowity dla tej galezi
					$oporzastepczyrow=((1/$galazGora)+(1/$galazDol));
					$wynikoporzast=round(pow($oporzastepczyrow,-1),2);
					echo "<br><font color='navy'>Opór zastępczy w tej gałęzi wynosi {$wynikoporzast} Ω</font>";
					$oporcalkowity+=(1/$wynikoporzast);
					
		}
		//WYLICZANIE OPORU KONIEC
		
		echo "</center><hr></td></tr></center>";
		} //zamkniecie ifa
		$liczgalaz++;
	}//koniec while wyswietlenia obecnych galezi
}	

?>
</table>
<?

if ($istgalaz!=0)
{
$wynikcalkowity=round(pow($oporcalkowity,-1),2);
if ($wynikcalkowity==INF) { $wynikcalkowity=0; }
echo "<h2><font color='darkgreen'>Opór zastępczy dla całego projektu wynosi {$wynikcalkowity} Ω</font></h2>";
}
}
?></center><br>