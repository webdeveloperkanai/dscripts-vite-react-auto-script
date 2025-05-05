const adminMenu = [
    {
        "name": "Dashboard",
        "path": "/",
        "icon": 'fa-house',
        "children": []
    },
    {
        "name": "Files",
        "path": "#",
        "icon": 'fa-file-zipper',
        "children": [
            {
                "name": "App Settings",
                "path": "#"
            },
            {
                "name": "Help & Support",
                "path": "#"
            },
            {
                "name": "Download Backup",
                "path": "#"
            },
            {
                "name": "Logout Current Profile",
                "path": "/logout"
            }
        ]
    },

]

export default adminMenu
