<table class="content">
    <tr>
        <td>
            <div id="newEschoolInfo">
                <h2>Thank you for completing the verification process!</h2>
                <p>
                    Your free trial Stratus Platform is ready to be created. Please fill out this form to provide
                    us with the neccessary details to create your Platform.
                </p>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <?php include_partial('newInstitutionForm', array('newInstitutionForm' => $institution_form, 'formErrors' => $formErrors,)) ?>
        </td>
    </tr>
</table>