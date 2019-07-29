import React from 'react'
import { Link } from "react-router-dom";

import './../../assets/css/Header.css'


const HeaderView = props => {
    return (
        <header className="header">
            <div className="header__body __center">
                <Link to="/" className="header__logo">На главную</Link>
            </div>
        </header>
        
    )
}


export default HeaderView