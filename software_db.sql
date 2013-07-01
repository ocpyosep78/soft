-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 01, 2013 at 10:48 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `software_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Games - Arcade & Action'),
(2, 'Games - Brain & Puzzle'),
(3, 'Games - Cards & Casino'),
(4, 'Games - Casual'),
(5, 'Games - Racing'),
(6, 'Games - Sports Games'),
(7, 'Applications - Books & Reference'),
(8, 'Applications - Business'),
(9, 'Applications - Comics'),
(10, 'Applications - Communication'),
(11, 'Applications - Education'),
(12, 'Applications - Entertainment'),
(13, 'Applications - Finance'),
(14, 'Applications - Health & Fitness'),
(15, 'Applications - Libraries & Demo'),
(16, 'Applications - Lifestyle'),
(17, 'Applications - Media & Video'),
(18, 'Applications - Medical'),
(19, 'Applications - Music & Audio'),
(20, 'Applications - News & Magazines'),
(21, 'Applications - Personalization'),
(22, 'Applications - Photography'),
(23, 'Applications - Productivity'),
(24, 'Applications - Shopping'),
(25, 'Applications - Social'),
(26, 'Applications - Sport'),
(27, 'Applications - Tools'),
(28, 'Applications - Transportation'),
(29, 'Applications - Travel & Local'),
(30, 'Applications - Weather');

-- --------------------------------------------------------

--
-- Table structure for table `checkout_data`
--

CREATE TABLE IF NOT EXISTS `checkout_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `checkout_data`
--

INSERT INTO `checkout_data` (`id`, `tanggal`, `data`) VALUES
(1, '2013-06-29 18:26:44', '{"item_id":"9","dolar":0.52631578947368,"paypal_add":1,"paypal_dolar":"1.53"}'),
(2, '2013-06-30 02:43:34', '{"item_id":"9","dolar":0.01,"paypal_add":0,"paypal_dolar":"0.01","konversi_rupiah":"9500"}'),
(3, '2013-06-30 02:55:59', '{"item_id":"6","dolar":0.01,"paypal_add":0,"paypal_dolar":"0.01","konversi_rupiah":"9500","status":1,"paypal":1,"paypal_id":"6U011615GY7905312"}'),
(4, '2013-06-30 03:19:59', '{"item_id":"2","dolar":0.01,"paypal_add":0,"paypal_dolar":"0.01","konversi_rupiah":"9500","status":1,"paypal":1,"paypal_id":"7XS93823BY882705K"}'),
(5, '2013-06-30 04:01:56', '{"item_id":"6","dolar":0.52631578947368,"paypal_add":1,"paypal_dolar":"1.53","konversi_rupiah":"9500"}'),
(6, '2013-06-30 18:04:16', '{"item_id":"1","dolar":0.52631578947368,"paypal_add":1,"paypal_dolar":"1.53","konversi_rupiah":"9500","ipaymu_add":1000,"ipaymu_price":6000}'),
(7, '2013-07-01 10:11:15', '{"item_id":"1","dolar":0.52631578947368,"paypal_add":1,"paypal_dolar":"1.53","konversi_rupiah":"9500","ipaymu_add":1000,"ipaymu_price":6000}'),
(8, '2013-07-01 10:32:49', '{"item_id":"1","dolar":0.52631578947368,"paypal_add":1,"paypal_dolar":"1.53","konversi_rupiah":"9500","ipaymu_add":1000,"ipaymu_price":6000}');

-- --------------------------------------------------------

--
-- Table structure for table `default_value`
--

CREATE TABLE IF NOT EXISTS `default_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `default_value`
--

INSERT INTO `default_value` (`id`, `name`, `value`) VALUES
(2, 'Konversi Rupiah Dolar', '9500'),
(3, 'Tax Store', '3.5');

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `file`
--


-- --------------------------------------------------------

--
-- Table structure for table `ipaymu`
--

CREATE TABLE IF NOT EXISTS `ipaymu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trx_id` int(11) DEFAULT NULL COMMENT 'ID Transaksi IPAYMU',
  `sid` text COLLATE utf8_unicode_ci,
  `product` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nama produk / jasa yang dibayar',
  `quantity` int(5) DEFAULT NULL,
  `merchant` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'username merchant',
  `buyer` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Username pembeli.',
  `total` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Total pembayaran',
  `no_rekening_deposit` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nomor rekening pembayaran jika pembeli memilih opsi pembayaran Transfer Bank (Non-Member IPAYMU)',
  `action` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Aksi pembayaran: payment',
  `comments` text COLLATE utf8_unicode_ci COMMENT 'Komentar (opsional)',
  `referer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'URL referer dari IPAYMU: https://my.ipaymu.com',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `ipaymu`
--

INSERT INTO `ipaymu` (`id`, `status`, `trx_id`, `sid`, `product`, `quantity`, `merchant`, `buyer`, `total`, `no_rekening_deposit`, `action`, `comments`, `referer`) VALUES
(4, '', 0, '', '', 0, '', '', '', '', NULL, '', ''),
(3, '', 0, '', '', 0, '', '', '', '', NULL, '', ''),
(5, '', 0, '', '', 0, '', '', '', '', NULL, '', ''),
(6, '', 0, '', '', 0, '', '', '', '', NULL, '', ''),
(7, 'berhasil', 0, '', '', 0, '', '', '', '', NULL, '', ''),
(8, '', 0, '', '', 0, '', '', '', '', NULL, '', ''),
(9, '', 0, '', '', 0, '', '', '', '', NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `platform_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_status_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `price` double NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `filename` longtext NOT NULL,
  `date_update` date NOT NULL,
  `screenshot` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `user_id`, `platform_id`, `category_id`, `item_status_id`, `name`, `description`, `price`, `thumbnail`, `filename`, `date_update`, `screenshot`) VALUES
(1, 1, 1, 2, 2, 'Video Editor CS', 'Description', 5000, '2013/06/22/20130622_094019_5290.jpg', '["2013\\/06\\/22\\/blog-article.jpg"]', '2013-06-24', ''),
(2, 1, 2, 1, 2, 'Software A', 'Description', 5000, '', '["2013\\/06\\/22\\/blog_article_5.jpg"]', '2013-06-24', ''),
(6, 25, 3, 4, 2, 'Software [noname]', '456789', 5000, '', '["2013\\/06\\/23\\/blog_article_2.jpg","2013\\/06\\/23\\/creative_2_03_2.jpg"]', '2013-06-24', ''),
(7, 25, 1, 1, 1, 'Windows Optimizer', '<p>description</p>', 5000, '2013/06/23/20130623_134403_2120.jpg', '["2013\\/06\\/23\\/blog_article_3.jpg","2013\\/06\\/23\\/creative_2_03_3.jpg","2013\\/06\\/23\\/event01.jpg","2013\\/06\\/23\\/events_article.jpg"]', '2013-06-24', ''),
(8, 25, 4, 1, 1, 'nama2', 'nama2', 5000, '2013/06/23/20130623_134843_6773.jpg', '["2013\\/06\\/23\\/blog_article_4.jpg","2013\\/06\\/23\\/creative_2_03_4.jpg"]', '2013-06-24', ''),
(9, 1, 4, 1, 2, 'Software Var', '123', 5000, '2013/06/24/20130624_050639_5216.jpg', '["2013\\/06\\/24\\/1288448880720p4_2.jpg","2013\\/06\\/24\\/redhatlogo_1.jpg"]', '2013-06-24', ''),
(10, 42, 5, 1, 1, 'LintasGPS G55', '<p>aplikasi keren</p>', 5000, '2013/06/25/20130625_084139_8210.png', '["2013\\/06\\/25\\/LintasGPS.jad"]', '2013-06-27', '');

-- --------------------------------------------------------

--
-- Table structure for table `item_file`
--

CREATE TABLE IF NOT EXISTS `item_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned DEFAULT NULL,
  `file_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `item_file`
--


-- --------------------------------------------------------

--
-- Table structure for table `item_picture`
--

CREATE TABLE IF NOT EXISTS `item_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned DEFAULT NULL,
  `picture_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `item_picture`
--


-- --------------------------------------------------------

--
-- Table structure for table `item_status`
--

CREATE TABLE IF NOT EXISTS `item_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `item_status`
--

INSERT INTO `item_status` (`id`, `name`) VALUES
(1, 'Pending'),
(2, 'Approve');

-- --------------------------------------------------------

--
-- Table structure for table `nota`
--

CREATE TABLE IF NOT EXISTS `nota` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `store_id` int(10) unsigned DEFAULT NULL,
  `status_nota_id` int(10) unsigned DEFAULT NULL,
  `payment_method_id` int(10) unsigned DEFAULT NULL,
  `nota_note` longtext,
  `nota_date` datetime DEFAULT NULL,
  `nota_name` varchar(100) DEFAULT NULL,
  `nota_email` varchar(255) NOT NULL,
  `nota_address` longtext,
  `nota_phone` varchar(50) DEFAULT NULL,
  `nota_zipcode` varchar(10) DEFAULT NULL,
  `nota_city` varchar(50) DEFAULT NULL,
  `nota_country` varchar(50) DEFAULT NULL,
  `nota_currency` varchar(10) NOT NULL,
  `nota_tax` int(11) NOT NULL,
  `nota_deposit` int(11) NOT NULL,
  `nota_total` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `nota`
--

INSERT INTO `nota` (`id`, `user_id`, `store_id`, `status_nota_id`, `payment_method_id`, `nota_note`, `nota_date`, `nota_name`, `nota_email`, `nota_address`, `nota_phone`, `nota_zipcode`, `nota_city`, `nota_country`, `nota_currency`, `nota_tax`, `nota_deposit`, `nota_total`) VALUES
(1, 1, 1, 2, 1, '', '2013-06-18 07:04:48', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 0, 0, 2500),
(2, 1, 1, 2, 2, '', '2013-06-18 07:09:55', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 0, 0, 1000),
(3, 1, 1, 2, 2, '', '2013-06-19 07:10:45', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 158, 4343, 4500),
(4, 1, 1, 2, 2, '', '2013-06-19 08:02:10', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 88, 2413, 2500),
(5, 1, 1, 2, 2, '', '2013-06-19 09:15:21', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 88, 2413, 2500),
(6, 25, 1, 3, 1, '', '2013-06-19 09:15:39', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 35, 965, 1000),
(7, 1, 1, 3, 1, '', '2013-06-19 09:15:54', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 70, 1930, 2000),
(8, 25, 1, 3, 1, '', '2013-06-19 09:17:13', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 70, 1930, 2000),
(9, 1, 1, 3, 1, '', '2013-06-19 09:17:41', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 35, 965, 1000),
(10, 1, 1, 3, 1, '', '2013-06-19 09:20:50', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 35, 965, 1000),
(11, 1, 1, 2, 1, '', '2013-06-19 09:21:13', 'Herry', 'herry@simetri.in', '', '', '', '', '', 'IDR', 70, 1930, 2000),
(12, 0, 1, 1, 1, '', '2013-06-19 11:12:33', '', '', '', '', '', '', '', 'IDR', 35, 965, 1000),
(13, 0, 1, 1, 1, '', '2013-06-19 11:12:36', '', '', '', '', '', '', '', '', 0, 0, 0),
(14, 0, 1, 1, 1, '', '2013-06-19 11:12:37', '', '', '', '', '', '', '', '', 0, 0, 0),
(15, 0, 1, 1, 1, '', '2013-06-19 11:12:37', '', '', '', '', '', '', '', '', 0, 0, 0),
(16, 0, 1, 1, 1, '', '2013-06-19 11:12:37', '', '', '', '', '', '', '', '', 0, 0, 0),
(17, 0, 1, 1, 1, '', '2013-06-19 11:12:38', '', '', '', '', '', '', '', '', 0, 0, 0),
(18, 0, 1, 1, 1, '', '2013-06-19 11:12:38', '', '', '', '', '', '', '', '', 0, 0, 0),
(19, 0, 1, 1, 1, '', '2013-06-19 11:12:49', '', '', '', '', '', '', '', '', 0, 0, 0),
(20, 0, 1, 1, 1, '', '2013-06-19 11:12:52', '', '', '', '', '', '', '', '', 0, 0, 0),
(21, 0, 1, 1, 1, '', '2013-06-19 11:12:55', '', '', '', '', '', '', '', '', 0, 0, 0),
(22, 0, 1, 1, 1, '', '2013-06-19 11:12:58', '', '', '', '', '', '', '', '', 0, 0, 0),
(23, 0, 1, 1, 1, '', '2013-06-19 11:13:01', '', '', '', '', '', '', '', '', 0, 0, 0),
(24, 0, 1, 1, 1, '', '2013-06-19 11:13:04', '', '', '', '', '', '', '', '', 0, 0, 0),
(25, 0, 1, 1, 1, '', '2013-06-19 11:13:04', '', '', '', '', '', '', '', '', 0, 0, 0),
(26, 0, 1, 1, 1, '', '2013-06-19 11:13:04', '', '', '', '', '', '', '', '', 0, 0, 0),
(27, 0, 1, 1, 1, '', '2013-06-19 11:13:04', '', '', '', '', '', '', '', '', 0, 0, 0),
(28, 29, 1, 1, 1, '', '2013-06-20 04:35:42', 'ferdhie', 'ferdhie@simetri.in', '', '', '', '', '', 'IDR', 53, 1448, 1500),
(29, 0, 1, 2, 2, '', '2013-06-21 08:39:56', '', '', '', '', '', '', '', 'IDR', 35, 965, 1000),
(30, 0, 1, 2, 1, '', '2013-06-22 11:26:40', '', '', '', '', '', '', '', 'IDR', 35, 965, 1000),
(31, 0, 1, 2, 1, '', '2013-06-22 11:27:48', '', '', '', '', '', '', '', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `picture`
--

CREATE TABLE IF NOT EXISTS `picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `picture_name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `picture`
--


-- --------------------------------------------------------

--
-- Table structure for table `platform`
--

CREATE TABLE IF NOT EXISTS `platform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file_type` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `platform`
--

INSERT INTO `platform` (`id`, `name`, `file_type`) VALUES
(1, 'Mobile - Android', 'apk'),
(2, 'Mobile - Blackberry', 'jad,jar,cod'),
(3, 'Mobile - J2ME', 'jad,jar'),
(4, 'Mobile - Symbian', 'sis'),
(5, 'Desktop - Windows', 'exe,jar,zip,rar,gz,bz2,7z'),
(6, 'Desktop - Linux', 'jar,zip,rar,gz,bz2,7z'),
(8, 'Dokumen - Foto/Desain/Gambar', 'jpg,png,gif,tif,indd,idml,psd,pdf,jpef,doc,docx,ppt,pptx,odt,odp,xml,html,htm,css,js,ai,cdr,zip,rar,gz,bz2,7z'),
(9, 'Dokumen - Video', 'wmv,mp4,flv,mpg,3gp,ogg,avi,mkv,zip,rar,gz,bz2,7z'),
(10, 'Dokumen - Teks/Ebook/Jurnal', 'txt,chm,pdf,docx,doc,odt,odp,ppt,pptx,xls,xlsx,htm,html,zip,rar,gz,bz2,7z');

-- --------------------------------------------------------

--
-- Table structure for table `status_nota`
--

CREATE TABLE IF NOT EXISTS `status_nota` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `status_nota`
--

INSERT INTO `status_nota` (`id`, `name`) VALUES
(1, 'pending'),
(2, 'confirm'),
(3, 'cancel');

-- --------------------------------------------------------

--
-- Table structure for table `tickets32`
--

CREATE TABLE IF NOT EXISTS `tickets32` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `stub` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stub` (`stub`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tickets32`
--


-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned DEFAULT NULL,
  `nota_id` int(10) unsigned DEFAULT NULL,
  `quantity` int(10) unsigned DEFAULT NULL,
  `tax` int(10) unsigned DEFAULT NULL,
  `discount` int(10) unsigned DEFAULT NULL,
  `price` int(10) unsigned DEFAULT NULL,
  `price_final` int(11) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `deposit` int(11) NOT NULL,
  `total` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `item_id`, `nota_id`, `quantity`, `tax`, `discount`, `price`, `price_final`, `currency`, `deposit`, `total`) VALUES
(1, 1, 1, 1, 53, 0, 1500, 1500, 'IDR', 1448, 1500),
(2, 2, 1, 1, 35, 0, 1000, 1000, 'IDR', 965, 1000),
(3, 3, 2, 1, 35, 0, 1000, 1000, 'IDR', 965, 1000),
(4, 3, 3, 1, 35, 0, 1000, 1000, 'IDR', 965, 1000),
(5, 3, 4, 1, 35, 0, 1000, 1000, 'IDR', 965, 1000),
(6, 3, 5, 1, 35, 0, 1000, 1000, 'IDR', 965, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `passwd` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `deposit` int(11) NOT NULL,
  `reset` varchar(255) NOT NULL,
  `is_active` int(1) DEFAULT NULL COMMENT 'author / store is_active ?',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `fullname`, `passwd`, `address`, `deposit`, `reset`, `is_active`) VALUES
(1, 'herry', 'herry@simetri.in', 'Herry', 'd0cccd72f00289035b8e25ff29100dee', 'sukun', 3378, '', 1),
(25, 'hasan', 'hasan@mail.com', 'Hasan', 'a469a27e2ddd147e2807876925ff4830', 'Simetri', 0, '', 1),
(26, 'arifa', 'aa@yahoo.com', 'Arif Arxx', 'f66f28b62281bcf990fdcae0bdc73366', 'Jl medan merdeka barat no.1', 0, '', 2),
(30, 'arif', 'ayung@lintasgps.com', 'arief andriyan syahrul mahdi', '8e0de1b0ae20d3ef47ac38da32c88c33', 'Jl. medan', 0, '', 0),
(31, 'normal', 'normal@mail.com', 'normal user', 'f7fef91810ec4c86fb5d2b763664282d', 'jl. Sulfat no.96', 0, '', 1),
(33, 'ferdhie', 'ferdhie@gmail.com', '', 'b5d60de8a454e4e270337f763123a47d', '', 0, '', 0),
(35, 'tester', 'tester@mail.com', '', '7fa2d8e515ff0d7a8d80cd2972367754', '', 0, '', 0),
(36, 'hahahaha', 'test@simetri.in', '', '323bec531d57d6e53b40e19863dd82f9', '', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_item`
--

CREATE TABLE IF NOT EXISTS `user_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `invoice_no` int(11) NOT NULL,
  `price` double NOT NULL,
  `payment_name` varchar(200) NOT NULL,
  `payment_date` datetime NOT NULL,
  `ref_id` varchar(100) DEFAULT NULL,
  `terbayar` double NOT NULL,
  `currency` char(3) NOT NULL,
  `konversi` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ref_id` (`ref_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `user_item`
--

INSERT INTO `user_item` (`id`, `user_id`, `item_id`, `invoice_no`, `price`, `payment_name`, `payment_date`, `ref_id`, `terbayar`, `currency`, `konversi`) VALUES
(1, 1, 1, 1, 15000, 'paypal', '0000-00-00 00:00:00', NULL, 0, '', 0),
(3, 7, 1, 3, 15000, 'paypal', '0000-00-00 00:00:00', NULL, 0, '', 0),
(4, 7, 1, 4, 15000, 'paypal', '0000-00-00 00:00:00', NULL, 0, '', 0),
(5, 7, 3, 5, 2500, 'paypal', '0000-00-00 00:00:00', NULL, 0, '', 0),
(6, 7, 2, 6, 1500, 'paypal', '0000-00-00 00:00:00', NULL, 0, '', 0),
(7, 7, 1, 7, 15000, 'paypal', '0000-00-00 00:00:00', NULL, 0, '', 0),
(8, 7, 1, 8, 15000, 'paypal', '2013-06-23 00:00:00', NULL, 0, '', 0),
(9, 1, 9, 9, 1750, 'paypal', '2013-06-24 00:00:00', NULL, 0, '', 0),
(10, 42, 1, 10, 5000, 'paypal', '2013-06-27 00:00:00', NULL, 0, '', 0),
(18, 33, 2, 18, 0, 'paypal', '2013-06-29 19:20:26', '7XS93823BY882705K', 0.01, 'USD', 0),
(17, 33, 6, 17, 0, 'paypal', '2013-06-29 19:07:16', '6U011615GY7905312', 0.01, 'USD', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
