create database pokedex

use pokedex 

create table tipo (
	tipo_id int not null auto_increment,
	nombre_tipo varchar(50),
	color_fondo varchar(50),
	color_letra varchar(50),
	primary key (tipo_id)
)

create table pokemon (
	pokemon_id int not null auto_increment,
	nombre varchar(50) not null,
	numero varchar(500) not null,
	imagen varchar(105),
	primary key (pokemon_id)
)

create table rel_pokemon_tipo (
	rel_pokemon_tipo_id int not null auto_increment,
	tipo_id int not null,
	pokemon_id int not null,
	primary key (rel_pokemon_tipo_id),
	foreign key(tipo_id) references tipo(tipo_id),
	foreign key(pokemon_id) references pokemon(pokemon_id)
)

