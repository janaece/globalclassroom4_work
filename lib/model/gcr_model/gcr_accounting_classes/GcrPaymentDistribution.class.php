<?php
class GcrPaymentDistribution
{
    protected $gc;
    protected $seller;
    protected $total;
    protected $commission;

    public function __construct($total, $seller = 0, $gc = 0, $commission = 0)
    {
        $this->total = $total;
        $this->gc = $gc;
        $this->seller = $seller;
        $this->commission = $commission;
    }

    public function getTotal()
    {
        return $this->total;
    }
    public function getSeller()
    {
        return $this->seller;
    }
    public function getGC()
    {
        return $this->gc;
    }
    public function getOwner()
    {
        return $this->total - ($this->commission + $this->seller + $this->gc);
    }
    public function getCommission()
    {
        return $this->commission;
    }
}