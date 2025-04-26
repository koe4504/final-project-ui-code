DELIMITER $$

CREATE TRIGGER `after_attendance_insert_create_billing`
AFTER INSERT ON `daycare_management`.`attendance`
FOR EACH ROW
BEGIN
  IF NEW.status = 'present' THEN
    INSERT INTO `daycare_management`.`billing` (
        child_id,
        amount,
        due_date,
        status,
        description
    ) VALUES (
        NEW.child_id,
        50.00,
        NEW.date,
        'unpaid',
        CONCAT('Billing for attendance on ', DATE_FORMAT(NEW.date, '%Y-%m-%d'))
    );
  END IF;
END$$

DELIMITER ;

