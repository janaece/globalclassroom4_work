<form action="<?php echo ('updateMhrConfig')?>" method="POST">
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
                <b>Mahara(s):</b>
                <select id="institution" name="institution">
                    <option value="1">All Maharas</option>
                        <?php
                        foreach($institutionList as $institution)
                        {
                            print '<option value="' . $institution->getShortName() . '">' .
                                    $institution->getFullName() . ' (' . $institution->getShortName() . ')</option>';
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
                        print '<option value="' . $var->field . '">' . $var->field . '</option>';
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