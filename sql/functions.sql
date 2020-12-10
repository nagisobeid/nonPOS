delimiter $$
CREATE FUNCTION total_order(orderid INTEGER)
RETURNS FLOAT DETERMINISTIC
BEGIN
DECLARE total FLOAT;
SELECT SUM((price + extras) * quantity) into total
FROM contains
WHERE orderid = oID;
RETURN total;
END
$$
delimiter ;

delimiter $$
CREATE FUNCTION period_pay(employee INTEGER, sDate date)
RETURNS FLOAT DETERMINISTIC
BEGIN
RETURN (SELECT (hoursWorked * payRate)
FROM hours
WHERE employee = eID AND sDate = start);
END
$$
delimiter ;

delimiter $$
CREATE FUNCTION total_pay(employee INTEGER)
RETURNS FLOAT DETERMINISTIC
BEGIN
DECLARE pay FLOAT;
SELECT SUM(hoursWorked * payRate) into pay
FROM hours
WHERE employee = eID;
RETURN pay;
END
$$
delimiter ;

delimiter $$
CREATE FUNCTION calc_profits(bus INT, sDate date, eDate date)
RETURN FLOAT DETERMINISTIC
BEGIN
DECLARE total FLOAT;
SELECT SUM((price + extras) * quantity) into total
FROM allOrders
WHERE bus = bID, 