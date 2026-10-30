
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Validate inventory transactions before insertion.
        DB::unprepared("
            CREATE TRIGGER inventory_transactions_before_insert
            BEFORE INSERT ON inventory_transactions
            FOR EACH ROW
            BEGIN
                DECLARE current_stock INT DEFAULT 0;

                SELECT quantity INTO current_stock
                FROM products
                WHERE id = NEW.product_id;

                IF NEW.type NOT IN ('in', 'out', 'adjustment') THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Invalid inventory transaction type.';
                END IF;

                IF NEW.type IN ('in', 'out') AND NEW.quantity <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Stock In and Stock Out quantities must be positive.';
                END IF;

                IF NEW.type = 'out' AND current_stock < NEW.quantity THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Insufficient stock for this transaction.';
                END IF;

                IF NEW.type = 'adjustment'
                    AND current_stock + NEW.quantity < 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Adjustment cannot result in negative stock.';
                END IF;
            END
        ");

        // Automatically update product stock after insertion.
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
        DB::unprepared("DROP TRIGGER IF EXISTS inventory_transactions_after_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS inventory_transactions_before_insert");
    }
};
