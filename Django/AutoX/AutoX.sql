-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2022 at 03:28 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.34

use heroku_aac02b3bf470cc7;
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
(29, 'Can add product_attributes', 8, 'add_product_attributes'),
(30, 'Can change product_attributes', 8, 'change_product_attributes'),
(31, 'Can delete product_attributes', 8, 'delete_product_attributes'),
(32, 'Can view product_attributes', 8, 'view_product_attributes'),
(33, 'Can add product_wishlist', 9, 'add_product_wishlist'),
(34, 'Can change product_wishlist', 9, 'change_product_wishlist'),
(35, 'Can delete product_wishlist', 9, 'delete_product_wishlist'),
(36, 'Can view product_wishlist', 9, 'view_product_wishlist'),
(37, 'Can add product_attribute_value', 10, 'add_product_attribute_value'),
(38, 'Can change product_attribute_value', 10, 'change_product_attribute_value'),
(39, 'Can delete product_attribute_value', 10, 'delete_product_attribute_value'),
(40, 'Can view product_attribute_value', 10, 'view_product_attribute_value'),
(41, 'Can add jointablesmodel', 11, 'add_jointablesmodel'),
(42, 'Can change jointablesmodel', 11, 'change_jointablesmodel'),
(43, 'Can delete jointablesmodel', 11, 'delete_jointablesmodel'),
(44, 'Can view jointablesmodel', 11, 'view_jointablesmodel'),
(45, 'Can add gallery image product', 12, 'add_galleryimageproduct'),
(46, 'Can change gallery image product', 12, 'change_galleryimageproduct'),
(47, 'Can delete gallery image product', 12, 'delete_galleryimageproduct'),
(48, 'Can view gallery image product', 12, 'view_galleryimageproduct'),
(49, 'Can add cart', 13, 'add_cart'),
(50, 'Can change cart', 13, 'change_cart'),
(51, 'Can delete cart', 13, 'delete_cart'),
(52, 'Can view cart', 13, 'view_cart'),
(53, 'Can add history_order', 14, 'add_history_order'),
(54, 'Can change history_order', 14, 'change_history_order'),
(55, 'Can delete history_order', 14, 'delete_history_order'),
(56, 'Can view history_order', 14, 'view_history_order'),
(57, 'Can add comment', 15, 'add_comment'),
(58, 'Can change comment', 15, 'change_comment'),
(59, 'Can delete comment', 15, 'delete_comment'),
(60, 'Can view comment', 15, 'view_comment'),
(61, 'Can add user', 16, 'add_myuser'),
(62, 'Can change user', 16, 'change_myuser'),
(63, 'Can delete user', 16, 'delete_myuser'),
(64, 'Can view user', 16, 'view_myuser'),
(65, 'Can add located', 17, 'add_located'),
(66, 'Can change located', 17, 'change_located'),
(67, 'Can delete located', 17, 'delete_located'),
(68, 'Can view located', 17, 'view_located'),
(69, 'Can add card', 18, 'add_card'),
(70, 'Can change card', 18, 'change_card'),
(71, 'Can delete card', 18, 'delete_card'),
(72, 'Can view card', 18, 'view_card');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `prd_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `quantity`, `user_id`, `date_added`, `prd_id`) VALUES
(88, 1, 1, '2022-10-20', 3);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`) VALUES
(3, 'Dealer Car'),
(2, 'Local'),
(1, 'Scrap');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `msg` varchar(500) NOT NULL,
  `create_time` date NOT NULL,
  `prd_id` int(11) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `rating` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `fullname`, `email`, `msg`, `create_time`, `prd_id`, `ip_address`, `rating`) VALUES
(1, 'Vũ Xuân Phương', 'Vxphuongictu998@gmail.com', 'abcdef', '2022-10-21', 1, '127.0.0.1', 2),
(2, 'Vũ Xuân Phương', 'Vxphuongictu998@gmail.com', 'a ư ư', '2022-10-21', 1, '127.0.0.1', 5);

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
(13, 'products', 'cart'),
(6, 'products', 'categories'),
(15, 'products', 'comment'),
(12, 'products', 'galleryimageproduct'),
(14, 'products', 'history_order'),
(11, 'products', 'jointablesmodel'),
(7, 'products', 'product'),
(8, 'products', 'product_attributes'),
(10, 'products', 'product_attribute_value'),
(9, 'products', 'product_wishlist'),
(5, 'sessions', 'session'),
(18, 'users', 'card'),
(17, 'users', 'located'),
(16, 'users', 'myuser');

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
(1, 'contenttypes', '0001_initial', '2022-10-13 07:54:28.564941'),
(2, 'contenttypes', '0002_remove_content_type_name', '2022-10-13 07:54:28.619433'),
(3, 'auth', '0001_initial', '2022-10-13 07:54:28.884410'),
(4, 'auth', '0002_alter_permission_name_max_length', '2022-10-13 07:54:28.939125'),
(5, 'auth', '0003_alter_user_email_max_length', '2022-10-13 07:54:28.946627'),
(6, 'auth', '0004_alter_user_username_opts', '2022-10-13 07:54:28.953774'),
(7, 'auth', '0005_alter_user_last_login_null', '2022-10-13 07:54:28.962132'),
(8, 'auth', '0006_require_contenttypes_0002', '2022-10-13 07:54:28.963766'),
(9, 'auth', '0007_alter_validators_add_error_messages', '2022-10-13 07:54:28.969224'),
(10, 'auth', '0008_alter_user_username_max_length', '2022-10-13 07:54:28.975289'),
(11, 'auth', '0009_alter_user_last_name_max_length', '2022-10-13 07:54:28.980550'),
(12, 'auth', '0010_alter_group_name_max_length', '2022-10-13 07:54:29.035935'),
(13, 'auth', '0011_update_proxy_permissions', '2022-10-13 07:54:29.042426'),
(14, 'auth', '0012_alter_user_first_name_max_length', '2022-10-13 07:54:29.048647'),
(15, 'users', '0001_initial', '2022-10-13 07:54:29.374444'),
(16, 'admin', '0001_initial', '2022-10-13 07:54:29.506582'),
(17, 'admin', '0002_logentry_remove_auto_add', '2022-10-13 07:54:29.518098'),
(18, 'admin', '0003_logentry_add_action_flag_choices', '2022-10-13 07:54:29.530608'),
(19, 'users', '0002_myuser_username', '2022-10-13 07:54:29.588466'),
(20, 'users', '0003_alter_myuser_username', '2022-10-13 07:54:29.597923'),
(21, 'users', '0004_remove_myuser_username', '2022-10-13 07:54:29.629198'),
(22, 'users', '0005_myuser_username', '2022-10-13 07:54:29.681783'),
(23, 'users', '0006_alter_myuser_avatar', '2022-10-13 07:54:29.685751'),
(24, 'users', '0007_alter_myuser_avatar', '2022-10-13 07:54:29.689396'),
(25, 'users', '0008_alter_myuser_avatar', '2022-10-13 07:54:29.693451'),
(26, 'users', '0009_alter_myuser_avatar', '2022-10-13 07:54:29.698133'),
(27, 'users', '0010_alter_myuser_avatar', '2022-10-13 07:54:29.701431'),
(28, 'users', '0011_located', '2022-10-13 07:54:29.774020'),
(29, 'users', '0012_located_fullname_located_phone', '2022-10-13 07:54:29.880492'),
(30, 'users', '0013_rename_user_id_located_user', '2022-10-13 07:54:30.590837'),
(31, 'users', '0014_located_is_use', '2022-10-13 07:54:30.663200'),
(32, 'users', '0015_rename_is_use_located_is_default', '2022-10-13 07:54:30.695624'),
(33, 'users', '0016_alter_located_located_type', '2022-10-13 07:54:30.703599'),
(34, 'users', '0017_card', '2022-10-13 07:54:30.813310'),
(35, 'products', '0001_initial', '2022-10-13 07:54:31.365893'),
(36, 'products', '0002_alter_cart_prd', '2022-10-13 07:54:31.374653'),
(37, 'products', '0003_order_history', '2022-10-13 07:54:31.400489'),
(38, 'products', '0004_comment_history_order_delete_order_history_and_more', '2022-10-13 07:54:31.581612'),
(39, 'products', '0005_comment', '2022-10-13 07:54:31.679637'),
(40, 'sessions', '0001_initial', '2022-10-13 07:54:31.732581'),
(41, 'products', '0006_history_order_payment_method', '2022-10-13 08:13:53.963367'),
(42, 'products', '0007_history_order_invoice_code', '2022-10-18 04:23:07.132747'),
(43, 'products', '0008_comment_ip_address_comment_rating', '2022-10-21 01:28:52.593790');

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
('021i7dixpofsbc4k7obcx4qkqsv69t7c', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1oju9z:qY0PeQfcz0vD4MG0rqpQcgo4yMUXulWC91eeR9KHghk', '2022-10-16 04:41:15.720919'),
('0fa8zrsvdeyu0ucizci3zs2jxx3oo0ee', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okFjt:JfwKtga0LIIvazambZwtHasV8YqbDLRm4FTB3-8wvQ0', '2022-10-17 03:43:45.922112'),
('0toncjjlmoyd0rf7rz4qpir3ddi94nwd', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1olMSQ:2abhSz6B7xzuFHcy_p7qq7YiKgt3_ztvmRjI-SnUiiE', '2022-10-20 05:06:18.857318'),
('17jsilt2bk7lie8hd45bspukvdehiaac', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okEHy:R21nOmHfkPXFuPzwkyGBWzxu6nyAaP443K0mXDKB_p0', '2022-10-17 02:10:50.390755'),
('1xaxfzi929jcbfqfm60naxfch789v0u6', '.eJxVjstqwzAURP_lroO5ermyd0lrumkorT9AXL1iN40ULAcaQv69NmTRbmfOHOYGhi7zYC4lTGb00AKDzd_MkjuGtBb-i9IhVy6neRpttSLVoy3VPvvwvXuw_wQDlWFZC0sSldKBa64FaitkTU3gEdFHrG2shbKRy4ZZ0tLzyHT9RA1jWqGTTqzSEkoZczLh5zxOV2gVIm6g77b7t67vob3Bma6nkGZzCvOQ19Mfn8_vL90ytpSOxi0vl3D7uoP7_RcOUFCF:1okjSB:jqeNOKzwVrYB7tGKUX3VlOptzQdiqcPT0Gi42q7lXIo', '2022-10-18 11:27:27.198685'),
('3eewzfiavx8bqthw27nif52ezb8m5uml', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1olAGo:qZhwR2s-aXV0fwmK_jemQ4juUe2_5satXajmBAANciE', '2022-10-19 16:05:30.311956'),
('5fkle633onge72e3fisi0h74lyqdhhgy', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okL5n:pnlLUM7l44rJsApkNtb0phpVHA8sim5JXw2oWB51rkM', '2022-10-17 09:26:43.500223'),
('5umydh0wu7pqxvd4eda8znkwg0udo3ps', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1ok4sZ:sW30b1N2x8KZvYDkeBdp53XTcTyIetTLI6uoi7ELWF0', '2022-10-16 16:07:59.746045'),
('6khs730hrsez208762gtpnohp8fazcc5', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1ojFBI:kpHqmnoV2nC6OzFpjNYsJq-0tt-JyShT3iKq85Gof2g', '2022-10-14 08:55:52.418080'),
('72erfogi6qx7jk8bbqox95fb9121opw0', '.eJxVkEtrg0AUhf9KmHWQ8dnRVU1xUYhpwFLa1XDnoU6jo6gDkZD_3pkQQrM933cPh3tBFMzSUjPLiSqBMuSj7f-MAT9J7YD4Bd0MHh_0MinmOcW709krByG73d19Kmhhbu11yCDCcUxkQAISYsLCKIFUBjXGosYJq5MwZnUQpT4DEomg9knyAqnvkxjziIeudJbzrAZN5XlU04qyGGO8RVWRl_uiqlB2QSOsvdQL7eXSDm50_lnSj8P-_VDYAgb6RLldasHX2-6Y_7jQrHZlbbpOQ38jZvNtQG-OrRl08zBkD6pz-DzegOKLSVPy2rjcfqV_mCDEZJc6V8nFtqLr9Q9rVXbH:1okSRR:5tRluwLEiipVH2VRZqXx2ZALxuqG1btMXIR4HMZW0FU', '2022-10-17 17:17:33.125013'),
('7vc4vnoc663ap7e0y6ycmrioj79w92wm', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okJmw:lrwu_-yRaKKG3vhlHbH7O1JBUfRG_iTE4EM6T903JwU', '2022-10-17 08:03:10.172201'),
('a3t5ab30kzf7qqcnwlgcxxz1mdls9mt3', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1ok1t3:taCB0Ca-nh9tiRNc60_2yFKMO2FAGvNYHBBxzJfLzwM', '2022-10-16 12:56:17.482111'),
('atgygo8zyodhdgybktll2j96avvciipu', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okznp:unUuOiFtFXTszB54p0PJzd2rBv22_7-5dARSQr2Hj0k', '2022-10-19 04:54:53.398706'),
('b3mhnvpuq7qx1d43tyli3prf0bxsk1nx', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1olhsH:RjTpNACYBTqXfkIt54ImMFdPW6xidnjKCsCGHv6-yyM', '2022-10-21 03:58:25.182747'),
('edtjzmw4g3mg3z92whr2dpk9s4mfahrn', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1oit6A:QPKsqGKtgzY1gD1ZE48iv7r3_HGdX3agg34P-9IFva8', '2022-10-13 09:21:06.960585'),
('j9no594xm804f5gl1zvbfd7ayqb6s4rk', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1ojHTP:NjFdMQUJaT4hED9ZcDqyW_PpBd1lyajXqaCmIg2QDJI', '2022-10-14 11:22:43.699886'),
('k67ol83xk612zlmjijvkdqq89nb8fh4m', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okPhZ:FFS6wTVapHJ1beq4JJO4aQic_s1pCMdvtSOsspXK49U', '2022-10-17 14:22:01.738271'),
('kgot0adev3k3r3nd6rzztvsf8bjzp08d', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1ojGUF:F_2K6LWLxMC8N2Cp-g0xbVpARio_iXphjWABQxhFAvE', '2022-10-14 10:19:31.243945'),
('lalgr90xkutu59d2nupxhe1wps1xukp0', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okGvQ:3n7meIJmK_WPuC79LKPzV4h9DqptUqufmjN1LXXD5cA', '2022-10-17 04:59:44.451149'),
('ngu8oy7jfz7c5yxfg4qfr8eif8jub8q5', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1ojvTE:kB_vyLNpqynrLICvTAbFa8jB8VZpH6pG2ujvbBxuw8A', '2022-10-16 06:05:12.702160'),
('nqdfez2bzo7a1omjwedi0daj4c41anra', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okFcL:wmE7u8lDUGdg1M7bO5-dMasQXxpoXezIfjY1rZ0xXaQ', '2022-10-17 03:35:57.187344'),
('o1shclmoa1js0qe52ant9f43c41jgnlt', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1olgAJ:WlCdohgeV1V2JOr-N72QmHBQKxIwbOiaZsAwy0EFVWU', '2022-10-21 02:08:55.727077'),
('oz9lq9jg4llcr3i6njf8jsxz6hax8uog', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okM3E:VBkK3vWWTINF_rCK_8ycA25YoKQ-B3Cv5QS0d815yVo', '2022-10-17 10:28:08.385202'),
('q8517d8pwt0ecd775w55b7w01i0lmejr', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okH2Y:oDL7YaufalIwV3HquDZGpBbWmht2nw4YJunOzARINbQ', '2022-10-17 05:07:06.699444'),
('qbz7539lcjhxmuf9duioacjcoabf8dnq', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okySp:aGhuG_K43UQtujm7YvS5QMy9B7ukQpimCfTAxLSuhto', '2022-10-19 03:29:07.362475'),
('rmq886z1b9w1e1djrzgpr59payd51vhn', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okFka:YNqIKzJmAhSqhf44CwtTO8GdosNrHwU65PHQmpirFoA', '2022-10-17 03:44:28.988952'),
('ryertjgbum9mzf44cb05vx3m6hn40ps2', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okx8z:k2_T6PUSb4OQKd_3IVJ6UifopWzDy4SgKkNpGbgvIoQ', '2022-10-19 02:04:33.116799'),
('tt33xr84rxpo56c7ij5h08i2quw73q51', '.eJxVUdluozAU_RekvnWIjVlMpNEIVAhDs5TQJlVekA1mSYghGEho1X8fR9OXSlf3Smd7uOdTiT1ntfTiWJl_Ki2Zzoz3yZn1ZZMpc8V5XSWb9fLv2lMeFUr4KUmbjEnCe3clQoa-TIaulkDZ962Yz2aC8Iw2N5UXhNdDwwt15HMMMJ7xGhmzdhDliDS1Lds_0vh7oMF42x5Gz6seNJ8cnUXoxxnv1puw2JNd1lpoQmdMjwKL19X12mI3_vDCKogBtl-sdpCu694PNodD5dRXi9dxGwGbxMvCMuuPaHTe_fx5r7EhJIto6WD_8m6tUfEmje6lC8a3bkp9Ozs2W8udwguMFgdndzr6u1dD35Zrp42s8wovD7UOIzFauTndqLRGLQk1owxysMPXwsnD1F1ki2U3uma1yGkFN6dRyrTncXoKtsMOXI6nNwPvX6Ji48SS4UbYBPIG4vqAnuTId_bNiXH5S8PQIMK_kEEsC2BmpxCnGDJIdWrrNsih3KbGlK9HJflfgWBdUt0Lg8oPjJJURt6J7Eh40ahpw_uuoupdon6zQl3JUmv3W_sjoCSilG5EiQ4MAzMNaxgBTJFuEptpOQBZDkyam8iguabbkBKsZ1oOsWkRG0JsgFRP0T1UMCGqhifs1lbdpMwNAMDXPyfhzEU:1okdn3:x9BtTxm9JYgbu3kVY27Q7aik9UXBfyiMiejxfE0CuxM', '2022-10-18 05:24:37.550001'),
('ufp2dmw19v8qpzvt0oahrzkkankfnxdi', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1ok3G4:dJ5mhmv_V04Zxp5qcAMWD5AP-oSRgqy6nQLrKf3Qq24', '2022-10-16 14:24:08.337959'),
('yflj0kcdri55dcz1c8dghdfho8zuid1b', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1okNWk:nFwn_W2nXvmE0m0Cciw-Uev9UvAx_RrfGhsDBz4nZA8', '2022-10-17 12:02:42.778570'),
('zxb6am3w2kfrolnqe77i5acg64fzhki9', '.eJxVjDsOwjAQBe_iGlnrLw4lPWewdv0hBmSjOJFAiLtDpBTQvpk3L-ZxmUe_9DT5EtmBCbb73QjDNdUVxAvWc-Oh1XkqxFeFb7TzU4vpdtzcv8CIffy-FaEGY1ySTjoFjpS2OCSZAWIGS9kqQ1nqQRA6HWUWzu5xEMIZCDqoNdpT76VVnx73Mj3ZwQDA-wMWjz7a:1olOCC:Z_STN7_MLm7HJIqLpIAy5_XTw-JzLICttYeRtpP-cb4', '2022-10-20 06:57:40.260737');

-- --------------------------------------------------------

--
-- Table structure for table `join_table_models`
--

CREATE TABLE `join_table_models` (
  `id` bigint(20) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_description` varchar(5000) NOT NULL,
  `product_price` varchar(20) NOT NULL,
  `product_image` varchar(5000) NOT NULL,
  `attribute_name` varchar(200) NOT NULL,
  `attribute_value` varchar(200) NOT NULL,
  `post_time` date NOT NULL,
  `cat_id` varchar(100) NOT NULL,
  `cat_name` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(500) NOT NULL,
  `product_description` longtext NOT NULL,
  `product_price` varchar(20) NOT NULL,
  `product_image` varchar(100) NOT NULL,
  `product_create_time` date NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_description`, `product_price`, `product_image`, `product_create_time`, `category_id`) VALUES
(1, 'SUZUKI SWIFT 2018', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/10/06/Rectangle_13_3.png', '2022-10-06', 3),
(2, 'SUZUKI SWIFT 2018 1', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/10/06/Rectangle_13.png', '2022-10-06', 3),
(3, 'SUZUKI SWIFT 2018', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/10/06/Rectangle_14_1.png', '2022-10-06', 3),
(4, 'AUDI Q8', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/10/06/Why-is-Ransomware-Successful.png', '2022-10-06', 3),
(5, 'AUDI Q9', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/10/06/Rectangle_13_2_vndKdNr.png', '2022-10-06', 3),
(6, 'MASERATI QUATTROPORTE 2021', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/10/06/threatmark-flubot-malware-all-you-need-to-know-1-1647092544130636196132.png', '2022-10-06', 3),
(7, 'MASERATI QUATTROPORTE 2020', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/10/06/Rectangle_14_2_xiMbt6l.png', '2022-10-06', 3),
(8, 'MASERATI QUATTROPORTE 2018', 'Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control, Bluetooth hands free telephone connection, Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display Digital clock, Door ajar warning lamp, Fuel consumption screen, Lane departure warning system, Lights on warning, Low fuel level warning light, Navigation system, Outside temperature display. Adaptive cruise control, LED daytime running lights, LED Headlights, Steering wheel audio controls, Adaptive cruise control.', '326750000', 'uploads/2022/10/06/Register_page.png', '2022-10-06', 3);

-- --------------------------------------------------------

--
-- Table structure for table `products_history_order`
--

CREATE TABLE `products_history_order` (
  `id` bigint(20) NOT NULL,
  `order_data` varchar(1000) NOT NULL,
  `transport_fee` int(11) NOT NULL,
  `total_payment` int(11) NOT NULL,
  `order_time` date NOT NULL,
  `order_status` int(11) NOT NULL,
  `located_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `payment_method` tinyint(1) NOT NULL,
  `invoice_code` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_history_order`
--

INSERT INTO `products_history_order` (`id`, `order_data`, `transport_fee`, `total_payment`, `order_time`, `order_status`, `located_id`, `user_id`, `payment_method`, `invoice_code`) VALUES
(59, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"},{\"product_id\":\"4\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-18', 2, 1, 1, 0, 'AutoX_checkout_2022-10-18 09:53:38.286041'),
(67, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-18', 3, 1, 1, 1, 'AutoX_checkout_2022-10-18 10:22:44.744312'),
(70, '[{\"product_id\":\"5\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 4, 1, 1, 1, 'AutoX_checkout_2022-10-19 02:51:38.635597'),
(71, '[{\"product_id\":\"6\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 1, 1, 1, 1, 'AutoX_checkout_2022-10-19 02:51:47.616745'),
(72, '[{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 1, 1, 1, 1, 'AutoX_checkout_2022-10-19 02:51:56.819208'),
(73, '[{\"product_id\":\"4\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 4, 1, 1, 1, 'AutoX_checkout_2022-10-19 02:52:04.818064'),
(74, '[{\"product_id\":\"5\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 1, 1, 1, 1, 'AutoX_checkout_2022-10-19 02:53:55.021805'),
(75, '[{\"product_id\":\"6\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 1, 1, 1, 1, 'AutoX_checkout_2022-10-19 02:54:03.986303'),
(76, '[{\"product_id\":\"4\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 1, 1, 1, 1, 'AutoX_checkout_2022-10-19 02:54:13.265935'),
(77, '[{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 1, 1, 1, 1, 'AutoX_checkout_2022-10-19 02:54:22.220976'),
(78, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 1, 1, 1, 1, 'AutoX_checkout_2022-10-19 13:59:15.071407'),
(79, '[{\"product_id\":\"3\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 1, 1, 1, 1, 'AutoX_checkout_2022-10-19 14:13:45.640427'),
(80, '[{\"product_id\":\"3\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 326750000, '2022-10-19', 3, 1, 1, 1, 'AutoX_checkout_2022-10-19 14:15:24.014610'),
(81, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 01:47:37.393962'),
(82, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 01:52:07.670863'),
(83, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 01:55:40.304288'),
(84, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 01:55:56.494254'),
(85, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 01:56:23.654265'),
(86, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 02:03:49.759139'),
(87, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 02:05:45.844463'),
(88, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 02:10:55.511837'),
(89, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 02:11:05.792657'),
(90, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 02:11:29.591343'),
(91, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 0, 1, 1, 0, 'AutoX_checkout_2022-10-20 02:11:56.957376'),
(93, '[{\"product_id\":\"1\",\"product_price\":\"326750000\",\"product_quantity\":\"2\"},{\"product_id\":\"2\",\"product_price\":\"326750000\",\"product_quantity\":\"1\"}]', 500000, 980250000, '2022-10-20', 2, 1, 1, 0, 'AutoX_checkout_2022-10-20 02:13:51.970653');

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `attribute_id` int(11) NOT NULL,
  `attribute_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`attribute_id`, `attribute_name`) VALUES
(10, 'at'),
(13, 'body_type'),
(17, 'cheaper'),
(12, 'color'),
(11, 'condition'),
(18, 'fuel'),
(8, 'kilometer'),
(14, 'manufacturing_year'),
(9, 'post_time'),
(15, 'power'),
(7, 'reg_date'),
(16, 'seats');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_value`
--

CREATE TABLE `product_attribute_value` (
  `id` bigint(20) NOT NULL,
  `attribute_options` varchar(100) NOT NULL,
  `attribute_value` varchar(200) NOT NULL,
  `post_time` date NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_attribute_value`
--

INSERT INTO `product_attribute_value` (`id`, `attribute_options`, `attribute_value`, `post_time`, `attribute_id`, `product_id`) VALUES
(1, 'text', 'AT', '2022-10-06', 10, 1),
(2, 'text', 'Hatchback', '2022-10-06', 13, 1),
(3, 'text', 'Gray', '2022-10-06', 12, 1),
(4, 'text', 'Used', '2022-10-06', 11, 1),
(5, 'number', '29400', '2022-10-06', 8, 1),
(6, 'number', '2018', '2022-10-06', 14, 1),
(7, 'number', '182', '2022-10-06', 15, 1),
(8, 'number', '2018', '2022-10-06', 7, 1),
(9, 'number', '5', '2022-10-06', 16, 1),
(10, 'number', '125000000', '2022-10-06', 17, 1),
(11, 'text', 'Gasoline', '2022-10-06', 18, 1),
(12, 'date', '2020-06-11', '2022-10-06', 9, 1),
(13, 'text', 'AT', '2022-10-06', 10, 2),
(14, 'text', 'Hatchback', '2022-10-06', 13, 2),
(15, 'text', 'Gray', '2022-10-06', 12, 2),
(16, 'text', 'Used', '2022-10-06', 11, 2),
(17, 'number', '29400', '2022-10-06', 8, 2),
(18, 'number', '2018', '2022-10-06', 14, 2),
(19, 'number', '182', '2022-10-06', 15, 2),
(20, 'number', '2018', '2022-10-06', 7, 2),
(21, 'number', '5', '2022-10-06', 16, 2),
(22, 'number', '125000000', '2022-10-06', 17, 2),
(23, 'text', 'Gasoline', '2022-10-06', 18, 2),
(24, 'date', '2020-06-11', '2022-10-06', 9, 2),
(25, 'text', 'AT', '2022-10-06', 10, 3),
(26, 'text', 'Hatchback', '2022-10-06', 13, 3),
(27, 'text', 'Gray', '2022-10-06', 12, 3),
(28, 'text', 'Used', '2022-10-06', 11, 3),
(29, 'number', '29400', '2022-10-06', 8, 3),
(30, 'number', '2018', '2022-10-06', 14, 3),
(31, 'number', '182', '2022-10-06', 15, 3),
(32, 'number', '2018', '2022-10-06', 7, 3),
(33, 'number', '5', '2022-10-06', 16, 3),
(34, 'number', '125000000', '2022-10-06', 17, 3),
(35, 'text', 'Gasoline', '2022-10-06', 18, 3),
(36, 'date', '2020-06-11', '2022-10-06', 9, 3),
(37, 'text', 'AT', '2022-10-06', 10, 4),
(38, 'text', 'Hatchback', '2022-10-06', 13, 4),
(39, 'text', 'Gray', '2022-10-06', 12, 4),
(40, 'text', 'Used', '2022-10-06', 11, 4),
(41, 'number', '29400', '2022-10-06', 8, 4),
(42, 'number', '2018', '2022-10-06', 14, 4),
(43, 'number', '182', '2022-10-06', 15, 4),
(44, 'number', '2018', '2022-10-06', 7, 4),
(45, 'number', '5', '2022-10-06', 16, 4),
(46, 'number', '125000000', '2022-10-06', 17, 4),
(47, 'text', 'Gasoline', '2022-10-06', 18, 4),
(48, 'date', '2020-06-11', '2022-10-06', 9, 4),
(49, 'text', 'AT', '2022-10-06', 10, 5),
(50, 'text', 'Hatchback', '2022-10-06', 13, 5),
(51, 'text', 'Gray', '2022-10-06', 12, 5),
(52, 'text', 'Used', '2022-10-06', 11, 5),
(53, 'number', '29400', '2022-10-06', 8, 5),
(54, 'number', '2018', '2022-10-06', 14, 5),
(55, 'number', '182', '2022-10-06', 15, 5),
(56, 'number', '2018', '2022-10-06', 7, 5),
(57, 'number', '5', '2022-10-06', 16, 5),
(58, 'number', '125000000', '2022-10-06', 17, 5),
(59, 'text', 'Gasoline', '2022-10-06', 18, 5),
(60, 'date', '2020-06-11', '2022-10-06', 9, 5),
(61, 'text', 'AT', '2022-10-06', 10, 6),
(62, 'text', 'Hatchback', '2022-10-06', 13, 6),
(63, 'text', 'Gray', '2022-10-06', 12, 6),
(64, 'text', 'Used', '2022-10-06', 11, 6),
(65, 'number', '29400', '2022-10-06', 8, 6),
(66, 'number', '2018', '2022-10-06', 14, 6),
(67, 'number', '182', '2022-10-06', 15, 6),
(68, 'number', '2018', '2022-10-06', 7, 6),
(69, 'number', '5', '2022-10-06', 16, 6),
(70, 'number', '125000000', '2022-10-06', 17, 6),
(71, 'text', 'Gasoline', '2022-10-06', 18, 6),
(72, 'date', '2020-06-11', '2022-10-06', 9, 6),
(73, 'text', 'AT', '2022-10-06', 10, 7),
(74, 'text', 'Hatchback', '2022-10-06', 13, 7),
(75, 'text', 'Gray', '2022-10-06', 12, 7),
(76, 'text', 'Used', '2022-10-06', 11, 7),
(77, 'number', '29400', '2022-10-06', 8, 7),
(78, 'number', '2018', '2022-10-06', 14, 7),
(79, 'number', '182', '2022-10-06', 15, 7),
(80, 'number', '2018', '2022-10-06', 7, 7),
(81, 'number', '5', '2022-10-06', 16, 7),
(82, 'number', '125000000', '2022-10-06', 17, 7),
(83, 'text', 'Gasoline', '2022-10-06', 18, 7),
(84, 'date', '2020-06-11', '2022-10-06', 9, 7),
(85, 'text', 'AT', '2022-10-06', 10, 8),
(86, 'text', 'Hatchback', '2022-10-06', 13, 8),
(87, 'text', 'Gray', '2022-10-06', 12, 8),
(88, 'text', 'Used', '2022-10-06', 11, 8),
(89, 'number', '29400', '2022-10-06', 8, 8),
(90, 'number', '2018', '2022-10-06', 14, 8),
(91, 'number', '182', '2022-10-06', 15, 8),
(92, 'number', '2018', '2022-10-06', 7, 8),
(93, 'number', '5', '2022-10-06', 16, 8),
(94, 'number', '125000000', '2022-10-06', 17, 8),
(95, 'text', 'Gasoline', '2022-10-06', 18, 8),
(96, 'date', '2020-06-11', '2022-10-06', 9, 8);

-- --------------------------------------------------------

--
-- Table structure for table `product_gallery`
--

CREATE TABLE `product_gallery` (
  `id` bigint(20) NOT NULL,
  `image` varchar(100) NOT NULL,
  `prd_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_gallery`
--

INSERT INTO `product_gallery` (`id`, `image`, `prd_id`) VALUES
(1, 'uploads/2022/10/06/Rectangle_13_1.png', 1),
(2, 'uploads/2022/10/06/Rectangle_13_2.png', 1),
(3, 'uploads/2022/10/06/Rectangle_14_2.png', 1),
(4, 'uploads/2022/10/06/Rectangle_14_2_YZAClVU.png', 3),
(5, 'uploads/2022/10/06/Rectangle_13_1_ayCCfKR.png', 3),
(6, 'uploads/2022/10/06/images.jpeg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `product_wishlist`
--

CREATE TABLE `product_wishlist` (
  `id` bigint(20) NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `date_added` date NOT NULL,
  `prd_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_wishlist`
--

INSERT INTO `product_wishlist` (`id`, `user_id`, `date_added`, `prd_id`) VALUES
(1, '1', '2022-10-13', 1),
(2, '1', '2022-10-13', 5),
(5, 'x4qGyYT4zp7H4n1EI4vuvcs6xAjuRCYavMIWQXVtesayRpRysvA5Ujk4FmY0KdlH', '2022-10-19', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users_card`
--

CREATE TABLE `users_card` (
  `id` bigint(20) NOT NULL,
  `card_number` varchar(50) NOT NULL,
  `expiration` varchar(5) NOT NULL,
  `cvv` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_located`
--

CREATE TABLE `users_located` (
  `id` bigint(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `detail_country` varchar(500) NOT NULL,
  `located_type` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `is_default` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_located`
--

INSERT INTO `users_located` (`id`, `country`, `detail_country`, `located_type`, `user_id`, `fullname`, `phone`, `is_default`) VALUES
(1, 'Vietnam', '', 0, 1, 'Vu Xuan Phuong', '0877946666', 1);

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
  `avatar` varchar(100) NOT NULL,
  `username` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_myuser`
--

INSERT INTO `users_myuser` (`id`, `last_login`, `is_superuser`, `is_staff`, `is_active`, `date_joined`, `phone`, `password`, `fullname`, `email`, `avatar`, `username`) VALUES
(1, '2022-10-21 02:35:05.179760', 1, 1, 1, '2022-10-06 03:17:53.000000', '0877946666', 'pbkdf2_sha256$320000$xxOFhrGTColXcFuAfhYnVy$J1yb6TQKNPwa3p+UgVMmW9/+nqMobJFx8Oi3tlmpCpQ=', 'Vũ Xuân Phương', 'Vxphuongictu998@gmail.com', 'uploads/2022/10/20/D7EAF903-9D77-4CF0-A00D-2CD22C246261.jpeg', 'admin'),
(6, '2022-10-11 02:12:38.644831', 0, 0, 1, '2022-10-07 06:59:11.417793', '0981863823', 'pbkdf2_sha256$320000$2A0tqnGrXIOaNqKE0L6BET$6PFJh/zdRe4Y3GSmO1IhpY/dL7XcOKGFzwccvK4Ncmg=', 'Dinh Trung Hieu', 'fb.dthieu997@gmail.com', 'uploads/default-avatar.png', 'root');

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
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `cart_prd_id_0c514977_fk_products_product_id` (`prd_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_name` (`cat_name`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_prd_id_347f6537_fk_products_product_id` (`prd_id`);

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
-- Indexes for table `join_table_models`
--
ALTER TABLE `join_table_models`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cat_id` (`cat_id`),
  ADD UNIQUE KEY `cat_name` (`cat_name`),
  ADD KEY `join_table_models_product_id_0ca785de_fk_products_product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `products_category_id_a7a3a156_fk_categories_cat_id` (`category_id`);

--
-- Indexes for table `products_history_order`
--
ALTER TABLE `products_history_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_history_order_located_id_44826534_fk_users_located_id` (`located_id`),
  ADD KEY `products_history_order_user_id_9c47a033_fk_users_myuser_id` (`user_id`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`attribute_id`),
  ADD UNIQUE KEY `attribute_name` (`attribute_name`);

--
-- Indexes for table `product_attribute_value`
--
ALTER TABLE `product_attribute_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attribute_va_attribute_id_2b7ad66d_fk_product_a` (`attribute_id`),
  ADD KEY `product_attribute_va_product_id_7cce6921_fk_products_` (`product_id`);

--
-- Indexes for table `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_gallery_prd_id_a742e814_fk_products_product_id` (`prd_id`);

--
-- Indexes for table `product_wishlist`
--
ALTER TABLE `product_wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_wishlist_prd_id_ab4dae8f_fk_products_product_id` (`prd_id`);

--
-- Indexes for table `users_card`
--
ALTER TABLE `users_card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_card_user_id_bb228f77_fk_users_myuser_id` (`user_id`);

--
-- Indexes for table `users_located`
--
ALTER TABLE `users_located`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_located_user_id_f8021cd8_fk_users_myuser_id` (`user_id`);

--
-- Indexes for table `users_myuser`
--
ALTER TABLE `users_myuser`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `django_admin_log`
--
ALTER TABLE `django_admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `django_content_type`
--
ALTER TABLE `django_content_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `django_migrations`
--
ALTER TABLE `django_migrations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `join_table_models`
--
ALTER TABLE `join_table_models`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products_history_order`
--
ALTER TABLE `products_history_order`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `attribute_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `product_attribute_value`
--
ALTER TABLE `product_attribute_value`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `product_gallery`
--
ALTER TABLE `product_gallery`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_wishlist`
--
ALTER TABLE `product_wishlist`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users_card`
--
ALTER TABLE `users_card`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_located`
--
ALTER TABLE `users_located`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_myuser`
--
ALTER TABLE `users_myuser`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_prd_id_0c514977_fk_products_product_id` FOREIGN KEY (`prd_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_prd_id_347f6537_fk_products_product_id` FOREIGN KEY (`prd_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `django_admin_log`
--
ALTER TABLE `django_admin_log`
  ADD CONSTRAINT `django_admin_log_content_type_id_c4bce8eb_fk_django_co` FOREIGN KEY (`content_type_id`) REFERENCES `django_content_type` (`id`),
  ADD CONSTRAINT `django_admin_log_user_id_c564eba6_fk_users_myuser_id` FOREIGN KEY (`user_id`) REFERENCES `users_myuser` (`id`);

--
-- Constraints for table `join_table_models`
--
ALTER TABLE `join_table_models`
  ADD CONSTRAINT `join_table_models_product_id_0ca785de_fk_products_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_a7a3a156_fk_categories_cat_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`cat_id`);

--
-- Constraints for table `products_history_order`
--
ALTER TABLE `products_history_order`
  ADD CONSTRAINT `products_history_order_located_id_44826534_fk_users_located_id` FOREIGN KEY (`located_id`) REFERENCES `users_located` (`id`),
  ADD CONSTRAINT `products_history_order_user_id_9c47a033_fk_users_myuser_id` FOREIGN KEY (`user_id`) REFERENCES `users_myuser` (`id`);

--
-- Constraints for table `product_attribute_value`
--
ALTER TABLE `product_attribute_value`
  ADD CONSTRAINT `product_attribute_va_attribute_id_2b7ad66d_fk_product_a` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`attribute_id`),
  ADD CONSTRAINT `product_attribute_va_product_id_7cce6921_fk_products_` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD CONSTRAINT `product_gallery_prd_id_a742e814_fk_products_product_id` FOREIGN KEY (`prd_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `product_wishlist`
--
ALTER TABLE `product_wishlist`
  ADD CONSTRAINT `product_wishlist_prd_id_ab4dae8f_fk_products_product_id` FOREIGN KEY (`prd_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `users_card`
--
ALTER TABLE `users_card`
  ADD CONSTRAINT `users_card_user_id_bb228f77_fk_users_myuser_id` FOREIGN KEY (`user_id`) REFERENCES `users_myuser` (`id`);

--
-- Constraints for table `users_located`
--
ALTER TABLE `users_located`
  ADD CONSTRAINT `users_located_user_id_f8021cd8_fk_users_myuser_id` FOREIGN KEY (`user_id`) REFERENCES `users_myuser` (`id`);

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
