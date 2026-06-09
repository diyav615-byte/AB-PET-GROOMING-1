-- Run this on your PRODUCTION MySQL server as root
-- =====================================================

-- Create dedicated database user (replace with strong password)
CREATE USER 'ab_pet_user'@'localhost' IDENTIFIED BY 'CHANGE_THIS_TO_STRONG_PASSWORD';

-- Grant minimal required privileges
GRANT SELECT, INSERT, UPDATE, DELETE ON ab_pet_grooming.* TO 'ab_pet_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- =====================================================
-- VERIFY USER CREATED
-- =====================================================
SELECT User, Host FROM mysql.user WHERE User = 'ab_pet_user';

-- Show grants
SHOW GRANTS FOR 'ab_pet_user'@'localhost';