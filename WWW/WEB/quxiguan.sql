-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: wot3ak48.2375lan.dnstoo.com:3306
-- Generation Time: 2019-03-26 17:03:48
-- 服务器版本： 5.5.35.t15-log
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quxiguan`
--

-- --------------------------------------------------------

--
-- 表的结构 `f_activity`
--

CREATE TABLE `f_activity` (
  `id` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '2：满减3:折扣',
  `is_all` tinyint(1) DEFAULT '1' COMMENT '1：是否全场1：是2：不是',
  `shop_id` int(11) DEFAULT NULL,
  `shop_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '店铺',
  `title` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '标题',
  `max_price` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '满足金额/折扣',
  `min_price` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '减免金额'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='活动';

--
-- 转存表中的数据 `f_activity`
--

INSERT INTO `f_activity` (`id`, `type`, `is_all`, `shop_id`, `shop_name`, `title`, `max_price`, `min_price`) VALUES
(1, 2, 1, 1, '自营商店', '本店活动所有商品满500减50', '500', '30'),
(2, 3, 2, 2, '小王商店', '本店活动所有商品5折优惠', '5', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `f_admin`
--

CREATE TABLE `f_admin` (
  `id` int(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `pass` varchar(32) DEFAULT NULL COMMENT '铭文密码',
  `cat` tinyint(1) DEFAULT NULL,
  `regdate` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1正常 2禁用',
  `xingming` varchar(20) DEFAULT NULL,
  `dianhua` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `f_admin`
--

INSERT INTO `f_admin` (`id`, `name`, `password`, `pass`, `cat`, `regdate`, `status`, `xingming`, `dianhua`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '123456', 1, 1501852488, 1, 'admin', '13888888888'),
(84693447, '小王专卖店', 'e10adc3949ba59abbe56e057f20f883e', '123456', 9, 1552374918, 1, '小王', '13989989897');

-- --------------------------------------------------------

--
-- 表的结构 `f_admin_action`
--

CREATE TABLE `f_admin_action` (
  `id` int(20) NOT NULL,
  `fid` int(20) DEFAULT NULL,
  `m` varchar(20) DEFAULT NULL,
  `a` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `f_admin_action`
--

INSERT INTO `f_admin_action` (`id`, `fid`, `m`, `a`, `name`, `position`, `icon`) VALUES
(1, 0, 'Administrator', 'index', '管理员', 1531903044, 'icon-addressbook_fill'),
(3, 2, 'Administrator', 'add', '添加管理员', 1538969738, ''),
(4, 2, 'Administrator', 'cat', '角色管理', 14, ''),
(9, 0, 'Data', 'index', '数据管理', 1542009413, 'icon-shujuku'),
(10, 0, 'Menu', 'index', '菜单管理', 1537411349, 'icon-stealth'),
(11, 10, 'Menu', 'add', '添加菜单', 0, ''),
(12, 9, 'Data', 'backup', '数据备份', 1, ''),
(143, 0, 'Banner', 'index', '轮播图', 1528358503, 'icon-browse_fill'),
(133, 2, 'Administrator', 'cat_add', '添加角色', 10, ''),
(147, 0, 'Goods', 'index', '商品管理', 1542779465, 'icon-iconfontcaidan'),
(163, 0, 'Shop', 'index', '店铺管理', 1550023574, 'icon-homepage'),
(162, 0, 'system', 'index', '系统设置', 1550017840, 'icon-setup'),
(164, 163, 'Shop', 'cate', '店铺属性', 1550038888, ''),
(165, 147, 'Goods', 'cate', '商品分类', 1550628785, ''),
(166, 162, 'system', 'link', '联系我们', 1550822608, ''),
(167, 0, 'Favorable', 'index', '优惠管理', 1552355120, 'icon-financial_fill'),
(168, 167, 'Favorable', 'activity', '活动管理', 1552357636, ''),
(169, 0, 'Kd', 'index', '物流管理', 1553011823, 'icon-browse_fill'),
(170, 0, 'Order', 'index', '订单管理', 1553013324, 'icon-createtask'),
(171, 162, 'system', 'news', '消息管理', 1553014606, ''),
(172, 162, 'system', 'bank', '银行列表', 1553525927, ''),
(173, 0, 'Prize', 'index', '奖品管理', 1553528585, 'icon-tools'),
(174, 173, 'Prize', 'turntable', '转盘列表', 1553563182, ''),
(175, 173, 'Prize', 'ranking', '排行奖励', 1553566600, ''),
(176, 0, 'Energy', 'index', '能量商城', 1553581226, 'icon-document');

-- --------------------------------------------------------

--
-- 表的结构 `f_admin_auth`
--

CREATE TABLE `f_admin_auth` (
  `id` int(20) NOT NULL,
  `role_id` int(20) DEFAULT NULL,
  `action_id` int(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `f_admin_auth`
--

INSERT INTO `f_admin_auth` (`id`, `role_id`, `action_id`) VALUES
(3365, 2, 147),
(1526, 4, 35),
(1525, 4, 56),
(1524, 4, 55),
(1523, 4, 54),
(1522, 4, 53),
(1521, 4, 52),
(1520, 4, 51),
(1519, 4, 50),
(1518, 4, 49),
(1517, 4, 48),
(2308, 5, 95),
(2306, 5, 0),
(2307, 5, 29),
(2310, 5, 82),
(1996, 6, 90),
(1993, 6, 0),
(1994, 6, 33),
(1995, 6, 39),
(2935, 3, 0),
(1516, 4, 28),
(1515, 4, 71),
(1514, 4, 62),
(1513, 4, 61),
(1512, 4, 60),
(1511, 4, 59),
(1510, 4, 58),
(1509, 4, 57),
(1508, 4, 70),
(1507, 4, 67),
(1506, 4, 66),
(1505, 4, 65),
(2311, 5, 89),
(2312, 5, 100),
(2309, 5, 92),
(1504, 4, 64),
(1503, 4, 63),
(1502, 4, 26),
(1501, 4, 25),
(1500, 4, 47),
(1499, 4, 46),
(1498, 4, 27),
(1497, 4, 16),
(1496, 4, 15),
(1495, 4, 77),
(1494, 4, 76),
(1493, 4, 75),
(1492, 4, 74),
(1491, 4, 73),
(1490, 4, 72),
(1489, 4, 0),
(1527, 4, 33),
(2936, 3, 2),
(3364, 2, 0),
(3806, 1, 175),
(3805, 1, 174),
(3804, 1, 173),
(3803, 1, 170),
(3802, 1, 169),
(3801, 1, 168),
(3800, 1, 167),
(3799, 1, 164),
(3798, 1, 163),
(3797, 1, 172),
(3366, 2, 148),
(3796, 1, 171),
(3795, 1, 166),
(3794, 1, 162),
(3642, 9, 147),
(3643, 9, 165),
(3793, 1, 165),
(3792, 1, 147),
(3641, 9, 0),
(3791, 1, 11),
(3790, 1, 10),
(3789, 1, 3),
(3788, 1, 4),
(3787, 1, 133),
(3786, 1, 1),
(3785, 1, 143),
(3784, 1, 0),
(3807, 1, 176);

-- --------------------------------------------------------

--
-- 表的结构 `f_admin_cat`
--

CREATE TABLE `f_admin_cat` (
  `id` int(10) NOT NULL,
  `fid` int(10) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '状态：1--启用；2--禁用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `f_admin_cat`
--

INSERT INTO `f_admin_cat` (`id`, `fid`, `name`, `date`, `status`) VALUES
(1, 0, '超级管理员', '2018-10-08 12:28:08', NULL),
(9, 0, '商家管理员', '2018-12-04 09:55:13', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `f_bank`
--

CREATE TABLE `f_bank` (
  `id` int(12) NOT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='银行卡';

--
-- 转存表中的数据 `f_bank`
--

INSERT INTO `f_bank` (`id`, `bank_name`) VALUES
(1, '工商银行'),
(2, '招商银行'),
(3, '交通银行');

-- --------------------------------------------------------

--
-- 表的结构 `f_banner`
--

CREATE TABLE `f_banner` (
  `id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0首页,1:培训页',
  `img` varchar(255) NOT NULL,
  `link` varchar(10) NOT NULL COMMENT '连接id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '使用状态1：使用-1：禁用',
  `dateline` datetime NOT NULL COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='轮播图';

--
-- 转存表中的数据 `f_banner`
--

INSERT INTO `f_banner` (`id`, `type`, `img`, `link`, `status`, `dateline`) VALUES
(1, 0, '/uploads/image/20190228/5c7752f4d7694.jpg', '1', 1, '2018-11-19 15:35:52');

-- --------------------------------------------------------

--
-- 表的结构 `f_clock`
--

CREATE TABLE `f_clock` (
  `id` int(11) NOT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `type` smallint(6) NOT NULL DEFAULT '0' COMMENT '类型 1  滴水穿石 2 如履薄冰 3 歃血为盟  4 打卡时间设置 5 分配比例',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '签到开始时间',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '签到结束时间',
  `sunum` int(11) NOT NULL DEFAULT '0' COMMENT '签到成功人数',
  `finum` int(11) NOT NULL DEFAULT '0' COMMENT '签到失败人数',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '1 启用 2 停用',
  `portion` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分成比例'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='早起打卡相关设置表';

--
-- 转存表中的数据 `f_clock`
--

INSERT INTO `f_clock` (`id`, `addtime`, `uptime`, `type`, `starttime`, `endtime`, `sunum`, `finum`, `status`, `portion`) VALUES
(1, 1551686302, 1551686361, 4, 1551684235, 1551696520, 0, 0, 1, '0.00'),
(2, 1551755794, 0, 1, 0, 0, 10000, 500, 1, '0.00'),
(3, 1551755839, 0, 2, 0, 0, 10000, 500, 1, '0.00'),
(4, 1551755843, 0, 3, 0, 0, 10000, 500, 1, '0.00'),
(5, 1551755846, 0, 5, 0, 0, 0, 0, 1, '0.80');

-- --------------------------------------------------------

--
-- 表的结构 `f_collection`
--

CREATE TABLE `f_collection` (
  `id` int(12) NOT NULL,
  `uid` int(12) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1:商品2：店铺',
  `collect` int(12) DEFAULT NULL COMMENT '收藏ID',
  `logo` varchar(255) DEFAULT NULL COMMENT '图片',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `dateline` int(10) NOT NULL COMMENT '收藏时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏表';

--
-- 转存表中的数据 `f_collection`
--

INSERT INTO `f_collection` (`id`, `uid`, `type`, `collect`, `logo`, `title`, `price`, `dateline`) VALUES
(11, 3, 1, 1, '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 1552550545),
(20, 5, 1, 1, NULL, NULL, NULL, 1553259058),
(21, 7, 1, 4, '/uploads/image/20190228/5c779d3cb3e02.jpg', '小王', '299.00', 1553488520),
(18, 3, 1, 2, '/uploads/image/20190228/5c77980c90b16.jpg', '被子', '266.00', 1552823301),
(16, 1, 1, 1, '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 1552731813);

-- --------------------------------------------------------

--
-- 表的结构 `f_coupon`
--

CREATE TABLE `f_coupon` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '0是都可用优惠券',
  `shop_logo` varchar(255) DEFAULT NULL COMMENT '商店logo',
  `shop_name` varchar(50) DEFAULT NULL COMMENT '商店名称',
  `name` varchar(50) DEFAULT NULL COMMENT '名字',
  `mz_money` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '满足金额',
  `yh_money` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '优惠金额',
  `start_time` varchar(20) NOT NULL COMMENT '开始时间',
  `end_time` varchar(20) NOT NULL COMMENT '结束时间',
  `dateline` datetime NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券';

--
-- 转存表中的数据 `f_coupon`
--

INSERT INTO `f_coupon` (`id`, `shop_id`, `shop_logo`, `shop_name`, `name`, `mz_money`, `yh_money`, `start_time`, `end_time`, `dateline`) VALUES
(1, 1, NULL, NULL, '自营商城优惠券', '100', '50', '2019-03-03', '2019-03-30', '2019-03-07 00:00:00'),
(2, 1, NULL, NULL, '小王商城优惠券', '100', '20', '2019-03-03', '2019-03-30', '2019-03-07 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `f_coupon_del`
--

CREATE TABLE `f_coupon_del` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `coupon_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='删除优惠券';

-- --------------------------------------------------------

--
-- 表的结构 `f_energy_logo`
--

CREATE TABLE `f_energy_logo` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `content` varchar(50) DEFAULT NULL COMMENT '能量币',
  `time` varchar(30) NOT NULL COMMENT '时间筛选',
  `dateline` int(10) DEFAULT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='能量币明细';

-- --------------------------------------------------------

--
-- 表的结构 `f_energy_mart`
--

CREATE TABLE `f_energy_mart` (
  `id` int(12) NOT NULL,
  `cate_id` int(12) DEFAULT NULL,
  `goods_id` int(12) DEFAULT NULL,
  `energy_than` varchar(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='能量商城';

--
-- 转存表中的数据 `f_energy_mart`
--

INSERT INTO `f_energy_mart` (`id`, `cate_id`, `goods_id`, `energy_than`) VALUES
(1, 1, 3, '20');

-- --------------------------------------------------------

--
-- 表的结构 `f_feedback`
--

CREATE TABLE `f_feedback` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` varchar(200) DEFAULT NULL COMMENT '反馈内容',
  `dateline` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='意见反馈';

--
-- 转存表中的数据 `f_feedback`
--

INSERT INTO `f_feedback` (`id`, `uid`, `content`, `dateline`) VALUES
(1, 1, '希望有更多一点商品', '2019-03-20 10:03:18'),
(2, 1, '希望有更多一点商品', '2019-03-20 10:03:21'),
(3, 1, '希望有更多一点商品', '2019-03-20 10:03:47'),
(4, 1, '希望有更多一点商品', '2019-03-20 10:04:20'),
(5, 1, '希望有更多一点商品', '2019-03-20 10:04:47');

-- --------------------------------------------------------

--
-- 表的结构 `f_feedimg`
--

CREATE TABLE `f_feedimg` (
  `id` int(12) NOT NULL,
  `f_id` int(12) UNSIGNED ZEROFILL DEFAULT NULL COMMENT '反馈ID',
  `img` varchar(255) DEFAULT NULL COMMENT '反馈图片'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='反馈图片';

-- --------------------------------------------------------

--
-- 表的结构 `f_gift`
--

CREATE TABLE `f_gift` (
  `id` int(10) NOT NULL,
  `type` tinyint(5) DEFAULT NULL COMMENT '1:物品2：能量币3：会员券4.优惠券',
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `max_price` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `min_price` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='奖品表';

--
-- 转存表中的数据 `f_gift`
--

INSERT INTO `f_gift` (`id`, `type`, `name`, `max_price`, `min_price`, `img`) VALUES
(1, 3, '年度会员', '1', '', '/uploads/image/20190222/5c6fa857acb2a.jpg'),
(2, 2, '能量币', '100', NULL, '/uploads/image/20190222/5c6fa857acb2a.jpg'),
(3, 1, '耳机一个', '耳机', NULL, '/uploads/image/20190222/5c6fa857acb2a.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `f_goods`
--

CREATE TABLE `f_goods` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL DEFAULT '1' COMMENT '1:属于自营商品',
  `cate_id` int(11) DEFAULT NULL COMMENT '商城主分类ID',
  `zcate_id` int(11) DEFAULT NULL COMMENT '商城子分类ID',
  `size_id` int(11) DEFAULT NULL COMMENT '商品主分类ID',
  `zsize_id` int(11) DEFAULT NULL COMMENT '商品子分类ID',
  `shop_name` varchar(50) NOT NULL COMMENT '商店名字',
  `goods_name` varchar(50) DEFAULT NULL COMMENT '商品名字',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `yh_price` decimal(10,2) DEFAULT NULL COMMENT '优惠价格',
  `sn` varchar(50) DEFAULT NULL COMMENT '商品编号',
  `postage` int(5) NOT NULL DEFAULT '0' COMMENT '0：免邮费邮费',
  `is_activity` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1:无2：满减3：折扣',
  `activity_id` int(11) DEFAULT NULL COMMENT '活动ID',
  `activity_title` varchar(100) DEFAULT NULL COMMENT '活动标题',
  `is_new` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1不是2：是--新品',
  `is_hot` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1不是2：是--热卖',
  `addr` varchar(50) DEFAULT NULL COMMENT '地址',
  `img1` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img2` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img3` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `img4` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `content` varchar(255) DEFAULT NULL COMMENT '商品详情',
  `sales` int(20) NOT NULL DEFAULT '0' COMMENT '销量',
  `collect_num` int(20) NOT NULL DEFAULT '0' COMMENT '收藏人数',
  `top` tinyint(1) DEFAULT '1' COMMENT '是否推荐1:否2:是',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否上架1:是2:否',
  `dateline` int(10) DEFAULT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品列表';

--
-- 转存表中的数据 `f_goods`
--

INSERT INTO `f_goods` (`id`, `shop_id`, `cate_id`, `zcate_id`, `size_id`, `zsize_id`, `shop_name`, `goods_name`, `price`, `yh_price`, `sn`, `postage`, `is_activity`, `activity_id`, `activity_title`, `is_new`, `is_hot`, `addr`, `img1`, `img2`, `img3`, `img4`, `content`, `sales`, `collect_num`, `top`, `status`, `dateline`) VALUES
(2, 1, 1, 8, 1, 2, '趣习惯自营', '被子', '366.00', '266.00', '', 0, 2, 1, '全场满100减50', 2, 2, '', '/uploads/image/20190228/5c77980c90b16.jpg', '/uploads/image/20190228/5c7798148b13a.jpg', '', '', '<img src=\"/public_html/uploads/image/20190228/20190228161318_62137.jpg\" alt=\"\" />', 0, 3, 2, 1, 1551341600),
(3, 1, 1, 8, 1, 2, '趣习惯自营', '小杯子', '366.00', '199.00', '', 0, 3, 2, '全场5折', 2, 2, '', '/uploads/image/20190228/5c77987915acc.jpg', '/uploads/image/20190228/5c7798814d56d.jpg', '', '', '<img src=\"/public_html/uploads/image/20190228/20190228161506_45854.jpg\" alt=\"\" />', 0, 2, 2, 1, 1551341709),
(4, 1, 1, 8, 1, 2, '趣习惯自营', '小王', '366.00', '299.00', '', 0, 1, 1, NULL, 2, 2, '', '/uploads/image/20190228/5c779d3cb3e02.jpg', '/uploads/image/20190228/5c779d42d148e.jpg', '', '', '<img src=\"/public_html/uploads/image/20190228/20190228163524_44248.jpg\" alt=\"\" />', 0, 3, 2, 1, 1551342925),
(5, 1, 1, 8, 1, 2, '趣习惯自营', '水杯', '999.00', '666.00', '', 0, 1, 1, NULL, 2, 2, '', '/uploads/image/20190228/5c779dca9be0a.jpg', '/uploads/image/20190228/5c779dcfc7f58.jpg', '', '', '<img src=\"/public_html/uploads/image/20190228/20190228163744_37184.jpg\" alt=\"\" />', 0, 2, 2, 1, 1551343065),
(6, 1, 1, 8, 1, 2, '趣习惯自营', '奶瓶', '100.00', '99.00', '', 0, 1, 1, NULL, 2, 2, '', '/uploads/image/20190301/5c78f71218265.jpg', '', '', '', '<img src=\"/public_html/uploads/image/20190301/20190301171052_48540.jpg\" alt=\"\" />', 0, 5, 2, 1, 1551431454),
(7, 1, 1, 8, 1, 2, '趣习惯自营', '茅台', '999.00', '888.00', '', 0, 1, 1, NULL, 2, 2, '', '/uploads/image/20190301/5c78f7b44e711.jpg', '/uploads/image/20190301/5c78f7ba02ecd.jpg', '', '', '<img src=\"/public_html/uploads/image/20190301/20190301171340_74010.jpg\" alt=\"\" />', 0, 2, 2, 1, 1551431630),
(8, 1, 1, 8, 1, 2, '趣习惯自营', '小小', '366.00', '266.00', '', 0, 1, 1, NULL, 2, 2, '', '/uploads/image/20190301/5c78f7fb0be55.jpg', '/uploads/image/20190302/5c7a46d97318b.jpg', '', '', '<img src=\"/public_html/uploads/image/20190301/20190301171444_86409.jpg\" alt=\"\" />', 0, 2, 2, 1, 1551517405),
(9, 2, 1, 8, 3, 4, '小王专卖店', '小王王', '999.00', '888.00', NULL, 0, 1, 1, NULL, 2, 2, NULL, '/uploads/image/20190305/5c7e28ee39ddb.jpg', '/uploads/image/20190305/5c7e28f4bf7ee.jpg', '', '', '<img src=\"/public_html/uploads/image/20190305/20190305154501_50268.jpg\" alt=\"\" />', 0, 0, 1, 1, 1552442704);

-- --------------------------------------------------------

--
-- 表的结构 `f_goods_cate`
--

CREATE TABLE `f_goods_cate` (
  `id` int(10) NOT NULL,
  `shop_id` varchar(50) DEFAULT NULL,
  `fid` int(10) NOT NULL DEFAULT '0' COMMENT '父分类ID',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `dateline` int(11) DEFAULT NULL COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类表';

--
-- 转存表中的数据 `f_goods_cate`
--

INSERT INTO `f_goods_cate` (`id`, `shop_id`, `fid`, `name`, `dateline`) VALUES
(1, '1', 0, '女装', 1550652956),
(2, '1', 1, '羽绒服', 1550652966),
(3, '2', 0, '女装', 1552442668),
(4, '2', 3, '羽绒服', 1552442686);

-- --------------------------------------------------------

--
-- 表的结构 `f_goods_evaluate`
--

CREATE TABLE `f_goods_evaluate` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `shop_id` int(11) NOT NULL DEFAULT '1' COMMENT '商店ID',
  `u_logo` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `u_name` varchar(20) DEFAULT NULL COMMENT '用户名',
  `content` varchar(20) DEFAULT NULL COMMENT '评价内容',
  `g_logo` varchar(255) DEFAULT NULL COMMENT '商品图标',
  `g_name` varchar(50) DEFAULT NULL COMMENT '商品名称',
  `g_price` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `star` tinyint(5) DEFAULT '0' COMMENT '评星',
  `dateline` int(10) DEFAULT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品评价表';

--
-- 转存表中的数据 `f_goods_evaluate`
--

INSERT INTO `f_goods_evaluate` (`id`, `uid`, `goods_id`, `shop_id`, `u_logo`, `u_name`, `content`, `g_logo`, `g_name`, `g_price`, `star`, `dateline`) VALUES
(1, 1, 1, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 5, 1553069851),
(2, 1, 3, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190228/5c77987915acc.jpg', '小杯子', '199.00', 5, 1553069851),
(3, 1, 1, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 5, 1553070209),
(4, 1, 3, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190228/5c77987915acc.jpg', '小杯子', '199.00', 5, 1553070209),
(5, 1, 1, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 5, 1553071064),
(6, 1, 3, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190228/5c77987915acc.jpg', '小杯子', '199.00', 5, 1553071064),
(7, 1, 1, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 5, 1553071064),
(8, 1, 3, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190228/5c77987915acc.jpg', '小杯子', '199.00', 5, 1553071064),
(9, 1, 1, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 5, 1553071064),
(10, 1, 3, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190228/5c77987915acc.jpg', '小杯子', '199.00', 5, 1553071064),
(11, 1, 1, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 5, 1553071065),
(12, 1, 3, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190228/5c77987915acc.jpg', '小杯子', '199.00', 5, 1553071065),
(13, 1, 1, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 5, 1553071065),
(14, 1, 3, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190228/5c77987915acc.jpg', '小杯子', '199.00', 5, 1553071065),
(15, 1, 1, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 5, 1553071065),
(16, 1, 3, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190228/5c77987915acc.jpg', '小杯子', '199.00', 5, 1553071065),
(17, 1, 1, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190222/5c6fa857acb2a.jpg', '衣服', '266.00', 5, 1553075292),
(18, 1, 3, 1, '/upload/430621552727969.png', '只在看花成名时', '暂无评论', '/uploads/image/20190228/5c77987915acc.jpg', '小杯子', '199.00', 5, 1553075292);

-- --------------------------------------------------------

--
-- 表的结构 `f_goods_evimg`
--

CREATE TABLE `f_goods_evimg` (
  `id` int(11) NOT NULL,
  `ev_id` int(11) DEFAULT NULL COMMENT '评价ID',
  `img` varchar(255) DEFAULT NULL COMMENT '评价图'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评价图片';

--
-- 转存表中的数据 `f_goods_evimg`
--

INSERT INTO `f_goods_evimg` (`id`, `ev_id`, `img`) VALUES
(1, 1, ''),
(2, 2, ''),
(3, 3, ''),
(4, 4, ''),
(5, 5, ''),
(6, 6, ''),
(7, 7, ''),
(8, 8, ''),
(9, 9, ''),
(10, 10, ''),
(11, 11, ''),
(12, 12, ''),
(13, 13, ''),
(14, 14, ''),
(15, 15, ''),
(16, 16, ''),
(17, 17, ''),
(18, 18, '');

-- --------------------------------------------------------

--
-- 表的结构 `f_goods_size`
--

CREATE TABLE `f_goods_size` (
  `id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `color` varchar(50) NOT NULL COMMENT '颜色',
  `spec` varchar(50) DEFAULT NULL COMMENT '规格',
  `fprice` decimal(10,2) NOT NULL COMMENT '价格',
  `total` int(11) NOT NULL COMMENT '库存',
  `dateline` datetime NOT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品规格';

--
-- 转存表中的数据 `f_goods_size`
--

INSERT INTO `f_goods_size` (`id`, `goods_id`, `color`, `spec`, `fprice`, `total`, `dateline`) VALUES
(5, 2, '白色', 'M', '799.00', 100, '2019-02-28 16:13:20'),
(6, 2, '白色', 'L', '899.00', 100, '2019-02-28 16:13:20'),
(7, 3, '黑色', '23m', '0.00', 100, '2019-02-28 16:15:09'),
(8, 3, '黑色', '12m', '0.00', 10, '2019-02-28 16:15:09'),
(9, 4, '黑色', '12m', '0.00', 100, '2019-02-28 16:35:25'),
(10, 5, '白色', '13m', '0.00', 100, '2019-02-28 16:37:45'),
(13, 6, '白色', '18cm', '0.00', 100, '2019-03-01 17:10:54'),
(15, 7, '58度', '大', '0.00', 10, '2019-03-01 17:13:50'),
(17, 8, '15', '5', '0.00', 100, '2019-03-02 17:03:25'),
(19, 9, '白色', 'L', '0.00', 100, '2019-03-13 10:05:04');

-- --------------------------------------------------------

--
-- 表的结构 `f_history`
--

CREATE TABLE `f_history` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:商品搜索',
  `content` varchar(50) NOT NULL COMMENT '历史记录',
  `dateline` datetime NOT NULL COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='历史记录';

--
-- 转存表中的数据 `f_history`
--

INSERT INTO `f_history` (`id`, `uid`, `type`, `content`, `dateline`) VALUES
(3, 1, 1, '小杯子', '0000-00-00 00:00:00'),
(4, 1, 1, '衣服', '0000-00-00 00:00:00'),
(5, 5, 1, '被子', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `f_kd`
--

CREATE TABLE `f_kd` (
  `id` int(11) NOT NULL,
  `b_name` varchar(30) NOT NULL COMMENT '别名',
  `name` varchar(30) NOT NULL COMMENT '快递名',
  `logo` varchar(255) DEFAULT NULL COMMENT 'logo'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `f_kd`
--

INSERT INTO `f_kd` (`id`, `b_name`, `name`, `logo`) VALUES
(1, 'yunda', '韵达', '/uploads/image/20190320/5c911986780c1.jpg'),
(2, 'yuantong', '圆通速递', '/uploads/image/20190318/5c8f4600dd004.jpg'),
(3, 'jd', '京东', '/uploads/image/20190318/5c8f4600dd004.jpg'),
(4, 'tiantian', '天天快递', '/uploads/image/20190318/5c8f4600dd004.jpg'),
(6, 'shentong', '申通快递', '/uploads/image/20190318/5c8f4600dd004.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `f_money_logo`
--

CREATE TABLE `f_money_logo` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '资金标题',
  `money` varchar(50) DEFAULT NULL COMMENT '资金-1 +1',
  `time` varchar(30) NOT NULL COMMENT '时间筛选',
  `dateline` int(10) DEFAULT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='余额明细';

-- --------------------------------------------------------

--
-- 表的结构 `f_msg`
--

CREATE TABLE `f_msg` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `touid` int(11) NOT NULL COMMENT '发送人ID',
  `content` varchar(255) DEFAULT NULL COMMENT '发送信息',
  `status` tinyint(1) DEFAULT '0',
  `looked` tinyint(1) DEFAULT '0' COMMENT '是否查看0：未查看1：已查看',
  `date` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '1:文字2：图片3：语音'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天详情表';

--
-- 转存表中的数据 `f_msg`
--

INSERT INTO `f_msg` (`id`, `uid`, `touid`, `content`, `status`, `looked`, `date`, `type`) VALUES
(1, 7, 1, '你好', 0, 0, 1553512985, 1);

-- --------------------------------------------------------

--
-- 表的结构 `f_msg_list`
--

CREATE TABLE `f_msg_list` (
  `id` int(10) NOT NULL,
  `uid` int(10) NOT NULL COMMENT '发送人ID',
  `touid` int(10) NOT NULL COMMENT '被发送人ID',
  `content` varchar(255) NOT NULL COMMENT '发送内容',
  `date` int(10) NOT NULL COMMENT '发送时间',
  `dianpu_id` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天';

--
-- 转存表中的数据 `f_msg_list`
--

INSERT INTO `f_msg_list` (`id`, `uid`, `touid`, `content`, `date`, `dianpu_id`) VALUES
(1, 7, 1, '你好', 1553512985, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `f_oback_img`
--

CREATE TABLE `f_oback_img` (
  `id` int(12) NOT NULL,
  `back_id` int(12) DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='退款图片';

-- --------------------------------------------------------

--
-- 表的结构 `f_order`
--

CREATE TABLE `f_order` (
  `id` int(12) NOT NULL,
  `uid` int(12) DEFAULT NULL COMMENT '用户ID',
  `shop_id` int(10) DEFAULT NULL COMMENT '店铺ID',
  `type` tinyint(3) DEFAULT '1' COMMENT '是否是能量购买1：不是2：是',
  `shop_name` varchar(100) DEFAULT NULL COMMENT '商店名字',
  `u_name` varchar(20) DEFAULT NULL COMMENT '用户名',
  `u_mobile` varchar(20) DEFAULT NULL COMMENT '用户电话',
  `u_addr` varchar(100) DEFAULT NULL COMMENT '用户地址',
  `freight` int(10) DEFAULT '0' COMMENT '邮费',
  `coupon_id` int(10) DEFAULT NULL COMMENT '优惠券ID',
  `price` decimal(10,2) DEFAULT NULL COMMENT '支付金额',
  `content` varchar(100) DEFAULT '暂无留言' COMMENT '留言',
  `status` tinyint(5) DEFAULT '1' COMMENT '1:待付款2：待发货3：待收货4：待评价5：已评价6：申请退款8：同意退货9：退货完成',
  `pay_status` tinyint(2) DEFAULT '1' COMMENT '1:余额2:微信3：支付宝',
  `pay_time` int(10) DEFAULT NULL COMMENT '支付时间',
  `order_sn` varchar(50) DEFAULT NULL COMMENT '订单编号',
  `kd_name` varchar(50) DEFAULT NULL COMMENT '快递名字',
  `kd_num` varchar(50) DEFAULT NULL COMMENT '快递单号',
  `is_delete` tinyint(3) DEFAULT '1' COMMENT '1：未删除2：删除',
  `dateline` int(10) DEFAULT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表';

--
-- 转存表中的数据 `f_order`
--

INSERT INTO `f_order` (`id`, `uid`, `shop_id`, `type`, `shop_name`, `u_name`, `u_mobile`, `u_addr`, `freight`, `coupon_id`, `price`, `content`, `status`, `pay_status`, `pay_time`, `order_sn`, `kd_name`, `kd_num`, `is_delete`, `dateline`) VALUES
(1, 7, 1, 1, '趣习惯自营', '和', '17538701156', '北京北京东城区SOHO', 0, NULL, '99.00', '暂无留言', 1, 1, NULL, 'QXG1553513900406', NULL, NULL, 2, 1553513900),
(2, 7, 1, 1, '趣习惯自营', '和', '17538701156', '北京北京东城区SOHO', 0, NULL, '99.00', '暂无留言', 1, 1, NULL, 'QXG1553513901331', NULL, NULL, 2, 1553513901),
(3, 7, 1, 1, '趣习惯自营', '和', '17538701156', '北京北京东城区SOHO', 0, NULL, '99.00', '暂无留言', 1, 1, NULL, 'QXG1553513901382', NULL, NULL, 2, 1553513901),
(4, 7, 1, 1, '趣习惯自营', '和', '17538701156', '北京北京东城区SOHO', 0, NULL, '99.00', '暂无留言', 2, 1, NULL, 'QXG1553513903327', NULL, NULL, 2, 1553513903),
(5, 7, 1, 1, '趣习惯自营', '和', '17538701156', '北京北京东城区SOHO', 0, NULL, '99.00', '暂无留言', 3, 1, NULL, 'QXG1553513904624', 'jd', '89674596039', 2, 1553513904);

-- --------------------------------------------------------

--
-- 表的结构 `f_order_back`
--

CREATE TABLE `f_order_back` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='退货';

-- --------------------------------------------------------

--
-- 表的结构 `f_order_goods`
--

CREATE TABLE `f_order_goods` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `goods_size_id` int(11) DEFAULT NULL COMMENT '商品规格ID',
  `goods_img` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `goods_name` varchar(50) DEFAULT NULL COMMENT '商品名字',
  `goods_num` int(11) DEFAULT NULL COMMENT '商品数量',
  `color` varchar(50) DEFAULT NULL COMMENT '颜色分类',
  `spec` varchar(50) DEFAULT NULL COMMENT '尺寸',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单商品表';

--
-- 转存表中的数据 `f_order_goods`
--

INSERT INTO `f_order_goods` (`id`, `order_id`, `goods_size_id`, `goods_img`, `goods_name`, `goods_num`, `color`, `spec`, `price`) VALUES
(1, 1, 13, '/uploads/image/20190301/5c78f71218265.jpg', '奶瓶', 1, '白色', '18cm', '99.00'),
(2, 2, 13, '/uploads/image/20190301/5c78f71218265.jpg', '奶瓶', 1, '白色', '18cm', '99.00'),
(3, 3, 13, '/uploads/image/20190301/5c78f71218265.jpg', '奶瓶', 1, '白色', '18cm', '99.00'),
(4, 4, 13, '/uploads/image/20190301/5c78f71218265.jpg', '奶瓶', 1, '白色', '18cm', '99.00'),
(5, 5, 13, '/uploads/image/20190301/5c78f71218265.jpg', '奶瓶', 1, '白色', '18cm', '99.00');

-- --------------------------------------------------------

--
-- 表的结构 `f_prize`
--

CREATE TABLE `f_prize` (
  `id` int(12) NOT NULL,
  `prize` varchar(100) DEFAULT NULL COMMENT '奖励标题',
  `v` int(10) DEFAULT NULL COMMENT '概率',
  `gift_id` int(10) DEFAULT '0' COMMENT '奖品ID'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奖品表';

--
-- 转存表中的数据 `f_prize`
--

INSERT INTO `f_prize` (`id`, `prize`, `v`, `gift_id`) VALUES
(1, '谢谢惠顾', 10, 0),
(2, '会员券', 0, 1),
(3, '能量币', 0, 2),
(4, '耳机', 0, 3),
(5, '谢谢惠顾', 9, 0),
(6, '谢谢惠顾', 10, 0),
(7, '谢谢惠顾', 10, 0),
(8, '谢谢惠顾', 10, 0);

-- --------------------------------------------------------

--
-- 表的结构 `f_ranking`
--

CREATE TABLE `f_ranking` (
  `id` int(10) NOT NULL,
  `type` tinyint(3) DEFAULT NULL COMMENT '1:一等奖2：二等奖3：三等奖',
  `num` int(10) DEFAULT NULL COMMENT '满足人数',
  `explain` varchar(255) DEFAULT NULL COMMENT '说明',
  `goods_img` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `goods_name` varchar(50) NOT NULL COMMENT '奖品名字',
  `goods_explain` varchar(255) DEFAULT NULL COMMENT '商品说明',
  `is_lq` tinyint(2) DEFAULT '1' COMMENT '是否被领取1：没有2：已领取',
  `uid` int(10) DEFAULT NULL COMMENT '领取ID',
  `content` varchar(255) NOT NULL COMMENT '详细说明',
  `dateline` datetime DEFAULT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='排行奖品';

--
-- 转存表中的数据 `f_ranking`
--

INSERT INTO `f_ranking` (`id`, `type`, `num`, `explain`, `goods_img`, `goods_name`, `goods_explain`, `is_lq`, `uid`, `content`, `dateline`) VALUES
(1, 1, 10000, '<img src=\"/public_html/uploads/image/20190326/20190326113612_82016.png\" alt=\"\" />', '/uploads/image/20190326/5c999df020763.jpg', '苹果XS', '64G金色', 1, NULL, '邀请1000人开通VIP(同时平台奖励1%的永久分红股份。', '2019-03-25 00:00:00'),
(2, 2, 1000, '<img src=\"/public_html/uploads/image/20190326/20190326134436_74880.png\" alt=\"\" />', '/uploads/image/20190326/5c99bc1346949.jpg', 'VIVO手机', '120G双卡双待手机', 1, NULL, '邀请1000人开通VIP(同时平台奖励1%的永久分红股份。', '2019-03-26 13:45:10'),
(3, 3, 100, '<img src=\"/public_html/uploads/image/20190326/20190326134642_78539.png\" alt=\"\" />', '/uploads/image/20190326/5c99bc8d8698f.jpg', 'vivo手机', '32G双卡双待', 1, NULL, '邀请100人开通VIP(同时平台奖励1%的永久分红股份。', '2019-03-26 13:46:45');

-- --------------------------------------------------------

--
-- 表的结构 `f_return_goods`
--

CREATE TABLE `f_return_goods` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `goods_img` varchar(255) NOT NULL COMMENT '商品图片',
  `goods_name` varchar(50) NOT NULL COMMENT '商品名字',
  `money` decimal(10,2) NOT NULL COMMENT '退款金额',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `img` int(11) NOT NULL COMMENT '上传凭证',
  `content` varchar(50) NOT NULL COMMENT '退款原因',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '退款状态1:申请退款',
  `is_income` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否到货1:未到2:已到',
  `dateline` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退货表';

-- --------------------------------------------------------

--
-- 表的结构 `f_rule_activity`
--

CREATE TABLE `f_rule_activity` (
  `id` int(11) NOT NULL,
  `type` tinyint(3) DEFAULT NULL COMMENT '1:早起打卡2：奖励发放3：补签卡4：用户邀请',
  `content` text COMMENT '内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动规则';

--
-- 转存表中的数据 `f_rule_activity`
--

INSERT INTO `f_rule_activity` (`id`, `type`, `content`) VALUES
(1, 1, '支付成功后，“支付1元参与挑战”变为倒计时“打卡倒计时+倒计时（时分秒）”\r\n\r\n到打卡时间，倒计时变为“立即打卡”\r\n\r\n点击立即打卡，弹窗提示打卡成功，关闭弹窗跳转早起打卡页面展示客户上次参与活动\r\n\r\n点击早起打卡，如果客户已存在参与活动，则页面展示为当前用户参与的活动。如果客户不存已参与活动，则展示上次参与活动。如用户从未参与活动，则展示滴水穿石'),
(2, 2, '支付成功后，“支付1元参与挑战”变为倒计时“打卡倒计时+倒计时（时分秒）”\r\n\r\n到打卡时间，倒计时变为“立即打卡”\r\n\r\n点击立即打卡，弹窗提示打卡成功，关闭弹窗跳转早起打卡页面展示客户上次参与活动\r\n\r\n点击早起打卡，如果客户已存在参与活动，则页面展示为当前用户参与的活动。如果客户不存已参与活动，则展示上次参与活动。如用户从未参与活动，则展示滴水穿石'),
(3, 3, '支付成功后，“支付1元参与挑战”变为倒计时“打卡倒计时+倒计时（时分秒）”\r\n\r\n到打卡时间，倒计时变为“立即打卡”\r\n\r\n点击立即打卡，弹窗提示打卡成功，关闭弹窗跳转早起打卡页面展示客户上次参与活动\r\n\r\n点击早起打卡，如果客户已存在参与活动，则页面展示为当前用户参与的活动。如果客户不存已参与活动，则展示上次参与活动。如用户从未参与活动，则展示滴水穿石'),
(4, 4, '支付成功后，“支付1元参与挑战”变为倒计时“打卡倒计时+倒计时（时分秒）”\r\n\r\n到打卡时间，倒计时变为“立即打卡”\r\n\r\n点击立即打卡，弹窗提示打卡成功，关闭弹窗跳转早起打卡页面展示客户上次参与活动\r\n\r\n点击早起打卡，如果客户已存在参与活动，则页面展示为当前用户参与的活动。如果客户不存已参与活动，则展示上次参与活动。如用户从未参与活动，则展示滴水穿石');

-- --------------------------------------------------------

--
-- 表的结构 `f_shop`
--

CREATE TABLE `f_shop` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL COMMENT '分类ID',
  `cat_name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `id_card` varchar(18) DEFAULT NULL COMMENT '身份证号',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `u_addr` varchar(100) DEFAULT NULL COMMENT '具体地址',
  `zid_card` varchar(100) DEFAULT NULL COMMENT '身份证正面照片',
  `shop_name` varchar(50) DEFAULT NULL COMMENT '店铺名字',
  `shop_logo` varchar(255) DEFAULT NULL COMMENT '店铺LOGO',
  `s_addr` varchar(100) DEFAULT NULL COMMENT '店铺地址',
  `license` varchar(255) DEFAULT NULL COMMENT '营业执照',
  `fid_card` varchar(255) DEFAULT NULL COMMENT '身份证反面照片',
  `status` tinyint(1) DEFAULT '1' COMMENT '审核状态1：未审核2：同意3：拒绝',
  `dateline` datetime DEFAULT NULL COMMENT '提交时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的店铺';

--
-- 转存表中的数据 `f_shop`
--

INSERT INTO `f_shop` (`id`, `uid`, `cate_id`, `cat_name`, `name`, `id_card`, `mobile`, `u_addr`, `zid_card`, `shop_name`, `shop_logo`, `s_addr`, `license`, `fid_card`, `status`, `dateline`) VALUES
(1, 1, 0, '', '趣习惯', '', '', '', '', '趣习惯自营', '/uploads/image/20190222/5c6fab3cf1dbb.jpg', '', '', '', 2, '2019-03-02 00:00:00'),
(2, 2, 1, '	 化妆品', '小王', '659896365685989898', '19869359898', '河南省,郑州市,二七区', '/uploads/image/20190228/5c77980c90b16.jpg', '小王专卖店', '/uploads/image/20190228/5c77980c90b16.jpg', '河南省,郑州市,二七区', '/uploads/image/20190228/5c77980c90b16.jpg', '/uploads/image/20190228/5c77980c90b16.jpg', 2, '2019-03-02 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `f_shopping_cart`
--

CREATE TABLE `f_shopping_cart` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `goods_size_id` int(11) NOT NULL COMMENT '商品id ',
  `goods_img` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `goods_name` varchar(100) DEFAULT NULL COMMENT '商品名字',
  `shop_id` int(11) DEFAULT NULL COMMENT '商家名字',
  `spec` varchar(50) DEFAULT NULL COMMENT '规格',
  `color` varchar(50) DEFAULT NULL COMMENT '颜色',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '购买数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `dateline` datetime NOT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购物车';

--
-- 转存表中的数据 `f_shopping_cart`
--

INSERT INTO `f_shopping_cart` (`id`, `uid`, `goods_size_id`, `goods_img`, `goods_name`, `shop_id`, `spec`, `color`, `num`, `price`, `dateline`) VALUES
(23, 5, 5, '/uploads/image/20190228/5c77980c90b16.jpg', '被子', 1, 'M', '白色', 1, '799.00', '2019-03-25 19:51:59');

-- --------------------------------------------------------

--
-- 表的结构 `f_shop_cat`
--

CREATE TABLE `f_shop_cat` (
  `id` int(11) NOT NULL,
  `fid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `img` varchar(255) DEFAULT NULL COMMENT '图标',
  `number` int(10) DEFAULT NULL COMMENT '顺序',
  `dateline` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商店分类';

--
-- 转存表中的数据 `f_shop_cat`
--

INSERT INTO `f_shop_cat` (`id`, `fid`, `name`, `img`, `number`, `dateline`) VALUES
(1, 0, '化妆品', '/uploads/image/20190215/5c66707ee3884.jpg', 2, '2019-02-19 10:08:19'),
(2, 0, '保健品', '/uploads/image/20190215/5c6671b933648.jpg', 1, '2019-02-15 16:41:00'),
(3, 0, '服饰', '/uploads/image/20190215/5c6672a88efa2.jpg', 3, '2019-02-15 16:42:26'),
(4, 0, '家用电器', '/uploads/image/20190215/5c66735f76322.jpg', 4, '2019-02-15 16:08:00'),
(5, 0, '电子产品', '/uploads/image/20190215/5c6673810245e.jpg', 5, '2019-02-15 16:08:34'),
(6, 0, '居家生活', '/uploads/image/20190215/5c6673be77747.jpg', 6, '2019-02-15 16:09:35'),
(7, 0, '母婴', '/uploads/image/20190215/5c6673f20d5ca.jpg', 7, '2019-02-15 16:10:27'),
(8, 1, '护肤礼盒', '/uploads/image/20190215/5c66835236263.jpg', 1, '2019-02-19 09:59:39');

-- --------------------------------------------------------

--
-- 表的结构 `f_shop_news`
--

CREATE TABLE `f_shop_news` (
  `id` int(10) NOT NULL,
  `shop_id` int(10) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL COMMENT '活动消息'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商店活动消息';

--
-- 转存表中的数据 `f_shop_news`
--

INSERT INTO `f_shop_news` (`id`, `shop_id`, `content`) VALUES
(1, 1, '本店活动满500减50');

-- --------------------------------------------------------

--
-- 表的结构 `f_system`
--

CREATE TABLE `f_system` (
  `id` int(30) NOT NULL,
  `logo` varchar(255) NOT NULL COMMENT 'logo',
  `name` varchar(30) NOT NULL COMMENT 'APP名称',
  `version` varchar(50) NOT NULL COMMENT '版本',
  `gs_name` varchar(50) NOT NULL COMMENT '公司名字',
  `tx_gz` text COMMENT '提现规则',
  `enter_notice` text COMMENT '入驻须知',
  `min_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最低体现额度',
  `vip_money` decimal(10,2) NOT NULL COMMENT 'vip价格',
  `prize_num` int(5) NOT NULL COMMENT '抽奖次数',
  `step_money` int(10) DEFAULT NULL COMMENT '如：1000步一元',
  `dateline` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统表';

--
-- 转存表中的数据 `f_system`
--

INSERT INTO `f_system` (`id`, `logo`, `name`, `version`, `gs_name`, `tx_gz`, `enter_notice`, `min_money`, `vip_money`, `prize_num`, `step_money`, `dateline`) VALUES
(1, '/uploads/image/20190319/5c90a0de6c662.jpg', '趣习惯', '1.1.2', '犇犇科技', '开通VIP需要先实名认证，如果用户未实名，跳转实名认证页面\n\n会员特权：\n\n   1: 开通一年VIP会员980元；每次打卡，可获得双倍能量币、双倍抽奖次数。\n\n补签卡12张，每月一号发放1张。\n\n可成为商家申请入驻商城。\n\n（入驻需缴纳保证金，根据缴纳店铺诚信保证金的数额置顶）\n\n状态分为提交申请、审核中、审核通过、入驻费用待支付、入驻成功\n\n提交申请后所有信息不能编辑。\n\n审核通过后，点击【入驻费用待支付】按钮跳转待支付页面，支付成功，后台分配相应的操作后台。', '开通VIP需要先实名认证，如果用户未实名，跳转实名认证页面\n\n会员特权：\n\n   1: 开通一年VIP会员980元；每次打卡，可获得双倍能量币、双倍抽奖次数。\n\n补签卡12张，每月一号发放1张。\n\n可成为商家申请入驻商城。\n\n（入驻需缴纳保证金，根据缴纳店铺诚信保证金的数额置顶）\n\n状态分为提交申请、审核中、审核通过、入驻费用待支付、入驻成功\n\n提交申请后所有信息不能编辑。\n\n审核通过后，点击【入驻费用待支付】按钮跳转待支付页面，支付成功，后台分配相应的操作后台。', '50.00', '120.00', 3, 10000, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `f_system_link`
--

CREATE TABLE `f_system_link` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `content` varchar(50) DEFAULT NULL COMMENT '内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统联系方式';

--
-- 转存表中的数据 `f_system_link`
--

INSERT INTO `f_system_link` (`id`, `title`, `content`) VALUES
(1, '联系电话', '123-456-789'),
(2, '联系qq', '123-456-789');

-- --------------------------------------------------------

--
-- 表的结构 `f_system_news`
--

CREATE TABLE `f_system_news` (
  `id` int(11) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '标题',
  `img` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '图片',
  `content` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '内容',
  `dateline` int(10) DEFAULT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='系统消息';

--
-- 转存表中的数据 `f_system_news`
--

INSERT INTO `f_system_news` (`id`, `title`, `img`, `content`, `dateline`) VALUES
(1, '店铺上新品了', '/uploads/image/20190228/5c7752f4d7694.jpg', '很多淘宝卖家在上新的时候，是没有计划性的，有了宝贝就上新，一次性上很多。这是错误的，举个例子，如果你有40件宝贝，你如果一次性全上了的话，那么这40款宝贝，你能有精力全部照顾到么？我想大部分淘宝卖家是没有的吧，你可能只能集中精力去干一两款。所以，最好的方式是：你把这40件宝贝，平均分配到一个月甚至几个月，这样每周你就可以把精力集中到有限的几款商品上', 1553015728),
(2, '推荐下载', '/uploads/image/20190326/5c99769dae623.jpg', '下载二维码有好礼', 1553561260);

-- --------------------------------------------------------

--
-- 表的结构 `f_ugift_num`
--

CREATE TABLE `f_ugift_num` (
  `id` int(20) NOT NULL,
  `uid` int(12) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `f_ugift_num`
--

INSERT INTO `f_ugift_num` (`id`, `uid`, `dateline`) VALUES
(1, 4, 1553247494),
(2, 5, 1553259039),
(3, 5, 1553261725),
(4, 5, 1553262956),
(5, 1, 1553312175),
(6, 6, 1553312210),
(7, 2, 1553312426),
(8, 3, 1553313092),
(9, 3, 1553313103),
(10, 3, 1553313112),
(11, 5, 1553313652),
(12, 5, 1553332818),
(13, 5, 1553332892),
(14, 1, 1553354757),
(15, 1, 1553354779),
(16, 7, 1553488503),
(17, 5, 1553508987),
(18, 7, 1553512594),
(19, 7, 1553513154),
(20, 9, 1553517451);

-- --------------------------------------------------------

--
-- 表的结构 `f_user`
--

CREATE TABLE `f_user` (
  `id` int(11) NOT NULL,
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `wx` varchar(50) DEFAULT NULL COMMENT '微信号',
  `password` varchar(50) DEFAULT NULL COMMENT '密码',
  `logo` varchar(255) DEFAULT '/uploads/image/20190302/5c7a46d97318b.jpg' COMMENT '头像',
  `name` varchar(50) DEFAULT '未设置' COMMENT '姓名',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `sex` tinyint(1) DEFAULT '1' COMMENT '性别1：男2：女',
  `is_approve` tinyint(1) DEFAULT '1' COMMENT '实名认证1：未认证2：已认证',
  `is_pass` tinyint(1) DEFAULT '1' COMMENT '资金密码1：未设置2：已设置',
  `funds_pass` varchar(50) DEFAULT NULL COMMENT '资金密码',
  `alipay` varchar(50) DEFAULT NULL COMMENT '支付宝账号',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '余额',
  `f_money` decimal(10,2) DEFAULT '0.00' COMMENT '冻结金额',
  `energy_coin` int(10) DEFAULT '0' COMMENT '能量币',
  `is_vip` tinyint(1) DEFAULT '1' COMMENT 'VIP:1不是2：是',
  `end_time` int(10) DEFAULT NULL COMMENT '到期时间',
  `qd` int(10) DEFAULT '0' COMMENT '签到天数',
  `yq_code` varchar(30) DEFAULT NULL COMMENT '邀请码',
  `yq_img` varchar(255) DEFAULT NULL COMMENT '邀请图片地址',
  `yq_num` int(10) DEFAULT '0' COMMENT '邀请人数',
  `dateline` int(10) DEFAULT NULL COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `f_user`
--

INSERT INTO `f_user` (`id`, `mobile`, `wx`, `password`, `logo`, `name`, `nickname`, `sex`, `is_approve`, `is_pass`, `funds_pass`, `alipay`, `money`, `f_money`, `energy_coin`, `is_vip`, `end_time`, `qd`, `yq_code`, `yq_img`, `yq_num`, `dateline`) VALUES
(1, '13999999999', NULL, NULL, '/uploads/image/20190302/5c7a46d97318b.jpg', '未设置', '趣习惯自营', 1, 1, 1, NULL, NULL, '0.00', '0.00', 0, 1, NULL, 0, NULL, '/merge_img/20190325/5c98b22993c90.png', 0, NULL),
(7, '17538701156', 'omTly1v2Z11Onweig2PBEvWY5w1Y', NULL, '/uploads/image/20190302/5c7a46d97318b.jpg', '未设置', '先知！先见！先行！', 0, 1, 1, NULL, NULL, '0.00', '0.00', 0, 1, NULL, 0, NULL, '/merge_img/20190325/5c98b89a41dcd.png', 0, NULL),
(3, '13298329335', NULL, 'c7122a1349c22cb3c009da3613d242ab', '/uploads/image/20190302/5c7a46d97318b.jpg', '未设置', '13298329335', 1, 1, 1, NULL, NULL, '0.00', '0.00', 0, 1, NULL, 0, '1553508269418', '/merge_img/20190325/5c98a92033d82.png', 100, 1553508269),
(5, '17320150329', 'oU5Yyt_yFL3apdilkeeKOOxE5U8E', 'c7122a1349c22cb3c009da3613d242ab', '/uploads/image/20190302/5c7a46d97318b.jpg', '未设置', '17320150329', 1, 1, 1, NULL, NULL, '0.00', '0.00', 0, 1, NULL, 0, '1553508873834', '/merge_img/20190325/5c98aa61c1a4c.png', 0, 1553508873),
(8, '13673068561', NULL, 'c7122a1349c22cb3c009da3613d242ab', '/uploads/image/20190302/5c7a46d97318b.jpg', '未设置', '13673068561', 1, 1, 1, NULL, NULL, '0.00', '0.00', 0, 1, NULL, 0, '1553514006197', '/merge_img/20190325/5c98be446f5bd.png', 0, 1553514006),
(9, '13393920607', 'omTly1jaoQFZoFltHQIY_IfH-YCU', NULL, '/uploads/image/20190302/5c7a46d97318b.jpg', '未设置', '白手起家', 1, 1, 1, NULL, NULL, '0.00', '0.00', 0, 1, NULL, 0, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `f_user_addr`
--

CREATE TABLE `f_user_addr` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `province` varchar(20) DEFAULT NULL COMMENT '省',
  `city` varchar(20) DEFAULT NULL COMMENT '市',
  `area` varchar(20) DEFAULT NULL COMMENT '区',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `is_default` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否默认1:是2：不是'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地址列表';

--
-- 转存表中的数据 `f_user_addr`
--

INSERT INTO `f_user_addr` (`id`, `uid`, `name`, `mobile`, `province`, `city`, `area`, `address`, `is_default`) VALUES
(1, 7, '和', '17538701156', '北京', '北京', '东城区', 'SOHO', 1);

-- --------------------------------------------------------

--
-- 表的结构 `f_user_band`
--

CREATE TABLE `f_user_band` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `name` varchar(50) DEFAULT NULL COMMENT '用户名',
  `bank_name` varchar(50) DEFAULT NULL COMMENT '银行卡名字',
  `bank_num` varchar(50) DEFAULT NULL COMMENT '银行卡号',
  `bank_logo` varchar(255) DEFAULT NULL COMMENT '银行卡图片',
  `status` tinyint(3) DEFAULT '1' COMMENT '1:未审核2：已审核3：拒绝'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的银行卡';

-- --------------------------------------------------------

--
-- 表的结构 `f_user_clock`
--

CREATE TABLE `f_user_clock` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '签到时间',
  `nday` int(11) NOT NULL DEFAULT '0' COMMENT '签到当天凌晨时间戳',
  `cnums` int(11) NOT NULL DEFAULT '0' COMMENT '连续签到天数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户签到表';

-- --------------------------------------------------------

--
-- 表的结构 `f_user_clock_log`
--

CREATE TABLE `f_user_clock_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `ucid` int(11) NOT NULL DEFAULT '0' COMMENT '签到 ID',
  `uhid` int(11) NOT NULL DEFAULT '0' COMMENT '参与活动ID',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '如履薄冰 歃血为盟 签到天数',
  `type` smallint(6) NOT NULL DEFAULT '0' COMMENT '1 滴水穿石 2 如履薄冰 3 歃血为盟'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户活动打卡记录';

--
-- 转存表中的数据 `f_user_clock_log`
--

INSERT INTO `f_user_clock_log` (`id`, `uid`, `addtime`, `ucid`, `uhid`, `nums`, `type`) VALUES
(1, 1, 1551778572, 7, 1, 21, 3),
(2, 1, 1551778572, 7, 2, 7, 2),
(3, 1, 1551778572, 7, 3, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `f_user_clock_pay`
--

CREATE TABLE `f_user_clock_pay` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `type` smallint(6) NOT NULL DEFAULT '0' COMMENT '1 滴水穿石 2 如履薄冰 3 歃血为盟',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '1 进行中 | 2 成功  |  3 失败',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '添加当天凌晨时间戳',
  `cnums` smallint(6) NOT NULL DEFAULT '0' COMMENT '签到天数',
  `pay_money` decimal(10,2) NOT NULL COMMENT '支付金额',
  `reward_money` decimal(10,2) NOT NULL COMMENT '奖励金额',
  `s_status` smallint(6) NOT NULL DEFAULT '1' COMMENT '1  未领取奖励 2 领取奖励'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户早起打卡支付表';

--
-- 转存表中的数据 `f_user_clock_pay`
--

INSERT INTO `f_user_clock_pay` (`id`, `uid`, `addtime`, `uptime`, `type`, `status`, `times`, `cnums`, `pay_money`, `reward_money`, `s_status`) VALUES
(1, 1, 1551679200, 1551778572, 3, 2, 1551628800, 21, '0.00', '0.00', 1),
(2, 1, 1551679200, 1551778572, 2, 2, 1551628800, 7, '0.00', '0.00', 1),
(3, 1, 1551679200, 1551778572, 1, 2, 1551628800, 1, '0.00', '0.00', 1),
(4, 1, 1551834076, 1553168775, 3, 3, 1551801600, 0, '0.00', '0.00', 1),
(5, 1, 1551834082, 1553168775, 1, 3, 1551801600, 0, '0.00', '1.00', 1),
(6, 1, 1551834085, 1553168775, 2, 3, 1551801600, 0, '0.00', '2.00', 1),
(7, 1, 1553158971, 1553339916, 1, 3, 0, 0, '100.00', '0.00', 1),
(8, 1, 1553159031, 1553339916, 1, 3, 0, 0, '100.00', '0.00', 1),
(9, 1, 1553160502, 1553339916, 1, 3, 0, 0, '100.00', '0.00', 1),
(10, 2, 1553227162, 0, 1, 1, 0, 0, '100.00', '0.00', 1),
(11, 2, 1553227224, 0, 1, 1, 0, 0, '100.00', '0.00', 1);

-- --------------------------------------------------------

--
-- 表的结构 `f_user_coupon`
--

CREATE TABLE `f_user_coupon` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `shop_id` int(10) DEFAULT NULL COMMENT '店铺ID',
  `coupon_id` int(11) NOT NULL,
  `endtime` int(10) DEFAULT NULL COMMENT '结束时间',
  `is_use` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1：未使用2：已使用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的优惠券';

-- --------------------------------------------------------

--
-- 表的结构 `f_user_price`
--

CREATE TABLE `f_user_price` (
  `id` int(12) NOT NULL,
  `uid` int(12) DEFAULT NULL,
  `gift_id` int(12) DEFAULT NULL,
  `log` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '中奖日志',
  `time` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '筛选时间',
  `dateline` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='我的奖品';

--
-- 转存表中的数据 `f_user_price`
--

INSERT INTO `f_user_price` (`id`, `uid`, `gift_id`, `log`, `time`, `dateline`) VALUES
(1, 2, 1, '恭喜获得小王一枚请及时领取', '2019-03', 1552726595);

-- --------------------------------------------------------

--
-- 表的结构 `f_user_team`
--

CREATE TABLE `f_user_team` (
  `id` int(12) NOT NULL,
  `uid` int(12) DEFAULT NULL,
  `yq_id` int(12) DEFAULT NULL COMMENT '邀请ID',
  `time` varchar(50) DEFAULT NULL COMMENT '筛选时间2019-03',
  `dateline` int(10) DEFAULT NULL COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的团队';

--
-- 转存表中的数据 `f_user_team`
--

INSERT INTO `f_user_team` (`id`, `uid`, `yq_id`, `time`, `dateline`) VALUES
(1, 1, 1, '2019-03', 1552726595),
(2, 3, 1, '2019-03', 1552726595),
(3, 3, 5, '2019-03', 1552726595);

-- --------------------------------------------------------

--
-- 表的结构 `f_user_tx`
--

CREATE TABLE `f_user_tx` (
  `id` int(12) NOT NULL,
  `uid` int(12) DEFAULT NULL,
  `type` tinyint(3) DEFAULT NULL COMMENT '1:支付宝2：微信',
  `money` decimal(10,2) DEFAULT '0.00',
  `card_name` varchar(50) DEFAULT NULL COMMENT '卡名',
  `num` varchar(50) DEFAULT NULL COMMENT '支付宝-银行卡号',
  `status` tinyint(3) DEFAULT '1',
  `dateline` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='我的提现表';

--
-- 转存表中的数据 `f_user_tx`
--

INSERT INTO `f_user_tx` (`id`, `uid`, `type`, `money`, `card_name`, `num`, `status`, `dateline`) VALUES
(1, 1, 1, '100.00', NULL, '13298329335', 1, NULL),
(2, 1, 1, '100.00', NULL, '13298329335', 1, NULL),
(3, 1, 1, '200.00', NULL, '13298329335', 1, '2019-03-19 09:19:21'),
(4, 1, 1, '200.00', NULL, '13298329335', 1, '2019-03-19 09:19:56'),
(5, 1, 1, '200.00', NULL, '13298329335', 1, '2019-03-19 09:20:23'),
(6, 1, 1, '200.00', NULL, '13298329335', 1, '2019-03-19 09:20:42'),
(7, 1, 1, '200.00', NULL, '13298329335', 1, '2019-03-19 09:21:46');

-- --------------------------------------------------------

--
-- 表的结构 `f_user_verified`
--

CREATE TABLE `f_user_verified` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `card_num` varchar(50) DEFAULT NULL COMMENT '身份证号',
  `card_zimg` varchar(255) DEFAULT NULL COMMENT '身份证正面',
  `card_fimg` varchar(255) DEFAULT NULL COMMENT '身份证反面',
  `status` tinyint(3) DEFAULT '1' COMMENT '1：未审核2：审核通过3:未通过'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='身份证';

--
-- 转存表中的数据 `f_user_verified`
--

INSERT INTO `f_user_verified` (`id`, `uid`, `card_num`, `card_zimg`, `card_fimg`, `status`) VALUES
(1, 1, '412824199410156416', 'http://quxiguan.tainongnongzi.com/public_html/upload/855461552724948.png', 'http://quxiguan.tainongnongzi.com/public_html/upload/863941552724961.png', 1);

-- --------------------------------------------------------

--
-- 表的结构 `f_vip_content`
--

CREATE TABLE `f_vip_content` (
  `id` int(10) NOT NULL,
  `content` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='会员说明';

--
-- 转存表中的数据 `f_vip_content`
--

INSERT INTO `f_vip_content` (`id`, `content`) VALUES
(1, '开通一年VIP会员980元；每次打卡，可获得双倍能量币、双倍抽奖次数。补签卡12张，每月一号发放1张。'),
(2, '可成为商家申请入驻商城');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `f_activity`
--
ALTER TABLE `f_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_admin`
--
ALTER TABLE `f_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `f_admin_action`
--
ALTER TABLE `f_admin_action`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_admin_auth`
--
ALTER TABLE `f_admin_auth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cid` (`role_id`,`action_id`) USING BTREE;

--
-- Indexes for table `f_admin_cat`
--
ALTER TABLE `f_admin_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_bank`
--
ALTER TABLE `f_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_banner`
--
ALTER TABLE `f_banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_clock`
--
ALTER TABLE `f_clock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_collection`
--
ALTER TABLE `f_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_coupon`
--
ALTER TABLE `f_coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_coupon_del`
--
ALTER TABLE `f_coupon_del`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_energy_logo`
--
ALTER TABLE `f_energy_logo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_energy_mart`
--
ALTER TABLE `f_energy_mart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_feedback`
--
ALTER TABLE `f_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `f_feedimg`
--
ALTER TABLE `f_feedimg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_gift`
--
ALTER TABLE `f_gift`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_goods`
--
ALTER TABLE `f_goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`shop_id`);

--
-- Indexes for table `f_goods_cate`
--
ALTER TABLE `f_goods_cate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_goods_evaluate`
--
ALTER TABLE `f_goods_evaluate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_goods_evimg`
--
ALTER TABLE `f_goods_evimg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_goods_size`
--
ALTER TABLE `f_goods_size`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_history`
--
ALTER TABLE `f_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `f_kd`
--
ALTER TABLE `f_kd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_money_logo`
--
ALTER TABLE `f_money_logo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_msg`
--
ALTER TABLE `f_msg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_msg_list`
--
ALTER TABLE `f_msg_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_oback_img`
--
ALTER TABLE `f_oback_img`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_order`
--
ALTER TABLE `f_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_order_back`
--
ALTER TABLE `f_order_back`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_order_goods`
--
ALTER TABLE `f_order_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_prize`
--
ALTER TABLE `f_prize`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_ranking`
--
ALTER TABLE `f_ranking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_return_goods`
--
ALTER TABLE `f_return_goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `f_rule_activity`
--
ALTER TABLE `f_rule_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_shop`
--
ALTER TABLE `f_shop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `f_shopping_cart`
--
ALTER TABLE `f_shopping_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `f_shop_cat`
--
ALTER TABLE `f_shop_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_shop_news`
--
ALTER TABLE `f_shop_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_system`
--
ALTER TABLE `f_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_system_link`
--
ALTER TABLE `f_system_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_system_news`
--
ALTER TABLE `f_system_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_ugift_num`
--
ALTER TABLE `f_ugift_num`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_user`
--
ALTER TABLE `f_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_user_addr`
--
ALTER TABLE `f_user_addr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `f_user_band`
--
ALTER TABLE `f_user_band`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_user_clock`
--
ALTER TABLE `f_user_clock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_user_clock_log`
--
ALTER TABLE `f_user_clock_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_user_clock_pay`
--
ALTER TABLE `f_user_clock_pay`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_user_coupon`
--
ALTER TABLE `f_user_coupon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `f_user_price`
--
ALTER TABLE `f_user_price`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_user_team`
--
ALTER TABLE `f_user_team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_user_tx`
--
ALTER TABLE `f_user_tx`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_user_verified`
--
ALTER TABLE `f_user_verified`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `f_vip_content`
--
ALTER TABLE `f_vip_content`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `f_activity`
--
ALTER TABLE `f_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `f_admin`
--
ALTER TABLE `f_admin`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84693448;
--
-- 使用表AUTO_INCREMENT `f_admin_action`
--
ALTER TABLE `f_admin_action`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;
--
-- 使用表AUTO_INCREMENT `f_admin_auth`
--
ALTER TABLE `f_admin_auth`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3808;
--
-- 使用表AUTO_INCREMENT `f_admin_cat`
--
ALTER TABLE `f_admin_cat`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- 使用表AUTO_INCREMENT `f_bank`
--
ALTER TABLE `f_bank`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `f_banner`
--
ALTER TABLE `f_banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `f_clock`
--
ALTER TABLE `f_clock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `f_collection`
--
ALTER TABLE `f_collection`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- 使用表AUTO_INCREMENT `f_coupon`
--
ALTER TABLE `f_coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `f_coupon_del`
--
ALTER TABLE `f_coupon_del`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `f_energy_logo`
--
ALTER TABLE `f_energy_logo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `f_energy_mart`
--
ALTER TABLE `f_energy_mart`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `f_feedback`
--
ALTER TABLE `f_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `f_feedimg`
--
ALTER TABLE `f_feedimg`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `f_gift`
--
ALTER TABLE `f_gift`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `f_goods`
--
ALTER TABLE `f_goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `f_goods_cate`
--
ALTER TABLE `f_goods_cate`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `f_goods_evaluate`
--
ALTER TABLE `f_goods_evaluate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- 使用表AUTO_INCREMENT `f_goods_evimg`
--
ALTER TABLE `f_goods_evimg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- 使用表AUTO_INCREMENT `f_goods_size`
--
ALTER TABLE `f_goods_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- 使用表AUTO_INCREMENT `f_history`
--
ALTER TABLE `f_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `f_kd`
--
ALTER TABLE `f_kd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `f_money_logo`
--
ALTER TABLE `f_money_logo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `f_msg`
--
ALTER TABLE `f_msg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `f_msg_list`
--
ALTER TABLE `f_msg_list`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `f_oback_img`
--
ALTER TABLE `f_oback_img`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `f_order`
--
ALTER TABLE `f_order`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `f_order_back`
--
ALTER TABLE `f_order_back`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `f_order_goods`
--
ALTER TABLE `f_order_goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `f_prize`
--
ALTER TABLE `f_prize`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `f_ranking`
--
ALTER TABLE `f_ranking`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `f_return_goods`
--
ALTER TABLE `f_return_goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `f_rule_activity`
--
ALTER TABLE `f_rule_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `f_shop`
--
ALTER TABLE `f_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `f_shopping_cart`
--
ALTER TABLE `f_shopping_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- 使用表AUTO_INCREMENT `f_shop_cat`
--
ALTER TABLE `f_shop_cat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `f_shop_news`
--
ALTER TABLE `f_shop_news`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `f_system`
--
ALTER TABLE `f_system`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `f_system_link`
--
ALTER TABLE `f_system_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `f_system_news`
--
ALTER TABLE `f_system_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `f_ugift_num`
--
ALTER TABLE `f_ugift_num`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- 使用表AUTO_INCREMENT `f_user`
--
ALTER TABLE `f_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `f_user_addr`
--
ALTER TABLE `f_user_addr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `f_user_band`
--
ALTER TABLE `f_user_band`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `f_user_clock`
--
ALTER TABLE `f_user_clock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `f_user_clock_log`
--
ALTER TABLE `f_user_clock_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `f_user_clock_pay`
--
ALTER TABLE `f_user_clock_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- 使用表AUTO_INCREMENT `f_user_coupon`
--
ALTER TABLE `f_user_coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `f_user_price`
--
ALTER TABLE `f_user_price`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `f_user_team`
--
ALTER TABLE `f_user_team`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `f_user_tx`
--
ALTER TABLE `f_user_tx`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `f_user_verified`
--
ALTER TABLE `f_user_verified`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `f_vip_content`
--
ALTER TABLE `f_vip_content`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
