SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for userpassword
-- ----------------------------
DROP TABLE IF EXISTS `userpassword`;
CREATE TABLE `userpassword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` varchar(15) DEFAULT NULL,
  `jw` varchar(20) DEFAULT NULL COMMENT '教务处密码',
  `cw` varchar(20) DEFAULT NULL COMMENT '财务处密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
