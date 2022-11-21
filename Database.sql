-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 21, 2022 at 05:05 AM
-- Server version: 10.5.12-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u866174927_alidb`
--

-- --------------------------------------------------------

--
-- Table structure for table `ali_academicrecords`
--

DROP TABLE IF EXISTS `ali_academicrecords`;
CREATE TABLE `ali_academicrecords` (
  `no` mediumint(9) NOT NULL,
  `students_no` mediumint(9) NOT NULL,
  `schoolyear` varchar(10) NOT NULL,
  `trimester` varchar(2) NOT NULL,
  `level` int(1) DEFAULT 0,
  `session` int(1) DEFAULT 0,
  `att_score` varchar(3) DEFAULT '0',
  `ls_score` varchar(3) DEFAULT '0',
  `rw_score` varchar(3) DEFAULT '0',
  `toefl_score` varchar(3) DEFAULT '0',
  `gubun` int(1) NOT NULL DEFAULT 2,
  `plt_score` varchar(3) DEFAULT '0',
  `plt_date` date DEFAULT '0000-00-00',
  `plt_note` varchar(255) DEFAULT '0',
  `writer` varchar(50) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_acl`
--

DROP TABLE IF EXISTS `ali_acl`;
CREATE TABLE `ali_acl` (
  `id` int(11) NOT NULL,
  `type` enum('role','user') NOT NULL,
  `type_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `action` enum('allow','deny') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_aclresources`
--

DROP TABLE IF EXISTS `ali_aclresources`;
CREATE TABLE `ali_aclresources` (
  `id` int(11) NOT NULL,
  `resource` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `aclgroup` varchar(255) NOT NULL,
  `aclgrouporder` int(11) NOT NULL,
  `default_value` enum('true','false') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_aclroles`
--

DROP TABLE IF EXISTS `ali_aclroles`;
CREATE TABLE `ali_aclroles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `roleorder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_assignments`
--

DROP TABLE IF EXISTS `ali_assignments`;
CREATE TABLE `ali_assignments` (
  `no` mediumint(9) NOT NULL,
  `assigncat_no` mediumint(9) NOT NULL DEFAULT 0,
  `class_no` mediumint(9) NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL,
  `points` varchar(10) NOT NULL,
  `duedate` date NOT NULL DEFAULT '0000-00-00',
  `description` varchar(255) DEFAULT NULL,
  `isview` int(11) NOT NULL DEFAULT 0,
  `isdiscuss` int(11) NOT NULL DEFAULT 0,
  `writer` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_assign_cate`
--

DROP TABLE IF EXISTS `ali_assign_cate`;
CREATE TABLE `ali_assign_cate` (
  `no` mediumint(9) NOT NULL,
  `class_no` mediumint(9) NOT NULL DEFAULT 0,
  `name` varchar(50) DEFAULT NULL,
  `wpercentage` varchar(10) DEFAULT NULL,
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_assign_cate_basic`
--

DROP TABLE IF EXISTS `ali_assign_cate_basic`;
CREATE TABLE `ali_assign_cate_basic` (
  `no` mediumint(9) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `wpercentage` varchar(10) DEFAULT NULL,
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_attendance`
--

DROP TABLE IF EXISTS `ali_attendance`;
CREATE TABLE `ali_attendance` (
  `attendance_no` mediumint(9) UNSIGNED NOT NULL,
  `semester_no` mediumint(9) NOT NULL,
  `class_no` mediumint(9) NOT NULL DEFAULT 0,
  `class_group_no` mediumint(9) UNSIGNED NOT NULL,
  `period2` enum('1st','2nd') NOT NULL DEFAULT '1st',
  `instructors_no` mediumint(9) NOT NULL,
  `students_no` mediumint(9) NOT NULL DEFAULT 0,
  `items` enum('P','T1','T2','A','E') NOT NULL DEFAULT 'P',
  `attendance_day` date NOT NULL DEFAULT '0000-00-00',
  `enter_time` time DEFAULT NULL,
  `exit_time` time DEFAULT NULL,
  `reason_absent` tinytext DEFAULT NULL,
  `GUBUN` enum('R','E') NOT NULL DEFAULT 'R',
  `file_no` mediumint(9) DEFAULT NULL,
  `writer_no` mediumint(9) NOT NULL DEFAULT 0,
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_attendance_new`
--

DROP TABLE IF EXISTS `ali_attendance_new`;
CREATE TABLE `ali_attendance_new` (
  `no` int(10) UNSIGNED NOT NULL,
  `class_no` mediumint(9) NOT NULL DEFAULT 0,
  `student_no` mediumint(9) NOT NULL DEFAULT 0,
  `marks` varchar(2) DEFAULT NULL,
  `attendance_day` date NOT NULL DEFAULT '0000-00-00',
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_calboard`
--

DROP TABLE IF EXISTS `ali_calboard`;
CREATE TABLE `ali_calboard` (
  `calboard_no` mediumint(9) NOT NULL,
  `user_no` mediumint(9) DEFAULT NULL,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `contents` text DEFAULT NULL,
  `thisday` date NOT NULL DEFAULT '0000-00-00',
  `state` enum('C','P') NOT NULL DEFAULT 'P',
  `permission` enum('P','S') NOT NULL DEFAULT 'P',
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_calboard_reply`
--

DROP TABLE IF EXISTS `ali_calboard_reply`;
CREATE TABLE `ali_calboard_reply` (
  `reply_no` mediumint(9) NOT NULL,
  `calboard_no` mediumint(9) NOT NULL DEFAULT 0,
  `user_no` mediumint(9) DEFAULT NULL,
  `contents` text DEFAULT NULL,
  `thisday` date NOT NULL DEFAULT '0000-00-00',
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_class`
--

DROP TABLE IF EXISTS `ali_class`;
CREATE TABLE `ali_class` (
  `no` mediumint(9) NOT NULL,
  `schoolyear` varchar(10) NOT NULL,
  `trimester` varchar(2) NOT NULL,
  `level` int(1) NOT NULL DEFAULT 0,
  `session` int(1) NOT NULL DEFAULT 0,
  `classtype` varchar(10) NOT NULL,
  `room_no` tinyint(4) NOT NULL DEFAULT 0,
  `name` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_classes`
--

DROP TABLE IF EXISTS `ali_classes`;
CREATE TABLE `ali_classes` (
  `classes_no` mediumint(9) NOT NULL,
  `semester_no` mediumint(9) NOT NULL DEFAULT 0,
  `class_group_no` mediumint(9) NOT NULL DEFAULT 0,
  `instructors_no` tinyint(10) NOT NULL DEFAULT 0,
  `lecture_room_no` tinyint(10) NOT NULL DEFAULT 0,
  `class_name` varchar(50) DEFAULT NULL,
  `period` enum('am','pm','mm') NOT NULL DEFAULT 'am',
  `period2` enum('1st','2nd') NOT NULL DEFAULT '1st',
  `items` enum('i','u') NOT NULL DEFAULT 'u',
  `engradeid` varchar(15) DEFAULT NULL,
  `writer_no` mediumint(9) DEFAULT NULL,
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_classteachers`
--

DROP TABLE IF EXISTS `ali_classteachers`;
CREATE TABLE `ali_classteachers` (
  `no` mediumint(9) NOT NULL,
  `class_no` mediumint(9) NOT NULL DEFAULT 0,
  `teacher_no` mediumint(9) NOT NULL,
  `teachername` varchar(50) DEFAULT NULL,
  `isprimary` int(11) DEFAULT 0,
  `permission` int(11) DEFAULT 0,
  `writer` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedate` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_class_group`
--

DROP TABLE IF EXISTS `ali_class_group`;
CREATE TABLE `ali_class_group` (
  `class_group_no` mediumint(9) NOT NULL,
  `group_name` varchar(50) DEFAULT NULL,
  `startday` date DEFAULT NULL,
  `endday` date DEFAULT NULL,
  `sum_holidays` smallint(10) DEFAULT 0,
  `sum_classes` smallint(10) DEFAULT 0,
  `items` enum('i','u') NOT NULL DEFAULT 'u',
  `path` varchar(255) NOT NULL DEFAULT '/',
  `rno` mediumint(9) NOT NULL DEFAULT 0,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_enroll_class`
--

DROP TABLE IF EXISTS `ali_enroll_class`;
CREATE TABLE `ali_enroll_class` (
  `enroll_class_no` mediumint(9) NOT NULL,
  `students_no` mediumint(9) NOT NULL DEFAULT 0,
  `semester_no` mediumint(9) NOT NULL,
  `class_group_no` mediumint(9) NOT NULL DEFAULT 0,
  `classes_no` mediumint(9) NOT NULL,
  `startday` date NOT NULL DEFAULT '2011-01-03',
  `endday` date NOT NULL DEFAULT '2011-04-01',
  `sum_classes` smallint(10) NOT NULL DEFAULT 0,
  `gubun` enum('R','A','B','C','D','E') NOT NULL DEFAULT 'R',
  `etc` tinytext DEFAULT NULL,
  `memo` tinytext DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_files`
--

DROP TABLE IF EXISTS `ali_files`;
CREATE TABLE `ali_files` (
  `no` mediumint(9) NOT NULL,
  `gno` int(11) NOT NULL DEFAULT 0,
  `rno` mediumint(9) NOT NULL DEFAULT 0,
  `filename` varchar(255) DEFAULT NULL,
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_finance`
--

DROP TABLE IF EXISTS `ali_finance`;
CREATE TABLE `ali_finance` (
  `no` mediumint(9) NOT NULL,
  `students_no` mediumint(9) NOT NULL,
  `schoolyear` varchar(10) NOT NULL,
  `trimester` varchar(2) NOT NULL,
  `paiddate` date NOT NULL DEFAULT '0000-00-00',
  `description` varchar(255) DEFAULT NULL,
  `latefees` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amountpaid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `refunds` decimal(10,2) NOT NULL DEFAULT 0.00,
  `method` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `writer` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_grade`
--

DROP TABLE IF EXISTS `ali_grade`;
CREATE TABLE `ali_grade` (
  `grade_no` mediumint(9) NOT NULL,
  `students_no` mediumint(9) NOT NULL,
  `classes_no` mediumint(9) NOT NULL,
  `class_group_no` mediumint(9) NOT NULL,
  `semester_no` mediumint(9) NOT NULL DEFAULT 0,
  `att_score` varchar(15) DEFAULT '0',
  `part_score` varchar(15) DEFAULT '0',
  `home_score` varchar(15) DEFAULT '0',
  `quiz_score` varchar(15) DEFAULT '0',
  `midtem_score` varchar(15) DEFAULT '0',
  `final_score` varchar(15) DEFAULT '0',
  `record` varchar(15) DEFAULT NULL,
  `file_no` mediumint(9) DEFAULT NULL,
  `write_no` mediumint(9) NOT NULL,
  `writer` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_grade_new`
--

DROP TABLE IF EXISTS `ali_grade_new`;
CREATE TABLE `ali_grade_new` (
  `no` mediumint(9) NOT NULL,
  `class_no` mediumint(9) NOT NULL DEFAULT 0,
  `assign_no` mediumint(9) NOT NULL DEFAULT 0,
  `assigncat_no` mediumint(9) NOT NULL DEFAULT 0,
  `student_no` mediumint(9) NOT NULL DEFAULT 0,
  `score` varchar(10) NOT NULL,
  `writer` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_gradingperiod`
--

DROP TABLE IF EXISTS `ali_gradingperiod`;
CREATE TABLE `ali_gradingperiod` (
  `no` mediumint(9) NOT NULL,
  `schoolyear` varchar(50) NOT NULL,
  `gradingperiod` varchar(2) NOT NULL,
  `startday` date NOT NULL,
  `endday` date NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `writer` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_instructors`
--

DROP TABLE IF EXISTS `ali_instructors`;
CREATE TABLE `ali_instructors` (
  `instructors_no` mediumint(9) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `initial` varchar(5) DEFAULT NULL,
  `bgcolorone` varchar(10) DEFAULT NULL,
  `authority` enum('1','2','3','4') NOT NULL DEFAULT '3',
  `role_id` int(10) DEFAULT NULL,
  `user_ID` varchar(30) NOT NULL DEFAULT '',
  `passw` varchar(41) NOT NULL,
  `cellphone` varchar(14) NOT NULL DEFAULT '0000-0000-0000',
  `email` varchar(50) DEFAULT NULL,
  `etc` tinytext DEFAULT NULL,
  `status` enum('A','Q') NOT NULL DEFAULT 'A',
  `writer_no` mediumint(9) NOT NULL,
  `writer` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_level`
--

DROP TABLE IF EXISTS `ali_level`;
CREATE TABLE `ali_level` (
  `no` mediumint(9) NOT NULL,
  `levelname` varchar(30) DEFAULT NULL,
  `levelvalue` int(2) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_makeup`
--

DROP TABLE IF EXISTS `ali_makeup`;
CREATE TABLE `ali_makeup` (
  `makeup_no` mediumint(9) NOT NULL,
  `students_no` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `semester_no` mediumint(9) NOT NULL,
  `class_group_no` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `classes_no` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `instructors_no` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `attendance_day` date NOT NULL DEFAULT '0000-00-00',
  `enter_time` time NOT NULL DEFAULT '00:00:00',
  `exit_time` time DEFAULT NULL,
  `etc` tinytext DEFAULT NULL,
  `writer_no` mediumint(9) NOT NULL,
  `writer` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_recoverpwd`
--

DROP TABLE IF EXISTS `ali_recoverpwd`;
CREATE TABLE `ali_recoverpwd` (
  `ID` int(10) UNSIGNED NOT NULL,
  `UserID` varchar(30) NOT NULL,
  `Keyval` varchar(32) NOT NULL,
  `expDate` datetime NOT NULL,
  `gubun` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_remedialattendance`
--

DROP TABLE IF EXISTS `ali_remedialattendance`;
CREATE TABLE `ali_remedialattendance` (
  `no` int(10) UNSIGNED NOT NULL,
  `class_no` mediumint(9) NOT NULL DEFAULT 0,
  `student_no` mediumint(9) NOT NULL DEFAULT 0,
  `marks` varchar(2) DEFAULT NULL,
  `attendance_day` date NOT NULL DEFAULT '0000-00-00',
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_remedialclass`
--

DROP TABLE IF EXISTS `ali_remedialclass`;
CREATE TABLE `ali_remedialclass` (
  `no` mediumint(9) NOT NULL,
  `schoolyear` varchar(10) NOT NULL,
  `trimester` varchar(2) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `session` int(11) NOT NULL DEFAULT 0,
  `classtype` varchar(10) NOT NULL,
  `room_no` tinyint(4) NOT NULL DEFAULT 0,
  `name` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_remedialclassteachers`
--

DROP TABLE IF EXISTS `ali_remedialclassteachers`;
CREATE TABLE `ali_remedialclassteachers` (
  `no` mediumint(9) NOT NULL,
  `class_no` mediumint(9) NOT NULL DEFAULT 0,
  `teacher_no` mediumint(9) NOT NULL,
  `teachername` varchar(50) DEFAULT NULL,
  `isprimary` int(11) DEFAULT 0,
  `permission` int(11) DEFAULT 0,
  `writer` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedate` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_remedialroster`
--

DROP TABLE IF EXISTS `ali_remedialroster`;
CREATE TABLE `ali_remedialroster` (
  `no` mediumint(9) NOT NULL,
  `schoolyear` varchar(10) NOT NULL,
  `trimester` varchar(2) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `session` int(11) NOT NULL DEFAULT 0,
  `classtype` varchar(10) NOT NULL,
  `class_no` mediumint(9) NOT NULL,
  `students_no` mediumint(9) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `student_ID` varchar(20) NOT NULL,
  `writer` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_rooms`
--

DROP TABLE IF EXISTS `ali_rooms`;
CREATE TABLE `ali_rooms` (
  `no` mediumint(9) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_roster`
--

DROP TABLE IF EXISTS `ali_roster`;
CREATE TABLE `ali_roster` (
  `no` mediumint(9) NOT NULL,
  `schoolyear` varchar(10) NOT NULL,
  `trimester` varchar(2) NOT NULL,
  `level` int(1) NOT NULL DEFAULT 0,
  `session` int(1) NOT NULL DEFAULT 0,
  `classtype` varchar(10) NOT NULL,
  `class_no` mediumint(9) NOT NULL,
  `students_no` mediumint(9) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `student_ID` varchar(20) NOT NULL,
  `writer` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_specialday`
--

DROP TABLE IF EXISTS `ali_specialday`;
CREATE TABLE `ali_specialday` (
  `specialday_no` mediumint(9) NOT NULL,
  `class_group_no` mediumint(9) NOT NULL DEFAULT 0,
  `special_day` date NOT NULL DEFAULT '0000-00-00',
  `subject` tinytext NOT NULL,
  `items` enum('S','E','H','W','N') NOT NULL DEFAULT 'H',
  `writer_no` mediumint(9) NOT NULL,
  `writer` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_students`
--

DROP TABLE IF EXISTS `ali_students`;
CREATE TABLE `ali_students` (
  `students_no` mediumint(9) NOT NULL,
  `student_ID` varchar(20) DEFAULT NULL,
  `user_ID` varchar(30) NOT NULL,
  `passw` varchar(67) NOT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `firstname` varchar(30) DEFAULT NULL,
  `nickname` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `gender` enum('m','f') NOT NULL DEFAULT 'm',
  `cellphone` varchar(60) NOT NULL DEFAULT '0000-0000-0000',
  `cellphone2` varchar(60) DEFAULT NULL,
  `emergencyphone` varchar(60) DEFAULT '0000-0000-0000',
  `emergencyphone2` varchar(60) DEFAULT NULL,
  `address1` varchar(150) DEFAULT '',
  `address2` varchar(150) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `email2` varchar(50) DEFAULT NULL,
  `country` varchar(50) NOT NULL DEFAULT '',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `items` enum('n','d') NOT NULL DEFAULT 'n',
  `register_day` date DEFAULT NULL,
  `preschool` varchar(150) DEFAULT NULL,
  `preschool2` varchar(150) DEFAULT NULL,
  `transfer` varchar(150) DEFAULT NULL,
  `etc_memo` tinytext DEFAULT NULL,
  `note` tinytext DEFAULT NULL,
  `memo` text DEFAULT NULL,
  `progress` enum('c','a','n','r','w','v','s','d','p','f','o','e','t','h','m','l','z') NOT NULL DEFAULT 'c',
  `last_ip` varchar(40) DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `writer` varchar(50) NOT NULL,
  `probation` varchar(20) NOT NULL DEFAULT 'a2',
  `probation2` varchar(20) NOT NULL DEFAULT 'b2',
  `probation3` varchar(20) NOT NULL DEFAULT 'c2',
  `withd_day` date DEFAULT NULL,
  `complete` varchar(20) NOT NULL DEFAULT 'd2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_student_consult`
--

DROP TABLE IF EXISTS `ali_student_consult`;
CREATE TABLE `ali_student_consult` (
  `consult_no` mediumint(9) NOT NULL,
  `students_no` mediumint(9) NOT NULL DEFAULT 0,
  `recordday` date NOT NULL DEFAULT '0000-00-00',
  `memo` mediumtext DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `writer` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_student_family`
--

DROP TABLE IF EXISTS `ali_student_family`;
CREATE TABLE `ali_student_family` (
  `family_no` mediumint(9) NOT NULL,
  `students_no` mediumint(9) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `birthday` varchar(20) DEFAULT NULL,
  `memo` tinytext DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_user`
--

DROP TABLE IF EXISTS `ali_user`;
CREATE TABLE `ali_user` (
  `no` mediumint(9) NOT NULL,
  `user_ID` varchar(30) NOT NULL DEFAULT '',
  `passw` varchar(67) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `initial` varchar(5) DEFAULT NULL,
  `bgcolorone` varchar(10) DEFAULT NULL,
  `roleid` int(11) NOT NULL,
  `cellphone` varchar(14) DEFAULT NULL,
  `etc` varchar(255) DEFAULT NULL,
  `last_ip` varchar(40) DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `schoolemail` varchar(30) DEFAULT NULL,
  `schoolpassword` varchar(30) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `writer_no` mediumint(9) NOT NULL,
  `writer` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_user_login_attempt`
--

DROP TABLE IF EXISTS `ali_user_login_attempt`;
CREATE TABLE `ali_user_login_attempt` (
  `id` int(11) NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(120) DEFAULT NULL,
  `ip_address` varchar(120) NOT NULL,
  `success` tinyint(1) NOT NULL,
  `user_agent` varchar(180) DEFAULT NULL,
  `note` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ali_warning_letter`
--

DROP TABLE IF EXISTS `ali_warning_letter`;
CREATE TABLE `ali_warning_letter` (
  `letter_no` mediumint(9) NOT NULL,
  `receiver_no` mediumint(9) DEFAULT 0,
  `receiver` varchar(255) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `contents` mediumtext NOT NULL,
  `semester_no` mediumint(9) NOT NULL,
  `items` enum('0','1','2','3','4','r3','r4','7') NOT NULL DEFAULT '0',
  `reasons` enum('A','C','N') NOT NULL DEFAULT 'N',
  `upfilename` varchar(255) DEFAULT NULL,
  `attendance` varchar(15) DEFAULT NULL,
  `writer_no` mediumint(9) NOT NULL DEFAULT 0,
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `eg_attendance`
--

DROP TABLE IF EXISTS `eg_attendance`;
CREATE TABLE `eg_attendance` (
  `no` int(10) UNSIGNED NOT NULL,
  `file_no` int(10) UNSIGNED NOT NULL,
  `trimester_no` mediumint(9) NOT NULL,
  `engradeclassid` varchar(15) DEFAULT NULL,
  `classschoolyear` varchar(10) DEFAULT NULL,
  `classgradingperiod` varchar(2) DEFAULT NULL,
  `classname` varchar(50) DEFAULT NULL,
  `studentfirst` varchar(30) DEFAULT NULL,
  `studentlast` varchar(30) DEFAULT NULL,
  `studentid` varchar(20) DEFAULT NULL,
  `attendancedate` date DEFAULT NULL,
  `mark` varchar(2) DEFAULT NULL,
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `eg_grades`
--

DROP TABLE IF EXISTS `eg_grades`;
CREATE TABLE `eg_grades` (
  `no` int(10) UNSIGNED NOT NULL,
  `file_no` int(10) UNSIGNED NOT NULL,
  `engradeclassid` varchar(15) DEFAULT NULL,
  `classschoolyear` varchar(10) DEFAULT NULL,
  `classgradingperiod` varchar(2) DEFAULT NULL,
  `classname` varchar(50) DEFAULT NULL,
  `teachername` varchar(50) DEFAULT NULL,
  `studentfirst` varchar(30) DEFAULT NULL,
  `studentlast` varchar(30) DEFAULT NULL,
  `studentid` varchar(20) DEFAULT NULL,
  `grade` varchar(3) DEFAULT NULL,
  `percent` varchar(15) DEFAULT NULL,
  `missing` varchar(15) DEFAULT NULL,
  `teachercomment` varchar(255) DEFAULT NULL,
  `writer` varchar(50) DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `trimester_no` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `eg_importfiles`
--

DROP TABLE IF EXISTS `eg_importfiles`;
CREATE TABLE `eg_importfiles` (
  `file_no` mediumint(9) UNSIGNED NOT NULL,
  `trimester_no` mediumint(9) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `writer` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `eg_importfiles_g`
--

DROP TABLE IF EXISTS `eg_importfiles_g`;
CREATE TABLE `eg_importfiles_g` (
  `file_no` mediumint(9) UNSIGNED NOT NULL,
  `trimester_no` mediumint(9) NOT NULL,
  `title` varchar(150) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `active` int(11) NOT NULL,
  `writer` varchar(50) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ali_academicrecords`
--
ALTER TABLE `ali_academicrecords`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `ali_acl`
--
ALTER TABLE `ali_acl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ali_aclresources`
--
ALTER TABLE `ali_aclresources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ali_aclroles`
--
ALTER TABLE `ali_aclroles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ali_assignments`
--
ALTER TABLE `ali_assignments`
  ADD PRIMARY KEY (`no`),
  ADD KEY `ali_assignments_ibfk_1` (`assigncat_no`),
  ADD KEY `ali_assignments_ibfk_2` (`class_no`);

--
-- Indexes for table `ali_assign_cate`
--
ALTER TABLE `ali_assign_cate`
  ADD PRIMARY KEY (`no`),
  ADD KEY `ali_assign_cate_ibfk_1` (`class_no`);

--
-- Indexes for table `ali_assign_cate_basic`
--
ALTER TABLE `ali_assign_cate_basic`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `ali_attendance`
--
ALTER TABLE `ali_attendance`
  ADD PRIMARY KEY (`attendance_no`),
  ADD UNIQUE KEY `ATTENDANCE_INDEX` (`students_no`,`attendance_day`,`class_no`);

--
-- Indexes for table `ali_attendance_new`
--
ALTER TABLE `ali_attendance_new`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `ATTENDANCE_INDEX` (`student_no`,`attendance_day`,`class_no`),
  ADD KEY `fk_class_no` (`class_no`);

--
-- Indexes for table `ali_calboard`
--
ALTER TABLE `ali_calboard`
  ADD PRIMARY KEY (`calboard_no`);

--
-- Indexes for table `ali_calboard_reply`
--
ALTER TABLE `ali_calboard_reply`
  ADD PRIMARY KEY (`reply_no`);

--
-- Indexes for table `ali_class`
--
ALTER TABLE `ali_class`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `ali_classes`
--
ALTER TABLE `ali_classes`
  ADD PRIMARY KEY (`classes_no`);

--
-- Indexes for table `ali_classteachers`
--
ALTER TABLE `ali_classteachers`
  ADD PRIMARY KEY (`no`),
  ADD KEY `class_no` (`class_no`),
  ADD KEY `teacher_no` (`teacher_no`);

--
-- Indexes for table `ali_class_group`
--
ALTER TABLE `ali_class_group`
  ADD PRIMARY KEY (`class_group_no`);

--
-- Indexes for table `ali_enroll_class`
--
ALTER TABLE `ali_enroll_class`
  ADD PRIMARY KEY (`enroll_class_no`);

--
-- Indexes for table `ali_files`
--
ALTER TABLE `ali_files`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `ali_finance`
--
ALTER TABLE `ali_finance`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `ali_grade`
--
ALTER TABLE `ali_grade`
  ADD PRIMARY KEY (`grade_no`);

--
-- Indexes for table `ali_grade_new`
--
ALTER TABLE `ali_grade_new`
  ADD PRIMARY KEY (`no`),
  ADD KEY `ali_grade_new_ibfk_1` (`class_no`),
  ADD KEY `ali_grade_new_ibfk_2` (`assign_no`),
  ADD KEY `ali_grade_new_ibfk_3` (`assigncat_no`);

--
-- Indexes for table `ali_gradingperiod`
--
ALTER TABLE `ali_gradingperiod`
  ADD PRIMARY KEY (`schoolyear`,`gradingperiod`),
  ADD KEY `no_index` (`no`);

--
-- Indexes for table `ali_level`
--
ALTER TABLE `ali_level`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `ali_makeup`
--
ALTER TABLE `ali_makeup`
  ADD PRIMARY KEY (`makeup_no`);

--
-- Indexes for table `ali_recoverpwd`
--
ALTER TABLE `ali_recoverpwd`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ali_remedialattendance`
--
ALTER TABLE `ali_remedialattendance`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `ATTENDANCE_INDEX` (`student_no`,`attendance_day`,`class_no`),
  ADD KEY `fk_class_no` (`class_no`);

--
-- Indexes for table `ali_remedialclass`
--
ALTER TABLE `ali_remedialclass`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `ali_remedialclassteachers`
--
ALTER TABLE `ali_remedialclassteachers`
  ADD PRIMARY KEY (`no`),
  ADD KEY `class_no` (`class_no`),
  ADD KEY `teacher_no` (`teacher_no`);

--
-- Indexes for table `ali_remedialroster`
--
ALTER TABLE `ali_remedialroster`
  ADD PRIMARY KEY (`no`),
  ADD KEY `ali_remedialroster_ibfk_2` (`class_no`);

--
-- Indexes for table `ali_rooms`
--
ALTER TABLE `ali_rooms`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `ali_roster`
--
ALTER TABLE `ali_roster`
  ADD PRIMARY KEY (`no`),
  ADD KEY `ali_roster_ibfk_2` (`class_no`);

--
-- Indexes for table `ali_specialday`
--
ALTER TABLE `ali_specialday`
  ADD PRIMARY KEY (`specialday_no`);

--
-- Indexes for table `ali_students`
--
ALTER TABLE `ali_students`
  ADD PRIMARY KEY (`students_no`);

--
-- Indexes for table `ali_student_consult`
--
ALTER TABLE `ali_student_consult`
  ADD PRIMARY KEY (`consult_no`);

--
-- Indexes for table `ali_student_family`
--
ALTER TABLE `ali_student_family`
  ADD PRIMARY KEY (`family_no`);

--
-- Indexes for table `ali_user`
--
ALTER TABLE `ali_user`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `unique_user_ID` (`user_ID`);

--
-- Indexes for table `ali_user_login_attempt`
--
ALTER TABLE `ali_user_login_attempt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attempt_time` (`attempt_time`);

--
-- Indexes for table `ali_warning_letter`
--
ALTER TABLE `ali_warning_letter`
  ADD PRIMARY KEY (`letter_no`);

--
-- Indexes for table `eg_attendance`
--
ALTER TABLE `eg_attendance`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `eg_grades`
--
ALTER TABLE `eg_grades`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `eg_importfiles`
--
ALTER TABLE `eg_importfiles`
  ADD PRIMARY KEY (`file_no`);

--
-- Indexes for table `eg_importfiles_g`
--
ALTER TABLE `eg_importfiles_g`
  ADD PRIMARY KEY (`file_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ali_academicrecords`
--
ALTER TABLE `ali_academicrecords`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_acl`
--
ALTER TABLE `ali_acl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_aclresources`
--
ALTER TABLE `ali_aclresources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_aclroles`
--
ALTER TABLE `ali_aclroles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_assignments`
--
ALTER TABLE `ali_assignments`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_assign_cate`
--
ALTER TABLE `ali_assign_cate`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_assign_cate_basic`
--
ALTER TABLE `ali_assign_cate_basic`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_attendance`
--
ALTER TABLE `ali_attendance`
  MODIFY `attendance_no` mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_attendance_new`
--
ALTER TABLE `ali_attendance_new`
  MODIFY `no` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_calboard`
--
ALTER TABLE `ali_calboard`
  MODIFY `calboard_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_calboard_reply`
--
ALTER TABLE `ali_calboard_reply`
  MODIFY `reply_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_class`
--
ALTER TABLE `ali_class`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_classes`
--
ALTER TABLE `ali_classes`
  MODIFY `classes_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_classteachers`
--
ALTER TABLE `ali_classteachers`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_class_group`
--
ALTER TABLE `ali_class_group`
  MODIFY `class_group_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_enroll_class`
--
ALTER TABLE `ali_enroll_class`
  MODIFY `enroll_class_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_files`
--
ALTER TABLE `ali_files`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_finance`
--
ALTER TABLE `ali_finance`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_grade`
--
ALTER TABLE `ali_grade`
  MODIFY `grade_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_grade_new`
--
ALTER TABLE `ali_grade_new`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_gradingperiod`
--
ALTER TABLE `ali_gradingperiod`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_level`
--
ALTER TABLE `ali_level`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_makeup`
--
ALTER TABLE `ali_makeup`
  MODIFY `makeup_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_recoverpwd`
--
ALTER TABLE `ali_recoverpwd`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_remedialattendance`
--
ALTER TABLE `ali_remedialattendance`
  MODIFY `no` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_remedialclass`
--
ALTER TABLE `ali_remedialclass`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_remedialclassteachers`
--
ALTER TABLE `ali_remedialclassteachers`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_remedialroster`
--
ALTER TABLE `ali_remedialroster`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_rooms`
--
ALTER TABLE `ali_rooms`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_roster`
--
ALTER TABLE `ali_roster`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_specialday`
--
ALTER TABLE `ali_specialday`
  MODIFY `specialday_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_students`
--
ALTER TABLE `ali_students`
  MODIFY `students_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_student_consult`
--
ALTER TABLE `ali_student_consult`
  MODIFY `consult_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_student_family`
--
ALTER TABLE `ali_student_family`
  MODIFY `family_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_user`
--
ALTER TABLE `ali_user`
  MODIFY `no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_user_login_attempt`
--
ALTER TABLE `ali_user_login_attempt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ali_warning_letter`
--
ALTER TABLE `ali_warning_letter`
  MODIFY `letter_no` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eg_attendance`
--
ALTER TABLE `eg_attendance`
  MODIFY `no` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eg_grades`
--
ALTER TABLE `eg_grades`
  MODIFY `no` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eg_importfiles`
--
ALTER TABLE `eg_importfiles`
  MODIFY `file_no` mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eg_importfiles_g`
--
ALTER TABLE `eg_importfiles_g`
  MODIFY `file_no` mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ali_assignments`
--
ALTER TABLE `ali_assignments`
  ADD CONSTRAINT `ali_assignments_ibfk_1` FOREIGN KEY (`assigncat_no`) REFERENCES `ali_assign_cate` (`no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ali_assignments_ibfk_2` FOREIGN KEY (`class_no`) REFERENCES `ali_class` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ali_assign_cate`
--
ALTER TABLE `ali_assign_cate`
  ADD CONSTRAINT `ali_assign_cate_ibfk_1` FOREIGN KEY (`class_no`) REFERENCES `ali_class` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ali_attendance_new`
--
ALTER TABLE `ali_attendance_new`
  ADD CONSTRAINT `fk_class_no` FOREIGN KEY (`class_no`) REFERENCES `ali_class` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ali_grade_new`
--
ALTER TABLE `ali_grade_new`
  ADD CONSTRAINT `ali_grade_new_ibfk_1` FOREIGN KEY (`class_no`) REFERENCES `ali_class` (`no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ali_grade_new_ibfk_2` FOREIGN KEY (`assign_no`) REFERENCES `ali_assignments` (`no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ali_grade_new_ibfk_3` FOREIGN KEY (`assigncat_no`) REFERENCES `ali_assign_cate` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ali_remedialattendance`
--
ALTER TABLE `ali_remedialattendance`
  ADD CONSTRAINT `ali_remedialattendance_ibfk_1` FOREIGN KEY (`class_no`) REFERENCES `ali_remedialclass` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
