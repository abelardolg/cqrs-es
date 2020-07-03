<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180204151052 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', name VARCHAR(255) NOT NULL, credentials_email VARCHAR(255) NOT NULL, credentials_password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9299C9369 (credentials_email), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (project_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\',  name VARCHAR(255) NOT NULL,  description VARCHAR(255) NOT NULL,  UNIQUE INDEX UNIQ_1483A5E9299C9369 (name), PRIMARY KEY(project_id), FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasks (task_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', project_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', name VARCHAR(255) NOT NULL,  description VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9299C9369 (name), PRIMARY KEY(task_id), FOREIGN KEY(project_id) REFERENCES projects(project_id) ON DELETE CASCADE) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE tasks');
    }
}
