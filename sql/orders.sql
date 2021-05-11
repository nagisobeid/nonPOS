CREATE TABLE IF NOT EXISTS orders(
	oID integer NOT NULL AUTO_INCREMENT,
	username varchar(25) NOT NULL,
	placed datetime NOT NULL,
	completed datetime NOT NULL,
	type integer NOT NULL,
	eID integer NOT NULL,
	bID integer NOT NULL
	PRIMARY KEY (oID),
	FOREIGN KEY (bID) REFERENCES owners(bID),
	FOREIGN KEY (username) REFERENCES users(username),
	FOREIGN KEY (eID) REFERENCES employees(eID)
);
