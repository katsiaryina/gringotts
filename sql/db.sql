DROP DATABASE IF EXISTS gringotts;
CREATE DATABASE gringotts;
USE gringotts;
DROP TABLE IF EXISTS client;
DROP TABLE IF EXISTS client_address;
DROP TABLE IF EXISTS account;
DROP TABLE IF EXISTS `transaction`;
DROP TABLE IF EXISTS bill_supplier;
DROP TABLE IF EXISTS bill_payment;
DROP TABLE IF EXISTS transfer_interax;
DROP TABLE IF EXISTS logins;
DROP TABLE IF EXISTS notifications;

CREATE TABLE client (
  client_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  first_name VARCHAR(255) NOT  NULL,
  last_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  pwd VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE client_address (
  telephone VARCHAR(255),
  address VARCHAR(255),
  zip_code VARCHAR(6),
  province VARCHAR(2),
  country VARCHAR(3),
  client_id INT(11) NOT NULL,
  FOREIGN KEY (client_id) REFERENCES client(client_id)
);

CREATE TABLE account (
  account_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  account_type VARCHAR(255) NOT NULL, -- cheques, credit, investment
  description VARCHAR(255) NOT NULL,
  account_number VARCHAR(255) NOT NULL,
  balance DECIMAL(12,2) NOT NULL,
  client_id INT(11) NOT NULL,
  FOREIGN KEY (client_id) REFERENCES client(client_id)
);

CREATE TABLE `transaction` (
  transaction_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  transaction_date DATETIME,
  amount DECIMAL(12,2) NOT NULL,
  transaction_type VARCHAR(255) NOT NULL, -- withdrawal, deposit
  supplier VARCHAR(255),
  account_id INT(11) NOT NULL,
  FOREIGN KEY (account_id) REFERENCES account(account_id)
);

CREATE TABLE bill_supplier (
  supplier_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  supplier_name VARCHAR(255) NOT NULL,
  reference_number VARCHAR(30) NOT NULL,
  account_id INT(11) NOT NULL,
  FOREIGN KEY (account_id) REFERENCES account(account_id)
);

CREATE TABLE bill_payment (
  bill_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  amount DECIMAL(12,2) NOT NULL,
  payment_date DATETIME,
  interax_id VARCHAR(255), -- Save the id from interax here once transaction is complete
  supplier_id INT(11) NOT NULL,
  account_id INT(11) NOT NULL,
  FOREIGN KEY (supplier_id) REFERENCES bill_supplier(supplier_id),
  FOREIGN KEY (account_id) REFERENCES account(account_id)
);

CREATE TABLE interax_transfer (
  transfer_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  to_user_email VARCHAR(255) NOT NULL,
  from_account INT(11) NOT NULL,
  security_question VARCHAR(255),
  answer VARCHAR(255) NOT NULL,
  amount DECIMAL(6,2) NOT NULL,
  accepted BOOLEAN, 
  sent_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  accepted_at DATETIME,
  interax_id VARCHAR(255), -- Save the id from interax API
  FOREIGN KEY (from_account) REFERENCES account(account_id)
);

CREATE TABLE notifications (
  message VARCHAR(255),
  notification_date DATE,
  client_id INT(11),
  FOREIGN KEY (client_id) REFERENCES client(client_id)
);

CREATE TABLE logins (
  login_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ip_address VARCHAR(30),
  success VARCHAR(5),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(255)
);

-- =========================
-- DATA INSERTS
-- =========================

-- =========================
-- CLIENTS
-- =========================
INSERT INTO `client` (`client_id`, `first_name`, `last_name`, `email`, `pwd`, `created_at`) VALUES
-- password = asdf
(NULL, 'katsia', 'touchette', 'katsia@mail.com', '3da541559918a808c2402bba5012f6c60b27661c', current_timestamp()),
(NULL, 'bob', 'marley', 'bob@mail.com', '3da541559918a808c2402bba5012f6c60b27661c', current_timestamp())
;

-- =========================
-- Accounts
-- =========================
INSERT INTO `account` (`account_id`, `account_type`, `description`,`account_number`, `balance`, `client_id`) VALUES 
-- Katsia Accounts
(NULL, 'cheques', 'Compte de cheques', '0000-1 99-888-01', '11000.32', '1'),
(NULL, 'credit', 'Mastercard', '**** **** **** 1234', '-1133.65', '1'),
(NULL, 'investment', 'Investments banque', '0000-1 2345455', '2234.23', '1'),
-- Bob Accounts
(NULL, 'cheques', 'Compte de cheques', '0000-1 99-234-01', '11233333.32', '2'),
(NULL, 'credit', 'Mastercard', '**** **** **** 5555', '-13003.65', '2');

-- =========================
-- Transactions
-- =========================
INSERT INTO `transaction` (`transaction_id`, `transaction_date`, `amount`, `transaction_type`, `supplier`, `account_id`) VALUES 
-- Katsia
(NULL, '2019-07-13 00:00:00', '31.31', 'withdrawal', 'Amazon.ca', '2'),
(NULL, '2019-07-12 00:00:00', '35.35', 'withdrawal', 'Brunet', '2'),
(NULL, '2019-07-11 00:00:00', '40.00', 'withdrawal', 'Transfert Interax', '1'),
(NULL, '2019-07-10 00:00:00', '54.11', 'withdrawal', 'Metro', '2'),
(NULL, '2019-07-10 00:00:00', '131.90', 'withdrawal', 'Metro', '2'),
(NULL, '2019-07-08 00:00:00', '1000', 'deposit', 'Depot Paie', '1'),
-- Bob
(NULL, '2019-07-13 00:00:00', '31.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '35.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '55.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '25.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '25.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '25.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '25.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '25.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '25.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '25.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '25.31', 'withdrawal', 'Amazon.ca', '4'),
(NULL, '2019-07-13 00:00:00', '25.31', 'withdrawal', 'Amazon.ca', '4')

;

-- =========================
-- Bill Suppliers
-- =========================
-- 'Videotron S.E.N.C.' 
-- 'Bell Canada Inc.' 
-- 'Visa Scotia' 
-- 'Visa Desjardins' 
-- 'Mastercard BMO' 
INSERT INTO `bill_supplier` (`supplier_id`, `supplier_name`, `reference_number`, `account_id`) VALUES 
-- Katsia
(NULL, 'Mastercard BMO', '**** **** **** 1234', '1'),
(NULL, 'Videotron S.E.N.C.', '123 1234', '1'),
(NULL, 'Bell Canada Inc.', 'aa1234', '1'),
(NULL, 'Visa Scotia', 'sco123', '1'),
(NULL, 'Visa Desjardins', 'desj699', '1'),
-- bob
(NULL, 'Videotron S.E.N.C.', '222 2222', '4'),
(NULL, 'Visa Desjardins', '**** **** **** 2222', '4')
;

-- =========================
-- Bill Payments
-- =========================
INSERT INTO `bill_payment` (`bill_id`, `amount`, `payment_date`, `interax_id`, `supplier_id`, `account_id`) VALUES 
-- Katsia
(NULL, '1000', '2019-07-01 00:00:00', NULL, '1', '1'),
-- (NULL, '45.45', '2019-07-03 00:00:00', NULL, '2', '1'),
(NULL, '31.31', '2019-07-11 00:00:00', NULL, '3', '1'),
(NULL, '800', '2019-06-01 00:00:00', NULL, '1', '1');

-- =========================
-- Interax
-- =========================
INSERT INTO `interax_transfer` 
(`transfer_id`, `to_user_email`, `from_account`, `security_question`, `answer`, `amount`, `accepted`, `sent_at`, `accepted_at`) VALUES 
(NULL, 'test@email.com', '1', 'test question', 'test answer', '13', '0', current_timestamp(), NULL);