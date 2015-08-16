<script type="text/javascript" src="/js/lib.js"></script>
<div class="gc_small_form" style="width:50%"> 
    <table class="content" cellpadding="10" >
        <tr>
            <td colspan="2">
                <div>
                    <h1>Process Manual Transaction</h1>
                    Please select user, the item purchased, the total amount paid, and the check number or PayPal transaction number.
                </div>
                <br />
            </td>
        </tr>
        <tr>
            <td style="float:right">
                <?php include_partial('saleManualForm', array('saleManualForm' => $sale_form,
                                                                'return_url' => $return_url)); ?>
            </td>
            <td> &nbsp</td>
        </tr>
    </table>
</div>