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
        return 'Load default data';
    }

    public function up(Schema $schema): void
    {
        // Autonomous comunity
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

        // Equipment
        $this->addSql(<<<SQL
        INSERT INTO equipment (name,`type`,description,icon) 
            VALUES
            ('equipment.washing_machine',NULL,NULL,'mdi:washing-machine'),
            ('equipment.microwave',NULL,NULL,'material-symbols:microwave'),
            ('equipment.elevator',NULL,NULL,'medical-icon:elevators');
        SQL);

        // Rule
        $this->addSql(<<<SQL
        INSERT INTO rule (name, description, icon)
            VALUES
            ('rule.no_animals', NULL, 'foundation:no-dogs');
        SQL);

        // State
        $this->addSql(<<<SQL
        INSERT INTO state (name, description, icon)
            VALUES
            ('state.good', NULL, 'circum:face-smile'),
            ('state.normal', NULL, 'circum:face-meh'),
            ('state.bad', NULL, 'circum:face-frown');
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE autonomous_comunity');

        $this->addSql("TRUNCATE TABLE equipment");
        $this->addSql("TRUNCATE TABLE rule");
        $this->addSql("TRUNCATE TABLE state");
    }
}
