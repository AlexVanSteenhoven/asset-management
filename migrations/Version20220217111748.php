<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217111748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8F3F68C54584665A (product_id), INDEX IDX_8F3F68C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, product_group_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, status VARCHAR(25) NOT NULL, INDEX IDX_D34A04ADA76ED395 (user_id), INDEX IDX_D34A04AD35E4B3D0 (product_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_CC9C3F995E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(25) NOT NULL, lastname VARCHAR(75) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C54584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD35E4B3D0 FOREIGN KEY (product_group_id) REFERENCES product_group (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C54584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD35E4B3D0');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5A76ED395');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA76ED395');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_group');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
