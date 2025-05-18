import os
import json
import shutil
import subprocess
from pathlib import Path

def run_command(command, cwd=None):
    result = subprocess.run(command, shell=True, cwd=cwd)
    if result.returncode != 0:
        print(f"❌ Error running command: {command}")
        exit(result.returncode)


def update_package_json(project_path):
    package_json_path = project_path / "package.json"
    if package_json_path.exists():
        print("🔧 Updating package.json...")

        # Read the existing package.json
        with open(package_json_path, "r", encoding="utf-8") as f:
            package_data = json.load(f)

        # Modify the dev script
        package_data["scripts"]["dev"] = 'concurrently "php -S 0.0.0.0:5003 -t php" "vite"'

        # Write the modified content back to package.json
        with open(package_json_path, "w", encoding="utf-8") as f:
            json.dump(package_data, f, indent=2)

        print("✅ package.json updated with PHP server and Vite server.")


def main():
    script_dir = Path(__file__).resolve().parent       # Folder containing this script
    project_root = Path.cwd()                          # Folder where the script is run from

    project_name = input("Enter your project name: ").strip()
    if not project_name:
        print("Project name is required.")
        return

    project_path = project_root / project_name

    print(f"\n📦 Creating Vite project '{project_name}' in {project_root}...")
    run_command(f'npm create vite@latest {project_name} -- --template react', cwd=project_root)

    # Remove default src
    src_path = project_path / "src"
    if src_path.exists():
        print("🧹 Removing default src folder...")
        shutil.rmtree(src_path)

    # Copy custom src
    src_source = script_dir / "src"
    if src_source.exists():
        print("📁 Copying custom src folder from script directory...")
        shutil.copytree(src_source, src_path)
    else:
        print(f"⚠️  Warning: {src_source} not found. Skipping src copy.")

    # Copy php folder
    php_source = script_dir / "php"
    php_dest = project_path / "php"
    if php_source.exists():
        print("📁 Copying php folder from script directory...")
        shutil.copytree(php_source, php_dest)
    else:
        print(f"⚠️  Warning: {php_source} not found. Skipping php copy.")

    # Replace index.html
    index_html = script_dir / "index.html"
    if index_html.exists():
        print("📄 Replacing index.html...")
        shutil.copyfile(index_html, project_path / "index.html")
    else:
        print(f"⚠️  Warning: {index_html} not found. Skipping index.html.")

    # Replace public folder
    public_source = script_dir / "public"
    public_path = project_path / "public"
    if public_source.exists():
        if public_path.exists():
            print("🧹 Removing default public folder...")
            shutil.rmtree(public_path)
        print("📁 Copying custom public folder from script directory...")
        shutil.copytree(public_source, public_path)
    else:
        print(f"⚠️  Warning: {public_source} not found. Skipping public folder.")

    # Install base dependencies
    print("📦 Installing base dependencies...")
    run_command("npm install", cwd=project_path)

    # Install additional packages
    print("📦 Installing additional packages...")
    run_command("npm install axios react-helmet react-router-dom universal-cookie concurrently", cwd=project_path)

    config_content = f"""<?php
        require_once __DIR__ . "/devsecit/index.php";
        session_start();
        error_reporting(1);

        header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
 
        $DB_USER = "root";
        $DB_PASSWORD = "";
        $DB_NAME = "{project_name}";

        $con = mysqli_connect("localhost", "$DB_USER", "$DB_PASSWORD");
        $con->set_charset("utf8mb4");  

        """

    config_content+= """if (!$con) {
            die(json_encode(["status" => "Database is not connected", "code" => 400]));
        } 
        
        $db_check = mysqli_select_db($con, "$DB_NAME");

        if (!$db_check) { 
            DevSecIt\DB\CREATE_DATABASE("$DB_NAME");
        }
        function sanitize($con, $data) {
            return mysqli_real_escape_string($con, $data);
        }
        extract($_REQUEST); 

        """  

    output_path = os.path.join(project_path, "php/config.php")
    with open(output_path, "w", encoding="utf-8") as f:
        f.write(config_content)
    
    print(f"✅ config.php created at {output_path}")

    viteConfig = """
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:5003',   // your PHP server
        changeOrigin: true,
        rewrite: path => path.replace(/^\/api/, '')
      }
    }
  }
})


"""
    vitePath = os.path.join(project_path, "vite.config.js")
    with open(vitePath, "w", encoding="utf-8") as f:
        f.write(viteConfig)

    print(f"✅ vite.config.js created at {vitePath}")


    print("\n✅ Setup complete!")
    print(f"Your React project is ready in: {project_path}")

    update_package_json(project_path)
    # Start dev server
    print("🚀 Starting development server...")
    run_command("start http://localhost:5173", cwd=project_path)
    run_command("npm run dev", cwd=project_path)

if __name__ == "__main__":
    main()
