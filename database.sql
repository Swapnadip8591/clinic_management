-- Create the database
CREATE DATABASE IF NOT EXISTS clinic_management;
USE clinic_management;

-- Create the Users table for login
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Role ENUM('doctor', 'assistant') NOT NULL
);

-- Create the Patients table
CREATE TABLE IF NOT EXISTS Patients (
    PatientID INT AUTO_INCREMENT PRIMARY KEY,
    PatientName VARCHAR(100) NOT NULL,
    Age INT NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    ContactNumber VARCHAR(15) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Address TEXT,
    MedicalHistory TEXT
);

-- Create the Medicines table
CREATE TABLE IF NOT EXISTS Medicines (
    MedicineID INT AUTO_INCREMENT PRIMARY KEY,
    MedicineName VARCHAR(100) NOT NULL,
    ManufacturingDate DATE NOT NULL,
    ExpiryDate DATE NOT NULL,
    Company VARCHAR(100) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL
);

-- Create the Visits table
CREATE TABLE IF NOT EXISTS Visits (
    VisitID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT NOT NULL,
    UserID INT,
    VisitDate DATE NOT NULL,
    DiseaseDiagnosed VARCHAR(255) NOT NULL,
    PrescriptionDetails TEXT,
    FeeCharged INT(3) NOT NULL,
    FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create the Receipts table
CREATE TABLE IF NOT EXISTS Receipts (
    ReceiptID INT AUTO_INCREMENT PRIMARY KEY,
    VisitID INT NOT NULL,
    ReceiptDetails TEXT NOT NULL,
    TotalAmount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (VisitID) REFERENCES Visits(VisitID)
);
