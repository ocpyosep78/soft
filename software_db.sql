-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Waktu pembuatan: 23. Juni 2013 jam 17:30
-- Versi Server: 5.1.41
-- Versi PHP: 5.3.1

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
-- Struktur dari tabel `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data untuk tabel `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Game'),
(2, 'RSS'),
(3, 'News'),
(4, 'Movie');

-- --------------------------------------------------------

--
-- Struktur dari tabel `default_value`
--

CREATE TABLE IF NOT EXISTS `default_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `default_value`
--

INSERT INTO `default_value` (`id`, `name`, `value`) VALUES
(2, 'Konversi Rupiah Dolar', '9500'),
(3, 'Tax Store', '3.5');

-- --------------------------------------------------------

--
-- Struktur dari tabel `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `platform_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_status_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `price` int(11) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `filename` longtext NOT NULL,
  `date_update` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`id`, `user_id`, `platform_id`, `category_id`, `item_status_id`, `name`, `description`, `price`, `thumbnail`, `filename`, `date_update`) VALUES
(1, 1, 1, 2, 2, 'Nama Software', 'Description', 15000, '2013/06/22/20130622_094019_5290.jpg', '["2013\\/06\\/22\\/blog-article.jpg"]', '2013-06-22'),
(2, 1, 2, 1, 2, 'Software A', 'Description', 1500, '2013/06/22/20130622_103656_2343.jpg', '["2013\\/06\\/22\\/blog_article_5.jpg"]', '2013-06-22'),
(3, 1, 1, 2, 2, 'BocaNakal', 'Description', 2500, '2013/06/22/20130622_103831_1181.jpg', '["2013\\/06\\/22\\/blog_article_6.jpg"]', '2013-06-22'),
(7, 0, 4, 3, 1, 'Software [withname]', '123456', 2500, '2013/06/23/20130623_134403_2120.jpg', '["2013\\/06\\/23\\/blog_article_3.jpg","2013\\/06\\/23\\/creative_2_03_3.jpg","2013\\/06\\/23\\/event01.jpg","2013\\/06\\/23\\/events_article.jpg"]', '0000-00-00'),
(6, 0, 3, 4, 1, 'Software [noname]', '456789', 2500, '2013/06/23/20130623_134227_1019.jpg', '["2013\\/06\\/23\\/blog_article_2.jpg","2013\\/06\\/23\\/creative_2_03_2.jpg"]', '0000-00-00'),
(8, 1, 4, 4, 1, 'cek woo', 'asd', 1200, '2013/06/23/20130623_134843_6773.jpg', '["2013\\/06\\/23\\/blog_article_4.jpg","2013\\/06\\/23\\/creative_2_03_4.jpg"]', '0000-00-00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `item_status`
--

CREATE TABLE IF NOT EXISTS `item_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `item_status`
--

INSERT INTO `item_status` (`id`, `name`) VALUES
(1, 'Pending'),
(2, 'Approve');

-- --------------------------------------------------------

--
-- Struktur dari tabel `platform`
--

CREATE TABLE IF NOT EXISTS `platform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data untuk tabel `platform`
--

INSERT INTO `platform` (`id`, `name`) VALUES
(1, 'Windows'),
(2, 'Linux'),
(3, 'Java'),
(4, 'Android');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` longtext NOT NULL,
  `deposit` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `passwd`, `fullname`, `address`, `deposit`, `is_active`) VALUES
(1, 'her0satr', 'her0satr@yahoo.com', 'd0cccd72f00289035b8e25ff29100dee', '', '', 0, 0),
(7, '', 'mail@mail.com', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_item`
--

CREATE TABLE IF NOT EXISTS `user_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `invoice_no` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `payment_name` varchar(200) NOT NULL,
  `payment_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data untuk tabel `user_item`
--

INSERT INTO `user_item` (`id`, `user_id`, `item_id`, `invoice_no`, `price`, `payment_name`, `payment_date`) VALUES
(1, 1, 1, 1, 15000, 'paypal', '0000-00-00'),
(3, 7, 1, 3, 15000, 'paypal', '0000-00-00'),
(4, 7, 1, 4, 15000, 'paypal', '0000-00-00'),
(5, 7, 3, 5, 2500, 'paypal', '0000-00-00'),
(6, 7, 2, 6, 1500, 'paypal', '0000-00-00'),
(7, 7, 1, 7, 15000, 'paypal', '0000-00-00'),
(8, 7, 1, 8, 15000, 'paypal', '2013-06-23');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
