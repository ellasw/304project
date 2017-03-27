
/* avg cost of all albums
*/
SELECT AVG(price) FROM album;

/*Nested aggregation (like last problem of midterm 2):
group-by genre and find avg cost of the albums in that genre 
*/
SELECT AVG(price) FROM album
GROUP BY genre;

/* select customers who have purchased all the albums
*/
SELECT c.cust_email FROM customer c
WHERE NOT EXISTS (
	SELECT * FROM album a
	WHERE NOT EXISTS(
		select * FROM makes_purchase m, purchase_has_album p
		WHERE c.cust_email=m.cust_email 
		AND m.purchase_no=p.purchase_no
		AND a.album_id=p.album_id));











