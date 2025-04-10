-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 10, 2025 at 07:11 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mehar`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `BookingID` int(11) NOT NULL,
  `BookingDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `UserID` int(11) DEFAULT NULL,
  `WorkshopID` int(11) DEFAULT NULL,
  `ScheduleID` int(11) NOT NULL,
  `BID` bigint(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`BookingID`, `BookingDate`, `UserID`, `WorkshopID`, `ScheduleID`, `BID`) VALUES
(52, '2025-04-04 14:00:42', 8, 1, 3, 7799695467),
(53, '2025-04-04 14:00:57', 8, 8, 22, 5251832354),
(54, '2025-04-04 14:01:07', 8, 11, 31, 5762778174),
(55, '2025-04-04 14:01:20', 8, 13, 62, 2856941558),
(56, '2025-04-04 14:01:31', 8, 16, 70, 5599378434),
(57, '2025-04-04 14:01:42', 8, 19, 76, 9273245975),
(58, '2025-04-04 14:01:52', 8, 21, 83, 1955478866),
(59, '2025-04-04 14:02:01', 8, 24, 92, 7190717773),
(60, '2025-04-04 14:02:11', 8, 27, 101, 5956837909),
(61, '2025-04-04 14:02:26', 8, 26, 98, 1860815991),
(62, '2025-04-09 09:24:00', 6, 15, 67, 8306929220),
(63, '2025-04-09 11:04:03', 6, 19, 77, 7773607276),
(64, '2025-04-10 12:00:00', 6, 24, 92, 9490575194),
(65, '2025-04-09 11:05:08', 6, 9, 25, 3894731656),
(66, '2025-04-09 11:05:26', 6, 17, 72, 9724170889),
(67, '2025-04-09 11:06:01', 6, 13, 62, 8504490691),
(68, '2025-04-09 11:06:22', 6, 26, 99, 4809025418),
(71, '2025-04-10 07:40:16', 10, 8, 22, 9707013368),
(72, '2025-04-10 07:40:28', 10, 1, 1, 8261500951),
(74, '2025-04-10 07:46:36', 10, 3, 11, 8812204818),
(75, '2025-04-10 07:47:09', 10, 10, 28, 1719037362),
(76, '2025-04-10 07:47:48', 10, 14, 63, 4915324870);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `WorkshopID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `UserID`, `WorkshopID`) VALUES
(35, 6, 9),
(37, 6, 13),
(40, 6, 15),
(36, 6, 17),
(39, 6, 19),
(38, 6, 26),
(33, 7, 1),
(3, 7, 2),
(27, 7, 3),
(19, 7, 8),
(6, 7, 9),
(5, 7, 10),
(4, 7, 11),
(34, 8, 13),
(41, 10, 15),
(44, 10, 19),
(42, 10, 24),
(43, 10, 27),
(1, 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `likeID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `postID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userIP` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`likeID`, `userID`, `postID`, `created_at`, `userIP`) VALUES
(1, NULL, 48, '2025-04-09 23:06:48', '::1'),
(3, NULL, 47, '2025-04-10 04:39:58', '::1'),
(4, NULL, 46, '2025-04-10 04:40:25', '::1'),
(5, NULL, 49, '2025-04-10 04:40:49', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `PostID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `images` text,
  `comment` text NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`PostID`, `UserID`, `images`, `comment`, `post_date`) VALUES
(46, 6, '[\"uploads\\/post_67f6c67a6d92e4.35894006.jpg\",\"uploads\\/post_67f6c67a6e14a5.78709464.jpg\"]', 'Had so much fun making these cookies never knew it would be so relaxing. I highly recommended it ', '2025-04-09 19:11:54'),
(47, 6, '[\"uploads\\/post_67f6cc3f16bbf2.93262673.jpeg\",\"uploads\\/post_67f6cc3f172d17.93964462.jpeg\"]', 'Paddle nights are the best ', '2025-04-09 19:36:31'),
(48, 6, '[\"uploads\\/post_67f6cd8cb507d3.62184046.jpeg\",\"uploads\\/post_67f6cd8cb675f7.49715138.jpeg\"]', 'The perfect escape with the night breeze under the stars ✨ ', '2025-04-09 19:42:04'),
(49, 6, '[\"uploads\\/post_67f6d0b296c550.58780752.jpeg\",\"uploads\\/post_67f6d0b2970b40.27698349.jpeg\"]', 'Made my first jump today!  After failing for a month , Feeling accomplished', '2025-04-09 19:55:30'),
(60, 10, '[\"uploads\\/post_67f773eb32b917.77528006.jpeg\",\"uploads\\/post_67f773eb330354.92729336.jpeg\"]', 'A completely new and exciting challenge. Everything was well organized!', '2025-04-10 07:31:55'),
(61, 10, '[\"uploads\\/post_67f77441e921d0.06111426.jpeg\",\"uploads\\/post_67f77441e9f193.85140327.jpeg\"]', 'It was a calming and creative experience. I learned how to mix scents and pour wax into molds', '2025-04-10 07:33:21'),
(62, 10, '[\"uploads\\/post_67f774736f98f0.65830380.jpeg\",\"uploads\\/post_67f774736fcad3.64794361.jpeg\"]', 'A light and useful experience.The final result was delicious!', '2025-04-10 07:34:11'),
(63, 10, '[\"uploads\\/post_67f7749c7bd245.76364090.jpeg\",\"uploads\\/post_67f7749c7c3aa1.07258337.jpeg\"]', 'The workshop had a calm and inspiring atmosphere. I enjoyed expressing my thoughts through art', '2025-04-10 07:34:52');

-- --------------------------------------------------------

--
-- Table structure for table `previous_works`
--

CREATE TABLE `previous_works` (
  `WorkID` int(11) NOT NULL,
  `WorkshopID` int(11) NOT NULL,
  `ImageURL` varchar(255) NOT NULL,
  `ClientName` varchar(100) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `previous_works`
--

INSERT INTO `previous_works` (`WorkID`, `WorkshopID`, `ImageURL`, `ClientName`, `CreatedAt`) VALUES
(1, 2, 'workshops/embroidery4.jpg', 'Sara Ali', '2025-03-23 14:00:00'),
(2, 2, 'workshops/embroidery6.jpg', 'Nora Thamer', '2025-03-10 14:00:00'),
(3, 2, 'workshops/embroidery3.jpg', 'Deem Mohammed', '2025-03-09 14:00:00'),
(4, 2, 'workshops/embroidery5.jpg', 'Nouf Khalid', '2025-03-05 14:00:00'),
(5, 2, 'workshops/embroidery1.jpg', 'Jana Nawaf', '2025-02-01 14:00:00'),
(6, 1, 'workshops/candle3.jpg', 'Alanoud Ali', '2025-04-10 14:00:00'),
(7, 1, 'workshops/candle4.jpg', 'Maha Omer', '2025-04-10 14:00:00'),
(8, 1, 'workshops/candle5.jpg', 'Lena Saleh', '2025-04-09 14:00:00'),
(9, 1, 'workshops/candle6.jpg', 'Tarf Ahmed', '2025-04-05 14:00:00'),
(10, 1, 'workshops/candle8.jpg', 'Bushra Ibrahim', '2025-04-01 14:00:00'),
(11, 1, 'workshops/candle7.jpg', 'Nora Anas', '2025-04-01 14:00:00'),
(12, 3, 'workshops/soap1.jpg', 'Omar Khaled', '2025-03-05 14:00:00'),
(13, 3, 'workshops/soap2.jpg', 'Rania Ahmed', '2025-03-15 14:00:00'),
(14, 3, 'workshops/soap3.jpg', 'Fahad Nasser', '2025-04-01 14:00:00'),
(15, 3, 'workshops/soap4.jpg', 'Alya Salman', '2025-04-03 14:00:00'),
(16, 3, 'workshops/soap5.jpg', 'Hassan Talal', '2025-04-05 14:00:00'),
(17, 9, 'workshops/cover1.jpg', 'Maya Faisal', '2025-03-08 14:00:00'),
(18, 9, 'workshops/cover2.jpg', 'Khalid Badr', '2025-03-20 14:00:00'),
(19, 9, 'workshops/cover3.jpg', 'Leen Sami', '2025-04-02 14:00:00'),
(20, 9, 'workshops/cover4.jpg', 'Salem Adel', '2025-04-04 14:00:00'),
(21, 9, 'workshops/cover5.jpg', 'Lama Ziyad', '2025-04-06 14:00:00'),
(22, 10, 'workshops/Crochet1.jpg', 'Noura Ayman', '2025-03-07 14:00:00'),
(23, 10, 'workshops/Crochet2.jpg', 'Tariq Hussain', '2025-03-18 14:00:00'),
(24, 10, 'workshops/Crochet3.jpg', 'Dina Maged', '2025-03-30 14:00:00'),
(25, 10, 'workshops/Crochet4.jpg', 'Saif Jamal', '2025-04-01 14:00:00'),
(26, 10, 'workshops/Crochet5.jpg', 'Yara Faleh', '2025-04-05 14:00:00'),
(27, 11, 'workshops/Clothes1.jpg', 'Lina Faris', '2025-03-09 14:00:00'),
(28, 11, 'workshops/Clothes2.jpg', 'Badr Salim', '2025-03-19 14:00:00'),
(29, 11, 'workshops/Clothes3.jpg', 'Nawaf Khalil', '2025-03-28 14:00:00'),
(30, 11, 'workshops/Clothes4.jpg', 'Huda Riyadh', '2025-04-02 14:00:00'),
(31, 11, 'workshops/Clothes5.jpg', 'Waleed Mazin', '2025-04-05 14:00:00'),
(32, 12, 'workshops/Pottery1.jpg', 'Nada Jalal', '2025-03-04 14:00:00'),
(33, 12, 'workshops/Pottery2.jpg', 'Rashed Tariq', '2025-03-14 14:00:00'),
(34, 12, 'workshops/Pottery3.jpg', 'Yasmine Osama', '2025-03-27 14:00:00'),
(35, 12, 'workshops/Pottery4.jpg', 'Mohannad Sami', '2025-04-01 14:00:00'),
(36, 12, 'workshops/Pottery5.jpg', 'Ahlam Saeed', '2025-04-04 14:00:00'),
(37, 13, 'workshops/Stargazing1.jpg', 'Layla Ibrahim', '2025-03-10 14:00:00'),
(38, 13, 'workshops/Stargazing2.jpg', 'Yousef Anas', '2025-03-16 14:00:00'),
(39, 13, 'workshops/Stargazing3.jpg', 'Haya Nawaf', '2025-03-29 14:00:00'),
(40, 13, 'workshops/Stargazing4.jpg', 'Majed Rami', '2025-04-03 14:00:00'),
(41, 13, 'workshops/Stargazing5.jpg', 'Sara Hussam', '2025-04-06 14:00:00'),
(42, 15, 'workshops/Kashta1.jpg', 'Rami Jaber', '2025-03-11 14:00:00'),
(43, 15, 'workshops/Kashta2.jpg', 'Reem Tamer', '2025-03-17 14:00:00'),
(44, 15, 'workshops/Kashta3.jpg', 'Abdullah Aref', '2025-03-26 14:00:00'),
(45, 15, 'workshops/Kashta4.jpg', 'Lujain Sameer', '2025-04-02 14:00:00'),
(46, 15, 'workshops/Kashta5.jpg', 'Sultan Omar', '2025-04-06 14:00:00'),
(47, 16, 'workshops/mountaineering1.jpg', 'Sami Awad', '2025-03-06 14:00:00'),
(48, 16, 'workshops/mountaineering2.jpg', 'Hadeel Ziad', '2025-03-14 14:00:00'),
(49, 16, 'workshops/mountaineering3.jpg', 'Tamer Adel', '2025-03-27 14:00:00'),
(50, 16, 'workshops/mountaineering4.jpg', 'Noura Saad', '2025-04-02 14:00:00'),
(51, 16, 'workshops/mountaineering5.jpg', 'Mohannad Waleed', '2025-04-05 14:00:00'),
(52, 17, 'workshops/Paragliding1.jpg', 'Alaa Sulaiman', '2025-03-08 14:00:00'),
(53, 17, 'workshops/Paragliding2.jpg', 'Majd Farid', '2025-03-17 14:00:00'),
(54, 17, 'workshops/Paragliding3.jpg', 'Lina Abdulaziz', '2025-03-28 14:00:00'),
(55, 17, 'workshops/Paragliding4.jpg', 'Hazem Khaled', '2025-04-03 14:00:00'),
(57, 18, 'workshops/horse1.jpg', 'Reem Qasem', '2025-03-04 14:00:00'),
(58, 18, 'workshops/Horse2.jpg', 'Omar Mazen', '2025-03-15 14:00:00'),
(59, 18, 'workshops/Horse3.jpg', 'Sara Hamad', '2025-03-25 14:00:00'),
(60, 18, 'workshops/Horse4.jpg', 'Fares Latif', '2025-04-01 14:00:00'),
(61, 18, 'workshops/Horse5.jpg', 'Layan Wael', '2025-04-05 14:00:00'),
(62, 19, 'workshops/Paddle1.jpg', 'Rawan Basim', '2025-03-05 14:00:00'),
(63, 19, 'workshops/Paddle2.jpg', 'Tariq Rami', '2025-03-19 14:00:00'),
(64, 19, 'workshops/Paddle3.jpg', 'Omer Abdullah', '2025-03-30 14:00:00'),
(65, 19, 'workshops/Paddle4.jpg', 'Nader Saleh', '2025-04-02 14:00:00'),
(66, 19, 'workshops/Paddle5.jpg', 'Ruba Anwar', '2025-04-06 14:00:00'),
(68, 20, 'workshops/cycling2.jpg', 'Huda Sami', '2025-03-11 14:00:00'),
(69, 20, 'workshops/cycling3.jpg', 'Nora Khaled ', '2025-03-23 14:00:00'),
(70, 20, 'workshops/cycling4.jpg', 'Abeer Tarek', '2025-04-03 14:00:00'),
(71, 20, 'workshops/cycling.jpg', ' Mona Salem', '2025-04-06 14:00:00'),
(72, 21, 'workshops/cake1.jpg', 'Mohamed Nabil', '2025-03-07 14:00:00'),
(73, 21, 'workshops/cake2.jpg', 'Lama Badr', '2025-03-14 14:00:00'),
(74, 21, 'workshops/cake3.jpg', 'Sawsan Talal', '2025-03-31 14:00:00'),
(75, 21, 'workshops/cake4.jpg', 'Rami Joud', '2025-04-01 14:00:00'),
(76, 21, 'workshops/cake5.jpg', 'Hiba Faisal', '2025-04-05 14:00:00'),
(77, 22, 'workshops/pizza1.jpg', 'Nasser Ziad', '2025-03-09 14:00:00'),
(78, 22, 'workshops/pizza2.jpg', 'Rania Maher', '2025-03-18 14:00:00'),
(79, 22, 'workshops/pizza3.jpg', 'Yousef Ghanem', '2025-03-29 14:00:00'),
(80, 22, 'workshops/pizza4.jpg', 'Marwa Adel', '2025-04-03 14:00:00'),
(81, 22, 'workshops/pizza5.jpg', 'Samir Anas', '2025-04-06 14:00:00'),
(82, 23, 'workshops/coffee1.jpg', 'Layla Ibrahim', '2025-03-12 14:00:00'),
(83, 23, 'workshops/coffee2.jpg', 'Ibrahim Sami', '2025-03-20 14:00:00'),
(84, 23, 'workshops/coffee3.jpg', 'Haneen Wael', '2025-03-30 14:00:00'),
(85, 23, 'workshops/coffee4.jpg', 'Ziad Firas', '2025-04-01 14:00:00'),
(86, 23, 'workshops/coffee5.jpg', 'Alaa Majed', '2025-04-06 14:00:00'),
(87, 24, 'workshops/Cookies1.jpg', 'Salma Jamal', '2025-03-08 14:00:00'),
(88, 24, 'workshops/Cookies2.jpg', 'Rashed Ayman', '2025-03-15 14:00:00'),
(89, 24, 'workshops/Cookies3.jpg', 'Nada Sami', '2025-03-28 14:00:00'),
(90, 24, 'workshops/Cookies4.jpg', 'Omar Salem', '2025-04-03 14:00:00'),
(91, 24, 'workshops/Cookies5.jpg', 'Nora Adnan', '2025-04-05 14:00:00'),
(92, 25, 'workshops/Sushi1.jpg', 'Ayman Faris', '2025-03-06 14:00:00'),
(93, 25, 'workshops/Sushi2.jpg', 'Rana Fathi', '2025-03-21 14:00:00'),
(94, 25, 'workshops/Sushi3.jpg', 'Nasser Salim', '2025-03-27 14:00:00'),
(95, 25, 'workshops/Sushi4.jpg', 'Mariam Ihab', '2025-04-01 14:00:00'),
(96, 25, 'workshops/Sushi5.jpg', 'Khaled Nour', '2025-04-06 14:00:00'),
(97, 26, 'workshops/Pasta11.jpg', 'Ali Mazen', '2025-03-10 14:00:00'),
(98, 26, 'workshops/Pasta2.jpg', 'Nadine Hassan', '2025-03-20 14:00:00'),
(99, 26, 'workshops/Pasta3.jpg', 'Zainab Omar', '2025-03-31 14:00:00'),
(100, 26, 'workshops/Pasta4.jpg', 'Fahd Feras', '2025-04-02 14:00:00'),
(102, 27, 'workshops/Belgian.jpg', 'Yasmin Khalid', '2025-03-11 14:00:00'),
(103, 27, 'workshops/Belgian2.jpg', 'Tamer Nour', '2025-03-19 14:00:00'),
(104, 27, 'workshops/Belgian3.jpg', 'Fatima Qasim', '2025-03-30 14:00:00'),
(105, 27, 'workshops/Belgian4.jpg', 'Rami Basel', '2025-04-02 14:00:00'),
(106, 27, 'workshops/Belgian5.jpg', 'Saja Hadi', '2025-04-05 14:00:00'),
(107, 28, 'workshops/cupcake1.jpg', 'Nabil Hussein', '2025-03-14 14:00:00'),
(108, 28, 'workshops/cupcake2.jpg', 'Amal Adham', '2025-03-22 14:00:00'),
(109, 28, 'workshops/cupcake3.jpg', 'Majd Tarek', '2025-03-31 14:00:00'),
(110, 28, 'workshops/cupcake4.jpg', 'Dana Mazin', '2025-04-03 14:00:00'),
(111, 28, 'workshops/cupcake5.jpg', 'Kareem Laila', '2025-04-06 14:00:00'),
(112, 8, 'workshops/drawing1.jpg', 'Lina Qassim', '2025-03-04 14:00:00'),
(113, 8, 'workshops/drawing2.jpg', 'Tariq Adel', '2025-03-13 14:00:00'),
(114, 8, 'workshops/drawing3.jpg', 'Maya Sami', '2025-03-26 14:00:00'),
(115, 8, 'workshops/drawing4.jpg', 'Khaled Hussein', '2025-04-02 14:00:00'),
(116, 8, 'workshops/drawing5.jpg', 'Reema Nawaf', '2025-04-06 14:00:00'),
(117, 14, 'workshops/swim1.jpg', 'Sarah Khalil', '2025-03-03 14:00:00'),
(118, 14, 'workshops/swim3.jpg', 'Adel Omar', '2025-03-16 14:00:00'),
(119, 14, 'workshops/swim2.jpg', 'Yara Ahmed', '2025-03-28 14:00:00'),
(120, 14, 'workshops/swim4.jpg', 'Mohanad Fares', '2025-04-01 14:00:00'),
(121, 14, 'workshops/swim5.jpg', 'Hind Ziyad', '2025-04-05 14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `ReviewID` int(11) NOT NULL,
  `Rating` int(11) DEFAULT NULL,
  `Comment` text,
  `UserID` int(11) DEFAULT NULL,
  `WorkshopID` int(11) DEFAULT NULL,
  `BookingID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`ReviewID`, `Rating`, `Comment`, `UserID`, `WorkshopID`, `BookingID`) VALUES
(3, 5, 'Honestly, the workshop was fun and very helpful. The instructor was very comfortable and explained everything in a nice way, I did not feel bored. The place is beautiful and the atmosphere is amazing', 6, 3, NULL),
(115, 5, 'Honestly, the workshop was fun and very helpful. The instructor was very comfortable and explained everything in a nice way, I did not feel bored. The place is beautiful and the atmosphere is amazing.', 6, 1, 52),
(116, 4, 'I really enjoyed learning how to make candles. The scent mixing part was my favorite! Everything was well organized and easy to follow.', 8, 1, 53),
(117, 5, 'Making candles was both relaxing and creative. I loved customizing the colors and scents. Definitely doing this again.', 10, 1, 54),
(118, 5, 'This embroidery workshop was exactly what I needed. The materials were great quality and I finished a beautiful piece I’m proud of.', 6, 2, 55),
(119, 4, 'The stitching techniques were explained clearly. I liked the calm atmosphere and how helpful the instructor was throughout the session.', 9, 2, 56),
(120, 5, 'Amazing experience! I never thought embroidery could be so therapeutic. I learned a lot and enjoyed every minute.', 12, 2, 57),
(121, 4, 'Soap making was a new experience for me and I absolutely loved it. The natural ingredients and scent mixing were fun to explore.', 8, 3, 58),
(122, 5, 'I came home with five bars of personalized soap and a new hobby. The instructor made everything simple and fun.', 11, 3, 59),
(123, 5, 'Everything smelled amazing! I enjoyed crafting the soaps and learning about the different oils and natural additives.', 13, 3, 60),
(124, 5, 'I felt so free in this drawing workshop. There were no rules, just expression. It helped me loosen up and be more confident with my art.', 6, 8, 61),
(125, 4, 'Great session to boost creativity. I liked how the instructor gave space for personal interpretation and made it very relaxing.', 10, 8, 62),
(126, 5, 'This workshop unlocked a creative side of me I didn’t know existed. It was peaceful and inspiring. Loved it.', 12, 8, 63),
(127, 5, 'This was such a fun workshop! I decorated my phone case with glitter and paints, and it turned out great. Everything was provided and the vibe was fun.', 8, 9, 64),
(128, 4, 'I enjoyed customizing my cover. There were many tools and ideas, and the instructors were very encouraging. Would love to do it again!', 11, 9, 65),
(129, 3, 'The concept was cool but I think they could offer more decorative materials. Still, it was a good creative break.', 13, 9, 66),
(130, 5, 'I’ve always wanted to learn crochet, and this workshop was the perfect place to start. The instructor was patient and I made my first coaster!', 6, 10, 67),
(131, 5, 'A cozy and calm atmosphere made learning easy. The pace was just right for beginners like me, and the instructor explained things clearly.', 9, 10, 68),
(132, 4, 'I enjoyed this online class a lot. I was able to follow along from home and the instructions were super clear. Very relaxing too.', 10, 10, 69),
(133, 5, 'Painting on clothes was a new and exciting experience! I customized my t-shirt and it turned out amazing. The paints and materials were high quality.', 6, 11, 70),
(134, 4, 'The workshop encouraged creativity and gave us freedom to express ourselves on fabric. I loved how personal and fun it was.', 9, 11, 71),
(135, 5, 'The idea of wearable art is just brilliant. The session was full of energy and color, and the final result looked great!', 13, 11, 72),
(136, 4, 'Pottery is more fun than I thought! Molding and shaping the clay was relaxing, and I felt proud of the piece I made.', 8, 12, 73),
(137, 5, 'The instructor was so helpful and kind. I learned how to create a basic bowl and glaze it. Definitely want to do this again.', 10, 12, 74),
(138, 5, 'This workshop was therapeutic. Working with clay helped me disconnect from stress and connect with my creative side.', 11, 12, 75),
(139, 5, 'The stargazing experience was breathtaking. Seeing the stars and constellations through a telescope was magical. Peaceful and educational.', 9, 13, 76),
(140, 4, 'The night sky was beautiful, and the guide explained everything clearly. It was a calming and inspiring evening.', 12, 13, 77),
(141, 5, 'A night under the stars with telescopes and stories about constellations—definitely one of my favorite workshops.', 8, 13, 78),
(145, 5, 'The Kashta in the desert was peaceful and full of warmth. Sitting under the stars with hot drinks felt like a dream.', 10, 15, 79),
(146, 4, 'Loved the setup! It felt traditional and cozy. The desert view, the fire, and the tea were perfect.', 6, 15, 80),
(147, 5, 'Exactly what I needed to escape city life. Calm, scenic, and filled with good vibes. Highly recommend it!', 13, 15, 81),
(148, 4, 'The climb was challenging but totally worth it! The view from the top was beautiful. Great workout too.', 11, 16, 82),
(149, 5, 'This was such a powerful and exciting activity. I felt strong and accomplished reaching the top.', 12, 16, 83),
(150, 5, 'The instructors were professional and supportive. I felt safe and energized throughout the climb.', 9, 16, 84),
(151, 5, 'Flying above the sea was breathtaking. The views were surreal and the team made sure we were safe and comfortable.', 6, 17, 85),
(152, 4, 'It was my first time trying something like this, and I’m glad I did. The adrenaline and views were worth it!', 9, 17, 86),
(153, 5, 'This workshop made me feel alive! A thrilling experience with stunning ocean scenery.', 11, 17, 87),
(154, 5, 'The horses were calm and well-trained. It was peaceful and therapeutic riding through the farm.', 6, 18, 88),
(155, 4, 'I had such a great time bonding with the horses. The environment was quiet and beautiful.', 10, 18, 89),
(156, 5, 'Amazing atmosphere! I enjoyed the entire experience and learned a lot about horse handling.', 13, 18, 90),
(157, 4, 'Fun and energetic session! Got to meet new people and burn calories while having fun.', 8, 19, 91),
(158, 5, 'Loved the competitive spirit of the paddle challenge. Great instructor and environment!', 11, 19, 92),
(159, 5, 'Great for staying active and social. Definitely joining again next time.', 12, 19, 93),
(160, 4, 'Balance training helped me feel more confident while riding. Good guidance from the coach.', 10, 20, 94),
(161, 5, 'The activities were well-planned and fun. I didn’t expect to learn so much in one hour!', 9, 20, 95),
(162, 5, 'Loved the focus on coordination. Very helpful for beginners and fun at the same time.', 6, 20, 96),
(163, 5, 'I finally learned how to decorate cakes professionally! The frosting techniques were fun to try.', 6, 21, 97),
(164, 4, 'I enjoyed every moment. The instructor was supportive and creative!', 8, 21, 98),
(165, 5, 'So satisfying to see the final decorated cake. Definitely boosted my creativity.', 13, 21, 99),
(166, 5, 'The pizza workshop was absolutely delicious! I made my own pizza from scratch and loved it.', 9, 22, 100),
(167, 5, 'Perfect workshop for foodies! Easy to follow instructions and yummy results.', 11, 22, 101),
(168, 4, 'Kneading the dough was fun and the toppings were fresh. Highly recommend this one!', 10, 22, 102),
(169, 4, 'Never thought I’d be able to make latte art! The instructor was very detailed and fun.', 12, 23, 103),
(170, 5, 'From frothing milk to pouring – everything was covered. Great experience!', 8, 23, 104),
(171, 5, 'Loved it! The setup was cozy and the session was beginner-friendly.', 6, 23, 105),
(172, 5, 'Baking cookies was the best part of my week. The smell, taste, and fun were all top tier!', 10, 24, 106),
(173, 4, 'I loved learning the proper technique. My cookies turned out amazing!', 11, 24, 107),
(174, 5, 'Fun session with a sweet outcome. I’ll definitely bake again soon!', 13, 24, 108),
(175, 4, 'Rolling sushi was a challenge, but the chef made it enjoyable and easy to follow.', 12, 25, 109),
(176, 5, 'Fresh ingredients and great guidance. I feel like a sushi pro now!', 9, 25, 110),
(177, 5, 'Such a fun and cultural experience. Loved every minute!', 8, 25, 111),
(178, 5, 'Making pasta from scratch was so rewarding. I didn’t expect to enjoy it this much!', 10, 26, 112),
(179, 4, 'The instructor shared amazing tips. My pasta came out great!', 6, 26, 113),
(180, 5, 'A delicious experience with authentic vibes. Highly recommend.', 11, 26, 114),
(181, 5, 'Chocolate heaven! We got to mold, fill, and decorate. Super fun!', 8, 27, 115),
(182, 4, 'The quality of chocolate and tools provided were excellent. Loved it!', 9, 27, 116),
(183, 5, 'Hands-on and informative. I went home with beautiful and tasty creations.', 12, 27, 117),
(184, 4, 'Fun decorating session and tasty results! Great for all ages.', 6, 28, 118),
(185, 5, 'Loved learning the different icing styles. The cupcakes were delicious.', 13, 28, 119),
(186, 5, 'A sweet experience from start to finish. Great for beginners too!', 10, 28, 120),
(187, 4, 'Swimming in such a calm environment really helped me relax. The coach was helpful and gave tips to improve my technique.', 8, 14, 121),
(188, 5, 'It was refreshing and just what I needed. Clean pool, good timing, and chill atmosphere.', 10, 14, 122),
(189, 4, 'A great mix of fun and exercise. Even as a beginner, I felt comfortable joining and enjoyed every minute.', 13, 14, 123);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(64) NOT NULL,
  `Mobile` varchar(13) NOT NULL,
  `ProfilePhoto` varchar(255) DEFAULT NULL,
  `Bio` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Email`, `Password`, `Mobile`, `ProfilePhoto`, `Bio`) VALUES
(6, 'Mashael  ', 'khalid', 'mashael@example.com', '$2y$10$TNXrVsxUd6pH9puoiW4LdOIZP2lN0XKOaDFwgXv6wrPLAP9.ZYL82', '+966507504050', '67f622bdb8fa5_profile-pic.png', 'Let\'s make some new Memories'),
(8, 'Rahaf', 'AlFantoukh', 'Rahaf@example.com', '$2y$10$LuQbZoK4kWnZIU/PH9TgXO1JgA7NIoRrM.oxD301dfyrNG.J3zHza', '+966503018850', NULL, NULL),
(9, 'Aljawharah', 'Alsubaie', 'Aljawharah@example.com', '$2y$10$SOihvJGF.GGUVC.kSz2I0ub0zZYPNkOc5g8Q.93PeFq0zIVo6jnBu', '+966501926876', NULL, NULL),
(10, 'Leena', 'Alhaider', 'Leena@example.com', '$2y$10$SH9sipniFow/iuXO.kZXz.eb84ubRkE10wn3YOg4f28sWpgf6ZTWG', '+966538756710', '67f76c4760ad3_photo_2025-04-10_09-59-05.jpg', 'I love to live different experiences and enjoy every moment of it.'),
(11, 'Hadeel', 'Almutairi', 'Hadeel@example.com', '$2y$10$VHDAQcsr2l1tGjJo4QfbgeU.EwLrue6EkDwo7F5SM4gltnGRoR1Uy', '+966503086034', NULL, NULL),
(12, 'Ahmed', 'Khalid', 'ahmed@example.com', '$2y$10$OvArclovAOOOekU2a3k8pex9bdAEkfY70BLTRbapfDoyCTCam1wE6', '+966503086932', NULL, NULL),
(13, 'Omer', 'Abdulaziz', 'Omer@example.com', '$2y$10$TopcdnZ0JPB2p4IA4FwZ0uZiu2ugJ1F6YD.Oea7lFBNnBsO5ZebGa', '+966518456719', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workshop`
--

CREATE TABLE `workshop` (
  `WorkshopID` int(11) NOT NULL,
  `Title` varchar(50) NOT NULL,
  `ShortDes` text,
  `LongDes` text,
  `Category` enum('Art','Cooking','Adventure') NOT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `Type` enum('in-person','Online','Both') DEFAULT NULL,
  `Duration` text,
  `Age` int(11) NOT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `ImageURL` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `workshop`
--

INSERT INTO `workshop` (`WorkshopID`, `Title`, `ShortDes`, `LongDes`, `Category`, `Location`, `Type`, `Duration`, `Age`, `Price`, `ImageURL`) VALUES
(1, 'Candle Making', 'Create stunning candles that combine beauty and fragrance in a fun artistic experience.\r\n\r\n', 'Learn how to create beautiful and decorative candles using various techniques. This hands-on workshop covers\r\nwax melting, scent blending, and color customization, allowing you to craft unique candles for yourself or as gifts.\r\nPerfect for all skill levels!', 'Art', 'Jeddah', 'in-person', '2 Hours', 15, '160.00', 'workshops/candle.jpeg'),
(2, 'Embroidery', 'Explore the world of embroidery and learn basic stitches through simple and enjoyable steps.\r\n\r\n', 'In this workshop, you will learn the fundamentals of embroidery, including thread selection, color coordination, and a variety of stitching techniques. Whether you’re a beginner or looking to refine your skills, this session offers hands-on practice to create your own embroidered piece. All materials are provided, and you\'ll walk away with a finished design that reflects your personal style.', 'Art', 'Riyadh', 'Both', '3 Hours', 15, '270.00', 'workshops/Embroidery.png'),
(3, 'Soap Making', 'Craft artistic soap using natural ingredients and creative designs.\r\n\r\n', 'Learn how to craft your own natural soaps in this engaging, hands-on workshop. You\'ll discover how to mix essential oils, colors, and natural ingredients to create beautiful, skin-friendly soap bars. Great for personal use or gifting, this workshop allows you to customize your soaps to your liking while gaining knowledge in natural product creation.', 'Art', 'Jeddah', 'in-person', '2 Hours', 17, '260.00', 'workshops/soap.jpg'),
(4, 'Pottery', 'Crafting clay into unique and artistic shapes.', 'Dive into the world of pottery and clay art. In this immersive workshop, you’ll learn how to mold, shape, and glaze your own ceramic pieces using traditional and modern techniques. Whether you\'re trying pottery for the first time or expanding your skills, this experience offers a fun and rewarding way to express your creativity.', 'Art', 'Dammam', 'in-person', '3 Hours', 18, '200.00', 'workshops/pottry-workshop.jpg'),
(5, 'Painting', 'Hand-painting and decorating ceramic pieces with creative designs', 'Express your creativity by hand-painting ceramic pieces in this fun and artistic workshop. You\'ll get to work with pre-made ceramic items—such as plates, mugs, or tiles—and use special ceramic paints to create your own unique designs. Learn tips and techniques for color blending, brush control, and finishing touches to make your artwork pop. Once painted, the pieces will be glazed and kiln-fired, giving them a lasting, professional look. No prior experience needed—just bring your imagination!', 'Art', 'Dammam', NULL, '2 Hours', 0, '150.00', 'workshops/homeart.jpg'),
(6, 'Pizza Making', 'Learn the techniques of soap making.', 'In this workshop, you will learn how to prepare and bake authentic pizza from scratch. From kneading the dough to adding your favorite toppings and baking to perfection, this hands-on session is perfect for food lovers of all levels.', 'Cooking', 'Dammam', NULL, '2 Hours', 0, '190.00', 'workshops/homepizza.jpg'),
(7, 'Stargazing', 'Observing stars and constellations to explore the wonders of the universe', 'Join us for a magical stargazing experience using professional telescopes. You’ll explore the night sky, learn about constellations and planets, and enjoy the beauty of the universe in a peaceful outdoor setting.', 'Adventure', 'Dammam', NULL, '1 Hour', 0, '130.00', 'workshops/hometele2y.jpg'),
(8, 'Free Drawing', 'Discover your artistic style through a free drawing session that unleashes your creativity.\r\n\r\n', 'Unleash your creativity in our Free Drawing workshop designed to develop your artistic expression. Participants will explore a variety of creative drawing techniques in a relaxed and inspiring environment. The workshop is suitable for all skill levels and encourages personal interpretation and artistic freedom without limiting you to specific tools or materials.', 'Art', 'Ryiadh', 'in-person', '3 Hours', 6, '150.00', 'workshops/drawing.jpeg'),
(9, 'Customize Your cover', 'Learn how to design your own cover in a way that reflects your personality and artistic taste.\r\n\r\n', 'Give your phone a unique touch by customizing your own phone cover in this creative, hands-on workshop. You’ll learn how to decorate phone cases using paints, stickers, rhinestones, and various DIY techniques to create a design that truly reflects your style. Whether you prefer something bold, minimal, or sparkly, this workshop offers all the materials and inspiration you need to craft a one-of-a-kind phone cover.', 'Art', 'Jeddah', 'Both', '2 Hours', 10, '150.00', 'workshops/cover.jpg'),
(10, 'Crochet Basics', 'Dive into the world of crochet and master the fundamentals of stitching in a fun and easy way.\r\n\r\n', 'This beginner-friendly workshop will introduce you to the basics of crochet, including how to hold the hook, start your first stitches, and follow simple patterns. By the end of the session, you’ll be able to create small handmade pieces like coasters or decorative accents. Delivered online, the workshop provides step-by-step guidance in a relaxed, interactive setting.', 'Art', 'Jeddah', 'Online', '3 Hours', 12, '220.00', 'workshops/Crochet.jpg'),
(11, 'Clothes Painting', 'Express your creativity by painting and personalizing clothes in your unique style.\r\n\r\n', 'Turn plain clothes into wearable art in our Clothes Painting workshop! This session allows you to express yourself by painting directly on fabric using textile paints, brushes, and stencils. Whether you’re customizing a t-shirt, hoodie, or tote bag, you\'ll learn various techniques to help bring your designs to life. It\'s a fun and expressive experience that encourages bold creativity and personal style.', 'Art', 'Riyadh', 'in-person', '2 Hours', 12, '170.00', 'workshops/clothes.jpg'),
(12, 'Pottery Workshop', 'Explore the art of clay shaping and learn the techniques of pottery making.\r\n\r\n', 'Dive into the world of pottery and clay art. In this immersive workshop, you’ll learn how to mold, shape, and glaze your own ceramic pieces using traditional and modern techniques. Whether you\'re trying pottery for the first time or expanding your skills, this experience offers a fun and rewarding way to express your creativity.', 'Art', 'Jeddah', 'Both', '3 Hours', 18, '200.00', 'workshops/poterry2.jpeg'),
(13, 'Starry night ', 'A magical night under the stars with professional telescope stargazing', 'Experience a magical night under the stars with professional telescopes and expert guidance. Discover planets, constellations, and celestial stories in a serene outdoor setting.', 'Adventure', 'Riyadh', 'in-person', '3 Hours', 12, '280.00', 'workshops\\telescope.jpeg'),
(14, 'Swimming', 'A refreshing swim recharges your energy and lifts your spirits.', 'Dive into a refreshing swimming session designed to boost your energy and improve your mood. Suitable for all levels, this workshop includes guided exercises and free swim time.', 'Adventure', 'Jeddah', 'in-person', '1 Hour', 7, '300.00', 'workshops\\swim.jpeg'),
(15, 'Kashta in Red sanddune ', 'Enjoy a warm Kashta  that takes you away from the hustle and bustle of the city.', 'Relax and unwind in the desert with a traditional Kashta setup. Enjoy storytelling, warm drinks, and peaceful surroundings under the stars in the red sand dunes.', 'Adventure', 'Jeddah', 'in-person', '4 Hours', 18, '350.00', 'workshops\\download (1).jpeg'),
(16, 'mountaineering', 'Boost your energy and discover your strength with a fun and safe climbing experience.', 'Challenge yourself with a mountaineering adventure guided by professionals. Learn safe climbing techniques, build stamina, and enjoy breathtaking views from the top.', 'Adventure', 'Riyadh', 'in-person', '3 Hours', 10, '200.00', 'workshops\\mounturring.jpg'),
(17, 'Fly over the sea', 'Fly over the sea, enjoy the feeling of freedom and the stunning views from the sky.', 'Soar above the sea and enjoy a thrilling aerial adventure. This activity provides a safe and unforgettable flying experience, capturing stunning ocean views and freedom in flight.', 'Adventure', 'Jeddah', 'in-person', '1 Hour', 16, '250.00', 'workshops\\flowsky.jpg'),
(18, 'Horse riding experience', 'A light horse ride in a quiet farm and a fun atmosphere.', 'Enjoy a peaceful horseback ride through a quiet farm. This workshop provides basic riding techniques and the opportunity to bond with well-trained horses in a calm environment.', 'Adventure', 'Jeddah', 'in-person', '1 Hour', 10, '160.00', 'workshops\\horse.jpg'),
(19, 'Paddle challenge ', 'A fun and competitive paddle game that keeps your energy high.', 'Participate in an exciting paddle game that promotes fitness and teamwork. Learn rules and techniques in a fun and friendly competition perfect for all ages.', 'Adventure', 'Ryiadh', 'in-person', '2 Hours', 12, '150.00', 'workshops\\padle2.jpg'),
(20, 'Balance training', 'Learn balance and ride with confidence in a beginner-friendly session.', 'Develop your sense of balance and coordination with beginner-friendly riding techniques. This session focuses on body control and confidence in a safe environment.', 'Adventure', 'Ryiadh', 'in-person', '1 Hour', 7, '150.00', 'workshops\\bike.jpg'),
(21, 'Cake Art', 'Learn how to decorate cakes with using decorating tools.', 'Discover the art of cake decoration using various frosting and piping techniques. Learn to design creative cake finishes suitable for beginners and aspiring pastry artists.', 'Cooking', 'Ryiadh', 'in-person', '3 Hours', 13, '150.00', 'workshops\\cake_art.jpeg'),
(22, 'Pizza Making', 'Discover how to prepare authentic Italian pizza from dough to toppings.', 'Join our pizza-making session where you\'ll learn every step from kneading dough to choosing the best toppings. Cook your personalized pizza in a traditional oven.', 'Cooking', 'Jeddah', 'in-person', '3 Hours', 18, '200.00', ' workshops\\pizza.jpg'),
(23, 'Coffee Art', 'Enjoy coffee art techniques to serve your favorite drink in a creative style.', 'Master the skill of coffee art by practicing milk frothing and latte pouring techniques. Perfect for coffee lovers wanting to create visually appealing beverages.', 'Cooking', 'Ryiadh', 'Both', '2 Hours', 15, '130.00', ' workshops\\coffeeart.jpeg'),
(24, 'Chocolate Cookies ', 'Enjoy baking cookies professionally for perfect texture and delicious flavors.', 'Bake delicious cookies with a perfect texture and rich flavor. This workshop covers ingredients, mixing, shaping, and baking techniques to create your signature batch.', 'Cooking', 'Riyadh', 'Online', '2 Hours', 10, '160.00', ' workshops\\Chocolate Chip Cookies.jpeg'),
(25, 'Sushi', 'learn how to make fresh Japanese sushi rolls by hand.', 'Learn how to roll authentic Japanese sushi by hand. This hands-on session includes rice preparation, ingredient selection, rolling methods, and presentation tips.', 'Cooking', 'Jeaddh', 'Both', '3 Hours', 14, '220.00', ' workshops\\suchimake.jpg'),
(26, 'Italian Pasta', 'Learn to make pasta from scratch using  traditional Italian methods.', 'Make Italian pasta from scratch using traditional techniques. This workshop teaches dough preparation, cutting, and pairing with classic sauces for a complete dish.', 'Cooking', 'Jeddah', 'Online', '3 Hours', 12, '190.00', ' workshops\\pasta1.jpeg'),
(27, 'Belgian Chocolate Sweets', ' learn the secrets of melting and molding chocolate.', 'Explore chocolate crafting by learning how to temper, mold, and decorate chocolate creations. This sweet session is ideal for both beginners and dessert lovers.', 'Cooking', 'Jeddah', 'Both', '3 Hours', 18, '230.00', ' workshops\\palgeagancoco.jpeg'),
(28, 'Cupcake baking', 'Learn to bake cupcakes with creative flavors.', 'Bake and decorate cupcakes using creative flavor combinations and fun toppings. This hands-on workshop allows you to take home beautifully crafted cupcakes.', 'Cooking', 'Riyadh', 'Online', '2 Hours', 8, '140.00', 'workshops\\cupcake1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `workshop_schedule`
--

CREATE TABLE `workshop_schedule` (
  `ScheduleID` int(11) NOT NULL,
  `WorkshopID` int(11) NOT NULL,
  `Day` varchar(10) DEFAULT NULL,
  `Date` date NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `workshop_schedule`
--

INSERT INTO `workshop_schedule` (`ScheduleID`, `WorkshopID`, `Day`, `Date`, `StartTime`, `EndTime`) VALUES
(1, 1, 'Friday', '2025-04-10', '15:00:00', '17:00:00'),
(2, 1, 'Saturday', '2025-04-11', '09:00:00', '11:00:00'),
(3, 1, 'Saturday', '2025-04-11', '13:00:00', '15:00:00'),
(4, 2, 'Tuesday', '2025-04-29', '15:00:00', '18:00:00'),
(5, 2, 'Wednesday', '2025-04-30', '15:00:00', '18:00:00'),
(6, 2, 'Thursday', '2025-05-01', '15:00:00', '18:00:00'),
(7, 4, 'Saturday', '2025-05-03', '15:00:00', '18:00:00'),
(8, 4, 'Sunday', '2025-05-04', '14:00:00', '17:00:00'),
(9, 4, 'Sunday', '2025-05-04', '17:00:00', '20:00:00'),
(10, 3, 'Tuesday', '2025-05-13', '13:00:00', '15:00:00'),
(11, 3, 'Wednesday', '2025-05-14', '09:00:00', '11:00:00'),
(12, 3, 'Thursday', '2025-05-15', '18:00:00', '20:00:00'),
(13, 5, 'Friday', '2025-05-02', '14:00:00', '16:00:00'),
(14, 5, 'Friday', '2025-05-09', '16:00:00', '18:00:00'),
(15, 5, 'Friday', '2025-05-16', '18:00:00', '20:00:00'),
(16, 6, 'Tuesday', '2025-05-20', '13:00:00', '15:00:00'),
(17, 6, 'Wednesday', '2025-05-21', '16:00:00', '18:00:00'),
(18, 6, 'Thursday', '2025-05-22', '17:00:00', '19:00:00'),
(19, 7, 'Monday', '2025-05-05', '10:00:00', '11:00:00'),
(20, 7, 'Wednesday', '2025-05-07', '13:00:00', '14:00:00'),
(21, 8, 'Monday', '2025-05-12', '09:00:00', '12:00:00'),
(22, 8, 'Wednesday', '2025-05-14', '15:00:00', '18:00:00'),
(23, 8, 'Thursday', '2025-05-15', '16:00:00', '19:00:00'),
(24, 9, 'Friday', '2025-05-02', '14:00:00', '16:00:00'),
(25, 9, 'Friday', '2025-05-09', '11:00:00', '13:00:00'),
(26, 9, 'Friday', '2025-05-16', '17:00:00', '19:00:00'),
(27, 10, 'Tuesday', '2025-05-20', '09:00:00', '12:00:00'),
(28, 10, 'Wednesday', '2025-05-21', '13:00:00', '16:00:00'),
(29, 10, 'Thursday', '2025-05-22', '17:00:00', '20:00:00'),
(30, 11, 'Saturday', '2025-05-10', '10:00:00', '12:00:00'),
(31, 11, 'Sunday', '2025-05-11', '14:00:00', '16:00:00'),
(32, 12, 'Saturday', '2025-05-17', '13:00:00', '16:00:00'),
(33, 12, 'Sunday', '2025-05-18', '09:00:00', '12:00:00'),
(34, 12, 'Monday', '2025-05-19', '15:00:00', '18:00:00'),
(60, 13, 'Monday', '2025-06-23', '10:00:00', '13:00:00'),
(61, 13, 'Monday', '2025-06-02', '13:00:00', '16:00:00'),
(62, 13, 'Sunday', '2025-06-08', '09:00:00', '12:00:00'),
(63, 14, 'Sunday', '2025-05-25', '12:00:00', '13:00:00'),
(64, 14, 'Friday', '2025-05-30', '13:00:00', '14:00:00'),
(65, 14, 'Tuesday', '2025-06-24', '11:00:00', '12:00:00'),
(66, 15, 'Saturday', '2025-06-28', '10:00:00', '14:00:00'),
(67, 15, 'Saturday', '2025-05-24', '09:00:00', '13:00:00'),
(68, 16, 'Wednesday', '2025-06-11', '13:00:00', '16:00:00'),
(69, 16, 'Friday', '2025-06-06', '10:00:00', '13:00:00'),
(70, 16, 'Friday', '2025-05-30', '11:00:00', '14:00:00'),
(71, 17, 'Saturday', '2025-06-07', '09:00:00', '10:00:00'),
(72, 17, 'Friday', '2025-06-13', '14:00:00', '15:00:00'),
(73, 17, 'Sunday', '2025-06-22', '11:00:00', '12:00:00'),
(74, 18, 'Sunday', '2025-06-29', '09:00:00', '10:00:00'),
(75, 18, 'Monday', '2025-05-26', '11:00:00', '12:00:00'),
(76, 19, 'Thursday', '2025-06-05', '09:00:00', '11:00:00'),
(77, 19, 'Tuesday', '2025-06-10', '13:00:00', '15:00:00'),
(78, 19, 'Friday', '2025-06-14', '11:00:00', '13:00:00'),
(79, 20, 'Sunday', '2025-06-01', '12:00:00', '13:00:00'),
(80, 20, 'Monday', '2025-06-16', '09:00:00', '10:00:00'),
(81, 20, 'Wednesday', '2025-06-18', '14:00:00', '15:00:00'),
(82, 21, 'Saturday', '2025-06-15', '10:00:00', '13:00:00'),
(83, 21, 'Thursday', '2025-05-29', '14:00:00', '17:00:00'),
(84, 21, 'Tuesday', '2025-06-03', '09:00:00', '12:00:00'),
(85, 22, 'Monday', '2025-06-23', '15:00:00', '18:00:00'),
(86, 22, 'Sunday', '2025-06-08', '13:00:00', '16:00:00'),
(87, 22, 'Thursday', '2025-06-12', '10:00:00', '13:00:00'),
(88, 23, 'Tuesday', '2025-06-11', '15:00:00', '17:00:00'),
(89, 23, 'Thursday', '2025-06-12', '09:00:00', '11:00:00'),
(90, 23, 'Saturday', '2025-06-14', '11:00:00', '13:00:00'),
(91, 24, 'Sunday', '2025-06-22', '11:00:00', '13:00:00'),
(92, 24, 'Monday', '2025-06-30', '13:00:00', '15:00:00'),
(93, 24, 'Wednesday', '2025-06-25', '10:00:00', '12:00:00'),
(94, 25, 'Wednesday', '2025-06-04', '11:00:00', '14:00:00'),
(95, 25, 'Saturday', '2025-06-21', '10:00:00', '13:00:00'),
(96, 25, 'Friday', '2025-06-27', '09:00:00', '12:00:00'),
(97, 26, 'Wednesday', '2025-06-18', '13:00:00', '16:00:00'),
(98, 26, 'Monday', '2025-06-23', '14:00:00', '17:00:00'),
(99, 26, 'Thursday', '2025-06-19', '10:00:00', '13:00:00'),
(100, 27, 'Saturday', '2025-06-28', '12:00:00', '15:00:00'),
(101, 27, 'Monday', '2025-06-16', '15:00:00', '18:00:00'),
(102, 27, 'Friday', '2025-06-20', '11:00:00', '14:00:00'),
(103, 28, 'Thursday', '2025-06-26', '09:00:00', '11:00:00'),
(104, 28, 'Tuesday', '2025-06-03', '10:00:00', '12:00:00'),
(105, 28, 'Monday', '2025-06-09', '14:00:00', '16:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`BookingID`),
  ADD UNIQUE KEY `BID` (`BID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `WorkshopID` (`WorkshopID`),
  ADD KEY `ScheduleID` (`ScheduleID`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_fav` (`UserID`,`WorkshopID`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `fk_post` (`postID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `previous_works`
--
ALTER TABLE `previous_works`
  ADD PRIMARY KEY (`WorkID`),
  ADD KEY `WorkshopID` (`WorkshopID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `WorkshopID` (`WorkshopID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `workshop`
--
ALTER TABLE `workshop`
  ADD PRIMARY KEY (`WorkshopID`);

--
-- Indexes for table `workshop_schedule`
--
ALTER TABLE `workshop_schedule`
  ADD PRIMARY KEY (`ScheduleID`),
  ADD KEY `WorkshopID` (`WorkshopID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `BookingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `likeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `previous_works`
--
ALTER TABLE `previous_works`
  MODIFY `WorkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `ReviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `workshop_schedule`
--
ALTER TABLE `workshop_schedule`
  MODIFY `ScheduleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`WorkshopID`) REFERENCES `workshop` (`WorkshopID`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`ScheduleID`) REFERENCES `workshop_schedule` (`ScheduleID`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`postID`) REFERENCES `posts` (`PostID`);

--
-- Constraints for table `previous_works`
--
ALTER TABLE `previous_works`
  ADD CONSTRAINT `previous_works_ibfk_1` FOREIGN KEY (`WorkshopID`) REFERENCES `workshop` (`WorkshopID`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`WorkshopID`) REFERENCES `workshop` (`WorkshopID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
