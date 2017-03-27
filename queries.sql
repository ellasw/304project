
/* avg cost of all albums
*/
SELECT
AVG(price)
FROM 
album

/*Nested aggregation (like last problem of midterm 2):
group-by genre and find avg cost of the albums in that genre 
*/
SELECT 
AVG(price)
FROM  album
GROUP BY genre

/* select customers who have purchased all the albums
*/
SELECT cust_email
FROM customer
WHERE NOT EXISTS (
	SELECT *
	FROM purchase_has_album
	WHERE NOT EXISTS(
		select *
		FROM makes_purchase
		WHERE customer.cust_email=makes_purchase.cust_email AND ));











