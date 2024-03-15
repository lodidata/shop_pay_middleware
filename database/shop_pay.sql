/*
Navicat MySQL Data Transfer

Source Server         : 52.74.208.242
Source Server Version : 50726
Source Host           : 52.74.208.242:3308
Source Database       : shop_pay

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2023-07-12 17:47:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for site
-- ----------------------------
DROP TABLE IF EXISTS `site`;
CREATE TABLE `site` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '站点名称',
  `code` char(4) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '站点编号',
  `callback_url` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '回调地址',
  `redirect_url` varchar(100) NOT NULL DEFAULT '' COMMENT '重定向url',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_code` (`code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='站点表';
SET FOREIGN_KEY_CHECKS=1;
