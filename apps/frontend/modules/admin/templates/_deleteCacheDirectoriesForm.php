<form action="<?php echo ('deleteCacheDirectories')?>" method="POST">
  <table>
  	
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Submit" onclick="return confirmPost('Are you sure you want to DELETE CACHE DIRECTORIES?')" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     	<tr>
            <td>
                <b>Schema(s) to delete cache directories:</b>
                <select id="schema" name="schema">
                    <option value="2">All Maharas</option>
                    <?php 
                    foreach ($institutionList as $institution)
                    {
                        print '<option value="' . $institution->getShortName() . '">' . $institution->getFullName() . ' (' . $institution->getShortName() . ')</option>';
                    }
                    ?>
                    <option value="1">All Moodles</option>
                    <?php 
                    foreach ($eschoolList as $eschool)
                    {
                        print '<option value="' . $eschool->getShortName() . '">' . $eschool->getFullName() . ' (' . $eschool->getShortName() . ')</option>';
                    }

                    ?>
                </select>
            </td>
        </tr>
    </tbody>
  </table>
</form>