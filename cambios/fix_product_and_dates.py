
import os
import re

filename = 'index.php'

try:
    with open(filename, 'r', encoding='latin-1') as f:
        content = f.read()

    original_content = content
    changes_made = 0
    
    # Fix 1: Add originalId hidden field to product edit form
    old_hidden = '<input type="hidden" name="id" value="${movement.id}">'
    new_hidden = '<input type="hidden" name="id" value="${movement.id}">\n                        <input type="hidden" name="originalId" value="${movement.id || movement.reference}">'
    
    if old_hidden in content:
        content = content.replace(old_hidden, new_hidden)
        print("✓ Added originalId hidden field")
        changes_made += 1
    else:
        print("✗ Could not find hidden id field")
    
    # Fix 2: Fix date conversion to avoid timezone issues (use split instead of toISOString)
    # For product dates
    old_date_conversion = '''                    let productDate = '';
                    try {
                        if (movement.date || movement.created_at) {
                            const dateStr = movement.date || movement.created_at;
                            productDate = new Date(dateStr).toISOString().split('T')[0];
                        }
                    } catch(e) {
                        productDate = movement.date ? movement.date.split(' ')[0] : '';
                    }'''
    
    new_date_conversion = '''                    let productDate = '';
                    try {
                        if (movement.date || movement.created_at) {
                            const dateStr = movement.date || movement.created_at;
                            // Extract date part directly to avoid timezone issues
                            if (dateStr.includes(' ')) {
                                productDate = dateStr.split(' ')[0];
                            } else if (dateStr.includes('T')) {
                                productDate = dateStr.split('T')[0];
                            } else {
                                productDate = dateStr;
                            }
                        }
                    } catch(e) {
                        productDate = movement.date ? movement.date.split(' ')[0] : '';
                    }'''
    
    if old_date_conversion.replace('\n', '\r\n') in content:
        content = content.replace(old_date_conversion.replace('\n', '\r\n'), new_date_conversion.replace('\n', '\r\n'))
        print("✓ Fixed product date conversion")
        changes_made += 1
    elif old_date_conversion in content:
        content = content.replace(old_date_conversion, new_date_conversion)
        print("✓ Fixed product date conversion (LF)")
        changes_made += 1
    else:
        print("✗ Could not find product date conversion")
    
    # Fix 3: Update updateProduct function to use originalId
    old_update_product = '''        async function updateProduct(formData) {
            try {
                const response = await fetch('api/products.php', {
                    method: 'POST', // api/products.php usa POST para insert/update
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        id: formData.reference || formData.id,
                        name: formData.name,
                        quantity: formData.quantity,
                        purchasePrice: formData.purchasePrice,
                        wholesalePrice: formData.wholesalePrice,
                        retailPrice: formData.retailPrice,
                        supplier: formData.supplier,
                        date: formData.date
                    })
                });'''
    
    new_update_product = '''        async function updateProduct(formData) {
            try {
                // Use originalId if available, otherwise use reference or id
                const productId = formData.originalId || formData.reference || formData.id;
                
                const response = await fetch('api/products.php', {
                    method: 'POST', // api/products.php usa POST para insert/update
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        id: productId,
                        name: formData.name,
                        quantity: formData.quantity,
                        purchasePrice: formData.purchasePrice,
                        wholesalePrice: formData.wholesalePrice,
                        retailPrice: formData.retailPrice,
                        supplier: formData.supplier,
                        date: formData.date
                    })
                });'''
    
    if old_update_product.replace('\n', '\r\n') in content:
        content = content.replace(old_update_product.replace('\n', '\r\n'), new_update_product.replace('\n', '\r\n'))
        print("✓ Fixed updateProduct function")
        changes_made += 1
    elif old_update_product in content:
        content = content.replace(old_update_product, new_update_product)
        print("✓ Fixed updateProduct function (LF)")
        changes_made += 1
    else:
        print("✗ Could not find updateProduct function")
    
    # Fix 4: Fix all other date conversions (sales, warranties, restocks, expenses)
    # Pattern for date conversion using toISOString
    pattern = r"(\w+Date) = new Date\(([^)]+)\)\.toISOString\(\)\.split\('T'\)\[0\];"
    
    def fix_date_conversion(match):
        var_name = match.group(1)
        date_expr = match.group(2)
        return f'''{var_name} = (() => {{
                        const dateStr = {date_expr};
                        if (!dateStr) return '';
                        if (dateStr.includes(' ')) return dateStr.split(' ')[0];
                        if (dateStr.includes('T')) return dateStr.split('T')[0];
                        return dateStr;
                    }})();'''
    
    content_before = content
    content = re.sub(pattern, fix_date_conversion, content)
    if content != content_before:
        print("✓ Fixed other date conversions")
        changes_made += 1
    
    if content != original_content:
        with open(filename, 'w', encoding='latin-1') as f:
            f.write(content)
        print(f"\n✅ Successfully updated index.php ({changes_made} changes)")
    else:
        print("\n⚠️ No changes were made.")

except Exception as e:
    print(f"❌ Error: {e}")
    import traceback
    traceback.print_exc()
