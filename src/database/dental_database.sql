-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 02:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dental_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `firstname`, `lastname`, `email`, `contact`, `gender`, `password`, `img`, `status`) VALUES
(1018082519, 'max', 'verstappen', 'max1@gmail.com', '143143143', 'Male', '$2y$10$N3yW0tkpwkTGklHAYDbk3.lQAxrcSUhRriaisDDhwwpYBPWOCVyAy', 'exclamation.png', 'Active Now'),
(1029835186, 'emman', 'minendez', 'emman@gmail.com', '090909090909', 'Male', '$2y$10$y8/UN1.bEBPIL2lO17UNoueKBG4ZXNWM8W1MHFm8HMIjDBfAB1UGS', 'jb.jpg', NULL),
(1072582237, 'spike', 'spike', 'spike12@gmail.com', '09295527474', 'Male', '8dfaff49e7bca946c8891480af9569b7', '6761962109838_default_profile.png', 'Inactive'),
(1076722162, 'miguel', 'polison', 'miguel@gmail.com', '0929552474', 'Male', '$2y$10$pqBVh6Hwa8n3JIljoiJAF.q6e0gzE56AzkV0xxsdcqlK1pSO24XS.', 'db.jpg', 'Active Now'),
(1104929486, 'nathan', 'anire', 'nathan@gmail.com', '123123', 'Male', '202cb962ac59075b964b07152d234b70', '6761497de199d_db1.jpg', 'Inactive'),
(1164252498, 'sky', 'clark', 'sky@gmail.com', '0929552747', 'Male', '$2y$10$roMJL5WIKce/SAl0TYuwVud1Mv3Xjh4HuUh9IzYb8PQx.KQGMrcei', 'db3.jpg', NULL),
(1193495411, 'Sydney', 'sweeney', 'Sydney1@students.com', '0911223344', 'Female', '$2y$10$pFbpbqVPyTPaUl/AcpEC6e6CXicaPa74RF3Hsf/Qgtgj90CxsNdbq', 'syd.jpg', 'Active Now'),
(1220888698, 'asd', 'asd', 'asd@gmail.com', 'asd', 'Male', '7815696ecbf1c96e6894b779456d330e', '6761820a2199f_correct.png', 'Inactive'),
(1259099870, 'miguel', 'miguel', 'miguel123@gmail.com', '123123123123', 'Male', '$2y$10$QgQ6pDmoQOWKfjWszI1vHeK6u1Mjeq.bCQbawgDV8U386qJ7kFrhq', '67619255cbaa7_default_profile.png', 'Active Now'),
(1270683752, 'mico', 'earl', 'mico@gmail.com', '143143143', 'Male', '$2y$10$PEJbxmcjoWZ7hjuZB/rvkeB6y.f8cp31XftmHv8NtU6qyUWCB4lta', 'db3.jpg', NULL),
(1277730194, 'brando', 'allen', 'brando1@gmail.com', '143143143', 'Male', '$2y$10$wzg24lwLIunneR5F050m8.U1DIWMDfByMQWLDxzbeU8RsHLxfLUCm', 'db.jpg', NULL),
(1290148401, 'spike', 'cleah', 'spike@gmail.com', '123', 'Male', '202cb962ac59075b964b07152d234b70', '67614882b3510_db.jpg', 'Inactive'),
(1312843924, 'Morie', 'Go', 'morie@gmail.com', '143143143143', 'Male', '$2y$10$5e7InvFQ1zpPAcm4oAvvBu/j5mbaQC6BmklwtQ0XkK58bmIEuFPXi', 'default_profile.png', NULL),
(1337710609, 'mello', 'marsh', 'mello2@gmail.com', '09090909', 'Male', '0876151896507a427d14fb49e436d8fd', '67619058389d6_db1.jpg', 'Inactive'),
(1350862572, 'go', 'morie', 'go@gmail.com', '123', 'Male', '$2y$10$Un69rB0B28.LGgJCWDASC.gnzQ7ITDdz2GD70PwRXRcVbwRpFi2oy', '6761441368ece_default_profile.png', 'Inactive'),
(1371178825, 'earl', 'mendilio', 'earl@gmail.com', '143143143143', 'Male', 'a5307dcdfef4c4ecbb514b1670774b05', '67617cb3bce51_default_profile.png', 'Inactive'),
(1420974310, 'cleah', 'dog', 'dog1@gmail.com', '0909090902222', 'Male', '6be237a000de33e2076acc5c25c36617', '67618142902ce_cleah.jpg', 'Inactive'),
(1443535739, 'cess', 'cess', 'cess@gmail.com', '123123', '$2y$10$9aOuEZmfu2UKyCQ84LD.CORSay6dRzJxxFhJ14w9i2b8KVYNaPhWG', 'Male', '67613680a947d_exclamation.png', 'Inactive'),
(1460152085, 'charles', 'leclerc', 'charles1@gmail.com', '143143143', 'Male', '$2y$10$1/fKZLV635KjVD/kQ/m2XeoAKpa2fD1wB/fZoyv2b2GCLSUa.VlXG', 'db3.jpg', NULL),
(1474557457, 'Liza', 'Soberano', 'liza@gmail.com', '123', 'Male', '$2y$10$JWHQ7dG./Pc7sG.EmtUnMekcEOhqrOOJPGSYNHXCdUMw9OwutpPjy', '676141af58ebc_lizamylove.jpg', 'Inactive'),
(1573910519, 'rich', 'richie', 'rich@gmail.com', '1230000000', 'Male', '$2y$10$xfDaj9Sr905597IB9JXsb.gCT8Dt2bFda6/XYS6ZVdRQshaxfYWOW', '6761436300f49_default_profile.png', 'Inactive'),
(1583212222, 'cleah', 'cleah', 'cleah12@gmail.com', '123123123123', 'Male', '$2y$10$8Xis.1ADJ0cTJtErUE02U.LsbXBVxiPxKrUqwoV7v.l/BK2n4mcDm', '67619389141eb_default_profile.png', 'Active Now'),
(1583489945, 'spike', 'cleah', 'spike_cleah@gmail.com', '123', 'Male', '202cb962ac59075b964b07152d234b70', '6761490383969_cleah.jpg', 'Inactive'),
(1616317463, 'sainz', 'carlos', 'sainz@gmail.com', '143143143', 'Female', '$2y$10$YnJURtN/8xyYSWq5QuQy6.KgVLpfQHwhN4SVq8eWBY6qs.S1Zi432', 'db3.jpg', NULL),
(1662381892, 'megel', 'megel', 'megel@gmail.com', '143143143', 'Male', '$2y$10$bfMPn1RgV1vdpoXtzOb0eOeSh2ZKkW36DvwfbQAEuyT.CHiTU0XdG', 'db1.jpg', 'Active Now'),
(1719531779, 'marsh', 'mello', 'mello@gmail.com', '143143143', 'Male', '$2y$10$EwZZcZXuj1kNtZq7gwpfm.hC2lSu3bVFRewTgNFBvlH/fNWC29dWW', 'db.jpg', 'Active Now');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_list`
--

CREATE TABLE `appointment_list` (
  `patient_id` varchar(10) NOT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `requested_date` date DEFAULT NULL,
  `requested_time` time DEFAULT NULL,
  `date_of_request` date DEFAULT NULL,
  `requested_dentist` varchar(100) DEFAULT NULL,
  `reason_for_booking` varchar(255) DEFAULT NULL,
  `appointment_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_list`
--

INSERT INTO `appointment_list` (`patient_id`, `patient_name`, `payment_type`, `payment_status`, `requested_date`, `requested_time`, `date_of_request`, `requested_dentist`, `reason_for_booking`, `appointment_status`) VALUES
('PAT00204', 'Michael T. Lee', 'Online', 'Paid', '2024-12-28', '12:00:00', '2024-12-23', 'Dr. Sarah C. Davis', 'Dental cleaning', 'Completed'),
('PAT00205', 'Sophia L. Martinez', 'Cash-on-site', 'Paid', '2024-12-29', '13:00:00', '2024-12-24', 'Dr. Robert E. Wilson', 'Cavity filling', 'Completed'),
('PAT00206', 'James K. Anderson', 'Online', 'Paid', '2024-12-30', '14:00:00', '2024-12-25', 'Dr. Laura M. Clark', 'Wisdom tooth extraction', 'Completed'),
('PAT00207', 'Olivia N. Harris', 'Cash-on-site', 'Paid', '2024-12-31', '15:00:00', '2024-12-26', 'Dr. William J. Lewis', 'Braces adjustment', 'Completed'),
('PAT00208', 'Liam P. Walker', 'Online', 'Paid', '2025-01-01', '16:00:00', '2024-12-27', 'Dr. Emma L. Hall', 'Teeth whitening', 'Completed'),
('PAT00209', 'Isabella Q. Young', 'Cash-on-site', 'Paid', '2024-12-25', '17:00:00', '2024-12-28', 'Dr. Daniel K. Allen', 'Root canal treatment', 'Completed'),
('PAT00210', 'Noah R. King', 'Online', 'Paid', '2024-12-26', '18:00:00', '2024-12-29', 'Dr. Sophia M. Scott', 'Dental implant consultation', 'Completed'),
('PAT00211', 'Mia S. Wright', 'Cash-on-site', 'Pending', '2024-12-27', '09:00:00', '2024-12-20', 'Dr. Benjamin N. Green', 'Gum disease treatment', 'Cancelled'),
('PAT00212', 'Lucas T. Baker', 'Online', 'Pending', '2024-12-28', '10:00:00', '2024-12-21', 'Dr. Ava O. Adams', 'Veneers consultation', 'Cancelled'),
('PAT00213', 'Charlotte U. Nelson', 'Cash-on-site', 'Pending', '2024-12-29', '11:00:00', '2024-12-22', 'Dr. Henry P. Carter', 'Dentures fitting', 'Cancelled'),
('PAT00214', 'Mason V. Hill', 'Online', 'Pending', '2024-12-30', '12:00:00', '2024-12-23', 'Dr. Mia Q. Mitchell', 'Orthodontic consultation', 'Cancelled'),
('PAT00215', 'Evelyn W. Roberts', 'Cash-on-site', 'Pending', '2024-12-31', '13:00:00', '2024-12-24', 'Dr. Alexander R. Perez', 'Teeth cleaning', 'Cancelled'),
('PAT00216', 'Ethan X. Turner', 'Online', 'Pending', '2025-01-01', '14:00:00', '2024-12-25', 'Dr. Isabella S. Collins', 'Tooth extraction', 'Cancelled'),
('PAT00217', 'Harper Y. Phillips', 'Cash-on-site', 'Pending', '2024-12-25', '15:00:00', '2024-12-26', 'Dr. Matthew T. Edwards', 'Dental check-up', 'Cancelled'),
('PAT00218', 'Aiden Z. Campbell', 'Online', 'Pending', '2024-12-26', '16:00:00', '2024-12-27', 'Dr. Emily U. Morris', 'Crown fitting', 'Cancelled'),
('PAT00219', 'Abigail A. Parker', 'Cash-on-site', 'Pending', '2024-12-27', '17:00:00', '2024-12-28', 'Dr. Joshua V. Stewart', 'Bridge consultation', 'Cancelled'),
('PAT00220', 'Sebastian B. Evans', 'Online', 'Pending', '2024-12-28', '18:00:00', '2024-12-29', 'Dr. Olivia W. Rogers', 'Teeth alignment', 'Cancelled'),
('PAT00221', 'Victoria I. Torres', 'Cash-on-site', 'Pending', '2024-12-25', '09:00:00', '2024-12-20', 'Dr. Jacob X. Cook', 'Dental cleaning', 'Upcoming'),
('PAT00222', 'Jackson J. Ramirez', 'Online', 'Paid', '2024-12-26', '10:00:00', '2024-12-21', 'Dr. Charlotte Y. Bell', 'Toothache consultation', 'Upcoming'),
('PAT00223', 'Scarlett K. Diaz', 'Cash-on-site', 'Pending', '2024-12-27', '11:00:00', '2024-12-22', 'Dr. James Z. Murphy', 'Routine check-up', 'Upcoming'),
('PAT00224', 'Levi L. Hughes', 'Online', 'Paid', '2024-12-28', '12:00:00', '2024-12-23', 'Dr. Amelia A. Reed', 'Dental cleaning', 'Upcoming'),
('PAT00225', 'Grace G. Cox', 'Cash-on-site', 'Pending', '2024-12-29', '13:00:00', '2024-12-24', 'Dr. Michael B. Howard', 'Cavity filling', 'Upcoming'),
('PAT00226', 'Henry H. Ward', 'Online', 'Paid', '2024-12-30', '14:00:00', '2024-12-25', 'Dr. Sophia C. Brooks', 'Wisdom tooth extraction', 'Upcoming'),
('PAT00227', 'Victoria I. Torres', 'Cash-on-site', 'Pending', '2024-12-31', '15:00:00', '2024-12-26', 'Dr. William D. Foster', 'Braces adjustment', 'Upcoming'),
('PAT00228', 'Jackson J. Ramirez', 'Online', 'Paid', '2025-01-01', '16:00:00', '2024-12-27', 'Dr. Emma E. Bryant', 'Teeth whitening', 'Upcoming'),
('PAT00229', 'Scarlett K. Diaz', 'Cash-on-site', 'Pending', '2024-12-25', '17:00:00', '2024-12-28', 'Dr. Daniel F. Hayes', 'Root canal treatment', 'Upcoming'),
('PAT00230', 'Levi L. Hughes', 'Online', 'Paid', '2024-12-26', '18:00:00', '2024-12-29', 'Dr. Sophia G. Jenkins', 'Dental implant consultation', 'Upcoming'),
('PAT00231', 'Grace G. Cox', 'Cash-on-site', 'Pending', '2024-12-27', '09:00:00', '2024-12-20', 'Dr. Benjamin N. Green', 'Gum disease treatment', 'Upcoming'),
('PAT00232', 'Henry H. Ward', 'Online', 'Paid', '2024-12-28', '10:00:00', '2024-12-21', 'Dr. Ava O. Adams', 'Veneers consultation', 'Upcoming'),
('PAT00233', 'Victoria I. Torres', 'Cash-on-site', 'Pending', '2024-12-29', '11:00:00', '2024-12-22', 'Dr. Henry P. Carter', 'Dentures fitting', 'Upcoming'),
('PAT00234', 'Jackson J. Ramirez', 'Online', 'Paid', '2024-12-30', '12:00:00', '2024-12-23', 'Dr. Mia Q. Mitchell', 'Orthodontic consultation', 'Upcoming'),
('PAT00235', 'Scarlett K. Diaz', 'Cash-on-site', 'Pending', '2024-12-31', '13:00:00', '2024-12-24', 'Dr. Alexander R. Perez', 'Teeth cleaning', 'Upcoming'),
('PAT00236', 'Levi L. Hughes', 'Online', 'Paid', '2025-01-01', '14:00:00', '2024-12-25', 'Dr. Isabella S. Collins', 'Tooth extraction', 'Upcoming'),
('PAT00237', 'Grace G. Cox', 'Cash-on-site', 'Pending', '2024-12-25', '15:00:00', '2024-12-26', 'Dr. Matthew T. Edwards', 'Dental check-up', 'Upcoming'),
('PAT00238', 'Henry H. Ward', 'Online', 'Paid', '2024-12-26', '16:00:00', '2024-12-27', 'Dr. Emily U. Morris', 'Crown fitting', 'Upcoming'),
('PAT00239', 'Victoria I. Torres', 'Cash-on-site', 'Pending', '2024-12-27', '17:00:00', '2024-12-28', 'Dr. Joshua V. Stewart', 'Bridge consultation', 'Upcoming'),
('PAT00240', 'Jackson J. Ramirez', 'Online', 'Paid', '2024-12-28', '18:00:00', '2024-12-29', 'Dr. Olivia W. Rogers', 'Teeth alignment', 'Upcoming'),
('PAT00241', 'Scarlett K. Diaz', 'Cash-on-site', 'Pending', '2024-12-29', '09:00:00', '2024-12-20', 'Dr. Benjamin N. Green', 'Gum disease treatment', 'Upcoming'),
('PAT00242', 'Levi L. Hughes', 'Online', 'Paid', '2024-12-30', '10:00:00', '2024-12-21', 'Dr. Ava O. Adams', 'Veneers consultation', 'Upcoming'),
('PAT00243', 'Grace G. Cox', 'Cash-on-site', 'Pending', '2024-12-31', '11:00:00', '2024-12-22', 'Dr. Henry P. Carter', 'Dentures fitting', 'Upcoming'),
('PAT00244', 'Henry H. Ward', 'Online', 'Paid', '2025-01-01', '12:00:00', '2024-12-23', 'Dr. Mia Q. Mitchell', 'Orthodontic consultation', 'Upcoming'),
('PAT00245', 'Victoria I. Torres', 'Cash-on-site', 'Pending', '2024-12-25', '13:00:00', '2024-12-24', 'Dr. Alexander R. Perez', 'Teeth cleaning', 'Upcoming'),
('PAT00246', 'Jackson J. Ramirez', 'Online', 'Paid', '2024-12-26', '14:00:00', '2024-12-25', 'Dr. Isabella S. Collins', 'Tooth extraction', 'Upcoming'),
('PAT00247', 'Scarlett K. Diaz', 'Cash-on-site', 'Pending', '2024-12-27', '15:00:00', '2024-12-26', 'Dr. Matthew T. Edwards', 'Dental check-up', 'Upcoming'),
('PAT00248', 'Levi L. Hughes', 'Online', 'Paid', '2024-12-28', '16:00:00', '2024-12-27', 'Dr. Emily U. Morris', 'Crown fitting', 'Upcoming'),
('PAT00249', 'Grace G. Cox', 'Cash-on-site', 'Pending', '2024-12-29', '17:00:00', '2024-12-28', 'Dr. Joshua V. Stewart', 'Bridge consultation', 'Upcoming'),
('PAT00250', 'Henry H. Ward', 'Online', 'Paid', '2024-12-30', '18:00:00', '2024-12-29', 'Dr. Olivia W. Rogers', 'Teeth alignment', 'Upcoming');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_request`
--

CREATE TABLE `appointment_request` (
  `PatientID` varchar(10) NOT NULL,
  `PatientName` varchar(100) DEFAULT NULL,
  `PaymentType` varchar(50) DEFAULT NULL,
  `PaymentStatus` varchar(50) DEFAULT NULL,
  `RequestedDate` date DEFAULT NULL,
  `RequestedTime` time DEFAULT NULL,
  `DateOfRequest` date DEFAULT NULL,
  `RequestedDentist` varchar(100) DEFAULT NULL,
  `ReasonForBooking` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_request`
--

INSERT INTO `appointment_request` (`PatientID`, `PatientName`, `PaymentType`, `PaymentStatus`, `RequestedDate`, `RequestedTime`, `DateOfRequest`, `RequestedDentist`, `ReasonForBooking`) VALUES
('PAT001', 'Isabella R. Cruz', 'Cash on-site', 'Pending', '2024-12-01', '09:00:00', '2024-11-20', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT002', 'Liam S. Reyes', 'Online', 'Paid', '2024-12-02', '10:00:00', '2024-11-21', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT003', 'Mia T. Garcia', 'Cash on-site', 'Pending', '2024-12-03', '11:00:00', '2024-11-22', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT004', 'Noah U. Hernandez', 'Online', 'Paid', '2024-12-04', '12:00:00', '2024-11-23', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT005', 'Emma V. Santos', 'Cash on-site', 'Pending', '2024-12-05', '13:00:00', '2024-11-24', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT006', 'Oliver W. Cruz', 'Online', 'Paid', '2024-12-06', '14:00:00', '2024-11-25', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT007', 'Ava X. Garcia', 'Cash on-site', 'Pending', '2024-12-07', '15:00:00', '2024-11-26', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT008', 'Elijah Y. Hernandez', 'Online', 'Paid', '2024-12-08', '16:00:00', '2024-11-27', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT009', 'Sophia Z. Santos', 'Cash on-site', 'Pending', '2024-12-09', '09:00:00', '2024-11-28', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT010', 'James A. Cruz', 'Online', 'Paid', '2024-12-10', '10:00:00', '2024-11-29', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT011', 'Charlotte B. Garcia', 'Cash on-site', 'Pending', '2024-12-11', '11:00:00', '2024-11-30', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT012', 'Benjamin C. Hernandez', 'Online', 'Paid', '2024-12-12', '12:00:00', '2024-12-01', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT013', 'Amelia D. Santos', 'Cash on-site', 'Pending', '2024-12-13', '13:00:00', '2024-12-02', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT014', 'Lucas E. Cruz', 'Online', 'Paid', '2024-12-14', '14:00:00', '2024-12-03', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT015', 'Mia F. Garcia', 'Cash on-site', 'Pending', '2024-12-15', '15:00:00', '2024-12-04', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT016', 'Henry G. Hernandez', 'Online', 'Paid', '2024-12-16', '16:00:00', '2024-12-05', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT017', 'Evelyn H. Santos', 'Cash on-site', 'Pending', '2024-12-17', '09:00:00', '2024-12-06', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT018', 'Alexander I. Cruz', 'Online', 'Paid', '2024-12-18', '10:00:00', '2024-12-07', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT019', 'Harper J. Garcia', 'Cash on-site', 'Pending', '2024-12-19', '11:00:00', '2024-12-08', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT020', 'William K. Hernandez', 'Online', 'Paid', '2024-12-20', '12:00:00', '2024-12-09', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT021', 'Ella L. Santos', 'Cash on-site', 'Pending', '2024-12-21', '13:00:00', '2024-12-10', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT022', 'Sebastian M. Cruz', 'Online', 'Paid', '2024-12-22', '14:00:00', '2024-12-11', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT023', 'Avery N. Garcia', 'Cash on-site', 'Pending', '2024-12-23', '15:00:00', '2024-12-12', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT024', 'Jack O. Hernandez', 'Online', 'Paid', '2024-12-24', '16:00:00', '2024-12-13', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT025', 'Grace P. Santos', 'Cash on-site', 'Pending', '2024-12-25', '09:00:00', '2024-12-14', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT026', 'Daniel Q. Cruz', 'Online', 'Paid', '2024-12-26', '10:00:00', '2024-12-15', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT027', 'Sofia R. Garcia', 'Cash on-site', 'Pending', '2024-12-27', '11:00:00', '2024-12-16', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT028', 'Jackson S. Hernandez', 'Online', 'Paid', '2024-12-28', '12:00:00', '2024-12-17', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT029', 'Victoria T. Santos', 'Cash on-site', 'Pending', '2024-12-29', '13:00:00', '2024-12-18', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT030', 'David U. Cruz', 'Online', 'Paid', '2024-12-30', '14:00:00', '2024-12-19', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT031', 'Isabella V. Garcia', 'Cash on-site', 'Pending', '2024-12-31', '15:00:00', '2024-12-20', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT032', 'Liam W. Hernandez', 'Online', 'Paid', '2025-01-01', '16:00:00', '2024-12-21', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT033', 'Mia X. Santos', 'Cash on-site', 'Pending', '2025-01-02', '09:00:00', '2024-12-22', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT034', 'Noah Y. Cruz', 'Online', 'Paid', '2025-01-03', '10:00:00', '2024-12-23', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT035', 'Emma Z. Garcia', 'Cash on-site', 'Pending', '2025-01-04', '11:00:00', '2024-12-24', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT036', 'Oliver A. Hernandez', 'Online', 'Paid', '2025-01-05', '12:00:00', '2024-12-25', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT037', 'Ava B. Santos', 'Cash on-site', 'Pending', '2025-01-06', '13:00:00', '2024-12-26', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT038', 'Elijah C. Cruz', 'Online', 'Paid', '2025-01-06', '14:00:00', '2024-12-26', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT039', 'Sophia D. Garcia', 'Cash on-site', 'Pending', '2025-01-07', '15:00:00', '2024-12-27', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT040', 'James E. Hernandez', 'Online', 'Paid', '2025-01-08', '16:00:00', '2024-12-28', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT041', 'Charlotte F. Santos', 'Cash on-site', 'Pending', '2025-01-09', '09:00:00', '2024-12-29', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT042', 'Benjamin G. Cruz', 'Online', 'Paid', '2025-01-10', '10:00:00', '2024-12-30', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT043', 'Amelia H. Garcia', 'Cash on-site', 'Pending', '2025-01-11', '11:00:00', '2024-12-31', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT044', 'Lucas I. Hernandez', 'Online', 'Paid', '2025-01-12', '12:00:00', '2025-01-01', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT045', 'Mia J. Santos', 'Cash on-site', 'Pending', '2025-01-13', '13:00:00', '2025-01-02', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT046', 'Henry K. Cruz', 'Online', 'Paid', '2025-01-14', '14:00:00', '2025-01-03', 'Dr. Maria L. Reyes', 'Tooth extraction'),
('PAT047', 'Evelyn L. Garcia', 'Cash on-site', 'Pending', '2025-01-15', '15:00:00', '2025-01-04', 'Dr. Evan J. Santos', 'Cavity filling'),
('PAT048', 'Alexander M. Hernandez', 'Online', 'Paid', '2025-01-16', '16:00:00', '2025-01-05', 'Dr. Maria L. Reyes', 'Teeth whitening'),
('PAT049', 'Harper N. Santos', 'Cash on-site', 'Pending', '2025-01-17', '09:00:00', '2025-01-06', 'Dr. Evan J. Santos', 'Regular cleaning'),
('PAT050', 'William O. Cruz', 'Online', 'Paid', '2025-01-18', '10:00:00', '2025-01-07', 'Dr. Maria L. Reyes', 'Tooth extraction');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `Patient_ID` int(11) NOT NULL,
  `Patient_Firstname` varchar(50) DEFAULT NULL,
  `Patient_Lastname` varchar(50) DEFAULT NULL,
  `Patient_Email` varchar(100) DEFAULT NULL,
  `Patient_Contact` varchar(15) DEFAULT NULL,
  `Patient_Gender` enum('M','F') DEFAULT NULL,
  `Patient_Age` int(11) DEFAULT NULL,
  `Patient_Password` varchar(255) DEFAULT NULL,
  `Patient_Img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`Patient_ID`, `Patient_Firstname`, `Patient_Lastname`, `Patient_Email`, `Patient_Contact`, `Patient_Gender`, `Patient_Age`, `Patient_Password`, `Patient_Img`) VALUES
(100002, 'Alice', 'Johnson XD', 'alice.johnson@example.com', '1122334455', 'F', 35, '$2y$10$z2zeaRI5cr9TDRsPe89tSulO/kHEdCnLLwNppBptGOOa1qggJiGZO', 'uploads/6760dc2f61103_Isabelle.png'),
(100003, 'Bob', 'Brown', 'bob.brown@example.com', '6677889900', 'M', 0, '$2y$10$pB7.ApV.OPaLxoB.6PhLKeT/W/2HGcODazkxNUyivjPKfxRRlmYeC', 'uploads/6760dc21b8864_Nicole.png'),
(100004, 'Charlie', 'Davis', 'charlie.davis@example.com', '5566778899', 'M', 0, '$2y$10$POU3zSztrOPbTGYbAHK.ie4brQIDgwPdjDsAMIzxA0.FVqKz3NctG', 'uploads/6760dc0fb4f6b_Sophia.png'),
(100006, 'arnaldo', 'Dentist', 'arnaldo1@gmail.com', '1234566789', 'M', 21, '$2y$10$4PPObnpn0CVAFHN1d7tmkeKeIIxTF8o8jl2lxb1WRngjY.SHJKERS', 'uploads/6760dbe7760bb_Arnaldo.png'),
(100007, 'cleah', 'spike', 'cleah@gmail.com', '1111111111', 'F', 6, '$2y$10$dEJER/giL5WLv8bAIxj82uKKvYmixU8Iz6.MiPvsO1B1S5c.1wNVG', 'uploads/67610543ae1ef_default_profile.png'),
(100008, 'Brando', 'Allen', 'brando1@gmail.com', '0929552747', 'M', 21, '$2y$10$A0IOJONhw.U6ucxIGSzjAOlf8IFAqtjggic7wqMIqyEYaE3ZHqSX.', 'uploads/default.png'),
(100009, 'Mura', 'Musa', 'musa@gmail.com', '0295527474', 'M', 21, '$2y$10$wWO7qmCbfjXH6Vlrvmo4kud4VVTjEPBP1P4MMWQGsKb1TDeBVW0fG', 'uploads/6762532fd93b3_Exit_icon.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment_list`
--
ALTER TABLE `appointment_list`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `appointment_request`
--
ALTER TABLE `appointment_request`
  ADD PRIMARY KEY (`PatientID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`Patient_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1719531780;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `Patient_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100011;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
