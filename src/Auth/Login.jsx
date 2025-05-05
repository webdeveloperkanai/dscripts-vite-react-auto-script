import React, { useState } from 'react'
import { APP_CONFIG, validatePhone } from '../config'
import axios from 'axios'
import Cookies from 'universal-cookie'
import { Link } from 'react-router-dom' 

const Login = () => {
    const [phone, setPhone] = useState()
    const [password, setPassword] = useState()

    const login = (e) => {
        e.preventDefault()
        var formData = new FormData();
        formData.append("phone", phone)
        formData.append("password", password)
        formData.append("token", APP_CONFIG.API_TOKEN)
        formData.append("timestmp", Date.now());
        formData.append("AUTH_LOGIN", "true");

        axios.post(APP_CONFIG.API, formData).then((res) => {
            if (res.data.code !== '400') {
                localStorage.setItem('user', JSON.stringify(res.data))
                var cookie = new Cookies();
                cookie.set("uid", res.data.id, { path: '/' });
                window.location.href = "/";
            } else {
                alert("Please check credentials")
            }
        })
    }

    return (
        <>
            <div className="row col-md-12 p-5   login-div">
                <div className="col-md-4"></div>
                <div className='bg-light col-md-4 p-5 shadow-sm rounded form'>
                    <center><h2>LOGIN</h2></center>
                    <p>Enter phone no.</p>
                    <input type="tel" name="" id=""
                        className='form-control'
                        placeholder='Enter phone no.'
                        onChange={(e) => { validatePhone(e.target.value); setPhone(e.target.value) }}
                    /><br />
                    <p>Enter password</p>
                    <input type="password" name="" id=""
                        className='form-control'
                        placeholder='Enter password'
                        onChange={(e) => { setPassword(e.target.value) }}
                    />
                    <Link className='text-primary float-right float-end'>Forgot Password?</Link>
                    <br />
                    <center><button className='btn btn-primary w-50' onClick={login}>LOGIN</button></center>
                </div>

            </div>
            
        </>
    )
}

export default Login
