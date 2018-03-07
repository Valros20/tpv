/*+++++++++++++++CREAR LAS BASES DE DATOS++++++++++++++++++++++++++++*/
create database tpv
default character set utf8
collate utf8_unicode_ci;

/*++++++++++++++++CREAR AL USUARIO ADMINISTRADOR DE LA BD++++++++++++++++++++++++*/
create user utpv@'localhost'
identified by 'ctpv';

grant all           --Se le da acceso
on tpv.* to        --A esta base de datos
utpv@localhost;    --A este usuaio

flush privileges;


use tpv;

/*+++++++++++++++++CREAR TABLAS+++++++++++++++++++++++*/
create table if not exists member (
    id bigint not null auto_increment primary key,
    login varchar(40) not null unique,
    password varchar(250) not null
)engine=innodb default character set = utf8 collate utf8_unicode_ci;

create table if not exists client (
    id bigint not null auto_increment primary key,
    name varchar(40) not null,
    surname varchar(60) not null,
    tin varchar(20) not null,
    address varchar(100) not null,
    location varchar(100) not null,
    postalcode varchar(5),
    province varchar(30),
    email varchar(100) not null,
    unique (name, surname, tin)
)engine=innodb default character set = utf8 collate utf8_unicode_ci;

create table if not exists family(
    id bigint auto_increment not null primary key,
    family varchar(100) unique
)engine=innodb default character set = utf8 collate utf8_unicode_ci;

create table if not exists ticket(
    id bigint not null auto_increment primary key,
    date timestamp default current_timestamp on update current_timestamp not null,
    idmember bigint(20) not null,
    idclient bigint(20),
    foreign key (idmember) references member (id) on delete restrict,
    foreign key (idclient) references client (id) on delete restrict
)engine=innodb default character set = utf8 collate utf8_unicode_ci;

create table if not exists product(
    id bigint not null auto_increment primary key,
    idfamily bigint(20) not null,
    product varchar (100) not null,
    price decimal(10,2) not null,
    description text,
    unique (idfamily, product),
    foreign key (idfamily) references family (id) on delete restrict
) engine = innodb default character set = utf8 collate utf8_unicode_ci;

create table if not exists ticketdetail(
    id bigint not null auto_increment primary key,
    idticket bigint(20) not null,
    idproduct bigint (20) not null,
    quantity tinyint(4) not null,
    price decimal(10,2) not null,
    foreign key (idticket) references ticket (id)  on delete restrict,
    foreign key (idproduct) references product (id)  on delete restrict
)engine=innodb default character set =utf8 collate utf8_unicode_ci;

insert into family(family) values('pan');
insert into family(family) values('bolleria');
insert into family(family) values('croissant');
insert into family(family) values('navidad');
insert into family(family) values('otros');