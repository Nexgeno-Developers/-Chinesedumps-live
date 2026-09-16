-- RBAC migration for feature-based cms2 admin modules.
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

INSERT INTO `tblmodules` (`Name`)
SELECT 'Configuration' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Configuration')
UNION ALL SELECT 'Summary' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Summary')
UNION ALL SELECT 'Change Password' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Change Password')
UNION ALL SELECT 'Website Copyright' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Website Copyright')
UNION ALL SELECT 'Change Email Address' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Change Email Address')
UNION ALL SELECT 'Report' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Report')
UNION ALL SELECT 'Banners Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Banners Module')
UNION ALL SELECT 'Exam Results Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Exam Results Module')
UNION ALL SELECT 'Vendors Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Vendors Module')
UNION ALL SELECT 'Certifications Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Certifications Module')
UNION ALL SELECT 'Exams Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Exams Module')
UNION ALL SELECT 'Demo Exams Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Demo Exams Module')
UNION ALL SELECT 'Orders Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Orders Module')
UNION ALL SELECT 'Coupons Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Coupons Module')
UNION ALL SELECT 'Courses Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Courses Module')
UNION ALL SELECT 'Global Content Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Global Content Module')
UNION ALL SELECT 'Users Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Users Module')
UNION ALL SELECT 'Admin Users Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Admin Users Module')
UNION ALL SELECT 'Form Leads Module' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tblmodules WHERE Name = 'Form Leads Module');

-- Ensure every admin has a row for every active module before preserving old permissions.
INSERT INTO `tblpermissions` (`AdminID`, `ModuleID`, `Read`, `Write`, `Edit`, `Delete`)
SELECT a.admin_id, m.ModuleID, 0, 0, 0, 0
FROM tbladmin a
CROSS JOIN tblmodules m
WHERE m.Name IN (
  'Configuration','Summary','Change Password','Website Copyright','Change Email Address','Report',
  'Banners Module','Exam Results Module','Vendors Module','Certifications Module','Exams Module',
  'Demo Exams Module','Orders Module','Coupons Module','Courses Module','Global Content Module',
  'Users Module','Admin Users Module'
)
AND NOT EXISTS (
  SELECT 1 FROM tblpermissions p WHERE p.AdminID = a.admin_id AND p.ModuleID = m.ModuleID
);

-- Preserve custom old permissions into active modules.
UPDATE tblpermissions p
INNER JOIN tblmodules nm ON p.ModuleID = nm.ModuleID
LEFT JOIN (
  SELECT p.AdminID, 'Configuration' AS NewName, MAX(p.`Read`) AS R, 0 AS W, MAX(p.`Edit`) AS E, 0 AS D
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Configuration','Global Module') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Summary', MAX(p.`Read`), 0, MAX(p.`Edit`), 0
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Summary','Global Module') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Change Password', MAX(p.`Read`), 0, MAX(p.`Edit`), 0
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Change Password','Global Module') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Website Copyright', MAX(p.`Read`), 0, MAX(p.`Edit`), 0
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Website Copyright','Global Module') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Change Email Address', MAX(p.`Read`), 0, MAX(p.`Edit`), 0
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Change Email Address','Global Module') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Report', MAX(p.`Read`), MAX(p.`Write`), MAX(p.`Edit`), MAX(p.`Delete`)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Reports','Stats Module') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Users Module',
    MAX(CASE WHEN om.Name IN ('User Management','All Registered Users') THEN p.`Read` ELSE 0 END),
    MAX(CASE WHEN om.Name = 'Add User' THEN p.`Write` ELSE 0 END),
    MAX(CASE WHEN om.Name IN ('User Management','All Registered Users') THEN p.`Edit` ELSE 0 END),
    MAX(CASE WHEN om.Name IN ('User Management','All Registered Users') THEN p.`Delete` ELSE 0 END)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('User Management','Add User','All Registered Users') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Courses Module', MAX(CASE WHEN om.Name='Manage Course' THEN p.`Read` ELSE 0 END), MAX(CASE WHEN om.Name='Add new Course' THEN p.`Write` ELSE 0 END), MAX(CASE WHEN om.Name='Manage Course' THEN p.`Edit` ELSE 0 END), MAX(CASE WHEN om.Name='Manage Course' THEN p.`Delete` ELSE 0 END)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Manage Course','Add new Course') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Certifications Module', MAX(CASE WHEN om.Name='Certifications Management' THEN p.`Read` ELSE 0 END), MAX(CASE WHEN om.Name='Add Certification' THEN p.`Write` ELSE 0 END), MAX(CASE WHEN om.Name='Certifications Management' THEN p.`Edit` ELSE 0 END), MAX(CASE WHEN om.Name='Certifications Management' THEN p.`Delete` ELSE 0 END)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Certifications Management','Add Certification') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Vendors Module', MAX(CASE WHEN om.Name='Vendors Management' THEN p.`Read` ELSE 0 END), MAX(CASE WHEN om.Name='Add Vendors' THEN p.`Write` ELSE 0 END), MAX(CASE WHEN om.Name='Vendors Management' THEN p.`Edit` ELSE 0 END), MAX(CASE WHEN om.Name='Vendors Management' THEN p.`Delete` ELSE 0 END)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Vendors Management','Add Vendors') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Exams Module', MAX(CASE WHEN om.Name='Exams Management' THEN p.`Read` ELSE 0 END), MAX(CASE WHEN om.Name='Add Exam' THEN p.`Write` ELSE 0 END), MAX(CASE WHEN om.Name='Exams Management' THEN p.`Edit` ELSE 0 END), MAX(CASE WHEN om.Name='Exams Management' THEN p.`Delete` ELSE 0 END)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Exams Management','Add Exam') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Banners Module', MAX(CASE WHEN om.Name='Banners Management' THEN p.`Read` ELSE 0 END), MAX(CASE WHEN om.Name='Add Banner' THEN p.`Write` ELSE 0 END), MAX(CASE WHEN om.Name='Banners Management' THEN p.`Edit` ELSE 0 END), MAX(CASE WHEN om.Name='Banners Management' THEN p.`Delete` ELSE 0 END)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Banners Management','Add Banner') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Exam Results Module', MAX(CASE WHEN om.Name='Exam Results Management' THEN p.`Read` ELSE 0 END), MAX(CASE WHEN om.Name='Add Exam Results Image' THEN p.`Write` ELSE 0 END), MAX(CASE WHEN om.Name='Exam Results Management' THEN p.`Edit` ELSE 0 END), MAX(CASE WHEN om.Name='Exam Results Management' THEN p.`Delete` ELSE 0 END)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Exam Results Management','Add Exam Results Image') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Coupons Module', MAX(CASE WHEN om.Name='Manage Coupons' THEN p.`Read` ELSE 0 END), MAX(CASE WHEN om.Name='Add new Coupon' THEN p.`Write` ELSE 0 END), MAX(CASE WHEN om.Name='Manage Coupons' THEN p.`Edit` ELSE 0 END), MAX(CASE WHEN om.Name='Manage Coupons' THEN p.`Delete` ELSE 0 END)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Manage Coupons','Add new Coupon') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Orders Module', MAX(CASE WHEN om.Name='Final Orders' THEN p.`Read` ELSE 0 END), 0, MAX(CASE WHEN om.Name IN ('List Cart','Re-Order Cart','Final Orders') THEN p.`Edit` ELSE 0 END), MAX(CASE WHEN om.Name IN ('List Cart','Re-Order Cart','Final Orders') THEN p.`Delete` ELSE 0 END)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Final Orders','List Cart','Re-Order Cart') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Demo Exams Module', MAX(p.`Read`), MAX(p.`Write`), MAX(p.`Edit`), MAX(p.`Delete`)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('View Demo Emails','View Exam Request Email','Full Download Management') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Global Content Module', MAX(p.`Read`), 0, MAX(p.`Edit`), 0
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Edit Emails','Vendor Global Content','Certification Global Content','Exam Global Content') GROUP BY p.AdminID
  UNION ALL
  SELECT p.AdminID, 'Admin Users Module', MAX(CASE WHEN om.Name='Admin User Management' THEN p.`Read` ELSE 0 END), MAX(CASE WHEN om.Name='Add Admin User' THEN p.`Write` ELSE 0 END), MAX(CASE WHEN om.Name='Admin User Management' THEN p.`Edit` ELSE 0 END), MAX(CASE WHEN om.Name='Admin User Management' THEN p.`Delete` ELSE 0 END)
  FROM tblpermissions p INNER JOIN tblmodules om ON p.ModuleID = om.ModuleID WHERE om.Name IN ('Admin User Management','Add Admin User') GROUP BY p.AdminID
) merged ON merged.AdminID = p.AdminID AND merged.NewName = nm.Name
SET p.`Read` = GREATEST(p.`Read`, IFNULL(merged.R, 0)),
    p.`Write` = GREATEST(p.`Write`, IFNULL(merged.W, 0)),
    p.`Edit` = GREATEST(p.`Edit`, IFNULL(merged.E, 0)),
    p.`Delete` = GREATEST(p.`Delete`, IFNULL(merged.D, 0))
WHERE nm.Name IN (
  'Configuration','Summary','Change Password','Website Copyright','Change Email Address','Report',
  'Banners Module','Exam Results Module','Vendors Module','Certifications Module','Exams Module',
  'Demo Exams Module','Orders Module','Coupons Module','Courses Module','Global Content Module',
  'Users Module','Admin Users Module','Form Leads Module'
);

-- Super admin always has full access to active permission modules. Logout does not need a permission row.
UPDATE tblpermissions p
INNER JOIN tblmodules m ON p.ModuleID = m.ModuleID
INNER JOIN tbladmin a ON p.AdminID = a.admin_id
SET p.`Read` = 1, p.`Write` = 1, p.`Edit` = 1, p.`Delete` = 1
WHERE a.admin_role = 'super_admin';

-- Remove legacy modules, Logout permission rows, and stale permission rows.
DELETE p FROM tblpermissions p
INNER JOIN tblmodules m ON p.ModuleID = m.ModuleID
WHERE m.Name NOT IN (
  'Configuration','Summary','Change Password','Website Copyright','Change Email Address','Report',
  'Banners Module','Exam Results Module','Vendors Module','Certifications Module','Exams Module',
  'Demo Exams Module','Orders Module','Coupons Module','Courses Module','Global Content Module',
  'Users Module','Admin Users Module','Form Leads Module'
);

DELETE FROM tblmodules
WHERE Name NOT IN (
  'Configuration','Summary','Change Password','Website Copyright','Change Email Address','Report',
  'Banners Module','Exam Results Module','Vendors Module','Certifications Module','Exams Module',
  'Demo Exams Module','Orders Module','Coupons Module','Courses Module','Global Content Module',
  'Users Module','Admin Users Module','Form Leads Module'
);
