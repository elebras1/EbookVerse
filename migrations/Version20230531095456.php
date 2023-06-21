<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531095456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90F20E394C2');
        $this->addSql('DROP INDEX IDX_C0B9F90F20E394C2 ON discussion');
        $this->addSql('ALTER TABLE discussion CHANGE pseudo_id compte_id VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90FF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (pseudo)');
        $this->addSql('CREATE INDEX IDX_C0B9F90FF2C56620 ON discussion (compte_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90FF2C56620');
        $this->addSql('DROP INDEX IDX_C0B9F90FF2C56620 ON discussion');
        $this->addSql('ALTER TABLE discussion CHANGE compte_id pseudo_id VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90F20E394C2 FOREIGN KEY (pseudo_id) REFERENCES compte (pseudo) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C0B9F90F20E394C2 ON discussion (pseudo_id)');
    }
}
