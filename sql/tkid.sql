-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2018 at 02:10 AM
-- Server version: 10.2.18-MariaDB-cll-lve
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `r64332tkid_tkid`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_categories`
--

CREATE TABLE `app_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  `order_index` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `app_categories`
--

INSERT INTO `app_categories` (`id`, `parent_id`, `name`, `description`, `order_index`) VALUES
(1, 0, 'slider', 'Slider Banner', 0),
(5, 0, 'events', 'Events Calendar', 0),
(10, 0, 'contact', 'Contact Banner', 0),
(11, 0, 'instagram', 'Instagram Pics', 0),
(12, 0, 'gallery', 'Foto Galery', 0);

-- --------------------------------------------------------

--
-- Table structure for table `app_images`
--

CREATE TABLE `app_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `app_category_id` int(10) UNSIGNED NOT NULL,
  `file` varchar(250) NOT NULL,
  `img_width` int(10) UNSIGNED NOT NULL,
  `img_height` int(10) UNSIGNED NOT NULL,
  `extension` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `app_images`
--

INSERT INTO `app_images` (`id`, `app_category_id`, `file`, `img_width`, `img_height`, `extension`) VALUES
(40, 12, 'gallery-40.jpg', 1024, 768, 'jpg'),
(50, 12, 'gallery-50.jpg', 1024, 1026, 'jpg'),
(63, 1, 'slider-63.jpg', 1280, 1920, 'jpg'),
(64, 1, 'slider-64.jpg', 1280, 2147, 'jpg'),
(69, 12, 'gallery-69.jpg', 1280, 1920, 'jpg'),
(70, 12, 'gallery-70.jpg', 1024, 520, 'jpg'),
(76, 1, 'slider-76.jpg', 636, 311, 'jpg'),
(77, 1, 'slider-77.jpg', 1280, 1920, 'jpg'),
(81, 1, 'slider-81.jpg', 640, 408, 'jpg'),
(82, 12, 'gallery-82.jpg', 640, 346, 'jpg'),
(83, 12, 'gallery-83.jpg', 640, 376, 'jpg'),
(85, 12, 'gallery-85.jpg', 640, 428, 'jpg'),
(86, 12, 'gallery-86.jpg', 640, 428, 'jpg'),
(87, 12, 'gallery-87.jpg', 640, 428, 'jpg'),
(88, 1, 'slider-88.jpg', 1365, 2160, 'jpg');

-- --------------------------------------------------------

--
-- Table structure for table `app_images_meta`
--

CREATE TABLE `app_images_meta` (
  `id` int(10) UNSIGNED NOT NULL,
  `app_image_id` int(10) UNSIGNED NOT NULL,
  `app_category_id` int(11) NOT NULL,
  `image_alt` varchar(200) NOT NULL DEFAULT 'image_alt',
  `image_title` varchar(200) NOT NULL DEFAULT 'image_title',
  `image_caption` varchar(250) NOT NULL,
  `image_description` varchar(250) NOT NULL,
  `image_button_link_text` varchar(200) NOT NULL,
  `image_button_link_href` varchar(250) NOT NULL,
  `order_index` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `app_images_meta`
--

INSERT INTO `app_images_meta` (`id`, `app_image_id`, `app_category_id`, `image_alt`, `image_title`, `image_caption`, `image_description`, `image_button_link_text`, `image_button_link_href`, `order_index`) VALUES
(40, 40, 12, 'alt_attribute.kindergarten', 'title_attribute.kindergarten', '', '', '', '', 10),
(50, 50, 12, 'alt_attribute.sitting_chair_for_outside', 'title_attribute.sitting_chair_for_outside', '', '', '', '', 5),
(63, 63, 1, 'alt_attribute.lobby', 'title_attribute.lobby', 'quotes.henry_ward_beecher', '„Copiii sunt mainile cu ajutorul carora atingem cerurile.\" Henry Ward Beecher', '', '', 1),
(64, 64, 1, 'alt_attribute.indoor_fun_room', 'title_attribute.indoor_fun_room', 'quotes.samuel_johnson', '„Permiteti-le copiilor sa fie fericiti in felul lor, caci ce alta modalitate mai buna vor gasi?” Samuel Johnson', '', '', 6),
(69, 69, 12, 'alt_attribute.kids_playroom', 'title_attribute.kids_playroom', '', '', '', '', 2),
(70, 70, 12, 'alt_attribute.kinder_slide', 'title_attribute.kinder_slide', '', '', '', '', 6),
(76, 76, 1, 'alt_attribute.turtle_toy', 'title_attribute.turtle_toy', 'quotes.lucian_blaga', '„Copilaria este inima tuturor varstelor.” Lucian Blaga', '', '', 3),
(77, 77, 1, 'alt_attribute.indoor_playroom', 'title_attribute.indoor_playroom', 'quotes.stephen_sondheim', '„Ramai copil atata timp cat poti sa fii copil.\"\r\nStephen Sondheim', '', '', 4),
(81, 81, 1, 'alt_attribute.indoor_playroom', 'title_attribute.indoor_playroom', 'quotes.heidi_kaduson', '„Atunci cand esti liber, te poti juca, iar cand te joci, devii liber\". Heidi Kaduson', '', '', 5),
(82, 82, 12, 'alt_attribute.indoor_playground', 'title_attribute.indoor_playground', '', '', '', '', 4),
(83, 83, 12, 'alt_attribute.kinder_toys_room', 'title_attribute.kinder_toys_room', '', '', '', '', 4),
(85, 85, 12, 'alt_attribute.kids_toy', 'title_attribute.kids_toy', '', '', '', '', 1),
(86, 86, 12, 'alt_attribute.turtle_toy', 'title_attribute.turtle_toy', '', '', '', '', 3),
(87, 87, 12, 'alt_attribute.house_toy', 'title_attribute.house_toy', '', '', '', '', 4),
(88, 88, 1, 'alt_attribute.welcome_room', 'title_attribute.welcome_room', 'quotes.louise_hart', '„Cel mai bun lucru pe care poti sa-l cheltuiesti pentru copiii tai este timpul tau.” Louise Hart', '', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `attributes_names`
--

CREATE TABLE `attributes_names` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attributes_names`
--

INSERT INTO `attributes_names` (`id`, `name`) VALUES
(1, 'Culoare'),
(2, 'Masura');

-- --------------------------------------------------------

--
-- Table structure for table `attributes_values`
--

CREATE TABLE `attributes_values` (
  `id` int(10) UNSIGNED NOT NULL,
  `attribute_id` int(10) UNSIGNED NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attributes_values`
--

INSERT INTO `attributes_values` (`id`, `attribute_id`, `value`) VALUES
(1, 2, 'XS'),
(2, 2, 'S'),
(3, 2, 'M'),
(4, 2, 'L'),
(5, 2, 'XL'),
(6, 2, 'XXL');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `url_key` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  `short_description` mediumtext NOT NULL,
  `file` varchar(200) NOT NULL,
  `level` int(11) NOT NULL,
  `seo_title` varchar(150) NOT NULL,
  `seo_description` varchar(200) NOT NULL,
  `seo_keywords` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `display_separate_status` tinyint(4) NOT NULL,
  `order_index` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `county`
--

CREATE TABLE `county` (
  `id` int(11) NOT NULL,
  `code` varchar(2) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `county`
--

INSERT INTO `county` (`id`, `code`, `name`) VALUES
(1, 'AB', 'Alba'),
(2, 'AR', 'Arad'),
(3, 'AG', 'Arges'),
(4, 'BC', 'Bacau'),
(5, 'BH', 'Bihor'),
(6, 'BN', 'Bistrita-Nasaud'),
(7, 'BT', 'Botosani'),
(8, 'BR', 'Braila'),
(9, 'BV', 'Brasov'),
(10, 'B', 'Bucuresti'),
(11, 'BZ', 'Buzau'),
(12, 'CL', 'Calarasi'),
(13, 'CS', 'Caras-Severin'),
(14, 'CJ', 'Cluj'),
(15, 'CT', 'Constanta'),
(16, 'CV', 'Covasna'),
(17, 'DB', 'Dambovita'),
(18, 'DJ', 'Dolj'),
(19, 'GL', 'Galati'),
(20, 'GR', 'Giurgiu'),
(21, 'GJ', 'Gorj'),
(22, 'HR', 'Harghita'),
(23, 'HD', 'Hunedoara'),
(24, 'IL', 'Ialomita'),
(25, 'IS', 'Iasi'),
(26, 'IF', 'Ilfov'),
(27, 'MM', 'Maramures'),
(28, 'MH', 'Mehedinti'),
(29, 'MS', 'Mures'),
(30, 'NT', 'Neamt'),
(31, 'OT', 'Olt'),
(32, 'PH', 'Prahova'),
(33, 'SJ', 'Salaj'),
(34, 'SM', 'Satu-Mare'),
(35, 'SB', 'Sibiu'),
(36, 'SV', 'Suceava'),
(37, 'TR', 'Teleorman'),
(38, 'TM', 'Timis'),
(39, 'TL', 'Tulcea'),
(40, 'VL', 'Valcea'),
(41, 'VS', 'Vaslui'),
(42, 'VN', 'Vrancea');

-- --------------------------------------------------------

--
-- Table structure for table `langs`
--

CREATE TABLE `langs` (
  `id` int(10) UNSIGNED NOT NULL,
  `abbreviation` varchar(2) NOT NULL,
  `name` varchar(60) NOT NULL,
  `is_translated` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `langs`
--

INSERT INTO `langs` (`id`, `abbreviation`, `name`, `is_translated`) VALUES
(1, 'ro', 'Română', 1),
(2, 'en', 'English', 0),
(3, 'es', 'Español', 1),
(4, 'fr', 'François', 1),
(5, 'de', 'Deutsch', 1),
(6, 'it', 'Italiano', 1),
(7, 'hu', 'Magyar', 1);

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscriber`
--

CREATE TABLE `newsletter_subscriber` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `source_id` tinyint(4) NOT NULL,
  `date_added` datetime NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `newsletter_subscriber`
--

INSERT INTO `newsletter_subscriber` (`id`, `email`, `source_id`, `date_added`, `first_name`, `last_name`, `status`) VALUES
(1, 'alina.timniu@gmail.com', 1, '2017-12-15 18:47:28', 'Timniu', 'Alina', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `date_added` datetime NOT NULL,
  `payment_method` tinyint(4) NOT NULL,
  `shipping_type` tinyint(4) NOT NULL,
  `delivery_comments` mediumtext NOT NULL,
  `price_products` float NOT NULL,
  `price_shipping` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `date_added`, `payment_method`, `shipping_type`, `delivery_comments`, `price_products`, `price_shipping`) VALUES
(1, '2017-12-14 19:56:28', 1, 1, '', 246, 19),
(2, '2017-12-14 19:56:47', 1, 1, '', 246, 19);

-- --------------------------------------------------------

--
-- Table structure for table `order_billing`
--

CREATE TABLE `order_billing` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `client_type` tinyint(4) NOT NULL,
  `company_name` varchar(250) NOT NULL,
  `unique_registration_code` varchar(20) NOT NULL,
  `commerce_register_number` varchar(20) NOT NULL,
  `bank` varchar(50) NOT NULL,
  `iban` varchar(50) NOT NULL,
  `email` varchar(250) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `zip` varchar(50) NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `city` varchar(100) NOT NULL,
  `county_id` int(10) UNSIGNED NOT NULL,
  `phone` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_billing`
--

INSERT INTO `order_billing` (`id`, `order_id`, `client_type`, `company_name`, `unique_registration_code`, `commerce_register_number`, `bank`, `iban`, `email`, `first_name`, `last_name`, `zip`, `country_id`, `city`, `county_id`, `phone`, `mobile`, `address`) VALUES
(1, 1, 1, '', '', '', '', '', 'alina.timniu@gmail.com', 'Alina', 'Timniu', '', 1, 'Pitesti', 3, '', '074215271001', 'henri coanda'),
(2, 2, 1, '', '', '', '', '', 'alina.timniu@gmail.com', 'Alina', 'Timniu', '', 1, 'Pitesti', 3, '', '074215271001', 'henri coanda');

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(10) UNSIGNED NOT NULL,
  `unit_price` float NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`id`, `product_id`, `size_id`, `unit_price`, `price`, `quantity`, `order_id`) VALUES
(1, 56, 2, 123, 246, 2, 1),
(2, 56, 2, 123, 246, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `order_shipping`
--

CREATE TABLE `order_shipping` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `client_type` tinyint(4) NOT NULL,
  `company_name` varchar(250) NOT NULL,
  `unique_registration_code` varchar(20) NOT NULL,
  `commerce_register_number` varchar(20) NOT NULL,
  `bank` varchar(50) NOT NULL,
  `iban` varchar(50) NOT NULL,
  `email` varchar(250) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `zip` varchar(50) NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `city` varchar(100) NOT NULL,
  `county_id` int(10) UNSIGNED NOT NULL,
  `phone` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_shipping`
--

INSERT INTO `order_shipping` (`id`, `order_id`, `client_type`, `company_name`, `unique_registration_code`, `commerce_register_number`, `bank`, `iban`, `email`, `first_name`, `last_name`, `zip`, `country_id`, `city`, `county_id`, `phone`, `mobile`, `address`) VALUES
(1, 1, 1, '', '', '', '', '', 'alina.timniu@gmail.com', 'Adrian', 'Uta', '', 1, 'Pitesti', 3, '', '074215271001', 'henri coanda'),
(2, 2, 1, '', '', '', '', '', 'alina.timniu@gmail.com', 'Adrian', 'Uta', '', 1, 'Pitesti', 3, '', '074215271001', 'henri coanda');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `page_id` varchar(50) NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `page_id`, `parent_id`, `name`, `description`) VALUES
(3, 'permissions', 0, 'Lista Permisiuni Utilizatori', ''),
(4, 'dashboard', 0, 'Dashboard', ''),
(6, 'users_permissions', 0, 'Permisiuni Utilizatori', ''),
(7, 'users', 0, 'Utilizatori', ''),
(8, 'user_edit', 0, 'Utilizatori - Editare', ''),
(10, 'languages', 0, 'Limbi Vorbite', ''),
(24, 'messages_folders', 0, 'Foldere mesaje', ''),
(25, 'messages', 0, 'Mesaje', ''),
(38, 'roles', 0, 'Roluri', ''),
(46, 'permission_edit', 0, 'Lista Permisiuni Utilizatori - Editare', ''),
(54, 'cities', 0, 'Orase', ''),
(56, 'products', 0, 'Produse', ''),
(57, 'categories', 0, 'Categorii Produse', ''),
(58, 'categories_import', 0, 'Categorii Import', ''),
(59, 'app_categories', 0, 'Categoriile Aplicatiei', ''),
(60, 'app_images', 0, 'Imaginile Aplicatiei', ''),
(61, 'products_images', 0, 'Imaginile Produselor', '');

-- --------------------------------------------------------

--
-- Table structure for table `producers`
--

CREATE TABLE `producers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `producers`
--

INSERT INTO `producers` (`id`, `name`) VALUES
(1, 'Obsessive'),
(2, 'Saresia'),
(3, 'Looksexy'),
(4, 'ReneRofe'),
(5, 'Belsira'),
(6, 'Design by atixo'),
(7, 'Grey Velvet'),
(8, 'JustX'),
(9, 'Leg Avenue'),
(10, 'Allure'),
(11, 'Beautys Love'),
(12, 'KINGSPEARL'),
(13, 'Kiss Me'),
(14, 'Marko'),
(15, 'Alpha Male'),
(16, 'Anais'),
(17, 'Kinga'),
(18, 'Let\'s Duck'),
(19, 'Excellent Beauty'),
(20, 'Provocative'),
(21, 'Avanua'),
(22, 'Passion'),
(23, 'Tessoro'),
(24, 'Fiore'),
(25, 'Primo'),
(26, 'Bast'),
(27, 'Livco'),
(28, 'Casmir'),
(29, 'Me Seduce'),
(30, 'J4G'),
(31, 'Pink Lipstick');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `producer_id` int(10) UNSIGNED NOT NULL,
  `model` varchar(50) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `name` varchar(250) NOT NULL,
  `url_key` varchar(250) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_ids` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `default_image` varchar(250) NOT NULL,
  `default_image_id` int(10) UNSIGNED NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `price_before` decimal(8,2) NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `amount_unit` varchar(50) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `attribute_id` int(10) UNSIGNED NOT NULL,
  `attribute_value_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `product_id`, `attribute_id`, `attribute_value_id`) VALUES
(1201, 65, 2, 5),
(1202, 65, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes_summary`
--

CREATE TABLE `product_attributes_summary` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `color_ids` varchar(255) NOT NULL,
  `size_ids` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_attributes_summary`
--

INSERT INTO `product_attributes_summary` (`id`, `product_id`, `color_ids`, `size_ids`) VALUES
(62, 64, '', ''),
(63, 65, '', '5');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_id`) VALUES
(459, 96, 75),
(460, 95, 75),
(461, 98, 75),
(463, 100, 75),
(464, 91, 75),
(465, 103, 76),
(466, 104, 76),
(467, 105, 76),
(468, 106, 76),
(469, 107, 76),
(470, 108, 76),
(471, 109, 76),
(472, 110, 76),
(473, 111, 76),
(474, 112, 76),
(475, 113, 77),
(476, 114, 77),
(477, 115, 77),
(478, 116, 77),
(479, 117, 77),
(480, 118, 77),
(481, 119, 77),
(482, 120, 77),
(484, 121, 78),
(485, 122, 78),
(486, 123, 79),
(487, 124, 79),
(490, 125, 79),
(491, 126, 80),
(492, 127, 80),
(493, 128, 80),
(494, 129, 80),
(495, 130, 80),
(497, 132, 80),
(498, 133, 80),
(499, 134, 80),
(500, 135, 80),
(501, 136, 80),
(502, 137, 80),
(503, 138, 80),
(504, 139, 80),
(505, 140, 80),
(506, 141, 80),
(507, 142, 80),
(508, 143, 81),
(509, 144, 83),
(510, 145, 83),
(511, 146, 83),
(513, 148, 84),
(514, 149, 85),
(515, 150, 85),
(516, 151, 85),
(517, 97, 75),
(522, 154, 72),
(524, 155, 86),
(526, 157, 87),
(527, 158, 87),
(528, 159, 87),
(531, 162, 89),
(532, 163, 89),
(533, 164, 89),
(534, 165, 90),
(535, 166, 90),
(536, 167, 90),
(537, 168, 90),
(538, 169, 90),
(539, 170, 91),
(540, 171, 91),
(541, 172, 91),
(542, 173, 92),
(543, 174, 92),
(544, 175, 92),
(545, 176, 92),
(546, 177, 93),
(548, 178, 93),
(549, 179, 93),
(550, 180, 93),
(552, 181, 93),
(553, 182, 93),
(554, 183, 94),
(555, 184, 95),
(556, 185, 95),
(557, 186, 95),
(558, 187, 96),
(559, 188, 96),
(560, 189, 96),
(561, 190, 96),
(562, 191, 96),
(563, 192, 96),
(564, 193, 97),
(565, 194, 97),
(566, 195, 97),
(567, 196, 97),
(568, 197, 97),
(569, 198, 97),
(570, 199, 98),
(571, 200, 98),
(572, 201, 98),
(573, 202, 98),
(578, 131, 80),
(582, 152, 72),
(593, 93, 75),
(594, 93, 103),
(601, 160, 88),
(602, 99, 75),
(603, 99, 103),
(604, 90, 75),
(605, 90, 103),
(606, 88, 75),
(607, 88, 103),
(608, 92, 75),
(609, 92, 103),
(610, 89, 75),
(611, 89, 103),
(612, 94, 75),
(613, 156, 86),
(614, 147, 84),
(615, 147, 104),
(616, 153, 102),
(617, 153, 72),
(621, 161, 88),
(622, 161, 102);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `file` varchar(250) NOT NULL,
  `img_width` int(10) UNSIGNED NOT NULL,
  `img_height` int(10) UNSIGNED NOT NULL,
  `extension` varchar(10) NOT NULL,
  `order_index` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_images_meta`
--

CREATE TABLE `product_images_meta` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `images_count` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_rating`
--

CREATE TABLE `product_rating` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `rating` float NOT NULL,
  `users_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_rating`
--

INSERT INTO `product_rating` (`id`, `product_id`, `rating`, `users_count`) VALUES
(1, 6, 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_rating_history`
--

CREATE TABLE `product_rating_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_ip` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_rating_history`
--

INSERT INTO `product_rating_history` (`id`, `product_id`, `rating`, `date_added`, `user_ip`) VALUES
(1, 6, 8, '2017-12-14 19:38:56', '');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `status`) VALUES
(1, 'administrator', 'Administrator', 1),
(2, 'user', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key_name` varchar(50) NOT NULL,
  `key_value` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `key_value`) VALUES
(1, 'default_language', '1'),
(2, 'radar_property_contract_type', 'all'),
(3, 'radar_price_source', 'standard'),
(4, 'radar_display', 'compact'),
(5, 'radar_comparing', 'differences');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `lang_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(250) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `ip_address` varchar(250) NOT NULL,
  `user_rank` tinyint(4) NOT NULL,
  `is_online` tinyint(4) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `comission_percent` float NOT NULL,
  `allow_invisible` tinyint(4) NOT NULL,
  `is_invisible` tinyint(4) NOT NULL,
  `signature_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `role_id`, `lang_id`, `email`, `phone`, `password`, `first_name`, `last_name`, `date_added`, `last_login`, `status`, `ip_address`, `user_rank`, `is_online`, `birthdate`, `comission_percent`, `allow_invisible`, `is_invisible`, `signature_title`) VALUES
(24, 'alina', 1, 1, 'alina.timniu@gmail.com', '', '9feecac74dfdbe9e9fb6396391665a06', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, '*', 1, 1, '1979-03-30', 0, 1, 1, 'Project Manager IT');

-- --------------------------------------------------------

--
-- Table structure for table `users_permissions`
--

CREATE TABLE `users_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_permissions`
--

INSERT INTO `users_permissions` (`id`, `user_id`, `permission_id`) VALUES
(195, 24, 58),
(196, 24, 57),
(197, 24, 59),
(198, 24, 4),
(199, 24, 24),
(200, 24, 60),
(201, 24, 61),
(202, 24, 10),
(203, 24, 3),
(204, 24, 46),
(205, 24, 25),
(206, 24, 54),
(207, 24, 6),
(208, 24, 56),
(209, 24, 38),
(210, 24, 7),
(211, 24, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_categories`
--
ALTER TABLE `app_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_images`
--
ALTER TABLE `app_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_images_meta`
--
ALTER TABLE `app_images_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attributes_names`
--
ALTER TABLE `attributes_names`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attributes_values`
--
ALTER TABLE `attributes_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `county`
--
ALTER TABLE `county`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `account_county_52094d6e` (`name`);

--
-- Indexes for table `langs`
--
ALTER TABLE `langs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter_subscriber`
--
ALTER TABLE `newsletter_subscriber`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_billing`
--
ALTER TABLE `order_billing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_shipping`
--
ALTER TABLE `order_shipping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `producers`
--
ALTER TABLE `producers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_attributes_summary`
--
ALTER TABLE `product_attributes_summary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images_meta`
--
ALTER TABLE `product_images_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_rating`
--
ALTER TABLE `product_rating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_rating_history`
--
ALTER TABLE `product_rating_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_permissions`
--
ALTER TABLE `users_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_categories`
--
ALTER TABLE `app_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `app_images`
--
ALTER TABLE `app_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `app_images_meta`
--
ALTER TABLE `app_images_meta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `attributes_names`
--
ALTER TABLE `attributes_names`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attributes_values`
--
ALTER TABLE `attributes_values`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `county`
--
ALTER TABLE `county`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `langs`
--
ALTER TABLE `langs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `newsletter_subscriber`
--
ALTER TABLE `newsletter_subscriber`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_billing`
--
ALTER TABLE `order_billing`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_shipping`
--
ALTER TABLE `order_shipping`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `producers`
--
ALTER TABLE `producers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1203;

--
-- AUTO_INCREMENT for table `product_attributes_summary`
--
ALTER TABLE `product_attributes_summary`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=623;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images_meta`
--
ALTER TABLE `product_images_meta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_rating`
--
ALTER TABLE `product_rating`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_rating_history`
--
ALTER TABLE `product_rating_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users_permissions`
--
ALTER TABLE `users_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
