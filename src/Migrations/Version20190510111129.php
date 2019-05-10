<?php

declare(strict_types=1);

/*
 * This file is part of the package t3g/intercept.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190510111129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__documentation_jar AS SELECT id, repository_url, package_name, branch, created_at, last_rendered_at FROM documentation_jar');
        $this->addSql('DROP TABLE documentation_jar');
        $this->addSql('CREATE TABLE documentation_jar (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, repository_url VARCHAR(255) NOT NULL, package_name VARCHAR(255) NOT NULL, branch VARCHAR(255) NOT NULL, target_branch_directory VARCHAR(250) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, last_rendered_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL)');
        $this->addSql('INSERT INTO documentation_jar (id, repository_url, package_name, branch, created_at, last_rendered_at, target_branch_directory) SELECT id, repository_url, package_name, branch, created_at, last_rendered_at, branch FROM __temp__documentation_jar');
        $this->addSql('DROP TABLE __temp__documentation_jar');

        //$this->addSql('ALTER TABLE documentation_jar ADD COLUMN target_branch_directory VARCHAR(255) DEFAULT branch NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__documentation_jar AS SELECT id, repository_url, package_name, branch, created_at, last_rendered_at FROM documentation_jar');
        $this->addSql('DROP TABLE documentation_jar');
        $this->addSql('CREATE TABLE documentation_jar (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, repository_url VARCHAR(255) NOT NULL, package_name VARCHAR(255) NOT NULL, branch VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, last_rendered_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL)');
        $this->addSql('INSERT INTO documentation_jar (id, repository_url, package_name, branch, created_at, last_rendered_at) SELECT id, repository_url, package_name, branch, created_at, last_rendered_at FROM __temp__documentation_jar');
        $this->addSql('DROP TABLE __temp__documentation_jar');
    }
}
