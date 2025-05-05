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
                {dataTable && <link rel="stylesheet" href="//cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css" />}
            </Helmet>
        </>
    )
}

export default MetaTags
