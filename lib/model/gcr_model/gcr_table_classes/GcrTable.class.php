<?php

/**
 * Description of GcrTable
 *
 * @author Ron Stewart
 */
class GcrTable
{
    protected $columns;
    protected $attributes;
    protected $row_attributes;
    protected $has_header;

    public function __construct($columns = array(), $attributes = array(),
            $row_attributes = array(), $has_header = false)
    {
        $this->columns = $columns;
        $this->attributes = $attributes;
        $this->row_attributes = $row_attributes;
        $this->has_header = $has_header;
    }
    public function addColumn($column)
    {
        $this->columns[] = $column;
    }
    public function getRowAttributes($row)
    {
        return $this->row_attributes[$row];
    }
    public function setRowAttributes($row, $attributes = array())
    {
        $this->row_attributes[$row] = $attributes;
    }
    public function getAttributes($attribute = false)
    {
        if ($attribute)
        {
            return $this->attributes[$attribute];
        }
        return $this->attributes;
    }
    public function setAttributes($attributes = array())
    {
        $this->attributes = $attributes;
    }
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }
    public function getColumns($column = -1)
    {
        if ($column > -1)
        {
            return $this->columns[$column];
        }
        return $this->columns;
    }
    public function setColumns($columns = array())
    {
        $this->columns = $columns;
    }
    public function setColumn($index, $column)
    {
        $this->columns[$index] = $column;
    }
    public function getRowCount()
    {
        $row_count = 0;
        foreach($this->getColumns() as $column)
        {
            $column_row_count = $column->getCellCount();
            if ($column_row_count > $row_count)
            {
                $row_count = $column_row_count;
            }
        }
        return $row_count;
    }
    public function getColumnCount()
    {
        return count($this->columns);
    }
    public function hasHeader()
    {
        return $this->has_header;
    }
    public function setHasHeader($has_header)
    {
        $this->has_header = $has_header;
    }
    public function getArray() 
    {
        $table_array = array();
        $row_count = $this->getRowCount();
        for ($i = 0; $i < $row_count; $i++)
        {
            if ($this->has_header && $i == 0) 
            {
                continue;
            }
            $row_array = array();
            
            foreach ($this->columns as $column)
            {
                if (!$column->isHidden())
                {
                    $cell = $column->getCells($i);
                    $row_array[] = $cell->getContent();                  
                }
            }
            $table_array[] = $row_array;
        }
        return $table_array;
    }
    public function getHTML()
    {
        $html = '<table';
        foreach ($this->attributes as $key => $value)
        {
            $html .= ' ' . $key . '="' . $value . '"';
        }
        $html .= '>';

        $row_count = $this->getRowCount();
        for ($i = 0; $i < $row_count; $i++)
        {
            if ($i < 2 && $this->has_header)
            {
                if ($i == 0)
                {
                    $html .= '<thead>';
                }
                else
                {
                    $html .= '<tbody>';
                }
            }
            $html .= '<tr';
            if($this->row_attributes)
            {
                foreach ($this->row_attributes[$i] as $key => $value)
                {
                    $html .= ' ' . $key . '="' . $value . '"';
                }
            }
            $html .= '>';
            foreach ($this->columns as $column)
            {
                if (!$column->isHidden())
                {
                    $cell = $column->getCells($i);
                    if (isset($cell))
                    {
                        $html .= $cell->getHTML();
                    }
                    else
                    {
                        if ($this->has_header && ($i == 0))
                        {
                            $html .= '<th> </th>';
                        }
                        else
                        {
                            $html .= '<td> </td>';
                        }
                    }
                }
            }
            $html .= '</tr>';
            if ($i < 2 && $this->has_header)
            {
                $html .= '</thead>';
            }
        }
        $html .= ($this->has_header) ? '</tbody></table>' : '</table>';
        return $html;
    }
}
?>
