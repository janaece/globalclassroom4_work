<form action="<?php echo ('mnetReplacement')?>" method="POST">
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Submit" onclick="return confirmPost('Are you sure you want to REPLACE THE MNET KEY for this eSchool(s)?')" />
        </td>
      </tr>
    </tfoot>
    <tbody>
    	<tr>
        	<td>
        		<b>Maharas:</b>
        		<select id="eschool" name="eschool">
        			<option value="1">All eSchool Systems</option>
			  		<?php 
			  		foreach($institutionList as $institution)
			  		{
			  			$count = 0;
			  			if ($eschools = $institution->getEschools())
			  			{
			  				$count = count($eschools);
			  			}
			  			print '<option value="' . $institution->getShortName() . '">' . $institution->getFullName() . 
			  				' (' . $institution->getShortName() . ') with ' . $count . ' Moodle(s)</option>';
			  		}
			  		?>
			  	</select>
			</td>
  		</tr>
    </tbody>
  </table>
</form>