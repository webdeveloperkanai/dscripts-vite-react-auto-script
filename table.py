import os
from pathlib import Path
from modules import *
from modules.create import create_form
from modules.edit import edit_form


def main():
    # page_name = input("Enter your page name: ").strip()
    # if not page_name:
    #     print("❌ Page name is required.")
    #     return
    # page_name = page_name.replace(" ", "_")

    tableName = input("Enter database table name: ").strip()
    if not tableName:
        print("❌ Table name is required.")
        return

    items_input = input("Enter your items (comma separated): ").strip()
    if not items_input:
        print("❌ Items are required.")
        return

    # check directory is exists or not 
    if not os.path.exists(tableName):
        os.makedirs(tableName)
    
    # change directory 
    os.chdir(tableName)

    items = items_input.split(',')

    component_code = generate_component(tableName, items)

    # Create file
    output_path = Path.cwd() / f"{tableName.capitalize()}Manage.jsx"
    with open(output_path, "w", encoding="utf-8") as f:
        f.write(component_code)

    createForm = create_form(f"{tableName}Create", tableName, items)
    output_path2 = Path.cwd() / f"{tableName.capitalize()}Create.jsx"
    with open(output_path2, "w", encoding="utf-8") as f:
        f.write(createForm)

    editForm = edit_form(f"{tableName}Edit", tableName, items)
    editFormOutput = Path.cwd() / f"{tableName.capitalize()}Edit.jsx"
    with open(editFormOutput, "w", encoding="utf-8") as f:
        f.write(editForm)


    print(f"✅ React component '{output_path.name}' created successfully at:\n{output_path}")

if __name__ == "__main__":
    main()
