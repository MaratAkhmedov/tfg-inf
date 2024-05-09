<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509223125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Load default autonomous comunities for Spain';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
        INSERT INTO `autonomous_comunity` (`id`, `name`) 
            VALUES 
            (1,'Comunidad Valenciana'),
            (2,'Andalucía'),
            (3,'Aragón'),
            (4,'Cantabria'),
            (5,'Castilla y León'),
            (6,'Castilla-La Mancha'),
            (7,'Cataluña'),
            (8,'Ceuta'),
            (9,'Comunidad de Madrid'),
            (10,'Extremadura'),
            (11,'Galicia'),
            (12,'Islas Baleares'),
            (13,'Islas Canarias'),
            (14,'La Rioja'),
            (15,'Melilla'),
            (16,'País Vasco'),
            (17,'Navarra'),
            (18,'Principado de Asturias'),
            (19,'Región de Murcia');
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE DROP autonomous_comunity');
    }
}
