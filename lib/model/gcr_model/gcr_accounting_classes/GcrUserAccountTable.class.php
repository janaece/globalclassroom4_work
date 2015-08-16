<?php
/**
 * Description of GcrUserAccountTable
 *
 * @author Ron Stewart
 */
class GcrUserAccountTable extends GcrEschoolAccountTable
{
    protected $user;
    protected $owner;

    public function __construct($user, $start_ts, $end_ts, $admin = false, $owner = false)
    {
        global $CFG;
        $this->app = $CFG->current_app;
        $this->user = $user;
        $this->institution = $user->getApp();
        $this->is_internal = $this->institution->getIsInternal();
        if (!$end_ts)
        {
            $end_ts = time();
        }
        $this->start_ts = $start_ts;
        $this->end_ts = $end_ts;
        $this->admin = $admin;
        $this->owner = $owner;
        $this->account = new GcrUserAccount($this->user, $this->start_ts, $this->end_ts);
        $this->has_eclassroom = $this->user->getRoleManager()->hasRole('EclassroomUser');
        $this->table = new GcrTable(array(), array('id' => 'gc_account',
            'class' => 'tablesorter', 'cellspacing' => '1'), false, true);
        $this->column_functions = array();
        $this->current_item_amounts = array();
        $this->totals = array('total_gross' => 0, 'total_earnings' => 0,
            'total_seller' => 0, 'total_gc' => 0, 'record_count' => 0, 'total_fees' => 0);
        $this->buildTable();
    }
    protected function setColumns()
    {
        parent::setColumns();
        $this->table->getColumns(5)->setHidden(!($this->admin || $this->owner || $this->has_eclassroom));
        $this->table->getColumns(6)->setHidden(!($this->admin || $this->owner));
        $this->table->getColumns(7)->setHidden(!($this->admin || $this->owner));
        $this->table->getColumns(8)->setHidden(!($this->admin || $this->owner || $this->has_eclassroom));
        $this->table->getColumns(9)->setHidden(!($this->admin || $this->owner || $this->has_eclassroom));
    }
    protected function setTotals(GcrTransactionItem $item)
    {
        $distribution = $item->getDistribution();
        $this->current_item_amounts['earnings'] = $this->account->getItemEarnings($item);
        $this->current_item_amounts['total'] = $distribution->getTotal();
        $this->current_item_amounts['seller'] =  $this->account->getSellerEarnings($item); 
        $this->current_item_amounts['fees'] = $this->current_item_amounts['total'] - $this->current_item_amounts['seller'];
        $purchase = $item->getPurchase();
        if (!$purchase->isPayoff())
        {
            $this->totals['total_gross'] += $this->current_item_amounts['total'];
        }
        $this->totals['total_fees'] += $this->current_item_amounts['fees'];
        $this->totals['total_earnings'] += $this->current_item_amounts['earnings'];
        $this->totals['total_seller'] += $this->current_item_amounts['seller'];
        $this->totals['record_count']++;
    }
    protected function getBalanceCell($ts, $item)
    {
        $content = GcrPurchaseTable::gc_format_money($item->getEclassroomBalance());
        return new GcrTableCell(array(), $content);
    }
}
?>
