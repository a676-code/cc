DROP SCHEMA IF EXISTS cc;
CREATE SCHEMA cc;
USE cc;

CREATE TABLE cc.function (
	odd_coefficient INT NOT NULL,
	odd_addend INT NOT NULL,
	even_divisor INT NOT NULL,
	stabilizes TINYINT NOT NULL,
	CONSTRAINT function_pk PRIMARY KEY (odd_coefficient, odd_addend, even_divisor)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE cc.evaluation (
	odd_coefficient INT NOT NULL,
	odd_addend INT NOT NULL,
	even_divisor INT NOT NULL,
	`value` INT NOT NULL,
	`chain` varchar(1000) NOT NULL,
	prime_chain varchar(750) NOT NULL,
	chain_length INT NOT NULL,
	`loop` varchar(750) NULL,
	prime_loop varchar(750) NOT NULL,
	loop_length INT NULL,
	cl_ratio DECIMAL(6, 3) NULL,
	stopping_time INT NULL,
	total_stopping_time INT NULL,
	CONSTRAINT evaluation_pk PRIMARY KEY (odd_coefficient, odd_addend, even_divisor, `value`),
	CONSTRAINT evaluation_FK FOREIGN KEY (odd_coefficient, odd_addend, even_divisor) REFERENCES cc.function(odd_coefficient, odd_addend, even_divisor) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;