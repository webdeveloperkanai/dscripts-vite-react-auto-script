import os 
from modules.utils.accept_input import accept_input
from modules.table import create_table2
from pathlib import Path
from modules.utils.save_file import save_file

def func_newtable():
    tableName, items = accept_input()
    project_root = Path.cwd()
    os.chdir(project_root)

    # ask page name
    page_name = input("Enter page name [without space, CamelCase]: ").strip()
    if not page_name:
        print("❌ Page name is required.")
        return
    table = create_table2(pageName=page_name, tableName=tableName, items=items)
    save_file(f"{project_root}/{page_name}.jsx", table)

