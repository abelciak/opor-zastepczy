<b>Opory oporników</b><br>
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
						$opornikipokaz=mysql_query("SELECT * from oporniki where galazOpornik='{$idIncludeEdycjaSZ}' order by idOpornik ASC");
						//echo "SELECT * from oporniki where galazOpornik='{$idIncludeEdycjaSZ}' order by idOpornik ASC";
						while($rowoporniki=mysql_fetch_array($opornikipokaz))
						{
							$opornikAsz[$liczsz]=$rowoporniki["opornikA"];
							$opornikBsz[$liczsz]=sprintf("%02d",  $rowoporniki["opornikB"]);
							$liczsz++;
						}
						
						for ($iopor=1; $iopor<=13 ; $iopor++)
						{
							if (empty($opornikAsz[$iopor]))  {	$opornikAsz[$iopor]=0;	}
							if (empty($opornikBsz[$iopor]))  {	$opornikBsz[$iopor]=0;	}
						?>
						R<?=$iopor;?>: <? if ($iopor<=9) echo "&nbsp;"; ?> <input type="text" value="<?=$opornikAsz[$iopor];?>" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporA[<?=$iopor;?>]" maxlength="7" size="6" required><b>.</b><input type="text" value="<?=$opornikBsz[$iopor];?>" onkeyup="this.value=this.value.replace(/\D/g,'')" name="oporB[<?=$iopor;?>]" maxlength="2" size="1" required>Ω<br>
						<?
						
						}
					?>
					<br>
					<button type="submit"  value="">Edytuj gałąź szeregową!</button></form>
					</td></tr></table>