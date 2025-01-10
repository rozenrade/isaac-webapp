<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108150247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE build_character (build_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_2AF8476717C13F8B (build_id), INDEX IDX_2AF847671136BE75 (character_id), PRIMARY KEY(build_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE build_boss (build_id INT NOT NULL, boss_id INT NOT NULL, INDEX IDX_39820BD017C13F8B (build_id), INDEX IDX_39820BD0261FB672 (boss_id), PRIMARY KEY(build_id, boss_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE build_character ADD CONSTRAINT FK_2AF8476717C13F8B FOREIGN KEY (build_id) REFERENCES build (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE build_character ADD CONSTRAINT FK_2AF847671136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE build_boss ADD CONSTRAINT FK_39820BD017C13F8B FOREIGN KEY (build_id) REFERENCES build (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE build_boss ADD CONSTRAINT FK_39820BD0261FB672 FOREIGN KEY (boss_id) REFERENCES boss (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE boss DROP FOREIGN KEY FK_3EFE663AFB88E14F');
        $this->addSql('DROP INDEX IDX_3EFE663AFB88E14F ON boss');
        $this->addSql('ALTER TABLE boss DROP utilisateur_id');
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB034FB88E14F');
        $this->addSql('DROP INDEX IDX_937AB034FB88E14F ON `character`');
        $this->addSql('ALTER TABLE `character` DROP utilisateur_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE build_character DROP FOREIGN KEY FK_2AF8476717C13F8B');
        $this->addSql('ALTER TABLE build_character DROP FOREIGN KEY FK_2AF847671136BE75');
        $this->addSql('ALTER TABLE build_boss DROP FOREIGN KEY FK_39820BD017C13F8B');
        $this->addSql('ALTER TABLE build_boss DROP FOREIGN KEY FK_39820BD0261FB672');
        $this->addSql('DROP TABLE build_character');
        $this->addSql('DROP TABLE build_boss');
        $this->addSql('ALTER TABLE boss ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE boss ADD CONSTRAINT FK_3EFE663AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3EFE663AFB88E14F ON boss (utilisateur_id)');
        $this->addSql('ALTER TABLE `character` ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_937AB034FB88E14F ON `character` (utilisateur_id)');
    }
}
