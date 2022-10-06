DROP SCHEMA IF EXISTS cc;
CREATE SCHEMA cc;
USE cc;

CREATE TABLE cc.`function` (
	function_id varchar(750) NOT NULL,
	odd_coefficient INT NOT NULL,
	odd_addend INT NOT NULL,
	even_divisor INT NOT NULL,
	CONSTRAINT function_pk PRIMARY KEY (function_id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE cc.evaluation (
	evaluation_id INT auto_increment NOT NULL,
	function_id varchar(750) NOT NULL,
	chain_id INT NOT NULL,
	value INT NOT NULL,
	CONSTRAINT evaluation_pk PRIMARY KEY (evaluation_id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE cc.`chain` (
	chain_id INT auto_increment NOT NULL,
	eval_id varchar(750) NOT NULL,
	`chain` varchar(750) NOT NULL,
	prime_chain varchar(750) NULL,
	`length` INT NOT NULL,
	CONSTRAINT chain_pk PRIMARY KEY (chain_id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE cc.`loop` (
	loop_id INT auto_increment NOT NULL,
	chain_id INT NOT NULL,
	`loop` varchar(750) NULL,
	prime_loop varchar(750) NULL,
	`length` INT NULL,
	stabilization BOOL NOT NULL,
	CONSTRAINT loop_pk PRIMARY KEY (loop_id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- FOREIGN KEYS
ALTER TABLE cc.evaluation ADD CONSTRAINT evaluation_FK FOREIGN KEY (function_id) REFERENCES cc.`function`(function_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE cc.evaluation ADD CONSTRAINT evaluation_FK_1 FOREIGN KEY (chain_id) REFERENCES cc.`chain`(chain_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE cc.`loop` ADD CONSTRAINT loop_FK FOREIGN KEY (chain_id) REFERENCES cc.`chain`(chain_id) ON DELETE CASCADE ON UPDATE CASCADE;