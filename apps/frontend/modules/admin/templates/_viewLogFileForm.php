<form action="<?php echo ('viewLogFile')?>" method="POST">
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Submit" />
        </td>
      </tr>
    </tfoot>
    <tbody>
    	<tr>
            <td>
                <b>Log Files:</b>
                <select id="log_file" name="log_file">
                    <?php
                    foreach($logFileList as $key => $value)
                    {
                        print '<option value="' . $key . '">' . $value .'</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <b>Display # of Last Lines (default = 100)</b>
                <input id="num_lines" name="num_lines" />
            </td>
        </tr>
    </tbody>
  </table>
</form>