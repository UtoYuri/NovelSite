-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-05-24 18:45:39
-- 服务器版本： 5.7.9
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `novel_site`
--

-- --------------------------------------------------------

--
-- 表的结构 `t_level`
--

DROP TABLE IF EXISTS `t_level`;
CREATE TABLE IF NOT EXISTS `t_level` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(100) NOT NULL,
  `min_words` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `t_level`
--

INSERT INTO `t_level` (`uid`, `uname`, `min_words`, `is_active`) VALUES
(1, '白丁', 0, 1),
(2, '书生', 50000, 1),
(3, '秀才', 500000, 1),
(4, '状元', 2000000, 1),
(5, '鸿儒', 5000000, 1),
(6, '文豪', 10000000, 1);

-- --------------------------------------------------------

--
-- 表的结构 `t_mail`
--

DROP TABLE IF EXISTS `t_mail`;
CREATE TABLE IF NOT EXISTS `t_mail` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `umail` varchar(100) NOT NULL,
  `vertify_code` varchar(6) DEFAULT NULL,
  `last_request_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `umail` (`umail`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `t_mail`
--

INSERT INTO `t_mail` (`uid`, `umail`, `vertify_code`, `last_request_time`) VALUES
(7, '416694631@qq.com', '268035', '2017-05-23 08:30:12'),
(8, 'imryss@qq.com', '000000', '2017-05-23 08:52:00');

-- --------------------------------------------------------

--
-- 表的结构 `t_message`
--

DROP TABLE IF EXISTS `t_message`;
CREATE TABLE IF NOT EXISTS `t_message` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user_sender_id` int(11) NOT NULL,
  `user_receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `post_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `t_message`
--

INSERT INTO `t_message` (`uid`, `user_sender_id`, `user_receiver_id`, `message`, `status`, `post_time`) VALUES
(1, 5, 5, '这是一条测试站内信', 'readed', '2017-05-23 16:48:41');

-- --------------------------------------------------------

--
-- 表的结构 `t_notes`
--

DROP TABLE IF EXISTS `t_notes`;
CREATE TABLE IF NOT EXISTS `t_notes` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `notes` text NOT NULL,
  `post_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_novel_cate`
--

DROP TABLE IF EXISTS `t_novel_cate`;
CREATE TABLE IF NOT EXISTS `t_novel_cate` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_novel_chapter`
--

DROP TABLE IF EXISTS `t_novel_chapter`;
CREATE TABLE IF NOT EXISTS `t_novel_chapter` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `GUID` varchar(20) NOT NULL,
  `unovel_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `content` longtext NOT NULL,
  `words` int(11) DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_novel_info`
--

DROP TABLE IF EXISTS `t_novel_info`;
CREATE TABLE IF NOT EXISTS `t_novel_info` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `GUID` varchar(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `rank` float NOT NULL DEFAULT '0',
  `author` varchar(100) NOT NULL,
  `cover` varchar(200) NOT NULL,
  `ucate_id` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `discount` float NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  `meta_key` text NOT NULL,
  `meta_desc` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_propose`
--

DROP TABLE IF EXISTS `t_propose`;
CREATE TABLE IF NOT EXISTS `t_propose` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `post_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `t_propose`
--

INSERT INTO `t_propose` (`uid`, `user_id`, `tag`, `message`, `post_time`) VALUES
(1, 2, '这是测试留言', '测试', '2017-05-22 19:08:05');

-- --------------------------------------------------------

--
-- 表的结构 `t_purchase_token`
--

DROP TABLE IF EXISTS `t_purchase_token`;
CREATE TABLE IF NOT EXISTS `t_purchase_token` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `token` varchar(32) NOT NULL,
  `purchase_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_read_record`
--

DROP TABLE IF EXISTS `t_read_record`;
CREATE TABLE IF NOT EXISTS `t_read_record` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `last_read_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_user`
--

DROP TABLE IF EXISTS `t_user`;
CREATE TABLE IF NOT EXISTS `t_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `umail_id` int(11) DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `user_level` int(11) NOT NULL DEFAULT '1',
  `pocket` int(11) NOT NULL DEFAULT '50',
  `reg_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_key` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `umail_id` (`umail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `t_user`
--

INSERT INTO `t_user` (`uid`, `umail_id`, `password`, `is_admin`, `user_level`, `pocket`, `reg_time`, `session_key`) VALUES
(2, 7, '670b14728ad9902aecba32e22fa4f6bd', 0, 1, 50, '2017-05-22 17:19:48', 'da639654e75d5b7c2cbf604086399965'),
(5, 8, '670b14728ad9902aecba32e22fa4f6bd', 1, 1, 50, '2017-05-23 08:55:43', 'd99f8d56c16fddbd03f0b9659889f69f');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
