-- Make last_name nullable in orders table
ALTER TABLE `orders` MODIFY `last_name` VARCHAR(255) NULL;
