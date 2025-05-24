def create_table(tableName, items):
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

    conditions = ""

    tableBody = ""
    for item in items:
        tableBody += f"""
        <td>&#123;data.{item}&#125;</td> """

        conditions += f"""
          || item.{item}.toLowerCase().includes(value) """
        

    component_template = f"""import React, {{ useEffect, useState }} from 'react'
import Cookies from 'universal-cookie'
import {{ useNavigate, useParams, Link }} from 'react-router-dom'
import {{ APP_CONFIG, httpdService }} from '../../config'
import Loader from '../../widgets/Loader'
import WebPrinter from '../../widgets/WebPrinter'
import MetaTags from '../../widgets/MetaTags'

const {component_name} = () => {{
    const [isLoader, setisLoader] = useState(true)
    const [tableData, setTableData] = useState([]);
    const [filteredData, setFilteredData] = useState([])
    
    const navigate = useNavigate()
    const [orders, setOrders] = useState([])
    const [showPrint, setShowPrint] = useState(false)
    const [printUrl, setPrintUrl] = useState("")

    const {{status : status}} = useParams()

    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 50;

    // Calculate total pages
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);

    // Slice data for current page
    const paginatedData = filteredData.slice(
        (currentPage - 1) * itemsPerPage,
        currentPage * itemsPerPage
    );

    const fetchData = async () => {{
        setisLoader(true)
        const cookies = new Cookies()
        const uid = cookies.get('uid') 
        const rank = cookies.get('rank') 
        var formData = new FormData();
        formData.append('method', 'GET');
        formData.append('table', '{tableName}');
        if(status!="" && status!=undefined) {{
            formData.append('where', 'status="' + status+'"');
        }} else {{
            formData.append('where', ' 1 order by id desc'); // add your where condition
        }}
        

        httpdService(formData).then((response) => {{
            setisLoader(false)
            if (response.data.code !== 400 && response.data.code !== 403) {{
                setTableData(response.data);
                setFilteredData(response.data); 
            }} else {{
               // alert("No data found")
            }}
        }}).catch((error) => {{
            setisLoader(false)
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

    const updateData = (e, id, status) => {{
        e.preventDefault();
        setisLoader(true) 
        var formData = new FormData();
        formData.append('method', "UPDATE");
        formData.append('table', "{tableName}");
        formData.append('where', "id=" + id);
        formData.append('status', status);

        httpdService(formData).then(res => {{
            let resp = res.data;
            setisLoader(false)
            if (resp.code == 200) {{
                alert(resp.msg)
                fetchData()
            }}
        }}).catch((err) => {{
            setisLoader(false)
            alert(err)
        }})
    }}

    return (
        <>
            <MetaTags title="{component_name}" description="" keywords="" />
            {{isLoader && <Loader />}}
            {{showPrint && printUrl && <WebPrinter url={{printUrl}} print={{() => setShowPrint(false)}} />}}

            <div className="bg-light text-dark m-0 mt-3 main-body  col-md-12 shadow-sm p-3 rounded">
                <h2> {component_name}
                    <button className="btn-sm btn-danger float-right mb-2" onClick={{() => navigate(-1)}}>BACK</button>
                </h2>

                 <div className="row col-md-12 m-0 p-0 pb-3">
                    <div className="col-md-9"></div>
                    <div className="col-md-3">
                        <input type="text" className="form-control" placeholder="Search" onChange={{(e) => {{
                            const value = e.target.value.toLowerCase();
                            const filtered = tableData.filter(item => {{
                                return (
                                    item.id.toLowerCase().includes(value) 
                                    {conditions}
                                );
                            }});
                            setFilteredData(filtered);
                            setCurrentPage(1);
                        }}}} />
                    </div>
                </div>
                    <table className="table p-2">
                        <tr class="tr"> <th>SL</th> {tableHeaders} <th>Action </th></tr>

                        {{ paginatedData.length > 0 && paginatedData.map((data, index) => (
                            <tr key={{index}}>
                                <td> {{index + 1}} </td>
                                {tableBody}
                                <td>
                                <Link to={{`/{tableName}/edit/${{data.id}}`}}>
                                    <button className="btn-sm btn-primary text-uppercase text-white m-1"> Edit </button>
                                </Link> 
                                <button className="btn-sm btn-success text-uppercase text-white m-1" onClick={{(e) => updateData(e, data.id, "Active")}}> Approve </button>
                                <button className="btn-sm btn-danger text-uppercase text-white m-1" onClick={{(e) => updateData(e, data.id, "Rejected")}}> Reject </button>
                                <button className="btn-sm btn-danger text-uppercase text-white m-1" onClick={{(e) => updateData(e, data.id, "Deleted")}}> Delete </button>

                                </td>
                            </tr>
                        ))}}    
                    </table>

                    {{paginatedData.length == 0 && <div className="alert alert-danger">No data found</div>}}

                    <div className="navigate col-md-12 row m-0 mt-2 mb-2 justify-content-between mb-5 pb-5">
                    <button className='btn-sm btn-primary'
                        onClick={{() => setCurrentPage((prev) => Math.max(prev - 1, 1))}}
                        disabled={{currentPage === 1}}
                    >
                        Prev
                    </button>
                    <span>Page {{currentPage}} of {{totalPages}}</span>
                    <button className='btn-sm btn-primary'
                        onClick={{() => setCurrentPage((prev) => Math.min(prev + 1, totalPages))}}
                        disabled={{currentPage === totalPages}}
                    >
                        Next
                    </button>
                </div>
                
            </div>
        </>
    )
}}

export default {component_name};
"""

    return component_template.replace("&#123;", "{").replace("&#125;", "}")




# ///////////////////////////////////////////////////////////////////////////////////////////////////////
def create_table2(pageName, tableName, items): 
    component_name = pageName
    # Cleaned items
    items = [item.strip() for item in items if item.strip()]

    # Build dynamic table rows
    tableHeaders = ""
    for item in items:
        tableHeaders += f"""
        <th>{item.capitalize()}</th> """

    conditions = ""

    tableBody = ""
    for item in items:
        tableBody += f"""
        <td>&#123;data.{item}&#125;</td> """

        conditions += f"""
          || item.{item}.toLowerCase().includes(value) """
        

    component_template = f"""import React, {{ useEffect, useState }} from 'react'
import Cookies from 'universal-cookie'
import {{ useNavigate, useParams, Link }} from 'react-router-dom'
import {{ APP_CONFIG, httpdService }} from '../../config'
import Loader from '../../widgets/Loader'
import WebPrinter from '../../widgets/WebPrinter'
import MetaTags from '../../widgets/MetaTags'

const {component_name} = () => {{
    const [isLoader, setisLoader] = useState(true)
    const [tableData, setTableData] = useState([]);
    const [filteredData, setFilteredData] = useState([])
    
    const navigate = useNavigate()
    const [orders, setOrders] = useState([])
    const [showPrint, setShowPrint] = useState(false)
    const [printUrl, setPrintUrl] = useState("")

    const {{status : status}} = useParams()
    const {{id : id}} = useParams()

    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 50;
 
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
 
    const paginatedData = filteredData.slice(
        (currentPage - 1) * itemsPerPage,
        currentPage * itemsPerPage
    );

    const fetchData = async () => {{
        setisLoader(true)
        const cookies = new Cookies()
        const uid = cookies.get('uid') 
        const rank = cookies.get('rank') 
        var formData = new FormData();
        formData.append('method', 'GET');
        formData.append('table', '{tableName}');
        if(status!="" && status!=undefined) {{
           formData.append('where', 'status="' + status+'"');
        }} else {{
            formData.append('where', ' 1 order by id desc'); // add your where condition
        }}
        

        httpdService(formData).then((response) => {{
            setisLoader(false)
            if (response.data.code !== 400 && response.data.code !== 403) {{
                setTableData(response.data);
                setFilteredData(response.data); 
            }} else {{
               // alert("No data found")
            }}
        }}).catch((error) => {{
            setisLoader(false)
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

    const updateData = (e, id, status) => {{
        e.preventDefault();
        setisLoader(true) 
        var formData = new FormData();
        formData.append('method', "UPDATE");
        formData.append('table', "{tableName}");
        formData.append('where', "id=" + id);
        formData.append('status', status);

        httpdService(formData).then(res => {{
            let resp = res.data;
            setisLoader(false)
            if (resp.code == 200) {{
                alert(resp.msg)
                fetchData()
            }}
        }}).catch((err) => {{
            setisLoader(false)
            alert(err)
        }})
    }}

    return (
        <>
            <MetaTags title="{component_name}" description="" keywords="" />
            {{isLoader && <Loader />}}
            {{showPrint && printUrl && <WebPrinter url={{printUrl}} print={{() => setShowPrint(false)}} />}}

            <div className="bg-light text-dark m-0 mt-3 main-body  col-md-12 shadow-sm p-3 rounded">
                <h2> {component_name}
                    <button className="btn-sm btn-danger float-right mb-2" onClick={{() => navigate(-1)}}>BACK</button>
                </h2>

                 <div className="row col-md-12 m-0 p-0 pb-3">
                    <div className="col-md-9"></div>
                    <div className="col-md-3">
                        <input type="text" className="form-control" placeholder="Search" onChange={{(e) => {{
                            const value = e.target.value.toLowerCase();
                            const filtered = tableData.filter(item => {{
                                return (
                                    item.id.toLowerCase().includes(value) 
                                    {conditions}
                                );
                            }});
                            setFilteredData(filtered);
                            setCurrentPage(1);
                        }}}} />
                    </div>
                </div>
                    <table className="table p-2">
                        <thead><tr class="tr"> <th>SL</th> {tableHeaders} <th>Action </th></tr> </thead>

                        <tbody>
                        {{ paginatedData.length > 0 && paginatedData.map((data, index) => (
                            <tr key={{index}}>
                                <td> {{index + 1}} </td>
                                {tableBody}
                                <td>
                                <Link to={{`/{tableName}/edit/${{data.id}}`}}>
                                    <button className="btn-sm btn-primary text-uppercase text-white m-1"> Edit </button>
                                </Link> 
                                <button className="btn-sm btn-success text-uppercase text-white m-1" onClick={{(e) => updateData(e, data.id, "Active")}}> Approve </button>
                                <button className="btn-sm btn-danger text-uppercase text-white m-1" onClick={{(e) => updateData(e, data.id, "Rejected")}}> Reject </button>
                                <button className="btn-sm btn-danger text-uppercase text-white m-1" onClick={{(e) => updateData(e, data.id, "Deleted")}}> Delete </button>

                                </td>
                            </tr>
                        ))}}    
                        </tbody>
                    </table>

                    {{paginatedData.length == 0 && <div className="alert alert-danger">No data found</div>}}

                    <div className="navigate col-md-12 row m-0 mt-2 mb-2 justify-content-between mb-5 pb-5">
                    <button className='btn-sm btn-primary'
                        onClick={{() => setCurrentPage((prev) => Math.max(prev - 1, 1))}}
                        disabled={{currentPage === 1}}
                    >
                        Prev
                    </button>
                    <span>Page {{currentPage}} of {{totalPages}}</span>
                    <button className='btn-sm btn-primary'
                        onClick={{() => setCurrentPage((prev) => Math.min(prev + 1, totalPages))}}
                        disabled={{currentPage === totalPages}}
                    >
                        Next
                    </button>
                </div>
                
            </div>
        </>
    )
}}

export default {component_name};
"""

    return component_template.replace("&#123;", "{").replace("&#125;", "}")
# ///////////////////////////////////////////////////////////////////////////////////////////////////////