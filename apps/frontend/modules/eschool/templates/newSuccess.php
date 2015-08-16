<table class="content">
    <tr>
        <td>
            <div id="newEschoolInfo">
                <h1>Create A New Catalog</h1>
                Please complete the following form to create an additional Platform Catalog.<br/><br/>
                <span class="cautiontext">
                    Auto-fill may cause some fields within the form to be completed already.<br/>
                    Please make sure all information is correct before submitting.
                </span>				
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <?php include_partial('form', array('eschoolForm' => $eschoolForm, 'formErrors' => $formErrors,)) ?>
        </td>
    </tr>
</table>