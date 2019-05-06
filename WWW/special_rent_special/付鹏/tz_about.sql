/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : txt

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-04-29 18:09:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tz_about
-- ----------------------------
DROP TABLE IF EXISTS `tz_about`;
CREATE TABLE `tz_about` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT 'logo',
  `copy_right` varchar(255) NOT NULL DEFAULT '' COMMENT '备案号',
  `create_time` int(10) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='关于我们';

-- ----------------------------
-- Records of tz_about
-- ----------------------------
INSERT INTO `tz_about` VALUES ('1', '/uploads/image/123.jpg', 'copyRight2019', null, null);

-- ----------------------------
-- Table structure for tz_account
-- ----------------------------
DROP TABLE IF EXISTS `tz_account`;
CREATE TABLE `tz_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL COMMENT '用户uid',
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '1银行卡2支付宝3微信',
  `bankname` varchar(255) DEFAULT '' COMMENT '银行名称',
  `card` varchar(255) DEFAULT '' COMMENT '账户',
  `name` varchar(255) DEFAULT '' COMMENT '姓名',
  `idcard` varchar(255) DEFAULT '' COMMENT '身份证',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_account
-- ----------------------------
INSERT INTO `tz_account` VALUES ('4', '1', '1', '中国银行', '6217856100032656247', '1212', '12', '1556184447', '1556184447');
INSERT INTO `tz_account` VALUES ('6', '1', '2', '', '15190665204', '1212', '', '1556185119', '1556185119');
INSERT INTO `tz_account` VALUES ('7', '1', '3', '', '/uploads/image/201904251747.jpg', '', '', '1556185324', '1556185324');

-- ----------------------------
-- Table structure for tz_address
-- ----------------------------
DROP TABLE IF EXISTS `tz_address`;
CREATE TABLE `tz_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(10) NOT NULL COMMENT '用户uid',
  `name` varchar(255) DEFAULT '' COMMENT '收货人',
  `mobile` varchar(15) DEFAULT '' COMMENT '联系电话',
  `province` varchar(255) DEFAULT '' COMMENT '省',
  `city` varchar(255) DEFAULT '' COMMENT '市',
  `area` varchar(255) DEFAULT '' COMMENT '区',
  `street` varchar(255) DEFAULT '' COMMENT '街道',
  `address` varchar(255) DEFAULT '' COMMENT '详细地址',
  `is_default` tinyint(1) DEFAULT '0' COMMENT '是否默认',
  `create_time` int(10) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户地址表';

-- ----------------------------
-- Records of tz_address
-- ----------------------------
INSERT INTO `tz_address` VALUES ('3', '1', '张三', '13190663333', '江苏省', '南京市', '江宁区', '陆地之窗', 'c4栋', '1', '1556442521', '1556442521');

-- ----------------------------
-- Table structure for tz_banner
-- ----------------------------
DROP TABLE IF EXISTS `tz_banner`;
CREATE TABLE `tz_banner` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `img` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型1优惠券2活动产品详情3注册下载',
  `status` tinyint(1) DEFAULT '1' COMMENT '0:禁用1:正常',
  `create_time` int(10) DEFAULT NULL COMMENT 'a时间',
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='banner表';

-- ----------------------------
-- Records of tz_banner
-- ----------------------------
INSERT INTO `tz_banner` VALUES ('1', '优惠券', '/uploads/image/11.png', '1', '1', null, null);
INSERT INTO `tz_banner` VALUES ('2', '活动产品介绍', '/uploads/image/11.png', '2', '1', null, null);
INSERT INTO `tz_banner` VALUES ('3', '优惠券', '/uploads/image/11.png', '1', '1', null, null);
INSERT INTO `tz_banner` VALUES ('4', '注册下载', '/uploads/image/11.png', '3', '1', null, null);

-- ----------------------------
-- Table structure for tz_certification
-- ----------------------------
DROP TABLE IF EXISTS `tz_certification`;
CREATE TABLE `tz_certification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(10) NOT NULL COMMENT '用户uid',
  `role` tinyint(1) DEFAULT NULL COMMENT '角色1租车2司机3维修保养',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `realname` varchar(25) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `idcard` varchar(20) NOT NULL DEFAULT '' COMMENT '身份证号',
  `mobile` char(11) DEFAULT '' COMMENT '手机号',
  `panic_mobile` varchar(25) DEFAULT '' COMMENT '紧急电话',
  `type` varchar(50) DEFAULT '' COMMENT '车型1',
  `_type` varchar(50) DEFAULT '' COMMENT '车型2',
  `front_card` varchar(255) DEFAULT '' COMMENT '手持身份证正面',
  `driver_img` varchar(255) DEFAULT '' COMMENT '驾驶证',
  `travel_img` varchar(255) DEFAULT '' COMMENT '行驶证',
  `skills` varchar(255) DEFAULT '' COMMENT '技能',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `create_time` int(10) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='司机认证';

-- ----------------------------
-- Records of tz_certification
-- ----------------------------
INSERT INTO `tz_certification` VALUES ('1', '1', '2', '/uploads/image/1236.png', '张三', '3203231996063', '15190665201', '0255266666', '', '', '/uploads/image/456.png', '[\"\\/uploads\\/image\\/14.png\"]', '[\"\\/uploads\\/image\\/120.png\",\"\\/uploads\\/image\\/120.png\"]', '', '0', '1556259375', '1556259375');

-- ----------------------------
-- Table structure for tz_deposit
-- ----------------------------
DROP TABLE IF EXISTS `tz_deposit`;
CREATE TABLE `tz_deposit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `amount` int(10) NOT NULL DEFAULT '0' COMMENT '预约金',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='预约金表';

-- ----------------------------
-- Records of tz_deposit
-- ----------------------------
INSERT INTO `tz_deposit` VALUES ('1', '10000');

-- ----------------------------
-- Table structure for tz_driver
-- ----------------------------
DROP TABLE IF EXISTS `tz_driver`;
CREATE TABLE `tz_driver` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别1男2女',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '余额',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `sound` tinyint(1) unsigned DEFAULT '1' COMMENT '声音',
  `shock` tinyint(1) unsigned DEFAULT '1' COMMENT '震动',
  `lat` varchar(255) DEFAULT NULL COMMENT '纬度',
  `long` varchar(255) DEFAULT NULL COMMENT '经度',
  PRIMARY KEY (`id`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='司机表';

-- ----------------------------
-- Records of tz_driver
-- ----------------------------
INSERT INTO `tz_driver` VALUES ('1', '张三', '', '15651877405', '/uploads/image/1123.png', '1', '6978', null, '1556265687', '1', '1', '1', null, null);
INSERT INTO `tz_driver` VALUES ('2', '', '', '15651877404', '', '1', '0', null, null, '1', '1', '1', '', '');

-- ----------------------------
-- Table structure for tz_driver_order
-- ----------------------------
DROP TABLE IF EXISTS `tz_driver_order`;
CREATE TABLE `tz_driver_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(10) NOT NULL COMMENT '用户uid',
  `cate` varchar(255) DEFAULT '' COMMENT '司机类型',
  `begin_time` varchar(10) DEFAULT NULL COMMENT '预约时间',
  `address` varchar(255) DEFAULT '' COMMENT '预约地点',
  `username` varchar(255) DEFAULT '' COMMENT '预约人',
  `mobile` varchar(15) DEFAULT '' COMMENT '手机号',
  `content` varchar(255) DEFAULT '' COMMENT '备注',
  `pay_status` tinyint(1) DEFAULT NULL COMMENT '支付方式1支付宝2微信3余额',
  `pay_time` int(10) DEFAULT NULL COMMENT '支付时间',
  `use_time` varchar(255) DEFAULT NULL COMMENT '施工时长',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `area` varchar(255) DEFAULT NULL COMMENT '区域',
  `long` varchar(255) DEFAULT NULL COMMENT '经度',
  `lat` varchar(255) DEFAULT NULL COMMENT '纬度',
  `is_ban` tinyint(1) DEFAULT '0' COMMENT '是否选择余额',
  `balance` int(10) DEFAULT NULL COMMENT '余额支付金额',
  `driver_id` int(10) DEFAULT NULL COMMENT '接单司机uid',
  `driver_name` varchar(255) DEFAULT '' COMMENT '接单司机姓名',
  `driver_mobile` varchar(15) DEFAULT '' COMMENT '接单司机电话',
  `refusal_ids` varchar(255) DEFAULT NULL COMMENT '拒单司机id',
  `price` int(10) DEFAULT NULL COMMENT '单价',
  `trans_amount` int(10) DEFAULT NULL COMMENT '运费',
  `amount` int(10) DEFAULT NULL COMMENT '工时费',
  `order_sn` varchar(255) DEFAULT NULL COMMENT '订单号',
  `distance` varchar(255) DEFAULT '' COMMENT '距离',
  `type` tinyint(1) DEFAULT '2' COMMENT '类型1租车2找司机3维修保养',
  `front_amount` int(10) DEFAULT NULL COMMENT '定金',
  `status` tinyint(3) DEFAULT '-1' COMMENT '状态''-2：已取消-1：未付款 0已付款未分配 1等待接单（接单）  2已接单待出发（待出发）  3带施工 4带完工 5已完工'',',
  `create_time` int(10) DEFAULT NULL COMMENT '下单时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='司机订单';

-- ----------------------------
-- Records of tz_driver_order
-- ----------------------------
INSERT INTO `tz_driver_order` VALUES ('1', '1', '挖掘机', '03月03日周日09', '江苏省南京市江宁区绿地之窗', '蔡佳文', '15190663300', '备注', '1', null, '一月23天2小时30分', '南京', '江宁区', '32.1235', '118.1234', '0', null, '1', '', '', null, '8000', '30000', '3000000', '2019042850491015', '', '2', '10000', '-1', '1556432050', '1556432050');
INSERT INTO `tz_driver_order` VALUES ('2', '1', '挖掘机', '03月03日周日09', '江苏省南京市江宁区绿地之窗', '蔡佳文', '15190663300', '备注', '1', null, '一月23天2小时30分', '南京', '江宁区', '32.1235', '118.1234', '0', null, '1', '', '', null, '8000', '30000', '3000000', '2019042850491016', '', '3', '10000', '1', '1556432050', '1556432050');
INSERT INTO `tz_driver_order` VALUES ('3', '1', '挖掘机', '03月03日周日09', '江苏省南京市江宁区绿地之窗', '蔡佳文', '15190663300', '备注', '1', null, '一月23天2小时30分', '南京', '江宁区', '32.1235', '118.1234', '0', null, '2', '', '', null, '8000', '30000', '3000000', '2019042850491017', '36.4', '1', '10000', '-3', '1556432050', '1556518896');
INSERT INTO `tz_driver_order` VALUES ('4', '1', '挖掘机', '03月03日周日09', '江苏省南京市雨花台区绿地之窗C4', '张三', '15190663333', '', '1', null, null, '南京市', '江宁区', '33.125', '118.332458', '1', null, null, '', '', null, null, null, null, '2019042955499997', '', '2', '1000000', '-1', '1556527655', '1556527655');
INSERT INTO `tz_driver_order` VALUES ('5', '1', '挖掘机', '03月03日周日09', '江苏省南京市雨花台区绿地之窗C4', '张三', '15190663333', '', '1', null, null, '南京市', '江宁区', '33.125', '118.332458', '1', null, null, '', '', null, null, null, null, '2019042949971001', '', '2', '1000000', '-1', '1556527665', '1556527665');
INSERT INTO `tz_driver_order` VALUES ('6', '2', '挖掘机', '03月03日周日09', '江苏省南京市雨花台区绿地之窗C4', '李四', '15190663333', '', '1', null, null, '南京市', '江宁区', '33.125', '118.332458', '1', null, null, '', '', null, null, null, null, '2019042949501005', '', '2', '1000000', '-1', '1556530161', '1556530161');

-- ----------------------------
-- Table structure for tz_driver_type
-- ----------------------------
DROP TABLE IF EXISTS `tz_driver_type`;
CREATE TABLE `tz_driver_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(255) DEFAULT '' COMMENT '类型名称',
  `create_time` int(10) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='司机类型表';

-- ----------------------------
-- Records of tz_driver_type
-- ----------------------------
INSERT INTO `tz_driver_type` VALUES ('1', '挖掘机', null, null);
INSERT INTO `tz_driver_type` VALUES ('2', '塔吊', null, null);
INSERT INTO `tz_driver_type` VALUES ('3', '拖拉机', null, null);

-- ----------------------------
-- Table structure for tz_feedback
-- ----------------------------
DROP TABLE IF EXISTS `tz_feedback`;
CREATE TABLE `tz_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增',
  `uid` int(10) unsigned NOT NULL COMMENT 'uid',
  `type` varchar(255) DEFAULT '' COMMENT '反馈类型',
  `content` varchar(255) DEFAULT '' COMMENT '反馈内容',
  `img` varchar(255) DEFAULT '' COMMENT '图片',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `create_time` int(10) DEFAULT NULL COMMENT '反馈时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='反馈表';

-- ----------------------------
-- Records of tz_feedback
-- ----------------------------
INSERT INTO `tz_feedback` VALUES ('1', '4', '2', '测试7', '[\"\\/uploads\\/image\\/1123.png\"]', '0', '1556261403', '1556261403');
INSERT INTO `tz_feedback` VALUES ('2', '4', '\'\'', '测试7', '[\"\\/uploads\\/image\\/1123.png\"]', '0', '1556261476', '1556261476');
INSERT INTO `tz_feedback` VALUES ('3', '4', 'null ', '测试7', '[\"\\/uploads\\/image\\/1123.png\"]', '0', '1556261481', '1556261481');

-- ----------------------------
-- Table structure for tz_goods_evaluate
-- ----------------------------
DROP TABLE IF EXISTS `tz_goods_evaluate`;
CREATE TABLE `tz_goods_evaluate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `goods_id` int(10) DEFAULT NULL,
  `u_logo` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `u_name` varchar(50) DEFAULT NULL COMMENT '用户姓名',
  `content` text COMMENT '评价内容',
  `star` tinyint(3) DEFAULT '0',
  `date` int(11) DEFAULT NULL COMMENT '时间',
  `img1` varchar(255) DEFAULT NULL,
  `img2` varchar(255) DEFAULT NULL,
  `img3` varchar(255) DEFAULT NULL,
  `type` int(10) DEFAULT NULL COMMENT '1:商城商品  2：积分商品',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='商品评价表';

-- ----------------------------
-- Records of tz_goods_evaluate
-- ----------------------------
INSERT INTO `tz_goods_evaluate` VALUES ('28', '2', '21', 'dasdas', 'dasdas', '很好', '5', '2019', '11', null, null, '1');
INSERT INTO `tz_goods_evaluate` VALUES ('29', '2', '24', 'dasdas', 'dasdas', '很好', '5', '2019', '11', null, null, '1');
INSERT INTO `tz_goods_evaluate` VALUES ('30', '2', '23', 'dasdas', 'dasdas', '很好', '5', '2019', '11', null, null, '2');
INSERT INTO `tz_goods_evaluate` VALUES ('31', '2', '21', 'dasdas', 'dasdas', '很好', '5', '2019', '11', null, null, '2');

-- ----------------------------
-- Table structure for tz_link
-- ----------------------------
DROP TABLE IF EXISTS `tz_link`;
CREATE TABLE `tz_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `create_time` int(10) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_link
-- ----------------------------
INSERT INTO `tz_link` VALUES ('1', '客服工作时间', '9:00-18:00', null, null);

-- ----------------------------
-- Table structure for tz_rent_evaluate
-- ----------------------------
DROP TABLE IF EXISTS `tz_rent_evaluate`;
CREATE TABLE `tz_rent_evaluate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `rent_id` int(10) DEFAULT NULL,
  `u_avatar` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `u_name` varchar(50) DEFAULT NULL COMMENT '用户姓名',
  `content` text COMMENT '评价内容',
  `star` tinyint(3) DEFAULT '0',
  `date` int(11) DEFAULT NULL COMMENT '时间',
  `img1` varchar(255) DEFAULT NULL,
  `img2` varchar(255) DEFAULT NULL,
  `img3` varchar(255) DEFAULT NULL,
  `driver_id` int(10) DEFAULT NULL COMMENT '司机用户uid',
  `driver_name` varchar(255) DEFAULT NULL COMMENT '司机头像',
  `driver_logo` varchar(255) DEFAULT NULL COMMENT '司机头像',
  `driver_star` int(10) DEFAULT NULL COMMENT '司机评星',
  `driver_label` varchar(255) DEFAULT NULL COMMENT '司机标签 ',
  `type` tinyint(1) DEFAULT NULL COMMENT '订单类型1租车2司机3维修保养',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='租车评价表';

-- ----------------------------
-- Records of tz_rent_evaluate
-- ----------------------------
INSERT INTO `tz_rent_evaluate` VALUES ('28', '2', '1', '/uploads/image/12.png', 'dasdas', '很好', '5', '1156219870', '11', null, null, '1', null, null, null, null, '1');
INSERT INTO `tz_rent_evaluate` VALUES ('29', '2', '2', '/uploads/image/12.png', 'dasdas', '很好', '4', '1156219870', '11', null, null, '1', null, null, null, null, '1');
INSERT INTO `tz_rent_evaluate` VALUES ('30', '2', '1', '/uploads/image/12.png', 'dasdas', '很好', '5', '1156219870', '11', null, null, '2', null, null, null, null, '2');
INSERT INTO `tz_rent_evaluate` VALUES ('31', '2', '1', '/uploads/image/12.png', 'dasdas', '很好', '5', '1156219870', '11', null, null, '1', null, null, null, null, '2');

-- ----------------------------
-- Table structure for tz_rental
-- ----------------------------
DROP TABLE IF EXISTS `tz_rental`;
CREATE TABLE `tz_rental` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `author` varchar(255) DEFAULT NULL COMMENT '作者',
  `img` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `content` text,
  `status` tinyint(1) DEFAULT '1' COMMENT '0:禁用1:正常',
  `create_time` int(10) DEFAULT NULL COMMENT 'a时间',
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='租车指南表';

-- ----------------------------
-- Records of tz_rental
-- ----------------------------
INSERT INTO `tz_rental` VALUES ('1', '标题标题标题标题', '作者', '/uploads/image/11.png', '内容内容内容内容内容内容', '1', '1556029522', null);
INSERT INTO `tz_rental` VALUES ('2', '标题标题标题标题标题', '作者', '/uploads/image/11.png', '内容内容内容内容内容内容内容', '1', '1556029522', null);
INSERT INTO `tz_rental` VALUES ('3', '标题标题标题标题标题标题', '作者', '/uploads/image/11.png', '内容内容内容内容内容内容内容', '1', '1556029522', null);
INSERT INTO `tz_rental` VALUES ('4', '标题标题标题标题', '作者', '/uploads/image/11.png', '内容内容内容内容内容内容内容内容', '1', '1556029522', null);

-- ----------------------------
-- Table structure for tz_rentorder
-- ----------------------------
DROP TABLE IF EXISTS `tz_rentorder`;
CREATE TABLE `tz_rentorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `product_id` int(11) DEFAULT NULL COMMENT '产品id',
  `product_name` varchar(50) DEFAULT NULL COMMENT '产品名称',
  `product_amount` int(11) DEFAULT NULL COMMENT '金额',
  `parts` varchar(255) DEFAULT NULL COMMENT '配件集合',
  `status` int(5) DEFAULT '-1' COMMENT '-2：已取消-1：未付款 0已付款未分配 1已分配  2带出发  3带施工 4带完工 5已完工',
  `order_sn` varchar(50) DEFAULT NULL COMMENT '订单编号',
  `pay_time` int(11) DEFAULT NULL COMMENT '支付时间',
  `start_time` int(11) DEFAULT NULL COMMENT '下单时间',
  `parts_id` varchar(50) DEFAULT NULL COMMENT '配件id，多个以12,13,14',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型1租车2司机3维修保养',
  `img` varchar(50) DEFAULT NULL COMMENT '图片名称',
  `begin_time` int(11) DEFAULT NULL COMMENT '开工时间',
  `hours` decimal(10,1) DEFAULT '0.0' COMMENT '施工小时数',
  `time` varchar(50) DEFAULT NULL COMMENT '施工时长',
  `rent_amount` int(11) DEFAULT NULL COMMENT '租车费用',
  `trans_amount` int(11) DEFAULT NULL COMMENT '运费',
  `prefer_amount` int(11) DEFAULT '0' COMMENT '优惠',
  `discount_amount` int(11) DEFAULT NULL COMMENT '折扣',
  `real_amount` int(11) DEFAULT NULL COMMENT '实际应付金额',
  `uname` varchar(50) DEFAULT NULL COMMENT '用户名称',
  `uavatar` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `umobile` varchar(50) DEFAULT NULL COMMENT '用户手机号',
  `uaddr` varchar(255) DEFAULT NULL COMMENT '用户地址',
  `content` text COMMENT '备注',
  `rentcart` varchar(50) DEFAULT NULL COMMENT '车型',
  `driver_id` int(10) DEFAULT NULL COMMENT '司机id',
  `pay_status` int(10) DEFAULT NULL COMMENT '支付类型  1余额  2支付宝  3微信',
  `distance` varchar(25) DEFAULT NULL COMMENT '距离',
  `long` varchar(255) DEFAULT NULL COMMENT '经度',
  `alt` varchar(255) DEFAULT NULL COMMENT '纬度',
  `refusal_ids` varchar(255) DEFAULT '' COMMENT '拒单司机用户uid',
  `acce_time` int(10) DEFAULT '0' COMMENT '司机接单或拒绝时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_rentorder
-- ----------------------------
INSERT INTO `tz_rentorder` VALUES ('1', '1', '1', '产品名称1', '10000', '配件一,配件二', '5', '201904241532123593', '1556073319', '1556074319', '1,2', '1', '/uploads/image/111.png', '1556079319', '3.5', '1月3天3时', '310975', '10000', '10000', '30000', '108800', '张三', '/uploads/image/11.png', '15190665204', '江苏省南京市雨花台区绿地之窗', '备注', '铲车', '1', '1', '8.6', '23.777777', '108.33355', '', '1556178097', '1556106318');
INSERT INTO `tz_rentorder` VALUES ('2', '1', '1', '产品名称2', '10000', '配件一,配件二', '2', '201904241532123594', '1556073319', '1556074319', '1,2', '1', '/uploads/image/111.png', '1556079319', '2.2', '1月3天3时', '138800', '10000', '10000', '30000', '108800', '张三', '/uploads/image/11.png', '15190665204', '江苏省南京市雨花台区绿地之窗', '备注', '铲车', '1', '1', '8.6', '23.777777', '108.33355', '', '1556178097', null);
INSERT INTO `tz_rentorder` VALUES ('3', '1', '1', '产品名称2', '10000', '配件一,配件二', '3', '201904241532123594', '1556073319', '1556074319', '1,2', '1', '/uploads/image/111.png', '1556079319', '1.0', '1月3天3时', '138800', '10000', '10000', '30000', '108800', '张三', '/uploads/image/11.png', '15190665204', '江苏省南京市雨花台区绿地之窗', '备注', '铲车', '1', '1', '8.6', '23.777777', '108.33355', '', '1556108097', null);
INSERT INTO `tz_rentorder` VALUES ('4', '1', '1', '产品名称2', '10000', '配件一,配件二', '4', '201904241532123594', '1556073319', '1556074319', '1,2', '1', '/uploads/image/111.png', '1556079319', '2.0', '1月3天3时', '138800', '10000', '10000', '30000', '108800', '张三', '/uploads/image/11.png', '15190665204', '江苏省南京市雨花台区绿地之窗', '备注', '铲车', '1', '1', '8.6', '23.777777', '108.33355', '', '1556170097', null);
INSERT INTO `tz_rentorder` VALUES ('5', '1', '1', '产品名称2', '10000', '配件一,配件二', '5', '201904241532123594', '1556073319', '1556074319', '1,2', '1', '/uploads/image/111.png', '1556079319', '3.0', '1月3天3时', '138800', '10000', '10000', '30000', '108800', '张三', '/uploads/image/11.png', '15190665204', '江苏省南京市雨花台区绿地之窗', '备注', '铲车', '1', '1', '8.6', '23.777777', '108.33355', '', '1556178097', null);
INSERT INTO `tz_rentorder` VALUES ('6', '1', '1', '产品名称2', '10000', '配件一,配件二', '1', '201904241532123594', '1556073319', '1556074319', '1,2', '1', '/uploads/image/111.png', '1556079319', '4.0', '1月3天3时', '138800', '10000', '10000', '30000', '108800', '张三', '/uploads/image/11.png', '15190665204', '江苏省南京市雨花台区绿地之窗', '备注', '铲车', '1', '1', '8.6', '23.777777', '108.33355', '', '1556170097', null);

-- ----------------------------
-- Table structure for tz_rentot
-- ----------------------------
DROP TABLE IF EXISTS `tz_rentot`;
CREATE TABLE `tz_rentot` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '1:加时 2：减时',
  `type` int(10) DEFAULT NULL COMMENT '1加时2减少',
  `order_sn` varchar(255) DEFAULT NULL COMMENT '订单标号',
  `order_id` int(10) DEFAULT NULL COMMENT '订单id',
  `order_type` tinyint(1) DEFAULT NULL COMMENT '订单类型1租车2司机3维修保养',
  `status` int(10) DEFAULT '0' COMMENT '0未支付1已支付',
  `time` varchar(25) DEFAULT NULL COMMENT '时间',
  `amount` int(11) DEFAULT NULL COMMENT '金额',
  `need_amount` int(11) DEFAULT NULL COMMENT '应付金额',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_rentot
-- ----------------------------
INSERT INTO `tz_rentot` VALUES ('1', '1', '201904241532123593', '1', '1', '0', '1.2', '106620', '120000', '1556102753', '1556106048');
INSERT INTO `tz_rentot` VALUES ('2', '1', '201904241532123593', '1', '2', '0', '1.2', '106620', '120000', '1556102753', '1556106048');
INSERT INTO `tz_rentot` VALUES ('3', '2', '201904241532123593', '2', '3', '1', '1.5', '133275', null, '1556242766', '1556242766');
INSERT INTO `tz_rentot` VALUES ('5', '2', '201904241532123593', '1', '2', '1', '1.5', '133275', null, '1556246373', '1556246373');
INSERT INTO `tz_rentot` VALUES ('6', '2', '201904241532123593', '1', '3', '1', '1.5', '133275', null, '1556246972', '1556246972');

-- ----------------------------
-- Table structure for tz_sms
-- ----------------------------
DROP TABLE IF EXISTS `tz_sms`;
CREATE TABLE `tz_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `createtime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='短信验证码表';

-- ----------------------------
-- Records of tz_sms
-- ----------------------------
INSERT INTO `tz_sms` VALUES ('1', '15190665204', '1234', '1', '1556029522');

-- ----------------------------
-- Table structure for tz_tixian
-- ----------------------------
DROP TABLE IF EXISTS `tz_tixian`;
CREATE TABLE `tz_tixian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL COMMENT '用户uid',
  `type` tinyint(1) DEFAULT NULL COMMENT '提现类型',
  `amount` int(10) DEFAULT NULL COMMENT '提现金额',
  `status` tinyint(1) DEFAULT '0' COMMENT '提现状态',
  `desc` varchar(255) DEFAULT '' COMMENT '审核原因',
  `create_time` int(10) DEFAULT NULL COMMENT '提现时间',
  `update_time` int(10) DEFAULT NULL COMMENT '审核时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='提现表';

-- ----------------------------
-- Records of tz_tixian
-- ----------------------------
INSERT INTO `tz_tixian` VALUES ('1', '1', '1', '3022', '0', '', '1556343780', '1556343780');

-- ----------------------------
-- Table structure for tz_user
-- ----------------------------
DROP TABLE IF EXISTS `tz_user`;
CREATE TABLE `tz_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '组别ID',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `bio` varchar(100) NOT NULL DEFAULT '' COMMENT '格言',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '余额',
  `score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `successions` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '连续登录天数',
  `maxsuccessions` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '最大连续登录天数',
  `prevtime` int(10) DEFAULT NULL COMMENT '上次登录时间',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) NOT NULL DEFAULT '' COMMENT '登录IP',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `joinip` varchar(50) NOT NULL DEFAULT '' COMMENT '加入IP',
  `jointime` int(10) DEFAULT NULL COMMENT '加入时间',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'Token',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  `verification` varchar(255) NOT NULL DEFAULT '' COMMENT '验证',
  `is_send` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '震动',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户表';

-- ----------------------------
-- Records of tz_user
-- ----------------------------
INSERT INTO `tz_user` VALUES ('1', '1', '蔡佳文', 'admin', 'c13f62012fd6a8fdf06b3452a94430e5', 'rpR6Bv', 'admin@163.com', '13888888888', '', '0', '0', '2017-04-15', '', '399825', '0', '1', '1', '1516170492', '1516171614', '127.0.0.1', '0', '127.0.0.1', '1491461418', '0', '1516171614', '', 'normal', '', '1');
INSERT INTO `tz_user` VALUES ('2', '0', '', '', 'fcea920f7412b5da7be0cf42b8c93759', '', '', '15190665204', '', '0', '0', null, '', '0', '0', '1', '1', null, null, '', '0', '', null, '1556011582', '1556012297', '', '', '', '1');
