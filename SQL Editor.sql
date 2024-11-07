-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS funcionario;
USE funcionario;

-- Criação da tabela de funcionários
CREATE TABLE IF NOT EXISTS tbl_funcionario (
    fun_codigo INT PRIMARY KEY AUTO_INCREMENT,
    fun_nome VARCHAR(30),
    fun_cargo VARCHAR(40)
);

-- Criação da tabela de registros de ponto
CREATE TABLE IF NOT EXISTS tbl_registro (
    res_codigo INT PRIMARY KEY AUTO_INCREMENT,
    res_data DATE,       -- Tipo DATE para a data
    res_hora TIME,       -- Tipo TIME para a hora
    fun_codigo INT,
    FOREIGN KEY (fun_codigo) REFERENCES tbl_funcionario (fun_codigo)
        ON DELETE CASCADE  -- Garante que se um funcionário for excluído, seus registros de ponto também serão removidos
);

-- Exemplo de consulta para verificar os registros na tabela tbl_registro
SELECT * FROM tbl_registro;
