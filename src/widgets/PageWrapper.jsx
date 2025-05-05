import React, { useEffect, useState } from 'react'
import MenuBar from './MenuBar'
import Cookies from 'universal-cookie'
import FooterSupport from './FooterSupport'

const P2 = ({ elm }) => {
    const [user, setUser] = useState(null)
    const [name, setName] = useState(null)
    const [uid, setUid] = useState(null)

    useEffect(() => {
        var cookie = new Cookies();
        var uid = cookie.get("uid");
        setName(localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")).name : '');
        setUid(uid)
        setUser(localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")) : null)
    }, [])

    return (
        <>
            <MenuBar />
            <main className='col-md-12 row m-0 bg-light p-0'>
                {elm}
            </main>
            {window.innerWidth > 600 ? <FooterSupport /> : null}
        </>
    )
}

export default P2;
