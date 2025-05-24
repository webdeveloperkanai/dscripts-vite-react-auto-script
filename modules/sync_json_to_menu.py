import json
from pathlib import Path
from collections import defaultdict

def sync_json_to_menu(json_file_path, menu_file_path):
    with open(json_file_path, 'r', encoding='utf-8') as f:
        data = json.load(f)

    routes = data.get("app", {}).get("routes", [])
    grouped = defaultdict(list)

    for route in routes:
        path = route.get("path", "")
        title = route.get("title", "").strip()
        if not path or not title:
            continue

        segments = path.strip("/").split("/")
        if not segments:
            continue

        top = "/" + segments[0]  # e.g. "/users"

        if path == top:
            grouped[top].insert(0, {"name": title, "path": path})  # insert as root
        else:
            grouped[top].append({
                "name": title.replace(segments[0].capitalize(), "").strip() or "View",
                "path": path
            })

    menu_structure = []

    default_menu = [
        {
            "name": "Dashboard",
            "path": "/",
            "children": []
        },
        {
            "name": "Files",
            "path": "#",
            "children": [
                { "name": "App Settings", "path": "#" },
                { "name": "Help & Support", "path": "#" },
                { "name": "Download Backup", "path": "#" },
                { "name": "Logout Current Profile", "path": "/logout" }
            ]
        }
    ]

    menu_structure.extend(default_menu)
    
    for top_path, items in grouped.items():
        if not items:
            continue

        parent = items[0]
        children = items[1:]

        menu_structure.append({
            "name": parent["name"],
            "path": "#",
            "children": children
        })

    # Build JS output
    lines = ["const menu = ["]
    for item in menu_structure:
        lines.append("    {")
        lines.append(f'        name: "{item["name"]}",')
        lines.append(f'        path: "{item["path"]}",')
        lines.append("        children: [")
        for child in item["children"]:
            lines.append(f'            {{ name: "{child["name"]} {item["name"]}", path: "{child["path"]}" }},')
        lines.append("        ]")
        lines.append("    },")
    lines.append("];\n")
    lines.append("export default menu;\n")

    with open(menu_file_path, "w", encoding="utf-8") as f:
        f.write("\n".join(lines))

    print(f"✅ Synced menu to {menu_file_path}")

# # Usage
# sync_json_to_menu(".dscripts.txt", "menu.jsx")
