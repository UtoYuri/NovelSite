-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-06-08 23:54:48
-- 服务器版本： 5.7.12-log
-- PHP Version: 5.5.35

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

CREATE TABLE IF NOT EXISTS `t_level` (
  `uid` int(11) NOT NULL,
  `uname` varchar(100) NOT NULL,
  `min_words` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_mail`
--

CREATE TABLE IF NOT EXISTS `t_mail` (
  `uid` int(11) NOT NULL,
  `umail` varchar(100) NOT NULL,
  `vertify_code` varchar(6) DEFAULT NULL,
  `last_request_time` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_message`
--

CREATE TABLE IF NOT EXISTS `t_message` (
  `uid` int(11) NOT NULL,
  `user_sender_id` int(11) NOT NULL,
  `user_receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `post_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_notes`
--

CREATE TABLE IF NOT EXISTS `t_notes` (
  `uid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `notes` text NOT NULL,
  `post_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_novel_cate`
--

CREATE TABLE IF NOT EXISTS `t_novel_cate` (
  `uid` int(11) NOT NULL,
  `uname` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_novel_chapter`
--

CREATE TABLE IF NOT EXISTS `t_novel_chapter` (
  `uid` int(11) NOT NULL,
  `GUID` varchar(20) NOT NULL,
  `unovel_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `content` longtext NOT NULL,
  `words` int(11) DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_novel_info`
--

CREATE TABLE IF NOT EXISTS `t_novel_info` (
  `uid` int(11) NOT NULL,
  `GUID` varchar(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `rank` float NOT NULL DEFAULT '0',
  `author` varchar(100) NOT NULL,
  `cover` varchar(200) NOT NULL,
  `ucate_id` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '2',
  `discount` float NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  `meta_key` text NOT NULL,
  `meta_desc` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_propose`
--

CREATE TABLE IF NOT EXISTS `t_propose` (
  `uid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `post_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_purchase_token`
--

CREATE TABLE IF NOT EXISTS `t_purchase_token` (
  `uid` int(11) NOT NULL,
  `uorder` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `token` varchar(32) NOT NULL,
  `purchase_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_read_record`
--

CREATE TABLE IF NOT EXISTS `t_read_record` (
  `uid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `last_read_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `t_user`
--

CREATE TABLE IF NOT EXISTS `t_user` (
  `uid` int(11) NOT NULL,
  `umail_id` int(11) DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `user_level` int(11) NOT NULL DEFAULT '1',
  `pocket` float NOT NULL DEFAULT '999',
  `reg_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_key` varchar(32) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_level`
--
ALTER TABLE `t_level`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `t_mail`
--
ALTER TABLE `t_mail`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `umail` (`umail`);

--
-- Indexes for table `t_message`
--
ALTER TABLE `t_message`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `t_notes`
--
ALTER TABLE `t_notes`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `t_novel_cate`
--
ALTER TABLE `t_novel_cate`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `t_novel_chapter`
--
ALTER TABLE `t_novel_chapter`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `GUID` (`GUID`);

--
-- Indexes for table `t_novel_info`
--
ALTER TABLE `t_novel_info`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `t_propose`
--
ALTER TABLE `t_propose`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `t_purchase_token`
--
ALTER TABLE `t_purchase_token`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `t_read_record`
--
ALTER TABLE `t_read_record`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `umail_id` (`umail_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_level`
--
ALTER TABLE `t_level`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_mail`
--
ALTER TABLE `t_mail`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_message`
--
ALTER TABLE `t_message`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_notes`
--
ALTER TABLE `t_notes`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_novel_cate`
--
ALTER TABLE `t_novel_cate`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_novel_chapter`
--
ALTER TABLE `t_novel_chapter`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_novel_info`
--
ALTER TABLE `t_novel_info`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_propose`
--
ALTER TABLE `t_propose`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_purchase_token`
--
ALTER TABLE `t_purchase_token`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_read_record`
--
ALTER TABLE `t_read_record`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
