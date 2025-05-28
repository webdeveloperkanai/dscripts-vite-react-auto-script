import os
from pathlib import Path
from modules.utils.accept_input_for_selection import accept_input_for_selection
from modules.selection import create_selection
from modules.utils.save_file import save_file

def func_newselection():
    tableName, value = accept_input_for_selection()
    project_root = Path.cwd()
    os.chdir(project_root)

    rs = create_selection(tableName=tableName,  value=value)
    save_file(f"{project_root}/{tableName}Selection.jsx", rs)