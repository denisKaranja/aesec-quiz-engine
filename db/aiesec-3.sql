-- phpMyAdmin SQL Dump
-- version 4.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 19, 2015 at 10:00 PM
-- Server version: 5.6.24
-- PHP Version: 5.5.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aiesec`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `phone_number` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quiz_count` int(1) NOT NULL DEFAULT '1',
  `probation_count` int(1) NOT NULL DEFAULT '0',
  `first_time_probation` int(1) NOT NULL DEFAULT '0',
  `aiesec_winner` int(1) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '1',
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quest_answer`
--

CREATE TABLE IF NOT EXISTS `quest_answer` (
  `quiz_id` int(10) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `right_response` varchar(255) NOT NULL,
  `wrong_response` varchar(255) NOT NULL,
  `probation_quiz` varchar(255) NOT NULL,
  `probation_answer` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quest_answer`
--

INSERT INTO `quest_answer` (`quiz_id`, `question`, `answer`, `right_response`, `wrong_response`, `probation_quiz`, `probation_answer`) VALUES
(1, 'I am a portfolio. Everyone needs me. Without me, we’d probably be in court and poor.', 'fna', 'Congratulations on getting the first clue! Let’s keep going.', 'You got me wrong. Give me another try.', 'Look for GHANA for your redemption code.', 'aw957m004'),
(2, 'I have half a dozen of children whom I treasure dearly. One always fulfils their duties in a transparent manner. Who is my child?', 'integrity', 'You on fire! Keep it up!', 'No. You are an AIESECer, you know this!!', 'Look for INDIA for your redemption code.', 'aw639p005'),
(3, 'Part of me is a short relief of nature, the other is the sound a snake makes. I have remained relevant in AIESEC since 1948.', 'peace', 'Great job! You are awesome! On to the next one!', 'Not really! C’mon, think a little harder.', 'Look for POLAND for your redemption code.', 'aw834k002'),
(4, 'We are youth gaining definition as we emerge from a blue mass. At first it was only us but now it is all of us. Who are we?', 'aiesec', 'Great! I like the way you think! Let’s keep it moving.', 'You are close! Try again!', 'Look for GERMANY for your redemption code.', 'aw432j001'),
(5, 'I represent a group of cars bought with a lot of money and prepared with a lot of technology input. These cars will be driven on Friday on 22 Main Street.', 'flit', 'High five! You are brilliant.', 'Kind of…but that’s not it. Keep trying.', 'Look for SLOVAKIA for your redemption code.', 'aw084006'),
(6, 'I wear my kinky hair naturally every year. It wasn’t until 10 years that we saw each other again to engage in leadership and development.', 'afroxlds', 'Congratulations, treasure hunt is over. You are definitely a super AIESECer! You will be contacted shortly to know if you''re a winner!', 'Almost there! Try again.', 'Look for MAURITIUS for your redemption code.', 'aw602l003');

-- --------------------------------------------------------

--
-- Table structure for table `winners`
--

CREATE TABLE IF NOT EXISTS `winners` (
  `winner_id` int(10) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `finish_time` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`phone_number`);

--
-- Indexes for table `quest_answer`
--
ALTER TABLE `quest_answer`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indexes for table `winners`
--
ALTER TABLE `winners`
  ADD PRIMARY KEY (`winner_id`),
  ADD KEY `FK_members_winnersID` (`phone_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `quest_answer`
--
ALTER TABLE `quest_answer`
  MODIFY `quiz_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `winners`
--
ALTER TABLE `winners`
  MODIFY `winner_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `winners`
--
ALTER TABLE `winners`
  ADD CONSTRAINT `FK_members_winnersID` FOREIGN KEY (`phone_number`) REFERENCES `members` (`phone_number`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
