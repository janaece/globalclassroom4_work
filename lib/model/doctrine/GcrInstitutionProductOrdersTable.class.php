<?php

class GcrInstitutionProductOrdersTable extends Doctrine_Table
{
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GcrInstitutionProductOrders');
    }
	
	public static function  get_orders($institution_short_name, $product_short_name, $user_id)
	{
        $orders = Doctrine::getTable('GcrInstitutionProductOrders')
                ->createQuery('p')
                ->where('p.deleted_flag = ?', 0)
				->andWhere('p.institution_short_name = ?', $institution_short_name)
				->andWhere('p.product_short_name = ?', $product_short_name)
				->andWhere('p.user_id = ?', $user_id)
				->orderBy('p.id ASC')
                ->execute();
        if (count($orders) > 0)
        {
			return $orders;
        }				
        return false;
		//return true;
	}
	
}
