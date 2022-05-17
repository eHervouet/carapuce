DROP TABLE site;
DROP TABLE vehicle;
DROP TABLE vehicle_key;
DROP TABLE passenger;
DROP TABLE loan;
DROP TABLE person;

CREATE TABLE site
(
    id int primary key AUTO_INCREMENT,
    address varchar(128)
)ENGINE=InnoDB;

CREATE TABLE vehicle
(
    id int primary key AUTO_INCREMENT,
    brand varchar(32) not null,
    model varchar(32) not null,
    nb_places int not null,
    id_site int not null,

    constraint fk_vehicle_id_site FOREIGN KEY (id_site) REFERENCES site(id)
)ENGINE=InnoDB;

CREATE TABLE vehicle_key
(
    id int primary key AUTO_INCREMENT,
    id_vehicle int,

    constraint fk_key_id_vehicle FOREIGN KEY (id_vehicle) REFERENCES vehicle(id)
)ENGINE=InnoDB;

CREATE TABLE person
(
    id int primary key AUTO_INCREMENT,
    first_name varchar(32) not null,
    last_name varchar(32) not null,
    address varchar(128) not null,
    age int not null,
    phone_number int,
    email varchar(128) not null,
    password varchar(128) not null

)ENGINE=InnoDB;

CREATE TABLE loan
(
    id int primary key AUTO_INCREMENT,
    depart_date datetime not null,
    return_date datetime not null,
    return_vehicle boolean,
    return_key boolean,
    destination_address varchar(128) not null,
    driver int not null,
    affected_vehicle int not null,

    constraint fk_loan_driver FOREIGN KEY (driver) REFERENCES person(id),
    constraint fk_loan_affected_vehicle FOREIGN KEY (affected_vehicle) REFERENCES vehicle(id)

)ENGINE=InnoDB;

CREATE TABLE passenger
(
    id_loan int,
    id_person int,

    primary key(id_loan,id_person),

    constraint fk_passenger_id_loan FOREIGN KEY (id_loan) REFERENCES loan(id),
    constraint fk_passenger_id_person FOREIGN KEY (id_person) REFERENCES person(id)    
)ENGINE=InnoDB;