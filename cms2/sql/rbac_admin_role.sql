-- RBAC migration for cms2 admin roles.
-- If admin_role already exists, skip the ALTER TABLE line and run the UPDATE lines.

ALTER TABLE tbladmin ADD admin_role VARCHAR(20) NOT NULL DEFAULT 'admin';

UPDATE tbladmin SET admin_role = 'super_admin' WHERE admin_id = 1;
UPDATE tbladmin SET admin_role = 'admin' WHERE admin_id <> 1 AND (admin_role IS NULL OR admin_role = '');
