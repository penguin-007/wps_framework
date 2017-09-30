<html><body>
<table cellspacing="0" align="center" border="1" bgcolor="#F8F8F8" cellpadding="0" style="width:100%; max-width:600px;" >
<tr><td colspan="2" style="padding: 5px 10px; text-align:center;"><?= $form_title; ?></td></tr>

<?php 
foreach ($post as $key => $value) : 
	if ( $value != "" ) :
	?>
		<tr>
		  <td width="30%" style="padding: 5px 10px;"><?= $key; ?></td>
		  <td style="padding: 4px 8px;"><?= $value ?></td>
		</tr>
	<?php 
	endif;
endforeach;
?>

<tr><td colspan="2" style="padding: 5px 10px; text-align:center;">Конец письма</td></tr>
</table>
</body></html>