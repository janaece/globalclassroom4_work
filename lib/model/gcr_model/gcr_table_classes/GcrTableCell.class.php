<?php

/**
 * Description of GcrTableCell.
 *
 * @author Ron Stewart
 */
class GcrTableCell
{
    protected $attributes;
    protected $content;
    protected $is_header;

    public function __construct($attributes = array(), $content = '', $is_header = false)
    {
        $this->attributes = $attributes;
        $this->content = $content;
        $this->is_header = $is_header;
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
    public function getContent()
    {
        return $this->content;
    }
    public function setContent($content)
    {
        $this->content = $content;
    }
    public function isHeader()
    {
        return $this->is_header;
    }
    public function setHeader($is_header)
    {
        $this->is_header = $is_header;
    }
    public function getHTML()
    {
        $tag = ($this->is_header) ? 'th' : 'td';
        $html = '<' . $tag;
        foreach ($this->attributes as $key => $value)
        {
            $html .= ' ' . $key . '="' . $value . '"';
        }
        if ($this->content == '')
        {
            $html .= '> </' . $tag . '>';
        }
        else
        {
            $html .= '>' . $this->content . '</' . $tag . '>';
        }
        return $html;
    }
}
?>
