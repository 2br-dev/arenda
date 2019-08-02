import React from 'react'
import { connect } from 'react-redux'

import { getContracts } from './../Update'

import ContractsView from './views/ContractsView'

import { BACKEND } from './../path.js'


class Contracts extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            addError: '',
            update: 0
        }
    }


    addSubmit(e) {
        e.preventDefault()
        
        let errors = []
        let isValid = true
        
        const userId      = document.getElementById('addSelect').value
        const name        = document.getElementById('addName').value.trim()
        const price    = parseFloat(document.getElementById('addPrice').value.trim())
        const discount = parseFloat(document.getElementById('addDiscount').value.trim())
        
        let dateTo   = document.getElementById('addDateTo').value.trim().split('-')
        dateTo = `${dateTo[2]}-${dateTo[1]}-${dateTo[0]}`
        
        let dateFrom = document.getElementById('addDateFrom').value.trim().split('-')
        dateFrom = `${dateFrom[2]}-${dateFrom[1]}-${dateFrom[0]}`
        
        
        if(userId === '') {
            errors.push('Не указан пользователь')
            isValid = false
        }
        if(name === '') {
            errors.push('Не указано наименование')
            isValid = false
        }
        if(dateTo === '') {
            errors.push('Не указана дата От')
            isValid = false
        }
        if(dateFrom === '') {
            errors.push('Не указана дата До')
            isValid = false
        }
        if(isNaN(price)) {
            errors.push('Не корректная стоимость за расчетный период')
            isValid = false
        }
        if(isNaN(discount)) {
            errors.push('Не корректный процент дисконта')
            isValid = false
        }       
        
        if(errors.length > 0) {
            this.setState({addError: errors.join(' | ')})
        } else {
            this.setState({addError: ''})
        }
        
        const Data = new FormData()
        Data.append('user_id', userId)
        Data.append('name', name)
        Data.append('date_to', dateTo)
        Data.append('date_from', dateFrom)
        Data.append('price', price)
        Data.append('discount', discount)
        
        if(isValid) {
            const URL = `${BACKEND}addContract`
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
                        this.setState({addError: 'Договор с таким наименованием уже существует у данного пользователя'})
                    }
                })   
        }
    }


    update() {
        getContracts().then(resolve => {
            this.props.onUpdateContracts(resolve)
        })
    }

        
    render() {
        return (
            <ContractsView
                users={this.props.users}
                contracts={this.props.contracts}
                addSubmit={this.addSubmit.bind(this)}
                addError={this.state.addError}
            />
        )
    }
}


const mapStateToProps = state => ({
    users: state.dashboard.users,
    contracts: state.dashboard.contracts
})

const mapDispatchToProps = dispatch => ({
    onUpdateContracts: contracts => {
        dispatch({ type: 'UPDATE_CONTRACTS', payload: contracts })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Contracts)
