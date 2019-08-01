import React from 'react'
import { NavLink } from "react-router-dom";

import './../../assets/css/Header.css'


const HeaderView = props => {
    return (
        <header className="header">
            <div className="header__body __center">
                <nav className="header__nav">
                    <NavLink to="/" className="header__nav_link">Реестр</NavLink>
                    <NavLink to="/users" className="header__nav_link">Пользователи</NavLink>
                    <NavLink to="/contracts" className="header__nav_link">Договоры</NavLink>
                    <NavLink to="/invoices" className="header__nav_link">Счета</NavLink>
                    <NavLink to="/payments" className="header__nav_link">Платежи</NavLink>
                </nav>
                <a className="header__btn">Выход</a>
            </div>
        </header>
        
    )
}


export default HeaderView