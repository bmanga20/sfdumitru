CREATE TABLE IF NOT EXISTS `#__jsn_uniform_payment_paypal_logs` (
  `log_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `submission_id` int(11) NOT NULL,
  `log_amount` float NOT NULL DEFAULT '0',
  `log_status` varchar(50) NOT NULL DEFAULT 'unsuccessful',
  `log_currency` varchar(5) NOT NULL DEFAULT 'USD'
) DEFAULT CHARSET=utf8;
