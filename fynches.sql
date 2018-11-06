-- phpMyAdmin SQL Dump
-- version 4.0.10deb1ubuntu0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 06, 2018 at 11:34 AM
-- Server version: 5.5.62-0ubuntu0.14.04.1
-- PHP Version: 7.1.23-3+ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fynches`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `verb` varchar(191) NOT NULL,
  `object_id` int(11) NOT NULL,
  `details` text NOT NULL,
  `ip` varchar(191) NOT NULL,
  `useragent` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `verb`, `object_id`, `details`, `ip`, `useragent`, `created_at`, `updated_at`) VALUES
(1, 1, '', 0, 'Profile Updated Successfully.', '172.16.16.192', '', '2018-10-31 23:31:44', '2018-10-31 23:31:44'),
(2, 1, '', 0, 'Beta User Deleted Successfully.', '172.16.16.192', '', '2018-11-02 03:08:46', '2018-11-02 03:08:46'),
(3, 1, '', 0, 'Beta User Deleted Successfully.', '172.16.16.192', '', '2018-11-02 03:09:01', '2018-11-02 03:09:01'),
(4, 1, '', 0, 'Beta User Deleted Successfully.', '172.16.16.192', '', '2018-11-02 03:09:11', '2018-11-02 03:09:11'),
(5, 1, '', 0, 'Testimonial added successfully.', '127.0.0.1', '', '2018-11-05 03:25:01', '2018-11-05 03:25:01'),
(6, 1, '', 0, 'Testimonial updated successfully.', '127.0.0.1', '', '2018-11-05 03:36:17', '2018-11-05 03:36:17'),
(7, 1, '', 0, 'Static Block added successfully.', '127.0.0.1', '', '2018-11-05 04:29:02', '2018-11-05 04:29:02'),
(8, 1, '', 0, 'Static Block added successfully.', '127.0.0.1', '', '2018-11-05 04:36:01', '2018-11-05 04:36:01'),
(9, 1, '', 0, 'Static Block added successfully.', '127.0.0.1', '', '2018-11-05 04:39:09', '2018-11-05 04:39:09'),
(10, 1, '', 0, 'Static Block added successfully.', '127.0.0.1', '', '2018-11-05 04:42:22', '2018-11-05 04:42:22'),
(11, 1, '', 0, 'Static Block updated successfully.', '172.16.16.175', '', '2018-11-05 06:35:49', '2018-11-05 06:35:49'),
(12, 1, '', 0, 'Static Block updated successfully.', '172.16.16.175', '', '2018-11-05 06:36:27', '2018-11-05 06:36:27'),
(13, 1, '', 0, 'Static Block updated successfully.', '172.16.16.175', '', '2018-11-05 06:36:42', '2018-11-05 06:36:42'),
(14, 1, '', 0, 'EmailTemplate added successfully.', '172.16.16.175', '', '2018-11-05 08:42:38', '2018-11-05 08:42:38');

-- --------------------------------------------------------

--
-- Table structure for table `beta_signup`
--

CREATE TABLE IF NOT EXISTS `beta_signup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `beta_signup`
--

INSERT INTO `beta_signup` (`id`, `first_name`, `email`, `created_at`, `updated_at`) VALUES
(5, 'amit', 'amits@techuz.com', '2018-11-05 08:43:16', '2018-11-05 08:43:16');

-- --------------------------------------------------------

--
-- Table structure for table `child_info`
--

CREATE TABLE IF NOT EXISTS `child_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `first_name` varchar(191) NOT NULL,
  `age_range` varchar(191) NOT NULL,
  `recipient_image` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `child_info_event_id_foreign` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cms`
--

CREATE TABLE IF NOT EXISTS `cms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `featured_image` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(191) DEFAULT NULL,
  `user_email` varchar(191) NOT NULL,
  `user_url` varchar(191) DEFAULT NULL,
  `ip_address` varchar(191) NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `is_removed` tinyint(1) NOT NULL DEFAULT '0',
  `commentable_id` int(11) NOT NULL,
  `commentable_type` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comment_flags`
--

CREATE TABLE IF NOT EXISTS `comment_flags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `comment_id` int(10) unsigned NOT NULL,
  `reason` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_flags_comment_id_foreign` (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(191) NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(191) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `subject`, `content`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Beta signup', '<p>Thanks for signing up. We&rsquo;ll keep you up to date on the latest and greatest with Fynches.</p>', 'beta-signup', '', '2018-11-05 08:42:38', '2018-11-05 08:42:38');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `event_publish_date` datetime NOT NULL,
  `event_end_date` datetime NOT NULL,
  `zipcode` int(11) NOT NULL,
  `publish_url` varchar(191) DEFAULT NULL,
  `is_hide` int(11) NOT NULL,
  `status` enum('1','2','3') NOT NULL COMMENT '1=Active; 2=Inactive; 3=Expired',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE IF NOT EXISTS `experience` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `exp_name` varchar(191) NOT NULL,
  `image` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `experience_from` enum('0','1') NOT NULL,
  `gift_needed` int(11) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `experience_event_id_foreign` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `funding_report`
--

CREATE TABLE IF NOT EXISTS `funding_report` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `experience_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `donated_amount` double(8,2) NOT NULL,
  `description` text NOT NULL,
  `transaction_id` varchar(191) NOT NULL,
  `status` varchar(191) NOT NULL,
  `make_annoymas` enum('Yes','No') NOT NULL,
  `sent_mail` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `funding_report_event_id_foreign` (`event_id`),
  KEY `funding_report_experience_id_foreign` (`experience_id`),
  KEY `funding_report_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `global_setting`
--

CREATE TABLE IF NOT EXISTS `global_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `secret_key` varchar(191) NOT NULL,
  `publish_key` varchar(191) NOT NULL,
  `stripe_client_id` varchar(200) DEFAULT NULL,
  `commission` varchar(191) NOT NULL,
  `fb_client_id` varchar(191) NOT NULL,
  `fb_client_secret` varchar(191) NOT NULL,
  `fb_redirect` varchar(191) NOT NULL,
  `google_plus_client_id` varchar(191) NOT NULL,
  `google_plus_secret` varchar(191) NOT NULL,
  `google_plus_redirect` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mapping_custom_tag`
--

CREATE TABLE IF NOT EXISTS `mapping_custom_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mapping_custom_tag_tag_id_foreign` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mapping_event_media`
--

CREATE TABLE IF NOT EXISTS `mapping_event_media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `image` varchar(191) NOT NULL,
  `image_type` enum('0','1') NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mapping_event_media_event_id_foreign` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2017_12_06_213208_create_comments_table', 1),
(2, '2017_12_29_184856_create_comment_flags_table', 1),
(3, '2018_05_22_065027_create_beta_signup_table', 1),
(4, '2018_05_22_065257_create_activity_log_table', 1),
(5, '2018_05_22_065534_create_testimonial_table', 1),
(6, '2018_06_04_091404_create_email_templates_table', 1),
(7, '2018_06_11_105253_create_tags_table', 1),
(8, '2018_06_14_093724_create_static_block_table', 1),
(9, '2018_10_21_215120_make_flags_softdelete', 1),
(10, '2018_10_29_062337_create_users_table', 1),
(11, '2018_10_29_063227_add_column_to_users_table', 1),
(12, '2018_10_29_084051_add_first_name_to_user_table', 1),
(13, '2018_10_29_120346_create_events_table', 1),
(14, '2018_10_29_134051_create_experience_table', 1),
(15, '2018_10_30_045643_create_cms_table', 1),
(16, '2018_10_30_052139_create_mapping_event_media', 1),
(17, '2018_10_30_053035_create_mapping_custom_tag', 1),
(18, '2018_10_30_054037_create_funding_report', 1),
(19, '2018_10_30_094732_create_child_info_table', 1),
(20, '2018_10_30_133019_create_global_setting_table', 1),
(21, '2018_10_31_052323_create_country_table', 1),
(22, '2018_10_31_052537_create_state_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(10) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `state_country_id_foreign` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `static_block`
--

CREATE TABLE IF NOT EXISTS `static_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `static_block`
--

INSERT INTO `static_block` (`id`, `title`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Happiness Is Our Mission', 'happiness-is-our-mission', '<section class="our-mission">\r\n<div class="container">\r\n<div class="row">\r\n<div class="col-md-12 text-center">\r\n<h2 class="title">Happiness Is Our Mission</h2>\r\n\r\n<p>We believe that providing fun and enriching gift experiences helps children lead healthier and happier lives.</p>\r\n</div>\r\n</div>\r\n\r\n<div class="row">\r\n<div class="col-sm-12 col-md-6 col-lg-6">\r\n<div class="lft-img"><img alt="" class="img-fluid" src="http://172.16.16.175:8000/front/img/img1.png" title="" /></div>\r\n</div>\r\n\r\n<div class="col-sm-12 col-md-6 col-lg-6">\r\n<ul>\r\n	<li>Experiences help kids develop friendships, empathy, curiosity, and set them on a path of self-discovery.</li>\r\n	<li>Gifts that offer a child a chance to learn, explore, and have fun offer lasting memories they will cherish forever.</li>\r\n	<li>Scientific research has proven that providing kids with meaningful experiences helps them lead happier lives.</li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n</section>', '2018-11-05 04:29:01', '2018-11-05 06:36:42'),
(2, 'Gifting Made Easy-Peasy', 'gifting-made-easy-peasy', '<section class="gifting">\r\n<div class="container">\r\n<div class="row align-items-center">\r\n<div class="col-sm-12 col-md-5 col-lg-5 order-sm-1 order-md-2 order-lg-2"><img alt="Gifting" class="img-fluid" src="http://172.16.16.175:8000/front/img/Gifting-Easy.png" title="" /></div>\r\n\r\n<div class="col-sm-12  col-md-7 col-lg-7 order-sm-2 order-md-1 order-lg-1 pr-0">\r\n<h2 class="title">Gifting Made Easy-Peasy For You and Your Guests</h2>\r\n\r\n<p>Fynches takes the guessing out of gifting. No more dreaded gift duplicates, endless gift questions, or last-minute trips to a store.</p>\r\n\r\n<ul>\r\n	<li>Reduce the time your friends and family have to spend searching for a gift.</li>\r\n	<li>Avoid duplicate gifts, over-gifting, and constantly answering the &ldquo;what should we get?&rdquo; question.</li>\r\n	<li>Help friends and family gift experiences that will help your child grow and create magical memories.</li>\r\n</ul>\r\n<button class="btn common pink-btn" data-target="#largeModal" data-toggle="modal">SIGN UP FOR EARLY ACCESS</button></div>\r\n</div>\r\n</div>\r\n</section>', '2018-11-05 04:36:01', '2018-11-05 06:36:27'),
(3, 'Gifts and Experiences', 'gifts-and-experiences', '<section class="experience">\r\n<div class="container">\r\n<div class="row">\r\n<div class="col-md-12 text-center">\r\n<h2 class="title">Gifts and Experiences Your Child Will Love.</h2>\r\n\r\n<p>From swimming lessons to adventure parks we have everything you need to make your child&rsquo;s next gift an unforgettable experience.</p>\r\n<button class="btn common btn-border" data-target="#largeModal" data-toggle="modal">SIGN UP FOR EARLY ACCESS</button></div>\r\n</div>\r\n</div>\r\n</section>', '2018-11-05 04:39:09', '2018-11-05 04:39:09'),
(4, 'How It Works', 'how-it-works', '<section class="how-it-work">\r\n<div class="container">\r\n<div class="row">\r\n<div class="col-md-12">\r\n<h2 class="title">How It Works</h2>\r\n</div>\r\n</div>\r\n\r\n<div class="row">\r\n<div class="col-sm-12 col-md-4 col-lg-4 text-center pt-3">\r\n<div class="cust-icon"><img alt="flag" src="http://172.16.16.175:8000/front/img/flag.png" title="" /></div>\r\n\r\n<h3>Create Your Gift Page</h3>\r\n\r\n<p>Fynches will auto-magically create a gift page for your child that you can easily customize.</p>\r\n</div>\r\n\r\n<div class="col-sm-12 col-md-4 col-lg-4 text-center pt-3">\r\n<div class="cust-icon"><img alt="gift" src="http://172.16.16.175:8000/front/img/Gift.png" title="" /></div>\r\n\r\n<h3>Add Your Gifts</h3>\r\n\r\n<p>Choose form our amazing collection of curated gifts and experiences and add them to your gift page.</p>\r\n</div>\r\n\r\n<div class="col-sm-12 col-md-4 col-lg-4 text-center pt-3">\r\n<div class="cust-icon"><img alt="bird" src="http://172.16.16.175:8000/front/img/bird.png" title="" /></div>\r\n\r\n<h3>Share Your Gift Page</h3>\r\n\r\n<p>Easily share your Fynches gift page with friends and family so they can purchase a gift for your child.</p>\r\n</div>\r\n</div>\r\n\r\n<div class="row">\r\n<div class="col-md-12 text-center"><button class="btn common pink-btn" data-target="#largeModal" data-toggle="modal">SIGN UP FOR EARLY ACCESS</button></div>\r\n</div>\r\n</div>\r\n</section>', '2018-11-05 04:42:22', '2018-11-05 06:35:48');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(191) NOT NULL,
  `image` varchar(191) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `testimonial`
--

CREATE TABLE IF NOT EXISTS `testimonial` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `image` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `testimonial`
--

INSERT INTO `testimonial` (`id`, `name`, `image`, `description`, `author_name`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Heather M', '1541408101_Mom.png', '<p><span style="color: rgb(34, 34, 34); font-family: &quot;dejavu sans mono&quot;, monospace; font-size: 11px; white-space: pre-wrap;">Fynches is my go-to birthday solution for my daughter. Love it! </span></p>', 'Heather M', '2018-11-05 03:25:01', '2018-11-05 03:25:01', 'Active'),
(2, 'Adam M', '15414087775be0080981b43_Couple.png', '<p><span dejavu="" font-size:="" sans="" style="color: rgb(34, 34, 34); font-family: " white-space:="">Fynches is my go-to birthday solution for my daughter. Love it! </span></p>', 'Adam M', '2018-11-05 03:25:01', '2018-11-05 03:36:17', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `user_type` enum('1','2','3') NOT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `email_verify_code` varchar(191) NOT NULL,
  `verify_forgot_password` varchar(191) NOT NULL,
  `provider` enum('0','1','2') NOT NULL,
  `google_id` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `facebook_id` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `profile_image` varchar(191) NOT NULL,
  `user_status` enum('Active','InActive') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `user_type`, `password`, `remember_token`, `email_verify_code`, `verify_forgot_password`, `provider`, `google_id`, `token`, `facebook_id`, `created_at`, `updated_at`, `deleted_at`, `profile_image`, `user_status`) VALUES
(1, 'Admin', 'Test', 'admin@mail.com', '1', '$2y$10$5M66bzkIQn2pL.NLMJJBse6G6JhuUeIlavPCxtc.DnFu1NIpLXPQi', 'UOQyJW3gBk5XDco9G5L0WRTZScPKuHtDmNimNOTzt39bhLuUtEWfvZssqD8q', '', '', '0', '', '', '', NULL, '2018-10-31 23:31:43', NULL, '1541048503_Style and Supply Customizer.png', 'Active');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `child_info`
--
ALTER TABLE `child_info`
  ADD CONSTRAINT `child_info_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_flags`
--
ALTER TABLE `comment_flags`
  ADD CONSTRAINT `comment_flags_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `experience`
--
ALTER TABLE `experience`
  ADD CONSTRAINT `experience_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `funding_report`
--
ALTER TABLE `funding_report`
  ADD CONSTRAINT `funding_report_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `funding_report_experience_id_foreign` FOREIGN KEY (`experience_id`) REFERENCES `experience` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `funding_report_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mapping_custom_tag`
--
ALTER TABLE `mapping_custom_tag`
  ADD CONSTRAINT `mapping_custom_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mapping_event_media`
--
ALTER TABLE `mapping_event_media`
  ADD CONSTRAINT `mapping_event_media_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `state`
--
ALTER TABLE `state`
  ADD CONSTRAINT `state_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
