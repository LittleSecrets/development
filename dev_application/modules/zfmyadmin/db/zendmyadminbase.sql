-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 13 2012 г., 13:28
-- Версия сервера: 5.5.16
-- Версия PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `zendmyadminbase`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `group` int(8) NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `zfmyadmin_operations`
--

CREATE TABLE IF NOT EXISTS `zfmyadmin_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `category` tinyint(4) NOT NULL,
  `content` text NOT NULL,
  `target` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=134 ;

--
-- Дамп данных таблицы `zfmyadmin_operations`
--

INSERT INTO `zfmyadmin_operations` (`id`, `transaction_id`, `type`, `category`, `content`, `target`, `description`, `status`) VALUES
(1, 1, 2, 1, 'TestController.php', '\\application\\modules\\default\\controllers', 'Create controller file', 1),
(2, 1, 3, 1, '<?php\n/**\n * TestController\n * \n */\nclass TestController extends Zend_Controller_Action \n{\n\n}', '\\application\\modules\\default\\controllers\\TestController.php', 'TestController', 1),
(3, 1, 3, 3, '    /**\n     * @desctiption\n     */\n    public function init() \n    {\n        \n    }', '\\application\\modules\\default\\controllers\\TestController.php', 'init', 1),
(4, 1, 3, 3, '    /**\n     * @desctiption\n     */\n    public function preDispatch() \n    {\n        \n    }', '\\application\\modules\\default\\controllers\\TestController.php', 'preDispatch', 1),
(5, 1, 3, 3, '    /**\n     * @desctiption\n     */\n    public function postDispatch() \n    {\n        \n    }', '\\application\\modules\\default\\controllers\\TestController.php', 'postDispatch', 1),
(6, 1, 3, 2, '    /**\n     * @desctiption\n     */\n    public function indexAction() \n    {\n        \n    }\n', '\\application\\modules\\default\\controllers\\TestController.php', 'index', 1),
(7, 1, 1, 4, 'Test', '\\application\\modules\\default\\views\\scripts\\test', 'Create views directory', 1),
(8, 1, 2, 4, 'index.phtml', '\\application\\modules\\default\\views\\scripts\\test\\index.phtml', 'Create view', 1),
(9, 1, 1, 5, 'default', '\\public\\design\\css', 'Create css directory', 1),
(10, 1, 2, 5, 'test.css', '\\public\\design\\css\\default', 'Create css file', 1),
(11, 1, 1, 5, 'test', '\\public\\design\\css\\default', 'Create css directory', 1),
(12, 1, 2, 5, 'index.css', '\\public\\design\\css\\default\\test', 'Create css file', 1),
(13, 1, 1, 6, 'default', '\\public\\design\\js', 'Create js directory', 1),
(14, 1, 2, 6, 'test.js', '\\public\\design\\js\\default', 'Create js file', 1),
(15, 1, 1, 6, 'test', '\\public\\design\\js\\default', 'Create js directory', 1),
(16, 1, 2, 6, 'index.js', '\\public\\design\\js\\default\\test', 'Create js file', 1),
(17, 2, 2, 1, 'NextController.php', '\\application\\modules\\users\\controllers', 'Create controller file', 1),
(18, 2, 3, 1, '<?php\n/**\n * NextController\n * \n */\nclass Users_NextController extends Zend_Controller_Action \n{\n\n}', '\\application\\modules\\users\\controllers\\NextController.php', 'Users_NextController', 1),
(19, 2, 3, 3, '    /**\n     * @desctiption\n     */\n    public function preDispatch() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\NextController.php', 'preDispatch', 1),
(20, 2, 3, 2, '    /**\n     * @desctiption\n     */\n    public function indexAction() \n    {\n        \n    }\n', '\\application\\modules\\users\\controllers\\NextController.php', 'index', 1),
(21, 2, 1, 4, 'Next', '\\application\\modules\\users\\views\\scripts\\next', 'Create views directory', 1),
(22, 2, 2, 4, 'index.phtml', '\\application\\modules\\users\\views\\scripts\\next\\index.phtml', 'Create view', 1),
(23, 2, 1, 5, 'users', '\\public\\design\\css', 'Create css directory', 1),
(24, 2, 1, 5, 'next', '\\public\\design\\css\\users', 'Create css directory', 1),
(25, 2, 2, 5, 'index.css', '\\public\\design\\css\\users\\next', 'Create css file', 1),
(26, 2, 1, 6, 'users', '\\public\\design\\js', 'Create js directory', 1),
(27, 2, 2, 6, 'next.js', '\\public\\design\\js\\users', 'Create js file', 1),
(28, 3, 2, 1, 'MailController.php', '\\application\\modules\\users\\controllers', 'Create controller file', 1),
(29, 3, 3, 1, '<?php\n/**\n * MailController\n * \n */\nclass Users_MailController extends Zend_Controller_Action \n{\n\n}', '\\application\\modules\\users\\controllers\\MailController.php', 'Users_MailController', 1),
(30, 3, 3, 3, '    /**\n     * @desctiption\n     */\n    public function init() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\MailController.php', 'init', 1),
(31, 3, 3, 3, '    /**\n     * @desctiption\n     */\n    public function preDispatch() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\MailController.php', 'preDispatch', 1),
(32, 3, 3, 3, '    /**\n     * @desctiption\n     */\n    public function postDispatch() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\MailController.php', 'postDispatch', 1),
(33, 3, 3, 2, '    /**\n     * @desctiption\n     */\n    public function indexAction() \n    {\n        \n    }\n', '\\application\\modules\\users\\controllers\\MailController.php', 'index', 1),
(34, 3, 1, 4, 'Mail', '\\application\\modules\\users\\views\\scripts\\mail', 'Create views directory', 1),
(35, 3, 2, 4, 'index.phtml', '\\application\\modules\\users\\views\\scripts\\mail\\index.phtml', 'Create view', 1),
(36, 3, 1, 5, 'users', '\\public\\design\\css', 'Create css directory', 1),
(37, 3, 2, 5, 'mail.css', '\\public\\design\\css\\users', 'Create css file', 1),
(38, 3, 1, 5, 'mail', '\\public\\design\\css\\users', 'Create css directory', 1),
(39, 3, 2, 5, 'index.css', '\\public\\design\\css\\users\\mail', 'Create css file', 1),
(40, 3, 1, 6, 'users', '\\public\\design\\js', 'Create js directory', 1),
(41, 3, 2, 6, 'mail.js', '\\public\\design\\js\\users', 'Create js file', 1),
(42, 3, 1, 6, 'mail', '\\public\\design\\js\\users', 'Create js directory', 1),
(43, 3, 2, 6, 'index.js', '\\public\\design\\js\\users\\mail', 'Create js file', 1),
(44, 4, 2, 1, 'NewModelController.php', '\\application\\modules\\users\\controllers', 'Create controller file', 1),
(45, 4, 3, 1, '<?php\n/**\n * NewModelController\n * \n */\nclass Users_NewModelController extends Zend_Controller_Action \n{\n\n}', '\\application\\modules\\users\\controllers\\NewModelController.php', 'Users_NewModelController', 1),
(46, 4, 3, 3, '    /**\n     * @desctiption\n     */\n    public function init() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\NewModelController.php', 'init', 1),
(47, 4, 3, 3, '    /**\n     * @desctiption\n     */\n    public function preDispatch() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\NewModelController.php', 'preDispatch', 1),
(48, 4, 3, 3, '    /**\n     * @desctiption\n     */\n    public function postDispatch() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\NewModelController.php', 'postDispatch', 1),
(49, 4, 3, 2, '    /**\n     * @desctiption\n     */\n    public function indexAction() \n    {\n        \n    }\n', '\\application\\modules\\users\\controllers\\NewModelController.php', 'index', 1),
(50, 4, 1, 4, 'New-Model', '\\application\\modules\\users\\views\\scripts\\new-model', 'Create views directory', 1),
(51, 4, 2, 4, 'index.phtml', '\\application\\modules\\users\\views\\scripts\\new-model\\index.phtml', 'Create view', 1),
(52, 4, 1, 5, 'users', '\\public\\design\\css', 'Create css directory', 1),
(53, 4, 2, 5, 'new-model.css', '\\public\\design\\css\\users', 'Create css file', 1),
(54, 4, 1, 5, 'new-model', '\\public\\design\\css\\users', 'Create css directory', 1),
(55, 4, 2, 5, 'index.css', '\\public\\design\\css\\users\\new-model', 'Create css file', 1),
(56, 4, 1, 6, 'users', '\\public\\design\\js', 'Create js directory', 1),
(57, 4, 2, 6, 'new-model.js', '\\public\\design\\js\\users', 'Create js file', 1),
(58, 4, 1, 6, 'new-model', '\\public\\design\\js\\users', 'Create js directory', 1),
(59, 4, 2, 6, 'index.js', '\\public\\design\\js\\users\\new-model', 'Create js file', 1),
(60, 5, 2, 1, 'NewFunctionController.php', '\\application\\modules\\users\\controllers', 'Create controller file', 1),
(61, 5, 3, 1, '<?php\n/**\n * NewFunctionController\n * \n */\nclass Users_NewFunctionController extends Zend_Controller_Action \n{\n\n}', '\\application\\modules\\users\\controllers\\NewFunctionController.php', 'Users_NewFunctionController', 1),
(62, 5, 3, 3, '    /**\n     * @desctiption\n     */\n    public function init() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\NewFunctionController.php', 'init', 1),
(63, 5, 3, 3, '    /**\n     * @desctiption\n     */\n    public function preDispatch() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\NewFunctionController.php', 'preDispatch', 1),
(64, 5, 3, 3, '    /**\n     * @desctiption\n     */\n    public function postDispatch() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\NewFunctionController.php', 'postDispatch', 1),
(65, 5, 3, 2, '    /**\n     * @desctiption\n     */\n    public function indexAction() \n    {\n        \n    }\n', '\\application\\modules\\users\\controllers\\NewFunctionController.php', 'index', 1),
(66, 5, 1, 4, 'New-Function', '\\application\\modules\\users\\views\\scripts\\new-function', 'Create views directory', 1),
(67, 5, 2, 4, 'index.phtml', '\\application\\modules\\users\\views\\scripts\\new-function\\index.phtml', 'Create view', 1),
(68, 5, 1, 5, 'users', '\\public\\design\\css', 'Create css directory', 1),
(69, 5, 2, 5, 'new-function.css', '\\public\\design\\css\\users', 'Create css file', 1),
(70, 5, 1, 5, 'new-function', '\\public\\design\\css\\users', 'Create css directory', 1),
(71, 5, 2, 5, 'index.css', '\\public\\design\\css\\users\\new-function', 'Create css file', 1),
(72, 5, 1, 6, 'users', '\\public\\design\\js', 'Create js directory', 1),
(73, 5, 2, 6, 'new-function.js', '\\public\\design\\js\\users', 'Create js file', 1),
(74, 5, 1, 6, 'new-function', '\\public\\design\\js\\users', 'Create js directory', 1),
(75, 5, 2, 6, 'index.js', '\\public\\design\\js\\users\\new-function', 'Create js file', 1),
(76, 6, 2, 1, 'NewTransactionMetodController.php', '\\application\\modules\\users\\controllers', 'Create controller file', 1),
(77, 6, 3, 1, '<?php\n/**\n * NewTransactionMetodController\n * \n */\nclass Users_NewTransactionMetodController extends Zend_Controller_Action \n{\n\n}', '\\application\\modules\\users\\controllers\\NewTransactionMetodController.php', 'Users_NewTransactionMetodController', 1),
(78, 6, 3, 3, '    /**\n     * @desctiption\n     */\n    public function init() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\NewTransactionMetodController.php', 'init', 1),
(79, 6, 3, 3, '    /**\n     * @desctiption\n     */\n    public function preDispatch() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\NewTransactionMetodController.php', 'preDispatch', 1),
(80, 6, 3, 3, '    /**\n     * @desctiption\n     */\n    public function postDispatch() \n    {\n        \n    }', '\\application\\modules\\users\\controllers\\NewTransactionMetodController.php', 'postDispatch', 1),
(81, 6, 3, 2, '    /**\n     * @desctiption\n     */\n    public function indexAction() \n    {\n        \n    }\n', '\\application\\modules\\users\\controllers\\NewTransactionMetodController.php', 'index', 1),
(82, 6, 1, 4, 'New-Transaction-Metod', '\\application\\modules\\users\\views\\scripts\\new-transaction-metod', 'Create views directory', 1),
(83, 6, 2, 4, 'index.phtml', '\\application\\modules\\users\\views\\scripts\\new-transaction-metod\\index.phtml', 'Create view', 1),
(84, 6, 1, 5, 'users', '\\public\\design\\css', 'Create css directory', 1),
(85, 6, 2, 5, 'new-transaction-metod.css', '\\public\\design\\css\\users', 'Create css file', 1),
(86, 6, 1, 5, 'new-transaction-metod', '\\public\\design\\css\\users', 'Create css directory', 1),
(87, 6, 2, 5, 'index.css', '\\public\\design\\css\\users\\new-transaction-metod', 'Create css file', 1),
(88, 6, 1, 6, 'users', '\\public\\design\\js', 'Create js directory', 1),
(89, 6, 2, 6, 'new-transaction-metod.js', '\\public\\design\\js\\users', 'Create js file', 1),
(90, 6, 1, 6, 'new-transaction-metod', '\\public\\design\\js\\users', 'Create js directory', 1),
(91, 6, 2, 6, 'index.js', '\\public\\design\\js\\users\\new-transaction-metod', 'Create js file', 1),
(92, 7, 2, 1, 'NewViewController.php', '\\application\\modules\\default\\controllers', 'Create controller file', 1),
(93, 7, 3, 1, '<?php\n/**\n * NewViewController\n * \n */\nclass NewViewController extends Zend_Controller_Action \n{\n\n}', '\\application\\modules\\default\\controllers\\NewViewController.php', 'NewViewController', 1),
(94, 7, 3, 3, '    /**\n     * @desctiption\n     */\n    public function init() \n    {\n        \n    }', '\\application\\modules\\default\\controllers\\NewViewController.php', 'init', 1),
(95, 7, 3, 3, '    /**\n     * @desctiption\n     */\n    public function preDispatch() \n    {\n        \n    }', '\\application\\modules\\default\\controllers\\NewViewController.php', 'preDispatch', 1),
(96, 7, 3, 3, '    /**\n     * @desctiption\n     */\n    public function postDispatch() \n    {\n        \n    }', '\\application\\modules\\default\\controllers\\NewViewController.php', 'postDispatch', 1),
(97, 7, 3, 2, '    /**\n     * @desctiption\n     */\n    public function indexAction() \n    {\n        \n    }\n', '\\application\\modules\\default\\controllers\\NewViewController.php', 'index', 1),
(98, 7, 1, 4, 'New-View', '\\application\\modules\\default\\views\\scripts\\new-view', 'Create views directory', 1),
(99, 7, 2, 4, 'index.phtml', '\\application\\modules\\default\\views\\scripts\\new-view\\index.phtml', 'Create view', 1),
(100, 7, 1, 5, 'default', '\\public\\design\\css', 'Create css directory', 1),
(101, 7, 2, 5, 'new-view.css', '\\public\\design\\css\\default', 'Create css file', 1),
(102, 7, 1, 5, 'new-view', '\\public\\design\\css\\default', 'Create css directory', 1),
(103, 7, 2, 5, 'index.css', '\\public\\design\\css\\default\\new-view', 'Create css file', 1),
(104, 7, 1, 6, 'default', '\\public\\design\\js', 'Create js directory', 1),
(105, 7, 2, 6, 'new-view.js', '\\public\\design\\js\\default', 'Create js file', 1),
(106, 7, 1, 6, 'new-view', '\\public\\design\\js\\default', 'Create js directory', 1),
(107, 7, 2, 6, 'index.js', '\\public\\design\\js\\default\\new-view', 'Create js file', 1),
(108, 8, 2, 1, 'NewJsController.php', '\\application\\modules\\default\\controllers', 'Create controller file', 1),
(109, 8, 3, 1, '<?php\n/**\n * NewJsController\n * \n */\nclass NewJsController extends Zend_Controller_Action \n{\n\n}', '\\application\\modules\\default\\controllers\\NewJsController.php', 'NewJsController', 1),
(110, 8, 3, 3, '    /**\n     * @desctiption\n     */\n    public function init() \n    {\n        \n    }', '\\application\\modules\\default\\controllers\\NewJsController.php', 'init', 1),
(111, 8, 3, 3, '    /**\n     * @desctiption\n     */\n    public function preDispatch() \n    {\n        \n    }', '\\application\\modules\\default\\controllers\\NewJsController.php', 'preDispatch', 1),
(112, 8, 3, 3, '    /**\n     * @desctiption\n     */\n    public function postDispatch() \n    {\n        \n    }', '\\application\\modules\\default\\controllers\\NewJsController.php', 'postDispatch', 1),
(113, 8, 3, 2, '    /**\n     * @desctiption\n     */\n    public function indexAction() \n    {\n        \n    }\n', '\\application\\modules\\default\\controllers\\NewJsController.php', 'index', 1),
(114, 8, 1, 4, 'New-Js', '\\application\\modules\\default\\views\\scripts\\new-js', 'Create views directory', 1),
(115, 8, 2, 4, 'index.phtml', '\\application\\modules\\default\\views\\scripts\\new-js\\index.phtml', 'Create view', 1),
(116, 8, 1, 5, 'default', '\\public\\design\\css', 'Create css directory', 1),
(117, 8, 2, 5, 'new-js.css', '\\public\\design\\css\\default', 'Create css file', 1),
(118, 8, 1, 5, 'new-js', '\\public\\design\\css\\default', 'Create css directory', 1),
(119, 8, 2, 5, 'index.css', '\\public\\design\\css\\default\\new-js', 'Create css file', 1),
(120, 8, 1, 6, 'default', '\\public\\design\\js', 'Create js directory', 1),
(121, 8, 2, 6, 'new-js.js', '\\public\\design\\js\\default', 'Create js file', 1),
(122, 8, 1, 6, 'new-js', '\\public\\design\\js\\default', 'Create js directory', 1),
(123, 8, 2, 6, 'index.js', '\\public\\design\\js\\default\\new-js', 'Create js file', 1),
(124, 9, 2, 1, 'UserController.php', '\\application\\modules\\zfmyadmin\\controllers', 'Create controller file', 2),
(125, 9, 3, 1, '<?php\n/**\n* UserController\n* %Description%\n*\n* @package    \n* @subpackage \n* @copyright  \n* @license    \n* @version    \n* @link       \n* @since      File available since Release \n* @author     \n*/\n\nclass Zfmyadmin_UserController extends Zend_Controller_Action \n{\n\n}', '\\application\\modules\\zfmyadmin\\controllers\\UserController.php', 'Zfmyadmin_UserController', 2),
(126, 9, 3, 3, '    /**\n     * @desctiption\n     */\n    public function preDispatch() \n    {\n        \n    }', '\\application\\modules\\zfmyadmin\\controllers\\UserController.php', 'preDispatch', 2),
(127, 9, 3, 2, '    /**\n     * @desctiption\n     */\n    public function indexAction() \n    {\n        \n    }\n', '\\application\\modules\\zfmyadmin\\controllers\\UserController.php', 'index', 2),
(128, 9, 1, 4, 'User', '\\application\\modules\\zfmyadmin\\views\\scripts\\user', 'Create views directory', 2),
(129, 9, 2, 4, 'index.phtml', '\\application\\modules\\zfmyadmin\\views\\scripts\\user\\index.phtml', 'Create view', 2),
(130, 9, 1, 5, 'zfmyadmin', '\\public\\design\\css', 'Create css directory', 2),
(131, 9, 2, 5, 'user.css', '\\public\\design\\css\\zfmyadmin', 'Create css file', 2),
(132, 9, 1, 6, 'zfmyadmin', '\\public\\design\\js', 'Create js directory', 2),
(133, 9, 2, 6, 'user.js', '\\public\\design\\js\\zfmyadmin', 'Create js file', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `zfmyadmin_transactions`
--

CREATE TABLE IF NOT EXISTS `zfmyadmin_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`user_id`,`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `zfmyadmin_transactions`
--

INSERT INTO `zfmyadmin_transactions` (`id`, `user_id`, `time`) VALUES
(1, 0, '2012-09-06 07:56:23'),
(2, 0, '2012-09-06 10:21:29'),
(3, 0, '2012-09-06 18:07:05'),
(4, 0, '2012-09-07 10:50:09'),
(5, 0, '2012-09-07 10:52:51'),
(6, 0, '2012-09-07 11:05:00'),
(7, 0, '2012-09-07 12:04:02'),
(8, 0, '2012-09-07 12:16:39'),
(9, 0, '2012-09-08 11:34:55');

-- --------------------------------------------------------

--
-- Структура таблицы `zfmyadmin_users`
--

CREATE TABLE IF NOT EXISTS `zfmyadmin_users` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `zfmyadmin_users`
--

INSERT INTO `zfmyadmin_users` (`id`, `login`, `password`, `role`) VALUES
(2, 'los312', 'e10adc3949ba59abbe56e057f20f883e', 'admin');

-- --------------------------------------------------------

--
-- Структура таблицы `zfmyadmin_vars`
--

CREATE TABLE IF NOT EXISTS `zfmyadmin_vars` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `zfmyadmin_vars`
--

INSERT INTO `zfmyadmin_vars` (`id`, `type`, `name`, `value`, `user_id`) VALUES
(1, 'user_vars', 'info', 'a:8:{s:4:"name";s:9:"My name 3";s:10:"namePhpDoc";s:1:"1";s:5:"email";s:21:"chkhalo.acs@gmail.com";s:11:"emailPhpDoc";s:1:"1";s:7:"company";s:9:"zfmyadmin";s:13:"companyPhpDoc";s:1:"0";s:4:"site";s:0:"";s:10:"sitePhpDoc";s:1:"0";}', 2),
(6, 'project_settings', 'configs', 'a:7:{s:4:"root";s:22:"C:\\Sites\\zfmyadminbase";s:11:"modulesPath";s:42:"C:\\Sites\\zfmyadminbase\\application\\modules";s:14:"controllersDir";s:11:"controllers";s:14:"viewScriptsDir";s:13:"views\\scripts";s:9:"publicDir";s:6:"public";s:6:"cssDir";s:3:"css";s:5:"jsDir";s:2:"js";}', 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
