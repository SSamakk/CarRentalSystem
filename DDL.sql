-- The SERIAL data type: 
-- stores a sequential integer, automatically assigned by the 
-- database server when a new row is inserted.

-- to create database
CREATE DATABASE carrental;
USE carrental;

-- Car table
CREATE TABLE car (
    car_id      VARCHAR(20),
    office_id   INT             NOT NULL,
    model       VARCHAR(30)     NOT NULL,  
    `year`      Year            NOT NULL, 
    price       FLOAT           NOT NULL,
    `status`    ENUM('active', 'out of service', 'rented') DEFAULT 'active', 
    PRIMARY KEY (car_id)
);

-- Customer table
CREATE TABLE `user` (
    user_id      INT          AUTO_INCREMENT,
    fname        VARCHAR(255) NOT NULL,
    lname        VARCHAR(255) NOT NULL,  
    bdate        DATE         NOT NULL,
    caddress     VARCHAR(255) NOT NULL,
    email        VARCHAR(25)  UNIQUE NOT NULL, 
    `password`   VARCHAR(20)  NOT NULL,
    PRIMARY KEY  (user_id)
);

-- Office table
CREATE TABLE office (
    office_id   INT             AUTO_INCREMENT,
    phone       VARCHAR(100)    UNIQUE      NOT NULL,
    email       VARCHAR(25)     UNIQUE      NOT NULL,
    country     VARCHAR(30)     NOT NULL,
    city        VARCHAR(30)     NOT NULL,
    PRIMARY KEY  (office_id)
);

-- Reservation table
CREATE TABLE reservation (
    reservation_id  INT         AUTO_INCREMENT,
    car_id          VARCHAR(20) NOT NULL,
    user_id         INT         NOT NULL,
    reserve_date    DATE        DEFAULT CURRENT_DATE, 
    pickup_date     DATE        NOT NULL,
    return_date     DATE        NOT NULL,
    PRIMARY KEY     (reservation_id)
);

-- Payment table
CREATE TABLE payment (
    payment_id      INT         AUTO_INCREMENT,
    reservation_id  INT         NOT NULL,
    amount          FLOAT       NOT NULL,
    payment_date    DATE        NOT NULL,
    PRIMARY KEY     (payment_id)
);

-- system table
CREATE TABLE system (
    admin_id     INT         AUTO_INCREMENT,
    admin_name   VARCHAR(255) NOT NULL,
    email        VARCHAR(25)  NOT NULL,
    `password`   VARCHAR(20)  NOT NULL,
    PRIMARY KEY     (admin_id)
);


ALTER TABLE car ADD FOREIGN KEY (office_id) REFERENCES office(office_id);
ALTER TABLE reservation ADD FOREIGN KEY (car_id) REFERENCES car(car_id);
ALTER TABLE reservation ADD FOREIGN KEY (user_id) REFERENCES user(user_id);
ALTER TABLE payment ADD FOREIGN KEY (reservation_id) REFERENCES reservation(reservation_id);