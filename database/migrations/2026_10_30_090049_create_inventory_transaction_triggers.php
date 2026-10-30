
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER inventory_transactions_after_insert
            AFTER INSERT ON inventory_transactions
            FOR EACH ROW
            BEGIN
                IF NEW.type = 'in' THEN
                    UPDATE products
                    SET quantity = quantity + NEW.quantity
                    WHERE id = NEW.product_id;

                ELSEIF NEW.type = 'out' THEN
                    UPDATE products
                    SET quantity = quantity - NEW.quantity
                    WHERE id = NEW.product_id;

                ELSEIF NEW.type = 'adjustment' THEN
                    UPDATE products
                    SET quantity = quantity + NEW.quantity
                    WHERE id = NEW.product_id;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS inventory_transactions_after_insert
        ");
    }
};
