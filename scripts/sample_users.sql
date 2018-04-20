--
-- This script will set up a couple sample users and permission groups for you.
-- Change the database name here as necessary.

use `php-lwaf`;


-- These users will have the password 'password'. It's not secure at all, so change it immediately.
INSERT INTO `user` (`user_id`, `email`, `password`, `first_name`, `last_name`, `theme`, `timestamp`, `status`, `timezone`) VALUES
  (1, 'admin@changeme.com', '$2a$08$3foRX69BrC4gx8Jd93ARmeY09nlZMfcJDznAaDxW.C/9gFW9jhnne', 'Admin', 'Person', 'static/themes/default.css', '2018-04-08 01:50:15', 'active', 0),
  (2, 'user@changeme.com', '$2a$08$3foRX69BrC4gx8Jd93ARmeY09nlZMfcJDznAaDxW.C/9gFW9jhnne', 'User', 'Person', 'static/themes/default.css', '2018-04-08 01:50:15', 'active', 0);

INSERT INTO `role` (`role_id`, `slug`, `name`, `description`) VALUES
  (1, 'server_admin', 'Server Administrator', 'Has full control over the system'),
  (2, 'visitor', 'Site Visitor', 'Site Visitor Access');

INSERT INTO `role_user` (`role_user_id`, `user_id`, `role_id`) VALUES
  (1, 1, 1),
  (2, 2, 2);

INSERT INTO `permission` (`permission_id`, `slug`, `name`, `description`) VALUES
  (1, 'manage_users', 'Manage Users', 'Allows admins to manage user accounts'),
  (2, 'read_pages', 'Read Access', 'Read Access to pages');

INSERT INTO `permission_role` (`permission_role_id`, `permission_id`, `role_id`) VALUES
  (1, 1, 1),
  (2, 2, 1),
  (3, 2, 2);
