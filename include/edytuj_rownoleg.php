
					<b>Opory oporników</b><br>
					<i>Gałąź górna</i><br>
					<?php
					$opornikAsz=array();
					$opornikBsz=array();
					//Czyszczenie śmieci z CACHE formularza
					for ($iopor=1; $iopor<=13 ; $iopor++)
					{
						$opornikAsz[$iopor]=0;	
						$opornikBsz[$iopor]=0;	
					}
						//koniec czyszczenia
						$liczsz=1;
						$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik='{$idIncludeEdycjaROW}' AND polozenie='gora' order by idOpornik ASC");
						while($rowoporniki=mysql_fetch_array($opornikipokaz))
						{
							$opornikAsz[$liczsz]=$rowoporniki["opornikA"];
							$opornikBsz[$liczsz]=sprintf("%02d",  $rowoporniki["opornikB"]);
							$liczsz++;
						}
						for ($iopor=1; $iopor<=5 ; $iopor++)
						{
							if (empty($opornikAsz[$iopor]))  {	$opornikAsz[$iopor]=0;	}
							if (empty($opornikBsz[$iopor]))  {	$opornikBsz[$iopor]=0;	}
						?>
						R<?=$iopor;?>: <? if ($iopor<=9) echo "&nbsp;"; ?> <input type="text" value="<?=$opornikAsz[$iopor];?>" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporGoraA[<?=$iopor;?>]" maxlength="7" size="6" required><b>.</b><input type="text" value="<?=$opornikBsz[$iopor];?>" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporGoraB[<?=$iopor;?>]" maxlength="2" size="1" required>Ω<br>
						<?
						}
					?> 
					<br>
					<i>Gałąź dolna</i><br>
					<?php
						//Czyszczenie śmieci z CACHE formularza
					for ($iopor=1; $iopor<=13 ; $iopor++)
					{
						$opornikAsz[$iopor]=0;	
						$opornikBsz[$iopor]=0;	
					}
						//koniec czyszczenia
						$liczsz=1;
						$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik='{$idIncludeEdycjaROW}' AND polozenie='dol' order by idOpornik ASC");
						while($rowoporniki=mysql_fetch_array($opornikipokaz))
						{
							$opornikAsz[$liczsz]=$rowoporniki["opornikA"];
							$opornikBsz[$liczsz]=sprintf("%02d",  $rowoporniki["opornikB"]);
							$liczsz++;
						}
						for ($iopor=1; $iopor<=5 ; $iopor++)
						{
							if (empty($opornikAsz[$iopor]))  {	$opornikAsz[$iopor]=0;	}
							if (empty($opornikBsz[$iopor]))  {	$opornikBsz[$iopor]=0;	}
						?>
						R<?=$iopor;?>: <? if ($iopor<=9) echo "&nbsp;"; ?> <input type="text" value="<?=$opornikAsz[$iopor];?>" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporDolA[<?=$iopor;?>]" maxlength="7" size="6" required><b>.</b><input type="text" value="<?=$opornikBsz[$iopor];?>" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporDolB[<?=$iopor;?>]" maxlength="2" size="1" required>Ω<br>
						<?
						}
					?>

					<br>
					<button type="submit"  value="">Edytuj gałąź równoległą!</button></form>

					</td>

					</tr></table>