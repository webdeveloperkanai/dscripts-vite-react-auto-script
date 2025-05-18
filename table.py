import os
from pathlib import Path
from modules import *
from modules.create import create_form
from modules.edit import edit_form

def generate_component(tableName, items):
    # Capitalize component name
    # component_name = ''.join(word.capitalize() for word in page_name.split("_"))
    component_name = f"{tableName.capitalize()}Manage"
    # Cleaned items
    items = [item.strip() for item in items if item.strip()]

    # Build dynamic table rows
    tableHeaders = ""
    for item in items:
        tableHeaders += f"""
        <th>{item.capitalize()}</th> """

    tableBody = ""
    for item in items:
        tableBody += f"""
        <td>&#123;data.{item}&#125;</td> """
        

    component_template = f"""import React, {{ useEffect, useState }} from 'react'
import Cookies from 'universal-cookie'
import {{ useNavigate, useParams, Link }} from 'react-router-dom'
import {{ APP_CONFIG, httpdService }} from '../../config'
import Loader from '../../widgets/Loader'
import WebPrinter from '../../widgets/WebPrinter'

const {component_name} = () => {{
    const [isLoader, setisLoader] = useState(true)
    const [tableData, setTableData] = useState([]);
    const [filteredData, setFilteredData] = useState([])
    const {{ id }} = useParams()
    const navigate = useNavigate()
    const [orders, setOrders] = useState([])
    const [showPrint, setShowPrint] = useState(false)
    const [printUrl, setPrintUrl] = useState("")

    const {{status : status}} = useParams()

    const fetchData = async () => {{
        setisLoader(true)
        const cookies = new Cookies()
        const uid = cookies.get('uid') 
        const rank = cookies.get('rank') 
        var formData = new FormData();
        formData.append('uid', uid); 
        formData.append('rank', rank); 
        formData.append('method', 'GET');
        formData.append('table', '{tableName}');
        if(status!="" && status!=undefined) {{
            formData.append('where', 'status=' + status);
        }} else {{
            formData.append('where', ' 1 order by id desc'); // add your where condition
        }}
        

        httpdService(formData).then((response) => {{
            setisLoader(false)
            if (response.data.code !== 400) {{
                setTableData(response.data);
                setFilteredData(response.data); 
            }} else {{
               // alert("No data found")
            }}
        }}).catch((error) => {{
            alert(error)
        }});
    }}

    useEffect(() => {{
        fetchData();
    }}, [status, window.location.href]);

    const setPrint = (id) => {{
        setPrintUrl(APP_CONFIG.API + 'print/medicine-invoice?invoice=' + id )
        setShowPrint(true)
    }}
    return (
        <>
            {{isLoader && <Loader />}}
            {{showPrint && printUrl && <WebPrinter url={{printUrl}} print={{() => setShowPrint(false)}} />}}

            <div className="bg-light text-dark m-0 col-md-12 shadow-sm p-3 rounded">
                <h2> {component_name} Details
                    <button className="btn-sm btn-danger float-right mb-2" onClick={{() => navigate(-1)}}>BACK</button>
                    {{filteredData.length > 0 &&
                        <button className="btn-sm btn-success float-right mb-2 mr-2"
                            onClick={{() => {{ setPrint(filteredData[0].invoice_id) }} }}>
                            PRINT
                        </button>
                    }}
                </h2>

                {{filteredData.length > 0 &&
                    <table className="table p-2">
                        <tr class="tr"> <th>SL</th> {tableHeaders} <th>Action </th></tr>

                        {{ filteredData.map((data, index) => (
                            <tr key={{index}}>
                                <td> {{index + 1}} </td>
                                {tableBody}
                                <td>
                                <Link to={{`/{tableName}/edit/${{data.id}}`}}>
                                    <button className="btn-sm btn-primary text-uppercase text-white m-1"> Edit </button>
                                </Link> 
                                <button className="btn-sm btn-danger text-uppercase text-white m-1"> Delete </button>

                                </td>
                            </tr>
                        ))}}
                    </table>
                }}
            </div>
        </>
    )
}}

export default {component_name};
"""

    return component_template.replace("&#123;", "{").replace("&#125;", "}")

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
