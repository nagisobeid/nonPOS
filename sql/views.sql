CREATE VIEW companyPay AS
	SELECT hours.eID, payStart, payEnd, hours.payRate, hoursWorked, owners.bID
	FROM hours, employees, owners
	WHERE hours.eID = employees.eID AND owners.bID = employees.bID;
	
CREATE VIEW menuItems AS
	SELECT menus.menuID, mName, mDescrip, itemID, name, iDescrip, price
	FROM menus, items
	WHERE menus.menuID = items.menuID;
	
CREATE VIEW allMenus AS
	SELECT menuID, mName, mDescrip, bName
	FROM menus, owners
	WHERE owners.bID = menus.bID;
	
CREATE VIEW allOrders AS
	SELECT users.username, uEmail, oID, placed, completed, eID, orders.bID
	FROM users, orders, owners
	WHERE users.username = orders.username AND orders.bID = owners.bID;
	
CREATE VIEW wholeOrder AS
	SELECT orders.oID, placed, completed, itemID, quantity, price, extras
	FROM orders, contains
	WHERE orders.oID = contains.oID;
	
CREATE VIEW allEmployees AS
	SELECT employees.eID, employees.fName, employees.lName, employees.bID
	FROM employees, owners
	WHERE owners.bID = employees.bID;