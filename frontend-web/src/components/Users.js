import React from 'react'
import { connect } from 'react-redux'

import { getUsers } from './../Update'

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
                        this.update()
                    } else {
                        this.setState({addError: 'Пользователь с таким именем уже существует'})
                    }
                })   
        }      
    }


    update() {
        getUsers().then(resolve => {
            this.props.onUpdateUsers(resolve)
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
})

export default connect(mapStateToProps, mapDispatchToProps)(Users)
