<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202133717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student_answer ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student_answer ADD CONSTRAINT FK_54EB92A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_54EB92A5A76ED395 ON student_answer (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student_answer DROP FOREIGN KEY FK_54EB92A5A76ED395');
        $this->addSql('DROP INDEX IDX_54EB92A5A76ED395 ON student_answer');
        $this->addSql('ALTER TABLE student_answer DROP user_id');
    }
}
