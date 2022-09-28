-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2022 at 03:11 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `AutoX`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_group`
--

CREATE TABLE `auth_group` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_group_permissions`
--

CREATE TABLE `auth_group_permissions` (
  `id` bigint(20) NOT NULL,
  `group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_permission`
--

CREATE TABLE `auth_permission` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `codename` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_permission`
--

INSERT INTO `auth_permission` (`id`, `name`, `content_type_id`, `codename`) VALUES
(1, 'Can add log entry', 1, 'add_logentry'),
(2, 'Can change log entry', 1, 'change_logentry'),
(3, 'Can delete log entry', 1, 'delete_logentry'),
(4, 'Can view log entry', 1, 'view_logentry'),
(5, 'Can add permission', 2, 'add_permission'),
(6, 'Can change permission', 2, 'change_permission'),
(7, 'Can delete permission', 2, 'delete_permission'),
(8, 'Can view permission', 2, 'view_permission'),
(9, 'Can add group', 3, 'add_group'),
(10, 'Can change group', 3, 'change_group'),
(11, 'Can delete group', 3, 'delete_group'),
(12, 'Can view group', 3, 'view_group'),
(13, 'Can add content type', 4, 'add_contenttype'),
(14, 'Can change content type', 4, 'change_contenttype'),
(15, 'Can delete content type', 4, 'delete_contenttype'),
(16, 'Can view content type', 4, 'view_contenttype'),
(17, 'Can add session', 5, 'add_session'),
(18, 'Can change session', 5, 'change_session'),
(19, 'Can delete session', 5, 'delete_session'),
(20, 'Can view session', 5, 'view_session'),
(21, 'Can add categories', 6, 'add_categories'),
(22, 'Can change categories', 6, 'change_categories'),
(23, 'Can delete categories', 6, 'delete_categories'),
(24, 'Can view categories', 6, 'view_categories'),
(25, 'Can add product', 7, 'add_product'),
(26, 'Can change product', 7, 'change_product'),
(27, 'Can delete product', 7, 'delete_product'),
(28, 'Can view product', 7, 'view_product'),
(29, 'Can add product_wishlist', 8, 'add_product_wishlist'),
(30, 'Can change product_wishlist', 8, 'change_product_wishlist'),
(31, 'Can delete product_wishlist', 8, 'delete_product_wishlist'),
(32, 'Can view product_wishlist', 8, 'view_product_wishlist'),
(33, 'Can add product_meta', 9, 'add_product_meta'),
(34, 'Can change product_meta', 9, 'change_product_meta'),
(35, 'Can delete product_meta', 9, 'delete_product_meta'),
(36, 'Can view product_meta', 9, 'view_product_meta'),
(37, 'Can add gallery image product', 10, 'add_galleryimageproduct'),
(38, 'Can change gallery image product', 10, 'change_galleryimageproduct'),
(39, 'Can delete gallery image product', 10, 'delete_galleryimageproduct'),
(40, 'Can view gallery image product', 10, 'view_galleryimageproduct'),
(41, 'Can add user', 11, 'add_myuser'),
(42, 'Can change user', 11, 'change_myuser'),
(43, 'Can delete user', 11, 'delete_myuser'),
(44, 'Can view user', 11, 'view_myuser');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) NOT NULL,
  `cat_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `cat_name`) VALUES
(3, 'Dealer Car'),
(1, 'Local'),
(2, 'Scrap');

-- --------------------------------------------------------

--
-- Table structure for table `django_admin_log`
--

CREATE TABLE `django_admin_log` (
  `id` int(11) NOT NULL,
  `action_time` datetime(6) NOT NULL,
  `object_id` longtext DEFAULT NULL,
  `object_repr` varchar(200) NOT NULL,
  `action_flag` smallint(5) UNSIGNED NOT NULL CHECK (`action_flag` >= 0),
  `change_message` longtext NOT NULL,
  `content_type_id` int(11) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `django_admin_log`
--

INSERT INTO `django_admin_log` (`id`, `action_time`, `object_id`, `object_repr`, `action_flag`, `change_message`, `content_type_id`, `user_id`) VALUES
(1, '2022-09-28 00:58:28.813757', '1', 'Local', 1, '[{\"added\": {}}]', 6, 1),
(2, '2022-09-28 00:58:31.147791', '2', 'Scrap', 1, '[{\"added\": {}}]', 6, 1),
(3, '2022-09-28 00:58:33.630684', '3', 'Dealer Car', 1, '[{\"added\": {}}]', 6, 1),
(4, '2022-09-28 01:00:50.233135', '1', 'SUZUKI SWIFT 2018', 1, '[{\"added\": {}}, {\"added\": {\"name\": \"product_meta\", \"object\": \"Product_meta object (1)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (1)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (2)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (3)\"}}]', 7, 1),
(5, '2022-09-28 01:01:56.369987', '2', 'AUDI Q8', 1, '[{\"added\": {}}, {\"added\": {\"name\": \"product_meta\", \"object\": \"Product_meta object (2)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (4)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (5)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (6)\"}}]', 7, 1),
(6, '2022-09-28 01:02:53.427161', '3', 'MASERATI QUATTROPORTE 2021', 1, '[{\"added\": {}}, {\"added\": {\"name\": \"product_meta\", \"object\": \"Product_meta object (3)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (7)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (8)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (9)\"}}]', 7, 1),
(7, '2022-09-28 01:03:12.395267', '4', 'MASERATI QUATTROPORTE 2022', 1, '[{\"added\": {}}, {\"added\": {\"name\": \"product_meta\", \"object\": \"Product_meta object (4)\"}}]', 7, 1),
(8, '2022-09-28 01:04:02.049659', '5', 'SUZUKI SWIFT 2019', 1, '[{\"added\": {}}, {\"added\": {\"name\": \"product_meta\", \"object\": \"Product_meta object (5)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (10)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (11)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (12)\"}}]', 7, 1),
(9, '2022-09-28 01:04:23.841788', '6', 'SUZUKI SWIFT 2018 1', 1, '[{\"added\": {}}, {\"added\": {\"name\": \"product_meta\", \"object\": \"Product_meta object (6)\"}}]', 7, 1),
(10, '2022-09-28 01:04:59.003144', '7', 'SUZUKI SWIFT 2023', 1, '[{\"added\": {}}, {\"added\": {\"name\": \"product_meta\", \"object\": \"Product_meta object (7)\"}}]', 7, 1),
(11, '2022-09-28 01:05:40.746030', '8', 'AUDI Q8', 1, '[{\"added\": {}}, {\"added\": {\"name\": \"product_meta\", \"object\": \"Product_meta object (8)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (13)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (14)\"}}, {\"added\": {\"name\": \"gallery image product\", \"object\": \"GalleryImageProduct object (15)\"}}]', 7, 1),
(12, '2022-09-28 01:06:11.915577', '9', 'MASERATI QUATTROPORTE 2022', 1, '[{\"added\": {}}, {\"added\": {\"name\": \"product_meta\", \"object\": \"Product_meta object (9)\"}}]', 7, 1),
(13, '2022-09-28 01:06:29.891621', '1', 'Vu Xuan Phuong', 2, '[{\"changed\": {\"fields\": [\"Avatar\"]}}]', 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `django_content_type`
--

CREATE TABLE `django_content_type` (
  `id` int(11) NOT NULL,
  `app_label` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `django_content_type`
--

INSERT INTO `django_content_type` (`id`, `app_label`, `model`) VALUES
(1, 'admin', 'logentry'),
(3, 'auth', 'group'),
(2, 'auth', 'permission'),
(4, 'contenttypes', 'contenttype'),
(6, 'products', 'categories'),
(10, 'products', 'galleryimageproduct'),
(7, 'products', 'product'),
(9, 'products', 'product_meta'),
(8, 'products', 'product_wishlist'),
(5, 'sessions', 'session'),
(11, 'users', 'myuser');

-- --------------------------------------------------------

--
-- Table structure for table `django_migrations`
--

CREATE TABLE `django_migrations` (
  `id` bigint(20) NOT NULL,
  `app` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `applied` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `django_migrations`
--

INSERT INTO `django_migrations` (`id`, `app`, `name`, `applied`) VALUES
(1, 'contenttypes', '0001_initial', '2022-09-28 00:57:28.125297'),
(2, 'contenttypes', '0002_remove_content_type_name', '2022-09-28 00:57:28.173101'),
(3, 'auth', '0001_initial', '2022-09-28 00:57:28.448046'),
(4, 'auth', '0002_alter_permission_name_max_length', '2022-09-28 00:57:28.497160'),
(5, 'auth', '0003_alter_user_email_max_length', '2022-09-28 00:57:28.502915'),
(6, 'auth', '0004_alter_user_username_opts', '2022-09-28 00:57:28.505230'),
(7, 'auth', '0005_alter_user_last_login_null', '2022-09-28 00:57:28.507237'),
(8, 'auth', '0006_require_contenttypes_0002', '2022-09-28 00:57:28.507772'),
(9, 'auth', '0007_alter_validators_add_error_messages', '2022-09-28 00:57:28.509987'),
(10, 'auth', '0008_alter_user_username_max_length', '2022-09-28 00:57:28.512377'),
(11, 'auth', '0009_alter_user_last_name_max_length', '2022-09-28 00:57:28.514892'),
(12, 'auth', '0010_alter_group_name_max_length', '2022-09-28 00:57:28.564656'),
(13, 'auth', '0011_update_proxy_permissions', '2022-09-28 00:57:28.569013'),
(14, 'auth', '0012_alter_user_first_name_max_length', '2022-09-28 00:57:28.571123'),
(15, 'users', '0001_initial', '2022-09-28 00:57:28.878638'),
(16, 'admin', '0001_initial', '2022-09-28 00:57:29.002870'),
(17, 'admin', '0002_logentry_remove_auto_add', '2022-09-28 00:57:29.011633'),
(18, 'admin', '0003_logentry_add_action_flag_choices', '2022-09-28 00:57:29.016289'),
(19, 'products', '0001_initial', '2022-09-28 00:57:29.304185'),
(20, 'sessions', '0001_initial', '2022-09-28 00:57:29.351571');

-- --------------------------------------------------------

--
-- Table structure for table `django_session`
--

CREATE TABLE `django_session` (
  `session_key` varchar(40) NOT NULL,
  `session_data` longtext NOT NULL,
  `expire_date` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `django_session`
--

INSERT INTO `django_session` (`session_key`, `session_data`, `expire_date`) VALUES
('ahd1q9eht11iouck40hon8k6tktdbw49', '.eJxVjDsOwjAQBe_iGln-rH-U9DmDtbYXHEC2FCcV4u4QKQW0b2bei0Xc1hq3QUucCzszyU6_W8L8oLaDcsd26zz3ti5z4rvCDzr41As9L4f7d1Bx1G-dw1WjAQrBCynIKBlMEtJ7m9FY0KALSasJldPFiQDKJgCnCRDRiMzeH7p4Nto:1odLP2:PAiLpn5ptVsLXEr1jB3EArmbd_OCf4gPTjVwtJ8iGp8', '2022-10-12 00:58:20.242256');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) NOT NULL,
  `product_name` varchar(500) NOT NULL,
  `product_description` longtext NOT NULL,
  `product_price` varchar(20) NOT NULL,
  `product_image` varchar(100) NOT NULL,
  `product_create_time` date NOT NULL,
  `category_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_description`, `product_price`, `product_image`, `product_create_time`, `category_id`) VALUES
(1, 'SUZUKI SWIFT 2018', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/09/28/Rectangle_13_1.png', '2022-09-28', 1),
(2, 'AUDI Q8', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', '', '2022-09-28', 1),
(3, 'MASERATI QUATTROPORTE 2021', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/09/28/submit-a-car_1_2.png', '2022-09-28', 1),
(4, 'MASERATI QUATTROPORTE 2022', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/09/28/submit-a-car_1.png', '2022-09-28', 2),
(5, 'SUZUKI SWIFT 2019', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/09/28/Rectangle_13_HYSHJRv.png', '2022-09-28', 2),
(6, 'SUZUKI SWIFT 2018 1', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/09/28/submit-a-car_1_OTdxyyE.png', '2022-09-28', 2),
(7, 'SUZUKI SWIFT 2023', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/09/28/Rectangle_14_1.png', '2022-09-28', 2),
(8, 'AUDI Q8', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/09/28/Rectangle_14.png', '2022-09-28', 2),
(9, 'MASERATI QUATTROPORTE 2022', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/09/28/Login_page.png', '2022-09-28', 2);

-- --------------------------------------------------------

--
-- Table structure for table `product_gallery`
--

CREATE TABLE `product_gallery` (
  `id` bigint(20) NOT NULL,
  `image` varchar(100) NOT NULL,
  `prd_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_gallery`
--

INSERT INTO `product_gallery` (`id`, `image`, `prd_id`) VALUES
(1, 'uploads/2022/09/28/Home_non-user.png', 1),
(2, 'uploads/2022/09/28/Home_logged-in_user.png', 1),
(3, 'uploads/2022/09/28/autox-squareLogo-1630141386654.png', 1),
(4, 'uploads/2022/09/28/Home_non-user_d5KfC7U.png', 2),
(5, 'uploads/2022/09/28/Rectangle_13_1_ueGiONK.png', 2),
(6, 'uploads/2022/09/28/image_1.png', 2),
(7, 'uploads/2022/09/28/Rectangle_13.png', 3),
(8, 'uploads/2022/09/28/threatmark-flubot-malware-all-you-need-to-know-1-1647092544130636196132.png', 3),
(9, 'uploads/2022/09/28/Register_page.png', 3),
(10, 'uploads/2022/09/28/Register_page_s0FzuJS.png', 5),
(11, 'uploads/2022/09/28/Rectangle_14_2.png', 5),
(12, 'uploads/2022/09/28/images.jpeg', 5),
(13, 'uploads/2022/09/28/Rectangle_14_3mmb94Y.png', 8),
(14, 'uploads/2022/09/28/threatmark-flubot-malware-all-you-need-to-know-1-16470925441306361961_GIjmztU.png', 8),
(15, 'uploads/2022/09/28/Rectangle_14_1_pJUrRy6.png', 8);

-- --------------------------------------------------------

--
-- Table structure for table `product_meta`
--

CREATE TABLE `product_meta` (
  `id` bigint(20) NOT NULL,
  `product_kilometer` varchar(20) NOT NULL,
  `product_fuel` varchar(20) NOT NULL,
  `product_cheaper` int(11) NOT NULL,
  `product_post_time` date NOT NULL,
  `product_at` varchar(20) NOT NULL,
  `product_auction_price` int(11) NOT NULL,
  `product_time_remaining` date NOT NULL,
  `product_condition` varchar(50) NOT NULL,
  `product_color` varchar(50) NOT NULL,
  `product_reg_date` date NOT NULL,
  `product_body_type` varchar(100) NOT NULL,
  `product_manufacturing_year` int(11) NOT NULL,
  `product_power` int(11) NOT NULL,
  `product_seats` int(11) NOT NULL,
  `product_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_meta`
--

INSERT INTO `product_meta` (`id`, `product_kilometer`, `product_fuel`, `product_cheaper`, `product_post_time`, `product_at`, `product_auction_price`, `product_time_remaining`, `product_condition`, `product_color`, `product_reg_date`, `product_body_type`, `product_manufacturing_year`, `product_power`, `product_seats`, `product_id`) VALUES
(1, '29400', 'Gasoline', 125000000, '2022-09-28', 'AT', 326750000, '2022-10-15', 'Used', 'Gray', '2022-09-28', 'Hatchback', 2018, 182, 5, 1),
(2, '29400', 'Gasoline', 125000000, '2022-09-28', 'AT', 326750000, '2022-10-05', 'Used', 'Gray', '2022-09-28', 'Hatchback', 2018, 182, 5, 2),
(3, '29400', 'Gasoline', 125000000, '2022-09-28', 'AT', 326750000, '2022-09-28', 'Used', 'Gray', '2022-09-28', 'Hatchback', 2018, 182, 5, 3),
(4, '29400', 'Gasoline', 125000000, '2022-09-28', 'AT', 326750000, '2022-09-28', 'Used', 'Gray', '2022-09-28', 'Hatchback', 2018, 182, 5, 4),
(5, '29400', 'Gasoline', 125000000, '2022-09-28', 'AT', 326750000, '2022-09-28', 'Used', 'Gray', '2022-09-28', 'Hatchback', 2018, 182, 5, 5),
(6, '29400', 'Gasoline', 125000000, '2022-09-28', 'AT', 326750000, '2022-09-28', 'Used', 'Gray', '2022-09-28', 'Hatchback', 2018, 182, 5, 6),
(7, '29400', 'Gasoline', 125000000, '2022-09-28', 'AT', 326750000, '2022-10-05', 'Used', 'Gray', '2022-09-28', 'Hatchback', 2018, 182, 5, 7),
(8, '29400', 'Gasoline', 125000000, '2022-09-28', 'AT', 326750000, '2022-09-28', 'Used', 'Gray', '2022-09-28', 'Hatchback', 2018, 182, 5, 8),
(9, '29400', 'Gasoline', 125000000, '2022-09-28', 'AT', 326750000, '2022-09-28', 'Used', 'Gray', '2022-09-28', 'Hatchback', 2018, 182, 5, 9);

-- --------------------------------------------------------

--
-- Table structure for table `product_wishlist`
--

CREATE TABLE `product_wishlist` (
  `id` bigint(20) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_wishlist`
--

INSERT INTO `product_wishlist` (`id`, `prd_id`, `user_id`, `date_added`) VALUES
(1, 1, 1, '2022-09-28');

-- --------------------------------------------------------

--
-- Table structure for table `users_myuser`
--

CREATE TABLE `users_myuser` (
  `id` bigint(20) NOT NULL,
  `last_login` datetime(6) DEFAULT NULL,
  `is_superuser` tinyint(1) NOT NULL,
  `is_staff` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `date_joined` datetime(6) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(128) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `avatar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_myuser`
--

INSERT INTO `users_myuser` (`id`, `last_login`, `is_superuser`, `is_staff`, `is_active`, `date_joined`, `phone`, `password`, `fullname`, `email`, `avatar`) VALUES
(1, '2022-09-28 00:58:20.000000', 1, 1, 1, '2022-09-28 00:58:17.000000', '0877946666', 'pbkdf2_sha256$320000$GrsiyAZCu71ylqk4QdCJbS$aqiEbc67nWDjMLg+W+Z4igD3iLh9sNeLcsHVrY2gNSQ=', 'Vu Xuan Phuong', 'Vxphuongictu998@gmail.com', 'uploads/2022/09/28/threatmark-flubot-malware-all-you-need-to-know-1-16470925441306361961_aSHRPdc.png');

-- --------------------------------------------------------

--
-- Table structure for table `users_myuser_groups`
--

CREATE TABLE `users_myuser_groups` (
  `id` bigint(20) NOT NULL,
  `myuser_id` bigint(20) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_myuser_user_permissions`
--

CREATE TABLE `users_myuser_user_permissions` (
  `id` bigint(20) NOT NULL,
  `myuser_id` bigint(20) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_group`
--
ALTER TABLE `auth_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `auth_group_permissions`
--
ALTER TABLE `auth_group_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_group_permissions_group_id_permission_id_0cd325b0_uniq` (`group_id`,`permission_id`),
  ADD KEY `auth_group_permissio_permission_id_84c5c92e_fk_auth_perm` (`permission_id`);

--
-- Indexes for table `auth_permission`
--
ALTER TABLE `auth_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_permission_content_type_id_codename_01ab375a_uniq` (`content_type_id`,`codename`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cat_name` (`cat_name`);

--
-- Indexes for table `django_admin_log`
--
ALTER TABLE `django_admin_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `django_admin_log_content_type_id_c4bce8eb_fk_django_co` (`content_type_id`),
  ADD KEY `django_admin_log_user_id_c564eba6_fk_users_myuser_id` (`user_id`);

--
-- Indexes for table `django_content_type`
--
ALTER TABLE `django_content_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `django_content_type_app_label_model_76bd3d3b_uniq` (`app_label`,`model`);

--
-- Indexes for table `django_migrations`
--
ALTER TABLE `django_migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `django_session`
--
ALTER TABLE `django_session`
  ADD PRIMARY KEY (`session_key`),
  ADD KEY `django_session_expire_date_a5c62663` (`expire_date`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_a7a3a156_fk_categories_id` (`category_id`);

--
-- Indexes for table `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_gallery_prd_id_a742e814_fk_products_id` (`prd_id`);

--
-- Indexes for table `product_meta`
--
ALTER TABLE `product_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_meta_product_id_3ea46e52_fk_products_id` (`product_id`);

--
-- Indexes for table `product_wishlist`
--
ALTER TABLE `product_wishlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_myuser`
--
ALTER TABLE `users_myuser`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_myuser_groups`
--
ALTER TABLE `users_myuser_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_myuser_groups_myuser_id_group_id_701de95c_uniq` (`myuser_id`,`group_id`),
  ADD KEY `users_myuser_groups_group_id_320a3e7b_fk_auth_group_id` (`group_id`);

--
-- Indexes for table `users_myuser_user_permissions`
--
ALTER TABLE `users_myuser_user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_myuser_user_permis_myuser_id_permission_id_bfff4a24_uniq` (`myuser_id`,`permission_id`),
  ADD KEY `users_myuser_user_pe_permission_id_6f8831ec_fk_auth_perm` (`permission_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_group`
--
ALTER TABLE `auth_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_group_permissions`
--
ALTER TABLE `auth_group_permissions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_permission`
--
ALTER TABLE `auth_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `django_admin_log`
--
ALTER TABLE `django_admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `django_content_type`
--
ALTER TABLE `django_content_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `django_migrations`
--
ALTER TABLE `django_migrations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_gallery`
--
ALTER TABLE `product_gallery`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_meta`
--
ALTER TABLE `product_meta`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_wishlist`
--
ALTER TABLE `product_wishlist`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_myuser`
--
ALTER TABLE `users_myuser`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_myuser_groups`
--
ALTER TABLE `users_myuser_groups`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_myuser_user_permissions`
--
ALTER TABLE `users_myuser_user_permissions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_group_permissions`
--
ALTER TABLE `auth_group_permissions`
  ADD CONSTRAINT `auth_group_permissio_permission_id_84c5c92e_fk_auth_perm` FOREIGN KEY (`permission_id`) REFERENCES `auth_permission` (`id`),
  ADD CONSTRAINT `auth_group_permissions_group_id_b120cbf9_fk_auth_group_id` FOREIGN KEY (`group_id`) REFERENCES `auth_group` (`id`);

--
-- Constraints for table `auth_permission`
--
ALTER TABLE `auth_permission`
  ADD CONSTRAINT `auth_permission_content_type_id_2f476e4b_fk_django_co` FOREIGN KEY (`content_type_id`) REFERENCES `django_content_type` (`id`);

--
-- Constraints for table `django_admin_log`
--
ALTER TABLE `django_admin_log`
  ADD CONSTRAINT `django_admin_log_content_type_id_c4bce8eb_fk_django_co` FOREIGN KEY (`content_type_id`) REFERENCES `django_content_type` (`id`),
  ADD CONSTRAINT `django_admin_log_user_id_c564eba6_fk_users_myuser_id` FOREIGN KEY (`user_id`) REFERENCES `users_myuser` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_a7a3a156_fk_categories_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD CONSTRAINT `product_gallery_prd_id_a742e814_fk_products_id` FOREIGN KEY (`prd_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_meta`
--
ALTER TABLE `product_meta`
  ADD CONSTRAINT `product_meta_product_id_3ea46e52_fk_products_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `users_myuser_groups`
--
ALTER TABLE `users_myuser_groups`
  ADD CONSTRAINT `users_myuser_groups_group_id_320a3e7b_fk_auth_group_id` FOREIGN KEY (`group_id`) REFERENCES `auth_group` (`id`),
  ADD CONSTRAINT `users_myuser_groups_myuser_id_6c79e2c5_fk_users_myuser_id` FOREIGN KEY (`myuser_id`) REFERENCES `users_myuser` (`id`);

--
-- Constraints for table `users_myuser_user_permissions`
--
ALTER TABLE `users_myuser_user_permissions`
  ADD CONSTRAINT `users_myuser_user_pe_myuser_id_7135c2f9_fk_users_myu` FOREIGN KEY (`myuser_id`) REFERENCES `users_myuser` (`id`),
  ADD CONSTRAINT `users_myuser_user_pe_permission_id_6f8831ec_fk_auth_perm` FOREIGN KEY (`permission_id`) REFERENCES `auth_permission` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
