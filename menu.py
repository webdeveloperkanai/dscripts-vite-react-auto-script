import os
from modules import *
from pathlib import Path
from modules.create import create_form
from modules.edit import edit_form
from modules.selection import create_selection
from modules.table import create_table, create_table2
from modules.createProject import create_project


def save_file(filePath, content): 
    with open(filePath, "w", encoding="utf-8") as f:
        f.write(content)

def accept_input():
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

    return tableName, items

def accept_input_for_selection():
    tableName = input("Enter database table name: ").strip()
    if not tableName:
        print("❌ Table name is required.")
        return

    value = input("Enter value : ").strip()
    if not value:
        print("❌ Items are required.")
        return 
    
    return tableName, value


def init():
    print("""
    1. Create New Project
    2. Create New CRUD Component
    3. Create New Selection Component 
    4. Create New Table Component
    5. Create New Add Component
    6. Create New Edit Component
    """)
    choice = input("Enter your choice: ")

    if choice == "1":
        create_project()
        exit(0) 
      
    
    if choice == "2":
        tableName, items = accept_input()
        project_root = Path.cwd()
        os.chdir(project_root)
        # check dir is exists or not, if not then create
        # if not os.path.exists(tableName):
        #     os.makedirs(tableName)
        
        # # change directory 
        # os.chdir(tableName)

        table = create_table(tableName=tableName, items=items)
        craete = create_form(tableName=tableName, items=items)
        edit = edit_form(tableName=tableName, items=items)
        print(project_root)

        save_file(f"{project_root}/{tableName.capitalize()}Manage.jsx", table)
        save_file(f"{project_root}/{tableName.capitalize()}Create.jsx", craete)
        save_file(f"{project_root}/{tableName.capitalize()}Edit.jsx", edit)

    elif choice == "3":
        tableName, value = accept_input_for_selection()
        project_root = Path.cwd()
        os.chdir(project_root)

        create_selection(tableName=tableName,  value=value)
    elif choice == "4":
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

    elif choice == "5":
        tableName, items = accept_input()
        project_root = Path.cwd()
        os.chdir(project_root)
        create_form(tableName=tableName, items=items)
    elif choice == "6":
        tableName, items = accept_input()
        project_root = Path.cwd()
        os.chdir(project_root)
        edit_form(tableName=tableName, items=items)
    else:
        print("Invalid choice. Please try again.")

    print("✅ Operation completed successfully.")
    print("Exiting the program.")

init() 