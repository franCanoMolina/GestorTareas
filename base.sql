CREATE DATABASE IF NOT EXISTS task_manager
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE task_manager;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NULL,
    is_completed BOOLEAN NOT NULL DEFAULT FALSE,
    user_id INT NOT NULL,
    CONSTRAINT fk_tasks_users
        FOREIGN KEY (user_id) 
        REFERENCES users(id) 
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_tasks_user_id ON tasks(user_id);