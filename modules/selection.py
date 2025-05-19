
def create_selection(tableName, value):
    component_name = tableName.capitalize() 
    # Cleaned items
     

    component_template = f"""import React, {{ useEffect, useState, useRef }} from 'react'
import Cookies from 'universal-cookie'
import {{ useNavigate, useParams }} from 'react-router-dom'
import {{ APP_CONFIG, httpdService }} from '../../config'
import Loader from '../../widgets/Loader'
import WebPrinter from '../../widgets/WebPrinter'

const {component_name} = (className, title, value, onChange) => {{
    const formRef = useRef();
    const [isLoader, setisLoader] = useState(false)  
    const [data, setData] = useState([])

    const getData = (e) => {{
        e.preventDefault();
        setisLoader(true)
        var cookie = new Cookies()
        var formData = new FormData(); 
        
        formData.append('method', "GET");
        formData.append('table', "{tableName}");
        formData.append('where', " 1 order by id asc");
 
        httpdService(formData).then(res => {{
            let resp = res.data;
            setisLoader(false)
            if (resp.code !== 400) {{
                setData(resp)
            }}
        }}).catch((err) => {{
            setisLoader(false)
            alert(err)
        }})
    }}

     
    return (
        <>
            <div className={{className}} > 
                <p> Select {{ title }} </p>   
                <select name="select" id="select" className="form-control" onChange={{onChange}} >

                    <option value="" selected disabled > </option>
                    {{data.map((item, index) => (
                        <option key={{index}} value={{item.{value}}} >{{item.{value}}} </option>
                    ))}}

                </select>
            </div>  
        </>
    )
}}

export default {component_name};
"""

    return component_template.replace("&#123;", "{").replace("&#125;", "}")