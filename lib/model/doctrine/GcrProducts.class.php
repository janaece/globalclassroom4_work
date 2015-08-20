<?php

class GcrProducts extends BaseGcrProducts
{
/*     public function getProducts()
    {
        $purchases = Doctrine::getTable('GcrPurchase')
                ->createQuery('p')
                ->where('p.user_institution_id = ?', $this->user_institution_id)
                ->andWhere('p.user_id = ?', $this->user_id)
                ->andWhere('p.purchase_type == ?', 'classroom_manual')
                ->andWhere('p.purchse_type_eschool_id == ?', $this->eschool_id)
                ->execute();
        return $purchases;
    } */	
}
