CREATE DATABASE IF NOT EXISTS berudi_db;
USE berudi_db;

CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS vehicles (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    horsepower INT NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    available BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reservations (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);

-- Clear existing vehicles
DELETE FROM vehicles;

-- Premium Audi Sports Car Collection with High-Quality Images
INSERT INTO vehicles (model, year, price, horsepower, description, image_url) VALUES
('Audi RS6 Avant', 2023, 2500.00, 600, '4.0 V8 TFSI biturbo engine with 600 horsepower. Quattro all-wheel drive system for maximum performance. Perfect blend of wagon practicality and supercar performance.', 'assets/images/rs6-avant.jpg'),

('Audi RS7 Sportback', 2023, 2800.00, 600, '4.0 V8 TFSI biturbo engine with 600 horsepower. Sleek sportback design with cutting-edge technology. Acceleration from 0-60 mph in just 3.6 seconds.', 'assets/images/rs7-sportback.jpg'),

('Audi RS e-tron GT', 2023, 3000.00, 637, 'All-electric high-performance sports car with 637 horsepower. Instant torque delivery and 0-60 mph in 3.1 seconds. Sustainable luxury performance.', 'assets/images/rs-etron-gt.jpg'),

('Audi R8 V10 Coupe', 2023, 3500.00, 620, '5.2 V10 naturally aspirated engine with 620 horsepower. True supercar experience with iconic engine sound. Quattro all-wheel drive for superior handling.', 'assets/images/r8-v10-coupe.jpg'),

('Audi RS Q8', 2023, 2200.00, 600, '4.0 V8 TFSI biturbo engine in an SUV package. 600 horsepower with family practicality. Combines luxury, performance and versatility.', 'assets/images/rs-q8.jpg'),

('Audi TT RS', 2023, 1800.00, 400, '2.5 TFSI 5-cylinder engine with 400 horsepower. Compact sports car with distinctive design. Legendary 5-cylinder turbo engine sound.', 'assets/images/tt-rs.jpg'),

('Audi RS3 Sportback', 2023, 1600.00, 400, '2.5 TFSI 5-cylinder engine with 400 horsepower. Hot hatch performance with premium interior. Perfect for daily driving with weekend thrills.', 'assets/images/rs3-sportback.jpg'),

('Audi R8 V10 Spyder', 2023, 4000.00, 620, '5.2 V10 naturally aspirated engine in convertible form. Open-top supercar experience with 620 horsepower. Feel the wind and hear the V10 symphony.', 'assets/images/r8-v10-spyder.jpg'); 