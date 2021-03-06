import React from 'react'
import { connect } from 'react-redux'

import { getContracts, getRegistry } from './../Update'

import ContractsView from './views/ContractsView'

import { BACKEND } from './../path.js'


class Contracts extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            addError: '',
            complete: false
        }
    }


    addSubmit(e) {
        e.preventDefault()
        
        let errors = []
        let isValid = true
        
        const userId        = document.getElementById('addSelect').value
        const roomId        = document.getElementById('addRoom').value
        const name          = document.getElementById('addName').value.trim()
        const price         = parseFloat(document.getElementById('addPrice').value.trim())
        const discount      = parseFloat(document.getElementById('addDiscount').value.trim())
        const payment_zone  = Number(document.getElementById('addPaymentZone').value.trim())
        const discount_zone = Number(document.getElementById('addDiscountZone').value.trim())
        
        let dateTo   = document.getElementById('addDateTo').value.trim().split('-')
        dateTo = `${dateTo[2]}-${dateTo[1]}-${dateTo[0]}`
        
        let dateFrom = document.getElementById('addDateFrom').value.trim().split('-')
        dateFrom = `${dateFrom[2]}-${dateFrom[1]}-${dateFrom[0]}`
        
        
        if(userId === '') {
            errors.push('Не указан пользователь')
            isValid = false
        }
        if(roomId === '') {
            errors.push('Не указано помещение')
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
        if(isNaN(payment_zone)) {
            errors.push('Не корректный платежный период')
            isValid = false
        }
        if(isNaN(discount_zone)) {
            errors.push('Не корректный дисконтный период')
            isValid = false
        }       
        
        this.setState({addError: errors.join(' | ')})
        
        const Data = new FormData()
        Data.append('user_id', userId)
        Data.append('room_id', roomId)
        Data.append('name', name)
        Data.append('price', price)
        Data.append('discount', discount)
        Data.append('discount_payment_zone', discount_zone)
        Data.append('payment_zone', payment_zone)
        Data.append('date_opening', dateTo)
        Data.append('date_closure', dateFrom)
        
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
                        this.complete()
                        this.clear()
                        this.update()
                    } else {
                        this.setState({addError: 'Ошибка при занесении в БД'})
                    }                    
                })   
        }
    }


    clear() {
        document.getElementById('addName').value = ''
        document.getElementById('addPrice').value = ''
        document.getElementById('addDiscount').value = ''
        document.getElementById('addPaymentZone').value = ''
        document.getElementById('addDiscountZone').value = ''
    }
        
    complete() {
        this.setState({complete: true})
        const func = () => {
            this.setState({complete: false})
        }
        setTimeout(func.bind(this), 1500)
    }


    update() {
        getContracts().then(resolve => {
            this.props.onUpdateContracts(resolve)
        })
        getRegistry().then(resolve => {
			this.props.onUpdateRegistry(resolve)
		})
    }

        
    render() {
        return (
            <ContractsView
                users={this.props.users}
                rooms={this.props.rooms}
                contracts={this.props.contracts}
                addSubmit={this.addSubmit.bind(this)}
                addError={this.state.addError}
                complete={this.state.complete}
            />
        )
    }
}


const mapStateToProps = state => ({
    users: state.dashboard.users,
    rooms: state.dashboard.rooms,
    contracts: state.dashboard.contracts
})

const mapDispatchToProps = dispatch => ({
    onUpdateContracts: contracts => {
        dispatch({ type: 'UPDATE_CONTRACTS', payload: contracts })
    },
    onUpdateRegistry: registry => {
		dispatch({ type: 'UPDATE_REGISTRY', payload: registry })
    }
})

export default connect(mapStateToProps, mapDispatchToProps)(Contracts)
