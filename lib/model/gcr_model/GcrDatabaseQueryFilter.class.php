<?php

/**
 * Description of GcrDatabaseQueryFilter
 *
 * @author ron
 */
class GcrDatabaseQueryFilter
{
    protected $field;
    protected $value;
    protected $operator;
    protected $boolean_operator;

    public function __construct($field, $operator, $value, $boolean_operator = 'and')
    {
        $this->field = $field;
        $this->value = $value;
        $this->operator = $operator;
        $this->boolean_operator = $boolean_operator;
    }
    public function apply($query)
    {
        $params = $query->getParams();
        if (count($params) > 0)
        {
            $boolean_string = ' ' . $this->boolean_operator . ' ';
        }
        else
        {
            $boolean_string = ' where ';
        }
        $params[] = $this->value;
        $query->setQuery($query->getSql() . $boolean_string . '"' .
                $this->field . '" ' . $this->operator . ' ?', $params);
    }

    public function getField()
    {
        return $this->field;
    }
    public function setField($field)
    {
        $this->field = $field;
    }
    public function setValue($value)
    {
        $this->value = $value;
    }
    public function getValue()
    {
        return $this->value;
    }
    public function getOperator()
    {
        return $this->operator;
    }
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }
    public function getBooleanOperator()
    {
        return $this->boolean_operator;
    }
    public function setBooleanOperator($boolean_operator)
    {
        $this->boolean_operator = $boolean_operator;
    }
}
?>
