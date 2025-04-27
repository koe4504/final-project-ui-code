-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 27, 2025 at 06:17 PM
-- Server version: 8.0.41-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `daycare_management`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`dbadmin`@`localhost` PROCEDURE `create_billing_for_attendance` (IN `p_child_id` INT, IN `p_date` DATE)  BEGIN
    DECLARE p_amount DECIMAL(10,2) DEFAULT 100.00; 
    DECLARE p_due_date DATE;

    SET p_due_date = DATE_ADD(p_date, INTERVAL 7 DAY);

    INSERT INTO billing (child_id, amount, due_date, status, created_at)
    VALUES (p_child_id, p_amount, p_due_date, 'unpaid', NOW());
END$$

CREATE DEFINER=`dbadmin`@`localhost` PROCEDURE `GetRecipients` (IN `type` VARCHAR(50))  BEGIN
    IF type = 'Parent' THEN
        SELECT id, name FROM parents;
    ELSEIF type = 'Staff' THEN
        SELECT id, name FROM staff;
    ELSEIF type = 'Child' THEN
        SELECT id, name FROM children;
    END IF;
END$$

--
-- Functions
--
CREATE DEFINER=`dbadmin`@`localhost` FUNCTION `get_attendance_count` (`date_Input` DATE, `status_Type` ENUM('present','absent')) RETURNS INT BEGIN
    DECLARE attendance_count INT;
    SELECT COUNT(*) INTO attendance_count 
    FROM attendance 
    WHERE date = date_Input AND status = status_Type;
    RETURN attendance_count;
END$$

CREATE DEFINER=`dbadmin`@`localhost` FUNCTION `get_default_billing_amount` () RETURNS DECIMAL(10,2) BEGIN
    RETURN 100.00; 
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `active_teachers`
-- (See below for the actual view)
--
CREATE TABLE `active_teachers` (
`id` int
,`name` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `activity_resources`
--

CREATE TABLE `activity_resources` (
  `id` int NOT NULL,
  `activity_id` int DEFAULT NULL,
  `resource_type` enum('link','file','description') NOT NULL,
  `resource` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `id` int NOT NULL,
  `child_id` int DEFAULT NULL,
  `activity_id` int DEFAULT NULL,
  `score` int DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `feedback` text,
  `assessment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `child_id` int DEFAULT NULL,
  `staff_id` int DEFAULT NULL,
  `status` enum('present','absent') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `attendance`
--
DELIMITER $$
CREATE TRIGGER `trg_after_attendance_insert` AFTER INSERT ON `attendance` FOR EACH ROW BEGIN
    IF NEW.status = 'present' THEN
        CALL create_billing_for_attendance(NEW.child_id, NEW.date);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--

--
CREATE TABLE `attendance_today_summary` (
`status` enum('present','absent')
,`total` bigint
);

-- --------------------------------------------------------

--

--

CREATE TABLE `billing` (
  `id` int NOT NULL,
  `child_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '100.00',
  `due_date` date NOT NULL,
  `status` enum('paid','unpaid') DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE `children` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `children`
--
DELIMITER $$
CREATE TRIGGER `before_insert_children` BEFORE INSERT ON `children` FOR EACH ROW BEGIN
    DECLARE parent_phone VARCHAR(15);

    -- Get parent's phone number
    SELECT phone INTO parent_phone FROM parents WHERE id = NEW.parent_id;

    -- If child phone is NULL or empty, assign parent's phone
    IF NEW.phone IS NULL OR NEW.phone = '' THEN
        SET NEW.phone = parent_phone;
    END IF;
END
$$
DELIMITER ;



DELIMITER $$
CREATE TRIGGER `before_update_children` BEFORE UPDATE ON `children` FOR EACH ROW BEGIN
    DECLARE parent_phone VARCHAR(15);

    -- Get parent's phone number
    SELECT phone INTO parent_phone FROM parents WHERE id = NEW.parent_id;

    -- If child phone is NULL or empty, assign parent's phone
    IF NEW.phone IS NULL OR NEW.phone = '' THEN
        SET NEW.phone = parent_phone;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------


CREATE TABLE `child_attendance_summary` (
`child_id` int
,`child_name` varchar(100)
,`total_days_present` bigint
);

-- --------------------------------------------------------


CREATE TABLE `child_billing_summary` (
`child_id` int
,`child_name` varchar(100)
,`invoices` bigint
,`total_due` decimal(32,2)
,`total_paid` decimal(32,2)
,`total_unpaid` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `learning_activities`
--

CREATE TABLE `learning_activities` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `activity_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `title` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `recipient_type` enum('Parent','Staff','Child','All Parents','All Staffs') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `recipient_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `parents`
--
DELIMITER $$
CREATE TRIGGER `validate_parent_email` BEFORE INSERT ON `parents` FOR EACH ROW BEGIN
    -- Ensure email format is valid
    IF NEW.email NOT REGEXP '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email format!';
    END IF;
    
    -- Prevent duplicate emails in both tables
    IF (SELECT COUNT(*) FROM parents WHERE email = NEW.email) > 0
        OR (SELECT COUNT(*) FROM staff WHERE email = NEW.email) > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate email found!';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_parent_email_update` BEFORE UPDATE ON `parents` FOR EACH ROW BEGIN
    -- Ensure email format is valid
    IF NEW.email NOT REGEXP '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email format!';
    END IF;
    
    -- Prevent duplicate emails in both tables
    IF (SELECT COUNT(*) FROM parents WHERE email = NEW.email AND id <> OLD.id) > 0
        OR (SELECT COUNT(*) FROM staff WHERE email = NEW.email) > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate email found!';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_parent_phone` BEFORE INSERT ON `parents` FOR EACH ROW BEGIN
    -- Ensure phone number contains only digits and optional '+'
    IF NEW.phone NOT REGEXP '^[+]?[0-9]{10,15}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid phone number format!';
    END IF;
    
    -- Prevent duplicate phone numbers across staff and parents tables
    IF (SELECT COUNT(*) FROM parents WHERE phone = NEW.phone) > 0
        OR (SELECT COUNT(*) FROM staff WHERE phone = NEW.phone) > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate phone number found!';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `progress`
--

CREATE TABLE `progress` (
  `id` int NOT NULL,
  `child_id` int DEFAULT NULL,
  `activity_id` int DEFAULT NULL,
  `completion_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `notes` text,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `staff`
--
DELIMITER $$
CREATE TRIGGER `validate_staff_email` BEFORE INSERT ON `staff` FOR EACH ROW BEGIN
    -- Ensure email format is valid
    IF NEW.email NOT REGEXP '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email format!';
    END IF;
    
    -- Prevent duplicate emails in both tables
    IF (SELECT COUNT(*) FROM staff WHERE email = NEW.email) > 0
        OR (SELECT COUNT(*) FROM parents WHERE email = NEW.email) > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate email found!';
    END IF;
END
$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER `validate_staff_email_update` BEFORE UPDATE ON `staff` FOR EACH ROW BEGIN
    -- Ensure email format is valid
    IF NEW.email NOT REGEXP '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email format!';
    END IF;
    
    -- Prevent duplicate emails in both tables
    IF (SELECT COUNT(*) FROM staff WHERE email = NEW.email AND id <> OLD.id) > 0
        OR (SELECT COUNT(*) FROM parents WHERE email = NEW.email) > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate email found!';
    END IF;
END
$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER `validate_staff_phone` BEFORE INSERT ON `staff` FOR EACH ROW BEGIN
    -- Ensure phone number contains only digits and optional '+'
    IF NEW.phone NOT REGEXP '^[+]?[0-9]{10,15}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid phone number format!';
    END IF;
    
    -- Prevent duplicate phone numbers across staff and parents tables
    IF (SELECT COUNT(*) FROM staff WHERE phone = NEW.phone) > 0
        OR (SELECT COUNT(*) FROM parents WHERE phone = NEW.phone) > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate phone number found!';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_assignments`
--

CREATE TABLE `teacher_assignments` (
  `id` int NOT NULL,
  `staff_id` int DEFAULT NULL,
  `activity_id` int DEFAULT NULL,
  `assigned_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `after_insert_user_add_staff` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.role = 'staff' THEN
        INSERT INTO staff (name, email, phone, role, user_id)
        VALUES (NEW.username, NEW.email, NEW.phone, NEW.role, NEW.id);
    END IF;
END
$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER `after_user_update_update_staff` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
    IF OLD.role = 'staff' THEN
        UPDATE staff
        SET
            name = NEW.username,
            email = NEW.email,
            phone = NEW.phone,
            role = NEW.role
        WHERE user_id = NEW.id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_staff_when_user_deleted` BEFORE DELETE ON `users` FOR EACH ROW BEGIN
    IF OLD.role = 'staff' THEN
        DELETE FROM staff WHERE user_id = OLD.id;
    END IF;
END
$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER `insert_into_staff_on_role_update` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
    IF NEW.role = 'staff' AND OLD.role != 'staff' THEN
        IF NOT EXISTS (
            SELECT 1 FROM staff WHERE user_id = NEW.id
        ) THEN
            INSERT INTO staff (user_id) VALUES (NEW.id);
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `active_teachers`
--
DROP TABLE IF EXISTS `active_teachers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`dbadmin`@`localhost` SQL SECURITY DEFINER VIEW `active_teachers`  AS SELECT `staff`.`id` AS `id`, `staff`.`name` AS `name` FROM `staff` ;

-- --------------------------------------------------------

--
-- Structure for view `attendance_today_summary`
--


CREATE  VIEW `attendance_today_summary`  AS SELECT `attendance`.`status` AS `status`, count(0) AS `total` FROM `attendance` WHERE (`attendance`.`date` = curdate()) GROUP BY `attendance`.`status` ;

-- --------------------------------------------------------

--
-- Structure for view `child_attendance_summary`
--


CREATE  VIEW `child_attendance_summary`  AS SELECT `c`.`id` AS `child_id`, `c`.`name` AS `child_name`, count(`a`.`id`) AS `total_days_present` FROM (`children` `c` left join `attendance` `a` on(((`c`.`id` = `a`.`child_id`) and (`a`.`status` = 'present')))) GROUP BY `c`.`id`, `c`.`name` ;

-- --------------------------------------------------------

--
-- Structure for view `child_billing_summary`
--


CREATE  VIEW `child_billing_summary`  AS SELECT `c`.`id` AS `child_id`, `c`.`name` AS `child_name`, count(`b`.`id`) AS `invoices`, sum(`b`.`amount`) AS `total_due`, sum((case when (`b`.`status` = 'paid') then `b`.`amount` else 0 end)) AS `total_paid`, sum((case when (`b`.`status` = 'unpaid') then `b`.`amount` else 0 end)) AS `total_unpaid` FROM (`children` `c` left join `billing` `b` on((`c`.`id` = `b`.`child_id`))) GROUP BY `c`.`id`, `c`.`name` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_resources`
--
ALTER TABLE `activity_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `child_id` (`child_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance_per_day` (`child_id`,`date`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `child_id` (`child_id`);

--
-- Indexes for table `children`
--
ALTER TABLE `children`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `learning_activities`
--
ALTER TABLE `learning_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `child_id` (`child_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_staff_user` (`user_id`);

--
-- Indexes for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_resources`
--
ALTER TABLE `activity_resources`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `children`
--
ALTER TABLE `children`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_activities`
--
ALTER TABLE `learning_activities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progress`
--
ALTER TABLE `progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_resources`
--
ALTER TABLE `activity_resources`
  ADD CONSTRAINT `activity_resources_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `learning_activities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assessments`
--
ALTER TABLE `assessments`
  ADD CONSTRAINT `assessments_ibfk_1` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assessments_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `learning_activities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `children`
--
ALTER TABLE `children`
  ADD CONSTRAINT `children_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `progress`
--
ALTER TABLE `progress`
  ADD CONSTRAINT `progress_ibfk_1` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `progress_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `learning_activities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  ADD CONSTRAINT `teacher_assignments_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_assignments_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `learning_activities` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
