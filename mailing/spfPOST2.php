<?php
 
 echo'
 <table class="table datatable-basic">
								<thead>
									<tr>
										<th>Domain</th>
										<th>SPF Result</th>
									</tr>
								</thead>
								
								<tbody id="tableResult">
';															
								
							
							
   $domains = rtrim($_POST['domains'],',');
   $split = explode(',',$domains);

   
   foreach($split as $domain)
   {
	   $spf = shell_exec('nslookup -type=txt '.$domain);
	   
		
		$exists=strpos($spf,"exists",1);
		if ($exists){
			echo "<tr><td style=color:green><b>$domain</b></td>";
			echo  "<td><pre>$spf</pre></td>";
			echo "</tr>";
			
		}
		/*else {
			echo "<td style=color:red>Doesn't exists</td>";
		}
		echo  "<td><pre>$spf</pre></td>";
		echo "</tr>";*/

	}
	
echo
'
</tbody>
						    </table>
';

?>
<script type="text/javascript" src="datatables2_basic.js"></script>
