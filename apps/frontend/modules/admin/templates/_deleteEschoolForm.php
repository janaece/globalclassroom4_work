<form action="<?php echo ('deleteEschool')?>" method="POST">
  <table>
  	
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Delete Moodle" onclick="return confirmPost('****Are you sure you want to DELETE this eSchool?**** This cannot be undone, and all data associated with this eSchool will be lost!')" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     	<tr>
        	<td>
        		<b>Moodles:</b>
        		<select id="eschool" name="eschool">
        			<?php 
			  		foreach($eschoolList as $eschool)
			  		{
			  			print '<option value="' . $eschool->id . '">' . $eschool->full_name . ' (' . $eschool->short_name . 
			  				') owner: ' . $eschool->getInstitution()->getShortName() . '</option>';
			  		}
			  		?>
			  	</select>
			</td>
  		</tr>
    </tbody>
  </table>
</form>