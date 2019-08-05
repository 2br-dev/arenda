import React from 'react'
import { connect } from 'react-redux'

import { getUsers, getRegistry } from './../Update'

import UsersView from './views/UsersView'

import { BACKEND } from './../path.js'


class Users extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            addError: ''
        }
    }


    addSubmit(e) {
        this.setState({update: 0})
        e.preventDefault()
        
        const name     = document.getElementById('addName').value
        const city     = document.getElementById('addCity').value
        const address  = document.getElementById('addAddress').value
        const phone    = document.getElementById('addPhone').value
        const email    = document.getElementById('addEmail').value
        const password = document.getElementById('addPassword').value

        let isValid = true
        let errors = []
        if(name.length === 0) {
            isValid = false;
            errors.push('Не указано имя')
        }
        if(city.length === 0) {
            isValid = false;
            errors.push('Не указан город')
        }
        if(address.length === 0) {
            isValid = false;
            errors.push('Не указан адрес')
        }
        if(phone.length === 0) {
            isValid = false;
            errors.push('Не указан телефон')
        }
        if(email.length === 0) {
            isValid = false;
            errors.push('Не указан e-mail')
        }
        if(password.length === 0) {
            isValid = false;
            errors.push('Не указан пароль')
        }
        
        this.setState({addError: errors.join(' | ')})
        
        const Data = new FormData();
        Data.append('name', name)
        Data.append('city', city)
        Data.append('address', address)
        Data.append('phone', phone)
        Data.append('email', email)
        Data.append('password', password)

        if(isValid) {           
            const URL = `${BACKEND}addUser`
            const OPTIONS = {
                mode: 'cors',
                method: 'POST',
                body: Data
            }

            fetch(URL, OPTIONS)
                .then(response => response.json())
                .then(response => {
                    if(response === 1) {
                        this.setState({addError: ''})
                        this.update()
                    } else {
                        this.setState({addError: 'Пользователь с таким email уже существует'})
                    }
                })   
        }      
    }


    update() {
        getUsers().then(resolve => {
            this.props.onUpdateUsers(resolve)
        })
        getRegistry().then(resolve => {
			this.props.onUpdateRegistry(resolve)
		})     
    }
        

    render() {
        return (
            <UsersView
                addSubmit={this.addSubmit.bind(this)}
                addError={this.state.addError}
                users={this.props.users}
            />
        )
    }
}


const mapStateToProps = state => ({
    users: state.dashboard.users
})

const mapDispatchToProps = dispatch => ({
    onUpdateUsers: users => {
        dispatch({ type: 'UPDATE_USERS', payload: users })
    },
    onUpdateRegistry: registry => {
		dispatch({ type: 'UPDATE_REGISTRY', payload: registry })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Users)
