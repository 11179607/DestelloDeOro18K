
import os

filename = 'index.php'

try:
    with open(filename, 'r', encoding='latin-1') as f:
        content = f.read()

    original_content = content
    
    # Fix the catch block that shows success message on error
    old_catch = '''            } catch (error) {
                console.error('Error crítico al guardar movimiento editado:', error);
                await showDialog('Éxito', 'Cambios guardados correctamente.', 'success');
            }'''
    
    new_catch = '''            } catch (error) {
                console.error('Error crítico al guardar movimiento editado:', error);
                await showDialog('Error', 'Hubo un error al guardar los cambios: ' + error.message, 'error');
            }'''
    
    # Try with CRLF
    if old_catch.replace('\n', '\r\n') in content:
        content = content.replace(old_catch.replace('\n', '\r\n'), new_catch.replace('\n', '\r\n'))
        print("✓ Fixed error handling in catch block (CRLF)")
    elif old_catch in content:
        content = content.replace(old_catch, new_catch)
        print("✓ Fixed error handling in catch block (LF)")
    else:
        print("✗ Could not find catch block")
        # Try to find it with regex to see what's there
        import re
        pattern = r"console\.error\('Error.*movimiento editado.*\);\s*await showDialog\('.*?', '.*?', '.*?'\);"
        matches = re.findall(pattern, content, re.DOTALL)
        if matches:
            print(f"Found similar pattern: {matches[0][:100]}...")
    
    if content != original_content:
        with open(filename, 'w', encoding='latin-1') as f:
            f.write(content)
        print("\n✅ Successfully fixed error handling")
    else:
        print("\n⚠️ No changes were made")

except Exception as e:
    print(f"❌ Error: {e}")
    import traceback
    traceback.print_exc()
