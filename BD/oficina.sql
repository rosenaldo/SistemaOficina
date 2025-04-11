-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/04/2025 às 19:35
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `oficina`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(2, 'Lubrificantes'),
(3, 'Peças Carro'),
(4, 'Peças Motos'),
(6, 'Óleos'),
(7, 'Acessórios');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `cpf`, `telefone`, `email`, `endereco`, `data`) VALUES
(8, 'sterbom', '03.066.662/0001-52', '(84) 99401-8080', 'www.sterbom.com.br', 'Rodovia Br 101 S/N Km 305, Parnamirim, Rio Grande do Norte', '2025-04-04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comissoes`
--

CREATE TABLE `comissoes` (
  `id` int(11) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `mecanico` varchar(20) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `id_servico` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `comissoes`
--

INSERT INTO `comissoes` (`id`, `valor`, `mecanico`, `tipo`, `id_servico`, `data`) VALUES
(21, 75.00, '877.777.777-77', 'Orçamento', 19, '2021-06-09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `produto` int(11) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `funcionario` varchar(20) NOT NULL,
  `data` date NOT NULL,
  `id_conta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_pagar`
--

CREATE TABLE `contas_pagar` (
  `id` int(11) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `funcionario` varchar(20) NOT NULL,
  `data` date NOT NULL,
  `data_venc` date NOT NULL,
  `pago` varchar(5) NOT NULL,
  `imagem` varchar(100) DEFAULT NULL,
  `fornecedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_receber`
--

CREATE TABLE `contas_receber` (
  `id` int(11) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `adiantamento` decimal(8,2) DEFAULT NULL,
  `mecanico` varchar(20) NOT NULL,
  `cliente` varchar(20) NOT NULL,
  `funcionario` varchar(20) DEFAULT NULL,
  `data` date NOT NULL,
  `pago` varchar(5) NOT NULL,
  `id_servico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `controles`
--

CREATE TABLE `controles` (
  `id` int(11) NOT NULL,
  `veiculo` int(11) NOT NULL,
  `mecanico` varchar(20) NOT NULL,
  `data` date NOT NULL,
  `descricao` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `controles`
--

INSERT INTO `controles` (`id`, `veiculo`, `mecanico`, `data`, `descricao`) VALUES
(12, 9, '000.000.000-00', '2025-04-04', '2 Serviços');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `tipo_pessoa` varchar(20) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `nome`, `tipo_pessoa`, `cpf`, `telefone`, `email`, `endereco`) VALUES
(1, 'Marcos Souza', 'Jurídica', '55.555.555/5558-88', '(55) 55555-5555', 'marcos@hotmail.com', 'Rua A'),
(3, 'Paloma Campos', 'Física', '585.555.555-55', '(66) 66666-6666', 'paloma@hotmail.com', 'Rua D');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mecanicos`
--

CREATE TABLE `mecanicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `endereco` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `mecanicos`
--

INSERT INTO `mecanicos` (`id`, `nome`, `cpf`, `telefone`, `email`, `endereco`) VALUES
(1, 'Adriel', '788.888.888-60', '(88) 88888-8888', 'adriel@hotmail.com', 'Rua A');

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes`
--

CREATE TABLE `movimentacoes` (
  `id` int(11) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `funcionario` varchar(20) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamentos`
--

CREATE TABLE `orcamentos` (
  `id` int(11) NOT NULL,
  `cliente` varchar(20) NOT NULL,
  `veiculo` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `servico` int(11) NOT NULL,
  `data` date NOT NULL,
  `data_entrega` date NOT NULL,
  `garantia` int(11) NOT NULL,
  `mecanico` varchar(20) NOT NULL,
  `obs` text NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `orc_prod`
--

CREATE TABLE `orc_prod` (
  `id` int(11) NOT NULL,
  `orcamento` int(11) NOT NULL,
  `produto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `orc_prod`
--

INSERT INTO `orc_prod` (`id`, `orcamento`, `produto`) VALUES
(24, 2, 14),
(25, 5, 13),
(26, 5, 14),
(27, 5, 8),
(28, 2, 9),
(29, 2, 7),
(32, 7, 16),
(33, 7, 9),
(34, 9, 15),
(35, 9, 10),
(36, 15, 7),
(37, 15, 13),
(38, 18, 15),
(39, 18, 13),
(42, 19, 13),
(44, 19, 14),
(45, 19, 14),
(46, 20, 15),
(47, 20, 9),
(48, 21, 7),
(50, 22, 16),
(51, 25, 15),
(52, 25, 14),
(55, 42, 16),
(56, 42, 15),
(57, 42, 13),
(58, 43, 15),
(59, 43, 13),
(60, 43, 15),
(69, 46, 16),
(70, 48, 16),
(71, 48, 15);

-- --------------------------------------------------------

--
-- Estrutura para tabela `orc_serv`
--

CREATE TABLE `orc_serv` (
  `id` int(11) NOT NULL,
  `orcamento` int(11) NOT NULL,
  `servico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `orc_serv`
--

INSERT INTO `orc_serv` (`id`, `orcamento`, `servico`) VALUES
(1, 19, 7),
(2, 19, 6),
(4, 19, 1),
(6, 18, 7),
(7, 20, 7),
(8, 20, 6),
(9, 21, 2),
(10, 22, 7),
(11, 22, 6),
(12, 25, 7),
(13, 27, 6),
(14, 27, 5),
(15, 33, 7),
(16, 34, 7),
(17, 34, 3),
(18, 42, 2),
(19, 42, 7),
(20, 43, 7),
(21, 43, 1),
(57, 45, 47),
(58, 45, 46),
(59, 45, 47),
(60, 45, 46),
(61, 45, 47),
(62, 45, 47),
(63, 45, 47),
(64, 45, 46),
(65, 45, 47),
(66, 45, 46),
(67, 45, 47),
(68, 45, 46),
(69, 45, 46),
(70, 45, 47),
(71, 45, 47),
(72, 45, 46),
(73, 45, 47),
(74, 45, 47),
(75, 45, 46),
(76, 45, 47),
(77, 45, 46),
(78, 45, 46),
(79, 45, 47),
(80, 45, 46),
(81, 45, 47),
(82, 46, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `os`
--

CREATE TABLE `os` (
  `id` int(11) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `mecanico` varchar(20) NOT NULL,
  `cliente` varchar(20) NOT NULL,
  `data_entrega` date NOT NULL,
  `concluido` varchar(5) NOT NULL,
  `valor_mao_obra` decimal(8,2) NOT NULL,
  `data` date NOT NULL,
  `veiculo` int(11) NOT NULL,
  `garantia` int(11) DEFAULT NULL,
  `obs` text NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `id_orc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `categoria` int(11) NOT NULL,
  `fornecedor` int(11) NOT NULL,
  `valor_compra` decimal(8,2) NOT NULL,
  `valor_venda` decimal(8,2) NOT NULL,
  `estoque` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  `imagem` varchar(100) DEFAULT NULL,
  `nivel_min` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `categoria`, `fornecedor`, `valor_compra`, `valor_venda`, `estoque`, `descricao`, `imagem`, `nivel_min`) VALUES
(4, 'Bateria', 3, 3, 250.00, 350.00, 5, 'A Bateria,é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas....', 'bateria.jpg', 12),
(7, 'Correia Dentada', 3, 3, 150.00, 200.00, 6, 'A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas q', 'correia-dentada.jpg', 0),
(8, 'Óleo Pneu Pretinho', 6, 1, 25.00, 35.00, 5, 'As principais características dos óleos lubrificantes são a viscosidade, o índice de viscosidade (IV) e a densidade. A viscosidade mede a dificuldade com que o óleo escorre (escoa).', 'pneu-pretinho.jpg', 0),
(9, 'Óleo Lubrificante', 2, 1, 20.00, 35.00, 11, 'Linha de Lubrificação Aeronáutica Completa, você encontra na Lubvap. Melhores Preços, Qualidade Comprovada, Atendimento Personalizado, e muito mais, Confira. Atendimento 24h. Produto a pronta entrega. Melhor preço. Garantia de eficiência. Marcas: Rocol, A', 'oleo.jpg', 0),
(10, 'Cabo de Ignição', 3, 1, 250.00, 300.00, 11, 'A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.', 'cabo-de-ignicao.jpg', 9),
(11, 'Calota Aro 13', 3, 1, 120.00, 220.00, 10, 'A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.', 'calota-aro13.jpg', 6),
(12, 'Capa Proteção', 7, 1, 100.00, 120.00, 14, 'A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.', 'capa-protecao.jpg', 10),
(13, 'Embreagem', 3, 1, 350.00, 450.00, 11, 'A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.', 'embreagem.jpg', 10),
(14, 'Faról', 3, 3, 250.00, 300.00, -3, 'A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.', 'farol-de-carro.jpg', 8),
(15, 'Freio Disco', 3, 3, 250.00, 300.00, -8, 'A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.', 'freio-de-disco.jpg', 7),
(16, 'ParaChoque Dianteiro', 3, 1, 350.00, 500.00, 16, 'A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.A correia dentada, também chamada de correia de distribuição, é uma peça matreira. Além de não dar sinais evidentes de desgaste ou pistas de que algo está mal, ela mantém ocultas na sua parte interna, composta por pequenos dentes de borracha, as mazelas que resultam da fricção constante pelo movimento de tração.', 'parachoque-dianteiro.jpg', 8);

-- --------------------------------------------------------

--
-- Estrutura para tabela `recepcionistas`
--

CREATE TABLE `recepcionistas` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `endereco` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `recepcionistas`
--

INSERT INTO `recepcionistas` (`id`, `nome`, `cpf`, `telefone`, `email`, `endereco`) VALUES
(3, 'Samara', '444.444.444-44', '(84) 00000-0000', 'samara@gmail.com', 'Rua F');

-- --------------------------------------------------------

--
-- Estrutura para tabela `retornos`
--

CREATE TABLE `retornos` (
  `id` int(11) NOT NULL,
  `veiculo` int(11) NOT NULL,
  `data_serv` date NOT NULL,
  `data_contato` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `retornos`
--

INSERT INTO `retornos` (`id`, `veiculo`, `data_serv`, `data_contato`) VALUES
(2, 5, '2025-04-02', '2025-04-02'),
(3, 3, '2021-06-01', '2025-04-10'),
(4, 2, '2021-06-09', '2025-04-10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `valor` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`id`, `nome`, `valor`) VALUES
(1, 'Troca de Óleo', 130.00),
(2, 'Serviços de Oficina', 0.00),
(3, 'Manutenção Preventiva', 0.00),
(5, 'Pintura', 0.00),
(6, 'Balanceamento', 120.00),
(7, 'Alinhamento', 100.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(30) NOT NULL,
  `nivel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `cpf`, `email`, `senha`, `nivel`) VALUES
(1, 'Adriel', '788.888.888-60', 'adriel@hotmail.com', '123', 'mecanico'),
(5, 'Samara', '444.444.444-44', 'samara@gmail.com', '123', 'recep'),
(10, 'pedrinho', '000.000.000-00', 'pedrinho@hotmail.com', '123', 'mecanico'),
(17, 'Administrador', '000.000.000-00', 'marcos@adm.com.br', '123', 'admin');

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id` int(11) NOT NULL,
  `marca` varchar(30) NOT NULL,
  `modelo` varchar(30) NOT NULL,
  `cor` varchar(30) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `ano` int(11) NOT NULL,
  `km` int(11) NOT NULL,
  `cliente` varchar(20) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `veiculos`
--

INSERT INTO `veiculos` (`id`, `marca`, `modelo`, `cor`, `placa`, `ano`, `km`, `cliente`, `data`) VALUES
(9, 'ford', 'teste', 'branco', 'xxxxxx', 0, 0, '03.066.662/0001-52', '2025-04-04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `produto` int(11) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `funcionario` varchar(20) NOT NULL,
  `data` date NOT NULL,
  `id_orc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `comissoes`
--
ALTER TABLE `comissoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `contas_pagar`
--
ALTER TABLE `contas_pagar`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `contas_receber`
--
ALTER TABLE `contas_receber`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `controles`
--
ALTER TABLE `controles`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `mecanicos`
--
ALTER TABLE `mecanicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `orcamentos`
--
ALTER TABLE `orcamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `orc_prod`
--
ALTER TABLE `orc_prod`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `orc_serv`
--
ALTER TABLE `orc_serv`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `os`
--
ALTER TABLE `os`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `recepcionistas`
--
ALTER TABLE `recepcionistas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `retornos`
--
ALTER TABLE `retornos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `comissoes`
--
ALTER TABLE `comissoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `contas_pagar`
--
ALTER TABLE `contas_pagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `contas_receber`
--
ALTER TABLE `contas_receber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de tabela `controles`
--
ALTER TABLE `controles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `mecanicos`
--
ALTER TABLE `mecanicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `orcamentos`
--
ALTER TABLE `orcamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de tabela `orc_prod`
--
ALTER TABLE `orc_prod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de tabela `orc_serv`
--
ALTER TABLE `orc_serv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de tabela `os`
--
ALTER TABLE `os`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `recepcionistas`
--
ALTER TABLE `recepcionistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `retornos`
--
ALTER TABLE `retornos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
