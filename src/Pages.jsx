import Login from "./Auth/Login"
import Logout from "./Auth/Logout"
import HomeScreen from "./views/Home/HomeScreen"

const Pages = [
    { path: "/", name: "Home", element: <HomeScreen /> },
    { path: "/login", name: "Home", element: <Login /> },
    { path: "/logout", name: "Home", element: <Logout /> },
]
export default Pages