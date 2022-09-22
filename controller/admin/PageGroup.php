<?php

namespace app\shop\controller\admin;

use support\exception\Exception;

/**
 * @Menu(title=Shop Page Group,weigh=99600,ignore=toggle|all|select|tree|tree_array|tree_list|imports|exports,ismenu=1)
 */
class PageGroup extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\PageGroupValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\admin\PageGroupLogic::instance();
    }

    public function useTemplate()
    {
        try {
            $id = $this->request->post('id');
            $this->logic->useTemplate($id);
            return $this->success();
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}