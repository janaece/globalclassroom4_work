<form action="<?php echo ('executeSqlStatement')?>" method="POST">
  <table>
  	
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Submit" onclick="return confirmSQL()" />
        </td>
      </tr>
    </tfoot>
    <tbody>
    	<tr>
            <td>
                <b>SQL Statement Beginning:</b> 
                <input id="sqlStatementStart" name="sqlStatementStart" />
                </td>
            </tr>
     	<tr>
            <td>
                <b>Schema(s) Selector:</b>
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
        <tr>
            <td>
                <b>SQL Statement Ending:</b> 
                <input id="sqlStatementEnd" name="sqlStatementEnd" style="width:750px;" />
            </td>
        </tr>
    </tbody>
  </table>
</form>