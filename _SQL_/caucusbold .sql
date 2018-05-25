-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2018 at 09:48 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `caucusbold`
--

-- --------------------------------------------------------

--
-- Table structure for table `allbugs`
--

CREATE TABLE `allbugs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `bug` text NOT NULL,
  `fixed` int(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `allbugs`
--

INSERT INTO `allbugs` (`id`, `user_id`, `subject`, `bug`, `fixed`, `date`) VALUES
(1, 1, 'ssda', 'asdad', 0, '2017-09-11 05:51:42');

-- --------------------------------------------------------

--
-- Table structure for table `caucus_class`
--

CREATE TABLE `caucus_class` (
  `cate_id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `color` varchar(10) NOT NULL,
  `best_ans` varchar(7) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `caucus_class`
--

INSERT INTO `caucus_class` (`cate_id`, `sub_id`, `title`, `color`, `best_ans`, `date`) VALUES
(1, 1, 'biology', '#FF0000', '#FFEEEE', '2017-06-27 21:07:27'),
(2, 1, 'physics', '#56f643', '#DBFFD7', '2017-06-27 21:08:59'),
(3, 1, 'chemistry', '#FF0000', '#FFEEEE', '2017-06-27 21:09:21'),
(4, 1, 'astronomy', '#6C2200', '#FFE0D2', '2017-06-27 21:10:15'),
(5, 2, 'english I', '#159069', '#D2FFF1', '2017-06-27 21:11:16'),
(6, 2, 'english II', '#503b4c', '#FFDCF8', '2017-06-27 21:11:59'),
(7, 2, 'e. III & Lang', '#3e1361', '#ECD4FF', '2017-06-27 21:12:47'),
(8, 2, 'e. IV & Lit', '#af5b16', '#FFE5CF', '2017-06-27 21:13:17'),
(9, 3, 'algebra', '#e8a057', '#FFE9D2', '2017-06-27 21:13:48'),
(10, 3, 'geometry', '#677729', '#F5FFCE', '2017-06-27 21:14:30'),
(11, 3, 'calculus', '#a932fa', '#F2DEFF', '2017-06-27 21:15:23'),
(12, 3, 'adv. Maths', '#a7dbf9', '#E7F6FF', '2017-06-27 21:16:00'),
(13, 4, 'spanish', '#360c8b', '#EDE4FF', '2017-06-27 21:16:55'),
(14, 4, 'french', '#c0988a', '#FFE9E1', '2017-06-27 21:17:21'),
(15, 4, 'latin', '#bec6e4', '#E7ECFF', '2017-06-27 21:18:06'),
(16, 5, 'world History', '#666a4b', '#F9FFD4', '2017-06-27 21:18:33'),
(17, 5, 'uS History', '#3f3ac2', '#E2E1FF', '2017-06-27 21:19:03'),
(18, 5, 'economics', '#5e0b4e', '#FFDBF8', '2017-06-27 21:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `cau_comments`
--

CREATE TABLE `cau_comments` (
  `comm_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_uid` int(11) NOT NULL,
  `comment` text NOT NULL,
  `uploadimg_comm` text NOT NULL,
  `likes` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cau_leaderboard`
--

CREATE TABLE `cau_leaderboard` (
  `id` int(11) NOT NULL,
  `leader_id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cau_questions`
--

CREATE TABLE `cau_questions` (
  `id` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_uid` int(11) NOT NULL,
  `question` text NOT NULL,
  `uploadimg` text NOT NULL,
  `type` int(11) NOT NULL,
  `resolve` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comm_userlikes`
--

CREATE TABLE `comm_userlikes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_comm_id` int(11) NOT NULL,
  `liked_commentid` int(11) NOT NULL,
  `like_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `likecomm_notification`
--

CREATE TABLE `likecomm_notification` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `respond_uid` int(11) NOT NULL,
  `status` varchar(6) NOT NULL,
  `type` varchar(8) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subjects` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subjects`) VALUES
(1, 'Science'),
(2, 'English'),
(3, 'Mathematics'),
(4, 'Foreign language'),
(5, 'Social Studies');

-- --------------------------------------------------------

--
-- Table structure for table `subject_users`
--

CREATE TABLE `subject_users` (
  `id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `color` varchar(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `recoveryemail_enc` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `profile_pic` text NOT NULL,
  `color` varchar(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allbugs`
--
ALTER TABLE `allbugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `caucus_class`
--
ALTER TABLE `caucus_class`
  ADD PRIMARY KEY (`cate_id`);

--
-- Indexes for table `cau_comments`
--
ALTER TABLE `cau_comments`
  ADD PRIMARY KEY (`comm_id`);

--
-- Indexes for table `cau_leaderboard`
--
ALTER TABLE `cau_leaderboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cau_questions`
--
ALTER TABLE `cau_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comm_userlikes`
--
ALTER TABLE `comm_userlikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likecomm_notification`
--
ALTER TABLE `likecomm_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_users`
--
ALTER TABLE `subject_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allbugs`
--
ALTER TABLE `allbugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `caucus_class`
--
ALTER TABLE `caucus_class`
  MODIFY `cate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `cau_comments`
--
ALTER TABLE `cau_comments`
  MODIFY `comm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cau_leaderboard`
--
ALTER TABLE `cau_leaderboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cau_questions`
--
ALTER TABLE `cau_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comm_userlikes`
--
ALTER TABLE `comm_userlikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likecomm_notification`
--
ALTER TABLE `likecomm_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subject_users`
--
ALTER TABLE `subject_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
