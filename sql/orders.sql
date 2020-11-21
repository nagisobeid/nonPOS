CREATE TABLE IF NOT EXISTS orders(
	oID integer NOT NULL AUTO_INCREMENT,
	username varchar(25) NOT NULL,
	placed date NOT NULL,
	completed date NOT NULL,
	dt boolean NOT NULL,
	eID integer NOT NULL,
	PRIMARY KEY (oID),
	FOREIGN KEY (username) REFERENCES users(username),
	FOREIGN KEY (eID) REFERENCES employees(eID)
);