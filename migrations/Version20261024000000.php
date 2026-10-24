<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Experience: replace single 'date' string with typed start_date/end_date DATE columns.
 *
 * The running DB was already migrated out-of-band to this schema (start_date /
 * end_date exist, 'date' column dropped). This migration declares that change in
 * the migration chain so a fresh clone can build the schema reproducibly.
 */
final class Version20261024000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Experience: add start_date/end_date DATE columns, drop legacy string date column';
    }

    public function up(Schema $schema): void
    {
        // idempotent for the already-migrated prod DB; these run as separate
        // statements so a partially-applied schema does not fail the whole batch.
        $this->addSql('ALTER TABLE experience ADD COLUMN IF NOT EXISTS start_date DATE NOT NULL');
        $this->addSql('ALTER TABLE experience ADD COLUMN IF NOT EXISTS end_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE experience DROP COLUMN IF EXISTS date');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience ADD COLUMN date VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE experience DROP COLUMN IF EXISTS start_date');
        $this->addSql('ALTER TABLE experience DROP COLUMN IF EXISTS end_date');
    }
}
