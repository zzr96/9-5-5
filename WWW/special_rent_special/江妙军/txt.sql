/*
Navicat MySQL Data Transfer
Source Host     : localhost:3306
Source Database : txt
Target Host     : localhost:3306
Target Database : txt
Date: 2019-04-29 19:34:16
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for tz_about
-- ----------------------------
DROP TABLE IF EXISTS `tz_about`;
CREATE TABLE `tz_about` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service` text COMMENT '服务协议',
  `privacy` text COMMENT '隐私协议',
  `copyright` text COMMENT '版权',
  `hotline` varchar(15) DEFAULT NULL COMMENT '热线',
  `qq` varchar(15) DEFAULT NULL COMMENT 'qq',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `createtime` int(12) DEFAULT NULL,
  `updatetime` int(12) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `freight` int(10) DEFAULT NULL COMMENT '运费点',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='关于/联系我们';

-- ----------------------------
-- Records of tz_about
-- ----------------------------
INSERT INTO `tz_about` VALUES ('1', null, null, 'dasdas', null, null, null, null, null, 'sdasd', '1000');

-- ----------------------------
-- Table structure for tz_cart
-- ----------------------------
DROP TABLE IF EXISTS `tz_cart`;
CREATE TABLE `tz_cart` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '购物车ID',
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `goods_size_id` int(10) DEFAULT NULL COMMENT '商品组合id',
  `goods_img` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `goods_name` varchar(100) DEFAULT NULL COMMENT '商品姓名',
  `spec` varchar(50) DEFAULT NULL COMMENT '规格',
  `color` varchar(50) DEFAULT NULL COMMENT '颜色',
  `num` int(10) NOT NULL COMMENT '购买数量',
  `amount` int(10) NOT NULL COMMENT '价格',
  `date` datetime DEFAULT NULL,
  `goods_id` int(10) DEFAULT NULL COMMENT '商品id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_cart
-- ----------------------------
INSERT INTO `tz_cart` VALUES ('43', '2', '32', '/uploads/image/20190413/5cb148c89faf2.jpg', '爽肤水', '3', '2', '12', '50', '2019-04-13 10:33:30', null);
INSERT INTO `tz_cart` VALUES ('47', '2', '31', '/uploads/image/20190413/5cb148752c027.jpg', '冰毒', '1', '1', '5', '60', '2019-04-26 15:23:15', '21');

-- ----------------------------
-- Table structure for tz_category
-- ----------------------------
DROP TABLE IF EXISTS `tz_category`;
CREATE TABLE `tz_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `image` varchar(180) NOT NULL COMMENT '图片',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='商品分类';

-- ----------------------------
-- Records of tz_category
-- ----------------------------
INSERT INTO `tz_category` VALUES ('4', '0', '电子产品', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/509af801726984aaa359b4bf249f5716.png', '4', '1540367287', '1541402940');
INSERT INTO `tz_category` VALUES ('5', '4', '手机', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/4fffea1c27bfb8df655a39114bb05814.jpg', '5', '1540367298', '1541402932');
INSERT INTO `tz_category` VALUES ('6', '0', '水果', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/c83a0019dfa7a768037e98f02b70efd5.jpg', '6', '1540367311', '1541403647');
INSERT INTO `tz_category` VALUES ('7', '6', '进口水果', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/a460ffdbd534b10cdf40487189ccb6b7.jpg', '7', '1540367326', '1541403531');
INSERT INTO `tz_category` VALUES ('8', '4', '笔记本', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/efb53c4c7814c83aa3c21e0fd408b5df.jpg', '8', '1541402921', '1541403316');
INSERT INTO `tz_category` VALUES ('9', '6', '国产水果', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/753cd25e97135e874dca8ab5126ad144.jpg', '9', '1541403546', '1541403622');
INSERT INTO `tz_category` VALUES ('10', '5', '诺基亚', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/4fffea1c27bfb8df655a39114bb05814.jpg', '22', '1541403546', '1541403546');

-- ----------------------------
-- Table structure for tz_chat_message
-- ----------------------------
DROP TABLE IF EXISTS `tz_chat_message`;
CREATE TABLE `tz_chat_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `form_uid` int(10) unsigned NOT NULL COMMENT '消息发送者',
  `other_id` int(10) unsigned NOT NULL COMMENT '消息接受者',
  `show_time` tinyint(1) DEFAULT '1' COMMENT '是否显示时间',
  `content` text COMMENT '发送内容',
  `duration` varchar(255) DEFAULT NULL COMMENT '语音时长',
  `msg_type` tinyint(1) DEFAULT '1' COMMENT '消息类型1文本2语音3视频',
  `sendtime` int(10) DEFAULT NULL COMMENT '发送时间',
  `readtime` int(10) DEFAULT NULL COMMENT '读取时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息表';

-- ----------------------------
-- Records of tz_chat_message
-- ----------------------------

-- ----------------------------
-- Table structure for tz_collect
-- ----------------------------
DROP TABLE IF EXISTS `tz_collect`;
CREATE TABLE `tz_collect` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(10) unsigned DEFAULT NULL COMMENT '用户id',
  `itemid` int(10) unsigned DEFAULT NULL COMMENT '帖子id',
  `createtime` datetime DEFAULT NULL COMMENT '收藏时间',
  `updatetime` datetime DEFAULT NULL COMMENT '更新时间',
  `type` int(10) DEFAULT NULL COMMENT '1:车型收藏 2：商城 3：积分商场',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='收藏商品表';

-- ----------------------------
-- Records of tz_collect
-- ----------------------------
INSERT INTO `tz_collect` VALUES ('7', '1', '1', '2019-04-25 03:24:06', null, '1');

-- ----------------------------
-- Table structure for tz_disvolume
-- ----------------------------
DROP TABLE IF EXISTS `tz_disvolume`;
CREATE TABLE `tz_disvolume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(11) DEFAULT NULL COMMENT '折扣卷名称',
  `amount` int(11) DEFAULT NULL COMMENT '多少金额满减',
  `real_amount` int(11) DEFAULT NULL COMMENT '减满多少',
  `time` int(11) DEFAULT NULL COMMENT '至截时间',
  `num` int(11) DEFAULT NULL COMMENT '少张多',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_disvolume
-- ----------------------------
INSERT INTO `tz_disvolume` VALUES ('1', '全场通用折扣卷', '888', '88', '1556269442', '100');
INSERT INTO `tz_disvolume` VALUES ('2', '全场通用折扣卷', '785', '58', '1556269442', '50');
INSERT INTO `tz_disvolume` VALUES ('3', '全场通用折扣卷', '888', '66', '1556269442', '30');

-- ----------------------------
-- Table structure for tz_feedback
-- ----------------------------
DROP TABLE IF EXISTS `tz_feedback`;
CREATE TABLE `tz_feedback` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mark` varchar(255) DEFAULT NULL COMMENT '类型',
  `content` varchar(255) DEFAULT NULL COMMENT '内容',
  `img` varchar(255) DEFAULT NULL COMMENT '图片',
  `uid` int(10) DEFAULT NULL COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_feedback
-- ----------------------------
INSERT INTO `tz_feedback` VALUES ('1', 'adsada', '22222dsdasdasdas', 'uploads/image/20190423/419f962add5e7b63168e09d51c1c66a8.jpg', '2');

-- ----------------------------
-- Table structure for tz_goods
-- ----------------------------
DROP TABLE IF EXISTS `tz_goods`;
CREATE TABLE `tz_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) DEFAULT NULL COMMENT '商城主分类ID',
  `goods_name` varchar(50) DEFAULT NULL COMMENT '商品名字',
  `amount` int(10) DEFAULT NULL COMMENT '价格',
  `sn` varchar(50) DEFAULT NULL COMMENT '商品编号',
  `postage` int(5) DEFAULT '0' COMMENT '0：免邮费邮费',
  `img1` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img2` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img3` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img4` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `content` varchar(255) DEFAULT NULL COMMENT '商品详情',
  `sales` int(20) DEFAULT '0' COMMENT '销量',
  `collect_num` int(20) DEFAULT '0' COMMENT '收藏人数',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否上架1:是2:否',
  `dateline` int(10) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='商品列表';

-- ----------------------------
-- Records of tz_goods
-- ----------------------------
INSERT INTO `tz_goods` VALUES ('21', '1', '冰毒', '50', null, '0', '/uploads/image/20190413/5cb148752c027.jpg', '', '', '', '尔撒大三大大声大声道', '2', '0', '1', '1555122301');
INSERT INTO `tz_goods` VALUES ('22', '2', '海洛因', '50', null, '0', '/uploads/image/20190413/5cb1489845004.jpg', '', '', '', '&nbsp;dasdasdasdas', '1', '0', '1', '1555122333');
INSERT INTO `tz_goods` VALUES ('23', '1', '爽肤水', '50', null, '0', '/uploads/image/20190413/5cb148c89faf2.jpg', '', '', '', '问我其二完全', '0', '0', '1', '1555122380');
INSERT INTO `tz_goods` VALUES ('24', '1', '发生发射点发射点111', '60', null, '0', '/uploads/image/20190413/5cb148e7005ed.jpg', '', '', '', '分身乏术的', '2', '0', '1', '1555122411');
INSERT INTO `tz_goods` VALUES ('25', '2', '打算倒萨', '20', null, '0', '/uploads/image/20190413/5cb14909326c4.jpg', '', '', '', '按时大撒打算', '3', '0', '1', '1555122447');
INSERT INTO `tz_goods` VALUES ('26', '1', '包子', '80', null, '0', '/uploads/image/20190413/5cb1492eceaab.jpg', '', '', '', '1111', '4', '0', '1', '1555122481');

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
-- Table structure for tz_goods_size
-- ----------------------------
DROP TABLE IF EXISTS `tz_goods_size`;
CREATE TABLE `tz_goods_size` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) DEFAULT NULL COMMENT '商品ID',
  `color` varchar(50) DEFAULT NULL COMMENT '颜色',
  `spec` varchar(50) DEFAULT NULL COMMENT '规格',
  `fprice` decimal(10,2) DEFAULT NULL COMMENT '组合价格',
  `total` int(10) DEFAULT NULL COMMENT '库存',
  `dateline` int(11) DEFAULT NULL COMMENT '时间',
  `goods_size` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='商品规格表';

-- ----------------------------
-- Records of tz_goods_size
-- ----------------------------
INSERT INTO `tz_goods_size` VALUES ('31', '21', '1', '1', '60.00', '20', '2019', null);
INSERT INTO `tz_goods_size` VALUES ('32', '21', '2', '3', '0.00', '30', '2019', null);
INSERT INTO `tz_goods_size` VALUES ('33', '24', '1', '1', '20.00', '3', '2019', null);
INSERT INTO `tz_goods_size` VALUES ('35', '26', '1', '1', '1.00', '1', '2019', null);

-- ----------------------------
-- Table structure for tz_integrate_goods
-- ----------------------------
DROP TABLE IF EXISTS `tz_integrate_goods`;
CREATE TABLE `tz_integrate_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(50) DEFAULT NULL COMMENT '商品名字',
  `amount` int(10) DEFAULT NULL COMMENT '价格',
  `sn` varchar(50) DEFAULT NULL COMMENT '商品编号',
  `postage` int(5) DEFAULT '0' COMMENT '0：免邮费邮费',
  `img1` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img2` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img3` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img4` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `content` varchar(255) DEFAULT NULL COMMENT '商品详情',
  `sales` int(20) DEFAULT '0' COMMENT '销量',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否上架1:是2:否',
  `dateline` int(10) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='商品列表';

-- ----------------------------
-- Records of tz_integrate_goods
-- ----------------------------
INSERT INTO `tz_integrate_goods` VALUES ('21', '冰毒', '50', null, '0', '/uploads/image/20190413/5cb148752c027.jpg', '', '', '', '尔撒大三大大声大声道', '2', '1', '1555122301');
INSERT INTO `tz_integrate_goods` VALUES ('22', '海洛因', '50', null, '0', '/uploads/image/20190413/5cb1489845004.jpg', '', '', '', '&nbsp;dasdasdasdas', '1', '1', '1555122333');
INSERT INTO `tz_integrate_goods` VALUES ('23', '爽肤水', '50', null, '0', '/uploads/image/20190413/5cb148c89faf2.jpg', '', '', '', '问我其二完全', '0', '1', '1555122380');
INSERT INTO `tz_integrate_goods` VALUES ('24', '发生发射点发射点111', '60', null, '0', '/uploads/image/20190413/5cb148e7005ed.jpg', '', '', '', '分身乏术的', '2', '1', '1555122411');
INSERT INTO `tz_integrate_goods` VALUES ('25', '打算倒萨', '20', null, '0', '/uploads/image/20190413/5cb14909326c4.jpg', '', '', '', '按时大撒打算', '3', '1', '1555122447');
INSERT INTO `tz_integrate_goods` VALUES ('26', '包子', '80', null, '0', '/uploads/image/20190413/5cb1492eceaab.jpg', '', '', '', '1111', '4', '1', '1555122481');

-- ----------------------------
-- Table structure for tz_litestore_adress
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_adress`;
CREATE TABLE `tz_litestore_adress` (
  `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `province_id` int(11) unsigned NOT NULL DEFAULT '0',
  `city_id` int(11) unsigned NOT NULL DEFAULT '0',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0',
  `detail` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `isdefault` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否默认:0=非默认,1=默认地址',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_adress
-- ----------------------------

-- ----------------------------
-- Table structure for tz_litestore_category
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_category`;
CREATE TABLE `tz_litestore_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `image` varchar(180) NOT NULL COMMENT '图片',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='商品分类';

-- ----------------------------
-- Records of tz_litestore_category
-- ----------------------------
INSERT INTO `tz_litestore_category` VALUES ('4', '0', '电子产品', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/509af801726984aaa359b4bf249f5716.png', '4', '1540367287', '1541402940');
INSERT INTO `tz_litestore_category` VALUES ('5', '4', '手机', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/4fffea1c27bfb8df655a39114bb05814.jpg', '5', '1540367298', '1541402932');
INSERT INTO `tz_litestore_category` VALUES ('6', '0', '水果', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/c83a0019dfa7a768037e98f02b70efd5.jpg', '6', '1540367311', '1541403647');
INSERT INTO `tz_litestore_category` VALUES ('7', '6', '进口水果', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/a460ffdbd534b10cdf40487189ccb6b7.jpg', '7', '1540367326', '1541403531');
INSERT INTO `tz_litestore_category` VALUES ('8', '4', '笔记本', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/efb53c4c7814c83aa3c21e0fd408b5df.jpg', '8', '1541402921', '1541403316');
INSERT INTO `tz_litestore_category` VALUES ('9', '6', '国产水果', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/753cd25e97135e874dca8ab5126ad144.jpg', '9', '1541403546', '1541403622');

-- ----------------------------
-- Table structure for tz_litestore_freight
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_freight`;
CREATE TABLE `tz_litestore_freight` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '运费模版名称',
  `method` enum('10','20') NOT NULL DEFAULT '10' COMMENT '计费方式:10=按件数,20=按重量',
  `weigh` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权重',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_freight
-- ----------------------------
INSERT INTO `tz_litestore_freight` VALUES ('22', '手机', '10', '22', '1540288755', '1540288755');
INSERT INTO `tz_litestore_freight` VALUES ('23', '电脑', '10', '23', '1540363605', '1540363605');
INSERT INTO `tz_litestore_freight` VALUES ('24', '水果', '20', '24', '1540363644', '1540363644');

-- ----------------------------
-- Table structure for tz_litestore_freight_rule
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_freight_rule`;
CREATE TABLE `tz_litestore_freight_rule` (
  `rule_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `litestore_freight_id` int(11) unsigned NOT NULL DEFAULT '0',
  `region` text NOT NULL,
  `first` double unsigned NOT NULL DEFAULT '0',
  `first_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `additional` double unsigned NOT NULL DEFAULT '0',
  `additional_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `weigh` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  `create_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_freight_rule
-- ----------------------------
INSERT INTO `tz_litestore_freight_rule` VALUES ('18', '23', '2,20,38,61,76,84,104,124,150,168,180,197,208,221,232,244,250,264,271,278,290,304,319,337,352,362,372,376,389,398,407,422,430,442,449,462,467,481,492,500,508,515,522,530,537,545,553,558,566,574,581,586,597,607,614,619,627,634,640,646,656,675,692,702,711,720,730,748,759,764,775,782,793,802,821,833,842,853,861,871,880,887,896,906,913,920,927,934,948,960,972,980,986,993,1003,1010,1015,1025,1035,1047,1057,1066,1074,1081,1088,1093,1098,1110,1118,1127,1136,1142,1150,1155,1160,1169,1183,1190,1196,1209,1222,1234,1245,1253,1264,1274,1279,1285,1299,1302,1306,1325,1339,1350,1362,1376,1387,1399,1408,1415,1421,1434,1447,1459,1466,1471,1476,1479,1492,1504,1513,1522,1533,1546,1556,1572,1583,1593,1599,1612,1623,1630,1637,1643,1650,1664,1674,1685,1696,1707,1710,1724,1731,1740,1754,1764,1768,1774,1782,1791,1802,1809,1813,1822,1828,1838,1848,1854,1867,1880,1890,1900,1905,1912,1924,1936,1949,1955,1965,1977,1988,1999,2003,2011,2017,2025,2035,2041,2050,2056,2065,2070,2077,2082,2091,2123,2146,2150,2156,2163,2177,2189,2207,2215,2220,2225,2230,2236,2245,2258,2264,2276,2283,2292,2297,2302,2306,2324,2363,2368,2388,2395,2401,2409,2416,2426,2434,2440,2446,2458,2468,2475,2486,2493,2501,2510,2516,2521,2535,2554,2573,2584,2589,2604,2611,2620,2631,2640,2657,2671,2686,2696,2706,2712,2724,2730,2741,2750,2761,2775,2784,2788,2801,2807,2812,2817,2826,2845,2857,2870,2882,2890,2899,2913,2918,2931,2946,2958,2972,2984,2997,3008,3016,3023,3032,3036,3039,3045,3053,3058,3065,3073,3081,3090,3098,3108,3117,3127,3135,3142,3147,3152,3158,3165,3172,3179,3186,3190,3196,3202,3207,3216,3221,3225,3229,3237,3242,3252,3262,3267,3280,3289,3301,3309,3317,3326,3339,3378,3386,3416,3454,3458,3461,3491,3504,3518,3532,3551,3578,3592,3613,3632,3666,3683,3697,3704,3711,3717,3722,3728,3739,3745,3747', '1', '20.00', '1', '10.00', '0', '1540363605');
INSERT INTO `tz_litestore_freight_rule` VALUES ('49', '24', '2,20,38,61,76,84,104,124,150,168,180,197,208,221,232,244,250,264,271,278,290,304,319,337,352,362,372,376,389,398,407,422,430,442,449,462,467,481,492,500,508,515,522,530,537,545,553,558,566,574,581,586,597,607,614,619,627,634,640,646,656,675,692,702,711,720,730,748,759,764,775,782,793,802,821,833,842,853,861,871,880,887,896,906,913,920,927,934,948,960,972,980,986,993,1003,1010,1015,1025,1035,1047,1057,1066,1074,1081,1088,1093,1098,1110,1118,1127,1136,1142,1150,1155,1160,1169,1183,1190,1196,1209,1222,1234,1245,1253,1264,1274,1279,1285,1299,1302,1306,1325,1339,1350,1362,1376,1387,1399,1408,1415,1421,1434,1447,1459,1466,1471,1476,1479,1492,1504,1513,1522,1533,1546,1556,1572,1583,1593,1599,1612,1623,1630,1637,1643,1650,1664,1674,1685,1696,1707,1710,1724,1731,1740,1754,1764,1768,1774,1782,1791,1802,1809,1813,1822,1828,1838,1848,1854,1867,1880,1890,1900,1905,1912,1924,1936,1949,1955,1965,1977,1988,1999,2003,2011,2017,2025,2035,2041,2050,2056,2065,2070,2077,2082,2091,2123,2146,2150,2156,2163,2177,2189,2207,2215,2220,2225,2230,2236,2245,2258,2264,2276,2283,2292,2297,2302,2306,2324,2363,2368,2388,2395,2401,2409,2416,2426,2434,2440,2446,2458,2468,2475,2486,2493,2501,2510,2516,2521,2535,2554,2573,2584,2589,2604,2611,2620,2631,2640,2657,2671,2686,2696,2706,2712,2724,2730,2741,2750,2761,2775,2784,2788,2801,2807,2812,2817,2826,2845,2857,2870,2882,2890,2899,2913,2918,2931,2946,2958,2972,2984,2997,3008,3016,3023,3032,3036,3039,3045,3053,3058,3065,3073,3081,3090,3098,3108,3117,3127,3135,3142,3147,3152,3158,3165,3172,3179,3186,3190,3196,3202,3207,3216,3221,3225,3229,3237,3242,3252,3262,3267,3280,3289,3301,3309,3317,3326,3339,3378,3386,3416,3454,3458,3461,3491,3504,3518,3532,3551,3578,3592,3613,3632,3666,3683,3697,3704,3711,3717,3722,3728,3739,3745,3747', '1', '10.00', '1', '8.00', '0', '1543387208');
INSERT INTO `tz_litestore_freight_rule` VALUES ('50', '22', '2,20,38,61,76,84,104,124,150,168,180,197,208,221,232,244,250,264,271,278,290,304,319,337,352,362,372,376,389,398,407,422,430,442,449,462,467,481,492,500,508,515,522,530,537,545,553,558,566,574,581,586,597,607,614,619,627,634,640,646,656,675,692,702,711,720,730,748,759,764,775,782,793,802,821,833,842,853,861,871,880,887,896,906,913,920,927,934,948,960,972,980,986,993,1003,1010,1015,1025,1035,1047,1057,1066,1074,1081,1088,1093,1098,1110,1118,1127,1136,1142,1150,1155,1160,1169,1183,1190,1196,1209,1222,1234,1245,1253,1264,1274,1279,1285,1299,1302,1306,1325,1339,1350,1362,1376,1387,1399,1408,1415,1421,1434,1447,1459,1466,1471,1476,1479,1492,1504,1513,1522,1533,1546,1556,1572,1583,1593,1599,1612,1623,1630,1637,1643,1650,1664,1674,1685,1696,1707,1710,1724,1731,1740,1754,1764,1768,1774,1782,1791,1802,1809,1813,1822,1828,1838,1848,1854,1867,1880,1890,1900,1905,1912,1924,1936,1949,1955,1965,1977,1988,1999,2003,2011,2017,2025,2035,2041,2050,2056,2065,2070,2077,2082,2091,2123,2146,2150,2156,2163,2177,2189,2207,2215,2220,2225,2230,2236,2245,2258,2264,2276,2283,2292,2297,2302,2306,2324,2363,2368,2388,2395,2401,2409,2416,2426,2434,2440,2446,2458,2468,2475,2486,2493,2501,2510,2516,2521,2535,2554,2573,2584,2589,2604,2611,2620,2631,2640,2657,2671,2686,2696,2706,2712,2724,2730,2741,2750,2761,2775,2784,2788,2801,2807,2812,2817,2826,2845,2857,2870,2882,2890,2899,2913,2918,2931,2946,2958,2972,2984,2997,3008,3016,3023,3032,3036,3039,3045,3053,3058,3065,3073,3081,3090,3098,3108,3117,3127,3135,3142,3147,3152,3158,3165,3172,3179,3186,3190,3196,3202,3207,3216,3221,3225,3229,3237,3242,3252,3262,3267,3280,3289,3301,3309,3317,3326,3339,3378,3386,3416,3454,3458,3461,3491,3504,3518,3532,3551,3578,3592,3613,3632,3666,3683,3697,3704,3711,3717,3722,3728,3739,3745,3747', '1', '10.00', '1', '5.00', '0', '1543387223');

-- ----------------------------
-- Table structure for tz_litestore_goods
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_goods`;
CREATE TABLE `tz_litestore_goods` (
  `goods_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品类别',
  `images` varchar(1800) NOT NULL COMMENT '商品图片',
  `spec_type` enum('10','20') NOT NULL DEFAULT '10' COMMENT '商品规格:10=单规格,20=多规格',
  `deduct_stock_type` enum('10','20') NOT NULL DEFAULT '20' COMMENT '库存计算方式:10=下单减库存,20=付款减库存',
  `content` text NOT NULL COMMENT '描述详情',
  `sales_initial` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '初始销量',
  `sales_actual` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '实际销量',
  `goods_sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '权重',
  `delivery_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '运费模板ID',
  `goods_status` enum('10','20') NOT NULL DEFAULT '10' COMMENT '商品状态:10=上架,20=下架',
  `is_delete` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否删除:0=未删除,1=已删除',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`goods_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_goods
-- ----------------------------
INSERT INTO `tz_litestore_goods` VALUES ('21', '小米Mix3', '5', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/ffc4440df18661948b9c2d4dd4ae419b.jpg,https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/83bf8f141969a9e3e607a768407fc7e0.jpg,https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/c5d85254fc17b1a1b0e2254470881e59.jpg', '20', '20', '<p><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/f5b49f703245ef61bb3faa574f32076d.jpg\" data-filename=\"filename\" style=\"width: 699px;\"><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/7d0fe135394408d4332386117c928003.jpg\" data-filename=\"filename\" style=\"width: 699px;\"><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/6a87fa6af95e39d7dc227f666d7b8ff6.jpg\" data-filename=\"filename\" style=\"width: 699px;\"><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/32d58a08cf92282c8f28078137c970f2.jpg\" data-filename=\"filename\" style=\"width: 699px;\"><br></p>', '20', '4', '21', '22', '10', '0', '1541401778', '1543221954');
INSERT INTO `tz_litestore_goods` VALUES ('22', 'Mate 20 华为 HUAWEI ', '5', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/b044b7bcd4930202fcd96d6b50453894.jpg,https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/4fffea1c27bfb8df655a39114bb05814.jpg,https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/e0d6dc822cf7632c66f7718bdd0dc2bc.jpg', '20', '20', '<p style=\"text-align: center; line-height: 1.6;\"><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/3f0233e227359137bb55152c0750f8a2.png\" data-filename=\"filename\" style=\"width: 603px;\"><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/509af801726984aaa359b4bf249f5716.png\" data-filename=\"filename\" style=\"width: 603px;\"><br></p><p><br></p>', '10', '64', '22', '22', '10', '0', '1541402364', '1543242861');
INSERT INTO `tz_litestore_goods` VALUES ('23', 'MacBook Pro 13寸', '8', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/eaccd76080ed9e7ece7642694e734885.png,https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/85587c2e045b71fb4c977884a19a36cb.jpg,https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/efb53c4c7814c83aa3c21e0fd408b5df.jpg', '20', '20', '<p><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/43b7a84d68a15d9058971526068a853a.jpg\" data-filename=\"filename\" style=\"width: 603px;\"><br></p>', '0', '12', '23', '23', '10', '0', '1541403061', '1543319289');
INSERT INTO `tz_litestore_goods` VALUES ('24', '车厘子智利进口', '7', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/a460ffdbd534b10cdf40487189ccb6b7.jpg,https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/c83a0019dfa7a768037e98f02b70efd5.jpg', '10', '20', '<p><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181105/611619c7dac06511213278a469a1efea.jpg\" data-filename=\"filename\" style=\"width: 603px;\"><br></p>', '0', '12', '24', '24', '10', '0', '1541403509', '1543246427');

-- ----------------------------
-- Table structure for tz_litestore_goods_spec
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_goods_spec`;
CREATE TABLE `tz_litestore_goods_spec` (
  `goods_spec_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_no` varchar(100) NOT NULL DEFAULT '',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `line_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `stock_num` int(11) NOT NULL DEFAULT '0',
  `goods_sales` int(11) unsigned NOT NULL DEFAULT '0',
  `goods_weight` double unsigned NOT NULL DEFAULT '0',
  `spec_sku_id` varchar(255) NOT NULL DEFAULT '',
  `spec_image` varchar(580) NOT NULL DEFAULT '' COMMENT '规格封面',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`goods_spec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_goods_spec
-- ----------------------------
INSERT INTO `tz_litestore_goods_spec` VALUES ('66', '23', 'mac_0001', '12688.00', '0.00', '989', '10', '1.2', '48', '', '1541406888', '1543319289');
INSERT INTO `tz_litestore_goods_spec` VALUES ('67', '23', 'mac_0002', '12688.00', '0.00', '997', '2', '1.2', '49', '', '1541406888', '1543021905');
INSERT INTO `tz_litestore_goods_spec` VALUES ('81', '21', 'SN001', '3299.00', '0.00', '997', '2', '0.5', '40_42', '', '1542271178', '1543221954');
INSERT INTO `tz_litestore_goods_spec` VALUES ('82', '21', 'SN002', '3999.00', '0.00', '999', '0', '0.5', '40_43', '', '1542271178', '1542271178');
INSERT INTO `tz_litestore_goods_spec` VALUES ('83', '21', 'SN011', '3399.00', '0.00', '999', '0', '0.5', '41_42', '', '1542271178', '1542271178');
INSERT INTO `tz_litestore_goods_spec` VALUES ('84', '21', 'SN012', '4099.00', '0.00', '999', '0', '0.5', '41_43', '', '1542271178', '1542271178');
INSERT INTO `tz_litestore_goods_spec` VALUES ('94', '24', 'CHE001', '258.00', '299.00', '94', '12', '1', '', '', '1542707236', '1543283382');
INSERT INTO `tz_litestore_goods_spec` VALUES ('103', '22', 'SNHW001', '4499.00', '0.00', '941', '58', '500', '44_46', '', '1542784591', '1543242861');
INSERT INTO `tz_litestore_goods_spec` VALUES ('104', '22', 'SNHW001', '5899.00', '0.00', '997', '2', '500', '44_47', '', '1542784591', '1542978749');
INSERT INTO `tz_litestore_goods_spec` VALUES ('105', '22', 'SNHW001', '4699.00', '0.00', '996', '3', '500', '45_46', '', '1542784591', '1543242861');
INSERT INTO `tz_litestore_goods_spec` VALUES ('106', '22', 'SNHW001', '6099.00', '0.00', '999', '0', '500', '45_47', '', '1542784591', '1542784591');

-- ----------------------------
-- Table structure for tz_litestore_goods_spec_rel
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_goods_spec_rel`;
CREATE TABLE `tz_litestore_goods_spec_rel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0',
  `spec_id` int(11) unsigned NOT NULL DEFAULT '0',
  `spec_value_id` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_goods_spec_rel
-- ----------------------------
INSERT INTO `tz_litestore_goods_spec_rel` VALUES ('64', '23', '20', '48', '1541406888');
INSERT INTO `tz_litestore_goods_spec_rel` VALUES ('65', '23', '20', '49', '1541406888');
INSERT INTO `tz_litestore_goods_spec_rel` VALUES ('78', '21', '20', '40', '1542271178');
INSERT INTO `tz_litestore_goods_spec_rel` VALUES ('79', '21', '20', '41', '1542271178');
INSERT INTO `tz_litestore_goods_spec_rel` VALUES ('80', '21', '21', '42', '1542271178');
INSERT INTO `tz_litestore_goods_spec_rel` VALUES ('81', '21', '21', '43', '1542271178');
INSERT INTO `tz_litestore_goods_spec_rel` VALUES ('98', '22', '20', '44', '1542784591');
INSERT INTO `tz_litestore_goods_spec_rel` VALUES ('99', '22', '20', '45', '1542784591');
INSERT INTO `tz_litestore_goods_spec_rel` VALUES ('100', '22', '22', '46', '1542784591');
INSERT INTO `tz_litestore_goods_spec_rel` VALUES ('101', '22', '22', '47', '1542784591');

-- ----------------------------
-- Table structure for tz_litestore_news
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_news`;
CREATE TABLE `tz_litestore_news` (
  `id` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `image` varchar(180) NOT NULL DEFAULT '' COMMENT '图片',
  `content` mediumtext COMMENT '内容',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='首页banner';

-- ----------------------------
-- Records of tz_litestore_news
-- ----------------------------
INSERT INTO `tz_litestore_news` VALUES ('1', '双十一！来Pink Dream 脱单吧！', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181114/8543faa4986afc586e953137aaf741c3.png', '<section style=\"margin: 20px 0px 10px; padding: 0px; max-width: 100%; text-align: center; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 0px 10px; max-width: 100%; display: inline-block; min-width: 10%; vertical-align: top; word-wrap: break-word !important;\"><section class=\"\" powered-by=\"xiumi.us\" style=\"margin: 0px; padding: 0px; max-width: 100%; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 0px; max-width: 100%; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 3px 10px 6px; max-width: 100%; display: inline-block; min-width: 10%; vertical-align: top; border-width: 1px; border-radius: 0px; border-style: solid none; border-color: rgb(79, 197, 222); word-wrap: break-word !important;\"><section class=\"\" powered-by=\"xiumi.us\" style=\"margin: 0px; padding: 0px; max-width: 100%; word-wrap: break-word !important;\"><section style=\"margin: 3px 0px 0px; padding: 0px; max-width: 100%; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 14px; color: rgb(238, 162, 193); line-height: 2; letter-spacing: 1px; word-wrap: break-word !important;\"><p style=\"margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 16px; word-wrap: break-word !important;\">你还是单身吗？</span></p><p style=\"margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 16px; word-wrap: break-word !important;\">快来抓娃娃邂逅你的另一半吧！</span></p><p style=\"margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 16px; word-wrap: break-word !important;\">或许你的他是百发百中的抓娃娃大神，</span></p><p style=\"margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 16px; word-wrap: break-word !important;\">或许你的她是粉粉少女心的小仙女，</span></p><p style=\"margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 16px; word-wrap: break-word !important;\">来Pink Dream活动脱单吧！</span></p></section></section></section><section class=\"\" powered-by=\"xiumi.us\" style=\"margin: 0px; padding: 0px; max-width: 100%; word-wrap: break-word !important;\"><section style=\"margin: -5px 0px -20px; padding: 0px; max-width: 100%; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 0px 5px; max-width: 100%; display: inline-block; width: 30px; height: 30px; vertical-align: top; overflow: hidden; word-wrap: break-word !important;\"><section class=\"\" powered-by=\"xiumi.us\" style=\"margin: 0px; padding: 0px; max-width: 100%; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 0px; max-width: 100%; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 0px; max-width: 100%; vertical-align: middle; display: inline-block; width: 20px; word-wrap: break-word !important;\"><svg xmlns=\"http://www.w3.org/2000/svg\" x=\"0px\" y=\"0px\" viewBox=\"0 0 168.9 125.4\" style=\"vertical-align: middle; max-width: 100%;\" width=\"100%\"><g><path d=\"M96.7,62.7V9.3c0-5.2,4.3-9.3,9.3-9.3h53.4c5.2,0,9.5,4.1,9.5,9.3v53.4c0,34.6-28.2,62.7-62.8,62.7   c-5.1,0-9.3-4.1-9.3-9.3c0-5.2,4.3-9.3,9.3-9.3c21.1,0,38.8-14.9,43.1-34.7h-43.1C101,72.1,96.7,67.9,96.7,62.7z\" fill=\"rgb(79,197,222)\"></path><path d=\"M0,62.7V9.3C0,4.1,4.3,0,9.3,0h53.4c5.2,0,9.5,4.1,9.5,9.3v53.4c0,34.6-28.2,62.7-62.8,62.7   c-5.1,0-9.3-4.1-9.3-9.3c0-5.2,4.3-9.3,9.3-9.3c21.1,0,38.8-14.9,43.1-34.7H9.3C4.3,72.1,0,67.9,0,62.7z\" fill=\"rgb(79,197,222)\"></path></g></svg></section></section></section></section></section></section></section></section></section></section><p style=\"text-align: center; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; word-wrap: break-word !important;\"><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181114/20181128141517.gif\" style=\"width: 373.172px; height: 311.211px;\"></p><p style=\"text-align: center; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; word-wrap: break-word !important;\"><br></p><p style=\"text-align: center; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; word-wrap: break-word !important;\"><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181114/50286403e58df6c6cb296036f44f6ea4.png\" style=\"width: 537px;\"></p><p style=\"text-align: center; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; word-wrap: break-word !important;\"><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181114/fe795e4aa817900e223b6152f14eb57b.png\" style=\"width: 533px;\"><br style=\"margin: 0px; padding: 0px; max-width: 100%; word-wrap: break-word !important;\"></p></section>', '1542096807', '1543385827', 'normal');
INSERT INTO `tz_litestore_news` VALUES ('2', '轻断食免费送 | 没当上VOGUE女魔头 她却创立了一个婚纱帝国', 'https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181128/88ec778c0b1743586f42b5e848ad5f42.png', '<p><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181128/4647b1cb325061ae2d68c68028a762d0.jpg\" style=\"width: 669px;\" data-filename=\"filename\"></p><p>在纽约流行着一段话：</p><p>未婚女人憧憬拥有一件 Vera Wang</p><p>已婚女人时常怀念自己穿过的那件 VeraWang</p><p>再婚女人庆幸自己可以再要一件 Vera Wang</p><p style=\"margin-right: 16px; margin-bottom: 0px; margin-left: 16px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 1.75em; word-wrap: break-word !important; overflow-wrap: break-word !important;\"><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181128/297e2a8798261c7c9e5bc82c27377c57.jpg\" style=\"width: 657px;\" data-filename=\"filename\"></p><p style=\"margin-right: 16px; margin-bottom: 0px; margin-left: 16px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 1.75em; word-wrap: break-word !important; overflow-wrap: break-word !important;\"><br></p><p style=\"margin-right: 16px; margin-bottom: 0px; margin-left: 16px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 1.75em; word-wrap: break-word !important; overflow-wrap: break-word !important;\">创造了婚纱帝国的王薇薇 VeraWang，简直就是一位传奇女士。或许大家一直向往她的婚纱，但一定不知道这些华服下的，她那精彩的人生。</p><p style=\"margin-right: 16px; margin-bottom: 0px; margin-left: 16px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 1.75em; word-wrap: break-word !important; overflow-wrap: break-word !important;\"><br></p><p style=\"margin-right: 16px; margin-bottom: 0px; margin-left: 16px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 1.75em; word-wrap: break-word !important; overflow-wrap: break-word !important;\"><img src=\"https://her-family.oss-cn-qingdao.aliyuncs.com/addons_store_uploads/20181128/241e24822db3cf5edab983d7c3fec03f.jpg\" style=\"width: 657px;\" data-filename=\"filename\"></p><p style=\"margin-right: 16px; margin-bottom: 0px; margin-left: 16px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 1.75em; word-wrap: break-word !important; overflow-wrap: break-word !important;\"><br></p><p style=\"margin-right: 16px; margin-bottom: 0px; margin-left: 16px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 1.75em; word-wrap: break-word !important; overflow-wrap: break-word !important;\">今年69岁的王薇薇本身是个富家女。她也常常在采访中表示，能获得现在的财富，家庭对她的帮助和影响都很大。</p><p style=\"margin-right: 16px; margin-bottom: 0px; margin-left: 16px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 1.75em; word-wrap: break-word !important; overflow-wrap: break-word !important;\"><br></p><p style=\"margin-right: 16px; margin-bottom: 0px; margin-left: 16px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; font-variant-numeric: normal; font-variant-east-asian: normal; line-height: 1.75em; word-wrap: break-word !important; overflow-wrap: break-word !important;\">她的家庭非常富有，父亲事业上非常成功，精通工业、制造业，还是新加坡 Oceanic Petroleum 的主要股东,学历也超高，是麻省理工毕业的高材生。母亲吴赤芳是联合国的翻译官，小时候就会带着她去巴黎看时装走秀什么的，从小一直学滑冰，养成系的名媛。。。。。。</p>', '1543386743', '1543387060', 'normal');

-- ----------------------------
-- Table structure for tz_litestore_order
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_order`;
CREATE TABLE `tz_litestore_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_no` varchar(20) NOT NULL DEFAULT '' COMMENT '订单编号',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品金额',
  `pay_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单总支付金额',
  `pay_status` enum('10','20') NOT NULL DEFAULT '10' COMMENT '支付状态:10=未支付,20=已支付',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `express_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `express_company` varchar(50) NOT NULL DEFAULT '' COMMENT '快递公司',
  `express_no` varchar(50) NOT NULL DEFAULT '' COMMENT '快递单号',
  `freight_status` enum('10','20') NOT NULL DEFAULT '10' COMMENT '发货状态:10=未发货,20=已发货',
  `freight_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发货时间',
  `receipt_status` enum('10','20') NOT NULL DEFAULT '10' COMMENT '收货状态:10=未收货,20=已收货',
  `receipt_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收货时间',
  `order_status` enum('10','20','30') NOT NULL DEFAULT '10' COMMENT '订单状态:10=进行中,20=取消,30=已完成',
  `transaction_id` varchar(30) NOT NULL DEFAULT '' COMMENT '微信支付ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '生成时间',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_order
-- ----------------------------

-- ----------------------------
-- Table structure for tz_litestore_order_address
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_order_address`;
CREATE TABLE `tz_litestore_order_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `province_id` int(11) unsigned NOT NULL DEFAULT '0',
  `city_id` int(11) unsigned NOT NULL DEFAULT '0',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0',
  `detail` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_order_address
-- ----------------------------

-- ----------------------------
-- Table structure for tz_litestore_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_order_goods`;
CREATE TABLE `tz_litestore_order_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `images` varchar(1800) NOT NULL COMMENT '商品图片',
  `deduct_stock_type` enum('10','20') NOT NULL DEFAULT '20' COMMENT '库存计算方式:10=下单减库存,20=付款减库存',
  `spec_type` enum('10','20') NOT NULL DEFAULT '10' COMMENT '商品规格:10=单规格,20=多规格',
  `spec_sku_id` varchar(255) NOT NULL DEFAULT '' COMMENT '规格sku',
  `goods_spec_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品规格ID',
  `goods_attr` varchar(500) NOT NULL DEFAULT '' COMMENT '商品规格描述',
  `content` text NOT NULL COMMENT '商品描述',
  `goods_no` varchar(100) NOT NULL DEFAULT '' COMMENT '商品编号',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `line_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `goods_weight` double unsigned NOT NULL DEFAULT '0',
  `total_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总价',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_order_goods
-- ----------------------------

-- ----------------------------
-- Table structure for tz_litestore_spec
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_spec`;
CREATE TABLE `tz_litestore_spec` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `spec_name` varchar(255) NOT NULL DEFAULT '',
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_spec
-- ----------------------------
INSERT INTO `tz_litestore_spec` VALUES ('20', '颜色', '1541401442');
INSERT INTO `tz_litestore_spec` VALUES ('21', '版本', '1541401484');
INSERT INTO `tz_litestore_spec` VALUES ('22', '内存', '1541402270');

-- ----------------------------
-- Table structure for tz_litestore_spec_value
-- ----------------------------
DROP TABLE IF EXISTS `tz_litestore_spec_value`;
CREATE TABLE `tz_litestore_spec_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `spec_value` varchar(255) NOT NULL,
  `spec_id` int(11) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_litestore_spec_value
-- ----------------------------
INSERT INTO `tz_litestore_spec_value` VALUES ('40', '黑色', '20', '1541401442');
INSERT INTO `tz_litestore_spec_value` VALUES ('41', '翡翠绿', '20', '1541401451');
INSERT INTO `tz_litestore_spec_value` VALUES ('42', '6+128', '21', '1541401484');
INSERT INTO `tz_litestore_spec_value` VALUES ('43', '8+128', '21', '1541401489');
INSERT INTO `tz_litestore_spec_value` VALUES ('44', '亮黑色', '20', '1541402233');
INSERT INTO `tz_litestore_spec_value` VALUES ('45', '极光色', '20', '1541402243');
INSERT INTO `tz_litestore_spec_value` VALUES ('46', '6GB+64GB', '22', '1541402271');
INSERT INTO `tz_litestore_spec_value` VALUES ('47', '8GB+128GB', '22', '1541402279');
INSERT INTO `tz_litestore_spec_value` VALUES ('48', '天空灰', '20', '1541403005');
INSERT INTO `tz_litestore_spec_value` VALUES ('49', '银色', '20', '1541403011');

-- ----------------------------
-- Table structure for tz_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `tz_order_goods`;
CREATE TABLE `tz_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `goods_size_id` int(11) DEFAULT NULL COMMENT '商品规格ID',
  `goods_img` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `goods_name` varchar(50) DEFAULT NULL COMMENT '商品名字',
  `goods_num` int(11) DEFAULT NULL COMMENT '商品数量',
  `color` varchar(50) DEFAULT NULL COMMENT '颜色分类',
  `spec` varchar(50) DEFAULT NULL COMMENT '尺寸',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `order_sn` varchar(50) DEFAULT NULL COMMENT '订单号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 COMMENT='订单商品表';

-- ----------------------------
-- Records of tz_order_goods
-- ----------------------------
INSERT INTO `tz_order_goods` VALUES ('71', '60', '34', '/uploads/image/20190413/5cb14909326c4.jpg', '打算倒萨', '1', '无', '无', '20.00', 'YXDW1555636152974');
INSERT INTO `tz_order_goods` VALUES ('72', '61', '32', '/uploads/image/20190413/5cb148c89faf2.jpg', '爽肤水', '1', '2', '3', '50.00', 'YXDW1555123632933');
INSERT INTO `tz_order_goods` VALUES ('73', '61', '33', '/uploads/image/20190413/5cb148e7005ed.jpg', '发生发射点发射点111', '1', '1', '1', '20.00', 'YXDW1555636152354');
INSERT INTO `tz_order_goods` VALUES ('74', '62', '33', '/uploads/image/20190413/5cb148e7005ed.jpg', '发生发射点发射点111', '1', '1', '1', '20.00', 'YXDW1555636152354');
INSERT INTO `tz_order_goods` VALUES ('75', '63', '30', '/uploads/image/20190413/5cb1489845004.jpg', '海洛因', '1', '无', '无', '50.00', 'YXDW1555498071137');
INSERT INTO `tz_order_goods` VALUES ('76', '63', '33', '/uploads/image/20190413/5cb148e7005ed.jpg', '发生发射点发射点111', '1', '1', '1', '20.00', 'YXDW1555498071137');
INSERT INTO `tz_order_goods` VALUES ('77', '64', '33', '/uploads/image/20190413/5cb148e7005ed.jpg', '发生发射点发射点111', '1', '1', '1', '20.00', 'YXDW1555636152976');
INSERT INTO `tz_order_goods` VALUES ('78', '64', '34', '/uploads/image/20190413/5cb14909326c4.jpg', '打算倒萨', '1', '无', '无', '20.00', 'YXDW1555636152976');
INSERT INTO `tz_order_goods` VALUES ('79', '66', '31', '/uploads/image/20190413/5cb148c89faf2.jpg', '爽肤水', '1', '1', '1', '50.00', 'YXDW1556024898480');
INSERT INTO `tz_order_goods` VALUES ('80', '67', '31', '/uploads/image/20190413/5cb148c89faf2.jpg', '爽肤水', '4', '1', '1', '50.00', 'YXDW1556024898366');
INSERT INTO `tz_order_goods` VALUES ('81', '68', '31', '/uploads/image/20190413/5cb148c89faf2.jpg', '爽肤水', '4', '1', '1', '50.00', 'YXDW1556024898832');
INSERT INTO `tz_order_goods` VALUES ('82', '69', '31', '/uploads/image/20190413/5cb148c89faf2.jpg', '爽肤水', '4', '1', '1', '50.00', 'YXDW1556024898341');
INSERT INTO `tz_order_goods` VALUES ('83', '70', '31', '/uploads/image/20190413/5cb148c89faf2.jpg', '爽肤水', '4', '1', '1', '0.01', 'YXDW1556024898164');
INSERT INTO `tz_order_goods` VALUES ('84', '71', '31', '/uploads/image/20190413/5cb148c89faf2.jpg', '爽肤水', '1', '1', '1', '0.01', 'YXDW1556024898870');

-- ----------------------------
-- Table structure for tz_parts
-- ----------------------------
DROP TABLE IF EXISTS `tz_parts`;
CREATE TABLE `tz_parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(50) DEFAULT NULL COMMENT '图片',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `amount` int(11) DEFAULT NULL COMMENT '价格',
  `content` text COMMENT '详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_parts
-- ----------------------------
INSERT INTO `tz_parts` VALUES ('1', 'uploads/20190424150909.png', '车头', '10000', '重量10000255');
INSERT INTO `tz_parts` VALUES ('2', 'uploads/20190424150909.png', '铲车', '5000', '重量');
INSERT INTO `tz_parts` VALUES ('3', 'uploads/20190424150909.png', '电控发动机', '5000', '重量');
INSERT INTO `tz_parts` VALUES ('4', 'uploads/20190424150909.png', '加长履带', '10000', '重量');

-- ----------------------------
-- Table structure for tz_rent_evaluate
-- ----------------------------
DROP TABLE IF EXISTS `tz_rent_evaluate`;
CREATE TABLE `tz_rent_evaluate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `rent_id` int(10) DEFAULT NULL,
  `u_logo` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `u_name` varchar(50) DEFAULT NULL COMMENT '用户姓名',
  `content` text COMMENT '评价内容',
  `star` tinyint(3) DEFAULT '0',
  `date` int(11) DEFAULT NULL COMMENT '时间',
  `img1` varchar(255) DEFAULT NULL,
  `img2` varchar(255) DEFAULT NULL,
  `img3` varchar(255) DEFAULT NULL,
  `driver_name` varchar(255) DEFAULT NULL COMMENT '司机头像',
  `driver_logo` varchar(255) DEFAULT NULL COMMENT '司机头像',
  `driver_star` int(10) DEFAULT NULL COMMENT '司机评星',
  `driver_label` varchar(255) DEFAULT NULL COMMENT '司机标签 ',
  `driver_id` int(10) DEFAULT NULL COMMENT '司机id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='租车评价表';

-- ----------------------------
-- Records of tz_rent_evaluate
-- ----------------------------
INSERT INTO `tz_rent_evaluate` VALUES ('28', '2', '1', 'dasdas', 'dasdas', '很好', '5', '1156219870', '11', null, null, null, null, null, null, null);
INSERT INTO `tz_rent_evaluate` VALUES ('29', '2', '2', 'dasdas', 'dasdas', '很好', '5', '1156219870', '11', null, null, null, null, null, null, null);
INSERT INTO `tz_rent_evaluate` VALUES ('30', '2', '1', 'dasdas', 'dasdas', '很好', '5', '1156219870', '11', null, null, null, null, null, null, null);
INSERT INTO `tz_rent_evaluate` VALUES ('31', '2', '1', 'dasdas', 'dasdas', '很好', '5', '1156219870', '11', null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for tz_rentcar
-- ----------------------------
DROP TABLE IF EXISTS `tz_rentcar`;
CREATE TABLE `tz_rentcar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) DEFAULT NULL COMMENT '分类id',
  `img` varchar(255) DEFAULT NULL COMMENT '图片',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `amount` int(11) DEFAULT NULL COMMENT '金额',
  `parts_id` varchar(50) DEFAULT NULL COMMENT '12,13,14',
  `sale` int(11) DEFAULT NULL COMMENT '被租的次数',
  `content` text COMMENT '详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_rentcar
-- ----------------------------
INSERT INTO `tz_rentcar` VALUES ('1', '1', 'uploads/20190424150909.png', '新一代1', '1000', '', '10', '撒打撒打撒');
INSERT INTO `tz_rentcar` VALUES ('2', '2', 'uploads/20190424150909.png', '新一代2', '25462', '3,4', '20', 'dasdasd');
INSERT INTO `tz_rentcar` VALUES ('3', '3', 'uploads/20190424150909.png', '新一代3', '24581', '1,2,3', '10', 'dasdsa');
INSERT INTO `tz_rentcar` VALUES ('4', '4', 'uploads/20190424150909.png', '新一代4', '511233', '1,4', '20', '打算大苏打');

-- ----------------------------
-- Table structure for tz_rentcate
-- ----------------------------
DROP TABLE IF EXISTS `tz_rentcate`;
CREATE TABLE `tz_rentcate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '车型名字',
  `sort` int(10) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_rentcate
-- ----------------------------
INSERT INTO `tz_rentcate` VALUES ('1', '挖掘机', null);
INSERT INTO `tz_rentcate` VALUES ('2', '拖拉机', null);
INSERT INTO `tz_rentcate` VALUES ('3', '拖车', null);
INSERT INTO `tz_rentcate` VALUES ('4', '吊车', null);
INSERT INTO `tz_rentcate` VALUES ('5', '卡车', null);

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
  `img` varchar(50) DEFAULT NULL COMMENT '图片名称',
  `begin_time` int(11) DEFAULT NULL COMMENT '开工时间',
  `time` varchar(50) DEFAULT NULL COMMENT '施工时长',
  `rent_amount` int(11) DEFAULT NULL COMMENT '租车费用',
  `trans_amount` int(11) DEFAULT NULL COMMENT '运费',
  `prefer_amount` int(11) DEFAULT '0' COMMENT '优惠',
  `discount_amount` int(11) DEFAULT NULL COMMENT '折扣',
  `real_amount` int(11) DEFAULT NULL COMMENT '实际应付金额',
  `uname` varchar(50) DEFAULT NULL COMMENT '用户名称',
  `umobile` varchar(50) DEFAULT NULL COMMENT '用户手机号',
  `uaddr` varchar(255) DEFAULT NULL COMMENT '用户地址',
  `content` text COMMENT '备注',
  `rentcart` varchar(50) DEFAULT NULL COMMENT '车型',
  `driver_id` int(10) DEFAULT NULL COMMENT '司机id',
  `pay_status` int(10) DEFAULT NULL COMMENT '支付类型  1余额  2支付宝  3微信',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_rentorder
-- ----------------------------

-- ----------------------------
-- Table structure for tz_rentot
-- ----------------------------
DROP TABLE IF EXISTS `tz_rentot`;
CREATE TABLE `tz_rentot` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '1:加时 2：减时',
  `type` int(10) DEFAULT NULL,
  `order_sn` varchar(255) DEFAULT NULL COMMENT '订单标号',
  `order_id` int(10) DEFAULT NULL COMMENT '订单id',
  `status` int(10) DEFAULT NULL COMMENT '1:成功 2：失败',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_rentot
-- ----------------------------

-- ----------------------------
-- Table structure for tz_sms
-- ----------------------------
DROP TABLE IF EXISTS `tz_sms`;
CREATE TABLE `tz_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `mobile` varchar(20) DEFAULT '' COMMENT '手机号',
  `code` varchar(10) DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned DEFAULT '0' COMMENT '验证次数',
  `addtime` int(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='短信验证码表';

-- ----------------------------
-- Records of tz_sms
-- ----------------------------
INSERT INTO `tz_sms` VALUES ('1', '13858679329', '1984', '0', '1556017424');
INSERT INTO `tz_sms` VALUES ('2', '13858679329', '2971', '0', '1556017487');
INSERT INTO `tz_sms` VALUES ('3', '13858679329', '1784', '0', '1556017529');

-- ----------------------------
-- Table structure for tz_user
-- ----------------------------
DROP TABLE IF EXISTS `tz_user`;
CREATE TABLE `tz_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(10) unsigned DEFAULT '0' COMMENT '组别ID',
  `username` varchar(32) DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) DEFAULT '' COMMENT '昵称',
  `password` varchar(32) DEFAULT '' COMMENT '密码',
  `salt` varchar(30) DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) DEFAULT '' COMMENT '手机号',
  `avatar` varchar(255) DEFAULT '' COMMENT '头像',
  `level` tinyint(1) unsigned DEFAULT '0' COMMENT '等级',
  `gender` tinyint(1) unsigned DEFAULT '0' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `bio` varchar(100) DEFAULT '' COMMENT '格言',
  `money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '余额',
  `score` int(10) unsigned DEFAULT '0' COMMENT '积分',
  `successions` int(10) unsigned DEFAULT '1' COMMENT '连续登录天数',
  `maxsuccessions` int(10) unsigned DEFAULT '1' COMMENT '最大连续登录天数',
  `prevtime` int(10) DEFAULT NULL COMMENT '上次登录时间',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) DEFAULT '' COMMENT '登录IP',
  `loginfailure` tinyint(1) unsigned DEFAULT '0' COMMENT '失败次数',
  `joinip` varchar(50) DEFAULT '' COMMENT '加入IP',
  `jointime` int(10) DEFAULT NULL COMMENT '加入时间',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(50) DEFAULT '' COMMENT 'Token',
  `status` varchar(30) DEFAULT '' COMMENT '状态',
  `verification` varchar(255) DEFAULT '' COMMENT '验证',
  `integrate` int(11) DEFAULT NULL COMMENT '积分',
  `porn` varchar(50) DEFAULT NULL COMMENT '邀请码',
  `addr` varchar(255) DEFAULT NULL COMMENT '所在地',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员表';

-- ----------------------------
-- Records of tz_user
-- ----------------------------
INSERT INTO `tz_user` VALUES ('1', '1', 'admin', 'admin', 'c13f62012fd6a8fdf06b3452a94430e5', 'rpR6Bv', 'admin@163.com', '13858679328', '11', '0', '0', '2017-04-15', '', '0.00', '0', '1', '1', '1516170492', '1516171614', '127.0.0.1', '0', '127.0.0.1', '1491461418', '0', '1516171614', '', 'normal', '', '500', 'dasd55', '江苏南京');

-- ----------------------------
-- Table structure for tz_user_volume
-- ----------------------------
DROP TABLE IF EXISTS `tz_user_volume`;
CREATE TABLE `tz_user_volume` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `vid` int(10) DEFAULT NULL,
  `status` int(2) DEFAULT '0' COMMENT '0:为使用 1：已使用',
  `name` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `real_amount` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tz_user_volume
-- ----------------------------
INSERT INTO `tz_user_volume` VALUES ('1', '1', '1', '0', '全场通用折扣卷', '1556294400', '888', '88');
INSERT INTO `tz_user_volume` VALUES ('2', '1', '2', '0', '全场通用折扣卷', '1556269442', '785', '58');
