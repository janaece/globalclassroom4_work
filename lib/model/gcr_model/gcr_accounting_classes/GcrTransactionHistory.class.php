<?php
class GcrTransactionHistory
{
    protected $transactions;
    protected $items;
    protected $owner_balance;
    protected $seller_balance;

    public function _construct($institution = false)
    {
        if (!$this->institution = $institution)
        {
            global $CFG;
            $this->institution = $CFG->current_app->getInstitution();
        }
    }
    public function getTransactions()
    {
        return $transactions;
    }
    public function add($transaction)
    {
        $this->transactions[] = $transaction;
    }
    public function buildItems()
    {
        $this->items = array();
        if (!empty($this->transactions))
        {
            foreach ($this->transactions as $transaction)
            {
                if ($transaction->isRecurring())
                {
                    foreach($transaction->getPaypalRecords() as $record)
                    {
                        $this->items[$this->getUniqueTimeKey($record->getPaymentDate())] =
                                new GcrTransactionItem($transaction->getPurchase(), $record);
                    }
                }
                else
                {
                    $this->items[$this->getUniqueTimeKey($transaction->getPurchase()->getTransTime())] =
                            new GcrTransactionItem($transaction->getPurchase(), false);

                }
                if ($refunds = $transaction->getRefunds())
                {
                    foreach($refunds as $refund)
                    {
                        $this->items[$this->getUniqueTimeKey($refund->getPaymentDate())] =
                                new GcrTransactionItem($transaction->getPurchase(), $refund);
                    }
                }
            }
        }
        ksort($this->items, SORT_NUMERIC);
    }
    public function getItems()
    {
        return $this->items;
    }
    // Just in case two transaction items happen to have identical timestamps
    public function getUniqueTimeKey($key)
    {
        while (array_key_exists($key, $this->items))
        {
            $key++;
        }
        return $key;
    }
    public function setBalances($key, $owner, $eclassroom)
    {
        if (array_key_exists($key, $this->items))
        {
            $this->items[$key]->setOwnerBalance($owner);
            $this->items[$key]->setEclassroomBalance($eclassroom);
        }
    }
    public function setItem($date, $item)
    {
        $this->items[$date] = $item;
    }
    public function remove($ts)
    {
        unset($this->items[$ts]);
    }
}