<?php

$zfmyadmindDumpDb = array(
    "DROP TABLE IF EXISTS `zfmyadmin_operations`;",
    
    "CREATE TABLE IF NOT EXISTS `zfmyadmin_operations` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;",
    
    "DROP TABLE IF EXISTS `zfmyadmin_transactions`;",
    
    "CREATE TABLE IF NOT EXISTS `zfmyadmin_transactions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data` text,
    PRIMARY KEY (`id`),
    KEY `id` (`id`,`user_id`,`time`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;",
    
    "DROP TABLE IF EXISTS `zfmyadmin_users`;",
    
    "CREATE TABLE IF NOT EXISTS `zfmyadmin_users` (
    `id` smallint(6) NOT NULL AUTO_INCREMENT,
    `login` varchar(32) NOT NULL,
    `password` varchar(32) NOT NULL,
    `role` varchar(12) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;",
    
    
    "DROP TABLE IF EXISTS `zfmyadmin_vars`;",
    
    "CREATE TABLE IF NOT EXISTS `zfmyadmin_vars` (
    `id` smallint(6) NOT NULL AUTO_INCREMENT,
    `type` varchar(32) NOT NULL,
    `name` varchar(32) NOT NULL,
    `value` text NOT NULL,
    `user_id` int(11) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
);