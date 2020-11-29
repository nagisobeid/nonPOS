CREATE TABLE IF NOT EXISTS employees(
	eID integer NOT NULL AUTO_INCREMENT,
	fName varchar(25) NOT NULL,
	lName varchar(25) NOT NULL,
	dob date NOT NULL,
	ePass varchar(25) NOT NULL,
	address varchar(50) NOT NULL,
	city varchar(25) NOT NULL,
	state varchar(2) NOT NULL,
	zip varchar(5) NOT NULL,
	phone varchar(10) NOT NULL,
	permisions integer NOT NULL,
	payRate float NOT NULL,
	bID integer NOT NULL,
	PRIMARY KEY(eID),
	FOREIGN KEY (bID) REFERENCES owners(bID)
);