import { Route, Routes } from 'react-router-dom'
import P404 from './common/P404'
import Cookies from 'universal-cookie'
import { useEffect, useState } from 'react'
import Login from './Auth/Login'
import Pages from './Pages'
import P2 from './widgets/PageWrapper'

function App() {
  const [uid, setUid] = useState(null) 
  useEffect(() => {
    var cookie = new Cookies()
    setUid(cookie.get('uid')) 
  })

  return (
    <>

      <Routes>
        {!uid || uid == undefined || uid == "" ?
          <>
            <Route path="*" element={<Login />} />
          </> :
          <>
            {Pages.map((page, index) => <>
              <Route path={page.path} element={<P2 elm={page.element} />} />
              <Route path={"*"} element={<P2 elm={<P404 />} />} />
            </>)}
          </>}
      </Routes>
    </>
  );
}

export default App;
