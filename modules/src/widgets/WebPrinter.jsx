import React, { useEffect, useState } from 'react'
import { APP_CONFIG } from '../config'
import axios from 'axios'


const WebPrinter = ({ url: url, print: printComplete }) => {
    return (
        <div className='web-printer'>
            <iframe id='iframe' src={url} frameborder="0" style={{ width: '100%', height: '95%' }}></iframe>
            <center><button className='btn -smbtn-danger btn-sm' onClick={() => { printComplete(false) }}>CLOSE</button></center>
        </div>
    )
}

export default WebPrinter
