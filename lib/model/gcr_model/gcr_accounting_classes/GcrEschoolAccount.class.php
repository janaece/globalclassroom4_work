<?php
class GcrEschoolAccount
{
    protected $institution;
    protected $transaction_history;
    protected $start_ts;
    protected $end_ts;
    protected $has_eclassroom;
    protected $is_internal;

    public function __construct($institution, $start_ts = 0, $end_ts = false)
    {
        if (!$this->end_ts = $end_ts)
        {
            $this->end_ts = time();
        }
        if ($start_ts < gcr::startDateForApplication)
        {
            $this->start_ts = gcr::startDateForApplication;
        }
        else
        {
            $this->start_ts = $start_ts;
        }
        $this->institution = $institution;
        $this->is_internal = $institution->getIsInternal();
        $start_of_month_ts = 0;
        if ($start_ts > 0)
        {
            $start_of_month_ts = mktime(5, 0, 0, date('m', $start_ts), 1, date('Y', $start_ts));
        }
        $purchases = $this->institution->getPurchases(false, $start_of_month_ts, $this->end_ts, true);
        $this->buildTransactionHistory($purchases, $start_of_month_ts);
        $this->setTransactionItemBalances();
    }
    public function buildTransactionHistory($purchases, $start_ts = false)
    {
        if (!$start_ts)
        {
            $start_ts = $this->start_ts;
        }
        $this->has_eclassroom = false;
        $this->total_gc = 0;
        $this->total_eclassroom = 0;
        $this->gross_income = 0;
        $this->net_income = 0;
        $this->owner_expenses = 0;
        $this->transaction_history = new GcrTransactionHistory($this->institution);

        if (!empty($purchases))
        {
            foreach ($purchases as $purchase)
            {
                $txns = '';
                if ($purchase->isRecurring())
                {
                    $txns = $purchase->getPaypalTransactions('Completed', $start_ts, $this->end_ts);
                    $refunds = $purchase->getPaypalTransactions('Refunded', $start_ts, $this->end_ts);
                }
                else
                {
                    if ($purchase->isEclassroomCourse())
                    {
                        $this->has_eclassroom = true;
                    }
                    $refunds = GcrPaypalTable::getRefunds($purchase->getProfileId(), $start_ts, $this->end_ts);
                }
                $this->transaction_history->add(new GcrTransaction($purchase, $txns, $refunds));
            }
        }
        $this->transaction_history->buildItems();
    }
    public function setTransactionItemBalances()
    {
        $items = $this->transaction_history->getItems();
        $ts = key($items);
        $month = date('m', $ts);
        $year = date('Y', $ts);
        $data_record = $this->institution->getAccountManager()->getPreviousMonthlyDataRecord($month, $year);
        if ($data_record)
        {
            $owner_balance = $data_record->getEschoolBalance();
            $eclassroom_balance = $data_record->getEclassroomBalance();
        }
        else
        {
            $owner_balance = 0;
            $eclassroom_balance = 0;
        }
        foreach ($items as $key => $item)
        {
            $owner_balance += $this->getItemEarnings($item);
            $eclassroom_balance += $item->getDistribution()->getSeller();
            if ($key >= $this->start_ts)
            {
                $this->transaction_history->setBalances($key, $owner_balance, $eclassroom_balance);
            }
            else
            {
                $this->transaction_history->remove($key);
            }
        }
    }
    public function getStartTs()
    {
        return $this->start_ts;
    }
    public function getEndTs()
    {
        return $this->end_ts;
    }
    public function setStartTs($start_ts)
    {
        $this->start_ts = $start_ts;
    }
    public function setEndTs($end_ts)
    {
        $this->end_ts = $end_ts;
    }
    public function hasEclassroom()
    {
        return $this->has_eclassroom;
    }
    public function getItems()
    {
        return $this->transaction_history->getItems();
    }
    public function isInternal()
    {
        return $this->is_internal;
    }
    public function getInstitution()
    {
        return $this->institution;
    }
    public function isRemoteItem(GcrTransactionItem $item)
    {
        $purchase = $item->getPurchase();
        if ($purchase->isRemote())
        {
            $institution = $purchase->getPurchaserInstitution();
            if ($institution)
            {
                return ($institution->getId() == $this->institution->getId());
            }
        }
        return false;
    }
    public function getItemEarnings(GcrTransactionItem $item)
    {
        $earnings = 0;
        $purchase = $item->getPurchase();
        if ($purchase->isPayoff())
        {
            $payoff = GcrPayoffTable::getInstance()->find($purchase->getPurchaseTypeId());
            if ($payoff->isEschoolPayoff())
            {
                $earnings = $item->getAmount();
            }
        }
        else
        {
            if ($this->isRemoteItem($item))
            {
                $earnings = $item->getDistribution()->getCommission();
            }
            else
            {
                $earnings = $item->getDistribution()->getOwner();
            }
        }
        return $earnings;
    }
    public function getSellerEarnings(GcrTransactionItem $item)
    {
        $earnings = 0;
        if (!$this->isRemoteItem($item))
        {
            $earnings = $item->getDistribution()->getSeller();
        }
        return $earnings;
    }
    public function getStartOfNextMonthTimestamp($ts)
    {
        $next_month = date('m', $ts) + 1;
        $year = date('Y', $ts);
        if ($next_month == 13)
        {
            $next_month = 1;
            $year++;
        }
        $end_of_month = mktime(0, 0, 0, $next_month, 1, $year);
        return $end_of_month;
    }
    public function buildMonthlyDataRecord($month, $year, $verbose = false)
    {
        if ($verbose)
        {
            print $this->institution->getShortName() . ", $month/$year\n";
        }
        $account_manager = $this->institution->getAccountManager();
        $last_month_record = $account_manager->getPreviousMonthlyDataRecord($month, $year);
        if ($last_month_record)
        {
            $eschool_balance = $last_month_record->getEschoolBalance();
            $eclassroom_balance = $last_month_record->getEclassroomBalance();
        }
        else
        {
            $eschool_balance = 0;
            $eclassroom_balance = 0;
        }
        $gross_total = 0;
        $gc_total = 0;
        $seller_total = 0;
        $start_of_month = mktime(0, 0, 0, $month, 1, $year);
        $end_of_month = $this->getStartOfNextMonthTimestamp($start_of_month);

        foreach ($this->getItems() as $ts => $item)
        {
            if ($ts < $start_of_month)
            {
                continue;
            }
            else if ($ts >= $end_of_month)
            {
                break;
            }
            $distribution = $item->getDistribution();
            if (!$item->getPurchase()->isPayoff())
            {
                $gross_total += $distribution->getTotal();
                $gc_total += $distribution->getGC();
                $seller_total += $distribution->getSeller();
            }
            $eclassroom_balance += $distribution->getSeller();
            $eschool_balance += $this->getItemEarnings($item);
        }

        if (!$monthly_data = $account_manager->getMonthlyDataRecord($month, $year))
        {
            $monthly_data = new GcrEschoolMonthlyData();
            $monthly_data->setYearValue($year);
            $monthly_data->setMonthValue($month);
            $monthly_data->setEschoolId($this->institution->getShortName());
        }

        $monthly_data->setEschoolBalance($eschool_balance);
        $monthly_data->setEclassroomBalance($eclassroom_balance);
        $monthly_data->setGross($gross_total);
        $monthly_data->setGcFee($gc_total);

        $monthly_data->setSellerFee($seller_total);
        $monthly_data->setNumUsers(0); // not implemented yet
        $monthly_data->setNumCourses(0); // not implemented yet
        $monthly_data->save();
    }
    public function updateAccounting($verbose = false)
    {
        $month = date('m', $this->start_ts);
        $year = date('Y', $this->start_ts);
        $this_month = date('m', time());
        $this_year = date('Y', time());
        while (($month <= $this_month || $year < $this_year) && $year <= $this_year)
        {
            $this->buildMonthlyDataRecord($month++, $year, $verbose);
            if ($month == 13)
            {
                $month = 1;
                $year++;
            }
        }
    }
}