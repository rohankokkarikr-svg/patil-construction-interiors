-- ============================================================
-- Construction Engineer Portfolio — Database Schema
-- DB Name: contraction_db
-- ============================================================

CREATE DATABASE IF NOT EXISTS `contraction_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `contraction_db`;

-- ============================================================
-- Table: admin_users
-- ============================================================
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(200) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin: username=admin, password=admin123 (bcrypt)
INSERT INTO `admin_users` (`username`, `password`, `email`) VALUES
('admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com');

-- ============================================================
-- Table: projects
-- ============================================================
CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `category` ENUM('building','structural','infrastructure','internship') NOT NULL DEFAULT 'building',
  `tools_used` VARCHAR(500) DEFAULT NULL,
  `image_path` VARCHAR(300) DEFAULT 'assets/images/projects/default.jpg',
  `gallery_images` TEXT DEFAULT NULL,
  `duration` VARCHAR(100) DEFAULT NULL,
  `role` VARCHAR(150) DEFAULT NULL,
  `client` VARCHAR(200) DEFAULT NULL,
  `location` VARCHAR(200) DEFAULT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `projects` (`title`, `description`, `category`, `tools_used`, `image_path`, `duration`, `role`, `client`, `location`, `is_featured`) VALUES
('Riverside Residential Complex', 'A premium 12-storey residential complex featuring 144 apartments with modern amenities. Designed with RCC frame structure incorporating seismic zone III compliance. The project involved detailed structural analysis, reinforcement design and BOQ preparation for a total built-up area of 48,000 sq ft. Special attention was paid to waterproofing of basements and terrace gardens.', 'building', 'AutoCAD,Revit,STAAD Pro,MS Project', 'assets/images/projects/p1.jpg', 'Jan 2024 – Jun 2024', 'Structural Design Engineer', 'Skyline Developers Pvt. Ltd.', 'Pune, Maharashtra', 1),
('Highway Bridge — NH-48 Expansion', 'Design and construction supervision of a 4-lane flyover bridge spanning 240 metres across the Mula River. The project utilized prestressed concrete box girders and bored pile foundations. Responsible for load analysis, traffic impact assessment and drawing preparation using Civil 3D. Bridge construction was completed 3 weeks ahead of schedule.', 'infrastructure', 'Civil 3D,AutoCAD,STAAD Pro,Primavera P6', 'assets/images/projects/p2.jpg', 'Mar 2023 – Dec 2023', 'Site Engineer & Design Coordinator', 'NHAI (National Highways Authority of India)', 'Pune–Mumbai Expressway', 1),
('Commercial IT Park — Structural Analysis', 'Structural design of a 6-storey commercial IT park with a total built-up area of 86,000 sq ft on a 2-acre campus. Performed wind load and seismic analysis per IS 875 and IS 1893 codes. Designed post-tensioned slabs to minimize column grid spacing for open-plan office floors. Coordinated with MEP consultants for slab penetrations and service routing.', 'structural', 'STAAD Pro,AutoCAD,Revit,Excel', 'assets/images/projects/p3.jpg', 'Aug 2023 – Feb 2024', 'Structural Analyst', 'TechHaven Infrastructure Ltd.', 'Hinjewadi, Pune', 1),
('Rural Road Development — Palghar District', 'Construction of 18 km of rural roads under the Pradhan Mantri Gram Sadak Yojana (PMGSY) scheme. Prepared DPR (Detailed Project Report), cross-section drawings and material schedules. Supervised subgrade preparation, WBM and BT surfacing. Ensured quality control through field density tests and CBR testing as per MoRTH specifications.', 'infrastructure', 'AutoCAD,Civil 3D,MS Excel', 'assets/images/projects/p4.jpg', 'Jun 2022 – Nov 2022', 'Site Supervisor (Internship)', 'PWD Maharashtra', 'Palghar, Maharashtra', 0),
('Industrial Warehouse — Steel Frame', 'Pre-engineered steel structure warehouse of 12,000 sq ft for a logistics company. Coordinated with PEBS vendor for design drawings, anchor bolt layout and erection sequence. Supervised foundation work including isolated footings design and concrete quality monitoring. Project delivered within ₹1.2 Cr budget with zero site accidents.', 'building', 'AutoCAD,STAAD Pro,MS Project', 'assets/images/projects/p5.jpg', 'Sep 2023 – Jan 2024', 'Junior Site Engineer', 'LogiPark Solutions Pvt. Ltd.', 'Bhosari MIDC, Pune', 0),
('BIM Model — University Academic Block', 'Created a complete Building Information Model (BIM Level 2) for a proposed 4-storey academic block at a private university. Model includes Architectural, Structural and MEP disciplines. Generated coordinated 2D drawings, clash-detection reports and quantity takeoffs directly from the Revit model. Used Navisworks for 4D simulation and scheduling integration.', 'building', 'Revit,Navisworks,AutoCAD,BIM 360', 'assets/images/projects/p6.jpg', 'Oct 2023 – Dec 2023', 'BIM Coordinator (Academic Project)', 'Self-Initiated Academic Project', 'Pune, Maharashtra', 0);

-- ============================================================
-- Table: certifications
-- ============================================================
CREATE TABLE IF NOT EXISTS `certifications` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `issuer` VARCHAR(200) NOT NULL,
  `category` ENUM('academic','professional','software','training','internship') NOT NULL DEFAULT 'professional',
  `issue_date` DATE NOT NULL,
  `expiry_date` DATE DEFAULT NULL,
  `credential_id` VARCHAR(200) DEFAULT NULL,
  `verify_url` VARCHAR(500) DEFAULT NULL,
  `image_path` VARCHAR(300) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `certifications` (`title`, `issuer`, `category`, `issue_date`, `expiry_date`, `credential_id`, `verify_url`) VALUES
('Autodesk Certified Professional — Revit Structure', 'Autodesk', 'software', '2023-08-15', '2026-08-15', 'ACP-RS-2023-8842', 'https://www.autodesk.com/certification'),
('STAAD.Pro Advanced Structural Analysis', 'Bentley Systems', 'software', '2023-03-20', '2026-03-20', 'BSY-STAAD-2023-4421', 'https://www.bentley.com'),
('Project Management Professional (PMP) Foundation', 'PMI India', 'professional', '2023-11-10', NULL, 'PMI-FND-2023-77291', 'https://www.pmi.org'),
('AutoCAD Civil 3D Infrastructure Design', 'Autodesk', 'software', '2022-07-05', '2025-07-05', 'ACP-C3D-2022-3301', 'https://www.autodesk.com/certification'),
('Site Safety Management — Construction Sites', 'National Safety Council of India', 'training', '2024-01-18', '2027-01-18', 'NSCI-SSM-2024-0099', 'https://www.nsc.org.in');

-- ============================================================
-- Table: contacts
-- ============================================================
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(200) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `subject` VARCHAR(300) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Table: appointments
-- ============================================================
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(200) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `project_type` VARCHAR(200) DEFAULT NULL,
  `preferred_date` DATE NOT NULL,
  `preferred_time` TIME NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `status` ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
