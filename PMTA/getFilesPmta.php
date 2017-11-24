<?php



$dir   = '/var/www/exactarget/PMTA';
$files = scandir($dir);

echo '<table> <tr><td></td>  <td rowspan="20"><textarea class="form-control" id="txtContent" cols="200" rows="10"></textarea></td></tr>';
foreach($files as $file)
{
	if(preg_match('[.*-config.txt]', $file))
	{
		echo '<tr> <td> <a class="file" id="'.$file.'"> <span class="label bg-success-400">'.$file.'</span> </a> </td> </tr>';
	}
} 

echo '</table>';

?>