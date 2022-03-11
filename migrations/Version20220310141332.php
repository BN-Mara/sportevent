<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310141332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, currency VARCHAR(5) DEFAULT NULL, is_approved TINYINT(1) NOT NULL, reference VARCHAR(11) NOT NULL, transaction_reference VARCHAR(255) DEFAULT NULL, creation_time DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sport_event (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, modified_by_id INT DEFAULT NULL, title VARCHAR(128) NOT NULL, event_time DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, creation_time DATETIME DEFAULT NULL, modification_time DATETIME DEFAULT NULL, INDEX IDX_8FD26BBEB03A8386 (created_by_id), INDEX IDX_8FD26BBE99049ECE (modified_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, prices_id INT NOT NULL, user_id INT DEFAULT NULL, code LONGTEXT NOT NULL, is_validated TINYINT(1) DEFAULT NULL, INDEX IDX_97A0ADA3D9C9DE39 (prices_id), INDEX IDX_97A0ADA3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_price (id INT AUTO_INCREMENT NOT NULL, sport_event_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, is_enabled TINYINT(1) NOT NULL, type VARCHAR(20) DEFAULT NULL, INDEX IDX_E2F8415247551731 (sport_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, names VARCHAR(100) NOT NULL, phone VARCHAR(20) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE xrate (id INT AUTO_INCREMENT NOT NULL, usd DOUBLE PRECISION NOT NULL, cdf DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sport_event ADD CONSTRAINT FK_8FD26BBEB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sport_event ADD CONSTRAINT FK_8FD26BBE99049ECE FOREIGN KEY (modified_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3D9C9DE39 FOREIGN KEY (prices_id) REFERENCES ticket_price (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket_price ADD CONSTRAINT FK_E2F8415247551731 FOREIGN KEY (sport_event_id) REFERENCES sport_event (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket_price DROP FOREIGN KEY FK_E2F8415247551731');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3D9C9DE39');
        $this->addSql('ALTER TABLE sport_event DROP FOREIGN KEY FK_8FD26BBEB03A8386');
        $this->addSql('ALTER TABLE sport_event DROP FOREIGN KEY FK_8FD26BBE99049ECE');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3A76ED395');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE sport_event');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_price');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE xrate');
    }
}
