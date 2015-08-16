<?php
global $CFG;
?>
<div class="gc_small_form" style="width:50%"> 
    <table class="content" cellpadding="10" >
        <tr>
            <td colspan="2">
                <div>
                    <h1>Set Course Seller</h1>
                    Set an eClassroom user as the seller of an existing course purchase record.
                </div>
                <br />
            </td>
        </tr>
        <tr>
            <td style="float:right">
                <form id="setCourseSeller" name="setCourseSeller" action="<?php print $CFG->current_app->getUrl(); ?>/homeadmin/doSetCourseSeller" method="POST">
                    <input id="purchase_id" name="purchase_id" type="hidden" value="<?php print $purchase->getId(); ?>" />
                    <select id="course_seller_user_id" name="course_seller_user_id">
                        <option value="0">Platform Course (not an eClassroom course)</option>
                        <?php
                        $current_seller_id = $purchase->getSellerId();
                        foreach($eclassroom_users as $user)
                        {
                            $user_obj = $user->getObject();
                            print '<option value="' . $user_obj->id . '"';
                            if ($current_seller_id == $user_obj->id)
                            {
                                print ' selected=selected';
                            }     
                            print '>' . $user->getFullnameString() . ' (' . $user_obj->email . ')</option>'; 
                        } ?>
                    </select><br /><br />
                    <input type="submit" class="button" value="Save" />
                    <input type="button" class="button" value="Exit" onclick="window.location.href='<?php print $CFG->current_app->getUrl() . '/account/view?eschool=' . $institution->getShortName(); ?>'" />
                </form>    
            
            </td>
            <td> &nbsp</td>
        </tr>
    </table>
</div>
