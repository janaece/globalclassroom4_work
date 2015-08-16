<script type="text/javascript" src="/js/lib.js"></script>
<div class="gc_small_form" style="width:50%"> 
    <table class="content" cellpadding="10" >
        <tr>
            <td colspan="2">
                <div>
                    <h1>Process Course Enrollment Fees Paid by Check</h1>
                    Please select user, course, the total amount written on the check, the check number, and number of purchased enrollments
                </div>
                <br />
            </td>
        </tr>
        <tr>
            <td style="float:right">
                <?php include_partial('courseManualForm', array('courseManualForm' => $course_form,
                                                                'return_url' => $return_url)); ?>
            </td>
            <td> &nbsp</td>
        </tr>
    </table>
</div>