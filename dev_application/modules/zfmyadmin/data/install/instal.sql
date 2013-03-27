SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `zfmyadmin_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `category` tinyint(4) NOT NULL,
  `code` text,
  `content` text,
  `target` text,
  `description` text,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `zfmyadmin_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`user_id`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `zfmyadmin_users` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

INSERT INTO `zfmyadmin_users` (`id`, `login`, `password`, `role`) VALUES
(10, 'demo', 'f9fbb57f53e1f4ba4c81a07b02e22ac4', 'demo'),
(11, 'restricted', 'f9fbb57f53e1f4ba4c81a07b02e22ac4', 'restricted'),
(12, 'developer', 'f9fbb57f53e1f4ba4c81a07b02e22ac4', 'developer'),
(13, 'admin', 'f9fbb57f53e1f4ba4c81a07b02e22ac4', 'admin');

CREATE TABLE IF NOT EXISTS `zfmyadmin_vars` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
