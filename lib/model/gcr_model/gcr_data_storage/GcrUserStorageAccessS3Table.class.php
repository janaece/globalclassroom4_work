<?php
/**
 * class GcrUserstorageAccessS3Table
 *
 * @author Ron Stewart
 */
class GcrUserstorageAccessS3Table
{
    protected $table;
    protected $user_storage;
    protected $column_functions;
    
    public function __construct($user_storage)
    {
        $this->user_storage = $user_storage;
        $this->table = new GcrTable(array(), array('id' => 'gc_user_storage_table',
            'class' => 'tablesorter', 'cellspacing' => '1'), false, true);
        $this->column_functions = array();
        $this->buildTable();
    }
    protected function buildTable()
    {
        $this->setColumns();
        $files = $this->user_storage->getFileList();
        if ($files)
        {
            $file_count = 0;
            foreach ($files as $file)
            {
                $file_count++;
                $columns = $this->table->getColumns();
                for ($i = 0; $i < $this->table->getColumnCount(); $i++)
                {
                    $function = $this->column_functions[$i];
                    $columns[$i]->addCell($this->$function($file, $file_count));
                }
            }
        }
    }
    protected function setColumns()
    {
        $file_name = new GcrTableColumn();
        $file_name_header = new GcrTableCell(array(), 'File Name', true);
        $file_name->addCell($file_name_header);
        $this->column_functions[] = 'getFileNameCell';
        $this->table->addColumn($file_name);

        $action_move = new GcrTableColumn(array(), !$this->user_storage->isAdmin());
        $action_move_header = new GcrTableCell(array(), '', true);
        $action_move->addCell($action_move_header);
        $this->column_functions[] = 'getActionMoveCell';
        $this->table->addColumn($action_move);
        
        $action_delete = new GcrTableColumn(array(), !$this->user_storage->allowsUpload());
        $action_delete_header = new GcrTableCell(array(), '', true);
        $action_delete->addCell($action_delete_header);
        $this->column_functions[] = 'getActionDeleteCell';
        $this->table->addColumn($action_delete);
    }
    protected function getFileNameCell($file, $index)
    {
        $content = '<a href="' . $this->user_storage->getStaticUrl($file) 
                . '">' . $this->user_storage->getFileName($file) . '</a>';
        return new GcrTableCell(array(), $content);
    }
    protected function getActionMoveCell($file, $index)
    {
        global $CFG;
        $form_name = 'gc_move_file_form_';
        $form_id = $form_name . $index;
        $content = '<span class="gc_list_action_column"><form id="' . $form_id . 
                '" name="' . $form_id . '" action="' . $CFG->current_app->getUrl() . 
                '/institution/moveUserStorageFile" method="POST">';
        $content .= '<input type="hidden" name="' . $form_name . 'file_id" value="' . $file . '" />';
        $content .= '<input type="hidden" name="' . $form_name . 'key" value="' . 
                $this->user_storage->getKey() . '" />';
        $content .= 'Move To Folder: <select id="' . $form_id . '_file_new_id"' . 
                ' name="' . $form_name . 'file_new_id" class="gc_move_file_form_new_file_id"' . 
                ' gc_move_file_form_index="' . $index . '">';
        foreach ($this->user_storage->getFolders() as $key => $value)
        {
            $content .= '<option value="' . $key . '/' . $this->user_storage->getFileName($file) . '"';
        
            if ($key == $this->user_storage->getObjectKey($file))
            {
                $content .= ' selected="selected"';
            }
            $content .= '>' . $value . '</option>';
        }
        $content .= '</select></form></span>';
        return new GcrTableCell(array(), $content);
    }
     protected function getActionDeleteCell($file, $index)
    {
        $content = '<span class="gc_list_action_column"><button class="delete_s3_file" file_key="' . 
                $file . '">Delete</button></span>';
        return new GcrTableCell(array(), $content);
    }
    public function getHTML()
    {
        return $this->table->getHTML();
    }
    public function printTable()
    {
        print $this->getHTML();
    }
}
?>
