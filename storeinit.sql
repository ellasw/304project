drop table customer;
drop table album;
drop table branch;
drop table branch_employee;
drop table cart;
drop table makes_purchase;
drop table purchase_has_album;
drop table album_has_song;
drop table branch_carries_album;

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
	street_no int not null,
	city varchar(20) not null,
	province char(2) not null, 
	postal_code varchar(6) not null,
	primary key (branch_no));


create table branch_employee
	(emp_email varchar(50) not null,
	name varchar(40) not null,
	password varchar(20) not null,
	branch_no int not null,
	primary key (emp_email),
	foreign key (branch_no) references branch);
	
create table cart
	(cust_email varchar(50) not null,
	album_id int not null,
	quantity int not null,
	primary key (cust_email, album_id),
	foreign key (cust_email) references customer,
	foreign key (album_id) references album);
		
create table makes_purchase 
	(purchase_no int not null,
	datestamp int not null,
	total_price int not null,
	cust_email varchar(50) not null,
	primary key (purchase_no),
	foreign key (cust_email) references customer);
	
create table purchase_has_album
	(quantity int not null,
	album_id int not null,
	purchase_no int not null,
	primary key (album_id, purchase_no),
	foreign key (album_id) references album,
	foreign key (purchase_no) references purchase);
	
create table branch_carries_album 
	(album_id int not null,
	branch_no int not null,
	primary key (album_id, branch_no),
	foreign key (album_id) references album,
	foreign key (branch_no) references branch);

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
	
insert into album
	values ('1', '10', '300', '15.00', '2016', 'The Life of Pablo', 'Hip-Hop/Rap', 'Kanye West');
	
insert into album
	values ('2', '10', '400', '12.00', '2007', 'Graduation', 'Hip-Hop/Rap', 'Kanye West');

insert into album
	values ('3', '10', '700', '10.00', '2016', 'Cleopatra', 'Alternative', 'The Lumineers');
	
insert into album
	values ('4', '10', '50', '9.00', '2000', 'The Life of Pablo', 'Hip-Hop/Rap', 'Kanye West');
	
insert into album
	values ('5', '10', '1', '8.00', '1990', 'Since I Let You Go', 'Rock', 'Real Ponchos');
	
insert into album_has_song
	values ('1', '1', 'I Love You');

insert into album_has_song
	values ('6', '1', 'Love');
	
insert into album_has_song
	values ('2', '2', 'I Love You 2');
	
insert into album_has_song
	values ('3', '3', 'You Love Me');
	
insert into album_has_song
	values ('4', '4', 'She Loves You');
	
insert into album_has_song
	values ('5', '5', 'No One Loves You');
	
insert into branch
	values ('1', '1200 W 4th Ave', 'Vancouver', 'BC', 'V7R 9K3');
	
insert into branch
	values ('2', '300 Apple Street', 'Vancouver', 'BC', 'V7R 9K3');

insert into branch
	values ('3', '200 Blackcomb Way', 'Whistler', 'BC', 'G8L 6N8');
	
insert into branch
	values ('4', '1500 Main Street', 'Toronto', 'ON', 'N1C 3J6');

insert into branch
	values ('5', '600 North Street', 'Edmonton', 'AB', 'V7R 8B1');
	
insert into branch_employee
	values ('zack@gmail.com', 'Zack', '123', '1');
	
insert into branch_employee
	values ('tim@gmail.com', 'Tim', '456', '2');
	
insert into branch_employee
	values ('sarah@gmail.com', 'Sarah', '890', '3');
	
insert into branch_employee
	values ('andy@gmail.com', 'Andy', '234', '4');
	
insert into branch_employee
	values ('lisa@gmail.com', 'Lisa', '676', '5');
	
insert into cart
	values ('jon@gmail.com', '1', '3');
	
insert into cart
	values ('jon@gmail.com', '2', '1');
	
insert into cart
	values ('bob@gmail.com', '3', '6');
	
insert into cart
	values ('jimmyne@gmail.com', '4', '1');
	
insert into cart
	values ('se20@gmail.com', '4', '2');
	
insert into cart
	values ('sleepy@gmail.com', '4', '5');
	



