CREATE DATABASE IF NOT EXISTS career_advisor;
USE career_advisor;

CREATE TABLE admins (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
);

CREATE TABLE courses (
    id INT NOT NULL AUTO_INCREMENT,
    course_name VARCHAR(150) NOT NULL,
    stream VARCHAR(50) NOT NULL,
    description TEXT,
    duration VARCHAR(50),
    eligibility TEXT,
    salary_range VARCHAR(100),
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    about_course TEXT,
    career_opportunities TEXT,
    PRIMARY KEY (id),
    KEY idx_stream (stream),
    KEY idx_courses_stream (stream)
);

CREATE TABLE careers (
    id INT NOT NULL AUTO_INCREMENT,
    course_id INT DEFAULT NULL,
    career_name VARCHAR(150) NOT NULL,
    details TEXT,
    salary DECIMAL(10,2) DEFAULT NULL,
    job_roles TEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_course_id (course_id),
    CONSTRAINT careers_ibfk_1
        FOREIGN KEY (course_id)
        REFERENCES courses(id)
        ON DELETE SET NULL
);

CREATE TABLE colleges (
    id INT NOT NULL AUTO_INCREMENT,
    college_name VARCHAR(150) NOT NULL,
    location VARCHAR(100) NOT NULL,
    established_year INT DEFAULT NULL,
    about_college TEXT,
    courses_available TEXT,
    cutoff VARCHAR(100),
    facilities TEXT,
    placement_percentage DECIMAL(5,2),
    website VARCHAR(255),
    email VARCHAR(100),
    phone VARCHAR(20),
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    image VARCHAR(255),
    PRIMARY KEY (id),
    KEY idx_location (location),
    KEY idx_colleges_location (location),
    FULLTEXT KEY ft_search (college_name, location)
);

CREATE TABLE notifications (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    notification_date DATE NOT NULL,
    notification_type VARCHAR(50),
    deadline_date DATE,
    importance INT DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_date (notification_date),
    KEY idx_type (notification_type),
    KEY idx_notifications_date (notification_date)
);

CREATE TABLE profile (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    mobile VARCHAR(20),
    gender VARCHAR(20),
    dob DATE,
    class VARCHAR(20),
    school_name VARCHAR(255),
    state VARCHAR(100),
    preferred_stream VARCHAR(100),
    profile_image VARCHAR(255),
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
);

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    class VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    mobile VARCHAR(20),
    gender VARCHAR(20),
    dob DATE,
    school_name VARCHAR(255),
    state VARCHAR(100),
    preferred_stream VARCHAR(100),
    profile_image VARCHAR(255),
    PRIMARY KEY (id),
    UNIQUE KEY email (email),
    KEY idx_users_email (email)
);

CREATE TABLE quiz_questions (
    id INT NOT NULL AUTO_INCREMENT,
    question TEXT NOT NULL,
    category ENUM('Science','Commerce','Arts') NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

CREATE TABLE quiz_results (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    science_score DECIMAL(5,2) DEFAULT 0.00,
    commerce_score DECIMAL(5,2) DEFAULT 0.00,
    arts_score DECIMAL(5,2) DEFAULT 0.00,
    suggested_stream VARCHAR(50) NOT NULL,
    date TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_user_id (user_id),
    KEY idx_date (date),
    KEY idx_quiz_results_user (user_id),
    CONSTRAINT quiz_results_ibfk_1
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE TABLE saved_colleges (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    college_id INT NOT NULL,
    saved_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY unique_save (user_id, college_id),
    KEY college_id (college_id),
    KEY idx_user_id (user_id),
    CONSTRAINT saved_colleges_ibfk_1
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT saved_colleges_ibfk_2
        FOREIGN KEY (college_id)
        REFERENCES colleges(id)
        ON DELETE CASCADE
);

CREATE TABLE user_interests (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    interest_category VARCHAR(100),
    interest_value VARCHAR(100),
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_user_id (user_id),
    CONSTRAINT user_interests_ibfk_1
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);