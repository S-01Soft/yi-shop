ALTER TABLE `__PREFIX__shop_product` ADD `attr_group` VARCHAR( 1000 ) NOT NULL COMMENT '属性组' AFTER `seo_description` ,
ADD `attr_items` VARCHAR( 1000 ) NOT NULL COMMENT '属性项' AFTER `attr_group` ;