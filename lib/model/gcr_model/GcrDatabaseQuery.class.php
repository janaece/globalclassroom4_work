<?php

/**
 * Description of GcrDatabaseQuery
 *
 * @author ron
 */
class GcrDatabaseQuery
{
    protected $sql;
    protected $table;
    protected $filters;
    protected $params;
    protected $app;
    protected $order_by;

    public function __construct($app, $table, $sql,
            $filters = array(), $order_by = array(), $params = array())
    {
        $this->app = $app;
        $this->table = $table;
        $this->sql = $sql . ' ' . $app->getShortName() . '.' . $app->getDatabaseTablePrefix() . $table;
        $this->params = $params;
        $this->filters = $filters;
        $this->order_by = $order_by;
    }
    public function getSql()
    {
         return $this->sql;
    }
    public function getParams()
    {
        return $this->params;
    }
    public function setParams($params)
    {
        $this->params = $params;
    }
    public function setQuery($sql, $params = array())
    {
        $this->sql = $sql;
        $this->params = $params;
    }
    public function addFilter($filter)
    {
        $this->filters[] = $filter;
    }
    public function getFilters()
    {
        return $this->filters;
    }
    public function setFilters($filters = array())
    {
        $this->filters = $filters;
    }
    public function getOrderBy()
    {
        return $this->order_by;
    }
    public function setOrderBy($order_by = array())
    {
        $this->order_by = $order_by;
    }
    public function executeQuery($return_one_record = false)
    {
        foreach ($this->filters as $filter)
        {
            $filter->apply($this);
        }
        return $this->app->gcQuery($this->sql, $this->params, $return_one_record);
    }
}
?>
