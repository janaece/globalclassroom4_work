<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo ('deleteTrial')?>" method="POST">
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" class="button" value="Submit" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     <?php echo $form; ?>
    </tbody>
  </table>
</form>
