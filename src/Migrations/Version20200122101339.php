<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122101339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attempt_question_answer ADD value JSON DEFAULT NULL, ADD value_type VARCHAR(255) NOT NULL, ADD max_score INT NOT NULL, ADD score INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question CHANGE config config JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE quiz_attempt CHANGE score score INT DEFAULT NULL, CHANGE submitted_at submitted_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attempt_question_answer DROP value, DROP value_type, DROP max_score, DROP score');
        $this->addSql('ALTER TABLE question CHANGE config config LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE quiz_attempt CHANGE score score INT DEFAULT NULL, CHANGE submitted_at submitted_at DATETIME DEFAULT \'NULL\'');
    }
}
