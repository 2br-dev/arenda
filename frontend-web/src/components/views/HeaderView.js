import React from 'react'
import { Link } from "react-router-dom";

import './../../assets/css/Header.css'


const HeaderView = props => {
    return (
        <header className="header">
            <div className="header__body __center">
                <Link to="/" className="header__logo">Лого</Link>
                <a className="header__btn">Выход</a>
            </div>
        </header>
        
    )
}


export default HeaderView