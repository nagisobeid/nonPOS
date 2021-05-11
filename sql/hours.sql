CREATE TABLE IF NOT EXISTS hours(
	eID integer NOT NULL AUTO_INCREMENT,
	payStart date NOT NULL,
	payEnd date NOT NULL,
	payRate float NOT NULL,
	hoursWorked time,
	PRIMARY KEY(eID, payStart),
	FOREIGN KEY(eID) REFERENCES employees(eID)
);
