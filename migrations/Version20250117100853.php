<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250117100853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE synergy_user (synergy_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EE69E0F55CD4060B (synergy_id), INDEX IDX_EE69E0F5A76ED395 (user_id), PRIMARY KEY(synergy_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE synergy_user ADD CONSTRAINT FK_EE69E0F55CD4060B FOREIGN KEY (synergy_id) REFERENCES synergy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synergy_user ADD CONSTRAINT FK_EE69E0F5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synergy DROP FOREIGN KEY FK_122B0F1AFB88E14F');
        $this->addSql('DROP INDEX IDX_122B0F1AFB88E14F ON synergy');
        $this->addSql('ALTER TABLE synergy DROP utilisateur_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE synergy_user DROP FOREIGN KEY FK_EE69E0F55CD4060B');
        $this->addSql('ALTER TABLE synergy_user DROP FOREIGN KEY FK_EE69E0F5A76ED395');
        $this->addSql('DROP TABLE synergy_user');
        $this->addSql('ALTER TABLE synergy ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE synergy ADD CONSTRAINT FK_122B0F1AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_122B0F1AFB88E14F ON synergy (utilisateur_id)');
    }
}
