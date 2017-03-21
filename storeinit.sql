drop table customer;
drop table album;
drop table song;
drop table branch;
drop table branch_employee;
drop table cart;
drop table purchase;
drop table cust_makes_purchase;
drop table cust_cart;
drop table cart_has_album;
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
	price int not null,
	year int not null,
	name varchar(40) not null,
	genre varchar(20) not null, 
	artist varchar(40) not null,
	primary key (album_id));
	
create table song
	(song_id int not null,
	title varchar(30) not null,
	primary key (song_id));
	
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
	(price int not null,
	cust_email varchar(50) not null,
	album_id int not null,
	primary key (cust_email, album_id),
	foreign key (cust_email) references customer,
	foreign key (album_id) references album);
		
create table purchase 
	(purchase_no int not null,
	datestamp int not null,
	total_price int not null,
	primary key (purchase_no));
	
create table cust_makes_purchase
	(purchase_no int not null,
	cust_email varchar(50) not null,
	primary key (purchase_no, cust_email),
	foreign key (purchase_no) references purchase,
	foreign key (cust_email) references customer);
	
create table cust_cart
	(total_price int not null,
	cust_email varchar(50) not null,
	album_id int not null,
	primary key (cust_email),
	foreign key (cust_email) references customer,
	foreign key (album_id) references album);
	

create table cart_has_album
	(quantity int not null,
	cust_email varchar(50) not null,
	album_id int not null,
	primary key (cust_email, album_id),
	foreign key (cust_email) references customer,
	foreign key (album_id) references album);
	
create table purchase_has_album
	(quantity int not null,
	album_id int not null,
	purchase_no int not null,
	primary key (album_id, purchase_no),
	foreign key (album_id) references album,
	foreign key (purchase_no) references purchase);
	
create table album_has_song
	(song_id int not null,
	album_id int not null,
	primary key (album_id, song_id),
	foreign key (album_id) references album,
	foreign key (song_id) references song);
	
create table branch_carries_album 
	(album_id int not null,
	branch_no int not null,
	primary key (album_id, branch_no),
	foreign key (album_id) references album,
	foreign key (branch_no) references branch);