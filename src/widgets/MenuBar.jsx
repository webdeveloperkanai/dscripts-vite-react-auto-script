import React, { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { APP_CONFIG } from '../config'
import adminMenu from '../utils/AdminMenu'

const MenuBar = () => {
    const [show, setShow] = useState(false);
    useEffect(() => {
        if (window.innerWidth > 600) { setShow(true) } else {
            window.scrollTo(0, 0)
            setShow(false)
        }
    }, [window.location.href])
    return (
        <>
            <nav className="navbar navbar-expand-lg navbar-light">
                <Link className="navbar-brand" to={'/'}> <b> {APP_CONFIG.NAME} </b> </Link>
                <button className="navbar-toggler" type="button" onClick={() => { setShow(!show) }}>
                    <span className="navbar-toggler-icon" />
                </button>

                { show && <>
                    <div className={`collapse navbar-collapse show`} id="navbarSupportedContent">
                        <ul className="navbar-nav mr-auto">
                            {adminMenu.map((menu, index) => <>
                                <li className={menu.children.length > 0 ? "nav-item dropdown" : "nav-item"}>

                                    {menu.children.length > 0 ? <>
                                        <Link className={menu.children.length > 0 ? "nav-link dropdown-toggle" : "nav-link"} to={menu.path} role="button" data-toggle="dropdown" aria-expanded="false">
                                            {menu.name}
                                        </Link>
                                    </> :

                                        <>
                                            <Link className={menu.children.length > 0 ? "nav-link dropdown-toggle" : "nav-link"} to={menu.path} >
                                                {menu.name}
                                            </Link>
                                        </>}

                                    {menu.children.length > 0 && <>
                                        <div className="dropdown-menu">
                                            {menu.children.map((subMenu, index) => <>
                                                {subMenu.name === "hr" ? <div className="dropdown-divider"></div> :
                                                    <Link className="dropdown-item" to={subMenu.path} >{subMenu.name}</Link>} </>
                                            )}
                                        </div>
                                    </>}
                                </li>
                            </>)}

                        </ul>
                    </div>
                </>}
            </nav>

        </>
    )
}

export default MenuBar