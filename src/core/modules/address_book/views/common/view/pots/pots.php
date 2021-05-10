
				<!-- start of address_book common pots modal -->
<?php
	
	if(!empty($pots))
	{
?>
	<table class="table">
		<thead>
			<tr>
				<th>Type</th>
				<th>Country</th>
				<th>Number</th>
				<th>Private</th>
				<th>WhatsApp!</th>
				<th>Viber</th>
			</tr>
		</thead>
		<tbody>
<?php	
		foreach($pots as $details)
		{
			echo '
				<tr>
					<td>'.$details['type'].'</td>
					<td>+'.$details['dialInfo']['dialCode'].'</td>
					<td>'.$details['number'].'</td>
					<td>'.$details['private'].'</td>
					<td>'.$details['whatsapp'].'</td>
					<td>'.$details['viber'].'</td>
				</tr>
				';
		}
?>
		</tbody>
	</table>
<?php		
	}
?>
				<!-- end of address_book common pots modal -->
				