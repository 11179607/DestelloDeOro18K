-- Migración: Reorganizar columnas para que la fecha aparezca primero
-- y asegurar que las fechas usen la hora actual del sistema

USE destello_oro;

-- 1. TABLA SALES: Reorganizar para que sale_date sea la primera columna después del ID
ALTER TABLE sales MODIFY COLUMN sale_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id;

-- 2. TABLA EXPENSES: Reorganizar para que expense_date sea la primera columna después del ID
ALTER TABLE expenses MODIFY COLUMN expense_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id;

-- 3. TABLA WARRANTIES: Reorganizar para que created_at sea la primera columna después del ID
ALTER TABLE warranties MODIFY COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id;

-- 4. TABLA RESTOCKS: Reorganizar para que restock_date sea la primera columna después del ID
ALTER TABLE restocks MODIFY COLUMN restock_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id;

-- 5. TABLA PRODUCTS: Reorganizar para que created_at sea la primera columna después de reference
-- (entry_date ya existe, pero aseguramos que created_at esté visible)
ALTER TABLE products MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER reference;

-- Verificación: Mostrar estructura de las tablas
DESCRIBE sales;
DESCRIBE expenses;
DESCRIBE warranties;
DESCRIBE restocks;
DESCRIBE products;
