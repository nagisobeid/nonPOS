CREATE TABLE IF NOT EXISTS extras(
	exID integer NOT NULL AUTO_INCREMENT,
	name varchar(25) NOT NULL,
	exDescrip varchar(50),
	exPrice float,
	PRIMARY KEY (exID)
);
