
-- -----------------------------
-- Table structure for `f_ follow`
-- -----------------------------
DROP TABLE IF EXISTS `f_ follow`;
CREATE TABLE `f_ follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `follow_id` int(11) NOT NULL COMMENT '关注id',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关注表';


-- -----------------------------
-- Table structure for `f_ plan`
-- -----------------------------
DROP TABLE IF EXISTS `f_ plan`;
CREATE TABLE `f_ plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '名字',
  `mobile` varchar(11) NOT NULL COMMENT '电话',
  `content` varchar(255) NOT NULL COMMENT '需求',
  `dateline` datetime NOT NULL COMMENT '定制时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='预约定制方案';


-- -----------------------------
-- Table structure for `f_admin`
-- -----------------------------
DROP TABLE IF EXISTS `f_admin`;
CREATE TABLE `f_admin` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `cat` tinyint(1) DEFAULT NULL,
  `regdate` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1正常 2禁用',
  `xingming` varchar(20) DEFAULT NULL,
  `dianhua` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=84693446 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `f_admin`
-- -----------------------------
INSERT INTO `f_admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', '1501852488', '1', 'admin', '13888888888');
INSERT INTO `f_admin` VALUES ('2', 'root', 'e10adc3949ba59abbe56e057f20f883e', '2', '1518143665', '1', 'root', '13999999999');
INSERT INTO `f_admin` VALUES ('3', 'test', 'e10adc3949ba59abbe56e057f20f883e', '3', '1522053420', '1', '康桥', '');

-- -----------------------------
-- Table structure for `f_admin_action`
-- -----------------------------
DROP TABLE IF EXISTS `f_admin_action`;
CREATE TABLE `f_admin_action` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fid` int(20) DEFAULT NULL,
  `m` varchar(20) DEFAULT NULL,
  `a` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `m` (`a`,`m`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=145 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `f_admin_action`
-- -----------------------------
INSERT INTO `f_admin_action` VALUES ('1', '0', 'Administrator', 'index', '管理员', '1531903044', 'icon-addressbook_fill');
INSERT INTO `f_admin_action` VALUES ('3', '2', 'Administrator', 'add', '添加管理员', '1538969738', '');
INSERT INTO `f_admin_action` VALUES ('4', '2', 'Administrator', 'cat', '角色管理', '14', '');
INSERT INTO `f_admin_action` VALUES ('9', '0', 'Data', 'index', '数据管理', '1542009413', 'icon-shujuku');
INSERT INTO `f_admin_action` VALUES ('10', '0', 'Menu', 'index', '菜单管理', '1537411349', 'icon-stealth');
INSERT INTO `f_admin_action` VALUES ('11', '10', 'Menu', 'add', '添加菜单', '0', '');
INSERT INTO `f_admin_action` VALUES ('12', '9', 'Data', 'backup', '数据备份', '1', '');
INSERT INTO `f_admin_action` VALUES ('144', '0', 'Coupon', 'index', '优惠券管理', '1542079066', 'icon-tools');
INSERT INTO `f_admin_action` VALUES ('143', '0', 'Banner', 'index', '轮播图', '1528358503', 'icon-browse_fill');
INSERT INTO `f_admin_action` VALUES ('133', '2', 'Administrator', 'cat_add', '添加角色', '10', '');

-- -----------------------------
-- Table structure for `f_admin_auth`
-- -----------------------------
DROP TABLE IF EXISTS `f_admin_auth`;
CREATE TABLE `f_admin_auth` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `role_id` int(20) DEFAULT NULL,
  `action_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cid` (`role_id`,`action_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3175 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `f_admin_auth`
-- -----------------------------
INSERT INTO `f_admin_auth` VALUES ('2937', '2', '0');
INSERT INTO `f_admin_auth` VALUES ('1526', '4', '35');
INSERT INTO `f_admin_auth` VALUES ('1525', '4', '56');
INSERT INTO `f_admin_auth` VALUES ('1524', '4', '55');
INSERT INTO `f_admin_auth` VALUES ('1523', '4', '54');
INSERT INTO `f_admin_auth` VALUES ('1522', '4', '53');
INSERT INTO `f_admin_auth` VALUES ('1521', '4', '52');
INSERT INTO `f_admin_auth` VALUES ('1520', '4', '51');
INSERT INTO `f_admin_auth` VALUES ('1519', '4', '50');
INSERT INTO `f_admin_auth` VALUES ('1518', '4', '49');
INSERT INTO `f_admin_auth` VALUES ('1517', '4', '48');
INSERT INTO `f_admin_auth` VALUES ('2308', '5', '95');
INSERT INTO `f_admin_auth` VALUES ('2306', '5', '0');
INSERT INTO `f_admin_auth` VALUES ('2307', '5', '29');
INSERT INTO `f_admin_auth` VALUES ('2310', '5', '82');
INSERT INTO `f_admin_auth` VALUES ('1996', '6', '90');
INSERT INTO `f_admin_auth` VALUES ('1993', '6', '0');
INSERT INTO `f_admin_auth` VALUES ('1994', '6', '33');
INSERT INTO `f_admin_auth` VALUES ('1995', '6', '39');
INSERT INTO `f_admin_auth` VALUES ('2935', '3', '0');
INSERT INTO `f_admin_auth` VALUES ('1516', '4', '28');
INSERT INTO `f_admin_auth` VALUES ('1515', '4', '71');
INSERT INTO `f_admin_auth` VALUES ('1514', '4', '62');
INSERT INTO `f_admin_auth` VALUES ('1513', '4', '61');
INSERT INTO `f_admin_auth` VALUES ('1512', '4', '60');
INSERT INTO `f_admin_auth` VALUES ('1511', '4', '59');
INSERT INTO `f_admin_auth` VALUES ('1510', '4', '58');
INSERT INTO `f_admin_auth` VALUES ('1509', '4', '57');
INSERT INTO `f_admin_auth` VALUES ('1508', '4', '70');
INSERT INTO `f_admin_auth` VALUES ('1507', '4', '67');
INSERT INTO `f_admin_auth` VALUES ('1506', '4', '66');
INSERT INTO `f_admin_auth` VALUES ('1505', '4', '65');
INSERT INTO `f_admin_auth` VALUES ('2311', '5', '89');
INSERT INTO `f_admin_auth` VALUES ('2312', '5', '100');
INSERT INTO `f_admin_auth` VALUES ('2309', '5', '92');
INSERT INTO `f_admin_auth` VALUES ('1504', '4', '64');
INSERT INTO `f_admin_auth` VALUES ('1503', '4', '63');
INSERT INTO `f_admin_auth` VALUES ('1502', '4', '26');
INSERT INTO `f_admin_auth` VALUES ('1501', '4', '25');
INSERT INTO `f_admin_auth` VALUES ('1500', '4', '47');
INSERT INTO `f_admin_auth` VALUES ('1499', '4', '46');
INSERT INTO `f_admin_auth` VALUES ('1498', '4', '27');
INSERT INTO `f_admin_auth` VALUES ('1497', '4', '16');
INSERT INTO `f_admin_auth` VALUES ('1496', '4', '15');
INSERT INTO `f_admin_auth` VALUES ('1495', '4', '77');
INSERT INTO `f_admin_auth` VALUES ('1494', '4', '76');
INSERT INTO `f_admin_auth` VALUES ('1493', '4', '75');
INSERT INTO `f_admin_auth` VALUES ('1492', '4', '74');
INSERT INTO `f_admin_auth` VALUES ('1491', '4', '73');
INSERT INTO `f_admin_auth` VALUES ('1490', '4', '72');
INSERT INTO `f_admin_auth` VALUES ('1489', '4', '0');
INSERT INTO `f_admin_auth` VALUES ('1527', '4', '33');
INSERT INTO `f_admin_auth` VALUES ('2936', '3', '2');
INSERT INTO `f_admin_auth` VALUES ('2938', '2', '4');
INSERT INTO `f_admin_auth` VALUES ('3174', '1', '144');
INSERT INTO `f_admin_auth` VALUES ('3164', '1', '0');
INSERT INTO `f_admin_auth` VALUES ('3166', '1', '1');
INSERT INTO `f_admin_auth` VALUES ('3169', '1', '3');
INSERT INTO `f_admin_auth` VALUES ('3167', '1', '133');
INSERT INTO `f_admin_auth` VALUES ('3168', '1', '4');
INSERT INTO `f_admin_auth` VALUES ('3171', '1', '11');
INSERT INTO `f_admin_auth` VALUES ('3170', '1', '10');
INSERT INTO `f_admin_auth` VALUES ('3165', '1', '143');
INSERT INTO `f_admin_auth` VALUES ('3172', '1', '9');
INSERT INTO `f_admin_auth` VALUES ('3173', '1', '12');

-- -----------------------------
-- Table structure for `f_admin_cat`
-- -----------------------------
DROP TABLE IF EXISTS `f_admin_cat`;
CREATE TABLE `f_admin_cat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fid` int(10) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '状态：1--启用；2--禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `f_admin_cat`
-- -----------------------------
INSERT INTO `f_admin_cat` VALUES ('1', '0', '超级管理员', '2018-10-08 12:28:08', '');
INSERT INTO `f_admin_cat` VALUES ('2', '0', '一般管理员', '2018-10-08 12:28:22', '');
INSERT INTO `f_admin_cat` VALUES ('3', '0', '密码管理员', '2018-10-08 12:29:02', '');

-- -----------------------------
-- Table structure for `f_banner`
-- -----------------------------
DROP TABLE IF EXISTS `f_banner`;
CREATE TABLE `f_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0首页,1:培训页',
  `img` varchar(255) NOT NULL,
  `link` varchar(10) NOT NULL COMMENT '连接id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '使用状态1：使用-1：禁用',
  `dateline` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='轮播图';

-- -----------------------------
-- Records of `f_banner`
-- -----------------------------
INSERT INTO `f_banner` VALUES ('1', '0', '/uploads/image/20181119/5bf267b3ce3b6.jpg', '1', '1', '2018-11-19 15:35:52');

-- -----------------------------
-- Table structure for `f_collect`
-- -----------------------------
DROP TABLE IF EXISTS `f_collect`;
CREATE TABLE `f_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1:外卖百科2:课程',
  `uid` int(11) NOT NULL,
  `collect_id` int(11) NOT NULL COMMENT '收藏的id',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏表';


-- -----------------------------
-- Table structure for `f_comment`
-- -----------------------------
DROP TABLE IF EXISTS `f_comment`;
CREATE TABLE `f_comment` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `type` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1:外卖百科',
  `uid` int(20) DEFAULT NULL COMMENT '用户ID',
  `logo` varchar(255) DEFAULT NULL COMMENT '头像',
  `nickname` varchar(50) DEFAULT NULL COMMENT '名字',
  `content` varchar(200) DEFAULT NULL COMMENT '内容',
  `top_id` int(11) DEFAULT '1' COMMENT '顶一下',
  `dateline` int(10) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表';


-- -----------------------------
-- Table structure for `f_coupon`
-- -----------------------------
DROP TABLE IF EXISTS `f_coupon`;
CREATE TABLE `f_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '0是都可用优惠券',
  `name` varchar(50) NOT NULL DEFAULT '全场商品通用券' COMMENT '名字',
  `mz_money` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '满足金额',
  `yh_money` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '优惠金额',
  `start_time` varchar(20) NOT NULL COMMENT '开始时间',
  `end_time` varchar(20) NOT NULL COMMENT '结束时间',
  `dateline` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='优惠券';

-- -----------------------------
-- Records of `f_coupon`
-- -----------------------------
INSERT INTO `f_coupon` VALUES ('1', '1', '小白', '30', '10', '2018-11-22', '2018-11-23', '2018-11-20 16:04:24');
INSERT INTO `f_coupon` VALUES ('2', '0', '通用劵', '100', '50', '2018-11-01', '2018-11-22', '2018-11-20 16:12:39');

-- -----------------------------
-- Table structure for `f_course`
-- -----------------------------
DROP TABLE IF EXISTS `f_course`;
CREATE TABLE `f_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lectuer_id` int(11) DEFAULT NULL COMMENT '讲师ID',
  `type` tinyint(1) DEFAULT '1' COMMENT '1:线下2:线上',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `img` varchar(255) DEFAULT NULL COMMENT '课程图片',
  `img_j` varchar(255) DEFAULT NULL COMMENT '课程介绍',
  `apply` text COMMENT '报名流程',
  `price` int(10) DEFAULT NULL COMMENT '价格',
  `holiday` int(10) DEFAULT NULL COMMENT '节数',
  `hour` int(10) DEFAULT NULL COMMENT '小时',
  `is_top` tinyint(1) DEFAULT '1' COMMENT '是否推荐1:否2:是',
  `dateline` datetime DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程表';


-- -----------------------------
-- Table structure for `f_footmark`
-- -----------------------------
DROP TABLE IF EXISTS `f_footmark`;
CREATE TABLE `f_footmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的足迹--只针对商品';


-- -----------------------------
-- Table structure for `f_goods`
-- -----------------------------
DROP TABLE IF EXISTS `f_goods`;
CREATE TABLE `f_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '0:属于自营商品',
  `type` int(11) NOT NULL COMMENT '类型',
  `goods_name` varchar(50) NOT NULL COMMENT '商品名字',
  `shop_name` varchar(50) NOT NULL COMMENT '商店名字',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `yh_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠价格',
  `logo` varchar(255) DEFAULT NULL COMMENT '商品logo',
  `img` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img1` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img2` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `d_img` varchar(255) DEFAULT NULL COMMENT '商品详情图片',
  `d_img1` varchar(255) DEFAULT NULL COMMENT '商品详情图片',
  `d_img2` varchar(255) DEFAULT NULL COMMENT '商品详情图片',
  `gm_num` int(20) NOT NULL DEFAULT '0' COMMENT '购买人数',
  `is_ push` tinyint(1) DEFAULT '1' COMMENT '是否主推1:否2:是',
  `is_top` tinyint(1) DEFAULT '1' COMMENT '是否推荐1:否2:是',
  `dateline` int(10) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品列表';


-- -----------------------------
-- Table structure for `f_goods_flashover`
-- -----------------------------
DROP TABLE IF EXISTS `f_goods_flashover`;
CREATE TABLE `f_goods_flashover` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `goods_id` int(20) NOT NULL COMMENT '商品ID',
  `num` int(20) NOT NULL DEFAULT '0' COMMENT '已购数量',
  `all_num` int(20) NOT NULL COMMENT '当前闪购数量',
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品闪购';


-- -----------------------------
-- Table structure for `f_goods_size`
-- -----------------------------
DROP TABLE IF EXISTS `f_goods_size`;
CREATE TABLE `f_goods_size` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `spec` varchar(50) NOT NULL COMMENT '规格',
  `color` varchar(50) NOT NULL COMMENT '颜色',
  `dateline` datetime NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品规格';


-- -----------------------------
-- Table structure for `f_goods_type`
-- -----------------------------
DROP TABLE IF EXISTS `f_goods_type`;
CREATE TABLE `f_goods_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `one` int(11) NOT NULL DEFAULT '0',
  `two` int(11) NOT NULL DEFAULT '0',
  `three` int(11) NOT NULL DEFAULT '0',
  `four` int(11) NOT NULL DEFAULT '0',
  `dateline` datetime NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类';


-- -----------------------------
-- Table structure for `f_history`
-- -----------------------------
DROP TABLE IF EXISTS `f_history`;
CREATE TABLE `f_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:商品搜索2：店内搜索',
  `content` varchar(50) NOT NULL COMMENT '历史记录',
  `dateline` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='历史记录';


-- -----------------------------
-- Table structure for `f_laud`
-- -----------------------------
DROP TABLE IF EXISTS `f_laud`;
CREATE TABLE `f_laud` (
  `id` int(20) NOT NULL,
  `type` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1:评论表',
  `uid` int(20) NOT NULL COMMENT '用户ID',
  `laud_id` int(20) NOT NULL COMMENT '点赞ID'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='点赞表';


-- -----------------------------
-- Table structure for `f_lectuer`
-- -----------------------------
DROP TABLE IF EXISTS `f_lectuer`;
CREATE TABLE `f_lectuer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `logo` varchar(255) NOT NULL COMMENT '头像',
  `master` varchar(100) NOT NULL COMMENT '精通',
  `content` varchar(200) NOT NULL COMMENT '介绍',
  `dateline` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='讲师列表';


-- -----------------------------
-- Table structure for `f_look`
-- -----------------------------
DROP TABLE IF EXISTS `f_look`;
CREATE TABLE `f_look` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `type` int(20) NOT NULL DEFAULT '1' COMMENT '类型:1外卖百科',
  `uid` int(20) NOT NULL,
  `look_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='查看人数';


-- -----------------------------
-- Table structure for `f_m_log`
-- -----------------------------
DROP TABLE IF EXISTS `f_m_log`;
CREATE TABLE `f_m_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(100) NOT NULL COMMENT '日志内容',
  `dateline` datetime NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资金日志';


-- -----------------------------
-- Table structure for `f_operate`
-- -----------------------------
DROP TABLE IF EXISTS `f_operate`;
CREATE TABLE `f_operate` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '介绍内容',
  `dateline` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='运营介绍表--首页';


-- -----------------------------
-- Table structure for `f_operate_shop`
-- -----------------------------
DROP TABLE IF EXISTS `f_operate_shop`;
CREATE TABLE `f_operate_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(20) NOT NULL COMMENT '商品ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='运营介绍商品';


-- -----------------------------
-- Table structure for `f_release`
-- -----------------------------
DROP TABLE IF EXISTS `f_release`;
CREATE TABLE `f_release` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `re_id` int(11) NOT NULL COMMENT '类型id',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `content` varchar(200) DEFAULT NULL COMMENT '内容',
  `name` varchar(20) DEFAULT NULL COMMENT '名字',
  `zp_type` tinyint(1) DEFAULT '1' COMMENT '招聘类型1：全职2：兼职',
  `is_face` tinyint(1) DEFAULT '2' COMMENT '是否面议1：是2：否',
  `ms_money` decimal(10,2) DEFAULT NULL COMMENT '最低月薪',
  `me_money` decimal(10,2) DEFAULT NULL COMMENT '最高月薪',
  `fixing_cs` varchar(10) DEFAULT NULL COMMENT '设备成色',
  `attorn_num` int(10) DEFAULT NULL COMMENT '转让数量、耗材数',
  `attorn_money` int(10) DEFAULT NULL COMMENT '转让价格、耗材价格',
  `img` varchar(255) DEFAULT NULL COMMENT '设备图、耗材图片、招聘图、通用',
  `shop_area` decimal(10,2) DEFAULT NULL COMMENT '店铺面积',
  `shop_money` decimal(10,2) DEFAULT NULL COMMENT '店铺租金',
  `zr_money` decimal(10,2) DEFAULT NULL COMMENT '转让价格',
  `bs_photos` tinyint(1) DEFAULT '2' COMMENT '是否有营业照1：有2：无',
  `is_allow` tinyint(1) DEFAULT '2' COMMENT '是否有许可证1：有2：无',
  `h_img` varchar(255) DEFAULT NULL COMMENT '门头照',
  `f_img` varchar(255) DEFAULT NULL COMMENT '食堂照',
  `hc_img` varchar(255) DEFAULT NULL COMMENT '后厨照',
  `zz_img` varchar(255) DEFAULT NULL COMMENT '资质照',
  `province` varchar(20) DEFAULT NULL COMMENT '省',
  `city` varchar(20) DEFAULT NULL COMMENT '市',
  `area` varchar(20) DEFAULT NULL COMMENT '区',
  `address` varchar(100) DEFAULT NULL COMMENT '详细地址',
  `lng` varchar(20) DEFAULT NULL COMMENT '经度',
  `lat` varchar(20) DEFAULT NULL COMMENT '纬度',
  `mobile` varchar(11) DEFAULT NULL COMMENT '电话',
  `is_top` tinyint(1) DEFAULT '1' COMMENT '是否制定1：是2：不是',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `dateline` int(10) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='发布列表';


-- -----------------------------
-- Table structure for `f_release_type`
-- -----------------------------
DROP TABLE IF EXISTS `f_release_type`;
CREATE TABLE `f_release_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '类型名字',
  `dateline` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='发布类型';


-- -----------------------------
-- Table structure for `f_serve`
-- -----------------------------
DROP TABLE IF EXISTS `f_serve`;
CREATE TABLE `f_serve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '介绍内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='服务介绍表--培训页';


-- -----------------------------
-- Table structure for `f_shop`
-- -----------------------------
DROP TABLE IF EXISTS `f_shop`;
CREATE TABLE `f_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(1) DEFAULT '1' COMMENT '1：个人2：企业',
  `name` varchar(50) DEFAULT NULL COMMENT '名字/企业名称',
  `yy_img` varchar(255) DEFAULT NULL COMMENT '营业执照',
  `type_one` tinyint(4) DEFAULT '1' COMMENT '企业开店类型1:法人2：代理人',
  `f_name` varchar(50) DEFAULT NULL COMMENT '法人姓名',
  `id_card` varchar(17) DEFAULT NULL COMMENT '身份号 /法人身份号',
  `province` varchar(20) DEFAULT NULL COMMENT '省',
  `city` varchar(20) DEFAULT NULL COMMENT '市',
  `area` varchar(20) DEFAULT NULL COMMENT '区',
  `address` varchar(100) DEFAULT NULL COMMENT '详细地址',
  `z_img` varchar(255) DEFAULT NULL COMMENT '身份证正面/法人',
  `f_img` varchar(255) DEFAULT NULL COMMENT '身份证反面/法人',
  `s_img` varchar(255) DEFAULT NULL COMMENT '手持/代理-法人无',
  `agent_img` varchar(255) DEFAULT NULL COMMENT '代理授权书',
  `agent_name` varchar(20) DEFAULT NULL COMMENT '代理人名字',
  `agent_card` varchar(20) DEFAULT NULL COMMENT '代理人身份正号',
  `agent_z_img` varchar(255) DEFAULT NULL COMMENT '代理人正面身份证',
  `agent_f_img` varchar(255) DEFAULT NULL COMMENT '代理人反面身份证',
  `agent_s_img` varchar(255) DEFAULT NULL COMMENT '代理人手持身份证',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `shop_name` varchar(20) DEFAULT NULL COMMENT '商店名称',
  `status` tinyint(1) DEFAULT '1' COMMENT '审核状态1：未审核2：同意3：拒绝',
  `dateline` datetime DEFAULT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='我的店铺';

-- -----------------------------
-- Records of `f_shop`
-- -----------------------------
INSERT INTO `f_shop` VALUES ('1', '0', '1', '', '', '1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '小白', '2', '');

-- -----------------------------
-- Table structure for `f_shop_lable`
-- -----------------------------
DROP TABLE IF EXISTS `f_shop_lable`;
CREATE TABLE `f_shop_lable` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '标签名字'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商店标签';


-- -----------------------------
-- Table structure for `f_shop_skills`
-- -----------------------------
DROP TABLE IF EXISTS `f_shop_skills`;
CREATE TABLE `f_shop_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '具体内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='开店技巧';


-- -----------------------------
-- Table structure for `f_takeaway`
-- -----------------------------
DROP TABLE IF EXISTS `f_takeaway`;
CREATE TABLE `f_takeaway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1:经营思路2:营销技巧3:成功案例',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `img` varchar(255) NOT NULL COMMENT '图片',
  `content` text NOT NULL COMMENT '内容',
  `look_num` int(20) NOT NULL DEFAULT '0' COMMENT '查看数',
  `comment_num` int(20) NOT NULL DEFAULT '0' COMMENT '评论数',
  ` collect_num` int(20) NOT NULL DEFAULT '0' COMMENT '收藏数',
  `dateline` datetime NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='外卖百科';


-- -----------------------------
-- Table structure for `f_user`
-- -----------------------------
DROP TABLE IF EXISTS `f_user`;
CREATE TABLE `f_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(11) NOT NULL COMMENT '手机号',
  `password` varchar(30) NOT NULL COMMENT '密码',
  `salt` varchar(6) NOT NULL COMMENT '盐',
  `logo` varchar(255) NOT NULL DEFAULT '/uploads/default/head.png' COMMENT '头像',
  `nickname` varchar(30) NOT NULL COMMENT '昵称',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别1：男2：女',
  `birthday` varchar(20) NOT NULL DEFAULT '' COMMENT '生日',
  `wx` varchar(50) NOT NULL COMMENT '微信标识',
  `qq` varchar(50) NOT NULL COMMENT 'QQ标识',
  `money` decimal(10,2) NOT NULL COMMENT '余额',
  `integral` int(12) NOT NULL DEFAULT '0' COMMENT '积分',
  `dateline` datetime NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nickname` (`nickname`),
  UNIQUE KEY `mobile` (`mobile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';


-- -----------------------------
-- Table structure for `f_user_addr`
-- -----------------------------
DROP TABLE IF EXISTS `f_user_addr`;
CREATE TABLE `f_user_addr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `province` varchar(20) DEFAULT NULL COMMENT '省',
  `city` varchar(20) DEFAULT NULL COMMENT '市',
  `area` varchar(20) DEFAULT NULL COMMENT '区',
  `address` varchar(20) DEFAULT NULL COMMENT '详细地址',
  `is_default` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否默认1:是2：不是',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地址列表';


-- -----------------------------
-- Table structure for `f_user_coupon`
-- -----------------------------
DROP TABLE IF EXISTS `f_user_coupon`;
CREATE TABLE `f_user_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的优惠券';

