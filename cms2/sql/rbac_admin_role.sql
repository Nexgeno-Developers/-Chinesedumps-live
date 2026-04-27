-- RBAC migration for cms2 admin roles, legacy modules, and legacy permissions.
-- If admin_role already exists, skip the ALTER TABLE line and run the remaining statements.

ALTER TABLE tbladmin ADD admin_role VARCHAR(20) NOT NULL DEFAULT 'admin';

UPDATE tbladmin SET admin_role = 'super_admin' WHERE admin_id = 1;
UPDATE tbladmin SET admin_role = 'admin' WHERE admin_id <> 1 AND (admin_role IS NULL OR admin_role = '');

CREATE TABLE IF NOT EXISTS `tblmodules` (
  `ModuleID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`ModuleID`),
  UNIQUE KEY `Name` (`Name`)
);

CREATE TABLE IF NOT EXISTS `tblpermissions` (
  `PermissionID` int(11) NOT NULL AUTO_INCREMENT,
  `AdminID` int(11) NOT NULL,
  `ModuleID` int(11) NOT NULL,
  `Read` tinyint(1) NOT NULL DEFAULT '0',
  `Write` tinyint(1) NOT NULL DEFAULT '0',
  `Edit` tinyint(1) NOT NULL DEFAULT '0',
  `Delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`PermissionID`),
  UNIQUE KEY `admin_module` (`AdminID`,`ModuleID`)
);

INSERT IGNORE INTO `tblmodules` (`Name`) VALUES
('Configuration'),
('Summary'),
('Change Password'),
('Website Copyright'),
('Change Email Address'),
('Logout'),
('All Registered Users'),
('Reports'),
('Banners Management'),
('Add Banner'),
('Exam Results Management'),
('Add Exam Results Image'),
('Add Vendors'),
('Vendors Management'),
('Add Certification'),
('Certifications Management'),
('Add Exam'),
('Exams Management'),
('View Demo Emails'),
('View Exam Request Email'),
('Full Download Management'),
('Final Orders'),
('List Cart'),
('Re-Order Cart'),
('Manage Coupons'),
('Add new Coupon'),
('Manage Course'),
('Add new Course'),
('Edit Emails'),
('Vendor Global Content'),
('Certification Global Content'),
('Exam Global Content'),
('Add User'),
('User Management'),
('Admin User Management'),
('Add Admin User');

INSERT IGNORE INTO `tblpermissions` (`AdminID`, `ModuleID`, `Read`, `Write`, `Edit`, `Delete`)
SELECT a.admin_id, m.ModuleID, 1, 1, 1, 1
FROM tbladmin a
CROSS JOIN tblmodules m
WHERE a.admin_role = 'super_admin';

INSERT IGNORE INTO `tblpermissions` (`AdminID`, `ModuleID`, `Read`, `Write`, `Edit`, `Delete`)
SELECT a.admin_id, m.ModuleID, 1,
  CASE WHEN m.Name IN ('Add Banner','Add Exam Results Image','Add Vendors','Add Certification','Add Exam','Add new Coupon','Add new Course','Add User') THEN 1 ELSE 0 END,
  CASE WHEN m.Name IN ('Configuration','Change Password','Website Copyright','Change Email Address','Banners Management','Exam Results Management','Vendors Management','Certifications Management','Exams Management','List Cart','Re-Order Cart','Manage Coupons','Manage Course','Edit Emails','Vendor Global Content','Certification Global Content','Exam Global Content','User Management','Final Orders') THEN 1 ELSE 0 END,
  CASE WHEN m.Name IN ('Vendors Management','Certifications Management','Exams Management','Banners Management','Exam Results Management','Manage Course','User Management','Manage Coupons') THEN 1 ELSE 0 END
FROM tbladmin a
CROSS JOIN tblmodules m
WHERE a.admin_role = 'admin'
  AND m.Name IN ('Configuration','Summary','Change Password','Website Copyright','Change Email Address','Logout','All Registered Users','Reports','Banners Management','Add Banner','Exam Results Management','Add Exam Results Image','Add Vendors','Vendors Management','Add Certification','Certifications Management','Add Exam','Exams Management','View Demo Emails','View Exam Request Email','Full Download Management','Final Orders','List Cart','Re-Order Cart','Manage Coupons','Add new Coupon','Manage Course','Add new Course','Edit Emails','Vendor Global Content','Certification Global Content','Exam Global Content','Add User','User Management');

INSERT IGNORE INTO `tblpermissions` (`AdminID`, `ModuleID`, `Read`, `Write`, `Edit`, `Delete`)
SELECT a.admin_id, m.ModuleID, 1,
  CASE WHEN m.Name IN ('Add Banner','Add Exam Results Image','Add Vendors','Add Certification','Add Exam','Add new Course') THEN 1 ELSE 0 END,
  CASE WHEN m.Name IN ('Change Password','Banners Management','Exam Results Management','Vendors Management','Certifications Management','Exams Management','Manage Course','Edit Emails','Vendor Global Content','Certification Global Content','Exam Global Content') THEN 1 ELSE 0 END,
  0
FROM tbladmin a
CROSS JOIN tblmodules m
WHERE a.admin_role = 'editor'
  AND m.Name IN ('Summary','Change Password','Logout','Banners Management','Add Banner','Exam Results Management','Add Exam Results Image','Add Vendors','Vendors Management','Add Certification','Certifications Management','Add Exam','Exams Management','Manage Course','Add new Course','Edit Emails','Vendor Global Content','Certification Global Content','Exam Global Content');

INSERT IGNORE INTO `tblpermissions` (`AdminID`, `ModuleID`, `Read`, `Write`, `Edit`, `Delete`)
SELECT a.admin_id, m.ModuleID, 1,
  CASE WHEN m.Name IN ('Add User') THEN 1 ELSE 0 END,
  CASE WHEN m.Name IN ('Change Password','List Cart','Re-Order Cart','User Management') THEN 1 ELSE 0 END,
  0
FROM tbladmin a
CROSS JOIN tblmodules m
WHERE a.admin_role = 'support'
  AND m.Name IN ('Summary','Change Password','Logout','All Registered Users','Reports','View Demo Emails','View Exam Request Email','Full Download Management','Final Orders','List Cart','Re-Order Cart','Add User','User Management');
