<form action="<?php echo ('setOrganizationId')?>" method="POST">
  <table>
  	
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Change Owner" onclick="return confirmPost('Are you sure you want to CHANGE THE INSTITUTION of this eSchool?')" />
        </td>
      </tr>
    </tfoot>
    <tbody>
        <tr>
        <td>
            <b>eSchools:</b>
            <select id="eschool" name="eschool">
                <?php 
                    foreach($eschoolList as $eschool_data)
                    {
                        $eschool = Doctrine::getTable('GcrEschool')->find($eschool_data->id);
                        if (!$eschool->getDefaultEschoolInstitution())
                        {
                            $institution = $eschool->getInstitution();
                            print '<option value="' . $eschool_data->id . '">' . $eschool_data->full_name . 
                                    ' (' . $eschool_data->short_name . ') owner: ' . $institution->getShortName() . '</option>';
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
        <td>
            <b>Institutions:</b>
            <select id="institution" name="institution">
                <?php 
                    foreach($institutionList as $institution_data)
                    {
                        print '<option value="' . $institution_data->id . '">' . 
                                $institution_data->full_name . ' (' . $institution_data->short_name . ')</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
    </tbody>
  </table>
</form>