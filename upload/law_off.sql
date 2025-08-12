-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12-Maio-2021 às 15:17
-- Versão do servidor: 10.4.17-MariaDB
-- versão do PHP: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `advocacia`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pessoasingular_id` bigint(20) UNSIGNED NOT NULL,
  `utilizador_id` bigint(20) UNSIGNED NOT NULL,
  `no_registo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `associated_name` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_activated` int(11) NOT NULL DEFAULT 0,
  `is_account_setup` int(11) NOT NULL DEFAULT 0,
  `is_user_type` enum('SUPERADMIN','ADVOCATE','STAFF') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ADVOCATE',
  `invitation_status` enum('accepted','sent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `accepted_at` datetime DEFAULT NULL,
  `current_package` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` int(11) NOT NULL DEFAULT 0,
  `started_at` date DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S',
  `is_expired` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `otp` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_date` timestamp NULL DEFAULT NULL,
  `is_otp_verify` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `plataforma` enum('App','Web') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `admin`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `agenda`
--

CREATE TABLE `agenda` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assunto` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('new','exists') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `telefone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacao` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` enum('OPEN','CANCEL BY CLIENT','CANCEL BY ADVOCA') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OPEN',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `agenda`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `areaprocessual`
--

CREATE TABLE `areaprocessual` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `designacao` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `areaprocessual`
--

INSERT INTO `areaprocessual` (`id`, `designacao`) VALUES
(1, 'Cível'),
(2, 'Comércio'),
(3, 'Criminal'),
(4, 'Família'),
(5, 'Trabalho');

-- --------------------------------------------------------

--
-- Estrutura da tabela `areaprocessual_intervdesignacao`
--

CREATE TABLE `areaprocessual_intervdesignacao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `areaprocessual_id` bigint(20) UNSIGNED NOT NULL,
  `intervdesignacao_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `areaprocessual_intervdesignacao`
--

INSERT INTO `areaprocessual_intervdesignacao` (`id`, `areaprocessual_id`, `intervdesignacao_id`) VALUES
(1, 1, 3),
(2, 1, 5),
(3, 1, 6),
(4, 1, 8),
(5, 1, 9),
(6, 3, 1),
(7, 3, 2),
(8, 3, 4),
(9, 3, 7),
(10, 3, 8),
(11, 1, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `areaprocessual_tipoprocesso`
--

CREATE TABLE `areaprocessual_tipoprocesso` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `areaprocessual_id` bigint(20) UNSIGNED NOT NULL,
  `tipoprocesso_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `areaprocessual_tipoprocesso`
--

INSERT INTO `areaprocessual_tipoprocesso` (`id`, `areaprocessual_id`, `tipoprocesso_id`) VALUES
(1, 3, 1),
(2, 3, 2),
(4, 3, 4),
(8, 1, 3),
(9, 3, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `bairro`
--

CREATE TABLE `bairro` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `bairro`
--

INSERT INTO `bairro` (`id`, `nome`) VALUES
(1, 'Cazenga'),
(2, 'Cuca'),
(3, 'Mabor');

-- --------------------------------------------------------

--
-- Estrutura da tabela `bairro_municipio`
--

CREATE TABLE `bairro_municipio` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bairro_id` bigint(20) UNSIGNED NOT NULL,
  `municipio_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `bairro_municipio`
--

INSERT INTO `bairro_municipio` (`id`, `bairro_id`, `municipio_id`) VALUES
(1, 1, 2324),
(2, 2, 2324),
(3, 3, 2324);

-- --------------------------------------------------------

--
-- Estrutura da tabela `case_transfer`
--

CREATE TABLE `case_transfer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advocate_id` bigint(20) UNSIGNED NOT NULL,
  `processo_id` bigint(20) UNSIGNED NOT NULL,
  `from_juiz` bigint(20) UNSIGNED NOT NULL,
  `to_juiz` bigint(20) UNSIGNED NOT NULL,
  `from_tribunal` bigint(20) UNSIGNED NOT NULL,
  `to_tribunal` bigint(20) UNSIGNED NOT NULL,
  `transfer_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `primeiro_nome` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome_meio` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sobrenome` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` bigint(20) UNSIGNED NOT NULL,
  `sexo` enum('M','F') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_civil` enum('C','D','S','V') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `nome_pai` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_mae` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternate_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais_id` bigint(20) UNSIGNED NOT NULL,
  `provincia_id` bigint(20) UNSIGNED NOT NULL,
  `municipio_id` bigint(20) UNSIGNED NOT NULL,
  `utilizador_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nome_referencia` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S',
  `client_type` enum('single','multiple') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'single',
  `foto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cliente`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente_parte`
--

CREATE TABLE `cliente_parte` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `party_firstname` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_middlename` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_lastname` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_mobile` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_advocate` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracao`
--

CREATE TABLE `configuracao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facebook` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtub` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linked_in` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco_id` bigint(20) UNSIGNED DEFAULT NULL,
  `about_us` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracao_factura`
--

CREATE TABLE `configuracao_factura` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `formato_factura` int(11) NOT NULL DEFAULT 1,
  `prefixo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_note` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `termos_condicoes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `factura_no` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `receipt_no` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `configuracao_factura`
--

INSERT INTO `configuracao_factura` (`id`, `formato_factura`, `prefixo`, `client_note`, `termos_condicoes`, `factura_no`, `receipt_no`) VALUES
(1, 1, 'INV-', NULL, '##test1\r\n##test2\r\n##test3', 13, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracao_geral`
--

CREATE TABLE `configuracao_geral` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome_escritorio` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pincode` int(10) UNSIGNED NOT NULL,
  `formato_data` int(10) UNSIGNED NOT NULL,
  `logo_img` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon_img` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `configuracao_geral`
--

INSERT INTO `configuracao_geral` (`id`, `nome_escritorio`, `endereco_id`, `pincode`, `formato_data`, `logo_img`, `favicon_img`, `timezone`, `created_at`, `updated_at`) VALUES
(1, 'BS Advogados', NULL, 0, 1, NULL, NULL, '8', NULL, '2021-05-06 09:27:38');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contacto`
--

CREATE TABLE `contacto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contacto` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `despesa`
--

CREATE TABLE `despesa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fornecedor_id` bigint(20) UNSIGNED NOT NULL,
  `factura_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_total_amount` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_amount` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_id` int(10) UNSIGNED DEFAULT NULL,
  `inv_status` enum('Due','Partially Paid','Paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Due',
  `due_date` date NOT NULL,
  `inv_date` date NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `json_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_created_by` int(10) UNSIGNED NOT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `dia_semana`
--

CREATE TABLE `dia_semana` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento`
--

CREATE TABLE `documento` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ndi` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` bigint(20) UNSIGNED NOT NULL,
  `anexo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pessoasingular_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `dumps`
--

CREATE TABLE `dumps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `encrypted` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `endereco`
--

CREATE TABLE `endereco` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'S/N',
  `rua` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `municipio_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `endereco`
--

INSERT INTO `endereco` (`id`, `numero`, `rua`, `bairro_id`, `municipio_id`) VALUES
(1, '', '', 1, 2324),
(2, '', '', 1, 2324),
(3, '', '', 1, 2324),
(4, '', '', 1, 2324);

-- --------------------------------------------------------

--
-- Estrutura da tabela `escalatrabalho_diasemana`
--

CREATE TABLE `escalatrabalho_diasemana` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `escala` bigint(20) UNSIGNED NOT NULL,
  `dia` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `escala_trabalho`
--

CREATE TABLE `escala_trabalho` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `funcionario` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `estadoprocesso`
--

CREATE TABLE `estadoprocesso` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estado` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `estadoprocesso`
--

INSERT INTO `estadoprocesso` (`id`, `estado`, `activo`) VALUES
(1, 'Em curso', 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `factura`
--

CREATE TABLE `factura` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `factura_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_total_amount` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_amount` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `inv_status` enum('Due','Partially Paid','Paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Due',
  `due_date` date NOT NULL,
  `inv_date` date NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` int(10) UNSIGNED DEFAULT NULL,
  `json_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_created_by` int(10) UNSIGNED NOT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S',
  `despesa_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_meio` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sobrenome` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('P','F') COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nif` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco_id` bigint(20) UNSIGNED NOT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `fornecedor`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `funcao`
--

CREATE TABLE `funcao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `funcao`
--

INSERT INTO `funcao` (`id`, `nome`, `descricao`, `activo`) VALUES
(1, 'SuperAdmin', NULL, ''),
(2, 'Estagiário', NULL, 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcao_permissao`
--

CREATE TABLE `funcao_permissao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `funcao_id` bigint(20) UNSIGNED NOT NULL,
  `permissao_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `funcao_permissao`
--

INSERT INTO `funcao_permissao` (`id`, `funcao_id`, `permissao_id`) VALUES
(63, 1, 1),
(64, 1, 2),
(65, 1, 3),
(66, 1, 4),
(67, 1, 5),
(68, 1, 6),
(69, 1, 7),
(70, 1, 8),
(71, 1, 9),
(72, 1, 10),
(73, 1, 11),
(74, 1, 12),
(75, 1, 13),
(76, 1, 14),
(77, 1, 15),
(78, 1, 16),
(79, 1, 17),
(80, 1, 18),
(81, 1, 19),
(82, 1, 20),
(83, 1, 21),
(84, 1, 22),
(85, 1, 23),
(86, 1, 24),
(87, 1, 25),
(88, 1, 26),
(89, 1, 27),
(90, 1, 59),
(91, 1, 60),
(92, 1, 61),
(93, 1, 62),
(94, 1, 28),
(95, 1, 29),
(96, 1, 30),
(97, 1, 31),
(98, 1, 32),
(99, 1, 33),
(100, 1, 34),
(101, 1, 35),
(102, 1, 36),
(103, 1, 37),
(104, 1, 38),
(105, 1, 39),
(106, 1, 40),
(107, 1, 41),
(108, 1, 42),
(109, 1, 43),
(110, 1, 44),
(111, 1, 45),
(112, 1, 46),
(113, 1, 47),
(114, 1, 48),
(115, 1, 49),
(116, 1, 50),
(117, 1, 51),
(118, 1, 52),
(119, 1, 53),
(120, 1, 54),
(121, 1, 55),
(122, 1, 56),
(123, 1, 57),
(124, 1, 58),
(132, 2, 1),
(133, 2, 6),
(134, 2, 14),
(135, 2, 16);

-- --------------------------------------------------------

--
-- Estrutura da tabela `fusohorario`
--

CREATE TABLE `fusohorario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `fusohorario`
--

INSERT INTO `fusohorario` (`id`, `nome`) VALUES
(8, 'Africa/Luanda'),
(5, 'America/Anguilla'),
(4, 'America/Antigua'),
(19, 'America/Argentina/Buenos_Aires'),
(20, 'America/Argentina/Cordoba'),
(22, 'America/Argentina/Jujuy'),
(21, 'America/Argentina/Salta'),
(23, 'America/Argentina/Tucuman'),
(10, 'Antarctica/Casey'),
(11, 'Antarctica/Davis'),
(12, 'Antarctica/DumontDUrville'),
(13, 'Antarctica/Mawson'),
(9, 'Antarctica/McMurdo'),
(14, 'Antarctica/Palmer'),
(15, 'Antarctica/Rothera'),
(16, 'Antarctica/Syowa'),
(17, 'Antarctica/Troll'),
(18, 'Antarctica/Vostok'),
(2, 'Asia/Dubai'),
(3, 'Asia/Kabul'),
(7, 'Asia/Yerevan'),
(1, 'Europe/Andorra'),
(6, 'Europe/Tirane');

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico_processo`
--

CREATE TABLE `historico_processo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advogado_id` bigint(20) UNSIGNED NOT NULL,
  `processo_id` bigint(20) UNSIGNED NOT NULL,
  `bussiness_on_date` datetime DEFAULT NULL,
  `hearing_date` datetime DEFAULT NULL,
  `is_transfer` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `transfer_juiz` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `historico_processo`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `imposto`
--

CREATE TABLE `imposto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `per` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `intervdesignacao`
--

CREATE TABLE `intervdesignacao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `designacao` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `intervdesignacao`
--

INSERT INTO `intervdesignacao` (`id`, `designacao`, `activo`) VALUES
(1, 'Arguido(a)', 'S'),
(2, 'Assistente', 'S'),
(3, 'Autor(a)', 'S'),
(4, 'Declarante', 'S'),
(5, 'Executado', 'S'),
(6, 'Exequente', 'S'),
(7, 'Ofendido(a)', 'S'),
(8, 'Ré(u)', 'S'),
(9, 'Requerente', 'S'),
(10, 'Requerido', 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `item_despesa`
--

CREATE TABLE `item_despesa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED DEFAULT NULL,
  `tax` double DEFAULT 0,
  `category_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iteam_qty` int(10) UNSIGNED NOT NULL,
  `item_amount` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_rate` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `despesa_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `item_factura`
--

CREATE TABLE `item_factura` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `factura_id` bigint(20) UNSIGNED NOT NULL,
  `imposto_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax` double DEFAULT 0,
  `item_descricao` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iteam_qty` int(10) UNSIGNED NOT NULL,
  `item_amount` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_rate` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `hsn` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `juiz`
--

CREATE TABLE `juiz` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S',
  `tribunal` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `juiz`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `mailsetups`
--

CREATE TABLE `mailsetups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mail_host` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_port` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_username` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_email` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_password` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_driver` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_encryption` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `mailsetups`
--

INSERT INTO `mailsetups` (`id`, `mail_host`, `mail_port`, `mail_username`, `mail_email`, `mail_password`, `mail_driver`, `mail_encryption`) VALUES
(1, '', '', '', '', '', 'smtp', 'tls');

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_pais_table', 1),
(2, '2014_10_12_100000_create_provincia_table', 1),
(3, '2019_10_14_110019_create_municipio_table', 1),
(4, '2019_10_14_110020_create_bairro_table', 1),
(5, '2019_10_17_101133_create_bairro_municipio_table', 1),
(6, '2019_10_17_101537_create_endereco_table', 1),
(7, '2019_10_17_101621_create_tipo_pessoa_table', 1),
(8, '2019_10_17_101723_create_pessoa_table', 1),
(9, '2019_10_17_101855_create_pessoa_singular_table', 1),
(10, '2019_10_17_101937_create_pessoa_colectiva_table', 1),
(11, '2019_10_17_102033_create_area_processual_table', 1),
(12, '2019_10_17_102153_create_tribunal_table', 1),
(13, '2019_10_17_121502_create_tribunal_areaprocessual_table', 1),
(14, '2019_10_17_131544_create_seccao_table', 1),
(15, '2019_10_17_131723_create_tribunal_areaprocessual_seccao_table', 1),
(16, '2019_10_17_132335_create_profissao_table', 1),
(17, '2019_10_18_050351_create_tipo_contacto_table', 1),
(18, '2019_10_18_050431_create_contacto_table', 1),
(19, '2019_10_18_050522_create_pessoa_contacto_table', 1),
(20, '2019_10_18_050550_create_tipo_documento_table', 1),
(21, '2019_10_18_050619_create_documento_table', 1),
(22, '2019_10_19_024113_create_funcao_table', 1),
(23, '2019_12_03_112534_create_permissao_table', 1),
(24, '2021_03_24_160839_create_funcao_permissao_table', 1),
(25, '2021_04_28_095651_create_utilizador_table', 1),
(26, '2021_04_28_095713_create_admin_table', 1),
(27, '2021_04_28_095736_create_cliente_table', 1),
(28, '2021_04_28_095818_create_password_resets_table', 1),
(29, '2021_04_28_095840_create_tarefa_table', 1),
(30, '2021_04_28_095919_create_fornecedor_table', 1),
(31, '2021_04_28_095942_create_agenda_table', 1),
(32, '2021_04_28_100003_create_juiz_table', 1),
(33, '2021_04_28_100037_create_configuracao_geral_table', 1),
(34, '2021_04_28_100136_create_servico_table', 1),
(35, '2021_04_28_100208_create_configuracao_factura_table', 1),
(36, '2021_04_28_100245_create_mailsetups_table', 1),
(37, '2021_04_28_100330_create_configuracao_table', 1),
(38, '2021_04_28_100358_create_estado_processo_table', 1),
(39, '2021_04_28_100427_create_tipo_processo_table', 1),
(40, '2021_04_28_100449_create_imposto_table', 1),
(41, '2021_04_28_100511_create_tipo_despesa_table', 1),
(42, '2021_04_28_100529_create_despesa_table', 1),
(43, '2021_04_28_100854_create_item_despesa_table', 1),
(44, '2021_04_28_100919_create_factura_table', 1),
(45, '2021_04_28_100938_create_item_factura_table', 1),
(46, '2021_04_28_101009_create_sql_backup_table', 1),
(47, '2021_04_28_101052_create_interv_designacao_table', 1),
(48, '2021_04_28_101150_create_areaprocessual_interv_designacao_table', 1),
(49, '2021_04_28_101215_create_processo_table', 1),
(50, '2021_04_28_101239_create_cliente_parte_table', 1),
(51, '2021_04_28_101307_create_tarefa_membro_table', 1),
(52, '2021_04_28_101351_create_payment_mades_table', 1),
(53, '2021_04_28_101421_create_historico_processo_table', 1),
(54, '2021_04_28_101508_create_processo_imposto_table', 1),
(55, '2021_04_28_101538_create_escala_trabalho_table', 1),
(56, '2021_04_28_101608_create_processo_membro_table', 1),
(57, '2021_04_28_101648_create_payment_receiveds_table', 1),
(58, '2021_04_28_101716_create_fuso_horario_table', 1),
(59, '2021_04_28_101806_create_areaprocessual_tipoprocesso_table', 1),
(60, '2021_04_28_101831_create_dia_semana_table', 1),
(61, '2021_04_28_101938_create_escala_trabalho_dia_semana_table', 1),
(62, '2021_04_28_102007_create_utilizador_funcao_table', 1),
(63, '2021_04_29_222827_create_dumps_table', 1),
(64, '2021_05_05_163328_create_parte_contraria_table', 2),
(65, '2021_05_07_094654_create_case_transfer_table', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `municipio`
--

CREATE TABLE `municipio` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provincia_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `municipio`
--

INSERT INTO `municipio` (`id`, `nome`, `provincia_id`) VALUES
(1, 'Bombuflat', 1),
(2, 'Garacharma', 1),
(3, 'Port Blair', 1),
(4, 'Rangat', 1),
(5, 'Addanki', 2),
(6, 'Adivivaram', 2),
(7, 'Adoni', 2),
(8, 'Aganampudi', 2),
(9, 'Ajjaram', 2),
(10, 'Akividu', 2),
(11, 'Akkarampalle', 2),
(12, 'Akkayapalle', 2),
(13, 'Akkireddipalem', 2),
(14, 'Alampur', 2),
(15, 'Amalapuram', 2),
(16, 'Amudalavalasa', 2),
(17, 'Amur', 2),
(18, 'Anakapalle', 2),
(19, 'Anantapur', 2),
(20, 'Andole', 2),
(21, 'Atmakur', 2),
(22, 'Attili', 2),
(23, 'Avanigadda', 2),
(24, 'Badepalli', 2),
(25, 'Badvel', 2),
(26, 'Balapur', 2),
(27, 'Bandarulanka', 2),
(28, 'Banganapalle', 2),
(29, 'Bapatla', 2),
(30, 'Bapulapadu', 2),
(31, 'Belampalli', 2),
(32, 'Bestavaripeta', 2),
(33, 'Betamcherla', 2),
(34, 'Bhattiprolu', 2),
(35, 'Bhimavaram', 2),
(36, 'Bhimunipatnam', 2),
(37, 'Bobbili', 2),
(38, 'Bombuflat', 2),
(39, 'Bommuru', 2),
(40, 'Bugganipalle', 2),
(41, 'Challapalle', 2),
(42, 'Chandur', 2),
(43, 'Chatakonda', 2),
(44, 'Chemmumiahpet', 2),
(45, 'Chidiga', 2),
(46, 'Chilakaluripet', 2),
(47, 'Chimakurthy', 2),
(48, 'Chinagadila', 2),
(49, 'Chinagantyada', 2),
(50, 'Chinnachawk', 2),
(51, 'Chintalavalasa', 2),
(52, 'Chipurupalle', 2),
(53, 'Chirala', 2),
(54, 'Chittoor', 2),
(55, 'Chodavaram', 2),
(56, 'Choutuppal', 2),
(57, 'Chunchupalle', 2),
(58, 'Cuddapah', 2),
(59, 'Cumbum', 2),
(60, 'Darnakal', 2),
(61, 'Dasnapur', 2),
(62, 'Dauleshwaram', 2),
(63, 'Dharmavaram', 2),
(64, 'Dhone', 2),
(65, 'Dommara Nandyal', 2),
(66, 'Dowlaiswaram', 2),
(67, 'East Godavari Dist.', 2),
(68, 'Eddumailaram', 2),
(69, 'Edulapuram', 2),
(70, 'Ekambara kuppam', 2),
(71, 'Eluru', 2),
(72, 'Enikapadu', 2),
(73, 'Fakirtakya', 2),
(74, 'Farrukhnagar', 2),
(75, 'Gaddiannaram', 2),
(76, 'Gajapathinagaram', 2),
(77, 'Gajularega', 2),
(78, 'Gajuvaka', 2),
(79, 'Gannavaram', 2),
(80, 'Garacharma', 2),
(81, 'Garimellapadu', 2),
(82, 'Giddalur', 2),
(83, 'Godavarikhani', 2),
(84, 'Gopalapatnam', 2),
(85, 'Gopalur', 2),
(86, 'Gorrekunta', 2),
(87, 'Gudivada', 2),
(88, 'Gudur', 2),
(89, 'Guntakal', 2),
(90, 'Guntur', 2),
(91, 'Guti', 2),
(92, 'Hindupur', 2),
(93, 'Hukumpeta', 2),
(94, 'Ichchapuram', 2),
(95, 'Isnapur', 2),
(96, 'Jaggayyapeta', 2),
(97, 'Jallaram Kamanpur', 2),
(98, 'Jammalamadugu', 2),
(99, 'Jangampalli', 2),
(100, 'Jarjapupeta', 2),
(101, 'Kadiri', 2),
(102, 'Kaikalur', 2),
(103, 'Kakinada', 2),
(104, 'Kallur', 2),
(105, 'Kalyandurg', 2),
(106, 'Kamalapuram', 2),
(107, 'Kamareddi', 2),
(108, 'Kanapaka', 2),
(109, 'Kanigiri', 2),
(110, 'Kanithi', 2),
(111, 'Kankipadu', 2),
(112, 'Kantabamsuguda', 2),
(113, 'Kanuru', 2),
(114, 'Karnul', 2),
(115, 'Katheru', 2),
(116, 'Kavali', 2),
(117, 'Kazipet', 2),
(118, 'Khanapuram Haveli', 2),
(119, 'Kodar', 2),
(120, 'Kollapur', 2),
(121, 'Kondapalem', 2),
(122, 'Kondapalle', 2),
(123, 'Kondukur', 2),
(124, 'Kosgi', 2),
(125, 'Kothavalasa', 2),
(126, 'Kottapalli', 2),
(127, 'Kovur', 2),
(128, 'Kovurpalle', 2),
(129, 'Kovvur', 2),
(130, 'Krishna', 2),
(131, 'Kuppam', 2),
(132, 'Kurmannapalem', 2),
(133, 'Kurnool', 2),
(134, 'Lakshettipet', 2),
(135, 'Lalbahadur Nagar', 2),
(136, 'Machavaram', 2),
(137, 'Macherla', 2),
(138, 'Machilipatnam', 2),
(139, 'Madanapalle', 2),
(140, 'Madaram', 2),
(141, 'Madhuravada', 2),
(142, 'Madikonda', 2),
(143, 'Madugule', 2),
(144, 'Mahabubnagar', 2),
(145, 'Mahbubabad', 2),
(146, 'Malkajgiri', 2),
(147, 'Mamilapalle', 2),
(148, 'Mancheral', 2),
(149, 'Mandapeta', 2),
(150, 'Mandasa', 2),
(151, 'Mangalagiri', 2),
(152, 'Manthani', 2),
(153, 'Markapur', 2),
(154, 'Marturu', 2),
(155, 'Metpalli', 2),
(156, 'Mindi', 2),
(157, 'Mirpet', 2),
(158, 'Moragudi', 2),
(159, 'Mothugudam', 2),
(160, 'Nagari', 2),
(161, 'Nagireddipalle', 2),
(162, 'Nandigama', 2),
(163, 'Nandikotkur', 2),
(164, 'Nandyal', 2),
(165, 'Narasannapeta', 2),
(166, 'Narasapur', 2),
(167, 'Narasaraopet', 2),
(168, 'Narayanavanam', 2),
(169, 'Narsapur', 2),
(170, 'Narsingi', 2),
(171, 'Narsipatnam', 2),
(172, 'Naspur', 2),
(173, 'Nathayyapalem', 2),
(174, 'Nayudupeta', 2),
(175, 'Nelimaria', 2),
(176, 'Nellore', 2),
(177, 'Nidadavole', 2),
(178, 'Nuzvid', 2),
(179, 'Omerkhan daira', 2),
(180, 'Ongole', 2),
(181, 'Osmania University', 2),
(182, 'Pakala', 2),
(183, 'Palakole', 2),
(184, 'Palakurthi', 2),
(185, 'Palasa', 2),
(186, 'Palempalle', 2),
(187, 'Palkonda', 2),
(188, 'Palmaner', 2),
(189, 'Pamur', 2),
(190, 'Panjim', 2),
(191, 'Papampeta', 2),
(192, 'Parasamba', 2),
(193, 'Parvatipuram', 2),
(194, 'Patancheru', 2),
(195, 'Payakaraopet', 2),
(196, 'Pedagantyada', 2),
(197, 'Pedana', 2),
(198, 'Peddapuram', 2),
(199, 'Pendurthi', 2),
(200, 'Penugonda', 2),
(201, 'Penukonda', 2),
(202, 'Phirangipuram', 2),
(203, 'Pithapuram', 2),
(204, 'Ponnur', 2),
(205, 'Port Blair', 2),
(206, 'Pothinamallayyapalem', 2),
(207, 'Prakasam', 2),
(208, 'Prasadampadu', 2),
(209, 'Prasantinilayam', 2),
(210, 'Proddatur', 2),
(211, 'Pulivendla', 2),
(212, 'Punganuru', 2),
(213, 'Puttur', 2),
(214, 'Qutubullapur', 2),
(215, 'Rajahmundry', 2),
(216, 'Rajamahendri', 2),
(217, 'Rajampet', 2),
(218, 'Rajendranagar', 2),
(219, 'Rajoli', 2),
(220, 'Ramachandrapuram', 2),
(221, 'Ramanayyapeta', 2),
(222, 'Ramapuram', 2),
(223, 'Ramarajupalli', 2),
(224, 'Ramavarappadu', 2),
(225, 'Rameswaram', 2),
(226, 'Rampachodavaram', 2),
(227, 'Ravulapalam', 2),
(228, 'Rayachoti', 2),
(229, 'Rayadrug', 2),
(230, 'Razam', 2),
(231, 'Razole', 2),
(232, 'Renigunta', 2),
(233, 'Repalle', 2),
(234, 'Rishikonda', 2),
(235, 'Salur', 2),
(236, 'Samalkot', 2),
(237, 'Sattenapalle', 2),
(238, 'Seetharampuram', 2),
(239, 'Serilungampalle', 2),
(240, 'Shankarampet', 2),
(241, 'Shar', 2),
(242, 'Singarayakonda', 2),
(243, 'Sirpur', 2),
(244, 'Sirsilla', 2),
(245, 'Sompeta', 2),
(246, 'Sriharikota', 2),
(247, 'Srikakulam', 2),
(248, 'Srikalahasti', 2),
(249, 'Sriramnagar', 2),
(250, 'Sriramsagar', 2),
(251, 'Srisailam', 2),
(252, 'Srisailamgudem Devasthanam', 2),
(253, 'Sulurpeta', 2),
(254, 'Suriapet', 2),
(255, 'Suryaraopet', 2),
(256, 'Tadepalle', 2),
(257, 'Tadepalligudem', 2),
(258, 'Tadpatri', 2),
(259, 'Tallapalle', 2),
(260, 'Tanuku', 2),
(261, 'Tekkali', 2),
(262, 'Tenali', 2),
(263, 'Tigalapahad', 2),
(264, 'Tiruchanur', 2),
(265, 'Tirumala', 2),
(266, 'Tirupati', 2),
(267, 'Tirvuru', 2),
(268, 'Trimulgherry', 2),
(269, 'Tuni', 2),
(270, 'Turangi', 2),
(271, 'Ukkayapalli', 2),
(272, 'Ukkunagaram', 2),
(273, 'Uppal Kalan', 2),
(274, 'Upper Sileru', 2),
(275, 'Uravakonda', 2),
(276, 'Vadlapudi', 2),
(277, 'Vaparala', 2),
(278, 'Vemalwada', 2),
(279, 'Venkatagiri', 2),
(280, 'Venkatapuram', 2),
(281, 'Vepagunta', 2),
(282, 'Vetapalem', 2),
(283, 'Vijayapuri', 2),
(284, 'Vijayapuri South', 2),
(285, 'Vijayawada', 2),
(286, 'Vinukonda', 2),
(287, 'Visakhapatnam', 2),
(288, 'Vizianagaram', 2),
(289, 'Vuyyuru', 2),
(290, 'Wanparti', 2),
(291, 'West Godavari Dist.', 2),
(292, 'Yadagirigutta', 2),
(293, 'Yarada', 2),
(294, 'Yellamanchili', 2),
(295, 'Yemmiganur', 2),
(296, 'Yenamalakudru', 2),
(297, 'Yendada', 2),
(298, 'Yerraguntla', 2),
(299, 'Along', 3),
(300, 'Basar', 3),
(301, 'Bondila', 3),
(302, 'Changlang', 3),
(303, 'Daporijo', 3),
(304, 'Deomali', 3),
(305, 'Itanagar', 3),
(306, 'Jairampur', 3),
(307, 'Khonsa', 3),
(308, 'Naharlagun', 3),
(309, 'Namsai', 3),
(310, 'Pasighat', 3),
(311, 'Roing', 3),
(312, 'Seppa', 3),
(313, 'Tawang', 3),
(314, 'Tezu', 3),
(315, 'Ziro', 3),
(316, 'Abhayapuri', 4),
(317, 'Ambikapur', 4),
(318, 'Amguri', 4),
(319, 'Anand Nagar', 4),
(320, 'Badarpur', 4),
(321, 'Badarpur Railway Town', 4),
(322, 'Bahbari Gaon', 4),
(323, 'Bamun Sualkuchi', 4),
(324, 'Barbari', 4),
(325, 'Barpathar', 4),
(326, 'Barpeta', 4),
(327, 'Barpeta Road', 4),
(328, 'Basugaon', 4),
(329, 'Bihpuria', 4),
(330, 'Bijni', 4),
(331, 'Bilasipara', 4),
(332, 'Biswanath Chariali', 4),
(333, 'Bohori', 4),
(334, 'Bokajan', 4),
(335, 'Bokokhat', 4),
(336, 'Bongaigaon', 4),
(337, 'Bongaigaon Petro-chemical Town', 4),
(338, 'Borgolai', 4),
(339, 'Chabua', 4),
(340, 'Chandrapur Bagicha', 4),
(341, 'Chapar', 4),
(342, 'Chekonidhara', 4),
(343, 'Choto Haibor', 4),
(344, 'Dergaon', 4),
(345, 'Dharapur', 4),
(346, 'Dhekiajuli', 4),
(347, 'Dhemaji', 4),
(348, 'Dhing', 4),
(349, 'Dhubri', 4),
(350, 'Dhuburi', 4),
(351, 'Dibrugarh', 4),
(352, 'Digboi', 4),
(353, 'Digboi Oil Town', 4),
(354, 'Dimaruguri', 4),
(355, 'Diphu', 4),
(356, 'Dispur', 4),
(357, 'Doboka', 4),
(358, 'Dokmoka', 4),
(359, 'Donkamokan', 4),
(360, 'Duliagaon', 4),
(361, 'Duliajan', 4),
(362, 'Duliajan No.1', 4),
(363, 'Dum Duma', 4),
(364, 'Durga Nagar', 4),
(365, 'Gauripur', 4),
(366, 'Goalpara', 4),
(367, 'Gohpur', 4),
(368, 'Golaghat', 4),
(369, 'Golakganj', 4),
(370, 'Gossaigaon', 4),
(371, 'Guwahati', 4),
(372, 'Haflong', 4),
(373, 'Hailakandi', 4),
(374, 'Hamren', 4),
(375, 'Hauli', 4),
(376, 'Hauraghat', 4),
(377, 'Hojai', 4),
(378, 'Jagiroad', 4),
(379, 'Jagiroad Paper Mill', 4),
(380, 'Jogighopa', 4),
(381, 'Jonai Bazar', 4),
(382, 'Jorhat', 4),
(383, 'Kampur Town', 4),
(384, 'Kamrup', 4),
(385, 'Kanakpur', 4),
(386, 'Karimganj', 4),
(387, 'Kharijapikon', 4),
(388, 'Kharupetia', 4),
(389, 'Kochpara', 4),
(390, 'Kokrajhar', 4),
(391, 'Kumar Kaibarta Gaon', 4),
(392, 'Lakhimpur', 4),
(393, 'Lakhipur', 4),
(394, 'Lala', 4),
(395, 'Lanka', 4),
(396, 'Lido Tikok', 4),
(397, 'Lido Town', 4),
(398, 'Lumding', 4),
(399, 'Lumding Railway Colony', 4),
(400, 'Mahur', 4),
(401, 'Maibong', 4),
(402, 'Majgaon', 4),
(403, 'Makum', 4),
(404, 'Mangaldai', 4),
(405, 'Mankachar', 4),
(406, 'Margherita', 4),
(407, 'Mariani', 4),
(408, 'Marigaon', 4),
(409, 'Moran', 4),
(410, 'Moranhat', 4),
(411, 'Nagaon', 4),
(412, 'Naharkatia', 4),
(413, 'Nalbari', 4),
(414, 'Namrup', 4),
(415, 'Naubaisa Gaon', 4),
(416, 'Nazira', 4),
(417, 'New Bongaigaon Railway Colony', 4),
(418, 'Niz-Hajo', 4),
(419, 'North Guwahati', 4),
(420, 'Numaligarh', 4),
(421, 'Palasbari', 4),
(422, 'Panchgram', 4),
(423, 'Pathsala', 4),
(424, 'Raha', 4),
(425, 'Rangapara', 4),
(426, 'Rangia', 4),
(427, 'Salakati', 4),
(428, 'Sapatgram', 4),
(429, 'Sarthebari', 4),
(430, 'Sarupathar', 4),
(431, 'Sarupathar Bengali', 4),
(432, 'Senchoagaon', 4),
(433, 'Sibsagar', 4),
(434, 'Silapathar', 4),
(435, 'Silchar', 4),
(436, 'Silchar Part-X', 4),
(437, 'Sonari', 4),
(438, 'Sorbhog', 4),
(439, 'Sualkuchi', 4),
(440, 'Tangla', 4),
(441, 'Tezpur', 4),
(442, 'Tihu', 4),
(443, 'Tinsukia', 4),
(444, 'Titabor', 4),
(445, 'Udalguri', 4),
(446, 'Umrangso', 4),
(447, 'Uttar Krishnapur Part-I', 4),
(448, 'Amarpur', 5),
(449, 'Ara', 5),
(450, 'Araria', 5),
(451, 'Areraj', 5),
(452, 'Asarganj', 5),
(453, 'Aurangabad', 5),
(454, 'Bagaha', 5),
(455, 'Bahadurganj', 5),
(456, 'Bairgania', 5),
(457, 'Bakhtiyarpur', 5),
(458, 'Banka', 5),
(459, 'Banmankhi', 5),
(460, 'Bar Bigha', 5),
(461, 'Barauli', 5),
(462, 'Barauni Oil Township', 5),
(463, 'Barh', 5),
(464, 'Barhiya', 5),
(465, 'Bariapur', 5),
(466, 'Baruni', 5),
(467, 'Begusarai', 5),
(468, 'Behea', 5),
(469, 'Belsand', 5),
(470, 'Bettiah', 5),
(471, 'Bhabua', 5),
(472, 'Bhagalpur', 5),
(473, 'Bhimnagar', 5),
(474, 'Bhojpur', 5),
(475, 'Bihar', 5),
(476, 'Bihar Sharif', 5),
(477, 'Bihariganj', 5),
(478, 'Bikramganj', 5),
(479, 'Birpur', 5),
(480, 'Bodh Gaya', 5),
(481, 'Buxar', 5),
(482, 'Chakia', 5),
(483, 'Chanpatia', 5),
(484, 'Chhapra', 5),
(485, 'Chhatapur', 5),
(486, 'Colgong', 5),
(487, 'Dalsingh Sarai', 5),
(488, 'Darbhanga', 5),
(489, 'Daudnagar', 5),
(490, 'Dehri', 5),
(491, 'Dhaka', 5),
(492, 'Dighwara', 5),
(493, 'Dinapur', 5),
(494, 'Dinapur Cantonment', 5),
(495, 'Dumra', 5),
(496, 'Dumraon', 5),
(497, 'Fatwa', 5),
(498, 'Forbesganj', 5),
(499, 'Gaya', 5),
(500, 'Gazipur', 5),
(501, 'Ghoghardiha', 5),
(502, 'Gogri Jamalpur', 5),
(503, 'Gopalganj', 5),
(504, 'Habibpur', 5),
(505, 'Hajipur', 5),
(506, 'Hasanpur', 5),
(507, 'Hazaribagh', 5),
(508, 'Hilsa', 5),
(509, 'Hisua', 5),
(510, 'Islampur', 5),
(511, 'Jagdispur', 5),
(512, 'Jahanabad', 5),
(513, 'Jamalpur', 5),
(514, 'Jamhaur', 5),
(515, 'Jamui', 5),
(516, 'Janakpur Road', 5),
(517, 'Janpur', 5),
(518, 'Jaynagar', 5),
(519, 'Jha Jha', 5),
(520, 'Jhanjharpur', 5),
(521, 'Jogbani', 5),
(522, 'Kanti', 5),
(523, 'Kasba', 5),
(524, 'Kataiya', 5),
(525, 'Katihar', 5),
(526, 'Khagaria', 5),
(527, 'Khagaul', 5),
(528, 'Kharagpur', 5),
(529, 'Khusrupur', 5),
(530, 'Kishanganj', 5),
(531, 'Koath', 5),
(532, 'Koilwar', 5),
(533, 'Lakhisarai', 5),
(534, 'Lalganj', 5),
(535, 'Lauthaha', 5),
(536, 'Madhepura', 5),
(537, 'Madhubani', 5),
(538, 'Maharajganj', 5),
(539, 'Mahnar Bazar', 5),
(540, 'Mairwa', 5),
(541, 'Makhdumpur', 5),
(542, 'Maner', 5),
(543, 'Manihari', 5),
(544, 'Marhaura', 5),
(545, 'Masaurhi', 5),
(546, 'Mirganj', 5),
(547, 'Mohiuddinagar', 5),
(548, 'Mokama', 5),
(549, 'Motihari', 5),
(550, 'Motipur', 5),
(551, 'Munger', 5),
(552, 'Murliganj', 5),
(553, 'Muzaffarpur', 5),
(554, 'Nabinagar', 5),
(555, 'Narkatiaganj', 5),
(556, 'Nasriganj', 5),
(557, 'Natwar', 5),
(558, 'Naugachhia', 5),
(559, 'Nawada', 5),
(560, 'Nirmali', 5),
(561, 'Nokha', 5),
(562, 'Paharpur', 5),
(563, 'Patna', 5),
(564, 'Phulwari', 5),
(565, 'Piro', 5),
(566, 'Purnia', 5),
(567, 'Pusa', 5),
(568, 'Rafiganj', 5),
(569, 'Raghunathpur', 5),
(570, 'Rajgir', 5),
(571, 'Ramnagar', 5),
(572, 'Raxaul', 5),
(573, 'Revelganj', 5),
(574, 'Rusera', 5),
(575, 'Sagauli', 5),
(576, 'Saharsa', 5),
(577, 'Samastipur', 5),
(578, 'Sasaram', 5),
(579, 'Shahpur', 5),
(580, 'Shaikhpura', 5),
(581, 'Sherghati', 5),
(582, 'Shivhar', 5),
(583, 'Silao', 5),
(584, 'Sitamarhi', 5),
(585, 'Siwan', 5),
(586, 'Sonepur', 5),
(587, 'Sultanganj', 5),
(588, 'Supaul', 5),
(589, 'Teghra', 5),
(590, 'Tekari', 5),
(591, 'Thakurganj', 5),
(592, 'Vaishali', 5),
(593, 'Waris Aliganj', 5),
(594, 'Chandigarh', 6),
(595, 'Ahiwara', 7),
(596, 'Akaltara', 7),
(597, 'Ambagarh Chauki', 7),
(598, 'Ambikapur', 7),
(599, 'Arang', 7),
(600, 'Bade Bacheli', 7),
(601, 'Bagbahara', 7),
(602, 'Baikunthpur', 7),
(603, 'Balod', 7),
(604, 'Baloda', 7),
(605, 'Baloda Bazar', 7),
(606, 'Banarsi', 7),
(607, 'Basna', 7),
(608, 'Bemetra', 7),
(609, 'Bhanpuri', 7),
(610, 'Bhatapara', 7),
(611, 'Bhatgaon', 7),
(612, 'Bhilai', 7),
(613, 'Bilaspur', 7),
(614, 'Bilha', 7),
(615, 'Birgaon', 7),
(616, 'Bodri', 7),
(617, 'Champa', 7),
(618, 'Charcha', 7),
(619, 'Charoda', 7),
(620, 'Chhuikhadan', 7),
(621, 'Chirmiri', 7),
(622, 'Dantewada', 7),
(623, 'Deori', 7),
(624, 'Dhamdha', 7),
(625, 'Dhamtari', 7),
(626, 'Dharamjaigarh', 7),
(627, 'Dipka', 7),
(628, 'Doman Hill Colliery', 7),
(629, 'Dongargaon', 7),
(630, 'Dongragarh', 7),
(631, 'Durg', 7),
(632, 'Frezarpur', 7),
(633, 'Gandai', 7),
(634, 'Gariaband', 7),
(635, 'Gaurela', 7),
(636, 'Gelhapani', 7),
(637, 'Gharghoda', 7),
(638, 'Gidam', 7),
(639, 'Gobra Nawapara', 7),
(640, 'Gogaon', 7),
(641, 'Hatkachora', 7),
(642, 'Jagdalpur', 7),
(643, 'Jamui', 7),
(644, 'Jashpurnagar', 7),
(645, 'Jhagrakhand', 7),
(646, 'Kanker', 7),
(647, 'Katghora', 7),
(648, 'Kawardha', 7),
(649, 'Khairagarh', 7),
(650, 'Khamhria', 7),
(651, 'Kharod', 7),
(652, 'Kharsia', 7),
(653, 'Khonga Pani', 7),
(654, 'Kirandu', 7),
(655, 'Kirandul', 7),
(656, 'Kohka', 7),
(657, 'Kondagaon', 7),
(658, 'Korba', 7),
(659, 'Korea', 7),
(660, 'Koria Block', 7),
(661, 'Kota', 7),
(662, 'Kumhari', 7),
(663, 'Kumud Katta', 7),
(664, 'Kurasia', 7),
(665, 'Kurud', 7),
(666, 'Lingiyadih', 7),
(667, 'Lormi', 7),
(668, 'Mahasamund', 7),
(669, 'Mahendragarh', 7),
(670, 'Mehmand', 7),
(671, 'Mongra', 7),
(672, 'Mowa', 7),
(673, 'Mungeli', 7),
(674, 'Nailajanjgir', 7),
(675, 'Namna Kalan', 7),
(676, 'Naya Baradwar', 7),
(677, 'Pandariya', 7),
(678, 'Patan', 7),
(679, 'Pathalgaon', 7),
(680, 'Pendra', 7),
(681, 'Phunderdihari', 7),
(682, 'Pithora', 7),
(683, 'Raigarh', 7),
(684, 'Raipur', 7),
(685, 'Rajgamar', 7),
(686, 'Rajhara', 7),
(687, 'Rajnandgaon', 7),
(688, 'Ramanuj Ganj', 7),
(689, 'Ratanpur', 7),
(690, 'Sakti', 7),
(691, 'Saraipali', 7),
(692, 'Sarajpur', 7),
(693, 'Sarangarh', 7),
(694, 'Shivrinarayan', 7),
(695, 'Simga', 7),
(696, 'Sirgiti', 7),
(697, 'Takhatpur', 7),
(698, 'Telgaon', 7),
(699, 'Tildanewra', 7),
(700, 'Urla', 7),
(701, 'Vishrampur', 7),
(702, 'Amli', 8),
(703, 'Silvassa', 8),
(704, 'Daman', 9),
(705, 'Diu', 9),
(706, 'Delhi', 10),
(707, 'New Delhi', 10),
(708, 'Aldona', 11),
(709, 'Altinho', 11),
(710, 'Aquem', 11),
(711, 'Arpora', 11),
(712, 'Bambolim', 11),
(713, 'Bandora', 11),
(714, 'Bardez', 11),
(715, 'Benaulim', 11),
(716, 'Betora', 11),
(717, 'Bicholim', 11),
(718, 'Calapor', 11),
(719, 'Candolim', 11),
(720, 'Caranzalem', 11),
(721, 'Carapur', 11),
(722, 'Chicalim', 11),
(723, 'Chimbel', 11),
(724, 'Chinchinim', 11),
(725, 'Colvale', 11),
(726, 'Corlim', 11),
(727, 'Cortalim', 11),
(728, 'Cuncolim', 11),
(729, 'Curchorem', 11),
(730, 'Curti', 11),
(731, 'Davorlim', 11),
(732, 'Dona Paula', 11),
(733, 'Goa', 11),
(734, 'Guirim', 11),
(735, 'Jua', 11),
(736, 'Kalangat', 11),
(737, 'Kankon', 11),
(738, 'Kundaim', 11),
(739, 'Loutulim', 11),
(740, 'Madgaon', 11),
(741, 'Mapusa', 11),
(742, 'Margao', 11),
(743, 'Margaon', 11),
(744, 'Miramar', 11),
(745, 'Morjim', 11),
(746, 'Mormugao', 11),
(747, 'Navelim', 11),
(748, 'Pale', 11),
(749, 'Panaji', 11),
(750, 'Parcem', 11),
(751, 'Parra', 11),
(752, 'Penha de Franca', 11),
(753, 'Pernem', 11),
(754, 'Pilerne', 11),
(755, 'Pissurlem', 11),
(756, 'Ponda', 11),
(757, 'Porvorim', 11),
(758, 'Quepem', 11),
(759, 'Queula', 11),
(760, 'Raia', 11),
(761, 'Reis Magos', 11),
(762, 'Salcette', 11),
(763, 'Saligao', 11),
(764, 'Sancoale', 11),
(765, 'Sanguem', 11),
(766, 'Sanquelim', 11),
(767, 'Sanvordem', 11),
(768, 'Sao Jose-de-Areal', 11),
(769, 'Sattari', 11),
(770, 'Serula', 11),
(771, 'Sinquerim', 11),
(772, 'Siolim', 11),
(773, 'Taleigao', 11),
(774, 'Tivim', 11),
(775, 'Valpoi', 11),
(776, 'Varca', 11),
(777, 'Vasco', 11),
(778, 'Verna', 11),
(779, 'Abrama', 12),
(780, 'Adalaj', 12),
(781, 'Adityana', 12),
(782, 'Advana', 12),
(783, 'Ahmedabad', 12),
(784, 'Ahwa', 12),
(785, 'Alang', 12),
(786, 'Ambaji', 12),
(787, 'Ambaliyasan', 12),
(788, 'Amod', 12),
(789, 'Amreli', 12),
(790, 'Amroli', 12),
(791, 'Anand', 12),
(792, 'Andada', 12),
(793, 'Anjar', 12),
(794, 'Anklav', 12),
(795, 'Ankleshwar', 12),
(796, 'Anklesvar INA', 12),
(797, 'Antaliya', 12),
(798, 'Arambhada', 12),
(799, 'Asarma', 12),
(800, 'Atul', 12),
(801, 'Babra', 12),
(802, 'Bag-e-Firdosh', 12),
(803, 'Bagasara', 12),
(804, 'Bahadarpar', 12),
(805, 'Bajipura', 12),
(806, 'Bajva', 12),
(807, 'Balasinor', 12),
(808, 'Banaskantha', 12),
(809, 'Bansda', 12),
(810, 'Bantva', 12),
(811, 'Bardoli', 12),
(812, 'Barwala', 12),
(813, 'Bayad', 12),
(814, 'Bechar', 12),
(815, 'Bedi', 12),
(816, 'Beyt', 12),
(817, 'Bhachau', 12),
(818, 'Bhanvad', 12),
(819, 'Bharuch', 12),
(820, 'Bharuch INA', 12),
(821, 'Bhavnagar', 12),
(822, 'Bhayavadar', 12),
(823, 'Bhestan', 12),
(824, 'Bhuj', 12),
(825, 'Bilimora', 12),
(826, 'Bilkha', 12),
(827, 'Billimora', 12),
(828, 'Bodakdev', 12),
(829, 'Bodeli', 12),
(830, 'Bopal', 12),
(831, 'Boria', 12),
(832, 'Boriavi', 12),
(833, 'Borsad', 12),
(834, 'Botad', 12),
(835, 'Cambay', 12),
(836, 'Chaklasi', 12),
(837, 'Chala', 12),
(838, 'Chalala', 12),
(839, 'Chalthan', 12),
(840, 'Chanasma', 12),
(841, 'Chandisar', 12),
(842, 'Chandkheda', 12),
(843, 'Chanod', 12),
(844, 'Chaya', 12),
(845, 'Chenpur', 12),
(846, 'Chhapi', 12),
(847, 'Chhaprabhatha', 12),
(848, 'Chhatral', 12),
(849, 'Chhota Udepur', 12),
(850, 'Chikhli', 12),
(851, 'Chiloda', 12),
(852, 'Chorvad', 12),
(853, 'Chotila', 12),
(854, 'Dabhoi', 12),
(855, 'Dadara', 12),
(856, 'Dahod', 12),
(857, 'Dakor', 12),
(858, 'Damnagar', 12),
(859, 'Deesa', 12),
(860, 'Delvada', 12),
(861, 'Devgadh Baria', 12),
(862, 'Devsar', 12),
(863, 'Dhandhuka', 12),
(864, 'Dhanera', 12),
(865, 'Dhangdhra', 12),
(866, 'Dhansura', 12),
(867, 'Dharampur', 12),
(868, 'Dhari', 12),
(869, 'Dhola', 12),
(870, 'Dholka', 12),
(871, 'Dholka Rural', 12),
(872, 'Dhoraji', 12),
(873, 'Dhrangadhra', 12),
(874, 'Dhrol', 12),
(875, 'Dhuva', 12),
(876, 'Dhuwaran', 12),
(877, 'Digvijaygram', 12),
(878, 'Disa', 12),
(879, 'Dungar', 12),
(880, 'Dungarpur', 12),
(881, 'Dungra', 12),
(882, 'Dwarka', 12),
(883, 'Flelanganj', 12),
(884, 'GSFC Complex', 12),
(885, 'Gadhda', 12),
(886, 'Gandevi', 12),
(887, 'Gandhidham', 12),
(888, 'Gandhinagar', 12),
(889, 'Gariadhar', 12),
(890, 'Ghogha', 12),
(891, 'Godhra', 12),
(892, 'Gondal', 12),
(893, 'Hajira INA', 12),
(894, 'Halol', 12),
(895, 'Halvad', 12),
(896, 'Hansot', 12),
(897, 'Harij', 12),
(898, 'Himatnagar', 12),
(899, 'Ichchhapor', 12),
(900, 'Idar', 12),
(901, 'Jafrabad', 12),
(902, 'Jalalpore', 12),
(903, 'Jambusar', 12),
(904, 'Jamjodhpur', 12),
(905, 'Jamnagar', 12),
(906, 'Jasdan', 12),
(907, 'Jawaharnagar', 12),
(908, 'Jetalsar', 12),
(909, 'Jetpur', 12),
(910, 'Jodiya', 12),
(911, 'Joshipura', 12),
(912, 'Junagadh', 12),
(913, 'Kadi', 12),
(914, 'Kadodara', 12),
(915, 'Kalavad', 12),
(916, 'Kali', 12),
(917, 'Kaliawadi', 12),
(918, 'Kalol', 12),
(919, 'Kalol INA', 12),
(920, 'Kandla', 12),
(921, 'Kanjari', 12),
(922, 'Kanodar', 12),
(923, 'Kapadwanj', 12),
(924, 'Karachiya', 12),
(925, 'Karamsad', 12),
(926, 'Karjan', 12),
(927, 'Kathial', 12),
(928, 'Kathor', 12),
(929, 'Katpar', 12),
(930, 'Kavant', 12),
(931, 'Keshod', 12),
(932, 'Kevadiya', 12),
(933, 'Khambhaliya', 12),
(934, 'Khambhat', 12),
(935, 'Kharaghoda', 12),
(936, 'Khed Brahma', 12),
(937, 'Kheda', 12),
(938, 'Kheralu', 12),
(939, 'Kodinar', 12),
(940, 'Kosamba', 12),
(941, 'Kundla', 12),
(942, 'Kutch', 12),
(943, 'Kutiyana', 12),
(944, 'Lakhtar', 12),
(945, 'Lalpur', 12),
(946, 'Lambha', 12),
(947, 'Lathi', 12),
(948, 'Limbdi', 12),
(949, 'Limla', 12),
(950, 'Lunavada', 12),
(951, 'Madhapar', 12),
(952, 'Maflipur', 12),
(953, 'Mahemdavad', 12),
(954, 'Mahudha', 12),
(955, 'Mahuva', 12),
(956, 'Mahuvar', 12),
(957, 'Makarba', 12),
(958, 'Makarpura', 12),
(959, 'Makassar', 12),
(960, 'Maktampur', 12),
(961, 'Malia', 12),
(962, 'Malpur', 12),
(963, 'Manavadar', 12),
(964, 'Mandal', 12),
(965, 'Mandvi', 12),
(966, 'Mangrol', 12),
(967, 'Mansa', 12),
(968, 'Meghraj', 12),
(969, 'Mehsana', 12),
(970, 'Mendarla', 12),
(971, 'Mithapur', 12),
(972, 'Modasa', 12),
(973, 'Mogravadi', 12),
(974, 'Morbi', 12),
(975, 'Morvi', 12),
(976, 'Mundra', 12),
(977, 'Nadiad', 12),
(978, 'Naliya', 12),
(979, 'Nanakvada', 12),
(980, 'Nandej', 12),
(981, 'Nandesari', 12),
(982, 'Nandesari INA', 12),
(983, 'Naroda', 12),
(984, 'Navagadh', 12),
(985, 'Navagam Ghed', 12),
(986, 'Navsari', 12),
(987, 'Ode', 12),
(988, 'Okaf', 12),
(989, 'Okha', 12),
(990, 'Olpad', 12),
(991, 'Paddhari', 12),
(992, 'Padra', 12),
(993, 'Palanpur', 12),
(994, 'Palej', 12),
(995, 'Pali', 12),
(996, 'Palitana', 12),
(997, 'Paliyad', 12),
(998, 'Pandesara', 12),
(999, 'Panoli', 12),
(1000, 'Pardi', 12),
(1001, 'Parnera', 12),
(1002, 'Parvat', 12),
(1003, 'Patan', 12),
(1004, 'Patdi', 12),
(1005, 'Petlad', 12),
(1006, 'Petrochemical Complex', 12),
(1007, 'Porbandar', 12),
(1008, 'Prantij', 12),
(1009, 'Radhanpur', 12),
(1010, 'Raiya', 12),
(1011, 'Rajkot', 12),
(1012, 'Rajpipla', 12),
(1013, 'Rajula', 12),
(1014, 'Ramod', 12),
(1015, 'Ranavav', 12),
(1016, 'Ranoli', 12),
(1017, 'Rapar', 12),
(1018, 'Sahij', 12),
(1019, 'Salaya', 12),
(1020, 'Sanand', 12),
(1021, 'Sankheda', 12),
(1022, 'Santrampur', 12),
(1023, 'Saribujrang', 12),
(1024, 'Sarigam INA', 12),
(1025, 'Sayan', 12),
(1026, 'Sayla', 12),
(1027, 'Shahpur', 12),
(1028, 'Shahwadi', 12),
(1029, 'Shapar', 12),
(1030, 'Shivrajpur', 12),
(1031, 'Siddhapur', 12),
(1032, 'Sidhpur', 12),
(1033, 'Sihor', 12),
(1034, 'Sika', 12),
(1035, 'Singarva', 12),
(1036, 'Sinor', 12),
(1037, 'Sojitra', 12),
(1038, 'Sola', 12),
(1039, 'Songadh', 12),
(1040, 'Suraj Karadi', 12),
(1041, 'Surat', 12),
(1042, 'Surendranagar', 12),
(1043, 'Talaja', 12),
(1044, 'Talala', 12),
(1045, 'Talod', 12),
(1046, 'Tankara', 12),
(1047, 'Tarsali', 12),
(1048, 'Thangadh', 12),
(1049, 'Tharad', 12),
(1050, 'Thasra', 12),
(1051, 'Udyognagar', 12),
(1052, 'Ukai', 12),
(1053, 'Umbergaon', 12),
(1054, 'Umbergaon INA', 12),
(1055, 'Umrala', 12),
(1056, 'Umreth', 12),
(1057, 'Un', 12),
(1058, 'Una', 12),
(1059, 'Unjha', 12),
(1060, 'Upleta', 12),
(1061, 'Utran', 12),
(1062, 'Uttarsanda', 12),
(1063, 'V.U. Nagar', 12),
(1064, 'V.V. Nagar', 12),
(1065, 'Vadia', 12),
(1066, 'Vadla', 12),
(1067, 'Vadnagar', 12),
(1068, 'Vadodara', 12),
(1069, 'Vaghodia INA', 12),
(1070, 'Valbhipur', 12),
(1071, 'Vallabh Vidyanagar', 12),
(1072, 'Valsad', 12),
(1073, 'Valsad INA', 12),
(1074, 'Vanthali', 12),
(1075, 'Vapi', 12),
(1076, 'Vapi INA', 12),
(1077, 'Vartej', 12),
(1078, 'Vasad', 12),
(1079, 'Vasna Borsad INA', 12),
(1080, 'Vaso', 12),
(1081, 'Veraval', 12),
(1082, 'Vidyanagar', 12),
(1083, 'Vijalpor', 12),
(1084, 'Vijapur', 12),
(1085, 'Vinchhiya', 12),
(1086, 'Vinzol', 12),
(1087, 'Virpur', 12),
(1088, 'Visavadar', 12),
(1089, 'Visnagar', 12),
(1090, 'Vyara', 12),
(1091, 'Wadhwan', 12),
(1092, 'Waghai', 12),
(1093, 'Waghodia', 12),
(1094, 'Wankaner', 12),
(1095, 'Zalod', 12),
(1096, 'Ambala', 13),
(1097, 'Ambala Cantt', 13),
(1098, 'Asan Khurd', 13),
(1099, 'Asandh', 13),
(1100, 'Ateli', 13),
(1101, 'Babiyal', 13),
(1102, 'Bahadurgarh', 13),
(1103, 'Ballabgarh', 13),
(1104, 'Barwala', 13),
(1105, 'Bawal', 13),
(1106, 'Bawani Khera', 13),
(1107, 'Beri', 13),
(1108, 'Bhiwani', 13),
(1109, 'Bilaspur', 13),
(1110, 'Buria', 13),
(1111, 'Charkhi Dadri', 13),
(1112, 'Chhachhrauli', 13),
(1113, 'Chita', 13),
(1114, 'Dabwali', 13),
(1115, 'Dharuhera', 13),
(1116, 'Dundahera', 13),
(1117, 'Ellenabad', 13),
(1118, 'Farakhpur', 13),
(1119, 'Faridabad', 13),
(1120, 'Farrukhnagar', 13),
(1121, 'Fatehabad', 13),
(1122, 'Firozpur Jhirka', 13),
(1123, 'Gannaur', 13),
(1124, 'Ghraunda', 13),
(1125, 'Gohana', 13),
(1126, 'Gurgaon', 13),
(1127, 'Haileymandi', 13),
(1128, 'Hansi', 13),
(1129, 'Hasanpur', 13),
(1130, 'Hathin', 13),
(1131, 'Hisar', 13),
(1132, 'Hissar', 13),
(1133, 'Hodal', 13),
(1134, 'Indri', 13),
(1135, 'Jagadhri', 13),
(1136, 'Jakhal Mandi', 13),
(1137, 'Jhajjar', 13),
(1138, 'Jind', 13),
(1139, 'Julana', 13),
(1140, 'Kaithal', 13),
(1141, 'Kalanur', 13),
(1142, 'Kalanwali', 13),
(1143, 'Kalayat', 13),
(1144, 'Kalka', 13),
(1145, 'Kanina', 13),
(1146, 'Kansepur', 13),
(1147, 'Kardhan', 13),
(1148, 'Karnal', 13),
(1149, 'Kharkhoda', 13),
(1150, 'Kheri Sampla', 13),
(1151, 'Kundli', 13),
(1152, 'Kurukshetra', 13),
(1153, 'Ladrawan', 13),
(1154, 'Ladwa', 13),
(1155, 'Loharu', 13),
(1156, 'Maham', 13),
(1157, 'Mahendragarh', 13),
(1158, 'Mustafabad', 13),
(1159, 'Nagai Chaudhry', 13),
(1160, 'Narayangarh', 13),
(1161, 'Narnaul', 13),
(1162, 'Narnaund', 13),
(1163, 'Narwana', 13),
(1164, 'Nilokheri', 13),
(1165, 'Nuh', 13),
(1166, 'Palwal', 13),
(1167, 'Panchkula', 13),
(1168, 'Panipat', 13),
(1169, 'Panipat Taraf Ansar', 13),
(1170, 'Panipat Taraf Makhdum Zadgan', 13),
(1171, 'Panipat Taraf Rajputan', 13),
(1172, 'Pehowa', 13),
(1173, 'Pinjaur', 13),
(1174, 'Punahana', 13),
(1175, 'Pundri', 13),
(1176, 'Radaur', 13),
(1177, 'Raipur Rani', 13),
(1178, 'Rania', 13),
(1179, 'Ratiya', 13),
(1180, 'Rewari', 13),
(1181, 'Rohtak', 13),
(1182, 'Ropar', 13),
(1183, 'Sadauri', 13),
(1184, 'Safidon', 13),
(1185, 'Samalkha', 13),
(1186, 'Sankhol', 13),
(1187, 'Sasauli', 13),
(1188, 'Shahabad', 13),
(1189, 'Sirsa', 13),
(1190, 'Siwani', 13),
(1191, 'Sohna', 13),
(1192, 'Sonipat', 13),
(1193, 'Sukhrali', 13),
(1194, 'Taoru', 13),
(1195, 'Taraori', 13),
(1196, 'Tauru', 13),
(1197, 'Thanesar', 13),
(1198, 'Tilpat', 13),
(1199, 'Tohana', 13),
(1200, 'Tosham', 13),
(1201, 'Uchana', 13),
(1202, 'Uklana Mandi', 13),
(1203, 'Uncha Siwana', 13),
(1204, 'Yamunanagar', 13),
(1205, 'Arki', 14),
(1206, 'Baddi', 14),
(1207, 'Bakloh', 14),
(1208, 'Banjar', 14),
(1209, 'Bhota', 14),
(1210, 'Bhuntar', 14),
(1211, 'Bilaspur', 14),
(1212, 'Chamba', 14),
(1213, 'Chaupal', 14),
(1214, 'Chuari Khas', 14),
(1215, 'Dagshai', 14),
(1216, 'Dalhousie', 14),
(1217, 'Dalhousie Cantonment', 14),
(1218, 'Damtal', 14),
(1219, 'Daulatpur', 14),
(1220, 'Dera Gopipur', 14),
(1221, 'Dhalli', 14),
(1222, 'Dharamshala', 14),
(1223, 'Gagret', 14),
(1224, 'Ghamarwin', 14),
(1225, 'Hamirpur', 14),
(1226, 'Jawala Mukhi', 14),
(1227, 'Jogindarnagar', 14),
(1228, 'Jubbal', 14),
(1229, 'Jutogh', 14),
(1230, 'Kala Amb', 14),
(1231, 'Kalpa', 14),
(1232, 'Kangra', 14),
(1233, 'Kasauli', 14),
(1234, 'Kot Khai', 14),
(1235, 'Kullu', 14),
(1236, 'Kulu', 14),
(1237, 'Manali', 14),
(1238, 'Mandi', 14),
(1239, 'Mant Khas', 14),
(1240, 'Mehatpur Basdehra', 14),
(1241, 'Nadaun', 14),
(1242, 'Nagrota', 14),
(1243, 'Nahan', 14),
(1244, 'Naina Devi', 14),
(1245, 'Nalagarh', 14),
(1246, 'Narkanda', 14),
(1247, 'Nurpur', 14),
(1248, 'Palampur', 14),
(1249, 'Pandoh', 14),
(1250, 'Paonta Sahib', 14),
(1251, 'Parwanoo', 14),
(1252, 'Parwanu', 14),
(1253, 'Rajgarh', 14),
(1254, 'Rampur', 14),
(1255, 'Rawalsar', 14),
(1256, 'Rohru', 14),
(1257, 'Sabathu', 14),
(1258, 'Santokhgarh', 14),
(1259, 'Sarahan', 14),
(1260, 'Sarka Ghat', 14),
(1261, 'Seoni', 14),
(1262, 'Shimla', 14),
(1263, 'Sirmaur', 14),
(1264, 'Solan', 14),
(1265, 'Solon', 14),
(1266, 'Sundarnagar', 14),
(1267, 'Sundernagar', 14),
(1268, 'Talai', 14),
(1269, 'Theog', 14),
(1270, 'Tira Sujanpur', 14),
(1271, 'Una', 14),
(1272, 'Yol', 14),
(1273, 'Achabal', 15),
(1274, 'Akhnur', 15),
(1275, 'Anantnag', 15),
(1276, 'Arnia', 15),
(1277, 'Awantipora', 15),
(1278, 'Badami Bagh', 15),
(1279, 'Bandipur', 15),
(1280, 'Banihal', 15),
(1281, 'Baramula', 15),
(1282, 'Baramulla', 15),
(1283, 'Bari Brahmana', 15),
(1284, 'Bashohli', 15),
(1285, 'Batote', 15),
(1286, 'Bhaderwah', 15),
(1287, 'Bijbiara', 15),
(1288, 'Billawar', 15),
(1289, 'Birwah', 15),
(1290, 'Bishna', 15),
(1291, 'Budgam', 15),
(1292, 'Charari Sharief', 15),
(1293, 'Chenani', 15),
(1294, 'Doda', 15),
(1295, 'Duru-Verinag', 15),
(1296, 'Gandarbat', 15),
(1297, 'Gho Manhasan', 15),
(1298, 'Gorah Salathian', 15),
(1299, 'Gulmarg', 15),
(1300, 'Hajan', 15),
(1301, 'Handwara', 15),
(1302, 'Hiranagar', 15),
(1303, 'Jammu', 15),
(1304, 'Jammu Cantonment', 15),
(1305, 'Jammu Tawi', 15),
(1306, 'Jourian', 15),
(1307, 'Kargil', 15),
(1308, 'Kathua', 15),
(1309, 'Katra', 15),
(1310, 'Khan Sahib', 15),
(1311, 'Khour', 15),
(1312, 'Khrew', 15),
(1313, 'Kishtwar', 15),
(1314, 'Kud', 15),
(1315, 'Kukernag', 15),
(1316, 'Kulgam', 15),
(1317, 'Kunzer', 15),
(1318, 'Kupwara', 15),
(1319, 'Lakhenpur', 15),
(1320, 'Leh', 15),
(1321, 'Magam', 15),
(1322, 'Mattan', 15),
(1323, 'Naushehra', 15),
(1324, 'Pahalgam', 15),
(1325, 'Pampore', 15),
(1326, 'Parole', 15),
(1327, 'Pattan', 15),
(1328, 'Pulwama', 15),
(1329, 'Punch', 15),
(1330, 'Qazigund', 15),
(1331, 'Rajauri', 15),
(1332, 'Ramban', 15),
(1333, 'Ramgarh', 15),
(1334, 'Ramnagar', 15),
(1335, 'Ranbirsingh Pora', 15),
(1336, 'Reasi', 15),
(1337, 'Rehambal', 15),
(1338, 'Samba', 15),
(1339, 'Shupiyan', 15),
(1340, 'Sopur', 15),
(1341, 'Srinagar', 15),
(1342, 'Sumbal', 15),
(1343, 'Sunderbani', 15),
(1344, 'Talwara', 15),
(1345, 'Thanamandi', 15),
(1346, 'Tral', 15),
(1347, 'Udhampur', 15),
(1348, 'Uri', 15),
(1349, 'Vijaypur', 15),
(1350, 'Adityapur', 16),
(1351, 'Amlabad', 16),
(1352, 'Angarpathar', 16),
(1353, 'Ara', 16),
(1354, 'Babua Kalan', 16),
(1355, 'Bagbahra', 16),
(1356, 'Baliapur', 16),
(1357, 'Baliari', 16),
(1358, 'Balkundra', 16),
(1359, 'Bandhgora', 16),
(1360, 'Barajamda', 16),
(1361, 'Barhi', 16),
(1362, 'Barka Kana', 16),
(1363, 'Barki Saraiya', 16),
(1364, 'Barughutu', 16),
(1365, 'Barwadih', 16),
(1366, 'Basaria', 16),
(1367, 'Basukinath', 16),
(1368, 'Bermo', 16),
(1369, 'Bhagatdih', 16),
(1370, 'Bhaurah', 16),
(1371, 'Bhojudih', 16),
(1372, 'Bhuli', 16),
(1373, 'Bokaro', 16),
(1374, 'Borio Bazar', 16),
(1375, 'Bundu', 16),
(1376, 'Chaibasa', 16),
(1377, 'Chaitudih', 16),
(1378, 'Chakradharpur', 16),
(1379, 'Chakulia', 16),
(1380, 'Chandaur', 16),
(1381, 'Chandil', 16),
(1382, 'Chandrapura', 16),
(1383, 'Chas', 16),
(1384, 'Chatra', 16),
(1385, 'Chhatatanr', 16),
(1386, 'Chhotaputki', 16),
(1387, 'Chiria', 16),
(1388, 'Chirkunda', 16),
(1389, 'Churi', 16),
(1390, 'Daltenganj', 16),
(1391, 'Danguwapasi', 16),
(1392, 'Dari', 16),
(1393, 'Deoghar', 16),
(1394, 'Deorikalan', 16),
(1395, 'Devghar', 16),
(1396, 'Dhanbad', 16),
(1397, 'Dhanwar', 16),
(1398, 'Dhaunsar', 16),
(1399, 'Dugda', 16),
(1400, 'Dumarkunda', 16),
(1401, 'Dumka', 16),
(1402, 'Egarkunr', 16),
(1403, 'Gadhra', 16),
(1404, 'Garwa', 16),
(1405, 'Ghatsila', 16),
(1406, 'Ghorabandha', 16),
(1407, 'Gidi', 16),
(1408, 'Giridih', 16),
(1409, 'Gobindpur', 16),
(1410, 'Godda', 16),
(1411, 'Godhar', 16),
(1412, 'Golphalbari', 16),
(1413, 'Gomoh', 16),
(1414, 'Gua', 16),
(1415, 'Gumia', 16),
(1416, 'Gumla', 16),
(1417, 'Haludbani', 16),
(1418, 'Hazaribag', 16),
(1419, 'Hesla', 16),
(1420, 'Husainabad', 16),
(1421, 'Isri', 16),
(1422, 'Jadugora', 16),
(1423, 'Jagannathpur', 16),
(1424, 'Jamadoba', 16),
(1425, 'Jamshedpur', 16),
(1426, 'Jamtara', 16),
(1427, 'Jarangdih', 16),
(1428, 'Jaridih', 16),
(1429, 'Jasidih', 16),
(1430, 'Jena', 16),
(1431, 'Jharia', 16),
(1432, 'Jharia Khas', 16),
(1433, 'Jhinkpani', 16),
(1434, 'Jhumri Tilaiya', 16),
(1435, 'Jorapokhar', 16),
(1436, 'Jugsalai', 16),
(1437, 'Kailudih', 16),
(1438, 'Kalikapur', 16),
(1439, 'Kandra', 16),
(1440, 'Kanke', 16),
(1441, 'Katras', 16),
(1442, 'Kedla', 16),
(1443, 'Kenduadih', 16),
(1444, 'Kharkhari', 16),
(1445, 'Kharsawan', 16),
(1446, 'Khelari', 16),
(1447, 'Khunti', 16),
(1448, 'Kiri Buru', 16),
(1449, 'Kiriburu', 16),
(1450, 'Kodarma', 16),
(1451, 'Kuju', 16),
(1452, 'Kurpania', 16),
(1453, 'Kustai', 16),
(1454, 'Lakarka', 16),
(1455, 'Lapanga', 16),
(1456, 'Latehar', 16),
(1457, 'Lohardaga', 16),
(1458, 'Loiya', 16),
(1459, 'Loyabad', 16),
(1460, 'Madhupur', 16),
(1461, 'Mahesh Mundi', 16),
(1462, 'Maithon', 16),
(1463, 'Malkera', 16),
(1464, 'Mango', 16),
(1465, 'Manoharpur', 16),
(1466, 'Marma', 16),
(1467, 'Meghahatuburu Forest village', 16),
(1468, 'Mera', 16),
(1469, 'Meru', 16),
(1470, 'Mihijam', 16),
(1471, 'Mugma', 16),
(1472, 'Muri', 16),
(1473, 'Mushabani', 16),
(1474, 'Nagri Kalan', 16),
(1475, 'Netarhat', 16),
(1476, 'Nirsa', 16),
(1477, 'Noamundi', 16),
(1478, 'Okni', 16),
(1479, 'Orla', 16),
(1480, 'Pakaur', 16),
(1481, 'Palamau', 16),
(1482, 'Palawa', 16),
(1483, 'Panchet', 16),
(1484, 'Panrra', 16),
(1485, 'Paratdih', 16),
(1486, 'Pathardih', 16),
(1487, 'Patratu', 16),
(1488, 'Phusro', 16),
(1489, 'Pondar Kanali', 16),
(1490, 'Rajmahal', 16),
(1491, 'Ramgarh', 16),
(1492, 'Ranchi', 16),
(1493, 'Ray', 16),
(1494, 'Rehla', 16),
(1495, 'Religara', 16),
(1496, 'Rohraband', 16),
(1497, 'Sahibganj', 16),
(1498, 'Sahnidih', 16),
(1499, 'Saraidhela', 16),
(1500, 'Saraikela', 16),
(1501, 'Sarjamda', 16),
(1502, 'Saunda', 16),
(1503, 'Sewai', 16),
(1504, 'Sijhua', 16),
(1505, 'Sijua', 16),
(1506, 'Simdega', 16),
(1507, 'Sindari', 16),
(1508, 'Sinduria', 16),
(1509, 'Sini', 16),
(1510, 'Sirka', 16),
(1511, 'Siuliban', 16),
(1512, 'Surubera', 16),
(1513, 'Tati', 16),
(1514, 'Tenudam', 16),
(1515, 'Tisra', 16),
(1516, 'Topa', 16),
(1517, 'Topchanchi', 16),
(1518, 'Adityanagar', 17),
(1519, 'Adityapatna', 17),
(1520, 'Afzalpur', 17),
(1521, 'Ajjampur', 17),
(1522, 'Aland', 17),
(1523, 'Almatti Sitimani', 17),
(1524, 'Alnavar', 17),
(1525, 'Alur', 17),
(1526, 'Ambikanagara', 17),
(1527, 'Anekal', 17),
(1528, 'Ankola', 17),
(1529, 'Annigeri', 17),
(1530, 'Arkalgud', 17),
(1531, 'Arsikere', 17),
(1532, 'Athni', 17),
(1533, 'Aurad', 17),
(1534, 'Badagavettu', 17),
(1535, 'Badami', 17),
(1536, 'Bagalkot', 17),
(1537, 'Bagepalli', 17),
(1538, 'Bailhongal', 17),
(1539, 'Baindur', 17),
(1540, 'Bajala', 17),
(1541, 'Bajpe', 17),
(1542, 'Banavar', 17),
(1543, 'Bangarapet', 17),
(1544, 'Bankapura', 17),
(1545, 'Bannur', 17),
(1546, 'Bantwal', 17),
(1547, 'Basavakalyan', 17),
(1548, 'Basavana Bagevadi', 17),
(1549, 'Belagula', 17),
(1550, 'Belakavadiq', 17),
(1551, 'Belgaum', 17),
(1552, 'Belgaum Cantonment', 17),
(1553, 'Bellary', 17),
(1554, 'Belluru', 17),
(1555, 'Beltangadi', 17),
(1556, 'Belur', 17),
(1557, 'Belvata', 17),
(1558, 'Bengaluru', 17),
(1559, 'Bhadravati', 17),
(1560, 'Bhalki', 17),
(1561, 'Bhatkal', 17),
(1562, 'Bhimarayanagudi', 17),
(1563, 'Bhogadi', 17),
(1564, 'Bidar', 17),
(1565, 'Bijapur', 17),
(1566, 'Bilgi', 17),
(1567, 'Birur', 17),
(1568, 'Bommanahalli', 17),
(1569, 'Bommasandra', 17),
(1570, 'Byadgi', 17),
(1571, 'Byatarayanapura', 17),
(1572, 'Chakranagar Colony', 17),
(1573, 'Challakere', 17),
(1574, 'Chamrajnagar', 17),
(1575, 'Chamundi Betta', 17),
(1576, 'Channagiri', 17),
(1577, 'Channapatna', 17),
(1578, 'Channarayapatna', 17),
(1579, 'Chickballapur', 17),
(1580, 'Chik Ballapur', 17),
(1581, 'Chikkaballapur', 17),
(1582, 'Chikmagalur', 17),
(1583, 'Chiknayakanhalli', 17),
(1584, 'Chikodi', 17),
(1585, 'Chincholi', 17),
(1586, 'Chintamani', 17),
(1587, 'Chitaguppa', 17),
(1588, 'Chitapur', 17),
(1589, 'Chitradurga', 17),
(1590, 'Coorg', 17),
(1591, 'Dandeli', 17),
(1592, 'Dargajogihalli', 17),
(1593, 'Dasarahalli', 17),
(1594, 'Davangere', 17),
(1595, 'Devadurga', 17),
(1596, 'Devagiri', 17),
(1597, 'Devanhalli', 17),
(1598, 'Dharwar', 17),
(1599, 'Dhupdal', 17),
(1600, 'Dod Ballapur', 17),
(1601, 'Donimalai', 17),
(1602, 'Gadag', 17),
(1603, 'Gajendragarh', 17),
(1604, 'Ganeshgudi', 17),
(1605, 'Gangawati', 17),
(1606, 'Gangoli', 17),
(1607, 'Gauribidanur', 17),
(1608, 'Gokak', 17),
(1609, 'Gokak Falls', 17),
(1610, 'Gonikoppal', 17),
(1611, 'Gorur', 17),
(1612, 'Gottikere', 17),
(1613, 'Gubbi', 17),
(1614, 'Gudibanda', 17),
(1615, 'Gulbarga', 17),
(1616, 'Guledgudda', 17),
(1617, 'Gundlupet', 17),
(1618, 'Gurmatkal', 17),
(1619, 'Haliyal', 17),
(1620, 'Hangal', 17),
(1621, 'Harihar', 17),
(1622, 'Harpanahalli', 17),
(1623, 'Hassan', 17),
(1624, 'Hatti', 17),
(1625, 'Hatti Gold Mines', 17),
(1626, 'Haveri', 17),
(1627, 'Hebbagodi', 17),
(1628, 'Hebbalu', 17),
(1629, 'Hebri', 17),
(1630, 'Heggadadevanakote', 17),
(1631, 'Herohalli', 17),
(1632, 'Hidkal', 17),
(1633, 'Hindalgi', 17),
(1634, 'Hirekerur', 17),
(1635, 'Hiriyur', 17),
(1636, 'Holalkere', 17),
(1637, 'Hole Narsipur', 17),
(1638, 'Homnabad', 17),
(1639, 'Honavar', 17),
(1640, 'Honnali', 17),
(1641, 'Hosakote', 17),
(1642, 'Hosanagara', 17),
(1643, 'Hosangadi', 17),
(1644, 'Hosdurga', 17),
(1645, 'Hoskote', 17),
(1646, 'Hospet', 17),
(1647, 'Hubli', 17),
(1648, 'Hukeri', 17),
(1649, 'Hunasagi', 17),
(1650, 'Hunasamaranahalli', 17),
(1651, 'Hungund', 17),
(1652, 'Hunsur', 17),
(1653, 'Huvina Hadagalli', 17),
(1654, 'Ilkal', 17),
(1655, 'Indi', 17),
(1656, 'Jagalur', 17),
(1657, 'Jamkhandi', 17),
(1658, 'Jevargi', 17),
(1659, 'Jog Falls', 17),
(1660, 'Kabini Colony', 17),
(1661, 'Kadur', 17),
(1662, 'Kalghatgi', 17),
(1663, 'Kamalapuram', 17),
(1664, 'Kampli', 17),
(1665, 'Kanakapura', 17),
(1666, 'Kangrali BK', 17),
(1667, 'Kangrali KH', 17),
(1668, 'Kannur', 17),
(1669, 'Karkala', 17),
(1670, 'Karwar', 17),
(1671, 'Kemminja', 17),
(1672, 'Kengeri', 17),
(1673, 'Kerur', 17),
(1674, 'Khanapur', 17),
(1675, 'Kodigenahalli', 17),
(1676, 'Kodiyal', 17),
(1677, 'Kodlipet', 17),
(1678, 'Kolar', 17),
(1679, 'Kollegal', 17),
(1680, 'Konanakunte', 17),
(1681, 'Konanur', 17),
(1682, 'Konnur', 17),
(1683, 'Koppa', 17),
(1684, 'Koppal', 17),
(1685, 'Koratagere', 17),
(1686, 'Kotekara', 17),
(1687, 'Kothnur', 17),
(1688, 'Kotturu', 17),
(1689, 'Krishnapura', 17),
(1690, 'Krishnarajanagar', 17),
(1691, 'Krishnarajapura', 17),
(1692, 'Krishnarajasagara', 17),
(1693, 'Krishnarajpet', 17),
(1694, 'Kudchi', 17),
(1695, 'Kudligi', 17),
(1696, 'Kudremukh', 17),
(1697, 'Kumsi', 17),
(1698, 'Kumta', 17),
(1699, 'Kundapura', 17),
(1700, 'Kundgol', 17),
(1701, 'Kunigal', 17),
(1702, 'Kurgunta', 17),
(1703, 'Kushalnagar', 17),
(1704, 'Kushtagi', 17),
(1705, 'Kyathanahalli', 17),
(1706, 'Lakshmeshwar', 17),
(1707, 'Lingsugur', 17),
(1708, 'Londa', 17),
(1709, 'Maddur', 17),
(1710, 'Madhugiri', 17),
(1711, 'Madikeri', 17),
(1712, 'Magadi', 17),
(1713, 'Magod Falls', 17),
(1714, 'Mahadeswara Hills', 17),
(1715, 'Mahadevapura', 17),
(1716, 'Mahalingpur', 17),
(1717, 'Maisuru', 17),
(1718, 'Maisuru Cantonment', 17),
(1719, 'Malavalli', 17),
(1720, 'Mallar', 17),
(1721, 'Malpe', 17),
(1722, 'Malur', 17),
(1723, 'Manchenahalli', 17),
(1724, 'Mandya', 17),
(1725, 'Mangalore', 17),
(1726, 'Mangaluru', 17),
(1727, 'Manipal', 17),
(1728, 'Manvi', 17),
(1729, 'Maski', 17),
(1730, 'Mastikatte Colony', 17),
(1731, 'Mayakonda', 17),
(1732, 'Melukote', 17),
(1733, 'Molakalmuru', 17),
(1734, 'Mudalgi', 17),
(1735, 'Mudbidri', 17),
(1736, 'Muddebihal', 17),
(1737, 'Mudgal', 17),
(1738, 'Mudhol', 17),
(1739, 'Mudigere', 17),
(1740, 'Mudushedde', 17),
(1741, 'Mulbagal', 17),
(1742, 'Mulgund', 17),
(1743, 'Mulki', 17),
(1744, 'Mulur', 17),
(1745, 'Mundargi', 17),
(1746, 'Mundgod', 17),
(1747, 'Munirabad', 17),
(1748, 'Munnur', 17),
(1749, 'Murudeshwara', 17),
(1750, 'Mysore', 17),
(1751, 'Nagamangala', 17),
(1752, 'Nanjangud', 17),
(1753, 'Naragund', 17),
(1754, 'Narasimharajapura', 17),
(1755, 'Naravi', 17),
(1756, 'Narayanpur', 17),
(1757, 'Naregal', 17),
(1758, 'Navalgund', 17),
(1759, 'Nelmangala', 17),
(1760, 'Nipani', 17),
(1761, 'Nitte', 17),
(1762, 'Nyamati', 17),
(1763, 'Padu', 17),
(1764, 'Pandavapura', 17),
(1765, 'Pattanagere', 17),
(1766, 'Pavagada', 17),
(1767, 'Piriyapatna', 17),
(1768, 'Ponnampet', 17),
(1769, 'Puttur', 17),
(1770, 'Rabkavi', 17),
(1771, 'Raichur', 17),
(1772, 'Ramanagaram', 17),
(1773, 'Ramdurg', 17),
(1774, 'Ranibennur', 17),
(1775, 'Raybag', 17),
(1776, 'Robertsonpet', 17),
(1777, 'Ron', 17),
(1778, 'Sadalgi', 17),
(1779, 'Sagar', 17),
(1780, 'Sakleshpur', 17),
(1781, 'Saligram', 17),
(1782, 'Sandur', 17),
(1783, 'Sanivarsante', 17),
(1784, 'Sankeshwar', 17),
(1785, 'Sargur', 17),
(1786, 'Sathyamangala', 17),
(1787, 'Saundatti Yellamma', 17),
(1788, 'Savanur', 17),
(1789, 'Sedam', 17),
(1790, 'Shahabad', 17),
(1791, 'Shahabad A.C.C.', 17),
(1792, 'Shahapur', 17),
(1793, 'Shahpur', 17),
(1794, 'Shaktinagar', 17),
(1795, 'Shiggaon', 17),
(1796, 'Shikarpur', 17),
(1797, 'Shimoga', 17),
(1798, 'Shirhatti', 17),
(1799, 'Shorapur', 17),
(1800, 'Shravanabelagola', 17),
(1801, 'Shrirangapattana', 17),
(1802, 'Siddapur', 17),
(1803, 'Sidlaghatta', 17),
(1804, 'Sindgi', 17),
(1805, 'Sindhnur', 17),
(1806, 'Sira', 17),
(1807, 'Sirakoppa', 17),
(1808, 'Sirsi', 17),
(1809, 'Siruguppa', 17),
(1810, 'Someshwar', 17),
(1811, 'Somvarpet', 17),
(1812, 'Sorab', 17),
(1813, 'Sringeri', 17),
(1814, 'Srinivaspur', 17),
(1815, 'Sulya', 17),
(1816, 'Suntikopa', 17),
(1817, 'Talikota', 17),
(1818, 'Tarikera', 17),
(1819, 'Tekkalakota', 17),
(1820, 'Terdal', 17),
(1821, 'Thokur', 17),
(1822, 'Thumbe', 17),
(1823, 'Tiptur', 17),
(1824, 'Tirthahalli', 17),
(1825, 'Tirumakudal Narsipur', 17),
(1826, 'Tonse', 17),
(1827, 'Tumkur', 17),
(1828, 'Turuvekere', 17),
(1829, 'Udupi', 17),
(1830, 'Ullal', 17),
(1831, 'Uttarahalli', 17),
(1832, 'Venkatapura', 17),
(1833, 'Vijayapura', 17),
(1834, 'Virarajendrapet', 17),
(1835, 'Wadi', 17),
(1836, 'Wadi A.C.C.', 17),
(1837, 'Yadgir', 17),
(1838, 'Yelahanka', 17),
(1839, 'Yelandur', 17),
(1840, 'Yelbarga', 17),
(1841, 'Yellapur', 17),
(1842, 'Yenagudde', 17),
(1843, 'Adimaly', 19),
(1844, 'Adoor', 19),
(1845, 'Adur', 19),
(1846, 'Akathiyur', 19),
(1847, 'Alangad', 19),
(1848, 'Alappuzha', 19),
(1849, 'Aluva', 19),
(1850, 'Ancharakandy', 19),
(1851, 'Angamaly', 19),
(1852, 'Aroor', 19),
(1853, 'Arukutti', 19),
(1854, 'Attingal', 19),
(1855, 'Avinissery', 19),
(1856, 'Azhikode North', 19),
(1857, 'Azhikode South', 19),
(1858, 'Azhiyur', 19),
(1859, 'Balussery', 19),
(1860, 'Bangramanjeshwar', 19),
(1861, 'Beypur', 19),
(1862, 'Brahmakulam', 19),
(1863, 'Chala', 19),
(1864, 'Chalakudi', 19),
(1865, 'Changanacheri', 19),
(1866, 'Chauwara', 19),
(1867, 'Chavakkad', 19),
(1868, 'Chelakkara', 19),
(1869, 'Chelora', 19),
(1870, 'Chendamangalam', 19),
(1871, 'Chengamanad', 19),
(1872, 'Chengannur', 19),
(1873, 'Cheranallur', 19),
(1874, 'Cheriyakadavu', 19),
(1875, 'Cherthala', 19),
(1876, 'Cherukunnu', 19),
(1877, 'Cheruthazham', 19),
(1878, 'Cheruvannur', 19),
(1879, 'Cheruvattur', 19),
(1880, 'Chevvur', 19),
(1881, 'Chirakkal', 19),
(1882, 'Chittur', 19),
(1883, 'Chockli', 19),
(1884, 'Churnikkara', 19),
(1885, 'Dharmadam', 19),
(1886, 'Edappal', 19),
(1887, 'Edathala', 19),
(1888, 'Elayavur', 19),
(1889, 'Elur', 19),
(1890, 'Eranholi', 19),
(1891, 'Erattupetta', 19),
(1892, 'Ernakulam', 19),
(1893, 'Eruvatti', 19),
(1894, 'Ettumanoor', 19),
(1895, 'Feroke', 19),
(1896, 'Guruvayur', 19),
(1897, 'Haripad', 19),
(1898, 'Hosabettu', 19),
(1899, 'Idukki', 19),
(1900, 'Iringaprom', 19),
(1901, 'Irinjalakuda', 19),
(1902, 'Iriveri', 19),
(1903, 'Kadachira', 19),
(1904, 'Kadalundi', 19),
(1905, 'Kadamakkudy', 19),
(1906, 'Kadirur', 19),
(1907, 'Kadungallur', 19),
(1908, 'Kakkodi', 19),
(1909, 'Kalady', 19),
(1910, 'Kalamassery', 19),
(1911, 'Kalliasseri', 19),
(1912, 'Kalpetta', 19),
(1913, 'Kanhangad', 19),
(1914, 'Kanhirode', 19),
(1915, 'Kanjikkuzhi', 19),
(1916, 'Kanjikode', 19),
(1917, 'Kanjirappalli', 19),
(1918, 'Kannadiparamba', 19),
(1919, 'Kannangad', 19),
(1920, 'Kannapuram', 19),
(1921, 'Kannur', 19),
(1922, 'Kannur Cantonment', 19),
(1923, 'Karunagappally', 19),
(1924, 'Karuvamyhuruthy', 19),
(1925, 'Kasaragod', 19),
(1926, 'Kasargod', 19),
(1927, 'Kattappana', 19),
(1928, 'Kayamkulam', 19),
(1929, 'Kedamangalam', 19),
(1930, 'Kochi', 19),
(1931, 'Kodamthuruthu', 19),
(1932, 'Kodungallur', 19),
(1933, 'Koduvally', 19),
(1934, 'Koduvayur', 19),
(1935, 'Kokkothamangalam', 19),
(1936, 'Kolazhy', 19),
(1937, 'Kollam', 19),
(1938, 'Komalapuram', 19),
(1939, 'Koothattukulam', 19),
(1940, 'Koratty', 19),
(1941, 'Kothamangalam', 19),
(1942, 'Kottarakkara', 19),
(1943, 'Kottayam', 19),
(1944, 'Kottayam Malabar', 19),
(1945, 'Kottuvally', 19),
(1946, 'Koyilandi', 19),
(1947, 'Kozhikode', 19),
(1948, 'Kudappanakunnu', 19),
(1949, 'Kudlu', 19),
(1950, 'Kumarakom', 19),
(1951, 'Kumily', 19),
(1952, 'Kunnamangalam', 19),
(1953, 'Kunnamkulam', 19),
(1954, 'Kurikkad', 19),
(1955, 'Kurkkanchery', 19),
(1956, 'Kuthuparamba', 19),
(1957, 'Kuttakulam', 19),
(1958, 'Kuttikkattur', 19),
(1959, 'Kuttur', 19),
(1960, 'Malappuram', 19),
(1961, 'Mallappally', 19),
(1962, 'Manjeri', 19),
(1963, 'Manjeshwar', 19),
(1964, 'Mannancherry', 19),
(1965, 'Mannar', 19),
(1966, 'Mannarakkat', 19),
(1967, 'Maradu', 19),
(1968, 'Marathakkara', 19),
(1969, 'Marutharod', 19),
(1970, 'Mattannur', 19),
(1971, 'Mavelikara', 19),
(1972, 'Mavilayi', 19),
(1973, 'Mavur', 19),
(1974, 'Methala', 19),
(1975, 'Muhamma', 19),
(1976, 'Mulavukad', 19),
(1977, 'Mundakayam', 19),
(1978, 'Munderi', 19),
(1979, 'Munnar', 19),
(1980, 'Muthakunnam', 19),
(1981, 'Muvattupuzha', 19),
(1982, 'Muzhappilangad', 19),
(1983, 'Nadapuram', 19),
(1984, 'Nadathara', 19),
(1985, 'Narath', 19),
(1986, 'Nattakam', 19),
(1987, 'Nedumangad', 19),
(1988, 'Nenmenikkara', 19),
(1989, 'New Mahe', 19),
(1990, 'Neyyattinkara', 19),
(1991, 'Nileshwar', 19),
(1992, 'Olavanna', 19),
(1993, 'Ottapalam', 19),
(1994, 'Ottappalam', 19),
(1995, 'Paduvilayi', 19),
(1996, 'Palai', 19),
(1997, 'Palakkad', 19),
(1998, 'Palayad', 19),
(1999, 'Palissery', 19),
(2000, 'Pallikkunnu', 19),
(2001, 'Paluvai', 19),
(2002, 'Panniyannur', 19),
(2003, 'Pantalam', 19),
(2004, 'Panthiramkavu', 19),
(2005, 'Panur', 19),
(2006, 'Pappinisseri', 19),
(2007, 'Parassala', 19),
(2008, 'Paravur', 19),
(2009, 'Pathanamthitta', 19),
(2010, 'Pathanapuram', 19),
(2011, 'Pathiriyad', 19),
(2012, 'Pattambi', 19),
(2013, 'Pattiom', 19),
(2014, 'Pavaratty', 19),
(2015, 'Payyannur', 19),
(2016, 'Peermade', 19),
(2017, 'Perakam', 19),
(2018, 'Peralasseri', 19),
(2019, 'Peringathur', 19),
(2020, 'Perinthalmanna', 19),
(2021, 'Perole', 19),
(2022, 'Perumanna', 19),
(2023, 'Perumbaikadu', 19),
(2024, 'Perumbavoor', 19),
(2025, 'Pinarayi', 19),
(2026, 'Piravam', 19),
(2027, 'Ponnani', 19),
(2028, 'Pottore', 19),
(2029, 'Pudukad', 19),
(2030, 'Punalur', 19),
(2031, 'Puranattukara', 19),
(2032, 'Puthunagaram', 19),
(2033, 'Puthuppariyaram', 19),
(2034, 'Puzhathi', 19),
(2035, 'Ramanattukara', 19),
(2036, 'Shoranur', 19),
(2037, 'Sultans Battery', 19),
(2038, 'Sulthan Bathery', 19),
(2039, 'Talipparamba', 19),
(2040, 'Thaikkad', 19),
(2041, 'Thalassery', 19),
(2042, 'Thannirmukkam', 19),
(2043, 'Theyyalingal', 19),
(2044, 'Thiruvalla', 19),
(2045, 'Thiruvananthapuram', 19),
(2046, 'Thiruvankulam', 19),
(2047, 'Thodupuzha', 19),
(2048, 'Thottada', 19),
(2049, 'Thrippunithura', 19),
(2050, 'Thrissur', 19),
(2051, 'Tirur', 19),
(2052, 'Udma', 19),
(2053, 'Vadakara', 19),
(2054, 'Vaikam', 19),
(2055, 'Valapattam', 19),
(2056, 'Vallachira', 19),
(2057, 'Varam', 19),
(2058, 'Varappuzha', 19),
(2059, 'Varkala', 19),
(2060, 'Vayalar', 19),
(2061, 'Vazhakkala', 19),
(2062, 'Venmanad', 19),
(2063, 'Villiappally', 19),
(2064, 'Wayanad', 19),
(2065, 'Agethi', 20),
(2066, 'Amini', 20),
(2067, 'Androth Island', 20),
(2068, 'Kavaratti', 20),
(2069, 'Minicoy', 20),
(2070, 'Agar', 21),
(2071, 'Ajaigarh', 21),
(2072, 'Akoda', 21),
(2073, 'Akodia', 21),
(2074, 'Alampur', 21),
(2075, 'Alirajpur', 21),
(2076, 'Alot', 21),
(2077, 'Amanganj', 21),
(2078, 'Amarkantak', 21),
(2079, 'Amarpatan', 21),
(2080, 'Amarwara', 21),
(2081, 'Ambada', 21),
(2082, 'Ambah', 21),
(2083, 'Amla', 21),
(2084, 'Amlai', 21),
(2085, 'Anjad', 21),
(2086, 'Antri', 21),
(2087, 'Anuppur', 21),
(2088, 'Aron', 21),
(2089, 'Ashoknagar', 21),
(2090, 'Ashta', 21),
(2091, 'Babai', 21),
(2092, 'Bada Malhera', 21),
(2093, 'Badagaon', 21),
(2094, 'Badagoan', 21),
(2095, 'Badarwas', 21),
(2096, 'Badawada', 21),
(2097, 'Badi', 21),
(2098, 'Badkuhi', 21),
(2099, 'Badnagar', 21),
(2100, 'Badnawar', 21),
(2101, 'Badod', 21),
(2102, 'Badoda', 21),
(2103, 'Badra', 21),
(2104, 'Bagh', 21),
(2105, 'Bagli', 21),
(2106, 'Baihar', 21),
(2107, 'Baikunthpur', 21),
(2108, 'Bakswaha', 21),
(2109, 'Balaghat', 21),
(2110, 'Baldeogarh', 21),
(2111, 'Bamaniya', 21),
(2112, 'Bamhani', 21),
(2113, 'Bamor', 21),
(2114, 'Bamora', 21),
(2115, 'Banda', 21),
(2116, 'Bangawan', 21),
(2117, 'Bansatar Kheda', 21),
(2118, 'Baraily', 21),
(2119, 'Barela', 21),
(2120, 'Barghat', 21),
(2121, 'Bargi', 21),
(2122, 'Barhi', 21),
(2123, 'Barigarh', 21),
(2124, 'Barwaha', 21),
(2125, 'Barwani', 21),
(2126, 'Basoda', 21),
(2127, 'Begamganj', 21),
(2128, 'Beohari', 21),
(2129, 'Berasia', 21),
(2130, 'Betma', 21),
(2131, 'Betul', 21),
(2132, 'Betul Bazar', 21),
(2133, 'Bhainsdehi', 21),
(2134, 'Bhamodi', 21),
(2135, 'Bhander', 21),
(2136, 'Bhanpura', 21),
(2137, 'Bharveli', 21),
(2138, 'Bhaurasa', 21),
(2139, 'Bhavra', 21),
(2140, 'Bhedaghat', 21),
(2141, 'Bhikangaon', 21),
(2142, 'Bhilakhedi', 21),
(2143, 'Bhind', 21),
(2144, 'Bhitarwar', 21),
(2145, 'Bhopal', 21),
(2146, 'Bhuibandh', 21),
(2147, 'Biaora', 21),
(2148, 'Bijawar', 21),
(2149, 'Bijeypur', 21),
(2150, 'Bijrauni', 21),
(2151, 'Bijuri', 21),
(2152, 'Bilaua', 21),
(2153, 'Bilpura', 21),
(2154, 'Bina Railway Colony', 21),
(2155, 'Bina-Etawa', 21),
(2156, 'Birsinghpur', 21),
(2157, 'Boda', 21),
(2158, 'Budhni', 21),
(2159, 'Burhanpur', 21),
(2160, 'Burhar', 21),
(2161, 'Chachaura Binaganj', 21),
(2162, 'Chakghat', 21),
(2163, 'Chandameta Butar', 21),
(2164, 'Chanderi', 21),
(2165, 'Chandia', 21),
(2166, 'Chandla', 21),
(2167, 'Chaurai Khas', 21),
(2168, 'Chhatarpur', 21),
(2169, 'Chhindwara', 21),
(2170, 'Chhota Chhindwara', 21),
(2171, 'Chichli', 21),
(2172, 'Chitrakut', 21),
(2173, 'Churhat', 21),
(2174, 'Daboh', 21),
(2175, 'Dabra', 21),
(2176, 'Damoh', 21),
(2177, 'Damua', 21),
(2178, 'Datia', 21),
(2179, 'Deodara', 21),
(2180, 'Deori', 21),
(2181, 'Deori Khas', 21),
(2182, 'Depalpur', 21),
(2183, 'Devendranagar', 21),
(2184, 'Devhara', 21),
(2185, 'Dewas', 21),
(2186, 'Dhamnod', 21),
(2187, 'Dhana', 21),
(2188, 'Dhanpuri', 21),
(2189, 'Dhar', 21),
(2190, 'Dharampuri', 21),
(2191, 'Dighawani', 21),
(2192, 'Diken', 21),
(2193, 'Dindori', 21),
(2194, 'Dola', 21),
(2195, 'Dumar Kachhar', 21),
(2196, 'Dungariya Chhapara', 21),
(2197, 'Gadarwara', 21),
(2198, 'Gairatganj', 21),
(2199, 'Gandhi Sagar Hydel Colony', 21),
(2200, 'Ganjbasoda', 21),
(2201, 'Garhakota', 21),
(2202, 'Garhi Malhara', 21),
(2203, 'Garoth', 21),
(2204, 'Gautapura', 21),
(2205, 'Ghansor', 21),
(2206, 'Ghuwara', 21),
(2207, 'Gogaon', 21),
(2208, 'Gogapur', 21),
(2209, 'Gohad', 21),
(2210, 'Gormi', 21),
(2211, 'Govindgarh', 21),
(2212, 'Guna', 21),
(2213, 'Gurh', 21),
(2214, 'Gwalior', 21),
(2215, 'Hanumana', 21),
(2216, 'Harda', 21),
(2217, 'Harpalpur', 21),
(2218, 'Harrai', 21),
(2219, 'Harsud', 21),
(2220, 'Hatod', 21),
(2221, 'Hatpipalya', 21),
(2222, 'Hatta', 21),
(2223, 'Hindoria', 21),
(2224, 'Hirapur', 21),
(2225, 'Hoshangabad', 21),
(2226, 'Ichhawar', 21),
(2227, 'Iklehra', 21),
(2228, 'Indergarh', 21),
(2229, 'Indore', 21),
(2230, 'Isagarh', 21),
(2231, 'Itarsi', 21),
(2232, 'Jabalpur', 21),
(2233, 'Jabalpur Cantonment', 21),
(2234, 'Jabalpur G.C.F', 21),
(2235, 'Jaisinghnagar', 21),
(2236, 'Jaithari', 21),
(2237, 'Jaitwara', 21),
(2238, 'Jamai', 21),
(2239, 'Jaora', 21),
(2240, 'Jatachhapar', 21),
(2241, 'Jatara', 21),
(2242, 'Jawad', 21),
(2243, 'Jawar', 21),
(2244, 'Jeronkhalsa', 21),
(2245, 'Jhabua', 21),
(2246, 'Jhundpura', 21),
(2247, 'Jiran', 21),
(2248, 'Jirapur', 21),
(2249, 'Jobat', 21),
(2250, 'Joura', 21),
(2251, 'Kailaras', 21),
(2252, 'Kaimur', 21),
(2253, 'Kakarhati', 21),
(2254, 'Kalichhapar', 21),
(2255, 'Kanad', 21),
(2256, 'Kannod', 21),
(2257, 'Kantaphod', 21),
(2258, 'Kareli', 21),
(2259, 'Karera', 21),
(2260, 'Kari', 21),
(2261, 'Karnawad', 21),
(2262, 'Karrapur', 21),
(2263, 'Kasrawad', 21),
(2264, 'Katangi', 21),
(2265, 'Katni', 21),
(2266, 'Kelhauri', 21),
(2267, 'Khachrod', 21),
(2268, 'Khajuraho', 21),
(2269, 'Khamaria', 21),
(2270, 'Khand', 21),
(2271, 'Khandwa', 21),
(2272, 'Khaniyadhana', 21),
(2273, 'Khargapur', 21),
(2274, 'Khargone', 21),
(2275, 'Khategaon', 21),
(2276, 'Khetia', 21),
(2277, 'Khilchipur', 21),
(2278, 'Khirkiya', 21),
(2279, 'Khujner', 21),
(2280, 'Khurai', 21),
(2281, 'Kolaras', 21),
(2282, 'Kotar', 21),
(2283, 'Kothi', 21),
(2284, 'Kotma', 21),
(2285, 'Kukshi', 21),
(2286, 'Kumbhraj', 21),
(2287, 'Kurwai', 21),
(2288, 'Lahar', 21),
(2289, 'Lakhnadon', 21),
(2290, 'Lateri', 21),
(2291, 'Laundi', 21),
(2292, 'Lidhora Khas', 21),
(2293, 'Lodhikheda', 21),
(2294, 'Loharda', 21),
(2295, 'Machalpur', 21),
(2296, 'Madhogarh', 21),
(2297, 'Maharajpur', 21),
(2298, 'Maheshwar', 21),
(2299, 'Mahidpur', 21),
(2300, 'Maihar', 21),
(2301, 'Majholi', 21),
(2302, 'Makronia', 21),
(2303, 'Maksi', 21),
(2304, 'Malaj Khand', 21),
(2305, 'Malanpur', 21),
(2306, 'Malhargarh', 21),
(2307, 'Manasa', 21),
(2308, 'Manawar', 21),
(2309, 'Mandav', 21),
(2310, 'Mandideep', 21),
(2311, 'Mandla', 21),
(2312, 'Mandleshwar', 21),
(2313, 'Mandsaur', 21),
(2314, 'Manegaon', 21),
(2315, 'Mangawan', 21),
(2316, 'Manglaya Sadak', 21),
(2317, 'Manpur', 21),
(2318, 'Mau', 21),
(2319, 'Mauganj', 21),
(2320, 'Meghnagar', 21),
(2321, 'Mehara Gaon', 21);
INSERT INTO `municipio` (`id`, `nome`, `provincia_id`) VALUES
(2322, 'Belas', 185),
(2323, 'Cacuaco', 185),
(2324, 'Cazenga', 185),
(2325, 'Ícolo e Bengo', 185),
(2326, 'Kilamba Kiaxi', 185),
(2327, 'Luanda', 185),
(2328, 'Quiçama', 185),
(2329, 'Talatona', 185),
(2330, 'Viana', 185);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pais`
--

CREATE TABLE `pais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sortname` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonecode` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `pais`
--

INSERT INTO `pais` (`id`, `sortname`, `nome`, `phonecode`) VALUES
(1, 'AF', 'Afeganistão', 93),
(2, 'AL', 'Albania', 355),
(3, 'DZ', 'Algeria', 213),
(4, 'AS', 'American Samoa', 1684),
(5, 'AD', 'Andorra', 376),
(6, 'AO', 'Angola', 244),
(7, 'AI', 'Anguilla', 1264),
(8, 'AQ', 'Antarctica', 0),
(9, 'AG', 'Antigua And Barbuda', 1268),
(10, 'AR', 'Argentina', 54),
(11, 'AM', 'Armenia', 374),
(12, 'AW', 'Aruba', 297),
(13, 'AU', 'Australia', 61),
(14, 'AT', 'Austria', 43),
(15, 'AZ', 'Azerbaijan', 994),
(16, 'BS', 'Bahamas The', 1242),
(17, 'BH', 'Bahrain', 973),
(18, 'BD', 'Bangladesh', 880),
(19, 'BB', 'Barbados', 1246),
(20, 'BY', 'Belarus', 375),
(21, 'BE', 'Belgium', 32),
(22, 'BZ', 'Belize', 501),
(23, 'BJ', 'Benin', 229),
(24, 'BM', 'Bermuda', 1441),
(25, 'BT', 'Bhutan', 975),
(26, 'BO', 'Bolivia', 591),
(27, 'BA', 'Bosnia and Herzegovina', 387),
(28, 'BW', 'Botswana', 267),
(29, 'BV', 'Bouvet Island', 0),
(30, 'BR', 'Brazil', 55),
(31, 'IO', 'British Indian Ocean Territory', 246),
(32, 'BN', 'Brunei', 673),
(33, 'BG', 'Bulgaria', 359),
(34, 'BF', 'Burkina Faso', 226),
(35, 'BI', 'Burundi', 257),
(36, 'KH', 'Cambodia', 855),
(37, 'CM', 'Cameroon', 237),
(38, 'CA', 'Canada', 1),
(39, 'CV', 'Cape Verde', 238),
(40, 'KY', 'Cayman Islands', 1345),
(41, 'CF', 'Central African Republic', 236),
(42, 'TD', 'Chad', 235),
(43, 'CL', 'Chile', 56),
(44, 'CN', 'China', 86),
(45, 'CX', 'Christmas Island', 61),
(46, 'CC', 'Cocos (Keeling) Islands', 672),
(47, 'CO', 'Colombia', 57),
(48, 'KM', 'Comoros', 269),
(49, 'CG', 'Republica do Congo', 242),
(50, 'CD', 'Democratic Republic Of The Congo', 242),
(51, 'CK', 'Cook Islands', 682),
(52, 'CR', 'Costa Rica', 506),
(53, 'CI', 'Cote D\'Ivoire (Ivory Coast)', 225),
(54, 'HR', 'Croatia (Hrvatska)', 385),
(55, 'CU', 'Cuba', 53),
(56, 'CY', 'Cyprus', 357),
(57, 'CZ', 'Czech Republic', 420),
(58, 'DK', 'Denmark', 45),
(59, 'DJ', 'Djibouti', 253),
(60, 'DM', 'Dominica', 1767),
(61, 'DO', 'Dominican Republic', 1809),
(62, 'TP', 'East Timor', 670),
(63, 'EC', 'Ecuador', 593),
(64, 'EG', 'Egypt', 20),
(65, 'SV', 'El Salvador', 503),
(66, 'GQ', 'Equatorial Guinea', 240),
(67, 'ER', 'Eritrea', 291),
(68, 'EE', 'Estonia', 372),
(69, 'ET', 'Ethiopia', 251),
(70, 'XA', 'External Territories of Australia', 61),
(71, 'FK', 'Falkland Islands', 500),
(72, 'FO', 'Faroe Islands', 298),
(73, 'FJ', 'Fiji Islands', 679),
(74, 'FI', 'Finland', 358),
(75, 'FR', 'France', 33),
(76, 'GF', 'French Guiana', 594),
(77, 'PF', 'French Polynesia', 689),
(78, 'TF', 'French Southern Territories', 0),
(79, 'GA', 'Gabon', 241),
(80, 'GM', 'Gambia The', 220),
(81, 'GE', 'Georgia', 995),
(82, 'DE', 'Germany', 49),
(83, 'GH', 'Ghana', 233),
(84, 'GI', 'Gibraltar', 350),
(85, 'GR', 'Greece', 30),
(86, 'GL', 'Greenland', 299),
(87, 'GD', 'Grenada', 1473),
(88, 'GP', 'Guadeloupe', 590),
(89, 'GU', 'Guam', 1671),
(90, 'GT', 'Guatemala', 502),
(91, 'XU', 'Guernsey and Alderney', 44),
(92, 'GN', 'Guinea', 224),
(93, 'GW', 'Guinea-Bissau', 245),
(94, 'GY', 'Guyana', 592),
(95, 'HT', 'Haiti', 509),
(96, 'HM', 'Heard and McDonald Islands', 0),
(97, 'HN', 'Honduras', 504),
(98, 'HK', 'Hong Kong S.A.R.', 852),
(99, 'HU', 'Hungary', 36),
(100, 'IS', 'Iceland', 354),
(101, 'IN', 'India', 91),
(102, 'ID', 'Indonesia', 62),
(103, 'IR', 'Iran', 98),
(104, 'IQ', 'Iraq', 964),
(105, 'IE', 'Ireland', 353),
(106, 'IL', 'Israel', 972),
(107, 'IT', 'Italy', 39),
(108, 'JM', 'Jamaica', 1876),
(109, 'JP', 'Japão', 81),
(110, 'XJ', 'Jersey', 44),
(111, 'JO', 'Jordan', 962),
(112, 'KZ', 'Kazakhstan', 7),
(113, 'KE', 'Kenya', 254),
(114, 'KI', 'Kiribati', 686),
(115, 'KP', 'Korea North', 850),
(116, 'KR', 'Korea South', 82),
(117, 'KW', 'Kuwait', 965),
(118, 'KG', 'Kyrgyzstan', 996),
(119, 'LA', 'Laos', 856),
(120, 'LV', 'Latvia', 371),
(121, 'LB', 'Lebanon', 961),
(122, 'LS', 'Lesotho', 266),
(123, 'LR', 'Liberia', 231),
(124, 'LY', 'Libya', 218),
(125, 'LI', 'Liechtenstein', 423),
(126, 'LT', 'Lithuania', 370),
(127, 'LU', 'Luxembourg', 352),
(128, 'MO', 'Macau S.A.R.', 853),
(129, 'MK', 'Macedonia', 389),
(130, 'MG', 'Madagascar', 261),
(131, 'MW', 'Malawi', 265),
(132, 'MY', 'Malaysia', 60),
(133, 'MV', 'Maldives', 960),
(134, 'ML', 'Mali', 223),
(135, 'MT', 'Malta', 356),
(136, 'XM', 'Man (Isle of)', 44),
(137, 'MH', 'Marshall Islands', 692),
(138, 'MQ', 'Martinique', 596),
(139, 'MR', 'Mauritania', 222),
(140, 'MU', 'Mauritius', 230),
(141, 'YT', 'Mayotte', 269),
(142, 'MX', 'Mexico', 52),
(143, 'FM', 'Micronesia', 691),
(144, 'MD', 'Moldova', 373),
(145, 'MC', 'Monaco', 377),
(146, 'MN', 'Mongolia', 976),
(147, 'MS', 'Montserrat', 1664),
(148, 'MA', 'Morocco', 212),
(149, 'MZ', 'Moçambique', 258),
(150, 'MM', 'Myanmar', 95),
(151, 'NA', 'Namibia', 264),
(152, 'NR', 'Nauru', 674),
(153, 'NP', 'Nepal', 977),
(154, 'AN', 'Netherlands Antilles', 599),
(155, 'NL', 'Netherlands The', 31),
(156, 'NC', 'New Caledonia', 687),
(157, 'NZ', 'New Zealand', 64),
(158, 'NI', 'Nicaragua', 505),
(159, 'NE', 'Niger', 227),
(160, 'NG', 'Nigeria', 234),
(161, 'NU', 'Niue', 683),
(162, 'NF', 'Norfolk Island', 672),
(163, 'MP', 'Northern Mariana Islands', 1670),
(164, 'NO', 'Norway', 47),
(165, 'OM', 'Oman', 968),
(166, 'PK', 'Pakistan', 92),
(167, 'PW', 'Palau', 680),
(168, 'PS', 'Palestinian Territory Occupied', 970),
(169, 'PA', 'Panama', 507),
(170, 'PG', 'Papua new Guinea', 675),
(171, 'PY', 'Paraguay', 595),
(172, 'PE', 'Peru', 51),
(173, 'PH', 'Philippines', 63),
(174, 'PN', 'Pitcairn Island', 0),
(175, 'PL', 'Poland', 48),
(176, 'PT', 'Portugal', 351),
(177, 'PR', 'Puerto Rico', 1787),
(178, 'QA', 'Qatar', 974),
(179, 'RE', 'Reunion', 262),
(180, 'RO', 'Romania', 40),
(181, 'RU', 'Russia', 70),
(182, 'RW', 'Rwanda', 250),
(183, 'SH', 'Saint Helena', 290),
(184, 'KN', 'Saint Kitts And Nevis', 1869),
(185, 'LC', 'Saint Lucia', 1758),
(186, 'PM', 'Saint Pierre and Miquelon', 508),
(187, 'VC', 'Saint Vincent And The Grenadines', 1784),
(188, 'WS', 'Samoa', 684),
(189, 'SM', 'San Marino', 378),
(190, 'ST', 'Sao Tome and Principe', 239),
(191, 'SA', 'Saudi Arabia', 966),
(192, 'SN', 'Senegal', 221),
(193, 'RS', 'Serbia', 381),
(194, 'SC', 'Seychelles', 248),
(195, 'SL', 'Sierra Leone', 232),
(196, 'SG', 'Singapore', 65),
(197, 'SK', 'Slovakia', 421),
(198, 'SI', 'Slovenia', 386),
(199, 'XG', 'Smaller Territories of the UK', 44),
(200, 'SB', 'Solomon Islands', 677),
(201, 'SO', 'Somalia', 252),
(202, 'ZA', 'South Africa', 27),
(203, 'GS', 'South Georgia', 0),
(204, 'SS', 'South Sudan', 211),
(205, 'ES', 'Spain', 34),
(206, 'LK', 'Sri Lanka', 94),
(207, 'SD', 'Sudan', 249),
(208, 'SR', 'Suriname', 597),
(209, 'SJ', 'Svalbard And Jan Mayen Islands', 47),
(210, 'SZ', 'Swaziland', 268),
(211, 'SE', 'Sweden', 46),
(212, 'CH', 'Switzerland', 41),
(213, 'SY', 'Syria', 963),
(214, 'TW', 'Taiwan', 886),
(215, 'TJ', 'Tajikistan', 992),
(216, 'TZ', 'Tanzania', 255),
(217, 'TH', 'Thailand', 66),
(218, 'TG', 'Togo', 228),
(219, 'TK', 'Tokelau', 690),
(220, 'TO', 'Tonga', 676),
(221, 'TT', 'Trinidad And Tobago', 1868),
(222, 'TN', 'Tunisia', 216),
(223, 'TR', 'Turkey', 90),
(224, 'TM', 'Turkmenistan', 7370),
(225, 'TC', 'Turks And Caicos Islands', 1649),
(226, 'TV', 'Tuvalu', 688),
(227, 'UG', 'Uganda', 256),
(228, 'UA', 'Ukraine', 380),
(229, 'AE', 'United Arab Emirates', 971),
(230, 'GB', 'United Kingdom', 44),
(231, 'US', 'United States', 1),
(232, 'UM', 'United States Minor Outlying Islands', 1),
(233, 'UY', 'Uruguay', 598),
(234, 'UZ', 'Uzbekistan', 998),
(235, 'VU', 'Vanuatu', 678),
(236, 'VA', 'Vatican City State (Holy See)', 39),
(237, 'VE', 'Venezuela', 58),
(238, 'VN', 'Vietnam', 84),
(239, 'VG', 'Virgin Islands (British)', 1284),
(240, 'VI', 'Virgin Islands (US)', 1340),
(241, 'WF', 'Wallis And Futuna Islands', 681),
(242, 'EH', 'Western Sahara', 212),
(243, 'YE', 'Yemen', 967),
(244, 'YU', 'Yugoslavia', 38),
(245, 'ZM', 'Zambia', 260),
(246, 'ZW', 'Zimbabwe', 263);

-- --------------------------------------------------------

--
-- Estrutura da tabela `parte_contraria`
--

CREATE TABLE `parte_contraria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `processo_id` bigint(20) UNSIGNED NOT NULL,
  `qualidade` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `party_advocate` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `parte_contraria`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `payment_mades`
--

CREATE TABLE `payment_mades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fornecedor_id` bigint(20) UNSIGNED NOT NULL,
  `factura_id` bigint(20) UNSIGNED NOT NULL,
  `valor` double(8,2) NOT NULL DEFAULT 0.00,
  `receiving_date` date DEFAULT NULL,
  `payment_type` enum('Cash','Cheque','Net Banking','Other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash',
  `no_referencia` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_date` datetime DEFAULT NULL,
  `observacao` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_received_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `payment_receiveds`
--

CREATE TABLE `payment_receiveds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `factura_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_number` int(10) UNSIGNED DEFAULT NULL,
  `amount` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiving_date` datetime NOT NULL,
  `payment_type` enum('Cash','Cheque','Net Banking','Other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash',
  `cheque_date` date DEFAULT NULL,
  `reference_number` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_received_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissao`
--

CREATE TABLE `permissao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `permissao`
--

INSERT INTO `permissao` (`id`, `nome`, `descricao`) VALUES
(1, 'dashboard_list', NULL),
(2, 'client_list', NULL),
(3, 'client_add', NULL),
(4, 'client_edit', NULL),
(5, 'client_delete', NULL),
(6, 'task_list', NULL),
(7, 'task_add', NULL),
(8, 'task_edit', NULL),
(9, 'task_delete', NULL),
(10, 'vendor_list', NULL),
(11, 'vendor_add', NULL),
(12, 'vendor_edit', NULL),
(13, 'vendor_delete', NULL),
(14, 'case_list', NULL),
(15, 'case_add', NULL),
(16, 'case_edit', NULL),
(17, 'appointment_list', NULL),
(18, 'appointment_add', NULL),
(19, 'appointment_edit', NULL),
(20, 'expense_type_list', NULL),
(21, 'expense_type_add', NULL),
(22, 'expense_type_edit', NULL),
(23, 'expense_type_delete', NULL),
(24, 'expense_list', NULL),
(25, 'expense_add', NULL),
(26, 'expense_edit', NULL),
(27, 'expense_delete', NULL),
(28, 'invoice_list', NULL),
(29, 'invoice_add', NULL),
(30, 'invoice_edit', NULL),
(31, 'invoice_delete', NULL),
(32, 'case_type_list', NULL),
(33, 'case_type_add', NULL),
(34, 'case_type_edit', NULL),
(35, 'case_type_delete', NULL),
(36, 'court_type_list', NULL),
(37, 'court_type_add', NULL),
(38, 'court_type_edit', NULL),
(39, 'court_type_delete', NULL),
(40, 'court_list', NULL),
(41, 'court_add', NULL),
(42, 'court_edit', NULL),
(43, 'court_delete', NULL),
(44, 'case_status_list', NULL),
(45, 'case_status_add', NULL),
(46, 'case_status_edit', NULL),
(47, 'case_status_delete', NULL),
(48, 'judge_list', NULL),
(49, 'judge_add', NULL),
(50, 'judge_edit', NULL),
(51, 'judge_delete', NULL),
(52, 'tax_list', NULL),
(53, 'tax_add', NULL),
(54, 'tax_edit', NULL),
(55, 'tax_delete', NULL),
(56, 'invoice_setting_edit', NULL),
(57, 'mail_setup_edit', NULL),
(58, 'general_setting_edit', NULL),
(59, 'service_list', NULL),
(60, 'service_add', NULL),
(61, 'service_edit', NULL),
(62, 'service_delete', NULL),
(63, 'listar_seccao', NULL),
(64, 'section_add', NULL),
(65, 'section_edit', NULL),
(66, 'section_delete', NULL),
(67, 'listar_intervdesignacao', NULL),
(68, 'designacao_add', NULL),
(69, 'designacao_edit', NULL),
(70, 'designacao_delete', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa`
--

CREATE TABLE `pessoa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo` bigint(20) UNSIGNED NOT NULL,
  `foto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `pessoa`
--

INSERT INTO `pessoa` (`id`, `tipo`, `foto`, `endereco_id`) VALUES
(1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoacolectiva`
--

CREATE TABLE `pessoacolectiva` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pessoa_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoasingular`
--

CREATE TABLE `pessoasingular` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome_meio` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sobrenome` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` enum('M','F') COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_civil` enum('S','C','D','V') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `nome_pai` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_mae` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pais_id` bigint(20) UNSIGNED DEFAULT NULL,
  `municipio_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pessoa_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `pessoasingular`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa_contacto`
--

CREATE TABLE `pessoa_contacto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pessoa_id` bigint(20) UNSIGNED NOT NULL,
  `contacto_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `processo`
--

CREATE TABLE `processo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `client_position` bigint(20) UNSIGNED NOT NULL,
  `party_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `party_lawyer` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_processo` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `areaprocessual_id` bigint(20) UNSIGNED NOT NULL,
  `tipoprocesso_id` bigint(20) UNSIGNED NOT NULL,
  `estado` bigint(20) UNSIGNED NOT NULL,
  `tribunal_id` bigint(20) UNSIGNED NOT NULL,
  `seccao_id` bigint(20) UNSIGNED NOT NULL,
  `prioridade` enum('Baixa','Média','Alta') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baixa',
  `juiz_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data_registo` datetime NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `processo`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `processo_imposto`
--

CREATE TABLE `processo_imposto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `processo_membro`
--

CREATE TABLE `processo_membro` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `processo_id` bigint(20) UNSIGNED NOT NULL,
  `membro` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `processo_membro`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `profissao`
--

CREATE TABLE `profissao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `provincia`
--

CREATE TABLE `provincia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `provincia`
--

INSERT INTO `provincia` (`id`, `nome`, `pais_id`) VALUES
(1, 'Andaman and Nicobar Islands', 101),
(2, 'Andhra Pradesh', 101),
(3, 'Arunachal Pradesh', 101),
(4, 'Assam', 101),
(5, 'Bihar', 101),
(6, 'Chandigarh', 101),
(7, 'Chhattisgarh', 101),
(8, 'Dadra and Nagar Haveli', 101),
(9, 'Daman and Diu', 101),
(10, 'Delhi', 101),
(11, 'Goa', 101),
(12, 'Gujarat', 101),
(13, 'Haryana', 101),
(14, 'Himachal Pradesh', 101),
(15, 'Jammu and Kashmir', 101),
(16, 'Jharkhand', 101),
(17, 'Karnataka', 101),
(18, 'Kenmore', 101),
(19, 'Kerala', 101),
(20, 'Lakshadweep', 101),
(21, 'Madhya Pradesh', 101),
(22, 'Maharashtra', 101),
(23, 'Manipur', 101),
(24, 'Meghalaya', 101),
(25, 'Mizoram', 101),
(26, 'Nagaland', 101),
(27, 'Narora', 101),
(28, 'Natwar', 101),
(29, 'Odisha', 101),
(30, 'Paschim Medinipur', 101),
(31, 'Pondicherry', 101),
(32, 'Punjab', 101),
(33, 'Rajasthan', 101),
(34, 'Sikkim', 101),
(35, 'Tamil Nadu', 101),
(36, 'Telangana', 101),
(37, 'Tripura', 101),
(38, 'Uttar Pradesh', 101),
(39, 'Uttarakhand', 101),
(40, 'Vaishali', 101),
(41, 'West Bengal', 101),
(42, 'Badakhshan', 1),
(43, 'Badgis', 1),
(44, 'Baglan', 1),
(45, 'Balkh', 1),
(46, 'Bamiyan', 1),
(47, 'Farah', 1),
(48, 'Faryab', 1),
(49, 'Gawr', 1),
(50, 'Gazni', 1),
(51, 'Herat', 1),
(52, 'Hilmand', 1),
(53, 'Jawzjan', 1),
(54, 'Kabul', 1),
(55, 'Kapisa', 1),
(56, 'Khawst', 1),
(57, 'Kunar', 1),
(58, 'Lagman', 1),
(59, 'Lawghar', 1),
(60, 'Nangarhar', 1),
(61, 'Nimruz', 1),
(62, 'Nuristan', 1),
(63, 'Paktika', 1),
(64, 'Paktiya', 1),
(65, 'Parwan', 1),
(66, 'Qandahar', 1),
(67, 'Qunduz', 1),
(68, 'Samangan', 1),
(69, 'Sar-e Pul', 1),
(70, 'Takhar', 1),
(71, 'Uruzgan', 1),
(72, 'Wardag', 1),
(73, 'Zabul', 1),
(74, 'Berat', 2),
(75, 'Bulqize', 2),
(76, 'Delvine', 2),
(77, 'Devoll', 2),
(78, 'Dibre', 2),
(79, 'Durres', 2),
(80, 'Elbasan', 2),
(81, 'Fier', 2),
(82, 'Gjirokaster', 2),
(83, 'Gramsh', 2),
(84, 'Has', 2),
(85, 'Kavaje', 2),
(86, 'Kolonje', 2),
(87, 'Korce', 2),
(88, 'Kruje', 2),
(89, 'Kucove', 2),
(90, 'Kukes', 2),
(91, 'Kurbin', 2),
(92, 'Lezhe', 2),
(93, 'Librazhd', 2),
(94, 'Lushnje', 2),
(95, 'Mallakaster', 2),
(96, 'Malsi e Madhe', 2),
(97, 'Mat', 2),
(98, 'Mirdite', 2),
(99, 'Peqin', 2),
(100, 'Permet', 2),
(101, 'Pogradec', 2),
(102, 'Puke', 2),
(103, 'Sarande', 2),
(104, 'Shkoder', 2),
(105, 'Skrapar', 2),
(106, 'Tepelene', 2),
(107, 'Tirane', 2),
(108, 'Tropoje', 2),
(109, 'Vlore', 2),
(110, '\'Ayn Daflah', 3),
(111, '\'Ayn Tamushanat', 3),
(112, 'Adrar', 3),
(113, 'Algiers', 3),
(114, 'Annabah', 3),
(115, 'Bashshar', 3),
(116, 'Batnah', 3),
(117, 'Bijayah', 3),
(118, 'Biskrah', 3),
(119, 'Blidah', 3),
(120, 'Buirah', 3),
(121, 'Bumardas', 3),
(122, 'Burj Bu Arririj', 3),
(123, 'Ghalizan', 3),
(124, 'Ghardayah', 3),
(125, 'Ilizi', 3),
(126, 'Jijili', 3),
(127, 'Jilfah', 3),
(128, 'Khanshalah', 3),
(129, 'Masilah', 3),
(130, 'Midyah', 3),
(131, 'Milah', 3),
(132, 'Muaskar', 3),
(133, 'Mustaghanam', 3),
(134, 'Naama', 3),
(135, 'Oran', 3),
(136, 'Ouargla', 3),
(137, 'Qalmah', 3),
(138, 'Qustantinah', 3),
(139, 'Sakikdah', 3),
(140, 'Satif', 3),
(141, 'Sayda\'', 3),
(142, 'Sidi ban-al-\'Abbas', 3),
(143, 'Suq Ahras', 3),
(144, 'Tamanghasat', 3),
(145, 'Tibazah', 3),
(146, 'Tibissah', 3),
(147, 'Tilimsan', 3),
(148, 'Tinduf', 3),
(149, 'Tisamsilt', 3),
(150, 'Tiyarat', 3),
(151, 'Tizi Wazu', 3),
(152, 'Umm-al-Bawaghi', 3),
(153, 'Wahran', 3),
(154, 'Warqla', 3),
(155, 'Wilaya d Alger', 3),
(156, 'Wilaya de Bejaia', 3),
(157, 'Wilaya de Constantine', 3),
(158, 'al-Aghwat', 3),
(159, 'al-Bayadh', 3),
(160, 'al-Jaza\'ir', 3),
(161, 'al-Wad', 3),
(162, 'ash-Shalif', 3),
(163, 'at-Tarif', 3),
(164, 'Eastern', 4),
(165, 'Manu\'a', 4),
(166, 'Swains Island', 4),
(167, 'Western', 4),
(168, 'Andorra la Vella', 5),
(169, 'Canillo', 5),
(170, 'Encamp', 5),
(171, 'La Massana', 5),
(172, 'Les Escaldes', 5),
(173, 'Ordino', 5),
(174, 'Sant Julia de Loria', 5),
(175, 'Bengo', 6),
(176, 'Benguela', 6),
(177, 'Bié', 6),
(178, 'Cabinda', 6),
(179, 'Cunene', 6),
(180, 'Huambo', 6),
(181, 'Huila', 6),
(182, 'Kuando-Kubango', 6),
(183, 'Cuanza Norte', 6),
(184, 'Cuanza Sul', 6),
(185, 'Luanda', 6),
(186, 'Lunda Norte', 6),
(187, 'Lunda Sul', 6),
(188, 'Malanje', 6),
(189, 'Moxico', 6),
(190, 'Namibe', 6),
(191, 'Uíge', 6),
(192, 'Zaire', 6),
(193, 'Other Provinces', 7),
(194, 'Sector claimed by Argentina/Ch', 8),
(195, 'Sector claimed by Argentina/UK', 8),
(196, 'Sector claimed by Australia', 8),
(197, 'Sector claimed by France', 8),
(198, 'Sector claimed by New Zealand', 8),
(199, 'Sector claimed by Norway', 8),
(200, 'Unclaimed Sector', 8),
(201, 'Barbuda', 9),
(202, 'Saint George', 9),
(203, 'Saint John', 9),
(204, 'Saint Mary', 9),
(205, 'Saint Paul', 9),
(206, 'Saint Peter', 9),
(207, 'Saint Philip', 9),
(208, 'Buenos Aires', 10),
(209, 'Catamarca', 10),
(210, 'Chaco', 10),
(211, 'Chubut', 10),
(212, 'Cordoba', 10),
(213, 'Corrientes', 10),
(214, 'Distrito Federal', 10),
(215, 'Entre Rios', 10),
(216, 'Formosa', 10),
(217, 'Jujuy', 10),
(218, 'La Pampa', 10),
(219, 'La Rioja', 10),
(220, 'Mendoza', 10),
(221, 'Misiones', 10),
(222, 'Neuquen', 10),
(223, 'Rio Negro', 10),
(224, 'Salta', 10),
(225, 'San Juan', 10),
(226, 'San Luis', 10),
(227, 'Santa Cruz', 10),
(228, 'Santa Fe', 10),
(229, 'Santiago del Estero', 10),
(230, 'Tierra del Fuego', 10),
(231, 'Tucuman', 10),
(232, 'Aragatsotn', 11),
(233, 'Ararat', 11),
(234, 'Armavir', 11),
(235, 'Gegharkunik', 11),
(236, 'Kotaik', 11),
(237, 'Lori', 11),
(238, 'Shirak', 11),
(239, 'Stepanakert', 11),
(240, 'Syunik', 11),
(241, 'Tavush', 11),
(242, 'Vayots Dzor', 11),
(243, 'Yerevan', 11),
(244, 'Aruba', 12),
(245, 'Auckland', 13),
(246, 'Australian Capital Territory', 13),
(247, 'Balgowlah', 13),
(248, 'Balmain', 13),
(249, 'Bankstown', 13),
(250, 'Baulkham Hills', 13),
(251, 'Bonnet Bay', 13),
(252, 'Camberwell', 13),
(253, 'Carole Park', 13),
(254, 'Castle Hill', 13),
(255, 'Caulfield', 13),
(256, 'Chatswood', 13),
(257, 'Cheltenham', 13),
(258, 'Cherrybrook', 13),
(259, 'Clayton', 13),
(260, 'Collingwood', 13),
(261, 'Frenchs Forest', 13),
(262, 'Hawthorn', 13),
(263, 'Jannnali', 13),
(264, 'Knoxfield', 13),
(265, 'Melbourne', 13),
(266, 'New South Wales', 13),
(267, 'Northern Territory', 13),
(268, 'Perth', 13),
(269, 'Queensland', 13),
(270, 'South Australia', 13),
(271, 'Tasmania', 13),
(272, 'Templestowe', 13),
(273, 'Victoria', 13),
(274, 'Werribee south', 13),
(275, 'Western Australia', 13),
(276, 'Wheeler', 13),
(277, 'Bundesland Salzburg', 14),
(278, 'Bundesland Steiermark', 14),
(279, 'Bundesland Tirol', 14),
(280, 'Burgenland', 14),
(281, 'Carinthia', 14),
(282, 'Karnten', 14),
(283, 'Liezen', 14),
(284, 'Lower Austria', 14),
(285, 'Niederosterreich', 14),
(286, 'Oberosterreich', 14),
(287, 'Salzburg', 14),
(288, 'Schleswig-Holstein', 14),
(289, 'Steiermark', 14),
(290, 'Styria', 14),
(291, 'Tirol', 14),
(292, 'Upper Austria', 14),
(293, 'Vorarlberg', 14),
(294, 'Wien', 14),
(295, 'Abseron', 15),
(296, 'Baki Sahari', 15),
(297, 'Ganca', 15),
(298, 'Ganja', 15),
(299, 'Kalbacar', 15),
(300, 'Lankaran', 15),
(301, 'Mil-Qarabax', 15),
(302, 'Mugan-Salyan', 15),
(303, 'Nagorni-Qarabax', 15),
(304, 'Naxcivan', 15),
(305, 'Priaraks', 15),
(306, 'Qazax', 15),
(307, 'Saki', 15),
(308, 'Sirvan', 15),
(309, 'Xacmaz', 15),
(310, 'Abaco', 16),
(311, 'Acklins Island', 16),
(312, 'Andros', 16),
(313, 'Berry Islands', 16),
(314, 'Biminis', 16),
(315, 'Cat Island', 16),
(316, 'Crooked Island', 16),
(317, 'Eleuthera', 16),
(318, 'Exuma and Cays', 16),
(319, 'Grand Bahama', 16),
(320, 'Inagua Islands', 16),
(321, 'Long Island', 16),
(322, 'Mayaguana', 16),
(323, 'New Providence', 16),
(324, 'Ragged Island', 16),
(325, 'Rum Cay', 16),
(326, 'San Salvador', 16),
(327, '\'Isa', 17),
(328, 'Badiyah', 17),
(329, 'Hidd', 17),
(330, 'Jidd Hafs', 17),
(331, 'Mahama', 17),
(332, 'Manama', 17),
(333, 'Sitrah', 17),
(334, 'al-Manamah', 17),
(335, 'al-Muharraq', 17),
(336, 'ar-Rifa\'a', 17),
(337, 'Bagar Hat', 18),
(338, 'Bandarban', 18),
(339, 'Barguna', 18),
(340, 'Barisal', 18),
(341, 'Bhola', 18),
(342, 'Bogora', 18),
(343, 'Brahman Bariya', 18),
(344, 'Chandpur', 18),
(345, 'Chattagam', 18),
(346, 'Chittagong Division', 18),
(347, 'Chuadanga', 18),
(348, 'Dhaka', 18),
(349, 'Dinajpur', 18),
(350, 'Faridpur', 18),
(351, 'Feni', 18),
(352, 'Gaybanda', 18),
(353, 'Gazipur', 18),
(354, 'Gopalganj', 18),
(355, 'Habiganj', 18),
(356, 'Jaipur Hat', 18),
(357, 'Jamalpur', 18),
(358, 'Jessor', 18),
(359, 'Jhalakati', 18),
(360, 'Jhanaydah', 18),
(361, 'Khagrachhari', 18),
(362, 'Khulna', 18),
(363, 'Kishorganj', 18),
(364, 'Koks Bazar', 18),
(365, 'Komilla', 18),
(366, 'Kurigram', 18),
(367, 'Kushtiya', 18),
(368, 'Lakshmipur', 18),
(369, 'Lalmanir Hat', 18),
(370, 'Madaripur', 18),
(371, 'Magura', 18),
(372, 'Maimansingh', 18),
(373, 'Manikganj', 18),
(374, 'Maulvi Bazar', 18),
(375, 'Meherpur', 18),
(376, 'Munshiganj', 18),
(377, 'Naral', 18),
(378, 'Narayanganj', 18),
(379, 'Narsingdi', 18),
(380, 'Nator', 18),
(381, 'Naugaon', 18),
(382, 'Nawabganj', 18),
(383, 'Netrakona', 18),
(384, 'Nilphamari', 18),
(385, 'Noakhali', 18),
(386, 'Pabna', 18),
(387, 'Panchagarh', 18),
(388, 'Patuakhali', 18),
(389, 'Pirojpur', 18),
(390, 'Rajbari', 18),
(391, 'Rajshahi', 18),
(392, 'Rangamati', 18),
(393, 'Rangpur', 18),
(394, 'Satkhira', 18),
(395, 'Shariatpur', 18),
(396, 'Sherpur', 18),
(397, 'Silhat', 18),
(398, 'Sirajganj', 18),
(399, 'Sunamganj', 18),
(400, 'Tangayal', 18),
(401, 'Thakurgaon', 18),
(402, 'Christ Church', 19),
(403, 'Saint Andrew', 19),
(404, 'Saint George', 19),
(405, 'Saint James', 19),
(406, 'Saint John', 19),
(407, 'Saint Joseph', 19),
(408, 'Saint Lucy', 19),
(409, 'Saint Michael', 19),
(410, 'Saint Peter', 19),
(411, 'Saint Philip', 19),
(412, 'Saint Thomas', 19),
(413, 'Brest', 20),
(414, 'Homjel\'', 20),
(415, 'Hrodna', 20),
(416, 'Mahiljow', 20),
(417, 'Mahilyowskaya Voblasts', 20),
(418, 'Minsk', 20),
(419, 'Minskaja Voblasts\'', 20),
(420, 'Petrik', 20),
(421, 'Vicebsk', 20),
(422, 'Antwerpen', 21),
(423, 'Berchem', 21),
(424, 'Brabant', 21),
(425, 'Brabant Wallon', 21),
(426, 'Brussel', 21),
(427, 'East Flanders', 21),
(428, 'Hainaut', 21),
(429, 'Liege', 21),
(430, 'Limburg', 21),
(431, 'Luxembourg', 21),
(432, 'Namur', 21),
(433, 'Ontario', 21),
(434, 'Oost-Vlaanderen', 21),
(435, 'Provincie Brabant', 21),
(436, 'Vlaams-Brabant', 21),
(437, 'Wallonne', 21),
(438, 'West-Vlaanderen', 21),
(439, 'Belize', 22),
(440, 'Cayo', 22),
(441, 'Corozal', 22),
(442, 'Orange Walk', 22),
(443, 'Stann Creek', 22),
(444, 'Toledo', 22),
(445, 'Alibori', 23),
(446, 'Atacora', 23),
(447, 'Atlantique', 23),
(448, 'Borgou', 23),
(449, 'Collines', 23),
(450, 'Couffo', 23),
(451, 'Donga', 23),
(452, 'Littoral', 23),
(453, 'Mono', 23),
(454, 'Oueme', 23),
(455, 'Plateau', 23),
(456, 'Zou', 23),
(457, 'Hamilton', 24),
(458, 'Saint George', 24),
(459, 'Bumthang', 25),
(460, 'Chhukha', 25),
(461, 'Chirang', 25),
(462, 'Daga', 25),
(463, 'Geylegphug', 25),
(464, 'Ha', 25),
(465, 'Lhuntshi', 25),
(466, 'Mongar', 25),
(467, 'Pemagatsel', 25),
(468, 'Punakha', 25),
(469, 'Rinpung', 25),
(470, 'Samchi', 25),
(471, 'Samdrup Jongkhar', 25),
(472, 'Shemgang', 25),
(473, 'Tashigang', 25),
(474, 'Timphu', 25),
(475, 'Tongsa', 25),
(476, 'Wangdiphodrang', 25),
(477, 'Beni', 26),
(478, 'Chuquisaca', 26),
(479, 'Cochabamba', 26),
(480, 'La Paz', 26),
(481, 'Oruro', 26),
(482, 'Pando', 26),
(483, 'Potosi', 26),
(484, 'Santa Cruz', 26),
(485, 'Tarija', 26),
(486, 'Federacija Bosna i Hercegovina', 27),
(487, 'Republika Srpska', 27),
(488, 'Central Bobonong', 28),
(489, 'Central Boteti', 28),
(490, 'Central Mahalapye', 28),
(491, 'Central Serowe-Palapye', 28),
(492, 'Central Tutume', 28),
(493, 'Chobe', 28),
(494, 'Francistown', 28),
(495, 'Gaborone', 28),
(496, 'Ghanzi', 28),
(497, 'Jwaneng', 28),
(498, 'Kgalagadi North', 28),
(499, 'Kgalagadi South', 28),
(500, 'Kgatleng', 28),
(501, 'Kweneng', 28),
(502, 'Lobatse', 28),
(503, 'Ngamiland', 28),
(504, 'Ngwaketse', 28),
(505, 'North East', 28),
(506, 'Okavango', 28),
(507, 'Orapa', 28),
(508, 'Selibe Phikwe', 28),
(509, 'South East', 28),
(510, 'Sowa', 28),
(511, 'Bouvet Island', 29),
(512, 'Acre', 30),
(513, 'Alagoas', 30),
(514, 'Amapa', 30),
(515, 'Amazonas', 30),
(516, 'Bahia', 30),
(517, 'Ceara', 30),
(518, 'Distrito Federal', 30),
(519, 'Espirito Santo', 30),
(520, 'Estado de Sao Paulo', 30),
(521, 'Goias', 30),
(522, 'Maranhao', 30),
(523, 'Mato Grosso', 30),
(524, 'Mato Grosso do Sul', 30),
(525, 'Minas Gerais', 30),
(526, 'Para', 30),
(527, 'Paraiba', 30),
(528, 'Parana', 30),
(529, 'Pernambuco', 30),
(530, 'Piaui', 30),
(531, 'Rio Grande do Norte', 30),
(532, 'Rio Grande do Sul', 30),
(533, 'Rio de Janeiro', 30),
(534, 'Rondonia', 30),
(535, 'Roraima', 30),
(536, 'Santa Catarina', 30),
(537, 'Sao Paulo', 30),
(538, 'Sergipe', 30),
(539, 'Tocantins', 30),
(540, 'British Indian Ocean Territory', 31),
(541, 'Belait', 32),
(542, 'Brunei-Muara', 32),
(543, 'Temburong', 32),
(544, 'Tutong', 32),
(545, 'Blagoevgrad', 33),
(546, 'Burgas', 33),
(547, 'Dobrich', 33),
(548, 'Gabrovo', 33),
(549, 'Haskovo', 33),
(550, 'Jambol', 33),
(551, 'Kardzhali', 33),
(552, 'Kjustendil', 33),
(553, 'Lovech', 33),
(554, 'Montana', 33),
(555, 'Oblast Sofiya-Grad', 33),
(556, 'Pazardzhik', 33),
(557, 'Pernik', 33),
(558, 'Pleven', 33),
(559, 'Plovdiv', 33),
(560, 'Razgrad', 33),
(561, 'Ruse', 33),
(562, 'Shumen', 33),
(563, 'Silistra', 33),
(564, 'Sliven', 33),
(565, 'Smoljan', 33),
(566, 'Sofija grad', 33),
(567, 'Sofijska oblast', 33),
(568, 'Stara Zagora', 33),
(569, 'Targovishte', 33),
(570, 'Varna', 33),
(571, 'Veliko Tarnovo', 33),
(572, 'Vidin', 33),
(573, 'Vraca', 33),
(574, 'Yablaniza', 33),
(575, 'Bale', 34),
(576, 'Bam', 34),
(577, 'Bazega', 34),
(578, 'Bougouriba', 34),
(579, 'Boulgou', 34),
(580, 'Boulkiemde', 34),
(581, 'Comoe', 34),
(582, 'Ganzourgou', 34),
(583, 'Gnagna', 34),
(584, 'Gourma', 34),
(585, 'Houet', 34),
(586, 'Ioba', 34),
(587, 'Kadiogo', 34),
(588, 'Kenedougou', 34),
(589, 'Komandjari', 34),
(590, 'Kompienga', 34),
(591, 'Kossi', 34),
(592, 'Kouritenga', 34),
(593, 'Kourweogo', 34),
(594, 'Leraba', 34),
(595, 'Mouhoun', 34),
(596, 'Nahouri', 34),
(597, 'Namentenga', 34),
(598, 'Noumbiel', 34),
(599, 'Oubritenga', 34),
(600, 'Oudalan', 34),
(601, 'Passore', 34),
(602, 'Poni', 34),
(603, 'Sanguie', 34),
(604, 'Sanmatenga', 34),
(605, 'Seno', 34),
(606, 'Sissili', 34),
(607, 'Soum', 34),
(608, 'Sourou', 34),
(609, 'Tapoa', 34),
(610, 'Tuy', 34),
(611, 'Yatenga', 34),
(612, 'Zondoma', 34),
(613, 'Zoundweogo', 34),
(614, 'Bubanza', 35),
(615, 'Bujumbura', 35),
(616, 'Bururi', 35),
(617, 'Cankuzo', 35),
(618, 'Cibitoke', 35),
(619, 'Gitega', 35),
(620, 'Karuzi', 35),
(621, 'Kayanza', 35),
(622, 'Kirundo', 35),
(623, 'Makamba', 35),
(624, 'Muramvya', 35),
(625, 'Muyinga', 35),
(626, 'Ngozi', 35),
(627, 'Rutana', 35),
(628, 'Ruyigi', 35),
(629, 'Banteay Mean Chey', 36),
(630, 'Bat Dambang', 36),
(631, 'Kampong Cham', 36),
(632, 'Kampong Chhnang', 36),
(633, 'Kampong Spoeu', 36),
(634, 'Kampong Thum', 36),
(635, 'Kampot', 36),
(636, 'Kandal', 36),
(637, 'Kaoh Kong', 36),
(638, 'Kracheh', 36),
(639, 'Krong Kaeb', 36),
(640, 'Krong Pailin', 36),
(641, 'Krong Preah Sihanouk', 36),
(642, 'Mondol Kiri', 36),
(643, 'Otdar Mean Chey', 36),
(644, 'Phnum Penh', 36),
(645, 'Pousat', 36),
(646, 'Preah Vihear', 36),
(647, 'Prey Veaeng', 36),
(648, 'Rotanak Kiri', 36),
(649, 'Siem Reab', 36),
(650, 'Stueng Traeng', 36),
(651, 'Svay Rieng', 36),
(652, 'Takaev', 36),
(653, 'Adamaoua', 37),
(654, 'Centre', 37),
(655, 'Est', 37),
(656, 'Littoral', 37),
(657, 'Nord', 37),
(658, 'Nord Extreme', 37),
(659, 'Nordouest', 37),
(660, 'Ouest', 37),
(661, 'Sud', 37),
(662, 'Sudouest', 37),
(663, 'Alberta', 38),
(664, 'British Columbia', 38),
(665, 'Manitoba', 38),
(666, 'New Brunswick', 38),
(667, 'Newfoundland and Labrador', 38),
(668, 'Northwest Territories', 38),
(669, 'Nova Scotia', 38),
(670, 'Nunavut', 38),
(671, 'Ontario', 38),
(672, 'Prince Edward Island', 38),
(673, 'Quebec', 38),
(674, 'Saskatchewan', 38),
(675, 'Yukon', 38),
(676, 'Boavista', 39),
(677, 'Brava', 39),
(678, 'Fogo', 39),
(679, 'Maio', 39),
(680, 'Sal', 39),
(681, 'Santo Antao', 39),
(682, 'Sao Nicolau', 39),
(683, 'Sao Tiago', 39),
(684, 'Sao Vicente', 39),
(685, 'Grand Cayman', 40),
(686, 'Bamingui-Bangoran', 41),
(687, 'Bangui', 41),
(688, 'Basse-Kotto', 41),
(689, 'Haut-Mbomou', 41),
(690, 'Haute-Kotto', 41),
(691, 'Kemo', 41),
(692, 'Lobaye', 41),
(693, 'Mambere-Kadei', 41),
(694, 'Mbomou', 41),
(695, 'Nana-Gribizi', 41),
(696, 'Nana-Mambere', 41),
(697, 'Ombella Mpoko', 41),
(698, 'Ouaka', 41),
(699, 'Ouham', 41),
(700, 'Ouham-Pende', 41),
(701, 'Sangha-Mbaere', 41),
(702, 'Vakaga', 41),
(703, 'Batha', 42),
(704, 'Biltine', 42),
(705, 'Bourkou-Ennedi-Tibesti', 42),
(706, 'Chari-Baguirmi', 42),
(707, 'Guera', 42),
(708, 'Kanem', 42),
(709, 'Lac', 42),
(710, 'Logone Occidental', 42),
(711, 'Logone Oriental', 42),
(712, 'Mayo-Kebbi', 42),
(713, 'Moyen-Chari', 42),
(714, 'Ouaddai', 42),
(715, 'Salamat', 42),
(716, 'Tandjile', 42),
(717, 'Aisen', 43),
(718, 'Antofagasta', 43),
(719, 'Araucania', 43),
(720, 'Atacama', 43),
(721, 'Bio Bio', 43),
(722, 'Coquimbo', 43),
(723, 'Libertador General Bernardo O\'', 43),
(724, 'Los Lagos', 43),
(725, 'Magellanes', 43),
(726, 'Maule', 43),
(727, 'Metropolitana', 43),
(728, 'Metropolitana de Santiago', 43),
(729, 'Tarapaca', 43),
(730, 'Valparaiso', 43),
(731, 'Anhui', 44),
(732, 'Anhui Province', 44),
(733, 'Anhui Sheng', 44),
(734, 'Aomen', 44),
(735, 'Beijing', 44),
(736, 'Beijing Shi', 44),
(737, 'Chongqing', 44),
(738, 'Fujian', 44),
(739, 'Fujian Sheng', 44),
(740, 'Gansu', 44),
(741, 'Guangdong', 44),
(742, 'Guangdong Sheng', 44),
(743, 'Guangxi', 44),
(744, 'Guizhou', 44),
(745, 'Hainan', 44),
(746, 'Hebei', 44),
(747, 'Heilongjiang', 44),
(748, 'Henan', 44),
(749, 'Hubei', 44),
(750, 'Hunan', 44),
(751, 'Jiangsu', 44),
(752, 'Jiangsu Sheng', 44),
(753, 'Jiangxi', 44),
(754, 'Jilin', 44),
(755, 'Liaoning', 44),
(756, 'Liaoning Sheng', 44),
(757, 'Nei Monggol', 44),
(758, 'Ningxia Hui', 44),
(759, 'Qinghai', 44),
(760, 'Shaanxi', 44),
(761, 'Shandong', 44),
(762, 'Shandong Sheng', 44),
(763, 'Shanghai', 44),
(764, 'Shanxi', 44),
(765, 'Sichuan', 44),
(766, 'Tianjin', 44),
(767, 'Xianggang', 44),
(768, 'Xinjiang', 44),
(769, 'Xizang', 44),
(770, 'Yunnan', 44),
(771, 'Zhejiang', 44),
(772, 'Zhejiang Sheng', 44),
(773, 'Christmas Island', 45),
(774, 'Cocos (Keeling) Islands', 46),
(775, 'Amazonas', 47),
(776, 'Antioquia', 47),
(777, 'Arauca', 47),
(778, 'Atlantico', 47),
(779, 'Bogota', 47),
(780, 'Bolivar', 47),
(781, 'Boyaca', 47),
(782, 'Caldas', 47),
(783, 'Caqueta', 47),
(784, 'Casanare', 47),
(785, 'Cauca', 47),
(786, 'Cesar', 47),
(787, 'Choco', 47),
(788, 'Cordoba', 47),
(789, 'Cundinamarca', 47),
(790, 'Guainia', 47),
(791, 'Guaviare', 47),
(792, 'Huila', 47),
(793, 'La Guajira', 47),
(794, 'Magdalena', 47),
(795, 'Meta', 47),
(796, 'Narino', 47),
(797, 'Norte de Santander', 47),
(798, 'Putumayo', 47),
(799, 'Quindio', 47),
(800, 'Risaralda', 47),
(801, 'San Andres y Providencia', 47),
(802, 'Santander', 47),
(803, 'Sucre', 47),
(804, 'Tolima', 47),
(805, 'Valle del Cauca', 47),
(806, 'Vaupes', 47),
(807, 'Vichada', 47),
(808, 'Mwali', 48),
(809, 'Njazidja', 48),
(810, 'Nzwani', 48),
(811, 'Bouenza', 49),
(812, 'Brazzaville', 49),
(813, 'Cuvette', 49),
(814, 'Kouilou', 49),
(815, 'Lekoumou', 49),
(816, 'Likouala', 49),
(817, 'Niari', 49),
(818, 'Plateaux', 49),
(819, 'Pool', 49),
(820, 'Sangha', 49),
(821, 'Bandundu', 50),
(822, 'Bas-Congo', 50),
(823, 'Equateur', 50),
(824, 'Haut-Congo', 50),
(825, 'Kasai-Occidental', 50),
(826, 'Kasai-Oriental', 50),
(827, 'Katanga', 50),
(828, 'Kinshasa', 50),
(829, 'Maniema', 50),
(830, 'Nord-Kivu', 50),
(831, 'Sud-Kivu', 50),
(832, 'Aitutaki', 51),
(833, 'Atiu', 51),
(834, 'Mangaia', 51),
(835, 'Manihiki', 51),
(836, 'Mauke', 51),
(837, 'Mitiaro', 51),
(838, 'Nassau', 51),
(839, 'Pukapuka', 51),
(840, 'Rakahanga', 51),
(841, 'Rarotonga', 51),
(842, 'Tongareva', 51),
(843, 'Alajuela', 52),
(844, 'Cartago', 52),
(845, 'Guanacaste', 52),
(846, 'Heredia', 52),
(847, 'Limon', 52),
(848, 'Puntarenas', 52),
(849, 'San Jose', 52),
(850, 'Abidjan', 53),
(851, 'Agneby', 53),
(852, 'Bafing', 53),
(853, 'Denguele', 53),
(854, 'Dix-huit Montagnes', 53),
(855, 'Fromager', 53),
(856, 'Haut-Sassandra', 53),
(857, 'Lacs', 53),
(858, 'Lagunes', 53),
(859, 'Marahoue', 53),
(860, 'Moyen-Cavally', 53),
(861, 'Moyen-Comoe', 53),
(862, 'N\'zi-Comoe', 53),
(863, 'Sassandra', 53),
(864, 'Savanes', 53),
(865, 'Sud-Bandama', 53),
(866, 'Sud-Comoe', 53),
(867, 'Vallee du Bandama', 53),
(868, 'Worodougou', 53),
(869, 'Zanzan', 53),
(870, 'Bjelovar-Bilogora', 54),
(871, 'Dubrovnik-Neretva', 54),
(872, 'Grad Zagreb', 54),
(873, 'Istra', 54),
(874, 'Karlovac', 54),
(875, 'Koprivnica-Krizhevci', 54),
(876, 'Krapina-Zagorje', 54),
(877, 'Lika-Senj', 54),
(878, 'Medhimurje', 54),
(879, 'Medimurska Zupanija', 54),
(880, 'Osijek-Baranja', 54),
(881, 'Osjecko-Baranjska Zupanija', 54),
(882, 'Pozhega-Slavonija', 54),
(883, 'Primorje-Gorski Kotar', 54),
(884, 'Shibenik-Knin', 54),
(885, 'Sisak-Moslavina', 54),
(886, 'Slavonski Brod-Posavina', 54),
(887, 'Split-Dalmacija', 54),
(888, 'Varazhdin', 54),
(889, 'Virovitica-Podravina', 54),
(890, 'Vukovar-Srijem', 54),
(891, 'Zadar', 54),
(892, 'Zagreb', 54),
(893, 'Camaguey', 55),
(894, 'Ciego de Avila', 55),
(895, 'Cienfuegos', 55),
(896, 'Ciudad de la Habana', 55),
(897, 'Granma', 55),
(898, 'Guantanamo', 55),
(899, 'Habana', 55),
(900, 'Holguin', 55),
(901, 'Isla de la Juventud', 55),
(902, 'La Habana', 55),
(903, 'Las Tunas', 55),
(904, 'Matanzas', 55),
(905, 'Pinar del Rio', 55),
(906, 'Sancti Spiritus', 55),
(907, 'Santiago de Cuba', 55),
(908, 'Villa Clara', 55),
(909, 'Government controlled area', 56),
(910, 'Limassol', 56),
(911, 'Nicosia District', 56),
(912, 'Paphos', 56),
(913, 'Turkish controlled area', 56),
(914, 'Central Bohemian', 57),
(915, 'Frycovice', 57),
(916, 'Jihocesky Kraj', 57),
(917, 'Jihochesky', 57),
(918, 'Jihomoravsky', 57),
(919, 'Karlovarsky', 57),
(920, 'Klecany', 57),
(921, 'Kralovehradecky', 57),
(922, 'Liberecky', 57),
(923, 'Lipov', 57),
(924, 'Moravskoslezsky', 57),
(925, 'Olomoucky', 57),
(926, 'Olomoucky Kraj', 57),
(927, 'Pardubicky', 57),
(928, 'Plzensky', 57),
(929, 'Praha', 57),
(930, 'Rajhrad', 57),
(931, 'Smirice', 57),
(932, 'South Moravian', 57),
(933, 'Straz nad Nisou', 57),
(934, 'Stredochesky', 57),
(935, 'Unicov', 57),
(936, 'Ustecky', 57),
(937, 'Valletta', 57),
(938, 'Velesin', 57),
(939, 'Vysochina', 57),
(940, 'Zlinsky', 57),
(941, 'Arhus', 58),
(942, 'Bornholm', 58),
(943, 'Frederiksborg', 58),
(944, 'Fyn', 58),
(945, 'Hovedstaden', 58),
(946, 'Kobenhavn', 58),
(947, 'Kobenhavns Amt', 58),
(948, 'Kobenhavns Kommune', 58),
(949, 'Nordjylland', 58),
(950, 'Ribe', 58),
(951, 'Ringkobing', 58),
(952, 'Roervig', 58),
(953, 'Roskilde', 58),
(954, 'Roslev', 58),
(955, 'Sjaelland', 58),
(956, 'Soeborg', 58),
(957, 'Sonderjylland', 58),
(958, 'Storstrom', 58),
(959, 'Syddanmark', 58),
(960, 'Toelloese', 58),
(961, 'Vejle', 58),
(962, 'Vestsjalland', 58),
(963, 'Viborg', 58),
(964, '\'Ali Sabih', 59),
(965, 'Dikhil', 59),
(966, 'Jibuti', 59),
(967, 'Tajurah', 59),
(968, 'Ubuk', 59),
(969, 'Saint Andrew', 60),
(970, 'Saint David', 60),
(971, 'Saint George', 60),
(972, 'Saint John', 60),
(973, 'Saint Joseph', 60),
(974, 'Saint Luke', 60),
(975, 'Saint Mark', 60),
(976, 'Saint Patrick', 60),
(977, 'Saint Paul', 60),
(978, 'Saint Peter', 60),
(979, 'Azua', 61),
(980, 'Bahoruco', 61),
(981, 'Barahona', 61),
(982, 'Dajabon', 61),
(983, 'Distrito Nacional', 61),
(984, 'Duarte', 61),
(985, 'El Seybo', 61),
(986, 'Elias Pina', 61),
(987, 'Espaillat', 61),
(988, 'Hato Mayor', 61),
(989, 'Independencia', 61),
(990, 'La Altagracia', 61),
(991, 'La Romana', 61),
(992, 'La Vega', 61),
(993, 'Maria Trinidad Sanchez', 61),
(994, 'Monsenor Nouel', 61),
(995, 'Monte Cristi', 61),
(996, 'Monte Plata', 61),
(997, 'Pedernales', 61),
(998, 'Peravia', 61),
(999, 'Puerto Plata', 61),
(1000, 'Salcedo', 61),
(1001, 'Samana', 61),
(1002, 'San Cristobal', 61),
(1003, 'San Juan', 61),
(1004, 'San Pedro de Macoris', 61),
(1005, 'Sanchez Ramirez', 61),
(1006, 'Santiago', 61),
(1007, 'Santiago Rodriguez', 61),
(1008, 'Valverde', 61),
(1009, 'Aileu', 62),
(1010, 'Ainaro', 62),
(1011, 'Ambeno', 62),
(1012, 'Baucau', 62),
(1013, 'Bobonaro', 62),
(1014, 'Cova Lima', 62),
(1015, 'Dili', 62),
(1016, 'Ermera', 62),
(1017, 'Lautem', 62),
(1018, 'Liquica', 62),
(1019, 'Manatuto', 62),
(1020, 'Manufahi', 62),
(1021, 'Viqueque', 62),
(1022, 'Azuay', 63),
(1023, 'Bolivar', 63),
(1024, 'Canar', 63),
(1025, 'Carchi', 63),
(1026, 'Chimborazo', 63),
(1027, 'Cotopaxi', 63),
(1028, 'El Oro', 63),
(1029, 'Esmeraldas', 63),
(1030, 'Galapagos', 63),
(1031, 'Guayas', 63),
(1032, 'Imbabura', 63),
(1033, 'Loja', 63),
(1034, 'Los Rios', 63),
(1035, 'Manabi', 63),
(1036, 'Morona Santiago', 63),
(1037, 'Napo', 63),
(1038, 'Orellana', 63),
(1039, 'Pastaza', 63),
(1040, 'Pichincha', 63),
(1041, 'Sucumbios', 63),
(1042, 'Tungurahua', 63),
(1043, 'Zamora Chinchipe', 63),
(1044, 'Aswan', 64),
(1045, 'Asyut', 64),
(1046, 'Bani Suwayf', 64),
(1047, 'Bur Sa\'id', 64),
(1048, 'Cairo', 64),
(1049, 'Dumyat', 64),
(1050, 'Kafr-ash-Shaykh', 64),
(1051, 'Matruh', 64),
(1052, 'Muhafazat ad Daqahliyah', 64),
(1053, 'Muhafazat al Fayyum', 64),
(1054, 'Muhafazat al Gharbiyah', 64),
(1055, 'Muhafazat al Iskandariyah', 64),
(1056, 'Muhafazat al Qahirah', 64),
(1057, 'Qina', 64),
(1058, 'Sawhaj', 64),
(1059, 'Sina al-Janubiyah', 64),
(1060, 'Sina ash-Shamaliyah', 64),
(1061, 'ad-Daqahliyah', 64),
(1062, 'al-Bahr-al-Ahmar', 64),
(1063, 'al-Buhayrah', 64),
(1064, 'al-Fayyum', 64),
(1065, 'al-Gharbiyah', 64),
(1066, 'al-Iskandariyah', 64),
(1067, 'al-Ismailiyah', 64),
(1068, 'al-Jizah', 64),
(1069, 'al-Minufiyah', 64),
(1070, 'al-Minya', 64),
(1071, 'al-Qahira', 64),
(1072, 'al-Qalyubiyah', 64),
(1073, 'al-Uqsur', 64),
(1074, 'al-Wadi al-Jadid', 64),
(1075, 'as-Suways', 64),
(1076, 'ash-Sharqiyah', 64),
(1077, 'Ahuachapan', 65),
(1078, 'Cabanas', 65),
(1079, 'Chalatenango', 65),
(1080, 'Cuscatlan', 65),
(1081, 'La Libertad', 65),
(1082, 'La Paz', 65),
(1083, 'La Union', 65),
(1084, 'Morazan', 65),
(1085, 'San Miguel', 65),
(1086, 'San Salvador', 65),
(1087, 'San Vicente', 65),
(1088, 'Santa Ana', 65),
(1089, 'Sonsonate', 65),
(1090, 'Usulutan', 65),
(1091, 'Annobon', 66),
(1092, 'Bioko Norte', 66),
(1093, 'Bioko Sur', 66),
(1094, 'Centro Sur', 66),
(1095, 'Kie-Ntem', 66),
(1096, 'Litoral', 66),
(1097, 'Wele-Nzas', 66),
(1098, 'Anseba', 67),
(1099, 'Debub', 67),
(1100, 'Debub-Keih-Bahri', 67),
(1101, 'Gash-Barka', 67),
(1102, 'Maekel', 67),
(1103, 'Semien-Keih-Bahri', 67),
(1104, 'Harju', 68),
(1105, 'Hiiu', 68),
(1106, 'Ida-Viru', 68),
(1107, 'Jarva', 68),
(1108, 'Jogeva', 68),
(1109, 'Laane', 68),
(1110, 'Laane-Viru', 68),
(1111, 'Parnu', 68),
(1112, 'Polva', 68),
(1113, 'Rapla', 68),
(1114, 'Saare', 68),
(1115, 'Tartu', 68),
(1116, 'Valga', 68),
(1117, 'Viljandi', 68),
(1118, 'Voru', 68),
(1119, 'Addis Abeba', 69),
(1120, 'Afar', 69),
(1121, 'Amhara', 69),
(1122, 'Benishangul', 69),
(1123, 'Diredawa', 69),
(1124, 'Gambella', 69),
(1125, 'Harar', 69),
(1126, 'Jigjiga', 69),
(1127, 'Mekele', 69),
(1128, 'Oromia', 69),
(1129, 'Somali', 69),
(1130, 'Southern', 69),
(1131, 'Tigray', 69),
(1132, 'Christmas Island', 70),
(1133, 'Cocos Islands', 70),
(1134, 'Coral Sea Islands', 70),
(1135, 'Falkland Islands', 71),
(1136, 'South Georgia', 71),
(1137, 'Klaksvik', 72),
(1138, 'Nor ara Eysturoy', 72),
(1139, 'Nor oy', 72),
(1140, 'Sandoy', 72),
(1141, 'Streymoy', 72),
(1142, 'Su uroy', 72),
(1143, 'Sy ra Eysturoy', 72),
(1144, 'Torshavn', 72),
(1145, 'Vaga', 72),
(1146, 'Central', 73),
(1147, 'Eastern', 73),
(1148, 'Northern', 73),
(1149, 'South Pacific', 73),
(1150, 'Western', 73),
(1151, 'Ahvenanmaa', 74),
(1152, 'Etela-Karjala', 74),
(1153, 'Etela-Pohjanmaa', 74),
(1154, 'Etela-Savo', 74),
(1155, 'Etela-Suomen Laani', 74),
(1156, 'Ita-Suomen Laani', 74),
(1157, 'Ita-Uusimaa', 74),
(1158, 'Kainuu', 74),
(1159, 'Kanta-Hame', 74),
(1160, 'Keski-Pohjanmaa', 74),
(1161, 'Keski-Suomi', 74),
(1162, 'Kymenlaakso', 74),
(1163, 'Lansi-Suomen Laani', 74),
(1164, 'Lappi', 74),
(1165, 'Northern Savonia', 74),
(1166, 'Ostrobothnia', 74),
(1167, 'Oulun Laani', 74),
(1168, 'Paijat-Hame', 74),
(1169, 'Pirkanmaa', 74),
(1170, 'Pohjanmaa', 74),
(1171, 'Pohjois-Karjala', 74),
(1172, 'Pohjois-Pohjanmaa', 74),
(1173, 'Pohjois-Savo', 74),
(1174, 'Saarijarvi', 74),
(1175, 'Satakunta', 74),
(1176, 'Southern Savonia', 74),
(1177, 'Tavastia Proper', 74),
(1178, 'Uleaborgs Lan', 74),
(1179, 'Uusimaa', 74),
(1180, 'Varsinais-Suomi', 74),
(1181, 'Ain', 75),
(1182, 'Aisne', 75),
(1183, 'Albi Le Sequestre', 75),
(1184, 'Allier', 75),
(1185, 'Alpes-Cote dAzur', 75),
(1186, 'Alpes-Maritimes', 75),
(1187, 'Alpes-de-Haute-Provence', 75),
(1188, 'Alsace', 75),
(1189, 'Aquitaine', 75),
(1190, 'Ardeche', 75),
(1191, 'Ardennes', 75),
(1192, 'Ariege', 75),
(1193, 'Aube', 75),
(1194, 'Aude', 75),
(1195, 'Auvergne', 75),
(1196, 'Aveyron', 75),
(1197, 'Bas-Rhin', 75),
(1198, 'Basse-Normandie', 75),
(1199, 'Bouches-du-Rhone', 75),
(1200, 'Bourgogne', 75),
(1201, 'Bretagne', 75),
(1202, 'Brittany', 75),
(1203, 'Burgundy', 75),
(1204, 'Calvados', 75),
(1205, 'Cantal', 75),
(1206, 'Cedex', 75),
(1207, 'Centre', 75),
(1208, 'Charente', 75),
(1209, 'Charente-Maritime', 75),
(1210, 'Cher', 75),
(1211, 'Correze', 75),
(1212, 'Corse-du-Sud', 75),
(1213, 'Cote-d\'Or', 75),
(1214, 'Cotes-d\'Armor', 75),
(1215, 'Creuse', 75),
(1216, 'Crolles', 75),
(1217, 'Deux-Sevres', 75),
(1218, 'Dordogne', 75),
(1219, 'Doubs', 75),
(1220, 'Drome', 75),
(1221, 'Essonne', 75),
(1222, 'Eure', 75),
(1223, 'Eure-et-Loir', 75),
(1224, 'Feucherolles', 75),
(1225, 'Finistere', 75),
(1226, 'Franche-Comte', 75),
(1227, 'Gard', 75),
(1228, 'Gers', 75),
(1229, 'Gironde', 75),
(1230, 'Haut-Rhin', 75),
(1231, 'Haute-Corse', 75),
(1232, 'Haute-Garonne', 75),
(1233, 'Haute-Loire', 75),
(1234, 'Haute-Marne', 75),
(1235, 'Haute-Saone', 75),
(1236, 'Haute-Savoie', 75),
(1237, 'Haute-Vienne', 75),
(1238, 'Hautes-Alpes', 75),
(1239, 'Hautes-Pyrenees', 75),
(1240, 'Hauts-de-Seine', 75),
(1241, 'Herault', 75),
(1242, 'Ile-de-France', 75),
(1243, 'Ille-et-Vilaine', 75),
(1244, 'Indre', 75),
(1245, 'Indre-et-Loire', 75),
(1246, 'Isere', 75),
(1247, 'Jura', 75),
(1248, 'Klagenfurt', 75),
(1249, 'Landes', 75),
(1250, 'Languedoc-Roussillon', 75),
(1251, 'Larcay', 75),
(1252, 'Le Castellet', 75),
(1253, 'Le Creusot', 75),
(1254, 'Limousin', 75),
(1255, 'Loir-et-Cher', 75),
(1256, 'Loire', 75),
(1257, 'Loire-Atlantique', 75),
(1258, 'Loiret', 75),
(1259, 'Lorraine', 75),
(1260, 'Lot', 75),
(1261, 'Lot-et-Garonne', 75),
(1262, 'Lower Normandy', 75),
(1263, 'Lozere', 75),
(1264, 'Maine-et-Loire', 75),
(1265, 'Manche', 75),
(1266, 'Marne', 75),
(1267, 'Mayenne', 75),
(1268, 'Meurthe-et-Moselle', 75),
(1269, 'Meuse', 75),
(1270, 'Midi-Pyrenees', 75),
(1271, 'Morbihan', 75),
(1272, 'Moselle', 75),
(1273, 'Nievre', 75),
(1274, 'Nord', 75),
(1275, 'Nord-Pas-de-Calais', 75),
(1276, 'Oise', 75),
(1277, 'Orne', 75),
(1278, 'Paris', 75),
(1279, 'Pas-de-Calais', 75),
(1280, 'Pays de la Loire', 75),
(1281, 'Pays-de-la-Loire', 75),
(1282, 'Picardy', 75),
(1283, 'Puy-de-Dome', 75),
(1284, 'Pyrenees-Atlantiques', 75),
(1285, 'Pyrenees-Orientales', 75),
(1286, 'Quelmes', 75),
(1287, 'Rhone', 75),
(1288, 'Rhone-Alpes', 75),
(1289, 'Saint Ouen', 75),
(1290, 'Saint Viatre', 75),
(1291, 'Saone-et-Loire', 75),
(1292, 'Sarthe', 75),
(1293, 'Savoie', 75),
(1294, 'Seine-Maritime', 75),
(1295, 'Seine-Saint-Denis', 75),
(1296, 'Seine-et-Marne', 75),
(1297, 'Somme', 75),
(1298, 'Sophia Antipolis', 75),
(1299, 'Souvans', 75),
(1300, 'Tarn', 75),
(1301, 'Tarn-et-Garonne', 75),
(1302, 'Territoire de Belfort', 75),
(1303, 'Treignac', 75),
(1304, 'Upper Normandy', 75),
(1305, 'Val-d\'Oise', 75),
(1306, 'Val-de-Marne', 75),
(1307, 'Var', 75),
(1308, 'Vaucluse', 75),
(1309, 'Vellise', 75),
(1310, 'Vendee', 75),
(1311, 'Vienne', 75),
(1312, 'Vosges', 75),
(1313, 'Yonne', 75),
(1314, 'Yvelines', 75),
(1315, 'Cayenne', 76),
(1316, 'Saint-Laurent-du-Maroni', 76),
(1317, 'Iles du Vent', 77),
(1318, 'Iles sous le Vent', 77),
(1319, 'Marquesas', 77),
(1320, 'Tuamotu', 77),
(1321, 'Tubuai', 77),
(1322, 'Amsterdam', 78),
(1323, 'Crozet Islands', 78),
(1324, 'Kerguelen', 78),
(1325, 'Estuaire', 79),
(1326, 'Haut-Ogooue', 79),
(1327, 'Moyen-Ogooue', 79),
(1328, 'Ngounie', 79),
(1329, 'Nyanga', 79),
(1330, 'Ogooue-Ivindo', 79),
(1331, 'Ogooue-Lolo', 79),
(1332, 'Ogooue-Maritime', 79),
(1333, 'Woleu-Ntem', 79),
(1334, 'Banjul', 80),
(1335, 'Basse', 80),
(1336, 'Brikama', 80),
(1337, 'Janjanbureh', 80),
(1338, 'Kanifing', 80),
(1339, 'Kerewan', 80),
(1340, 'Kuntaur', 80),
(1341, 'Mansakonko', 80),
(1342, 'Abhasia', 81),
(1343, 'Ajaria', 81),
(1344, 'Guria', 81),
(1345, 'Imereti', 81),
(1346, 'Kaheti', 81),
(1347, 'Kvemo Kartli', 81),
(1348, 'Mcheta-Mtianeti', 81),
(1349, 'Racha', 81),
(1350, 'Samagrelo-Zemo Svaneti', 81),
(1351, 'Samche-Zhavaheti', 81),
(1352, 'Shida Kartli', 81),
(1353, 'Tbilisi', 81),
(1354, 'Auvergne', 82),
(1355, 'Baden-Wurttemberg', 82),
(1356, 'Bavaria', 82),
(1357, 'Bayern', 82),
(1358, 'Beilstein Wurtt', 82),
(1359, 'Berlin', 82),
(1360, 'Brandenburg', 82),
(1361, 'Bremen', 82),
(1362, 'Dreisbach', 82),
(1363, 'Freistaat Bayern', 82),
(1364, 'Hamburg', 82),
(1365, 'Hannover', 82),
(1366, 'Heroldstatt', 82),
(1367, 'Hessen', 82),
(1368, 'Kortenberg', 82),
(1369, 'Laasdorf', 82),
(1370, 'Land Baden-Wurttemberg', 82),
(1371, 'Land Bayern', 82),
(1372, 'Land Brandenburg', 82),
(1373, 'Land Hessen', 82),
(1374, 'Land Mecklenburg-Vorpommern', 82),
(1375, 'Land Nordrhein-Westfalen', 82),
(1376, 'Land Rheinland-Pfalz', 82),
(1377, 'Land Sachsen', 82),
(1378, 'Land Sachsen-Anhalt', 82),
(1379, 'Land Thuringen', 82),
(1380, 'Lower Saxony', 82),
(1381, 'Mecklenburg-Vorpommern', 82),
(1382, 'Mulfingen', 82),
(1383, 'Munich', 82),
(1384, 'Neubeuern', 82),
(1385, 'Niedersachsen', 82),
(1386, 'Noord-Holland', 82),
(1387, 'Nordrhein-Westfalen', 82),
(1388, 'North Rhine-Westphalia', 82),
(1389, 'Osterode', 82),
(1390, 'Rheinland-Pfalz', 82),
(1391, 'Rhineland-Palatinate', 82),
(1392, 'Saarland', 82),
(1393, 'Sachsen', 82),
(1394, 'Sachsen-Anhalt', 82),
(1395, 'Saxony', 82),
(1396, 'Schleswig-Holstein', 82),
(1397, 'Thuringia', 82),
(1398, 'Webling', 82),
(1399, 'Weinstrabe', 82),
(1400, 'schlobborn', 82),
(1401, 'Ashanti', 83),
(1402, 'Brong-Ahafo', 83),
(1403, 'Central', 83),
(1404, 'Eastern', 83),
(1405, 'Greater Accra', 83),
(1406, 'Northern', 83),
(1407, 'Upper East', 83),
(1408, 'Upper West', 83),
(1409, 'Volta', 83),
(1410, 'Western', 83),
(1411, 'Gibraltar', 84),
(1412, 'Acharnes', 85),
(1413, 'Ahaia', 85),
(1414, 'Aitolia kai Akarnania', 85),
(1415, 'Argolis', 85),
(1416, 'Arkadia', 85),
(1417, 'Arta', 85),
(1418, 'Attica', 85),
(1419, 'Attiki', 85),
(1420, 'Ayion Oros', 85),
(1421, 'Crete', 85),
(1422, 'Dodekanisos', 85),
(1423, 'Drama', 85),
(1424, 'Evia', 85),
(1425, 'Evritania', 85),
(1426, 'Evros', 85),
(1427, 'Evvoia', 85),
(1428, 'Florina', 85),
(1429, 'Fokis', 85),
(1430, 'Fthiotis', 85),
(1431, 'Grevena', 85),
(1432, 'Halandri', 85),
(1433, 'Halkidiki', 85),
(1434, 'Hania', 85),
(1435, 'Heraklion', 85),
(1436, 'Hios', 85),
(1437, 'Ilia', 85),
(1438, 'Imathia', 85),
(1439, 'Ioannina', 85),
(1440, 'Iraklion', 85),
(1441, 'Karditsa', 85),
(1442, 'Kastoria', 85),
(1443, 'Kavala', 85),
(1444, 'Kefallinia', 85),
(1445, 'Kerkira', 85),
(1446, 'Kiklades', 85),
(1447, 'Kilkis', 85),
(1448, 'Korinthia', 85),
(1449, 'Kozani', 85),
(1450, 'Lakonia', 85),
(1451, 'Larisa', 85),
(1452, 'Lasithi', 85),
(1453, 'Lesvos', 85),
(1454, 'Levkas', 85),
(1455, 'Magnisia', 85),
(1456, 'Messinia', 85),
(1457, 'Nomos Attikis', 85),
(1458, 'Nomos Zakynthou', 85),
(1459, 'Pella', 85),
(1460, 'Pieria', 85),
(1461, 'Piraios', 85),
(1462, 'Preveza', 85),
(1463, 'Rethimni', 85),
(1464, 'Rodopi', 85),
(1465, 'Samos', 85),
(1466, 'Serrai', 85),
(1467, 'Thesprotia', 85),
(1468, 'Thessaloniki', 85),
(1469, 'Trikala', 85),
(1470, 'Voiotia', 85),
(1471, 'West Greece', 85),
(1472, 'Xanthi', 85),
(1473, 'Zakinthos', 85),
(1474, 'Aasiaat', 86),
(1475, 'Ammassalik', 86),
(1476, 'Illoqqortoormiut', 86),
(1477, 'Ilulissat', 86),
(1478, 'Ivittuut', 86),
(1479, 'Kangaatsiaq', 86),
(1480, 'Maniitsoq', 86),
(1481, 'Nanortalik', 86),
(1482, 'Narsaq', 86),
(1483, 'Nuuk', 86),
(1484, 'Paamiut', 86),
(1485, 'Qaanaaq', 86),
(1486, 'Qaqortoq', 86),
(1487, 'Qasigiannguit', 86),
(1488, 'Qeqertarsuaq', 86),
(1489, 'Sisimiut', 86),
(1490, 'Udenfor kommunal inddeling', 86),
(1491, 'Upernavik', 86),
(1492, 'Uummannaq', 86),
(1493, 'Carriacou-Petite Martinique', 87),
(1494, 'Saint Andrew', 87),
(1495, 'Saint Davids', 87),
(1496, 'Saint George\'s', 87),
(1497, 'Saint John', 87),
(1498, 'Saint Mark', 87),
(1499, 'Saint Patrick', 87),
(1500, 'Basse-Terre', 88),
(1501, 'Grande-Terre', 88),
(1502, 'Iles des Saintes', 88),
(1503, 'La Desirade', 88),
(1504, 'Marie-Galante', 88),
(1505, 'Saint Barthelemy', 88),
(1506, 'Saint Martin', 88),
(1507, 'Agana Heights', 89),
(1508, 'Agat', 89),
(1509, 'Barrigada', 89),
(1510, 'Chalan-Pago-Ordot', 89),
(1511, 'Dededo', 89),
(1512, 'Hagatna', 89),
(1513, 'Inarajan', 89),
(1514, 'Mangilao', 89),
(1515, 'Merizo', 89),
(1516, 'Mongmong-Toto-Maite', 89),
(1517, 'Santa Rita', 89),
(1518, 'Sinajana', 89),
(1519, 'Talofofo', 89),
(1520, 'Tamuning', 89),
(1521, 'Yigo', 89),
(1522, 'Yona', 89),
(1523, 'Alta Verapaz', 90),
(1524, 'Baja Verapaz', 90),
(1525, 'Chimaltenango', 90),
(1526, 'Chiquimula', 90),
(1527, 'El Progreso', 90),
(1528, 'Escuintla', 90),
(1529, 'Guatemala', 90),
(1530, 'Huehuetenango', 90),
(1531, 'Izabal', 90),
(1532, 'Jalapa', 90),
(1533, 'Jutiapa', 90),
(1534, 'Peten', 90),
(1535, 'Quezaltenango', 90),
(1536, 'Quiche', 90),
(1537, 'Retalhuleu', 90),
(1538, 'Sacatepequez', 90),
(1539, 'San Marcos', 90),
(1540, 'Santa Rosa', 90),
(1541, 'Solola', 90),
(1542, 'Suchitepequez', 90),
(1543, 'Totonicapan', 90),
(1544, 'Zacapa', 90),
(1545, 'Alderney', 91),
(1546, 'Castel', 91),
(1547, 'Forest', 91),
(1548, 'Saint Andrew', 91),
(1549, 'Saint Martin', 91),
(1550, 'Saint Peter Port', 91),
(1551, 'Saint Pierre du Bois', 91),
(1552, 'Saint Sampson', 91),
(1553, 'Saint Saviour', 91),
(1554, 'Sark', 91),
(1555, 'Torteval', 91),
(1556, 'Vale', 91),
(1557, 'Beyla', 92),
(1558, 'Boffa', 92),
(1559, 'Boke', 92),
(1560, 'Conakry', 92),
(1561, 'Coyah', 92),
(1562, 'Dabola', 92),
(1563, 'Dalaba', 92),
(1564, 'Dinguiraye', 92),
(1565, 'Faranah', 92),
(1566, 'Forecariah', 92),
(1567, 'Fria', 92),
(1568, 'Gaoual', 92),
(1569, 'Gueckedou', 92),
(1570, 'Kankan', 92),
(1571, 'Kerouane', 92),
(1572, 'Kindia', 92),
(1573, 'Kissidougou', 92),
(1574, 'Koubia', 92),
(1575, 'Koundara', 92),
(1576, 'Kouroussa', 92),
(1577, 'Labe', 92),
(1578, 'Lola', 92),
(1579, 'Macenta', 92),
(1580, 'Mali', 92),
(1581, 'Mamou', 92),
(1582, 'Mandiana', 92),
(1583, 'Nzerekore', 92),
(1584, 'Pita', 92),
(1585, 'Siguiri', 92),
(1586, 'Telimele', 92),
(1587, 'Tougue', 92),
(1588, 'Yomou', 92),
(1589, 'Bafata', 93),
(1590, 'Bissau', 93),
(1591, 'Bolama', 93),
(1592, 'Cacheu', 93),
(1593, 'Gabu', 93),
(1594, 'Oio', 93),
(1595, 'Quinara', 93),
(1596, 'Tombali', 93),
(1597, 'Barima-Waini', 94),
(1598, 'Cuyuni-Mazaruni', 94),
(1599, 'Demerara-Mahaica', 94),
(1600, 'East Berbice-Corentyne', 94),
(1601, 'Essequibo Islands-West Demerar', 94),
(1602, 'Mahaica-Berbice', 94),
(1603, 'Pomeroon-Supenaam', 94),
(1604, 'Potaro-Siparuni', 94),
(1605, 'Upper Demerara-Berbice', 94),
(1606, 'Upper Takutu-Upper Essequibo', 94),
(1607, 'Artibonite', 95),
(1608, 'Centre', 95),
(1609, 'Grand\'Anse', 95),
(1610, 'Nord', 95),
(1611, 'Nord-Est', 95),
(1612, 'Nord-Ouest', 95),
(1613, 'Ouest', 95),
(1614, 'Sud', 95),
(1615, 'Sud-Est', 95),
(1616, 'Heard and McDonald Islands', 96),
(1617, 'Atlantida', 97),
(1618, 'Choluteca', 97),
(1619, 'Colon', 97),
(1620, 'Comayagua', 97),
(1621, 'Copan', 97),
(1622, 'Cortes', 97),
(1623, 'Distrito Central', 97),
(1624, 'El Paraiso', 97),
(1625, 'Francisco Morazan', 97),
(1626, 'Gracias a Dios', 97),
(1627, 'Intibuca', 97),
(1628, 'Islas de la Bahia', 97),
(1629, 'La Paz', 97),
(1630, 'Lempira', 97),
(1631, 'Ocotepeque', 97),
(1632, 'Olancho', 97),
(1633, 'Santa Barbara', 97),
(1634, 'Valle', 97),
(1635, 'Yoro', 97),
(1636, 'Hong Kong', 98),
(1637, 'Bacs-Kiskun', 99),
(1638, 'Baranya', 99),
(1639, 'Bekes', 99),
(1640, 'Borsod-Abauj-Zemplen', 99),
(1641, 'Budapest', 99),
(1642, 'Csongrad', 99),
(1643, 'Fejer', 99),
(1644, 'Gyor-Moson-Sopron', 99),
(1645, 'Hajdu-Bihar', 99),
(1646, 'Heves', 99),
(1647, 'Jasz-Nagykun-Szolnok', 99),
(1648, 'Komarom-Esztergom', 99),
(1649, 'Nograd', 99),
(1650, 'Pest', 99),
(1651, 'Somogy', 99),
(1652, 'Szabolcs-Szatmar-Bereg', 99),
(1653, 'Tolna', 99),
(1654, 'Vas', 99),
(1655, 'Veszprem', 99),
(1656, 'Zala', 99),
(1657, 'Austurland', 100),
(1658, 'Gullbringusysla', 100),
(1659, 'Hofu borgarsva i', 100),
(1660, 'Nor urland eystra', 100),
(1661, 'Nor urland vestra', 100),
(1662, 'Su urland', 100),
(1663, 'Su urnes', 100),
(1664, 'Vestfir ir', 100),
(1665, 'Vesturland', 100),
(1666, 'Aceh', 102),
(1667, 'Bali', 102),
(1668, 'Bangka-Belitung', 102),
(1669, 'Banten', 102),
(1670, 'Bengkulu', 102),
(1671, 'Gandaria', 102),
(1672, 'Gorontalo', 102),
(1673, 'Jakarta', 102),
(1674, 'Jambi', 102),
(1675, 'Jawa Barat', 102),
(1676, 'Jawa Tengah', 102),
(1677, 'Jawa Timur', 102),
(1678, 'Kalimantan Barat', 102),
(1679, 'Kalimantan Selatan', 102),
(1680, 'Kalimantan Tengah', 102),
(1681, 'Kalimantan Timur', 102),
(1682, 'Kendal', 102),
(1683, 'Lampung', 102),
(1684, 'Maluku', 102),
(1685, 'Maluku Utara', 102),
(1686, 'Nusa Tenggara Barat', 102),
(1687, 'Nusa Tenggara Timur', 102),
(1688, 'Papua', 102),
(1689, 'Riau', 102),
(1690, 'Riau Kepulauan', 102),
(1691, 'Solo', 102),
(1692, 'Sulawesi Selatan', 102),
(1693, 'Sulawesi Tengah', 102),
(1694, 'Sulawesi Tenggara', 102),
(1695, 'Sulawesi Utara', 102),
(1696, 'Sumatera Barat', 102),
(1697, 'Sumatera Selatan', 102),
(1698, 'Sumatera Utara', 102),
(1699, 'Yogyakarta', 102),
(1700, 'Ardabil', 103),
(1701, 'Azarbayjan-e Bakhtari', 103),
(1702, 'Azarbayjan-e Khavari', 103),
(1703, 'Bushehr', 103),
(1704, 'Chahar Mahal-e Bakhtiari', 103),
(1705, 'Esfahan', 103),
(1706, 'Fars', 103),
(1707, 'Gilan', 103),
(1708, 'Golestan', 103),
(1709, 'Hamadan', 103),
(1710, 'Hormozgan', 103),
(1711, 'Ilam', 103),
(1712, 'Kerman', 103),
(1713, 'Kermanshah', 103),
(1714, 'Khorasan', 103),
(1715, 'Khuzestan', 103),
(1716, 'Kohgiluyeh-e Boyerahmad', 103),
(1717, 'Kordestan', 103),
(1718, 'Lorestan', 103),
(1719, 'Markazi', 103),
(1720, 'Mazandaran', 103),
(1721, 'Ostan-e Esfahan', 103),
(1722, 'Qazvin', 103),
(1723, 'Qom', 103),
(1724, 'Semnan', 103),
(1725, 'Sistan-e Baluchestan', 103),
(1726, 'Tehran', 103),
(1727, 'Yazd', 103),
(1728, 'Zanjan', 103),
(1729, 'Babil', 104),
(1730, 'Baghdad', 104),
(1731, 'Dahuk', 104),
(1732, 'Dhi Qar', 104),
(1733, 'Diyala', 104),
(1734, 'Erbil', 104),
(1735, 'Irbil', 104),
(1736, 'Karbala', 104),
(1737, 'Kurdistan', 104),
(1738, 'Maysan', 104),
(1739, 'Ninawa', 104),
(1740, 'Salah-ad-Din', 104),
(1741, 'Wasit', 104),
(1742, 'al-Anbar', 104),
(1743, 'al-Basrah', 104),
(1744, 'al-Muthanna', 104),
(1745, 'al-Qadisiyah', 104),
(1746, 'an-Najaf', 104),
(1747, 'as-Sulaymaniyah', 104),
(1748, 'at-Ta\'mim', 104),
(1749, 'Armagh', 105),
(1750, 'Carlow', 105),
(1751, 'Cavan', 105),
(1752, 'Clare', 105),
(1753, 'Cork', 105),
(1754, 'Donegal', 105),
(1755, 'Dublin', 105),
(1756, 'Galway', 105),
(1757, 'Kerry', 105),
(1758, 'Kildare', 105),
(1759, 'Kilkenny', 105),
(1760, 'Laois', 105),
(1761, 'Leinster', 105),
(1762, 'Leitrim', 105),
(1763, 'Limerick', 105),
(1764, 'Loch Garman', 105),
(1765, 'Longford', 105),
(1766, 'Louth', 105),
(1767, 'Mayo', 105),
(1768, 'Meath', 105),
(1769, 'Monaghan', 105),
(1770, 'Offaly', 105),
(1771, 'Roscommon', 105),
(1772, 'Sligo', 105),
(1773, 'Tipperary North Riding', 105),
(1774, 'Tipperary South Riding', 105),
(1775, 'Ulster', 105),
(1776, 'Waterford', 105),
(1777, 'Westmeath', 105),
(1778, 'Wexford', 105),
(1779, 'Wicklow', 105),
(1780, 'Beit Hanania', 106),
(1781, 'Ben Gurion Airport', 106),
(1782, 'Bethlehem', 106),
(1783, 'Caesarea', 106),
(1784, 'Centre', 106),
(1785, 'Gaza', 106),
(1786, 'Hadaron', 106),
(1787, 'Haifa District', 106),
(1788, 'Hamerkaz', 106),
(1789, 'Hazafon', 106),
(1790, 'Hebron', 106),
(1791, 'Jaffa', 106),
(1792, 'Jerusalem', 106),
(1793, 'Khefa', 106),
(1794, 'Kiryat Yam', 106),
(1795, 'Lower Galilee', 106),
(1796, 'Qalqilya', 106),
(1797, 'Talme Elazar', 106),
(1798, 'Tel Aviv', 106),
(1799, 'Tsafon', 106),
(1800, 'Umm El Fahem', 106),
(1801, 'Yerushalayim', 106),
(1802, 'Abruzzi', 107),
(1803, 'Abruzzo', 107),
(1804, 'Agrigento', 107),
(1805, 'Alessandria', 107),
(1806, 'Ancona', 107),
(1807, 'Arezzo', 107),
(1808, 'Ascoli Piceno', 107),
(1809, 'Asti', 107),
(1810, 'Avellino', 107),
(1811, 'Bari', 107),
(1812, 'Basilicata', 107),
(1813, 'Belluno', 107),
(1814, 'Benevento', 107),
(1815, 'Bergamo', 107),
(1816, 'Biella', 107),
(1817, 'Bologna', 107),
(1818, 'Bolzano', 107),
(1819, 'Brescia', 107),
(1820, 'Brindisi', 107),
(1821, 'Calabria', 107),
(1822, 'Campania', 107),
(1823, 'Cartoceto', 107),
(1824, 'Caserta', 107),
(1825, 'Catania', 107),
(1826, 'Chieti', 107),
(1827, 'Como', 107),
(1828, 'Cosenza', 107),
(1829, 'Cremona', 107),
(1830, 'Cuneo', 107),
(1831, 'Emilia-Romagna', 107),
(1832, 'Ferrara', 107),
(1833, 'Firenze', 107),
(1834, 'Florence', 107),
(1835, 'Forli-Cesena ', 107),
(1836, 'Friuli-Venezia Giulia', 107),
(1837, 'Frosinone', 107),
(1838, 'Genoa', 107),
(1839, 'Gorizia', 107),
(1840, 'L\'Aquila', 107),
(1841, 'Lazio', 107),
(1842, 'Lecce', 107),
(1843, 'Lecco', 107),
(1844, 'Lecco Province', 107),
(1845, 'Liguria', 107),
(1846, 'Lodi', 107),
(1847, 'Lombardia', 107),
(1848, 'Lombardy', 107),
(1849, 'Macerata', 107),
(1850, 'Mantova', 107),
(1851, 'Marche', 107),
(1852, 'Messina', 107),
(1853, 'Milan', 107),
(1854, 'Modena', 107),
(1855, 'Molise', 107),
(1856, 'Molteno', 107),
(1857, 'Montenegro', 107),
(1858, 'Monza and Brianza', 107),
(1859, 'Naples', 107),
(1860, 'Novara', 107),
(1861, 'Padova', 107),
(1862, 'Parma', 107),
(1863, 'Pavia', 107),
(1864, 'Perugia', 107),
(1865, 'Pesaro-Urbino', 107),
(1866, 'Piacenza', 107),
(1867, 'Piedmont', 107),
(1868, 'Piemonte', 107),
(1869, 'Pisa', 107),
(1870, 'Pordenone', 107),
(1871, 'Potenza', 107),
(1872, 'Puglia', 107),
(1873, 'Reggio Emilia', 107),
(1874, 'Rimini', 107),
(1875, 'Roma', 107),
(1876, 'Salerno', 107),
(1877, 'Sardegna', 107),
(1878, 'Sassari', 107),
(1879, 'Savona', 107),
(1880, 'Sicilia', 107),
(1881, 'Siena', 107),
(1882, 'Sondrio', 107),
(1883, 'South Tyrol', 107),
(1884, 'Taranto', 107),
(1885, 'Teramo', 107),
(1886, 'Torino', 107),
(1887, 'Toscana', 107),
(1888, 'Trapani', 107),
(1889, 'Trentino-Alto Adige', 107),
(1890, 'Trento', 107),
(1891, 'Treviso', 107),
(1892, 'Udine', 107),
(1893, 'Umbria', 107),
(1894, 'Valle d\'Aosta', 107),
(1895, 'Varese', 107),
(1896, 'Veneto', 107),
(1897, 'Venezia', 107),
(1898, 'Verbano-Cusio-Ossola', 107),
(1899, 'Vercelli', 107),
(1900, 'Verona', 107),
(1901, 'Vicenza', 107),
(1902, 'Viterbo', 107),
(1903, 'Buxoro Viloyati', 108),
(1904, 'Clarendon', 108),
(1905, 'Hanover', 108),
(1906, 'Kingston', 108),
(1907, 'Manchester', 108),
(1908, 'Portland', 108),
(1909, 'Saint Andrews', 108),
(1910, 'Saint Ann', 108),
(1911, 'Saint Catherine', 108),
(1912, 'Saint Elizabeth', 108),
(1913, 'Saint James', 108),
(1914, 'Saint Mary', 108),
(1915, 'Saint Thomas', 108),
(1916, 'Trelawney', 108),
(1917, 'Westmoreland', 108),
(1918, 'Aichi', 109),
(1919, 'Akita', 109),
(1920, 'Aomori', 109),
(1921, 'Chiba', 109),
(1922, 'Ehime', 109),
(1923, 'Fukui', 109),
(1924, 'Fukuoka', 109),
(1925, 'Fukushima', 109),
(1926, 'Gifu', 109),
(1927, 'Gumma', 109),
(1928, 'Hiroshima', 109),
(1929, 'Hokkaido', 109),
(1930, 'Hyogo', 109),
(1931, 'Ibaraki', 109),
(1932, 'Ishikawa', 109),
(1933, 'Iwate', 109),
(1934, 'Kagawa', 109),
(1935, 'Kagoshima', 109),
(1936, 'Kanagawa', 109),
(1937, 'Kanto', 109),
(1938, 'Kochi', 109),
(1939, 'Kumamoto', 109),
(1940, 'Kyoto', 109),
(1941, 'Mie', 109),
(1942, 'Miyagi', 109),
(1943, 'Miyazaki', 109),
(1944, 'Nagano', 109),
(1945, 'Nagasaki', 109),
(1946, 'Nara', 109),
(1947, 'Niigata', 109),
(1948, 'Oita', 109),
(1949, 'Okayama', 109),
(1950, 'Okinawa', 109),
(1951, 'Osaka', 109),
(1952, 'Saga', 109),
(1953, 'Saitama', 109),
(1954, 'Shiga', 109),
(1955, 'Shimane', 109),
(1956, 'Shizuoka', 109),
(1957, 'Tochigi', 109),
(1958, 'Tokushima', 109),
(1959, 'Tokyo', 109),
(1960, 'Tottori', 109),
(1961, 'Toyama', 109),
(1962, 'Wakayama', 109),
(1963, 'Yamagata', 109),
(1964, 'Yamaguchi', 109),
(1965, 'Yamanashi', 109),
(1966, 'Grouville', 110),
(1967, 'Saint Brelade', 110),
(1968, 'Saint Clement', 110),
(1969, 'Saint Helier', 110),
(1970, 'Saint John', 110),
(1971, 'Saint Lawrence', 110),
(1972, 'Saint Martin', 110),
(1973, 'Saint Mary', 110),
(1974, 'Saint Peter', 110),
(1975, 'Saint Saviour', 110),
(1976, 'Trinity', 110),
(1977, '\'Ajlun', 111),
(1978, 'Amman', 111),
(1979, 'Irbid', 111),
(1980, 'Jarash', 111),
(1981, 'Ma\'an', 111),
(1982, 'Madaba', 111),
(1983, 'al-\'Aqabah', 111),
(1984, 'al-Balqa\'', 111),
(1985, 'al-Karak', 111),
(1986, 'al-Mafraq', 111),
(1987, 'at-Tafilah', 111),
(1988, 'az-Zarqa\'', 111),
(1989, 'Akmecet', 112),
(1990, 'Akmola', 112),
(1991, 'Aktobe', 112),
(1992, 'Almati', 112),
(1993, 'Atirau', 112),
(1994, 'Batis Kazakstan', 112),
(1995, 'Burlinsky Region', 112),
(1996, 'Karagandi', 112),
(1997, 'Kostanay', 112),
(1998, 'Mankistau', 112),
(1999, 'Ontustik Kazakstan', 112),
(2000, 'Pavlodar', 112),
(2001, 'Sigis Kazakstan', 112),
(2002, 'Soltustik Kazakstan', 112),
(2003, 'Taraz', 112),
(2004, 'Central', 113),
(2005, 'Coast', 113),
(2006, 'Eastern', 113),
(2007, 'Nairobi', 113),
(2008, 'North Eastern', 113),
(2009, 'Nyanza', 113),
(2010, 'Rift Valley', 113),
(2011, 'Western', 113),
(2012, 'Abaiang', 114),
(2013, 'Abemana', 114),
(2014, 'Aranuka', 114),
(2015, 'Arorae', 114),
(2016, 'Banaba', 114),
(2017, 'Beru', 114),
(2018, 'Butaritari', 114),
(2019, 'Kiritimati', 114),
(2020, 'Kuria', 114),
(2021, 'Maiana', 114),
(2022, 'Makin', 114),
(2023, 'Marakei', 114),
(2024, 'Nikunau', 114),
(2025, 'Nonouti', 114),
(2026, 'Onotoa', 114),
(2027, 'Phoenix Islands', 114),
(2028, 'Tabiteuea North', 114),
(2029, 'Tabiteuea South', 114),
(2030, 'Tabuaeran', 114),
(2031, 'Tamana', 114),
(2032, 'Tarawa North', 114),
(2033, 'Tarawa South', 114),
(2034, 'Teraina', 114),
(2035, 'Chagangdo', 115),
(2036, 'Hamgyeongbukto', 115),
(2037, 'Hamgyeongnamdo', 115),
(2038, 'Hwanghaebukto', 115),
(2039, 'Hwanghaenamdo', 115),
(2040, 'Kaeseong', 115),
(2041, 'Kangweon', 115),
(2042, 'Nampo', 115),
(2043, 'Pyeonganbukto', 115),
(2044, 'Pyeongannamdo', 115),
(2045, 'Pyeongyang', 115),
(2046, 'Yanggang', 115),
(2047, 'Busan', 116),
(2048, 'Cheju', 116),
(2049, 'Chollabuk', 116),
(2050, 'Chollanam', 116),
(2051, 'Chungbuk', 116),
(2052, 'Chungcheongbuk', 116),
(2053, 'Chungcheongnam', 116),
(2054, 'Chungnam', 116),
(2055, 'Daegu', 116),
(2056, 'Gangwon-do', 116),
(2057, 'Goyang-si', 116),
(2058, 'Gyeonggi-do', 116),
(2059, 'Gyeongsang ', 116),
(2060, 'Gyeongsangnam-do', 116),
(2061, 'Incheon', 116),
(2062, 'Jeju-Si', 116),
(2063, 'Jeonbuk', 116),
(2064, 'Kangweon', 116),
(2065, 'Kwangju', 116),
(2066, 'Kyeonggi', 116),
(2067, 'Kyeongsangbuk', 116),
(2068, 'Kyeongsangnam', 116),
(2069, 'Kyonggi-do', 116),
(2070, 'Kyungbuk-Do', 116),
(2071, 'Kyunggi-Do', 116),
(2072, 'Kyunggi-do', 116),
(2073, 'Pusan', 116),
(2074, 'Seoul', 116),
(2075, 'Sudogwon', 116),
(2076, 'Taegu', 116),
(2077, 'Taejeon', 116),
(2078, 'Taejon-gwangyoksi', 116),
(2079, 'Ulsan', 116),
(2080, 'Wonju', 116),
(2081, 'gwangyoksi', 116),
(2082, 'Al Asimah', 117),
(2083, 'Hawalli', 117),
(2084, 'Mishref', 117),
(2085, 'Qadesiya', 117),
(2086, 'Safat', 117),
(2087, 'Salmiya', 117),
(2088, 'al-Ahmadi', 117),
(2089, 'al-Farwaniyah', 117),
(2090, 'al-Jahra', 117),
(2091, 'al-Kuwayt', 117),
(2092, 'Batken', 118),
(2093, 'Bishkek', 118),
(2094, 'Chui', 118),
(2095, 'Issyk-Kul', 118),
(2096, 'Jalal-Abad', 118),
(2097, 'Naryn', 118),
(2098, 'Osh', 118),
(2099, 'Talas', 118),
(2100, 'Attopu', 119),
(2101, 'Bokeo', 119),
(2102, 'Bolikhamsay', 119),
(2103, 'Champasak', 119),
(2104, 'Houaphanh', 119),
(2105, 'Khammouane', 119),
(2106, 'Luang Nam Tha', 119),
(2107, 'Luang Prabang', 119),
(2108, 'Oudomxay', 119),
(2109, 'Phongsaly', 119),
(2110, 'Saravan', 119),
(2111, 'Savannakhet', 119),
(2112, 'Sekong', 119),
(2113, 'Viangchan Prefecture', 119),
(2114, 'Viangchan Province', 119),
(2115, 'Xaignabury', 119),
(2116, 'Xiang Khuang', 119),
(2117, 'Aizkraukles', 120),
(2118, 'Aluksnes', 120),
(2119, 'Balvu', 120),
(2120, 'Bauskas', 120),
(2121, 'Cesu', 120),
(2122, 'Daugavpils', 120),
(2123, 'Daugavpils City', 120),
(2124, 'Dobeles', 120),
(2125, 'Gulbenes', 120),
(2126, 'Jekabspils', 120),
(2127, 'Jelgava', 120),
(2128, 'Jelgavas', 120),
(2129, 'Jurmala City', 120),
(2130, 'Kraslavas', 120),
(2131, 'Kuldigas', 120),
(2132, 'Liepaja', 120),
(2133, 'Liepajas', 120),
(2134, 'Limbazhu', 120),
(2135, 'Ludzas', 120),
(2136, 'Madonas', 120),
(2137, 'Ogres', 120),
(2138, 'Preilu', 120),
(2139, 'Rezekne', 120),
(2140, 'Rezeknes', 120),
(2141, 'Riga', 120),
(2142, 'Rigas', 120),
(2143, 'Saldus', 120),
(2144, 'Talsu', 120),
(2145, 'Tukuma', 120),
(2146, 'Valkas', 120),
(2147, 'Valmieras', 120),
(2148, 'Ventspils', 120),
(2149, 'Ventspils City', 120),
(2150, 'Beirut', 121),
(2151, 'Jabal Lubnan', 121),
(2152, 'Mohafazat Liban-Nord', 121),
(2153, 'Mohafazat Mont-Liban', 121),
(2154, 'Sidon', 121),
(2155, 'al-Biqa', 121),
(2156, 'al-Janub', 121),
(2157, 'an-Nabatiyah', 121),
(2158, 'ash-Shamal', 121),
(2159, 'Berea', 122),
(2160, 'Butha-Buthe', 122),
(2161, 'Leribe', 122),
(2162, 'Mafeteng', 122),
(2163, 'Maseru', 122),
(2164, 'Mohale\'s Hoek', 122),
(2165, 'Mokhotlong', 122),
(2166, 'Qacha\'s Nek', 122),
(2167, 'Quthing', 122),
(2168, 'Thaba-Tseka', 122),
(2169, 'Bomi', 123),
(2170, 'Bong', 123),
(2171, 'Grand Bassa', 123),
(2172, 'Grand Cape Mount', 123),
(2173, 'Grand Gedeh', 123),
(2174, 'Loffa', 123),
(2175, 'Margibi', 123),
(2176, 'Maryland and Grand Kru', 123),
(2177, 'Montserrado', 123),
(2178, 'Nimba', 123),
(2179, 'Rivercess', 123),
(2180, 'Sinoe', 123),
(2181, 'Ajdabiya', 124);

-- --------------------------------------------------------

--
-- Estrutura da tabela `seccao`
--

CREATE TABLE `seccao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `seccao`
--

INSERT INTO `seccao` (`id`, `nome`, `activo`) VALUES
(1, '1ª Secção da Sala dos Crimes Comuns', 'S'),
(2, '3ª Secção da Sala dos Crimes Comuns', 'S'),
(3, '4ª Secção da Sala dos Crimes Comuns', 'S'),
(4, '5ª Secção da Sala dos Crimes Comuns', 'S'),
(5, '6ª Secção da Sala dos Crimes Comuns', 'S'),
(6, '7ª Secção da Sala dos Crimes Comuns', 'S'),
(7, '1ª Secção da Sala do Comércio', 'S'),
(8, '2ª Secção da Sala do Comércio', 'S'),
(9, '9ª Secção da Sala dos Crimes Comuns', 'S'),
(10, '10ª Secção da Sala dos Crimes Comuns', 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico`
--

CREATE TABLE `servico` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` double(8,2) DEFAULT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `servico`
--

INSERT INTO `servico` (`id`, `nome`, `valor`, `activo`) VALUES
(1, 'Consulta Pública', 300.00, 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sql_backup`
--

CREATE TABLE `sql_backup` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tarefa`
--

CREATE TABLE `tarefa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rel_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rel_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `project_type_task_id` int(10) UNSIGNED DEFAULT NULL,
  `task_subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prioridade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inicio` date NOT NULL,
  `termino` date NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checklist_complete_remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checklist_complete_signature` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S',
  `critical_comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tarefa`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `tarefa_membro`
--

CREATE TABLE `tarefa_membro` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tarefa_id` bigint(20) UNSIGNED NOT NULL,
  `membro_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tarefa_membro`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipocontacto`
--

CREATE TABLE `tipocontacto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `designacao` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipodespesa`
--

CREATE TABLE `tipodespesa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipodocumento`
--

CREATE TABLE `tipodocumento` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `designacao` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipopessoa`
--

CREATE TABLE `tipopessoa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `designacao` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tipopessoa`
--

INSERT INTO `tipopessoa` (`id`, `designacao`) VALUES
(1, 'Instituição Pública'),
(2, 'Particular'),
(3, 'Sociedade Comercial');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipoprocesso`
--

CREATE TABLE `tipoprocesso` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `designacao` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tipoprocesso`
--

INSERT INTO `tipoprocesso` (`id`, `designacao`, `activo`) VALUES
(1, 'Autos de corpo de delito', 'S'),
(2, 'Caução', 'S'),
(3, 'Querela', 'S'),
(4, 'Sumário', 'S'),
(5, 'Policia Correcional', 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tribunal`
--

CREATE TABLE `tribunal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tribunal`
--

INSERT INTO `tribunal` (`id`, `nome`, `activo`) VALUES
(1, 'Tribunal da Comarca de Luanda', 'S'),
(2, 'Tribunal da Comarca de Belas', 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tribunal_areaprocessual`
--

CREATE TABLE `tribunal_areaprocessual` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tribunal_id` bigint(20) UNSIGNED NOT NULL,
  `areaprocessual_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tribunal_areaprocessual`
--

INSERT INTO `tribunal_areaprocessual` (`id`, `tribunal_id`, `areaprocessual_id`) VALUES
(1, 1, 3),
(2, 1, 2),
(3, 1, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tribunal_areaprocessual_seccao`
--

CREATE TABLE `tribunal_areaprocessual_seccao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trib_areaprocessual` bigint(20) UNSIGNED NOT NULL,
  `seccao_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tribunal_areaprocessual_seccao`
--

INSERT INTO `tribunal_areaprocessual_seccao` (`id`, `trib_areaprocessual`, `seccao_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador`
--

CREATE TABLE `utilizador` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` enum('Admin','User','Cliente') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'User',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `utilizador`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador_funcao`
--

CREATE TABLE `utilizador_funcao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `utilizador_id` bigint(20) UNSIGNED NOT NULL,
  `funcao_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `utilizador_funcao`
--

INSERT INTO `utilizador_funcao` (`id`, `utilizador_id`, `funcao_id`) VALUES
(1, 1, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_pessoasingular_id_foreign` (`pessoasingular_id`),
  ADD KEY `admin_utilizador_id_foreign` (`utilizador_id`);

--
-- Índices para tabela `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agenda_cliente_id_foreign` (`cliente_id`);

--
-- Índices para tabela `areaprocessual`
--
ALTER TABLE `areaprocessual`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `areaprocessual_designacao_unique` (`designacao`);

--
-- Índices para tabela `areaprocessual_intervdesignacao`
--
ALTER TABLE `areaprocessual_intervdesignacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `areaprocessual_intervdesignacao_areaprocessual_id_foreign` (`areaprocessual_id`),
  ADD KEY `areaprocessual_intervdesignacao_intervdesignacao_id_foreign` (`intervdesignacao_id`);

--
-- Índices para tabela `areaprocessual_tipoprocesso`
--
ALTER TABLE `areaprocessual_tipoprocesso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `areaprocessual_tipoprocesso_areaprocessual_id_foreign` (`areaprocessual_id`),
  ADD KEY `areaprocessual_tipoprocesso_tipoprocesso_id_foreign` (`tipoprocesso_id`);

--
-- Índices para tabela `bairro`
--
ALTER TABLE `bairro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bairro_nome_unique` (`nome`);

--
-- Índices para tabela `bairro_municipio`
--
ALTER TABLE `bairro_municipio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bairro_municipio_bairro_id_foreign` (`bairro_id`),
  ADD KEY `bairro_municipio_municipio_id_foreign` (`municipio_id`);

--
-- Índices para tabela `case_transfer`
--
ALTER TABLE `case_transfer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_transfer_advocate_id_foreign` (`advocate_id`),
  ADD KEY `case_transfer_processo_id_foreign` (`processo_id`),
  ADD KEY `case_transfer_from_juiz_foreign` (`from_juiz`),
  ADD KEY `case_transfer_to_juiz_foreign` (`to_juiz`),
  ADD KEY `case_transfer_from_tribunal_foreign` (`from_tribunal`),
  ADD KEY `case_transfer_to_tribunal_foreign` (`to_tribunal`);

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_tipo_foreign` (`tipo`),
  ADD KEY `cliente_pais_id_foreign` (`pais_id`),
  ADD KEY `cliente_provincia_id_foreign` (`provincia_id`),
  ADD KEY `cliente_municipio_id_foreign` (`municipio_id`),
  ADD KEY `cliente_utilizador_id_foreign` (`utilizador_id`);

--
-- Índices para tabela `cliente_parte`
--
ALTER TABLE `cliente_parte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_parte_cliente_id_foreign` (`cliente_id`);

--
-- Índices para tabela `configuracao`
--
ALTER TABLE `configuracao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `configuracao_endereco_id_foreign` (`endereco_id`);

--
-- Índices para tabela `configuracao_factura`
--
ALTER TABLE `configuracao_factura`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `configuracao_geral`
--
ALTER TABLE `configuracao_geral`
  ADD PRIMARY KEY (`id`),
  ADD KEY `configuracao_geral_endereco_id_foreign` (`endereco_id`);

--
-- Índices para tabela `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacto_tipo_foreign` (`tipo`);

--
-- Índices para tabela `despesa`
--
ALTER TABLE `despesa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `despesa_fornecedor_id_foreign` (`fornecedor_id`);

--
-- Índices para tabela `dia_semana`
--
ALTER TABLE `dia_semana`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dia_semana_nome_unique` (`nome`);

--
-- Índices para tabela `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento_ndi_unique` (`ndi`),
  ADD KEY `documento_tipo_foreign` (`tipo`),
  ADD KEY `documento_pessoasingular_id_foreign` (`pessoasingular_id`);

--
-- Índices para tabela `dumps`
--
ALTER TABLE `dumps`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`id`),
  ADD KEY `endereco_bairro_id_foreign` (`bairro_id`),
  ADD KEY `endereco_municipio_id_foreign` (`municipio_id`);

--
-- Índices para tabela `escalatrabalho_diasemana`
--
ALTER TABLE `escalatrabalho_diasemana`
  ADD PRIMARY KEY (`id`),
  ADD KEY `escalatrabalho_diasemana_escala_foreign` (`escala`),
  ADD KEY `escalatrabalho_diasemana_dia_foreign` (`dia`);

--
-- Índices para tabela `escala_trabalho`
--
ALTER TABLE `escala_trabalho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `escala_trabalho_funcionario_foreign` (`funcionario`);

--
-- Índices para tabela `estadoprocesso`
--
ALTER TABLE `estadoprocesso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `estadoprocesso_estado_unique` (`estado`);

--
-- Índices para tabela `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_cliente_id_foreign` (`cliente_id`);

--
-- Índices para tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nif` (`nif`),
  ADD KEY `fornecedor_endereco_id_foreign` (`endereco_id`);

--
-- Índices para tabela `funcao`
--
ALTER TABLE `funcao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `funcao_nome_unique` (`nome`);

--
-- Índices para tabela `funcao_permissao`
--
ALTER TABLE `funcao_permissao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `funcao_permissao_funcao_id_foreign` (`funcao_id`),
  ADD KEY `funcao_permissao_permissao_id_foreign` (`permissao_id`);

--
-- Índices para tabela `fusohorario`
--
ALTER TABLE `fusohorario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fusohorario_nome_unique` (`nome`);

--
-- Índices para tabela `historico_processo`
--
ALTER TABLE `historico_processo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historico_processo_advogado_id_foreign` (`advogado_id`),
  ADD KEY `historico_processo_processo_id_foreign` (`processo_id`),
  ADD KEY `historico_processo_transfer_juiz_foreign` (`transfer_juiz`);

--
-- Índices para tabela `imposto`
--
ALTER TABLE `imposto`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `intervdesignacao`
--
ALTER TABLE `intervdesignacao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `intervdesignacao_designacao_unique` (`designacao`);

--
-- Índices para tabela `item_despesa`
--
ALTER TABLE `item_despesa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_despesa_despesa_id_foreign` (`despesa_id`);

--
-- Índices para tabela `item_factura`
--
ALTER TABLE `item_factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_factura_factura_id_foreign` (`factura_id`),
  ADD KEY `item_factura_imposto_id_foreign` (`imposto_id`);

--
-- Índices para tabela `juiz`
--
ALTER TABLE `juiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `juiz_tribunal_foreign` (`tribunal`);

--
-- Índices para tabela `mailsetups`
--
ALTER TABLE `mailsetups`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `municipio_provincia_id_foreign` (`provincia_id`);

--
-- Índices para tabela `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pais_nome_unique` (`nome`);

--
-- Índices para tabela `parte_contraria`
--
ALTER TABLE `parte_contraria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parte_contraria_processo_id_foreign` (`processo_id`),
  ADD KEY `parte_contraria_qualidade_foreign` (`qualidade`);

--
-- Índices para tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Índices para tabela `payment_mades`
--
ALTER TABLE `payment_mades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_mades_fornecedor_id_foreign` (`fornecedor_id`),
  ADD KEY `payment_mades_factura_id_foreign` (`factura_id`);

--
-- Índices para tabela `payment_receiveds`
--
ALTER TABLE `payment_receiveds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_receiveds_cliente_id_foreign` (`cliente_id`),
  ADD KEY `payment_receiveds_factura_id_foreign` (`factura_id`);

--
-- Índices para tabela `permissao`
--
ALTER TABLE `permissao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissao_nome_unique` (`nome`);

--
-- Índices para tabela `pessoa`
--
ALTER TABLE `pessoa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pessoa_tipo_foreign` (`tipo`),
  ADD KEY `pessoa_endereco_id_foreign` (`endereco_id`);

--
-- Índices para tabela `pessoacolectiva`
--
ALTER TABLE `pessoacolectiva`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pessoacolectiva_pessoa_id_foreign` (`pessoa_id`);

--
-- Índices para tabela `pessoasingular`
--
ALTER TABLE `pessoasingular`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pessoasingular_pais_id_foreign` (`pais_id`),
  ADD KEY `pessoasingular_municipio_id_foreign` (`municipio_id`),
  ADD KEY `pessoasingular_pessoa_id_foreign` (`pessoa_id`);

--
-- Índices para tabela `pessoa_contacto`
--
ALTER TABLE `pessoa_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pessoa_contacto_pessoa_id_foreign` (`pessoa_id`),
  ADD KEY `pessoa_contacto_contacto_id_foreign` (`contacto_id`);

--
-- Índices para tabela `processo`
--
ALTER TABLE `processo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `processo_no_processo_unique` (`no_processo`),
  ADD KEY `processo_cliente_id_foreign` (`cliente_id`),
  ADD KEY `processo_client_position_foreign` (`client_position`),
  ADD KEY `processo_areaprocessual_id_foreign` (`areaprocessual_id`),
  ADD KEY `processo_tipoprocesso_id_foreign` (`tipoprocesso_id`),
  ADD KEY `processo_estado_foreign` (`estado`),
  ADD KEY `processo_tribunal_id_foreign` (`tribunal_id`),
  ADD KEY `processo_seccao_id_foreign` (`seccao_id`),
  ADD KEY `processo_juiz_id_foreign` (`juiz_id`);

--
-- Índices para tabela `processo_imposto`
--
ALTER TABLE `processo_imposto`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `processo_membro`
--
ALTER TABLE `processo_membro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `processo_membro_processo_id_foreign` (`processo_id`),
  ADD KEY `processo_membro_membro_foreign` (`membro`);

--
-- Índices para tabela `profissao`
--
ALTER TABLE `profissao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profissao_nome_unique` (`nome`);

--
-- Índices para tabela `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provincia_pais_id_foreign` (`pais_id`);

--
-- Índices para tabela `seccao`
--
ALTER TABLE `seccao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `seccao_nome_unique` (`nome`);

--
-- Índices para tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `servico_nome_unique` (`nome`);

--
-- Índices para tabela `sql_backup`
--
ALTER TABLE `sql_backup`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tarefa`
--
ALTER TABLE `tarefa`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tarefa_membro`
--
ALTER TABLE `tarefa_membro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tarefa_membro_tarefa_id_foreign` (`tarefa_id`),
  ADD KEY `tarefa_membro_membro_id_foreign` (`membro_id`);

--
-- Índices para tabela `tipocontacto`
--
ALTER TABLE `tipocontacto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipocontacto_designacao_unique` (`designacao`);

--
-- Índices para tabela `tipodespesa`
--
ALTER TABLE `tipodespesa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipodespesa_nome_unique` (`nome`);

--
-- Índices para tabela `tipodocumento`
--
ALTER TABLE `tipodocumento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipodocumento_designacao_unique` (`designacao`);

--
-- Índices para tabela `tipopessoa`
--
ALTER TABLE `tipopessoa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipopessoa_designacao_unique` (`designacao`);

--
-- Índices para tabela `tipoprocesso`
--
ALTER TABLE `tipoprocesso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipoprocesso_designacao_unique` (`designacao`);

--
-- Índices para tabela `tribunal`
--
ALTER TABLE `tribunal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tribunal_nome_unique` (`nome`);

--
-- Índices para tabela `tribunal_areaprocessual`
--
ALTER TABLE `tribunal_areaprocessual`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tribunal_areaprocessual_tribunal_id_foreign` (`tribunal_id`),
  ADD KEY `tribunal_areaprocessual_areaprocessual_id_foreign` (`areaprocessual_id`);

--
-- Índices para tabela `tribunal_areaprocessual_seccao`
--
ALTER TABLE `tribunal_areaprocessual_seccao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tribunal_areaprocessual_seccao_trib_areaprocessual_foreign` (`trib_areaprocessual`),
  ADD KEY `tribunal_areaprocessual_seccao_seccao_id_foreign` (`seccao_id`);

--
-- Índices para tabela `utilizador`
--
ALTER TABLE `utilizador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utilizador_email_unique` (`email`);

--
-- Índices para tabela `utilizador_funcao`
--
ALTER TABLE `utilizador_funcao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilizador_funcao_utilizador_id_foreign` (`utilizador_id`),
  ADD KEY `utilizador_funcao_funcao_id_foreign` (`funcao_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `areaprocessual`
--
ALTER TABLE `areaprocessual`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `areaprocessual_intervdesignacao`
--
ALTER TABLE `areaprocessual_intervdesignacao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `areaprocessual_tipoprocesso`
--
ALTER TABLE `areaprocessual_tipoprocesso`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `bairro`
--
ALTER TABLE `bairro`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `bairro_municipio`
--
ALTER TABLE `bairro_municipio`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `case_transfer`
--
ALTER TABLE `case_transfer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `cliente_parte`
--
ALTER TABLE `cliente_parte`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `configuracao`
--
ALTER TABLE `configuracao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `configuracao_factura`
--
ALTER TABLE `configuracao_factura`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `configuracao_geral`
--
ALTER TABLE `configuracao_geral`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `despesa`
--
ALTER TABLE `despesa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `dia_semana`
--
ALTER TABLE `dia_semana`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `documento`
--
ALTER TABLE `documento`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `dumps`
--
ALTER TABLE `dumps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `escalatrabalho_diasemana`
--
ALTER TABLE `escalatrabalho_diasemana`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escala_trabalho`
--
ALTER TABLE `escala_trabalho`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estadoprocesso`
--
ALTER TABLE `estadoprocesso`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `factura`
--
ALTER TABLE `factura`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `funcao`
--
ALTER TABLE `funcao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `funcao_permissao`
--
ALTER TABLE `funcao_permissao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT de tabela `fusohorario`
--
ALTER TABLE `fusohorario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `historico_processo`
--
ALTER TABLE `historico_processo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `imposto`
--
ALTER TABLE `imposto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `intervdesignacao`
--
ALTER TABLE `intervdesignacao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `item_despesa`
--
ALTER TABLE `item_despesa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `item_factura`
--
ALTER TABLE `item_factura`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `juiz`
--
ALTER TABLE `juiz`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `mailsetups`
--
ALTER TABLE `mailsetups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de tabela `municipio`
--
ALTER TABLE `municipio`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2331;

--
-- AUTO_INCREMENT de tabela `pais`
--
ALTER TABLE `pais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT de tabela `parte_contraria`
--
ALTER TABLE `parte_contraria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `payment_mades`
--
ALTER TABLE `payment_mades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `payment_receiveds`
--
ALTER TABLE `payment_receiveds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `permissao`
--
ALTER TABLE `permissao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de tabela `pessoa`
--
ALTER TABLE `pessoa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pessoacolectiva`
--
ALTER TABLE `pessoacolectiva`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoasingular`
--
ALTER TABLE `pessoasingular`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `pessoa_contacto`
--
ALTER TABLE `pessoa_contacto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `processo`
--
ALTER TABLE `processo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `processo_imposto`
--
ALTER TABLE `processo_imposto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `processo_membro`
--
ALTER TABLE `processo_membro`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `profissao`
--
ALTER TABLE `profissao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `provincia`
--
ALTER TABLE `provincia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2182;

--
-- AUTO_INCREMENT de tabela `seccao`
--
ALTER TABLE `seccao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `sql_backup`
--
ALTER TABLE `sql_backup`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa`
--
ALTER TABLE `tarefa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tarefa_membro`
--
ALTER TABLE `tarefa_membro`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `tipocontacto`
--
ALTER TABLE `tipocontacto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipodespesa`
--
ALTER TABLE `tipodespesa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipopessoa`
--
ALTER TABLE `tipopessoa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tipoprocesso`
--
ALTER TABLE `tipoprocesso`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tribunal`
--
ALTER TABLE `tribunal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `tribunal_areaprocessual`
--
ALTER TABLE `tribunal_areaprocessual`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tribunal_areaprocessual_seccao`
--
ALTER TABLE `tribunal_areaprocessual_seccao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `utilizador`
--
ALTER TABLE `utilizador`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `utilizador_funcao`
--
ALTER TABLE `utilizador_funcao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_pessoasingular_id_foreign` FOREIGN KEY (`pessoasingular_id`) REFERENCES `pessoasingular` (`id`),
  ADD CONSTRAINT `admin_utilizador_id_foreign` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizador` (`id`);

--
-- Limitadores para a tabela `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `agenda_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`);

--
-- Limitadores para a tabela `areaprocessual_intervdesignacao`
--
ALTER TABLE `areaprocessual_intervdesignacao`
  ADD CONSTRAINT `areaprocessual_intervdesignacao_areaprocessual_id_foreign` FOREIGN KEY (`areaprocessual_id`) REFERENCES `areaprocessual` (`id`),
  ADD CONSTRAINT `areaprocessual_intervdesignacao_intervdesignacao_id_foreign` FOREIGN KEY (`intervdesignacao_id`) REFERENCES `intervdesignacao` (`id`);

--
-- Limitadores para a tabela `areaprocessual_tipoprocesso`
--
ALTER TABLE `areaprocessual_tipoprocesso`
  ADD CONSTRAINT `areaprocessual_tipoprocesso_areaprocessual_id_foreign` FOREIGN KEY (`areaprocessual_id`) REFERENCES `areaprocessual` (`id`),
  ADD CONSTRAINT `areaprocessual_tipoprocesso_tipoprocesso_id_foreign` FOREIGN KEY (`tipoprocesso_id`) REFERENCES `tipoprocesso` (`id`);

--
-- Limitadores para a tabela `bairro_municipio`
--
ALTER TABLE `bairro_municipio`
  ADD CONSTRAINT `bairro_municipio_bairro_id_foreign` FOREIGN KEY (`bairro_id`) REFERENCES `bairro` (`id`),
  ADD CONSTRAINT `bairro_municipio_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipio` (`id`);

--
-- Limitadores para a tabela `case_transfer`
--
ALTER TABLE `case_transfer`
  ADD CONSTRAINT `case_transfer_advocate_id_foreign` FOREIGN KEY (`advocate_id`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `case_transfer_from_juiz_foreign` FOREIGN KEY (`from_juiz`) REFERENCES `juiz` (`id`),
  ADD CONSTRAINT `case_transfer_from_tribunal_foreign` FOREIGN KEY (`from_tribunal`) REFERENCES `tribunal` (`id`),
  ADD CONSTRAINT `case_transfer_processo_id_foreign` FOREIGN KEY (`processo_id`) REFERENCES `processo` (`id`),
  ADD CONSTRAINT `case_transfer_to_juiz_foreign` FOREIGN KEY (`to_juiz`) REFERENCES `juiz` (`id`),
  ADD CONSTRAINT `case_transfer_to_tribunal_foreign` FOREIGN KEY (`to_tribunal`) REFERENCES `tribunal` (`id`);

--
-- Limitadores para a tabela `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipio` (`id`),
  ADD CONSTRAINT `cliente_pais_id_foreign` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id`),
  ADD CONSTRAINT `cliente_provincia_id_foreign` FOREIGN KEY (`provincia_id`) REFERENCES `provincia` (`id`),
  ADD CONSTRAINT `cliente_tipo_foreign` FOREIGN KEY (`tipo`) REFERENCES `tipopessoa` (`id`),
  ADD CONSTRAINT `cliente_utilizador_id_foreign` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizador` (`id`);

--
-- Limitadores para a tabela `cliente_parte`
--
ALTER TABLE `cliente_parte`
  ADD CONSTRAINT `cliente_parte_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`);

--
-- Limitadores para a tabela `configuracao`
--
ALTER TABLE `configuracao`
  ADD CONSTRAINT `configuracao_endereco_id_foreign` FOREIGN KEY (`endereco_id`) REFERENCES `endereco` (`id`);

--
-- Limitadores para a tabela `configuracao_geral`
--
ALTER TABLE `configuracao_geral`
  ADD CONSTRAINT `configuracao_geral_endereco_id_foreign` FOREIGN KEY (`endereco_id`) REFERENCES `endereco` (`id`);

--
-- Limitadores para a tabela `contacto`
--
ALTER TABLE `contacto`
  ADD CONSTRAINT `contacto_tipo_foreign` FOREIGN KEY (`tipo`) REFERENCES `tipocontacto` (`id`);

--
-- Limitadores para a tabela `despesa`
--
ALTER TABLE `despesa`
  ADD CONSTRAINT `despesa_fornecedor_id_foreign` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id`);

--
-- Limitadores para a tabela `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `documento_pessoasingular_id_foreign` FOREIGN KEY (`pessoasingular_id`) REFERENCES `pessoasingular` (`id`),
  ADD CONSTRAINT `documento_tipo_foreign` FOREIGN KEY (`tipo`) REFERENCES `tipodocumento` (`id`);

--
-- Limitadores para a tabela `endereco`
--
ALTER TABLE `endereco`
  ADD CONSTRAINT `endereco_bairro_id_foreign` FOREIGN KEY (`bairro_id`) REFERENCES `bairro` (`id`),
  ADD CONSTRAINT `endereco_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipio` (`id`);

--
-- Limitadores para a tabela `escalatrabalho_diasemana`
--
ALTER TABLE `escalatrabalho_diasemana`
  ADD CONSTRAINT `escalatrabalho_diasemana_dia_foreign` FOREIGN KEY (`dia`) REFERENCES `dia_semana` (`id`),
  ADD CONSTRAINT `escalatrabalho_diasemana_escala_foreign` FOREIGN KEY (`escala`) REFERENCES `escala_trabalho` (`id`);

--
-- Limitadores para a tabela `escala_trabalho`
--
ALTER TABLE `escala_trabalho`
  ADD CONSTRAINT `escala_trabalho_funcionario_foreign` FOREIGN KEY (`funcionario`) REFERENCES `admin` (`id`);

--
-- Limitadores para a tabela `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`);

--
-- Limitadores para a tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD CONSTRAINT `fornecedor_endereco_id_foreign` FOREIGN KEY (`endereco_id`) REFERENCES `endereco` (`id`);

--
-- Limitadores para a tabela `funcao_permissao`
--
ALTER TABLE `funcao_permissao`
  ADD CONSTRAINT `funcao_permissao_funcao_id_foreign` FOREIGN KEY (`funcao_id`) REFERENCES `funcao` (`id`),
  ADD CONSTRAINT `funcao_permissao_permissao_id_foreign` FOREIGN KEY (`permissao_id`) REFERENCES `permissao` (`id`);

--
-- Limitadores para a tabela `historico_processo`
--
ALTER TABLE `historico_processo`
  ADD CONSTRAINT `historico_processo_advogado_id_foreign` FOREIGN KEY (`advogado_id`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `historico_processo_processo_id_foreign` FOREIGN KEY (`processo_id`) REFERENCES `processo` (`id`),
  ADD CONSTRAINT `historico_processo_transfer_juiz_foreign` FOREIGN KEY (`transfer_juiz`) REFERENCES `juiz` (`id`);

--
-- Limitadores para a tabela `item_despesa`
--
ALTER TABLE `item_despesa`
  ADD CONSTRAINT `item_despesa_despesa_id_foreign` FOREIGN KEY (`despesa_id`) REFERENCES `despesa` (`id`);

--
-- Limitadores para a tabela `item_factura`
--
ALTER TABLE `item_factura`
  ADD CONSTRAINT `item_factura_factura_id_foreign` FOREIGN KEY (`factura_id`) REFERENCES `factura` (`id`),
  ADD CONSTRAINT `item_factura_imposto_id_foreign` FOREIGN KEY (`imposto_id`) REFERENCES `imposto` (`id`);

--
-- Limitadores para a tabela `juiz`
--
ALTER TABLE `juiz`
  ADD CONSTRAINT `juiz_tribunal_foreign` FOREIGN KEY (`tribunal`) REFERENCES `tribunal_areaprocessual_seccao` (`id`);

--
-- Limitadores para a tabela `municipio`
--
ALTER TABLE `municipio`
  ADD CONSTRAINT `municipio_provincia_id_foreign` FOREIGN KEY (`provincia_id`) REFERENCES `provincia` (`id`);

--
-- Limitadores para a tabela `parte_contraria`
--
ALTER TABLE `parte_contraria`
  ADD CONSTRAINT `parte_contraria_processo_id_foreign` FOREIGN KEY (`processo_id`) REFERENCES `processo` (`id`),
  ADD CONSTRAINT `parte_contraria_qualidade_foreign` FOREIGN KEY (`qualidade`) REFERENCES `intervdesignacao` (`id`);

--
-- Limitadores para a tabela `payment_mades`
--
ALTER TABLE `payment_mades`
  ADD CONSTRAINT `payment_mades_factura_id_foreign` FOREIGN KEY (`factura_id`) REFERENCES `factura` (`id`),
  ADD CONSTRAINT `payment_mades_fornecedor_id_foreign` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id`);

--
-- Limitadores para a tabela `payment_receiveds`
--
ALTER TABLE `payment_receiveds`
  ADD CONSTRAINT `payment_receiveds_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `payment_receiveds_factura_id_foreign` FOREIGN KEY (`factura_id`) REFERENCES `factura` (`id`);

--
-- Limitadores para a tabela `pessoa`
--
ALTER TABLE `pessoa`
  ADD CONSTRAINT `pessoa_endereco_id_foreign` FOREIGN KEY (`endereco_id`) REFERENCES `endereco` (`id`),
  ADD CONSTRAINT `pessoa_tipo_foreign` FOREIGN KEY (`tipo`) REFERENCES `tipopessoa` (`id`);

--
-- Limitadores para a tabela `pessoacolectiva`
--
ALTER TABLE `pessoacolectiva`
  ADD CONSTRAINT `pessoacolectiva_pessoa_id_foreign` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoa` (`id`);

--
-- Limitadores para a tabela `pessoasingular`
--
ALTER TABLE `pessoasingular`
  ADD CONSTRAINT `pessoasingular_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipio` (`id`),
  ADD CONSTRAINT `pessoasingular_pais_id_foreign` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id`),
  ADD CONSTRAINT `pessoasingular_pessoa_id_foreign` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoa` (`id`);

--
-- Limitadores para a tabela `pessoa_contacto`
--
ALTER TABLE `pessoa_contacto`
  ADD CONSTRAINT `pessoa_contacto_contacto_id_foreign` FOREIGN KEY (`contacto_id`) REFERENCES `contacto` (`id`),
  ADD CONSTRAINT `pessoa_contacto_pessoa_id_foreign` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoa` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `processo`
--
ALTER TABLE `processo`
  ADD CONSTRAINT `processo_areaprocessual_id_foreign` FOREIGN KEY (`areaprocessual_id`) REFERENCES `areaprocessual` (`id`),
  ADD CONSTRAINT `processo_client_position_foreign` FOREIGN KEY (`client_position`) REFERENCES `intervdesignacao` (`id`),
  ADD CONSTRAINT `processo_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `processo_estado_foreign` FOREIGN KEY (`estado`) REFERENCES `estadoprocesso` (`id`),
  ADD CONSTRAINT `processo_juiz_id_foreign` FOREIGN KEY (`juiz_id`) REFERENCES `juiz` (`id`),
  ADD CONSTRAINT `processo_seccao_id_foreign` FOREIGN KEY (`seccao_id`) REFERENCES `seccao` (`id`),
  ADD CONSTRAINT `processo_tipoprocesso_id_foreign` FOREIGN KEY (`tipoprocesso_id`) REFERENCES `tipoprocesso` (`id`),
  ADD CONSTRAINT `processo_tribunal_id_foreign` FOREIGN KEY (`tribunal_id`) REFERENCES `tribunal` (`id`);

--
-- Limitadores para a tabela `processo_membro`
--
ALTER TABLE `processo_membro`
  ADD CONSTRAINT `processo_membro_membro_foreign` FOREIGN KEY (`membro`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `processo_membro_processo_id_foreign` FOREIGN KEY (`processo_id`) REFERENCES `processo` (`id`);

--
-- Limitadores para a tabela `provincia`
--
ALTER TABLE `provincia`
  ADD CONSTRAINT `provincia_pais_id_foreign` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id`);

--
-- Limitadores para a tabela `tarefa_membro`
--
ALTER TABLE `tarefa_membro`
  ADD CONSTRAINT `tarefa_membro_membro_id_foreign` FOREIGN KEY (`membro_id`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `tarefa_membro_tarefa_id_foreign` FOREIGN KEY (`tarefa_id`) REFERENCES `tarefa` (`id`);

--
-- Limitadores para a tabela `tribunal_areaprocessual`
--
ALTER TABLE `tribunal_areaprocessual`
  ADD CONSTRAINT `tribunal_areaprocessual_areaprocessual_id_foreign` FOREIGN KEY (`areaprocessual_id`) REFERENCES `areaprocessual` (`id`),
  ADD CONSTRAINT `tribunal_areaprocessual_tribunal_id_foreign` FOREIGN KEY (`tribunal_id`) REFERENCES `tribunal` (`id`);

--
-- Limitadores para a tabela `tribunal_areaprocessual_seccao`
--
ALTER TABLE `tribunal_areaprocessual_seccao`
  ADD CONSTRAINT `tribunal_areaprocessual_seccao_seccao_id_foreign` FOREIGN KEY (`seccao_id`) REFERENCES `seccao` (`id`),
  ADD CONSTRAINT `tribunal_areaprocessual_seccao_trib_areaprocessual_foreign` FOREIGN KEY (`trib_areaprocessual`) REFERENCES `tribunal_areaprocessual` (`id`);

--
-- Limitadores para a tabela `utilizador_funcao`
--
ALTER TABLE `utilizador_funcao`
  ADD CONSTRAINT `utilizador_funcao_funcao_id_foreign` FOREIGN KEY (`funcao_id`) REFERENCES `funcao` (`id`),
  ADD CONSTRAINT `utilizador_funcao_utilizador_id_foreign` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizador` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
