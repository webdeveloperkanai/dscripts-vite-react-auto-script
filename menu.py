import os
from modules import *
from pathlib import Path
from modules.table_by_status import table_by_status
from modules.create import create_form
from modules.edit import edit_form
from modules.selection import create_selection
from modules.table import create_table, create_table2
from modules.createProject import create_project
from modules.configure import NestedJsonFile
from modules.tags import get_meta_tags, get_loader_tag, get_printer_tag


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
    # if not os.path.exists(tableName):
    #     os.makedirs(tableName)
    
    # # change directory 
    # os.chdir(tableName)

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

def check_project_root():
    if Path("package.json").is_file():
        return True
    return False

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
        
        project_root = Path.cwd() 
        os.chdir(project_root)
        current_folder = os.path.basename(os.getcwd())
        root = check_project_root()
        if not root:
            print("❌ You are not in project root directory. Please run this script from project root directory.")
            return
        

        # Checking default widget files 
        if Path("widgets").is_dir():
            if not os.path.exists("widgets/MetaTags.jsx"):
                meta = get_meta_tags()
                save_file(f"{project_root}/src/widgets/MetaTags.jsx", meta)

            if not os.path.exists("widgets/Loader.jsx"):
                meta = get_loader_tag()
                save_file(f"{project_root}/src/widgets/Loader.jsx", meta)

            if not os.path.exists("widgets/WebPrinter.jsx"):
                printer = get_printer_tag()
                save_file(f"{project_root}/src/widgets/WebPrinter.jsx", printer)
        else:
            os.mkdir("widgets")       
            if not os.path.exists("widgets/MetaTags.jsx"):
                meta = get_meta_tags()
                save_file(f"{project_root}/src/widgets/MetaTags.jsx", meta)

            if not os.path.exists("widgets/Loader.jsx"):
                meta = get_loader_tag()
                save_file(f"{project_root}/src/widgets/Loader.jsx", meta)

            if not os.path.exists("widgets/WebPrinter.jsx"):
                printer = get_printer_tag()
                save_file(f"{project_root}/src/widgets/WebPrinter.jsx", printer)
        # Checking default widget files  done 

    
        tableName, items = accept_input()
        configFile = NestedJsonFile("dsc.json")
        
        configFile.add_or_update(["app", "name"], current_folder.replace("-", "_").replace(" ", "_"))        
        configFile.add_or_update(["app", "version"], "1.0.0")
        configFile.add_or_update(["app", "config", "theme"], "light")  

        # manage page
        configFile.append_to_list(["app", "routes"], { "path": f"/{tableName}", "title": tableName.capitalize(), "element": f"<{tableName.capitalize()}Manage />", "location": f"/src/views/{tableName.lower()}/{tableName.capitalize()}Manage.jsx" })
       
        # add page 
        configFile.append_to_list(["app", "routes"], { "path": f"/{tableName}/add", "title": f"{tableName.capitalize()} Add", "element": f"<{tableName.capitalize()}Add />", "location": f"/src/views/{tableName.lower()}/{tableName.capitalize()}Add.jsx" })
        
        # # edit page 
        configFile.append_to_list(["app", "routes"], { "path": f"/{tableName}/edit/:id", "title": f"{tableName.capitalize()} Update", "element": f"<{tableName.capitalize()}Edit />", "location": f"/src/views/{tableName.lower()}/{tableName.capitalize()}Edit.jsx" })

        # # by status 
        configFile.append_to_list(["app", "routes"], { "path": f"/{tableName}/status/:status", "title": f"{tableName.capitalize()} By Status", "element": f"<{tableName.capitalize()}ByStatus />", "location": f"/src/views/{tableName.lower()}/{tableName.capitalize()}ByStatus.jsx" })

        # # by date 
        configFile.append_to_list(["app", "routes"], { "path": f"/{tableName}/date/:date", "title": f"{tableName.capitalize()} By Date", "element": f"<{tableName.capitalize()}ByDate />", "location": f"/src/views/{tableName.lower()}/{tableName.capitalize()}ByDate.jsx" })

        # # by id details  
        configFile.append_to_list(["app", "routes"], { "path": f"/{tableName}/details/:id", "title": f"{tableName.capitalize()} Details ", "element": f"<{tableName.capitalize()}Details />", "location": f"/src/views/{tableName.lower()}/{tableName.capitalize()}Details.jsx" })

        # # by dynamic   
        configFile.append_to_list(["app", "routes"], { "path": f"/{tableName}/dyn/:any/:valu", "title": f"{tableName.capitalize()} Dynamic ", "element": f"<{tableName.capitalize()}Dynamic />", "location": f"/src/views/{tableName.lower()}/{tableName.capitalize()}Dynamic.jsx" })  


        # check dir is exists or not, if not then create
        os.chdir("src")
        

        if not os.path.exists("views"):
            os.makedirs("views") 
        os.chdir("views")


        if not os.path.exists(tableName.lower()):
            os.makedirs(tableName.lower())
        
        # # change directory 
        os.chdir(tableName.lower())

        table = create_table(tableName=tableName, items=items)
        tableByStatus = table_by_status(tableName=tableName, items=items)
        craete = create_form(tableName=tableName, items=items)
        edit = edit_form(tableName=tableName, items=items)

        print(project_root)

        save_file(f"{project_root}/src/views/{tableName.lower()}/{tableName.capitalize()}Manage.jsx", table)
        print(f"Created manage page at src/views/{tableName.lower()}/{tableName.capitalize()}Manage.jsx")

        save_file(f"{project_root}/src/views/{tableName.lower()}/{tableName.capitalize()}ByStatus.jsx", tableByStatus)
        print(f"Created by status page at src/views/{tableName.lower()}/{tableName.capitalize()}ByStatus.jsx")

        save_file(f"{project_root}/src/views/{tableName.lower()}/{tableName.capitalize()}ByDate.jsx", tableByStatus)
        print(f"Created by date page at src/views/{tableName.lower()}/{tableName.capitalize()}ByDate.jsx")

        save_file(f"{project_root}/src/views/{tableName.lower()}/{tableName.capitalize()}ByDyn.jsx", tableByStatus)
        print(f"Created by date page at src/views/{tableName.lower()}/{tableName.capitalize()}ByDyn.jsx")

        save_file(f"{project_root}/src/views/{tableName.lower()}/{tableName.capitalize()}Details.jsx", tableByStatus)
        print(f"Created by date page at src/views/{tableName.lower()}/{tableName.capitalize()}Details.jsx")

        save_file(f"{project_root}/src/views/{tableName.lower()}/{tableName.capitalize()}Add.jsx", craete)
        print(f"Created add page at src/views/{tableName.lower()}/{tableName.capitalize()}Add.jsx")

        save_file(f"{project_root}/src/views/{tableName.lower()}/{tableName.capitalize()}Edit.jsx", edit)
        print(f"Created edit page at src/views/{tableName.lower()}/{tableName.capitalize()}Edit.jsx")
 

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