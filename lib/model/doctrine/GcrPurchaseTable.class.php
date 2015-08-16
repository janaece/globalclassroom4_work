<?php

class GcrPurchaseTable extends Doctrine_Table
{
    public static function getNextBillingDate($last_payment_ts, $bill_cycle)
    {
        $payment_day = date('j', $last_payment_ts);
        $payment_month = date('n', $last_payment_ts);
        $payment_year = date('Y', $last_payment_ts);

        if ($bill_cycle == 'Month')
        {
            if ($payment_month == 12)
            {
                $payment_year++;
                $payment_month = 0;
            }
                return mktime(0, 0, 0, ++$payment_month, $payment_day, $payment_year);
        }
        else if ($bill_cycle == 'Year')
        {
            return mktime(0, 0, 0, $payment_month, $payment_day, ++$payment_year);
        }
        else
        {
            global $CFG;
            $CFG->current_app->gcError('Bill cycle invalid, ' . $bill_cycle, 'gcdatabaseerror');
        }
    }
    public static function gc_format_money($amount)
    {
        if ($amount >= 0)
        {
            return '$' . number_format($amount, 2, '.', ',');
        }
        else
        {
            return '($' . number_format($amount * -1, 2, '.', ',') . ')';
        }
    }
    // This function retrieves all purchases where this app is set in the purchase_type_eschool_id
    //
    // The parameter $type is a Purchase->purchase_type filter, while the $start_ts and $end_ts set
    // a time period to get records from.
    //
    // Parameter $include_all_recurring is a flag to include recurring
    // purchases which fall outside the time period. This is useful because in many cases we
    // want to get all transactions during a time period, but the original purchase records for
    // some of the transactions were created before the time period start date.
    public static function getAppPurchases($app, $type = false, $start_ts = 0, $end_ts = null,
            $include_all_recurring = false, $include_manual = true)
    {
        if (!$end_ts)
        {
            $end_ts = time();
        }
        $where_string = '(p.trans_time >= ? AND p.trans_time <= ?)';
        $where_array = array($start_ts, $end_ts);
        if ($include_all_recurring)
        {
            $where_string .= ' OR p.bill_cycle != ?';
            $where_array[] = 'single';
        }

        $short_name = $app->getShortName();
        $where_app_string = 'p.purchase_type_eschool_id = ? OR p.user_institution_id = ?';
        $where_app_array = array($short_name, $short_name);
        if ($type)
        {

            $purchases = Doctrine::getTable('GcrPurchase')->createQuery('p')
                ->where('p.purchase_type LIKE ?', $type . '%')
                ->andWhere($where_app_string, $where_app_array)
                ->andWhere('p.profile_id != ?', GcrPaypalTable::TXN_PENDING)
                ->andWhere($where_string, $where_array)
                ->execute();
        }
        else
        {
            $purchases = Doctrine::getTable('GcrPurchase')->createQuery('p')
                ->where($where_app_string, $where_app_array)
                ->andWhere($where_string, $where_array)
                ->andWhere('p.profile_id != ?', GcrPaypalTable::TXN_PENDING)
                ->execute();
        }
        return (count($purchases) > 0) ? $purchases : false;
    }
    public static function getCommissionFee($institution, $eschool)
    {
        $commission_fee = 0;
        $eschool_institution = $eschool->getInstitution();
        if ($institution->getId() != $eschool_institution->getid())
        {
            $commission = GcrCommissionTable::getCommission($institution, $eschool);
            if ($commission)
            {
                $commission_rate = $commission->getCommissionRate();
                if ($commission_rate > 0 && $commission_rate <= 100)
                {
                    $commission_fee = $commission_rate;
                }
            }
        }
        return $commission_fee;
    }
    public static function clearPendingTransactions($expiry = 0)
    {
        Doctrine_Query::create()
                ->delete('GcrPurchase p')
                ->where('p.profile_id = ?', GcrPaypalTable::TXN_PENDING)
                ->andWhere('p.trans_time < ?', (time() - $expiry))
                ->execute();
    }
    public static function convertDatetoTimestamp($date)
    {
        $ts = mktime(0, 0, 0, $date['month'], $date['day'], $date['year']);
        if (!$ts)
        {
            return null;
        }
        return $ts;
    }
    public static function deleteInstitutionRecords($institution)
    {
        $purchases = Doctrine::getTable('GcrPurchase')->findByUserInstitutionId($institution->getShortName());
        foreach ($purchases as $purchase)
        {
            $app = $purchase->getPurchaseTypeApp();
            if ($app)
            {
                $app_institution = $app->getInstitution();
                if ($app_institution->getId() == $institution->getId())
                {
                    $purchase->delete();
                }
                else
                {
                    // Assign the purchaser user as the gc admin from the purchase owning institution
                    $gcadminuser = $app_institution->getGCAdminUser();
                    $purchase->setUserInstitutionId($app_institution->getShortName());
                    $purchase->setUserId($gcadminuser->getObject()->id);
                    $purchase->save();
                }
            }
            else
            {
                $purchase->delete();
            }
        }
    }
}
