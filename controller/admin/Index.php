<?php

namespace app\shop\controller\admin;

use support\exception\Exception;

/**
 * @Menu(title=数据统计,weigh=99999,ismenu=1)
 */
class Index extends Base
{

    public $noNeedCheck = ['getPages', 'getTabBars', 'getExpandPages'];

    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\admin\IndexLogic::instance();
    }

    /**
     * @Menu(title=查看,ismenu=1)
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            try {
                $data = $this->logic->getTotalData();
                return $this->success($data);
            } catch (Exception $e) {
                return $this->error($e->getMessage(), $e->getCode());
            }
        }
        return $this->fetch();
    }

    /**
     * @Menu(title=获取订单每日数据)
     */
    public function getEchartData()
    {        
        try {
            $params = $this->request->post();
            $data = $this->logic->getEchartData($params);
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @Menu(title=下载前端)
     */
    public function down()
    {
        $fullconfig = get_module_full_config('shop');
        $config = get_module_config('shop');
        $payload = (object)[
            'params' => []
        ];
        event('ShopReplaceParams', $payload);
        $params = $payload->params;
        
        $pages = explode(',', $config['template_pages']);
        $cache_tpl_path = runtime_path() . DS . 'temp' . DS . 'modules' . DS . 'shop' . DS . 'template';
        if (is_dir($cache_tpl_path)) rmdirs($cache_tpl_path);
        if (is_file($cache_tpl_path . '.zip')) @unlink($cache_tpl_path . '.zip');
        copy_files(app_path() . DS . 'shop'. DS . 'frontend', $cache_tpl_path);
        $module_list = get_module_list();
        foreach ($module_list as $module) {
            if (!empty($module['dep'])) {
                $deps = explode(',', str_replace(' ', '', $module['dep']));
                if (in_array('shop', $deps)) {
                    if (is_dir(app_path() . DS . $module['name'] . DS . 'frontend')) copy_files(app_path() . DS . $module['name'] . DS . 'frontend', $cache_tpl_path);
                }
            }
        }
        foreach ($pages as $page) {
            $file = str_replace('/', DS, $cache_tpl_path . DS . $page);
            $content = file_get_contents($file);
            foreach ($fullconfig as $v) {
                if (!empty($v['is_replace'])) {
                    $content = str_replace($v['name'], $v['value'], $content);
                }
            }
            foreach ($params as $k => $v) {
                $content = str_replace('<!--' . $k . '-->', $v, $content);
                $content = str_replace('/**' . $k . '**/', $v, $content);
                $content = str_replace($k, $v, $content);
            }
            file_put_contents($file, $content);
        }

        $zipFile = $cache_tpl_path . '.zip';
        if (is_file($zipFile)) rmdirs($zipFile);
        $cache_tpl_path  = $cache_tpl_path . DS;
        $zip = new \ZipArchive;
        $zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($cache_tpl_path), \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = str_replace(DS, '/', substr($filePath, strlen($cache_tpl_path)));
                if (!in_array($file->getFilename(), ['.git', '.DS_Store', 'Thumbs.db'])) {
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
        $zip->close();

        $file = fopen($zipFile, 'rb');
        $file_dir = './down/';  

        return response()->download($zipFile, 'shop-uniapp.zip');
    }

    public function getPages()
    {
        try {
            $data = $this->logic->getPages();
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getTabBars()
    {
        try {
            $data = $this->logic->getTabs();
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    
    public function getExpandPages()
    {
        try {
            $data = $this->logic->getExpandPages();
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}