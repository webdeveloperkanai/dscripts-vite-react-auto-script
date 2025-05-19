import React from 'react'
import { getCurrentYear } from '../config'

const FooterSupport = () => {
    return (
        <div>
            <footer className='p-1 bg-dark position-fixed w-100 bottom-0 row m-0 text-light'>
                <div className="col-md-4">
                    {getCurrentYear()} &copy; all rights reserved!
                </div>
                <div className="col-md-1"></div>
                <div className="col-md-4">Designed and Developed by <b>DEV SEC IT</b></div>
                <div className="col-md-3 footer-links">
                    <a href="">Help & Support</a>
                    <a href="">FAQ</a>
                    <a href="">Tutorials</a>
                    <a href="https://devsecit.com/solutions/">Services</a>
                </div>
            </footer>
        </div>
    )
}

export default FooterSupport
