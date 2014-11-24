CREATE TABLE `currency`.`currency` (
  `currency_id` INT NOT NULL AUTO_INCREMENT,
  `abbreviation` VARCHAR(45) NOT NULL,
  `amount` INT NOT NULL,
  `nominal` INT NOT NULL,
  `name_ru` VARCHAR(45) NOT NULL,
  `visible` TINYINT(1) NULL,
  PRIMARY KEY (`currency_id`),
  UNIQUE INDEX `abbreviation_UNIQUE` (`abbreviation` ASC));


CREATE TABLE `currency`.`rate` (
  `rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_id` int(11) NOT NULL,
  `value` float NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`rate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=utf8;



INSERT INTO currency.currency(`abbreviation`, `nominal`, `name_ru`, `visible`) VALUES
  ('AUD', '1', 'Австралийский доллар', 0),
  ('AZN', '1', 'Азербайджанский манат', 0),
  ('GBP', '1', 'Фунт стерлингов Соединенного королевства', 0),
  ('AMD', '100', 'Армянских драмов', 0),
  ('BYR', '10000', 'Белорусских рублей', 1),
  ('BGN', '1', 'Болгарский лев', 0),
  ('BRL', '1', 'Бразильский реал', 0),
  ('HUF', '100', 'Венгерских форинтов', 0),
  ('DKK', '10', 'Датских крон', 0),
  ('USD', '1', 'Доллар США', 1),
  ('EUR', '1', 'Евро', 1),
  ('INR', '100', 'Индийских рупий', 0),
  ('KZT', '100', 'Казахских тенге', 0),
  ('CAD', '1', 'Канадский доллар', 0),
  ('KGS', '100', 'Киргизских сомов', 0),
  ('CNY', '10', 'Китайских юаней', 0),
  ('LTL', '1', 'Литовский лит', 0),
  ('MDL', '10', 'Молдавских леев', 0),
  ('NOK', '10', 'Норвежских крон', 0),
  ('PLN', '1', 'Польский злотый', 0),
  ('RON', '1', 'Новый румынский лей', 0),
  ('XDR', '1', 'СДР (специальные права заимствования)', 0),
  ('SGD', '1', 'Сингапурский доллар', 0),
  ('TJS', '10', 'Таджикских сомони', 0),
  ('TRY', '1', 'Турецкая лира', 0),
  ('TMT', '1', 'Новый туркменский манат', 0),
  ('UZS', '1000', 'Узбекских сумов', 0),
  ('UAH', '10', 'Украинских гривен', 1),
  ('CZK', '10', 'Чешских крон', 0),
  ('SEK', '10', 'Шведских крон', 0),
  ('CHF', '1', 'Швейцарский франк', 0),
  ('ZAR', '10', 'Южноафриканских рэндов', 0),
  ('KRW', '1000', 'Вон Республики Корея', 0),
  ('JPY', '100', 'Японских иен', 0)
