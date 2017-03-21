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
	price int not null,
	year int not null,
	name varchar(40) not null,
	genre varchar(20) not null, 
	artist varchar(40) not null,
	primary key (album_id));
	
create table album_has_song
	(song_id int not null,
	album_id int not null,
	song_title varchar(30) not null,
	primary key (album_id),
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
	(total_cost int not null,
	cust_email varchar(50) not null,
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
