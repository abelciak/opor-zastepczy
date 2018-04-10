<? include('./config.php'); ?>
<title>Obliczanie oporu zastępczego</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css" />
<center>
<h1>Projekt "Obliczanie oporu zastępczego"</h1>
<br><br>
<center>

<b><font color='#600CE8'>Posiadasz zapisany projekt i chcesz go kontynuować?<br></font></b>
<form method="post" action="<?=$_SERVER['REQUEST_URI'];?>" > 
<b>Wprowadź ID projektu: </b> <input type="text" name="idprojekt" maxlength="6" onkeyup="this.value=this.value.replace(/\D/g,'')" value="">
<button type="submit"  value="">Kontynuuj projekt</button>
</form>

<?
//sprawdzenie id projektu 
$idprojekt = $_POST['idprojekt'];
$result = mysql_query("SELECT count(*) from projekty where idprojekt='{$idprojekt}'");
$sprawdzenie=mysql_result($result, 0);

if (!empty($_POST['idprojekt']))
{
	if ($sprawdzenie==0)
	{
		echo "<font color='red'><b>Błąd! Podałeś nieprawidłowy numer projektu ! Wpisz poprawny id projektu aby kontynuować! </b></font><br>";
	}
	else
	{
		$_SESSION['wybor'] = $_POST['idprojekt'];
		echo 'test pokazania'.$_POST['idprojekt'];
		echo 'test sesji'.$_SESSION['wybor'];
		$_SESSION['wybor'] = $_POST['idprojekt'];	
		header('Location: projekt.php');
		exit;
	}
}
?>

<br>
<b><font color='darkblue'>Nie posiadasz projektu ?</font></b>
<form method="post" action="projekt.php">
<INPUT type="hidden" NAME="projekt" VALUE="utworz">
<input type="submit" value="Utwórz nowy projekt !"></form>



</center>