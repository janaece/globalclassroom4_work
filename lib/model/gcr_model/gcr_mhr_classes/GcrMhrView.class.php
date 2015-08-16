<?php

//
// This class represents a user from a mdl_user table of a given $this->eschool, and offers methods
// to manipulate this mahara user, without the need to actauly be logged in to that user's mahara.
class GcrMhrView extends GcrMhrTableRecord
{
    public function getAccessList()
    {
        $sql = 'SELECT va.*, g.grouptype, g.name
                FROM '.$this->app->getShortName().'.mhr_view_access va
                LEFT OUTER JOIN '.$this->app->getShortName().'.mhr_group g
                ON (g.id = va.group AND g.deleted = ?)
                WHERE va.view IN (?) AND va.visible = ?';

        $accessgroups = $this->app->gcQuery($sql, array(0, $this->obj->id, 1));
        if($accessgroups)
        {
            foreach ($accessgroups as $access)
            {
                $vi = $access->view;

                // Forget about secret urls for now, they don't really fit into the layout well
                /*
                if ($access->token)
                {
                    $access->accesstype = 'token';
                    if (!isset($vi['type'][$vi['id']]['secreturls']))
                    {
                        $vi['type'][$vi['id']]['secreturls'] = 0;
                    }
                    $vi['type'][$vi['id']]['secreturls']++;
                    continue;
                }
                */

                $key = null;
                if ($access->usr)
                {
                    $access->accesstype = 'user';
                    $access->id = $access->usr;
                }
                else if ($access->group)
                {
                    $access->accesstype = 'group';
                    $access->id = $access->group;
                    /*
                    if ($access->role)
                    {
                        $access->roledisplay = get_string($access->role, 'grouptype.' . $access->grouptype);
                    }
                    */
                }
                else if($access->token)
                {
                    $access->accesstype = 'token';
                    $access->name = 'Secret URL';
                }
                else
                {
                    $key = $access->accesstype;
                }
                if ($key)
                {
                    if (!isset($vi['type'][$vi['id']]['accessgroups'][$key]))
                    {
                        $data[$vi['type']][$vi['id']]['accessgroups'][$key] = (array) $access;
                    }
                }
                else
                {
                    $data[$vi['type']][$vi['id']]['accessgroups'][] = (array) $access;
                }
            }
            return $accessgroups;
        }
        return false;
    }
    public function replaceBlocks($mhr_view_new)
    {
        $mhr_view_obj = $mhr_view_new->getObject();
        // replace block instances with copies of the new view
        $this->deleteBlocks();

        $mhr_template_block_instances = $this->app->selectFromMhrTable('block_instance', 'view', $mhr_view_obj->id);
        foreach ($mhr_template_block_instances as $mhr_template_block_instance)
        {
            $this->copyBlock($mhr_template_block_instance);
        }
        // update mhr_view row
        return $this->app->updateMhrTable('view', array('numcolumns' => $mhr_view_obj->numcolumns, 
            'layout' => $mhr_view_obj->layout), array('id' => $this->obj->id));
    }
    public function copyBlock($mhr_template_block_instance)
    {
        $params = array('id' => gcr::autoNumber,
                        '"view"' => $this->obj->id,
                        '"row"' => $mhr_template_block_instance->row,
                        '"column"' => $mhr_template_block_instance->column,
                        'blocktype' => $mhr_template_block_instance->blocktype,
                        'configdata' => $mhr_template_block_instance->configdata,
                        'title' => $mhr_template_block_instance->title,
                        '"order"' => $mhr_template_block_instance->order);
        $mhr_block_instance = $this->app->insertIntoMhrTable('block_instance', $params);
        // check for associated artefacts which will need a record in mhr_view_artefact for the
        // new block copy
        if ($mhr_view_artefacts = $this->app->selectFromMhrTable('view_artefact', 'block', $mhr_template_block_instance->id))
        {
            foreach ($mhr_view_artefacts as $mhr_view_artefact)
            {
                $params = array('id' => gcr::autoNumber,
                            '"view"' => $this->obj->id,
                            'artefact' => $mhr_view_artefact->artefact,
                            'block' => $mhr_block_instance->id);
                $this->app->insertIntoMhrTable('view_artefact', $params);
            }
        }
        return $mhr_block_instance;
    }
    public function getBlocks()
    {
        return $this->app->selectFromMhrTable('block_instance', 'view', $this->obj->id);
    }
    public function deleteBlocks()
    {
        $this->app->deleteFromMhrTable('view_artefact', 'view', $this->obj->id);
        $this->app->deleteFromMhrTable('block_instance', 'view', $this->obj->id);
    }
    public function appendBlock($mhr_block_instance)
    {
        $highest_order = 1;
        $highest_column = 1;
        $highest_row = 1;
        $blocks = $this->getBlocks();
        foreach ($blocks as $block)
        {
            if ($block->order > $highest_order)
            {
                $highest_order = $block->order;
            }
        }
        foreach ($blocks as $block)
        {
            if ($block->order == $highest_order)
            {
                if ($block->column > $highest_column)
                {
                    $highest_column = $block->column;
                }
            }
            if ($block->row > $highest_row)
            {
                $highest_row = $block->row;
            }
        }
        if ($highest_column >= $this->obj->numcolumns)
        {
            $highest_column = 1;
            $highest_order++;
        }
        else
        {
            $highest_column++;
        }
        $mhr_block_instance->order = $highest_order;
        $mhr_block_instance->column = $highest_column;
        $mhr_block_instance->row = $highest_row;
        $this->copyBlock($mhr_block_instance);
    }
}
