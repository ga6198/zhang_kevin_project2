create table users(
	id int not null auto_increment,
    username varchar(100),
    email varchar(200),
    verified tinyint(4),
    token varchar(100),
    password varchar(255),
    primary key (id)
);

ALTER TABLE users
ADD profile_picture varchar(200);

-- set default profile picture
SET SQL_SAFE_UPDATES = 0;
UPDATE users
SET profile_picture='vanguard_blue.jpg';
SET SQL_SAFE_UPDATES = 1;

select * from users;

-- SET SQL_SAFE_UPDATES = 0;
-- delete from users where id is not null;