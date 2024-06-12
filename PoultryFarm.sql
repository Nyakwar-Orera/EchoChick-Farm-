SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

-- Creating Database
CREATE DATABASE `PoultryFarm`;
USE `PoultryFarm`;


-- Database: `PoultryFarm`

-- ------------------------

-- Creating tables

-- Table structure for table `authorization`
--

CREATE TABLE IF NOT EXISTS `authorization` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `role` varchar(255) CHARACTER SET latin1 NOT NULL,
  `createuser` varchar(255) DEFAULT NULL,
  `deleteuser` varchar(255) DEFAULT NULL,
  `createbid` varchar(255) DEFAULT NULL,
  `updatebid` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting data for table `authorization`
INSERT INTO `authorization` (`id`, `role`, `createuser`, `deleteuser`, `createbid`, `updatebid`) VALUES
(1, 'Superuser', '1', '1', '1', '1'),
(2, 'Admin', '1', '1', '1', '1'),
(3, 'customer', NULL, NULL, '1', NULL);

-- ----------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` int(10) AUTO_INCREMENT PRIMARY KEY,
  `Staffid` int(10) DEFAULT NULL,
  `role` varchar(120) DEFAULT NULL,
  `UserName` varchar(120) DEFAULT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Telephone` bigint DEFAULT NULL,
  `Email` varchar(320) DEFAULT NULL,
  `Status` int(11) NOT NULL DEFAULT 1,
  `Photo` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'avatar15.jpg',
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Populating data for table `admin`
--

INSERT INTO `admin` (`ID`, `Staffid`, `role`, `UserName`, `FirstName`, `LastName`, `Telephone`, `Email`, `Status`, `Photo`, `Password`, `AdminRegdate`) VALUES
(3, 35, 'Admin', 'admin', 'Patrick', 'Ogonyo', 740704740, 'patrickogonyo76@gmail.com', 1, 'pat.jpeg', '$2y$10$767tYGGWwexdSXTqEQqxKOhBxPIEjH4ySgOqW5jFn5ANmUJk3o6va', '2023-12-03 1:18:39'),
(5, 43, 'Admin', 'staff', 'Margret', 'Were', 743765890, 'margret3@gmail.com', 1, 'mag.jpeg', '$2y$10$sVKiOdXWUeeahF.p0i2wauPxZfNmS/EO0NPtJTvr1XBc8nBzUslOi', '2023-12-15 10:18:39');

-- -----------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) AUTO_INCREMENT,
  `CustomerId` int(11), -- Corrected the backticks
  `role` VARCHAR(10) NOT NULL DEFAULT 'Customer',
  `FirstName` VARCHAR(50),
  `LastName` VARCHAR(50),
  `Telephone` bigint,
  `Email` VARCHAR(100),
  `Photo` varchar(255) NOT NULL DEFAULT 'avatar.jpg',
  `Gender` VARCHAR(10),
  `Password` VARCHAR(100),
  `DOB` DATE,
  `Address` VARCHAR(100),
  `Security1` VARCHAR(100),
  `Answer1` VARCHAR(100),
  `Security2` VARCHAR(100),
  `Answer2` VARCHAR(100),
  `CustomerRegdate` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) -- Set the primary key correctly
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- ----------

--
-- Table structure for table `store_out`
--

CREATE TABLE `store_out` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `date` date NOT NULL,
  `item` varchar(500) NOT NULL,
  `quantity` varchar(500) NOT NULL,
  `itemsoutvalue` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------

-- Table structure for table `store_stock`

CREATE TABLE `store_stock` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `date` date NOT NULL,
  `item` varchar(500) NOT NULL,
  `quantity` varchar(500) NOT NULL,
  `rate` varchar(500) NOT NULL,
  `total` varchar(500) NOT NULL,
  `quantity_remaining` varchar(500) NOT NULL,
  `itemvalue` int(15) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `CategoryName` varchar(200) DEFAULT NULL,
  `CategoryCode` varchar(50) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------

--
-- Table structure for table `Farm`
--

CREATE TABLE `farm` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `regno` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `farmname` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `farmemail` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `country` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `farmtelephone` text NOT NULL,
  `farmaddress` varchar(255) CHARACTER SET latin1 NOT NULL,
  `farmlogo` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'avatar.jpg',
  `status` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `developer` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `creationdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Populating 'farm' table 
--

INSERT INTO `farm` (`id`, `regno`, `farmname`, `farmemail`, `country`, `farmtelephone`, `farmaddress`, `farmlogo`, `status`, `developer`, `creationdate`) VALUES
(2, '1036658', 'ECHOCHICK FARM', 'echochickfarm@gmail.com', 'Kenya', '+254112200012', 'Bondo-Usenge Rd', 'poultry.png', '1', 'Patrick_Ogonyo', '2023-11-15 12:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `item` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Creationdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  -----------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `ProductId` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `InvoiceNumber` int(11) DEFAULT NULL,
  `CustomerName` varchar(150) DEFAULT NULL,
  `CustomerContactNo` bigint(12) DEFAULT NULL,
  `PaymentMode` varchar(100) DEFAULT NULL,
  `InvoiceGenDate` timestamp NULL DEFAULT current_timestamp(),
   `CustomerId` int(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ----------------------------------------------------------------------

-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `CategoryName` varchar(150) DEFAULT NULL,
  `ProductName` varchar(150) DEFAULT NULL,
  `ProductImage` varchar(255) DEFAULT NULL,
  `ProductPrice` decimal(10,0) DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ---------------------------------------------------------------------------

-- Table structure for table order_tracking
--

CREATE TABLE `order_tracking` (
  `order_id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `delivery_date` datetime DEFAULT NULL,
  FOREIGN KEY (`customer_id`) REFERENCES `customer`(`id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------


-- ---
-- Table Structure for `inventory_management` table
CREATE TABLE `inventory_management` (
  `inventory_id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `item_id` int(11) NOT NULL,
  `current_stock` int(11) NOT NULL,
  `minimum_stock_level` int(11) NOT NULL,
  `reorder_level` int(11) NOT NULL,
  FOREIGN KEY (`item_id`) REFERENCES `items`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for `user_activity_logs`
--
CREATE TABLE IF NOT EXISTS `user_activity_logs` (
  `log_id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `activity_description` text NOT NULL,
  `activity_date` datetime DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `customer`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 
-- Table structure for 'news'
--

CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    published_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    author VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Table structure for 'messages'
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expire_at DATETIME NOT NULL
);


CREATE TABLE `customer_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------

--
-- Adding Indexes
--

-- Add index on Email and UserName for admin table
CREATE INDEX idx_admin_email ON admin(Email);
CREATE INDEX idx_admin_username ON admin(UserName);

-- Add index on Email for customer table
CREATE INDEX idx_customer_email ON customer(Email);

-- ----------------------------------------------------------------

ALTER TABLE products ADD COLUMN featured BOOLEAN DEFAULT FALSE;
