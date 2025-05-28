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