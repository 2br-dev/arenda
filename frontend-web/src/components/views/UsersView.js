import React from 'react'
import { NavLink } from "react-router-dom"

import Updater from './../Updater'

import './../../assets/css/Users.css'


const UsersView = props => {    
    return (
        <div className="content">
            <UserMain addSubmit={props.addSubmit} addError={props.addError} users={props.users}/>
            {props.update === 1 
                ? <Updater update="User" />
                : null
            }
        </div>
    )
}


export default UsersView


const UserMain = props => {
    return (
        <div className="content__body __center">
            <Add 
                addSubmit={props.addSubmit} 
                addError={props.addError}
                />
            <List users={props.users}/>
        </div>
    )
}


const Add = (props) => {
    
    const addErrorClass = addError(props.addError)

    return (
        <section className="add">
            <p className="add__title">Добавить нового пользователя</p>
            <form className="add__box" id="add" onSubmit={props.addSubmit}>
                <Input id="addName" placeholder="Имя пользователя"/>
                <Submit id="addSubmit" text="Добавить пользователя"/>
            </form>
            <p className={addErrorClass}>{props.addError}</p>
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


const Input = props => {
    return (
        <div className="add__row">
            <input type="text" id={props.id} placeholder={props.placeholder} className="add__input"/>
        </div>
    )
}

const Submit = props => {
    return (
        <div className="add__row-submit">
            <button type="submit" id={props.id} className="add__submit">{props.text}</button>
        </div>
    )
}

function addError(value) {
    let temp = 'add__error '
    if(value.length !== 0) {
        temp += 'add__error-visible'
    }
    return temp
}