import React from 'react'
import { NavLink } from "react-router-dom"


import './../../assets/css/Users.css'


const UsersView = props => {    
    return (
        <div className="content">
            <UserMain addSubmit={props.addSubmit} addError={props.addError} users={props.users}/>
        </div>
    )
}


export default UsersView


const UserMain = props => {
    return (
        <div className="content__body __center">
            <Add 
                submit={props.addSubmit} 
                error={props.addError}
                />
            <List users={props.users}/>
        </div>
    )
}


const Add = ({error, submit}) => {
    
    const addErrorClass = addError(error)

    return (
        <section className="add">
            <p className="add__title">Добавить нового пользователя</p>
            <form className="add__box" id="add" onSubmit={submit}>
                <div className="add__box">
                    <input id="addName" type="text" className="add__input" placeholder="Имя пользователя*"/>
                </div>
                <div className="add__box">
                    <input id="addCity" type="text" className="add__input" placeholder="Город*"/>
                </div>
                <div className="add__box">
                    <input id="addAddress" type="text" className="add__input" placeholder="Адрес*"/>
                </div>
                <div className="add__box">
                    <input id="addPhone" type="text" className="add__input" placeholder="Телефон*"/>
                </div>
                <div className="add__box">
                    <input id="addEmail" type="text" className="add__input" placeholder="E-mail*"/>
                </div>
                <div className="add__box">
                    <input id="addPassword" type="text" className="add__input" placeholder="Пароль*"/>
                </div>

                <div className="add__box-submit">
                    <button type="submit" className="add__submit">Добавить пользователя</button>
                </div>
                <p className={addErrorClass}>{error}</p>
            </form>
        </section>
    )
}

const List = (props) => {
    return(
        <section className="list">
            <p className="list__title">Список пользователей</p>
            <div className="list__box">
                {props.users.map(user =>
                    <NavLink key={`user_${user.id}`} to={`/users/${user.id}`} user-id={user.id} className="list__link">{user.name}</NavLink>  
                )}
            </div>
        </section>
    )
}


function addError(value) {
    let temp = 'add__error '
    if(value!== '') {
        temp += 'add__error-visible'
    }
    return temp
}