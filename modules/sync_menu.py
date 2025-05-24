import json
from pathlib import Path

def sync_json_to_pages(json_file_path, pages_file_path):
    # Read JSON file
    with open(json_file_path, 'r', encoding='utf-8') as f:
        data = json.load(f)

    routes = data.get("app", {}).get("routes", [])

    import_lines = []
    route_lines = []

    for route in routes:
        location = route.get("location", "")
        element = route.get("element", "")
        path = route.get("path", "")
        title = route.get("title", "")

        if not location or not element:
            continue

        # Get import path and component name
        component_path = location.replace("/src", ".").replace(".jsx", "")
        component_name = element.strip("<> /")

        import_lines.append(f'import {component_name} from "{component_path}";')
        route_lines.append(f'  {{ path: "{path}", title: "{title}", element: <{component_name} /> }},')

    # Deduplicate import lines
    import_lines = sorted(set(import_lines))

    # Compose file content
    pages_jsx_content = "\n".join(import_lines) + "\n\n"
    pages_jsx_content += "const Pages = [\n" + "\n".join(route_lines) + "\n];\n\n"
    pages_jsx_content += "export default Pages;\n"

    # Write to pages.jsx
    with open(pages_file_path, 'w', encoding='utf-8') as f:
        f.write(pages_jsx_content)

    print(f"✅ Synced {len(route_lines)} routes to {pages_file_path}")


# Usage
 # or ".dscripts.json"
