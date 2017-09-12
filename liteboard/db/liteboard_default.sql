-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 12, 2017 at 11:52 AM
-- Server version: 5.6.36-82.1-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `liteboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_admin` int(1) NOT NULL,
  `login_count` int(10) NOT NULL,
  `count_reset_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `name`, `password`, `is_admin`, `login_count`, `count_reset_date`) VALUES
(1, 'Student', '$2y$10$JYXiJpmQn/tEzmeCKfFwX.ON8gli5hNVyHX3nchqci8XAjN71J7fW', 0, 1, '2017-09-08 08:49:42'),
(2, 'Teacher', '$2y$10$JYXiJpmQn/tEzmeCKfFwX.ON8gli5hNVyHX3nchqci8XAjN71J7fW', 1, 1, '2017-09-08 08:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `alert`
--

CREATE TABLE `alert` (
  `alert_id` int(10) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(10) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `alert`
--

INSERT INTO `alert` (`alert_id`, `description`, `category_id`, `date_created`) VALUES
(1, 'Cras justo odio.', 1, '2017-08-22 00:00:00'),
(2, 'Dapibus ac facilisis in.', 2, '2017-08-20 00:00:00'),
(4, 'Porta ac consectetur ac.', 1, '2017-08-11 00:00:00'),
(8, 'Aliquam sagittis arcu a nulla', 10, '2017-08-25 05:12:40');

-- --------------------------------------------------------

--
-- Table structure for table `alert_category`
--

CREATE TABLE `alert_category` (
  `category_id` int(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `colour` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `alert_category`
--

INSERT INTO `alert_category` (`category_id`, `name`, `colour`) VALUES
(1, 'Reminder', '#337ab7'),
(2, 'Homework', '#5cb85c'),
(10, 'Open Days', '#d92121');

-- --------------------------------------------------------

--
-- Stand-in structure for view `alert_category_join`
-- (See below for the actual view)
--
CREATE TABLE `alert_category_join` (
`alert_id` int(10)
,`description` text
,`date_created` datetime
,`name` varchar(20)
,`colour` varchar(10)
,`category_id` int(10)
);

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `content_id` int(10) NOT NULL,
  `parent_folder_id` int(10) DEFAULT NULL,
  `arrangement` int(10) NOT NULL,
  `target_type` int(1) NOT NULL,
  `target_id` int(10) NOT NULL,
  `is_divider` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`content_id`, `parent_folder_id`, `arrangement`, `target_type`, `target_id`, `is_divider`) VALUES
(1, NULL, 1, 1, 1, 0),
(2, NULL, 2, 1, 2, 1),
(3, NULL, 3, 1, 3, 0),
(4, NULL, 4, 1, 4, 1),
(5, NULL, 5, 1, 5, 0),
(6, NULL, 6, 1, 6, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `content_editor_join`
-- (See below for the actual view)
--
CREATE TABLE `content_editor_join` (
`content_id` int(10)
,`parent_folder_id` int(10)
,`arrangement` int(10)
,`target_type` int(1)
,`target_id` int(10)
,`is_divider` int(1)
,`editor_id` int(10)
,`text` text
,`date_created` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `content_file_join`
-- (See below for the actual view)
--
CREATE TABLE `content_file_join` (
`content_id` int(10)
,`parent_folder_id` int(10)
,`arrangement` int(10)
,`target_type` int(1)
,`target_id` int(10)
,`is_divider` int(1)
,`file_id` int(10)
,`name` varchar(255)
,`stored_name` varchar(255)
,`description` varchar(255)
,`date_created` datetime
,`download_count` int(10)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `content_folder_join`
-- (See below for the actual view)
--
CREATE TABLE `content_folder_join` (
`content_id` int(10)
,`parent_folder_id` int(10)
,`arrangement` int(10)
,`target_type` int(1)
,`target_id` int(10)
,`is_divider` int(1)
,`folder_id` int(10)
,`name` varchar(255)
,`description` varchar(255)
,`icon` varchar(30)
,`date_created` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `editor`
--

CREATE TABLE `editor` (
  `editor_id` int(10) NOT NULL,
  `text` text NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date_start` varchar(20) NOT NULL,
  `date_end` varchar(20) NOT NULL,
  `colour` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `title`, `date_start`, `date_end`, `colour`) VALUES
(1, 'Example Event', '2017-09-22T12:00', '2017-09-22T13:00', '#3a87ad'),
(2, 'Revision Session', '2017-09-23T15:00', '2017-09-23T16:00', '#ff0000'),
(6, 'Leyla\'s Bday', '2017-10-09', '2017-10-09', '#69d2ff');

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `file_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `download_count` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `folder`
--

CREATE TABLE `folder` (
  `folder_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `folder`
--

INSERT INTO `folder` (`folder_id`, `name`, `description`, `icon`, `date_created`) VALUES
(1, 'Year 12', '', 'menu-right', '0000-00-00 00:00:00'),
(2, 'Year 13', '', 'menu-right', '0000-00-00 00:00:00'),
(3, 'Exam Structure', '', 'pencil', '0000-00-00 00:00:00'),
(4, 'Exam Survival', '', 'pencil', '0000-00-00 00:00:00'),
(5, 'Careers', '', 'education', '0000-00-00 00:00:00'),
(6, 'Contact', '', 'envelope', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `news_post`
--

CREATE TABLE `news_post` (
  `news_id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `URL` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news_post`
--

INSERT INTO `news_post` (`news_id`, `title`, `description`, `URL`, `date_created`) VALUES
(1, 'Nam Porta Tempus Ornare Maecenas Sed Rutrum Nibh', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vitae eleifend libero. Vestibulum vel scelerisque velit, ut viverra nibh. Quisque tortor erat, viverra ac velit sed, auctor fermentum augue.', 'https://scholar.google.co.uk/', '2017-08-20 00:00:00'),
(2, 'Donec Commodo Tortor Sapien, Non Feugiat Risus Aliquet Id', 'Proin posuere feugiat bibendum. Vestibulum sagittis hendrerit lectus, sit amet scelerisque enim. Sed vel convallis quam, eu posuere nisl. Aliquam sagittis arcu a nulla porttitor lobortis. Quisque blandit, orci ut cursus eleifend, tortor ipsum gravida odio, laoreet tempor sapien justo ut lacus.', 'https://scholar.google.co.uk/', '2017-08-21 02:00:00'),
(3, 'Cras Justo Risus Egestas Quis Rutrum A Lacinia Ac Arcu', 'Phasellus semper pellentesque gravida. Nam quis aliquam purus. Aliquam at mattis sem. Nulla sit amet suscipit nisi, tincidunt pellentesque est. In varius, sapien at dictum accumsan, arcu tortor mollis lectus, sed pellentesque quam massa hendrerit turpis.', 'https://scholar.google.co.uk/', '2017-08-22 04:00:00');

-- --------------------------------------------------------

--
-- Structure for view `alert_category_join`
--
DROP TABLE IF EXISTS `alert_category_join`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `alert_category_join`  AS  select `alert`.`alert_id` AS `alert_id`,`alert`.`description` AS `description`,`alert`.`date_created` AS `date_created`,`alert_category`.`name` AS `name`,`alert_category`.`colour` AS `colour`,`alert`.`category_id` AS `category_id` from (`alert` join `alert_category` on((`alert`.`category_id` = `alert_category`.`category_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `content_editor_join`
--
DROP TABLE IF EXISTS `content_editor_join`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `content_editor_join`  AS  select `content`.`content_id` AS `content_id`,`content`.`parent_folder_id` AS `parent_folder_id`,`content`.`arrangement` AS `arrangement`,`content`.`target_type` AS `target_type`,`content`.`target_id` AS `target_id`,`content`.`is_divider` AS `is_divider`,`editor`.`editor_id` AS `editor_id`,`editor`.`text` AS `text`,`editor`.`date_created` AS `date_created` from (`content` join `editor` on((`content`.`target_id` = `editor`.`editor_id`))) where (`content`.`target_type` = 2) ;

-- --------------------------------------------------------

--
-- Structure for view `content_file_join`
--
DROP TABLE IF EXISTS `content_file_join`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `content_file_join`  AS  select `content`.`content_id` AS `content_id`,`content`.`parent_folder_id` AS `parent_folder_id`,`content`.`arrangement` AS `arrangement`,`content`.`target_type` AS `target_type`,`content`.`target_id` AS `target_id`,`content`.`is_divider` AS `is_divider`,`file`.`file_id` AS `file_id`,`file`.`name` AS `name`,`file`.`stored_name` AS `stored_name`,`file`.`description` AS `description`,`file`.`date_created` AS `date_created`,`file`.`download_count` AS `download_count` from (`content` join `file` on((`content`.`target_id` = `file`.`file_id`))) where (`content`.`target_type` = 3) ;

-- --------------------------------------------------------

--
-- Structure for view `content_folder_join`
--
DROP TABLE IF EXISTS `content_folder_join`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `content_folder_join`  AS  select `content`.`content_id` AS `content_id`,`content`.`parent_folder_id` AS `parent_folder_id`,`content`.`arrangement` AS `arrangement`,`content`.`target_type` AS `target_type`,`content`.`target_id` AS `target_id`,`content`.`is_divider` AS `is_divider`,`folder`.`folder_id` AS `folder_id`,`folder`.`name` AS `name`,`folder`.`description` AS `description`,`folder`.`icon` AS `icon`,`folder`.`date_created` AS `date_created` from (`content` join `folder` on((`content`.`target_id` = `folder`.`folder_id`))) where (`content`.`target_type` = 1) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `alert`
--
ALTER TABLE `alert`
  ADD PRIMARY KEY (`alert_id`);

--
-- Indexes for table `alert_category`
--
ALTER TABLE `alert_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `editor`
--
ALTER TABLE `editor`
  ADD PRIMARY KEY (`editor_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `folder`
--
ALTER TABLE `folder`
  ADD PRIMARY KEY (`folder_id`);

--
-- Indexes for table `news_post`
--
ALTER TABLE `news_post`
  ADD PRIMARY KEY (`news_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `alert`
--
ALTER TABLE `alert`
  MODIFY `alert_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `alert_category`
--
ALTER TABLE `alert_category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `content_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `editor`
--
ALTER TABLE `editor`
  MODIFY `editor_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `file_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `folder`
--
ALTER TABLE `folder`
  MODIFY `folder_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `news_post`
--
ALTER TABLE `news_post`
  MODIFY `news_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
