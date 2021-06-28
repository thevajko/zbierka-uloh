create table messages
(
    id int auto_increment
        primary key,
    message text not null,
    created datetime default current_timestamp() null,
    user varchar(100) null,
    private_for varchar(100) null
);

create table users
(
    id int auto_increment
        primary key,
    name varchar(100) not null
);