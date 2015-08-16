<?php
/**
 * Description of GcrEschoolAccountTable
 *
 * @author Ron Stewart
 */
class GcrEschoolAccountTable
{
    protected $table;
    protected $institution;
    protected $app;
    protected $account;
    protected $start_ts;
    protected $end_ts;
    protected $admin;
    protected $is_internal;
    protected $has_eclassroom;
    protected $column_functions;
    protected $current_item_amounts;
    protected $totals;
    protected $owner;

    public function __construct($institution, $start_ts, $end_ts, $admin)
    {
        global $CFG;
        $this->app = $CFG->current_app;
        $this->institution = $institution;
        $this->is_internal = $institution->getIsInternal();
        if (!$end_ts)
        {
            $end_ts = time();
        }
        $this->start_ts = $start_ts;
        $this->end_ts = $end_ts;
        $this->admin = $admin;
        $this->owner = $this->app->hasPrivilege('EschoolAdmin');
        $this->account = new GcrEschoolAccount($this->institution, $this->start_ts, $this->end_ts);
        $this->has_eclassroom = $this->account->hasEclassroom();
        $this->table = new GcrTable(array(), array('id' => 'gc_account',
            'class' => 'tablesorter', 'cellspacing' => '1'), false, true);
        $this->column_functions = array();
        $this->current_item_amounts = array();
        $this->totals = array('total_gross' => 0, 'total_earnings' => 0,
            'total_seller' => 0, 'record_count' => 0, 'total_fees' => 0);
        $this->buildTable();
    }
    protected function buildTable()
    {
        $this->setColumns();
        foreach ($this->account->getItems() as $ts => $item)
        {
            $this->setTotals($item);
            $columns = $this->table->getColumns();
            for ($i = 0; $i < $this->table->getColumnCount(); $i++)
            {
                $function = $this->column_functions[$i];
                $columns[$i]->addCell($this->$function($ts, $item));
            }
        }
        foreach($columns as $column)
        {
            if (!$column->hasContent())
            {
                $column->setHidden(true);
            }
        }
    }
    protected function setColumns()
    {
        $transaction_date = new GcrTableColumn();
        $transaction_date_header = new GcrTableCell(array(), 'Date', true);
        $transaction_date->addCell($transaction_date_header);
        $this->column_functions[] = 'getTransactionDateCell';
        $this->table->addColumn($transaction_date);

        $purchase_item = new GcrTableColumn();
        $purchase_item_header = new GcrTableCell(array(), 'Item', true);
        $purchase_item->addCell($purchase_item_header);
        $this->column_functions[] = 'getPurchaseItemCell';
        $this->table->addColumn($purchase_item);

        $purchase_type = new GcrTableColumn();
        $purchase_type_header = new GcrTableCell(array(), 'Type', true);
        $purchase_type->addCell($purchase_type_header);
        $this->column_functions[] = 'getPurchaseTypeCell';
        $this->table->addColumn($purchase_type);

        $purchaser = new GcrTableColumn();
        $purchaser_header = new GcrTableCell(array(), 'User', true);
        $purchaser->addCell($purchaser_header);
        $this->column_functions[] = 'getPurchaserCell';
        $this->table->addColumn($purchaser);

        $amount = new GcrTableColumn();
        $amount_header = new GcrTableCell(array(), 'Amount', true);
        $amount->addCell($amount_header);
        $this->column_functions[] = 'getAmountCell';
        $this->table->addColumn($amount);

        $fees_paid = new GcrTableColumn();
        $fees_paid_header = new GcrTableCell(array(), 'Fees', true);
        $fees_paid->addCell($fees_paid_header);
        $this->column_functions[] = 'getFeesPaidCell';
        $this->table->addColumn($fees_paid);

        $eclassroom = new GcrTableColumn();
        $eclassroom_header = new GcrTableCell(array(), 'Seller', true);
        $eclassroom->addCell($eclassroom_header);
        $this->column_functions[] = 'getEclassroomCell';
        $this->table->addColumn($eclassroom);

        $seller_earns = new GcrTableColumn(array(), (!$this->has_eclassroom));
        $seller_earns_header = new GcrTableCell(array(), 'Seller Earns', true);
        $seller_earns->addCell($seller_earns_header);
        $this->column_functions[] = 'getSellerEarnsCell';
        $this->table->addColumn($seller_earns);

        $earnings = new GcrTableColumn();
        $earnings_header = new GcrTableCell(array(), 'Earnings', true);
        $earnings->addCell($earnings_header);
        $this->column_functions[] = 'getEarningsCell';
        $this->table->addColumn($earnings);

        $balance = new GcrTableColumn();
        $balance_header = new GcrTableCell(array(), 'Balance', true);
        $balance->addCell($balance_header);
        $this->column_functions[] = 'getBalanceCell';
        $this->table->addColumn($balance);
    }
    protected function setTotals(GcrTransactionItem $item)
    {
        $distribution = $item->getDistribution();
        $this->current_item_amounts['earnings'] = $this->account->getItemEarnings($item);
        $this->current_item_amounts['total'] = $distribution->getTotal();
        $this->current_item_amounts['seller'] =  $this->account->getSellerEarnings($item); 
        $this->current_item_amounts['fees'] = $this->current_item_amounts['total'] - 
                ($this->current_item_amounts['earnings'] + $this->current_item_amounts['seller']);
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
    public function getTotal($key)
    {
        return $this->totals[$key];
    }      
    protected function getTransactionDateCell($ts, $item)
    {
        $content = '<span style="display:none">' . $ts . '</span>' . date('M j, Y', $ts);
        return new GcrTableCell(array('class' => 'transactionDate'), $content);
    }
    protected function getPurchaseItemCell($ts, $item)
    {
        $full_description = '';
        $purchase = $item->getPurchase();
        if ($content = $item->getPurchase()->getDescription())
        {
            if (strlen($content) > 54)
            {
                $full_description = $content;
                $content = substr($content, 0, 50) . '...';
            }
            if ($purchase->purchaseTypeExists())
            {
                $content = '<a href="' . $purchase->getHyperlinkToPurchaseType() .
                        '" target="_blank">' . $content . '</a>';
            }
        }
        else
        {
            $description = $purchase->getPurchaseDescription();
        }
        return new GcrTableCell(array('title' => $full_description), $content);
    }
    protected function getPurchaseTypeCell($ts, $item)
    {
        $purchase = $item->getPurchase();
        $refund_str = '';
        if ($item->isRefund())
        {
            $refund_str = 'Refund ';
        }
        $content = $refund_str . $purchase->getPurchaseString();
        if ($this->admin)
        {
            switch ($purchase->getPurchaseType())
            {
                case 'course_manual':
                    $content = '<span class="course_manual"><a href="' . $this->app->getUrl() . '/homeadmin/manualCourse?eschool=' .
                            $this->institution->getShortName() . '&purchase=' . $purchase->getId() . '">' . $content . '</a></span>';
                    break;
                case 'classroom_manual':
                    $content = '<span class="classroom_manual"><a href="' . $this->app->getUrl() . '/homeadmin/manualClassroom?eschool=' .
                            $this->institution->getShortName() . '&purchase=' . $purchase->getId() . '">' . $content . '</a></span>';
                    break;
                case 'eschool_manual':
                    $content = '<span class="eschool_manual"><a href="' . $this->app->getUrl() . '/homeadmin/manualEschool?eschool=' .
                            $this->institution->getShortName() . '&purchase=' . $purchase->getId() . '">' . $content . '</a></span>';
                    break;
                case 'membership_manual':
                    $content = '<span class="eschool_manual"><a href="' . $this->app->getUrl() . '/homeadmin/manualMembership?eschool=' .
                            $this->institution->getShortName() . '&purchase=' . $purchase->getId() . '">' . $content . '</a></span>';
                    break;
                case 'sale_manual':
                    $content = '<span class="eschool_manual"><a href="' . $this->app->getUrl() . '/homeadmin/manualSale?eschool=' .
                            $this->institution->getShortName() . '&purchase=' . $purchase->getId() . '">' . $content . '</a></span>';
                    break;
                case 'payoff':
                    $payoff = Doctrine::getTable('GcrPayoff')->findOneByPurchaseId($purchase->getId());
                    if ($payoff->isManual())
                    {
                        $content = '<span class="payoff_manual"><a href="' . $this->app->getUrl() . '/account/editManualPayoff?id=' .
                                $payoff->getId() . '">Manual ' . $content . '</a></span>';
                    }
                    break;
            }
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getPurchaserCell($ts, $item)
    {
        $content = '';
        $user_tooltip = '';
        $purchaser = $item->getPurchase()->getPurchaserUser();
        if ($purchaser && (!$purchaser->getRoleManager()->hasPrivilege('GCAdmin')))
        {
            $mhr_user_obj = $purchaser->getObject();
            $user_tooltip = $mhr_user_obj->username . ', email: ' . $mhr_user_obj->email;
            $content = $purchaser->getFullnameString();
            if ($this->owner)
            {
                $content = '<a href="' . $this->app->getUrl() .
                    '/account/view?eschool=' . $purchaser->getApp()->getShortName() . '&user=' .
                    $mhr_user_obj->id . '" target="_blank">' . $content . '</a>';
            }
        }
        return new GcrTableCell(array('title' => $user_tooltip), $content);
    }
    protected function getAmountCell($ts, $item)
    {
        $content = GcrPurchaseTable::gc_format_money($this->current_item_amounts['total']);
        return new GcrTableCell(array(), $content);
    }
    protected function getFeesPaidCell($ts, $item)
    {
        $content = GcrPurchaseTable::gc_format_money($this->current_item_amounts['fees']);
        $purchase = $item->getPurchase();
        if ($this->admin && $purchase->isCourse() && (!$item->isRefund()))
        {
            $content .= '<span class="editButtonImage editFeesButton" value="' . $purchase->getId() .
                    '" gc_fee="' . $purchase->getGCFee() . '" owner_fee="' . $purchase->getOwnerFee() .
                    '" commission_fee="' . $purchase->getCommissionFee() .
                    '"><img src="' . $this->app->getUrl() . 
                    '/images/icons/editinformation.png" alt="Edit" /></span>';
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getEclassroomCell($ts, $item)
    {
        $content = '';
        $user_tooltip = '';
        if ($item->isEclassroomCourse() && (!$this->account->isRemoteItem($item)))
        {
            $seller = $item->getSeller();
            if ($seller)
            {
                $mhr_user_obj = $seller->getObject();
                $user_tooltip = $mhr_user_obj->username . ', email: ' . $mhr_user_obj->email;
                $content = $seller->getFullnameString();
                if ($this->owner)
                {
                    $content = '<a href="' . $this->app->getUrl() .
                        '/account/view?eschool=' . $seller->getApp()->getShortName() .
                        '&user=' . $mhr_user_obj->id . '" target="_blank">' .
                        $content . '</a>';
                }
            }
            else
            {
                $content = 'Miscellaneous';
            }
        }
        $purchase = $item->getPurchase();
        if ($this->admin && $purchase->isCourse())
        {
            $content .= '<a class="editButtonImage" href="' . $this->app->getUrl() . 
                    '/homeadmin/setCourseSeller?id=' . $purchase->getid() . '"><img src="' . $this->app->getUrl() . 
                    '/images/icons/editinformation.png" alt="Edit" /></a>';
        }
        return new GcrTableCell(array('title' => $user_tooltip), $content);
    }
    protected function getSellerEarnsCell($ts, $item)
    {
        $content = '';
        if ($item->isEclassroomCourse() && (!$this->account->isRemoteItem($item)))
        {
            $content = GcrPurchaseTable::gc_format_money($this->current_item_amounts['seller']);
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getEarningsCell($ts, $item)
    {
        $content = GcrPurchaseTable::gc_format_money($this->current_item_amounts['earnings']);
        return new GcrTableCell(array(), $content);
    }
    protected function getBalanceCell($ts, $item)
    {
        $content = GcrPurchaseTable::gc_format_money($item->getOwnerBalance());
        return new GcrTableCell(array(), $content);
    }
    public function getHTML()
    {
        return $this->table->getHTML();
    }
    public function printTable()
    {
        print $this->getHTML();
    }
    public function hasEclassroom()
    {
        return $this->has_eclassroom;
    }
}
?>
