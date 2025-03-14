create database FastRoute;

use FastRoute;


-- stato plichi 
create table stati(
nome varchar(50) primary key
);

-- email conferma
create table email_conferma(
	id int primary key auto_increment,
	descrizione varchar(250)
);

-- sede 
create table sedi(
nome varchar(50) primary key,
citta varchar(50)
);

-- personale
create table personale(
codice_fiscale varchar(50) primary key,
nome varchar(100),
mail varchar(100),
password varchar(100),
sede varchar(50),
foreign key (sede) references sedi(nome)
);


-- plichi 
create table plichi(
codice int primary key,
id_email int, 
stato varchar(50),
foreign key (id_email) references email_conferma(id),
foreign key (stato) references stati(nome)
);


-- tabella relazione "riporre" (plichi e sedi)
create table plichi_sedi(
nome_sede varchar(50),
codice_plico int,
primary key(nome_sede, codice_plico),
foreign key (nome_sede) references sedi(nome),
foreign key (codice_plico) references plichi(codice)
);

-- clienti
create table clienti(
codice_fiscale varchar(50) primary key,
nome varchar(100),
cognome varchar(100),
indirizzo varchar(100),
mail varchar(100),
punteggio int
);


-- destinatari
create table destinatari(
codice_fiscale varchar(50) primary key,
nome varchar(100),
cognome varchar(100)
);



-- consegne
create table consegne(
cliente varchar(50),
codice_plico int,
data datetime,
primary key(cliente, codice_plico),
foreign key (cliente) references clienti(codice_fiscale),
foreign key (codice_plico) references plichi(codice)
);

-- ritiri
create table ritiri(
destinatario varchar(50),
codice_plico int,
data datetime,
primary key(destinatario, codice_plico),
foreign key (destinatario) references destinatari(codice_fiscale),
foreign key (codice_plico) references plichi(codice)
);


drop database fastroute;

show tables;

