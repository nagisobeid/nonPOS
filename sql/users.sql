CREATE TABLE IF NOT EXISTS users(
	username varchar(25) NOT NULL,
	uEmail varchar(25) NOT NULL,
	fName varchar(25),
	lName varchar(25),
	uPass varchar(25) NOT NULL,
	PRIMARY KEY (username)
);