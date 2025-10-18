-- Create Database
CREATE DATABASE IF NOT EXISTS smarthire;
USE smarthire;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    education VARCHAR(100),
    experience INT DEFAULT 0,
    preferred_location VARCHAR(100),
    resume_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User Skills Table
CREATE TABLE user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Jobs Table
CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    company VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    salary VARCHAR(50),
    description TEXT,
    required_skills TEXT,
    status VARCHAR(20) DEFAULT 'active',
    posted_date DATE DEFAULT (CURRENT_DATE)
);

-- Applications Table
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    match_percentage INT,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

-- Insert Sample Jobs
INSERT INTO jobs (title, company, location, salary, description, required_skills) VALUES
('Python Developer', 'Tech Solutions', 'Bangalore', '6-10 LPA', 'Develop Python applications and APIs', 'Python,Django,SQL,REST API'),
('Data Analyst', 'DataCorp', 'Hyderabad', '5-8 LPA', 'Analyze data and create reports', 'Python,SQL,Excel,Power BI'),
('Frontend Developer', 'WebWorks', 'Mumbai', '5-9 LPA', 'Build responsive websites', 'HTML,CSS,JavaScript,React'),
('Machine Learning Engineer', 'AI Labs', 'Pune', '8-15 LPA', 'Develop ML models', 'Python,Machine Learning,TensorFlow'),
('Full Stack Developer', 'StartupHub', 'Bangalore', '7-12 LPA', 'Work on web applications', 'JavaScript,React,Node.js,MongoDB'),
('Java Developer', 'Enterprise Tech', 'Chennai', '5-9 LPA', 'Build Java applications', 'Java,Spring,MySQL'),
('DevOps Engineer', 'CloudTech', 'Gurgaon', '8-14 LPA', 'Manage cloud infrastructure', 'AWS,Docker,Linux,Git'),
('Data Scientist', 'Analytics Hub', 'Bangalore', '10-18 LPA', 'Build data models', 'Python,Machine Learning,SQL'),
('React Developer', 'Digital Agency', 'Mumbai', '6-11 LPA', 'Create React applications', 'JavaScript,React,HTML,CSS'),
('Backend Developer', 'Tech Systems', 'Bangalore', '7-13 LPA', 'Build server-side applications', 'Node.js,MongoDB,Express');