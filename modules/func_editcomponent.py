import os 
from pathlib import Path
from modules.utils.accept_input import accept_input
from modules.edit import edit_form
from modules.utils.save_file import save_file

def func_editcomponent(): 
    tableName, items = accept_input()
    project_root = Path.cwd()
    os.chdir(project_root)
    res= edit_form(tableName=tableName, items=items)
    save_file(f"{project_root}/{tableName}Edit.jsx", res)