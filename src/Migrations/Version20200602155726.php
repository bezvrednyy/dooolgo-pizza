<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200602155726 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` CHANGE user_id user_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `order` RENAME INDEX fk_f5299398a76ed395 TO IDX_F5299398A76ED395');
        $this->addSql('ALTER TABLE `order` RENAME INDEX fk_f5299398d41d1d42 TO IDX_F5299398D41D1D42');
        $this->addSql('ALTER TABLE user CHANGE id id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` RENAME INDEX idx_f5299398d41d1d42 TO FK_F5299398D41D1D42');
        $this->addSql('ALTER TABLE `order` RENAME INDEX idx_f5299398a76ed395 TO FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE user CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
