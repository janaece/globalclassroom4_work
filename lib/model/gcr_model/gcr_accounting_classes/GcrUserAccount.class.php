<?php
class GcrUserAccount extends GcrEschoolAccount 
{
    protected $user;
    protected $is_eclassroom_user;

    public function __construct($user, $start_ts = 0, $end_ts = false)
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
        $this->user = $user;
        $this->is_eclassroom_user = $this->user->getRoleManager()->hasRole('EclassroomUser');
        $this->institution = $user->getApp();
        $this->is_internal = $this->institution->getIsInternal();
        $purchases = $this->user->getPurchases($this->start_ts, $this->end_ts, true);
        $sales = $this->user->getEclassroomCourseSales($this->start_ts, $this->end_ts);
        if ($sales)
        {
            if ($purchases)
            {
                $purchases = $purchases->merge($sales);
            }
            else
            {
                $purchases = $sales;
            }
        }
        parent::buildTransactionHistory($purchases);
        $this->setTransactionItemBalances();
    }
    public function getItemEarnings(GcrTransactionItem $item)
    {
        $earnings = 0;
        $purchase = $item->getPurchase();
        if ($purchase->isPayoff())
        {
            $earnings = $item->getAmount();
        }
        else
        {
            $seller = $purchase->getSellerUser();
            if ($seller && $seller->isSameUser($this->user))
            {
                $earnings = $item->getDistribution()->getSeller();
            }
        }
        return $earnings;
    }
    public function getSellerEarnings(GcrTransactionItem $item)
    {
        return $this->getItemEarnings($item);
    }
    public function getUser()
    {
        return $this->user;
    }
    public function isEclassroomUser()
    {
        return $this->is_eclassroom_user;
    }
    public function setTransactionItemBalances()
    {
        $items = $this->transaction_history->getItems();
        $ts = key($items);
        $month = date('m', $ts);
        $year = date('Y', $ts);
        $owner_balance = 0;
        $data_record = $this->user->getAccountManager()->getPreviousMonthlyDataRecord($month, $year);
        if (!$data_record)
        {
            $eclassroom_balance = 0;
        }
        else
        {
            $eclassroom_balance = $data_record->getUserBalance();
        }
        foreach ($items as $key => $item)
        {
            $eclassroom_balance += $this->getItemEarnings($item);
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
    public function buildMonthlyDataRecord($month, $year, $verbose = false)
    {
        if ($verbose)
        {
            print $this->user->getApp()->getShortName() . ", " . $this->user->getObject()->username . ", $month/$year\n";
        }
        $account_manager = $this->user->getAccountManager();
        $last_month_record = $account_manager->getPreviousMonthlyDataRecord($month, $year);
        if ($last_month_record)
        {
            $user_balance = $last_month_record->getUserBalance();
        }
        else
        {
            $user_balance = 0;
        }
        $gross_total = 0;
        $gc_total = 0;
        $seller_total = 0;
        $owner_total = 0;
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
            }     
            $seller_total += $this->getItemEarnings($item);
        }

        $user_balance += $seller_total;

        if (!$monthly_data = $account_manager->getMonthlyDataRecord($month, $year))
        {
            $monthly_data = new GcrUserMonthlyData();
            $monthly_data->setYearValue($year);
            $monthly_data->setMonthValue($month);
            $monthly_data->setUserId($this->user->getObject()->id);
            $monthly_data->setUserInstitutionId($this->institution->getShortName());
        }

        $monthly_data->setUserBalance($user_balance);
        $monthly_data->setGross($gross_total);
        $monthly_data->setGcFee($gc_total);
        $monthly_data->setOwnerFee($gross_total - ($seller_total + $gc_total));
        $monthly_data->save();
    }
}