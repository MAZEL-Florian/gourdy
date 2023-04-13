<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220405012451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD sujet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497C4D497E FOREIGN KEY (sujet_id) REFERENCES sujet (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6497C4D497E ON user (sujet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497C4D497E');
        $this->addSql('DROP INDEX IDX_8D93D6497C4D497E ON user');
        $this->addSql('ALTER TABLE user DROP sujet_id');
    }
}
