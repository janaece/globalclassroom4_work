<form action="<?php echo ('deleteInstitution')?>" method="POST">
  <table>
  	
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Delete eSchool System" onclick="return confirmPost('****Are you sure you want to DELETE this Institution (and all Moodles owned by this institution)?**** This cannot be undone, and all data associated with this Institution will be lost!')" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     	<tr>
        	<td>
        		<b>Maharas:</b>
        		<select id="institution" name="institution">
        			<?php 
			  		foreach($institutionList as $institution)
			  		{
			  			$count = 0;
			  			if ($eschools = $institution->getEschools())
			  			{
			  				$count = count($eschools);
			  			}
			  			print '<option value="' . $institution->getId() . '">' . $institution->getFullName() . 
			  				' (' . $institution->getShortName() . ') with ' . $count . ' Moodle(s)</option>';
			  		}
			  		?>
			  	</select>
			</td>
  		</tr>
    </tbody>
  </table>
</form>