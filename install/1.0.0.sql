CREATE TABLE IF NOT EXISTS `__PREFIX__shop_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL COMMENT '文章标题',
  `description` varchar(500) DEFAULT NULL COMMENT '文章描述',
  `content` text COMMENT '内容',
  `image` varchar(500) DEFAULT NULL COMMENT '缩略图',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `created_at` int(10) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='文章表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_articles_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='文章分类中间表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL COMMENT '上级分类',
  `name` varchar(255) NOT NULL COMMENT '分类名称',
  `description` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `sort` int(11) DEFAULT '10000' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='文章分类表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '商品',
  `sku_id` int(10) unsigned DEFAULT NULL COMMENT 'SKU',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '销售价',
  `quantity` int(10) DEFAULT '0' COMMENT '数量',
  `is_selected` tinyint(1) DEFAULT '1' COMMENT '是否选中',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='购物车表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `image` varchar(500) DEFAULT NULL COMMENT '图标',
  `sort` int(10) NOT NULL DEFAULT '1000' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用',
  `created_at` int(10) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='商品分类表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `order_product_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned DEFAULT NULL COMMENT '订单',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '商品',
  `sku_id` int(10) unsigned DEFAULT NULL COMMENT 'SKU',
  `images` varchar(1000) NOT NULL COMMENT '图片',
  `content` text COMMENT '评价内容',
  `star` tinyint(1) DEFAULT '5' COMMENT '星级',
  `created_at` int(10) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(10) DEFAULT NULL COMMENT '最后更新',
  `deleted_at` int(10) DEFAULT NULL COMMENT '删除时间',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='订评价表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_delivery_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_id` int(10) unsigned NOT NULL COMMENT '模板ID',
  `first_price` decimal(10,2) NOT NULL COMMENT '首重/件价格',
  `rest_price` decimal(10,2) NOT NULL COMMENT '续重/件价格',
  `area_ids` varchar(500) DEFAULT NULL,
  `area_names` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='运费规则表' ;
 
CREATE TABLE IF NOT EXISTS `__PREFIX__shop_delivery_rule_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_area_id` int(10) unsigned NOT NULL COMMENT '运费规则ID',
  `area_id` int(10) unsigned NOT NULL COMMENT '地区',
  `area_name` varchar(100) NOT NULL COMMENT '地区名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='运费规则地区对照表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_delivery_tpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '计费方式',
  `sort` int(5) unsigned NOT NULL DEFAULT '1000' COMMENT '排序',
  `is_default` tinyint(1) DEFAULT '0' COMMENT '默认',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='运费模板表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_express` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL COMMENT '快递公司编号',
  `name` varchar(100) NOT NULL COMMENT '快递公司名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='快递公司表' ;
 
CREATE TABLE IF NOT EXISTS `__PREFIX__shop_favorite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '商品',
  `created_at` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='订评价表' ;
 
CREATE TABLE IF NOT EXISTS `__PREFIX__shop_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '商品',
  `created_at` int(10) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='浏览历史表' ;

 
CREATE TABLE IF NOT EXISTS `__PREFIX__shop_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) NOT NULL COMMENT '订单编号',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户',
  `is_pay` tinyint(1) DEFAULT '0' COMMENT '是否已支付',
  `pay_time` int(10) unsigned DEFAULT NULL COMMENT '支付时间',
  `is_delivery` tinyint(1) DEFAULT '0' COMMENT '是否发货',
  `delivery` int(10) unsigned DEFAULT NULL COMMENT '发货时间',
  `is_received` tinyint(1) DEFAULT '0',
  `received_time` int(10) DEFAULT NULL,
  `express_code` varchar(45) DEFAULT NULL COMMENT '快递公司编号',
  `express_no` varchar(45) DEFAULT NULL COMMENT '快递单号',
  `status` tinyint(1) DEFAULT '0' COMMENT '订单状态',
  `contactor` varchar(45) NOT NULL COMMENT '联系人',
  `contactor_phone` varchar(45) NOT NULL COMMENT '联系电话',
  `address` varchar(500) DEFAULT NULL COMMENT '送货地址',
  `delivery_price` decimal(10,2) DEFAULT '0.00' COMMENT '运费',
  `order_price` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  `pay_price` decimal(10,2) DEFAULT '0.00' COMMENT '应付金额',
  `pay_type` varchar(15) DEFAULT NULL COMMENT '支付平台',
  `pay_method` varchar(15) DEFAULT NULL COMMENT '支付方式',
  `products_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品总额',
  `discount_price` decimal(10,2) DEFAULT '0.00' COMMENT '优惠金额',
  `payed_price` decimal(10,2) DEFAULT '0.00' COMMENT '已付金额',
  `buyer_comment` tinyint(1) DEFAULT '0' COMMENT '买家是否评价',
  `saler_comment` tinyint(1) DEFAULT '0' COMMENT '卖家是否评价',
  `saler_remark` varchar(255) DEFAULT NULL COMMENT '买家备注',
  `created_at` int(10) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(10) DEFAULT NULL COMMENT '最后更新',
  `deleted_at` int(10) DEFAULT NULL COMMENT '删除时间',
  `remark` varchar(400) DEFAULT '' COMMENT '用户备注',
  `order_type` tinyint(2) DEFAULT '0' COMMENT '订单类型',
  `groupon_status` tinyint(2) DEFAULT '0' COMMENT '团购状态',
  `order_sn_re` varchar(45) DEFAULT '' COMMENT 'out_trade_no',
  `after_buyer_remark` varchar(400) DEFAULT '' COMMENT '售后买家留言',
  `after_saler_remark` varchar(400) DEFAULT '' COMMENT '售后卖家留言',
  `after_sale_status` tinyint(2) DEFAULT '0' COMMENT '售后状态',
  `after_sale_data` varchar(500) DEFAULT '{}' COMMENT '售后数据',
  `refund_fee` decimal(10,2) DEFAULT '0.00' COMMENT '退款金额',
  `score_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `use_score` int(11) NOT NULL DEFAULT '0',
  `money_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_sn_UNIQUE` (`order_sn`),
  KEY `order_sn` (`order_sn`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='订单表' ;
 
CREATE TABLE IF NOT EXISTS `__PREFIX__shop_orderlogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `action` int(5) NOT NULL DEFAULT '1',
  `desc` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extend` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;
 
CREATE TABLE IF NOT EXISTS `__PREFIX__shop_order_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned DEFAULT NULL COMMENT '订单',
  `product_id` int(10) unsigned DEFAULT NULL COMMENT '商品',
  `sku_id` int(10) unsigned DEFAULT NULL,
  `shop_id` int(10) unsigned DEFAULT NULL COMMENT '商家',
  `title` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `description` varchar(255) DEFAULT NULL COMMENT '商品描述',
  `image` varchar(1000) DEFAULT NULL COMMENT '商品图片',
  `attributes` varchar(255) DEFAULT NULL COMMENT '商品规格',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '销售价',
  `quantity` int(10) DEFAULT '0' COMMENT '数量',
  `unit` varchar(25) NOT NULL DEFAULT '',
  `product_price` decimal(10,2) DEFAULT '0.00' COMMENT '应付金额',
  `discount_price` decimal(10,2) DEFAULT '0.00' COMMENT '优惠金额',
  `order_price` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  `buyer_comment` tinyint(1) DEFAULT '0',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `deleted_at` int(10) DEFAULT NULL,
  `stock_type` int(1) DEFAULT '1' COMMENT '何时扣库存',
  `freeze_stock` int(11) NOT NULL DEFAULT '0' COMMENT '冻结库存',
  `auto_receive_time` int(1) DEFAULT '1' COMMENT '自动收货时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='订单商品表' ;
 
CREATE TABLE IF NOT EXISTS `__PREFIX__shop_page_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='页面' ;
 
CREATE TABLE IF NOT EXISTS `__PREFIX__shop_page_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL COMMENT '模板说明',
  `content` text COMMENT '模板内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='页面模板表' ;
 
CREATE TABLE IF NOT EXISTS `__PREFIX__shop_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned DEFAULT NULL COMMENT '分类',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `seo_description` varchar(500) NOT NULL DEFAULT '' COMMENT 'SEO_描述',
  `content` text NOT NULL COMMENT '内容',
  `image` varchar(1000) NOT NULL COMMENT '图片',
  `on_sale` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否销售',
  `rating` double(8,2) NOT NULL DEFAULT '5.00' COMMENT '折扣',
  `sold_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售数量',
  `unit_id` int(10) DEFAULT NULL COMMENT '单位',
  `comment_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评价数量',
  `service_tags` varchar(500) DEFAULT NULL,
  `home_recommend` tinyint(1) DEFAULT '0' COMMENT '首页推荐',
  `category_recommend` tinyint(1) DEFAULT '0' COMMENT '分类推荐',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售价',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `delivery_tpl_id` int(5) DEFAULT '1' COMMENT '运费模板',
  `stock_type` int(1) DEFAULT '0' COMMENT '何时扣库存',
  `score_persent` float NOT NULL DEFAULT '0',
  `product_type` int(1) NOT NULL DEFAULT '1' COMMENT '商品类型',
  `auto_receive_time` int(1) DEFAULT '0' COMMENT '自动收货时间',
  PRIMARY KEY (`id`),
  KEY `index2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='商品表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_product_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) DEFAULT NULL,
  `sku_id` int(10) unsigned NOT NULL COMMENT '商品',
  `key` varchar(50) DEFAULT NULL COMMENT '属性名称',
  `value` varchar(50) DEFAULT NULL COMMENT '属性值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品属性表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_product_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `product_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_product_sku` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL COMMENT '商品',
  `keys` varchar(255) DEFAULT NULL COMMENT '属性',
  `value` text COMMENT '属性值',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售价',
  `stock` int(10) NOT NULL DEFAULT '0' COMMENT '库存',
  `weight` float NOT NULL DEFAULT '0' COMMENT '重量',
  `sn` varchar(50) NOT NULL DEFAULT '' COMMENT '货号',
  `market_price` decimal(10,2) DEFAULT '0.00' COMMENT '市场价',
  `sold_count` int(10) DEFAULT '0' COMMENT '销量',
  `image` varchar(255) DEFAULT '' COMMENT '图片',
  `sort` int(5) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='商品属性表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_product_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '名称',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `weigh` int(11) NOT NULL DEFAULT '10000' COMMENT '排序',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_refundlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `out_trade_no` varchar(45) DEFAULT NULL COMMENT '商户订单号',
  `out_refund_no` varchar(45) DEFAULT NULL COMMENT '商户退款单号',
  `total_fee` decimal(10,2) DEFAULT NULL COMMENT '支付金额',
  `refund_fee` decimal(10,2) DEFAULT NULL COMMENT '退款金额',
  `pay_type` varchar(20) NOT NULL DEFAULT '' COMMENT '付款方式',
  `status` tinyint(1) DEFAULT '0' COMMENT '退款状态',
  `created_at` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='退款日志表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `keyword` varchar(255) NOT NULL COMMENT '搜索词',
  `hits` int(11) NOT NULL DEFAULT '1' COMMENT '搜索次数',
  `weigh` int(10) NOT NULL DEFAULT '10' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='搜索词' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_service_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '名称',
  `description` varchar(255) NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='服务标签表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_tag_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL COMMENT '模板标识',
  `description` varchar(45) DEFAULT NULL COMMENT '模板描述',
  `content` text COMMENT '模板内容',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='模板表' ;

CREATE TABLE IF NOT EXISTS `__PREFIX__shop_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '单位名称',
  `code` varchar(50) NOT NULL COMMENT '单位符号',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='计量单位表' ;
