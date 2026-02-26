#!/usr/bin/env python3
# -*- coding: latin-1 -*-
"""
Script para corregir el título del modal de edición de productos
"""

import sys

def fix_modal_title(file_path):
    """
    Corrige el título del modal para productos
    """
    print(f"Leyendo archivo: {file_path}")
    
    # Leer con latin-1
    try:
        with open(file_path, 'r', encoding='latin-1') as f:
            content = f.read()
        print(f"✓ Archivo leído con codificación: latin-1")
    except Exception as e:
        print(f"❌ Error al leer: {e}")
        return False
    
    original_content = content
    
    # Buscar la línea exacta (línea 6745)
    lines = content.split('\n')
    target_line_num = 6744  # índice 0-based
    
    if target_line_num < len(lines):
        old_line = lines[target_line_num]
        print(f"\nLínea original {target_line_num + 1}:")
        print(f"  {repr(old_line[:100])}")
        
        # Verificar que es la línea correcta
        if "modalTitle.textContent" in old_line and "Editar" in old_line:
            # Extraer la indentación
            indent = len(old_line) - len(old_line.lstrip())
            indent_str = old_line[:indent]
            
            # Nueva línea con el mismo formato
            new_line = indent_str + "modalTitle.textContent = `Editar ${type === 'sales' ? 'Venta' : type === 'expenses' ? 'Gasto' : type === 'product' ? 'Producto de Inventario' : 'Garantía'}`;"
            
            # Reemplazar la línea
            lines[target_line_num] = new_line
            content = '\n'.join(lines)
            
            print(f"\nLínea nueva:")
            print(f"  {repr(new_line[:100])}")
            print("\n✓ Título del modal corregido")
        else:
            print(f"⚠ La línea {target_line_num + 1} no contiene el patrón esperado")
            return False
    else:
        print(f"⚠ El archivo no tiene suficientes líneas")
        return False
    
    if content == original_content:
        print("\n⚠ No se realizaron cambios")
        return False
    
    # Guardar
    try:
        with open(file_path, 'w', encoding='latin-1') as f:
            f.write(content)
        print(f"\n✅ Archivo actualizado exitosamente!")
        return True
    except Exception as e:
        print(f"\n❌ Error al guardar: {e}")
        return False

if __name__ == "__main__":
    file_path = r"c:\Users\PARQUEADERO EXT\Documents\DestellodeOro18K\index.php"
    
    print("=" * 60)
    print("CORRECCIÓN DEL TÍTULO DEL MODAL")
    print("=" * 60)
    print()
    
    success = fix_modal_title(file_path)
    
    if success:
        print("\n✅ CORRECCIÓN COMPLETADA")
    else:
        print("\n⚠ VERIFICAR MANUALMENTE")
    
    sys.exit(0 if success else 1)
