<form action="<?php echo ('addAdminAccess')?>" method="POST">
  <table>
  	
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Grant Access" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     	<tr>
        	<td>
        		<b>Users in Home:</b>
        		<select id="user" name="user">
        			<?php 
			  		foreach($userList as $user)
			  		{
			  			print '<option value="' . $user->id . '">' . $user->username . '</option>';
			  		}
			  		?>
			  	</select>
			</td>
  		</tr>
    </tbody>
  </table>
</form>