import React from 'react'
import { connect } from 'react-redux'

import UsersView from './views/UsersView'

import { BACKEND } from './../path.js'


class Users extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            addError: '',
            users: []
        }
        this.getUsers()
        
    }


    addSubmit(e) {
        e.preventDefault()
        
        const name = document.getElementById('addName').value

        let flag = true
        let errors = []
        if(name.length === 0) {
            flag = false;
            errors.push('Не указано имя')
        }
        this.setState({addError: errors.join(' | ')})
        
        const Data = new FormData();
        Data.append('name', name)

        if(flag) {
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
                        this.getUsers()
                    } else {
                        this.setState({addError: 'Пользователь с таким именем уже существует'})
                    }
                })   
        }      
    }
        
        
    getUsers() {
        
        const URL = `${BACKEND}getUser`
        const OPTIONS = {
            mode: 'cors',
            method: 'POST'
        }
        
        fetch(URL, OPTIONS)
            .then(response => response.json())
            .then(response => {
                    this.props.onUpdateUsers(response)
                    this.setState({users: response})
                })
    }


    render() {
        return (
            <UsersView
                addSubmit={this.addSubmit.bind(this)}
                addError={this.state.addError}
                users={this.state.users}
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
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Users)
