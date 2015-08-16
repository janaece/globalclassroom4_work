<table class="content">
    <tr>
        <td>
            <div id="newEschoolInfo">
                <h1>Create A New Stratus Platform</h1>
                Please complete the following form to receive your trial Stratus Platform.<br/><br/>
                <span class="cautiontext">
                    Please make sure all information is correct before submitting.
                </span>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <?php include_partial('institutionForm', array('institutionForm' => $institutionForm, 'formErrors' => $formErrors,)) ?>
        </td>
    </tr>
</table>

<script type="text/javascript">
    jQuery('#country').val('US').attr('selected', 'true');
</script>