CREATE TABLE cars
(
    id     INT AUTO_INCREMENT PRIMARY KEY,
    title  VARCHAR(255)   NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    price  DECIMAL(10, 2) NOT NULL,
    date   DATE           NOT NULL,
    time   TIME           NOT NULL
);