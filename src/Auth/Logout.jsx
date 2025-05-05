import React, { useEffect } from 'react'
import Cookies from 'universal-cookie' 
import Loader from '../widgets/Loader'


const Logout = () => {

    useEffect(() => {
        var cookie = new Cookies()
        cookie.remove("uid")
        cookie.remove("oid")
        cookie.remove("oname")
        cookie.remove("region")
        cookie.remove("state")
        cookie.remove("district")
        cookie.remove("pincode")
        cookie.remove("circle")
        cookie.remove("theme")
        cookie.remove("otype")
        cookie.remove("orank")
        cookie.remove("name")
        cookie.remove("age")
        cookie.remove("phone")
        document.cookie = "uid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"

        setTimeout(() => {
            window.location.href = "/"
        }, 3000);
    }, [window.location.href])
    return (
        <div>
            <Loader />
        </div>
    )
}

export default Logout
