#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script para corregir dos problemas en la edición de productos:
1. Título del modal que dice "Editar Garantía" en lugar de "Editar Producto de Inventario"
2. Fecha que se guarda con un día menos debido a la conversión UTC
"""

import re
import sys

def fix_product_edit_issues(file_path):
    """
    Corrige los problemas de edición de productos en index.php
    """
    print(f"Leyendo archivo: {file_path}")
    
    # Intentar diferentes codificaciones
    encodings = ['utf-8', 'latin-1', 'cp1252', 'iso-8859-1']
    content = None
    used_encoding = None
    
    for encoding in encodings:
        try:
            with open(file_path, 'r', encoding=encoding) as f:
                content = f.read()
            used_encoding = encoding
            print(f"✓ Archivo leído con codificación: {encoding}")
            break
        except Exception as e:
            continue
    
    if content is None:
        print(f"❌ Error: No se pudo leer el archivo con ninguna codificación")
        return False
    
    original_content = content
    changes_made = []
    
    # ============================================================
    # FIX 1: Corregir el título del modal de edición
    # ============================================================
    # Buscar el patrón donde se establece el título del modal
    # Debe cambiar de "Editar Garantía" a incluir el caso de 'product'
    
    pattern1 = r"modalTitle\.textContent\s*=\s*`Editar\s+\$\{type\s*===\s*'sales'\s*\?\s*'Venta'\s*:\s*type\s*===\s*'expenses'\s*\?\s*'Gasto'\s*:\s*'Garantía'\}`;"
    
    replacement1 = r"modalTitle.textContent = `Editar ${type === 'sales' ? 'Venta' : type === 'expenses' ? 'Gasto' : type === 'product' ? 'Producto de Inventario' : 'Garantía'}`;"
    
    if re.search(pattern1, content):
        content = re.sub(pattern1, replacement1, content)
        changes_made.append("✓ Título del modal corregido para productos")
    else:
        print("⚠ No se encontró el patrón del título del modal")
    
    # ============================================================
    # FIX 2: Corregir la conversión de fecha para productos
    # ============================================================
    # Buscar donde se convierte la fecha del producto usando toISOString
    # y reemplazarlo con una función que no cambie la zona horaria
    
    # Primero, buscar el bloque donde se convierte productDate
    pattern2 = r"(case\s+'product':\s*\n\s*let\s+productDate\s*=\s*'';\s*\n\s*try\s*\{\s*\n\s*)productDate\s*=\s*new\s+Date\(movement\.date\)\.toISOString\(\)\.split\('T'\)\[0\];"
    
    replacement2 = r"\1// Convertir fecha sin cambiar zona horaria\n                      const dateObj = new Date(movement.date);\n                      productDate = dateObj.toLocaleDateString('en-CA'); // Formato YYYY-MM-DD"
    
    if re.search(pattern2, content, re.MULTILINE):
        content = re.sub(pattern2, replacement2, content, flags=re.MULTILINE)
        changes_made.append("✓ Conversión de fecha de producto corregida")
    else:
        # Intentar un patrón más flexible
        pattern2_alt = r"(let\s+productDate\s*=\s*'';\s*try\s*\{)\s*productDate\s*=\s*new\s+Date\(movement\.date\)\.toISOString\(\)\.split\('T'\)\[0\];"
        
        if re.search(pattern2_alt, content):
            # Buscar y reemplazar de forma más específica
            lines = content.split('\n')
            new_lines = []
            i = 0
            while i < len(lines):
                line = lines[i]
                
                # Buscar la línea específica
                if 'productDate = new Date(movement.date).toISOString().split' in line and 'case \'product\':' in '\n'.join(lines[max(0, i-10):i]):
                    # Reemplazar esta línea
                    indent = len(line) - len(line.lstrip())
                    new_lines.append(' ' * indent + '// Convertir fecha sin cambiar zona horaria')
                    new_lines.append(' ' * indent + 'const dateObj = new Date(movement.date);')
                    new_lines.append(' ' * indent + 'productDate = dateObj.toLocaleDateString(\'en-CA\'); // Formato YYYY-MM-DD')
                    changes_made.append("✓ Conversión de fecha de producto corregida (método alternativo)")
                else:
                    new_lines.append(line)
                
                i += 1
            
            content = '\n'.join(new_lines)
    
    # ============================================================
    # Verificar si se hicieron cambios
    # ============================================================
    if content == original_content:
        print("\n⚠ No se realizaron cambios. Verificando patrones...")
        
        # Verificar qué patrones existen
        if 'modalTitle.textContent' in content:
            print("  - Se encontró 'modalTitle.textContent'")
            # Extraer el contexto
            for match in re.finditer(r'.{0,100}modalTitle\.textContent.{0,100}', content):
                print(f"    Contexto: {match.group()[:200]}")
        
        if 'productDate' in content:
            print("  - Se encontró 'productDate'")
            for match in re.finditer(r'.{0,50}productDate.{0,100}', content):
                print(f"    Contexto: {match.group()[:150]}")
        
        return False
    
    # ============================================================
    # Guardar el archivo modificado
    # ============================================================
    try:
        with open(file_path, 'w', encoding=used_encoding) as f:
            f.write(content)
        
        print(f"\n✅ Archivo actualizado exitosamente!")
        print(f"   Codificación usada: {used_encoding}")
        print(f"\nCambios realizados:")
        for change in changes_made:
            print(f"  {change}")
        
        return True
        
    except Exception as e:
        print(f"\n❌ Error al guardar el archivo: {e}")
        return False

if __name__ == "__main__":
    file_path = r"c:\Users\PARQUEADERO EXT\Documents\DestellodeOro18K\index.php"
    
    print("=" * 60)
    print("CORRECCIÓN DE PROBLEMAS EN EDICIÓN DE PRODUCTOS")
    print("=" * 60)
    print("\nProblemas a corregir:")
    print("1. Título del modal: 'Editar Garantía' → 'Editar Producto de Inventario'")
    print("2. Fecha con un día menos debido a conversión UTC")
    print()
    
    success = fix_product_edit_issues(file_path)
    
    if success:
        print("\n" + "=" * 60)
        print("✅ CORRECCIONES COMPLETADAS")
        print("=" * 60)
        sys.exit(0)
    else:
        print("\n" + "=" * 60)
        print("❌ NO SE PUDIERON APLICAR TODAS LAS CORRECCIONES")
        print("=" * 60)
        sys.exit(1)
