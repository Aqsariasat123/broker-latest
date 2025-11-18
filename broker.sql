-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 18, 2025 at 12:15 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `broker`
--

-- --------------------------------------------------------

--
-- Table structure for table `beneficial_owners`
--

DROP TABLE IF EXISTS `beneficial_owners`;
CREATE TABLE IF NOT EXISTS `beneficial_owners` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ownership_percentage` decimal(5,2) DEFAULT NULL,
  `id_document_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `poa_document_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `beneficial_owners_owner_code_unique` (`owner_code`),
  KEY `beneficial_owners_client_id_foreign` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `claims`
--

DROP TABLE IF EXISTS `claims`;
CREATE TABLE IF NOT EXISTS `claims` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `claim_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `policy_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loss_date` date DEFAULT NULL,
  `claim_date` date DEFAULT NULL,
  `claim_amount` decimal(15,2) DEFAULT NULL,
  `claim_summary` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `close_date` date DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT NULL,
  `settlment_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `claims_claim_id_unique` (`claim_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nin_bcrn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob_dor` date DEFAULT NULL,
  `mobile_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signed_up` date NOT NULL,
  `employer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `income_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `married` tinyint(1) NOT NULL DEFAULT '0',
  `spouses_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` text COLLATE utf8mb4_unicode_ci,
  `island` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `po_box_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pep` tinyint(1) NOT NULL DEFAULT '0',
  `pep_comment` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salutation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_names` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passport_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_clid_unique` (`clid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `client_name`, `client_type`, `nin_bcrn`, `dob_dor`, `mobile_no`, `wa`, `district`, `occupation`, `source`, `status`, `signed_up`, `employer`, `clid`, `contact_person`, `income_source`, `married`, `spouses_name`, `alternate_no`, `email_address`, `location`, `island`, `country`, `po_box_no`, `pep`, `pep_comment`, `image`, `salutation`, `first_name`, `other_names`, `surname`, `passport_no`, `created_at`, `updated_at`) VALUES
(1, 'test test test', 'Business', '55555', '2025-10-22', '66666666', '5555555555', 'Providence', 'Payroll Officer', 'PUC', 'Active', '2025-10-23', 'test', 'CL1001', 'test', 'Allowance', 1, 'test', '5555555555', 'hhh@gmail.com', 'test', 'Eden', 'Bangladesh', '666', 0, 'test', NULL, 'Ms', 'test', 'test', 'test', '5555555', '2025-10-21 22:13:04', '2025-10-21 22:13:04');

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

DROP TABLE IF EXISTS `commissions`;
CREATE TABLE IF NOT EXISTS `commissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `policy_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `insurer_id` bigint UNSIGNED DEFAULT NULL,
  `grouping` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `basic_premium` decimal(15,2) DEFAULT NULL,
  `rate` decimal(8,2) DEFAULT NULL,
  `amount_due` decimal(15,2) DEFAULT NULL,
  `payment_status_id` bigint UNSIGNED DEFAULT NULL,
  `amount_received` decimal(15,2) DEFAULT NULL,
  `date_received` date DEFAULT NULL,
  `statement_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode_of_payment_id` bigint UNSIGNED DEFAULT NULL,
  `variance` decimal(15,2) DEFAULT NULL,
  `variance_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_due` date DEFAULT NULL,
  `commission_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `commission_note_id` bigint UNSIGNED DEFAULT NULL,
  `commission_statement_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  UNIQUE KEY `commissions_commission_code_unique` (`commission_code`),
  KEY `commissions_policy_id_foreign` (`policy_id`),
  KEY `commissions_client_id_foreign` (`client_id`),
  KEY `commissions_insurer_id_foreign` (`insurer_id`),
  KEY `commissions_payment_status_id_foreign` (`payment_status_id`),
  KEY `commissions_mode_of_payment_id_foreign` (`mode_of_payment_id`),
  KEY `commissions_commission_note_id_foreign` (`commission_note_id`),
  KEY `commissions_commission_statement_id_foreign` (`commission_statement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commission_notes`
--

DROP TABLE IF EXISTS `commission_notes`;
CREATE TABLE IF NOT EXISTS `commission_notes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `com_note_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_on` date DEFAULT NULL,
  `total_premium` decimal(15,2) DEFAULT NULL,
  `expected_commission` decimal(15,2) DEFAULT NULL,
  `attachment_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commission_notes_com_note_id_unique` (`com_note_id`),
  KEY `commission_notes_schedule_id_foreign` (`schedule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commission_statements`
--

DROP TABLE IF EXISTS `commission_statements`;
CREATE TABLE IF NOT EXISTS `commission_statements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `commission_note_id` bigint UNSIGNED DEFAULT NULL,
  `com_stat_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `net_commission` decimal(15,2) DEFAULT NULL,
  `tax_withheld` decimal(15,2) DEFAULT NULL,
  `attachment_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commission_statements_com_stat_id_unique` (`com_stat_id`),
  KEY `commission_statements_commission_note_id_foreign` (`commission_note_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acquired` date DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_contact` date DEFAULT NULL,
  `next_follow_up` date DEFAULT NULL,
  `coid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `salutation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `email_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `savings_budget` decimal(10,2) DEFAULT NULL,
  `married` tinyint(1) NOT NULL DEFAULT '0',
  `children` int NOT NULL DEFAULT '0',
  `children_details` text COLLATE utf8mb4_unicode_ci,
  `vehicle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `house` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contacts_contact_id_unique` (`contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `contact_name`, `contact_no`, `type`, `occupation`, `employer`, `acquired`, `source`, `status`, `rank`, `first_contact`, `next_follow_up`, `coid`, `dob`, `salutation`, `source_name`, `agency`, `agent`, `address`, `email_address`, `contact_id`, `savings_budget`, `married`, `children`, `children_details`, `vehicle`, `house`, `business`, `other`, `created_at`, `updated_at`) VALUES
(2, 'test', '555555555', 'Contact', 'test', 'test', '2025-10-21', 'Online', 'Keep In View', 'High', '2025-10-22', '2025-11-01', '44444444', '2025-10-22', 'Ms', 'test', 'LIS', 'Mandy', 'test', 'gg@gmail.com', 'CT166', 4.00, 1, 6, 'test', 'test', 'test', 'test', 'test', '2025-10-21 22:11:48', '2025-10-21 22:11:48');

-- --------------------------------------------------------

--
-- Table structure for table `debit_notes`
--

DROP TABLE IF EXISTS `debit_notes`;
CREATE TABLE IF NOT EXISTS `debit_notes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_plan_id` bigint UNSIGNED NOT NULL,
  `debit_note_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_on` date DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `document_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `debit_notes_debit_note_no_unique` (`debit_note_no`),
  KEY `debit_notes_payment_plan_id_foreign` (`payment_plan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `doc_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tied_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_doc_id_unique` (`doc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `endorsements`
--

DROP TABLE IF EXISTS `endorsements`;
CREATE TABLE IF NOT EXISTS `endorsements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `policy_id` bigint UNSIGNED NOT NULL,
  `endorsement_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `description` text COLLATE utf8mb4_unicode_ci,
  `document_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `endorsements_endorsement_no_unique` (`endorsement_no`),
  KEY `endorsements_policy_id_foreign` (`policy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `expense_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payee` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_paid` date NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expenses_expense_id_unique` (`expense_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_id`, `payee`, `date_paid`, `amount_paid`, `description`, `category`, `mode_of_payment`, `expense_notes`, `created_at`, `updated_at`) VALUES
(1, 'EX1001', '555', '2025-10-16', 444.00, 'test', 'Travel', 'Bank Transfer', 'test', '2025-10-21 22:18:50', '2025-10-21 22:18:50');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `followups`
--

DROP TABLE IF EXISTS `followups`;
CREATE TABLE IF NOT EXISTS `followups` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `follow_up_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `life_proposal_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `channel` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `summary` text COLLATE utf8mb4_unicode_ci,
  `next_action` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `followups_follow_up_code_unique` (`follow_up_code`),
  KEY `followups_contact_id_foreign` (`contact_id`),
  KEY `followups_client_id_foreign` (`client_id`),
  KEY `followups_life_proposal_id_foreign` (`life_proposal_id`),
  KEY `followups_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

DROP TABLE IF EXISTS `incomes`;
CREATE TABLE IF NOT EXISTS `incomes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `income_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `income_source_id` bigint UNSIGNED DEFAULT NULL,
  `date_rcvd` date DEFAULT NULL,
  `amount_received` decimal(15,2) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode_of_payment_id` bigint UNSIGNED DEFAULT NULL,
  `statement_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `income_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `incomes_income_id_unique` (`income_id`),
  KEY `incomes_income_source_id_foreign` (`income_source_id`),
  KEY `incomes_mode_of_payment_id_foreign` (`mode_of_payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `life_proposals`
--

DROP TABLE IF EXISTS `life_proposals`;
CREATE TABLE IF NOT EXISTS `life_proposals` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposers_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `insurer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `policy_plan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sum_assured` decimal(15,2) DEFAULT NULL,
  `term` int NOT NULL,
  `add_ons` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `offer_date` date NOT NULL,
  `premium` decimal(10,2) NOT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `age` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mcr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doctor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `date_completed` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `agency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_submitted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `life_proposals_prid_unique` (`prid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `life_proposals`
--

INSERT INTO `life_proposals` (`id`, `proposers_name`, `insurer`, `policy_plan`, `sum_assured`, `term`, `add_ons`, `offer_date`, `premium`, `frequency`, `stage`, `date`, `age`, `status`, `source_of_payment`, `mcr`, `doctor`, `date_sent`, `date_completed`, `notes`, `agency`, `prid`, `class`, `is_submitted`, `created_at`, `updated_at`) VALUES
(1, 'test', 'Hsavy', 'Householder\'s', 444.00, 44, 'Accidental Death', '2025-10-30', 444.00, 'Days', 'Offer Made', '2025-10-22', 45, 'Approved', 'Prize', '555', 'Dr. Williams', '2025-10-23', '2025-10-30', 'test', 'Keystone', 'PR1001', 'General', 0, '2025-10-21 22:13:57', '2025-10-21 22:13:57');

-- --------------------------------------------------------

--
-- Table structure for table `lookup_categories`
--

DROP TABLE IF EXISTS `lookup_categories`;
CREATE TABLE IF NOT EXISTS `lookup_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lookup_categories_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lookup_categories`
--

INSERT INTO `lookup_categories` (`id`, `name`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Insurers', 1, NULL, NULL),
(2, 'Policy Classes', 1, NULL, NULL),
(3, 'Policy Plans', 1, NULL, NULL),
(4, 'Policy Statuses', 1, NULL, NULL),
(5, 'Business Types', 1, NULL, NULL),
(6, 'Term Units', 1, NULL, NULL),
(7, 'Frequencies', 1, NULL, NULL),
(8, 'Pay Plans', 1, NULL, NULL),
(9, 'Contact Type', 1, '2025-10-21 20:53:40', '2025-10-21 20:53:40'),
(10, 'Claim Stage', 1, '2025-10-21 20:53:40', '2025-10-21 20:53:40'),
(11, 'Vehicle Make', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(12, 'Client Type', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(13, 'Insurer', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(14, 'Frequency', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(15, 'Payment Plan', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(16, 'Contact Stage', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(17, 'Source', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(18, 'Contact Status', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(19, 'Policy Status', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(20, 'APL Agency', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(21, 'Payment Status', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(22, 'Agent', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(23, 'Ranking', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(24, 'Client Status', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(25, 'Issuing Country', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(26, 'Source Of Payment', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(27, 'ID Type', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(28, 'Class', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(29, 'Island', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(30, 'Mode Of Payment (Life)', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(31, 'Claim Status', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(32, 'Salutation', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(33, 'Mode Of Payment (General)', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(34, 'Useage', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(35, 'Expense Category', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(36, 'Vehicle Type', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(37, 'Income Category', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(38, 'Business Type', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(39, 'Income Source', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(40, 'Proposal Stage', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(41, 'Proposal Status', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(42, 'PaymentType', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(43, 'Term', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(44, 'Engine Type', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(45, 'ENDORSEMENT', 1, '2025-10-21 20:53:41', '2025-10-21 20:53:41');

-- --------------------------------------------------------

--
-- Table structure for table `lookup_values`
--

DROP TABLE IF EXISTS `lookup_values`;
CREATE TABLE IF NOT EXISTS `lookup_values` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `lookup_category_id` bigint UNSIGNED NOT NULL,
  `seq` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lookup_values_lookup_category_id_seq_unique` (`lookup_category_id`,`seq`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lookup_values`
--

INSERT INTO `lookup_values` (`id`, `lookup_category_id`, `seq`, `name`, `active`, `description`, `type`, `code`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'SACOS', 1, NULL, NULL, NULL, NULL, NULL),
(2, 1, 2, 'Alliance', 1, NULL, NULL, NULL, NULL, NULL),
(3, 1, 3, 'Hsavy', 1, NULL, NULL, NULL, NULL, NULL),
(4, 1, 4, 'AON', 1, NULL, NULL, NULL, NULL, NULL),
(5, 1, 5, 'Marsh', 1, NULL, NULL, NULL, NULL, NULL),
(6, 2, 1, 'Motor', 1, NULL, NULL, NULL, NULL, NULL),
(7, 2, 2, 'General', 1, NULL, NULL, NULL, NULL, NULL),
(8, 2, 3, 'Travel', 1, NULL, NULL, NULL, NULL, NULL),
(9, 2, 4, 'Marine', 1, NULL, NULL, NULL, NULL, NULL),
(10, 2, 5, 'Health', 1, NULL, NULL, NULL, NULL, NULL),
(11, 2, 6, 'Life', 1, NULL, NULL, NULL, NULL, NULL),
(12, 3, 1, 'Comprehensive', 1, NULL, NULL, NULL, NULL, NULL),
(13, 3, 2, 'Third Party', 1, NULL, NULL, NULL, NULL, NULL),
(14, 3, 3, 'Householder\'s', 1, NULL, NULL, NULL, NULL, NULL),
(15, 3, 4, 'Public Liability', 1, NULL, NULL, NULL, NULL, NULL),
(16, 3, 5, 'Employer\'s Liability', 1, NULL, NULL, NULL, NULL, NULL),
(17, 3, 6, 'Fire & Special Perils', 1, NULL, NULL, NULL, NULL, NULL),
(18, 3, 7, 'House Insurance', 1, NULL, NULL, NULL, NULL, NULL),
(19, 3, 8, 'Fire Industrial', 1, NULL, NULL, NULL, NULL, NULL),
(20, 3, 9, 'World Wide Basic', 1, NULL, NULL, NULL, NULL, NULL),
(21, 3, 10, 'Marine Hull', 1, NULL, NULL, NULL, NULL, NULL),
(22, 4, 1, 'In Force', 1, NULL, NULL, NULL, NULL, NULL),
(23, 4, 2, 'DFR', 1, NULL, NULL, NULL, NULL, NULL),
(24, 4, 3, 'Expired', 1, NULL, NULL, NULL, NULL, NULL),
(25, 4, 4, 'Cancelled', 1, NULL, NULL, NULL, NULL, NULL),
(26, 5, 1, 'Direct', 1, NULL, NULL, NULL, NULL, NULL),
(27, 5, 2, 'Transfer', 1, NULL, NULL, NULL, NULL, NULL),
(28, 5, 3, 'Renewal', 1, NULL, NULL, NULL, NULL, NULL),
(29, 6, 1, 'Year', 1, NULL, NULL, NULL, NULL, NULL),
(30, 6, 2, 'Month', 1, NULL, NULL, NULL, NULL, NULL),
(31, 6, 3, 'Days', 1, NULL, NULL, NULL, NULL, NULL),
(32, 7, 1, 'Annually', 1, NULL, NULL, NULL, NULL, NULL),
(33, 7, 2, 'Monthly', 1, NULL, NULL, NULL, NULL, NULL),
(34, 7, 3, 'Quarterly', 1, NULL, NULL, NULL, NULL, NULL),
(35, 7, 4, 'One Off', 1, NULL, NULL, NULL, NULL, NULL),
(36, 7, 5, 'Single', 1, NULL, NULL, NULL, NULL, NULL),
(37, 8, 1, 'Full', 1, NULL, NULL, NULL, NULL, NULL),
(38, 8, 2, 'Instalments', 1, NULL, NULL, NULL, NULL, NULL),
(39, 8, 3, 'Regular', 1, NULL, NULL, NULL, NULL, NULL),
(40, 9, 1, 'Lead', 1, NULL, NULL, NULL, '2025-10-21 20:53:40', '2025-10-21 20:53:40'),
(41, 9, 2, 'Prospect', 1, NULL, NULL, NULL, '2025-10-21 20:53:40', '2025-10-21 20:53:40'),
(42, 9, 3, 'Contact', 1, NULL, NULL, NULL, '2025-10-21 20:53:40', '2025-10-21 20:53:40'),
(43, 9, 4, 'SO Bank Officer', 1, NULL, NULL, NULL, '2025-10-21 20:53:40', '2025-10-21 20:53:40'),
(44, 9, 5, 'Payroll Officer', 1, NULL, NULL, NULL, '2025-10-21 20:53:40', '2025-10-21 20:53:40'),
(45, 10, 1, 'Awaiting Documents', 1, NULL, NULL, NULL, '2025-10-21 20:53:40', '2025-10-21 20:53:40'),
(46, 10, 2, 'Awaiting QS Report', 1, NULL, NULL, NULL, '2025-10-21 20:53:40', '2025-10-21 20:53:40'),
(47, 11, 1, 'Hyundai', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(48, 11, 2, 'Kia', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(49, 11, 3, 'Suzuki', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(50, 11, 4, 'Toyota', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(51, 11, 5, 'Ford', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(52, 11, 6, 'MG', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(53, 11, 7, 'Nissan', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(54, 11, 8, 'Mazda', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(55, 11, 9, 'BMW', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(56, 11, 10, 'Mercedes', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(57, 11, 11, 'Lexus', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(58, 11, 12, 'Haval', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(59, 11, 13, 'Honda', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(60, 11, 14, 'Tata', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(61, 11, 15, 'Isuzu', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(62, 12, 1, 'Individual', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(63, 12, 2, 'Business', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(64, 12, 3, 'Company', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(65, 12, 4, 'Organization', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(66, 13, 1, 'SACOS', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(67, 13, 2, 'HSavy', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(68, 13, 3, 'Alliance', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(69, 13, 4, 'MUA', 0, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(70, 14, 1, 'Year', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(71, 14, 2, 'Days', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(72, 14, 3, 'Weeks', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(73, 15, 1, 'Single', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(74, 15, 2, 'Instalments', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(75, 15, 3, 'Regular (Life)', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(76, 16, 1, 'Open', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(77, 16, 2, 'Qualified', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(78, 16, 3, 'KIV', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(79, 16, 4, 'Closed', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(80, 17, 1, 'Direct', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(81, 17, 2, 'Online', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(82, 17, 3, 'Bank ABSA', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(83, 17, 4, 'MCB', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(84, 17, 5, 'NOU', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(85, 17, 6, 'BAR', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(86, 17, 7, 'BOC', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(87, 17, 8, 'SCB', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(88, 17, 9, 'SCU', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(89, 17, 10, 'AIRTEL', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(90, 17, 11, 'Cable & Wireless', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(91, 17, 12, 'Intelvision', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(92, 17, 13, 'PUC', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(93, 17, 14, 'SFA', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(94, 17, 15, 'STC', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(95, 17, 16, 'FSA', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(96, 17, 17, 'Mins Of Education', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(97, 17, 18, 'Mins Of Health', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(98, 17, 19, 'SFRSA', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(99, 17, 20, 'Seychelles Police', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(100, 17, 21, 'Treasury', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(101, 17, 22, 'Judiciary', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(102, 17, 23, 'Pilgrims', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(103, 17, 24, 'SPTC', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(104, 18, 1, 'Not Contacted', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(105, 18, 2, 'Qualified', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(106, 18, 3, 'Converted to Client', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(107, 18, 4, 'Keep In View', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(108, 18, 5, 'Archived', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(109, 19, 1, 'In Force', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(110, 19, 2, 'Expired', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(111, 19, 3, 'Cancelled', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(112, 19, 4, 'Lapsed', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(113, 19, 5, 'Matured', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(114, 19, 6, 'Surrenders', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(115, 19, 7, 'Payout D', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(116, 19, 8, 'Payout TPD', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(117, 19, 9, 'Null & Void', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(118, 20, 1, 'Keystone', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(119, 20, 2, 'LIS', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(120, 21, 1, 'Paid', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(121, 21, 2, 'Partly Paid', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(122, 21, 3, 'Unpaid', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(123, 22, 1, 'Mandy', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(124, 22, 2, 'Simon', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(125, 23, 1, 'VIP', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(126, 23, 2, 'High', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(127, 23, 3, 'Medium', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(128, 23, 4, 'Low', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(129, 24, 1, 'Active', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(130, 24, 2, 'Dormant', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(131, 25, 1, 'Seychelles', 1, NULL, NULL, 'SEY', '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(132, 25, 2, 'Great Britain', 1, NULL, NULL, 'GBR', '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(133, 25, 3, 'Botswana', 1, NULL, NULL, 'BOT', '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(134, 25, 4, 'Sri Lanka', 1, NULL, NULL, 'SRI', '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(135, 25, 5, 'India', 1, NULL, NULL, 'IND', '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(136, 25, 6, 'Nepal', 1, NULL, NULL, 'NEP', '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(137, 25, 7, 'Bangladesh', 1, NULL, NULL, 'BAN', '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(138, 25, 8, 'Russia', 1, NULL, NULL, 'RUS', '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(139, 25, 9, 'Ukraine', 1, NULL, NULL, 'UKR', '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(140, 25, 10, 'Kenya', 1, NULL, NULL, 'KEN', '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(141, 26, 1, 'Commission', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(142, 26, 2, 'Bonus', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(143, 26, 3, 'Prize', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(144, 26, 4, 'Other', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(145, 27, 1, 'ID Card', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(146, 27, 2, 'Driving License', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(147, 27, 3, 'Passport', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(148, 28, 1, 'Motor', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(149, 28, 2, 'General', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(150, 28, 3, 'Life', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(151, 28, 4, 'Bonds', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(152, 28, 5, 'Travel', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(153, 28, 6, 'Marine', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(154, 28, 7, 'Health', 0, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(155, 29, 1, 'Mahe', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(156, 29, 2, 'Praslin', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(157, 29, 3, 'La Digue', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(158, 29, 4, 'Perseverance', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(159, 29, 5, 'Cerf', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(160, 29, 6, 'Eden', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(161, 29, 7, 'Silhouette', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(162, 30, 1, 'Transfer', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(163, 30, 2, 'Cheque', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(164, 30, 3, 'Cash', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(165, 30, 4, 'Online', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(166, 30, 5, 'Standing Order', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(167, 30, 6, 'Salary Deduction', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(168, 30, 7, 'Direect', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(169, 31, 1, 'Processing', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(170, 31, 2, 'Settled', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(171, 31, 3, 'Declined', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(172, 32, 1, 'Mr', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(173, 32, 2, 'Ms', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(174, 32, 3, 'Mrs', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(175, 32, 4, 'Miss', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(176, 32, 5, 'Dr', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(177, 32, 6, 'Mr & Mrs', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(178, 33, 1, 'Cash', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(179, 33, 2, 'Card', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(180, 33, 3, 'Transfer', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(181, 33, 4, 'Cheque', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(182, 34, 1, 'Private', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(183, 34, 2, 'Commercial', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(184, 34, 3, 'For Hire', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(185, 34, 4, 'Carriage Of Goods', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(186, 34, 5, 'Commuter', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(187, 35, 1, 'License', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(188, 35, 2, 'Insurance', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(189, 35, 3, 'Office supplies', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(190, 35, 4, 'Telephone & Internet', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(191, 35, 5, 'Marketting', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(192, 35, 6, 'Travel', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(193, 35, 7, 'Referals', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(194, 35, 8, 'Rentals', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(195, 35, 9, 'Vehicle', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(196, 35, 10, 'Fuel', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(197, 35, 11, 'Bank Fees', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(198, 35, 12, 'Charges', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(199, 35, 13, 'Misc', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(200, 35, 14, 'Asset Purchase', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(201, 36, 1, 'SUV', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(202, 36, 2, 'Hatchback', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(203, 36, 3, 'Sedan', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(204, 36, 4, 'Twin Cab', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(205, 36, 5, 'Pick Up', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(206, 36, 6, 'Scooter', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(207, 36, 7, 'Motor Cycle', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(208, 36, 8, 'Taxi', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(209, 36, 9, 'Van', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(210, 37, 1, 'Less than 10,000', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(211, 37, 2, '10,001 to 20,000', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(212, 37, 3, '20,001 to 30,000', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(213, 37, 4, '30,001 to 40,000', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(214, 37, 5, '40,001 to 50,000', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(215, 37, 6, '50,001 and above', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(216, 38, 1, 'Direct', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(217, 38, 2, 'Transfer', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(218, 38, 3, 'Renewal', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(219, 39, 1, 'Employment', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(220, 39, 2, 'Self Employed', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(221, 39, 3, 'Business', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(222, 39, 4, 'Investment', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(223, 39, 5, 'Rentals', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(224, 39, 6, 'Retirement', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(225, 39, 7, 'Allowance', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(226, 39, 8, 'Other', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(227, 40, 1, 'Not Contacted', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(228, 40, 2, 'RNR', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(229, 40, 3, 'In Discussion', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(230, 40, 4, 'Offer Made', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(231, 40, 5, 'Proposal Filled', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(232, 41, 1, 'Awaiting Medical', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(233, 41, 2, 'Awaiting Policy', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(234, 41, 3, 'Approved', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(235, 41, 4, 'Declined', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(236, 41, 5, 'Withdrawn', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(237, 42, 1, 'Full', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(238, 42, 2, 'Instalment', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(239, 42, 3, 'Adjustment', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(240, 43, 1, 'Annual', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(241, 43, 2, 'Single', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(242, 43, 3, 'Monthly', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(243, 43, 4, 'Quarterly', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(244, 43, 5, 'Bi-Annual', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(245, 44, 1, 'Hybrid', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(246, 44, 2, 'Petrol', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(247, 44, 3, 'Diesel', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(248, 44, 4, 'Electric', 1, NULL, NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(249, 45, 1, 'Renewal', 1, 'Policy Renewed', NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(250, 45, 2, 'Cancelation', 1, 'Policy Cancelled', NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(251, 45, 3, 'Amendment', 1, 'Sum Insured Reduced', NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(252, 45, 4, 'Amendment', 1, 'Sum Insured Increased', NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(253, 45, 5, 'Amendment', 1, 'Plan Cover Changed', NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(254, 45, 6, 'Amendment', 1, 'Beneficary change', NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(255, 45, 7, 'Amendment', 1, 'Pay Plan Changed', NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41'),
(256, 45, 8, 'Amendment', 1, 'Vehicle changed', NULL, NULL, '2025-10-21 20:53:41', '2025-10-21 20:53:41');

-- --------------------------------------------------------

--
-- Table structure for table `medicals`
--

DROP TABLE IF EXISTS `medicals`;
CREATE TABLE IF NOT EXISTS `medicals` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `life_proposal_id` bigint UNSIGNED NOT NULL,
  `medical_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `medical_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordered_on` date DEFAULT NULL,
  `completed_on` date DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `results_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `medicals_medical_code_unique` (`medical_code`),
  KEY `medicals_life_proposal_id_foreign` (`life_proposal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(11, '0001_01_01_000000_create_users_table', 1),
(12, '0001_01_01_000001_create_cache_table', 1),
(13, '0001_01_01_000002_create_jobs_table', 1),
(14, '2025_10_05_171758_create_tasks_table', 1),
(15, '2025_10_11_084446_create_lookup_tables', 1),
(16, '2025_10_15_101405_create_policies_table', 1),
(17, '2025_10_19_112711_create_contacts_table', 1),
(18, '2025_10_19_153256_create_clients_table', 1),
(19, '2025_10_19_184103_create_life_proposals_table', 1),
(20, '2025_10_19_200529_create_expenses_table', 1),
(21, '2025_11_09_125623_create_documents_table', 2),
(22, '2025_11_09_141513_create_vehicles_table', 2),
(23, '2025_11_09_145357_create_claims_table', 2),
(24, '2025_11_09_152705_create_incomes_table', 2),
(26, '2025_11_09_180836_create_statements_table', 2),
(27, '2025_11_18_112128_create_beneficial_owners_table', 3),
(28, '2025_11_18_112148_create_nominees_table', 3),
(29, '2025_11_18_112215_create_renewal_notices_table', 3),
(30, '2025_11_18_112236_create_schedules_table', 3),
(31, '2025_11_18_112248_create_payment_plans_table', 3),
(32, '2025_11_18_112305_create_debit_notes_table', 3),
(33, '2025_11_18_112314_create_payments_table', 3),
(34, '2025_11_18_112323_create_endorsements_table', 3),
(35, '2025_11_18_112341_create_followups_table', 3),
(36, '2025_11_18_112352_create_medicals_table', 3),
(37, '2025_11_18_112401_create_commission_notes_table', 3),
(38, '2025_11_18_112418_create_commission_statements_table', 3),
(39, '2025_11_18_112435_create_tax_returns_table', 3),
(40, '2025_11_09_163024_create_commissions_table', 4),
(41, '2025_11_18_112445_add_note_and_statement_refs_to_commissions_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `nominees`
--

DROP TABLE IF EXISTS `nominees`;
CREATE TABLE IF NOT EXISTS `nominees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nominee_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `policy_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `share_percentage` decimal(5,2) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `id_document_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nominees_nominee_code_unique` (`nominee_code`),
  KEY `nominees_policy_id_foreign` (`policy_id`),
  KEY `nominees_client_id_foreign` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `debit_note_id` bigint UNSIGNED NOT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_on` date DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `mode_of_payment_id` bigint UNSIGNED DEFAULT NULL,
  `receipt_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_reference_unique` (`payment_reference`),
  KEY `payments_debit_note_id_foreign` (`debit_note_id`),
  KEY `payments_mode_of_payment_id_foreign` (`mode_of_payment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_plans`
--

DROP TABLE IF EXISTS `payment_plans`;
CREATE TABLE IF NOT EXISTS `payment_plans` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `installment_label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `frequency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_plans_schedule_id_foreign` (`schedule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

DROP TABLE IF EXISTS `policies`;
CREATE TABLE IF NOT EXISTS `policies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `policy_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `insurer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `policy_class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `policy_plan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sum_insured` decimal(15,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `insured` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_registered` date NOT NULL,
  `policy_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `insured_item` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `renewable` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biz_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term` int NOT NULL,
  `term_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_premium` decimal(10,2) NOT NULL,
  `premium` decimal(10,2) NOT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pay_plan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `policies_policy_id_unique` (`policy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `policy_no`, `client_name`, `insurer`, `policy_class`, `policy_plan`, `sum_insured`, `start_date`, `end_date`, `insured`, `policy_status`, `date_registered`, `policy_id`, `insured_item`, `renewable`, `biz_type`, `term`, `term_unit`, `base_premium`, `premium`, `frequency`, `pay_plan`, `agency`, `agent`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'MPV-23-HEA-P0002132', 'Jean Grey', 'SACOS', 'Motor', 'Comprehensive', 390000.00, '2023-10-16', '2024-10-15', 'S44444', 'DFR', '2024-10-16', 'PL111', 'Suzuki Fronx', 'Yes', 'Direct', 1, 'Year', 9875.77, 11455.89, 'Annually', 'Full', NULL, NULL, 'New vehicle policy', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(2, 'FSP-21-P00012999', 'Barbara Walton', 'SACOS', 'General', 'Householder\'s', NULL, '2020-04-18', '2025-04-17', NULL, 'In Force', '2020-04-18', 'PL110', 'Residence at Anse Royal', 'Yes', 'Direct', 1, 'Year', 7650.00, 35467.00, 'Annually', 'Full', NULL, NULL, 'Home insurance policy', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(3, 'PL-22-ALP-000033', 'Cornerstone (Pty) Ltd', 'Alliance', 'General', 'Public Liability', NULL, '2022-11-30', '2023-11-29', NULL, 'DFR', '2022-11-30', 'PL109', NULL, 'Yes', 'Direct', 1, 'Year', 5000.00, 5800.00, 'Annually', 'Full', NULL, NULL, 'Business liability insurance', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(4, 'HS1-23-P00023132', 'Cornerstone (Pty) Ltd', 'Alliance', 'General', 'Employer\'s Liability', NULL, '2022-11-12', '2023-11-11', NULL, 'DFR', '2022-11-12', 'PL108', NULL, 'Yes', 'Direct', 1, 'Year', 2500.00, 2900.00, 'Annually', 'Instalments', NULL, NULL, 'Employee coverage', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(5, 'FSP-19-P00024', 'Anna\'s Spa', 'SACOS', 'General', 'Fire & Special Perils', NULL, '2023-10-06', '2024-10-05', NULL, 'Expired', '2022-10-05', 'PL107', 'SPA at English River', 'Yes', 'Direct', 1, 'Year', 3750.00, 4350.00, 'Annually', 'Full', NULL, NULL, 'Spa business insurance', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(6, 'MVC-18-000331', 'Brian Trapper', 'Hsavy', 'Motor', 'Comprehensive', 285000.00, '2022-11-15', '2023-11-14', 'S260', 'In Force', '2022-11-15', 'PL106', 'Toyota Hyrider', 'Yes', 'Direct', 1, 'Year', 6652.00, 7716.32, 'Annually', 'Full', NULL, NULL, 'SUV insurance', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(7, 'MTC-22-000012', 'Adbul Juma', 'Alliance', 'Motor', 'Third Party', 0.00, '2022-09-11', '2023-09-10', 'S32453', 'Cancelled', '2022-09-11', 'PL105', 'Hyundai Creta', 'Yes', 'Transfer', 1, 'Year', 1500.00, 1827.00, 'Annually', 'Full', NULL, NULL, 'Third party only', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(8, 'MVT-21-000324', 'Beta Center', 'Hsavy', 'General', 'Fire & Special Perils', NULL, '2022-12-03', '2023-12-02', NULL, 'In Force', '2022-12-03', 'PL104', 'Shop Office Complex Providence', 'Yes', 'Transfer', 1, 'Year', 14377.00, 16677.32, 'Annually', 'Full', NULL, NULL, 'Commercial property', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(9, 'MVT-21-000324', 'Steven Drax', 'Hsavy', 'General', 'House Insurance', NULL, '2024-01-04', '2025-01-03', NULL, 'In Force', '2023-01-04', 'PL103', 'Residence at Belombre', 'Yes', 'Direct', 1, 'Year', 8765.60, 10168.10, 'Annually', 'Full', NULL, NULL, 'Residential property', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(10, 'HS1-070-1-P0000435', 'Cold Cuts', 'Alliance', 'General', 'Fire Industrial', NULL, '2022-08-12', '2023-08-11', NULL, 'Expired', '2022-08-12', 'PL102', NULL, 'Yes', 'Transfer', 1, 'Year', 4750.00, 5510.00, 'Annually', 'Full', NULL, NULL, 'Industrial fire coverage', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(11, 'MVC-22-000023', 'Atlas Cars', 'Alliance', 'Motor', 'Comprehensive', 386000.00, '2022-08-16', '2023-08-15', NULL, 'In Force', '2022-08-16', 'PL101', NULL, 'Yes', 'Direct', 1, 'Year', 14325.55, 16617.64, 'Annually', 'Full', NULL, NULL, 'Fleet insurance', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(12, 'TIP-23-P67367283', 'Trevor Thomas', 'SACOS', 'Travel', 'World Wide Basic', NULL, '2022-08-06', '2022-08-20', NULL, 'In Force', '2022-08-06', 'PL100', NULL, 'No', 'Direct', 14, 'Days', 1657.44, 1657.44, 'One Off', 'Full', NULL, NULL, 'Travel insurance', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(13, 'PHV-22-P0000233', 'Walter Cox', 'SACOS', 'Marine', 'Marine Hull', 400000.00, '2022-06-03', '2023-06-02', NULL, 'In Force', '2021-06-03', 'PL99', NULL, 'No', 'Direct', 20, 'Year', 13456.00, 13436.00, 'Monthly', 'Regular', NULL, NULL, 'Boat insurance', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(14, 'HS1-22-HIN-000321', 'Tom Blakley', 'Hsavy', 'General', 'House Insurance', 2500000.00, '2023-11-04', '2024-11-19', NULL, 'DFR', '2022-11-03', 'PL98', NULL, 'No', 'Direct', 12, 'Year', 35467.00, 8874.00, 'Single', 'Full', NULL, NULL, 'Long term house insurance', '2025-10-21 20:39:38', '2025-10-21 20:39:38'),
(15, 'ttttttMVT-21-000327800', 'test', 'SACOS', 'Motor', 'House Insurance', 44444.00, '2025-10-22', '2025-10-24', '555', 'DFR', '2025-10-22', 'MVT-21-000324666', '5555', 'Yes', 'Direct', 555, 'Year', 444.00, 5555.00, 'Annually', 'Full', 'test', 'test', 'test', '2025-10-21 22:15:07', '2025-10-21 22:15:07');

-- --------------------------------------------------------

--
-- Table structure for table `renewal_notices`
--

DROP TABLE IF EXISTS `renewal_notices`;
CREATE TABLE IF NOT EXISTS `renewal_notices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `policy_id` bigint UNSIGNED NOT NULL,
  `rnid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notice_date` date DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `delivery_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `renewal_notices_rnid_unique` (`rnid`),
  KEY `renewal_notices_policy_id_foreign` (`policy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
CREATE TABLE IF NOT EXISTS `schedules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `policy_id` bigint UNSIGNED NOT NULL,
  `schedule_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_on` date DEFAULT NULL,
  `effective_from` date DEFAULT NULL,
  `effective_to` date DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `debit_note_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_schedule_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `renewal_notice_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_agreement_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `schedules_schedule_no_unique` (`schedule_no`),
  KEY `schedules_policy_id_foreign` (`policy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1acLEZ1J0mUIikFVHW35aMjzVQmXGwDWLGSjL9bf', 1, '37.111.145.232', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid1lYNWJzWkNBbGZIaVBYTFIxSlJ6SFFnTXJVWnlnNjdBdmZmY0RiayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYnJva2VyLmJ5cmVkc3RvbmUuY29tL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1763460328),
('cfDE0Ik8mEbiABmDKZsUk7GXG1gGhWZYa0ZMDjXw', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 OPR/123.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVXR6Z1oySk5PNVhtOXhTMXVIUHF4TzBscHc5eXk2TzY3dHdScWE0dyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wb2xpY2llcy8xIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1763467504);

-- --------------------------------------------------------

--
-- Table structure for table `statements`
--

DROP TABLE IF EXISTS `statements`;
CREATE TABLE IF NOT EXISTS `statements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `statement_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurer_id` bigint UNSIGNED DEFAULT NULL,
  `business_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_received` date DEFAULT NULL,
  `amount_received` decimal(15,2) DEFAULT NULL,
  `mode_of_payment_id` bigint UNSIGNED DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `statements_statement_no_unique` (`statement_no`),
  KEY `statements_insurer_id_foreign` (`insurer_id`),
  KEY `statements_mode_of_payment_id_foreign` (`mode_of_payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date NOT NULL,
  `due_time` time DEFAULT NULL,
  `date_in` date DEFAULT NULL,
  `assignee` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_status` enum('Not Done','In Progress','Completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Not Done',
  `date_done` date DEFAULT NULL,
  `repeat` tinyint(1) NOT NULL DEFAULT '0',
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rpt_date` date DEFAULT NULL,
  `rpt_stop_date` date DEFAULT NULL,
  `task_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tasks_task_id_unique` (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_id`, `category`, `description`, `name`, `contact_no`, `due_date`, `due_time`, `date_in`, `assignee`, `task_status`, `date_done`, `repeat`, `frequency`, `rpt_date`, `rpt_stop_date`, `task_notes`, `created_at`, `updated_at`) VALUES
(2, 'TK24001', 'test', 'test', 'test', '4444444444444', '2025-10-22', '09:09:00', '2025-10-23', 'fff', 'In Progress', '2025-10-22', 1, 'day', '2025-10-22', '2025-10-22', 'test', '2025-10-21 22:08:47', '2025-10-21 22:08:47'),
(3, 'TK24002', 'abc', 'sdfghbvfdrty', 'adminsstrdyfughj', '4567890987654', '2025-11-10', '14:34:00', '2025-11-10', 'ertyuiilkjhgfd', 'In Progress', '2025-11-10', 0, NULL, '2025-11-09', '2025-11-11', 'wertyuiugf', '2025-11-10 16:36:33', '2025-11-10 16:36:33');

-- --------------------------------------------------------

--
-- Table structure for table `tax_returns`
--

DROP TABLE IF EXISTS `tax_returns`;
CREATE TABLE IF NOT EXISTS `tax_returns` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `commission_statement_id` bigint UNSIGNED NOT NULL,
  `tax_ref_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filing_period` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filed_on` date DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `amount_due` decimal(15,2) DEFAULT NULL,
  `amount_paid` decimal(15,2) DEFAULT NULL,
  `supporting_doc_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tax_returns_tax_ref_id_unique` (`tax_ref_id`),
  KEY `tax_returns_commission_statement_id_foreign` (`commission_statement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@example.com', NULL, '$2y$12$.dSd/Wr9Iu0oj2JQ1gxlfuu.eM9GOKBneQ6lGBzs5Z6Xl0dKIiaRW', NULL, '2025-10-21 20:41:17', '2025-10-21 20:41:17');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regn_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `make` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `useage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` decimal(15,2) DEFAULT NULL,
  `policy_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `engine` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `engine_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `engine_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chassis_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from` date DEFAULT NULL,
  `to` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_vehicle_id_unique` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_income_source_id_foreign` FOREIGN KEY (`income_source_id`) REFERENCES `lookup_values` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incomes_mode_of_payment_id_foreign` FOREIGN KEY (`mode_of_payment_id`) REFERENCES `lookup_values` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lookup_values`
--
ALTER TABLE `lookup_values`
  ADD CONSTRAINT `lookup_values_lookup_category_id_foreign` FOREIGN KEY (`lookup_category_id`) REFERENCES `lookup_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `statements`
--
ALTER TABLE `statements`
  ADD CONSTRAINT `statements_insurer_id_foreign` FOREIGN KEY (`insurer_id`) REFERENCES `lookup_values` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `statements_mode_of_payment_id_foreign` FOREIGN KEY (`mode_of_payment_id`) REFERENCES `lookup_values` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
