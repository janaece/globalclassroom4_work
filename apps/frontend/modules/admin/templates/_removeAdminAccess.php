<form action="<?php echo ('removeAdminAccess')?>" method="POST">
  <table> 	
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Remove Access" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     	<tr>
        	<td>
        		<b>Sitewide Admins:</b>
        		<select id="admin" name="admin">
        			<?php 
			  		foreach($adminList as $admin)
			  		{
			  			print '<option value="' . $admin->id . '">' . $admin->username . '</option>';
			  		}
			  		?>
			  	</select>
			</td>
  		</tr>
    </tbody>
  </table>
</form>