/*
 Navicat MySQL Data Transfer

 Source Server         : mysql
 Source Server Type    : MySQL
 Source Server Version : 50505
 Source Host           : localhost
 Source Database       : yunchi_db

 Target Server Type    : MySQL
 Target Server Version : 50505
 File Encoding         : utf-8

 Date: 12/25/2018 15:15:49 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `admin_menu`
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(63) NOT NULL DEFAULT '' COMMENT '菜单ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级id',
  `icon` varchar(100) NOT NULL COMMENT 'icon',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1开启 0关闭',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '地址',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='菜单管理';

-- ----------------------------
--  Records of `admin_menu`
-- ----------------------------
BEGIN;
INSERT INTO `admin_menu` VALUES ('1', '权限管理', '0', 'fa fa-lock fa-fw', '1', '2', 'javascript:;', '1544516516'), ('2', '系统管理员', '1', 'fa fa-angle-right fa-fw', '1', '3', '/index/Auth/manage', '1544516516'), ('3', '菜单管理', '1', 'fa fa-angle-right fa-fw', '1', '2', '/index/Auth/menu', '1544516516'), ('4', '角色管理', '1', 'fa fa-angle-right fa-fw', '1', '1', '/index/Auth/role', '1544516516'), ('5', '服务对象中心', '0', 'fa fa-group fa-fw', '1', '6', 'javascript:;', '1544516516'), ('6', '服务对象分组管理', '5', 'fa fa-angle-right fa-fw', '1', '25', '/index/server/group', '1544516516'), ('7', '服务对象标签管理', '5', 'fa fa-angle-right fa-fw', '1', '23', '/index/server/label', '1544516516'), ('8', '服务对象管理', '5', 'fa fa-angle-right fa-fw', '1', '26', '/index/server/show', '1544516516'), ('9', '工单管理', '0', 'fa fa-cogs ', '1', '10', 'javascript:;', '1544516516'), ('10', '全部工单', '9', 'fa fa-angle-right fa-fw', '1', '10', 'javascript:;', '1544516516'), ('11', '安全管理', '0', 'fa fa-warning fa-fw', '1', '4', 'javascript:;', '1544516516'), ('12', '定位地图', '11', 'fa fa-angle-right fa-fw', '1', '10', '/index/map/show', '1544516516'), ('13', '服务商管理', '0', 'fa fa-life-ring fa-fw', '1', '3', 'javascript:;', '1544516516'), ('14', '系统管理', '0', 'fa fa-gear', '1', '1', 'javascript:;', '1544516516'), ('24', '易米开放接口', '14', 'fa fa-angle-right fa-fw', '1', '10', '/index/system/callCenter', '0'), ('25', '话务员管理', '0', 'glyphicon glyphicon-user', '1', '4', 'javascript:;', '1545629703'), ('26', '技能组管理', '25', 'fa fa-angle-right fa-fw', '1', '10', '/index/staff/group', '0'), ('27', '话务员管理', '25', 'fa fa-angle-right fa-fw', '1', '11', '/index/staff/staff', '0'), ('28', '计划工单管理', '0', 'fa fa-tasks fa-fw', '1', '5', 'javascript:;', '1545714656'), ('29', '计划任务管理', '28', 'fa fa-angle-right fa-fw', '1', '10', '/index/work/order', '0'), ('31', '全部计划工单', '28', 'fa fa-angle-right fa-fw', '1', '9', '/index/work/list', '0'), ('32', '积分管理', '0', 'glyphicon glyphicon-credit-card', '1', '5', 'javascript:;', '1545717884'), ('33', '积分管理', '32', 'fa fa-angle-right fa-fw', '1', '10', '/index/integral/show', '0'), ('34', '积分核销历史', '32', 'fa fa-angle-right fa-fw', '1', '9', '/index/integral/market', '0'), ('35', '积分累计历史', '32', 'fa fa-angle-right fa-fw', '1', '8', '/index/integral/history', '0');
COMMIT;

-- ----------------------------
--  Table structure for `admin_role`
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(31) NOT NULL COMMENT '角色名称',
  `mid` varchar(255) NOT NULL COMMENT '权限目录id',
  `status` tinyint(1) NOT NULL COMMENT '状态 1启用 0关闭',
  `details` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='角色管理';

-- ----------------------------
--  Records of `admin_role`
-- ----------------------------
BEGIN;
INSERT INTO `admin_role` VALUES ('1', '超级管理员', '9,10,5,8,6,7,11,12,13,1,2,3,4,24,14,25,27,26,28,29,31,32,33,34,35', '1', '备注', '1544516516'), ('2', '管理1', '9,10,5,8,7,6,11,12,13,1,2,3,4,14', '1', '备注', '1544591092'), ('5', '测试', '9,10,1,2,3,4', '1', '备注', '1544604003');
COMMIT;

-- ----------------------------
--  Table structure for `admin_users`
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `username` varchar(31) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `nickname` varchar(31) NOT NULL COMMENT '昵称',
  `mobile` varchar(15) NOT NULL COMMENT '手机号',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `sid` int(11) NOT NULL COMMENT '角色id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0 关闭 1启用',
  `details` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='系统管理员';

-- ----------------------------
--  Records of `admin_users`
-- ----------------------------
BEGIN;
INSERT INTO `admin_users` VALUES ('1', '12312', '123123', '123', '123213', '123', '123', '1', '123', '0', '1531111111'), ('2', 'admin', '0192023a7bbd73250516f069df18b500', '超级管理员', '17685531321', '2018@123.com', '1', '1', '备注', '1544679042', '1531111111');
COMMIT;

-- ----------------------------
--  Table structure for `server_object`
-- ----------------------------
DROP TABLE IF EXISTS `server_object`;
CREATE TABLE `server_object` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(31) NOT NULL COMMENT '姓名',
  `card` char(18) NOT NULL COMMENT '身份证号码',
  `pic` varchar(100) NOT NULL DEFAULT '' COMMENT '头像',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '手机号',
  `mobile_watch` varchar(15) NOT NULL DEFAULT '' COMMENT '腕表手机号',
  `watch_imei` char(16) NOT NULL DEFAULT '' COMMENT '腕表IMEI',
  `is_watch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '腕表是否已发放 0否 1是',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别 0女1男',
  `age` smallint(4) NOT NULL DEFAULT '0' COMMENT '年龄',
  `gid` varchar(50) NOT NULL DEFAULT '' COMMENT '分组ID',
  `bid` varchar(50) NOT NULL DEFAULT '' COMMENT '标签id',
  `birthday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '生日',
  `site` varchar(100) NOT NULL DEFAULT '' COMMENT '现住址',
  `location` varchar(100) NOT NULL DEFAULT '' COMMENT '获取当前的定位信息',
  `height` smallint(3) NOT NULL DEFAULT '0' COMMENT '身高单位CM',
  `widget` smallint(3) NOT NULL COMMENT '体重单位KG',
  `is_yan` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否抽烟 0否 1是',
  `is_jiu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否喝酒 0否 1是',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='服务对象';

-- ----------------------------
--  Records of `server_object`
-- ----------------------------
BEGIN;
INSERT INTO `server_object` VALUES ('1', '服务', '123', '123', '17686531379', '213', '123', '0', '1', '9999', '1,12', '1', '2010-02-02 15:56:48', '213', '123', '213', '123', '1', '0', '1571111111', '1545113422');
COMMIT;

-- ----------------------------
--  Table structure for `server_object_details`
-- ----------------------------
DROP TABLE IF EXISTS `server_object_details`;
CREATE TABLE `server_object_details` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `oid` int(11) NOT NULL,
  `jiguan` varchar(31) NOT NULL DEFAULT '' COMMENT '籍贯',
  `minzu` varchar(31) NOT NULL DEFAULT '' COMMENT '民族',
  `xueli` varchar(15) NOT NULL DEFAULT '' COMMENT '学历',
  `zhengzhi` varchar(31) NOT NULL DEFAULT '' COMMENT '政治面貌',
  `zongjia` varchar(31) NOT NULL DEFAULT '' COMMENT '宗教',
  `xingqu` varchar(100) NOT NULL DEFAULT '' COMMENT '兴趣爱好',
  `yinshi` varchar(100) NOT NULL DEFAULT '' COMMENT '饮食禁忌',
  `xuexing` varchar(31) NOT NULL DEFAULT '' COMMENT '血型',
  `rn_yinxing` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'rn阴性 0否 1是',
  `jingji` varchar(31) NOT NULL DEFAULT '' COMMENT '经济来源',
  `shenghuo` varchar(31) NOT NULL DEFAULT '' COMMENT '生活来源',
  `zhaokan` varchar(31) NOT NULL DEFAULT '' COMMENT '照看人',
  `zhuangkuang` varchar(31) NOT NULL DEFAULT '' COMMENT '身体状况',
  `juzhu` varchar(31) NOT NULL DEFAULT '' COMMENT '居住类型',
  `zhufang` varchar(31) NOT NULL DEFAULT '' COMMENT '住房类型',
  `tigong` varchar(31) NOT NULL DEFAULT '' COMMENT '资料提供人',
  `beizhu` varchar(200) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='服务对象信息详情';

-- ----------------------------
--  Table structure for `server_object_group`
-- ----------------------------
DROP TABLE IF EXISTS `server_object_group`;
CREATE TABLE `server_object_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(31) NOT NULL COMMENT '名称',
  `details` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='服务对象分组';

-- ----------------------------
--  Records of `server_object_group`
-- ----------------------------
BEGIN;
INSERT INTO `server_object_group` VALUES ('1', '康桥', '分组的', '1571111111'), ('12', '奉贤', '分组测试', '1544765591');
COMMIT;

-- ----------------------------
--  Table structure for `server_object_label`
-- ----------------------------
DROP TABLE IF EXISTS `server_object_label`;
CREATE TABLE `server_object_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(31) NOT NULL COMMENT '标签名称',
  `details` varchar(100) NOT NULL COMMENT '标签描述',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='服务器对象标签';

-- ----------------------------
--  Records of `server_object_label`
-- ----------------------------
BEGIN;
INSERT INTO `server_object_label` VALUES ('1', 'vip', 'vipa', '1571111111'), ('3', 'vipss', '标签测试', '1544692389');
COMMIT;

-- ----------------------------
--  Table structure for `server_object_relation`
-- ----------------------------
DROP TABLE IF EXISTS `server_object_relation`;
CREATE TABLE `server_object_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '关系 0其他 1子女 2父母 3亲戚 4朋友 5同学 6同事',
  `oid` int(11) NOT NULL COMMENT '服务对象id',
  `name` varchar(31) NOT NULL COMMENT '姓名',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '手机号',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='服务对象联系人信息';

-- ----------------------------
--  Records of `server_object_relation`
-- ----------------------------
BEGIN;
INSERT INTO `server_object_relation` VALUES ('1', '127', '0', '', '', '0000-00-00 00:00:00');
COMMIT;

-- ----------------------------
--  Table structure for `staff_group`
-- ----------------------------
DROP TABLE IF EXISTS `staff_group`;
CREATE TABLE `staff_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '技能组id',
  `name` varchar(32) NOT NULL COMMENT '技能组名称',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '技能组id',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态信息',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `staff_group`
-- ----------------------------
BEGIN;
INSERT INTO `staff_group` VALUES ('4', '云池技能组', '593', '1', '1545636239');
COMMIT;

-- ----------------------------
--  Table structure for `staff_users`
-- ----------------------------
DROP TABLE IF EXISTS `staff_users`;
CREATE TABLE `staff_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `display_name` varchar(32) NOT NULL COMMENT '用户显示名称',
  `phone` varchar(15) NOT NULL COMMENT '用户电话号码',
  `gid` int(11) NOT NULL COMMENT '分组id',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `work_number` varchar(16) NOT NULL COMMENT '用户工号',
  `call_time` smallint(6) NOT NULL DEFAULT '100' COMMENT '呼叫限制',
  `number` int(11) NOT NULL COMMENT '用户分机号',
  `last_time` int(11) NOT NULL COMMENT '最后登陆时间',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1012 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `staff_users`
-- ----------------------------
BEGIN;
INSERT INTO `staff_users` VALUES ('1010', '测试账号', '17686531379', '593', 'lhf123', 'YC1009', '100', '1009', '0', '1545644419'), ('1011', '话务员', '17686531378', '593', 'lhf123', 'YC1011', '100', '1011', '0', '1545646433');
COMMIT;

-- ----------------------------
--  Table structure for `system_call`
-- ----------------------------
DROP TABLE IF EXISTS `system_call`;
CREATE TABLE `system_call` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '子账号昵称',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(32) NOT NULL DEFAULT '',
  `sid` char(32) NOT NULL DEFAULT '' COMMENT '子账户id',
  `token` char(32) NOT NULL DEFAULT '' COMMENT '子账户token',
  `number` int(11) NOT NULL COMMENT '企业总机号码',
  `call_url` varchar(63) NOT NULL DEFAULT '' COMMENT '呼叫请求鉴权服务器url',
  `user_data` varchar(63) NOT NULL DEFAULT '' COMMENT '用户私有数据',
  `limit_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '限制类型 0-不限制(默认); 1-自然月+周一开 始计周; 2-自然月+周日开始计周; 3-非自然周 /月:当日前一周或月统计',
  `day_limit` tinyint(2) NOT NULL DEFAULT '0' COMMENT '每天呼叫次数限制(1-10)，0 表示不限制; 未输入表示保留上次的设置值',
  `week_limit` tinyint(2) NOT NULL DEFAULT '0' COMMENT '每周呼叫次数限制(1-25)，0 表示不限制; 未输入表示保留上次的设置值。',
  `month_limit` tinyint(2) NOT NULL DEFAULT '0' COMMENT '每月呼叫次数限制(1-50)，0 表示不限制; 未输入表示保留上次的设置值。',
  `big_number` int(11) NOT NULL COMMENT '当前账号用户最大工号',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用禁用 1启用 0禁用',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `system_call`
-- ----------------------------
BEGIN;
INSERT INTO `system_call` VALUES ('1', '测试账号', '17686531379', '17686531379@163.com', '835f10b3cae9e7fd934a1e3cebbf46f9', 'dbba7b2b1cf003751e9ada2b450ff6fb', '2147483647', 'http://www.yunchi.cn/', 'YUNCHIDATA', '0', '0', '0', '0', '1012', '1', '1545630599');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
