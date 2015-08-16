<form action="<?php echo ('syncLdap')?>" method="POST">
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Submit" onclick="return confirmPost('Are you sure you want to SYNC LDAP USERS?')" />
        </td>
      </tr>
    </tfoot>
    <tbody>
    	<tr>
            <td>
                <b>Institutions(s):</b>
                <select id="institution" name="institution">
                    <option value="1">All Maharas</option>
                    <?php
                    foreach($institutionList as $institution)
                    {
                        print '<option value="' . $institution->getShortName() . '">' . $institution->getFullName() . ' (' . $institution->getShortName() . ')</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
    </tbody>
  </table>
</form>