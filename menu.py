import os
from modules import *
from pathlib import Path 
from modules.create import create_form
from modules.edit import edit_form 
from modules.createProject import create_project 
from modules.func_crud import func_crud
from modules.func_newselection import func_newselection
from modules.func_newtable import func_newtable
from modules.func_addcomponent import func_addcomponent
from modules.func_editcomponent import func_editcomponent

   
def init(): 
    print("""
    1. Create New Project
    2. Create New CRUD Component
    3. Create New Selection Component 
    4. Create New Table Component
    5. Create New Add Component
    6. Create New Edit Component
    7. Sync Menu
    x. Exit Program
    """)
    choice = input("Enter your choice: ")

    if choice == "1": # create new project
        create_project()
        exit(0)  
    
    if choice == "2": # Create New CRUD Component
         
        func_crud()
        # sync_json_to_menu("dsc.json", "src/utils/menu.jsx")
        # print("✅ Done syncing json to menu.jsx")

    elif choice == "3": # Create New Selection Component 
        func_newselection()
    elif choice == "4": # Create New Table Component
        func_newtable()
    elif choice == "5": # Create New Add Component
        func_addcomponent()
    elif choice == "6": # Create New Edit Component
        func_editcomponent()
    elif choice == "x": # Create New Edit Component
        exit(0)
    else:
        print("Invalid choice. Please try again.")

    print("✅ Operation completed successfully.")
    print("Exiting the program.")


# while True:
init() 