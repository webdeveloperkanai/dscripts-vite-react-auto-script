import React, { useState } from 'react'
import { validatePhone } from '../config'
import Cookies from 'universal-cookie'
import { Link } from 'react-router-dom'
import httpdService from '../services/httpdService'
import Loader from '../widgets/Loader'

const Login = () => {
    const [phone, setPhone] = useState()
    const [password, setPassword] = useState()
    const [isLoading, setIsLoading] = useState(false)

    const login = (e) => {
        e.preventDefault()
        var formData = new FormData();
        if (phone === undefined || password === undefined || phone === "" || password === "") {
            return;
        }
        setIsLoading(true)
        formData.append("phone", phone)
        formData.append("password", password)
        formData.append("AUTH_LOGIN", "true");

        httpdService(formData).then((res) => {
            setIsLoading(false)
            if (res.data.code !== 400 && res.data.code !== 403 && res.data.id !== null && res.data.id !== undefined && res.data.id !== "") {
                localStorage.setItem('user', JSON.stringify(res.data))
                var cookie = new Cookies();
                cookie.set("uid", res.data.id, { path: '/' });
                window.location.href = "/";
            } else {
                alert("Please check credentials")
            }
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
                    <center><h2>LOGIN</h2></center>
                    <p>Enter phone no.</p>
                    <input type="tel" name="" id=""
                        className='form-control'
                        placeholder='Enter phone no.'
                        onChange={(e) => { validatePhone(e.target.value); setPhone(e.target.value) }}
                        autoComplete='off'
                        autoSave='off'
                    /><br />
                    <p>Enter password</p>
                    <input type="password" name="" id=""
                        className='form-control'
                        placeholder='Enter password'
                        onChange={(e) => { setPassword(e.target.value) }}
                        autoComplete='off'
                        autoSave='off'
                    />
                    <Link className='text-primary float-right float-end' to="/forgot-password">Forgot Password?</Link>
                    <br />
                    <center><button className='btn-sm btn-primary w-50' onClick={login}>LOGIN</button></center>
                </div>

            </div>

        </>
    )
}

export default Login
