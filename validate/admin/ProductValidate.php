<?php
namespace app\shop\validate\admin;

use yi\Validate;

class ProductValidate extends Validate
{
    protected $rule =  [
        'title|标题' => 'require|length:0,255',
        'description|描述' => 'require|length:0,255',
        'image|图片' => 'require|length:0,1000',
        'content|内容' => 'require',
        'on_sale|上架' => 'require',
        'rating|折扣' => 'require',
        'score_persent|积分抵现比例' => 'require',
        'product_type|商品类型' => 'require',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['title', 'description', 'image', 'content', 'on_sale', 'rating', 'sold_count', 'comment_count', 'price', 'score_persent', 'product_type'],
        'edit' => ['title', 'description', 'image', 'content', 'on_sale', 'rating', 'sold_count', 'comment_count', 'price', 'score_persent', 'product_type'],
    ];    
}