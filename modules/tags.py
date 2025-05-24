def get_meta_tags(): 
    dt = """
import React from 'react'
import { Helmet } from 'react-helmet'
import { APP_CONFIG } from '../config'

const MetaTags = ({ title: title, description: description, keywords: keywords, dataTable: dataTable }) => {
    const canionical = window.location.href.split('?')
    return (
        <>
            <Helmet>
                <title>{title} - {APP_CONFIG.NAME}</title>
                <meta name="description" content={description} />
                <meta name="keywords" content={keywords} />
                <link rel="canonical" href={canionical[0]} /> 
            </Helmet>
        </>
    )
}

export default MetaTags

"""
    return dt 


def get_loader_tag():
    dt = """
import React from 'react'
const Loader = () => {
  return (
    <div className="loader-div">
        <div className='loader'> 
        </div>
    </div>
  )
} 
export default Loader


"""
    return dt


def get_printer_tag():
    dt = """
const WebPrinter = ({ url: url, print: printComplete }) => {
    return (
        <div className='web-printer'>
            <iframe id='iframe' src={url} frameborder="0" style={{ width: '100%', height: '95%' }}></iframe>
            <center><button className='btn-sm btn-danger btn-sm' onClick={() => { printComplete(false) }}>CLOSE</button></center>
        </div>
    )
}

export default WebPrinter


"""
    return dt