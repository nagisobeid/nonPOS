CREATE TABLE IF NOT EXISTS extras(
	exID varchar(2),
	name varchar(25) NOT NULL,
	exDescrip varchar(50),
	exPrice float,
	PRIMARY KEY (exID)
);