
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