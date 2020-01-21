<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200121094559 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attempt_question_answer (id INT AUTO_INCREMENT NOT NULL, quiz_attempt_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_A9525362F8FE9957 (quiz_attempt_id), INDEX IDX_A95253621E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE learner (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_attempt (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, learner_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', is_submitted TINYINT(1) NOT NULL, max_score INT NOT NULL, score INT NOT NULL, started_at DATETIME NOT NULL, submitted_at DATETIME DEFAULT NULL, INDEX IDX_AB6AFC6853CD175 (quiz_id), INDEX IDX_AB6AFC66209CB66 (learner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attempt_question_answer ADD CONSTRAINT FK_A9525362F8FE9957 FOREIGN KEY (quiz_attempt_id) REFERENCES quiz_attempt (id)');
        $this->addSql('ALTER TABLE attempt_question_answer ADD CONSTRAINT FK_A95253621E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE quiz_attempt ADD CONSTRAINT FK_AB6AFC6853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz_attempt ADD CONSTRAINT FK_AB6AFC66209CB66 FOREIGN KEY (learner_id) REFERENCES learner (id)');
        $this->addSql('ALTER TABLE question CHANGE config config JSON DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quiz_attempt DROP FOREIGN KEY FK_AB6AFC66209CB66');
        $this->addSql('ALTER TABLE attempt_question_answer DROP FOREIGN KEY FK_A9525362F8FE9957');
        $this->addSql('DROP TABLE attempt_question_answer');
        $this->addSql('DROP TABLE learner');
        $this->addSql('DROP TABLE quiz_attempt');
        $this->addSql('ALTER TABLE question CHANGE config config LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin');
    }
}
