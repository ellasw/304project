drop table branch_employee;
drop table cart;
drop table purchase_has_album;
drop table makes_purchase;
drop table album_has_song;
drop table branch_carries_album;
drop table customer;
drop table album;
drop table branch;

create table customer
	(cust_email varchar(50) not null,
	cust_name varchar(40) not null,
	cust_password varchar(20) not null,
	primary key (cust_email));
	
create table album 
	(album_id int not null,
	minimum_stock int not null,
	stock int not null,
	price float not null,
	year int not null,
	name varchar(40) not null,
	genre varchar(20) not null, 
	artist varchar(40) not null,
	primary key (album_id));
	
create table album_has_song
	(song_id int not null,
	album_id int not null,
	song_title varchar(30) not null,
	primary key (song_id),
	foreign key (album_id) references album);
	
create table branch
	(branch_no int not null,
	street_no  varchar(40) not null,
	city varchar(20) not null,
	province char(2) not null, 
	postal_code varchar(7) not null,
	primary key (branch_no));


create table branch_employee
	(emp_email varchar(50) not null,
	name varchar(40) not null,
	password varchar(20) not null,
	branch_no int not null,
	primary key (emp_email),
	foreign key (branch_no) references branch ON DELETE CASCADE);
	
create table cart
	(cust_email varchar(50) not null,
	album_id int not null,
	quantity int not null,
	primary key (cust_email, album_id),
	foreign key (cust_email) references customer ON DELETE CASCADE,
	foreign key (album_id) references album);
		
create table makes_purchase 
	(purchase_no int not null,
	cust_email varchar(50) not null,
	purchase_month int not null CHECK (purchase_month>=1 AND purchase_month<=12),
	purchase_year int not null CHECK (purchase_year=2016 OR purchase_year=2017),
	primary key (purchase_no),
	foreign key (cust_email) references customer ON DELETE CASCADE);
	
create table purchase_has_album
	(purchase_no int not null,
	album_id int not null,
	quantity int not null,
	primary key (album_id, purchase_no),
	foreign key (album_id) references album,
	foreign key (purchase_no) references makes_purchase ON DELETE CASCADE);
	
create table branch_carries_album 
	(branch_no int not null,
	album_id int not null,
	primary key (album_id, branch_no),
	foreign key (album_id) references album,
	foreign key (branch_no) references branch ON DELETE CASCADE);

insert into customer
	values('jon@gmail.com', 'Jon', '123');

insert into customer 
	values('bob@gmail.com', 'Bob', '124');
	
insert into customer 
	values('jimmyne@gmail.com', 'Jim', '102');
	
insert into customer 
	values ('se20@gmail.com', 'Sam', '436');
	
insert into customer 
	values ('sleepy@gmail.com', 'Steven', '246');
	
insert into customer 
	values ('lisa@gmail.com', 'Lisa', '123');

insert into customer 
	values ('ella@gmail.com', 'Ella', '123');
	
insert into customer 
	values ('linda@gmail.com', 'Linda', '123');

insert into customer 
	values ('laks@gmail.com', 'Laks', '123');

insert into customer 
	values ('kevin@gmail.com', 'Kevin', '123');

insert into customer 
	values ('samantha@gmail.com', 'Samantha', '123');

insert into customer 
	values ('erika@gmail.com', 'Erika', '123');

insert into customer 
	values ('julie@gmail.com', 'Julie', '123');

insert into customer 
	values ('alex@gmail.com', 'Alex', '123');

insert into customer 
	values ('hannah@gmail.com', 'Hannah', '123');

insert into customer 
	values ('carly@gmail.com', 'Carly', '123');

insert into customer 
	values ('juliet@gmail.com', 'Juliet', '123');

insert into customer 
	values ('joe@gmail.com', 'Joe', '123');
	
insert into customer 
	values ('allie@gmail.com', 'Allie', '123');

insert into customer 
	values ('rob@gmail.com', 'Rob', '123');

	
insert into album
	values (1, 10, 300, 15.00, 2016, 'The Life of Pablo', 'Hip-Hop/Rap', 'Kanye West');
	
insert into album
	values (2, 10, 400, 12.00, 2007, 'Graduation', 'Hip-Hop/Rap', 'Kanye West');

insert into album
	values (3, 10, 700, 10.00, 2016, 'Cleopatra', 'Alternative', 'The Lumineers');
	
insert into album
	values (4, 10, 50, 9.00, 2000, 'Yeezus', 'Hip-Hop/Rap', 'Kanye West');
	
insert into album
	values (5, 10, 1, 8.00, 1990, 'Since I Let You Go', 'Rock', 'Real Ponchos');
	
insert into album
	values (6, 10, 300, 16.00, 2017, 'More Life', 'Hip-Hop/Rap', 'Drake');

insert into album
	values (7, 10, 600, 20.00, 2017, 'Divide', 'Pop', 'Ed Sheeran');

insert into album
	values (8, 10, 100, 15.00, 2016, 'American Teen', 'Hip-Hop/Rap', 'Khalid');

insert into album
	values (9, 10, 75, 10.00, 2017, 'Rather You Than Me', 'Hip-Hop/Rap', 'Rick Ross');

insert into album
	values (10, 10, 3, 15.00, 2016, 'Starboy', 'Hip-Hop/Rap', 'The Weeknd');

insert into album
	values (11, 10, 100, 14.00, 2017, 'NAV', 'Hip-Hop/Rap', 'NAV');

insert into album
	values (12, 10, 200, 14.00, 2017, 'So Good', 'Pop', 'Zara Larsson');

insert into album
	values (13, 10, 200, 14.00, 2005, 'Lang Lang in Paris', 'Classical', 'Lang Lang');

insert into album
	values (14, 10, 200, 14.00, 2000, 'Sing Me Home', 'Classical', 'The Silk Road Ensemble');

insert into album
	values (15, 10, 200, 10.00, 1980, 'Chuck', 'Rock', 'Chuck Berry');

insert into album
	values (16, 10, 150, 10.00, 2000, 'Blossom', 'Alternative', 'Milky Chance');

insert into album
	values (17, 10, 150, 10.00, 2004, 'Stoney', 'Pop', 'Post Malone');
	
insert into album
	values (18, 10, 150, 10.00, 2003, '24K Magic', 'Pop', 'Bruno Mars');

insert into album
	values (19, 10, 150, 8.00, 2009, 'Views', 'Hip-Hop/Rap', 'Drake');

insert into album
	values (20, 10, 150, 8.00, 2008, 'Roses', 'Rock', 'The Chainsmokers');


	
insert into album_has_song
	values (1, 1, 'Ultralight Beam');

insert into album_has_song
	values (2, 1, 'Father Stretch');
	
insert into album_has_song
	values (3, 1, 'Pt. 2');
	
insert into album_has_song
	values (4, 1, 'Famous');
	
insert into album_has_song
	values (5, 1, 'Feedback');
	
insert into album_has_song
	values (6, 2, 'Good Morning');
	
insert into album_has_song
	values (7, 2, 'Champion');

insert into album_has_song
	values (8, 2, 'Stronger');

insert into album_has_song
	values (9, 2, 'I Wonder');

insert into album_has_song
	values (10, 2, 'Good Life');

insert into album_has_song
	values (11, 3, 'Sleep on the Floor');

insert into album_has_song
	values (12, 3, 'Ophelia');
	
insert into album_has_song
	values (13, 3, 'Angela');
	
insert into album_has_song
	values (14, 3, 'My Eyes');

insert into album_has_song
	values (15, 3, 'Patience');

insert into album_has_song
	values (16, 4, 'On Sight');

insert into album_has_song
	values (17, 5, 'Slow Burn');
	
insert into album_has_song
	values (18, 6, 'Portland');

insert into album_has_song
	values (19, 7, 'Perfect');

insert into album_has_song
	values (20, 8, 'Location');

insert into album_has_song
	values (21, 9, 'Apple of My Eye');

insert into album_has_song
	values (22, 10, 'Starboy');

insert into album_has_song
	values (23, 11, 'Myself');

insert into album_has_song
	values (24, 12, 'Lush Life');

insert into album_has_song
	values (25, 13, 'In Evening Air');

insert into album_has_song
	values (26, 14, 'Going Home');

insert into album_has_song
	values (27, 15, 'Big Boys');

insert into album_has_song
	values (28, 16, 'Firebird');

insert into album_has_song
	values (29, 17, 'Go Flex');

insert into album_has_song
	values (30, 18, '24K Magic');

insert into album_has_song
	values (31, 19, '9');

insert into album_has_song
	values (32, 20, 'Roses');
	
	
insert into branch
	values (1, '1200 W 4th Ave', 'Vancouver', 'BC', 'V7R 9K3');
	
insert into branch
	values (2, '300 Apple Street', 'Vancouver', 'BC', 'V7R 9K3');

insert into branch
	values (3, '200 Blackcomb Way', 'Whistler', 'BC', 'G8L 6N8');
	
insert into branch
	values (4, '1500 Main Street', 'Toronto', 'ON', 'N1C 3J6');
	
insert into branch_employee
	values ('zack@gmail.com', 'Zack', '123', 1);
	
insert into branch_employee
	values ('tim@gmail.com', 'Tim', '456', 1);
	
insert into branch_employee
	values ('sarah@gmail.com', 'Sarah', '890', 2);
	
insert into branch_employee
	values ('andy@gmail.com', 'Andy', '234', 2);
	
insert into branch_employee
	values ('lisa@gmail.com', 'Lisa', '234', 3);
	
insert into branch_employee
	values ('ben@gmail.com', 'Ben', '234', 3);

insert into branch_employee
	values ('mike@gmail.com', 'Mike', '234', 4);

insert into branch_employee
	values ('jonathan@gmail.com', 'Jonathan', '234', 4);

insert into branch_employee
	values ('dora@gmail.com', 'Lisa', '234', 4);
	
insert into makes_purchase 
	values (1, 'jon@gmail.com', 8, 2016);
		
insert into makes_purchase 
	values (2, 'jon@gmail.com', 9, 2016);

insert into makes_purchase 
	values (3, 'jon@gmail.com', 10, 2016);

insert into makes_purchase 
	values (4, 'jon@gmail.com', 11, 2016);

insert into makes_purchase 
	values (5, 'jon@gmail.com', 1, 2017);

insert into makes_purchase 
	values (6, 'bob@gmail.com', 8, 2016);

insert into makes_purchase 
	values (7, 'bob@gmail.com', 1, 2017);

insert into makes_purchase 
	values (8, 'bob@gmail.com', 1, 2017);

insert into makes_purchase 
	values (9, 'jimmyne@gmail.com', 2, 2017);

insert into makes_purchase 
	values (10, 'jimmyne@gmail.com', 2, 2017);

insert into makes_purchase 
	values (11, 'sleepy@gmail.com', 8, 2016);

insert into makes_purchase 
	values (12, 'lisa@gmail.com', 10, 2016);

insert into makes_purchase 
	values (13, 'ella@gmail.com', 8, 2016);

insert into makes_purchase 
	values (14, 'linda@gmail.com', 9, 2016);

insert into makes_purchase 
	values (15, 'laks@gmail.com', 3, 2017);

insert into makes_purchase 
	values (16, 'kevin@gmail.com', 11, 2016);

insert into makes_purchase 
	values (17, 'samantha@gmail.com', 12, 2016);

insert into makes_purchase 
	values (18, 'erika@gmail.com', 12, 2016);

insert into makes_purchase 
	values (19, 'julie@gmail.com', 3, 2017);

insert into makes_purchase 
	values (20, 'alex@gmail.com', 12, 2016);


		
insert into purchase_has_album
	values (1, 1, 1);

insert into purchase_has_album
	values (1, 2, 1);
	
insert into purchase_has_album
	values (1, 3, 1);
	
insert into purchase_has_album
	values (1, 4, 1);
	
/* add albums 5-20 with qty 1 to purchase_no 1*/
	
insert into purchase_has_album
	values (2, 5, 1);
	
insert into purchase_has_album
	values (2, 6, 1);

insert into purchase_has_album
	values (3, 7, 1);

insert into purchase_has_album
	values (4, 8, 1);

insert into purchase_has_album
	values (4, 9, 1);

insert into purchase_has_album
	values (4, 1, 1);

insert into purchase_has_album
	values (5, 2, 2);

insert into purchase_has_album
	values (5, 3, 2);

insert into purchase_has_album
	values (5, 4, 2);

insert into purchase_has_album
	values (6, 5, 2);

insert into purchase_has_album
	values (6, 10, 2);

insert into purchase_has_album
	values (6, 11, 2);

insert into purchase_has_album
	values (7, 12, 2);

insert into purchase_has_album
	values (8, 13, 2);

insert into purchase_has_album
	values (9, 14, 2);

insert into purchase_has_album
	values (10, 15, 2);

insert into purchase_has_album
	values (11, 16, 3);

insert into purchase_has_album
	values (12, 17, 3);

insert into purchase_has_album
	values (12, 18, 3);

insert into purchase_has_album
	values (12, 1, 3);

insert into purchase_has_album
	values (13, 2, 3);

insert into purchase_has_album
	values (13, 3, 3);

insert into purchase_has_album
	values (14, 4, 3);

insert into purchase_has_album
	values (15, 5, 3);

insert into purchase_has_album
	values (15, 6, 3);

insert into purchase_has_album
	values (15, 7, 3);

insert into purchase_has_album
	values (15, 8, 3);

insert into purchase_has_album
	values (16, 9, 1);

insert into purchase_has_album
	values (16, 10, 1);

insert into purchase_has_album
	values (16, 11, 1);

insert into purchase_has_album
	values (17, 12, 1);

insert into purchase_has_album
	values (18, 13, 1);
	
insert into purchase_has_album
	values (19, 14, 1);

insert into purchase_has_album
	values (20, 15, 1);

insert into purchase_has_album
	values (20, 16, 1);

insert into branch_carries_album
	values (1, 1);

insert into branch_carries_album
	values (1, 2);

insert into branch_carries_album
	values (1, 3);

insert into branch_carries_album
	values (1, 4);

insert into branch_carries_album
	values (1, 5);

insert into branch_carries_album
	values (1, 6);

insert into branch_carries_album
	values (1, 7);

insert into branch_carries_album
	values (1, 8);

insert into branch_carries_album
	values (1, 9);

insert into branch_carries_album
	values (1, 10);

insert into branch_carries_album
	values (1, 11);

insert into branch_carries_album
	values (1, 12);

insert into branch_carries_album
	values (1, 13);

insert into branch_carries_album
	values (1, 14);

insert into branch_carries_album
	values (1, 15);

insert into branch_carries_album
	values (1, 16);

insert into branch_carries_album
	values (1, 17);

insert into branch_carries_album
	values (1, 18);

insert into branch_carries_album
	values (1, 19);

insert into branch_carries_album
	values (1, 20);
	
insert into branch_carries_album
	values (2, 1);

insert into branch_carries_album
	values (2, 2);

insert into branch_carries_album
	values (2, 3);

insert into branch_carries_album
	values (2, 4);

insert into branch_carries_album
	values (2, 5);

insert into branch_carries_album
	values (2, 6);

insert into branch_carries_album
	values (2, 7);

insert into branch_carries_album
	values (2, 8);

insert into branch_carries_album
	values (2, 9);

insert into branch_carries_album
	values (2, 10);

insert into branch_carries_album
	values (2, 11);

insert into branch_carries_album
	values (2, 12);

insert into branch_carries_album
	values (2, 13);

insert into branch_carries_album
	values (2, 14);

insert into branch_carries_album
	values (2, 15);

insert into branch_carries_album
	values (2, 16);

insert into branch_carries_album
	values (2, 17);

insert into branch_carries_album
	values (2, 18);

insert into branch_carries_album
	values (2, 19);

insert into branch_carries_album
	values (2, 20);

insert into branch_carries_album
	values (3, 1);

insert into branch_carries_album
	values (3, 2);

insert into branch_carries_album
	values (3, 3);

insert into branch_carries_album
	values (3, 4);

insert into branch_carries_album
	values (3, 5);

insert into branch_carries_album
	values (3, 6);

insert into branch_carries_album
	values (3, 7);

insert into branch_carries_album
	values (3, 8);

insert into branch_carries_album
	values (3, 9);

insert into branch_carries_album
	values (3, 10);

insert into branch_carries_album
	values (4, 11);

insert into branch_carries_album
	values (4, 12);

insert into branch_carries_album
	values (4, 13);

insert into branch_carries_album
	values (4, 14);

insert into branch_carries_album
	values (4, 15);

insert into branch_carries_album
	values (4, 16);

insert into branch_carries_album
	values (4, 17);

insert into branch_carries_album
	values (4, 18);

insert into branch_carries_album
	values (4, 19);

insert into branch_carries_album
	values (4, 20);


