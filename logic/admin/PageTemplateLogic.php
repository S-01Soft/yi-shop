<?php

namespace app\shop\logic\admin;

use support\exception\Exception;

class PageTemplateLogic extends Logic
{

    protected function initialize()
    {
        $this->static = \app\shop\model\admin\PageTemplateModel::class;
        parent::initialize();
    }

    public function paginateView($c)
    {
        $c->hidden = ['import_btn', 'export_btn'];
    }

    public function designView()
    {}

    public function getDesign($id)
    {
        return $this->static::find($id);
    }

    public function postDesign($form)
    {
        $id = $form['id'];
        $row = $this->model->find($id);
        $row->update($form);
        return $row;
    }

    public function useTemplate($id)
    {
        $row = $this->model->find($id);
        $config = [
            'template_id' => $row['id'],
            'template_title' => $row['title'],
            'page_setting' => $row['content']
        ];
        set_addon_config('shop', $config);
        return $row;
    }

    public function copy($id)
    {
        $row = $this->static::find($id);
        if (!$row) throw new Exception("模板不存在");
        $this->static::create([
            'title' => $row->title,
            'content' => $row->content
        ]);
    }

    public function delTemplate($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}