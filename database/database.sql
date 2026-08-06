CREATE DATABASE IF NOT EXISTS online_cinema;
USE online_cinema;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  balance DECIMAL(10,2) NOT NULL DEFAULT 1000.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS movies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  category VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  poster VARCHAR(255) NOT NULL,
  video_file VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_movies_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS purchases (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  movie_id INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_purchases_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_purchases_movie FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
  INDEX idx_purchases_user (user_id),
  INDEX idx_purchases_movie (movie_id),
  UNIQUE KEY uq_purchase_user_movie (user_id, movie_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
