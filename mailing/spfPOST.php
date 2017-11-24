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
	   
		  echo
		  '
			 <tr>
			   <td>'.$domain.'</td>
			   <td><pre>'.$spf.'</pre></td>
			 </tr>
		  ';
	}
	
echo
'
</tbody>
						    </table>
';

?>
<script type="text/javascript" src="datatables2_basic.js"></script>
