import os
from pathlib import Path
from modules.utils import accept_input_for_selection
from modules.selection import create_selection

def func_newselection():
    tableName, value = accept_input_for_selection()
    project_root = Path.cwd()
    os.chdir(project_root)

    create_selection(tableName=tableName,  value=value)