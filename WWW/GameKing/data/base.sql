/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yungeche_com

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-11-02 12:13:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ke_admin
-- ----------------------------
DROP TABLE IF EXISTS `ke_admin`;
CREATE TABLE `ke_admin` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `cat` tinyint(1) DEFAULT NULL,
  `regdate` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1正常 2禁用',
  `xingming` varchar(20) DEFAULT NULL,
  `dianhua` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ke_admin
-- ----------------------------
INSERT INTO `ke_admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', '1501852488', '1', 'admin', '13888888888');

-- ----------------------------
-- Table structure for ke_admin_action
-- ----------------------------
DROP TABLE IF EXISTS `ke_admin_action`;
CREATE TABLE `ke_admin_action` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fid` int(20) DEFAULT NULL,
  `m` varchar(20) DEFAULT NULL,
  `a` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `m` (`a`,`m`)
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ke_admin_action
-- ----------------------------
INSERT INTO `ke_admin_action` VALUES ('1', '0', 'Xitong', 'index', '系统设置', '1526025559', 'icon-setup');
INSERT INTO `ke_admin_action` VALUES ('2', '0', 'Guanliyuan', 'index', '管理员', '1523955960', 'icon-addressbook_fill');
INSERT INTO `ke_admin_action` VALUES ('3', '2', 'Guanliyuan', 'add', '添加管理员', '10', '');
INSERT INTO `ke_admin_action` VALUES ('4', '2', 'Guanliyuan', 'cat', '角色管理', '14', '');
INSERT INTO `ke_admin_action` VALUES ('9', '0', 'Data', 'index', '数据管理', '1525403647', 'icon-shujuku');
INSERT INTO `ke_admin_action` VALUES ('10', '0', 'Caidan', 'index', '菜单管理', '1523956081', 'icon-stealth');
INSERT INTO `ke_admin_action` VALUES ('11', '10', 'Caidan', 'add', '添加菜单', '0', '');
INSERT INTO `ke_admin_action` VALUES ('12', '9', 'Data', 'backup', '数据备份', '1', '');
INSERT INTO `ke_admin_action` VALUES ('15', '0', 'User', 'index', '用户管理', '1523155228', 'icon-group');

-- ----------------------------
-- Table structure for ke_admin_auth
-- ----------------------------
DROP TABLE IF EXISTS `ke_admin_auth`;
CREATE TABLE `ke_admin_auth` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `role_id` int(20) DEFAULT NULL,
  `action_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cid` (`role_id`,`action_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3190 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ke_admin_auth
-- ----------------------------
INSERT INTO `ke_admin_auth` VALUES ('2480', '2', '1');
INSERT INTO `ke_admin_auth` VALUES ('2469', '2', '0');
INSERT INTO `ke_admin_auth` VALUES ('3179', '1', '1');
INSERT INTO `ke_admin_auth` VALUES ('3178', '1', '12');
INSERT INTO `ke_admin_auth` VALUES ('2464', '3', '0');
INSERT INTO `ke_admin_auth` VALUES ('2475', '2', '15');
INSERT INTO `ke_admin_auth` VALUES ('2479', '2', '11');
INSERT INTO `ke_admin_auth` VALUES ('2478', '2', '10');
INSERT INTO `ke_admin_auth` VALUES ('3177', '1', '9');
INSERT INTO `ke_admin_auth` VALUES ('3176', '1', '11');
INSERT INTO `ke_admin_auth` VALUES ('3175', '1', '10');
INSERT INTO `ke_admin_auth` VALUES ('3174', '1', '4');
INSERT INTO `ke_admin_auth` VALUES ('3173', '1', '3');
INSERT INTO `ke_admin_auth` VALUES ('3172', '1', '2');
INSERT INTO `ke_admin_auth` VALUES ('3168', '1', '15');
INSERT INTO `ke_admin_auth` VALUES ('3163', '1', '0');

-- ----------------------------
-- Table structure for ke_admin_cat
-- ----------------------------
DROP TABLE IF EXISTS `ke_admin_cat`;
CREATE TABLE `ke_admin_cat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fid` int(10) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ke_admin_cat
-- ----------------------------
INSERT INTO `ke_admin_cat` VALUES ('1', '0', '总管理员', '1368023195');
INSERT INTO `ke_admin_cat` VALUES ('2', '0', '商城管理员', '1524206855');
INSERT INTO `ke_admin_cat` VALUES ('3', '0', '超市管理员', '1368023195');

-- ----------------------------
-- Table structure for ke_article
-- ----------------------------
DROP TABLE IF EXISTS `ke_article`;
CREATE TABLE `ke_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `a_title` varchar(255) DEFAULT NULL COMMENT '问章标题',
  `a_ftitle` varchar(255) DEFAULT NULL COMMENT '副标题',
  `a_type` int(10) unsigned DEFAULT '0' COMMENT '文章分类',
  `a_time` datetime DEFAULT NULL COMMENT '发布时间',
  `a_uid` int(10) unsigned DEFAULT NULL COMMENT '发布人',
  `a_content` text COMMENT '发布内容',
  `a_pic` varchar(255) DEFAULT '' COMMENT '文章首页图',
  `a_xuan` varchar(255) DEFAULT NULL COMMENT '广告语',
  `a_color` varchar(100) DEFAULT NULL COMMENT '文章宣传语字体颜色',
  `a_tuijian` tinyint(1) unsigned DEFAULT '0' COMMENT '是否推荐0：不推荐 1：推荐',
  `status` tinyint(1) DEFAULT '0',
  `pic_one` varchar(255) DEFAULT NULL COMMENT '图片一',
  `pic_two` varchar(255) DEFAULT NULL COMMENT '图片二',
  `pic_three` varchar(255) DEFAULT NULL COMMENT '图片三',
  `a_write` varchar(255) DEFAULT NULL COMMENT '文章作者',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ke_article
-- ----------------------------
INSERT INTO `ke_article` VALUES ('2', '测试', '创富圈', '3', '2018-08-11 17:23:41', null, '阿斯顿发到付萨达打算的撒&nbsp;', '/uploads/image/20180811/5b6eab1a37699.jpg', '这回是奥运11111134', '#ff0000', '1', '1', '/static/manage/images/addpic.gif', '/static/manage/images/addpic.gif', '/static/manage/images/addpic.gif', '');
INSERT INTO `ke_article` VALUES ('10', '汽车热报', '7月26日，下半年即将上市重点合资轿车盘点', '3', '2018-09-03 16:33:37', '1', '<div class=\"tz-paragraph\" style=\"margin:0px;padding:0px;color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	&nbsp;七月末订车，<span style=\"font-family:myfont;\"></span>月初提车，已经开<span style=\"font-family:myfont;\"></span>2000多公里，一直想发个提车贴，无奈懒癌严重。<span style=\"font-family:myfont;\"></span>学毕业后很想要一辆车，但<span style=\"font-family:myfont;\"></span>过<span style=\"font-family:myfont;\"></span>浑浑噩噩，没有固定经济来源，生活状态很差。17年稍微稳定一些，就想买车<span style=\"font-family:myfont;\"></span>，本来去年就要买<span style=\"font-family:myfont;\"></span>，看好雷凌，贷款办好<span style=\"font-family:myfont;\"></span>，首付交完<span style=\"font-family:myfont;\"></span>，4S说居住证不好办，于<span style=\"font-family:myfont;\"></span>退<span style=\"font-family:myfont;\"></span>款。自己去派出所办理居住证，等<span style=\"font-family:myfont;\"></span>半年。居住证办好<span style=\"font-family:myfont;\"></span>，思域出<span style=\"font-family:myfont;\"></span>机油事件，继续等待，出<span style=\"font-family:myfont;\"></span>召回方案。然而思域中毒太深，看其他车都没感觉，去试驾<span style=\"font-family:myfont;\"></span>一圈，没啥感觉，可能也<span style=\"font-family:myfont;\"></span>没开过什么好车吧，然后就去谈价格，问<span style=\"font-family:myfont;\"></span>一些群里<span style=\"font-family:myfont;\"></span>车友，价格差不多能接受就交<span style=\"font-family:myfont;\"></span>定金。<br />\r\n选车：在等居住证<span style=\"font-family:myfont;\"></span>半年也在重新选车，卡罗拉、雷凌都<span style=\"font-family:myfont;\"></span>省心<span style=\"font-family:myfont;\"></span>车，内部空间<span style=\"font-family:myfont;\"></span>，但<span style=\"font-family:myfont;\"></span>外观看起来有点小，就排除<span style=\"font-family:myfont;\"></span>。速腾样子太普通<span style=\"font-family:myfont;\"></span>，没啥特色。凌度，宽体轿跑，据说<span style=\"font-family:myfont;\"></span><span style=\"font-family:myfont;\"></span>众第二好看<span style=\"font-family:myfont;\"></span>车，第一CC，但<span style=\"font-family:myfont;\"></span>凌度配置有点低，高配价格高。迈锐宝XL，差点中毒<span style=\"font-family:myfont;\"></span>车，优惠很<span style=\"font-family:myfont;\"></span>，后来想想油耗还<span style=\"font-family:myfont;\"></span>算<span style=\"font-family:myfont;\"></span>吧。思域刚上市<span style=\"font-family:myfont;\"></span>时候，并不觉<span style=\"font-family:myfont;\"></span>它好看，还加价，就没什么兴趣去<span style=\"font-family:myfont;\"></span>解它，然后有一次跟朋友去看实车，暗金蓝，太特么好看<span style=\"font-family:myfont;\"></span>，那时就中毒<span style=\"font-family:myfont;\"></span>，一定要买暗金蓝<span style=\"font-family:myfont;\"></span>思域，以至于后来看什么车都觉<span style=\"font-family:myfont;\"></span>还<span style=\"font-family:myfont;\"></span>思域好。身边很多朋友劝我，买轩逸，买宝来，买朗逸，因为他们<span style=\"font-family:myfont;\"></span>这个车，觉<span style=\"font-family:myfont;\"></span>好看好用，我想想<span style=\"font-family:myfont;\"></span>我买车，当然首先要自己喜欢。中途也差点去定天籁，因为我爸觉<span style=\"font-family:myfont;\"></span>天籁坐起来很舒适。后来也因为一些原因排除<span style=\"font-family:myfont;\"></span>天籁。<br />\r\n订车<span style=\"font-family:myfont;\"></span>提车：车型2016款自动豪华，颜色白色，没要喜欢<span style=\"font-family:myfont;\"></span>暗金蓝，因为听说暗金蓝不<span style=\"font-family:myfont;\"></span>在洗车就<span style=\"font-family:myfont;\"></span>在去洗车<span style=\"font-family:myfont;\"></span>路上，车友称之为洗车蓝，觉<span style=\"font-family:myfont;\"></span>自己应该没有很勤快<span style=\"font-family:myfont;\"></span>洗车，于<span style=\"font-family:myfont;\"></span>选<span style=\"font-family:myfont;\"></span>烂<span style=\"font-family:myfont;\"></span>街<span style=\"font-family:myfont;\"></span>白色。对于配置<span style=\"font-family:myfont;\"></span>话，豪华版够用<span style=\"font-family:myfont;\"></span>，其实<span style=\"font-family:myfont;\"></span>觉<span style=\"font-family:myfont;\"></span>加一万<span style=\"font-family:myfont;\"></span>有点贵，尊贵吸引我<span style=\"font-family:myfont;\"></span>只有那对led<span style=\"font-family:myfont;\"></span>灯，如果后期忍受不<span style=\"font-family:myfont;\"></span>卤素再换总成吧。定金交<span style=\"font-family:myfont;\"></span>5000，五天后就通知验车，四个朋友一起去，结果发现有刮痕，要求换一辆，又等<span style=\"font-family:myfont;\"></span>四天，再次验车，没问题办贷款，交首付，然后电脑上选牌照，其它4S去办，然后就等通知提车。送<span style=\"font-family:myfont;\"></span>脚垫、贴膜、记录仪、底盘装甲，座椅包皮不送，那就后期布<span style=\"font-family:myfont;\"></span>脏<span style=\"font-family:myfont;\"></span>再去包吧。<br />\r\n行车感受：开<span style=\"font-family:myfont;\"></span>一个月<span style=\"font-family:myfont;\"></span>右，<span style=\"font-family:myfont;\"></span>自己理想中<span style=\"font-family:myfont;\"></span>车，很爱惜它，没有<span style=\"font-family:myfont;\"></span>脚油门，没有急刹车，看到鸟粪、污点、树胶都会用水擦干净。说说满意<span style=\"font-family:myfont;\"></span><span style=\"font-family:myfont;\"></span>方吧：1.安全配置比同级别车高，前排安全气囊、前排侧气囊、头部气囊，车身稳定、上坡辅助均为标配。2.高颜值<span style=\"font-family:myfont;\"></span>外观，有点小跑车<span style=\"font-family:myfont;\"></span>风格，<span style=\"font-family:myfont;\"></span>胆又不浮夸。3.动力，1.5T排量很合适啊，加速有力。4.空间，反正家里也没有很高<span style=\"font-family:myfont;\"></span>，我前排合适，爸妈坐后排也不拥挤，门板、扶手箱、中控隔层储物够多。缺点：1.低俗顿挫，通病吧，速度起来后还<span style=\"font-family:myfont;\"></span>挺平顺<span style=\"font-family:myfont;\"></span>。2.雨刮器刮水<span style=\"font-family:myfont;\"></span>时候声音有点<span style=\"font-family:myfont;\"></span>，考虑换<span style=\"font-family:myfont;\"></span>。3.底盘低<span style=\"font-family:myfont;\"></span>一点，感觉坐在<span style=\"font-family:myfont;\"></span>上开车，离<span style=\"font-family:myfont;\"></span>间隙让我过烂路<span style=\"font-family:myfont;\"></span>时候心有点虚，怕蹭底盘。<br />\r\n以下<span style=\"font-family:myfont;\"></span>从验车起，<span style=\"font-family:myfont;\"></span>今天早上去海边拍<span style=\"font-family:myfont;\"></span>一些照片，分享给<span style=\"font-family:myfont;\"></span>家，下面上图：\r\n	</div>\r\n<div style=\"margin:0px;padding:0px;color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n</div>\r\n<br />\r\n<div style=\"margin:0px;padding:0px;color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	</div>\r\n<div class=\"tz-figure\" style=\"margin:10px 0px 0px;padding:4px;border:1px solid #CCCCCC;background-color:#FFFFFF;text-align:center;color:#333333;font-family:\" font-size:14px;\"=\"\">\r\n	<div class=\"x-loaded\" style=\"margin:0px;padding:0px;border:0px;background-color:#333333;\">\r\n		<img id=\"img-0-1\" src=\"https://club2.autoimg.cn/album/g30/M01/FE/C7/userphotos/2018/09/07/14/500_ChsEoFuSFPiAZJWhABDr56osTlM782.jpg\" /> \r\n	</div>\r\n	<div style=\"margin:0px;padding:0px;\">\r\n	</div>\r\n	<div class=\"description\" style=\"margin:0px;padding:10px;background-color:#333333;color:#FFFFFF;text-align:left;\">\r\n		在4S车库验车，拍<span style=\"font-family:myfont;\"></span>第一张照片\r\n	</div>\r\n	<div style=\"margin:0px;padding:0px;\">\r\n	</div>\r\n</div>\r\n<div style=\"margin:0px;padding:0px;color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	</div>\r\n<br />\r\n<div style=\"margin:0px;padding:0px;color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n</div>\r\n<div class=\"tz-figure\" style=\"margin:10px 0px 0px;padding:4px;border:1px solid #CCCCCC;background-color:#FFFFFF;text-align:center;color:#333333;font-family:\" font-size:14px;\"=\"\">\r\n<div class=\"x-loaded\" style=\"margin:0px;padding:0px;border:0px;background-color:#333333;\">\r\n	<img id=\"img-0-2\" src=\"https://club2.autoimg.cn/album/g2/M00/BD/C8/userphotos/2018/09/07/14/500_ChsEmluSFY2Aa9jQABFe0k-LPYw238.jpg\" /> \r\n</div>\r\n<div style=\"margin:0px;padding:0px;\">\r\n</div>\r\n<div class=\"description\" style=\"margin:0px;padding:10px;background-color:#333333;color:#FFFFFF;text-align:left;\">\r\n	新车5公里\r\n</div>\r\n<div style=\"margin:0px;padding:0px;\">\r\n</div>\r\n	</div>\r\n<div style=\"margin:0px;padding:0px;color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n</div>\r\n<br />\r\n<div style=\"margin:0px;padding:0px;color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	</div>\r\n<div class=\"tz-figure\" style=\"margin:10px 0px 0px;padding:4px;border:1px solid #CCCCCC;background-color:#FFFFFF;text-align:center;color:#333333;font-family:\" font-size:14px;\"=\"\">\r\n	<div class=\"x-loaded\" style=\"margin:0px;padding:0px;border:0px;background-color:#333333;\">\r\n		<img id=\"img-0-3\" src=\"https://club2.autoimg.cn/album/g30/M01/FE/CD/userphotos/2018/09/07/14/500_ChsEoFuSFdeAAEgMAAG6frgJ-_I083.jpg\" /> \r\n	</div>\r\n	<div style=\"margin:0px;padding:0px;\">\r\n	</div>\r\n	<div class=\"description\" style=\"margin:0px;padding:10px;background-color:#333333;color:#FFFFFF;text-align:left;\">\r\n		开<span style=\"font-family:myfont;\"></span>300公里高速到家时<span style=\"font-family:myfont;\"></span>油耗\r\n	</div>\r\n	<div style=\"margin:0px;padding:0px;\">\r\n	</div>\r\n</div>\r\n<div style=\"margin:0px;padding:0px;color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n	</div>\r\n<br />\r\n<div style=\"margin:0px;padding:0px;color:#333333;font-family:\" font-size:14px;background-color:#ffffff;\"=\"\">\r\n</div>\r\n<div class=\"tz-figure\" style=\"margin:10px 0px 0px;padding:4px;border:1px solid #CCCCCC;background-color:#FFFFFF;text-align:center;color:#333333;font-family:\" font-size:14px;\"=\"\">\r\n<div class=\"x-loaded\" style=\"margin:0px;padding:0px;border:0px;background-color:#333333;\">\r\n	<img id=\"img-0-4\" src=\"https://club2.autoimg.cn/album/g3/M09/D4/CD/userphotos/2018/09/07/14/500_ChcCRVuSFjOAEMrHAA3nOf9MsE8477.jpg\" /> \r\n</div>\r\n<div style=\"margin:0px;padding:0px;\">\r\n</div>\r\n<div class=\"description\" style=\"margin:0px;padding:10px;background-color:#333333;color:#FFFFFF;text-align:left;\">\r\n	前脸，进气格栅<span style=\"font-family:myfont;\"></span>镀铬条与<span style=\"font-family:myfont;\"></span>灯连在一起，横向张力十足，让车看起来很宽，发动机盖两边隆起，加上两条不<span style=\"font-family:myfont;\"></span>很明显<span style=\"font-family:myfont;\"></span>线，不会显<span style=\"font-family:myfont;\"></span>很突兀，很有力量<span style=\"font-family:myfont;\"></span>感觉\r\n</div>\r\n	</div>', '/uploads/image/20180903/5b8cf1c3990d7.jpg', 'adaf梵蒂冈87,&nbsp;', '#FF0000', '1', '0', '/uploads/image/20180911/5b977c00a006f.jpg', '/uploads/image/20180911/5b977c54223d6.jpg', '/uploads/image/20180911/5b977bfbe3d51.jpg', '刘琪');

-- ----------------------------
-- Table structure for ke_article_cate
-- ----------------------------
DROP TABLE IF EXISTS `ke_article_cate`;
CREATE TABLE `ke_article_cate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(255) DEFAULT NULL COMMENT '文章类型名称',
  `c_icon` varchar(255) DEFAULT NULL,
  `c_sort` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `c_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ke_article_cate
-- ----------------------------
INSERT INTO `ke_article_cate` VALUES ('4', '汽车保养', null, '0', null);
INSERT INTO `ke_article_cate` VALUES ('3', '汽车购买', null, '3', null);
INSERT INTO `ke_article_cate` VALUES ('5', '汽车二手交易', '', '3', '');
INSERT INTO `ke_article_cate` VALUES ('1', '推荐', null, '0', null);
INSERT INTO `ke_article_cate` VALUES ('10', '汽车买卖', '', '3', '');
INSERT INTO `ke_article_cate` VALUES ('11', '资讯列表3', '/uploads/image/20180910/5b96593aa39d4.jpg', '0', null);
INSERT INTO `ke_article_cate` VALUES ('12', '33333', '', '0', '');

-- ----------------------------
-- Table structure for ke_user
-- ----------------------------
DROP TABLE IF EXISTS `ke_user`;
CREATE TABLE `ke_user` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `headimg` varchar(120) DEFAULT '/uploads/default/head.png' COMMENT '用户名头像路径',
  `nicheng` varchar(60) DEFAULT NULL COMMENT '昵称',
  `phone` varchar(20) DEFAULT NULL COMMENT '手机号 账号',
  `password` char(32) DEFAULT NULL COMMENT '密码',
  `sex` tinyint(1) unsigned DEFAULT '1' COMMENT '1:男 2：女',
  `address` varchar(180) DEFAULT '' COMMENT '所在地',
  `yue` decimal(20,2) DEFAULT '0.00' COMMENT '押金余额',
  `datetime` datetime DEFAULT NULL COMMENT '注册时间',
  `qiyong` tinyint(1) unsigned DEFAULT '1' COMMENT '1:启用 2：禁用',
  `status` tinyint(1) DEFAULT '0',
  `accessToken` varchar(100) DEFAULT NULL COMMENT '访问token',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`nicheng`),
  UNIQUE KEY `tel` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ke_user
-- ----------------------------
INSERT INTO `ke_user` VALUES ('49', '', '测试', '10086', 'e10adc3949ba59abbe56e057f20f883e', '1', '', '0.00', '2018-11-02 12:13:18', '1', '0', null);

-- ----------------------------
-- Table structure for ke_user_order
-- ----------------------------
DROP TABLE IF EXISTS `ke_user_order`;
CREATE TABLE `ke_user_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1' COMMENT '1为订单消费，2为充值缴费，3为余额支付',
  `order_num` varchar(20) DEFAULT NULL COMMENT '订单编号，唯一',
  `orderid` int(10) DEFAULT NULL COMMENT '对应订单id',
  `money` decimal(10,2) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '0未支付，1为支付',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ordernum` (`order_num`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ke_user_order
-- ----------------------------
INSERT INTO `ke_user_order` VALUES ('1', '45', '1', '201808251445383353', null, '15202.00', '1535179538', '0');
INSERT INTO `ke_user_order` VALUES ('2', '45', '2', '201808251458277391', null, '123123.00', '1535180307', '0');
INSERT INTO `ke_user_order` VALUES ('3', '45', '2', '201808251459215176', null, '123123.00', '1535180361', '0');
INSERT INTO `ke_user_order` VALUES ('4', '45', '1', '201808281118027320', null, '800000.00', '1535426282', '0');
INSERT INTO `ke_user_order` VALUES ('5', '45', '3', '201808281118208152', null, '800000.00', '1535426300', '1');
INSERT INTO `ke_user_order` VALUES ('6', '45', '1', '201808281137133675', null, '800000.00', '1535427433', '0');
INSERT INTO `ke_user_order` VALUES ('7', '45', '3', '201808281137152768', null, '800000.00', '1535427435', '1');
INSERT INTO `ke_user_order` VALUES ('8', '45', '1', '201808281145504897', null, '800000.00', '1535427950', '0');
INSERT INTO `ke_user_order` VALUES ('9', '45', '3', '201808281145536787', null, '800000.00', '1535427953', '1');
INSERT INTO `ke_user_order` VALUES ('10', '32', '2', '2018102349979957', null, '0.01', '1540254865', '0');
INSERT INTO `ke_user_order` VALUES ('11', null, '2', '201810231700228831', null, '0.01', '1540285222', '0');
INSERT INTO `ke_user_order` VALUES ('12', null, '2', '201810231700405157', null, '0.01', '1540285240', '0');
INSERT INTO `ke_user_order` VALUES ('13', '32', '2', '201810231705457127', null, '0.01', '1540285545', '0');
INSERT INTO `ke_user_order` VALUES ('14', '32', '2', '201810231708254348', null, '0.01', '1540285705', '0');
INSERT INTO `ke_user_order` VALUES ('15', '32', '2', '201810231745023328', null, '0.01', '1540287902', '0');
INSERT INTO `ke_user_order` VALUES ('16', '32', '2', '201810231745187041', null, '0.01', '1540287918', '0');
INSERT INTO `ke_user_order` VALUES ('17', '32', '2', '201810231746166240', null, '0.01', '1540287976', '0');
INSERT INTO `ke_user_order` VALUES ('18', '32', '2', '201810231746175030', null, '0.01', '1540287977', '0');
INSERT INTO `ke_user_order` VALUES ('19', '32', '2', '201810231746184798', null, '0.01', '1540287978', '0');
INSERT INTO `ke_user_order` VALUES ('20', '32', '2', '201810231746182678', null, '0.01', '1540287978', '0');
INSERT INTO `ke_user_order` VALUES ('21', '32', '2', '201810231746182186', null, '0.01', '1540287978', '0');
INSERT INTO `ke_user_order` VALUES ('22', '32', '2', '201810231746192612', null, '0.01', '1540287979', '0');
INSERT INTO `ke_user_order` VALUES ('23', '32', '2', '201810231746321810', null, '0.01', '1540287992', '0');
INSERT INTO `ke_user_order` VALUES ('24', '32', '2', '201810231746326317', null, '0.01', '1540287992', '0');
INSERT INTO `ke_user_order` VALUES ('25', '32', '2', '201810231746327410', null, '0.01', '1540287992', '0');
INSERT INTO `ke_user_order` VALUES ('26', '32', '2', '201810231746391046', null, '0.01', '1540287999', '0');
INSERT INTO `ke_user_order` VALUES ('27', '32', '2', '201810231746401621', null, '0.01', '1540288000', '0');
INSERT INTO `ke_user_order` VALUES ('28', '32', '2', '201810231746408280', null, '0.01', '1540288000', '0');
INSERT INTO `ke_user_order` VALUES ('29', '32', '2', '201810231746411150', null, '0.01', '1540288001', '0');
INSERT INTO `ke_user_order` VALUES ('30', '32', '2', '201810231746419498', null, '0.01', '1540288001', '0');
INSERT INTO `ke_user_order` VALUES ('31', '32', '2', '201810231746415578', null, '0.01', '1540288001', '0');
INSERT INTO `ke_user_order` VALUES ('32', '32', '2', '201810231746413475', null, '0.01', '1540288001', '0');
INSERT INTO `ke_user_order` VALUES ('33', '32', '2', '201810231746416333', null, '0.01', '1540288001', '0');
INSERT INTO `ke_user_order` VALUES ('34', '32', '2', '201810231748573736', null, '0.01', '1540288137', '0');
INSERT INTO `ke_user_order` VALUES ('35', '32', '2', '201810231749262238', null, '0.01', '1540288166', '0');
INSERT INTO `ke_user_order` VALUES ('36', '32', '2', '201810231749334162', null, '0.01', '1540288173', '0');
INSERT INTO `ke_user_order` VALUES ('37', '32', '2', '201810231750132896', null, '0.01', '1540288213', '0');
INSERT INTO `ke_user_order` VALUES ('38', '32', '2', '201810231750207283', null, '0.01', '1540288220', '0');
INSERT INTO `ke_user_order` VALUES ('39', '32', '2', '201810231750258523', null, '0.01', '1540288225', '0');
INSERT INTO `ke_user_order` VALUES ('40', '32', '2', '201810231750257601', null, '0.01', '1540288225', '0');
INSERT INTO `ke_user_order` VALUES ('41', '32', '2', '201810231750253483', null, '0.01', '1540288225', '0');
INSERT INTO `ke_user_order` VALUES ('42', '32', '2', '201810231750259464', null, '0.01', '1540288225', '0');
INSERT INTO `ke_user_order` VALUES ('43', '32', '2', '201810231750256207', null, '0.01', '1540288225', '0');
INSERT INTO `ke_user_order` VALUES ('44', '32', '2', '201810231750497724', null, '0.01', '1540288249', '0');
INSERT INTO `ke_user_order` VALUES ('45', '32', '2', '201810231750496031', null, '0.01', '1540288249', '0');
INSERT INTO `ke_user_order` VALUES ('46', '32', '2', '201810231750499752', null, '0.01', '1540288249', '0');
INSERT INTO `ke_user_order` VALUES ('47', '32', '2', '201810231750503500', null, '0.01', '1540288250', '0');
INSERT INTO `ke_user_order` VALUES ('48', '32', '2', '201810231750509505', null, '0.01', '1540288250', '0');
INSERT INTO `ke_user_order` VALUES ('49', '32', '2', '201810231750501301', null, '0.01', '1540288250', '0');
INSERT INTO `ke_user_order` VALUES ('50', '32', '2', '201810231750505321', null, '0.01', '1540288250', '0');
INSERT INTO `ke_user_order` VALUES ('51', '32', '2', '201810231750506022', null, '0.01', '1540288250', '0');
INSERT INTO `ke_user_order` VALUES ('52', '32', '2', '201810231751029171', null, '0.01', '1540288262', '0');
INSERT INTO `ke_user_order` VALUES ('53', '32', '2', '201810231751031861', null, '0.01', '1540288263', '0');
INSERT INTO `ke_user_order` VALUES ('54', '32', '2', '201810231751284208', null, '0.01', '1540288288', '0');
INSERT INTO `ke_user_order` VALUES ('55', '32', '2', '201810231751309934', null, '0.01', '1540288290', '0');
INSERT INTO `ke_user_order` VALUES ('56', '32', '2', '201810231752244577', null, '0.01', '1540288344', '0');
INSERT INTO `ke_user_order` VALUES ('57', '32', '2', '201810231752242829', null, '0.01', '1540288344', '0');
INSERT INTO `ke_user_order` VALUES ('58', '32', '2', '201810231753306334', null, '0.01', '1540288410', '0');
INSERT INTO `ke_user_order` VALUES ('59', '32', '2', '201810231753557676', null, '0.01', '1540288435', '0');
INSERT INTO `ke_user_order` VALUES ('60', '32', '2', '201810231754026659', null, '0.01', '1540288442', '0');
INSERT INTO `ke_user_order` VALUES ('61', '32', '2', '201810231755226445', null, '0.01', '1540288522', '0');
INSERT INTO `ke_user_order` VALUES ('62', '32', '2', '201810231755524102', null, '0.01', '1540288552', '0');
INSERT INTO `ke_user_order` VALUES ('63', '32', '2', '201810231757208937', null, '0.01', '1540288640', '0');
INSERT INTO `ke_user_order` VALUES ('64', '32', '2', '201810231757212955', null, '0.01', '1540288641', '0');
INSERT INTO `ke_user_order` VALUES ('65', '32', '2', '201810231757212993', null, '0.01', '1540288641', '0');
INSERT INTO `ke_user_order` VALUES ('66', '32', '2', '201810231757212127', null, '0.01', '1540288641', '0');
INSERT INTO `ke_user_order` VALUES ('67', '32', '2', '201810231757513920', null, '0.01', '1540288671', '0');
INSERT INTO `ke_user_order` VALUES ('68', '32', '2', '201810231758365642', null, '0.01', '1540288716', '0');
INSERT INTO `ke_user_order` VALUES ('69', '32', '2', '201810231759425607', null, '0.01', '1540288782', '0');
INSERT INTO `ke_user_order` VALUES ('70', '32', '2', '201810231759555519', null, '0.01', '1540288795', '0');
INSERT INTO `ke_user_order` VALUES ('71', '32', '2', '201810231800347651', null, '0.01', '1540288834', '0');
INSERT INTO `ke_user_order` VALUES ('72', '32', '2', '201810231801438442', null, '0.01', '1540288903', '0');
INSERT INTO `ke_user_order` VALUES ('73', '32', '2', '201810231802005738', null, '0.01', '1540288920', '0');
INSERT INTO `ke_user_order` VALUES ('74', '32', '2', '201810231817101849', null, '0.01', '1540289830', '0');
INSERT INTO `ke_user_order` VALUES ('75', '32', '2', '201810231817235790', null, '0.01', '1540289843', '0');
INSERT INTO `ke_user_order` VALUES ('76', '32', '2', '201810231820308628', null, '0.01', '1540290030', '0');
INSERT INTO `ke_user_order` VALUES ('77', '32', '2', '201810231823357769', null, '0.01', '1540290215', '0');
INSERT INTO `ke_user_order` VALUES ('78', '32', '2', '201810231825203098', null, '0.01', '1540290320', '0');
INSERT INTO `ke_user_order` VALUES ('79', '32', '2', '201810231825238849', null, '0.01', '1540290323', '0');
INSERT INTO `ke_user_order` VALUES ('80', '32', '2', '201810231827334457', null, '0.01', '1540290453', '0');
INSERT INTO `ke_user_order` VALUES ('81', '32', '2', '201810231827349980', null, '0.01', '1540290454', '0');

-- ----------------------------
-- Table structure for ke_xitong
-- ----------------------------
DROP TABLE IF EXISTS `ke_xitong`;
CREATE TABLE `ke_xitong` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `index` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `des` varchar(250) DEFAULT NULL,
  `keywords` varchar(250) DEFAULT NULL,
  `content` text COMMENT '奖励机制',
  `tel` varchar(250) DEFAULT NULL,
  `wx` varchar(250) DEFAULT NULL,
  `wxecode` varchar(255) DEFAULT NULL,
  `appver` varchar(255) DEFAULT NULL,
  `hongbao` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ke_xitong
-- ----------------------------
INSERT INTO `ke_xitong` VALUES ('1', '赟个车', '赟个车', '赟个车', '赟个车', '', '66666666666', '66666666666', '', '1.8.1', '0');

-- ----------------------------
-- Table structure for ke_yue
-- ----------------------------
DROP TABLE IF EXISTS `ke_yue`;
CREATE TABLE `ke_yue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `y_type` varchar(30) DEFAULT NULL COMMENT '类型',
  `y_source` varchar(100) DEFAULT NULL COMMENT '来源',
  `y_time` datetime DEFAULT NULL COMMENT '时间',
  `y_price` int(10) unsigned DEFAULT NULL COMMENT '消费金额',
  `y_uid` int(8) unsigned DEFAULT NULL COMMENT '所属人',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ke_yue
-- ----------------------------
INSERT INTO `ke_yue` VALUES ('1', '1', '1231', '2018-08-23 18:26:29', '100', '45');
INSERT INTO `ke_yue` VALUES ('2', '1', '14', '2018-08-28 11:18:20', '800000', '45');
INSERT INTO `ke_yue` VALUES ('3', '1', '15', '2018-08-28 11:37:15', '800000', '45');
INSERT INTO `ke_yue` VALUES ('4', '1', '16', '2018-08-28 11:45:53', '800000', '45');
