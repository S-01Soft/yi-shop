<?php
namespace app\shop\validate\admin;

use yi\Validate;

class ArticleValidate extends Validate
{
    protected $rule =  [
        'title|文章标题' => 'length:0,500|require',
        'description|文章描述' => 'length:0,500',
        'content|内容' => 'require',
        'image|缩略图' => 'length:0,500',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['title', 'description', 'content', 'image'],
        'edit' => ['title', 'description', 'content', 'image'],
    ];    
}