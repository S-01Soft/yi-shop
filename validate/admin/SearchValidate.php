<?php
namespace app\shop\validate\admin;

use yi\Validate;

class SearchValidate extends Validate
{

    public function __construct()
    {
        $this->field = [
            'id' => lang('Id'),
            'keyword' => lang('Keyword'),
            'hits' => lang('Hits'),
            'weigh' => lang('Weigh'),
            'status' => lang('Status')
        ];
        parent::__construct();
    }

    protected $rule =  [
        'keyword' => 'require|length:0,255',
        'hits' => 'require',
        'weigh' => 'require',
        'status' => 'require',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['keyword', 'hits', 'weigh', 'status'],
        'edit' => ['keyword', 'hits', 'weigh', 'status'],
    ];    
}