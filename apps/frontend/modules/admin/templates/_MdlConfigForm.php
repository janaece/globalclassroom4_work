<form action="<?php echo ('updateMdlConfig')?>" method="POST">
  <table>
  	
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Submit" onclick="return confirmPost('Are you sure you want to UPDATE THIS CONFIG VALUE?')" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     	<tr>
        	<td>
        		<b>eSchool(s):</b>
        		<select id="eschool" name="eschool">
        			<option value="1">All eSchools</option>
			  		<?php 
			  		foreach($eschoolList as $eschool)
			  		{
			  			print '<option value="' . $eschool->getShortName() . '">' . $eschool->getFullName() . ' (' . $eschool->getShortName() . ')</option>';
			  		}
			  		?>
			  	</select>
			</td>
  		</tr>
  		<tr>
        	<td>
        		<b>Config Vars:</b> 
        		<select id="config" name="config">
        			<?php 
			  		foreach($configVars as $var)
			  		{
			  			print '<option value="' . $var->name . '">' . $var->name . '</option>';
			  		}
			  		foreach($pluginConfigVars as $var)
			  		{
			  			print '<option value="#' . $var->name . '">' . $var->name . ' (plugins)</option>';
			  		}
			  		?>
			  	</select>
			</td>
  		</tr>
  		<tr>
        	<td>
        		<b>Set Value:</b> 
        		<input id="newConfigValue" name="newConfigValue" />
			</td>
  		</tr>
    </tbody>
  </table>
</form>