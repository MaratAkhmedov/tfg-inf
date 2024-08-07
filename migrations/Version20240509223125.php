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
        INSERT INTO `autonomous_comunity` (`id`, `name`, `iso_code`) 
            VALUES 
            (1,'Comunidad Valenciana', 'VC'),
            (2,'Andalucía', 'AN'),
            (3,'Aragón', 'AR'),
            (4,'Cantabria', 'CB'),
            (5,'Castilla y León', 'CL'),
            (6,'Castilla-La Mancha', 'CM'),
            (7,'Cataluña', 'CT'),
            (8,'Ceuta', 'CE'),
            (9,'Comunidad de Madrid', ''),
            (10,'Extremadura', 'EX'),
            (11,'Galicia', 'GA'),
            (12,'Islas Baleares', 'IB'),
            (13,'Islas Canarias', 'CN'),
            (14,'La Rioja', 'RI'),
            (15,'Melilla', 'ML'),
            (16,'País Vasco', 'PV'),
            (17,'Navarra', 'NC'),
            (18,'Principado de Asturias', 'AS'),
            (19,'Región de Murcia', 'MC');
        SQL);

        $this->addSql(<<<SQL
        INSERT INTO province (id, name, iso_code,autonomous_comunity_id) 
            VALUES
            (1, 'Álava', 'VI', 16),
            (2, 'Albacete', 'AB', 6),
            (3, 'Alicante/Alacant', 'A', 1),
            (4, 'Almería', 'AL', 2),
            (5, 'Ávila', 'AV', 5),
            (6, 'Badajoz', 'BA', 10),
            (7, 'Islas Baleares', 'PM', 12),
            (8, 'Barcelona', 'B', 7),
            (9, 'Burgos', 'BU', 5),
            (10, 'Cáceres', 'CC', 10),
            (11, 'Cádiz', 'CA', 2),
            (12, 'Castellón/Castelló', 'CS', 1),
            (13, 'Ciudad Real', 'CR', 6),
            (14, 'Córdoba', 'CO', 2),
            (15, 'A Coruña/La Coruña', 'C', 11),
            (16, 'Cuenca', 'CU', 6),
            (17, 'Girona', 'GI', 7),
            (18, 'Granada', 'GR', 2),
            (19, 'Guadalajara', 'GU', 6),
            (20, 'Gipuzkoa', 'SS', 16),
            (21, 'Huelva', 'H', 2),
            (22, 'Huesca', 'HU', 3),
            (23, 'Jaén', 'J', 2),
            (24, 'León', 'LE', 5),
            (25, 'Lérida/Lleida', 'L', 7),
            (26, 'La Rioja', 'LO', 14),
            (27, 'Lugo', 'LU', 11),
            (28, 'Madrid', 'M', 9),
            (29, 'Málaga', 'MA', 2),
            (30, 'Murcia', 'MU', 19),
            (31, 'Navarra', 'NA', 17),
            (32, 'Ourense', 'OR', 11),
            (33, 'Asturias', 'O', 18),
            (34, 'Palencia', 'P', 5),
            (35, 'Las Palmas', 'GC', 13),
            (36, 'Pontevedra', 'PO', 11),
            (37, 'Salamanca', 'SA', 5),
            (38, 'Santa Cruz de Tenerife', 'TF', 13),
            (39, 'Cantabria', 'S', 4),
            (40, 'Segovia', 'SG', 5),
            (41, 'Sevilla', 'SE', 2),
            (42, 'Soria', 'SO', 5),
            (43, 'Tarragona', 'T', 7),
            (44, 'Teruel', 'TE', 3),
            (45, 'Toledo', 'TO', 6),
            (46, 'Valencia/València', 'V', 1),
            (47, 'Valladolid', 'VA', 5),
            (48, 'Bizkaia', 'BI', 16),
            (49, 'Zamora', 'ZA', 5),
            (50, 'Zaragoza', 'Z', 3),
            (51, 'Ceuta', 'CE', 8),
            (52, 'Melilla', 'ML', 15);
        SQL);

        $this->addSql(<<<SQL
        INSERT INTO city (name,province_id) 
            VALUES
            ('Valencia', 46);
        SQL);

        // Flat attributes
        $this->addSql(<<<SQL
        INSERT INTO `attribute` (name,description,icon,label,discr) 
        VALUES
        ('furnished',NULL,'bx:cabinet','flat.attributes.furnished','flat'),
        ('elevator',NULL,'iconoir:elevator','flat.attributes.elevator','flat'),
        ('garage',NULL,'material-symbols:garage-outline','flat.attributes.garage','flat'),
        ('storage_room',NULL,'cil:room','flat.attributes.storage_room','flat'),
        ('terrace',NULL,'iconoir:balcony','flat.attributes.terrace','flat'),
        ('pool',NULL,'mdi:pool','flat.attributes.pool','flat'),
        ('reduced_mobility',NULL,'tabler:disabled','flat.attributes.reduced_mobility','flat');
        SQL);

        $this->addSql(<<<SQL
        INSERT INTO `attribute` (name,description,icon,label,discr) 
        VALUES
        ('cupboard',NULL,'iconoir:closet','room.attributes.cupboard','room'),
        ('desk',NULL,'material-symbols-light:desk','room.attributes.desk','room'),
        ('chair',NULL,'ph:chair-light','room.attributes.chair','room'),
        ('window',NULL,'material-symbols:window-outline','room.attributes.window','room'),
        ('tv',NULL,'ri:tv-line','room.attributes.tv','room'),
        ('couch',NULL,'mdi:couch','room.attributes.couch',''),
        ('balcony',NULL,'iconoir:balcony','room.attributes.balcony','room'),
        ('private_bathroom',NULL,'cil:bathroom','room.attributes.private_bathroom','room');
        SQL);

        // Rule
        $this->addSql(<<<SQL
        INSERT INTO rule (id, name, label, description, icon)
            VALUES
            (1, 'no_animals','rule.no_animals', NULL, 'foundation:no-dogs'),
            (2, 'no_smoke','rule.no_smoke', NULL, 'cil:smoke-free');
        SQL);

        // State
        $this->addSql(<<<SQL
        INSERT INTO state (id, name, label, description, icon)
            VALUES
            (1, 'good', 'state.good', NULL, 'circum:face-smile'),
            (2, 'normal', 'state.normal', NULL, 'circum:face-meh'),
            (3, 'bad', 'state.bad', NULL, 'circum:face-frown');
        SQL);

         // PropertyType
         $this->addSql(<<<SQL
         INSERT INTO property_type (id, name, label)
             VALUES
             (1, 'room','property.type.room'),
             (2, 'flat','property.type.flat');
         SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE autonomous_comunity');

        $this->addSql("TRUNCATE TABLE equipment");
        $this->addSql("TRUNCATE TABLE attribute");
        $this->addSql("TRUNCATE TABLE rule");
        $this->addSql("TRUNCATE TABLE state");
    }
}
