
from pathlib import Path
def check_project_root():
    if Path("package.json").is_file():
        return True
    return False