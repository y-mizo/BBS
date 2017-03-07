create database YOUR_DATABASE_NAME;

use YOUR_DATABASE_NAME;

grant all on YOUR_DATABASE_NAME.* to YOUR_NAME@localhost identified by 'YOUR_PASSWORD';

create table users(
id int primary key auto_increment,
name varchar(255),
email varchar(255),
created_at datetime
);

create table posts(
id int primary key auto_increment,
name varchar(255),
message text,
created_at datetime,
updated_at datetime
);