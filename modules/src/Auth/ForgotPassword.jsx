import React, { useState } from 'react'
import { validatePhone } from '../config'
import Cookies from 'universal-cookie'
import { Link } from 'react-router-dom'
import httpdService from '../services/httpdService'
import Loader from '../widgets/Loader'

const ResetPassword = () => {
    const [phone, setPhone] = useState()
    const [password, setPassword] = useState()
    const [isLoading, setIsLoading] = useState(false)

    const login = (e) => {
        e.preventDefault()
        var formData = new FormData();
        if (phone === undefined || phone === "" || phone === null || `` + phone.length != 10) {
            return;
        }
        setIsLoading(true)
        formData.append("phone", phone)
        formData.append("GET_PASSWORD", "true");

        httpdService(formData).then((res) => {
            setIsLoading(false)
            alert(res.data.msg)
        }).catch((err) => {
            setIsLoading(false)
            alert(err)
        })
    }

    return (
        <>
            {isLoading && <Loader />}
            <div className="row col-md-12 p-5 login-div">
                <div className="col-md-4"></div>
                <div className='bg-light col-md-4 p-5 shadow-sm rounded form'>
                    <center><h2>GET NEW PASSWORD</h2></center>
                    <p>Enter phone no.</p>
                    <input type="tel" name="" id=""
                        className='form-control'
                        placeholder='Enter phone no.'
                        onChange={(e) => { validatePhone(e.target.value); setPhone(e.target.value) }}
                        autoComplete='off'
                        autoSave='off'
                    /><br />

                    <Link className='text-primary float-right float-end' to="/login">Recovered? Login</Link>
                    <br />
                    <center><button className='btn btn-primary w-50' onClick={login}>GET NEW PASSWORD</button></center>
                </div>

            </div>

        </>
    )
}

export default ResetPassword
