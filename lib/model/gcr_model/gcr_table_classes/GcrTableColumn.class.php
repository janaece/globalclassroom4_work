<?php
/**
 * This class represents a single column of a data holding table.
 *
 * @author Ron Stewart
 * 05/05/11
 */
class GcrTableColumn
{
    protected $cells;
    protected $is_hidden;

    public function __construct($cells = array(), $is_hidden = false)
    {
        $this->cells = $cells;
        $this->is_hidden = $is_hidden;
    }
    public function addCell($cell)
    {
        $this->cells[] = $cell;
    }
    public function getCells($row = -1)
    {
        if ($row >= 0)
        {
            return $this->cells[$row];
        }
        return $this->cells;
    }
    public function setCells($cells = array())
    {
        $this->cells = $cells;
    }
    public function setCell($row, $cell)
    {
        $this->cells[$row] = $cell;
    }
    public function getCellCount()
    {
        return count($this->cells);
    }
    public function isHidden()
    {
        return $this->is_hidden;
    }
    public function hasContent()
    {
        $header = true;
        foreach($this->cells as $cell)
        {
            if ($header)
            {
                $header = false;
            }
            else
            {
                $content = $cell->getContent();
                if (isset($content) && $content != '')
                {
                    return true;
                }
            }
        }
        return false;
    }
    public function setHidden($is_hidden)
    {
        $this->is_hidden = $is_hidden;
    }
}
?>
