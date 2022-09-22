<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=微页管理,weigh=99500,ignore=tree|tree_list)
 */
class PageTemplate extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\PageTemplateValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\admin\PageTemplateLogic;
    }

    /**
     * @Menu(title=设计)
     */
    public function design()
    {
        if ($this->request->isPost()) {
            $this->request->filter([]);
            $form = $this->request->post('form');
            try {
                $this->logic->postDesign($form);
                return $this->success();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        if ($this->request->isAjax()) {
            $id = $this->request->get('id');
            try {
                $row = $this->logic->getDesign($id);
                return $this->success($row);
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->logic->designView($this);
        return $this->fetch();
    }


    /**
     * @Menu(title=复制模板)
     */
    public function copy()
    {
        $id = $this->request->post('id');
        try {
            $data = $this->logic->copy($id);
            return $this->success($data);
        } catch (\support\exception\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Menu(title=使用模板)
     */
    public function useTemplate()
    {
        $id = $this->request->post('id');
        try {
            $data = $this->logic->useTemplate($id);
            return $this->success($data);
        } catch (\support\exception\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Menu(title=删除模板)
     */
    public function delTemplate()
    {
        $id = $this->request->post('id');
        try {
            $data = $this->logic->delTemplate($id);
            return $this->success($data);
        } catch (\support\exception\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }



}