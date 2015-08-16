<?php

/**
 * Description of GcrAccountManager:
 * This class encapsulates all accounting code so that it may be
 * accessed per user where platform owners have an GcrEschoolAccount type
 * and eClassroom Users have a GcrUserAccount type.
 *
 * @author Ron Stewart
 */
class GcrAccountManager 
{
    protected $user;
    protected $account;
    protected $is_owner;
    protected $is_eclassroom_user;
    
    public function __construct($user)
    {
        $this->user = $user->getUserOnInstitution();
        $role_manager = $this->user->getRoleManager();
        $this->account = false;
        $this->is_owner = $role_manager->hasRole('Owner');
        $this->is_eclassroom_user = $role_manager->hasRole('EclassroomUser');
    }
    public function canRequestWithdrawal()
    {
        $max_withdrawal = $this->getMaxWithdrawalAmount();
        $allow_withdrawal = ($this->is_owner || $this->is_eclassroom_user);
        if ($this->is_owner && $this->user->getApp()->isInternal())
        {
            $allow_withdrawal = false;
        }
        else if ($max_withdrawal <= 0)
        {
            $allow_withdrawal = false;
        }
        else if ($this->getPendingWithdrawal())
        {
            $allow_withdrawal = false;
        }
        return $allow_withdrawal;
    }
    public function getAccount($start_ts = false, $end_ts = false)
    {
        if (!$this->account)
        {
            if (!$end_ts)
            {
                $end_ts = time();
            }
            if ($this->is_owner)
            {
                $this->account = new GcrEschoolAccount($this->user->getApp(), $start_ts, $end_ts);
            }
            else
            {
                $this->account = new GcrUserAccount($this->user, $start_ts, $end_ts);
            }
        }
        return $this->account;
    }
    public function getAccountBalance($month_year_array = false)
    {
        if (!$month_year_array)
        {
            $month = date('m', time());
            $year = date('Y', time());
        }
        else
        {
            $month = $month_year_array['month'];
            $year = $month_year_array['year'];
        }
        $data_record = $this->getMonthlyDataRecord($month, $year);
        if ($data_record)
        {
            if ($this->is_owner)
            {
                $balance = $data_record->getEschoolBalance();
            }
            else
            {
                $balance = $data_record->getUserBalance();
            }
            return $balance;
        }
        return false;
    }
    public function getAccountTable($start_ts, $end_ts, $gc_admin_view = false, $owner_view = false)
    {
        if ($this->is_owner)
        {
            $account_table = new GcrEschoolAccountTable($this->user->getApp(), $start_ts, $end_ts, $gc_admin_view);
        }
        else
        {
            $account_table = new GcrUserAccountTable($this->user, $start_ts,
                $end_ts, $gc_admin_view, $owner_view);
        }
        return $account_table;
    }
    public function getPayoffType()
    {
        if ($this->is_owner)
        {
            $type = 'eschool';
        }
        else
        {
            $type = 'classroom';
        }
        return $type;
    }
    public function getMaxWithdrawalAmount()
    {
        $withdrawal_max = -1;
        $balance = $this->getAccountBalance();
        $float = $this->getMinAccountBalance();
        if ($float && $balance)
        {
            $withdrawal_max = $balance - $float;
            if ($withdrawal_max <= 0)
            {
                $withdrawal_max = -1;
            }
        }
        return $withdrawal_max;
    }
    public function getMinAccountBalance()
    {
        if ($this->is_owner)
        {
            return $this->user->getApp()->getConfigVar('gc_eschool_min_balance');
        }
        else
        {
            return $this->user->getApp()->getConfigVar('gc_eclassroom_min_balance');
        }
    }
    public function getMonthlyDataRecord($month, $year)
    {
        if ($this->is_owner)
        {
            $record = Doctrine::getTable('GcrEschoolMonthlyData')->createQuery('e')
                ->where('e.year_value = ?', $year)
                ->andWhere('e.month_value = ?', $month)
                ->andWhere('e.eschool_id = ?', $this->user->getApp()->getShortName())
                ->fetchOne();
        }
        else
        {
            $record = Doctrine::getTable('GcrUserMonthlyData')->createQuery('u')
                    ->where('u.year_value = ?', $year)
                    ->andWhere('u.month_value = ?', $month)
                    ->andWhere('u.user_institution_id = ?', $this->user->getApp()->getShortName())
                    ->andWhere('u.user_id = ?', $this->user->getObject()->id)
                    ->fetchOne();
        }
        return $record;
    }
    public function getMostRecentCompletedPayoff()
    {
        $payoff = Doctrine::getTable('GcrPurchase')->createQuery('p')
                ->where('p.purchase_type_eschool_id = ?', $this->user->getApp()->getShortName())
                ->andWhere('p.user_institution_id = ?', $this->user->getApp()->getShortName())
                ->andWhere('p.user_id = ?', $this->user->getObject()->id)
                ->andWhere('p.purchase_type = ?', 'payoff')
                ->orderBy('p.trans_time DESC')
                ->fetchOne();
        return $payoff;
    }
    public function getPayoffCredentials()
    {
        $credentials = Doctrine::getTable('GcrPayoffCredentials')->createQuery('pc')
                ->where('pc.user_eschool_id = ?', $this->user->getApp()->getShortName())
                ->andWhere('pc.user_id = ?', $this->user->getObject()->id)
                ->andWhere('pc.verify_status = ?', 'verified')
                ->fetchOne();
        return $credentials;
    }
    public function getPendingWithdrawal()
    {
        $payoff = Doctrine::getTable('GcrPayoff')
                ->createQuery('p')
                ->where('p.eschool_id = ?', $this->user->getApp()->getShortName())
                ->andWhere('p.user_eschool_id = ?', $this->user->getApp()->getShortName())
                ->andWhere('p.user_id = ?', $this->user->getObject()->id)
                ->andWhere('p.payoff_status = ?', 'pending')
                ->fetchOne();
        return $payoff;
    }
    public function getPreviousMonthlyDataRecord($month, $year)
    {
        if ($month == 1)
        {
            $month = 13;
            $year--;
        }
        return $this->getMonthlyDataRecord(--$month, $year);
    }
    public function getPreviousPayoff()
    {
        $payoff = Doctrine::getTable('GcrPayoff')->createQuery('p')
                ->where('p.eschool_id = ?', $this->user->getApp()->getShortName())
                ->andWhere('p.user_eschool_id = ?', $this->user->getApp()->getShortName())
                ->andWhere('p.user_id = ?', $this->user->getObject()->id)
                ->orderBy('p.trans_time DESC')
                ->fetchOne();
        return $payoff;
    }
    public function isOwner()
    {
        return $this->is_owner;
    }
    public function isEclassroomUser()
    {
        return $this->is_eclassroom_user;
    }
    public function updateAccounting($params = array())
    {
        $verbose = false;
        $update_eclassrooms = false;
        if (isset($params['verbose']) && $params['verbose'])
        {
            $verbose = true;
        }
        if ($this->is_owner && isset($params['update_eclassrooms']) && $params['update_eclassrooms'])
        {
            $update_eclassrooms = true;
        }
            
        if ($this->is_owner || $this->is_eclassroom_user)
        {
            $this->getAccount()->updateAccounting($verbose);
            if ($update_eclassrooms)
            {
                foreach ($this->user->getApp()->getEclassroomUsers() as $user)
                {
                    $account_manager = $user->getAccountManager();
                    $account_manager->updateAccounting(array('verbose' => $verbose));
                }
            }
        }
    }
    public function getChainedPaymentsAllowed()
    {
         $chained_payment = Doctrine::getTable('GcrChainedPayment')
                ->createQuery('c')
                ->where('c.user_id = ?', $this->user->getObject()->id)
                ->andWhere('c.user_institution_id = ?', $this->user->getApp()->getShortName())
                ->fetchOne();
        if (count($chained_payment) > 0)
        {
            return $chained_payment;
        }
        return false;
    }
    public function setChainedPaymentsAllowed($allowed = true)
    {
        $chained_payment = $this->getChainedPaymentsAllowed();
        if (!$allowed)
        {
            if ($chained_payment)
            {
                $chained_payment->delete();
            }
        }
        else if (!$chained_payment)
        {
            $chained_payment = new GcrChainedPayment();
            $chained_payment->setUserId($this->user->getObject()->id);
            $chained_payment->setUserInstitutionId($this->user->getApp()->getShortName());
            $chained_payment->save();
        }
    }
    public function usesChainedPayments()
    {
        $flag = false;
        if ($this->getChainedPaymentsAllowed())
        {
            if ($this->is_owner || $this->user->getApp()->isInternal())
            {
                if ($this->getPayoffCredentials())
                {
                    $flag = true;    
                }
                else
                {
                    global $CFG;
                    $CFG->current_app->gcError('Warning: app id ' . $this->user->getApp()->getShortName() . 
                            ', user id ' . $this->user->getObject()->id .
                            ' has chained payments enabled, but owner has no payoff credentials.');
                }
            }
        }
        return $flag;
    }
    public function getUser()
    {
        return $this->user;
    }
}

?>