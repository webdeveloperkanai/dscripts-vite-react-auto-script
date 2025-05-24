import React from 'react'
import MetaTags from '../widgets/MetaTags'

const P404 = () => {
    return (
        <>
            <MetaTags title={"404 Page Not Found"} /> 
            <div className="mt-5 pt-5 row col-md-12"></div>
            <div className='col-md-12 m-0 alert alert-danger'>
                <div className='container p-5 mt-5 d-flex justify-content-center align-items-center flex-column'>
                    <h1 className='mt-4'>404 PAGE NOT FOUND</h1> <br />
                    <button className='btn-sm btn-primary mb-5' onClick={() => { window.history.back() }} >BACK </button>
                </div>
            </div>
        </>
    )
}

export default P404
