import os
from pathlib import Path
from modules.utils.accept_input import accept_input
from modules.create import create_form
from modules.utils.save_file import save_file

def func_addcomponent():
    tableName, items = accept_input()
    project_root = Path.cwd()
    os.chdir(project_root)
    res = create_form(tableName=tableName, items=items)
    save_file(f"{project_root}/{tableName}Create.jsx", res)