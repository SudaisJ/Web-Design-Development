-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 31, 2026 at 12:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(150) NOT NULL,
  `category` varchar(100) DEFAULT 'General',
  `isbn` varchar(50) NOT NULL,
  `cover_image` varchar(255) DEFAULT 'default_cover.png',
  `published_year` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `status` enum('Available','Borrowed') DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `isbn`, `cover_image`, `published_year`, `quantity`, `status`, `created_at`) VALUES
(739, 'Introduction to Operating Systems for Engineers', 'Michelle Miller', 'Computer Science', '9788432648366', 'default_cover.png', 2000, 4, 'Available', '2026-05-31 09:55:29'),
(740, 'Principles of Operating Systems 4th Edition', 'Michael Miller', 'Computer Science', '9782973360942', 'default_cover.png', 2003, 4, 'Available', '2026-05-31 09:55:29'),
(741, 'Introduction to Software Engineering for Engineers', 'John Smith', 'Computer Science', '9785222745894', 'default_cover.png', 2005, 2, 'Available', '2026-05-31 09:55:29'),
(742, 'Applied Computer Architecture for Engineers', 'Charles Jones', 'Computer Science', '9784035585986', 'default_cover.png', 2023, 4, 'Available', '2026-05-31 09:55:29'),
(743, 'Mastering Database Management 2nd Edition', 'John Garcia', 'Computer Science', '9781590176941', 'default_cover.png', 2022, 9, 'Available', '2026-05-31 09:55:29'),
(744, 'Applied Data Structures in Practice', 'Sarah Rodriguez', 'Computer Science', '9789783678865', 'default_cover.png', 2018, 5, 'Available', '2026-05-31 09:55:29'),
(745, 'Principles of Database Management 3rd Edition', 'Daniel Johnson', 'Computer Science', '9783505495626', 'default_cover.png', 2014, 6, 'Available', '2026-05-31 09:55:29'),
(746, 'Fundamentals of Algorithms 3rd Edition', 'David Gonzalez', 'Computer Science', '9781507177811', 'default_cover.png', 2012, 5, 'Available', '2026-05-31 09:55:29'),
(747, 'Principles of Database Management 3rd Edition', 'Sarah Martinez', 'Computer Science', '9788723350206', 'default_cover.png', 2014, 10, 'Available', '2026-05-31 09:55:29'),
(748, 'Fundamentals of Database Management', 'James Brown', 'Computer Science', '9784348415562', 'default_cover.png', 2017, 9, 'Available', '2026-05-31 09:55:29'),
(749, 'Modern Database Management 2nd Edition', 'Thomas Davis', 'Computer Science', '9781201430106', 'default_cover.png', 2010, 3, 'Available', '2026-05-31 09:55:29'),
(750, 'Introduction to Algorithms for Engineers', 'Amanda Smith', 'Computer Science', '9781184203945', 'default_cover.png', 2019, 10, 'Available', '2026-05-31 09:55:29'),
(751, 'Introduction to Database Management', 'Michelle Martinez', 'Computer Science', '9781222195608', 'default_cover.png', 2003, 5, 'Available', '2026-05-31 09:55:29'),
(752, 'Mastering Computer Networks', 'Robert Hernandez', 'Computer Science', '9787651740272', 'default_cover.png', 2011, 9, 'Available', '2026-05-31 09:55:29'),
(753, 'Fundamentals of Data Structures in Practice', 'Sarah Williams', 'Computer Science', '9788269853242', 'default_cover.png', 2018, 4, 'Available', '2026-05-31 09:55:29'),
(754, 'Modern Data Structures 3rd Edition', 'Richard Martinez', 'Computer Science', '9782695103216', 'default_cover.png', 2015, 3, 'Available', '2026-05-31 09:55:29'),
(755, 'Advanced Data Structures for Engineers', 'Richard Martinez', 'Computer Science', '9785319384366', 'default_cover.png', 2007, 2, 'Available', '2026-05-31 09:55:29'),
(756, 'Advanced Computer Networks', 'William Rodriguez', 'Computer Science', '9786478269205', 'default_cover.png', 2006, 3, 'Available', '2026-05-31 09:55:29'),
(757, 'Modern Algorithms in Practice', 'Lisa Garcia', 'Computer Science', '9783682190165', 'default_cover.png', 2012, 7, 'Available', '2026-05-31 09:55:29'),
(758, 'Advanced Data Structures 2nd Edition', 'Jessica Gonzalez', 'Computer Science', '9786604667396', 'default_cover.png', 2011, 8, 'Available', '2026-05-31 09:55:29'),
(759, 'Applied Data Structures 4th Edition', 'William Davis', 'Computer Science', '9789869387736', 'default_cover.png', 2000, 2, 'Available', '2026-05-31 09:55:29'),
(760, 'Modern Computer Networks 2nd Edition', 'Robert Miller', 'Computer Science', '9785219657777', 'default_cover.png', 2000, 5, 'Available', '2026-05-31 09:55:29'),
(761, 'Introduction to Database Management 3rd Edition', 'John Wilson', 'Computer Science', '9787834885060', 'default_cover.png', 2018, 10, 'Available', '2026-05-31 09:55:29'),
(762, 'Introduction to Computer Networks for Engineers', 'Richard Garcia', 'Computer Science', '9789351877091', 'default_cover.png', 2012, 10, 'Available', '2026-05-31 09:55:29'),
(763, 'Advanced Database Management', 'Robert Wilson', 'Computer Science', '9784903061266', 'default_cover.png', 2017, 4, 'Available', '2026-05-31 09:55:29'),
(764, 'Advanced Database Management', 'Richard Jones', 'Computer Science', '9785282980269', 'default_cover.png', 2013, 10, 'Available', '2026-05-31 09:55:29'),
(765, 'Applied Compiler Design 4th Edition', 'Jennifer Miller', 'Computer Science', '9783781662964', 'default_cover.png', 2008, 5, 'Available', '2026-05-31 09:55:29'),
(766, 'Mastering Operating Systems for Engineers', 'Amanda Jones', 'Computer Science', '9783041744545', 'default_cover.png', 2005, 10, 'Available', '2026-05-31 09:55:29'),
(767, 'Introduction to Operating Systems 4th Edition', 'Robert Wilson', 'Computer Science', '9784776842995', 'default_cover.png', 2020, 4, 'Available', '2026-05-31 09:55:29'),
(768, 'Fundamentals of Database Management for Engineers', 'Jennifer Davis', 'Computer Science', '9789529713819', 'default_cover.png', 2013, 8, 'Available', '2026-05-31 09:55:29'),
(769, 'Mastering Operating Systems for Engineers', 'Ashley Brown', 'Computer Science', '9786353825712', 'default_cover.png', 2020, 7, 'Available', '2026-05-31 09:55:29'),
(770, 'Modern Algorithms 2nd Edition', 'James Wilson', 'Computer Science', '9781489357724', 'default_cover.png', 2006, 3, 'Available', '2026-05-31 09:55:29'),
(771, 'Introduction to Computer Architecture 2nd Edition', 'Richard Williams', 'Computer Science', '9788501094913', 'default_cover.png', 2018, 9, 'Available', '2026-05-31 09:55:29'),
(772, 'Fundamentals of Computer Architecture 3rd Edition', 'Charles Martinez', 'Computer Science', '9786562706906', 'default_cover.png', 2020, 9, 'Available', '2026-05-31 09:55:29'),
(773, 'Mastering Data Structures', 'Charles Gonzalez', 'Computer Science', '9789223613090', 'default_cover.png', 2001, 4, 'Available', '2026-05-31 09:55:29'),
(774, 'Principles of Database Management for Engineers', 'Michael Garcia', 'Computer Science', '9781928457412', 'default_cover.png', 2003, 2, 'Available', '2026-05-31 09:55:29'),
(775, 'Modern Data Structures 3rd Edition', 'Jennifer Smith', 'Computer Science', '9781061967275', 'default_cover.png', 2022, 5, 'Available', '2026-05-31 09:55:29'),
(776, 'Applied Data Structures', 'Charles Garcia', 'Computer Science', '9785395637312', 'default_cover.png', 2013, 6, 'Available', '2026-05-31 09:55:29'),
(777, 'Mastering Database Management', 'James Johnson', 'Computer Science', '9782613466635', 'default_cover.png', 2017, 5, 'Available', '2026-05-31 09:55:29'),
(778, 'Fundamentals of Algorithms', 'William Wilson', 'Computer Science', '9782806032621', 'default_cover.png', 2022, 8, 'Available', '2026-05-31 09:55:29'),
(779, 'Principles of Database Management 2nd Edition', 'Michelle Smith', 'Computer Science', '9783028249094', 'default_cover.png', 2014, 3, 'Available', '2026-05-31 09:55:29'),
(780, 'Mastering Operating Systems 4th Edition', 'Daniel Garcia', 'Computer Science', '9782298582334', 'default_cover.png', 2010, 3, 'Available', '2026-05-31 09:55:29'),
(781, 'Modern Compiler Design 3rd Edition', 'Michelle Jones', 'Computer Science', '9789515557966', 'default_cover.png', 2018, 8, 'Available', '2026-05-31 09:55:29'),
(782, 'Advanced Compiler Design 3rd Edition', 'Sarah Davis', 'Computer Science', '9783336371004', 'default_cover.png', 2021, 6, 'Available', '2026-05-31 09:55:29'),
(783, 'Modern Robotics 3rd Edition', 'James Smith', 'AI', '9787027540225', 'default_cover.png', 2013, 2, 'Available', '2026-05-31 09:55:29'),
(784, 'Applied Deep Learning 4th Edition', 'William Rodriguez', 'AI', '9783958964776', 'default_cover.png', 2002, 5, 'Available', '2026-05-31 09:55:29'),
(785, 'Fundamentals of Artificial Intelligence for Engineers', 'James Lopez', 'AI', '9785517620587', 'default_cover.png', 2013, 7, 'Available', '2026-05-31 09:55:29'),
(786, 'Introduction to Robotics for Engineers', 'Ashley Martinez', 'AI', '9781439694937', 'default_cover.png', 2010, 7, 'Available', '2026-05-31 09:55:29'),
(787, 'Advanced Robotics 2nd Edition', 'Michael Smith', 'AI', '9789813773272', 'default_cover.png', 2010, 6, 'Available', '2026-05-31 09:55:29'),
(788, 'Mastering Reinforcement Learning 3rd Edition', 'Michelle Johnson', 'AI', '9788228188319', 'default_cover.png', 2023, 5, 'Available', '2026-05-31 09:55:29'),
(789, 'Advanced Neural Networks for Engineers', 'Jennifer Rodriguez', 'AI', '9785918714637', 'default_cover.png', 2022, 4, 'Available', '2026-05-31 09:55:29'),
(790, 'Principles of Deep Learning for Engineers', 'David Johnson', 'AI', '9789252175898', 'default_cover.png', 2009, 4, 'Available', '2026-05-31 09:55:29'),
(791, 'Principles of Artificial Intelligence 2nd Edition', 'James Davis', 'AI', '9789754801511', 'default_cover.png', 2016, 6, 'Available', '2026-05-31 09:55:29'),
(792, 'Modern Machine Learning in Practice', 'Amanda Gonzalez', 'AI', '9782071614998', 'default_cover.png', 2015, 8, 'Available', '2026-05-31 09:55:29'),
(793, 'Introduction to Natural Language Processing', 'Richard Williams', 'AI', '9786538450141', 'default_cover.png', 2009, 10, 'Available', '2026-05-31 09:55:29'),
(794, 'Principles of Artificial Intelligence for Engineers', 'John Wilson', 'AI', '9781481447876', 'default_cover.png', 2003, 5, 'Available', '2026-05-31 09:55:29'),
(795, 'Applied Artificial Intelligence 3rd Edition', 'James Brown', 'AI', '9781023997211', 'default_cover.png', 2015, 3, 'Available', '2026-05-31 09:55:29'),
(796, 'Mastering Machine Learning in Practice', 'Emily Jones', 'AI', '9783158346678', 'default_cover.png', 2011, 10, 'Available', '2026-05-31 09:55:29'),
(797, 'Principles of Neural Networks for Engineers', 'Sarah Anderson', 'AI', '9787134179157', 'default_cover.png', 2017, 8, 'Available', '2026-05-31 09:55:29'),
(798, 'Applied Deep Learning 2nd Edition', 'Charles Hernandez', 'AI', '9787607254810', 'default_cover.png', 2003, 7, 'Available', '2026-05-31 09:55:29'),
(799, 'Advanced Natural Language Processing 4th Edition', 'Michael Williams', 'AI', '9784420204013', 'default_cover.png', 2021, 8, 'Available', '2026-05-31 09:55:29'),
(800, 'Introduction to Neural Networks', 'Amanda Jones', 'AI', '9787345546818', 'default_cover.png', 2013, 4, 'Available', '2026-05-31 09:55:29'),
(801, 'Modern Machine Learning for Engineers', 'Jessica Gonzalez', 'AI', '9788114969983', 'default_cover.png', 2009, 6, 'Available', '2026-05-31 09:55:29'),
(802, 'Principles of Computer Vision 2nd Edition', 'Lisa Anderson', 'AI', '9784161689332', 'default_cover.png', 2015, 4, 'Available', '2026-05-31 09:55:29'),
(803, 'Mastering Machine Learning 4th Edition', 'William Smith', 'AI', '9782286313685', 'default_cover.png', 2014, 9, 'Available', '2026-05-31 09:55:29'),
(804, 'Principles of Computer Vision for Engineers', 'Jennifer Johnson', 'AI', '9787947480830', 'default_cover.png', 2011, 3, 'Available', '2026-05-31 09:55:29'),
(805, 'Applied Artificial Intelligence 3rd Edition', 'John Smith', 'AI', '9784852385660', 'default_cover.png', 2020, 9, 'Available', '2026-05-31 09:55:29'),
(806, 'Principles of Deep Learning 2nd Edition', 'Robert Garcia', 'AI', '9783816820495', 'default_cover.png', 2003, 9, 'Available', '2026-05-31 09:55:29'),
(807, 'Fundamentals of Machine Learning for Engineers', 'Thomas Williams', 'AI', '9783676701724', 'default_cover.png', 2013, 2, 'Available', '2026-05-31 09:55:29'),
(808, 'Modern Artificial Intelligence 2nd Edition', 'Emily Williams', 'AI', '9788017739189', 'default_cover.png', 2022, 4, 'Available', '2026-05-31 09:55:29'),
(809, 'Fundamentals of Artificial Intelligence 3rd Edition', 'Thomas Davis', 'AI', '9784502186070', 'default_cover.png', 2006, 2, 'Available', '2026-05-31 09:55:29'),
(810, 'Mastering Computer Vision in Practice', 'Emily Jones', 'AI', '9789648452582', 'default_cover.png', 2009, 2, 'Available', '2026-05-31 09:55:29'),
(811, 'Mastering Natural Language Processing 4th Edition', 'Ashley Smith', 'AI', '9784576932626', 'default_cover.png', 2021, 10, 'Available', '2026-05-31 09:55:29'),
(812, 'Introduction to Computer Vision 2nd Edition', 'David Martinez', 'AI', '9784059856950', 'default_cover.png', 2020, 7, 'Available', '2026-05-31 09:55:29'),
(813, 'Advanced Machine Learning 3rd Edition', 'Michael Lopez', 'AI', '9786578747187', 'default_cover.png', 2008, 7, 'Available', '2026-05-31 09:55:29'),
(814, 'Introduction to Artificial Intelligence in Practice', 'Ashley Jones', 'AI', '9784943252172', 'default_cover.png', 2017, 3, 'Available', '2026-05-31 09:55:29'),
(815, 'Principles of Natural Language Processing 4th Edition', 'Daniel Garcia', 'AI', '9785955903902', 'default_cover.png', 2023, 9, 'Available', '2026-05-31 09:55:29'),
(816, 'Advanced Reinforcement Learning 4th Edition', 'John Jones', 'AI', '9781826845065', 'default_cover.png', 2004, 8, 'Available', '2026-05-31 09:55:29'),
(817, 'Principles of Neural Networks 3rd Edition', 'Ashley Anderson', 'AI', '9782938837620', 'default_cover.png', 2014, 8, 'Available', '2026-05-31 09:55:29'),
(818, 'Fundamentals of Artificial Intelligence 4th Edition', 'Michael Lopez', 'AI', '9783749204116', 'default_cover.png', 2002, 10, 'Available', '2026-05-31 09:55:29'),
(819, 'Fundamentals of Neural Networks 2nd Edition', 'John Gonzalez', 'AI', '9788242408820', 'default_cover.png', 2017, 5, 'Available', '2026-05-31 09:55:29'),
(820, 'Applied Artificial Intelligence 4th Edition', 'Sarah Martinez', 'AI', '9785404439920', 'default_cover.png', 2003, 10, 'Available', '2026-05-31 09:55:29'),
(821, 'Applied Reinforcement Learning 2nd Edition', 'Jessica Brown', 'AI', '9783384836316', 'default_cover.png', 2009, 5, 'Available', '2026-05-31 09:55:29'),
(822, 'Modern Robotics in Practice', 'Lisa Gonzalez', 'AI', '9786145791083', 'default_cover.png', 2007, 10, 'Available', '2026-05-31 09:55:29'),
(823, 'Introduction to Reinforcement Learning for Engineers', 'Thomas Davis', 'AI', '9789153739058', 'default_cover.png', 2017, 8, 'Available', '2026-05-31 09:55:29'),
(824, 'Introduction to Robotics in Practice', 'David Williams', 'AI', '9783781732176', 'default_cover.png', 2014, 7, 'Available', '2026-05-31 09:55:29'),
(825, 'Introduction to Artificial Intelligence', 'David Hernandez', 'AI', '9787264334730', 'default_cover.png', 2006, 4, 'Available', '2026-05-31 09:55:29'),
(826, 'Principles of Natural Language Processing 4th Edition', 'Ashley Rodriguez', 'AI', '9782147906877', 'default_cover.png', 2005, 9, 'Available', '2026-05-31 09:55:29'),
(827, 'Introduction to Predictive Modeling 2nd Edition', 'Lisa Johnson', 'Data Science', '9786278687762', 'default_cover.png', 2015, 3, 'Available', '2026-05-31 09:55:29'),
(828, 'Fundamentals of Data Mining', 'Jessica Wilson', 'Data Science', '9783876873336', 'default_cover.png', 2018, 4, 'Available', '2026-05-31 09:55:29'),
(829, 'Applied Big Data Analytics 4th Edition', 'James Martinez', 'Data Science', '9784990657995', 'default_cover.png', 2008, 7, 'Available', '2026-05-31 09:55:29'),
(830, 'Applied Data Mining 4th Edition', 'James Rodriguez', 'Data Science', '9788243521940', 'default_cover.png', 2018, 3, 'Available', '2026-05-31 09:55:29'),
(831, 'Applied Data Science for Engineers', 'Jennifer Brown', 'Data Science', '9784791056696', 'default_cover.png', 2006, 3, 'Available', '2026-05-31 09:55:29'),
(832, 'Modern Statistical Learning in Practice', 'John Rodriguez', 'Data Science', '9787679597158', 'default_cover.png', 2019, 3, 'Available', '2026-05-31 09:55:29'),
(833, 'Modern Data Visualization in Practice', 'Emily Miller', 'Data Science', '9782390489622', 'default_cover.png', 2011, 2, 'Available', '2026-05-31 09:55:29'),
(834, 'Introduction to Statistical Learning', 'William Anderson', 'Data Science', '9783751557427', 'default_cover.png', 2010, 9, 'Available', '2026-05-31 09:55:29'),
(835, 'Modern Predictive Modeling 3rd Edition', 'Emily Hernandez', 'Data Science', '9783993383302', 'default_cover.png', 2005, 2, 'Available', '2026-05-31 09:55:29'),
(836, 'Modern Statistical Learning 3rd Edition', 'Michelle Anderson', 'Data Science', '9788976930763', 'default_cover.png', 2010, 7, 'Available', '2026-05-31 09:55:29'),
(837, 'Mastering Data Science 4th Edition', 'Thomas Johnson', 'Data Science', '9786639360724', 'default_cover.png', 2011, 4, 'Available', '2026-05-31 09:55:29'),
(838, 'Applied Big Data Analytics', 'Jessica Rodriguez', 'Data Science', '9782402672824', 'default_cover.png', 2010, 5, 'Available', '2026-05-31 09:55:29'),
(839, 'Modern Data Science', 'Daniel Miller', 'Data Science', '9787357369392', 'default_cover.png', 2007, 8, 'Available', '2026-05-31 09:55:29'),
(840, 'Principles of Big Data Analytics 2nd Edition', 'Jennifer Gonzalez', 'Data Science', '9784380946528', 'default_cover.png', 2007, 8, 'Available', '2026-05-31 09:55:29'),
(841, 'Advanced Data Visualization', 'Charles Hernandez', 'Data Science', '9782989293039', 'default_cover.png', 2010, 8, 'Available', '2026-05-31 09:55:29'),
(842, 'Applied Big Data Analytics 2nd Edition', 'Jessica Johnson', 'Data Science', '9782629519304', 'default_cover.png', 2003, 10, 'Available', '2026-05-31 09:55:29'),
(843, 'Applied Statistical Learning for Engineers', 'Emily Williams', 'Data Science', '9788864939877', 'default_cover.png', 2011, 9, 'Available', '2026-05-31 09:55:29'),
(844, 'Fundamentals of Big Data Analytics for Engineers', 'Michael Johnson', 'Data Science', '9784785565337', 'default_cover.png', 2023, 5, 'Available', '2026-05-31 09:55:29'),
(845, 'Fundamentals of Data Science 2nd Edition', 'Ashley Martinez', 'Data Science', '9786485169527', 'default_cover.png', 2007, 9, 'Available', '2026-05-31 09:55:29'),
(846, 'Introduction to Predictive Modeling in Practice', 'Robert Johnson', 'Data Science', '9784059132301', 'default_cover.png', 2022, 2, 'Available', '2026-05-31 09:55:29'),
(847, 'Applied Data Visualization 4th Edition', 'Lisa Johnson', 'Data Science', '9783089964685', 'default_cover.png', 2009, 3, 'Available', '2026-05-31 09:55:29'),
(848, 'Principles of Data Science in Practice', 'Charles Gonzalez', 'Data Science', '9785409104986', 'default_cover.png', 2013, 7, 'Available', '2026-05-31 09:55:29'),
(849, 'Modern Time Series Analysis 3rd Edition', 'Daniel Davis', 'Data Science', '9789841463324', 'default_cover.png', 2018, 7, 'Available', '2026-05-31 09:55:29'),
(850, 'Applied Predictive Modeling 4th Edition', 'Amanda Garcia', 'Data Science', '9782851260125', 'default_cover.png', 2000, 8, 'Available', '2026-05-31 09:55:29'),
(851, 'Advanced Time Series Analysis in Practice', 'Emily Brown', 'Data Science', '9786024387443', 'default_cover.png', 2018, 9, 'Available', '2026-05-31 09:55:29'),
(852, 'Introduction to Statistical Learning', 'Daniel Rodriguez', 'Data Science', '9789679934732', 'default_cover.png', 2019, 5, 'Available', '2026-05-31 09:55:29'),
(853, 'Fundamentals of Data Science', 'Thomas Miller', 'Data Science', '9783369116549', 'default_cover.png', 2021, 2, 'Available', '2026-05-31 09:55:29'),
(854, 'Modern Data Science', 'Richard Hernandez', 'Data Science', '9781410364951', 'default_cover.png', 2016, 8, 'Available', '2026-05-31 09:55:29'),
(855, 'Advanced Time Series Analysis', 'David Garcia', 'Data Science', '9789310988049', 'default_cover.png', 2007, 10, 'Available', '2026-05-31 09:55:29'),
(856, 'Principles of Data Science 4th Edition', 'Lisa Hernandez', 'Data Science', '9788149576925', 'default_cover.png', 2018, 3, 'Available', '2026-05-31 09:55:29'),
(857, 'Modern Statistical Learning 4th Edition', 'Michael Lopez', 'Data Science', '9788468956853', 'default_cover.png', 2015, 4, 'Available', '2026-05-31 09:55:29'),
(858, 'Principles of Statistical Learning 3rd Edition', 'James Davis', 'Data Science', '9781400818360', 'default_cover.png', 2007, 6, 'Available', '2026-05-31 09:55:29'),
(859, 'Mastering Data Science 3rd Edition', 'Lisa Rodriguez', 'Data Science', '9789581610753', 'default_cover.png', 2015, 2, 'Available', '2026-05-31 09:55:29'),
(860, 'Mastering Data Mining 4th Edition', 'Emily Johnson', 'Data Science', '9784077849240', 'default_cover.png', 2002, 6, 'Available', '2026-05-31 09:55:29'),
(861, 'Applied Big Data Analytics 2nd Edition', 'Daniel Gonzalez', 'Data Science', '9787424143524', 'default_cover.png', 2014, 3, 'Available', '2026-05-31 09:55:29'),
(862, 'Principles of Big Data Analytics', 'David Jones', 'Data Science', '9785948412631', 'default_cover.png', 2010, 10, 'Available', '2026-05-31 09:55:29'),
(863, 'Mastering Predictive Modeling in Practice', 'Robert Williams', 'Data Science', '9785834632887', 'default_cover.png', 2010, 5, 'Available', '2026-05-31 09:55:29'),
(864, 'Applied Predictive Modeling 3rd Edition', 'Charles Smith', 'Data Science', '9788614182372', 'default_cover.png', 2013, 5, 'Available', '2026-05-31 09:55:29'),
(865, 'Fundamentals of Time Series Analysis for Engineers', 'Thomas Johnson', 'Data Science', '9783037034633', 'default_cover.png', 2013, 8, 'Available', '2026-05-31 09:55:29'),
(866, 'Modern Data Visualization for Engineers', 'Richard Hernandez', 'Data Science', '9781803628355', 'default_cover.png', 2003, 8, 'Available', '2026-05-31 09:55:29'),
(867, 'Modern Predictive Modeling 3rd Edition', 'Jennifer Miller', 'Data Science', '9787412439167', 'default_cover.png', 2008, 9, 'Available', '2026-05-31 09:55:29'),
(868, 'Applied Data Science 3rd Edition', 'David Jones', 'Data Science', '9788756019345', 'default_cover.png', 2003, 4, 'Available', '2026-05-31 09:55:29'),
(869, 'Principles of Data Visualization 4th Edition', 'Ashley Johnson', 'Data Science', '9784619462631', 'default_cover.png', 2006, 7, 'Available', '2026-05-31 09:55:29'),
(870, 'Modern Time Series Analysis for Engineers', 'Amanda Brown', 'Data Science', '9786353922296', 'default_cover.png', 2014, 7, 'Available', '2026-05-31 09:55:29'),
(871, 'Introduction to Frontend Architecture 2nd Edition', 'Robert Rodriguez', 'Web Design and Development', '9783170508406', 'default_cover.png', 2015, 3, 'Available', '2026-05-31 09:55:29'),
(872, 'Principles of Frontend Architecture in Practice', 'Amanda Miller', 'Web Design and Development', '9783374134910', 'default_cover.png', 2016, 10, 'Available', '2026-05-31 09:55:29'),
(873, 'Advanced UI/UX Design 2nd Edition', 'Michael Wilson', 'Web Design and Development', '9786636113336', 'default_cover.png', 2005, 5, 'Available', '2026-05-31 09:55:29'),
(874, 'Applied CSS Grid in Practice', 'Daniel Rodriguez', 'Web Design and Development', '9785481328066', 'default_cover.png', 2003, 9, 'Available', '2026-05-31 09:55:29'),
(875, 'Mastering Frontend Architecture 2nd Edition', 'Michelle Anderson', 'Web Design and Development', '9787796522867', 'default_cover.png', 2000, 2, 'Available', '2026-05-31 09:55:29'),
(876, 'Mastering JavaScript Mastery 3rd Edition', 'Emily Lopez', 'Web Design and Development', '9782574453054', 'default_cover.png', 2011, 5, 'Available', '2026-05-31 09:55:29'),
(877, 'Modern UI/UX Design 2nd Edition', 'Robert Anderson', 'Web Design and Development', '9787084846629', 'default_cover.png', 2005, 9, 'Available', '2026-05-31 09:55:29'),
(878, 'Applied UI/UX Design 3rd Edition', 'Jessica Johnson', 'Web Design and Development', '9786334173630', 'default_cover.png', 2016, 5, 'Available', '2026-05-31 09:55:29'),
(879, 'Advanced React Patterns in Practice', 'William Davis', 'Web Design and Development', '9787268225569', 'default_cover.png', 2000, 5, 'Available', '2026-05-31 09:55:29'),
(880, 'Advanced Web Development in Practice', 'Thomas Martinez', 'Web Design and Development', '9782093589921', 'default_cover.png', 2023, 3, 'Available', '2026-05-31 09:55:29'),
(881, 'Principles of Web Development', 'William Wilson', 'Web Design and Development', '9785355439628', 'default_cover.png', 2021, 5, 'Available', '2026-05-31 09:55:29'),
(882, 'Modern Full Stack Engineering for Engineers', 'Michelle Anderson', 'Web Design and Development', '9784170433075', 'default_cover.png', 2010, 9, 'Available', '2026-05-31 09:55:29'),
(883, 'Modern React Patterns 4th Edition', 'Emily Johnson', 'Web Design and Development', '9782334974816', 'default_cover.png', 2006, 8, 'Available', '2026-05-31 09:55:29'),
(884, 'Applied React Patterns for Engineers', 'Ashley Lopez', 'Web Design and Development', '9783631439747', 'default_cover.png', 2011, 6, 'Available', '2026-05-31 09:55:29'),
(885, 'Mastering CSS Grid 3rd Edition', 'Thomas Brown', 'Web Design and Development', '9788068044912', 'default_cover.png', 2014, 9, 'Available', '2026-05-31 09:55:29'),
(886, 'Principles of Frontend Architecture 4th Edition', 'Sarah Wilson', 'Web Design and Development', '9781003082115', 'default_cover.png', 2020, 2, 'Available', '2026-05-31 09:55:29'),
(887, 'Introduction to JavaScript Mastery 2nd Edition', 'William Brown', 'Web Design and Development', '9785086721327', 'default_cover.png', 2014, 7, 'Available', '2026-05-31 09:55:29'),
(888, 'Fundamentals of Frontend Architecture', 'Robert Wilson', 'Web Design and Development', '9784435906668', 'default_cover.png', 2022, 5, 'Available', '2026-05-31 09:55:29'),
(889, 'Fundamentals of Frontend Architecture', 'Michelle Rodriguez', 'Web Design and Development', '9783913915667', 'default_cover.png', 2011, 4, 'Available', '2026-05-31 09:55:29'),
(890, 'Fundamentals of Web Development for Engineers', 'John Smith', 'Web Design and Development', '9789681036914', 'default_cover.png', 2004, 8, 'Available', '2026-05-31 09:55:29'),
(891, 'Introduction to Full Stack Engineering 2nd Edition', 'Robert Jones', 'Web Design and Development', '9789320137925', 'default_cover.png', 2004, 3, 'Available', '2026-05-31 09:55:29'),
(892, 'Modern React Patterns', 'Daniel Rodriguez', 'Web Design and Development', '9786869472466', 'default_cover.png', 2006, 4, 'Available', '2026-05-31 09:55:29'),
(893, 'Modern JavaScript Mastery in Practice', 'Charles Lopez', 'Web Design and Development', '9785227459200', 'default_cover.png', 2019, 5, 'Available', '2026-05-31 09:55:29'),
(894, 'Advanced React Patterns in Practice', 'James Hernandez', 'Web Design and Development', '9785530911862', 'default_cover.png', 2000, 6, 'Available', '2026-05-31 09:55:29'),
(895, 'Modern UI/UX Design 3rd Edition', 'Lisa Jones', 'Web Design and Development', '9784866613023', 'default_cover.png', 2010, 8, 'Available', '2026-05-31 09:55:29'),
(896, 'Applied Web Development for Engineers', 'John Gonzalez', 'Web Design and Development', '9789343972884', 'default_cover.png', 2017, 7, 'Available', '2026-05-31 09:55:29'),
(897, 'Introduction to Web Development 2nd Edition', 'Ashley Wilson', 'Web Design and Development', '9781568168797', 'default_cover.png', 2002, 6, 'Available', '2026-05-31 09:55:29'),
(898, 'Modern Web Development 4th Edition', 'Lisa Anderson', 'Web Design and Development', '9787519396590', 'default_cover.png', 2013, 6, 'Available', '2026-05-31 09:55:29'),
(899, 'Applied Frontend Architecture 3rd Edition', 'Amanda Garcia', 'Web Design and Development', '9787280859264', 'default_cover.png', 2000, 6, 'Available', '2026-05-31 09:55:29'),
(900, 'Mastering React Patterns 2nd Edition', 'John Jones', 'Web Design and Development', '9783733193632', 'default_cover.png', 2003, 6, 'Available', '2026-05-31 09:55:29'),
(901, 'Fundamentals of Web Development 3rd Edition', 'Michelle Smith', 'Web Design and Development', '9783686543490', 'default_cover.png', 2002, 5, 'Available', '2026-05-31 09:55:29'),
(902, 'Advanced CSS Grid 3rd Edition', 'Daniel Wilson', 'Web Design and Development', '9786216850916', 'default_cover.png', 2015, 4, 'Available', '2026-05-31 09:55:29'),
(903, 'Applied UI/UX Design for Engineers', 'Lisa Martinez', 'Web Design and Development', '9782761973965', 'default_cover.png', 2007, 3, 'Available', '2026-05-31 09:55:29'),
(904, 'Introduction to React Patterns for Engineers', 'Daniel Jones', 'Web Design and Development', '9781681390560', 'default_cover.png', 2005, 5, 'Available', '2026-05-31 09:55:29'),
(905, 'Applied Web Development in Practice', 'Richard Johnson', 'Web Design and Development', '9787774348217', 'default_cover.png', 2007, 4, 'Available', '2026-05-31 09:55:29'),
(906, 'Advanced React Patterns 4th Edition', 'Michael Martinez', 'Web Design and Development', '9788982094889', 'default_cover.png', 2021, 3, 'Available', '2026-05-31 09:55:29'),
(907, 'Applied Full Stack Engineering', 'Ashley Williams', 'Web Design and Development', '9789945807042', 'default_cover.png', 2014, 10, 'Available', '2026-05-31 09:55:29'),
(908, 'Applied Web Development 4th Edition', 'Michelle Lopez', 'Web Design and Development', '9782848838043', 'default_cover.png', 2007, 2, 'Available', '2026-05-31 09:55:29'),
(909, 'Applied Web Development', 'Lisa Gonzalez', 'Web Design and Development', '9781456115500', 'default_cover.png', 2020, 4, 'Available', '2026-05-31 09:55:29'),
(910, 'Modern JavaScript Mastery', 'Michelle Williams', 'Web Design and Development', '9784834642316', 'default_cover.png', 2023, 9, 'Available', '2026-05-31 09:55:29'),
(911, 'Advanced Web Development 3rd Edition', 'Sarah Miller', 'Web Design and Development', '9786119428439', 'default_cover.png', 2012, 8, 'Available', '2026-05-31 09:55:29'),
(912, 'Applied JavaScript Mastery in Practice', 'William Anderson', 'Web Design and Development', '9787039859297', 'default_cover.png', 2004, 7, 'Available', '2026-05-31 09:55:29'),
(913, 'Applied Full Stack Engineering 4th Edition', 'Sarah Rodriguez', 'Web Design and Development', '9785770549567', 'default_cover.png', 2018, 3, 'Available', '2026-05-31 09:55:29'),
(914, 'Mastering JavaScript Mastery 2nd Edition', 'Daniel Smith', 'Web Design and Development', '9782223803081', 'default_cover.png', 2016, 10, 'Available', '2026-05-31 09:55:29'),
(915, 'Advanced Signal Processing for Engineers', 'Ashley Wilson', 'Electrical Engineering', '9787772855296', 'default_cover.png', 2000, 6, 'Available', '2026-05-31 09:55:29'),
(916, 'Applied Microelectronics 3rd Edition', 'Sarah Brown', 'Electrical Engineering', '9787875319462', 'default_cover.png', 2022, 5, 'Available', '2026-05-31 09:55:29'),
(917, 'Modern Digital Logic 3rd Edition', 'Robert Rodriguez', 'Electrical Engineering', '9781822419192', 'default_cover.png', 2012, 2, 'Available', '2026-05-31 09:55:29'),
(918, 'Mastering Circuits', 'John Gonzalez', 'Electrical Engineering', '9783121032680', 'default_cover.png', 2022, 9, 'Available', '2026-05-31 09:55:29'),
(919, 'Modern Circuits 4th Edition', 'James Jones', 'Electrical Engineering', '9782869941841', 'default_cover.png', 2010, 4, 'Available', '2026-05-31 09:55:29'),
(920, 'Advanced Power Systems', 'Jennifer Anderson', 'Electrical Engineering', '9786623121547', 'default_cover.png', 2008, 9, 'Available', '2026-05-31 09:55:29'),
(921, 'Principles of Circuits for Engineers', 'James Miller', 'Electrical Engineering', '9781607020189', 'default_cover.png', 2023, 2, 'Available', '2026-05-31 09:55:29'),
(922, 'Advanced Electromagnetics 4th Edition', 'Charles Jones', 'Electrical Engineering', '9783780019303', 'default_cover.png', 2006, 10, 'Available', '2026-05-31 09:55:29'),
(923, 'Principles of Signal Processing 4th Edition', 'Thomas Garcia', 'Electrical Engineering', '9783872893441', 'default_cover.png', 2011, 6, 'Available', '2026-05-31 09:55:29'),
(924, 'Principles of Microelectronics 2nd Edition', 'Robert Jones', 'Electrical Engineering', '9786307768166', 'default_cover.png', 2005, 10, 'Available', '2026-05-31 09:55:29'),
(925, 'Mastering Power Systems 2nd Edition', 'Michael Martinez', 'Electrical Engineering', '9783170159121', 'default_cover.png', 2003, 2, 'Available', '2026-05-31 09:55:29'),
(926, 'Applied Electromagnetics in Practice', 'David Davis', 'Electrical Engineering', '9784098596243', 'default_cover.png', 2017, 8, 'Available', '2026-05-31 09:55:29'),
(927, 'Mastering Electromagnetics for Engineers', 'Jennifer Garcia', 'Electrical Engineering', '9789381767974', 'default_cover.png', 2004, 8, 'Available', '2026-05-31 09:55:29'),
(928, 'Advanced Power Systems for Engineers', 'Michelle Brown', 'Electrical Engineering', '9785410463265', 'default_cover.png', 2020, 10, 'Available', '2026-05-31 09:55:29'),
(929, 'Applied Microelectronics 4th Edition', 'Richard Anderson', 'Electrical Engineering', '9782888523184', 'default_cover.png', 2001, 3, 'Available', '2026-05-31 09:55:29'),
(930, 'Applied Control Systems for Engineers', 'David Lopez', 'Electrical Engineering', '9781303778675', 'default_cover.png', 2018, 2, 'Available', '2026-05-31 09:55:29'),
(931, 'Advanced Circuits 3rd Edition', 'Thomas Jones', 'Electrical Engineering', '9785060145986', 'default_cover.png', 2008, 4, 'Available', '2026-05-31 09:55:29'),
(932, 'Introduction to Power Systems 4th Edition', 'Jessica Gonzalez', 'Electrical Engineering', '9784427082573', 'default_cover.png', 2016, 7, 'Available', '2026-05-31 09:55:29'),
(933, 'Advanced Control Systems 3rd Edition', 'James Anderson', 'Electrical Engineering', '9789171157129', 'default_cover.png', 2007, 7, 'Available', '2026-05-31 09:55:29'),
(934, 'Fundamentals of Signal Processing 4th Edition', 'William Hernandez', 'Electrical Engineering', '9786631340615', 'default_cover.png', 2012, 6, 'Available', '2026-05-31 09:55:29'),
(935, 'Modern Microelectronics in Practice', 'Emily Lopez', 'Electrical Engineering', '9781250003348', 'default_cover.png', 2013, 10, 'Available', '2026-05-31 09:55:29'),
(936, 'Modern Signal Processing 3rd Edition', 'Lisa Garcia', 'Electrical Engineering', '9782599357286', 'default_cover.png', 2020, 8, 'Available', '2026-05-31 09:55:29'),
(937, 'Fundamentals of Electromagnetics 2nd Edition', 'Richard Martinez', 'Electrical Engineering', '9786703183695', 'default_cover.png', 2023, 8, 'Available', '2026-05-31 09:55:29'),
(938, 'Mastering Signal Processing for Engineers', 'William Miller', 'Electrical Engineering', '9786933601982', 'default_cover.png', 2017, 6, 'Available', '2026-05-31 09:55:29'),
(939, 'Introduction to Signal Processing 4th Edition', 'Robert Williams', 'Electrical Engineering', '9786464215579', 'default_cover.png', 2005, 4, 'Available', '2026-05-31 09:55:29'),
(940, 'Applied Control Systems for Engineers', 'John Hernandez', 'Electrical Engineering', '9789139231344', 'default_cover.png', 2005, 5, 'Available', '2026-05-31 09:55:29'),
(941, 'Applied Electromagnetics in Practice', 'Michelle Gonzalez', 'Electrical Engineering', '9785815497873', 'default_cover.png', 2002, 2, 'Available', '2026-05-31 09:55:29'),
(942, 'Applied Control Systems 3rd Edition', 'Ashley Jones', 'Electrical Engineering', '9786447649923', 'default_cover.png', 2012, 6, 'Available', '2026-05-31 09:55:29'),
(943, 'Introduction to Control Systems 3rd Edition', 'Daniel Johnson', 'Electrical Engineering', '9787196377725', 'default_cover.png', 2017, 10, 'Available', '2026-05-31 09:55:29'),
(944, 'Advanced Microelectronics 2nd Edition', 'Emily Garcia', 'Electrical Engineering', '9781266087823', 'default_cover.png', 2012, 3, 'Available', '2026-05-31 09:55:29'),
(945, 'Fundamentals of Microelectronics 2nd Edition', 'Charles Martinez', 'Electrical Engineering', '9782814889569', 'default_cover.png', 2021, 9, 'Available', '2026-05-31 09:55:29'),
(946, 'Applied Circuits for Engineers', 'Michael Brown', 'Electrical Engineering', '9787646879330', 'default_cover.png', 2008, 2, 'Available', '2026-05-31 09:55:29'),
(947, 'Fundamentals of Power Systems for Engineers', 'Thomas Hernandez', 'Electrical Engineering', '9781044237520', 'default_cover.png', 2007, 8, 'Available', '2026-05-31 09:55:29'),
(948, 'Modern Control Systems 2nd Edition', 'John Rodriguez', 'Electrical Engineering', '9784047130416', 'default_cover.png', 2011, 6, 'Available', '2026-05-31 09:55:29'),
(949, 'Advanced Digital Logic 2nd Edition', 'Charles Smith', 'Electrical Engineering', '9787750827425', 'default_cover.png', 2013, 10, 'Available', '2026-05-31 09:55:29'),
(950, 'Principles of Digital Logic 3rd Edition', 'Lisa Johnson', 'Electrical Engineering', '9789877646402', 'default_cover.png', 2006, 3, 'Available', '2026-05-31 09:55:29'),
(951, 'Introduction to Signal Processing', 'Daniel Miller', 'Electrical Engineering', '9784532481032', 'default_cover.png', 2007, 6, 'Available', '2026-05-31 09:55:29'),
(952, 'Advanced Digital Logic in Practice', 'Michael Wilson', 'Electrical Engineering', '9787323757620', 'default_cover.png', 2003, 4, 'Available', '2026-05-31 09:55:29'),
(953, 'Advanced Power Systems 3rd Edition', 'Charles Martinez', 'Electrical Engineering', '9788087155561', 'default_cover.png', 2009, 5, 'Available', '2026-05-31 09:55:29'),
(954, 'Modern Digital Logic in Practice', 'Michelle Anderson', 'Electrical Engineering', '9789695314252', 'default_cover.png', 2013, 4, 'Available', '2026-05-31 09:55:29'),
(955, 'Fundamentals of Digital Logic 2nd Edition', 'Lisa Wilson', 'Electrical Engineering', '9783892598656', 'default_cover.png', 2011, 4, 'Available', '2026-05-31 09:55:29'),
(956, 'Modern Electromagnetics in Practice', 'Jennifer Anderson', 'Electrical Engineering', '9788982207763', 'default_cover.png', 2013, 9, 'Available', '2026-05-31 09:55:29'),
(957, 'Fundamentals of Microelectronics 2nd Edition', 'Charles Davis', 'Electrical Engineering', '9786053235032', 'default_cover.png', 2012, 4, 'Available', '2026-05-31 09:55:29'),
(958, 'Introduction to Control Systems 2nd Edition', 'James Garcia', 'Electrical Engineering', '9784916077109', 'default_cover.png', 2015, 9, 'Available', '2026-05-31 09:55:29'),
(959, 'Fundamentals of Machine Design in Practice', 'Michelle Hernandez', 'Mechanical Engineering', '9784345612695', 'default_cover.png', 2013, 9, 'Available', '2026-05-31 09:55:29'),
(960, 'Advanced Dynamics in Practice', 'Daniel Garcia', 'Mechanical Engineering', '9784825782018', 'default_cover.png', 2005, 2, 'Available', '2026-05-31 09:55:29'),
(961, 'Principles of Machine Design 4th Edition', 'Jennifer Wilson', 'Mechanical Engineering', '9785075732382', 'default_cover.png', 2005, 9, 'Available', '2026-05-31 09:55:29'),
(962, 'Principles of Heat Transfer 2nd Edition', 'Amanda Davis', 'Mechanical Engineering', '9785934708533', 'default_cover.png', 2011, 10, 'Available', '2026-05-31 09:55:29'),
(963, 'Principles of Thermodynamics', 'Sarah Jones', 'Mechanical Engineering', '9786198575314', 'default_cover.png', 2021, 3, 'Available', '2026-05-31 09:55:29'),
(964, 'Mastering Machine Design 3rd Edition', 'Ashley Hernandez', 'Mechanical Engineering', '9785370283997', 'default_cover.png', 2013, 3, 'Available', '2026-05-31 09:55:29'),
(965, 'Fundamentals of Machine Design 2nd Edition', 'Amanda Lopez', 'Mechanical Engineering', '9781188334825', 'default_cover.png', 2004, 2, 'Available', '2026-05-31 09:55:29'),
(966, 'Introduction to Machine Design', 'Thomas Lopez', 'Mechanical Engineering', '9784880009179', 'default_cover.png', 2000, 10, 'Available', '2026-05-31 09:55:29'),
(967, 'Fundamentals of Thermodynamics 3rd Edition', 'William Miller', 'Mechanical Engineering', '9788525102967', 'default_cover.png', 2011, 7, 'Available', '2026-05-31 09:55:29'),
(968, 'Advanced Dynamics in Practice', 'James Anderson', 'Mechanical Engineering', '9788086321856', 'default_cover.png', 2008, 4, 'Available', '2026-05-31 09:55:29'),
(969, 'Fundamentals of Machine Design', 'Daniel Brown', 'Mechanical Engineering', '9788773347430', 'default_cover.png', 2004, 2, 'Available', '2026-05-31 09:55:29'),
(970, 'Principles of Fluid Mechanics in Practice', 'Jessica Gonzalez', 'Mechanical Engineering', '9783726802090', 'default_cover.png', 2014, 8, 'Available', '2026-05-31 09:55:29'),
(971, 'Applied Fluid Mechanics in Practice', 'Sarah Martinez', 'Mechanical Engineering', '9782241341183', 'default_cover.png', 2016, 4, 'Available', '2026-05-31 09:55:29'),
(972, 'Fundamentals of Machine Design', 'James Lopez', 'Mechanical Engineering', '9788508737715', 'default_cover.png', 2000, 3, 'Available', '2026-05-31 09:55:29'),
(973, 'Fundamentals of Thermodynamics 4th Edition', 'James Lopez', 'Mechanical Engineering', '9781013711197', 'default_cover.png', 2007, 8, 'Available', '2026-05-31 09:55:29'),
(974, 'Advanced Fluid Mechanics 2nd Edition', 'Daniel Brown', 'Mechanical Engineering', '9785238406115', 'default_cover.png', 2005, 9, 'Available', '2026-05-31 09:55:29'),
(975, 'Advanced Solid Mechanics 3rd Edition', 'Jessica Jones', 'Mechanical Engineering', '9786241326841', 'default_cover.png', 2009, 8, 'Available', '2026-05-31 09:55:29'),
(976, 'Advanced Machine Design in Practice', 'William Lopez', 'Mechanical Engineering', '9785518357222', 'default_cover.png', 2011, 3, 'Available', '2026-05-31 09:55:29'),
(977, 'Modern Heat Transfer in Practice', 'Jessica Gonzalez', 'Mechanical Engineering', '9783485638283', 'default_cover.png', 2016, 2, 'Available', '2026-05-31 09:55:29'),
(978, 'Principles of Fluid Mechanics for Engineers', 'Amanda Wilson', 'Mechanical Engineering', '9784660676301', 'default_cover.png', 2010, 10, 'Available', '2026-05-31 09:55:29'),
(979, 'Advanced Heat Transfer 2nd Edition', 'Jessica Smith', 'Mechanical Engineering', '9783736271217', 'default_cover.png', 2006, 6, 'Available', '2026-05-31 09:55:29'),
(980, 'Fundamentals of Fluid Mechanics 2nd Edition', 'Thomas Wilson', 'Mechanical Engineering', '9788144128564', 'default_cover.png', 2015, 10, 'Available', '2026-05-31 09:55:29'),
(981, 'Fundamentals of Thermodynamics', 'Daniel Davis', 'Mechanical Engineering', '9782654630361', 'default_cover.png', 2014, 4, 'Available', '2026-05-31 09:55:29'),
(982, 'Advanced Dynamics for Engineers', 'Michael Garcia', 'Mechanical Engineering', '9787217410472', 'default_cover.png', 2000, 2, 'Available', '2026-05-31 09:55:29'),
(983, 'Modern Dynamics', 'Jennifer Gonzalez', 'Mechanical Engineering', '9788821899014', 'default_cover.png', 2021, 8, 'Available', '2026-05-31 09:55:29'),
(984, 'Principles of Robotics in Practice', 'Jessica Hernandez', 'Mechanical Engineering', '9784393466755', 'default_cover.png', 2007, 8, 'Available', '2026-05-31 09:55:29'),
(985, 'Applied Thermodynamics 2nd Edition', 'Jessica Miller', 'Mechanical Engineering', '9788227177533', 'default_cover.png', 2004, 9, 'Available', '2026-05-31 09:55:29'),
(986, 'Advanced Machine Design for Engineers', 'Lisa Williams', 'Mechanical Engineering', '9783849641642', 'default_cover.png', 2010, 9, 'Available', '2026-05-31 09:55:29'),
(987, 'Principles of Heat Transfer in Practice', 'Ashley Wilson', 'Mechanical Engineering', '9784024457855', 'default_cover.png', 2004, 3, 'Available', '2026-05-31 09:55:29'),
(988, 'Principles of Fluid Mechanics 3rd Edition', 'Richard Smith', 'Mechanical Engineering', '9789495197440', 'default_cover.png', 2004, 4, 'Available', '2026-05-31 09:55:29'),
(989, 'Principles of Robotics 3rd Edition', 'Michelle Davis', 'Mechanical Engineering', '9784089432640', 'default_cover.png', 2023, 6, 'Available', '2026-05-31 09:55:29'),
(990, 'Introduction to Dynamics 2nd Edition', 'Lisa Davis', 'Mechanical Engineering', '9781445151506', 'default_cover.png', 2017, 10, 'Available', '2026-05-31 09:55:29'),
(991, 'Applied Dynamics 3rd Edition', 'Ashley Jones', 'Mechanical Engineering', '9784079353221', 'default_cover.png', 2020, 8, 'Available', '2026-05-31 09:55:29'),
(992, 'Mastering Thermodynamics for Engineers', 'Emily Lopez', 'Mechanical Engineering', '9784033258447', 'default_cover.png', 2015, 4, 'Available', '2026-05-31 09:55:29'),
(993, 'Modern Fluid Mechanics in Practice', 'Michelle Johnson', 'Mechanical Engineering', '9781226635774', 'default_cover.png', 2010, 6, 'Available', '2026-05-31 09:55:29'),
(994, 'Principles of Thermodynamics 3rd Edition', 'Michael Johnson', 'Mechanical Engineering', '9782936842032', 'default_cover.png', 2008, 5, 'Available', '2026-05-31 09:55:29'),
(995, 'Principles of Robotics 3rd Edition', 'David Garcia', 'Mechanical Engineering', '9782031760868', 'default_cover.png', 2020, 2, 'Available', '2026-05-31 09:55:29'),
(996, 'Principles of Heat Transfer', 'Sarah Johnson', 'Mechanical Engineering', '9783055640688', 'default_cover.png', 2004, 4, 'Available', '2026-05-31 09:55:29'),
(997, 'Principles of Thermodynamics 3rd Edition', 'Jennifer Williams', 'Mechanical Engineering', '9783329910638', 'default_cover.png', 2019, 6, 'Available', '2026-05-31 09:55:29'),
(998, 'Fundamentals of Solid Mechanics in Practice', 'Lisa Johnson', 'Mechanical Engineering', '9786768855993', 'default_cover.png', 2012, 2, 'Available', '2026-05-31 09:55:29'),
(999, 'Applied Fluid Mechanics for Engineers', 'Ashley Brown', 'Mechanical Engineering', '9788617008838', 'default_cover.png', 2000, 2, 'Available', '2026-05-31 09:55:29'),
(1000, 'Fundamentals of Machine Design for Engineers', 'Jennifer Smith', 'Mechanical Engineering', '9789920352485', 'default_cover.png', 2007, 10, 'Available', '2026-05-31 09:55:29'),
(1001, 'Principles of Fluid Mechanics for Engineers', 'Michael Anderson', 'Mechanical Engineering', '9781427834027', 'default_cover.png', 2008, 6, 'Available', '2026-05-31 09:55:29'),
(1002, 'Fundamentals of Solid Mechanics for Engineers', 'James Wilson', 'Mechanical Engineering', '9787729197256', 'default_cover.png', 2017, 2, 'Available', '2026-05-31 09:55:29'),
(1003, 'Principles of Transportation Systems in Practice', 'Michael Lopez', 'Civil Engineering', '9783710356799', 'default_cover.png', 2004, 10, 'Available', '2026-05-31 09:55:29'),
(1004, 'Principles of Surveying 4th Edition', 'Michelle Anderson', 'Civil Engineering', '9786280465791', 'default_cover.png', 2014, 6, 'Available', '2026-05-31 09:55:29'),
(1005, 'Fundamentals of Surveying for Engineers', 'Daniel Jones', 'Civil Engineering', '9789980314371', 'default_cover.png', 2008, 9, 'Available', '2026-05-31 09:55:29'),
(1006, 'Fundamentals of Concrete Design 2nd Edition', 'Michelle Anderson', 'Civil Engineering', '9781467915879', 'default_cover.png', 2009, 8, 'Available', '2026-05-31 09:55:29'),
(1007, 'Mastering Structural Analysis 2nd Edition', 'William Martinez', 'Civil Engineering', '9787864986484', 'default_cover.png', 2015, 7, 'Available', '2026-05-31 09:55:29'),
(1008, 'Mastering Surveying in Practice', 'Michelle Wilson', 'Civil Engineering', '9787334897948', 'default_cover.png', 2013, 7, 'Available', '2026-05-31 09:55:29'),
(1009, 'Introduction to Geotechnical Engineering in Practice', 'John Lopez', 'Civil Engineering', '9789649481746', 'default_cover.png', 2007, 3, 'Available', '2026-05-31 09:55:29'),
(1010, 'Applied Concrete Design in Practice', 'Ashley Wilson', 'Civil Engineering', '9781320792061', 'default_cover.png', 2014, 6, 'Available', '2026-05-31 09:55:29'),
(1011, 'Applied Environmental Engineering for Engineers', 'Richard Lopez', 'Civil Engineering', '9782641519884', 'default_cover.png', 2010, 7, 'Available', '2026-05-31 09:55:29'),
(1012, 'Principles of Geotechnical Engineering 4th Edition', 'Richard Hernandez', 'Civil Engineering', '9786777612639', 'default_cover.png', 2012, 5, 'Available', '2026-05-31 09:55:29'),
(1013, 'Mastering Geotechnical Engineering 4th Edition', 'Thomas Davis', 'Civil Engineering', '9784536567041', 'default_cover.png', 2002, 3, 'Available', '2026-05-31 09:55:29'),
(1014, 'Applied Geotechnical Engineering', 'Richard Johnson', 'Civil Engineering', '9783015426447', 'default_cover.png', 2004, 6, 'Available', '2026-05-31 09:55:29'),
(1015, 'Mastering Geotechnical Engineering 3rd Edition', 'Jessica Jones', 'Civil Engineering', '9786285052037', 'default_cover.png', 2022, 9, 'Available', '2026-05-31 09:55:29'),
(1016, 'Modern Structural Analysis in Practice', 'William Hernandez', 'Civil Engineering', '9787044478545', 'default_cover.png', 2003, 3, 'Available', '2026-05-31 09:55:29'),
(1017, 'Applied Concrete Design in Practice', 'Robert Miller', 'Civil Engineering', '9786267695733', 'default_cover.png', 2022, 6, 'Available', '2026-05-31 09:55:29'),
(1018, 'Advanced Environmental Engineering', 'Michelle Rodriguez', 'Civil Engineering', '9786753882280', 'default_cover.png', 2003, 5, 'Available', '2026-05-31 09:55:29'),
(1019, 'Advanced Transportation Systems in Practice', 'Jessica Miller', 'Civil Engineering', '9788223456406', 'default_cover.png', 2013, 2, 'Available', '2026-05-31 09:55:29'),
(1020, 'Modern Structural Analysis', 'Amanda Anderson', 'Civil Engineering', '9783418279807', 'default_cover.png', 2019, 2, 'Available', '2026-05-31 09:55:29'),
(1021, 'Mastering Transportation Systems in Practice', 'Jessica Hernandez', 'Civil Engineering', '9789600536042', 'default_cover.png', 2009, 5, 'Available', '2026-05-31 09:55:29'),
(1022, 'Introduction to Structural Analysis 4th Edition', 'Charles Gonzalez', 'Civil Engineering', '9787577587054', 'default_cover.png', 2020, 5, 'Available', '2026-05-31 09:55:29'),
(1023, 'Fundamentals of Surveying 2nd Edition', 'Daniel Lopez', 'Civil Engineering', '9789092635820', 'default_cover.png', 2009, 8, 'Available', '2026-05-31 09:55:29'),
(1024, 'Modern Surveying in Practice', 'James Jones', 'Civil Engineering', '9787976902126', 'default_cover.png', 2020, 5, 'Available', '2026-05-31 09:55:29'),
(1025, 'Applied Concrete Design', 'Daniel Rodriguez', 'Civil Engineering', '9788153350650', 'default_cover.png', 2005, 4, 'Available', '2026-05-31 09:55:29'),
(1026, 'Introduction to Surveying in Practice', 'Daniel Anderson', 'Civil Engineering', '9782738870824', 'default_cover.png', 2004, 5, 'Available', '2026-05-31 09:55:29'),
(1027, 'Advanced Transportation Systems for Engineers', 'Daniel Hernandez', 'Civil Engineering', '9782302019293', 'default_cover.png', 2021, 2, 'Available', '2026-05-31 09:55:29'),
(1028, 'Modern Environmental Engineering', 'Amanda Miller', 'Civil Engineering', '9781618520463', 'default_cover.png', 2019, 10, 'Available', '2026-05-31 09:55:29'),
(1029, 'Applied Transportation Systems for Engineers', 'John Lopez', 'Civil Engineering', '9785214437427', 'default_cover.png', 2001, 2, 'Available', '2026-05-31 09:55:29'),
(1030, 'Introduction to Transportation Systems 2nd Edition', 'Thomas Miller', 'Civil Engineering', '9786038395015', 'default_cover.png', 2019, 8, 'Available', '2026-05-31 09:55:29'),
(1031, 'Modern Concrete Design', 'William Garcia', 'Civil Engineering', '9783444535687', 'default_cover.png', 2000, 10, 'Available', '2026-05-31 09:55:29'),
(1032, 'Advanced Structural Analysis in Practice', 'Daniel Miller', 'Civil Engineering', '9783740201607', 'default_cover.png', 2008, 2, 'Available', '2026-05-31 09:55:29'),
(1033, 'Modern Surveying', 'Charles Davis', 'Civil Engineering', '9784583877800', 'default_cover.png', 2014, 7, 'Available', '2026-05-31 09:55:29'),
(1034, 'Fundamentals of Surveying', 'Robert Jones', 'Civil Engineering', '9787631579967', 'default_cover.png', 2023, 5, 'Available', '2026-05-31 09:55:29');
INSERT INTO `books` (`id`, `title`, `author`, `category`, `isbn`, `cover_image`, `published_year`, `quantity`, `status`, `created_at`) VALUES
(1035, 'Applied Environmental Engineering for Engineers', 'Daniel Brown', 'Civil Engineering', '9789302345805', 'default_cover.png', 2010, 7, 'Available', '2026-05-31 09:55:29'),
(1036, 'Mastering Transportation Systems 2nd Edition', 'Thomas Smith', 'Civil Engineering', '9789435946156', 'default_cover.png', 2011, 8, 'Available', '2026-05-31 09:55:29'),
(1037, 'Modern Geotechnical Engineering', 'Sarah Jones', 'Civil Engineering', '9784819518598', 'default_cover.png', 2007, 4, 'Available', '2026-05-31 09:55:29'),
(1038, 'Principles of Structural Analysis', 'Ashley Smith', 'Civil Engineering', '9783152979041', 'default_cover.png', 2014, 4, 'Available', '2026-05-31 09:55:29'),
(1039, 'Principles of Surveying 3rd Edition', 'Sarah Davis', 'Civil Engineering', '9784082846804', 'default_cover.png', 2015, 4, 'Available', '2026-05-31 09:55:29'),
(1040, 'Fundamentals of Structural Analysis 3rd Edition', 'Daniel Martinez', 'Civil Engineering', '9781217686819', 'default_cover.png', 2020, 5, 'Available', '2026-05-31 09:55:29'),
(1041, 'Fundamentals of Surveying', 'Michael Brown', 'Civil Engineering', '9784152073484', 'default_cover.png', 2009, 9, 'Available', '2026-05-31 09:55:29'),
(1042, 'Mastering Transportation Systems in Practice', 'Michelle Gonzalez', 'Civil Engineering', '9781516667848', 'default_cover.png', 2012, 8, 'Available', '2026-05-31 09:55:29'),
(1043, 'Fundamentals of Geotechnical Engineering', 'Daniel Johnson', 'Civil Engineering', '9787734406707', 'default_cover.png', 2020, 3, 'Available', '2026-05-31 09:55:29'),
(1044, 'Mastering Geotechnical Engineering 3rd Edition', 'Charles Smith', 'Civil Engineering', '9785943865237', 'default_cover.png', 2005, 7, 'Available', '2026-05-31 09:55:29'),
(1045, 'Applied Transportation Systems in Practice', 'Michelle Miller', 'Civil Engineering', '9787295975135', 'default_cover.png', 2010, 9, 'Available', '2026-05-31 09:55:29'),
(1046, 'Introduction to Surveying', 'Richard Jones', 'Civil Engineering', '9789127999402', 'default_cover.png', 2014, 8, 'Available', '2026-05-31 09:55:29'),
(1047, 'Fundamentals of Digital Communications for Engineers', 'John Martinez', 'Telecommunication', '9788685044322', 'default_cover.png', 2004, 8, 'Available', '2026-05-31 09:55:29'),
(1048, 'Introduction to Satellite Communications 4th Edition', 'John Williams', 'Telecommunication', '9784649967503', 'default_cover.png', 2015, 3, 'Available', '2026-05-31 09:55:29'),
(1049, 'Applied Microwave Engineering 3rd Edition', 'Michelle Martinez', 'Telecommunication', '9788418620604', 'default_cover.png', 2020, 5, 'Available', '2026-05-31 09:55:29'),
(1050, 'Mastering Antenna Theory 4th Edition', 'Michelle Jones', 'Telecommunication', '9783703484737', 'default_cover.png', 2006, 5, 'Available', '2026-05-31 09:55:29'),
(1051, 'Mastering Optical Fiber 2nd Edition', 'Sarah Anderson', 'Telecommunication', '9783886556884', 'default_cover.png', 2003, 4, 'Available', '2026-05-31 09:55:29'),
(1052, 'Fundamentals of Digital Communications', 'John Smith', 'Telecommunication', '9785478813585', 'default_cover.png', 2013, 10, 'Available', '2026-05-31 09:55:29'),
(1053, 'Mastering Microwave Engineering 2nd Edition', 'William Williams', 'Telecommunication', '9787866810620', 'default_cover.png', 2000, 2, 'Available', '2026-05-31 09:55:29'),
(1054, 'Fundamentals of Wireless Networks for Engineers', 'Ashley Jones', 'Telecommunication', '9781386410488', 'default_cover.png', 2001, 6, 'Available', '2026-05-31 09:55:29'),
(1055, 'Mastering Digital Communications 4th Edition', 'David Hernandez', 'Telecommunication', '9788442615766', 'default_cover.png', 2013, 6, 'Available', '2026-05-31 09:55:29'),
(1056, 'Introduction to Optical Fiber 3rd Edition', 'Richard Gonzalez', 'Telecommunication', '9787911501839', 'default_cover.png', 2001, 2, 'Available', '2026-05-31 09:55:29'),
(1057, 'Mastering Antenna Theory 2nd Edition', 'Sarah Gonzalez', 'Telecommunication', '9781521289510', 'default_cover.png', 2004, 10, 'Available', '2026-05-31 09:55:29'),
(1058, 'Principles of Wireless Networks in Practice', 'Thomas Johnson', 'Telecommunication', '9787271309076', 'default_cover.png', 2009, 9, 'Available', '2026-05-31 09:55:29'),
(1059, 'Principles of Digital Communications in Practice', 'Emily Wilson', 'Telecommunication', '9789939006040', 'default_cover.png', 2006, 5, 'Available', '2026-05-31 09:55:29'),
(1060, 'Modern Microwave Engineering in Practice', 'Jennifer Brown', 'Telecommunication', '9787284744187', 'default_cover.png', 2022, 8, 'Available', '2026-05-31 09:55:29'),
(1061, 'Principles of Antenna Theory 4th Edition', 'Michelle Rodriguez', 'Telecommunication', '9784414764639', 'default_cover.png', 2009, 7, 'Available', '2026-05-31 09:55:29'),
(1062, 'Introduction to Optical Fiber 4th Edition', 'Jennifer Smith', 'Telecommunication', '9783267491494', 'default_cover.png', 2003, 6, 'Available', '2026-05-31 09:55:29'),
(1063, 'Applied Optical Fiber', 'James Johnson', 'Telecommunication', '9788535275093', 'default_cover.png', 2016, 9, 'Available', '2026-05-31 09:55:29'),
(1064, 'Fundamentals of Satellite Communications 3rd Edition', 'Robert Martinez', 'Telecommunication', '9785004270759', 'default_cover.png', 2012, 5, 'Available', '2026-05-31 09:55:29'),
(1065, 'Modern Microwave Engineering in Practice', 'Michelle Johnson', 'Telecommunication', '9786533279005', 'default_cover.png', 2019, 9, 'Available', '2026-05-31 09:55:29'),
(1066, 'Principles of Wireless Networks in Practice', 'Daniel Davis', 'Telecommunication', '9787182355695', 'default_cover.png', 2006, 2, 'Available', '2026-05-31 09:55:29'),
(1067, 'Introduction to Wireless Networks', 'Michelle Rodriguez', 'Telecommunication', '9781935832774', 'default_cover.png', 2004, 9, 'Available', '2026-05-31 09:55:29'),
(1068, 'Principles of Wireless Networks 2nd Edition', 'Jennifer Jones', 'Telecommunication', '9782119456070', 'default_cover.png', 2006, 4, 'Available', '2026-05-31 09:55:29'),
(1069, 'Mastering Wireless Networks', 'Emily Smith', 'Telecommunication', '9785296076755', 'default_cover.png', 2016, 8, 'Available', '2026-05-31 09:55:29'),
(1070, 'Mastering Microwave Engineering 4th Edition', 'Robert Wilson', 'Telecommunication', '9789978249247', 'default_cover.png', 2016, 6, 'Available', '2026-05-31 09:55:29'),
(1071, 'Applied Optical Fiber in Practice', 'David Brown', 'Telecommunication', '9785118150689', 'default_cover.png', 2007, 4, 'Available', '2026-05-31 09:55:29'),
(1072, 'Mastering Optical Fiber in Practice', 'Michael Lopez', 'Telecommunication', '9786583284202', 'default_cover.png', 2015, 8, 'Available', '2026-05-31 09:55:29'),
(1073, 'Fundamentals of Digital Communications 3rd Edition', 'Richard Brown', 'Telecommunication', '9782881184623', 'default_cover.png', 2008, 2, 'Available', '2026-05-31 09:55:29'),
(1074, 'Introduction to Wireless Networks 2nd Edition', 'Robert Lopez', 'Telecommunication', '9784686554420', 'default_cover.png', 2006, 5, 'Available', '2026-05-31 09:55:29'),
(1075, 'Advanced Wireless Networks for Engineers', 'Jessica Smith', 'Telecommunication', '9782354426833', 'default_cover.png', 2023, 10, 'Available', '2026-05-31 09:55:29'),
(1076, 'Fundamentals of Antenna Theory', 'David Jones', 'Telecommunication', '9787620567849', 'default_cover.png', 2019, 3, 'Available', '2026-05-31 09:55:29'),
(1077, 'Applied Digital Communications for Engineers', 'Jennifer Brown', 'Telecommunication', '9784079494688', 'default_cover.png', 2008, 6, 'Available', '2026-05-31 09:55:29'),
(1078, 'Introduction to Wireless Networks', 'David Wilson', 'Telecommunication', '9789924516661', 'default_cover.png', 2023, 5, 'Available', '2026-05-31 09:55:29'),
(1079, 'Advanced Optical Fiber 3rd Edition', 'Emily Wilson', 'Telecommunication', '9786401615160', 'default_cover.png', 2017, 10, 'Available', '2026-05-31 09:55:29'),
(1080, 'Modern Antenna Theory in Practice', 'Michelle Martinez', 'Telecommunication', '9786740407732', 'default_cover.png', 2003, 7, 'Available', '2026-05-31 09:55:29'),
(1081, 'Advanced Digital Communications', 'Richard Rodriguez', 'Telecommunication', '9785734596885', 'default_cover.png', 2012, 6, 'Available', '2026-05-31 09:55:29'),
(1082, 'Principles of Digital Communications in Practice', 'John Smith', 'Telecommunication', '9788472644018', 'default_cover.png', 2004, 3, 'Available', '2026-05-31 09:55:29'),
(1083, 'Fundamentals of Optical Fiber 3rd Edition', 'Daniel Hernandez', 'Telecommunication', '9781708732178', 'default_cover.png', 2019, 9, 'Available', '2026-05-31 09:55:29'),
(1084, 'Introduction to Wireless Networks', 'Sarah Gonzalez', 'Telecommunication', '9782009332946', 'default_cover.png', 2011, 8, 'Available', '2026-05-31 09:55:29'),
(1085, 'Applied Microwave Engineering 2nd Edition', 'Ashley Wilson', 'Telecommunication', '9781240023947', 'default_cover.png', 2005, 6, 'Available', '2026-05-31 09:55:29'),
(1086, 'Modern Antenna Theory', 'Ashley Williams', 'Telecommunication', '9784101731321', 'default_cover.png', 2012, 4, 'Available', '2026-05-31 09:55:29'),
(1087, 'Modern Wireless Networks', 'Michael Martinez', 'Telecommunication', '9785394267854', 'default_cover.png', 2002, 6, 'Available', '2026-05-31 09:55:29'),
(1088, 'Applied Digital Communications for Engineers', 'William Davis', 'Telecommunication', '9782789914973', 'default_cover.png', 2000, 7, 'Available', '2026-05-31 09:55:29'),
(1089, 'Modern Satellite Communications for Engineers', 'Robert Garcia', 'Telecommunication', '9781735501271', 'default_cover.png', 2002, 7, 'Available', '2026-05-31 09:55:29'),
(1090, 'Fundamentals of Microwave Engineering in Practice', 'Daniel Lopez', 'Telecommunication', '9785652962490', 'default_cover.png', 2018, 5, 'Available', '2026-05-31 09:55:29');

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `fine_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('borrowed','returned') DEFAULT 'borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowings`
--

INSERT INTO `borrowings` (`id`, `user_id`, `book_id`, `borrow_date`, `due_date`, `return_date`, `fine_amount`, `status`) VALUES
(12, 7, 1024, '2026-05-31 09:58:11', '2026-09-27', NULL, 0.00, 'borrowed'),
(13, 7, 773, '2026-05-31 09:58:15', '2026-09-27', NULL, 0.00, 'borrowed'),
(14, 3, 1024, '2026-05-31 09:59:23', '2026-09-27', NULL, 0.00, 'borrowed'),
(15, 3, 772, '2026-05-31 09:59:26', '2026-09-27', NULL, 0.00, 'borrowed'),
(16, 6, 1040, '2026-05-31 10:00:12', '2026-09-27', NULL, 0.00, 'borrowed'),
(17, 9, 1030, '2026-05-31 10:01:56', '2026-09-27', NULL, 0.00, 'borrowed'),
(18, 9, 1029, '2026-05-31 10:01:58', '2026-09-27', NULL, 0.00, 'borrowed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default_avatar.png',
  `role` enum('admin','student','faculty','staff') DEFAULT 'student',
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_image`, `role`, `is_approved`, `created_at`) VALUES
(1, 'admin', 'admin@library.com', '$2y$12$50XNGV2R/mX3fdp9souQUOICCgsh4oU0SK40jH5PQ/TnF3sNEl0au', 'default_avatar.png', 'admin', 1, '2026-05-31 08:33:15'),
(2, 'Ali Ahmad', 'aliahmad@gmail.com', '$2y$10$pcMrRZkYbqc7GkZfayWS1.Vaql2tIGFYC7EL1e98eeGIVLSsWajem', 'default_avatar.png', 'student', 1, '2026-05-31 08:34:24'),
(3, 'Aliya Ali', 'aliyaali11@gmail.com', '$2y$10$lqtYgpuAtSeM7FyPq6NGHugie0seY3OAOqE.Z42xUhd4cEw0nYHLi', 'default_avatar.png', 'student', 1, '2026-05-31 08:35:49'),
(5, 'Mian Saeed Akber', 'miansaeed@faculty.uetm.edu', '$2y$10$uotodc4GuEVv9fqYans1X.FO/33ybAWt3Uzsx0GoJqZz0rYyc/LUy', 'default_avatar.png', 'faculty', 1, '2026-05-31 08:49:19'),
(6, 'Hammad Khan', 'hammadkhan12@gmail.com', '$2y$10$8q0lTjj0QHTHN/o8M6AW/e57.RZu0IKqLOnD5j1ERnr3yAbV5CpK.', 'default_avatar.png', 'student', 1, '2026-05-31 09:23:56'),
(7, 'Sudais Jamal', 'sudaisj435@gmail.com', '$2y$10$95D.VgItKPvVfiCm2lUcAe3q77bP8t/KmmwAhu1itkgCDgqjVbpT2', 'profile_6a1c012d49f07.jpeg', 'student', 1, '2026-05-31 09:26:16'),
(8, 'Akber Chacha', 'akber@staff.uetm.edu', '$2y$10$5HpM4EkiL.YGaFgzTCAeqO6HLREK9iyDD6p3Vx/yd6gElLByLB8z6', 'default_avatar.png', 'staff', 1, '2026-05-31 09:39:31'),
(9, 'Mansha ', 'mansha@gmail.com', '$2y$10$a6EH.BlLp2eTmw/RG8RWN.jW0rDyxfVvGAM.Y.2NGFyKvuaXHP3bK', 'profile_6a1c0720a10e5.jpeg', 'student', 1, '2026-05-31 10:01:24');

-- --------------------------------------------------------

--
-- Table structure for table `waitlist`
--

CREATE TABLE `waitlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `waitlist`
--
ALTER TABLE `waitlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1091;

--
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `waitlist`
--
ALTER TABLE `waitlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowings_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `waitlist`
--
ALTER TABLE `waitlist`
  ADD CONSTRAINT `waitlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `waitlist_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
