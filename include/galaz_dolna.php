
<div id="pokazd1[<?=$idGalaz;?>]" style="display:block;"  onclick="document.getElementById('pokazd2[<?=$idGalaz;?>]').style.display='block';document.getElementById('pokazd1[<?=$idGalaz;?>]').style.display='none';">
	<font color='purple'><b>Kliknij tutaj aby dodać podgałąź <u>dolną</u></b></font>
</div>

<div id="pokazd2[<?=$idGalaz;?>]" style="display:none;">		


<table border='1'>
<tr><td colspan='2'><center><h2><font color='darkblue'><b>Dodaj podgałaź dolną</b></font></h2></center></td></tr>		
<tr><td><b>Dodaj połączenie szeregowe</b></td><td><b>Dodaj połączenie równoległe</b></td></tr>
<tr><td>
<form method='post' width='100' action='<?=$_SERVER['REQUEST_URI'];?>#podgalaz' >
<INPUT type="hidden" NAME="aktualnagalaz" VALUE="<?=$idGalazNAST;?>">
<INPUT type="hidden" NAME="polaczenieg" VALUE="szereg">
<INPUT type="hidden" NAME="wybormiejsce" VALUE="dol">
<select  name='podgalaz'>
<? 
		echo "<option value='{$liczgalaz}'>Dodaj {$liczgalaz}.1 podgałąź</option>";
?>
</select><br>

<b>Opory oporników</b><br>
<?php
    for ($iopor=1; $iopor<=13 ; $iopor++)
	{
	?>
	R<?=$iopor;?>: <? if ($iopor<=9) echo "&nbsp;"; ?> <input type="text" value="0" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporA[<?=$iopor;?>]" maxlength="7" size="6" required><b>.</b><input type="text" value="0" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporB[<?=$iopor;?>]" maxlength="2" size="1" required>Ω<br>
	<?
	}
?>
<br>

<button type="submit"  value="">Dodaj gałąź szeregową!</button></form>

</td>

<td>
<form method='post' width='100' action='<?=$_SERVER['REQUEST_URI'];?>#podgalaz' >
<INPUT type="hidden" NAME="aktualnagalaz" VALUE="<?=$idGalazNAST;?>">
<INPUT type="hidden" NAME="polaczenieg" VALUE="rownoleg">
<select  name='podgalaz'>
<? 
echo "<option value='{$liczgalaz}'>Dodaj {$liczgalaz}.1 podgałąź</option>";
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






</tr></table>





<div onclick="document.getElementById('pokazd1[<?=$idGalaz;?>]').style.display='block';document.getElementById('pokazd2[<?=$idGalaz;?>]').style.display='none';">
		<font color='red'><b>Kliknij tutaj aby zwinąć menu dodawania podgałęzi <u>dolnej</u></b></font>
	</div>
</div>