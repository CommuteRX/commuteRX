/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : jackrobe-db

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-11-29 22:14:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for crx_user_route
-- ----------------------------
DROP TABLE IF EXISTS `crx_user_route`;
CREATE TABLE `crx_user_route` (
  `uid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `depart_time` datetime DEFAULT NULL,
  `arrival_time` datetime DEFAULT NULL,
  KEY `ur_rid` (`rid`),
  KEY `ur_uid` (`uid`),
  CONSTRAINT `ur_uid` FOREIGN KEY (`uid`) REFERENCES `crx_user` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ur_rid` FOREIGN KEY (`rid`) REFERENCES `crx_routes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
