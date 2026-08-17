-- =============================================================================
-- Raw SQL equivalent of the NEW migrations only (MySQL / MariaDB)
--
-- Mirrors, in order:
--   2026_08_17_000000_add_requestor_email_to_document_trackers.php
--   2026_08_17_000001_add_requesting_office_id_to_users_table.php
--
-- Index / foreign-key names match what Laravel's schema builder generates,
-- so the DOWN section drops them cleanly.
--
-- Assumes the `document_trackers` and `users` tables already exist.
-- =============================================================================


-- -----------------------------------------------------------------------------
-- UP
-- -----------------------------------------------------------------------------

-- 2026_08_17_000000_add_requestor_email_to_document_trackers -------------------
ALTER TABLE `document_trackers`
    ADD COLUMN `requestor_email` VARCHAR(255) NULL AFTER `requestor_name`;


-- 2026_08_17_000001_add_requesting_office_id_to_users_table --------------------
ALTER TABLE `users`
    ADD COLUMN `requesting_office_id` BIGINT UNSIGNED NULL AFTER `position`,
    ADD KEY `users_requesting_office_id_foreign` (`requesting_office_id`),
    ADD CONSTRAINT `users_requesting_office_id_foreign`
        FOREIGN KEY (`requesting_office_id`) REFERENCES `requesting_offices` (`requesting_office_id`)
        ON DELETE SET NULL;


-- -----------------------------------------------------------------------------
-- DOWN  (reverse order)
-- -----------------------------------------------------------------------------

-- 2026_08_17_000001 (reverse)
ALTER TABLE `users`
    DROP FOREIGN KEY `users_requesting_office_id_foreign`,
    DROP COLUMN `requesting_office_id`;

-- 2026_08_17_000000 (reverse)
ALTER TABLE `document_trackers`
    DROP COLUMN `requestor_email`;
