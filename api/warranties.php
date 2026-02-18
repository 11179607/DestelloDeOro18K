$stmt->execute([
    ':sid' => $saleIdInt,
    ':inv' => $data->originalSaleId ?? '',
    ':cust' => $data->customerName ?? '',
    ':pref' => $data->originalProductId ?? '',
    ':pname' => $data->originalProductName ?? '',
    ':quantity' => $data->quantity ?? 1,  // ← Cambiado de :qty_val a :quantity
    ':reason' => $data->warrantyReason ?? '',
    ':edate' => $data->endDate ?? null,
    ':notes' => $data->notes ?? '',
    ':ptype' => $data->productType ?? 'same',
    ':npref' => $data->newProductRef ?? null,
    ':npname' => $data->newProductName ?? null,
    ':addval' => $data->additionalValue ?? 0,
    ':shipval' => $data->shippingValue ?? 0,
    ':total' => $totalCost,
    ':status' => $data->status ?? 'pending',
    ':uid' => $_SESSION['user_id'],
    ':uname' => $_SESSION['username'],
    ':created_at' => $createdAt
]);