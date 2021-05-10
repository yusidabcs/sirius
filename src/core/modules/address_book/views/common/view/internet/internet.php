
				<!-- start of address_book common pots modal -->
<?php
	
	if(!empty($internet))
	{
?>
	<table class="table">
		<thead>
			<tr>
				<th>Type</th>
				<th>ID</th>
			</tr>
		</thead>
		<tbody>
<?php	
		foreach($internet as $details)
		{
			echo '
				<tr>
					<td>'.$details['type'].'</td>
					<td>'.$details['id'].'</td>
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
				