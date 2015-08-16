<form action="<?php echo ('turnOffEmails')?>" method="POST">
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Submit" onclick="return confirmPost('Are you sure you want to DISABLE EMAILS?')" />
        </td>
      </tr>
    </tfoot>
    <tbody>
    	<tr>
            <td>
                <b>Mahara:</b>
                <select id="institution" name="institution">
                    <?php
                    foreach($institutionList as $institution)
                    {
                        print '<option value="' . $institution->getId() . '">' . $institution->getFullName() . ' (' . $institution->getShortName() . ')</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
    </tbody>
  </table>
</form>