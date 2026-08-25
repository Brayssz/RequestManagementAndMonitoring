-- =============================================================================
-- Raw SQL equivalent of (MySQL / MariaDB):
--   2026_08_25_000000_add_requesting_office_id_to_document_trackers.php
--
-- Adds the originating office/school link to document trackers so the
-- "Requesting Office" column can be shown in the Document Trackers table.
--
-- Index / foreign-key names match what Laravel's schema builder generates,
-- so the DOWN section drops them cleanly.
--
-- Assumes the `document_trackers` and `requesting_offices` tables already exist,
-- and that `document_trackers`.`requestor_email` has already been added
-- (see document_tracking_migrations.sql).
-- =============================================================================


-- -----------------------------------------------------------------------------
-- UP
-- -----------------------------------------------------------------------------

-- 2026_08_25_000000_add_requesting_office_id_to_document_trackers --------------
ALTER TABLE `document_trackers`
    ADD COLUMN `requesting_office_id` BIGINT UNSIGNED NULL AFTER `requestor_email`,
    ADD KEY `document_trackers_requesting_office_id_foreign` (`requesting_office_id`),
    ADD CONSTRAINT `document_trackers_requesting_office_id_foreign`
        FOREIGN KEY (`requesting_office_id`) REFERENCES `requesting_offices` (`requesting_office_id`)
        ON DELETE SET NULL;


-- OPTIONAL: record the migration as already run so a later `php artisan migrate`
-- does not try to add the column a second time. Skip this block if you apply
-- schema changes purely by hand and never run the migrator.
SET @migration := '2026_08_25_000000_add_requesting_office_id_to_document_trackers';
SET @batch := (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM `migrations`);

DELETE FROM `migrations` WHERE `migration` = @migration;
INSERT INTO `migrations` (`migration`, `batch`) VALUES (@migration, @batch);


-- -----------------------------------------------------------------------------
-- DOWN
-- -----------------------------------------------------------------------------

-- 2026_08_25_000000 (reverse)
ALTER TABLE `document_trackers`
    DROP FOREIGN KEY `document_trackers_requesting_office_id_foreign`,
    DROP COLUMN `requesting_office_id`;

DELETE FROM `migrations`
WHERE `migration` = '2026_08_25_000000_add_requesting_office_id_to_document_trackers';
