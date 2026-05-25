<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260602090000_CreateFoodsTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create foods table and seed OpenFoodFacts data.';
    }

    public function up(Schema $schema): void
    {
        // Create foods table using Schema API for cross-platform compatibility
        if ($schema->hasTable('foods')) {
            return;
        }

        $table = $schema->createTable('foods');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('name', 'string', ['length' => 150]);
        $table->addColumn('category', 'string', ['length' => 50]);
        $table->addColumn('calories', 'decimal', ['precision' => 6, 'scale' => 2, 'default' => 0]);
        $table->addColumn('protein', 'decimal', ['precision' => 6, 'scale' => 2, 'default' => 0]);
        $table->addColumn('carbs', 'decimal', ['precision' => 6, 'scale' => 2, 'default' => 0]);
        $table->addColumn('fat', 'decimal', ['precision' => 6, 'scale' => 2, 'default' => 0]);
        $table->addColumn('serving', 'string', ['length' => 60, 'default' => '100g']);
        $table->addColumn('image_url', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('source', 'string', ['length' => 50, 'default' => 'openfoodfacts']);
        $table->addColumn('source_product_id', 'string', ['length' => 100, 'notnull' => false]);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);

        $table->addUniqueIndex(['source', 'source_product_id'], 'ux_foods_source_product');
        $table->addIndex(['name'], 'idx_foods_name');
        $table->addIndex(['category'], 'idx_foods_category');
        $table->addIndex(['created_at'], 'idx_foods_created_at');

        // We intentionally do not seed the entire OpenFoodFacts dataset here —
        // seeding can be done via a separate import step (supabase SQL or import script).
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE foods');
    }
}
