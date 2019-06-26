create table users(
	id int not null auto_increment,
    username varchar(100),
    email varchar(200),
    verified tinyint(4),
    token varchar(100),
    password varchar(255),
    primary key (id)
)

select * from users;

-- SET SQL_SAFE_UPDATES = 0;
-- delete from users where id is not null;