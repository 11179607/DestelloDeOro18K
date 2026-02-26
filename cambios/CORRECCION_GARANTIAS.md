# Corrección de Tabla de Garantías - Resumen

## 🔍 Problema Identificado

En la tabla de garantías, algunos datos no coincidían correctamente con los nombres de los campos:

1. **Fecha**: El campo `created_at` no se mapeaba a `date`, causando inconsistencia con otras tablas
2. **Motivo**: El campo `reason` se mapeaba correctamente pero faltaba documentación
3. **Usuario**: El campo `username` no se mapeaba a `user`, causando problemas en la visualización

## ✅ Solución Implementada

### Archivo Modificado: `api/warranties.php`

Se corrigió el mapeo de campos en la función GET (líneas 38-68) para que coincidan correctamente con lo que espera el frontend:

#### Mapeo de Campos Actualizado:

```php
// Fecha: mapear created_at a 'date' para consistencia con otras tablas
$warranty['date'] = $warranty['created_at'];
$warranty['createdAt'] = $warranty['created_at'];
$warranty['endDate'] = $warranty['end_date'] ?? null;

// Cliente
$warranty['customerName'] = $warranty['customer_name'];

// Venta original
$warranty['originalSaleId'] = $warranty['original_invoice_id'];

// Producto original
$warranty['originalProductId'] = $warranty['product_ref'];
$warranty['originalProductName'] = $warranty['product_name'];

// Motivo de garantía
$warranty['warrantyReason'] = $warranty['reason'];
$warranty['warrantyReasonText'] = $warranty['reason'];

// Costos
$warranty['totalCost'] = (float)($warranty['total_cost'] ?? 0);
$warranty['additionalValue'] = (float)($warranty['additional_value'] ?? 0);
$warranty['shippingValue'] = (float)($warranty['shipping_value'] ?? 0);

// Usuario
$warranty['user'] = $warranty['username'] ?? 'admin';
$warranty['createdBy'] = $warranty['username'] ?? 'admin';
```

## 📊 Campos Corregidos

### 1. **Fecha** ✓
- **Antes**: Solo `createdAt` estaba disponible
- **Ahora**: Tanto `date` como `createdAt` apuntan a `created_at`
- **Beneficio**: Consistencia con otras tablas (sales, expenses, restocks)

### 2. **Motivo** ✓
- **Campo DB**: `reason`
- **Campos JS**: `warrantyReason` y `warrantyReasonText`
- **Beneficio**: Compatibilidad con diferentes partes del frontend

### 3. **Usuario** ✓
- **Antes**: Solo `createdBy` estaba disponible
- **Ahora**: Tanto `user` como `createdBy` apuntan a `username`
- **Beneficio**: Consistencia con otras tablas

### 4. **Costos** ✓
- `totalCost` → `total_cost`
- `additionalValue` → `additional_value`
- `shippingValue` → `shipping_value`
- **Beneficio**: Conversión automática a float

### 5. **Cliente y Producto** ✓
- `customerName` → `customer_name`
- `originalSaleId` → `original_invoice_id`
- `originalProductId` → `product_ref`
- `originalProductName` → `product_name`
- **Beneficio**: Nombres descriptivos y consistentes

## 🎯 Resultado

Ahora la tabla de garantías muestra correctamente:

1. ✅ **Fecha**: Se muestra la fecha de creación de la garantía
2. ✅ **Motivo**: Se muestra el motivo de la garantía (reason)
3. ✅ **Cliente**: Nombre del cliente
4. ✅ **Costo**: Costo total de la garantía
5. ✅ **Estado**: Estado actual (pending, in_process, completed, cancelled)
6. ✅ **Usuario**: Usuario que registró la garantía

## 🔄 Compatibilidad

Los cambios son **100% retrocompatibles**:
- Se mantienen todos los campos anteriores
- Se agregan campos adicionales para mayor flexibilidad
- No se eliminan campos existentes

## 📝 Notas Adicionales

- Todos los campos numéricos se convierten a `float` para evitar problemas de tipo
- Los campos opcionales usan el operador `??` para valores por defecto
- La documentación con comentarios facilita el mantenimiento futuro

---

**Fecha de corrección**: 2026-02-04  
**Archivo modificado**: `api/warranties.php`  
**Líneas modificadas**: 38-68
