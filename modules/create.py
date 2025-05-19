
def create_form(pageName, tableName, items):
    component_name = ''.join(word.capitalize() for word in pageName.split("_"))
    # Cleaned items
    items = [item.strip() for item in items if item.strip()]

    # Build dynamic table rows
    forms = ""
    for item in items:
        forms += f"""
                <div id="phone_div" className='col-md-3'>
                    <p> Enter {item.capitalize()} </p>
                    <input type='number' name='{item}' id='{item}'
                        className="form-control"
                        placeholder='Enter {item.capitalize()}'
                    />
                </div>
         """ 

    component_template = f"""import React, {{ useEffect, useState, useRef }} from 'react'
import Cookies from 'universal-cookie'
import {{ useNavigate, useParams }} from 'react-router-dom'
import {{ APP_CONFIG, httpdService }} from '../../config'
import Loader from '../../widgets/Loader'
import WebPrinter from '../../widgets/WebPrinter'

const {component_name} = () => {{
    const formRef = useRef();
    const [isLoader, setisLoader] = useState(true) 
    const navigate = useNavigate() 
    const [showPrint, setShowPrint] = useState(false)
    const [printUrl, setPrintUrl] = useState("")

    const addData = (e) => {{
        e.preventDefault();
        setisLoader(true)
        var cookie = new Cookies()
        var formData = new FormData();

         const formElements = formRef.current.elements;
         for (let i = 0; i < formElements.length; i++) {{
            const field = formElements[i];
             if (field.name) {{
                formData.append(field.name, field.value);
             }}
         }} 
        formData.append('method', "PUT");
        formData.append('table', "{tableName}");



        httpdService(formData).then(res => {{
            let resp = res.data;
            setisLoader(false)
            if (resp.code == 200) {{
                alert(resp.msg)
                navigate("/{tableName}/view")
            }}
        }}).catch((err) => {{
            setisLoader(false)
            alert(err)
        }})
    }}

    const setPrint = (id) => {{
        setPrintUrl(APP_CONFIG.API + 'print/{tableName}?print=' + id )
        setShowPrint(true)
    }}
    return (
        <>
            {{isLoader && <Loader />}} 
            {{showPrint && printUrl && <WebPrinter url={{printUrl}} print={{() => setShowPrint(false)}} />}}
            <div className="bg-light text-dark m-0 col-md-12 shadow-sm p-3 rounded">
                <h2> {component_name} 
                    <button className="btn-sm btn-danger float-right mb-2" onClick={{() => navigate(-1)}}>BACK</button> 
                </h2>

                <form ref={{formRef}} onSubmit={{addData}} encType="multipart/form-data" className="form bg-light text-dark m-3 p-3 row shadow-sm col-md-12">

                    { forms }
                    <div className="col-md-12"> 
                        <input type="submit" name="ADD_NEW_DATA" id="ADD_NEW_DATA" className="btn btn-primary" onClick={{addData}} />
                    </div>
                </form>
            </div>
        </>
    )
}}

export default {component_name};
"""

    return component_template.replace("&#123;", "{").replace("&#125;", "}")