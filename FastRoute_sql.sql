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

-- spedizioni
create table spedizioni(
                           personale varchar(50),
                           codice_plico int,
                           data datetime,
                           primary key(personale, codice_plico),
                           foreign key (personale) references personale(codice_fiscale),
                           foreign key (codice_plico) references plichi(codice)
);

drop database fastroute;

show tables;


-- sedi di default

INSERT INTO sedi (nome, citta) VALUES
                                   ('Sede Milano', 'Milano'),
                                   ('Sede Roma', 'Roma'),
                                   ('Sede Napoli', 'Napoli'),
                                   ('Sede Torino', 'Torino'),
                                   ('Sede Bologna', 'Bologna'),
                                   ('Sede Firenze', 'Firenze'),
                                   ('Sede Venezia', 'Venezia'),
                                   ('Sede Palermo', 'Palermo'),
                                   ('Sede Genova', 'Genova'),
                                   ('Sede Bari', 'Bari');

-- Inserimento stati di default
INSERT INTO stati (nome) VALUES
                             ('in partenza'),
                             ('in transito'),
                             ('consegnato');

--  personale di default
/*INSERT INTO personale (codice_fiscale, nome, mail, password, sede) VALUES
('CF123456789', 'Giovanni Rossi', 'giovanni.rossi@email.com', 'Password123!', 'Sede Milano'),
('CF987654321', 'Maria Bianchi', 'maria.bianchi@email.com', 'Password123!', 'Sede Roma'),
('CF112233445', 'Luca Verdi', 'luca.verdi@email.com', 'Password123!', 'Sede Napoli'),
('CF998877665', 'Anna Gialli', 'anna.gialli@email.com', 'Password123!', 'Sede Torino'),
('CF667788991', 'Marco Blu', 'marco.blu@email.com', 'Password123!', 'Sede Bologna'),
('CF223344556', 'Elena Rosa', 'elena.rosa@email.com', 'Password123!', 'Sede Firenze'),
('CF334455667', 'Stefano Azzurri', 'stefano.azzurri@email.com', 'Password123!', 'Sede Venezia'),
('CF445566778', 'Paola Neri', 'paola.neri@email.com', 'Password123!', 'Sede Palermo'),
('CF556677889', 'Giulia Verde', 'giulia.verde@email.com', 'Password123!', 'Sede Genova'),
('CF667788992', 'Francesco Arancio', 'francesco.arancio@email.com', 'Password123!', 'Sede Bari');*/


select * from personale;
select * from clienti;

delete from clienti;
delete from personale;