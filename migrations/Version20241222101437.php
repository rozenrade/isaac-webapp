<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222101437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boss (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, name VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_3EFE663AFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE build (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_BDA0F2DBFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE build_item (build_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_186748F417C13F8B (build_id), INDEX IDX_186748F4126F525E (item_id), PRIMARY KEY(build_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_item (category_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_94805F5912469DE2 (category_id), INDEX IDX_94805F59126F525E (item_id), PRIMARY KEY(category_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, name VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_937AB034FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_list (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_8CF8BCE3FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_list_item (item_list_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_560FEC0736F330DF (item_list_id), INDEX IDX_560FEC07126F525E (item_id), PRIMARY KEY(item_list_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE synergy (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_122B0F1AFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE synergy_item (synergy_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_7CE113A25CD4060B (synergy_id), INDEX IDX_7CE113A2126F525E (item_id), PRIMARY KEY(synergy_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boss ADD CONSTRAINT FK_3EFE663AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE build ADD CONSTRAINT FK_BDA0F2DBFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE build_item ADD CONSTRAINT FK_186748F417C13F8B FOREIGN KEY (build_id) REFERENCES build (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE build_item ADD CONSTRAINT FK_186748F4126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_item ADD CONSTRAINT FK_94805F5912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_item ADD CONSTRAINT FK_94805F59126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE item_list ADD CONSTRAINT FK_8CF8BCE3FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE item_list_item ADD CONSTRAINT FK_560FEC0736F330DF FOREIGN KEY (item_list_id) REFERENCES item_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_list_item ADD CONSTRAINT FK_560FEC07126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synergy ADD CONSTRAINT FK_122B0F1AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE synergy_item ADD CONSTRAINT FK_7CE113A25CD4060B FOREIGN KEY (synergy_id) REFERENCES synergy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synergy_item ADD CONSTRAINT FK_7CE113A2126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boss DROP FOREIGN KEY FK_3EFE663AFB88E14F');
        $this->addSql('ALTER TABLE build DROP FOREIGN KEY FK_BDA0F2DBFB88E14F');
        $this->addSql('ALTER TABLE build_item DROP FOREIGN KEY FK_186748F417C13F8B');
        $this->addSql('ALTER TABLE build_item DROP FOREIGN KEY FK_186748F4126F525E');
        $this->addSql('ALTER TABLE category_item DROP FOREIGN KEY FK_94805F5912469DE2');
        $this->addSql('ALTER TABLE category_item DROP FOREIGN KEY FK_94805F59126F525E');
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB034FB88E14F');
        $this->addSql('ALTER TABLE item_list DROP FOREIGN KEY FK_8CF8BCE3FB88E14F');
        $this->addSql('ALTER TABLE item_list_item DROP FOREIGN KEY FK_560FEC0736F330DF');
        $this->addSql('ALTER TABLE item_list_item DROP FOREIGN KEY FK_560FEC07126F525E');
        $this->addSql('ALTER TABLE synergy DROP FOREIGN KEY FK_122B0F1AFB88E14F');
        $this->addSql('ALTER TABLE synergy_item DROP FOREIGN KEY FK_7CE113A25CD4060B');
        $this->addSql('ALTER TABLE synergy_item DROP FOREIGN KEY FK_7CE113A2126F525E');
        $this->addSql('DROP TABLE boss');
        $this->addSql('DROP TABLE build');
        $this->addSql('DROP TABLE build_item');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_item');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_list');
        $this->addSql('DROP TABLE item_list_item');
        $this->addSql('DROP TABLE synergy');
        $this->addSql('DROP TABLE synergy_item');
        $this->addSql('DROP TABLE `user`');
    }
}